<div class="rbox">
    <h1><i class="fa fa-user"></i> <?php echo $oprName; ?></h1>
    <div class="rb">
        <form name="form1" id="form1" action="" method="POST">
            <table border="0" cellspacing="0" cellpadding="0" class="updateTable">
                <tbody>
                        <tr>
                            <th>用户名：</th>
                            <td><input class="form-control" type="<?php if($isUpdate == 'true'){echo 'hidden';}elseif($isUpdate == 'false'){echo 'text';}?>" name="username" id="username" value="<?php echo $data['username'] ?>" /><?php if($isUpdate == 'true'){echo $data['username'];}elseif($isUpdate == 'false'){echo '';}?></td>
                        </tr>
                        <tr>
                            <th>密码：</th>
                            <td><input class="form-control" type="text" name="password" id="password" value="" /></td>
                        </tr>
                        <tr>
                            <th>姓名：</th>
                            <td><input class="form-control" type="text" name="realname" id="realname" value="<?php echo $data['realname'] ?>" /></td>
                        </tr>
                        <tr>
                            <th>性别：</th>
                                    <td>
                                        <select class="form-control"  name="sex" id="sex">
                                            <option value="">选择性别</option>
                                            <option value="男" <?php if($data['sex'] == '男'){echo 'selected="selected"';}?>>男</option>
                                            <option value="女" <?php if($data['sex'] == '女'){echo 'selected="selected"';}?>>女</option>
                                            <option value="保密" <?php if($data['sex'] == '保密'){echo 'selected="selected"';}?>>保密</option>
                                        </select>
                                    </td>
                        </tr>
                        <tr>
                            <th>手机：</th>
                            <td><input class="form-control" type="text" name="mobile" id="mobile" value="<?php echo $data['mobile'] ?>" /></td>
                        </tr>
                        <tr>
                            <th>email：</th>
                            <td><input class="form-control" type="text" size="80" name="email" id="email" value="<?php echo $data['email'] ?>" /></td>
                        </tr>
                        <?php if($isUpdate == 'false' || ($isUpdate == 'true' && $data['role'] == 1)){ ?>
                        <tr>
                            <th>角色：</th>
                            <td><input name="role" type="radio" value="1" checked="checked"/>观察者</td>
                        </tr>
                        <tr>
                            <th>可查看项目：</th>
                            <td>
                                <?php foreach((array)$items as $key => $value){ ?>
                                    <input name="projectList[]" type="checkbox" value="<?php echo $value['itemtag']; ?>" <?php if(in_array($value['itemtag'], (array)$data['projectList'])){ echo 'checked="checked"'; } ?> />
                                    <?php echo $value['itemname']; ?>
                                <?php }?>
                            </td>
                        </tr>
                        <?php }?>
                </tbody>
            </table>
        </form>
        <div class="oprBtns">
            <a class="add goSubmitUser btn btn-sm btn-info" href="javascript:void(0);" url="<?php echo $postUrl?>">确认</a>
            <div class="clear"></div>
        </div>
    </div>
</div>