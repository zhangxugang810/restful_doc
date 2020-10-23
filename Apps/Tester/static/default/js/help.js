$(document).ready(function(){
//    $('.item').live('click', function(){showList($(this));});
    $(document).on('click', '.item', function(){showList($(this));});
//    $('.funcname').live('click', function(){getApiContent($(this)); });
    $(document).on('click', '.funcname', function(){getApiContent($(this)); });
//    $('.systemoption').live('click', function(){getststemoption(); });
    $(document).on('click', '.systemoption', function(){getststemoption(); });
//    $('.testBtn').live('click', function(){goApi($(this));});
    $(document).on('click', '.testBtn', function(){goApi($(this));});
//    $('.uploadFileBtn').live('click', function(){uploadFile($(this));});
    $(document).on('click', '.uploadFileBtn', function(){uploadFile($(this));});

    $('#scrollUp').click(function (e){$('.page-content').animate({ scrollTop:0});});

    $('.qr_tool').click(function(){refreshDoc();});

    
    $(document).on('click', '.returndatatype', function(){$(".returndata").slideToggle();});

//    $('.jsondecode').live('click', function(){$(".jsonTable").slideToggle();});
    $(document).on('click', '.jsondecode', function(){$(".jsonTable").slideToggle();});
   
//   $('#submit').live('click', function(){submit();});
   $(document).on('click', '#submit', function(){submit();});
//   $('.showInfo').live('click', function(){showInfo($(this))});
   $(document).on('click', '.showInfo', function(){showInfo($(this));});
   
//   $('.environment > a').live('click', function (){changeEnvironment($(this));});
   $(document).on('click', '.environment > button', function(){changeEnvironment($(this));});
   
//   $('.goUrl').click(function (){goUrl($(this));});
   $(document).on('click', '.goUrl', function(){goUrl($(this));});
   $('.nav').mouseenter(function(){showMenu($(this));return false;});
   $('.nav').mouseleave(function(){hideMenu($(this));return false;});
//   $('.goSubmit').live('click', function(){goSubmit($(this));});
   $(document).on('click', '.goSubmit', function(){goSubmit($(this));});
   $(document).on('click', '.goSubmitUser', function(){goSubmitUser($(this));});
   $(document).on('click', '.goSubmitItem', function(){goSubmitItem($(this));});
//   $('.del').live('click', function(){del($(this));});
   $(document).on('click', '.del', function(){del($(this));});
//   $('.selectItme').click(function(){isShowSelect($(this));});
   $('.selectItme > ul > li').click(function(){selectItem($(this));});
//   $('.addDir').live('click', function(){addDir($(this));});
   $(document).on('click', '.addDir', function(){addDir($(this));});
//   $('.delDir').live('click', function(){delDir($(this));});
   $(document).on('click', '.delDir', function(){delDir($(this));});
//   $('.addGroupUser').live('click', function(){addGroupUser();});
   $(document).on('click', '.addGroupUser', function(){addGroupUser();});
//   $('.removeUser').live('click', function(){removeUser($(this));});
   $(document).on('click', '.removeUser', function(){removeUser($(this));});
//   $('.selectChild').live('click', function(){selectChild($(this));});
   $(document).on('click', '.selectChild', function(){selectChild($(this));});

//   $('.addRule').live('click', function(){addDir($(this));});
   $(document).on('click', '.addRule', function(){addDir($(this));});
//   $('.delRule').live('click', function(){delDir($(this));});
   $(document).on('click', '.delRule', function(){delDir($(this));});
//   $('.selectTop').live('click', function(){selTop($(this));});
   $(document).on('click', '.selectTop', function(){selTop($(this));});
//   $('.selectItem').live('click', function(){selItem($(this));});
   $(document).on('click', '.selectItem', function(){selItem($(this));});
   
//   $('.addFlow').live('click', function(){addDir($(this));changeid($(this));});
   $(document).on('click', '.addFlow', function(){addDir($(this));changeid($(this));});
//   $('.delFlow').live('click', function(){delDir($(this));});
   $(document).on('click', '.delFlow', function(){delDir($(this));});
//   $('.Uploadfile').live('click', function(){$(this).prev().click();setTimeout('upFile("'+$(this).attr('id')+'")',100);});
   $(document).on('click', '.Uploadfile', function(){$(this).prev().click();setTimeout('upFile("'+$(this).attr('id')+'")',100);});
   
   /**单元测试开始**/
/*   $('.addUnitStep').live('click', function(){addUnitStep();});*/
/*   $('.delStep').live('click', function(){delStep($(this));});*/
/*   $('.changeOrd').live('keyup', function(){changeOrd($(this));});单元测试改变序号时更新所有表单id和name*/
//   $('.saveStep').live('click', function(){saveSteps();});
   $(document).on('click', '.saveStep', function(){saveSteps();});
//   $('.header-select').live('click', function(){paramForm($(this));});
   $(document).on('click', '.header-select', function(){paramForm($(this));});
//   $('.param-select').live('click', function(){paramForm($(this));});
   $(document).on('click', '.param-select', function(){paramForm($(this));});
//   $('.delUnit').live('click', function(){delUnit($(this));});
   $(document).on('click', '.delUnit', function(){delUnit($(this));});
//   $('.runUnitTest').live('click', function(){runUnitTest($(this));});
   $(document).on('click', '.runUnitTest', function(){runUnitTest($(this));});
   /**单元测试结束**/
   
   $('#tool_flag').click(function(){showTools();})
   $(document).on('blur', '.itemDirs', function(){checkDir($(this));});
   $(document).on('click', '.showHelpCon', function(){showFormHelpContent($(this));});
   $(document).on('click', '.itemindex', function(){getItemIndex($(this).attr('itemId'));});
   $(document).on('click', '.iconlist > div', function(){showIndexInfo($(this));});
   $(document).on('click', '.createDocument', function(){doCreateDoc($(this));});
   $(document).on('click', '.createIkangDocument', function(){doCreateDoc($(this));});
});

