<?php
abstract class Core {
    /**
     * 系统主函数用来按照系统指定的app，m,a去找到指定的组中的指定的控制器中的指定Action来运行
     * @param type $app 应用名称
     * @param type $m Controller名称
     * @param type $a Acton名称
     * @param type $data 其他参数
     */
    static private $g;
    static private $configs;

    /**
     * 设置get参数
     * @param type $g
     */
    static public function setParams($g) {
        self::$g = $g;
    }

    /**
     * 核心运行方法
     * @param type $app
     * @param type $m
     * @param type $a
     * @param type $data
     */
    static public function run($app = 'Home', $m = 'Index', $a = 'index', $data) {
        $controller = $m . 'Controller';
        if ($app == '') {
            $path = __BASE__ . '/' . $controller . '.class.php';
        } else {
            $path = './Apps/' . $app . '/Controller/' . $controller . '.class.php';
            if (!file_exists($path)) {
                throw new InkException(L('controller_is_not_exists_file').'：'.$path, 110060001);
            }
        }
        $configPath = './Apps/' . APP_NAME . '/Conf/config.php';
        if(file_exists($configPath)){
            self::$configs = include($configPath);
        }else{
            throw new InkException(L('config_is_not_exists_file').'：'.$configPath, 110090002);
        }
        try{
            $x = new $controller($data);
        }catch(Exception $e){
            throw new InkException(L('controller_is_not_exists').'：'.$controller, 110060002);
        }
        if (empty($x->tplVars)) {
            if(method_exists($x, 'init')){$x->init();}
            if(method_exists($x, 'init')){
                $x->$a();
            }else{
                throw new InkException(L('action_is_not_exists').'：'.$m.'Controller->'.$a.'()', 110060003);
            }
        }
    }

    /**
     * 自动生成以上要生成的所有东西
     */
    static public function checkDir() {
        return false;//暂时未使用
        $appPath = './Apps/'.APP_NAME;
        $lockPath = $appPath.'/Data/create.lock';
        if(!file_exists($lockPath)){
            if(!file_exists($appPath)){die('您请求的应用：'.APP_NAME.'不存在');}
            $paths = array($appPath.'/Conf', $appPath.'/Controller', $appPath.'/Lang', $appPath.'/Model', $appPath.'/Theme', $appPath.'/static', $appPath.'/Lang/zh_cn', $appPath.'/Theme/default', $appPath.'/Theme/default/Index', $appPath.'/Theme/default/Public', $appPath.'/static/default', $appPath.'/static/default/default', $appPath.'/static/default/default/css', $appPath.'/static/default/default/images', $appPath.'/static/default/js');
            foreach($paths as $key => $path){if(!file_exists($path)){@mkdir($path);}}
            $files = array($appPath.'/Conf/config.inc.php', $appPath.'/Conf/config.php', $appPath.'/Controller/IndexController.class.php', $appPath.'/Controller/PublicController.class.php', $appPath.'/Lang/zh_cn/language.php', $appPath.'/Theme/default/Index/index.tpl.php', $appPath.'/static/default/default/css/style.css', $appPath.'/static/default/js/index.js');
            foreach($files as $key => $file){
                if(!file_exists($file)){
                    $basename = basename($file);
                    if($basename == 'config.inc.php' && !file_exists($file)){$str = '<?php'."\n".'define(\'__DEFAULT_THEME__\',\'default\'); //默认站点样式'."\n".'define(\'__DEFAULT_STYLE__\',\'default\'); //默认站点样式'."\n".'define(\'__DEFAULT_LANG__\', \'zh_cn\');//默认网站语言'."\n".'define(\'__CREATE_HTML__\', false); //是否生成静态文件';}
                    if($basename == 'config.php' && !file_exists($file)){$str = '<?php'."\n".'return array();'; }
                    if($basename == 'IndexController.class.php' && !file_exists($file)){$str = '<?php'."\n".'class IndexController extends PublicController{'."\n".'    /**'."\n".'     * @access private'."\n".'     * @name abc'."\n".'     * @see 构造函数'."\n".'     */'."\n".'    public function __construct($data){'."\n".'        parent::__construct($data);'."\n".'    }'."\n\n".'    /**'."\n".'     * @access public'."\n".'     * @name index'."\n".'     * @see 首页'."\n".'     * @param $data：数组'."\n".'     * @return 页面'."\n".'     */'."\n".'    public function index(){'."\n".'        $this->display();'."\n".'    }'."\n".'}';}
                    if($basename == 'PublicController.class.php' && !file_exists($file)){$str = '<?php'."\n".'class PublicController extends Controller{'."\n".'    /**'."\n".'     * @access private'."\n".'     * @name abc'."\n".'     * @see 构造函数'."\n".'     */'."\n".'    public function __construct($data){'."\n".'        parent::__construct($data);'."\n".'    }'."\n".'}';}
                    if($basename == 'language.php' && !file_exists($file)){$str = '<?php'."\n".'return array();';}
                    if($basename == 'index.tpl.php' && !file_exists($file)){$str = '<!DOCTYPE html>'."\n".'<html lang="en">'."\n".'<head>'."\n".'<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>'."\n".'<title>Welcome to INKCMS！</title>'."\n".'<meta name="keywords" content=""/>'."\n".'<meta name="description" content=""/>'."\n".'<?php S(\'style\')?>'."\n".'<?php S(\'index\', \'js\')?>'."\n".'</head>'."\n".'<body>'."\n".'<div>Welcome to INKCMS！</div>'."\n".'</body>'."\n".'</html>';}
                    if($basename == 'style.css' && !file_exists($file)){$str = 'html, body{margin:0; padding:0;height:100%;font-family:\'Microsoft Yahei\';}'."\n".'body{font-size:14px;}';}
                    if($basename == 'index.js' && !file_exists($file)){$str = '$(document).ready(function(){});';}
                    file_put_contents($file, $str);
                }
            }
            file_put_contents($appPath.'/Data/create.lock', 'true');
        }
    }

