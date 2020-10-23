<div class="rbox">
    <h1><i class="fa fa-user"></i> 用户</h1>
    <div class="rb">
        <div class="btn-group"><!--oprBtns -->
            <a class="add goUrl btn btn-sm btn-info" href="javascript:void(0);" url="<?php echo U('Tester/Users/userAdd')?>"><i class="fa fa-plus"></i></a>
            <a class="del btn btn-sm btn-danger" href="javascript:void(0);" url="<?php echo U('Tester/Users/doUserDelete')?>"><i class="fa fa-remove"></i></a>
            <div class="clearfix"></div>
        </div>
        <table border="0" cellspacing="0" cellpadding="0" class="listTable">
            <thead>
                <tr>
                    <th class="selectAll"><input type="checkbox" name="selectAll" id="selectAll" /></th>
                    <th>用户名</th>
                    <th>姓名</th>
                    <!--<th>所属组</th>-->
                    <th>注册日期</th>
                    <th>登录日期</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ((array) $data as $key => $user) { ?>
                    <tr>
                        <td class="selectAll">
                            <?php if($me['username'] != $user['username'] && $user['founder'] != 'yes'){?>
                            <input type="checkbox" name="usernames[]" value="<?php echo $user['username'] ?>" />
                            <?php }?>
                        </td>
                        <td><?php echo $user['username'] ?><?php if($me['username'] == $user['username']){echo '（我自己）';}?><?php if($user['founder'] == 'yes'){echo '（创始人）';}?></td>
                        <td><?php echo $user['realname'] ?></td>
                        <!--<td><?php echo $user['groupname'] ?></td>-->
                        <td><?php echo $user['ctime'] ?></td>
                        <td><?php echo $user['logintime'] ?></td>
                        <td><a class="update goUrl btn btn-sm btn-info" href="javascript:void(0);" url="<?php echo U('Tester/Users/userUpdate', array('username' => $user['username']))?>"><i class="fa fa-pencil"></i></a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>