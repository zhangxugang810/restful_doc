<?php foreach((array)$apilist as $key => $value){
    if(!empty($value['funcname'])){
?>
<a href="javascript:void(0)" title="<?php echo $value['see']?>" class="funcname" funcname="<?php echo $value['funcname']?>" path="<?php echo $path?>" app="<?php echo $app?>"><i class="fa fa-file-text-o"></i><?php echo $value['see']?></a>
<?php }}?>