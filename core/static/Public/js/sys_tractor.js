$(document).ready(function() {
    /*拉伸事件*/
    /*向下*/
    $('.boxbottom').live('mousedown', function(e) {$.tuolaji.startExtrude($(this), 'bottom', e);});
    $('.boxbottom').live('mouseup', function(e) {$.tuolaji.stopBottom($(this));});
    /*向上*/
    $('.boxtop').live('mousedown', function(e) {$.tuolaji.startExtrude($(this), 'top', e);});
    $('.boxtop').live('mouseup', function(e) {$.tuolaji.stopBottom($(this));});
    /*向右*/
    $('.boxright').live('mousedown', function(e) {$.tuolaji.startExtrude($(this), 'right', e);});
    $('.boxright').live('mouseup', function(e) {$.tuolaji.stopBottom($(this));});
    /*向左*/
    $('.boxleft').live('mousedown', function(e) {$.tuolaji.startExtrude($(this), 'left', e);});
    $('.boxleft').live('mouseup', function(e) {$.tuolaji.stopBottom($(this));});
    /*右下*/
    $('.rb').live('mouseover', function() {$('.boxbottom').die('mousedown mouseup');});
    $('.rb').live('mouseout', function() {$('.boxbottom').live('mousedown', function(e) {$.tuolaji.startExtrude($(this), 'bottom', e);});$('.boxbottom').live('mouseup', function(e) {$.tuolaji.stopBottom($(this));});});
    $('.rb').live('mousedown', function(e) {$.tuolaji.startExtrude($(this).parent(), 'right_bottom', e);});
    $('.rb').live('mouseup', function(e) {$.tuolaji.stopBottom($(this));});
    /*左上*/
    $('.lt').live('mouseover', function() {$('.boxtop').die('mousedown mouseup');});
    $('.lt').live('mouseout', function() {$('.boxtop').live('mousedown', function(e) {$.tuolaji.startExtrude($(this), 'top', e);});$('.boxtop').live('mouseup', function(e) {$.tuolaji.stopBottom($(this));});});
    $('.lt').live('mousedown', function(e) {$.tuolaji.startExtrude($(this).parent(), 'left_top', e);});
    $('.lt').live('mouseup', function(e) {$.tuolaji.stopBottom($(this));});
    /*右上*/
    $('.rt').live('mouseover', function() {$('.boxtop').die('mousedown mouseup');});
    $('.rt').live('mouseout', function() {$('.boxtop').live('mousedown', function(e) {$.tuolaji.startExtrude($(this), 'top', e);});$('.boxtop').live('mouseup', function(e) {$.tuolaji.stopBottom($(this));});});
    $('.rt').live('mousedown', function(e) {$.tuolaji.startExtrude($(this).parent(), 'right_top', e);});
    $('.rt').live('mouseup', function(e) {$.tuolaji.stopBottom($(this));});
    /*左下*/
    $('.lb').live('mouseover', function() {$('.boxbottom').die('mousedown mouseup');});
    $('.lb').live('mouseout', function() {$('.boxbottom').live('mousedown', function(e) {$.tuolaji.startExtrude($(this), 'bottom', e);});$('.boxbottom').live('mouseup', function(e) {$.tuolaji.stopBottom($(this));});});
    $('.lb').live('mousedown', function(e) {$.tuolaji.startExtrude($(this).parent(), 'left_bottom', e);});
    $('.lb').live('mouseup', function(e) {$.tuolaji.stopBottom($(this));});
    /*移动事件*/
    $('.block').live('click', function(e) {$.tuolaji.addMoveBox($(this)); return false;});
    //$('.block').live('mousedown', function(e) {$.tuolaji.startDrag(e, $(this));return false});
    $('.block').live('mouseup', function() {$.tuolaji.stopDrag($(this));});
    $('.block').live('blur', function() {$.tuolaji.delMoveBox($(this));});
    $('.panel').live('click', function(e) {$.tuolaji.addMoveBox($(this));return false;});
    $('.panel').live('blur', function() {$.tuolaji.delMoveBox($(this));});
    $('.panel').live('mouseup', function() {$.tuolaji.stopDrag($(this));/*加入拖拽进入虚线框上方插入当前对象的事件*/});
    /*$('.panel').live('mouseover', function() {$.tuolaji.addInBox($(this));});*/
    $('#main').bind('mousemove', function(e) {$.tuolaji.drag(e);/*加入计算临时虚线框位置的事件*/$.tuolaji.panelDrag(e); return false;});
    $(window).scroll(function() {$.tuolaji.dragScroll();});
    $('.moveInBox').mouseover(function(){$(this).attr('class','moveInBox1')});
});

