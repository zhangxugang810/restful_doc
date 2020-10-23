<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
        <?php S('bootstrap.min') ?>
        <?php S('font-awesome.min') ?>
        <?php S('style') ?>
        <?php S('install') ?>
        <?php S('jquery.1.11.3', 'js') ?>
        <?php S('bootstrap.min', 'js') ?>
        <?php S('install', 'js') ?>
    </head>
    <body class="bgcolor1">
        <div class="header">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="javascript:void(0)"><img height="20" src="<?php echo IMAGE_PATH. '/logo.png'?>" /></a>
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
            </nav>
        </div>
        <div class="step">
            <ul>
                <li>
                    <i class="fa fa-bullseye"></i>
                    <a href="javascript:void(0);">安装协议</a>
                </li>
                <li>
                    <i class="fa fa-bullseye"></i>
                    <a href="javascript:void(0);">环境检查</a>
                </li>
                <li>
                    <i class="fa fa-bullseye"></i>
                    <a href="javascript:void(0);">账户设置</a>
                </li>
                <li>
                    <i class="fa fa-bullseye"></i>
                    <a href="javascript:void(0);">系统设置</a>
                </li>
                <!--<li>数据库安装</li>-->
                <li class="selected">
                    <i class="fa fa-fighter-jet"></i>
                    <a href="javascript:void(0);">完成安装</a>
                </li>
            </ul>
        </div>
        <div class="container-fluid mainbg">
            <div class="first">
                <div class="title"><i class="fa fa-tag"></i> 安装完成</div>
                    <h1>安装完成</h1>
                <div>
                    恭喜您已经成功安装了INK文档系统，你现在可以点击下面的进入系统就可以开始使用了。
                </div>
                <div>
                    <a href="/">进入系统</a>
                </div>
                <div class="pbtm"></div>
            </div>
        </div>
        <div class="container-fluid footer">
            
        </div>
    </body>
</html>
