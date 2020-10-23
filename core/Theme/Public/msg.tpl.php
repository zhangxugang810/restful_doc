<!DOCTYPE html>
<html>
    <head>
        <title><?php echo L($status)?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <?php echo S('sys_msg','css')?>
	<?php echo S('sys_jquery','js')?>
    </head>
    <body>
        <div class="msg-pos">
            <div class="msg-border">
                <div class="msg">
                    <h1><?php echo L($status)?></h1>
                    <dl>
                        <!--dt class="<?php echo $status?>_icon"></dt-->
                        <dd>
                            <input type="hidden" name="url" id="url" value="<?php echo $url?>" />
                            <span class="<?php echo $status?>_txt"><?php echo $msg?></span>
                            <label>系统将在<b id="totalSecond">3</b>秒后自动自动跳转到您<a onclick="window.history.go(-1);" href="javascript:void(0);">指定页面</a>，如果您没有指定页面，那么系统将自动返回<a href="./">上一页</a>，如果长时间没有反应请点击返回<a href="./">首页</a>或<a onclick="window.history.go(-1);" href="javascript:void(0);">指定页面</a></label>
                        </dd>
                        <div class="clear"></div>
                    </dl>
                </div>
            </div>
        </div>
    </body>
	<?php echo S('sys_msg','js')?>
</html>