/**
 * extrudemouse：鼠标按下拉准备拉伸时的鼠标位置。
 * extrudestatus：是否允许拉伸的状态记录。
 * objStatus:当前要拉伸的对象的宽高左上等属性。
 * dragstatus：是否允许拖动的状态记录。
 * dragmouse：鼠标按下准备拖动时的鼠标位置。
 * dragid：当前对象的id值（数字）。
 * thisTop：当滚动条滚动式记录的当前对象的top值。
 */
$.tuolaji = {
    extrudemouse: {},
    extrudestatus: 0,
    objStatus: {},
    dragstatus: {},
    dragmouse: {},
    dragid: 0,
    thisTop: 0,
    panelHeight: 'auto',
    panelWidth: 'auto',
    panelStartTop:0,
    movePanelObj:{},
    panelBaseHeight:null,/*panel层的高度基数*/
    startExtrude: function(obj, direction, e) {
        var o = obj.parent().parent();
        this.dragid = parseInt(o.attr('id').replace('block_', ''));
        this.extrudestatus = 1;
        this.extrudemouse = {x: e.clientX, y: e.clientY};
        this.objStatus = {objh: parseInt(o.css('height')), objw: parseInt(o.css('width')), objtop: parseInt(o.css('top')), objleft: parseInt(o.css('left'))};
        $('#main').unbind('mousemove');
        var tuolaji = this;
        $('#main').bind('mousemove', function(mouse) {
            tuolaji.extrude(o, direction, mouse);
        });
    },
    objtop: function(e) {
        var h = this.extrudemouse.y - e.clientY;
        var height = this.objStatus.objh + h;
        var top = this.objStatus.objtop - h;
        return {height: height, top: top};
    },
    objbottom: function(e) {
        var h = e.clientY - this.extrudemouse.y;
        var height = this.objStatus.objh + h;
        return {'height': height};
    },
    objleft: function(e) {
        var w = this.extrudemouse.x - e.clientX;
        var width = this.objStatus.objw + w;
        var left = this.objStatus.objleft - w;
        return {width: width, left: left};
    },
    objleft1: function(e) {
        var w = this.extrudemouse.x - e.clientX;
        var width = this.objStatus.objw + w;
        var left = this.objStatus.objleft - w;
        return {width: width};
    },
    objright: function(e) {
        var w = e.clientX - this.extrudemouse.x;
        var width = this.objStatus.objw + w;
        return {width: width};
    },
    extrude:function(obj, direction, e) {
        this.dragid = parseInt(obj.attr('id').replace('block_', ''));
        this.dragstatus[this.dragid] = 0;
        if (this.extrudestatus == 1) {
            var directs = direction.split('_');
            for (var i in directs) {
                if(obj.attr('isblock') == 1 && (directs[i] == 'top' || directs[i] == 'left')  && obj.attr('class') != 'panel panelright'){
                    break;
                }
                if(obj.attr('isblock') == 1 && obj.attr('class') == 'panel panelright'){
                    if(directs[i] == 'left'){
                        var data =this.objleft1(e);
                    }else if(directs[i] == 'right' || directs[i] == 'top'){
                        break;
                    }else{
                        var data = eval('this.obj' + directs[i] + '(e)');
                    }
                }else{
                    var data = eval('this.obj' + directs[i] + '(e)');
                }
                var os = obj.parent();
                var maxWidth = parseInt(os.css('width'));
                var maxHeight = parseInt(os.css('height'));
                if(data.width >= maxWidth){
                    data.width = maxWidth;
                }
                if(os.attr('id') != 'main'){
                    if(data.height >= maxHeight){
                        data.width = maxWidth;
                    }
                }
                obj.css(data);
                /*this.setBorderSize(obj);*/
            }
        }
    },
/*    setBorderSize:function (obj){
        var height = obj.attr('offsetHeight');
        var width = obj.attr('offsetWidth');
        obj.children('div[class=border]').css({height:height-2, width:width-2});
    },*/

    addLayer:function (id){
        var layerid = 'layer_'+id;
        var html = '<div id="'+layerid+'" class="layer"><div class="eye"><em class="look"></em></div><div class="pic"><em></em></div><div class="txt"><span>'+id+'</span></div><div class="layopt"><a class="setLayer" href="javascript:void(0);"></a><a class="delLayer" href="javascript:void(0);"></a></div</div>';
        var obj = $("#layerslist");
        $("#layerslist").append(html);
        var sHeight = obj.attr('scrollHeight');
        obj.attr("scrollTop", sHeight);
    },

    stopBottom: function(obj) {
        var o = obj.parent().parent();
        this.dragid = parseInt(o.attr('id').replace('block_', ''));
        this.dragstatus[this.dragid] = 1;
        this.extrudestatus = 0;
        $('#main').unbind('mousemove');
        var tuolaji = this;
        $('#main').bind('mousemove', function(e) {
            tuolaji.drag(e);
        });
    },
    /*拖动方法*/
    startDrag: function(e, obj) {
        /*this.addMoveBox(obj);*/
        var objclass = obj.attr('class').split(' ');
        objclass = objclass[0];
        if(objclass == 'block'){
            this.dragid = parseInt(obj.attr('id').replace('block_', ''));
            this.dragmouse.x = parseInt(obj.css('left')) - e.clientX;
            this.dragmouse.y = parseInt(obj.css('top')) - e.clientY;
            this.dragstatus[this.dragid] = 1;
            $('#main').children('div[class=block]').css('z-index', 0);
        }else if(objclass == 'panel'){
            this.dragid = parseInt(obj.attr('id').replace('block_', ''));
            this.dragmouse.x = parseInt(obj.css('left')) - e.clientX;
            this.dragmouse.y = parseInt(obj.css('top')) - e.clientY;
            this.dragstatus[this.dragid] = 1;
            this.movePanelObj = obj;
            var outerHeight = obj.outerHeight();
            var outerWidth = obj.outerWidth();
            this.panelHeight = obj.css('height');
            this.panelWidth = obj.css('width');
            var html = '<span id="selfInBox" class="moveInBox"></span>';
            obj.after(html);
            $('#selfInBox').css({height:outerHeight-2,width:outerWidth-2,border:'1px dashed #666'});
            //取得初始位置;
            var index = obj.index();
            var h = 0;
            for(var i = 0; i < index; i++){
                var o = obj.parent().children().eq(i);
                h += parseInt(o.outerHeight());
            }
            $('#titleWin_2').html(h+','+index);
            var top = obj.offset().top-57+h;
            this.panelStartTop = top;
            obj.css({position:'absolute',height:outerHeight,width:outerWidth,top:top});
            obj.attr('isblock', '0');0
            $('#main').children('div[class=panel]').css('z-index', 0);
        }
        obj.css('z-index', 1);
    },
    
    panelDrag:function(e){
        var obj = $('#block_'+this.dragid);
        var objid = obj.attr("id");
        var panelBaseHeight = 57+obj.outerHeight();
        if(this.dragstatus[this.dragid] != 1){return false;}
        var btm = parseInt(obj.css('top'))+obj.outerHeight();
        $('.panel[id!='+objid+']').each(function(){
            var o = $(this);
            var y = o.offset().top - panelBaseHeight;
            y = y + o.height()/2;
            if(btm > y){
                $('#selfInBox').insertBefore(o);
            }
            
        });
    },
    
    /*addInBox:function(obj){
        try{
            if(this.dragstatus[this.dragid]!=1 && this.dragid != 0){
                //alert('dsfsdf');
            }
        }catch(e){}
    },*/

    selectLayer:function (obj){
        var objs = obj.parent().children('div[class=layer]');
        var len = objs.length;
        objs.css({background:'',color:'#333333'});
        for(var i = 0; i < len; i++){
            try{
                var id = $(objs[i]).attr('id').replace('layer_','');
            }catch(e){}
            $.tuolaji.delMoveBox($('#'+id));
        }
        try{
            var objid = obj.attr('id').replace('layer_','');
            this.addMoveBox($('#'+objid));
        }catch(e){}
        obj.css({background:'#3399ff',color:'#ffffff'});
        try{
            $("html,body").animate({scrollTop:$('#'+objid).offset().top-56},'slow');
        }catch(e){}
    },

    selectLayerKey:function(obj){
        var objs = obj.parent().children('div[class=layer]');
        var len = objs.length;
        objs.css({background:'',color:'#333333'});
        
            for(var i = 0; i < len; i++){
                try{
                    var id = $(objs[i]).attr('id').replace('layer_','');
                }catch(e){}
            }
        try{
            var objid = obj.attr('id').replace('layer_','');
        }catch(e){}
        obj.css({background:'#3399ff',color:'#ffffff'});
    },

    unselectLayerKey:function(obj){
        obj.css({background:'',color:'#333333'});
    },

    isHideLayer:function(obj){
        var objid = obj.parent().parent().attr('id').replace('layer_','');
        if(obj.attr('class') == 'look'){
            obj.removeClass('look');
            $('#'+objid).hide();
        }else{
            obj.addClass('look');
            $('#'+objid).show();
        }
    },

    addMoveBox: function(obj) {
        var o = this;
        var id = obj.attr('id');
        obj.parent().children('div[id!=' + id + ']').blur();
        obj.focus();
        var os = obj.children('dl[class=movebox]');
        if (os.length <= 0) {
            var html = '<dl class="movebox">';
            html += '<dt class="boxtop"><span class="lt"></span><span class="t"></span><span class="rt"></span><dt>';
            html += '<dt class="boxleft"><span class="l"></span><dt>';
            html += '<dt class="boxright"><span class="r"></span><dt>';
            html += '<dt class="boxbottom"><span class="lb"></span><span class="b"></span><span class="rb"></span><dt>';
            html += '</dl>';
            obj.append(html);
        }
        var o = $('#layer_'+id);
        this.selectLayerKey(o);
    },

    delMoveBox: function(obj) {
        var o = obj.children('dl');
        o.remove();
        var okey = $('#layer_'+obj.attr("id"));
        this.unselectLayerKey(okey);
    },
    stopDrag: function(obj) {
        var objclass = obj.attr('class').split(' ');
        objclass = objclass[0];
        var id = obj.attr('id').replace('block_', '');
        this.dragstatus[id] = 0;
        this.dragid = 0;
        if(objclass != 'block'){
            var data = {position:'relative','z-index':0, top:0,left:0};
            if(this.panelWidth != '0'){
                data['height'] = this.panelHeight;
            }else{
                data['height'] = 'auto';
            }
            if(this.panelWidth != '0'){
                data['width'] = this.panelWidth;
            }else{
                data['width'] = 'auto';
            }
            obj.css(data);
            $('#selfInBox').remove();
        }
    },
    drag: function(e) {
        if (this.dragstatus[this.dragid] == 1) {
            var obj = $('#block_' + this.dragid);
            var os = obj.children('dl[class=movebox]');
            var objclass = obj.attr('class').split(' ');
            objclass = objclass[0];
            if (os.length > 0 && (obj.attr('isblock') == null || obj.attr('isblock') == '0')) {
                var thisW = parseInt(obj.css('width'));
                var thisH = parseInt(obj.css('height'));
                var mainW = parseInt(obj.parent().css('width'));
                var mainH = parseInt(obj.parent().css('height'));
                var top = this.dragmouse.y + e.clientY;
                var tp = top
                if (top < 0) {
                    top = 0;
                }else if (top > mainH - thisH) {
                    if(obj.parent().attr('id') == 'main'){
                        obj.parent().css('min-height', top+thisH);
                    }else{
                        top = mainH - thisH;
                    }
                }
//                if(objclass == 'panel'){
//                    top = top - this.panelBaseHeight;
//                }
                var left = this.dragmouse.x + e.clientX;
                if (left < 0) {
                    left = 0;
                } else if (left > mainW - thisW) {
                    left = mainW - thisW;
                }
                this.thisTop = top;
                obj.css({top: top, left: left});
                
                if(objclass == 'panel'){
                    
                }
            }
        }
    },

    dragScroll: function() {
        if (this.dragstatus[this.dragid] == 1) {
            var sTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
            var obj = $('#block_' + this.dragid);
            if(obj.attr('isblock') != 1){
                var top = this.thisTop + sTop;
                obj.css('top', top);
            }
        }
    },

    movePixel:function(direction, obj){
        if(obj.attr('isblock') == 1){return false;}
        switch(direction){
            case 'left':
                var left = parseInt(obj.css('left'))-1;
                if(left <= 0){left = 0;}
                obj.css('left', left);
                break;
            case 'right':
                var mainW = parseInt($('#main').css('width'));
                var thisW = parseInt(obj.css('width'));
                var maxLeft = mainW-thisW;
                var left = parseInt(obj.css('left'))+1;
                if(left >= maxLeft){left = maxLeft};
                obj.css('left', left);
                break;
            case 'up':
                    var top = parseInt(obj.css('top'))-1;
                    if(top <= 0){top = 0;}
                    obj.css('top', top);
                break;
            case 'down':
                    var thisH = parseInt(obj.css('height'));
                    var mainH = parseInt($('#main').css('height'));
                    var top = parseInt(obj.css('top'))+1;
                    obj.css('top', top);
                    if (top > mainH - thisH) {
                        $('#main').css('min-height', top+thisH);
                    }
                break;
        }
        return false;
    },

    deleteObj:function(obj, id){
        if(confirm('您确认要删除这个层吗？')){
            var o = obj.parent();
            obj.remove();
            if(o.attr('class') == 'panel panelall'){
                if(o.html() == '<div class="clear"></div>'){
                    o.html('<b class="blockTitle">'+o.attr('title')+'</b><div class="clear"></div>');
                }
            }else{
                if(o.html() == ''){
                    o.html('<b class="blockTitle">'+o.attr('title')+'</b>');
                }
            }
            if(id == null){
                var id = obj.attr('id');
            }
            var okey = $('#layer_'+id);
            okey.remove();
        }else{
            this.addMoveBox(obj);
        }
    },
};