<?php
class Cookie{
    private $expire;
    private $path;
    private $domain;
    private $secure;
    private $httponly;
    private $name;
    /**
     * @see 构造函数
     * @param string $name
     */
    public function __construct($name) {
        $this->name = $name;
        $this->setExpire();
        $this->setPath();
        $this->setDomain();
        $this->setSecure();
        $this->setSecure();
    }
    
    /**
     * @see 设置有效cookie时间
     * @param int $expire cookie的有效时间
     */
    public function setExpire($expire = 0){
        $this->expire = $expire;
    }
    
    /**
     * @see 设置有效cookie路径
     * @param string $path cookie的路径
     */
    public function setPath($path = '/'){
        $this->path = $path;
    }
    
    /**
     * @see 设置Cookie有效域名
     * @param string $domain cookie的有效域名
     */
    public function setDomain($domain = null){
        $this->domain = $domain;
    }
    
    /**
     * @see 设置Cookie
     * @param boolean $secure 是否通过安全的 HTTPS 连接来传输 cookie。
     */
    public function setSecure($secure = false){
        $this->secure = $secure;
    }
    
    /**
     * @see 设置Cookie是否仅Http有效
     * @param boolean $httponly 是否尽到HTTP有效
     */
    public function setHttpOnly($httponly = true){
        $this->httponly = $httponly;
    }
    
    /**
     * @see 设置Cookie值
     * @param string $value Cookie的内容
     * @return boolean 设置Cookie是否成功
     */
    public function setCookie($value){
        return setcookie($this->name, $value, $this->expire, $this->path, $this->domain, $this->secure, $this->httponly);
    }
    
    /**
     * @see 获取Cookie
     * @param string $Mode
     * @return string 返回cookie的内容
     */
    public function getCookie($Mode = 'str'){
        if(isset($_COOKIE[$this->name])){
            $ck = $_COOKIE[$this->name];
        }else{
            $ck = '';
        }
        if($Mode == 'json'){
            return (array)json_decode($ck);
        }else{
            return $ck;
        }
    }
    
    /**
     * @see 删除Cookie
     * @return string 删除cookie是否成功
     */
    public function deleteCookie(){
        return setcookie($this->name, '', time()-10000, $this->path, $this->domain, $this->secure, $this->httponly);
    }
}