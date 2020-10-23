<div class="rbox">
    <h1><i class="fa fa-users"></i> 用户组</h1>
    <div class="rb">
        <div class="btn-group"><!--oprBtns-->
            <a class="add goUrl btn btn-sm btn-info" href="javascript:void(0);" url="<?php echo U('Tester/Groups/groupAdd')?>"><i class="fa fa-plus"></i></a>
            <a class="del btn btn-sm btn-danger" href="javascript:void(0);" url="<?php echo U('Tester/Groups/doGroupDelete')?>"><i class="fa fa-remove"></i></a>
            <div class="clearfix"></div>
        </div>
        <table border="0" cellspacing="0" cellpadding="0" class="listTable">
            <thead>
                <tr>
                    <th class="selectAll"><input type="checkbox" name="selectAll" id="selectAll" /></th>
                    <th>组名</th>
                    <th>组管理员</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ((array) $data as $key => $group) { ?>
                    <tr>
                        <td class="selectAll"><input type="checkbox" name="groupnames[]" value="<?php echo $group['groupname'] ?>" /></td>
                        <td><?php echo $group['groupname'] ?></td>
                        <td><?php echo $group['groupManager'] ?></td>
                        <td><a class="update goUrl btn btn-sm btn-info" href="javascript:void(0);" url="<?php echo U('Tester/Groups/groupUpdate', array('groupname' => $group['groupname']))?>"><i class="fa fa-pencil"></i></a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>