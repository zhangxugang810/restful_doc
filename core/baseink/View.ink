{loadcss}
{loadjs}
<form class="listbox" id="saveAjaxForm" name="saveAjaxForm" action="<?php echo isset($id) ? U(APP_NAME . '/{MoudleTag1}s/do{MoudleTag1}Edit') : U(APP_NAME . '/{MoudleTag1}s/do{MoudleTag1}Add') ?>" method="POST">
    <input type="hidden" name="{MoudleTag}id" id="{MoudleTag}id" value="<?php echo isset($id) ? $id : '' ?>"/>
    {hiddenForm}
    <table class="winTable" border="0" cellpadding="0" cellspacing="0">
        {foreach}
        <tr>
            <th width="30"><?php echo L('{fieldName}') ?>：</th>
            <td>{fieldForm}</td>
        </tr>
        {foreach}
    </table>

    <div class="saveBtn">
        <input type="submit" {wId} name="submit" id="submit" value="<?php echo L('save') ?>" />
        <input type="button" {wId} name="cancel" class="cancel" id="cancel" value="<?php echo L('cancel') ?>" />
    </div>
</form>
<script type="text/javascript">
    $('#saveAjaxForm').find('input[type=checkbox]').hide(0, function(){changeCheckBox($(this))});
    $('#saveAjaxForm').easyValidate({ajaxSubmit:true,formid:'saveAjaxForm'});
</script>
