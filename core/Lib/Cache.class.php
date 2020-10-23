<?php
/**
* 缓存类
*
* 本程序主要作用定义硬盘缓存，将数据缓存到硬盘
* 
* @category   Lib
* @package    Lib
* @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
class Cache{
    public $timeout;
    public $cachePath;
    /**
     * 构造方法
     * @param type $timeout 缓存实效是长
     */
    public function __construct($timeout = 3000) {
        $this->timeout = $timeout;
        $this->setCachePath();
    }
    
    /**
     * 设置缓存时间
     * @param type $timeout  缓存实效是长
     */
    public function setTimeOut($timeout){
        $this->timeout = $timeout;
    }
    
    /**
     * 写入缓存数据
     * @param type $key 缓存地址
     * @param type $value 缓存数据
     * @return type 真/假
     */
    public function set($key, $value){
        $path = $this->cachePath.'/'.$key.'.json';
        return file_put_contents($path, serialize($value));
    }
    
    /**
     * 取得缓存中的数据
     * @param type $key 缓存地址
     * @return type 缓存数据
     */
    public function get($key){
        $path = $this->cachePath.'/'.$key.'.json';
        if(file_exists($path)){
            if($this->checkTimeOut($path)){
                $json = file_get_contents($path);
                $data = unserialize($json);
                return $data;
            }else{
                return ;
            }
        }else{
            return ;
        }
    }
    /**
     * 删除单个缓存文件
     * @param type $key
     * @return type
     */
    public function delete($key){
        $path = $this->cachePath.'/'.$key.'.json';
        if(file_exists($path)){
            return @unlink($path);
        }
    }
    
    public function clear(){
        $hander = opendir($this->cachePath);
        while($file = readdir($hander)){
            if($file != '..' && $file != '.'){
                $fnames = explode('.', $file);
                $this->delete($fnames[0]);
            }
        }
    }


    /**
     * 判断缓存是否实效
     * @param type $path 缓存路径
     * @return boolean 是否实效
     */
    private function checkTimeOut($path){
        if(file_exists($path)){
            if($this->timeout == 0){
                return true;
            }
            clearstatcache(true,$path);
            $time = filemtime($path);
            $curTime = time();
            $diff = $curTime-$time;
            if($diff > $this->timeout){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }
    
    /**
     * 寻找并生成缓存路径
     */
    public function setCachePath(){
        if(!file_exists(__CACHE_PATH__)){
            mkdir(__CACHE_PATH__);
        }
        
        $path = __CACHE_PATH__.'/caches';
        if(!file_exists($path)){
            @mkdir($path);
        }
        $this->cachePath = $path;
    }
}