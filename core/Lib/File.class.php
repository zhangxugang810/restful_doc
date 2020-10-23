<?php
/**
 * 文件操作类（含上传操作）
 * 本程序主要作用管理文件和上传文件以及删除文件等
 * @category   Lib
 * @package    Lib
 * @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
 * @author     张旭刚
 * @version    v1.0 beta
 */
class File {

    private $thumbWidth;
    private $thumbHeight;
    private $limitSize; //kb
    private $limitType;
    private $limitWidth; //px
    private $limitHeight; //px
    private $isRename;
    private $uploadPath;
    private $compressPath;

    /**
     * 构造方法 
     */
    public function __construct() {
        $this->setLimitHeight();
        $this->setLimitWidth();
        $this->setLimitSize();
        $this->setLimitType();
        $this->setThumbHeight();
        $this->setThumbWidth();
        $this->setIsRename();
        $this->setUploadPath();
        $this->setCompressPath();
    }
    

    /**
     * 设置上传的文件是否重新命名 true：是，false：不是，默认为重新命名
     * @param type $status 
     */
    public function setIsRename($status = true) {
        $this->isRename = $status;
    }

    /**
     * 设置缩略图的宽度
     * @param type $width 
     */
    public function setThumbWidth($width = 300) {
        $this->thumbWidth = $width;
    }

    /**
     * 设置缩略图的高度
     * @param type $height 
     */
    public function setThumbHeight($height = 200) {
        $this->thumbHeight = $height;
    }

    /**
     * 设置最大允许上传的大小 单位：KB
     * @param type $size 
     */
    public function setLimitSize($size = 500) {
        $this->limitSize = $size;
    }

    /**
     * 设置允许上传的文件类型
     * @param type $types 
     */
    public function setLimitType($types = array('gif', 'jpg', 'png', 'jpeg','swf')) {
        $this->limitType = $types;
    }

    /**
     * 设置允许上传的图片宽度
     * @param type $width 
     */
    public function setLimitWidth($width = 0) {
        $this->limitWidth = $width;
    }

    /**
     * 设置允许上传的图片高度
     * @param type $height 
     */
    public function setLimitHeight($height = 0) {
        $this->limitHeight = $height;
    }

    /**
     * 设置图片上传到得服务器路径
     * @param type $path 
     */
    public function setUploadPath($path = './Data') {
        $this->uploadPath = $path;
    }
    
    /**
     * 设置压缩文件的路径
     * @param type $path
     */
    public function setCompressPath($path = 'Appzip'){
        $this->compressPath = $path;
    }

    /* 取得当前目录下的文件和文件夹 */

    /**
     * 取得目录下的文件和目录列表
     * @param type $dir
     * @return type 
     */
    public function getDir($dir) {
        $handler = opendir($dir);
        while ($file = readdir($handler)) {
            if ($file != '.' && $file != '..') {
                $data[] = $file;
            }
        }
        return $data;
    }
    
    /**
     * 读取文件行到数组
     * @param type $file
     * @return type
     */
    public function fileToArray($file){
        $data = file($file);
        return $data;
    }
    
    /**
     * 读取文件内容
     * @param type $file
     * @return type 
     */
    public function readFile($file) {
        return @file_get_contents($file);
    }

    /**
     * 写入文件内容
     * @param type $file
     * @param type $content
     * @return type 
     */
    public function writeFile($file, $content) {
        return @file_put_contents($file, $content);
    }
    
    /**
     * 删除文件
     * @param type $file
     * @return boolean
     */
    public function deleteFile($file){
        if($this->isExist($file)){
            return @unlink($file);
        }else{
            return true;
        }
    }

    /**
     * 上传文件
     * @param type $file
     * @return string 
     */
    public function uploadFile($file) {
        if (empty($file)) {
            return 'UploadError:file_information_is_empty';
        }
        $fileName = $file['name'];
        $source = $file['tmp_name'];
        $fileSize = $file['size'];
        if ($this->checkSize($fileSize)) {
            return 'UploadError:file_is_too_large';
        } elseif (!$this->checkType($fileName)) {
            return 'UploadError:file_type_is_not_allow';
        } else {
            if (!$this->isExist($this->uploadPath)) {
                $this->createDir($this->uploadPath);
            }
            $dest = $this->uploadPath . '/' . $this->getFileName($fileName);
            if (@copy($source, $dest)) {
                return array('path' => $dest, 'filename' => $fileName);
            } else {
                return 'UploadError:file_upload_fail';
            }
        }
    }

