$(document).ready(function () {
    $('.backgrounds > ul > li').click(function (){setBackground($(this));return false;});
});

function setBackground(obj){
    var bgclass = obj.attr('class');
    var cls = $('body').attr('class');
    $.cookie('bgclass', bgclass);
    try{
        var o = obj.parent().children('li[class='+cls+']');
        $('.'+cls).removeClass(cls).addClass(bgclass);
        o.attr('class', cls);
    }catch(e){
        
    }
}