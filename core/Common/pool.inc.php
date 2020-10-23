<?php
/**
 * 数据库地址池，当使用读写分开的时候请吧所有的数据库连接设置在这里
 * 当使用读写分开时core/config.inc.php下的数据库设置将失效。
 */
$pools = array(
    'master' => array(
        'first' => array('host' => '192.168.1.103','user' => 'root','password' => '123456','dbname' => 'cms'),
        'second' => array('host' => '192.168.1.103','user' => 'root','password' => '123456','dbname' => 'cms'),
    ),
    
    'slave' => array(
        'first' => array('host' => '192.168.1.108','user' => 'root','password' => '123456','dbname' => 'cms'),
        'second' => array('host' => '192.168.1.192','user' => 'root','password' => '123456','dbname' => 'cms'),
        'third' => array('host' => '192.168.1.108','user' => 'root','password' => '123456','dbname' => 'cms'),
        'forth' => array('host' => '192.168.1.192','user' => 'root','password' => '123456','dbname' => 'cms'),
    )
);