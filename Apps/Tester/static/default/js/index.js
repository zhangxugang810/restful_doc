$(document).ready(function(){
    $('.downList').click(function(){
        showMenu($(this));
    });
    
    $('.downList').mouseleave(function(){
        hideMenu($(this));
    });
    
    $('#controllerList > li').click(function(){
        setController($(this));
        return false;
    });
    
    $('#funcList > li').live('click', function(){
        setFunc($(this));
    });
    
    $('#goRun').click(function(){
        goRun();
    });
    
    $(".displayDesc").click(function(){
        var obj = $('#description');
        obj.parent().slideToggle('fast');
    });
});

function goRun(){
    var data = {};
    var controller = $('#controller').html();
    var func = $('#func').html();
    if(controller == '' || controller == '选择控制器'){
        alert('请选择控制器');
        return false;
    }
    if(func == '' || func == '选择方法'){
        alert('请选择方法');
        return false;
    }
    var params = $('#params').attr('value');
    if($('#ciphertext').attr('checked')){
        var ciphertext = $('#ciphertext').attr('value');
    }else{
        var ciphertext = false;
    }
    var ds = {};
    if(params != '' && params != null){
        var d = params.split("\n");

        for(var i in d){
            var val = d[i].split(':');
            ds[val[0]] = val[1];
        }
    }
    var url = U('Api/'+controller+'/'+func);
    $.post(url, ds, function(t){
        var html = '';
        if(ENCODE){
            if(ciphertext){
                html += '<div style="height:20%;border-bottom:1px solid #aaa; overflow:auto;">密文：'+"<br/>"+t+'</div>';
                var w = '79%';
            }else{
                var w = '100%';
            }
            if(t != '' && t!= null){
                var url = U('Tester/Index/goRun');
                $.post(url, {code:t}, function(s){
                    html += '<div style="height:'+w+'; overflow:auto;">返回结果：'+"<br/>"+s+'</div>';
                    $('.main').html(html);
                },'html');
            }else{
                $('.main').html('<span style="color:#F00;font-weight:bold;">错误消息：没有任何返回数据请检查数据接口！</span>');
            }
        }else{
            var url = U('Tester/Index/format');
            $.post(url, {code:t}, function(s){
                html += '<div style="height:100%;border-bottom:1px solid #aaa; overflow:auto;">返回结果：'+"<br/>"+s+'</div>';
                $('.main').html(html);
            },'html');
            
        }
    },'html');
}

function setFunc(obj){
    var fsee = obj.attr('fsee');
    var fname = obj.attr('fname');
    var freturn  = obj.attr("freturn");
    var fparam = obj.attr('fparam');
    var code = '';
    if(fsee != '' && fsee != null && fsee != 'undefined'){code += '<div>'+fsee+'</div>'};
    if(fname != '' && fname != null && fname != 'undefined'){code += '<div>'+fname+'</div>'};
    if(fparam != '' && fparam != null && fparam != 'undefined'){code += '<div>'+fparam+'</div>'};
    if(freturn != '' && freturn != null && freturn != 'undefined'){code += '<div>'+freturn+'</div>'};
    $('#func').html(obj.html());
    $('#description').html(code);
}

function setController(obj){
    var controller = obj.html();
    var o = obj.parent().parent().children('i');
    o.html(controller);
    var o1 = obj.parent();
    o1.fadeOut('slow');
    var url = U('Tester/Index/getFuncs');
    $.post(url, {'controller': controller}, function(t){
        if(t.result == true){
            $('#func').html('选择方法');
            var str = '';
            for(var i in t.data){
                str += '<li value="'+t.data[i].funcname+'" fname="'+t.data[i].func+'" fsee="'+t.data[i].see+'" fparam="'+t.data[i].param+'" freturn="'+t.data[i].return+'">'+t.data[i].funcname+'</li>';
            }
            $('#funcList').html(str);
        }
    },'json');
}

function hideMenu(obj){
    var o = obj.children('ul');
    o.slideUp('fast');
}

function showMenu(obj){
    var o = obj.children('ul');
    o.slideToggle('fast');
}

window.onload = function(){
    setMainSize();
}

window.onresize = function(){
    setMainSize();
}

function setMainSize(){
    var h = $('body').outerHeight();
    var height = h - 141;
    $('.main').css('height', height);
}