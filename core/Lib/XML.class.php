<?php
/**
* 简单XML操作类
*
* 本程序主要作用取得简单的XML文档解析并保存XML文档
* 
* @category   Lib
* @package    Lib
* @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
class XML{
    private $xml;
    
    /**
     * 
     * @param type $file
     */
    public function __construct($file = null) {
        $this->xml = simplexml_load_file($file);
    }
    
    /**
     * 取得XML文件的内容并解析后返回
     * @return type 
     */
    public function getArrayFromXML(){
        $data = (array)$this->xml;
        return $data;
    }
    
    /**
     * 将XML代码保存到文件中
     * @param type $file
     * @param type $code
     * @return type 
     */
    public function saveSetting($file,$code){
        return file_put_contents($file, $code);
    }
}