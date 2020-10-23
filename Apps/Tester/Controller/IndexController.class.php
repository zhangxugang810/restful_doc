<?php

/**
 * 问题：
 * 1.private $url应该改为配置
 * 2.private $controllerPath应该改为配置
 * 3.返回信息应该只含有格式化数据未格式化数据貌似必要不到
 * 4.读取类注释如果没有应该显示"未定义类或者直接已类名称显示"
 * 5.返回数据的顶级格式不应该是固定的
 * 6.读取文件名的格式也不应该是固定的应该可配置
 */

class IndexController extends PublicController {

//    private $controllerPath;
    private $returnarray = array();
    private $cache;
    private $itemsdb;


    public function __construct($data) {
        parent::__construct($data);
//        $this->controllerPath = __API_CONTROLLER_PATH__;
        $this->cache = $this->X('Cache');
//        $this->getUrl();
        $this->itemsdb = $this->X('Filedb', 'items');
    }

    private function getItems(){
        $items = $this->itemsdb->getAll();
//        $items = $this->getLimitItems($items);
        //该用户是观察者，能查看的项目
        if($this->user['role'] == 1){
            foreach ((array)$items as $key => $value) {
                if (!in_array($value['itemtag'], (array)$this->user['projectList'])) {
                    unset($items[$key]);
                }
            }
        }
        $this->assign('items', $items);
        return $items;
    }
    
//    private function getUrl(){
//        $urls = core::getConfig('environment');
//        $this->assign('environment', $urls);
//        $this->url = $urls['testing']['url'];
//    }
    
//    private function getDefaultGetParams(){
//        $this->assign('defaultGetParams', core::getConfig('defaultGetParams'));
//    }
    
    public function main(){
//        print_r($this->user);
        $this->display();
    }

    private function getLimitItems($items){
        $limitItems = array();
        foreach($this->user['groups'] as $k => $v){
            $limitItems = array_merge($limitItems, $v['items']);
        }
        
        foreach($items as $k => $item){
            if(!in_array($item['itemtag'], $limitItems)){
                unset($items[$k]);
            }
        }
        return $items;
    }

    public function index() {
        $settingsdb = $this->X('Filedb', 'settings');
        $txt = doEncrypt('setting', __CODE_KEY__);
        $key = md5(doEncrypt('setting', __CODE_KEY__));
//        echo $key;exit;
        $settings = $settingsdb->get($key);
        $this->assign('sysname', $settings['sysname']);
        $this->getItems();
        $this->assign('bgclass', $_COOKIE['bgclass']);
        $this->display();
    }
    
    public function getItemIndex(){
        $itemId = $this->p['itemid'];
        $itemkey = md5(doEncrypt($itemId, __CODE_KEY__));
        $item = $this->itemsdb->get($itemkey);
        $description = html_entity_decode($item['description']);
        $this->assign('itemIndex', $description);
        $this->json();
    }


    public function getItem(){
        $itemId = $this->p['itemid'];
        $s = doEncrypt($itemId, __CODE_KEY__);
        $itemkey = md5($s);
        $item = $this->itemsdb->get($itemkey);
//        print_r($itemkey);exit;
        $controllers = $this->saveControllers($itemId, $item);
        /*流程图设置*/
        $list = $item['flows'];
        if(!empty($list['name'][0])){
            $l = array();
            foreach($list['name'] as $k => $v){
                $l[] = array('name' => $v, 'url' => $list['url'][$k]);
            }
            $controllers[] = array(
                'name' => '流程图',
                'status' => 'noajax',
                'controller' => 'Flow',
                'controllerPath' => 'Flow',
                'app' => 'Home',
                'title' => '流程图',
                'list' => $l
            );
        }
        $this->assign('item', $item);
        $this->assign('controllers', $controllers);
        $this->display();
    }
    
    private function saveControllers($itemId, $item){
        $key = md5('CONTROLLERS_LIST_'.strtoupper($itemId));
        $controllers = $this->cache->get($key);
        if (empty($controllers)) {
            $controllers = $this->getControllers($item);
            $this->cache->set($key, $controllers);
        }
        return $controllers;
    }

