<?php
/**
* @see Model基类 本程序主要用来取得在Model中使用的一些基本方法和基本变量
* @category   Model
* @package    Base
* @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
class Model{
    protected $db; //数据库对象
    public $name; //模型名称
    public $table; //模型对应的主表名称
    public $pageSize = 12; //默认分页长度
    public $mem; //内存缓存对象
    public $querySql; //刚刚运行的SQL语句
    private $alias; /*主表的别名*/
    /**
     * 以下变量涉及到数据库读写分开和负载均衡
     */
    private $optType; //判断读写,临时变量
    private $masters; //主数据库连接统计池
    private $slaves;  //丛数据库连接统计池
    private $pools;   //数据库连接池
    private $dbKey;    //数据库连接当前选项：临时变量
    
    /**
     * @see 构造函数
     * @param string $name
     */
    public function __construct($name = null) { //$db = null, 
        $this->name = $name;
//        $this->db = $db;
        if(__USE_MEMCACHE__){$this->mem = $this->X('Mem');}
        $this->setTable();
    }
    /**
     * @see 设置Model主表的别名
     * @param string $alias 主表的别名
     */
    public function setAlias($alias){
        $this->alias = $alias;
    }

    /**
     * @see 获取当前Model对应的表
     */
    protected function setTable(){
        $this->table = __DB_PREFIX__.strtolower($this->name);
    }
    
    /**
     * @see 创建数据库
     * @param string $dbname 要出创建的数据库名称
     * @return boolean 创建结果
     */
    public function createDatabase($dbname){
        $sql = 'CREATE DATABASE `'.$dbname.'`;';
        return $this->execSql($sql);
    }
    
    /**
     * @see 创建表
     * @param string $table
     * @param array $fields
     */
    public function createTable($table, $fields = array()){
        
    }
    
    /**
     * @see 显示当前数据库的所有表
     * @return array 数据库下的所有表名的列表
     */
    public function getTables(){
        $sql = 'SHOW tables FROM `'.__DB_NAME__.'`';
        $data = $this->query($sql);
        foreach((array)$data as $key => $value){
            $tables[] = str_replace(__DB_PREFIX__, '', $value['Tables_in_'.__DB_NAME__]);
        }
        return $tables;
    }
    
    /**
     * @see 根据给定表获取表的全部字段属性
     * @param Description string $table 表名
     * @return array 数据库字段属性
     */
    public function getTableFields($table = null){
        if(empty($table)){
            $table = $this->table;
        }else{
            $table = __DB_PREFIX__.$table;
        }
        $sql = 'SHOW FULL FIELDS FROM `'.$table.'`';
        $data = $this->query($sql);
        return $data;
    }

    /**
     * @see 设置视图
     * @param string $viewSql 设置视图用的SQL语句
     */
    protected function createView($viewSql = null){
        $sql = 'SHOW TABLES WHERE Tables_in_'.__DB_NAME__.'=\''.$this->table.'\'';
        $data = $this->query($sql);
        if(count($data) <= 0){
            if(empty($viewSql)){die('the_view_not_exists');}
            $this->execSql($viewSql);
            $this->deleteHTML();
        }
    }
    
    /**
     * @see 设置分页长度
     * @param int $pageSize 分页长度
     */
    public function setPageSize($pageSize = 10){
        $this->pageSize = $pageSize;
    }
    
    /**
     * 
     * 
     */
    
    /**
     * @see  连接数据库
     * @return object 返回数据库连接之后数据库实例
     */
    private function getdb(){
        include_once(__LIB__.'/adodb/adodb.inc.php');
        $this->db = ADONewConnection(__DB_TYPE__);
        if(!__USE_DB_POOL__){
            $this->db->createdatabase = true;
            if(__DB_PCONNECT__){
                $this->db->PConnect(__DB_HOST__, __DB_USER__, __DB_PWD__, __DB_NAME__);
            }else{
                $this->db->Connect(__DB_HOST__, __DB_USER__, __DB_PWD__, __DB_NAME__);
            }
        }else{
            //判断哪个数据库正在被使用
            $dbConfig = $this->getDBConfig();
            if(__DB_PCONNECT__){
                $this->db->PConnect($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['dbname']);
            }else{
                $this->db->Connect($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['dbname']);
            }
        }
        $this->db->Execute("set names utf8");
    }
    
    /**
     * @see 获取数据库连接
     * @return 获取数据库配置
     */
    private function getDBConfig(){
        if($this->optType == 'master'){
            $min = min($this->masters);
            $this->dbKey = array_search($min, $this->masters);
            $dbConfig = $this->pools['master'][$this->dbKey];
            $this->masters[$this->dbKey] += 1;
        }elseif($this->optType == 'slave'){
            $min = min($this->slaves);
            $this->dbKey = array_search($min, $this->slaves);
            $dbConfig = $this->pools['slave'][$this->dbKey];
            $this->slaves[$this->dbKey] += 1;
        }
        $d = array('master' => $this->masters, 'slave' => $this->slaves);
        $this->savePool($d, 'poolConfig');
        return $dbConfig;
    }
    
    /**
     * @see 更新数据库读取链接统计
     */
    private function freshDbLink(){
        if($this->optType == 'master'){
            $this->masters[$this->dbKey] > 0 ? $this->masters[$this->dbKey] -= 1 : $this->masters[$this->dbKey] = 0;
        }elseif($this->optType == 'slave'){
            $this->slaves[$this->dbKey] > 0 ? $this->slaves[$this->dbKey] -= 1 :  $this->slaves[$this->dbKey] = 0;
        }
        $d = array('master' => $this->masters, 'slave' => $this->slaves);
        $this->savePool($d, 'poolConfig');
    }
    /**
     * @see 获取当前数据库连接数的全站状况统计
     */
    private function _InitializationDB(){
        $pools = $this->getPool('poolConfig'); 
        $this->pools = unserialize(__POOLS__);
        $master = (array)$pools['master'];
        if(array_sum($master) <= 0 || empty($master)){
            foreach((array)$this->pools['master'] as $k => $v){
                $this->masters[$k] = 0;
            }
        }else{
            $this->masters = $pools['master'];
        }
        $slave = (array)$pools['slave'];
        if(array_sum($slave) <= 0 || empty($slave)){
            foreach((array)$this->pools['slave'] as $k => $v){
                $this->slaves[$k] = 0;
            }
        }else{
            $this->slaves = $pools['slave'];
        }
    }
    
    /**
     * @see 保存全站数据库连接状况
     * @param array $data 链接数据
     * @param string $key 数据库名
     * @return boolean 保存结果
     */
    private function savePool($data, $key){
        $file = './Data/pool';
        if(!file_exists($file)){
            @mkdir($file);
        }
        $content = serialize($data);
        $file .= '/'.$key.'.db';
        return @file_put_contents($file, $content);
    }
    
    /**
     * @see 读取全在数据库连接状况
     * @param string $key 链接的数据库名称
     * @return string 链接数据
     */
    private function getPool($key){
        $file = './Data/pool/'.$key.'.db';
        if(!file_exists($file)){
            return array();
        }
        $data = @file_get_contents($file);
        $data = unserialize($data);
        return $data;
    }

    /**
     * @see 运行Sql并直接返回数据，读函数
     * @param string $sql Sql语句
     * @return array 返回结果集
     */
    protected function query($sql, $data = false){
        $this->querySql = $sql;
        $this->optType = 'slave';
        if(__USE_DB_POOL__){$this->_InitializationDB();}
        $this->getdb();
        $result = $this->db->Execute($sql, $data);
        if($result){
            $data = array();
            while($arr = $result->FetchRow()){$data[] = $this->arrayUnique($arr);}
            if(__USE_DB_POOL__){$this->freshDbLink();}
            return $data;
        }else{
            throw new InkException(L('sql_syntax_error').'：'.$sql, 110020001);
        }
    }

    /**
     * @see 运行Sql并返回是否运行成功，写函数
     * @return boolean 返回SQL运行结果
     */
    protected function execSql($sql, $data = false){
        $this->querySql = $sql;
        $this->optType = 'master';
        if(__USE_DB_POOL__){$this->_InitializationDB();}
        $this->getdb();
        try{
        $obj = is_object($this->db->Execute($sql, $data));
        }catch(Exception $e){
            throw new InkException(L('sql_syntax_error').'：'.$sql, 110020002);
        }
        if(__USE_DB_POOL__){$this->freshDbLink();}
        if($obj){return true;}else{return false;}
    }

    /**
     * @see 对查询所得的数据集进行处理，去掉所有数字键项
     * @param array $data 要处理的查询结果
     * @return array 处理之后的结果
     */
    private function arrayUnique($data){
        foreach((array)$data as $key => $value){
            if(is_numeric($key)){unset($data[$key]);}
        }
        return $data;
    }

    /**
     * @see 根据条件取得数据表中有符合条件的数据的数量
     * @param array $map 查询条件
     * @return int 查询结果数量值
     */
    public function getCount($maps = null, $mapData = false){
        $wheres = $this->getWhere($maps, $mapData);
        $where = $wheres[0];
        $sql = 'SELECT count(1) as `count` FROM '.$this->table.' WHERE 1 '.$where;
        $data = $this->query($sql, $wheres[1]);
        return $data[0]['count'];
    }
    
    /**
     * @see 计算某个字段的总计
     * @param string $field 要计算的字段名
     * @param string 或 array $map 计算条件
     * @return int 返回结果总计
     */
    public function getSum($field = '1', $maps = null, $mapData = false){
        $wheres = $this->getWhere($maps, $mapData);
        $where = $wheres[0];
        $sql = 'SELECT SUM('.$field.') as `sum` FROM '.$this->table.' WHERE 1 '.$where;
        $data = $this->query($sql, $wheres[1]);
        return $data[0]['sum'];
    }
    
    /**
     * @see 根据给定条件获取一条数据。
     * @param string 或 array $fields：要查询的字段名
     * @param string 或 array $maps：查询条件
     * @return array
     */
    public function getOneMap($fields, $maps, $orders = null, $mapData = false){
        $wheres = $this->getWhere($maps, $mapData);
        $where = $wheres[0];
        $fields = $this->getFields($fields);
        $key = $this->getPK();
        if(empty($orders)){
            $order = 'ORDER BY `'.$key.'` ASC';
        }else{
            $order = 'ORDER BY '.$orders;
        }
        $sql = 'SELECT '.$fields.' FROM '.$this->table.' WHERE 1 '.$where.' '.$order.' LIMIT 0,1';
        $data = $this->query($sql, $wheres[1]);
        return $data[0];
    }

    /**
     * @see 取得当前数据表中的单挑数据，根据给定的id
     * @param array $field 要显示的字段
     * @param string $id 查询的id值
     * @return array 返回查询得到的数据
     */
    public function getOne($fields, $id, $pk = null){
        if($pk == null){$pk = $this->getPK();}
        $d = $this->getOneFromDB($fields, $pk, $id);
        if(!empty($d)){return $d[0];}else{return ;}
    }

    /**
     * @see 形如key-value数据库的单条数据进行json解析
     * @param string $key 要查询的key
     * @return array 返回的结果
     */
    public function getOneValue($key){
        $field = array('_value');
        $data = $this->getOne($field, $key);
        $value = $data['_value'];
        $d = (array)json_decode($value);
        return $d;
    }

    /**
     * @see 从数据库中获得单条数据
     * @param array $field 要查询的字段
     * @param string $pk 主键
     * @param string $id 主键值
     * @return array 取得的数据
     */
    private function getOneFromDB($fields, $pk, $id){
        $where = 'WHERE `'.$pk.'` = ?';
        $fields = $this->getFields($fields);
        $sql = 'SELECT '.$fields.' FROM '.$this->table.' '.$where;
        $d = $this->query($sql, array($id));
        return $d;
    }
    
    /**
     * @see 根据给定数组获取插入多条数据的SQL
     * @param type $data 要插入表的数据
     * @return string insert 语句
     */
    protected function getInsertMoreSql($data){
        if(empty($data)){return false;}
        $keys = array_keys($data[0]);
        $sql = 'INSERT INTO '.$this->table.'(`'.implode('`,`', $keys).'`) VALUES';
        $insertData = array();
        foreach((array)$data as $key => $d){
            $values = $this->getInsertValues($d);
            $sqls[] = '('.implode(',',$values).')';
            $insertData = array_merge($insertData, $d);
        }
        $sql .= implode($sqls, ',');
        return array($sql, $insertData);
    }
    
    /**
     * @see 向表中插入2维数组，要求第二维度中的数组key和value必须是相同的才可以使用此方法。
     * @param array $data 要插入表的数据
     * @return boolean 插入结果
     */
    public function insertMore($data){
        $sqls = $this->getInsertMoreSql($data);
        if($this->execSql($sql[0], $sqls[1])){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @see 根据数组形成INSERT Sql语句
     * @param array $data 要插入表的数据
     * @return string sql insert语句
     */
    protected function getInsertSql($data){
        if(empty($data)){return false;}
        $keys = array_keys($data);
        $values = $this->getInsertValues($data);
        $sql = 'INSERT INTO '.$this->table.'(`'.implode('`,`', $keys).'`) VALUES('.implode(',',$values).')';
        return $sql;
    }
    
    public function getInsertValues($data){
        $values = array();
        foreach((array)$data as $value){
            $values[] = '?';
        }
        return $values;
    }



    /**
     * @see 向表中插入数组
     * @param array $data 要插入表的数据
     * @return boolean or int 返回 插入的结果
     */
    public function insert($data){
        //取得fileField名称列表(为了更新文件和该数据条目的关系表)。
        if(isset($data['fileField'])){
            $fields = $data['fileField'];
            unset($data['fileField']);
        }
        
        $sql = $this->getInsertSql($data);
        if($this->execSql($sql, $data)){
            $insertId = $this->InsertId();
            if(isset($fields) && !empty($fields)){
                $this->getFileids($fields, $data, $insertId);
            }
            $this->deleteHTML();
            return $insertId;
        }else{
            return false;
        }
    }
    /**
     * @see 删除数据时更新文件表
     * @param type $ids 要更新的文件表的ID号
     */
    private function updatePics($ids){
        if(!empty($ids)){
            $values = $this->getInsertValues($ids);
            $sql = 'SELECT `picid` FROM '.__DB_PREFIX__.'pic2mdl WHERE `picid` IN ('.implode(',', $values).')';
            $data = $this->query($sql, $ids);
            $nums = array();
            $sels = array();
            foreach((array)$data as $key => $value){
                $sels[] = $value['picid'];
            }
            $str = '';
            foreach((array)$ids as $key => $id){
                if(in_array($id, $sels)){
                    $str .= ' WHEN '.$id.' THEN 2 ';
                }else{
                    $str .= ' WHEN '.$id.' THEN 1 ';
                }
            }
            $sql = 'UPDATE '.__DB_PREFIX__.'pics SET `isused`= CASE `picid` '.$str.' END WHERE `picid` IN ('.implode(',', $values).')';
            $this->execSql($sql, $ids);
        }
    }
    
    /**
     * @see 删除数据时更新图像和数据关系表
     * @param array或string $dataids 要删除的文件ID号
     * @param type $fresh 是否刷新数据
     */
    private function deleteFileids($dataids, $fresh = false){
        $mapData = array(strtolower($this->name));
        if(!is_array($dataids)){
            $dataids = array($dataids); 
        }
        $values = $this->getInsertValues($dataids);
        $where = 'tablename=? AND `dataid` IN('.implode(',', $values).')';
        $mapData = array_merge($mapData,$dataids);
        if($fresh){
            $sql = 'SELECT `picid` FROM '.__DB_PREFIX__.'pic2mdl WHERE '.$where;
            $pics = $this->query($sql, $mapData);
            foreach((array)$pics as $key => $value){
                $ids[] = $value['picid'];
            }
        }
        $sql = 'DELETE FROM '.__DB_PREFIX__.'pic2mdl WHERE '.$where;
        $this->execSql($sql, $mapData);
        $this->updatePics($ids);
    }

    /**
     * @see 插入和修改数据时更新图像和数据关系表
     * @param string 或 array $fields
     * @param array $data 要写入的数据
     * @param int $dataid 要写入的字段的ID号
     */
    private function getFileids($fields, $data, $dataid){
        $this->deleteFileids($dataid);
        $files = array();
        foreach((array)$fields as $key => $value){
            $d = trim($data[$value]);
            if(empty($d)){
                continue;
            }
            $fs = explode(',',$d);
            if(count($fs) > 1){
                $files = array_merge($files, $fs);
            }else{
                $files[] = $fs[0];
            }
        }
        $values = $this->getInsertValues($files);
        $sql = 'SELECT `picid` FROM '.__DB_PREFIX__.'pics WHERE filepath IN ('.implode(',', $values).')';
        $pics = $this->query($sql, $files);
        $str = array();
        $mapsData = array();
        foreach((array)$pics as $value){
            $mapData = array_merge($mapsData, array($value['picid'], strtolower($this->name), $dataid));
            $str[] = '(?,?,?) ';
            $ids[] = $value['picid'];
        }
        $sql = 'INSERT INTO '.__DB_PREFIX__.'pic2mdl (`picid`,`tablename`,`dataid`) VALUES '.implode(',', $str);
        $this->execSql($sql, $mapData);
        $this->updatePics($ids);
    }
    
    /**
     * @see 返回刚刚插入的数据的ID号
     * @return int
     */
    public function InsertId(){
        $status = $this->db->Insert_ID();
        return $status;
    }

    /**
     * @see 根据条件获得符合条件的所有数据的列表
     * @param array $fields 要查询的字段
     * @param array or string $maps 查询条件
     * @param string $order 排序条件
     * @param string $limit 查询条数
     * @return array 查询结果
     */
    public function getAll($fields = '*', $maps = '', $order = null, $limit = null, $mapData = false){
        $field = $this->getFields($fields);
        if(empty($mapData)){
            $mapData = false;
        }
        if(is_array($maps)){
            $wheres = $this->getWhere($maps, $mapData);
            $where = $wheres[0];
            $mapData = $wheres[1];
        }else{
            $where = $maps;
        }
        if(!empty($order)){$order = ' ORDER BY '.$order;}else{$order = '';}
        if(!empty($limit)){$limit = ' LIMIT '.$limit;}else{$limit = '';}
        $sql = 'SELECT '.$field.' FROM '.$this->table.' WHERE 1 '.$where.$order.$limit;
        $d = $this->query($sql, $mapData);
        return $d;
    }

    /**
     * @see 根据数组取得要查询的字段字符串
     * @param array $fields
     * @return string 要查询的字段星形成的字符串
     */
    protected function getFields($fields){
        if(!is_array($fields)){
            if(empty($fields) || $fields == '*'){$str = '*';}else{$str = $fields;}
        }else{
            foreach((array)$fields as $key => $field){$fields[$key] = $this->precessField($field);}
            $str = implode(',',$fields);
        }
        return $str;
    }
    
    /**
     * @see 处理单个字段的显示方式
     * @param string $field
     * @return string 返回字段名处理后的结果
     */
    private function precessField($field){
        $fs = explode(' as ', $field);
        if(count($fs) < 2){$fs = explode(' AS ', $field);}
        if(count($fs) < 2){$fs = explode(' aS ', $field);}
        if(count($fs) < 2){$fs = explode(' As ', $field);}
        $as = '';
        if(count($fs) > 1){
            $as = trim($fs[1]);
            $field = trim($fs[0]);
        }
        $fields = explode('.', $field);
        if(count($keys) > 1){$field = $fields[0].'.`'.$fields[1].'`';}else{$field = '`'.$field.'`';}
        if($as != ''){$field.= ' AS '.$as;}
        return $field;
    }

    /**
     * @see 根据查询条件进行分页查询 - 也就是通用分页方法
     * @param array $fields 要查询的字段
     * @param int $page 当前页码
     * @param array $maps 查询条件
     * @param string $pageType 分页显示方式
     * @param string $order 排序方式
     * @return array 查询结果和分页字符串
     */
    public function getPageList($fields = '*', $page = 1, $maps = '', $pageMode = 'goPage', $order = null, $mapData = false, $pageType = 'Number'){
        $field = $this->getFields($fields);
        if(empty($mapData)){
            $mapData = false;
        }
        if(is_array($maps)){
            $wheres = $this->getWhere($maps, $mapData);
            $where = $wheres[0];
            $mapData = $wheres[1];
        }else{
            $where = $maps;
        }
        $pageData = $this->getPageParams($page, $where, $mapData);
        $pageData['pageMode'] = $pageMode;
        if($page <= $pageData['maxPage']){
            $start = $pageData['start'];
            $limit = ' LIMIT '.$start.','.$this->pageSize;
            if(!empty($order)){$order = ' ORDER BY '.$order.' ';}
            $sql = 'SELECT '.$field.' FROM '.$this->table.' WHERE 1 '.$where.$order.$limit;
            $rows = $this->query($sql, $mapData);
            $pageData['count'] = count($rows);
            if($pageData['maxPage'] > 1){$pageStr = $this->getPageStr($pageType,$pageData);}else{$pageStr = '';}
        }else{
            $rows = null;
            $pageStr = '';
        }
        return array('rows' => $rows, 'pageStr' => $pageStr);
    }

    /**
     * @see 根据传过来的条件分析取得查询Sql语句的Where部分
     * @param array $maps 查询条件
     * @return string where部分
     */
    protected function getWhere($maps, $mapData = false){
        if(!is_array($maps)){
            return $this->getMaps2Stmt($maps, $mapData);
        }else{
            $str = '';
            $data = array();
            foreach((array)$maps as $key => $value){
                if(is_numeric($key)){$key = $this->getPK();}
                $wheres = $this->getWhereStr($key, $value);
                $str .= $wheres[0];
                $data[] = $wheres[1];
            }
        }
        if(empty($data)){$data = false;}
        return array($str, $data);
    }
    
    
    private function getMaps2Stmt($maps, $mapData = false){
        if(empty($maps)){
            return array('', false);
        }else{
            return array($maps, $mapData);//$this->CalOper($maps);
        }
    }
    
//    private function calOper1(){
//        $data = array('<=>', '>=', '<=', '!=', '<>', ':=', '||', '&&' , '>', '<', '=' ,'&' ,'|', '<<', '>>','-', '+', '*', '/', '%', '^', '-', '', '~ ', '!', '(', ')', '`', ',',
//            'FROM_UNIXTIME','SELECT','UPDATE','INSERT','CREATE','DROP','DATEBASE','TABLE','FIELD','GROUP_CONCAT','CONCAT','CAST','BINARY','COLLATE' ,'MOD', 'DIV', 'IN', 'LIKE', 'REGEXP', 
//            'AND', 'OR', 'XOR', 'NOT','CASE', 'WHEN', 'IS','STRCMP','NULL','ABS','ACOS','ASIN','SHOW','ALTER','FROM','WHERE','SET','ATAN','CEILING','CEIL','COS','COT',
//            'CRC32','DEGREES','EXP','FLOOR','FORMAT','LN','LOG','LOG2','LOG10','PI','POW','POWER','RADIANS','RAND','ORDER','BY','GROUP','HAVING','LEFT','JOIN','RIGHT',
//            'INNER','ON','LIMIT','ROUND','SIGN','SQRT','TAN','TRUNCATE','DATE_SUB','CURDATE','INTERVAL','NOW','CURTIME','UTC_DATE','UTC_TIME','UTC_TIMESTAMP','DAY',
//            'CURRENT_TIMESTAMP','CURRENT_TIME','CURRENT_DATE','UNIX_TIMESTAMP','ADDDATE','ADDTIME','CONVERT_TZ','DATE','DATEDIFF','DATE_ADD','DATE_FORMAT','DAYNAME',
//            'DAYOFMONTH','DAYOFWEEK','YEAR','DAYOFYEAR','EXTRACT','FROM_DAYS','GET_FORMAT','HOUR','LAST_DAY','LOCALTIME','MAKEDATE','MAKETIME','MICROSECOND','MINUTE',
//            'MONTH','MONTHNAME','PERIOD_ADD','PERIOD_DIFF','QUARTER','SECOND','SEC_TO_TIME','STR_TO_DATE','SUBDATE','SUBTIME','SYSDATE','TIME','TIMEDIFF','TIMESTAM',
//            'TIMESTAMPADD','TIMESTAMPDIFF','TIME_FORMAT','TIME_TO_SEC','TO_DAYS','WEEKDAY','WEEK','YEAR','YEARWEEK','MID','WEEKOFYEAR','MATCH','AGAINST','INDEX','REPAIR',
//            'QUICK','CONVERT','INTO','VALUES','DECODE','DES_DECRYPT','DES_ENCRYPT','ENCRYPT','MD5','OLD_PASSWORD','PASSWORD','SHA1','SHA','BENCHMARK','CHARSET','COERCIBILITY',
//            'COLLATION','CONNECTION_ID','CURRENT_USER','FOUND_ROWS','ROW_COUNT','COUNT','SUM','SCHEMA','SESSION_USER','SYSTEM_USER','USER','VERSION','DEFAULT','FORMAT',
//            'GET_LOCK','INET_ATON','INET_NTOA','IS_FREE_LOCK','IS_USED_LOCK','MASTER_POS_WAIT','NAME_CONST','RELEASE_LOCK','SLEEP','UUID','AVG','BIT_AND','BIT_OR','BIT_XOR',
//            'MIN','MAX','STD','STDDEV_POP','STDDEV_SAMP','VAR_POP','VAR_SAMP','VARIANCE','INT','CHAR','VARCHAR','TEXT','FLOAT','WITH','ROLLUP','DELETE','DO','HANDLER','DESC',
//            'ASC','LOAD','DATA','INFILE','REPLACE','TRUNCATE','DESCRIBE','USE','START','TRANSACTION','COMMIT','ROLLBACK','SAVEPOINT','SAVEPOINT','TO','LOCK','UNLOCK','RENAME',
//            'GRANT','REVOKE','PURGE','MASTER','LOGS','RESET','EVENTS','STATUS','SLAVE','HOSTS','ENGINE','TYPE','MYISAM','INNODB','MERGE','MEMORY','EXAMPLE','FEDERATED','ARCHIVE'
//            ,'BLACKHOLE','CSV','TRIGGER','VIEW','DECIMAL','NUMERIC','DOUBLE','REAL','FIXED','PRECISION');
//        /**
//         * 1.替换所有关键字为：[关键字]
//         * 2.以逗号分开整个语句
//         * 3.删除空的，所有 []含的不做替换，所有不含的，替换为？，切取出值昨晚
//         * 
//         */
//        
//    }


//    private function CalOper($maps){
//        $data = array('<=>', '>=', '<=', '!=', '<>', '>', '<', '=', 'IN', 'LIKE', 'REGEXP');
//        $str = replaceDoubleSpace($maps);//替换多余空格
//        $str = deleteRightSpace($str, $data);//删除操作符右侧的空格
//        $str = deleteBeforeAndAfterSpace($str, array(',')); //删除，前后的空格
//        //先用AND分开
//        $d = $this->getMapValues($str, $data);
//        return $d;
//    }
    
//    private function getMapValues($str, $data){
//        $cdata = array('AND', 'OR');
//        $strs = explode(' ', $str);
//        $d = array();
//        $ds = array();
//        foreach((array)$strs as $k => $v){
//            $sv = trim(strtoupper($v));
//            if(!in_array($sv, $cdata)){
//                foreach((array)$data as $key => $value){
//                    $ds = $this->precessValue($v, $value);
//                    if(is_array($ds)){$strs[$k] = $ds[0];break;}
//                }
//                $d = !is_array($ds) ? array() :  array_merge($d, $ds[1]);
//            }else{continue;}
//        }
//        
//        
//        return array(implode(' ', $strs), $d);
//    }
    
//    private function precessValue($v, $sy){
//        $d = array();
//        $s = explode($sy, $v);
//        if(count($s) > 1){//含有指定符号的
//            $x = $s[1];
//            $xs = explode('%', $x);
//            if(count($xs) >1){
//                $x = str_replace('\'', '', $x);
//                $x = str_replace('%', '', $x);
//                $d = array_merge($d, array($x));
//                $s[1] = str_replace($x, '?', $s[1]);
//            }else{
//                $xs = explode('(', $x);
//                if(count($xs) > 1){
//                    $y = str_replace('(', '', $x);
//                    $y = str_replace(')', '', $y);
//                    $ys = explode(',', $y);
//                    $d = array_merge($d, $ys);
//                    $values = '('.implode(',', $this->getInsertValues($ys)).')';
//                    $s[1] = str_replace($x, $values, $s[1]);
//                }else{
//                    $d = array_merge($d, array($s[1]));
//                    $s[1] = '?';
//                }
//            }
//        }
//        $str = implode($sy, $s=='?' ? $s : $s.' ');
//        return array($str, $d);
//    }
    
    


    /**
     * @see 条件数组形式
     * @param string $key 字段名
     * @param string $value 字段值
     * @return string 返回WHERE或ON语句
     */    
    private function getWhereStr($key, $value){
        $key = $this->precessField($key);
        if(!is_array($value)){
            $str = ' AND '.$key.'=?';
            $v = $value;
        }else{
            $count = count($value);
            if($count == 2){
                $str = ' AND '.$key.$value[0].'?';
                $v = $value[1];
            }elseif($count == 3){
                $str = ' '.$value[0].' '.$key.$value[1].'?';
                $v = $value[2];
            }else{
                $str = ' AND '.$key.'=?';
                $v = $value[0];
            }
        }
        return array($str, $v);
    }
    

    /**
     * @see 取得分页所需要的参数
     * @param int $page 当前页码
     * @param string $where 查询条件
     * @return array 分页所需要的参数
     */
    private function getPageParams($page, $where, $mapData = false){
        $sql = 'SELECT count(1) as Num FROM '.$this->table.' WHERE 1 '.$where;
        $rows = $this->query($sql, $mapData);
        $num = $rows[0]['Num'];
        $maxPage = ceil($num / $this->pageSize);
        if($page > $maxPage){$page = $maxPage;}
        if($page < 1){$page = 1;}
        $start = ($page-1)* $this->pageSize;
        return array('num' => $num, 'page' => $page, 'maxPage' => $maxPage, 'start' => $start);
    }

    /**
     * @see 分页显示字符串（html）
     * @param string $pageType 分页显示方式
     * @param array $pageData 分页所需数据
     * @return string 分页显示的方式
     */
    private function getPageStr($pageType = 'Normal', $pageData){
        $funcName = 'getPage'.$pageType;
        return $this->X('Page', $pageData)->$funcName();
    }

    /**
     * @see 根据数组取得Update语句
     * @param array $data 数据
     * @return string 返回Update语句
     */
    private function getUpdateSql($data){
        $mapData = array();
        $pk = $this->getPK();
        if(!empty($data[$pk])){
            $where = ' WHERE `'.$pk.'` = ?';
            $pkData = array($data[$pk]);
            unset($data[$pk]);
        }
        if(empty($where)){
            throw new InkException(L('按照条件更新数据库必须带有条件，否则不允许执行'), 110020005);
        }
        $keys = array_keys($data);
        $str = '';
        foreach((array)$keys as $k => $v){
            $str .= '`'.$v.'`=?,';
            $mapData[] = $data[$v];
        }
        $str = substr($str, 0, strlen($str)-1);
        $sql = 'UPDATE '.$this->table.' SET '.$str.$where;
        $mapData = array_merge($mapData, $pkData);
        return array($sql, $mapData);
    }
    
    /**
     * @see 根据数组获取更新多条数据的Update语句
     * @param array $data 要更新的数组
     * @return string 更新用的SQL语句
     */
    private function getUpdateMoreSql($data){
        $pk = $this->getPK();
        $mapData = array();
        $keys = array_keys($data[0]);
        foreach($keys as $key => $value){
            if($value == $pk){
                unset($keys[$key]);
            }
        }
        $pks = array();
        foreach($data as $key => $value){
            $pks[] = $value[$pk];
        }
        $sql = 'UPDATE `'.$this->table.'` SET ';
        $sqls = array();
        $str = '';
        foreach($keys as $key => $value){
            $str = '`?`= CASE `?` ';
            $mapData = array_merge($mapData, array($value, $pk));
            foreach($data as $k => $v){
                $str .= 'WHEN ? THEN ? ';
                $mapData = array_merge($mapData, array($v[$pk], $v[$value]));
            }
            $str .= 'END ';
            $sqls[] = $str;
        }
        $sql .= implode(',', $sqls);
        $values = $this->getInsertValues($pks);
        $sql .= ' WHERE `'.$pk.'` IN('.implode(',', $values).')';
        $mapData = array($mapData, $pks);
        return array($sql, $mapData);
    }


    /**
     * @see 一次更新多条数据
     * @param array $data 数组
     * @return boolean or object 返回是否成功更新
     */
    public function updateMore($data){
        $sqls = $this->getUpdateMoreSql($data);
        return $this->execSql($sqls[0], $sqls[1]);
    }

    /**
     * @see 根据数组更新数据库的某条数据
     * @param array $data 数组
     * @return boolean or object 返回是否成功更新
     */
    public function update($data){
        //取得fileField名称列表(为了更新文件和该数据条目的关系表)。
        if(isset($data['fileField'])){
            $fields = $data['fileField'];
            unset($data['fileField']);
        }
        $sqls = $this->getUpdateSql($data);
        if($this->execSql($sqls[0], $sqls[1])){
            if(isset($fields) && !empty($fields)){
                $this->getFileids($fields, $data, $data[$this->getPK()]);
            }
            $this->deleteHTML();
            return true;
        }
        return false;
    }
    
    /**
     * @see 根据给定条件更新数据
     * @param array $data 要更新的数据
     * @param array $maps 执行条件
     * @return boolean 执行是否成功
     */
    public function updateMaps($data, $maps, $mapData = false){
        if(empty($maps)){
            throw new InkException(L('按照条件更新数据库必须带有条件，否则不允许执行'), 110020004);
        }
        //取得fileField名称列表(为了更新文件和该数据条目的关系表)。
        if(isset($data['fileField'])){
            $fields = $data['fileField'];
            unset($data['fileField']);
        }
        if(empty($mapData)){
            $mapData = false;
        }
        $wheres = $this->getWhere($maps, $mapData);
        $where = $wheres[0];
        $mapData = $wheres[1];
        $pk = $this->getPK();
        $sql = 'SELECT `'.$pk.'` FROM '.$this->table.' WHERE 1 '.$where;
        $ids = $this->query($sql, $mapData);
        foreach((array)$data as $k => $v){$str .= '`'.$k.'`=\''.$v.'\',';}
        $str = substr($str, 0, strlen($str)-1);
        $sql = 'UPDATE '.$this->table.' SET '.$str.' WHERE 1 '.$where;
        if($this->execSql($sql, $mapData)){
            if(!empty($ids)){
                foreach((array) $ids as $key => $id){
                    if(isset($fields) && !empty($fields)){
                        $this->getFileids($fields, $data, $id[$pk]);
                    }
                }
            }
            $this->deleteHTML();
            return true;
        }
        return false;
    }
    
    /**
     * @see 更新点击次数
     * @param string $field 要更新的字段名
     * @param int $num 要增加的偏移量
     * @return boolean 执行是否成功
     */
    public function updateStat($field, $id, $pk = null, $num=1){
        if(empty($pk)){$pk = $this->getPK();}
        $sql = 'UPDATE '.$this->table.' SET `?`=`?`+? WHERE `?`=?';
        $data = array($field, $id, $num, $pk, $id);
        return $this->execSql($sql, $data);
    }

    /**
     * @see 根据给的的主键值删除符合条件的数据
     * @param array $ids 主键值列表
     * @return boolean or object 返回成功与否
     */
    public function delete($ids, $pk = null){
        if(empty($pk)){
            $pk = $this->getPK();
        }
        $values = $this->getInsertValues($ids);
        $sql = 'DELETE FROM '.$this->table.' WHERE `'.$pk.'` in('.implode(',',$values).')';
        if($this->execSql($sql, $ids)){
            $this->deleteFileids($ids, true);
            $this->deleteHTML();
            return true;
        }
        return false;
    }
    
    /**
     * @see 根据给定条件删除相应数据
     * @param string或array $maps 查询条件
     * @return boolean
     */
    public function deleteMaps($maps, $mapData = false){
        if(empty($mapData)){
            $mapData = false;
        }
        $wheres = $this->getWhere($maps, $mapData);
        $where = $wheres[0];
        $mapData = $wheres[1];
        if(empty($where)){
            throw new InkException(L('按照条件删除必须带条件，否则不允许执行'), 110020003);
        }
        $pk = $this->getPK();
        $sql = 'SELECT `'.$pk.'` FROM '.$this->table.' WHERE 1 '.$where;
        $iddata = $this->query($sql, $mapData);
        $sql = 'DELETE FROM '.$this->table.' WHERE 1 '.$where;
        if($this->execSql($sql, $mapData)){
            if(!empty($iddata)){
                $ids = array();
                foreach((array) $iddata as $key => $id){
                    $ids[] = $id[$pk];
                }
                $this->deleteFileids($ids, true);
            }
            $this->deleteHTML();
            return true;
        }
        return false;
    }

    /**
     * @see 取得表的主键
     * @return string 返回主键
     */
    protected function getPK(){
        if(__USE_MEMCACHE__){
            $mem_key = $this->table.'_pk';
            $pk = $this->mem->get($mem_key);
            if(empty($pk)){
                $pk = $this->getPKFromDB();
                if(!empty($pk)){$this->mem->set($mem_key, $pk);}
            }
        }else{
            $pk = $this->getPKFromDB();
        }
        return $pk;
    }

    /**
     * @see 从数据表中获取主键
     * @return string 返回主键
     */
    private function getPKFromDB(){
        $sql = 'SHOW INDEX FROM '.$this->table.' WHERE Key_name=\'PRIMARY\'';
        $data = $this->query($sql);
        $pk = $data[0]['Column_name'];
        return $pk;
    }

    /**
     * @see 取得第三方插件实例的方法
     * @param string $name 插件名称
     * @param array $params 实例化时需要的参数
     * @return object 返回三方插件的实例
     */
    protected function X($name = null,$params = null){
        $xpath = __LIB__.'/'.$name.'.class.php';
        if(!file_exists($xpath)){
            return 'lib_file_is_not_exist:'.$xpath;
        }else{
            include_once($xpath);
            if(!class_exists($name)){
                return 'lib_class_is_not_define:'.$name;
            }else{
                if(!empty($params)){return new $name($params);}else{return new $name();}
            }
        }
    }

    /**
     * @see 删除表
     * @param string $table 数据表名称
     */
    public function delTable($table){
        $sql = 'DROP TABLE IF EXISTS `'.$table.'`;';
        $this->execSql($sql);
        $this->deleteHTML();
    }
    
    /**
     * @see 打印刚刚被执行过的SQL语句。
     */
    public function printSql(){
        echo $this->querySql;
        exit;
    }
    
    
    
    /**
     * @see 根据给定条件，获取指定表的指定字段的数据
     * @param array 或 string $fields:缺省值为'*',或者可以定义为数组array('a','t.b','t.c as abc');
     * @param string $table 表名
     * @param array 或 string $where 查询条件
     * @return array 返回查询结果
     */
    public function getTableData($fields, $table, $maps, $mapData = false){
        $field = $this->getFields($fields);
        if(empty($mapData)){
            $mapData = false;
        }
        if(!empty($maps)){
            $wheres = $this->getWhere($maps, $mapData);
            $where = $wheres[0];
            $mapData = $wheres[1];
        }else{
            $where = '';
        }
        $sql = 'SELECT '.$field.' FROM `'.$table.'` WHERE 1 '. $where;
        $data = $this->query($sql, $mapData);
        return $data;
    }

    /**
     * @see 获取mysql版本号
     * @return string 返回mysql版本
     */
    public function getMysqlVersion(){
        $sql = 'SELECT VERSION() as version';
        $data = $this->query($sql);
        return $data;
    }

    /**
     * @see获取数据库大小
     * @return float 返回数据表的长度
     */
    public function getMysqlDataLength(){
        $sql = 'SHOW TABLE STATUS LIKE \''.__DB_PREFIX__.'%\'';
        $data = $this->query($sql);
        foreach ((array)$data as $k){$dbsize += $k['Data_length'] + $k['Index_length'];}
        return $dbsize;
    }
    
    /**
     * @see 截断表
     * @return boolean 返回阶段表的结果
     */
    public function transcate(){
        $sql = 'TRUNCATE `'.$this->table.'`';
        $this->deleteHTML();
        return $this->execSql($sql);
    }
    
    /**
     * @see 删除动态生成的HTML文件
     */
    private function deleteHTML(){
/*        $nothtmls = array('Apps','Appzip','core','Data','nbproject','.svn','.htaccess','index.php','.','..');
        $path = './';
        $file = $this->X('File');
        $dirs = $file->getDir($path);
        foreach((array)$dirs as $key => $f){
            if(!in_array($f, $nothtmls)){
                $file->deleteDir($f, true);
            }
        }
		*/
    }
    
    /**
     * @see 取得LEFT JOIN SQL
     * @param array $join ：array('direction', 'table', 'alias', 'on'); on = $maps;
     * @return string 返回生成的LEFT JOIN SQL
     */
    private function leftSql($join){
        $leftSql = ' LEFT JOIN '.$join[1];
        $leftSql .= !empty($join[2]) ? ' AS '.$join[2] : '';
        $wheres = $this->getWhere($join[3]);
        $rightSql .= ' ON '. $wheres[0];
        return array($rightSql, $wheres[1]);
    }
    
    /**
     * @see 取得RIGHT JOIN SQL
     * @param array $join ：array('direction', 'table', 'alias', 'on'); on = $maps;
     * @return string 返回生成的RIGHT JOIN SQL
     */
    private function rightSql($join){
        $rightSql = ' RIGHT JOIN '.$join[1];
        $rightSql .= !empty($join[2]) ? ' AS '.$join[2] : '';
        $wheres = $this->getWhere($join[3]);
        $rightSql .= ' ON '. $wheres[0];
        return array($rightSql, $wheres[1]);
    }
    
    /**
     * @see 取得INNER JOIN SQL
     * @param array $join ：array('direction', 'table', 'alias', 'on'); on = $maps;
     * @return string 返回生成的INNER JOIN SQL
     */
    private function innerSql($join){
        $innerSql = ' INNER JOIN '.$join[1];
        $innerSql .= !empty($join[2]) ? ' AS '.$join[2] : '';
        $wheres = $this->getWhere($join[3]);
        $innerSql .= ' ON '. $wheres[0];
        return array($innerSql, $wheres[1]);
    }
    
    /**
     * @see 取得JOIN SQL
     * @param array $join ：array('direction', 'table', 'alias', 'on'); on = $maps;
     * @return string 返回生成的JOIN SQL
     */
    private function joinSql($join){
        $join[0] = strtolower($join[0]);
        $func .= $join[0].'Sql';
        $joinSqls = $this->$func($join);
        return $joinSqls;
    }
    
    /**
     * @see 运行多表查询SQL
     * @param string 或 array $fields:缺省值为'*',或者可以定义为数组array('a','t.b','t.c as abc');
     * @param array $joins: array('direction','table', 'alias', 'on');,on:和普通语句中的maps是一样的写法；
     * @param str 或 array $maps:条件array('a' => 'b', 'c' => array('d', '>', '10'), 'e' => array('OR', 'f', '>', '10'), 'g' => 'array('abc'));
     * @param string $onData Join语句On的条件数据
     * @param string $mapData Where语句的条件数据
     * @param string $order 排序字段和排序方式
     * @param array 或 null $group 分组字段名
     */
    protected function runJoinSql($fields = '*', $joins = null, $maps = null, $order = null, $group = null, $onData = false, $mapData = false){
        $fields = $this->getFields($fields);
        $sql = 'SELECT '.$fields.' FROM '.$this->table.(!empty($this->alias) ? ' AS '.$this->alias : '');
        $mapData = array();
        foreach((array)$joins as $key => $value){
            $joinSqls = $this->joinSql($value);
            $sql .= $joinSqls[0].' ';
            $mapData = array_merge($mapData, $joinSqls[1]);
        }
        $wheres = $this->getWhere($maps, $mapData);
        $sql .= $wheres[0];
        $mapData = $mapData = array_merge($mapData, $wheres[1]);
        $sql .= !empty($order) ? ' ORDER BY '.$order : '';
        $sql .= !empty($group) ? ' GROUP BY '.$group : '';
        return $this->query($sql, $mapData);
    }
}