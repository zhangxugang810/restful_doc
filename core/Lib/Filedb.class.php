<?php
/**
* 缓存类
*
* 本程序主要作用定义硬盘缓存，将数据缓存到硬盘
* 
* @category   Lib
* @package    Lib
* @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
class Filedb{
    public $dbPath;
    public $table;
    public $data;
    /**
     * 构造方法
     * @param type $timeout 缓存实效是长
     */
    public function __construct($table) {
        $this->setTable($table);
        $this->setDbPath();
        $this->getData();
    }
    
    private function setTable($table){
        $this->table = $table;
    }


    /**
     * 写入数据
     * @param type $key 地址
     * @param type $value 数据
     * @return type 真/假
     */
    public function set($key, $value){
        $path = $this->dbPath.'/'.$key.'.usr';
        return file_put_contents($path, serialize($value));
    }
    
    public function isExists($key){
        $path = $this->dbPath.'/'.$key.'.usr';
        return file_exists($path);
    }


    private function getData(){
        $handler = opendir($this->dbPath);
        $data = array();
        while($file = readdir($handler)){
            if($file != '.' && $file != '..'){
                $data[] = unserialize(file_get_contents($this->dbPath.'/'.$file));
            }
        }
        $this->data = $data;
    }


    public function getAll($where = null){
        foreach($this->data as $key => $value){
            if(!$this->isWhere($where, $value)){continue;}
            $data[] = $value;
        }
        return $data;
    }
    
    public function pageList($start = 0, $len = 10, $where = null){
        $data = array();
        $n = 0;
        foreach($this->data as $key => $value){
            if($file != '.' && $file != '..'){
                if(!$this->isWhere($where, $value)){continue;}
                $data[] = $value;
                $n++;
                if($n > $len){break;}
            }
        }
        return $data;
    }
    
    private function isWhere($where = null, $value = null){
//        if(empty($where)){return true;}
//        if(empty($value)){return false;}
        return true;
    }

    /**
     * 取得数据
     * @param type $key 地址
     * @return type 数据
     */
    public function get($key){
        $path = $this->dbPath.'/'.$key.'.usr';
        if(file_exists($path)){
            $json = file_get_contents($path);
            $data = unserialize($json);
            return $data;
        }else{
            return ;
        }
    }
    /**
     * 删除单个文件
     * @param type $key
     * @return type
     */
    public function delete($key){
        $path = $this->dbPath.'/'.$key.'.usr';
        if(file_exists($path)){
            return @unlink($path);
        }
    }
    
    public function clear(){
        $hander = opendir($this->dbPath);
        while($file = readdir($hander)){
            if($file != '..' && $file != '.'){
                $fnames = explode('.', $file);
                $this->delete($fnames[0]);
            }
        }
    }

    /**
     * 寻找并生成数据路径
     */
    private function setDbPath(){
        if(!file_exists(__FILE_DB_PATH__)){
            mkdir(__FILE_DB_PATH__);
        }
        
        $path = __FILE_DB_PATH__.'/filedb/'.$this->table;
        if(!file_exists($path)){
            @mkdir($path);
        }
        $this->dbPath = $path;
    }
}