    /**
     * 检查过滤字符串的函数，进行防注入处理
     * @param type $data 要过滤的参数可以使数组或字符串
     * @return type 数组或字符串
     */
    static public function checkStr($data) {
        if (is_array($data)) {
            foreach ((array) $data as $k => $v){if ($k == 'submit') {unset($data[$k]);}else{$data[$k] = self::check($v);}}
            return $data;
        }
        return self::check($data);
    }

    /**
     * 处理字符串，防注入处理
     * @param type $text 要过滤的字符串
     * @return type 过滤之后的字符串
     */
    static public function check($text) {
        if(is_numeric($text)){
            $text = (string)floatval($text);
        }else{
            if(is_array($text)){
                $text = self::checkArray($text);
            }else{
                $text = self::get_str($text);
            }
        }
        return $text;
    }
    
    /**
     * @name 数组类型检查
     * @param type $data，要检查的数组
     * @return type 返回检查后的数组
     */
    static function checkArray($data){
        foreach($data as $key => $value){
            if(is_numeric($value)){
                $data[$key] = (string)floatval($value);
            }else{
                if(is_array($value)){
                    $data[$key] = self::checkArray($value);
                }else{
                    $data[$key] = self::get_str($value);
                }
            }
        }
        return $data;
    }

    //字符串型过滤函数//不在这里处理防SQL注入问题了，防SQL注入问题，应该使用Mysqli的stmt来解决就可以了，这里只需要防止HTML代码和Javascript代码注入即可。
    static function get_str($string) {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        if(!get_magic_quotes_gpc()){$string = addslashes($string);}
        return $string;
    }
    
    static function unGetStr($str){
        for($i = 0; $i < 10; $i++){
            $str = str_replace('&amp;', '&', $str);
        }
        $str = str_replace('&#039;', '\'', $str);
        $str = stripcslashes($str);
        $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
        return $str;
    }

    /**
     * 取得POST提交的数据，如果不传入参数则返回$_POST数组
     * @param type $name 提交表单的名称
     * @return type 提交表单的值
     */
    static public function P($name = null) {
        self::checkReferer();
        if(!empty($name)){return self::checkStr($_POST[$name]);}else{return self::checkStr($_POST);}
    }

    /**
     * 不允许远程提交POST数据 GET提交的防远程提交请用户自己斟酌，请注意您不能屏蔽所有的外部GET提交，否则搜索引擎将无法找到您的页面。
     */
    static public function checkReferer() {
        if(!empty($_POST)) {
            $apps = explode(',', __ALLOW_POST_APP__);
            if(!in_array(APP_NAME, $apps)) {
                $thisDomain = $_SERVER['SERVER_NAME'];
                $referer = str_replace('http://', '', $_SERVER['HTTP_REFERER']);
                $arr = explode('/', $referer);
                $refererDomail = $arr[0];
                if($thisDomain != $refererDomail && empty($_FILES)){
                    throw new InkException(L('you_can_not_remotely_want_the_site_to_submit_data'), 110120001);
                }
            }
        }
    }

    /**
     * 取得GET提交的数据，如果不传入参数则返回$_GET数组
     * @param type $name 提交参数的名称
     * @return type 提交参数的值
     */
    static public function G($name = null) {
        if(!empty($name)){return self::checkStr($_GET[$name]);}else{return self::checkStr($_GET);}
    }

    /**
     * 取得REQUEST提交的数据，如果不传入参数则返回$_REQUEST数组
     * @param type $name 提交参数的名称
     * @return type 提交参数的值
     */
    static public function R($name = null) {
        if(!empty($name)){return self::checkStr($_REQUEST[$name]);}else{return self::checkStr($_REQUEST);}
    }

    /**
     * 获取后台全部设置
     * @param strint $_key
     * @return strint
     */
    static public function getAdminConfig($_key) {
        $file = './Data/config/' . $_key . '.jpg';
        $text = file_get_contents($file);
        return $text;
    }

    /**
     * 获取后台指定设置
     * @param strint $name
     * @return string
     */
    static public function getConfig($name) {
        if(isset(self::$configs[$name])){return self::$configs[$name];}
        return '';
    }

}
