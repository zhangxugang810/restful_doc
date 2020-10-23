<h1>返回结果 </h1>
<div class="rb">
<!--    <span class="returndatatype codeType">返回数据</span>
    <div class="returndata">
        <p><? //= $returncode;?></p>
    </div>-->
    <!--<span class="jsondecode codeType">返回数据 </span>-->
    <!--JSON解析-->
    <div class="jsonTable">
        <ul class="line">
            <?php for($i = 1; $i<=$pcounts; $i++){?>
                <li class="numline line<?php echo $i%2 == 0 ? 2 : 1?>"><?php echo $i?></li>
            <?php }?>
        </ul>
        <ul class="code">
            <?php foreach((array)$codedata as $key => $value){?>
                <li class="codeline line<?php echo ($key%2 == 0) ? 1 : 2?>">
                    <?php if($key == 0){?>
                        <code class="js plain">{</code>
                    <?php }elseif($key == ($pcounts-1)){?>
                        <code class="js plain">}</code>
                    <?php }else{ ?>
                        <?php $val = explode(':', $value)?>
                        <code class="js spaces"></code>
                         <code class="js string"><?php echo unescape(stripslashes($val[0]))?></code>
                         <code class="js plain">: </code>
                         <?php
                            $valtext = unescape(($val[1]));
                            $length = strlen($valtext);
                         ?>
                         <code class="js codevalue <?php echo (is_string($valtext)) ? 'string' : 'plain'?>" title="<?php echo $valtext;?>"><?php echo ($valtext); ?></code>
                    <?php }?>
                </li>
            <?php }?>
        </ul>
        <span class="clear"></span>
    </div>
</div>
<script>
//    hanleCodevalue();
//    function hanleCodevalue(){
//        $('.codevalue').each(function(){
//            $codevalue = encodeURIComponent($(this).text());
//            alert($codevalue);
//        });
//    }
</script>


<!--<h1>返回结果 </h1>
<div class="rb">
    <span class="codeType">JSON示例</span>
    <div class="jsonTable">
        <ul class="line">
            <li class="numline line1">1</li>
            <li class="numline line2">2</li>
            <li class="numline line1">3</li>
            <li class="numline line2">4</li>
            <li class="numline line1">5</li>
            <li class="numline line2">6</li>
            <li class="numline line1">7</li>
            <li class="numline line2">8</li>
            <li class="numline line1">9</li>
            <li class="numline line2">10</li>
            <li class="numline line2">11</li>
        </ul>
        <ul class="code">
            <li class="codeline line1">
                <code class="js plain">{</code>
            </li>
            <li class="codeline line2">
                <code class="js spaces">&nbsp;&nbsp;&nbsp;&nbsp;</code>
                <code class="js string">"created_at"</code>
                <code class="js plain">: </code>
                <code class="js string">"Tue May 31 17:46:55 +0800 2011"</code>
                <code class="js plain">,</code>
            </li>
            <li class="codeline line1">
                <code class="js spaces">&nbsp;&nbsp;&nbsp;&nbsp;</code>
                <code class="js string">"id"</code>
                <code class="js plain">: 11488058246,</code>
            </li>
            <li class="codeline line2">
                <code class="js spaces">&nbsp;&nbsp;&nbsp;&nbsp;</code>
                <code class="js string">"text"</code>
                <code class="js plain">: </code>
                <code class="js string">"求关注。"</code>
                <code class="js plain">，</code>
            </li>
            <li class="codeline line1">
                <code class="js spaces">&nbsp;&nbsp;&nbsp;&nbsp;</code>
                <code class="js string">"source"</code>
                <code class="js plain">: </code>
                <code class="js string">"&lt;a href="</code>
                <code class="js plain">http:</code>
                <code class="js comments">//weibo.com" rel="nofollow"&gt;新浪微博&lt;/a&gt;",</code>
            </li>
            <li class="codeline line2">
                <code class="js spaces">&nbsp;&nbsp;&nbsp;&nbsp;</code>
                <code class="js string">"favorited"</code>
                <code class="js plain">: </code>
                <code class="js keyword">false</code>
                <code class="js plain">,</code>
            </li>
            <li class="codeline line1">
                <code class="js spaces">&nbsp;&nbsp;&nbsp;&nbsp;</code>
                <code class="js string">"truncated"</code>
                <code class="js plain">: </code>
                <code class="js keyword">false</code>
                <code class="js plain">,</code>
            </li>
            <li class="codeline line2">
                <code class="js spaces">&nbsp;&nbsp;&nbsp;&nbsp;</code>
                <code class="js string">"in_reply_to_status_id"</code>
                <code class="js plain">: </code>
                <code class="js string">""</code>
                <code class="js plain">,</code>
            </li>
            <li class="codeline line1">
                <code class="js spaces">&nbsp;&nbsp;&nbsp;&nbsp;</code>
                <code class="js string">"in_reply_to_user_id"</code>
                <code class="js plain">: </code>
                <code class="js string">""</code>
                <code class="js plain">,</code>
            </li>
            <li class="codeline line2">
                <code class="js spaces">&nbsp;&nbsp;&nbsp;&nbsp;</code>
                <code class="js string">"in_reply_to_screen_name"</code>
                <code class="js plain">: </code>
                <code class="js string">""</code>
                <code class="js plain">,</code>
            </li>
            <li class="codeline line1">
                <code class="js plain">}</code>
            </li>
        </ul>
        <span class="clear"></span>
    </div>
</div>-->