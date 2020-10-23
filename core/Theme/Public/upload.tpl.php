<?php S('sys_upload', 'css'); ?>
<?php S('sys_upfile', 'css'); ?>
<?php S('sys_upload', 'js'); ?>
<?php S('sys_filedrop', 'js'); ?>
<?php S('sys_upfile', 'js'); ?>
<div class="upload">
    <h1 class="uploadTitle">
        <?php if(!isset($someUp)){?>
        <span id="tab_1" class="selected">本地文件</span>
        <span id="tab_2">网络文件</span>
        <?php }else{?>
        <span id="tab_3" <?php if(isset($someUp)){ echo 'class="selected"';}?>>多文件上传</span>
        <?php }?>
    </h1>
    <div class="uploadBody">
        <input type="hidden" name="uploadedFile" id="uploadedFile" value="" />
        <input type="hidden" name="callback" id="callback" value="<?php echo isset($callback)? $callback:'';?>" />
        <input type="hidden" name="iseditor" id="iseditor" value="<?php echo isset($iseditor)? $iseditor:'';?>" />
        <input type="hidden" name="type" id="type" value="<?php echo isset($type)?$type:'';?>" />
        <?php if(!isset($someUp)){?>
        <div class="netB" id="file_1">
            <!--<span id="heart">0</span>-->
            <form name="upformFrame" id="upformFrame" target="uploadframe" method="POST"  enctype="multipart/form-data" action="<?php echo U(APP_NAME.'/'.MODEL_NAME.'/uploadFile')?>">
                <input type="file" name="upload" value="" width="200" /><input type="hidden" name="rtn" value="gogogo" width="" />
                <?php echo !empty($uploadPath)? '<input type="hidden" name="uploadPath" id="uploadPath" value="'.$uploadPath.'" />':'aaa';?>
                <input type="button" name="uploadbtn" {wId} id="uploadButton" value="上传" />
                <input type="button" name="uploadCancel" {wId} id="uploadCancel" onclick="$.win.close($(this));" value="取消" />
            </form>
            <span id="framespan"></span>
        </div>
        <div class="netB" id="file_2" style="display:none;">
            <form name="upform" id="upform" method="POST"  enctype="multipart/form-data">
                <input type="text" name="fileurl" id="fileurl" value="请您输入文件的绝对地址" />
                <input type="button" name="downloadButton" {wId} id="downloadButton" value="确定" />
                <input type="button" name="uploadCancel" {wId} id="uploadCancel" onclick="$.win.close($(this));" value="取消" />
            </form>
        </div>
        <?php }else{?>
        <div class="uploadB" id="file_3" style="display:<?php if(isset($someUp)){ echo 'block';}?>">
            <div id="dropbox">
                <span class="message">请把你的图片拖拽到这里</span>
            </div>
            <div id="divMovieContainer">
                <a {wId} id="someFileUploaded">确定</a>
            </div>
        </div>
        <?php }?>
    </div>
</div>