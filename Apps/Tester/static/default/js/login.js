$(document).ready(function () {
    $('#loginBtn').click(function (){login();return false;});
    $('.remember > a').click(function (){remember($(this));return false;});
    $('#forgotBtn').click(function (){doForgot();return false;});
    $('#changeBtn').click(function(){doChangePwd();return false;});
    $('#changeVerify').click(function(){changeVerify($(this));return false;});
});

function changeVerify(obj){
    var img = obj.children('img');
    var pic = img.attr('src')+'?v='+Math.random();
    img.attr('src', pic);
}

function doChangePwd(){
    var email = $("#email").val();
    var verify = $('#verify').val();
    var password = $('#password').val();
    var repassword = $('#repassword').val();
    if(email == ''){alert('请您按照流程执行程序，忘记密码请先获取邮箱验证码');window.location.href = U('Tester/Users/login');return false;}
    if(verify == '' || verify.length != 6){alert('请您输入正确的邮箱验证码');return false;}
    if(password == ''){alert('请您输入您的密码');return false;}
    if(repassword == ''){alert('请您输入您的确认密码');return false;}
    if(repassword != password){alert('两次输入的密码不一致，请您重新输入密码和确认密码');return false;}
    var url = U('Tester/Users/doChangePwd');
    $.post(url, {'email': email, 'verify': verify, 'password': password, 'repassword': repassword}, function(t){
        if(t.result.result){
            alert(t.msg);
            window.location.href = U('Tester/Users/login');
        }else{alert(t.msg);}
    }, 'json');
}

function doForgot() {
    var email = $('#email').val();
    var verifyCode = $('#verifyCode').val();
    if (email == ''){alert('请您填写您的电子邮箱地址');return false;}
    if (!isEmail(email)){alert('请您填写正确的电子邮箱地址');return false;}
    if(verifyCode == '' ||verifyCode.length != 4){alert('请您输入正确的验证码');return false;}
    var url = U('Tester/Users/doForgot');
    $.post(url, {'email': email, 'verifyCode': verifyCode}, function(t){
        if(t.result.result){
            alert(t.msg);
            window.location.href = U('Tester/Users/changePwd', {'email':t.result.email});
        }else{alert(t.msg);}
    }, 'json');
}

function isEmail(strEmail) {
    if (strEmail.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) != -1){
        return true;
    }else{
        return false;
    }
}

function remember(obj) {
    var o = obj.parent();
    var cls = o.attr('class');
    if (cls == 'remember') {
        o.attr('class', 'remember icon');
        $('#remember').val('0');
    } else {
        o.attr('class', 'remember');
        $('#remember').val('1');
    }
}

function login() {
    var username = $('#username').val();
    var password = $('#password').val();
    var verifyCode = $('#verifyCode').val();
    if(username == ''){
        $('#loginMsg').html('请输入登录用户名');return false;
    }
    if(password == ''){
        $('#loginMsg').html('请输入登录密码');return false;
    }
    if(verifyCode == ''){
        $('#loginMsg').html('请输入图片验证码');return false;
    }
    var data = {'username': username, 'password': password, 'verifyCode': verifyCode, 'remember': $('#remember').val()};
    var url = U('Tester/Users/doLogin');
    $.post(url, data, function (t) {
        if (t != null) {
            if (t.result) {
                window.location.href = U('Tester/Index/index');
            } else {
                $('#loginMsg').html(t.msg);
            }
        }
    }, 'json');
}