<div class="rbox">
    <h1>
        <?php echo $desc['see']?>（<?php echo strtolower($controller).'/'.$funcname?>）
        <a href="javascript:void(0);" class="createDocument" url="<?php echo U('Tester/Index/apiToWord').'?controller='.$controller.'&funcname='.$funcname.'&path='.base64_encode($path).'&item='.$item.'&app='.$app?>">下载文档</a>
    </h1>
    <div class="rb">
        <div class="btn-group environment">
            <?php $c = 100 / count($envir);?>
            <?php foreach($envir as $key => $v){?>
            <?php if($key == 0){$url = $v['url'];}?>
            <button id="envi_<?php echo $key; ?>" class="btn btn-sm <?php if($key == 0){echo 'btn-info';}else{echo ' btn-default';}?>" url="<?php echo $v['url']?>"><?php echo str_replace('环境','',$v['name']).'环境'?></button>
            <?php }?>
        </div>
        <div id="rburl">
            <a target="_blank" href="<?php echo $envir[0]['url']?>"><?php echo $envir[0]['url']?></a>
        </div>
        <div>
            <?php echo $desc['describe']?>
        </div>
    </div>
</div>
<script type="text/javascript">setEnvironment();</script>
<?php if(!empty($desc['header'])){?>
<div class="rbox">
    <h1><span>HEADER参数</span><a class="showInfo" href="javascript:void(0)">展开 <i class="fa fa-chevron-down"></i></a></h1>
    <div class="rb">
        <div class="short">
            <ul>
                <?php foreach((array)$desc['header'] as $key => $value){?>
                <li title="<?php echo $value[3]?>"><?php echo $value[0]?></li>
                <?php }?>
                <div class="clear"></div>
            </ul>
            <div class="clear"></div>
        </div>
        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="paramsTable" style="display:none;">
            <tr>
                <th width="10%">参数名</th>
                <th width="10%">必选</th>
                <th width="10%">类型</th>
                <th width="75%" class="desc">说明</th>
            </tr>
            <?php foreach((array)$desc['header'] as $key => $value){?>
                <tr>
                    <td><?php echo $value[0]?></td>
                    <td><?php echo ($value[2] == 'required') ? 'true' : 'false'?></td>
                    <td><?php echo $value[1]?></td>
                    <td class="desc"><?php echo $value[3].(!empty($value[4]) ? '&nbsp('.$value[4].')' : ''); ?></td>
                </tr>
            <?php }?>
        </table>
    </div>
</div>
<?php }?>
<div class="rbox">
    <h1>HTTP提交方式</h1>
    <div class="rb">
        <div><?php echo empty($desc['method']) ? 'POST' : strtoupper($desc['method']) ; echo '发送'.$desc['requestType'].'数据'?></div>
    </div>
</div>
<?php if(!empty($desc['param'])){?>
<div class="rbox">
    <h1><span>请求参数</span><a class="showInfo" href="javascript:void(0)">展开 <i class="fa fa-chevron-down"></i></a></h1>
    <div class="rb">
        <div class="short">
            <ul>
                <?php foreach((array)$desc['param'] as $key => $value){?>
                <li title="<?php echo $value[3]?>"><?php echo $value[0]?></li>
                <?php }?>
                <div class="clear"></div>
            </ul>
            
            <div class="clearfix"></div>
        </div>
        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="paramsTable" style="display:none;">
            <tr>
                <th width="10%">参数名</th>
                <th width="10%">必选</th>
                <th width="10%">类型</th>
                <th width="75%" class="desc">说明</th>
            </tr>
            <?php foreach((array)$desc['param'] as $key => $value){?>
                <tr>
                    <td><?php echo $value[0]?></td>
                    <td><?php echo ($value[2] == 'required') ? 'true' : 'false'?></td>
                    <td><?php echo $value[1]?></td>
                    <td class="desc"><?php echo $value[3].(!empty($value[4]) ? '&nbsp('.$value[4].')' : ''); ?></td>
                </tr>
            <?php }?>
        </table>
    </div>
</div>
<?php }?>
<?php if(!empty($examples)){?>
<div class="rbox">
    <h1>参数示例</h1>
    <div class="rb">
        <?php 
        $examples = explode('(', $desc['example'][0]);
        $examples[1] = substr($examples[1], 0, strlen($examples[1])-1);
        $t = $examples[0];
        $examples = $examples[1];
        if($t == 'param'){
        ?>
        <?php $examples = explode('|', $examples);?>
        <?php ?>
        <?php if(empty($examples[0])){unset($examples[0]);}?>
        <?php if(count($examples) > 0){?>
        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="paramsTable">
            <tr>
                <th width="10%">参数名</th>
                <th width="10%" class="desc">参数内容</th>
            </tr>
            <?php foreach((array)$examples as $key => $value){?>
            <?php $s = explode(':', $value)?>
                <tr>
                    <td><?php echo $s[0]?></td>
                    <td style="word-wrap:break-word;word-break:break-all;" class="desc"><?php echo $s[1]?></td>
                </tr>
            <?php }?>
        </table>
        <?php }?>
        <?php } elseif($t == 'str') {echo $examples;}?>
    </div>
</div>
<?php }?>