    /**
     * 上传所有文件
     * @param type $data
     * @return string 
     */
    public function uploadAll($data) {
        if (!empty($data)) {
            foreach ((array)$data as $k => $v) {
                $d[] = $this->uploadFile($v);
            }
        } else {
            return 'no_files';
        }
    }

    /**
     * 生成缩略图
     * @param type $srcFile
     * @param type $toFile
     * @param int $toW
     * @param int $toH
     */
    function thumbFile($srcFile, $toFile = "", $toW = 100, $toH = 100) {
        if ($toFile == "") {
            $toFile = $srcFile;
        }
        if(empty($toW)){
            $toW = 100;
        }
        if(empty($toH)){
            $toH = 100;
        }
        $info = "";
        $data = GetImageSize($srcFile, $info);
        switch ($data[2]) {
            case 1:
                if (!function_exists("imagecreatefromgif")) {
                    echo "你的GD库不能使用GIF格式的图片，请使用Jpeg或PNG格式！<a href='javascript:go(-1);'>返回</a>";
                    exit();
                }
                $im = ImageCreateFromGIF($srcFile);
                break;
            case 2:
                if (!function_exists("imagecreatefromjpeg")) {
                    echo "你的GD库不能使用jpeg格式的图片，请使用其它格式的图片！<a href='javascript:go(-1);'>返回</a>";
                    exit();
                }
                $im = ImageCreateFromJpeg($srcFile);
                break;
            case 3:
                $im = ImageCreateFromPNG($srcFile);
                break;
        }
        $srcW = ImageSX($im);
        $srcH = ImageSY($im);
        $toWH = $toW / $toH;
        $srcWH = $srcW / $srcH;
        if ($toWH <= $srcWH) {
            $ftoW = $toW;
            $ftoH = $ftoW * ($srcH / $srcW);
        } else {
            $ftoH = $toH;
            $ftoW = $ftoH * ($srcW / $srcH);
        }
        if($srcW>$toW && $srcH>$toH) {
            if (function_exists("imagecreatetruecolor")) {
                @$ni = ImageCreateTrueColor($ftoW, $ftoH);
                $white = imagecolorallocate($ni, 255, 255, 255);
                imagefill($ni, 0, 0, $white);
                if ($ni)
                    ImageCopyResampled($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
                else {
                    $ni = ImageCreate($ftoW, $ftoH);
                    ImageCopyResized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
                }
            } else {
                $ni = ImageCreate($ftoW, $ftoH);
                ImageCopyResized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
            }
            if (function_exists('imagejpeg'))
                ImageJpeg($ni, $toFile);
            else
                ImagePNG($ni, $toFile);
            ImageDestroy($ni);
        }
        ImageDestroy($im);
    }

    /**
     * @param $src_img string     源图绝对完整地址{带文件名及后缀名}
     * @param $dst_img string     目标图绝对完整地址{带文件名及后缀名}
     * @param $width int        缩略图宽{0:此时目标高度不能为0，目标宽度为源图宽*(目标高度/源图高)}
     * @param $height int        缩略图高{0:此时目标宽度不能为0，目标高度为源图高*(目标宽度/源图宽)}
     * @param $cut int        是否裁切{宽,高必须非0}
     * @param $proportion int/float  缩放{0:不缩放, 0<this<1:缩放到相应比例(此时宽高限制和裁切均失效)}
     * @return boolean
     */
    public function thumbFile_bak($src_img, $dst_img, $width = 150, $height = 150, $cut = 0, $proportion = 0) {
        if (!is_file($src_img)) {
            return false;
        }
        $ot = $this->getExtName($dst_img);
        $otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
        $srcinfo = getimagesize($src_img);
        $src_w = $srcinfo[0];
        $src_h = $srcinfo[1];
        $type = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
        $createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);
        $dst_h = $height;
        $dst_w = $width;
        $x = $y = 0;
        if (($width > $src_w && $height > $src_h) || ($height > $src_h && $width == 0) || ($width > $src_w && $height == 0)) {
            $proportion = 1;
        }
        if ($width > $src_w) {
            $dst_w = $width = $src_w;
        }
        if ($height > $src_h) {
            $dst_h = $height = $src_h;
        }

        if (!$width && !$height && !$proportion) {
            return false;
        }
        if (!$proportion) {
            if ($cut == 0) {
                if ($dst_w && $dst_h) {
                    if ($dst_w / $src_w > $dst_h / $src_h) {
                        $dst_w = $src_w * ($dst_h / $src_h);
                        $x = 0 - ($dst_w - $width) / 2;
                    } else {
                        $dst_h = $src_h * ($dst_w / $src_w);
                        $y = 0 - ($dst_h - $height) / 2;
                    }
                } else if ($dst_w xor $dst_h) {
                    if ($dst_w && !$dst_h) {
                        $propor = $dst_w / $src_w;
                        $height = $dst_h = $src_h * $propor;
                    } else if (!$dst_w && $dst_h) {
                        $propor = $dst_h / $src_h;
                        $width = $dst_w = $src_w * $propor;
                    }
                }
            } else {
                if (!$dst_h) {
                    $height = $dst_h = $dst_w;
                }
                if (!$dst_w) {
                    $width = $dst_w = $dst_h;
                }
                $propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
                $dst_w = (int) round($src_w * $propor);
                $dst_h = (int) round($src_h * $propor);
                $x = ($width - $dst_w) / 2;
                $y = ($height - $dst_h) / 2;
            }
        } else {
            $proportion = min($proportion, 1);
            $height = $dst_h = $src_h * $proportion;
            $width = $dst_w = $src_w * $proportion;
        }

        $src = $createfun($src_img);
        $dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
//        $dst = imagecreatetruecolor($dst_w, $dst_h);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);

        if (function_exists('imagecopyresampled')) {
            imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        } else {
            imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        }
        $otfunc($dst, $dst_img);
        imagedestroy($dst);
        imagedestroy($src);
        return true;
    }

