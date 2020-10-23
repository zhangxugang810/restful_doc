<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $sysname ?> - REST接口文档系统</title>
        <?php S('bootstrap.min') ?>
        <?php S('font-awesome.min') ?>
        <?php S('style') ?>
        <?php S('jquery.jsonview') ?>
        <?php S('help') ?>
        <?php S('jquery.1.11.3', 'js') ?>
        <?php S('jquery.jsonview', 'js') ?>
        <?php S('jquery.cookie', 'js') ?>
        <?php S('bootstrap.min', 'js') ?>
        <?php S('sys', 'js') ?>
        <?php S('tester','js') ?>
        <?php S('help', 'js') ?>
    </head>
    <body class="<?php echo empty($bgclass) ? 'bgcolor1' : $bgclass;?>">
        <div class="page-header navbar navbar-fixed-top">
            <div class="page-header-inner ">
                <div class="page-logo"> <a href="http://www.inkphp.cn" target="_blank"><img class="logo-default" height="16" alt="<?php echo $sysname ?> - REST接口文档系统" src="<?= IMAGE_PATH ?>/logo.png"></a></div>
                <div class="library-menu"> <span class="one">-</span> <span class="two">-</span> <span class="three">-</span> </div>
                <div class="top-menu">
                    <ul class="nav navbar-nav pull-right">
                        <?php /*<li class="dropdown">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle count-info" aria-expanded="false">
                                <i class="fa fa-envelope"></i>
                                <span class="badge badge-info">6</span>
                            </a>
                                                        <ul class="dropdown-menu dropdown-menu-default">
                                                            <li><a id="unit_menu" class="goUrl" href="javascript:void(0);" url="<?php //echo U('Tester/Unit/index')    ?>">单元测试</a></li>
                                                            <li><a class="goUrl" href="javascript:void(0);" url="<?php //echo U('Tester/Index/main')    ?>">代码检查</a></li>
                                                        </ul>
                        </li>*/?>
                        <li class="dropdown dropdown-user">
                            <a href="#" data-toggle="dropdown" class="dropdown-toggle count-info">
                                <?php /*<!--<i class="fa fa-cog"></i> */ ?>
                                <span class="username">系统设置</span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default <?php echo empty($bgclass) ? 'bgcolor1' : $bgclass;?>">
                                <li><a class="goUrl" href="javascript:void(0);" url="<?php echo U('Tester/Index/main') ?>"><i class="fa fa-home"></i> 首页</a></li>
                                <li class="divider"> </li>
                                <li><a class="goUrl" href="javascript:void(0);" url="<?php echo U('Tester/Users/userList') ?>"><i class="fa fa-user"></i> 用户</a></li>
                                <?php /*<li><a class="goUrl" href="javascript:void(0);" url="<?php //echo U('Tester/Groups/groupList') ?>"><i class="fa fa-group"></i> 用户组</a></li>-->
                                <!--<li class="divider"> </li>*/?>
                                <li><a class="goUrl" href="javascript:void(0);" url="<?php echo U('Tester/Items/itemList') ?>"><i class="fa fa-cogs"></i> 项目设置</a></li>
                                <li><a class="goUrl" href="javascript:void(0);" url="<?php echo U('Tester/Settings/setting') ?>"><i class="fa fa-cog"></i> 系统设置</a></li>
                            </ul>
                        </li>
                        <li class="dropdown dropdown-user">
                            <a data-close-others="true" data-hover="dropdown" data-toggle="dropdown" class="dropdown-toggle" href="javascript:;">
                                <?php /*<img src="<?= IMAGE_PATH ?>/a10.jpg" class="img-circle" alt="">*/ ?>
                                <span class="username username-hide-on-mobile"><?= $user['realname'] ?></span>
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-default <?php echo empty($bgclass) ? 'bgcolor1' : $bgclass;?>">
                                <?php /*<li> <a href="profile.html"> <i class="fa fa-user-o"></i> 我的信息 </a></li>
                                <li> <a href="profile.html"> <i class="fa fa-key"></i> 修改密码 </a></li>*/
                                ?>
                                <li><a class="goUrl" id="help" url="<?php echo U('Tester/Index/syshelp'); ?>" href="javascript:void(0);"><i class="fa fa-file-text-o"></i> 系统使用说明</a></li>
                                <li><a class="goUrl" id="help" url="<?php echo U('Tester/Index/help'); ?>" href="javascript:void(0);"><i class="fa fa-file-text-o"></i> 代码注释标准</a></li>
                                <?php /*<li><a class="goUrl" id="flow" url="<?php //echo U('Tester/Index/flow'); ?>" href="javascript:void(0);"><i class="fa fa-tasks"></i> 调用流程</a></li>*/?>
                                <li class="divider"> </li>
                                <li><a class="goUrl" href="javascript:void(0);" url="<?php echo U('Tester/Index/about'); ?>"><i class="fa fa-id-card-o"></i> 关于</a></li>
                                <li><a href="<?php echo U('Tester/Users/doExit'); ?>"><i class="fa fa-power-off"></i> 退出</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="tool_flag" class="tool_flag" title="调试工具"><i class="fa fa-chevron-down"></i></div>
        </div>
        <div class="page-container">
            <div class="page-sidebar-wrapper">
                <div class="page-sidebar">
                    <ul class="page-sidebar-menu  page-header-fixed ">
                        <li class="sidebar-search-wrapper">
                            <div class="input-group selectItme">
                                <input id="thisItem" type="text" class="form-control dropdown-toggle btn-black-o" placeholder="选择项目" readonly="readonly" aria-describedby="basic-addon2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" />
                                <span class="input-group-addon dropdown-toggle btn-black-o border" id="basic-addon2" aria-describedby="basic-addon2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-chevron-down"></i></span>
                                <ul class="dropdown-menu <?php echo empty($bgclass) ? 'bgcolor1' : $bgclass;?>">
                                    <?php foreach ((array) $items as $key => $it) { ?>
                                    <li itemid="<?php echo $it['itemtag'];?>"><a href="javascript:void(0);"><?php echo $it['itemname'] ?></a></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item active">
                            <ul class="catalog" id="apilist"></ul>
                            <input type="hidden" id="controller" value=""/>
                            <input type="hidden" id="funcname" value=""/>
                            <input type="hidden" id="controllerPath" value=""/>
                            <input type="hidden" id="itemid" value=""/>
                        </li>
                    </ul>
                </div>
                <div class="page-content">
                    <div id="r" class="r"></div>
                    <div id="tool" class="tool <?php echo empty($bgclass) ? 'bgcolor1' : $bgclass;?>"></div>
                </div>
            </div>
            <div class="footer">
                <div class="copyright grid-full">
                    <h6>&copy; 2017 - 2027 <a href="http://www.inkphp.net" target="_blank">INKPHP</a>. 保留所有权利。</h6>
                </div>
                <div class="bottom_tools">
                    <div class="qr_tool" title="更新系统缓存"><i class="fa fa-refresh"></i></div>
                    <?php /*<a id="feedback" href="http://www.inkphp.com/" target="_blank" title="意见反馈"><i class="icon icon-comment"></i></a>*/?>
                    <div class="scrollUp" id="scrollUp" title="飞回顶部"><i class="fa fa-arrow-up"></i></div>
                    <?php /*<img class="qr_img" width="173" width="234" src="<?php //echo IMAGE_PATH ?>/0f8ae4f29290b0das.png">*/?>
                </div>
                <div class="backgrounds">
                    <ul>
                        <li class="bgimg0"></li>
                        <li class="bgimg1"></li>
                        <li class="bgimg2"></li>
                        <li class="bgimg3"></li>
                        <li class="bgimg4"></li>
                        <li class="bgimg5"></li>
                        <li class="bgimg6"></li>
                        <li class="bgcolor1"></li>
                        <li class="bgcolor2"></li>
                        <li class="bgcolor3"></li>
                        <li class="bgcolor4"></li>
                        <li class="bgcolor5"></li>
                        <li class="bgcolor6"></li>
                    </ul>
                </div>
            </div>
        </div>
        <script type="text/javascript">returnUrl(U('Tester/Index/main'));</script>
    </body>
</html>
