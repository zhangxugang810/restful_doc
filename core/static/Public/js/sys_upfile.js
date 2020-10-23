$(function(){
    var dropbox = $('#dropbox'),
    message = $('.message', dropbox);
	
    dropbox.filedrop({
        // The name of the $_FILES entry:
        paramname:'pic',
		
        maxfiles: 10,
        maxfilesize: 2,
        url: U('Admin/Slides/uploadFile'),
        uploadFinished:function(i,file,response){
            if(response.result == false){
                alert('上传失败');
            }else{
                var obj = $('#uploadedFile')
                var str = obj.attr('value')+response.data.path+'|';
                obj.attr('value', str);
                $.data(file).addClass('done');
            }
        // response is the JSON object that post_file.php returns
        },
		
        error: function(err, file) {
            switch(err) {
                case 'BrowserNotSupported':
                    showMessage('您的浏览器不支持HTML5文件上传方式，请换用其他浏览器！');
                    break;
                case 'TooManyFiles':
                    alert('您一次上床不能超过'+this.maxfiles+'个文件');
                    break;
                case 'FileTooLarge':
                    alert(file.name+'：文件太大，您不能上床超过'+this.maxfilesize+'M的文件');
                    break;
                default:
                    break;
            }
        },
		
        // Called before each upload is started
        beforeEach: function(file){
            if(!file.type.match(/^image\//)){
                alert('上传文件类型错误，只能上传图片文件！');
				
                // Returning false will cause the
                // file to be rejected
                return false;
            }
        },
		
        uploadStarted:function(i, file, len){
            createImage(file);
        },
		
        progressUpdated: function(i, file, progress) {
            $.data(file).find('.progress').width(progress);
        }
    	 
    });
	
    var template = '<div class="preview">'+
    '<span class="imageHolder">'+
    '<img />'+
    '<span class="uploaded"></span>'+
    '</span>'+
    '<div class="progressHolder">'+
    '<div class="progress"></div>'+
    '</div>'+
    '</div>'; 
	
	
    function createImage(file){

        var preview = $(template), 
        image = $('img', preview);
			
        var reader = new FileReader();
		
        image.width = 100;
        image.height = 100;
		
        reader.onload = function(e){
			
            // e.target.result holds the DataURL which
            // can be used as a source of the image:
			
            image.attr('src',e.target.result);
        };
		
        // Reading the file as a DataURL. When finished,
        // this will trigger the onload function above:
        reader.readAsDataURL(file);
		
        message.hide();
        preview.appendTo(dropbox);
		
        // Associating a preview container
        // with the file, using jQuery's $.data():
		
        $.data(file,preview);
    }

    function showMessage(msg){
        message.html(msg);
    }

});