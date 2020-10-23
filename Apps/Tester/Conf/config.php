<?php
return array(
//    'environment' => array(
////        'development' => array(
////            'name' => '开发环境',
////            'url' => 'http://dev.trade-mall.ikang.com'
////        ),
//        
//        'testing' => array(
//            'name' => '测试环境',
//            'url' => 'http://test.trade-mall.ikang.com'
//        ),
//        
//        'uat' => array(
//            'name' => 'UAT环境',
//            'url' => 'http://uat.trade-mall.ikang.com'
//        ),
//        
//        'production' => array(
//            'name' => '生产环境',
//            'url' => 'http://trade-mall.ikang.com'
//        )
//    ),
    
//    'defaultGetParams' => array(
//        array('name' => 'psid', 'displayName' => 'PSID', 'description', 'required' => true),
//    ),
    
    'nologins' => array('Users/login', 'Users/doLogin', 'Users/doExit', 'Users/forgot', 'Users/doForgot', 'Users/changePwd', 'Users/doChangePwd', 'Index/verify'),
    'noLimits' => array('Items/itemList', 'Items/itemAdd', 'Items/doItemAdd', 'Items/itemUpdate', 'Items/doItemUpdate', 'Items/doItemDelete', 'Settings/setting', 'Settings/doSetting', 'Users/userList', 'Users/userAdd', 'Users/doUserAdd', 'Users/userUpdate', 'Users/doUserUpdate', 'Users/doUserUpdate', 'Users/doUserUpdate', 'Users/doUserDelete'),
    'notAdmins' => array(
        'Users' => array('userList', 'doUserAdd', 'userAdd', 'userUpdate', 'doUserUpdate', 'doUserDelete'),
        'Group' => array('groupList', 'doGroupAdd', 'groupAdd', 'groupUpdate', 'doGroupUpdate', 'doGroupDelete'),
        'Settings' => array('setting', 'doSetting'),
        'Items' => array('itemList', 'itemAdd', 'doItemAdd', 'itemUpdate', 'doItemUpdate', 'doItemDelete'),
    ),
);