function doCreateDoc(obj){
    var url = obj.attr('url');
    $.get(url,{}, function(t){
        setTimeout('void(0)', 1000);
        window.location=t
    },'html');
}

function showIndexInfo(obj){
    var txt = obj.children('em').html();
    obj.parent().children('div[class=selected]').removeClass('selected');
    obj.addClass('selected');
    var o = obj.parent().next();
    o.html(txt);
}

function showFormHelpContent(obj){
    var o = obj.parent().next();
    if(o.css('display') == 'none'){
        obj.children('i').attr('class', 'fa fa-chevron-up');
        o.slideDown('fast');
    }else{
        obj.children('i').attr('class', 'fa fa-chevron-down');
        o.slideUp('fast');
    }
}

function checkDir(obj){
    var path = obj.val();
    var url = U('Tester/Index/checkDir');
    $.post(url,{'path':path}, function(t){
        if(!t.result){
            obj.next().next().html('您填写的目录不存在，请确认目录是否正确');obj.select();
            obj.attr('is', 'no');
        }else{
            var html = setFilesList(t.data);
            obj.next().next().html(html);
            obj.attr('is', 'yes');
        }
    }, 'json');
    return false;
}

function setFilesList(files){
    var html = '<div class="btn-group open">';
    html += '    <button type="button" class="btn btn-info">文件列表</button>';
    html += '       <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
    html += '           <span class="caret"></span>';
    html += '           <span class="sr-only">Toggle Dropdown</span>';
    html += '       </button>';
    html += '       <ul class="dropdown-menu bgcolor1">';
    var k = 1;
    for(var i in files){
        html += '           <li><a href="javascript:void(0);">'+k+'.'+files[i]+'</a></li>';
        k++;
    }
    html += '       </ul>';
    html += '    </div>';
    return html;
}

function showTools(){
    var html = $('#tool').html();
    if(html != ''){
        $('#tool').slideToggle('fast');
    }
}

function runUnitTest(obj){
    obj.html('...');
    var key = obj.attr('key');
    var url = U('Tester/Unit/runUnit');
    $.post(url, {key:key}, function(t){
        if(t.result){alert('运行完成');obj.html('<i class="icon icon-play"></i>');}
    }, 'json');
}

