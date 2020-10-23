$(document).ready(function(){
    $('.backgrounds > ul > li').click(function (){setBackground($(this));return false;});
    $('#xieyi').click(function(){agree($(this))});
    $('.first').find('input').blur(function(){checkEmpty($(this));});
    $('.goThird').click(function(){goThird($(this));});
    $('.complete').click(function(){complete($(this))});
});

function checkEmpty(obj){
    var o = obj.parent().next();
    if(obj.val() == ''){o.html('请您填写'+obj.attr('tip')+'！');}else{o.html('');}
}

function complete(obj){
    if($('#sysname').val() == ''){$('#sysname').parent().next().html('请您填写系统显示名称！');return false;}else{$('#sysname').parent().next().html('');}
    if($('#mailServerHost').val() == ''){$('#mailServerHost').parent().next().html('请您填写邮件服务器地址！');return false;}else{$('#mailServerHost').parent().next().html('');}
    if($('#mailServerUsername').val() == ''){$('#mailServerUsername').parent().next().html('请您填写邮件服务器用户名！');return false;}else{$('#mailServerUsername').parent().next().html('');}
    if($('#mailServerPassword').val() == ''){$('#mailServerPassword').parent().next().html('请您填写邮件服务器密码！');return false;}else{$('#mailServerPassword').parent().next().html('');}
    if($('#mailCharSet').val() == ''){$('#mailCharSet').parent().next().html('请您填写默认发件编码！');return false;}else{$('#mailCharSet').parent().next().html('');}
    if($('#mailFromName').val() == ''){$('#mailFromName').parent().next().html('请您填写默认发件人姓名！');return false;}else{$('#mailFromName').parent().next().html('');}
    var inputs = $('input');
    var data = {};
    for(var i = 0; i < inputs.length; i++){
        data[$(inputs[i]).attr('name')] = $(inputs[i]).val();
    }
    var completeUrl = obj.attr('completeUrl');
    $.post(completeUrl, data, function(t){
        if(t.result){
            window.location.href = t.returnUrl
        }else{
            $('.tips').html(t.msg);
        }
    }, 'json');
}

function goThird(obj){
    if($('#username').val() == ''){$('#username').parent().next().html('请您填写用户名！');return false;}else{$('#username').parent().next().html('');}
    if($('#password').val() == ''){$('#password').parent().next().html('请您填写密码！');return false;}else{$('#password').parent().next().html('');}
    if($('#repassword').val() == ''){$('#repassword').parent().next().html('请您填写确认密码！');return false;}else{$('#repassword').parent().next().html('');}
    if($('#repassword').val() != $('#password').val()){$('#repassword').parent().next().html('两次输入的密码不相同！');return false;}else{$('#repassword').parent().next().html('');}
    if($('#realname').val() == ''){$('#realname').parent().next().html('请您填写姓名！');return false;}else{$('#realname').parent().next().html('');}
    if($('#mobile').val() == ''){$('#mobile').parent().next().html('请您填写手机！');return false;}else{$('#mobile').parent().next().html('');}
    if($('#email').val() == ''){$('#email').parent().next().html('请您填写E-mail！');return false;}else{$('#email').parent().next().html('');}
    var inputs = $('input');
    var data = {};
    for(var i = 0; i < inputs.length; i++){
        data[$(inputs[i]).attr('name')] = $(inputs[i]).val();
    }
    var saveurl = obj.attr('saveurl');
    $.post(saveurl, data, function(t){ 
        if(t.result){
            window.location.href = t.returnUrl
        }else{
            $('.tips').html(t.msg);
        }
    }, 'json');
}

function agree(obj){
    if(obj.is(':checked')){
        $('#next').removeAttr('disabled');
        $('#next').attr('href', $('#next').attr('url'));
    }else{
        $('#next').attr('disabled', 'disabled');
    }
}

function setBackground(obj) {
    var bgclass = obj.attr('class');
    $('body').attr('class', bgclass);
}

function checkEnvirs(){
    var inputs = $("input");
    var status = true;
    for(var i = 0; i < inputs.length; i++){
        if($(inputs[i]).val() == '' || $(inputs[i]).val() == 0){
            status = false;
            break;
        }
    }
    if(status == true){
        var url = U('Install/Index/stepSecond');
        $('.nextStep').attr('href', url).attr('disabled', false);
    }else{
        $('.nextStep').attr('href', 'javascript:void(0);').attr('disabled', true);
    }
}