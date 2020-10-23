<div class="rbox">
    <h1><i class="fa fa-cog"></i> 系统设置</h1>
    <div class="rb">
        <form name="form1" id="form1" action="" method="POST">
            <table border="0" cellspacing="0" cellpadding="0" class="updateTable">
                <tbody>
                    <tr>
                        <th>系统显示名称：</th>
                        <td><input type="text" class="form-control" name="sysname" size="80" id="sysname" value="<?php echo $data['sysname'] ?>" /></td>
                    </tr>
                    <tr>
                        <th>邮件服务器地址：</th>
                        <td><input type="text" class="form-control" name="mailServerHost" size="80" id="mailServerHost" value="<?php echo $data['mailServerHost'] ?>" /></td>
                    </tr>
                    <tr>
                        <th>邮件服务器用户名：</th>
                        <td><input type="text" class="form-control" name="mailServerUsername" size="80" id="mailServerUsername" value="<?php echo $data['mailServerUsername'] ?>" /></td>
                    </tr>
                    <tr>
                        <th>邮件服务器密码：</th>
                        <td><input type="text" class="form-control" name="mailServerPassword" size="80" id="mailServerPassword" value="<?php echo $data['mailServerPassword'] ?>" /></td>
                    </tr>
                    <tr>
                        <th>默认发件编码：</th>
                        <td><input type="text" class="form-control" name="mailCharSet" size="80" id="mailCharSet" value="<?php echo $data['mailCharSet'] ?>" /></td>
                    </tr>
                    <tr>
                        <th>默认发件人姓名：</th>
                        <td><input type="text" class="form-control" name="mailFromName" size="80" id="mailFromName" value="<?php echo $data['mailFromName'] ?>" /></td>
                    </tr>
    <!--                <tr>
                        <th>系统版权信息：</th>
                        <td><textarea name="copyright" id="copyright" rows="15" cols="14" style="resize: none;"><?php //echo $data['copyright'] ?></textarea></td>
                    </tr>-->
                </tbody>
        </table>
        </form>
        <div class="oprBtns">
            <a class="add goSubmit btn btn-sm btn-info" href="javascript:void(0);" url="<?php echo $postUrl?>">确认</a>
            <div class="clear"></div>
        </div>
    </div>
</div>