    public function syshelp(){
        $this->display();
    }

    public function help(){
        $this->display();
    }


    /**
     * 获取菜单数据
     */
    private function getControllers($item) {
        $data = array();
        $apps = array();
        foreach($item['itemDirs'] as $k => $v){
            if(file_exists($v)){
                $d = $this->file->getFiles($v);
                $data = array_merge($data, $d);
                $apps[$v] = $item['itemDirUrls'][$k];
            }
        }
        if(empty($data)){return false;}
        $ds = array();
        foreach ((array) $data as $key => $path) {
//            if ($this->isExistString($path, 'Controller')) {
                $content = file_get_contents($path);
                $desc = $this->getApiName($content);
                $ks = str_replace('/'.basename($path), '', $path);
                if ($desc['classaccess'] == 'public') {
                    $ds[$key] = array('name' => $desc['classname'], 'version' => $desc['version'], 'description' => $desc['description'], 'controller' => str_replace(['Controller.php', '.php'], ['', ''], basename($path)), 'controllerPath' => $path, 'app' => $apps[$ks], 'errorCode' => $desc['errorCode']);
                } else {
                    unset($d[$k]);
                    continue;
                }
//            }
        }
        return $ds;
    }

    /**
     * 获取接口名称
     */
    private function getApiName($code) {
        $data = explode('/**', $code);
        $temp = explode('*/', $data[1]);
        $desc = $this->analysisDesc($temp[0]);
        return $desc;
    }

    /**
     * 获取一个类中的所有接口方法
     */
    public function classApiList() {
        $controller = $this->p['controller'];
        $path = base64_decode($this->p['controllerPath']);
        $app = $this->p['app'];
        $key = md5(strtoupper($path));
        $d = $this->cache->get($key);
        if (empty($d)) {
            $data = file_get_contents($path);
            $d = $this->analysisCode($data);
            $this->cache->set($key, $d);
        }
        $this->assign('app', $app);
        $this->assign('path', base64_encode($path));
        $this->assign('apilist', $d);
        $this->display();
    }

    /**
     * 按照“/**”分割接口文件
     * @param type $code
     * @return type
     */
    private function analysisCode($code, $funcname = null) {
        $data = explode('/**', $code);
        foreach ($data as $key => $value) {
            if ($key > 0) {
                $temp = explode('*/', $value);
                $desc = $this->analysisDesc($temp[0], $funcname);
                if ($desc['access'] == 'public') {
                    if (empty($funcname)) {
                        $d[$key] = $desc;
                    } elseif (strtolower($desc['funcname']) == strtolower($funcname)) {
                        return $desc;
                    }
                }
            }
        }
        return $d;
    }

