$(document).ready(function(){
    /*删除单条数据的事件*/
//    $('.deleteOne').live('click', function(){deleteOne($(this));return false;});
    $(document).on('click', '.deleteOne', function(){deleteOne($(this));return false;});
//    $('.editOne').live('click', function(){editOne($(this));return false;});
    $(document).on('click', '.editOne', function(){editOne($(this));return false;});
//    $('.addOne').live('click', function(){addOne($(this));return false;});
    $(document).on('click', '.addOne', function(){addOne($(this));return false;});
//    $('.auditOne').live('click', function(){auditOne($(this));return false;});
    $(document).on('click', '.auditOne', function(){auditOne($(this));return false;});
    /*删除多条数据的事件*/
    $('.deleteSel').click(function(){delSel($(this));return false;});
    /*上传单张图片事件*/
//    $('.uploadpic').live('click', function(){uploadPic($(this));return false;});
    $(document).on('click', '.uploadpic', function(){uploadPic($(this));return false;});
    /*上传多张图片事件*/
//    $('.uploadpics').live('click', function(){uploadPic($(this), 'More');return false;});
    $(document).on('click', '.uploadpics', function(){uploadPic($(this), 'More');return false;});
     /*上传单个文件事件*/
//    $('.uploadfile').live('click', function(){uploadPic($(this));return false;});
    $(document).on('click', '.uploadfile', function(){uploadPic($(this));return false;});
    /*上传多个文件事件*/
//    $('.uploadfiles').live('click', function(){uploadPic($(this), 'More');return false;});
    $(document).on('click', '.uploadfiles', function(){uploadPic($(this), 'More');return false;});
    /*删除批量上传的图片*/
//    $('#clearPics').live('click', function(){clearFiles();});
    $(document).on('click', '#clearPics', function(){clearFiles();});
    /*全选事件*/
    $('.selectAll').click(function(){selectOther();});
    /*保存排序事件*/
    $('.saveOrders').click(function(){saveOrders($(this));});
    /*预览图片*/
//    $('.lookPic').live('click', function(){lookPic($(this)); return false;});
    $(document).on('click', '.lookPic', function(){lookPic($(this)); return false;});
    $('.recommend').click(function(){recommendOne($(this));return false;});
//    $('.divselect[selectid=recommend_ord] > ul >li').live('click', function(){recommends($(this));return false;});
    $(document).on('click', '.divselect[selectid=recommend_ord] > ul >li', function(){recommends($(this));return false;});
    /*单选弹出调用事件*/
    $('.getMoreData').click(function(){
        /*这个暂时未考虑清楚应该如何做
        1.应该调用哪个模块的数据
        2.是否可搜索
        3.点击确定以后如何保存到页面和保存到数据库
        */
    });
//    $('.goUrl').live('click', function(){goUrl($(this));return false;});
    $(document).one('click', '.goUrl', function(){goUrl($(this));return false;});
    
    /*多选弹出调用事件*/
    $('.getOneData').click(function(){
        /*这个暂时未考虑清楚应该如何做
        1.应该调用哪个模块的数据
        2.是否可搜索
        3.点击确定以后如何保存到页面和保存到数据库
        */
    });
//    $('.cancel').live('click',function(){$.win.close($(this));});
    $(document).on('click', '.cancel', function(){$.win.close($(this));});
//    $('.date').live('click', function(){$.dateSelector.open($(this));});
    $(document).on('click', '.date', function(){$.dateSelector.open($(this));});
//    $('.datetime').live('click', function(){$.dateSelector.open($(this));});
    $(document).on('click', '.datetime', function(){$.dateSelector.open($(this));});
//    $('.gradeselect').live('change', function(){getSonsList($(this))});
    $(document).on('change', '.gradeselect', function(){getSonsList($(this))});
//    $('.areaselect').live('change', function(){getAreaseSonsList($(this))});
    $(document).on('change', '.areaselect', function(){getAreaseSonsList($(this))});
//    $('.selectOne').live('click', function(){getMdls($(this), 'one');});
    $(document).on('click', '.selectOne', function(){getMdls($(this), 'one');});
//    $('.selectMore').live('click', function(){getMdls($(this), 'more');});
    $(document).on('click', '.selectMore', function(){getMdls($(this), 'more');});
//    $('.goAjaxPage').live('click', function(){goAjaxPage($(this));});
    $(document).on('click', '.goAjaxPage', function(){goAjaxPage($(this));});
    
//    $('td').live('mouseover',function(){$(this).parent().attr('class', 'listbg');});
    $(document).on('mouseover', 'td', function(){$(this).parent().attr('class', 'listbg');});
//    $('td').live('mouseout',function(){$(this).parent().attr('class', 'list_m_bg');});
    $(document).on('mouseout', 'td', function(){$(this).parent().attr('class', 'list_m_bg');});
    $('.changeCode').click(function(){changeCode($(this));});
    $('select').hide(0, function(){changeSelect($(this));});
    $('input[type=radio]').hide(0, function(){changeRadio($(this))});
//    $('input[type=checkbox]').hide(0, function(){changeCheckBox($(this))});
//    $('.divselect').live('click', function(){showSelectList($(this));});
    $(document).on('click', '.divselect', function(){showSelectList($(this));});
//    $('.divselect').live('mouseleave', function(){hideSelectList($(this));});
    $(document).on('mouseleave', '.divselect', function(){hideSelectList($(this));});
//    $('.divselect > ul > li').live('click', function(){return liSelect($(this));});
    $(document).on('click', '.divselect > ul > li', function(){return liSelect($(this));});
//    $('.divselect > span[contenteditable=true]').live('keyup', function(){return setSelect($(this));});
    $(document).on('keyup', '.divselect > span[contenteditable=true]', function(){return setSelect($(this));});
});

