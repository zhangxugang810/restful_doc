/*
 * easyValidate, a form validation jquery plugin
 * By Alex Gill, www.alexpetergill.com
 * Version 1.3
 * Copyright 2011 APGDESIGN
 * Updated 23/06/2013
 * Free to use under the MIT License
 * http://www.opensource.org/licenses/mit-license.php
 */
(function($) {

    // DEFINE METHOD
    $.fn.easyValidate = function(settings) {

        // DEFAULT OPTIONS
        var config = {
            ajaxSubmit: false,
            ajaxSubmitFile: ""
        };

        // EXTEND OPTIONS
        var settings = $.extend(config, settings);

        return this.each(function() {

            // SET VARIABLES
            var promptText = "";
            var isError = false;
            var button;
            if(settings.formid == null || settings.formid == ''){
                var form = $(this).find('form');
            }else{
                var form =$('#'+settings.formid);
            }
            // IF AJAX, BUILD AJAX PROMPTS
            if (settings.ajaxSubmit) {
                _buildAjaxPrompts();
                var ajaxError = $('.ajaxError');
                var ajaxSuccess = $('.ajaxSuccess');
                var ajaxLoading = $('.ajaxLoading');
            }

            // GET ALL FORM ELEMENTS
            var elements = $(this).find('input, textarea, radio, checkbox, select, password, hidden');
            elements.each(function() {

                // SET BUTTON VARIABLE
                if ($(this).attr('type') == 'submit' ) {
                    button = $(this);
                }

                // FOCUS LISTERNER
                $(this).bind('blur change', function() {/*keyup*/
                    _getRules($(this));
                });
            });
            
            if(button != null && button != ''){
                // BUTTON LISTENER
                button.bind('click', function(e) {
                    var s = _isValid();
                    if (s) {
                        return _formSubmit();
                    }else{
                        return false;
                    }
    /*                e.preventDefault();*/
                });
            }

            // GET RULES FROM CLASS NAME
            function _getRules(element) {
                try{
                    var rulesParsed = element.attr('checkinput');
                    if (rulesParsed) {
                        var rules = rulesParsed.split(' ');
                        _validate(element, rules);
                    }
                }catch(e){}
            }
            ;

            // APPLY RULES TO EACH ELEMENT
            function _validate(element, rules) {
                // RESET VALUES FOR EACH ELEMENT
                promptText = element.attr('fieldname');
                if(promptText == null){
                    promptText = '这里';
                }
                isError = false;
                // LOOP RULES FOR EACH ELEMENT
                for (var i = 0; i < rules.length; i++) {
                    if(eval('_'+rules[i]+'(element)'))break;
                }

                // BUILD PROMPT IF RULE FAILS
                if (isError) {
                    _buildPrompt(element, promptText);
                    _addErrorClasses(element);
                } else {
                    _removePrompt(element);
                    _removeErrorClasses(element);
                }
            }
            ;
            
            // RULE: REQUIRED FIELD
            function _required(element) {

                // VALIDATE TEXT, TEXTAREA AND SELECT ELEMENTS
                var elementTagName = element.attr('tagName');
                var elementType = element.attr('type');
                if (elementTagName == 'INPUT' && elementType == 'text' || elementTagName == 'TEXTAREA' || elementTagName == 'SELECT') {
                    if (!element.val() || element.val() == 0) {
                        isError = true;
                        promptText = promptText + '不能为空';
                    }
                }

                // VALIDATE RADIO AND CHECKBOX ELEMENTS
                if (elementType == 'radio' || elementType == 'checkbox') {
                    elementName = element.attr('name');
                    if ($('input[name="' + elementName + '"]:checked').size() == 0) {
                        isError = true;
                        if (elementType == 'radio') {
                            promptText = promptText + '必须选择一个选项';
                        } else {
                            promptText = promptText + '不能为空';
                        }
                    }
                }

            }
            ;
            function _checklen(element){
                var lens = element.attr('len').split(',');
                var content = element.attr('value');
                if(lens.length >= 2){
                    if(lens[0] != 'n'){
                        var min = parseInt(lens[0]);
                        var max = parseInt(lens[1]);
                    }else{
                        var min = lens[0];
                        var max = parseInt(lens[1]);
                    }
                }else{
                    var min = parseInt(lens);
                    var max = 'n';
                }
                if(max == 'n'){
                    if(content.length < min || content.length == null){
                        isError = true;
                        promptText = promptText + '长度不能小于'+min+'位';
                    }
                }else{
                    if(min == 'n'){
                        isError = true;
                        promptText = promptText + '长度不能大于'+max+'位';
                    }else{
                        if(max == min){
                            if(content.length != max){
                                isError = true;
                                promptText = promptText + '长度必须是'+max+'位';
                            }
                        }else if(content.length > max || content.length < min){
                            isError = true;
                            promptText = promptText + '长度必须在'+min+'和'+max+'位之间';
                        }
                    }
                }
            }
            ;

            // RULE: VALID EMAIL STRING REQUIRED
            function _email(element) {
                var status = true;
                var email = element.attr('value');
                var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (!filter.test(email)) {
                    status = false;
                }
                if(!status){
                    isError = true;
                    promptText = promptText + '必须是一个有效的电子邮件地址';
                }
            }
            ;
            //数字判断
            function _numric(element) {
                var status = true;
                var num = element.attr('value');
                for (var i = 0; i < num.length; i++) {
                    var oneNum = num[i];
                    if (oneNum != '0' && oneNum != 1 && oneNum != 2 && oneNum != 3 && oneNum != 4 && oneNum != 5 && oneNum != 6 && oneNum != 7 && oneNum != 8 && oneNum != 9){
                        status = false;
                        break;
                    }
                }
                if(status == false){
                    isError = true;
                    promptText = promptText + '必须是一个有效的数字';
                }
            }
            ;

            function _checkdate(element, iscall){
                if(iscall == null || iscall == ''){
                    iscall = false;
                }
                var status = true
                if(iscall == false){
                    var d = element.attr('value');
                }else{
                    var d = element;
                }
                 var d1 = d.replace(/-/g,'1');
                 if(!/\d*[.]?\d*/g.exec(d1)){
                     status = false;
                 }else{
                     var ds = d.split('-');
                     if(ds.length != 3){
                         status = false;
                     }else{
                         ds[0] = parseInt(ds[0]);
                         ds[1] = parseInt(ds[1]);
                         ds[2] = parseInt(ds[2]);
                         if(ds[0] == null || ds[0] == '' || ds[1] == null || ds[1] == '' || ds[2] == null || ds[2] == ''){
                             status = false;
                         }else if(ds[1] < 1 || ds[1] > 12 || ds[2] < 1){
                             status = false;
                         }else if(ds[0] % 4 == 0 && ds[0] % 400 != 0 && ds[1] == 2 && ds[2] > 29){
                             status = false;
                         }else if((ds[0] % 4 != 0 && ds[0] % 400 == 0) && ds[1] == 2 && ds[2] > 28){
                             status = false;
                         }else if((ds[1] == 1 || ds[1] == 3 || ds[1] == 5 || ds[1] == 7 || ds[1] == 8 || ds[1] == 10 || ds[1] == 2) && ds[2] > 31){
                             status = false;
                         }else if((ds[1] == 4 || ds[1] == 6 || ds[1] == 9 || ds[1] == 11) && ds[2] > 30){
                             status = false;
                         }
                     }
                 }
                if(!iscall){
                    if(!status){
                        isError = true
                        promptText = promptText + '必须是一个日期格式：如：2012-10-01 或 2012-10-01';
                    }
                }else{
                    return status;
                }
            }
            ;

            function _checktime(element, iscall){
                if(iscall == null || iscall == ''){
                    iscall = false;
                }
                var status = true;
                if(iscall == false){
                    var t = element.attr('value');
                }else{
                    t = element;
                }
                var t1 = t.replace(/:/g,'1');
                if(!/\d*[.]?\d*/g.exec(t1)){
                    status = false;
                }else{
                    var ts = t.split(':');
                    if(ts.length != 3){
                        status = false;
                    }else{
                        ts[0] = parseInt(ts[0]);
                        ts[1] = parseInt(ts[1]);
                        ts[2] = parseInt(ts[2]);
                        if(ts[0] < 0 || ts[0] > 23){
                            status = false;
                        }else if(ts[1] < 0 || ts[1] > 59 || ts[2] < 0 || ts[0] > 59){
                            status = false;
                        }
                    }
                }

                if(!iscall){
                    if(!status){
                        isError = true
                        promptText = promptText + '必须是一个时间格式：如：23:20:12';
                    }
                }else{
                    return status;
                }
            }
            ;

            function _checkdatetime(element){
                var status = true;
                var d = element.attr('value');
                var dt = d.split(' ');
                if(!_checkdate(dt[0], true) || !_checktime(dt[1], true)){
                    status = false;
                }
                if(!status){
                    isError = true
                    promptText = promptText + '必须是一个日期时间格式：如：2012-10-01 23:20:12 或 2012-10-01 23:20:12';
                }
            }
            ;

            function _url(element){
                var status = true;
                var url = element.attr('value');
                var strRegex = "^((https|http|ftp|rtsp|mms)?://"
                + "?(([0-9a-zA-Z_!~*'().&=+$%-]+: )?[0-9a-zA-Z_!~*'().&=+$%-]+@)?" //ftp的user@
                + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184
                + "|" // 允许IP和DOMAIN（域名）
                + "([0-9a-zA-Z_!~*'()-]+\.)*" // 域名- www.
                + "([0-9a-zA-Z][0-9a-zA-Z-]{0,61})?[0-9a-zA-Z]\." // 二级域名
                + "[a-zA-Z]{2,6})" // first level domain- .com or .museum
                + "(:[0-9]{1,4})?" // 端口- :80
                + "((/?)|"
                + "(/[0-9a-zA-Z_!~*'().;?:@&=+$,%#-]+)+/?))$";
                var re=new RegExp(strRegex);
                if(!re.test(url)){
                    status = false;
                }
                if(!status) {
                    isError = true
                    promptText = promptText + '必须是一个有效的URL地址';
                }
            }
            ;
            
            function _checkint(element){
                var status = true;
                var str = element.attr('value');
                var result=str.match(/^(-|\+)?\d+$/);
                if(result==null){
                    status = false;
                }
                if(!status) {
                    isError = true
                    promptText = promptText + '必须是一个整数';
                }
            }
            ;
            
            function _checkpint(element){
                var status = true;
                var str = element.attr('value');
                var result=str.match(/^(\+)?\d+$/);
                if(result==null){
                    status = false;
                }
                if(!status) {
                    isError = true
                    promptText = promptText + '必须是一个正整数';
                }
            }
            ;
            
            function _checknint(element){
                var status = true;
                var str = element.attr('value');
                var result=str.match(/^(-)?\d+$/);
                if(result==null){
                    status = false;
                }
                if(!status) {
                    isError = true
                    promptText = promptText + '必须是一个负整数';
                }
            }
            ;
            
            function _postcode(element){
                var status = true;
                var str = element.attr('value');
                var result=str.match(/[1-9]\d{5}(?!\d)/);
                if(result==null){
                    status = false;
                }
                if(!status) {
                    isError = true
                    promptText = promptText + '必须是一个有效的邮政编码';
                }
            }
            
            function _checkip(element){
                var status = true;
                var str = element.attr('value');
                var result=str.match(/\d+\.\d+\.\d+\.\d+/);
                if(result==null){
                    status = false;
                }else{
                    var strs = str.split('.');
                    for(var i in strs){
                        if(strs[i] < 0 || strs[i] > 255){
                            status = false;
                            break;
                        }
                    }
                }
                if(!status) {
                    isError = true
                    promptText = promptText + '必须是一个有效的IP地址';
                }
            }
            ;

            function _cnword(element){
                var status = true;
                var str = element.attr('value');
                var re = /[^\u4e00-\u9fa5]/;
                if(re.test(str)){
                    status = false;
                }else{
                    status = true;
                }
                if(!status) {
                    isError = true
                    promptText = promptText + '必须是中文';
                }
            }
            ;
            

            function _notcnword(element){
                var status = true;
                var str = element.attr('value');
                var re = /[^\u4e00-\u9fa5]/;
                if(!re.test(str)){
                    status = false;
                }else{
                    status = true;
                }
                if(!status) {
                    isError = true
                    promptText = promptText + '不能是中文';
                }
            }
            ;

            function _enword(element){
                var status = true;
                var str = element.attr('value');
                var result=str.match(/^[A-Za-z]+$/);
                if(result==null){
                    status = false;
                }
                if(!status) {
                    isError = true
                    promptText = promptText + '必须是英文字母';
                }
            }
            ;

            function _enwordnum(element){
                var status = true;
                var str = element.attr('value');
                if(str != '' && str != null){
                    var first = parseInt(str[0]);
                    for(var i = 0; i <= 9; i++){
                        if(first == i){
                            status = false;
                            break;
                        }
                    }
                    if(status == true){
                        var result=str.match(/^[A-Za-z0-9\_]+$/);
                        if(result==null){
                            status = false;
                        }
                    }
                }
                if(!status) {
                    isError = true
                    promptText = promptText + '必须是英文数字或下划线，且第一位不能是数字';
                }
            }
            ;

            function _phone(element){
                var status = true;
                var str = element.attr('value');
                var result=str.match(/\d{3}-\d{8}|\d{4}-\d{7}/);
                if(result==null){
                    status = false;
                }
                if(!status) {
                    isError = true
                    promptText = promptText + '必须是一个有效的电话号码';
                }
            }
            ;

            function _mobile(element){
                var status = true;
                var str = element.attr('value');
                var result=str.match(/(86)*0*13\d{9}/);
                if(result==null){
                    status = false;
                }
                if(!status) {
                    isError = true
                    promptText = promptText + '必须是一个有效的手机号码';
                }
            }
            ;

            // RETURNS FORM VALIDATION STATUS
            function _isValid() {
                var errorsFound = 0;
                var len = elements.length;
                for(var i = 0; i < len; i++){
                    _getRules($(elements[i]));
                    if (isError) {
                        errorsFound++;
                    }
                }
                if (!errorsFound > 0) {
                    return true;
                }
                return false;
            }
            ;

            // BUILDS DYNAMIC ERROR PROMPT
            function _buildPrompt(element, prompText) {

                // REMOVE ALL EXISTING PROMPTS ON INIT
                _removePrompt(element);

                // CREATE ERROR WRAPPER
                var oClass=$(element).parent().attr('class');
                
                var divFormError = $('<span></span>');
                $(divFormError).addClass('formError');
                $(divFormError).addClass('formError' + $(element).attr('name'));
                if(oClass == '' || oClass == null){
                    var o = $(element).parent();
                }else{
                    var o = $(element).parent().parent();
                }
                o.append(divFormError);

                // CREATE ERROR CONTENT
                var formErrorContent = $('<em></em>');
                $(formErrorContent).addClass('formErrorContent');
                $(divFormError).append(formErrorContent);
                $(formErrorContent).html(promptText+'<i class="errorArrow"></i>');
                element.css('border-color',"#991515");
                return true;
            }
            ;

            // REMOVE PROMPT
            function _removePrompt(element) {
                $('body').find('.formError' + $(element).attr('name')).remove();
                element.css('border-color','rgba(255,255,255,0.3');
            }
            ;

            // SUBMIT FORM
            function _formSubmit() {
                //判断有几个需要Ajax检查的方法
                //调用Ajax检查函数
                var len = elements.length;
                var n = 0;
                var data = [];
                for(var i = 0; i < len; i++){
                    var obj = $(elements[i]);
                    if(obj.attr('checkajax') == 'ajax'){
                        var func = obj.attr('func');
                        data[n] = {name:obj.attr('name'),id:obj.attr('id'),value:obj.attr('value'),mtag:obj.attr('mtag'),};
                        if(func != null && func != ''){data[n]['func'] = func;}
                        var def = obj.attr('def');
                        if(def != null && def != ''){data[n]['def'] = def;}
                        n++;
                    }
                }
                
                try{
                    CKupdate();
                }catch(e){}
                if(n > 0){
                    var url = U(define.APP_NAME+'/'+define.MODEL_NAME+'/checkAjax');
                    var d = {data:data};
                    $.post(url, d, function(t){
                        var status = true;
                        t = t.rs;
                        for(var i in t){
                            if(t[i].result == true){
                                status = false;
                                isError = true;
                                promptText = $('#'+t[i].id).attr('fieldname');
                                if(promptText == null){promptText = '这里';}
                                promptText += t[i].msg;
                                if (isError) {
                                    _buildPrompt($('#'+t[i].id), promptText);
                                    _addErrorClasses($('#'+t[i].id));
                                } else {
                                    _removePrompt($('#'+t[i].id));
                                    _removeErrorClasses($('#'+t[i].id));
                                }
                            }
                        }
                        if(status == true){
                            if(settings.ajaxSubmit == true){
                                /*这个代码是表单验证成功之后的Ajax提交数据的过程，
                                * 且在提交之后根据PHP返回的JS代码执行相关程序来做一些页面方面的操作*/
                                form.ajaxSubmit(function(t){
                                    if(t != '' && t != null){
                                        var t = $.parseJSON(t);
                                        if(t.msg != '' && t.msg != null){$.win.open('','', 'txt', t.msg);}//提示方法
                                        $.win.close(button);
                                        $('#formcle').bind('click', function(){goFunc(t.func, t, $(this));});
//                                      $.win.close(button);
                                    }else{$.win.close(button);}
                                });
                                return false;
                            }else{
                                var f = $("#saveAjaxForm");
                                f.ajaxSubmit(function(t){
                                    if(t != '' && t != null){
                                        var t = $.parseJSON(t);
                                        if(t.msg != '' && t.msg != null){$.win.open('','', 'txt', t.msg);}//提示方法
                                        $.win.close(button);
                                        $('#formcle').bind('click', function(){goFunc(t.func, t, $(this));});
//                                      $.win.close(button);
                                    }else{$.win.close(button);}
                                });
                            }
                        }else{return false;}
                    },'json');
                    return false;
                }else{
                    
                    if (settings.ajaxSubmit == true) {
                        /*这个代码是表单验证成功之后的Ajax提交数据的过程，
                        * 且在提交之后根据PHP返回的JS代码执行相关程序来做一些页面方面的操作*/
                        form.ajaxSubmit(function(t){
                            var t = $.parseJSON(t);
                            if(t.msg != ''){$.win.open('','', 'txt', t.msg);}//提示方法
                            $.win.close(button);
                            $('#formcle').bind('click', function(){goFunc(t.func, t, $(this));return true;});
                        });
                        return false;
                    }else{form.submit();}
                }
            }
            ;
            function CKupdate(){
                try{
                    for(instance in CKEDITOR.instances){
                        CKEDITOR.instances[instance].updateElement();
                    }
                }catch(e){}
            }
            
            function goFunc(func, t, obj){
                eval(func);
                $.win.close(obj);
            };

            // BUILD AJAX PROMPTS
            function _buildAjaxPrompts() {
                var o = form.parent().children('*').last();
                var ajaxErrorDiv = $('<div></div>');
                ajaxErrorDiv.addClass('ajaxError');
                //form.after(ajaxErrorDiv);
                o.after(ajaxErrorDiv);
                var ajaxSuccessDiv = $('<div></div>');
                ajaxSuccessDiv.addClass('ajaxSuccess');
                //form.after(ajaxSuccessDiv);
                o.after(ajaxSuccessDiv);

                var ajaxLoadingDiv = $('<div>加载中...</div>');
                ajaxLoadingDiv.addClass('ajaxLoading');
                //form.after(ajaxLoadingDiv);
                o.after(ajaxLoadingDiv);

            }
            ;

            // ADD ERROR CLASSES TO ELEMENTS
            function _addErrorClasses(element) {
//                $(element).addClass('form-error').siblings().addClass('form-error');
                return false;
            }

            // REMOVE ERROR CLASSES FROM ELEMENTS
            function _removeErrorClasses(element) {
                $(element).removeClass('form-error').siblings().removeClass('form-class');
                $(element).parent().find('span').removeClass('form-error'); //NOT SURE WHY THIS WASNT REMOVED WITH SIBLINGS()
                return false;
            }

        });

    };

})(jQuery);