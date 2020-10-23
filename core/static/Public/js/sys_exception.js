$(document).ready(function(){
    setBackground();
});

function setBackground(){
    var p = window.parent[0];
    if(p == null){
        $('body').css('background', 'url(/Apps/Admin/static/default/default/images/e.jpg)');
        $('body').css('filter', 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src="/Apps/Admin/static/default/default/images/e.jpg",sizingMethod="scale"');
        $('body').css('background-size', 'cover');
    }
}