function setSelect(obj){
    var html = obj.html();
    $('.divselect > input[type=text]').attr('value', html);
}

function recommends(obj){
    var recommend = $('#recommend').attr('value');
    if(recommend == '' || recommend == null){
        alert('请选择您要做的操作！');
        return false;
    }
    var ord = obj.attr('value');
    var cbox = $('input[type=checkbox]');
    var len = cbox.length;
    var ids = [];
    var n = 0;
    for(var i = 0; i < len; i++){
        if($(cbox[i]).attr('checked')){
            ids[n] = $(cbox[i]).attr('value');
            n++;
        }
    }
    if(ids == null || ids == '' || ids == []){
        alert('您没有选择要操作的数据！');
    }else{
        var url = $('#recommends_url').attr('value');
        var data = {'ids':ids, ord:ord, recommend:recommend};
        $.post(url, data, function(t){
            if(t.result == true){
                window.location.reload();
            }else{
                alert(t.msg);
            }
        },'json');
    }
}

function recommendOne(obj){
    var ord = obj.attr('ord');
    var id = obj.attr("id").replace('list_','');
    var url = obj.attr('url');
    $.post(url, {ord:ord, id:id}, function(t){
        if(t.result == true){
            if(t.recommend == 1){
                obj.removeClass('button-red');
                obj.addClass('button-green');
                obj.attr("title",'点击取消推荐到'+obj.html());
            }else{
                obj.addClass('button-red');
                obj.removeClass('button-green');
                obj.attr("title",'点击推荐到'+obj.html());
            }
        }else{
            alert(t.msg);
        }
    },'json');
}

function goUrl(obj){
    var url = obj.attr('url');
    window.location.href=url;
}

function auditOne(obj){
    var id = parseInt(obj.attr("id"));
    var url = obj.attr('url');
    $.post(url, {id:id}, function(t){
        if(t.result == true){
            if(t.audit == 1){
                obj.html('<span class="green" title="点击变为未通过审核">已通过</span>');
            }else if(t.audit == 2){
                obj.html('<span class="red" title="点击变为已通过审核">未审核</span>');
            }
        }else{
            alert('操作失败');
        }
    },'json');
}

function changeCheckBox(o){
    var text = o.next().html();
    o.next().remove();
    var html = '<div class="checkboxDiv"></div>';
    var n = $(html);
    o.before(n);
    n.append(o);
    if(o.attr('checked')){
        var cls = ' on';
        var txt = '<i class="icon-large icon-ok"></i>';
        var bcolor = '#4CAE4C';
    }else{
        var cls = ' off';
        var txt = '<i class="icon-large icon-remove"></i>';
        var bcolor = '#D43F3A';
    }
    if(text != '' && text != null){
        n.append('<div onclick="selectCheckBox($(this));" class="checkboxbg'+cls+'">'+txt+'</div>');
        n.append('<div class="checkBoxText">'+text+'</div>');
    }else{
        n.append('<div onclick="selectCheckBox($(this));" class="checkboxbg'+cls+'" style="border-radius:4px 4px 4px 4px; border-right:1px solid '+bcolor+';">'+txt+'</div>');
    }
    o.hide();
}

