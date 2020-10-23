$.trees = {
    getTree:function(obj){
        var cobj = obj.children('ul');
        if(cobj[0] != null){
            if(cobj.css('display') == 'none'){
                cobj.slideDown('fast');
                obj.children('b').children('i').removeClass('icon-folder-close').addClass('icon-folder-open');
            }else{
                cobj.slideUp('fast');
                obj.children('b').children('i').removeClass('icon-folder-open').addClass('icon-folder-close');
            }
        }else{
            var url = obj.attr('url');
            $.get(url,{}, function(t){
                if(t.result == true){
                    var data = t.data;
                    var html = '<ul class="trees" style="display:none">';
                    for(var i in data){
                        if(data[i].sonsCount > 0){
                            var cls = 'class="getSons"';
                            var icon = 'icon-folder-close';
                        }else{
                            var cls = 'class="noSons"';
                            var icon = 'icon-file ';
                        }
                        html += '<li '+cls+' id="tree_'+data[i].id+'" url="'+data[i].url+'">';
                        html += '    <b><i class="'+icon+'"></i></b>';
                        html += '    <span>'+data[i].name+'</span>';
                        if(data[i].opsUrls.length > 0){
                            html += '    <em>';
                            for(var n in data[i].opsUrls){
                                html += '        '+data[i].opsUrls[n];
                            }
                            html += '    </em>';
                        }
                        html += '    <div class="clear"></div>';
                        html +='</li>';
                    }
                    html += '</ul>';
                    obj.append(html);
                    var aobj = obj.children('ul');
                    aobj.slideDown('fast');
                    obj.children('b').children('i').removeClass('icon-folder-close').addClass('icon-folder-open');
                }else{
                    alert('获取数据失败');
                }
            },'json');
        }
    },
}

$(document).ready(function(){
    $('.getSons').live('click', function(){
        $.trees.getTree($(this));return false;
    });
    
    $('.noSons').live('click', function(){
        return false;
    });
    
    $('.trees > li').live('mouseenter', function(){
        $(this).children('em').fadeIn('fast');
        return false;
    });
    
    $('.trees > li').live('mouseleave', function(){
        $(this).children('em').fadeOut('fast');
        return false;
    });
});