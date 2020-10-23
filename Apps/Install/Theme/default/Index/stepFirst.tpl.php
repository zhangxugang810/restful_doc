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
                <li class="selected">
                    <i class="fa fa-fighter-jet"></i>
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
                <li>
                    <i class="fa fa-bullseye"></i>
                    <a href="javascript:void(0);">完成安装</a>
                </li>
            </ul>
        </div>
        <div class="container-fluid mainbg">
            <div class="first">
                <div class="title"><i class="fa fa-tag"></i> 环境检查</div>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="200">项目</th>
                            <th width="300">所需配置</th>
                            <th>当前配置</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($envirs as $k => $v){?>
                        <tr>
                            <td><?php echo $v[0]?></td>
                            <td><?php echo implode('/', $v[1]);?></td>
                            <td><input type="hidden" value="<?php echo $v[3]?>" /><?php echo $v[3] ? '<span class="text-success"><i class="fa fa-check"></i> '.$v[2].'</span>' : '<span class="text-danger"><i class="fa fa-remove"></i> '.$v[2].'</span>';?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <div class="title"><i class="fa fa-tag"></i> 目录/文件权限检查</div>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="200">目录文件</th>
                            <th width="300">所需状态</th>
                            <th>当前状态</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rdirs as $k => $v){?>
                        <tr>
                            <td><?php echo $v[0]?></td>
                            <td><?php echo $v[1]?></td>
                            <td><?php echo $v[2]?></td>
                        </tr>
                        <?php }?>
                        <?php foreach($wdirs as $k => $va){?>
                        <tr>
                            <td><?php echo $va[0]?></td>
                            <td><?php echo $va[1]?></td>
                            <td><?php echo $va[2]?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <div class="title"><i class="fa fa-tag"></i> PHP组件依赖检查</div>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="200">组件名称</th>
                            <th width="300">所需状态</th>
                            <th>当前状态</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($assemblies as $k => $vs){?>
                        <tr>
                            <td><?php echo $vs[0]?></td>
                            <td><?php echo $vs[1]?></td>
                            <td><?php echo $vs[2]?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                <div class="pbtm"></div>
            </div>
        </div>
        <div class="container-fluid footer">
            <div class="btn-group btn-footer">
                <a href="<?php echo U('Install/Index/index') ?>" class="btn btn-sm btn-default"> 上一步（Previous）</a>
                <a href="javascript:void(0);" class="btn btn-sm btn-info nextStep" disabled="disabled"> 下一步（Next） </a>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        checkEnvirs();
    </script>
</html>
