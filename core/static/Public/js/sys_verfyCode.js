function changeImg(url){
   var c = Math.random();
   url += '&'+c;
   $('#codeImg').attr('src',url);
}