<?php if($desc['baseauth'][0][0] == 'yes'){?>
<div class="rbox">
    <h1>基本认证</h1>
    <div class="rb">
        需要
    </div>
</div>
<?php }?>
<div class="rbox">
    <h1>返回参数基本格式</h1>
    <div class="rb">
        <?php if(empty($formats)){?>
            请您到项目管理中设置基本参数格式
        <?php } else {?>
        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="paramsTable">
            <tr>
                <th width="10%">参数名</th>
                <th width="10%">类型</th>
                <th width="75%" class="desc">说明</th>
            </tr>
            <?php foreach((array)$formats as $k => $v){?>
            <tr>
                <td><?php echo $v[0]?></td>
                <td><?php echo $v[1] == 'array' ? '<a href="#datareturn">'.$v[1].'</a>' : $v[1];?></td>
                <td class="desc"><?php echo $v[2]?></td>
            </tr>
            <?php }?>
        </table>
        <?php }?>
        
<!--        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="paramsTable">
            <tr>
                <th width="10%">参数名</th>
                <th width="10%">类型</th>
                <th width="75%">说明</th>
            </tr>
            <tr>
                <td>flag</td>
                <td>int</td>
                <td>成功状态：success，成功；fail，失败 </td>
            </tr>
            <tr>
                <td>message</td>
                <td><a href="#messagereturn">array</a></td>
                <td>成功状态提示</td>
            </tr>
            <tr>
                <td>datas</td>
                <td><a href="#datareturn">array</a></td>
                <td>接口调用成功返回内容</td>
            </tr>
        </table>-->
    </div>
</div>

<!--<div class="rbox" id="messagereturn">
    <h1>返回字段 - message</h1>
    <div class="rb">
        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="paramsTable">
            <tr>
                <th width="10%">参数名</th>
                <th width="10%">类型</th>
                <th width="75%">说明</th>
            </tr>
            <tr>
                <td>code</td>
                <td>string</td>
                <td>消息状态代码：200为成功，其他为错误代码</td>
            </tr>
            <tr>
                <td>msg</td>
                <td>string</td>
                <td>消息内容,如:获取成功</td>
            </tr>
        </table>
    </div>
</div>-->

<div class="rbox" id="datareturn">
    <h1>返回参数</h1>
    <?php if(!empty($desc['return'])){ ?>
        <div class="rb">
            <?php if(empty($desc['return'][0]) && isset($desc['return'][0]) && $desc['return'][1] == null){ echo '无返回'; }else{?>
                <table width="100%" cellspacing="0" cellpadding="0" border="0" class="paramsTable">
                    <tr>
                        <th width="10%">参数名</th>
                        <th width="10%">类型</th>
                        <th width="75%" class="desc">说明</th>
                    </tr>
                    <?php if(empty($desc['return'][0]) && isset($desc['return'][0])){?>
                        <tr>
                            <td>无</td>
                            <td><?php echo $desc['return'][1]?></td>
                            <td class="desc"><?php echo $desc['return'][2]?></td>
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
                                    <?php echo ($value[1] == 'table') ? $value[4] : $value[2]?>
                                </td>
                            </tr>
                    <?php } }?>
                </table>
            <?php }?>
        </div>
    <?php }else{
        echo '<div class="rb">未定义返回参数</div>';
    }?>
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
                        <th width="75%" class="desc">说明</th>
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
<div class="rbox">
    <h1>返回数据格式</h1>
    <div class="rb">JSON</div>
