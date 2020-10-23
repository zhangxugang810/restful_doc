<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>用户登录 - <?php echo $sysname?></title>
    <?php S('bootstrap.min') ?>
    <?php S('font-awesome.min') ?>
    <?php S('style') ?>
    <?php S('login') ?>
    <?php S('jquery.1.11.3','js') ?>
    <?php S('jquery.jsonview','js') ?>
    <?php S('jquery.cookie','js') ?>
    <?php S('sys','js') ?>
    <?php S('login','js') ?>
    <?php S('tester','js') ?>
    <?php S('bootstrap.min','js') ?>
</head>
<body class="<?php echo empty($bgclass) ? 'bgcolor1' : $bgclass;?>">
    <div class="loginbox">
        <div class="welcome">
            <div class="logo"><img src="<?php echo IMAGE_PATH.'/logo.png';?>" /></div>
            <div class="txt"><span>WELCOME</span> TO RESTFUL DOCUMENT</div>
        </div>
        <div class="login">
            <div id="loginMsg" class="loginMsg"></div>
            <div class="loginBody">
                <div class="input-group">
                    <input type="text" id="username" name="username" class="form-control" placeholder="请输入登录用户名" />
                    <div class="input-group-addon"><i class="fa fa-user"></i></div>
                </div>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="请输入登录密码" />
                    <div class="input-group-addon"><i class="fa fa-key"></i></div>
                </div>
                <div class="input-group">
                    <input type="text" id="verifyCode" name="verifyCode" class="form-control" placeholder="请您输入图片验证码" />
                    <a id="changeVerify" class="input-group-addon"><img src="<?php echo U('Tester/Index/verify');?>" /></a>
                </div>
            </div>
            <div class="loginRemember">
                <div class="remember">
                    <input type="hidden" name="remember" id="remember" value="1" />
                    <a href="javascript:void(0);">
                        <i class="fa fa-square"></i>
                        <i class="fa fa-check-square"></i> 记住我
                    </a>
                </div>
                <div class="forgot"><a href="<?php echo U('Tester/Users/forgot');?>">忘记密码？</a></div>
            </div>
            
            <div class="loginBtns">
                <button id="loginBtn" type="button" class="btn btn-lg btn-greensea form-control">确认登录</button>
            </div>
            <div class="backgrounds">
                <ul>
                    <li class="bgimg1"></li>
                    <li class="bgimg2"></li>
                    <li class="bgimg3"></li>
                    <li class="bgimg4"></li>
                    <li class="bgimg5"></li>
                    <li class="bgimg6"></li>
                </ul>
                <ul>
                    <li class="bgcolor1"></li>
                    <li class="bgcolor2"></li>
                    <li class="bgcolor3"></li>
                    <li class="bgcolor4"></li>
                    <li class="bgcolor5"></li>
                    <li class="bgcolor6"></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            <h6>©2017 - 2027 INKPHP. 保留所有权利。</h6>
        </div>
    </div>
</body>
</html>
