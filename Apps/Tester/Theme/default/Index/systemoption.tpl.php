<div class="rbox">
    <h1>系统参数</h1>
    <div class="rb">系统参数</div>
</div>
<div class="rbox">
    <h1>HTTP请求方式</h1>
    <div class="rb">head</div>
</div>
<div class="rbox">
    <h1>请求参数</h1>
    <div class="rb">
        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="paramsTable">
            <tr>
                <th width="10%">参数名</th>
                <th width="10%">必选</th>
                <th width="10%">类型</th>
                <th width="75%">说明</th>
            </tr>
            <?php foreach((array)$systemoption as $key => $value){?>
                <tr>
                    <td><?php echo $key; ?></td>
                    <td><?php echo $value['required'] ? 'true' : 'false'; ?></td>
                    <td><?php echo $value['type']; ?></td>
                    <td><?php echo $value['name']; ?></td>
                </tr>
            <?php }?>
        </table>
    </div>
</div>
<div class="rbox">
    <h1>注意事项</h1>
    <div class="rb">如果对应参数没有值，则传空</div>
</div>
