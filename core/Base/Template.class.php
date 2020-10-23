<?php
/**
* Template类
*
* 本程序主要作用是把Controller中取得的数据放入页面以及渲染页面。
*
* @category   Template
* @package    Base
* @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
* @author     张旭刚
* @version    v1.0 beta
*/
class Template{
    public $tplVars;
    /**
     *@method __contruct 构造函数
     */
    public function __construct() {$this->tplVars = array();}

    /**
     * @method 输出页面
     * @param string $template 要输出的模板地址
     */
    public function display($template,$dest=null){
        $content = $this->fetch($template);
        if(!empty($dest)){
            if($dest == 'html'){return $content;}
            $this->createHtml($dest, $content);
        }
        echo $content;
    }
    
    /**
     * @method 创建HTML文件
     * @param type $dest
     * @param type $content
     * @return type
     */
    private function createHtml($dest, $content){
        $basename = basename($dest);
        $path = str_replace($basename, '', $dest);
        $file = $this->X('File');
        $file->createDir($path);
        $content = $this->compressHtml($content);
        return @file_put_contents($dest, $content);
    }
    
    /**
     * @method 压缩HTML文件
     * @param type $string
     * @return type
     */
    private function compressHtml($string){
        $string=str_replace("\r\n",'',$string);
        $string=str_replace("\n",'',$string);
        $string=str_replace("\t",'',$string);
        $pattern=array("/> *([^ ]*) *</","/[\s]+/","/<!--[^!]*-->/","/\" /","/ \"/","'/\*[^*]*\*/'");
        $replace=array (">\\1<"," ","","\"","\"","");
        return preg_replace($pattern, $replace, $string);
    } 

    /**
     * @method 运行模板并加入并解析模板变量
     * @param string $template 要运行的模板
     * @return 运行之后的全部html代码
     */
    private function fetch($template){
        foreach((array)$this->tplVars as $key => $v){$$key = $v;}
        ob_start();
        include($template);
        $content = ob_get_clean();
        return $content;
    }

    /**
     * @method 为模板变量赋值
     * @param string $name 模板变量名称
     * @param anytype $data 模板变量值
     */
    public function assign($name, $data = null){$this->tplVars[$name] = $data;}
    
    /**
     * @method 将模板变量变为Json输出
     */
    public function json(){
        if(empty($this->tplVars)){
            die();
        }else{
            unset($this->tplVars['user']);
            unset($this->tplVars['_fields']);
            unset($this->tplVars['bg']);
            if(__ENCODE__){
                $json = base64_encode(doEncrypt(json_encode($this->tplVars), __ENCODE_KEY__));
            }else{
                $json = json_encode($this->tplVars);
            }
            die($json);
        }
    }



    /**
     * @method 将模板变量变为Json输出
     */
    public function appjson($enabled = false){
        if(empty($this->tplVars)){
            die();
        }else{
            unset($this->tplVars['user']);
            unset($this->tplVars['_fields']);
            unset($this->tplVars['bg']);
            if($this->tplVars['c']){
                $this->tplVars['c'] = 1;
            }else{
                $this->tplVars['c'] = 0;
            }
            if(__ENCODEAPI__ && $enabled){
                if(isset($this->tplVars['c'])){
                    $this->tplVars['o'] = doEncrypt(json_encode($this->tplVars['o']), __ENCODE_KEY__, false);
                    $json = json_encode($this->tplVars);
                }else{
                    $json = doEncrypt(json_encode($this->tplVars), __ENCODE_KEY__, true);
                }
            }else{
                $json = json_encode($this->tplVars);
            }
            die($json);
        }
    }

    protected function X($name = null,$params = null){
        $xpath = __LIB__.'/'.$name.'.class.php';
        if(!file_exists($xpath)){
            return 'lib_file_is_not_exist:'.$xpath;
        }else{
            include_once($xpath);
            if(!class_exists($name)){
                return 'lib_class_is_not_define:'.$name;
            }else{
                if(!empty($params)){return new $name($params);}else{return new $name();}
            }
        }
    }
    public function __destruct() {unset($this->tplVars);}
    
}
