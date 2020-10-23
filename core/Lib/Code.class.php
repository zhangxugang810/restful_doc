<?php
/**
* 验证码生成类
*
* 本程序主要作用生成验证码图片
* 
* 第三方类 
*/
class Code{
    private $width; //验证码图片宽度
    private $height; //验证码图片高度
    private $codeNum; //验证码字符个数
    private $checkCode; //验证码字符
    private $image; //验证码画布
    private $imagetype;
    private $numbers = '23456789';
    private $sChars = 'abcdefghjkmnpqrstuvwxyz';
    private $lChars = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    /**
     * 构造函数
     * @param type $width
     * @param type $height
     * @param type $codeNum
     */
    public function __construct($width=60,$height=25,$codeNum=4){
        $this->width = $width;
        $this->height = $height;
        $this->codeNum = $codeNum;
        $this->checkCode = $this->createCheckCode();
        $this->setCodeSession();
        $this->setImageType();
    }
    /**
     * 设置验证码Session
     */
    public function setCodeSession(){
        $_SESSION['verifyCode'] = $this->checkCode;
    }
    
    /**
     * 设置图像文件格式
     * @param type $type
     */
    public function setImageType($type = 'png'){
        $this->imagetype = $type;
    }
    
    /**
     * 显示图像
     * @return type
     */
    public function showImage(){
        $this->getcreateImage();
        $this->outputText();
        $this->setDisturbColor();
        return $this->outputImage();
    }
    
    /**
     * 获取验证码
     * @return type
     */
    public function getCheckCode(){
        return $this->chekCode;
    }
    
    /**
     * 创建图像
     */
    private function getCreateImage(){
        $this->image = imagecreatetruecolor($this->width,$this->height);
        $back = imagecolorallocate($this->image,rand(240,255),rand(240,255),rand(240,255));
//        $border = imagecolorallocate($this->image,rand(0,255),rand(0,255),rand(0,255));
        imagefilledrectangle($this->image,0,0,$this->width-1,$this->height-1,$back);
    }
    
    /**
     * 创建验证码
     * @return type
     */
    private function createCheckCode(){
        $asc_number = '';
        for($i=0;$i<$this->codeNum;$i++){
            $number = rand(0,2);
            switch($number){
                case 0:
                    $rand_number = rand(0,7);
                    $asc = $this->numbers[$rand_number];
                    break;//数字
                case 1:
                    $rand_number = rand(0,22);
                    $asc = $this->lChars[$rand_number];
                    break;//大写字母
                case 2:
                    $rand_number = rand(0,22);
                    $asc = $this->sChars[$rand_number];
                    break;//小写字母
            }
//            $asc = sprintf("%c",$rand_number);
            $asc_number .= $asc;
        }
        return $asc_number;
    }
    /**
     * 干扰码设置
     */
    private function setDisturbColor(){
        for($i=0;$i<=100;$i++){
            $color = imagecolorallocate($this->image, 0, rand(240,255), rand(0,10));
//            $color = imagecolorallocate($this->image,255,255,255);
            imagesetpixel($this->image,rand(1,$this->width-2),rand(1,$this->height-2),$color);
        }
        //color = imagecolorallocate(this->image,0,0,0);
        //imagesetpixel(this->image,rand(1,this->width-2),rand(1,this->height-2),color);
    }
    
    /**
     * 随机颜色、随机摆放、随机字符串向图像输出
     */
    private function outputText(){
        $bg_color = imagecolorallocate($this->image,rand(0,10),rand(0,255),rand(240,255));
        for($i=0;$i<=$this->codeNum;$i++){
            $x = floor($this->width/$this->codeNum)*$i+3;
            $y = rand(0,$this->height-15);
            $chr = substr($this->checkCode,$i,$i+1);
            imagechar($this->image,5,$x,$y,$chr,$bg_color);
        }
    }
    
    /**
     * 输出图像
     * @return type
     */
    private function outputImage(){
        @ob_end_clean();
        if($this->imagetype == 'gif'){
            header("Content_type:image/gif");
            $data = imagegif($this->image);
        }elseif($this->imagetype == 'jpeg'){
            header("Content-type:image/jpeg");
            $data = imagejpeg($this->image,"",0.5);
        }elseif($this->imagetype == 'png'){
            header("Content-type:image/png");
            $data = imagepng($this->image);
        }elseif($this->imagetype == 'wbmp'){
            header("Content-type:image/vnd.wap.wbmp");
            $data = imagewbmp($this->image);
        }else{
            die("PHP不支持图像创建");
        }
        return $data;
    }

    public function __destruct(){
        imagedestroy($this->image);
    }
}