<?php

class UnitController extends PublicController{
    private $unitdb;
    
    public function __construct($data) {
        parent::__construct($data);
        $this->unitdb = $this->X('Filedb', 'unit');
        $this->cookiedb = $this->X('Filedb', 'cookie');
    }
    
    public function index(){
        $data = $this->unitdb->getAll();
        $this->assign('data', $data);
        $this->display();
    }
    
    public function save(){
        $data = $this->p['step'];
        $d = array();
        foreach($data['name'] as $key => $value){
            if(empty($value)){die(json_encode(array('result' => false, 'msg' => '请填写名称为:"'.$value.'"项的名称')));}
            if(empty($data['url'][$key])){die(json_encode(array('result' => false, 'msg' => '请填写名称为:"'.$value.'"项的接口地址')));}
            if(empty($data['result'][$key])){die(json_encode(array('result' => false, 'msg' => '请填写名称为:"'.$value.'"项的预期结果格式')));}
            if(empty($data['times'][$key])){die(json_encode(array('result' => false, 'msg' => '请填写名称为:"'.$value.'"项的测试次数')));}
            $d[] = array(
//                'ord' => $value, 
                'name' => $value, 
                'url' => $data['url'][$key], 
                'header-from' => $data['header-from'][$key], 
                'header' => $data['header'][$key], 
                'param-from' => $data['param-from'][$key], 
                'param' => $data['param'][$key], 
                'method' => $data['method'][$key], 
                'result' => $data['result'][$key], 
                'cookie' => $data['cookie'][$key],
                'cookie-list' => $data['cookie-list'][$key],
                'times' => $data['times'][$key],
            );
        }
        
        foreach($d as $k => $v){
            $key = md5($v['url'].__CODE_KEY__);
            $this->unitdb->set($key, $v);
        }
        die(json_encode(array('result' => true, 'msg' => '保存成功')));
    }
    
    public function delUnit(){
        $key = $this->p['key'];
        if($this->unitdb->delete($key)){die(json_encode(array('result' => true)));}
        die(json_encode(array('result' => false)));
    }
    
    public function runAllUnit(){
        
    }


    public function runUnit(){
        $key = $this->p['key'];
        $data = $this->unitdb->get($key);
        $runlongs = array();
        if(empty($data['times'])){$data['times'] = 10;}
        print_r($data);
        for($i = 0; $i < (int)$data['times']; $i++){
            $startTime = microtime(true);
            $this->startUnit($data);
            $endTime = microtime(true);
            $runlongs[] = ($endTime - $startTime)*1000;
        }
        print_r($runlongs);
    }
    
    private function startUnit($data){
        $name = $data['name'];
        $url = $data['url'];
        $headerfrom = $data['header-from'];
        $header = $data['header'];
        $paramfrom = $data['param-from'];
        $param = $data['param'];
        $method = $data['method'];
        $result = $data['result'];
        $cookie = $data['cookie'];
        $cookieid = session_id();
        
    }
    
    private function getHeaders($headerfrom, $header){
        if(empty($header)){
            return [];
        }
        if($headerfrom == 'input'){
            $header = (array)json_decode($header);
            return $header;
        }
        if($header == 'result'){
            
        }
        if($header == 'all'){
            $header = (array)json_decode($header);
            
        }
    }
    
    
}