function selectCheckBox(obj){
    var o = obj.parent().children('input[type=checkbox]');
    if(!o.attr('checked')){
        o.attr('checked', true);
        obj.html('<i class="icon-large icon-ok"></i>');
        obj.removeClass('off');
        obj.addClass('on');
        if(obj.css('border-right-width') == '1px'){
            obj.css('border-right-color','#4CAE4C');
        }
    }else{
        o.attr('checked', false);
        obj.html('<i class="icon-large icon-remove"></i>');
        obj.removeClass('on');
        obj.addClass('off');
        if(obj.css('border-right-width') == '1px'){
            obj.css('border-right-color','#D43F3A');
        }
    }
}

var v = '';
function changeRadio(o){
    var len = o.parent().children('input[type=radio]').length;
    var n = o.next();
    var checked = '';
    var str = '';
    var name = o.attr('name');
    var os1 = o.parent().children('input[type=radio]');
    for(var i = 0; i < len; i++){
        if($(os1[i]).attr('checked') == true){v = $(os1[i]).attr('value');break;}
    }
    var cls = o.attr('class');
    if(cls != '' && cls != null){cls = 'class="'+cls+'"';}else{cls = '';}
    var checkinput = o.attr('checkinput');
    if(checkinput != '' && checkinput != null){checkinput = 'checkinput="'+checkinput+'"';}else{checkinput = '';}
    var checklen = o.attr('checklen');
    if(checklen != '' && checklen != null){checklen = 'checklen="'+checklen+'"';}else{checklen = '';}
    if(o.attr('checked')){
        checked = ' checked';
        str = '<input style="display:none;" type="text" name="'+name+'" id="'+name+'" '+checkinput+' '+checklen+' '+cls+' value="'+o.attr('value')+'" />';
    }
    var html = '<div cv="'+o.attr('value')+'" class="radioDiv'+checked+'"></div>';
    var d = $(html);
    o.before(d);
    d.html(n);
    var first = d.parent().children('div').first();
    first.addClass('border-left');
    first.addClass('radiusleft');
    var os = d.parent().children('div');
    if(len == 1){
        d.addClass('radiusright');
        if(str == ''){str = '<input style="display:none;" type="text" name="'+name+'" id="'+name+'" '+checkinput+' '+checklen+' '+' '+checked+' '+cls+'  value="" />';}
    }
    o.remove();
    if(o[0] == null){d.after(str);}
    d.click(function(){selectRadio($(this), name)});
}

function selectRadio(obj, changeId){
    var p = obj.parent();
    p.children('div').removeClass('checked');
    obj.addClass('checked');
    $('#'+changeId).attr('value', obj.attr('cv'));
    $('#'+changeId).click();
}

function liSelect(obj){
    var html = obj.html();
    var v = obj.attr('value');
    var o = obj.parent().parent().children('input');
    var attrib = $.param($(obj[0].attributes));
    attrib = unparam(attrib, true);
    for(var key in attrib){if(key != 'class'){o.attr(key, attrib[key]);}}
    o.change();
    var o1 = obj.parent().parent().children('span');
    o1.attr('text', v);
    o1.html(html);
    obj.parent().slideUp('fast');
    return false;
}

function hideSelectList(obj){
    obj.css('z-index','1');
    var o = obj.children('ul');
    o.slideUp('fast');
}

function showSelectList(obj){
    $('.divselect').css('z-index','1');
    obj.css('z-index','2');
    var o = obj.children('ul');
    o.slideDown('fast');
}

