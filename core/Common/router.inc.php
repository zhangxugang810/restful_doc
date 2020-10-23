<?php
/**
* 系统路由配置
*
* 本程序主要用来定义路由类调用的路由的配置，定义改配置的作用是简化链接地址，有利于地址的记忆和搜索引擎的优化
* 
* @category   Common
* @package    Common
* @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
//注意：
//1.router的key必须唯一
//2.请不要使用App的名字直接作为路由的Key
return array(
    '404' => array('app' => 'Admin', 'm' => 'Index', 'a' => 'nopage'),
    'adminlogin' => array('app' => 'Admin', 'm' => 'Users', 'a' => 'login'),
    'adminindex' => array('app' => 'Admin', 'm' => 'Index', 'a' => 'index'),
    'doadminlogin' => array('app' => 'Admin', 'm' => 'Users', 'a' => 'doLogin'),
    'center' => array('app' => 'Center', 'm' => 'Index', 'a' => 'index'),
    'tester' => array('app' => 'Tester', 'm' => 'Index', 'a' => 'index'),
    'login' => array('app' => 'Tester', 'm' => 'Users', 'a' => 'login'),
    'install' => array('app' => 'Install', 'm' => 'Index', 'a' => 'index'),
    'first' => array('app' => 'Install', 'm' => 'Index', 'a' => 'stepFirst'),
    'second' => array('app' => 'Install', 'm' => 'Index', 'a' => 'stepSecond'),
    'third' => array('app' => 'Install', 'm' => 'Index', 'a' => 'stepThird'),
    'forth' => array('app' => 'Install', 'm' => 'Index', 'a' => 'stepForth'),
    'complete' => array('app' => 'Install', 'm' => 'Index', 'a' => 'complete'),
);