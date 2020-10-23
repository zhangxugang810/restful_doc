<div class="unit-l" style="display: block;">
    <form name="stepForm" id="stepForm" method="POST" action="<?php echo U('Tester/Unit/save')?>" >
        <div class="stepsbody">
            <h1>
                <div class="title">单元测试设置</div>
                <div class="buttons">
                    <!--<button class="addUnitStep" type="button"><i class="icon icon-plus"></i> 增加步骤</button>-->
                    <button class="saveStep" type="button"><i class="icon icon-save"></i> 保存单元测试</button>
                </div>
                <div class="clear"></div>
            </h1>
            <div class="steps">
                <table class="step-table" border="0" width="100%" cellspacing="0" cellpadding="0">
                    <thead>
                        <?php
//                        <tr>
//                            <th colspan="2" width="30%">序号</th>
//                            <td><input type="text" id="step_ord_0" readonly="readonly" class="changeOrd" name="step[ord][]" placeholder="如：渠道鉴权" value="0" /></td>
//                        </tr>
                        ?>
                        <tr>
                            <th colspan="2" width="30%">名称</th>
                            <td><input type="text" id="step_name_0" name="step[name][]" placeholder="如：渠道鉴权" /></td>
                        </tr>
                        <tr>
                            <th colspan="2">地址</th>
                            <td><input type="text" id="step_url_0" name="step[url][]" placeholder="如：http://local-trade.ikang.com/index.php?r=channel/index" /></td>
                        </tr>
                        <tr>
                            <th rowspan="2" width="10%">头信息</th>
                            <th>头参数来源</th>
                            <td>
                                <label><input class="header-select" type="radio" id="step_header-from_0" name="step[header-from][]" value="result" /> 上一步结果</label>
                                <label><input class="header-select" type="radio" id="step_header-from_0" name="step[header-from][]" value="input" checked="checked" /> 输入</label>
                                <label><input class="header-select" type="radio" id="step_header-from_0" name="step[header-from][]" value="all"/> 混合</label>
                            </td>
                        </tr>
                        <tr>
                            <th>头参数详情</th>
                            <td><input type="text" id="step_header_0" name="step[header][]" placeholder="如：{'id':'1'}" /></td>
                        </tr>
                        <tr>
                            <th rowspan="2">参数:</th>
                            <th>参数来源</th>
                            <td>
                                <label><input class="param-select" type="radio" id="step_param-from_0" name="step[param-from][]" value="result" /> 上一步结果</label>
                                <label><input class="param-select" type="radio" id="step_param-from_0" name="step[param-from][]" value="input" checked="checked" /> 输入</label>
                                <label><input class="param-select" type="radio" id="step_param-from_0" name="step[param-from][]" value="all" /> 混合</label>
                            </td>
                        </tr>
                        <tr>
                            <th>参数详情</th>
                            <td><input type="text" id="step_param_0" name="step[param][]" placeholder="如：{'id':'1'}" /></td>
                        </tr>
                        <tr>
                            <th colspan="2">提交方法</th>
                            <td>
                                <label><input type="radio" id="step_method_0" name="step[method][]" value="get" /> GET</label>
                                <label><input value="post" type="radio" id="step_method_0" name="step[method][]" checked="checked" /> POST</label>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2">预期结果格式</th>
                            <td><input type="text" id="step_result_0" name="step[result][]" placeholder="如：Correct:code=1|Error:code=0" /></td>
                        </tr>
<!--                        <tr>
                            <th colspan="2">结果存入Cookie</th>
                            <td>
                                <label><input type="radio" id="step_cookie_0" name="step[cookie][]" value="get" /> 存入</label>
                                <label><input value="post" type="radio" id="step_cookie_0" name="step[cookie][]" checked="checked" /> 不存</label>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2">存入Cookie参数</th>
                            <td><input type="text" id="step_cookie-list_0" name="step[cookie-list][]" placeholder="如：token|access-token" /></td>
                        </tr>-->
                        <tr>
                            <th colspan="2">测试次数</th>
                            <td><input type="text" id="step_cookie-list_0" name="step[times][]" placeholder="如：10" /></td>
                        </tr>
                        <?php
//                        <tr class="delete hide">
//                            <th colspan="3"><button type="button" class="delStep"><i class="icon icon-remove"></i> 删除</button></th>
//                        </tr>
                        ?>
                    </thead>
                </table>
                
            </div>
        </div>
    </form>
</div>

<div class="unit-r" style="display: block;">
    <div class="stepsbody">
        <h1>
            <div class="title">单元测试列表</div>
            <div class="buttons">
                <button type="button"><i class="icon icon-play"></i> 全部开始</button>
                <a id="look-report" href="javascript:void(0);" url="<?php echo U('Tester/Unit/report')?>"><i class="icon icon-eye-open"></i> 查看报告</a>
            </div>
            <div class="clear"></div>
        </h1>
        <div class="rb">
            <!--<h3 class="h3">1.测试预期结果分析，性能分析，安全性分析，未知异常分析</h3>-->
            <table class="step-table list-table" border="0" width="100%" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th>单元名称</th>
                        <th>接口地址</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach((array)$data as $k => $v){?>
                    <tr class="listtr">
                        <td class="txtcenter"><?php echo $v['name']?></td>
                        <td><?php echo $v['url']?></td>
                        <td class="txtcenter btn-td">
                            <button key="<?php echo md5($v['url'].__CODE_KEY__)?>" class="runUnitTest" type="button" title="开始测试"><i class="icon icon-play"></i></button>
                            <button key="<?php echo md5($v['url'].__CODE_KEY__)?>" class="lookUnitReport" type="button" title="查看报告"><i class="icon icon-eye-open"></i></button>
                            <button key="<?php echo md5($v['url'].__CODE_KEY__)?>" class="delUnit" type="button" title="删除"><i class="icon icon-remove"></i></button>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
    </div>
</div>