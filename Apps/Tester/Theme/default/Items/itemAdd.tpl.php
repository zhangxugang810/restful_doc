<?php S('editor/ueditor.config', 'js')?>
<?php S('editor/ueditor.all', 'js')?>
<?php S('editor/lang/zh-cn/zh-cn', 'js')?>
<div class="rbox">
    <h1><i class="fa fa-user"></i> <?php echo $oprName; ?></h1>
    <div class="rb">
        <form name="form1" id="form1" action="" method="POST">
            <input size="50" type="hidden" name="itemtag" id="itemtag" value="<?php echo $projectId ?>" />
            <table border="0" cellspacing="0" cellpadding="0" class="updateTable">
                <tbody>
                    <tr>
                        <th>项目API实际地址：</th>
                        <td>
                            <ul class="editInputs">
                                <?php if(empty($data['itemDirs'])){?>
                                <li>
                                    <input class="itemDirs" is="no" name="itemDirs[]" type="text" size="70" value="" placeholder="接口文件所在目录" />
                                    <!--<input class="" name="itemDirUrls[]" type="text" value="" size="30" placeholder="接口文件目录URL，不含域名" />-->
                                    <div class="btn-group">
                                        <button class="addDir btn btn-sm btn-info" type="button"><span><i class="fa fa-plus"></i></span></button>
                                        <button class="delDir btn btn-sm btn-danger" type="button"><span><i class="fa fa-minus"></i></span></button>
                                    </div>
                                    <div class="errorMsg"></div>
                                    
                                </li>
                                <?php }else{?>
                                <?php foreach((array)$data['itemDirs'] as $key => $dir){?>
                                <li>
                                    <input class="itemDirs" is="no" name="itemDirs[]" type="text" size="70" value="<?php echo str_replace('\\\\', '\\', $dir)?>" placeholder="接口文件所在目录" />
                                    <!--<input name="itemDirUrls[]" type="text" value="<?php echo $data['itemDirUrls'][$key]?>" size="30" placeholder="接口文件目录URL，不含域名" />-->
                                    <div class="btn-group">
                                        <button class="addDir btn btn-sm btn-info" type="button"><span><i class="fa fa-plus"></i></span></button>
                                        <button class="delDir btn btn-sm btn-danger" type="button"><span><i class="fa fa-minus"></i></span></button>
                                    </div>
                                    <div class="errorMsg"></div>
                                </li>
                                <?php }}?>
                                <script type="text/javascript">checkDir($('.itemDirs'));</script>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>项目名称：</th>
                        <td>
                            <div class="editInputs"><input size="50" type="text" name="itemname" id="itemname" value="<?php echo $data['itemname'] ?>" /></div>
                            <div class="errorMsg"></div>
                        </td>
                    </tr>
                    <tr>
                        <th>文件命名规范：</th>
                        <td>
                            <div class="editInputs"><input name="fileNameRule" id="fileNameRule"  type="text" value="<?php echo $data['fileNameRule'] ?>" placeholder="文件命名规范，如：{name}Controller" size="50"/></div>
                            <div class="errorMsg"></div>
                        </td>
                    </tr>
                    <tr>
                        <th>Rewrite规则：</th>
                        <td>
                            <div class=""></div>
                            <div class="editInputs"><input name="rewrite" id="rewrite" type="text" size="70" value="<?php echo $data['rewrite'] ?>" placeholder="文件命名规范，如：{App}/{Controller}/{Action}.html" size="50"/></div>
                            <div class="errorMsg"></div>
                        </td>
                    </tr>