    private function analysisDesc($str, $funcname = null) {
        $arr = explode("\n", $str);
        $d = array();
        foreach ((array) $arr as $key => $value) {
            if (isExistsStr($value, '@description')) {
                $d['description'] = trim(str_replace('* @description', '', $value));
            }
            if (isExistsStr($value, '@classname')) {
                $d['classname'] = trim(str_replace('* @classname', '', $value));
            }

            if (isExistsStr($value, '@classaccess')) {
                $d['classaccess'] = strtolower(trim(str_replace('* @classaccess', '', $value)));
                if ($d['classaccess'] != 'public') {
                    return $d;
                }
            }
            
            
            
            if (isExistsStr($value, '@version')) {
                $d['version'] = trim(str_replace('* @version', '', $value));
            }
            

            if (isExistsStr($value, '@access')) {
                $d['access'] = trim(str_replace('* @access', '', $value));
            }
            if (isExistsStr($value, '@name')) {
                $d['funcname'] = trim(str_replace('* @name', '', $value));
                $d['func'] = trim(str_replace('* @name', '函数名称：', $value));
            }

            if (isExistsStr($value, '@method')) {
                $d['method'] = strtoupper(trim(str_replace('* @method', '', $value)));
            }

            if (isExistsStr($value, '@requestType')) {
                $d['requestType'] = strtoupper(trim(str_replace('* @requestType', '', $value)));
            }

            if (isExistsStr($value, '@author')) {
                $d['author'] = $this->author($value);//strtoupper(trim(str_replace('* @author', '', $value)));
            }

            if (isExistsStr($value, '@notice')) {
                $d['notice'] = trim(str_replace('* @notice', '', $value));
            }

            if (isExistsStr($value, '@see')) {
                $d['see'] = trim(str_replace('* @see', '', $value));
            }

            if (isExistsStr($value, '@describe')) {
                $d['describe'] = trim(str_replace('* @describe', '', $value));
            }

            if (isExistsStr($value, '@param')) {
                $d['param'][] = explode('|', trim(str_replace('* @param', '', $value)));
            }
            
            if (isExistsStr($value, '@example')) {
                $d['example'][] = trim(str_replace('* @example', '', $value));
            }
            
            if (isExistsStr($value, '@cookie')) {
                $d['cookie'] = trim(str_replace('* @cookie', '', $value));
            }

            if (isExistsStr($value, '@header')) {
                $d['header'][] = explode('|', trim(str_replace('* @header', '', $value)));
            }

            if (isExistsStr($value, '@baseauth')) {
                $d['baseauth'][] = explode('|', trim(str_replace('* @baseauth', '', $value)));
            }
            if (isExistsStr($value, '@errorCode')) {
                $d['errorCode'][] = explode('|', trim(str_replace('* @errorCode', '', $value)));
            }

//            if (isExistsStr($value, '@defget')) {
//                $d['defget'][] = explode('|', trim(str_replace('* @defget', '', $value)));
//            }

//            if (isExistsStr($value, '@default')) {
//                $d['default'] = trim(str_replace('* @default', '', $value));
//            }

            if (isExistsStr($value, '@datanotice')) {
                $datanotice = explode('|', trim(str_replace('* @datanotice', '', $value)));
                $d['datanoticeurlname'] = $datanotice[0];
                $d['datanoticeurlcontent'] = $this->url . $datanotice[1];
                $d['datanoticeurlwidth'] = $datanotice[2];
                $d['datanoticeurlheight'] = $datanotice[3];
            }

            if (isExistsStr($value, '@return ')) {
                $return = explode('|', trim(str_replace('* @return', '', $value)));
                if (empty($return[0])) {  //如果为空，则代表返回参数为空
                    $d['return'] = $return;
                    break;
                } else {
                    $d['return'][$key] = $return;
                    if ($return[1] == 'table' || $return[1] == 'array') {
                        if (!empty($funcname) && strtolower($funcname) == strtolower($d['funcname'])) {
//                            if ($return[1] == 'table') {
//                                $fields = $this->getTableFiled(trim(strtolower($return[2])), trim($return[3]));
//                                $this->returnarray['return_' . $return[0]] = $fields;
////                                $this->returnarray['return_'.$return[0]] = array_merge($this->returnarray['return_'.$return[0]], $fields);
//                            }
                        } else {
                            continue;
                        }
                        $this->getArrayFields($arr, $key + 1, $return[0]);
                    }
                }
            }
        }
        if (!empty($funcname) && strtolower($funcname) == strtolower($d['funcname'])) {
            $d['returnarray'] = $this->returnarray;
        } else {
            unset($this->returnarray);
        }
        return $d;
    }
    
    private function author($str){
        $str = strtoupper(trim(str_replace('* @author', '', $str)));
        $strs = explode('|', $str);
        $s = [];
        $v = __VERSION_TEMP__;
        $i = 1;
        foreach((array)$strs as $key =>$value){
            $s[$i]['v'] = str_replace('{n}', $i, $v);
            $author = explode(',', $value);
            $s[$i]['author'] = $author[0];
            $s[$i]['time'] = !empty($author[1]) ? $author[1] : '';
            $s[$i]['desc'] = !empty($author[2]) ? $author[2] : '';
            $i++ ;
        }
        return $s;
    }

