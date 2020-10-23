<div class="rbox">
    <h1><i class="fa fa-cogs"></i> 项目设置</h1>
    <div class="rb">
        <div class="btn-group">
            <a class="add goUrl btn btn-sm btn-info" href="javascript:void(0);" url="<?php echo U('Tester/Items/itemAdd')?>"><i class="fa fa-plus"></i></a>
            <a class="del btn btn-sm btn-danger" href="javascript:void(0);" url="<?php echo U('Tester/Items/doItemDelete')?>"><i class="fa fa-remove"></i></a>
            <div class="clearfix"></div>
        </div>
        <table border="0" cellspacing="0" cellpadding="0" class="listTable">
            <thead>
                <tr>
                    <th class="selectAll"><input type="checkbox" name="selectAll" id="selectAll" /></th>
                    <th>项目名称</th>
                    <th>文件命名规范</th>
                    <th>项目负责人</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ((array) $data as $key => $item) { ?>
                    <tr>
                        <td class="selectAll"><input type="checkbox" name="itemtags[]" value="<?php echo $item['itemtag'] ?>" /></td>
                        <td><?php echo $item['itemname'] ?></td>
                        <td><?php echo $item['fileNameRule'];?></td>
                        <td><?php echo $item['username'] ?></td>
                        <td><a class="update goUrl btn btn-sm btn-info" href="javascript:void(0);" url="<?php echo U('Tester/Items/itemUpdate', array('itemtag' => $item['itemtag']))?>"><i class="fa fa-pencil"></i></a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>