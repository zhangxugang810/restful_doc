<?php
/**
* Controlller基类
*
* 本程序主要用来取得在Controller中使用的一些基本方法和基本变量
*
* @category   Controller
* @package    Base
* @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
class Controller{
    protected $tpl;
    public $app;
    public $m;
    public $a;
    public $g;
    public $p;
    public $r;
    private $db = false;
    private $publicTpl;
    private $limitType;
    public function __construct($data) {
        $this->getTplObj();
        $this->g = $data['g'];
        $this->r = $data['r'];
        $this->p = $data['p'];
        $this->app = APP_NAME;
        $this->m = MODEL_NAME;
        $this->a = ACTION_NAME;
        $this->publicTpl = array('upload', 'msg', 'nopage');
    }
    
    public function init(){}

    /**
     * @see 使用Session做登陆的检查。
     * @param string $name session名字
     * @param string $jumpUrl 检查不存在的话要跳转到得位置,一般是登陆页的地址
     */
    protected function checkSessionLogin($name, $jumpUrl){if(empty($_SESSION[$name])){$this->jump($jumpUrl);}}
    
    /**
     * @see 检查Cookie登陆状态，并跳转
     * @param string $name
     * @param string $jumpUrl
     */
    protected function checkCookieLogin($name,$jumpUrl){
        $cookie = $this->X('Cookie', $name);
        $ck = $cookie->getCookie();
        if(empty($ck)){$this->jump($jumpUrl);}
    }


    /**
     * @see 魔术方法，当调用的Action不存在的话自动会进入这个方法
     * @param string $name Action的名字
     * @param string $params 此参数暂时无用处
     * @return string html
     */
    public function __call($name,$params = null){
        print_r($params);
        echo APP_NAME.'/'.MODEL_NAME.'/'.ACTION_NAME;exit;
        if(APP_NAME == __DEFAULT_APP__ && MODEL_NAME == __DEFAULT_MODEL__){
            $this->__DEFAULT_ACTION__($name);
        }else{
            return $this->display($name);
        }
    }

    /**
     * @see 将模板变量变为Json数据输出到浏览器，可加密也可以不加密
     */
    public function json(){$this->tpl->json();exit;}

    public function appjson($enabled = false){
        $this->tpl->appjson($enabled);
        exit;
    }

    /**
     * @see 此方法的主要功能是显示渲染页面。
     * @param string $name 模板名称，如果为空的话会自动变为Action的名字，无需扩展名
     * @param string $dest 要创建的静态文件的存放目录
     */
    final public function display($name = null, $dest = null){
        $path = $this->changeTpl($name);
        if(__CREATE_HTML__){$dest = '.'.U(APP_NAME.'/'.MODEL_NAME.'/'.ACTION_NAME, $this->g);}elseif($dest != 'html'){$dest = null;}
        $result = $this->tpl->display($path, $dest);
        if($dest == 'html'){return $result;}
        exit;
    }

    /**
     * @see 检查模板名字
     * @param sting $name 模板名称
     * @return string $path 模板路径
     */
    private function changeTpl($name){
        if(empty($name)){$name = $this->a;}
        $name = str_replace('.tpl.php','',$name);
        if(in_array($name, $this->publicTpl)){$path = './core/Theme/Public/'.$name.'.tpl.php';}else{if(str_replace('/', '', $name) == $name){$path = './Apps/'.$this->app.'/Theme/'.__DEFAULT_THEME__.'/'.$this->m.'/'.$name.'.tpl.php';}else{$path = './Apps/'.$this->app.'/Theme/'.__DEFAULT_THEME__.'/'.$name.'.tpl.php';}}
        if(!file_exists($path)){throw new InkException(L('template_is_not_exists').'：'.basename($path), 110030001);}
        return $path;
    }


    /**
     * @see 设置模板变量
     * @param string $name
     * @param anytype $data
     */
    final public function assign($name, $data = null){if(!is_object($data)){$this->tpl->assign($name, $data);}}
    /**
     * @see 检查文件是否存在
     * @return boolean
     */
    public function checkFile(){
        $path = $this->p['file'];
        $type = $this->p['filetype'];
        if($type == 'local' || $type == ''){if(file_exists($file)){$result = true;}else{$result = false;$msg = '文件不存在';}}else if($type == 'url'){$data = @file_get_contents($file);if(!$data){$result = false;$msg = '文件不存在';}else{return true;}}
        $this->assign('result', $result);
        $this->assign('msg', $msg);
        $this->json();
    }

    /**
     * @see 显示提示消息
     * @param string $msg 要显示的提示消息
     * @param string $status 提示类型：error，warring，success
     * @param string $jumpUrl 消息显示完成后要跳转的页面，默认为上一个页面
     */
    private function showMsg($msg, $status, $jumpUrl = null){
        $msgs = explode(':', $msg);
        if(count($msgs) == 2){
            $msg = L($msgs[0]).$msgs[1];
            $this->assign('msg', $msg);
        }else{
            $this->assign('msg', L($msg));
        }
        if($jumpUrl == '' || empty($jumpUrl)){
            $jumpUrl = 'return';
        }
        $this->assign('url', $jumpUrl);
        $this->assign('status', $status);
        $this->display('msg');
        exit;
    }


    /**
     * @see 成功提示消息
     * @param string $msg 消息文本
     * @param string $jumpUrl 跳转路径
     */
    public function success($msg, $jumpUrl = null){$this->showMsg($msg, 'success', $jumpUrl);}

    /**
     * @see 错误提示消息
     * @param string $msg 消息文本
     * @param string $jumpUrl 跳转路径
     */
    public function error($msg, $jumpUrl = null){$this->showMsg($msg, 'error', $jumpUrl);}
    /**
     * @see 警告消息
     * @param string $msg 消息文本
     * @param string $jumpUrl 跳转路径
     */
    public function warring($msg, $jumpUrl = null){$this->showMsg($msg, 'warring',$jumpUrl);}

    /**
     * @see 取得Model实例的方法,如果实例已存在则直接返回已经存在的实例，否则实例化Model然后返回新实例化的Model
     * @param string $name
     * @return object 返回Model的实例
     */
    protected function D($name = null){
        $model = $name.'Model';
        $baseModelPath = 'Model.class.php';
        include_once($baseModelPath);
        if(isset($this->$model)){
            return $this->$model;
        }else{
            $modelPath = 'Apps/'.APP_NAME.'/Model/'.$model.'.class.php';
            if(file_exists($modelPath)){
                include_once($modelPath);
                if(!class_exists($model)){$this->$model = new Model($name);}else{$this->$model = new $model($name);}
            }else{
                $this->$model = new Model($name);
            }
            return $this->$model;
        }
    }

    /**
     * @see 取得第三方插件实例的方法
     * @param string $name 插件名称
     * @param string $params 插件实例化需要调用的参数
     * @return object 返回插件实例
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
     * @see 实例化模板类
     * @return string 返回模板实例
     */
    private function getTplObj(){
        $path = __BASE__.'/Template.class.php';
        if(file_exists($path)){
            include($path);
            if(class_exists('Template')){$this->tpl = new Template();}else{return 'template_class_bad';}
        }else{
            return 'template_class_bad';
        }
    }

    /**
     * @see 公用上传页面
     */
    public function upload(){
        if(isset($this->g['someUp'])){$this->assign('someUp', $this->g['someUp']);}
        if(isset($this->g['type'])){$this->assign('type', $this->g['type']);}
        $this->assign('callback', $this->g['callback']);
        $this->assign('iseditor', $this->g['iseditor']);
        $this->assign('uploadPath', base64_decode($this->g['uploadPath']));
        $this->display();
    }
    
    /**
     * @see 生成默认缩略图
     * @param string $file
     * @param string $path
     */
    public function setthumb($file,$path){
        $cachekey = 'cache_photo_setting';
        $cache = $this->X('Cache');
        $cache->setTimeOut(0);
        $set = $cache->get($cachekey);
        $extname = $file->getExtName($path);
        $dst_img1 = str_replace('.'.$extname,'_thumb1.'.$extname,$path);
        $dst_img2 = str_replace('.'.$extname,'_thumb2.'.$extname,$path);
        $dst_img3 = str_replace('.'.$extname,'_thumb3.'.$extname,$path);
        $file->thumbFile($path, $dst_img1, $set['width1'], $set['height1']);
        $file->thumbFile($path, $dst_img2, $set['width2'], $set['height2']);
        $file->thumbFile($path, $dst_img3, $set['width3'], $set['height3']);
    }
    
    /**
     * @see 设置允许上传的文件路径
     * @param array $limitType: array('jpg', jpeg', 'png', 'gif');
     */
    public function setLimitType($limitType){
        $this->limitType = $limitType;
    }

    /**
     * @see 公用上传方法如需使用请重写该方法
     */
    public function uploadFile(){
        $uploadPath = $this->p['uploadPath'];
        if(empty($uploadPath)){
            $uploadPath = './Data/graphic/'.date('Y').'/'.date('m').'/'.date('d');
        }
        $data = $_FILES['upload'];
        if(!empty($data['tmp_name'])){
            $md5file = md5_file($data['tmp_name']);
//            $pic = $this->D('Pics')->getOne('*', $md5file, 'md5file');
//            if(!empty($pic)){
//                die(json_encode(array('path' =>$pic['filepath'],'filename' => $pic['filename'])));
//            }
        }else{
            die(json_encode(array('path' =>'','filename' => '')));
        }
        $file = $this->X('File');
        if(empty($this->limitType)){
            $this->limitType = array('jpg', 'jpeg', 'gif', 'png', 'svg','pdf','rar','zip','doc','docx','xls','xlsx','swf');
        }
        $file->setLimitType($this->limitType);
        $file->setLimitSize(20480);
        $file->setIsRename(true);
        
        $file->setUploadPath($uploadPath);
        $d = $file->uploadFile($data);
        if(is_array($d)){
            $extname = $file->getExtName($data['name']);
            $f['originalname'] = $data['name'];
            $f['filename'] = basename($d['path']);
            $f['filetype'] = $extname;
            $f['filesize'] = $data['size'];
            $f['filepath'] = $d['path'];
            $f['ctime'] = time();
            $f['md5file'] = $md5file;
            if(in_array($extname, array('jpg', 'jpeg', 'gif', 'png','svg'))){
                $s = getimagesize($d['path']);
                $f['width'] = $s[0];
                $f['height'] = $s[1];
            }
            $this->D('Pics')->insert($f);
            die(json_encode($d));
        }else{
            die($d);
        }
    }

    /**
     * @see 下载网络文件到服务器
     */
    public function downloadFile(){
        $url = $this->p['fileurl'];
        $file = $this->X('File');
        $limitType = array('jpg', 'jpeg', 'gif', 'png','svg');
        $file->setLimitType($limitType);
        $file->setLimitSize(20480);
        $file->setIsRename(true);
        $file->setUploadPath('./Data/graphic/'.date('Y').'/'.date('m').'/'.date('d'));
        $data = $file->downloadFile($url);
        if(!empty($data['path'])){
            $md5file = md5_file($data['path']);
            $pic = $this->D('Pics')->getOne('*', $md5file, 'md5file');
            if(!empty($pic)){
                @unlink($data['path']);
                die(json_encode(array('path' =>$pic['filepath'],'filename' => $pic['filename'])));
            }
        }else{
            die(json_encode(array('path' =>'','filename' => '')));
        }
        $extname = $file->getExtName($data['filename']);
        $f['originalname'] = basename($url);
        $f['filename'] = basename($data['path']);
        $f['filetype'] = $extname;
        $f['filesize'] = filesize($data['path']);
        $f['filepath'] = $data['path'];
        $f['ctime'] = time();
        $f['md5file'] = $md5file;
        if(in_array($extname, array('jpg', 'jpeg', 'gif', 'png','svg'))){
            $s = getimagesize($data['path']);
            $f['width'] = $s[0];
            $f['height'] = $s[1];
        }
        $this->D('Pics')->insert($f);
        die(json_encode($data));
    }
    
    /**
     * @see 取得验证码
     */
    public function getVerify(){
        $code = $this->X('Code');
        $code->showImage();
    }

    /**
     * @see 页面跳转
     * @param string $url 要跳转到得页面地址
     */
    protected function jump($url){
        header('location:'.$url);
        exit;
    }

    /**
     * @see 获取一个不重复的随机字符串
     * @return string
     */
    protected function getKey(){
        return uniqid().'_'.time();
    }

    /**
     * @see 获取内存使用情况和程序运行时间的方法
     * @staticvar array $_info
     * @staticvar array $_mem
     * @param int $start 开始记录时间
     * @param int $end 结束记录时间
     * @param int $dec 
     * @return type
     */
    public function G($start, $end = '', $dec = 4) {
        static $_info = array();
        static $_mem = array();
        if(is_float($end)) { // 记录时间
            $_info[$start] = $end;
        }elseif(!empty($end)){ // 统计时间和内存使用
            if(!isset($_info[$end])) $_info[$end] = microtime(TRUE);
            if(__MEMORY_LIMIT_ON__ && $dec == 'm'){
                if(!isset($_mem[$end])) $_mem[$end] = memory_get_usage();
                return number_format(($_mem[$end] - $_mem[$start])/1024);
            }else{
                return number_format(($_info[$end] - $_info[$start]), $dec);
            }

        }else{ // 记录时间和内存使用
            $_info[$start] = microtime(TRUE);
            if(__MEMORY_LIMIT_ON__) $_mem[$start] = memory_get_usage();
        }
    }
    /**
     * @see 保存设置
     * @param array $data 要保持的设置内容
     * @param string $key 要保持的设置文件名
     * @return boolean 设置保持是否成功
     */
    protected function saveSettings($data, $key){
        $file = './Data/settings';
        if(!file_exists($file)){
            @mkdir($file);
        }
        $content = serialize($data);
        $file .= '/'.$key.'.inc';
        return @file_put_contents($file, $content);
    }
    
    /**
     * @see 读取设置
     * @param string $key 设置的文件名
     * @return array 读取到的设置的内容
     */
    protected function getSettings($key){
        $file = './Data/settings/'.$key.'.inc';
        if(!file_exists($file)){
            return array();
        }
        $data = file_get_contents($file);
        $data = unserialize($data);
        return $data;
    }
    
    /**
     * @see 删除设置
     * @param string $key 要删除的设置的文件名
     * @return boolean 删除是否成功
     */
    protected function deleteSettings($key){
        $file = './Data/settings/'.$key.'.inc';
        return @unlink($file);
    }
    
    /**
     * @see 强制变成下载链接，并下载
     * @param string $file 要下载的文件地址
     */
    protected function downFile($file){
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=".basename($file));
        readfile($file); 
    }

    /**
     * @see 默认404页面
     */
    public function nopage(){
        $this->display();
    }
    
    /**
     * @see 析构函数
     */
    public function __destruct(){
        unset($this->app);
        unset($this->a);
        unset($this->g);
        unset($this->m);
        unset($this->p);
        unset($this->r);
        unset($this->tpl);
    }
}