</div>

<?php if(!empty($desc['errorCode'])){?>
<div class="rbox">
    <h1>错误代码说明</h1>
    <div class="rb">
        <table class="paramsTable" width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
                <tr>
                    <th width="15%">错误代码</th>
                    <th>错误说明</th>
                </tr>
                <?php foreach($desc['errorCode'] as $key => $errorCode){?>
                <tr class="list_m_bg">
                    <td><?php echo $errorCode[0]?></td>
                    <td class="desc"><?php echo $errorCode[1].(empty($errorCode[2]) ? '' : '('.errorCode[2].')')?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>
<?php } ?>

<div class="rbox">
    <h1>更新记录</h1>
    <div class="rb">
        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="paramsTable">
            <tr>
                <th width="15%">版本</th>
                <th width="15%">作者</th>
                <th width="20%">修改时间</th>
                <th width="50%" class="desc">修改原因及内容</th>
            </tr>
            <?php foreach((array)$desc['author'] as $k => $v){?>
            <tr>
                <td><?php echo $v['v']?></td>
                <td><?php echo $v['author']?></td>
                <td><?php echo $v['time']?></td>
                <td class="desc"><?php echo $v['desc']?></td>
            </tr>
            <?php }?>
        </table>
    </div>
</div>
<?php if(!empty($data['notice'])){?>
<div class="rbox">
    <h1>注意事项</h1>
    <div class="rb"><?php echo $desc['notice']?></div>
</div>
<?php } ?>
<?php if(!empty($desc['datanoticeurlname'])){ ?>
    <div class="rbox">
        <h1><?php echo $desc['datanoticeurlname']?></h1>
        <div class="rb">
            <iframe border=2 width="<?php echo empty($desc['datanoticeurlwidth']) ? '100%' : $desc['datanoticeurlwidth']?>" height="<?php echo empty($desc['datanoticeurlheight']) ? 300 : $desc['datanoticeurlheight']?>" frameborder=0  marginheight=0 marginwidth=0 scrolling="yes" src="<?php echo $desc['datanoticeurlcontent']?>" onload="Javascript:SetWinHeight(this)"></iframe>
        </div>
    </div>
<?php } ?>



<?php if(!empty($introduce)){ ?>
<div class="rbox">
    <h1>补充说明</h1>
    <div class="rb">
        <div class="buchong">
            <?php echo $introduce?>
        </div>
    </div>
</div>
<?php } ?>

{|}
<div class="apiTool">
    <h1>API测试工具</h1>
    <div class="rbox" id="testtool">
        <?php if(!empty($desc['header'])){?>
        <h2>HTTP协议请求头(header)：</h2>
        <div class="rb" id="headers">
            <ul class="testerForms">
                <?php foreach((array)$desc['header'] as $key => $value){?>
                    <li>
                        <b><?php echo $value[0]?>：</b>
                        <span>
                            <input class="form-control" type="text" name="<?php echo $value[0]?>" id="<?php echo $value[0]?>" value="<?php echo $value[5]?>" />
                        </span>
                        <em> <?php echo $value[2] == 'required' ? '*' : ''?><?php echo isset($value[4])? $value[4] : ''?></em>
                        <div class="clearfix"></div>
                    </li>
                <?php }?>
            </ul>
        </div>
        <?php }?>
    </div>   
    <div class="rbox" id="testtool">
        <?php if(!empty($desc['baseauth']) && $desc['baseauth'][0][0] == 'yes'){?>
        <h2>基本认证：</h2>
        <div class="rb"  id="baseauth">
            <ul class="testerForms">
                    <li>
                        <b>用户名：</b>
                        <span><input class="form-control" type="text" name="username" id="username" value="<?php echo isset($desc['baseauth'][0][1])? $desc['baseauth'][0][1] : ''?>" /></span>
                        <em> *</em>
                        <div class="clearfix"></div>
                    </li>
                    <li>
                        <b>密码：</b>
                        <span><input class="form-control" type="text" name="password" id="password" value="<?php echo isset($desc['baseauth'][0][2])? $desc['baseauth'][0][2] : ''?>" /></span>
                        <em> *</em>
                        <div class="clearfix"></div>
                    </li>
            </ul>
        </div>
        <?php }?>
    </div>
    <div class="rbox" id="testtool">
        <h2>请求参数：</h2>
        <?php //if($isfile){?>
        <!--<form name="fileForm" id="fileForm" method="POST" action="<?php //echo $url?>" target="fileFrame" enctype="multipart/form-data">-->
        <?php //}?>

        <div class="rb">
            <input type="hidden" name="requestType" id="requestType" value="<?php echo $desc['requestType']?>" />
            <?php if($desc['requestType'] == 'FORM'){?>
            <ul class="testerForms formDatas">
                <!--系统参数-->
                <!--参数：-->
                <?php foreach((array)$systemoption as $key => $value){ ?>
                    <li>
                        <b><?php echo $value['name']?>：</b>
                        <span>
                            <?php if($value['type'] == 'text'){?>
                                <textarea class="form-control" name="<?php echo $key?>" id="<?php echo $key?>"></textarea>
                            <?php }elseif($value[5] == 'file'){ ?>
                                <input class="form-control" type="file" name="<?php echo $key?>" id="<?php echo $key?>" />
                            <?php }else{ ?>
                                <input class="form-control" type="text" name="<?php echo $key?>" id="<?php echo $key?>" />
                            <?php }?>
                        </span>
                        <em><?php echo $value['required'] ? ' * ' : ''; ?><?php echo $value['decription']?></em>
                        <div class="clearfix"></div>
                    </li>
                <?php }?>
                <!--<hr/>-->
                <!--系统参数-->
                <?php foreach((array)$desc['param'] as $key => $value){?>
                    <li>
                        <b><?php echo $value[3]?>：</b>
                        <span>
                            <?php if($value[1] == 'text'){?>
                                <textarea class="form-control" name="<?php echo $value[0]?>" id="<?php echo $value[0]?>"></textarea>
                            <?php }elseif($value[1] == 'file'){ ?>
                                <input class="form-control" type="file" name="<?php echo $value[0]?>" id="<?php echo $value[0]?>" />
                            <?php }else{ ?>
                                <input class="form-control" type="text" name="<?php echo $value[0]?>" id="<?php echo $value[0]?>" />
                            <?php }?>
                        </span>
                        <em> <?php echo $value[2] == 'required' ? '*' : ''?><?php echo $value[4]?></em>
                        <div class="clearfix"></div>
                    </li>
                <?php }?>
            </ul>
            <?php }else{ ?>
                <ul class="testerForms formDatas"><li><b>JSON数据：</b><span><textarea class="form-control" name="jsondata" id="jsondata"></textarea></span><div class="clearfix"></div></li></ul>
            <?php }?>
            </div>
            <div class="rb testerBtnsBox">
                <div class="testerBtns">
                    <?php if($isfile){?>
    <!--                <button type="button" id="submit" name="submit">确认提交</button>
                    <button type="submit" id="qsubmit" name="submit"></button>-->
                    <a id="uploadFileBtn" class="uploadFileBtn btn btn-sm btn-info" tag="<?php if(empty($desc['param'])){ echo 'unparameter'; }else{ echo 'parameter';}?>" url ="<?php echo $url?>" method="<?php echo empty($desc['method']) ? 'POST' : strtoupper($desc['method'])?>" href="javascript:void(0);">确认提交</a>
                    <?php }else{?>
                    <a class="testBtn btn btn-sm btn-info" cookie="<?=$desc['cookie']?>" tag="<?php if(empty($desc['param'])){ echo 'unparameter'; }else{ echo 'parameter';}?>" url ="<?php echo $url?>" method="<?php echo empty($desc['method']) ? 'POST' : strtoupper($desc['method'])?>" href="javascript:void(0);">确认提交</a>
                    <?php }?>
                </div>
            </div>
        <?php //if($isfile){?>
        <!--</form>-->
        <?php //}?>
    </div>
    <?php //if($isfile){?>
    <!--<iframe id="fileFrame" name="fileFrame" src="" frameborder="0" width="100%" height="400"></iframe>-->
    <?php //}?>
    <div class="returnCode">
        <div class="rbox" id="returnjsoncode">&nbsp;</div>
    </div>
</div>