    /**
     * 取得文件的扩展名
     * @param type $filename
     * @return type 
     */
    public function getExtName($filename) {
        $pos = strrpos($filename, '.') + 1;
        $ext = substr($filename, $pos, strlen($filename));
        return strtolower($ext);
    }
    
    /**
     * 保存二进制流文件
     * @param type $source
     * @param type $extName
     * @param type $destPath
     * @return boolean
     */
    public function savePostFile($source, $extName, $destPath) {
        $fileName = $this->getFileName($extName);
        $this->createDir($destPath);
//        $source = $this->getHexdec($source);
        $fileName = $destPath . $fileName;
        if ($this->writeFile($fileName, $source)) {
            return __SITENAME__ . $fileName;
        } else {
            return false;
        }
    }

    /**
     * 苹果二进制流处理
     * @param type $source 数据
     * @return type 二进制流
     */
    private function getHexdec($source) {
        $source = str_replace('<', '', $source);
        $source = str_replace('>', '', $source);
        $source = str_replace(' ', '', $source);
        return pack('H*', $source);
    }
    
    /**
     * 遍历删除文件夹中的所有文件，包括文件夹本身
     * @param type $path 文件夹路径
     */
    public function deleteDir($dir, $isdelfile = false){
        if(!is_dir($dir)) {
            if($isdelfile){@unlink($dir);}else{return 'not a folder';}
        }else{
            $handler = opendir($dir);
            while(($file = readdir($handler)) !== false){
                if($file == "."||$file == ".."){continue;}
                $file = $dir.'/'.$file;
                if(is_dir($file)){
                    $this->deleteDir($file);
                }else{
                    @unlink($file);
                }
            }
            closedir($handler);
            @rmdir($dir);
        }
        return true;
    }
    
    /**
     * 复制一个目录里边的所有文件到指定位置
     * @param type $dir 规定要复制的目录
     * @param type $destination 规定复制文件的目的地
     * @return boolean
     */
    public function copyDir($dir, $destination){
        if(!is_dir($dir)){
            return 'not a folder';
        }else{
            $handler = opendir($dir);
            while(($filename = readdir($handler)) !== false){
                if($filename == "." || $filename == ".."){continue;}
                $filepath = $dir.'/'.$filename;
                if(is_dir($filepath)){
                    $path = $destination.'/'.$filename;
                    $this->createDir($path);
                    $this->copyDir($filepath, $path);
                }else{
                    copy($filepath, $destination.'/'.$filename);
                }
            }
            closedir($handler);
        }
    }

    /**
     * 判断文件或目录是否存在
     * @param type $path
     * @return type 
     */
    public function isExist($path) {
        return file_exists($path);
    }

    /**
     * 创建多层目录
     * @param type $path 
     */
    public function createDir($path) {
        $arr = explode('/', $path);
        $p = '';
        foreach ((array)$arr as $value) {
            $p .= $value . '/';
            $status = $this->isExist($p);
            if (!$status) {
                @mkdir($p);
            }
        }
    }

