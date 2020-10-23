<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>忘记密码 - <?php echo $sysname?></title>
    <?php S('bootstrap.min') ?>
    <?php S('font-awesome.min') ?>
    <?php S('style') ?>
    <?php S('login') ?>
    <?php S('jquery.1.11.3','js') ?>
    <?php S('jquery.jsonview','js') ?>
    <?php S('sys','js') ?>
    <?php S('login','js') ?>
    <?php S('tester','js') ?>
    <?php S('bootstrap.min','js') ?>
</head>
<body class="bgcolor1">
    <div class="loginbox">
        <div class="welcome">
            <div class="logo"><img src="<?php echo IMAGE_PATH.'/logo.png';?>" /></div>
            <div class="txt"><span>FORGOT</span> YOUR PASSWORD</div>
        </div>
        <div class="login">
            <!--<div class="loginMsg"></div>-->
            <div class="loginBody">
                <div class="input-group">
                    <input type="text" id="email" name="email" class="form-control" placeholder="请输入您的电子邮箱地址" />
                    <div class="input-group-addon"><i class="fa fa-envelope-o"></i></div>
                </div>
                <div class="input-group">
                    <input type="text" id="verifyCode" name="verifyCode" class="form-control" placeholder="请您输入图片验证码" />
                    <a id="changeVerify" class="input-group-addon"><img src="<?php echo U('Tester/Index/verify');?>" /></a>
                </div>
            </div>
            <div class="loginRemember">
                <div class="forgot"><a href="<?php echo U('Tester/Users/login');?>">想起密码了，返回登录？</a></div>
            </div>
            <div class="loginBtns">
                <button id="forgotBtn" type="button" class="btn btn-lg btn-greensea form-control">确认忘记密码</button>
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
