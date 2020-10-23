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
        <?php S('sys', 'js') ?>
        <?php S('install', 'js') ?>
    </head>
    <body class="bgcolor1">
        <div class="header">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="javascript:void(0)">安装程序</a>
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
                <li class="selected">
                    <i class="fa fa-fighter-jet"></i>
                    <a href="javascript:void(0);">账户设置</a>
                </li>
                <li>
                    <i class="fa fa-bullseye"></i>
                    <a href="javascript:void(0);">系统设置</a>
                </li>
                <!--<li>数据库安装</li>-->
                <li>
                    <i class="fa fa-bullseye"></i>
                    <a href="javascript:void(0);">完成安装</a>
                </li>
            </ul>
        </div>
        <div class="container-fluid mainbg">
            <div class="first">
                <div class="title"><i class="fa fa-tag"></i> 账户设置</div>
                <span class="tips"></span>
                <table class="updateTable">
                    <tbody>
                        <tr>
                            <th width="200">用户名：</th>
                            <td>
                                <div><input id="username" name="username" tip="用户名" class="form-control" value="" type="text" /></div>
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <th width="200">密码：</th>
                            <td>
                                <div><input id="password" name="password" tip="密码" class="form-control" value="" type="password" /></div>
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <th width="200">确认密码：</th>
                            <td>
                                <div><input id="repassword" name="repassword" tip="确认密码" class="form-control" value="" type="password" /></div>
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <th width="200">姓名：</th>
                            <td>
                                <div><input id="realname" name="realname" tip="姓名" class="form-control" value="" type="text" /></div>
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <th width="200">手机：</th>
                            <td>
                                <div><input id="mobile" name="mobile" tip="手机" class="form-control" value="" type="text" /></div>
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <th width="200">E-mail：</th>
                            <td>
                                <div><input id="email" name="email" tip="E-mail" class="form-control" value="" type="text" /></div>
                                <span></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pbtm"></div>
            </div>
        </div>
        <div class="container-fluid footer">
            <div class="btn-group btn-footer">
                <a href="<?php echo U('Install/Index/stepFirst') ?>" class="btn btn-sm btn-default"> 上一步（Previous）</a>
                <a saveurl="<?php echo U('Install/Index/saveUser');?>" href="javascript:void(0);" class="btn btn-sm btn-info goThird"> 下一步（Next） </a>
            </div>
        </div>
    </body>
</html>