    private function getArrayFields($arr, $i, $tag) {
        $search = 'return_' . $tag;
        for ($i; $i < count($arr); $i++) {
            if (isExistsStr($arr[$i], '@' . $search . ' ')) {
                $val = trim(str_replace('* @' . $search . ' ', '', $arr[$i]));
                $val = explode('|', $val);
                $d[0] = $val;
                $this->returnarray[$search] = array_merge((array) $this->returnarray[$search], $d);
                if ($val[1] == 'table' || $val[1] == 'array') {
//                    if ($val[1] == 'table') {
//                        $this->returnarray[$search . '_' . $val[0]] = array_merge((array) $this->returnarray[$search . '_' . $val[0]], (array) $this->getTableFiled(trim(strtolower($val[2])), trim($val[3])));
//                    }
                    $this->returnarray[$search] = array_merge((array) $this->returnarray[$search], (array) $this->getArrayFields($arr, $i + 1, $tag . '_' . $val[0]));
                }
            }
        }
    }

    /**
     * 获取数据库表字段信息
     */
    private function getTableFiled($tablename, $tablefileds) {
        $data = $this->D(ucfirst($tablename))->getTableFields($tablename);
        foreach ((array) $data as $key => $value) {
            if ($tablefileds == '*') {
                $d[$key][] = $value['Field'];
                $d[$key][] = $this->settingFieldType($value['Type']);
                $d[$key][] = $value['Comment'];
            } else {
                $fields = explode(',', $tablefileds);
                foreach ((array) $fields as $k => $v) {
                    $fields[$k] = trim($v);
                }
                if (in_array($value['Field'], $fields)) {
                    $d[$key][] = $value['Field'];
                    $d[$key][] = $this->settingFieldType($value['Type']);
                    $d[$key][] = $value['Comment'];
                }
            }
        }
        return $d;
    }

    private function isExistString($str, $checkStr) {
        $d = explode($checkStr, $str);
        $len = count($d);
        return $len > 1;
    }
    
    public function apiToWord(){
        $controller = $this->g['controller'];
        $fname = $this->g['funcname'];
        $app = $this->g['app'];
        $path = base64_decode($this->g['path']);
        $item = $this->g['item'];
        $doc = $this->X('Document');
        $funcname = $this->getFunctionName($fname);
        $envir = $this->getEnvir($item, $app, $controller, $funcname);

        $key = md5($path . '_' . $funcname);
        $desc = $this->cache->get($key);
        if (empty($desc)) {
            $desc = $this->analysisCode(file_get_contents($path), $fname);
            $this->cache->set($key, $desc);
        }
        if(!empty($desc['param'])){$this->assign('isfile', $this->isfile($desc['param']));}
        
        $formats = $this->getParamsFormat($item);//
        $introduce = $this->getIntroduce($item);//
        
        //获取errorCode
//        $errors = $this->getControllerErrorCode($item, $controller);
//        $doc->assign('errors', $errors);
        $doc->assign('envir', $envir);
        $doc->assign('desc', $desc);
        $doc->assign('controller', $controller);
        $doc->assign('funcname', $funcname);
        $doc->assign('app', $app);
        $url = empty($this->url) ? __SITENAME__ . 'Api/' . $controller . '/' . $funcname : $this->url . '/rest.php?route=' . $fname;
        $doc->assign('url', $url);
//        $this->getDefaultGetParams();
        $doc->assign('systemoption', $this->systemoption);
        if(!empty($formats)){$doc->assign('formats', $formats);}
        if(!empty($introduce)){$doc->assign('introduce', $introduce);}
//        if (!empty($desc['default'])) {$this->display('defalutApiContent');}
//        $html = $this->display(null, 'html');
        $fileName = $desc['see']; //iconv('UTF-8', 'GBK', $desc['see']);
        $file = $doc->createWord($fileName);
//        $file = iconv('GBK', 'UTF-8', $file);
        $file = substr($file, 1, strlen($file));
        echo $file; exit;
    }
    
