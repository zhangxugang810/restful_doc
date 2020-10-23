$.rightMenu = {
    menus:{},
    obj:{},
    menuStyle:'default',
    styleHtml:'',
    path:'',
    filename:'',
    appid:0,
    id:'',
    appName: '',
    showMenus:function(obj){
        $('.rgMenu').remove();
        $('.rgMenuStyle').remove();
        this.appName = obj.children('div').children('span').attr('appName');
        var html = this.styleHtml + this.getMenuHtml(this.menus);
        this.obj = obj;
        this.path = obj.children('div').children('span').attr('path');
        this.filename = obj.children('div').children('span').html();
        this.appid = obj.attr('appid');
        this.id = obj.attr("id");
        $('body').append(html);
    },
    
    setPosition:function(x, y){
        $('.rgMenu').css('top', y).css('left', x);
        $('.rgMenu').focus();
        $('.rgMenu').live('blur', function(){$(this).fadeOut('fast', function(){$(this).remove();$('#rgMenuStyle').remove();});});
    },
    
    getMenuHtml:function(data){
        var html = '<div class="rgMenu" tabindex="0">';
        for(var i in data){
            if(data[i].url == ''){
                data[i].url = 'javascript:void(0);';
            }
            if(data[i].click != ''){
                var clickstr = 'onclick="'+data[i].click+'"';
            }else{
                clickstr = '';
            }
            if(data[i].uploadPath != '' && data[i].uploadPath != null){
                var uploadPathStr = ' uploadPath="'+data[i].uploadPath+'"';
            }
            if(this.appName != '' && this.appName != null){
                var appNameStr = ' appName="'+this.appName+'"';
            }
            
            if(data[i].name == '-'){
                html += '<b><i></i><strong></strong></b>';
            }else{
                html += '<a href="'+data[i].url+'" '+clickstr+uploadPathStr+appNameStr+'><em></em><span>'+data[i].name+'</span><i>'+data[i].shortcut+'</i></a>';
            }
        }
        html += '</div>';
        return html;
    },
    
    uploadPic:function(obj){
        var appName = obj.attr('appName');
        var paths = this.path.split('/');
        if(paths.length == 3){
            var path = this.path+'/images';
        }else if(paths.length == 4){
            var path = this.path;
        }else if(paths.length == 5){
            var path = paths[0]+'/'+paths[1]+'/'+paths[2]+'/images';
        }
        path = './Apps/'+appName+'/'+path;
        obj.attr('uploadPath', path);
        uploadPic(obj);
    },
    
    setStyle:function(style){
        if(style != null && style != ''){
            this.menuStyle = style;
        }
        var func = 'this.'+style+'Style()';
        var styleHtml = eval(func);
        styleHtml;
    },
    
    setObj:function(obj){
        this.obj = obj;
    },
    
    addMenu:function (menu){
        var len = this.menus;
        this.menus[len] = menu;
    },
    
    addMenus:function (menus){
        this.menus = menus;
    },
    
    defaultStyle:function(){
        var styleHtml = '<style id="rgMenuStyle" type="text/css">';
        styleHtml += '.rgMenu{position:absolute;border:1px solid #a0a0a0; background:#f0f0f0;z-index: 10000; padding:3px 0; font-family:"Microsoft Yahei";}';
        styleHtml += '.rgMenu a{display:block; height:29px; line-height: 29px;}';
        styleHtml += '.rgMenu a:hover{height:25px; border-radius: 3px; line-height: 25px;margin:1px 2px; background:rgba(51,153,255, 0.1); border:1px solid #aecff7;}';
        styleHtml += '.rgMenu a em{display:block; float:left; width:29px;height:29px; border-right:1px solid #e2e3e3;}';
        styleHtml += '.rgMenu a span{display:block; float:left; width:100px;height:29px;border-left:1px solid #ffffff;padding:0 0 0 10px; font-size:12px; color:#333;}';
        styleHtml += '.rgMenu a i{display:block; float:left; width:90px;height:29px; text-align:right;color:#333; font-size:12px;font-style:normal; padding:0 10px 0 0;}';
        styleHtml += '.rgMenu a:hover em{width:26px;height:25px;}';
        styleHtml += '.rgMenu a:hover span{width:97px;height:25px;}';
        styleHtml += '.rgMenu b{display:block;height:2px;line-height:0; margin:1px 0;}';
        styleHtml += '.rgMenu b i{display:block; height:2px;border-right:1px solid #e2e3e3;float:left;width:29px;}';
        styleHtml += '.rgMenu b strong{display:block;border-top:1px solid #e2e3e3;width:200px;border-bottom:1px solid #ffffff;border-left:1px solid #ffffff;float:left;padding:0 0 0 10px;}';
        styleHtml += '</style>';
        this.styleHtml = styleHtml;
    },
    
    blackStyle:function(){
        var styleHtml = '<style id="rgMenuStyle" type="text/css">';
        styleHtml += '.rgMenu{position:absolute;border:1px solid #666; background:#000;z-index: 10000; padding:3px 0; font-family:"Microsoft Yahei";}';
        styleHtml += '.rgMenu a{display:block; height:29px; line-height: 29px;}';
        styleHtml += '.rgMenu a:hover{background:rgba(255,255,255, 0.1);}';
        styleHtml += '.rgMenu a em{display:block; float:left; width:29px;height:29px; border-right:1px solid #666; color:#666;}';
        styleHtml += '.rgMenu a span{display:block; float:left; width:100px;height:29px;border-left:0;padding:0 0 0 10px; font-size:12px; color:#666;}';
        styleHtml += '.rgMenu a i{display:block; float:left; width:90px;height:29px; text-align:right;color:#666; font-size:12px;font-style:normal; padding:0 10px 0 0;}';
        styleHtml += '.rgMenu b{display:block;height:1px;line-height:0; margin:0;}';
        styleHtml += '.rgMenu b i{display:block; height:1px;border-right:1px solid #666;float:left;width:29px;}';
        styleHtml += '.rgMenu b strong{display:block;border-top:1px solid #666;width:200px;border-bottom:0;border-left:0;float:left;padding:0 0 0 10px;}';
        styleHtml += '</style>';
        this.styleHtml = styleHtml;
    },
    
    doCreateController:function(){
        var url = U('Tools/Programer/createController');
        var data = {'path':this.path, 'filename':this.filename};
        $.post(url, data, function(t){
            if(t.result){
                alert('创建成功！');
            }
        }, 'json'); 
    },
    
    editFile:function (obj){
        var appid = this.appid;
        var file = this.path;
        var filename = this.path;
        var of = this;
        var data = {appid:appid, file: file};
        var url = U(define.APP_NAME+'/'+define.MODEL_NAME+'/editFile');
        $.post(url, data, function(t){
            if(t.result == true){
                var type = getExtName(file);
                var icontype = getIconType(type);
                if(type == 'js' || type == 'css' || type == 'php' || type == 'html' || type == 'htm' || type == 'tpl' || type == 'txt'){
                    var o = $('#editor');
                    var fname = processFilename(file, '.', '_');
                    var fname = processFilename(fname, '/', '_');
                    var editorid = 'editor_'+fname;
                    var curObj = $('#'+editorid);
                    if(curObj[0] == null){
                        of.newEditor(editorid, type, filename, icontype, fname, t.content);
                    }else{
                        $('.editortabs').children('a').removeClass('current');
                        $('#tab_'+fname).addClass('current');
                        $('.editor').hide(0);
                        $('#'+editorid).show(0);
                    }
                }else if(type == 'jpg' || type == 'jpeg' || type == 'gif' || type == 'png' || type == 'bmp'){
                    var path = '/Apps/'+obj.attr('appName')+'/'+filename;
                    $.win.open('查看文件', path, 'pic');
                }else{
                    alert('非文本类型文件，不可编辑');
                }
            }
        }, 'json');
    },
    
    newEditor:function(editorid, type, filename, icontype, fname, content){
        var of = this;
        var o = $('#editor');
        $('.editor').hide(0);
        o.before('<div id="'+editorid+'" class="editor" style="font-size:14px;"></div>');
        if(type == 'js'){type = 'javascript';}
        var ts = "ace/mode/"+type;
        if(type != 'php'){$('#curTheme').html('<script src="/core/static/Public/js/sys_ace/mode-'+type+'.js" language="javascript">');}
        var editor = ace.edit(editorid);
        var mode = ace.require(ts).Mode;
        editor.setTheme("ace/theme/ambiance");
        editor.getSession().setMode(new mode());
        editor.setValue(content);
        editor.gotoLine(0);
        var ofname = of.baseName(filename);
        $('.editortabs').children('a').removeClass('current');
        $('.editortabs').append('<a class="tab current" id="tab_'+fname+'" href="javascript:void(0);" editor="Controller"><b class="fileIcon '+icontype+'"></b><span>'+ofname+'</span><em class="closeTabBtn"></em></a>');
    },
    
    baseName:function(path){
        var paths = path.split('/');
        return paths[paths.length-1];
    },
    
    createFile:function(type){
        var url = U(define.APP_NAME+'/'+define.MODEL_NAME+'/createFile');
        var data = {appid:this.appid, type:type};
        $.win.open('创建控制器文件', url, '', data);
    },
    
    doCreateFile:function(obj){
        var o = this;
        var appid = $('#appid').attr('value');
        var type = $('#type').attr('value');
        var extName = $('#extName').attr('value');
        var fileName = $('#fileName').attr('value');
        var data = {appid:appid, type:type, extName:extName, fileName:fileName, filePath:this.path};
        var url = U(define.APP_NAME+'/'+define.MODEL_NAME+'/doCreateFile');
        $.post(url, data, function(t){
            if(t.result){
                o.appid = appid;
                o.path = t.returnPath;
                o.editFile();
                o.refreshFileList(appid);
                $.win.close(obj);
            }else{
                alert('文件已存在，请使用其他文件名');
            }
        }, 'json');
    },
    
    getPrototype:function(){
        var appid = this.appid;
        var file = this.path;
        var filename = this.path;
        var data = {appid:appid, file: file};
        var url = U(define.APP_NAME+'/'+define.MODEL_NAME+'/getPrototype');
        $.post(url, data, function(t){
            $.win.open('属性', U(define.APP_NAME+'/'+define.MODEL_NAME+'/showPrototype'), '', t);
        }, 'json');
    },
    
    refreshFileList:function(appid){
        var data = {appid:appid};
        var html = '';
        var url = U(define.APP_NAME+'/'+define.MODEL_NAME+'/getAppFilesList');
        $.post(url, data, function(t){
            if(t.result){
                for(var i in t.data){
                    if(t.data[i].type == 'dir'){
                        var str = 'tabindex="0" class="getDirList"';
                        var str1 = '<i class="icon icon-folder-close"></i>';
                    }else{
                        var str = 'class="editFile"';
                        var str1 = '<i class="icon icon-file"></i>';
                    }
                    html += '<li '+str+' appid="'+appid+'">';
                    html += '    <div class="fileTitle">';
                    html += '        <em>'+str1+'</em>';
                    html += '        <span path="'+t.data[i].name+'">'+t.data[i].name+'</span>';
                    html += '    </div>';
                    html += '    <div class="clear"></div>';
                    html += '</li>';
                }
                $('#fileList').html(html);
            }
        },'json');
    },
    
    deleteFile:function(){
        if(confirm('您确认要删除这个文件吗？')){
            var o = this;
            var data = {path:this.path, appid:this.appid};
            var url = U(define.APP_NAME+'/'+define.MODEL_NAME+'/deleteFile');
            $.post(url, data, function(t){
                if(t.result){
                    var ob = $('#'+o.id);
                    ob.remove();
                    try{
                        var path = ob.children('div').children('span').attr('path');
                        path = o.replaceStr('/', '_', path);
                        path = 'tab_'+o.replaceStr('.', '_', path);
                        var ospan = $('#'+path).children('span');
                        closeEditor(ospan);
                    }catch(e){}
                }
            }, 'json');
        }
    },
    
    replaceStr:function(search, replace, str){
        var rlen = replace.length;
        var strs = str.split(search);
        var pstr = '';
        for(var i = 0; i < strs.length; i++){
            pstr += replace+strs[i];
        }
        pstr = pstr.replace(replace, '');
        return pstr;
    },
    
    createDir:function(type){
        var url = U(define.APP_NAME+'/'+define.MODEL_NAME+'/createDir');
        var data = {appid:this.appid, type:type, path:this.path};
        $.win.open('创建文件夹', url, '', data);
    },
    
    doCreateDir:function(){
        var o = this;
        var type = $('#type').attr('value');
        var dirName = $('#dirName').attr('value');
        var url = U(define.APP_NAME+'/'+define.MODEL_NAME+'/doCreateDir');
        var appid = this.appid;
        var data = {appid:this.appid, type:type, dirName:dirName, path:this.path};
        $.post(url, data, function(t){
            var obj = $('#createDir');
            o.refreshFileList(appid);
            $.win.close(obj);
        }, 'json');
    }
}