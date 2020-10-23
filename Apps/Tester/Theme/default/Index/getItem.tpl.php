<?php if(!empty($controllers)){?>
    <li>
        <div class="itemindex" itemId="<?php echo $item['itemtag']?>">
            <i class="fa fa-home"></i>
            <span><?php echo '首页';?></span>
            <!--<span class="arrow"></span>-->
        </div>
    </li>
    <?php foreach((array)$controllers as $key => $value){?>
    <li>
        <div class="item" status="<?php echo $value['status']?>" controller="<?php echo $value['controller']?>" controllerPath="<?php echo base64_encode($value['controllerPath'])?>" app="<?php echo $value['app']?>" title="<?php echo empty($value['description']) ? '接口文件' : $value['description']?>">
            <i class="fa fa-folder-o"></i>
            <span><?php echo empty($value['name']) ? '接口文件' : $value['name']?></span>
            <span class="arrow"></span>
        </div>
        <div class="catalog2">
            <?php if($value['status'] == 'noajax'){?>
                <?php foreach ($value['list'] as $k => $v){?>
                <a href="javascript:void(0)" status="noajax" title="<?php echo $v['name']?>" class="funcname" pic="<?php echo $v['url']?>" ><i class="fa fa-file"></i><?php echo $v['name']?></a>
                <?php }?>
            <?php }?>
        </div>
    </li>
    <?php }?>
<?php }?>