    /**
     * 检查文件大小是否在允许范围之内
     * @param type $size
     * @return boolean 
     */
    private function checkSize($size) {
        $size = round($size / 1024, 2);
        if ($size > $this->limitSize) {
            return true;
        }
        return false;
    }
    
    /**
     * 文件大小计算（带单位）
     * @param type $size
     * @return type
     */
    public function calFileSize($size) {
        $dw = 'B';
        if ($size > 1024) {
            $size = round($size / 1024, 2);
            $dw = 'KB';
            if ($size > 1024) {
                $size = round($size / 1024, 2);
                $dw = 'MB';
                if ($size > 1024) {
                    $size = round($size / 1024, 2);
                    $dw = 'GB';
                    if ($size > 1024) {
                        $size = round($size / 1024, 2);
                        $dw = 'TB';
                        if ($size > 1024) {
                            $size = round($size / 1024, 2);
                            $dw = 'PB';
                        }
                    }
                }
            }
        }
        return $size . $dw;
    }

    /**
     * 检查文件类型是否允许上传
     * @param type $fileName
     * @return boolean 
     */
    private function checkType($fileName) {
        $extName = $this->getExtName($fileName);
        if (in_array($extName, $this->limitType)) {
            return true;
        }
        return false;
    }

    /**
     * 检查文件宽度是否允许上传
     * @param type $width
     * @return boolean 
     */
    private function checkWidth($width) {
        if ($this->limitWidth < $width) {
            return false;
        }
        return true;
    }

    /**
     * 检查文件高度是否允许上传
     * @param type $height
     * @return boolean 
     */
    private function checkHeight($height) {
        if ($this->limitHeight < $height) {
            return false;
        }
        return true;
    }
    
    /**
     * 获取指定目录下的文件
     * @param type $dir 目录
     * @return string 返回目录下到所有文件
     */
    public function getFiles($dir){
        $handler = opendir($dir);
        while(($file = readdir($handler)) !== false){
            if($file == "."||$file == ".."){continue;}
            if (!is_dir($dir.'/'.$file)) {
                $data[] = $dir.'/'.$file;
            }else{
                $data[] = $this->getFiles($dir.'/'.$file);
            }
            
        }
        return $data;
    }

    /**
     * 取得新的文件名
     * @param type $fileName
     * @param type $pre
     * @return string 
     */
    private function getFileName($fileName = null, $pre = null) {
        if ($this->isRename) {
            $extName = $this->getExtName($fileName);
            $fName = uniqid($pre) . '.' . $extName;
            return $fName;
        }
        return basename($fileName);
    }
    
    /**
     * 下载网络文件
     * @param type $url
     * @return string
     */
    public function downloadFile($url) {
        $basename = basename($url);
        if (empty($url)) {
            return 'DownloadError:filename_can_not_empty';
        }
        if (!$this->checkType($basename)) {
            return 'DownloadError:file_type_is_not_allow';
        }

        $content = file_get_contents($url);
        if (empty($content)) {
            return 'DownloadError:file_information_is_empty';
        }
        if ($content != '') {
            if (!$this->isExist($this->uploadPath)) {
                $this->createDir($this->uploadPath);
            }
            $filePath = $this->uploadPath . '/' . $this->getFileName($basename);
            if ($this->writeFile($filePath, $content)) {
                return array('path' => $filePath, 'filename' => $basename);
            } else {
                return 'DownloadError:downloda_file_error';
            }
        }
    }
    
    /**
     * 压缩文件
     * @param type $paths 压缩文件的路径
     * @param type $zipName 压缩文件名称
     */
    public function zip_File($paths, $zipName, $type = 'zip'){
        $this->createDir($this->compressPath);
        if($type == 'zip'){
            $status = $this->toZip($paths, $zipName);
            return $status;
        }
    }
    
    /**
     * 压缩文件成zip，并删除源文件
     * @param type $paths 压缩文件的路径
     * @param type $zipName 压缩文件名称
     * @return string 
     */
    public function toZip($paths, $zipName){
        $zip = new ZipArchive();
        $zip->open($this->compressPath.'/'.$zipName.'.zip', ZIPARCHIVE::CREATE);
        foreach((array)$paths as $key => $value){
            if(!is_dir($value)){
                if($this->isExist($value)){
                    $zip->addFile($value, basename($value));
                }else{
                    return $value.' no found';
                }
            }
        }
        $zip->close();
        foreach((array)$paths as $key => $value){
            if(!is_dir($value)){
                @unlink($value);
            }else{
                @rmdir($value);
            }
        }
        return 'success';
    }
}