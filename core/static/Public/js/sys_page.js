$(document).ready(function(){
    $('.goPage').click(function(){
        var page = $(this).attr('page');
        goPage(page);
    });
    
    $('.goAjaxPage').click(function(){
        var page = $(this).attr('page');
        goAjaxPage(page);
    })
});

function goPage(page){
    var form = $('#listForm');
    var p = $('#page');
    p.attr('value', page);
    form.submit();
}

function goAjaxPage(page){
    var wId = $('#wId').attr('wId');
    var form = $('#listForm');
    var p = $('#page');
    p.attr('value', page);
    var data = form.serialize();
    var url = form.attr('action')+'&'+data;
    $.win.load(url, wId);
}