function changeSelect(obj){
    var objlen = obj.length;
    var contenteditable = obj.attr('contenteditable');
    for(var i = 0; i < objlen; i++){
        var sobj = $(obj[i]);
        var id = sobj.attr('id');
        var name = sobj.attr('name');
        var objs = sobj.children('option');
        var checkinput = sobj.attr('checkinput');
        var grade = sobj.attr('grade');
        if(grade != '' && grade != null){var gradestr = 'grade="'+grade+'"';}
        var checkstr = '';
        if(checkinput != '' && checkinput != null){checkstr += ' checkinput="'+checkinput+'" ';}
        var checklen = sobj.attr('len');
        if(checklen != '' && checklen != null){checkstr += ' len="'+checklen+'" ';}
        var checkajax = obj.attr('checkajax');
        if(checkajax != '' && checkajax != null){checkstr += ' checkajax="'+checkajax+'" ';}
        var fieldname = sobj.attr('fieldname');
        if(fieldname != '' && fieldname != null){checkstr += ' fieldname="'+fieldname+'" ';}
        var fname = sobj.attr('fname');
        if(fname != '' && fname != null){checkstr += ' fname="'+fname+'" ';}
        var mtag = sobj.attr('mtag');
        if(mtag != '' && mtag != null){checkstr += ' mtag="'+mtag+'" ';}
        var func = sobj.attr('func');
        if(func != '' && func != null){checkstr += ' func="'+func+'" ';}
        var def = sobj.attr('def');
        if(def != '' && def != null){checkstr += ' def="'+def+'" ';}

        var selValue = sobj.children('option[selected]').attr('value');
        var selText = sobj.children('option[selected]').html();
        if(selValue == null){selValue = sobj.children('option:first').attr('value');}
        if(selText == null || selText == ''){selText = sobj.children('option:first').html();}
        var firstTxt = sobj.children('option:first').html();
        

        var len = objs.length;
        var str = '<div selectId="'+sobj.attr('id')+'" '+gradestr+' class="divselect"><ul>'
        var cls = sobj.attr('class');
        for(var n = 0; n < len; n++){
            var o = $(objs[n]);
            var attrib = $.param($(objs[n].attributes));
            attrib = unparam(attrib);
            if(o.attr('value') == selValue){
                var formtag = $(objs[n]).attr("formtag");
                str += '<li class="selected" '+attrib+'>'+ o.html()+'</li>';
            }else{
                str += '<li '+attrib+'>'+ o.html()+'</li>';
            }
        }
        if(formtag != '' && formtag != null){checkstr += ' formtag="'+formtag+'" ';}
        if(contenteditable == 'true'){var contenteditablestr = 'contenteditable="true"';}
        str += '</ul><span id="span_'+id+'" tabindex="-1" value="'+selValue+'" '+contenteditablestr+'>'+selText+'</span><em></em><input '+checkstr+' txt="'+selText+'" firstTxt="'+firstTxt+'" class="'+cls+'" type="text" style="display:none;" id="'+id+'" name="'+name+'" value="'+selValue+'"></div>';
        sobj.after(str);
        sobj.remove();
    }
}

function unparam(str, isarr){
    if(isarr == null){
        isarr = false;
    }
    var d = str.split('&');
    if(isarr == false){
        var data = '';
    }else{
        var data = {};
    }
    for(var i in d){
        var arr = d[i].split('=');
        if(isarr == false){
            data += decodeURI(arr[0])+'="'+decodeURI(arr[1])+'" ';
        }else{
            data[decodeURI(arr[0])] = decodeURI(arr[1]);
        }
    }
    return data;
}

function changeCode(obj){
    var rnd = Math.round(Math.random()*1000000);
    obj.children('img').attr('src', (obj.attr('url')+'?v='+rnd));
}

function goAjaxPage(obj){
    var fieldtag = $('#fieldtag').attr('value');
    var mtag = $('#datamtag').attr('value');
    var type = $('#dataliststype').attr('value');
    var page = obj.attr('page');
    var data = {'keyword':$('#selectKeyword').attr('value'),'mtag':mtag,'type':type,'field':fieldtag,'json':"json",'page':page};
    var url = U('Admin/Mdls/getDataList');
    $.post(url, data, function(t){
        if(t.result == true){
            var str = '';
            var idkey = mtag+'id';
            for(var i in t.list.rows){
                str += '<ul class="lists" id="list_'+t.list.rows[i][idkey]+'">';
                str += '    <li class="lisel">';
                if(type == 'one'){
                    str += '<input type="radio" name="'+idkey+'" value="'+t.list.rows[i][idkey]+'" />';
                }else if(type == 'more'){
                    str += '<input name="'+idkey+'" value="'+t.list.rows[i][idkey]+'" type="checkbox" />';
                }
                str += '    <li class="litext">'+t.list.rows[i][fieldtag]+'</li>';
                str += '</ul>';
            }
            str += '<div class="pageList">'+t.list.pageStr+'</div>';
        }else{
            str = '未找到数据！';
        }
        $('#selectBody').html(str);
    },'json');
}

function selectSearch(){
    var fieldtag = $('#fieldtag').attr('value');
    var mtag = $('#datamtag').attr('value');
    var type = $('#dataliststype').attr('value');
    var data = {'keyword':$('#selectKeyword').attr('value'),'mtag':mtag,'type':type,'field':fieldtag,'json':"json"};
    var url = U('Admin/Mdls/getDataList');
    $.post(url, data, function(t){
        if(t.result == true){
            var str = '';
            var idkey = mtag+'id';
            for(var i in t.list.rows){
                str += '<ul class="lists" id="list_'+t.list.rows[i][idkey]+'">';
                str += '    <li class="lisel">';
                if(type == 'one'){
                    str += '<input type="radio" name="'+idkey+'" value="'+t.list.rows[i][idkey]+'" />';
                }else if(type == 'more'){
                    str += '<input name="'+idkey+'" value="'+t.list.rows[i][idkey]+'" type="checkbox" />';
                }
                str += '    <li class="litext">'+t.list.rows[i][fieldtag]+'</li>';
                str += '</ul>';
            }
            str += '<div class="pageList">'+t.list.pageStr+'</div>';
        }else{
            str = '未找到数据！';
        }
        $('#selectBody').html(str);
    },'json');
}

