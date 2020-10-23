<div class="rbox">
    <h1><i class="fa fa-users"></i> <?php echo $oprName; ?></h1>
    <div class="rb">
        <form name="form1" id="form1" action="" method="POST">
            <table border="0" cellspacing="0" cellpadding="0" class="updateTable">
                <tbody>
                        <tr>
                            <th>组名称：</th>
                            <td><input class="form-control" size="50" type="<?php if($isUpdate == 'true'){echo 'hidden';}elseif($isUpdate == 'false'){echo 'text';}?>" name="groupname" id="groupname" value="<?php echo $data['groupname'] ?>" /><?php if($isUpdate == 'true'){echo $data['groupname'];}elseif($isUpdate == 'false'){echo '';}?></td>
                        </tr>
                        <tr>
                            <th>组成员设置：</th>
                            <td>
                                <div class="input-group">
                                    <select class="form-control" name="username" id="username">
                                        <option value="0">选择用户</option>
                                        <?php foreach($users as $key => $user){?>
                                        <option value="<?php echo $user['username']?>"><?php echo $user['username']?></option>
                                        <?php }?>
                                    </select>
                                    <span class="input-group-addon"><a href="javascript:void(0);" class="addBtn addGroupUser">增加</a></span>
                                </div>
                                <ul class="groupUserList">
                                    <?php foreach((array) $data['groupUsers'] as $key => $user){?>
                                    <li username="<?php echo $user?>"><input type="hidden" name="groupUsers[]" value="<?php echo $user?>" /><span><?php echo $user?></span><em class="removeUser"><i class="icon icon-remove"></i></em></li>
                                    <?php }?>
                                    <div class="clear"></div>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>组权限设置：</th>
                            <td>
                                <?php foreach($items as $k => $item){?>
                                <div class="limitbox">
                                    <h2><input type="checkbox" name="items[<?php echo $k?>]" class="items selectChild" value="<?php echo $item['itemtag']?>" <?php if(in_array($item['itemtag'], $data['items'])){echo 'checked="checked"';}?> /><?php echo $item['itemname']?></h2>
                                        <?php foreach($item['classes'] as $key => $class){?>
                                        <ul class="classbox">
                                            <h3><input type="checkbox" name="<?php echo $item['itemtag']?>[<?php echo $key?>]" class="classes  selectChild selectTop" value="<?php echo $class['classtag']?>" <?php if(in_array($class['classtag'], $data[$item['itemtag']])){echo 'checked="checked"';}?> /><?php echo $class['classname']?></h3>
                                            <?php foreach($class['funcs'] as $fkey => $func){?>
                                            <li><input type="checkbox" name="<?php echo $item['itemtag'].'_'.$class['classtag']?>[<?php echo $fkey?>]" class="funcs selectItem" value="<?php echo $func['name']?>" <?php if(in_array($func['name'], $data[$item['itemtag'].'_'.$class['classtag']])){echo 'checked="checked"';}?> /><?php echo $func['see']?></li>
                                            <?php }?>
                                            <div class="clear"></div>
                                        </ul>
                                        <?php }?>
                                </div>
                                <?php }?>
                            </td>
                        </tr>
                        <tr>
                            <th>组管理员：</th>
                            <td>
                                <select class="form-control" name="groupManager" id="groupManager">
                                    <option value="0">选择管理员</option>
                                    <?php foreach($users as $key => $user){?>
                                    <option value="<?php echo $user['username']?>" <?php if($data['groupManager'] == $user['username']){echo 'selected="selected"';}?>><?php echo $user['username']?></option>
                                    <?php }?>
                                </select>
                            </td>
                        </tr>
                </tbody>
            </table>
        </form>
        <div class="oprBtns">
            <a class="add goSubmit" href="javascript:void(0);" url="<?php echo $postUrl?>">确认</a>
            <div class="clear"></div>
        </div>
    </div>
</div>