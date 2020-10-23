$(document).ready(function(){
    $('#subSelect').click(function(){
        subSelect($(this));
    })
});

function subSelect(btn){
    var selMode = $('#selMode').attr('value');
    var reFields = $('#reFields').attr('value').split('_');
    if(selMode == 'selOne'){
        var objs = $('input[type=radio]');
        var len = objs.length;
        var status = true;
        for(var i=0; i < len; i++){
            if($(objs[i]).attr('checked') == false){
                status = false;
            }else{
                var obj = $(objs[i]);
            }
        }
        if(!status){
            alert('你没有做任何选择，请先做选择再点击确定');
        }
        var l = reFields.length;
        for(var i = 0; i < l; i++){
            var txt =  $('#'+reFields[i]+'_'+obj.attr('value')).html();
            alert(txt);
            $('#'+reFields[i]).attr('value',txt);
        }
        $.win.close(btn);
    }else if(selMode == 'selSome'){
        alert(reFields);
    }
}