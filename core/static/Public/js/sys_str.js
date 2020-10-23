$.str = {
    getBaseName:function(s){
        var data = s.split('/');
        var len = data.length;
        return data[len-1];
    }
}