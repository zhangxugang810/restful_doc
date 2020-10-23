<?php
/**
* Memcache内存缓存类
*
* 本程序主要作用把数据缓存到内存中
* 
* @category   Lib
* @package    Lib
* @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
class Mem{
    private $mem;
    private $timeout;//不是服务器链接的实效时长，而是具体缓存的实效时长，链接服务器的实效时长采用默认，以免出现问题，导致速度变慢。
    private $host = '192.168.2.16';
    private $port = '11211';
    /**
     * 构造方法
     * @param type $timeout 缓存实效时长
     */
    public function __construct($timeout = 300) {
        $this->mem = new Memcache;
        $this->mem->connect($this->host, $this->port);
        $this->timeout = $timeout;
    }
    
    /**
     * 添加一个可供使用的服务器地址到连接池中
     * @param type $host 服务器的地址
     * @param type $port 服务器端口
     * @param type $persistent 是否是一个持久连接
     * @param type $weight 这台服务器在所有服务器中所占的权重
     * @param type $retry_interval 连接重试的间隔时间，默认为15,设置为-1表示不进行重试
     * @param type $status 控制服务器的在线状态
     * @param type $failure_callback 允许设置一个回掉函数来处理错误信息。
     * @return type 如果成功则返回 TRUE，失败则返回 FALSE。
     */
    public function addServer($host, $port = '11211', $persistent = false, $weight = null, $retry_interval = 15, $status = true, $failure_callback = null){
        return $this->mem->addserver($host, $port, $persistent, $weight, $this->timeout, $retry_interval, $status, $failure_callback, $this->timeout);
    }
    
    /**
     * 如果$key不存在的时候，使用这个函数来存储$var的值。
     * @param type $key 将要存储的键值。
     * @param type $value 存储的值，字符型和整型会按原值保存，其他类型自动序列化以后保存。
     * @param type $flag 是否用MEMCACHE_COMPRESSED来压缩存储的值，true表示压缩，false表示不压缩。
     * @return type 存储值的过期时间，如果为0表示不会过期，你可以用unix时间戳或者描述来表示从现在开始的时间，但是你在使用秒数表示的时候，不要超过2592000秒 (表示30天)。
     */
    public function add($key, $value, $flag = false){
        return $this->mem->add($key, $value, $flag, $this->timeout);
    }
    
    /**
     * 如果成功则返回 TRUE，失败则返回 FALSE
     */
    public function close(){
        return $this->mem->close();
    }
    
    /**
     * 控制调试功能，前提是php在编译的时候使用了-enable-debug选项，否则这个函数不会有作用。
     * @param type $on_off true表示开启调试，false表示关闭调试
     * @return type 如果php在编译的时候使用了-enable-debug选项，返回true，否则返回false
     */
    public function debug($on_off = false){
        return $this->memcache_debug($on_off);
    }
    
    /**
     * 
     * @param type $key
     * @param type $value
     * @return type 
     */
    public function increment($key, $value = 0){
        return $this->mem->increment($key, $value);
    }
    
    /**
     * 
     * @param type $key
     * @param type $value
     * @return type
     */
    public function decrement($key, $value = 0){
        return $this->mem->decrement($key, $value);
    }
    
    /**
     * 
     * @return type
     */
    public function flush(){
        return $this->mem->flush();
    }
    
    /**
     * 
     * @param type $type
     * @param type $slabid
     * @param type $limit
     * @return type
     */
    public function getExtendedStats($type, $slabid = 'cachedump',$limit = 'cachedump'){
        return $this->mem->getextendedstats($type, $slabid, $limit);
    }
    
    /**
     * 
     * @param type $host
     * @param type $port
     * @return type
     */
    public function getServerStatus($host, $port = '11211'){
        return $this->mem->getserverstatus($host, $port);
    }
    
    /**
     * 
     * @param type $type
     * @param type $slabid
     * @param type $limit
     * @return type
     */
    public function getStats($type, $slabid = 'cachedump',$limit = 'cachedump'){
        return $this->mem->getStats($type, $slabid, $limit);
    }
    
    /**
     * 
     * @param type $key
     * @return type
     */
    public function delete($key){
        return $this->mem->delete($key);
    }
    
    /**
     * 
     * @return type
     */
    public function getversion(){
        return $this->mem->getversion();
    }
    
    /**
     * 
     * @param type $key
     * @param type $value
     * @return type
     */
    public function replace($key, $value){
        return $this->mem->replace($key, $value);
    }
    
    /**
     * 
     * @return type
     */
    public function append(){
        return $this->mem->append();
    }
    
    /**
     * 设置缓存时间
     * @param type $timeout 
     */
    public function setTimeOut($timeout){
        $this->timeout = $timeout;
    }
    
    /**
     * 写入缓存数据
     * @param type $key
     * @param type $value
     * @return type 
     */
    public function set($key,$value){
        return $this->mem->set($key,$value);
    }
    
    /**
     * 取得缓存中的数据
     * @param type $key
     * @return type 
     */
    public function get($key){
        return $this->mem->get($key);
    }
    
    /**
     * 析构方法 
     */
    public function __destruct() {
        $this->close();
    }
}