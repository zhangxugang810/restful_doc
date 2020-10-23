$.dateSelector = {
    d:new Date(),
    datetype:'datetime',
    obj:{},
    open:function(obj){
        this.obj = obj;
        if(obj.attr('formtag') != null){
            this.datetype = obj.attr('formtag');
        }        
        var o = obj.parent();
        var html = this.getdatelist();
        o.append(html);
        this.setcurDay();
    },
    
    close:function(){
        var o = $('.dateSelector');
        o.fadeOut('fast', function(){
            o.remove();
        });
    },
    
    setDate:function(obj){
        var day = obj.html();
        var month = $('#month').attr('value');
        var year = $('#year').attr('value');
        var str = year+'-'+month+'-'+day;
        this.obj.attr('value', str);
        this.close();
    },
    
    setDatetime:function(obj){
        var day = obj.html();
        var month = $('#month').attr('value');
        var year = $('#year').attr('value');
        var hour = this.d.getHours();
        var minutes = this.d.getMinutes();
        var seconds = this.d.getSeconds();
        var str = year+'-'+month+'-'+day+' '+hour+':'+minutes+':'+seconds;
        this.obj.attr('value', str);
        this.close();
    },
    
    getdatelist:function(){
        var str = '<div class="dateSelector">';
        str += '<div class="dateHeader">';
        str += '<a class="minusyear" onclick="$.dateSelector.goMinusYear();" href="javascript:void(0);"></a><a class="minusmonth" onclick="$.dateSelector.goMinusMonth();" href="javascript:void(0);"></a><div class="selectym"><select onchange="$.dateSelector.changeDate();" name="year" id="year">';
        var ysel = '';
        var msel = '';
        for(var i = this.getcurYear()-100; i <= this.getcurYear()+100; i++){
            if(i == this.getcurYear()){
                var ysel = 'selected="selected"'
            }
            str += '<option value="'+i+'" '+ysel+'>'+i+'</option>';
            ysel = '';
        }
        str += '</select> 年 ';
        str += '<select onchange="$.dateSelector.changeDate();" name="month" id="month">';
        for(var i = 1; i <= 12; i++){
            if(i == this.getcurMonth()){
                var msel = 'selected="selected"'
            }
            str += '<option value="'+i+'" '+msel+'>'+i+'</option>';
            msel = '';
        }
        str += '</select> 月 </div><a class="plusmonth" onclick="$.dateSelector.goPlusMonth();" href="javascript:void(0);"></a><a class="plusyear" onclick="$.dateSelector.goPlusYear();" href="javascript:void(0);"></a><b class="close" onclick="$.dateSelector.close();"></b>';
        str += '</div>'
        str += '<div class="dateBody">';
        str += '<div class="week"><em>日</em><span>一</span><span>二</span><span>三</span><span>四</span><span>五</span><em>六</em></div>';
        str += '<ul id="dayList" class="dayList">';
        str += this.getDayList(this.getcurYear(),this.getcurMonth());
        str += '<div class="clear"></div></ul>';
        str += '</div>'
        str += '</div>';
        return str;
    },
    
    setcurDay:function(){
        var objs = $('#dayList').children('a');
        for(var i = 0; i < objs.length; i++){
            if($(objs[i]).html() == this.getcurDay() && $('#month').attr('value') == this.getcurMonth() && $('#year').attr('value') == this.getcurYear()){
                $(objs[i]).attr('class','hover');
                break;
            }
        }
    },
    
    getDayList:function(year, month){
        var mdays = this.getMonthDays(year,month);
        var weeknum = this.weekNum(year,month, 1);
        var weekmargin = 'style="margin-left:'+(weeknum*38)+'px"';
        var str = '';
        if(this.datetype == 'datetime'){
            var onclk = 'onclick="$.dateSelector.setDatetime($(this));"';
        }else if(this.datetype == 'date'){
            var onclk = 'onclick="$.dateSelector.setDate($(this));"';
        }
        for(var i = 1; i <= mdays; i++){
            if(i == 1){
                str += '<a '+weekmargin+' '+onclk+' href="javascript:void(0);">'+i+'</a>';
            }else{
                str += '<a '+onclk+' href="javascript:void(0);">'+i+'</a>';
            }
        }
        return str;
    },
    
    setcurDayList:function(html){
        $('#dayList').html(html);
        this.setcurDay();
    },
    
    getMonthDays:function(year, month){
        if(month == 2){
            if(year % 4 == 0 && year % 400 != 0){
                return 29;
            }else{
                return 28;
            }
        }else if(month == 4 || month == 6 || month == 9 || month == 11){
            return 30;
        }else{
            return 31;
        }
    },
    
    getcurYear:function(){
        return this.d.getFullYear();
    },
    
    getcurMonth:function(){
        return this.d.getMonth()+1;
    },
    
    getcurDay:function(){
        return this.d.getDate();
    },
    
    weekNum:function(Year,Month,Day){
        Month=Month-1;
        var d = new Date(Year,Month,Day);
        return d.getDay();
    },
    
    goMinusYear:function(){
        var obj = $('#year');
        var objs = obj.children('option');
        var len = objs.length;
        for(var i = 0; i < len; i++){
            if($(objs[i]).attr('selected') && i >= 0){
                $(objs[i-1]).attr('selected', true);
                $(objs[i]).attr('selected', false);
                break;
            }
        }
        var y = obj.attr('value');
        var m = $('#month').attr('value');
        var str = this.getDayList(y,m)+'<div class="clear"></div>';
        this.setcurDayList(str);
    },
    
    changeDate:function(){
        var y = $('#year').attr('value');
        var m = $('#month').attr('value');
        var str = this.getDayList(y,m)+'<div class="clear"></div>';
        this.setcurDayList(str);
    },
    
    goPlusYear:function(){
        var obj = $('#year');
        var objs = obj.children('option');
        var len = objs.length;
        for(var i = 0; i < len; i++){
            if($(objs[i]).attr('selected') && i < 200){
                $(objs[i+1]).attr('selected', true);
                $(objs[i]).attr('selected', false);
                break;
            }
        }
        var y = obj.attr('value');
        var m = $('#month').attr('value');
        var str = this.getDayList(y,m)+'<div class="clear"></div>';
        this.setcurDayList(str);
    },
    
    goMinusMonth:function(){
        var obj = $('#month');
        var objs = obj.children('option');
        if(parseInt(obj.attr('value')) > 1){
            var len = objs.length;
            for(var i = 0; i < len; i++){
                if($(objs[i]).attr('selected')){
                    $(objs[i-1]).attr('selected', true);
                    $(objs[i]).attr('selected', false);
                    break;
                }
            }
            var y = $('#year').attr('value');
            var m = obj.attr('value');
            var str = this.getDayList(y,m)+'<div class="clear"></div>';
            this.setcurDayList(str);
        }else{
            $(objs[11]).attr('selected', true);
            $(objs[0]).attr('selected', false);
            this.goMinusYear();
        }
    },
    
    goPlusMonth:function(){
        var obj = $('#month');
        var objs = obj.children('option');
        if(parseInt(obj.attr('value')) < 12){
            var len = objs.length;
            for(var i = 0; i < len; i++){
                if($(objs[i]).attr('selected')){
                    $(objs[i+1]).attr('selected', true);
                    $(objs[i]).attr('selected', false);
                    break;
                }
            }
            var y = $('#year').attr('value');
            var m = obj.attr('value');
            var str = this.getDayList(y,m)+'<div class="clear"></div>';
            this.setcurDayList(str);
        }else{
            $(objs[0]).attr('selected', true);
            $(objs[11]).attr('selected', false);
            this.goPlusYear();
        }
    }
};