function delUnit(obj){
    if(confirm('您确认要删除这个测试单元吗？删除后无法恢复，请谨慎删除')){
        var key = obj.attr('key');
        var url = U('Tester/Unit/delUnit');
        $.post(url, {key:key}, function(t){
            if(t.result){obj.parent().parent().remove();}
        }, 'json');
    }
}

function paramForm(obj){
    var v = obj.val();
    var o = obj.parent().parent().parent().next();
    var data = {result: '如：token,id', cookie: '如：token,id', input: '如：{\'id\':\'1\'}', all: '如：result=token,id|input={\'id\':\'1\'}'}
    o.find('input').attr('placeholder', data[v]);
}

function saveSteps(){
    var form = $('#stepForm');
    var data = form.serialize();
    var url = form.attr("action");
    $.post(url, data, function(t){
        $('#unit_menu').click();
        alert(t.msg);
    }, 'json');
}
/*
function changeOrd(obj){
    var v = obj.val();
    var objs = obj.parent().parent().parent().find('input');
    var os = obj.parent().parent().parent().find('textarea');
    for(var i = 0; i < objs.length; i++){
        setObj($(objs[i]), v);
    }
    
    for(var i = 0; i < os.length; i++){
        setObj($(os[i]), v);
    }
}*/

function setObj(obj, num){
    var ids = obj.attr('id').split('_');
    ids[2] = num;
    var id = ids[0]+'_'+ids[1]+'_'+ids[2];
    var name = ids[0]+'['+ids[1]+']['+ids[2]+']';
    obj.attr('id', id).attr('name', name);
}

//function delStep(obj){
//    if(confirm('您确认要删除这个单元测试步骤吗？')){
//        var o = obj.parent().parent().parent().parent();
//        var o1 = o.parent().find('table');
//        if(o1.length > 2){
//            var o2 = o.prev().find('tr[class="delete hide"]');
//            o2.removeClass('hide');
//        }
//        o1.length > 1 ? o.remove() : alert('最后一条了，不能删除了哦！');
//    }
//}

//function addUnitStep(){
//    var obj = $('.steps');
//    var tables = obj.find('table');
//    var i = tables.length-1;
//    var o = $(tables[i]).clone();//克隆对象
//    var o1 = o.find('input[class=changeOrd]');
//    var num = parseInt(o1.attr("value")) + 1;
//    o1.val(num);
//    changeNum(o, num);
//    var o2 = o.find('tr[class="delete hide"]');
//    o2.removeClass('hide');
//    var o3 = $(tables[i]).find('tr[class="delete"]');
//    o3.addClass('hide');
//    obj.append(o);
//}

//function changeNum(obj, v){
//    var objs = obj.find('input');
//    var os = obj.find('textarea');
//    for(var i = 0; i < objs.length; i++){
//        setObj($(objs[i]), v);
//    }
//    
//    for(var i = 0; i < os.length; i++){
//        setObj($(os[i]), v);
//    }
//}

function changeid(obj){
    var o = obj.parent().next().find('button[class=Uploadfile]');
    var ids = o.attr('id').split('_');
    var d = new Date();
    var num = d.getTime() + '_' + Math.round(Math.random() * 10000 +1,0);
    var id = ids[0]+'_'+num;alert(id);
    o.attr('id', id);
}

function upFile(id){
    var obj = $('#'+id);
    var file = obj.parent().find('input[type=file]');
    var url = obj.attr('url');
//    var headers = getHeaders();
    if(file[0].files[0] != null){
        var xhr = createXHR();
        xhr.open("post", url, true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    //    for(var key in headers){
    //        xhr.setRequestHeader(key, headers[key]);
    //    }
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4) {
                
                var flag = $.parseJSON(xhr.responseText);
                obj.prev().prev().val(flag.path);
//                parseJson2Html(flag);
            }
        };
        var form = new FormData();
        form.append("upload", file[0].files[0]);
        xhr.send(form);
    }else{
        setTimeout('upFile("'+id+'")',100);
    }
}

