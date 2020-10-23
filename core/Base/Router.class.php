<?php
/**
* Router（路由）类
*
* 本程序主要用根据用户URL地址寻找要执行的Controller和Action
* 
* @category   Router
* @package    Base
* @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
class Router{
    public $configFile;
    public $rules;
    public $url;
    public $nData;
    /**
     * @method __contruct 构造函数
     * @param string $url 浏览器中的链接地址
     */
    public function __construct($url) {
        $this->configFile = './core/Common/router.inc.php';
        $this->getRules();
        $this->url = $this->processUrl($url);
    }
    
    /**
     * @method 处理从浏览器得到的链接地址
     * @param string $url 链接地址
     * @return string 处理后的链接地址
     */
    private function processUrl($url){
        $url = substr($url, 1, strlen($url));
        return $url;
    }
    
    /**
     * @method 从配置文件中取得路由配置 
     */
    private function getRules(){
        $this->rules = include($this->configFile);
    }

    /**
     * @method 取得router
     * @return array 返回取得的路由 
     */
    public function getRouter(){
        $data = explode('/',$this->url);
        //取出全部路由配置中全部与url参数相同的
        $rule = $this->checkSameRouter();
        if(empty($rule)){
            $rules = $this->checkFirstSameRouter($data[0]);
            if(count($rules) == 1){//只找到一条
                $rule = $rules[0];
                $rule = $this->addParams($rule, $data ,1);
            }elseif(empty($rules)){//未找到，则可能是全路径，进行全路径判断
                $rule = $this->checkUrl($data);
            }else{//如果出现多条数据,那么去做第二个参数相交的判断
                $rules1 = $rules;
                $rule = $this->rules[$data[0].'/'.$data[1]];
                if(!empty($rule)){
                    return $rule;
                }
                $rules = $this->checkSecondSameRouter($rules, $data[1]);
                if(count($rules) == 1){//只找到一条
                    $rule = $rules[0];
                     //加入提交过来的参数
                    $rule = $this->addParams($rule, $data ,2);
                }else{//未找到，剩下两种可能：1.可能第一个有完全匹配的，2.可能是一个全路径
                    $rule = $this->checkOnlyFirstRouter($rules1, $data);
                    if(empty($rule)){
                        $rule = $this->checkUrl($data);
                    }
                }
            }
        }
        return $rule;
    }
    
    /**
     * @method 计算路径访问页面以往的提交的参数
     * @param array $rule 路由规则
     * @param array $data 浏览器提交过来的链接地址数据
     * @param int $start 从哪里开始算作参数
     * @return string 返回完整路由规则
     */
    private function addParams($rule, $data, $start){
        if($start % 2 == 0){
            $ck = 0;
        }else{
            $ck = 1;
        }
        $num = count($data);
        if($num > $start){
            for($i = $start; $i < $num; $i++){
                if($i % 2 == $ck){
                    $rule[$data[$i]] = $data[$i+1];
                }
            }
        }
        return $rule;
    }
    
    /**
     * @method 判断第一个标签全部匹配的
     * @param array $rules 路由规则
     * @param array $data 链接地址
     * @return array 路由规则
     */
    private function checkOnlyFirstRouter($rules,$data){
        foreach((array)$rules as $k => $v){
            if($k == $data[0]){
                $rule = $v;
            }
        }
        $rule = $this->addParams($rule, $data, 1);
        return $rule;
    }
    
    /**
     * @method 取出全部相同的路由配置
     * @return array 路由规则 
     */
    private function checkSameRouter(){
        foreach((array)$this->rules as $k => $v){
            if($this->url == $k){
                $rule = $v;
            }
        }
        if(isset($rule)){
            return $rule;
        }else{
            return ;
        }
    }
    
    /**
     * @method 取第一个参数相交的所有配置
     * @param string $param 检查路由的第一个参数是否相同
     * @return array 路由 
     */
    private function checkFirstSameRouter($param){
        foreach((array)$this->rules as $k => $v){
            $ks = explode('/', $k);
            if($ks[0] == $param){
                $rules[] = $v;
            }
        }
        if(isset($rules)){
            return $rules;
        }else{
            return ;
        }
    }
    
    /**
     * @method 取第二个参数相交的部分
     * @param array $rules 路由规则
     * @param string $param 
     * @return array 路由 
     */
    private function checkSecondSameRouter($rules, $param){
        foreach((array)$rules as $k => $v){
            $ks = explode('/', $k);
            if($ks[2] == $param){
                $rs[] = $v;
            }
        }
        return $rs;
    }
    
    /**
     * @method 判断是否非数字的字符串
     * @param string $str 要被判断的字符串
     * @return boolean 判断结果
     */
    public function isString($str){
        if(is_numeric($str)){
            return false;
        }else{
            //判断字符串是否为是由英文和数字做成组合
             preg_match_all("/^.[A-Za-z0-9]+$/i", $str, $data);
             if(empty($data[0])){
                 return false;
             }
             return true;
             
        }
    }

    /**
     * @method 检查前面的三个参数是否全部为字符串:检测是否是全路径
     * @param array $data 要判断的路由
     * @return array 路由
     */
    private function checkUrl($data){
        $num = count($data);
        if($num >= 3){
            $isStr = true;
            $i = 0;
            for($i = 0; $i < 3; $i++){
                if(!$this->isString($data[$i])){
                    $isStr = false;
                    break;
                }
            }
            if($isStr){
                if($num == 3){
                    $rule = array('app' => $data[0], 'm' => $data[1], 'a' => $data[2]);
                }else{
                    $rule = array('app' => $data[0], 'm' => $data[1], 'a' => $data[2]);
                    $rule = $this->addParams($rule, $data, 3);
                    return $rule;
                }
            }else{ //取距离最近的路由
                $rule = array('app' => __DEFAULT_APP__, 'm' => 'Index', 'a' => 'index');
            }
        }else{
            if($num == 1){
                if(!empty($data[0])){
                    $rule['app'] = $data[0];
                }else{
                    $rule['app'] = __DEFAULT_APP__;
                }
                $rule['m'] = 'Index';
            }elseif($num == 2){
                if(!empty($data[0])){
                    $rule['app'] = $data[0];
                }else{
                    $rule['app'] = __DEFAULT_APP__;
                }
                if(!empty($data[1])){
                    $rule['m'] = $data[1];
                }else{
                    $rule['m'] = 'Index';
                }
            }
            $rule['a'] = 'index';
        }
        return $rule;
    }
    
    public function __destruct() {
        unset($this->configFile);
        unset($this->rules);
        unset($this->url);
        unset($this->nData);
    }
}