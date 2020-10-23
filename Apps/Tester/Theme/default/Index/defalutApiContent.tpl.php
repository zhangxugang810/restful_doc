<div class="rbox">
    <h1><?php echo $controller.'/'.$funcname?></h1>
    <div class="rb"><?php echo $desc['see']?></div>
</div>
<div class="rbox">
    <h1>URL</h1>
    <div class="rb"><a target="_blank" href="<?php echo $url?>"><?php echo $url?></a></div>
</div>
<div class="rbox">
    <h1>支持格式</h1>
    <div class="rb">JSON</div>
</div>
<div class="rbox">
    <h1>HTTP请求方式</h1>
    <div class="rb"><?php echo empty($desc['method']) ? 'POST' : strtoupper($desc['method'])?></div>
</div>
<div class="rbox">
    <h1>请求参数</h1>
    <div class="rb">
        <?php if(empty($desc['param'])){?>
            无参数
        <?php }else{?>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" class="paramsTable">
                <tr>
                    <th width="10%">参数名</th>
                    <th width="10%">必选</th>
                    <th width="10%">类型</th>
                    <th width="75%">说明</th>
                </tr>
                <?php foreach((array)$desc['param'] as $key => $value){?>
                    <tr>
                        <td><?php echo $value[0]?></td>
                        <td><?php echo ($value[2] == 'required') ? 'true' : 'false'?></td>
                        <td><?php echo $value[1]?></td>
                        <td class="desc"><?php echo $value[3]?></td>
                    </tr>
                <?php }?>
            </table>
        <?php }?>
    </div>
</div>
<div class="rbox">
    <h1>注意事项</h1>
    <div class="rb"><?php echo $desc['notice']?></div>
</div>
<div class="rbox">
    <h1>调试工具</h1>
    <div class="rb"><a href="#testtool">API测试工具</a></div>
</div>
<div class="rbox">
    <h1>返回字段</h1>
    <div class="rb">
        <?php if(empty($desc['return'][0]) && isset($desc['return'][0]) && $desc['return'][1] == null){ echo '无返回'; }else{?>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" class="paramsTable">
                <tr>
                    <th width="10%">参数名</th>
                    <th width="10%">类型</th>
                    <th width="75%">说明</th>
                </tr>
                <?php if(empty($desc['return'][0]) && isset($desc['return'][0])){?>
                    <tr>
                        <td>无</td>
                        <td><?php echo $desc['return'][1]?></td>
                        <td><?php echo $desc['return'][2]?></td>
                    </tr>
                <?php }else{ ?>
                <?php foreach((array)$desc['return'] as $key => $value){?>
                        <tr>
                            <td><?php echo $value[0]?></td>
                            <td>
                                <?php if($value[1] == 'table' || $value[1] == 'array'){?>
                                     <a href="#return_<?php echo $value[0]?>">object</a>
                                <?php }else{?>
                                     <?php echo $value[1]?>
                                <?php }?>
                            </td>
                            <td class="desc">
                                <?php echo ($value[1] == 'table' || $value[1] == 'array') ? $value[4] : $value[2]?>
                            </td>
                        </tr>
                <?php } }?>
            </table>
        <?php }?>
    </div>
</div>
<?php foreach((array)$desc['returnarray'] as $key => $v){?>
    <div class="rbox" id="<?php echo $key?>">
        <?php 
            $keylist = explode('_', $key);
            $keyname = $keylist[count($keylist)-1];
            unset($keylist[count($keylist)-1]);
            $deskey = implode('_', $keylist);
            if($deskey != 'return'){
                $descarray = $desc['returnarray'][$deskey];
            }else{
                $descarray = $desc['return'];
            }
            foreach((array)$descarray as $descval){
                if($descval[0] == $keyname){
                    if($descval[1] == 'table'){
                        $descript = $descval[4];
                    }else{
                        $descript = $descval[2];
                    }
                }
            }
        ?>
        <h2><?php echo $descript.'('.$keyname.')'?></h2>
        <div class="rb">
            <table width="100%" cellspacing="0" cellpadding="0" border="0" class="paramsTable">
                <?php if(!empty($v)){ ?>
                    <tr>
                        <th width="10%">参数名</th>
                        <th width="10%">类型</th>
                        <th width="75%">说明</th>
                    </tr>
                    <?php foreach((array)$v as $k => $value){?>
                        <tr>
                            <td><?php echo $value[0]?></td>
                            <td>
                                <?php if($value[1] == 'table' || $value[1] == 'array'){?>
                                     <a href="#<?php echo $key.'_'.$value[0]?>">object</a>
                                <?php }else{?>
                                     <?php echo $value[1]?>
                                <?php }?>
                            </td>
                            <td class="desc">
                                <?php if($value[1] == 'table'){
                                    echo $value[4];
                                }else{
                                    echo $value[2];
                                }
                                ?>
                            </td>
                        </tr>
                    <?php }?>
                <?php }?>
            </table>
        </div>
    </div>
<?php }?>
<div class="rbox" id="testtool">
    <h1>API测试工具</h1>
    <div class="rb">
        <ul class="testerForms">
            <?php foreach((array)$desc['param'] as $key => $value){?>
                <li>
                    <b><?php echo $value[3]?>：</b>
                    <span>
                        <?php if($value[5] == 'text'){?>
                            <textarea name="<?php echo $value[0]?>" id="<?php echo $value[0]?>"></textarea>
                        <?php }else{ ?>
                            <input type="text" name="<?php echo $value[0]?>" id="<?php echo $value[0]?>" />
                        <?php }?>
                    </span>
                    <em> <?php echo $value[2] == 'required' ? '*' : ''?><?php echo $value[4]?></em>
                    <div class="clear"></div>
                </li>
            <?php }?>
        </ul>
        <div class="testerBtns">
            <a class="testBtn" tag="<?php if(empty($desc['param'])){ echo 'unparameter'; }else{ echo 'parameter';}?>" url ="<?php echo $url?>" method="<?php echo empty($desc['method']) ? 'POST' : strtoupper($desc['method'])?>" href="javascript:void(0);">确认提交</a>
        </div>
    </div>
</div>
<div class="rbox" id="returnjsoncode">
</div>