function goSelectModule(obj){
    var inputid = $('#inputid').attr('value');
    var type = $('#dataliststype').attr('value');
    if(type == 'one'){
        var o = $('#selectBody').find('input[type=radio]');
        var len = o.length;
        for(var i = 0; i < len; i++){
            if($(o[i]).attr('checked')){
                var id = $(o[i]).attr('value');
                var title = $(o[i]).parent().parent().children('li[class=litext]').html();
                break;
            }
        }
        if(id != null){
            $('#'+inputid).attr('value', id);
            $('#'+inputid+'_titles').attr('value', title);
            $('#'+inputid).parent().children('div[class=selectName]').html('<span class="button button-middle button-gray">'+title+'</span>'+'<div class="clear"></div>');
        }
    }else if(type == 'more'){
        var o = $('#selectBody').find('input[type=checkbox]');
        var len = o.length;
        var ids = '';
        var titles = '';
        var ts = '';
        for(var i = 0; i < len; i++){
            if($(o[i]).attr('checked')){
                ids += $(o[i]).attr('value')+',';
                titles += '<span class="button button-middle button-gray">'+$(o[i]).parent().parent().children('li[class=litext]').html()+'</span>';
                ts += $(o[i]).parent().parent().children('li[class=litext]').html()+',';
            }
        }
        if(ids != ''){
            $('#'+inputid).attr('value', ids);
            $('#'+inputid+'_titles').attr('value', ts);
            $('#'+inputid).parent().children('div[class=selectNames]').html(titles+'<div class="clear"></div>');
        }
    }
    $.win.close(obj);
}

function getMdls(obj, type){
    var data = {'type':type, 'mtag':obj.attr('mtag'),'field':obj.attr('field'),'inputid':obj.attr('inputid')};
    var url = U('Admin/Mdls/getDataList');
    var title = '选择'+obj.attr('title');
    $.win.open(title, url, '', data);
}

function getSameTypeList(typeid, grade,formname, fieldname, obj){
    var data = {'typeid':typeid}
    var url = U('Admin/Types/getSameTypes');
    var fidstr = '';
    for(var n = 0; n< grade; n++){
        fidstr +='_1';
    }
    var fid = obj.attr('id')+fidstr;
    $.post(url, data, function(t){
        if(t.result){
            var len = t.list.length;
            var str = '<select name="'+formname+'[]" fname="'+fieldname+'" id="'+fid+'" grade="'+grade+'"><option value="">选择'+fieldname+'</option>';
            var sel = '';
            for(var i in t.list){
                if(t.list[i].typeid == typeid){
                    sel = 'selected="selected"';
                }
                str += '<option value="'+t.list[i].typeid+'" '+sel+'>'+t.list[i].typename+'</option>';
                sel = '';
            }
            str += '</select>';
            $('#'+fid).parent().prev().nextAll().remove();
            obj.parent().after(str);
            changeSelect($('#'+fid));
            $('#'+fid).change(function(){getSonsList($(this));});
        }else{
            $('#'+fid).parent().prev().nextAll().remove();
        }
        
    }, 'json');
}

function getAreaSameTypeList(formname, fieldname, obj, idlist, grade){
    var areaids = idlist.split(',');
    var areaid = areaids[grade];
    var idlistlen = areaids.length;
    var data = {'upid':areaid}
    var url = U('Admin/Areas/getSameTypes');
    var fidstr = '';
    for(var n = 0; n< grade+1; n++){
        fidstr +='_1';
    }
    var fid = obj.attr('id')+fidstr;
    $.post(url, data, function(t){
        if(t.result){
            var len = t.list.length;
            var str = '<select name="'+formname+'[]" fname="'+fieldname+'" id="'+fid+'" grade="'+(grade+1)+'"><option value="">选择'+fieldname+'</option>';
            var sel = '';
            for(var i in t.list){
                if(t.list[i].areacode == areaid){
                    sel = 'selected="selected"';
                }
                str += '<option value="'+t.list[i].areacode+'" areacode="'+t.list[i].areacode+'"'+sel+'>'+t.list[i].areaname+'</option>';
                sel = '';
            }
            str += '</select>';
            $('#'+fid).parent().prev().nextAll().remove();
            obj.parent().after(str);
            changeSelect($('#'+fid));
            $('#'+fid).change(function(){getAreaseSonsList($(this));});
            if(grade < idlistlen-1){
                grade++;
                getAreaSameTypeList(formname, fieldname, obj, idlist, grade);
            }
        }else{
            $('#'+fid).parent().prev().nextAll().remove();
        }
    }, 'json');
}

