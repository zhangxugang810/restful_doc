<?php
/**
* 系统运行前处理文件
*
* 本程序主要作用在系统进入Controller前所做的工作
* 
* @category   Common
* @package    Common
* @copyright  Copyright (c) inkPHP工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
session_start();
date_default_timezone_set('Asia/Shanghai');
set_exception_handler('handle_exception');// 自定义异常函数
set_error_handler('handle_error');// 自定义错误函数
include(__BASE__.'/Core.class.php');
include(__BASE__.'/Controller.class.php');
include(__BASE__.'/InkException.class.php');
if(__USE_ROUTER__){//如果使用Rewrite规则将执行这段代码
    $url = checkUrl($_SERVER['REQUEST_URI']);
    include(__BASE__.'/Router.class.php');
    $router = new Router($url);
    $g = $router->getRouter();
    $app = $g['app'];
    $m = $g['m'];
    $a = $g['a'];
    define('APP_NAME', $app);
    define('MODEL_NAME', $m);
    define('ACTION_NAME', $a);
    Core::setParams($g);
    $p = Core::P();
    $g = array_merge($g, Core::G());
    $r = array_merge($p, $g);
}else{//如果您不使用rewrite规则则执行这个代码
    $p = Core::P();
    $g = Core::G();
    $r = array_merge($p, $g);
    $app = $g['app'];
    $app = !empty($app)? $app:__DEFAULT_APP__;
    $m = $g['m'];
    $m = !empty($m)? $m:'Index';
    $a = $g['a'];
    $a = !empty($a)? $a:'index';
    define('APP_NAME', $app);
    define('MODEL_NAME', $m);
    define('ACTION_NAME', $a);
}
spl_autoload_register('inkAutoload');
//Core::checkDir();
unset($g['app']);
unset($g['m']);
unset($g['a']);
unset($r['app']);
unset($r['m']);
unset($r['a']);
$data = array('g' => $g, 'r' => $r, 'p' => $p);
unset($_POST);
unset($_GET);
unset($_REQUEST);

//加载APP配置
$configFile = './Apps/'.APP_NAME.'/Conf/config.inc.php';

if(file_exists($configFile)){
    include($configFile);
}else{
    throw new InkException('系统找不到APP配置文件：'.$configFile, 110090001);
}
if(__USE_DB_POOL__){
    include_once(__COMMON__.'/pool.inc.php');
    define('__POOLS__', serialize($pools));
}
define('IMAGE_PATH','/Apps/'.APP_NAME.'/static/'.__DEFAULT_THEME__.'/'.__DEFAULT_STYLE__.'/images');