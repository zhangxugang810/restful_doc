<?php
class {MoudleTag1}sController extends PublicController{
    /*该模块的所有自定义属性*/
    private $properties;
    public function __construct($data) {
        parent::__construct($data);
        $this->properties = {properties};
    }
    
    public function {MoudleTag}List(){
        if(isset($this->p['page'])){
            $page = $this->p['page'];
        }else{
            $page = 1;
        }
        $field = $this->p['fd'];
        $keyword = $this->p['keyword'];
        if(!empty($field) && !empty($keyword)){
            $maps = ' AND `'.$field.'` like \'%'.$keyword.'%\'';
        }
        $this->D('{MoudleTag1}s')->setPageSize(10);
        $data = $this->D('{MoudleTag1}s')->getPageList('*', $page, $maps, 'goPage', 'orders ASC');
        $this->assign('data', $data['rows']);
        $this->assign('pageStr', $data['pageStr']);
        $this->assign('keyword', $keyword);
        $this->assign('fd', $field);
        $this->assign('properties', $this->properties);
        $this->display();
    }
{addFunction}
{editFunction}
{doAddFunction}
{doEditFunction}
{delFunction}
{ordersFunction}
{auditFunction}
{recommendsFunction}
}