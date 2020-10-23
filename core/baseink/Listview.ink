<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <?php S('admin') ?>
            {loadcss}
            <?php include(getTplPath('Public/js'));?>
            <?php S('sys_validate', 'js') ?>
            <?php S('{MoudleTag}s', 'js') ?>
            {loadjs}
    </head>
    <body>
        <div class="pos"><i class="icon-arrow-right"></i><em><?php echo '{MoudleName}' . L('manage') ?> &gt; <a href="<?php U(APP_NAME.'/'.MODEL_NAME.'/{MoudleTag}List')?>"><?php echo '{MoudleName}' . L('list') ?></a></em></div>
        <div class="listBtns">
            <div class="bleft">
                {addOne}
                {selectAll}
                {deleteSel}
                {saveOrders}
            </div>
            <div class="bright">
                <form method="POST" action="" name="searchForm" id="searchForm" class="search">
                    <input type="text" name="keyword" id="keyword" value="<?php echo $keyword?>" />
                    <select name="fd" id="fd">
                        <option value="">选择要搜索的字段</option>
                        <?php foreach((array)$properties as $key => $value){?>
                        <option value="<?php echo $key ?>" <?php if($fd == $key){?>selected="selected"<?php }?>><?php echo $value ?></option>
                        <?php }?>
                    </select>
                    <button type="submit" class="button-middle button-blue"><i class="icon-search"></i></button>
                </form>
            </div>
        </div>
        <form id="listForm" name="listForm" action="" method="POST">
            <div class="listbox">
                <table class="list" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <th width="30" class="cent"></th>
                        <th width="60" class="cent"><?php echo L('id')?></th>
                        {ordersTitle}
                        {foreach}
                        <th><?php echo L('{fieldName}') ?></th>
                        {foreach}
                        {auditTitle}
                        <th width="150"><?php echo L('operate') ?></th>
                    </tr>

                    <?php foreach ((array) $data as $key => $value) { ?>
                        <tr>
                            <td class="cent"><input type="checkbox" name="ids[]" class="ids" value="<?php echo $value['{MoudleTag}id'] ?>"/></td>
                            <td class="cent">{mdlid}</td>
                            {ordersList}
                            {foreach}
                            <td class="cent">{fieldTag}</td>
                            {foreach}
                            {auditList}
                            <td class="cent">
                                {editOne}
                                {deleteOne}
                            </td>
                        </tr>
                    <?php } ?>

                </table>
            </div>
            <div class="pages" style="<?php if(!empty($pageStr)){ ?>display:block;<?php }else{?>{commendsDisplay}<?php }?>">
                {recommends}
                <?php if(!empty($pageStr)){ ?>
                <div class="opList">
                    <input type="hidden" name="fd" value="<?php echo $fd?>"/>
                    <input type="hidden" name="keyword" value="<?php echo $keyword?>"/>
                    <?php echo $pageStr ?>
                </div>
                <?php }?>
                <div class="clear"></div>
            </div>
        </form>
    </body>
</html>