function uploadFile(obj){
    var file = obj.parent().parent().prev().prev().find('input[type=file]');
    var url = obj.attr('url');
    var headers = getHeaders();
    
    var xhr = createXHR();
    xhr.open("post", url, true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    for(var key in headers){
        xhr.setRequestHeader(key, headers[key]);
    }
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            var flag = $.parseJSON(xhr.responseText);
            parseJson2Html(flag);
        }
    };
    var form = new FormData();
    form.append("file", file[0].files[0]);
    xhr.send(form);
}

function createXHR() {
    if (typeof ActiveXObject !== "undefined") {
        createXHR = function () {
            if (typeof arguments.callee.activeXString != "string") {
                var versions = ["MSXML2.XMLHttp.6.0", "MSXML2.XMLHttp.3.0", "MSXML2.XMLHttp"];
                for (var i = 0, len = versions.length; i < len; i++) {
                    try {
                        var xhr = new ActiveXObject(versions[i]);
                        arguments.callee.activeXString = versions[i];
                        return xhr;
                    } catch (error) {
                        // TODO  
                    }
                }
            }

            return new ActiveXObject(arguments.callee.activeXString);
        };
    } else if (typeof XMLHttpRequest !== "undefined") {
        createXHR = function () {
            return new XMLHttpRequest();
        };
    } else {
        createXHR = function () {
            throw new Error("No XHR object avaliable.");
        };
    }

    return createXHR();
}  

function selTop(obj){
    var o = obj.parent().parent().parent().children('h2').children('input');
    o.attr('checked', obj.attr('checked'));
}

function selItem(obj){
    var o = obj.parent().parent().children('h3').children('input');
    o.attr('checked', obj.attr('checked'));
}

function selectChild(obj){
    var o = obj.parent().parent();
    var os = o.find('input[type=checkbox]');
    var checked = obj.attr('checked');
    checked = checked == null ? false : true;
    os.attr('checked', checked);
}

function removeUser(obj){
    var o = obj.parent();
    o.remove();
}

function addGroupUser(){
    var obj = $('#username');
    var username = obj.val();
    if(username == '0' || username == ''){alert('请您选择用户');return false;}
    if(isInsertUser(username)){alert('该组已经有这个用户，不能重复选择!');return false;}
    var str = '<li username="'+username+'"><input type="hidden" name="groupUsers[]" value="'+username+'" /><span>'+username+'</span><em class="removeUser"><i class="icon icon-remove"></i></em></li>';
    $('.groupUserList').append(str);
}

function isInsertUser(username){
    var obj = $('.groupUserList').children('li[username='+username+']');
    if(obj[0] == null){return false;}
    return true;
}

function addDir(obj){
    var o = obj.parent().parent();
    var html = '<li>' + o.html() + '</li>';
    o.after(html);
}



function delDir(obj){
    var o = obj.parent().parent().parent().children('li');
    var len = o.length;
    if(len <= 1){alert('再删就没了，亲！，就这样吧，你看呢！');return false;}
    obj.parent().parent().remove();
}

function selectItem(obj){
    var itemid = obj.attr('itemid');
    var itemname = obj.children('a').html();
    var o = $('#thisItem');
    o.attr('itemid', itemid);
    o.val(itemname);
    $('#itemid').val(itemid);
    var url = U('Tester/Index/getItem');
    item = itemid;
    $.post(url, {'itemid':itemid, 'itemname':itemname}, function(t){$('#apilist').html(t);}, 'html');
//    var url = U('Tester/Index/getItemIndex');
//    $.post(url, {'itemid':itemid}, function(t){$('#r').html(t.itemIndex)}, 'json');
    getItemIndex(itemid);
}

function getItemIndex(itemid){
    var url = U('Tester/Index/getItemIndex');
    $.post(url, {'itemid':itemid}, function(t){$('#r').html(t.itemIndex);}, 'json');
}

function isShowSelect(obj){
    var menu = obj.children('ul');
    menu.slideToggle(0);
}