function getAreaseSonsList(obj){
    var upid = obj.attr('areacode');
    var data = {'upid':upid};
    var url = U('Admin/Areas/getSonsList');
    var grade = parseInt(obj.parent().attr('grade'));
    var fname = obj.attr('name');
    var areaname = obj.attr('firstTxt');
    var fidlen = obj.attr('id').length;
    var fid = obj.attr('id').substr(0, fidlen-2);
    //清空之前的选项
    var o = obj.parent().children('select');
    var l = o.length;
    var str = '<span id="loading" style="margin-left:10px;">loading……</span>';
    l = l+1;
    for(var n = 0; n < l; n++){
        var ograde = parseInt($(o[n]).attr('grade'));
        if(ograde > grade){
            $(o[n]).remove();
        }
    }

    $.post(url, data, function(t){
        $('#loading').remove();
        if(t.result == true){
            var len = t.list.length;
            var str = '<select name="'+fname+'" areaname="'+areaname+'" id="'+fid+'" grade="'+(grade-1)+'"><option value="">'+areaname+'</option>';
            for(var i in t.list){
                str += '<option value="'+t.list[i].areacode+'" areacode="'+t.list[i].areacode+'">'+t.list[i].areaname+'</option>';
            }
            str += '</select>';
            obj.parent().nextAll().remove();
            obj.parent().after(str);
            changeSelect($('#'+fid));
            $('#'+fid).change(function(){getAreaseSonsList($(this));});
        }else{
            obj.parent().nextAll().remove();
        }
    }, 'json');
}

function getSonsList(obj){
    var upid = obj.attr('value');
    var data = {'upid':upid};
    var url = U('Admin/Types/getSonsList');
    var grade = parseInt(obj.attr('grade'));
    var fname = obj.attr('name');
    var fieldname = obj.attr('fname');
    var fid = obj.attr('id')+'_1';
    //清空之前的选项
    var o = obj.parent().children('select');
    var l = o.length;
    var str = '<span id="loading" style="margin-left:10px;">loading……</span>';
    obj.after(str);
    l = l+1;
    for(var n = 0; n < l; n++){
        var ograde = parseInt($(o[n]).attr('grade'));
        if(ograde > grade){
            $(o[n]).remove();
        }
    }
    
    $.post(url, data, function(t){
        $('#loading').remove();
        if(t.result == true){
            var len = t.list.length;
            var str = '<select name="'+fname+'" fname="'+fieldname+'" id="'+fid+'" grade="'+(grade+1)+'"><option value="">选择'+fieldname+'</option>';
            for(var i in t.list){
                str += '<option value="'+t.list[i].typeid+'">'+t.list[i].typename+'</option>';
            }
            str += '</select>';
            $('#'+fid).parent().prev().nextAll().remove();
            obj.parent().after(str);
            changeSelect($('#'+fid));
            $('#'+fid).change(function(){getSonsList($(this));});
        }else{
            $('#'+fid).parent().prev().nextAll().remove();
        }
    }, 'json');
}

function setEditor(id){
    CKEDITOR.replace(id);
}

function lookPic(obj){
    var url = obj.attr('pic');
    var title = '图片预览';
    $.win.open(title, url, 'pic')
}

function saveOrders(obj){
    var url = U(obj.attr('url'));
    if(url == '' || url == null){
        alert('保存排序调用地址未设置！');
        return false;
    }
    var formname = obj.attr('formname');
    if(formname == '' || formname == null){
        formname = 'listForm';
    }
    $('#'+formname).attr('action', url);
    $('#'+formname).ajaxSubmit(function(t){
        var t = $.parseJSON(t);
        if(t.msg != '' && t.msg != null){$.win.open('','', 'txt', t.msg);}//提示方法
        $('#formcle').attr('onclick','');
        $('#formcle').click(function(){Fresh();});
        try{
            $.win.close(button);
        }catch(e){}
    });
}

