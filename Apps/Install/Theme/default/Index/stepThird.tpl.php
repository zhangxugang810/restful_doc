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
                        <a class="navbar-brand text-center" href="javascript:void(0)"><img height="20" src="<?php echo IMAGE_PATH. '/logo.png'?>" /></a>
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
                <li class="selected">
                    <i class="fa fa-fighter-jet"></i>
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
                            <th width="200">系统显示名称：</th>
                            <td>
                                <div><input id="sysname" name="sysname" class="form-control" tip="系统显示名称" value="" type="text" /></div>
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <th width="200">邮件服务器地址：</th>
                            <td>
                                <div><input id="mailServerHost" name="mailServerHost" tip="邮件服务器地址" class="form-control" value="" type="text" /></div>
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <th width="200">邮件服务器用户名：</th>
                            <td>
                                <div><input id="mailServerUsername" name="mailServerUsername" tip="邮件服务器用户名" class="form-control" value="" type="text" /></div>
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <th width="200">邮件服务器密码：</th>
                            <td>
                                <div><input id="mailServerPassword" name="mailServerPassword" tip="邮件服务器密码" class="form-control" value="" type="text" /></div>
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <th width="200">默认发件编码：</th>
                            <td>
                                <div><input id="mailCharSet" name="mailCharSet" tip="默认发件编码" class="form-control" value="" type="text" /></div>
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <th width="200">默认发件人姓名：</th>
                            <td>
                                <div><input id="mailFromName" name="mailFromName" tip="默认发件人姓名" class="form-control" value="" type="text" /></div>
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
                <a href="<?php echo U('Install/Index/stepThird') ?>" class="btn btn-sm btn-default"> 上一步（Previous）</a>
                <a completeUrl="<?php echo U('Install/Index/saveConfigure')?>" href="javascript:void(0);" class="btn btn-sm btn-info complete"> 完成安装（Complete） </a>
            </div>
        </div>
    </body>
</html>
