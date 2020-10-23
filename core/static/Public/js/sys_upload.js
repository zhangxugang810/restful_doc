/**
* Jquery上传操作组件
*
* 本程序主要作用用来在弹出窗口中形成上传文件功能
* 
* @category   Javascript
* @package    Javascript
* @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
$(document).ready(function(){
    $('#fileurl').click(function(){setUrlTextNull($(this));});
    $('#fileurl').blur(function(){setUrlText($(this));});
    $('#uploadCancel').click(function(){$.win.close($(this));});
    $('#tab_1').click(function(){showTab($(this));});
    $('#tab_2').click(function(){showTab($(this));});
    $('#tab_3').click(function(){showTab($(this));});
    $('#downloadButton').click(function(){downloadFile($('#fileurl'),$(this));});
    $('#uploadButton').click(function(){createIframe();frameHeart();});
    $('#someFileUploaded').click(function(){callbackSomeFile($(this));});
});

function callbackSomeFile(obj){
    var data = $('#uploadedFile').attr('value');
    var f = eval($('#callback').attr('value'));
    f(data);
    $.win.close(obj);
}

var frameid;
function createIframe(obj){
    var o = $('#framespan');
    var rnd = Math.round(Math.random()*10000);
    frameid = 'upframe_'+rnd;
    o.html('<iframe name="'+frameid+'" id="'+frameid+'" src="" style="display:none;" ></iframe>');
    $('#upformFrame').attr('target',frameid);
    $('#upformFrame').submit();
    
}

function frameHeart(){
    var ifm = $(window.frames[frameid].document).attr('body');
    var content = $(ifm).html();
    if(content == ''){setTimeout('frameHeart()',500);}else{
        var o = $('#uploadButton');
        var type = $('#type').attr('value');
        try{var dobj = $.parseJSON(content);}catch(e){alert('上传文件失败！');return false;}
        var f = $('#callback').attr('value');
        var uploadPath = $('#uploadPath').attr('value');
        var iseditor = $('#iseditor').attr('value');
        if(type == 'More'){
            var str = $('#'+f).attr('value');
            str += dobj.path+',';
            $('#'+f).attr('value', str);
            var img = '<span><img height="100" src="'+dobj.path.replace('./','/')+'" /></span>';
            $('#picsList').prepend(img);
        }else{
            alert(uploadPath);
            if(f == ''){
                if(uploadPath == '' || uploadPath == null){
                    window.location.reload();
                }
            }else{
                if(iseditor != '' && iseditor != null){
                    var p = dobj.path.replace('./','/');
                    $('#'+f).attr('value', p);
                    var iseditors = iseditor.split(':');
                    if(iseditors[1] == 'FLASH'){
                        var html = '<embed height="100%" width="100%" src="' + p + '" type="application/x-shockwave-flash"></embed>';
                        $('#'+iseditors[0]).html(html).show();
                    }else{
                        $('#'+iseditor).attr('src',p).show();
                    }
                }else{
                    $('#'+f).attr('value', dobj.path);
                    $('#lookPic_'+f).remove();
                    $('#'+f).next().next().after('<a id="lookPic_'+f+'" pic="'+dobj.path.replace('./','/')+'" href="javascript:void(0);" class="lookPic onePicPrev" id="prepic_'+f+'"><img src="'+dobj.path.replace('./','/')+'" height="30"/></a>');
                }
            }
        }
        var os = $('.uploadpics');
        var len = os.length;
        for(var i = 0; i < len; i++){
            if($(os[i]).next().attr('class') != 'fileField'){
                var html = '<input type="hidden" value="'+$(os[i]).prev().attr('name')+'" name="fileField[]" class="fileField" />';
                $(os[i]).after(html);
            }
        }
        var os1 = $('.uploadpic');
        var len = os1.length;
        for(var i = 0; i < len; i++){
            if($(os1[i]).next().attr('class') != 'fileField'){
                var html = '<input type="hidden" value="'+$(os1[i]).prev().attr('name')+'" name="fileField[]" class="fileField" />';
                $(os1[i]).after(html);
            }
        }
        $.win.close(o);
        $('#framespan').html('');
    }
}

function downloadFile(obj,o){
     var text = obj.attr('value');
     if(text == '请您输入文件的绝对地址' || text == ''){alert('请输入要下载文件的绝对地址');}else{
         var url = U('Admin/Public/downloadFile');
         var data = {'fileurl':text};
         $.post(url, data, function(dobj){
            var type = $('#type').attr('value');
            var f = $('#callback').attr('value');
            if(type == 'More'){var str = $('#'+f).attr('value');str += dobj.path+',';$('#'+f).attr('value', str);var img = '<span><img height="100" src="'+dobj.path.replace('./','/')+'" /></span>';$('#picsList').prepend(img);}else{if(f == ''){window.location.reload();}else{$('#'+f).attr('value', dobj.path);$('#lookPic_'+f).remove();$('#'+f).next().next().after('<a id="lookPic_'+f+'" pic="'+dobj.path.replace('./','/')+'" href="javascript:void(0);" class="lookPic onePicPrev" id="prepic_'+f+'"><img src="'+dobj.path.replace('./','/')+'" height="30"/></a>');}}
            var os = $('.uploadpics');
            var len = os.length;
            for(var i = 0; i < len; i++){if($(os[i]).next().attr('class') != 'fileField'){var html = '<input type="hidden" value="'+$(os[i]).prev().attr('name')+'" name="fileField[]" class="fileField" />';$(os[i]).after(html);}}
            var os1 = $('.uploadpic');
            var len = os1.length;
            for(var i = 0; i < len; i++){if($(os1[i]).next().attr('class') != 'fileField'){var html = '<input type="hidden" value="'+$(os1[i]).prev().attr('name')+'" name="fileField[]" class="fileField" />';$(os1[i]).after(html);}}
             $('#'+f).attr('value', t.path);
             $.win.close(o);
         },'json');
     }
}

var dispTab = '1';
function showTab(obj){
    if(dispTab == null){dispTab = '1';}
    var id = obj.attr('id').replace('tab_','');
    if(id != dispTab){$('#file_'+id).show();$('#tab_'+id).attr('class','selected');$('#file_'+dispTab).hide();$('#tab_'+dispTab).attr('class','');dispTab = id;$.win.setCenter('iframe');}
}

function setUrlTextNull(obj){
    var text = obj.attr('value');if(text == '请您输入文件的绝对地址'){obj.attr('value','');}
}

function setUrlText(obj){
    var text = obj.attr('value');if(text == ''){obj.attr('value','请您输入文件的绝对地址');}
}

function fileSelected() {
    var file = document.getElementById('fileToUpload').files[0];
    if (file) {var fileSize = 0;if (file.size > 1024 * 1024){fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';}else{fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';}document.getElementById('fileName').innerHTML = 'Name: ' + file.name;document.getElementById('fileSize').innerHTML = 'Size: ' + fileSize;document.getElementById('fileType').innerHTML = 'Type: ' + file.type;}
}