function del(obj){
    var url = obj.attr('url');
    var objs = $('.listTable').find('input[type=checkbox]');
    var len = objs.length;
    var data = {};
    var d = [];
    var n = 0;
    for(var i = 0; i < len; i++){
        if($(objs[i]).prop('checked')){
            d[n] = $(objs[i]).val();
            n++;
        }
    }
    data.data = d;
    $.post(url, data, function(t){alert(t.msg);returnUrl(t.returnUrl);}, 'json');
}

function goSubmitUser(obj){
    if($('#username').val() == ''){alert('请您填写用户名!');return false;}
//    if($('#password').val() == ''){alert('请您填写密码!');return false;}
    if($('#realname').val() == ''){alert('请您填写姓名!');return false;}
    if($('#sex').val() == ''){alert('请您选择性别!');return false;}
    if($('#mobile').val() == ''){alert('请您填写手机号!');return false;}
    if($('#email').val() == ''){alert('请您填写电子邮箱，请注意这里的电子邮箱可能与账号相关联!');return false;}
    var url = obj.attr('url');
    var data = $("#form1").serialize();
    $.post(url, data, function(t){
        alert(t.msg);
        returnUrl(t.returnUrl);
    }, 'json');
}



function goSubmitItem(obj){
    var dirs = $('.itemDirs');
    var len = dirs.length;
    for(var i = 0; i < dirs.length; i++){
        if($(dirs[i]).val() == ''){$(dirs[i]).next().next().html('请您填写项目API实际地址');return false;}
        if($(dirs[i]).attr('is') == 'no'){$(dirs[i]).next().next().html('您填写的目录不存在，请确认目录是否正确');return false;}
    }
    if($('#itemname').val() == ''){$('#itemname').parent().next().html('请你填写项目名称');return false;}else{$('#itemname').parent().next().html('');}
    if($('#fileNameRule').val() == ''){$('#fileNameRule').parent().next().html('请你填写文件命名规范');return false;}else{$('#fileNameRule').parent().next().html('');}
    if($('#rewrite').val() == ''){$('#rewrite').parent().next().html('请你填写Rewrite规则');return false;}else{$('#rewrite').parent().next().html('');}
    if($('#paramsFormat').val() == ''){$('#paramsFormat').parent().next().html('请你填写返回参数基本格式');return false;}else{$('#paramsFormat').parent().next().html('');}
    var url = obj.attr('url');
    var data = $("#form1").serialize();
    $.post(url, data, function(t){
        alert(t.msg);
        returnUrl(t.returnUrl);
    }, 'json');
}

function goSubmit(obj){
    var url = obj.attr('url');
    var data = $("#form1").serialize();
    $.post(url, data, function(t){
        alert(t.msg);
        returnUrl(t.returnUrl);
    }, 'json');
}

function returnUrl(url){
    if(url == null){return false;}
    $.get(url,{}, function(t){$('#r').html(t).show();}, 'html');
}

function showMenu(obj){
    var o = obj.children('ul');
    if(o[0] != null){o.show(0);}
}

function hideMenu(obj){
    var o = obj.children('ul');
    if(o[0] != null){o.hide(0);}
}

function goUrl(obj){
    var url = obj.attr('url');
    if(url == '' || url == null){alert('God, 亲你要去哪里呀，没有地址可不行哦！');return false;}
    $.get(url,{}, function(t){
        $('#tool').css('display', 'none');
        $('#r').html(t).show();
    }, 'html');
}

function changeEnvironment(obj){
    obj.parent().children('button').removeClass('btn-info').addClass('btn-default');
    obj.addClass('btn-info');
    var url = obj.attr('url');
    $('#rburl > a').attr('href', url).html(url);
//    $('#tool').css('display', 'none');
    try{
        $('.testBtn').attr('url', url);
        defaultEnv = obj.attr('id');
    }catch(e){
        $('#fileForm').attr('action', url);
    }
}