<!--                    <tr>
                        <th>项目负责人：</th>
                            <td>
                                <select name="username" id="username">
                                    <option value="0">选择项目负责人</option>
                                    <?php //foreach($users as $key => $user){?>
                                        <option value="<?php //echo $user['username']?>" <?php //if($data['username'] == $user['username']){echo 'selected="selected"';}?>><?php //echo $user['username']?></option>
                                    <?php //}?>
                                </select>
                    </tr>-->
                    <tr>
                        <th>调试环境：</th>
                        <td>
                            <?php //print_r($data['urls'])?>
                            <ul class="editInputs">
                                <?php if(empty($data['urls'])){?>
                                <li>
                                    <input name="urls[name][]" type="text" value="开发" size="6" placeholder="环境名称" /> ：
                                    <input name="urls[url][]" type="text" value="" size="50" placeholder="环境调试基础地址，如：http://dev.ink.com" />
                                    <div class="btn-group">
                                        <button type="button" class="addRule btn btn-sm btn-info"><span><i class="fa fa-plus"></i></span></button>
                                        <button type="button" class="delRule btn btn-sm btn-danger"><span><i class="fa fa-minus"></i></span></button>
                                    </div>
                                </li>
                                <li>
                                    <input name="urls[name][]" type="text" value="测试" size="6" placeholder="环境名称" /> ：
                                    <input name="urls[url][]" type="text" value="" size="50" placeholder="环境调试基础地址，如：http://test.ink.com" />
                                    <div class="btn-group">
                                        <button type="button" class="addRule btn btn-sm btn-info"><span><i class="fa fa-plus"></i></span></button>
                                        <button type="button" class="delRule btn btn-sm btn-danger"><span><i class="fa fa-minus"></i></span></button>
                                    </div>
                                </li>
                                <li>
                                    <input name="urls[name][]" type="text" value="生产" size="6" placeholder="环境名称" /> ：
                                    <input name="urls[url][]" type="text" value="" size="50" placeholder="环境调试基础地址，如：http://www.ink.com" />
                                    <div class="btn-group">
                                        <button type="button" class="addRule btn btn-sm btn-info"><span><i class="fa fa-plus"></i></span></button>
                                        <button type="button" class="delRule btn btn-sm btn-danger"><span><i class="fa fa-minus"></i></span></button>
                                    </div>
                                </li>
                                <?php }else{?>
                                <?php foreach($data['urls']['name'] as $k => $v){?>
                                <li>
                                    <input name="urls[name][]" type="text" value="<?php echo empty($v) ? '' : $v?>" size="6" placeholder="环境名称" /> ：
                                    <input name="urls[url][]" type="text" value="<?php echo empty($data['urls']['url'][$k]) ? '' : $data['urls']['url'][$k]?>" size="50" placeholder="环境调试基础地址，如：http://dev.ink.com" />
                                    <div class="btn-group">
                                        <button type="button" class="addRule btn btn-sm btn-info"><span><i class="fa fa-plus"></i></span></button>
                                        <button type="button" class="delRule btn btn-sm btn-danger"><span><i class="fa fa-minus"></i></span></button>
                                    </div>
                                </li>
                                <?php }}?>
                            </ul>
                        </td>
                    </tr>
<!--                    <tr>
                        <th>任务设置：</th>
                        <td>
                            <ul class="editInputs">
                                <li>
                                    <input name="dateStart" type="text" value="<?php //echo $data['dateStart']?>" size="50" placeholder="开始时间，如：2016-10-01 10:10:10" />
                                    <label><input type="radio" name="repeat" id="repeat" value="one" <?php //if($data['repeat'] == 'one'){echo 'checked="checked"';}?> /> 单次</label>
                                    <label><input type="radio" name="repeat" id="repeat" value="repeat" <?php //if($data['repeat'] == 'repeat'){echo 'checked="checked"';}?> /> 循环</label>
                                    <input name="interval" type="text" value="<?php //echo $data['interval']?>" size="20" placeholder="循环间隔,如：10" />
                                    <select id="timeUnit" name="timeUnit">
                                        <option value="0">时间单位</option>
                                        <option value="year" <?php //if($data['timeUnit'] == 'year'){echo 'selected="selected"';}?>>年</option>
                                        <option value="month" <?php //if($data['timeUnit'] == 'month'){echo 'selected="selected"';}?>>月</option>
                                        <option value="week" <?php //if($data['timeUnit'] == 'week'){echo 'selected="selected"';}?>>周</option>
                                        <option value="day" <?php //if($data['timeUnit'] == 'day'){echo 'selected="selected"';}?>>日</option>
                                        <option value="hour" <?php //if($data['timeUnit'] == 'hour'){echo 'selected="selected"';}?>>时</option>
                                        <option value="minute" <?php //if($data['timeUnit'] == 'minute'){echo 'selected="selected"';}?>>分</option>
                                        <option value="second" <?php //if($data['timeUnit'] == 'second'){echo 'selected="selected"';}?>>秒</option>
                                    </select>
                                </li>
                            </ul>
                        </td>
                    </tr>-->
