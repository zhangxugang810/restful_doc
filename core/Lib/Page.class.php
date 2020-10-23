<?php
/**
* 分页显示方式类
*
* 本程序主要作用取得各种不同的分页显示方式
* 
* @category   Lib
* @package    Lib
* @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
class Page {
    public $pageData;
    public function __construct($pageData) {
        $this->pageData = $pageData;
    }
    /**
     * 
     * @return string
     */
    public function getPageAjax(){
        $pageMode = $this->pageData['pageMode'];
            if($this->pageData['page'] == $this->pageData['maxPage'] && $this->pageData['maxPage'] ==1){//只有一页
                $str .= '首页 上一页 下一页 尾页';
            }elseif($this->pageData['page'] == 1 && $this->pageData['maxPage'] > 1){//当前第一页
                $str .= '首页 上一页 <a class="'.$pageMode.'" page="'.($this->pageData['page']+1).'" href="javascript:void(0);">下一页</a> <a class="'.$pageMode.'" page="'.$this->pageData['maxPage'].'" href="javascript:void(0);">尾页</a>';
            }elseif($this->pageData['page'] > 1 && $this->pageData['page'] < $this->pageData['maxPage']){//当前为中间页
                $str .= '<a class="'.$pageMode.'" page="1" href="javascript:void(0);">首页</a> <a class="'.$pageMode.'" page="'.($this->pageData['page']-1).'" href="javascript:void(0);">上一页</a> <a class="'.$pageMode.'" page="'.($this->pageData['page']+1).'" href="javascript:void(0);">下一页</a> <a class="'.$pageMode.'" page="'.$this->pageData['maxPage'].'" href="javascript:void(0);">尾页</a>';
            }elseif($this->pageData['page'] > 1 && $this->pageData['page'] == $this->pageData['maxPage']){//当前末页
                $str .= '<a class="'.$pageMode.'" page="1"  href="javascript:void(0);">首页</a> <a class="'.$pageMode.'" page="'.($this->pageData['page']-1).'" href="javascript:void(0);">上一页</a> 下一页 尾页';
            }else{
                $str .= '首页 上一页 下一页 尾页';
            }
            return $str;
    }
    
    /**
     * 生成首页上一页下一页尾页形式的分页显示方式
     * @return string 
     */
    public function getPageNormal(){
            $pageMode = $this->pageData['pageMode'];
            $str = '共'.$this->pageData['maxPage'].'页 当前第'.$this->pageData['page'].'页 共'.$this->pageData['num'].'条 本页'.$this->pageData['count'].'条 ';
            if($this->pageData['page'] == $this->pageData['maxPage'] && $this->pageData['maxPage'] ==1){//只有一页
                $str .= '首页 上一页 下一页 尾页';
            }elseif($this->pageData['page'] == 1 && $this->pageData['maxPage'] > 1){//当前第一页
                $str .= '首页 上一页 <a class="'.$pageMode.'" page="'.($this->pageData['page']+1).'" href="javascript:void(0);">下一页</a> <a class="'.$pageMode.'" page="'.$this->pageData['maxPage'].'" href="javascript:void(0);">尾页</a>';
            }elseif($this->pageData['page'] > 1 && $this->pageData['page'] < $this->pageData['maxPage']){//当前为中间页
                $str .= '<a class="'.$pageMode.'" page="1" href="javascript:void(0);">首页</a> <a class="'.$pageMode.'" page="'.($this->pageData['page']-1).'" href="javascript:void(0);">上一页</a> <a class="'.$pageMode.'" page="'.($this->pageData['page']+1).'" href="javascript:void(0);">下一页</a> <a class="'.$pageMode.'" page="'.$this->pageData['maxPage'].'" href="javascript:void(0);">尾页</a>';
            }elseif($this->pageData['page'] > 1 && $this->pageData['page'] == $this->pageData['maxPage']){//当前末页
                $str .= '<a class="'.$pageMode.'" page="1"  href="javascript:void(0);">首页</a> <a class="'.$pageMode.'" page="'.($this->pageData['page']-1).'" href="javascript:void(0);">上一页</a> 下一页 尾页';
            }else{//其他情况
                $str .= '首页 上一页 下一页 尾页';
            }
            $str .= '<input type="submit" name="goPage" id="goPage" value="go" /><input type="text" name="page" id="page" size="3" value="'.$this->pageData['page'].'" />';
            return $str;
    }
    
    /**
     * 生成页码方式的分页显示方式
     * @return string 
     */
    public function getPageNumber(){
        $dispNum = 5;
        $pageMode = $this->pageData['pageMode'];
        $q = ($dispNum-1)/2;//偏移量;
        //计算循环初始变量
        if($this->pageData['maxPage'] <= $dispNum){
            $start = 1;
            $end = $this->pageData['maxPage'];
        }elseif($this->pageData['maxPage'] > $dispNum ){
            $start = $this->pageData['page'] - $q;
            if($start < 1 ){
                $end = $start+($dispNum) + abs($start);
                $start = 1;
            }else{
                $end = $start+($dispNum-1);
            }
            if($end > $this->pageData['maxPage']){
                $s = $end - $this->pageData['maxPage'];
                $end = $this->pageData['maxPage'];
                $start = $start-$s;
            }
        }
        
        $str = '<div class="pagelist">';
//        if($this->pageData['page'] <= 1){
//            $str .= '<a class="gray" href="javascript:void(0);">首页</a>
//            <a class="gray" href="javascript:void(0);">上一页</a>';
//        }else{
//            $str .= '
//                <a class="'.$pageMode.'" page="1" href="javascript:void(0);">首页</a>
//                <a class="'.$pageMode.'" page="'.($this->pageData['page']-1).'" href="javascript:void(0);">上一页</a>
//                ';
//        }
        if($start >= 2){
            $str .= '<a class="'.$pageMode.' numPage" page="1" href="javascript:void(0);">1</a><em>…</em>';
        }
        //计算循环结束变量
        for($i = $start; $i <= $end; $i++){
            if($i == $this->pageData['page']){
                $str .= '<span class="hover">'.$i.'</span>';
            }else{
                $str .= '<a class="'.$pageMode.' numPage" page="'.$i.'" href="javascript:void(0);">'.$i.'</a>';
            }
        }
        $str .= '<input type="hidden" name="page" id="page" size="3" value="'.$this->pageData['page'].'" />';
        if(($end+1) <= $this->pageData['maxPage']){
            $str .= '<em>…</em><a class="'.$pageMode.' numPage" page="'.$this->pageData['maxPage'].'" href="javascript:void(0);">'.$this->pageData['maxPage'].'</a>';
        }
//        if($this->pageData['page'] >= $this->pageData['maxPage']){
//            $str .= '<a class="gray" href="javascript:void(0);">下一页</a>
//                <a class="gray" href="javascript:void(0);">尾页</a>';
//        }else{
//            $str .= '<a class="'.$pageMode.'" page="'.($this->pageData['page']+1).'" href="javascript:void(0);">下一页</a>
//                <a class="'.$pageMode.'" page="'.$this->pageData['maxPage'].'" href="javascript:void(0);">尾页</a>';
//        }
        $str .= '<div class="clear"></div></div>';
        return ''.$str.'';
    }
}