function showInfo(obj){
    if(obj.attr('class') == 'showInfo'){
        obj.parent().next().find('ul').hide(0, function(){
            obj.html('收缩 <i class="fa fa-chevron-up"></i>');
            obj.addClass('current');
        });
        obj.parent().next().find('table').show(0);
    }else{
        obj.parent().next().find('table').hide(0, function(){
            obj.html('展开 <i class="fa fa-chevron-down"></i>');
            obj.removeClass('current');
        });
        obj.parent().next().find('ul').show(0);
    }
}

function submit(){
//    setTimeout('getFrameJson()', 500);
    $('#qsubmit').click();
}

function getFrameJson(){
    var ifm = $(window.frames['fileFrame'].document).attr('body');
    var content = $(ifm).html();
    content == '' || content == null ? setTimeout('getFrameJson()', 500) : parseJson2Html(content);
}

function refreshDoc(){
    var url = U('Tester/Index/refreshDoc');
    $.post(url,{},function(t){
        if(t){alert('更新缓存成功！');}
    }, 'json');
}


function SetWinHeight(obj)
{
    var win=obj;
    if (document.getElementById)
    {
        if (win && !window.opera)
        {
            if (win.contentDocument && win.contentDocument.body.offsetHeight)
                win.height = win.contentDocument.body.offsetHeight;
            else if(win.Document && win.Document.body.scrollHeight)
                win.height = win.Document.body.scrollHeight;
        }
    }
}


function getststemoption(){
    var url = U('Tester/Index/systemoption');
    $.post(url, {}, function(t){
        $('.r').html(t);
    });
}

function setapicontentupdate(){
    var controller = $('#controller').val();
    var funcname = $('#funcname').val();
    if(funcname != ''){
        getContent(controller, funcname);
    }
}

function checkparameter(){
    var tag = $('.testBtn').attr('tag');
    if(tag == 'unparameter'){
        $('.testBtn').click();
    }
}

function goApi(obj){
    $('#returnjsoncode').html("").removeClass('bgwhite');;
    var o = $('.formDatas');
    var inputs = o.find('input');
    var texts = o.find('textarea');
    var data = {};
    for(var i= 0; i < inputs.length; i++){
        var os = $(inputs[i]);
        var name = os.attr('name');
        var value = os.val();
        data[name] = value;
    }
    
    for(var i = 0; i < texts.length; i++){
        var os = $(texts[i]);
        var name = os.attr('name');
        var value = os.val();
        data[name] = value;
    }
    var url = getUrl(obj.attr('url'));
    var cookie = obj.attr('cookie');
    var method = obj.attr('method');
    var requestType = $('#requestType').val();
    var datastr = getDataStr(data, requestType, method);
    var headers = getHeaders();
    if(method != '' && method != null){
        $.ajax({
            type: method,
            url: url,
            data: datastr,
            dataType:'json',
            headers: headers,
            success: function (t) {parseJson2Html(t);if(cookie != null && cookie != ''){setResult2Cookie(t, cookie);}},
            error:function(XMLHttpRequest, textStatus, errorThrown){
                httpError(XMLHttpRequest);
            }
        });
    }else{
        alert('请注意该方法没有定义提交方式,请查看您的代码');
    }
}

function httpError(xmlhttp){
//    alert(xmlhttp.status);
//    alert(xmlhttp.responseText);
    var url = U('Tester/index/saveError');
    
    $.post(url, {'html': xmlhttp.responseText}, function(t){
        if(t.result){
            var html = '<iframe id="errorFrame" name="errorFrame" src="'+t.file+'?'+Math.random()+'" width="100%" height="400" frameborder="0"></iframe>';
            $('#returnjsoncode').html(html).addClass('bgwhite');
        }
    }, 'json');
}

function setResult2Cookie(t, cookiename){
    var value = t.ret[cookiename];
    $.cookie(cookiename, value);
    var names = $.cookie('tokenlist');
    if(names == '' || names == null){
        names = cookiename;
    }else{
        names += ',' + cookiename;
    }
    $.cookie('tokenlist', names);
}