function uploadFile(obj, type){
    var title = '上传文件';
    var data = {'callback':obj.attr('callback')};
    if(type != null){
        data['type'] = type;
    }
    var url = U('Admin/Settings/upload', data);
    $.win.open(title, url);
}

function uploadPic(obj, type){
//    alert(obj.attr('uploadPath'));
    var title = '上传图片';
    var data = {};
    if(obj.attr('callback') != '' && obj.attr('callback') != null){
        data['callback'] = obj.attr('callback');
    }
    if(obj.attr('uploadPath') != '' && obj.attr('uploadPath') != null){
        data['uploadPath'] = base64encode(obj.attr('uploadPath'));
    }
    if(obj.attr('iseditor') != '' && obj.attr('iseditor') != null){
        data['iseditor'] = obj.attr('iseditor');
    }
    if(type != null){
        data['type'] = type;
    }
    var url = U('Admin/Settings/upload', data);
    $.win.open(title, url);
}

var useRouter = true;
function U(url,data){
    if(data == null){
        data = {};
    }
    
    var d = url.split("\/");
    if(!useRouter){
        var base = './index.php';
        var u = 'app='+d[0]+'&m='+d[1]+'&a='+d[2];
        base = base+'?'+u;
        var str = '';
        for(var k in data){
            str+= '&'+k+'='+data[k];
        }
    }else{
        var base = '/';
        var u = d[0]+'/'+d[1]+'/'+d[2];
        base = base+u;
        var str = '';
        for(var k in data){
            str+= '/'+k+'/'+data[k];
        }
        str += '.html';
    }
    base += str;
    return base;
}

function selectAll(){
    var o = $('input[type=checkbox]');
    var len = o.length;
    for(var i = 0; i < len; i++){
        $(o[i]).attr('checked',true);
    }
}

function selectOther(id){
    var o = $('input[type=checkbox]');
    var len = o.length;
    isChangeSelectAll(id);
    for(var i = 0; i < len; i++){
        if($(o[i]).attr('id') != id){
            var checked = $(o[i]).attr('checked');
            $(o[i]).attr('checked', !checked);
        }
    }
}

function isChangeSelectAll(id){
    var o = $('input[type=checkbox]');
    var len = o.length;
    var status = true;
    for(var i = 0; i < len-1; i++){
        if($(o[i]).attr('id') != id){
            if($(o[i]).attr('checked') != $(o[i+1]).attr('checked')){
                var status = false;
                break;
            }
        }
    }
    if(status){
        $('#'+id).attr('checked', !$('#'+id).attr('checked'));
    }else{
        $('#'+id).attr('checked', false);
    }
}

function selectOne(obj, id){
    var o = $('input[type=checkbox]');
    var len = o.length;
    var status = true;
    for(var i = 0; i < len-1; i++){
        if($(o[i]).attr('id') != id){
            if($(o[i]).attr('checked') != $(o[i+1]).attr('checked')){
                var status = false;
                break;
            }
        }
    }
    if(status){
        
        $('#'+id).attr('checked', obj.attr('checked'));
    }else{
        $('#'+id).attr('checked', false);
    }
}

function selectThis(obj){
    var checked = obj.attr('checked');
    var o = $('input[type=checkbox]');
    var len = o.length;
    for(var i = 0; i < len; i++){
        $(o[i]).attr('checked',checked);
    }
}

function selectOtherMod(url,selMode,reFields){
    url = U(url, {'ajax':1,'selMode':selMode,'reFields':reFields});
    $.win.open('选择项目',url,'');
}

/**
 * 
 * @param {type} msg ：提示消息
 * @param {type} url ：提交地址
 * @param {type} cls ：选项class值
 * @param {type} formname : form的id
 * @returns 无返回
 */

function delSel(obj){
    var url = U(obj.attr('url'));
    if(url == '' || url == null){
        alert('删除调用地址未设置！');
        return false;
    }
    var msg = obj.attr('msg');
    if(msg == null || msg == ''){
        msg = '您确认要删除这些数据吗？删除后不能恢复，请谨慎删除！';
    }
    var cls = obj.attr('cls');
    if(cls == '' || cls == null){
        cls = 'ids';
    }
    var formname = obj.attr('formname');
    if(formname == '' || formname == null){
        formname = 'listForm';
    }
    if(confirm(msg)){
        var objs = $('.'+cls);
        var ids = getids(objs);
        if(ids.length > 0){
            $('#'+formname).attr('action', url);
            $('#'+formname).ajaxSubmit(function(t){
                var t = $.parseJSON(t);
                if(t.msg != '' && t.msg != null){$.win.open('','', 'txt', t.msg);}//提示方法
                $('#formcle').attr('onclick','');
                $('#formcle').click(function(){Fresh();});
                try{
                    $.win.close(button);
                }catch(e){}
            });
        }else{
            alert('请选择要操作的选项！');
        }
    }
}