<!--                    <tr>
                        <th>流程图：</th>
                        <td>
                            <ul class="editInputs">
                                <?php //if(empty($data['flows'])){?>
                                <li>
                                    <input name="flows[name][]" type="text" value="" size="16" placeholder="流程图名称" /> ：
                                    <input name="flows[url][]" type="text" value="" size="50" placeholder="流程图地址" /> 
                                    <input name="upload" type="file" value="" class="hide" size="50" placeholder="上传流程图" />
                                    <button type="button" id="Uploadfile_0" class="Uploadfile btn btn-sm btn-primary" url="<?php //echo U('Tester/Index/uploadFile')?>">上传图片</button>
                                    <div class="btn-group">
                                        <button type="button" class="addFlow btn btn-sm btn-info"><span><i class="fa fa-plus"></i></span></button>
                                        <button type="button" class="delFlow btn btn-sm btn-danger"><span><i class="fa fa-minus"></i></span></button>
                                    </div>
                                </li>
                                <?php //}else{?>
                                <?php //foreach($data['flows']['name'] as $k => $v){?>
                                <li>
                                    <input name="flows[name][]" type="text" value="<?php //echo empty($v) ? '' : $v;?>" size="16" placeholder="流程图名称" /> ：
                                    <input name="flows[url][]" type="text" value="<?php //echo empty($data['flows']['url'][$k]) ? '' : $data['flows']['url'][$k];?>" size="50" placeholder="流程图地址" /> 
                                    <input name="upload" type="file" value="" class="hide" size="50" placeholder="上传流程图" />
                                    <button type="button" id="Uploadfile_<?php //echo $k;?>" class="Uploadfile btn btn-sm btn-primary" url="<?php //echo U('Tester/Index/uploadFile')?>">上传图片</button>
                                    <div class="btn-group">
                                        <button type="button" class="addFlow btn btn-sm btn-info"><span><i class="fa fa-plus"></i></span></button>
                                        <button type="button" class="delFlow btn btn-sm btn-danger"><span><i class="fa fa-minus"></i></span></button>
                                    </div>
                                </li>
                                <?php //}}?>
                            </ul>
                        </td>
                    </tr>-->
                    <tr>
                        <th>返回参数基本格式：</th>
                        <td>
                            <div class="formHelp">
                                <div class="helpTitle">参数设置方法：<a class="showHelpCon" href="javascript:void(0);">点击查看 <i class="fa fa-chevron-down"></i></a></div>
                                <ul class="helpCon">
                                    <li><span>1.参数格式：参数名称|参数类型|参数介绍。</span></li>
                                    <li><span>2.参数之间用分号(“;”或“;”)隔开。</span></li>
                                    <li>
                                        <span>3.实例：</span>
                                        <ul>
                                            <li><span>code|int|成功状态：（1：成功，0：失败）；</span></li>
                                            <li><span>msg|string|成功状态提示</span></li>
                                            <li><span>ret|array|接口调用成功返回内容</span></li>
                                            <li><span>errorCode|string|错误代码</span></li>
                                        </ul>
                                    </li>
                                    <li><span>4.注意事项：在参数设置中不能使用分号(“;”或“;”)。</span></li>
                                    <li><span>5.默认显示的是推荐格式，请根据自己的接口文档要求设置。</span></li>
                                </ul>
                                
                            </div>
                            <div class="editInputs">
                                <textarea name="paramsFormat" id="paramsFormat" class="form-control" rows="4" placeholder="每行一个参数，参数定义格式如下：参数名称|参数类型|参数介绍（如：ret|array|接口调用成功后的返回内容）。"><?php echo !empty($data['paramsFormat']) ? $data['paramsFormat'] : 'code|int|成功状态（1：成功，0：失败）；
msg|string|成功状态提示；
ret|array|接口调用成功返回内容；
errorCode|string|错误代码' ?>
                                </textarea></div>
                            <div class="errorMsg"></div>
                        </td>
                    </tr>
                    <tr>
                        <th>项目介绍（项目首页）：</th>
                        <td>
                            <textarea name="description" id="description" style="width:100%;height:300px;"><?php echo !empty($data['description']) ? $data['description'] : '<p>
    项目名称：XXX
</p>
<p>
    联系人信息：
</p>
<p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;手机号：XXXXXXXXXXX
</p>
<p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;QQ号：XXXXXXXX
</p>
<p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 微信号：XXXXXXXX
</p>
<p>
    项目背景：
</p>
<p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; XXX
</p>
<p>
    项目介绍：
</p>
<p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; XXXXXXXX
</p>
<p>
    接口调用流程：
</p>
<p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; XXXXXXXX
</p>
<p>
    <br/>
</p>' ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>文档补充：</th>
                        <td>
                            <div class="formHelp">
                                <div class="helpTitle">文档补充说明：<a class="showHelpCon" href="javascript:void(0);">点击查看 <i class="fa fa-chevron-down"></i></a></div>
                                <ul class="helpCon">
                                    <li><span>1.文档补充每一个接口的文档最下方显示。</span></li>
                                    <li><span>2.你可以填写任意内容，包括图片，文字等。</span></li>
                                </ul>
                            </div>
                            <textarea name="introduce" id="introduce" style="width:100%;height:300px;"><?php echo $data['introduce'] ?></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
        <div class="oprBtns">
            <a class="add goSubmitItem btn btn-sm btn-info" href="javascript:void(0);" url="<?php echo $postUrl?>">确认</a>
            <div class="clear"></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var ue = UE.getEditor('description');
    var ue1 = UE.getEditor('introduce');
</script>