function getUrl(url){
    var objs = $('.defaultGet').find('input[type=text]');
    var len = objs.length;
    for(var i = 0; i < len; i++){
        url += '&'+$(objs[i]).attr('name')+'='+$(objs[i]).val();
    }
    return url;
}

function getHeaders(){
    var o = $('#headers').find('input');
    var len = o.length;
    var data = {};
    for(var i = 0; i < len; i++){
        data[$(o[i]).attr('name')] = $(o[i]).val();
    }
    
    var username = $('#username').val();
    var password = $('#password').val();
    if(username != null && password !=null){
        data['Authorization'] = base64encode(username + ':' + password);
    }
    return data;
}

function getDataStr(data, requestType, method){
    if(method == 'GET'){
        return data;
    }
    if(requestType == 'FORM'){
        return data;
    }
    return data['jsondata'];
}

function parseJson2Html(json){
    try{
        $("#returnjsoncode").JSONView(json);
    }catch(e){
        $("#returnjsoncode").html(json);
    }
}

function getApiContent(obj){
    var status = obj.attr('status');
    $('a').removeClass('hover');
    obj.addClass('hover');
    if(status == 'noajax'){
        $('.r').hide(0);
        var pic = obj.attr('pic');
        var pic = pic.substr(1, pic.length);
        $('#r').html('<img src="'+pic+'" />');
    }else{
        $('.r').show(0);
        var controller = obj.parent().prev().attr('controller');
        var funcname = obj.attr('funcname');
        var path = obj.attr('path');
        var app = obj.attr('app');
        $('#controller').val(controller);
        $('#funcname').val(funcname);
        $('#path').attr('controllerPath', path);
        getContent(controller, funcname, path, app);
    }
    
}

function getContent(controller, funcname, path, app){
    var url = U('Tester/Index/apiContent');
    var item = $('#itemid').val();
    var data = {"controller" : controller, "funcname" : funcname, "path": path, "app": app, "item": item};
    $.post(url, data, function(t){
        t = t.split('{|}');
        $('#tool').html(t[1]);
        $('#r').html(t[0]);
        ajaxHeight($('.l'), $('.r'));
        checkparameter();
        setTokenList();
    });
}

function setTokenList(){
    try{
        var tokenlist = $.cookie('tokenlist').split(',');
        for(var i in tokenlist){
            $('#'+tokenlist[i]).val($.cookie(tokenlist[i]));
        }
    }catch(e){}
}

function ajaxHeight(o1, o2){
	var formH = o1.children('form').outerHeight()+55;
	var ulH = o1.children('ul').outerHeight()+50;
	var o1Height = formH+ulH;
	o1Height > o2.outerHeight() ? o1.css('min-height', o1Height) : o1.css('min-height', o2.outerHeight());
}

function showList(obj){
//    var o = obj.parent().children('div[class=catalog2]');
    var o = obj.next();
    if(o.children().length == 0){
        var data = {"controller" : obj.attr('controller'), "controllerPath": obj.attr('controllerPath'), "app": obj.attr('app')};
        var url = U('Tester/Index/classApiList');
        if(obj.attr('status') == ''){
            $.post(url, data, function(t){o.html(t);});
        }
    }
    if(o.css('display') == 'block'){
        obj.children('i').removeClass('fa-folder-open-o').addClass('fa-folder-o');
        obj.parent().removeClass('selected');
    }else{
        obj.children('i').removeClass('fa-folder-o').addClass('fa-folder-open-o');
        obj.parent().addClass('selected');
    }
    o.slideToggle('fast');
}

function setLeftHeight(obj){
    var o = obj.parent();
    var h = o.outerHeight();
    obj.css('min-height', h-50)
}

function setWidth(){
    var allwidth = $('.mbox').outerWidth() - 200;
    var w = allwidth/2 - 60;
    $('.r').css('width', w);
}

var defaultEnv = '';
function setEnvironment(){
    if(defaultEnv != null && defaultEnv != ''){
        var os = $('.environment').children('button');
        for(var i = 0; i < os.length; i++){
            if($(os[i]).attr('id') == defaultEnv){
                $(os[i]).click();break;
            }
        }
    }
}