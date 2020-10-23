<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>HTTP：404</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <?php S('sys_nopage') ?>
        <?php S('sys_jquery','js') ?>
        <?php S('sys_exception','js') ?>
    </head>
    <body style="height:100%;background:url(<?php echo $bg['backimg']?>);background-size: cover;filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $bg['backimg']?>',sizingMethod='scale');">
        <div class="box404">
            <div class="left"><h1 class="text404">404</h1></div>
            <div class="right">
                <h3>Sorry，找不到页面</h3>
                <p>我们无法找到您需要的页面,你可以做下面的操作： <br> <strong><a href="index.html">返回首页</a></strong> 或者尝试下面的搜索栏. </p>
            </div>
        </div>
        <div class="boxseach">
            <form class="form-inline form-search" method="get" action="index.html" role="form">
                <div><input type="search" id="keyword" name="keyword" class="form-control" placeholder="请输入您要搜索的关键字"></div>
                <span><button class="btn btn-primary" type="submit">搜索</button></span>
            </form>
        </div>
    </body>
</html>