$(document).ready(function(){
    setInterval("request()", 1000);
});
var totalSecond = $('#totalSecond').text();
function request(){
    if(totalSecond > 1){
        totalSecond--;
        $('#totalSecond').text(totalSecond);
    }else{
        var url = $('#url').attr('value');
        if(url == ''){
            url = '/';
        }
        if(url == 'return'){
            url = document.referrer;
        }
        window.location.href=url;
    }
}