function getids(objs){
    var len = objs.length;
    var arr = new Array();
    var n = 0;
    for(var i = 0; i < len; i++){
        if($(objs[i]).attr('checked') == true){
            arr[n] = $(objs[i]).attr('value');
            n++;
        }
    }
    return arr;
}

function editOne(obj){
    var url = obj.attr('url');
    var title = obj.attr('title');
    $.win.open(title, url);
}

function addOne(obj){
    var url = obj.attr('url');
    var title = obj.attr('title');
    $.win.open(title, url);
}

function deleteOne(obj){
    var id = obj.attr('id');
    var url = U(obj.attr('url'),{'ids':id});
    if(url == '' || url == null){
        alert('删除调用地址未设置！');
        return false;
    }
    var msg = obj.attr('msg');
    if(msg == null || msg == ''){
        msg = '您确认要删除这条数据吗？删除后不能恢复，请谨慎删除！';
    }
    var func = obj.attr('func');
    if(func == ''){
        func = {};
    }else{
        func = {func:func};
    }
    if(confirm(msg)){
        $.get(url, func, function(t){
            if(t == 'success'){
                obj.parent().parent().slideUp(0,function(){
                    $(this).remove();
                });
            }else{
                alert('删除失败！');
            }
        },'html');
    }
}

function clearFiles(type){
    if(type == null){
        type = 'pic';
    }
    if(type == 'pic'){
        var str = '图片';
        var fileid = 'pics';
        var listid = 'picsList';
    }
    if(type == 'file'){
        var str = '文件';
        var fileid = 'files';
        var listid = 'filesList'
    }
    if(confirm('您确认要删除下面的所有'+str+'吗？请注意图'+str+'删除后无法恢复！')){
        var obj = $('#'+listid);
        obj.html('<div class="clear"></div>');
        var o = $('.picspath');
        var pics = o.attr('value').split(',');
        var data = {'pics':pics};
        var url = U('Admin/Public/delFile');
        $.post(url, data, function(t){
            o.attr('value','');
        },'html');
    }
}

var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
var base64DecodeChars = new Array(
    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
    -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,
    52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,
    -1,  0,  1,  2,  3,  4,  5,  6,  7,  8,  9, 10, 11, 12, 13, 14,
    15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,
    -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
    41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);

function base64encode(str) {
    var out, i, len;
    var c1, c2, c3;

    len = str.length;
    i = 0;
    out = "";
    while(i < len) {
 c1 = str.charCodeAt(i++) & 0xff;
 if(i == len)
 {
     out += base64EncodeChars.charAt(c1 >> 2);
     out += base64EncodeChars.charAt((c1 & 0x3) << 4);
     out += "==";
     break;
 }
 c2 = str.charCodeAt(i++);
 if(i == len)
 {
     out += base64EncodeChars.charAt(c1 >> 2);
     out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
     out += base64EncodeChars.charAt((c2 & 0xF) << 2);
     out += "=";
     break;
 }
 c3 = str.charCodeAt(i++);
 out += base64EncodeChars.charAt(c1 >> 2);
 out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
 out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6));
 out += base64EncodeChars.charAt(c3 & 0x3F);
    }
    return out;
}

function base64decode(str) {
    var c1, c2, c3, c4;
    var i, len, out;

    len = str.length;
    i = 0;
    out = "";
    while(i < len) {
 /* c1 */
 do {
     c1 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
 } while(i < len && c1 == -1);
 if(c1 == -1)
     break;

 /* c2 */
 do {
     c2 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
 } while(i < len && c2 == -1);
 if(c2 == -1)
     break;

 out += String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));

 /* c3 */
 do {
     c3 = str.charCodeAt(i++) & 0xff;
     if(c3 == 61)
  return out;
     c3 = base64DecodeChars[c3];
 } while(i < len && c3 == -1);
 if(c3 == -1)
     break;

 out += String.fromCharCode(((c2 & 0XF) << 4) | ((c3 & 0x3C) >> 2));

 /* c4 */
 do {
     c4 = str.charCodeAt(i++) & 0xff;
     if(c4 == 61)
  return out;
     c4 = base64DecodeChars[c4];
 } while(i < len && c4 == -1);
 if(c4 == -1)
     break;
 out += String.fromCharCode(((c3 & 0x03) << 6) | c4);
    }
    return out;
}