    public function apiToIkangWord(){
        $controller = $this->g['controller'];
        $fname = $this->g['funcname'];
        $app = $this->g['app'];
        $path = base64_decode($this->g['path']);
        $item = $this->g['item'];
        $doc = $this->X('Document');
        $funcname = $this->getFunctionName($fname);
        $envir = $this->getEnvir($item, $app, $controller, $funcname);

        $key = md5($path . '_' . $funcname);
        $desc = $this->cache->get($key);
        if (empty($desc)) {
            $desc = $this->analysisCode(file_get_contents($path), $fname);
            $this->cache->set($key, $desc);
        }
        if(!empty($desc['param'])){$this->assign('isfile', $this->isfile($desc['param']));}
        
        $formats = $this->getParamsFormat($item);//
        $introduce = $this->getIntroduce($item);//
        
        //获取errorCode
//        $errors = $this->getControllerErrorCode($item, $controller);
//        $doc->assign('errors', $errors);
        $doc->assign('envir', $envir);
        $doc->assign('desc', $desc);
        $doc->assign('controller', $controller);
        $doc->assign('funcname', $funcname);
        $doc->assign('app', $app);
        $url = empty($this->url) ? __SITENAME__ . 'Api/' . $controller . '/' . $funcname : $this->url . '/rest.php?route=' . $fname;
        $doc->assign('url', $url);
//        $this->getDefaultGetParams();
        $doc->assign('systemoption', $this->systemoption);
        if(!empty($formats)){$doc->assign('formats', $formats);}
        if(!empty($introduce)){$doc->assign('introduce', $introduce);}
//        if (!empty($desc['default'])) {$this->display('defalutApiContent');}
//        $html = $this->display(null, 'html');
        $file = $doc->createIkangWord(iconv('UTF-8', 'GBK', $desc['see']));
        $file = iconv('GBK', 'UTF-8', $file);
        $file = substr($file, 1, strlen($file));
        echo $file; exit;
    }

    public function apiContent() {
        $controller = $this->p['controller'];
        $funcname = $this->getFunctionName($this->p['funcname']);
        $app = $this->p['app'];
        $path = base64_decode($this->p['path']);
        $envir = $this->getEnvir($this->p['item'], $app, $controller, $funcname);////
        $key = md5($path . '_' . $funcname);
        $desc = $this->cache->get($key);
        if (empty($desc)) {
            $desc = $this->analysisCode(file_get_contents($path), $this->p['funcname']);
            $this->cache->set($key, $desc);
        }
        
        if(!empty($desc['param'])){$this->assign('isfile', $this->isfile($desc['param']));}
        
        $formats = $this->getParamsFormat($this->p['item']);////
        $introduce = $this->getIntroduce($this->p['item']);////
        
//        print_r($desc['errorCode']);
        //获取errorCode
//        $errors = $this->getControllerErrorCode($this->p['item'], $controller);
//        $this->assign('errors', $errors);
        
        $this->assign('item', $this->p['item']);
        $this->assign('path', $path);
        $this->assign('envir', $envir);
        $this->assign('desc', $desc);
        $this->assign('controller', $controller);
        $this->assign('funcname', $funcname);
        $this->assign('app', $this->p['app']);
        $url = empty($this->url) ? __SITENAME__ . 'Api/' . $controller . '/' . $funcname : $this->url . '/rest.php?route=' . $this->p['funcname'];
        $this->assign('url', $url);
//        $this->getDefaultGetParams();
        $this->assign('systemoption', $this->systemoption);
        if(!empty($formats)){$this->assign('formats', $formats);}
        if(!empty($introduce)){$this->assign('introduce', $introduce);}
        if (!empty($desc['default'])) {$this->display('defalutApiContent');}
        $this->display();
    }
    
    private function getControllerErrorCode($itemId, $controller){
        $itemkey = md5(doEncrypt($itemId, __CODE_KEY__));
        $item = $this->itemsdb->get($itemkey);
        $this->saveControllers($itemId, $item);
        $key = md5('CONTROLLERS_LIST_'.strtoupper($itemId));
        $controllers = $this->cache->get($key);
        foreach($controllers as $k => $v){
            if($v['controller'] == $controller){
                $data = $v['errorCode'];
                break;
            }
        }
        return $data;
    }


