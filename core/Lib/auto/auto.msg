<!DOCTYPE html>
<html>
    <head>
        <title><{$status|L}></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <{'msg'|S:'css'}>
        <{'jquery'|S:'js'}>
    </head>
    <body>
        <div class="msg-pos">
            <div class="msg-border">
                <div class="msg">
                    <h1><{$status|L}></h1>
                    <dl>
                        <dt class="<{$status}>_icon"></dt>
                        <dd>
                            <span class="<{$status}>_txt"><{$msg}></span>
                            <label>系统将在<b id="totalSecond">3</b>秒后自动返回<a onclick="window.history.go(-1);" href="javascript:void(0);">上一页</a>，也有可能会返回<a href="./">首页</a>，如果长时间没有反应轻点击返回<a href="./">首页</a>或<a onclick="window.history.go(-1);" href="javascript:void(0);">上一页</a></label>
                        </dd>
                        <div class="clear"></div>
                    </dl>
                </div>
            </div>
        </div>
    </body>
    <{'msg'|S:'js'}>
</html>
