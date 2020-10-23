/**
* Jquery模拟弹出窗口组件
*
* 本程序主要作用弹出窗口并居中
* 
* @category   Javascript
* @package    Javascript
* @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
var winId = 0;
$.win = {
    status:new Array(),
    resizeWidth:0,
    resizeHeight:0,
    resizeLeft:0,
    resizeTop:0,
    closeFunc:'',
    open:function(title, url, type, data, poses, iscover, btns){
        try{
            this.closeFunc = data.closeFunc;
            data.closeFunc = null;
        }catch(e){}
        if(btns == '' || btns == null){
            var btns = ['closeWin'];
        }else{
            var btns = btns.split(',');
        }
        var os = $('.openWin[optUrl='+url+']');
        if(os[0] != null){
            this.closeWinOpt(url);
        }else{
            this.getWinId();
            if(iscover == null){this.iscover = true;}else{this.iscover = iscover;}
            var html = this.getHtml(btns);
            var o = this;
            var winBg = $('body', window.parent.document).css('background-image').replace('url("','').replace('")','');
            var winBgs = winBg.split('/');//('http://cms/Apps/Tools/static/default/images/','');
            var sWinBg = winBgs[winBgs.length-1];
            if(sWinBg == 'defaultbg.jpg'){
                winBg = $('#webbg').attr('value');
            }
            $('body').append(html);
            $('#openWin_'+winId).css('background-size','cover').css('background-image','url('+winBg+')').css('filter',"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+winBg+"',sizingMethod='scale')'");
            if(title == null || title == ''){
                $('#tWin_'+winId).hide();
            }else{
                $('#titleWin_'+winId).text(title);
            }
            $('#closeWin_'+winId).click(function(){o.close($(this));});
            $('#maxWin_'+winId).click(function(){o.maxWin($(this));});
            $('#minWin_'+winId).click(function(){o.minWin($(this));});
            $('#resizeWin_'+winId).click(function(){o.resizeWin($(this));});
            if(type == 'pic'){
                this.displayPic(url, winId);
            }else if(type == 'txt'){
                this.displayTxt(data, winId);
            }else if(type == 'flash'){
                this.displayTxt(data, winId);
            }else if(type == 'alert'){
                this.displayAlert(data, winId);
            }else if(type == 'confirm'){
                this.displayConfirm(data, winId);
            }else{
                if(data == '' || data == null){
                    data = 'get';
                }
                this.load(url,winId,data, poses);
            }
    //        window.onresize = function(){$.win.setCenter(winId);}
            $('#openWin_'+winId).attr('optUrl', url);
            
     /*       $('select').hide(0, function(){changeSelect($(this));});
            $('input[type=radio]').hide(0, function(){changeRadio($(this))});*/
            $('.divselect').live('click', function(){showSelectList($(this));});
            $('.divselect').live('mouseleave', function(){hideSelectList($(this));});
            $('.divselect > ul > li').live('click', function(){return liSelect($(this));});
        }
    },
    resize:function(winId){
        var bheight = $('#cover_'+winId).outerHeight();
        var o = $('#openWin_'+winId);
        var winTop = parseInt(o.css('top'));
        var winHeight = parseInt(o.outerHeight());
        var winBody = $('#winBody_'+winId);
        var winpTop = parseInt(winBody.css('margin-top'));
        var winpBtm = parseInt(winBody.css('margin-bottom'));
        var tWin = $('#tWin_'+winId);
        var tWinHeight = parseInt(tWin.outerHeight());;
        var maxHeight = bheight-100;
        if(winHeight > maxHeight){
            var wBodyHeight = maxHeight - tWinHeight - winpTop - winpBtm;
            var wWidth = winBody.outerWidth();
            winBody.css('height', wBodyHeight).css('overflow','auto').css('width',(wWidth+30));
        }
    },
    displayAlert:function(txt, winId){
        var txtstr = '<div class="txtWinBox">';
        txtstr += '       <div class="txtBxo"><div class="alert"><em class="warring"></em><span style="line-height:72px;">'+txt+'</span></div></div>';
        txtstr += '       <div class="txtBtnBox"><input type="button" wId="'+winId+'" onclick="$.win.close($(this));" class="formcle" id="formcle" value="确定"/></div>';
        txtstr += '   </div>';
        $('#winBody_'+winId).html(txtstr);
        if(this.iscover){$('#cover_'+winId).show();}
        $('#openWin_'+winId).fadeIn('slow');
        this.setCenter(winId);
    },
    
    displayConfirm:function(txt, winId){
        var txtstr = '<div class="txtWinBox">';
        txtstr += '       <div class="txtBxo"><div class="alert"><em class="confirm"></em><span>'+txt+'</span></div></div>';
        txtstr += '       <div class="txtBtnBox"><input type="button" wId="'+winId+'" onclick="$.win.close($(this));" class="cancel" id="formcle" value="确定"/><input type="button" wId="'+winId+'" onclick="$.win.close($(this));return false;" class="formcle" id="formcle" value="取消"/></div>';
        txtstr += '   </div>';
        $('#winBody_'+winId).html(txtstr);
        if(this.iscover){$('#cover_'+winId).show();}
        $('#openWin_'+winId).fadeIn('slow');
        this.setCenter(winId);
    },
    
    displayTxt:function(txt,winId){
        var txtstr = '<div class="txtWinBox">';
        txtstr += '       <div class="txtBxo">'+txt+'</div>';
        txtstr += '       <div class="txtBtnBox"><input type="button" wId="'+winId+'" class="cancel" id="formcle" value="确定"/></div>';
        txtstr += '   </div>';
        $('#winBody_'+winId).html(txtstr);
        if(this.iscover){$('#cover_'+winId).show();}
        $('#openWin_'+winId).fadeIn('slow');
        this.setCenter(winId);
    },
    displayPic:function(url, winId){
        var html = '<img />';
        var obj = this;
        $(html).attr('src', url).load(function(){$('#winBody_'+winId).html($(this));if(obj.iscover){$('#cover_'+winId).show();}$('#openWin_'+winId).fadeIn('slow');obj.setCenter(winId);});
    },
    maxWin:function(obj){
        var baseObj = $(".desktop");
        var maxWidth = baseObj.outerWidth()-2;
        var maxHeight = baseObj.outerHeight()-2;
        var winId = obj.attr('id').replace('maxWin_','');
        var win = $('#openWin_'+winId);
        this.resizeWidth = win.outerWidth();
        this.resizeHeight = win.outerHeight();
        this.resizeLeft = win.css('left');
        this.resizeTop = win.css('top');
        win.css('top',0).css('left',0).css('width', maxWidth).css('height', maxHeight);
        $('#maxWin_'+winId).hide(0);
        $('#resizeWin_'+winId).removeClass('hide');
        this.status[winId] = 0;
    },
    minWin:function(obj){
        
    },
    resizeWin:function(obj){
        var baseObj = $(".desktop");
        var winId = obj.attr('id').replace('resizeWin_','');
        var win = $('#openWin_'+winId);
        win.css('top',this.resizeTop).css('left',this.resizeLeft).css('width', this.resizeWidth).css('height', this.resizeHeight);
        $('#maxWin_'+winId).show(0);
        $('#resizeWin_'+winId).addClass('hide');
//        this.status[winId] = 1;
    },
    close:function(obj){
        var wId = obj.attr('wId');
        var o = $('#closeWin_'+wId).parent().parent();
        o.remove();
        $('#cover_'+wId).remove();
        $('body').unbind('mousemove');
        try{eval(this.closeFunc);}catch(e){}
    },
    
    closeWinOpt:function(url){
        var o = $('.openWin[optUrl='+url+']');
        var wId = o.attr('id').replace('openWin_','');
        o.remove();
        $('#cover_'+wId).remove();
    },
    
    startDrag:function(e, winId){
        var obj = $('#openWin_'+winId);
        $('.openWin').css('z-index', 10010);
        obj.css('z-index',10011);
        this.x = parseInt(obj.css('left')) - e.clientX;
        this.y = parseInt(obj.css('top')) - e.clientY;
        this.status[winId] = 1;
    },
    stopDrag:function(winId){
        this.status[winId] = 0;var obj = $('#openWin_'+winId);obj.css('z-index',10010);
    },
    drag:function(e, winId){if(this.status[winId] == 1){var obj = $('#openWin_'+winId);obj.css({top:this.y + e.clientY,left:this.x +e.clientX});}},
    getHtml:function(btns){
        var str  = '';
        for(var i = 0; i < btns.length; i++){
            if(btns[i] == 'closeWin'){var icon = 'icon-remove-sign'}
            else if(btns[i] == 'maxWin'){var icon = 'icon-plus-sign'}
            else if(btns[i] == 'minWin'){var icon = 'icon-minus-sign'}
            else if(btns[i] == 'resizeWin'){var icon = 'icon-screenshot hide'}
            str += '<i class="icon-large '+icon+' '+btns[i]+'" wId="'+winId+'" id="'+btns[i]+'_'+winId+'"></i>';
        }
        var html = '<div id="cover_'+winId+'" wId="'+winId+'" class="cover"></div>';/*onclick="$.win.close($(this));"*/
        html += '<div id="openWin_'+winId+'" class="openWin">';
        html += '   <h1 id="tWin_'+winId+'" class="tWin"><i class="icon-small icon-desktop ticon"></i><span class="titleWin" id="titleWin_'+winId+'"></span>'+str+'</h1>'
        html += '   <div id="winBody_'+winId+'" class="winBody"></div>';
        html +='</div>';
        return html;
    },
    getWinId:function(opr){winId++;},
    repalceWId:function(t,wId){
        var arr = t.split('{wId}');
        var len = arr.length-1;
        for(var i = 0; i < len; i++){t = t.replace('{wId}','wId="'+wId+'"');}
        return t;
    },
    load:function(url, winId, data, poses){
        var o = this;
        var obj = $('#openWin_'+winId);
        obj.css('top', 0-1000);
        if(data == 'get'){
            $.get(url,{},function(t){t = o.repalceWId(t, winId);$('#winBody_'+winId).html(t);if(o.iscover){$('#cover_'+winId).show();}$('#openWin_'+winId).show();o.setCenter(winId, poses);},'html');
        }else{
            $.post(url,data,function(t){t = o.repalceWId(t, winId);$('#winBody_'+winId).html(t);if(o.iscover){$('#cover_'+winId).show();}$('#openWin_'+winId).show();o.setCenter(winId, poses);},'html');
        }
    },
    setCenter:function(winId, poses){
        var o = this;
        if(poses != null && poses != ''){
            var obj = $('#openWin_'+winId);
/*            obj.css('top', poses.top);*/
            if(poses.left != null){
                obj.css('left', poses.left);
            }
            if(poses.right != null && poses.left == null){
                var bodyWidth = $('body').attr('offsetWidth');
                var thiswidth = $('#openWin_'+winId).attr('clientWidth');
                poses.left = bodyWidth - poses.right-thiswidth;
                obj.css('left', poses.left);
            }
            obj.css('top', 0-poses.top);
            obj.hide();
            obj.animate({ opacity: 'toggle', top:50}, 500, function(){
                o.resize(winId);
            });
/*            obj.animate({'left':poses.left});*/
        }else{
            var obj = $('#openWin_'+winId);
            var sizes = this.getSize(winId);
            var width = sizes.width;
            var height = sizes.height;
            var h = $('body').attr('offsetHeight');
            var left = Math.round(($('body').attr('offsetWidth') - width)/2);
            var top = Math.round((h - height)/2);
            if(top < 1){top = 1;}
            if(left < 1){left = 1;}
/*            obj.css('top', top);*/
            obj.css('left', left);
            obj.css('top', 0-top);
            obj.hide();
            obj.animate({opacity: 'toggle',top:50}, 500, function(){
                o.resize(winId);
            });
/*            obj.animate({'left':left});*/
        }
        $('#openWin_'+winId).children('h1[class=tWin]').bind('mousedown', function(e){$.win.startDrag(e, winId);return false;});
        $('#openWin_'+winId).children('h1[class=tWin]').bind('mouseup', function(){$.win.stopDrag(winId);return false;});
        $('body').bind('mousemove', function(e){$.win.drag(e, winId);return false;});
    },
    setPosition:function(iframe){
        var coverObj = $('#cover');
        var winObj = $('#openWin_'+winId);
        var coverTop = coverObj.css('top');
        var winTop = winObj.css('top');
        var bodyTop = $('body').attr('scrollTop');
        coverObj.css('top', (coverTop+bodyTop));
        winTop.css('top', (winTop+bodyTop));
    },
    getSize:function(winId){
        var w = $('#openWin_'+winId).attr('offsetWidth');
        var h = $('#openWin_'+winId).attr('offsetHeight');
        var data = {'width':w,'height':h};
        return data;
    }
}