    private function getIntroduce($itemtag){
        $itemkey = md5(doEncrypt($itemtag, __CODE_KEY__));
        $item = $this->itemsdb->get($itemkey);
        
        if(empty($item['introduce'])){return null;}
        return html_entity_decode($item['introduce']);
    }


    private function getParamsFormat($itemtag){
        $itemkey = md5(doEncrypt($itemtag, __CODE_KEY__));
        $item = $this->itemsdb->get($itemkey);
        if(empty($item['paramsFormat'])){return null;}
        $formats = str_replace('；', ';', $item['paramsFormat']);
        $formats = explode(';', $formats);
        foreach($formats as $k => $v){
            $formats[$k] = explode('|', $v);
        }
        return $formats;
    }


    private function getEnvir($itemtag, $app, $controller, $action){
        $itemkey = md5(doEncrypt($itemtag, __CODE_KEY__));
        $item = $this->itemsdb->get($itemkey);
//        $item = unserialize(base64_decode($item));
        $data = array();
        $url = str_replace('{App}', $app, $item['rewrite']);
        $url = str_replace('{Controller}', strtolower($controller), $url);
        $url = str_replace('{Action}', $action, $url);
        foreach($item['urls']['name'] as $k => $v){
            $data[] = array('name' => $v, 'url' => $item['urls']['url'][$k].'/'.$url);
        }
        return $data;
    }


    private function getFunctionName($func){
        $funcs = explode('/', $func);
        $len = count($funcs);
        return empty($funcs[$len-1]) ? 'index' : $funcs[$len-1];;
    }


    private function isfile($param){
        foreach((array)$param as $v){
            if($v[1] == 'file'){return true;}
        }
        return false;
    }

    public function format() {
        $json = $this->p['json'];
        $returncode = $this->p['code'];
        if($json == 'false'){die(html_entity_decode($returncode));}
        $code = $this->jsonFormat($returncode);
        $codedata = explode('<br />', $code);
        $this->assign('pcounts', count($codedata));
        $this->assign('codedata', $codedata);
        $this->assign('returncode', $returncode);
        $this->assign('code', $code);
        $this->display();
    }

    private function jsonFormat($json) {
        
        $result = '';
        $pos = 0;
        $strLen = strlen($json);
        $indentStr = '　　';
        $newLine = "\n";
        $prevChar = '';
        $outOfQuotes = true;
        for ($i = 0; $i <= $strLen; $i++) {
            $char = substr($json, $i, 1);
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;
            } else if (($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos --;
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
            $result .= $char;
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos ++;
                }
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }
            $prevChar = $char;
        }
        $result = str_replace(' ', '&nbsp;', $result);
        $result = str_replace("\n", '<br />', $result);
        return $result;
    }

    public function systemoption() {
        $this->assign('systemoption', $this->systemoption);
        $this->display();
    }

    public function refreshDoc() {
        $this->cache->clear();
        die(true);
    }

    private function is_json($string) {
        return !is_null(json_decode($string));
    }
    
    public function about(){
        $this->display();
    }
    
    public function flow(){
        $this->display();
    }
    
    public function verify(){
        return $this->showVerify();
    }
    
    public function checkDir(){
        $path = $this->p['path'];
        if(file_exists($path)){
            $handler = opendir($path);
            $files = [];
            while($file = readdir($handler)){
                if($file != '.' && $file != '..'){
                    $files[] = $file;
                }
            }
            echo json_encode(array('result' => true, 'data' => $files, 'error' => 0, 'errorCode' => 0));exit;
        }
        echo json_encode(array('result' => false, 'data' => [], 'error' => 1, 'errorCode' => 100001));exit;
    }
    
    public function saveError(){
        $html = html_entity_decode($this->p['html']);
        $path = './Data/debug';
        if(!file_exists($path)){@mkdir($path);}
        $file = $path . '/error.html';
        $result = file_put_contents($file, $html);
        $this->assign('result', $result);
        $this->assign('file', str_replace('./', '/', $file));
        $this->json();
    }
}
