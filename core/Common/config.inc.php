<?php
/**
* 系统常量配置
*
* 本程序主要作用定义系统共用的常量配置
* 
* @category   Common
* @package    Common
* @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
//站点默认配置
define('__SITENAME__', 'http://'.$_SERVER['SERVER_NAME'].'/'); //站点URL根路径
define('__CORE__', './core'); //框架核心目录
define('__APP__', './Apps'); //应用目录
define('__STATIC_PATH__','./static');//用户静态文件目录
define('__LIB__', __CORE__.'/Lib'); //第三方库目录
define('__BASE__', __CORE__.'/Base');//核心文件库目录
define('__MODEL__', './Model');//数据库Model目录
define('__DEFAULT_APP__','Tester');//默认访问的App
define('__DEFAULT_MODEL__','Index');//默认访问的App
define('__DEFAULT_ACTION__','index');//默认访问的App
define('__SERVER_PATH__', $_SERVER['DOCUMENT_ROOT']);//站点服务器目录

define('__DEBUG__', true); //是否开启调试模式,true：显示异常详细信息，false：只显示错误代码
define('__LOG_PATH__', __SERVER_PATH__.'/Data/debug');
define('__OFFICIAL_WEBSITE__', 'http://www.inkphp.com');

//echo __API_LOCAL_URL;exit;
//数据库配置开始
define('__DB_HOST__','test_mysql_intra_master.zichan360.com');
define('__DB_USER__','zichan360');
define('__DB_PWD__','Rootzichan360');
define('__DB_NAME__','zichan360');
define('__DB_TYPE__','mysqli');
define('__DB_PREFIX__','');
define('__DB_PCONNECT__', false);
define('__ALLOW_POST_APP__','Api');//多个APP用逗号隔开如：Api,Apii(这里是允许外站POST数据到本站的APP列表)
//数据库配置结束

/**
 * 主从数据库使用方法：
 * 1.设置__USE_DB_POOL__为 true；
 * 2.到core/Common/pool.inc.php中设置master下的主数据库和salve数据库的链接信息
 * 3.开启Memcache服务器，请先确认您安装了Memcache的相关服务和组件
 * 读写分开还没有写故障机制：如当一个服务器发生故障时会转向另一服务器
 */
define('__USE_DB_POOL__', false); //数据库是否使用读写分开和负载均衡,启用这个变量时请先启用Memcache，true：读写分开，false：不分开当这个变量为True时上面的数据库配置可以不写或者删除或者注释（不建议删除），这时候数据库的设置请在pool.inc.php中设置
//define('__USE_CACHE__',true);//是否使用缓存技术
define('__USE_MEMCACHE__', false);//缓存技术是否使用内存缓存
define('__USE_ROUTER__', true); //是否使用伪静态路由
define('__CACHE_PATH__','./Data');//缓存文件目录
define('__FILE_DB_PATH__','./Data');//文件数据库文件目录
define('__CACHE_FORWARD_FILE__', true);//前端文件如JS，CSS，是否需要浏览器缓存，需要为true，不需要为false
define('__LOAD_FILE__', true); //配置CSS和JS如何加载，true则采用文件方式加载，false采用代码方式加载，在此变量设置为false时请注意，__CACHE_FORWARD_FILE__必须设置为true；否则将无法CSS和JS文件


define('__ENCODE__', false); //接口返回数据是否加密
define('__ENCODE_KEY__','default'); //接口返回数据的加密密钥

define('__MEMORY_LIMIT_ON__', false); //统计内存使用需要时设置为true即可。
