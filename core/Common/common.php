<?php

/**
 * 共用函数
 *
 * 本程序主要作用定义系统共用的函数
 * 
 * @category   Common
 * @package    Common
 * @copyright  Copyright (c) ink工作室 (http://www.inkphp.com)
 * @author     张旭刚
 * @version    v1.0 beta
 */

/**
 * 取得模板文件的路径，用在模板文件中包含其他模板文件的时候
 * @param type $name 模板名称
 * @return string 返回全路径svn
 */
function getTplPath($name) {
    $path = './Apps/' . APP_NAME . '/Theme/' . __DEFAULT_THEME__ . '/' . $name . '.tpl.php';
    return $path;
}

/**
 * 用来处理跳转路径
 * @param type $router 链接地址
 * @param type $data 要传递的参数
 * @return string 完整链接地址
 */
function U($router, $data = null) {
    $s = substr($router, 0, 7);
    if ($s == 'http://') {
        return $router;
    }
    $rules = include('./core/Common/router.inc.php');
    if (__USE_ROUTER__) {
        $d = explode('/', $router);
        $isrule = true;
        foreach ((array) $rules as $key => $value) {
            if ($d[0] == $value['app'] && $d[1] == $value['m'] && $d[2] == $value['a']) {
                $isrule = true;
                $router = $key;
                break;
            }
        }
        $router = '/' . $router;
        $str = '';
        if (!empty($data)) {
            foreach ((array) $data as $k => $v) {
                $str .= '/' . $k . '/' . $v;
            }
        }
        $router .= $str . '.html';
    } else {
        if (empty($rules[$router])) {
            $d = explode('/', $router);
        } else {
            $d[0] = $rules[$router]['app'];
            $d[1] = $rules[$router]['m'];
            $d[2] = $rules[$router]['a'];
        }
        $router = './index.php?app=' . $d[0] . '&m=' . $d[1] . '&a=' . $d[2];
        foreach ((array) $data as $k => $v) {
            $str .= '&' . $k . '=' . $v;
        }
        $router .= $str;
    }
    return $router;
}

/**
 * 用来导入CSS和JS文件，可以采用两种方式，一种是以文件形式导入，一种是以代码形式导入，具体参考配置文件中的常量__CACHE_FORWARD_FILE__
 * @param type $name 导入文件的名字
 * @param type $type 导入文件的扩展名
 */
function S($name = 'base', $type = 'css') {
    $pre = substr($name, 0, 3);
    if (!__USE_ROUTER__) {
        if ($pre == 'sys') {
            $path = __CORE__ . '/static/Public/' . $type . '/' . $name . '.' . $type;
        } else {
            if ($type == 'css') {
                $path = './Apps/' . APP_NAME . '/static/' . __DEFAULT_THEME__ . '/' . __DEFAULT_STYLE__ . '/css/' . $name . '.css';
            } elseif ($type == 'js') {
                $path = './Apps/' . APP_NAME . '/static/' . __DEFAULT_THEME__ . '/js/' . $name . '.js';
            }
        }
    } else {
        if ($pre == 'sys') {
            $path = __SITENAME__ . 'core/static/Public/' . $type . '/' . $name . '.' . $type;
        } else {
            if ($type == 'css') {
                $path = __SITENAME__ . 'Apps/' . APP_NAME . '/static/' . __DEFAULT_THEME__ . '/' . __DEFAULT_STYLE__ . '/css/' . $name . '.css';
            } elseif ($type == 'js') {
                $path = __SITENAME__ . 'Apps/' . APP_NAME . '/static/' . __DEFAULT_THEME__ . '/js/' . $name . '.js';
            }
        }
    }
//    if(file_exists($path)){
    if (!__CACHE_FORWARD_FILE__) {
        $str = '?' . rand(1000000, 9999999);
        $path .= $str;
    }
    if (!__LOAD_FILE__) {
        if ($type == 'css') {
            $code = '<style type="text/css">' . "\n";
            $code .= file_get_contents($path);
            $code .= '</style>' . "\n";
        } elseif ($type == 'js') {
            $code = '<script language="javascript">' . "\n";
            $code .= file_get_contents($path);
            $code .= '</script>' . "\n";
        }
        echo $code;
    } else {
        if ($type == 'css') {
            echo '<link rel="stylesheet" href="' . $path . '" type="text/css" />' . "\n";
        } elseif ($type == 'js') {
            echo '<script language="javascript" src="' . $path . '"></script>' . "\n";
        }
    }
//    }
}

/**
 * 国际化方法，用来寻找各种语言包，并将之翻译显示在页面上
 * @param type $name 要翻译的字符串
 * @return type 
 */
function L($name) {
    if (defined(__DEFAULT_LANG__)) {
        $defaultLang = __DEFAULT_LANG__;
    } else {
        $defaultLang = 'zh_cn';
    }
    $path = './core/Lang/' . $defaultLang . '/language.php';
    if (!file_exists($path)) {
        return getLang($name);
    } else {
        $lang = include($path);
        if (!empty($lang[$name])) {
            return $lang[$name];
        } else {
            return getLang($name);
        }
    }
}

/**
 * 用来寻找用户自定义的国际化文件
 * @param type $name 要翻译的字符串
 * @return type 翻译之后的字符串，如果没有找到返回原字符串
 */
function getLang($name) {
    $path = './Apps/' . APP_NAME . '/Lang/' . __DEFAULT_LANG__ . '/language.php';
    if (!file_exists($path)) {
        return str_replace('_', ' ', strtoupper($name));
    } else {
        $lang = include($path);
        if (!empty($lang[$name])) {
            return $lang[$name];
        } else {
            return str_replace('_', ' ', strtoupper($name));
        }
    }
}

/**
 * 自动加载共用Controller,Model的方法
 * @param type $name Controller的名字
 */
function inkAutoload($name) {
    if (isModel($name)) {
        include('./Model/' . $name . '.class.php');
    } else {
        include('./Apps/' . APP_NAME . '/Controller/' . $name . '.class.php');
    }
}

function isModel($name) {
    $n = str_replace('Model', '', $name);
    if ($n != $name) {
        return true;
    }
    return false;
}

/**
 * @method DES方式数据加密
 * @param type $input
 * @param type $key
 * @return type
 */
function doEncrypt($data, $key) {
    $d = openssl_encrypt($data, 'DES-CBC', $key, 0, '');
    return base64_encode($d);
}

/**
 * @method DES方式数据解密
 * @param type $input
 * @param type $key
 * @return type
 */
function doDecrypt($data, $key) {
    $key = pack('H48', $key);
    $data = openssl_decrypt($data, 'DES-CBC', $key, 0, '');
    return unicode_decode($data);
}

function unicode_decode($name) {
    // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
    preg_match_all($pattern, $name, $matches);
    if (!empty($matches)) {
        $name = '';
        for ($j = 0; $j < count($matches[0]); $j++) {
            $name .= (strpos($str, '\\u') === 0) ? iconv('UCS-2', 'UTF-8', chr(base_convert(substr($str, 2, 2), 16, 10)) . chr(base_convert(substr($str, 4), 16, 10))) : $matches[0][$j];
        }
    }
    return $name;
}

//判断URL是否静态链接，如果是则直接返回404
function checkUrl($u) {
    $urls = explode('?', $u);
    $url = $urls[0];
    if ($url == '/') {
        return $url;
    }
    $data = array('html'); //控制扩展名必须是html，其他扩展名如果不存在则直接返回404,'php', null
    $ext = getExtName($url);
    if (!in_array($ext, $data)) {
        if (in_array($ext, array('gif', 'jpg', 'png', 'bmp', 'jpeg', 'ico'))) {
            header("Content-type:  image/" . $ext);
        } elseif (in_array($ext, array('mp3', 'wav'))) {
            header("Content-type:  audio/mpeg");
        } elseif (in_array($ext, array('txt', 'css', 'js'))) {
            header("Content-type:  text/plain");
        } elseif (in_array($ext, array('zip', 'rar', 'gz', '7z', 'biz', 'pdf', 'doc', 'txt', 'sql', 'xls', 'exe'))) {
            header('Content-Type: application/' . $ext);
        } else {
            header('location:' . U('Home/Index/nopage'));
            exit;
        }
        header('HTTP/1.1 404 Not Found');
        exit;
    } else {
        $url = str_replace('.html', '', $url);
//        $url = str_replace('.php','',$url);
        return $url;
    }
}

/**
 * 获取文件扩展名
 * @param type $filename
 * @return null
 */
function getExtName($filename) {
    $pos = strrpos($filename, '.');
    if (!$pos) {
        return null;
    }
    $pos++;
    $ext = substr($filename, $pos, strlen($filename));
    return strtolower($ext);
}

/**
 * @method 文件大小和单位计算
 * @param type $size
 * @return type
 */
function calFileSize($size, $dec = 2) {//PB，EB，ZB，YB，BB
    $dws = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB', 'BB'];
    $i = 0;
    while ($size >= 1024){
        $size /= 1024;
        $i++;
    }
    return round($size, $dec) . ' ' . $dws[$i-1];
}

/**
 * @method 读取block并显示在网页中。
 * @param type $blocks
 */
function getBlocks($blocks) {
    foreach ((array) $$blocks as $key => $value) {
        include(getTplPath('Blocks/' . $value));
    }
}

/**
 * @method  判断客户端浏览器版本和类型
 * @return string
 */
function getBrowser() {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Maxthon')) {
        $browser = 'Maxthon';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 12.0')) {
        $browser = 'IE12.0';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 11.0')) {
        $browser = 'IE11.0';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 10.0')) {
        $browser = 'IE10.0';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0')) {
        $browser = 'IE9.0';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) {
        $browser = 'IE8.0';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) {
        $browser = 'IE7.0';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) {
        $browser = 'IE6.0';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'NetCaptor')) {
        $browser = 'NetCaptor';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape')) {
        $browser = 'Netscape';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Lynx')) {
        $browser = 'Lynx';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) {
        $browser = 'Opera';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
        $browser = 'Chrome';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
        $browser = 'Firefox';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')) {
        $browser = 'Safari';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'iphone')) {
        $browser = 'iphone';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'ipod')) {
        $browser = 'ipod';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'ipad')) {
        $browser = 'iphone';
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'android')) {
        $browser = 'android';
    } else {
        $browser = 'other';
    }

    if (in_array($browser, array('IE6.0', 'IE7.0', 'IE8.0', 'IE9.0', 'IE10.0', 'IE11.0', 'IE12.0'))) {
        $data = array('version' => $browser, 'type' => 'ie');
    } else {
        $data = array('version' => $browser, 'type' => $browser);
    }
    return $data;
}

//判断客户端是否是微信内置浏览器
function is_weixin() {
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    }
    return false;
}

/**
 * @method 根据源文件和要输出的文件大小来生成缩略图，宽高任意一个为0的话输出原图。
 * @param type $pic
 * @param type $width
 * @param type $height
 * @return type $file图片文件路径
 */
function getThumb($pic, $width = 0, $height = 0) {
    if ($width == 0 || $height == 0) {
        if (file_exists($pic)) {
            $file = $pic;
        } else {
            $file = IMAGE_PATH . '/nopic.png';
        }
    } else {
        //缩略图
        $thumb = getThumbPath($pic, $width, $height);
        if (file_exists($thumb)) {
            $file = $thumb;
        } else {
            if (!file_exists($pic)) {
                $file = IMAGE_PATH . '/nopic.png';
            } else {
                $file = setThumb($pic, $thumb, $width, $height);
            }
        }
    }
    return str_replace('./', '/', $file);
}

/**
 * @method 获取缩略图的文件路径，一般不单独使用这个函数。
 * @param type $pic
 * @param type $width
 * @param type $height
 * @return string
 */
function getThumbPath($pic, $width, $height) {
    $extname = getExtName($pic);
    $path = substr($pic, 0, strlen($pic) - strlen($extname) - 1) . '_' . $width . '_' . $height . '.' . $extname;
    return $path;
}

/**
 * @method 缩略图生成函数
 * @param type $source
 * @param type $intent
 * @param type $width
 * @param type $height
 * @return boolean
 */
function setThumb($source, $intent, $width, $height) {
    if (!file_exists($source)) {
        return false;
    }
    $size = getimagesize($source);
    $s_width = $size[0];
    $s_height = $size[1];
    if ($s_width <= $width && $s_height <= $height) {
        return $source;
    } else {
        $wb = $width / $s_width;
        $hb = $height / $s_height;
        $extname = getExtName($source);
        $filetype = $extname == 'jpg' ? 'jpeg' : $extname;
        $func = 'imagecreatefrom' . $filetype;
        $cfunc = 'image' . $filetype;
        $im = $func($source);
        $thumb = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($im, 255, 255, 255);
        imagefill($thumb, 0, 0, $white);
        if ($wb < $hb) {//按照高度缩放，然后去图片中间部分为缩略图
            $w = $width / $hb;
            $h = $s_height;
            $top = 0;
            $left = ($s_width - $w) / 2;
        } else if ($wb > $hb) {//按照宽度缩放，去图片中间位置为缩略图
            $w = $s_width;
            $h = $height / $wb;
            $left = 0;
            $top = ($s_height - $h) / 2;
        } else {//相等状况直接缩放接口
            $top = 0;
            $left = 0;
            $w = $s_width;
            $h = $s_height;
        }
        imagecopyresized($thumb, $im, 0, 0, $left, $top, $width, $height, $w, $h);
        $cfunc($thumb, $intent);
        imagedestroy($im);
        imagedestroy($thumb);
        return $intent;
    }
}

/**
 * @method 过滤HTML标签
 * @param type $str
 * @param type $allowable_tags
 * @return type
 */
function real_strip_tags($str, $allowable_tags = "") {
    $str = html_entity_decode($str, ENT_QUOTES, 'UTF-8');
    return strip_tags($str, $allowable_tags);
}

/**
 * @method 过滤全部HTML标签
 * @param type $text
 * @return type
 */
function t($text) {
    $text = nl2br($text);
    $text = real_strip_tags($text);
    $text = addslashes($text);
    $text = trim($text);
    return $text;
}

/**
 * @method 获取客户端IP地址
 * @staticvar null $ip
 * @param type $type
 * @return null
 */
function get_client_ip($type = 0) {
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos)
            unset($arr[$pos]);
        $ip = trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('127.0.0.1', 0);
    return $ip[$type];
}

/**
 * @method 判断字符串是否为UTF-8编码
 * @param type $string
 * @return type
 */
function is_utf8($string) {
    return preg_match('%^(?:
         [\x09\x0A\x0D\x20-\x7E]            # ASCII
       | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
       |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
       | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
       |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
       |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
       | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
       |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
   )*$%xs', $string);
}

/**
 * 
 * @method 正则替换和过滤内容
 * 
 * @param  $html
 * @author jason
 */
function preg_html($html) {
    $p = array("/<[a|A][^>]+(topic=\"true\")+[^>]*+>#([^<]+)#<\/[a|A]>/",
        "/<[a|A][^>]+(data=\")+([^\"]+)\"[^>]*+>[^<]*+<\/[a|A]>/",
        "/<[img|IMG][^>]+(src=\")+([^\"]+)\"[^>]*+>/");
    $t = array('topic{data=$2}', '$2', 'img{data=$2}');
    $html = preg_replace($p, $t, $html);
    $html = strip_tags($html, "<br/>");
    return $html;
}

/**
 * @method 获取字串首字母(可获取汉字的拼音首字母)
 * @param type $s0
 * @return string 
 */
function getFirstLetter($s0) {
    $firstchar_ord = ord(strtoupper($s0{0}));
    if ($firstchar_ord >= 65 and $firstchar_ord <= 91)
        return strtoupper($s0{0});
    if ($firstchar_ord >= 48 and $firstchar_ord <= 57)
        return '#';
    $s = iconv("UTF-8", "gb2312", $s0);
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if ($asc >= -20319 and $asc <= -20284)
        return "A";
    if ($asc >= -20283 and $asc <= -19776)
        return "B";
    if ($asc >= -19775 and $asc <= -19219)
        return "C";
    if ($asc >= -19218 and $asc <= -18711)
        return "D";
    if ($asc >= -18710 and $asc <= -18527)
        return "E";
    if ($asc >= -18526 and $asc <= -18240)
        return "F";
    if ($asc >= -18239 and $asc <= -17923)
        return "G";
    if ($asc >= -17922 and $asc <= -17418)
        return "H";
    if ($asc >= -17417 and $asc <= -16475)
        return "J";
    if ($asc >= -16474 and $asc <= -16213)
        return "K";
    if ($asc >= -16212 and $asc <= -15641)
        return "L";
    if ($asc >= -15640 and $asc <= -15166)
        return "M";
    if ($asc >= -15165 and $asc <= -14923)
        return "N";
    if ($asc >= -14922 and $asc <= -14915)
        return "O";
    if ($asc >= -14914 and $asc <= -14631)
        return "P";
    if ($asc >= -14630 and $asc <= -14150)
        return "Q";
    if ($asc >= -14149 and $asc <= -14091)
        return "R";
    if ($asc >= -14090 and $asc <= -13319)
        return "S";
    if ($asc >= -13318 and $asc <= -12839)
        return "T";
    if ($asc >= -12838 and $asc <= -12557)
        return "W";
    if ($asc >= -12556 and $asc <= -11848)
        return "X";
    if ($asc >= -11847 and $asc <= -11056)
        return "Y";
    if ($asc >= -11055 and $asc <= -10247)
        return "Z";
    return '#';
}

/**
 * @method 传统形式显示无限极分类树
 * @param array $data 树形结构数据
 * @param string $stable 所操作的数据表
 * @param integer $left 样式偏移
 * @param array $delParam 删除关联信息参数，app、module、method
 * @param integer $level 添加子分类层级，默认为0，则可以添加无限子分类
 * @param integer $times 用于记录递归层级的次数，默认为1，调用函数时，不需要传入值。
 * @param integer $limit 分类限制字数。
 * @return string 树形结构的HTML数据
 */
function showTreeCategory($data, $stable, $left, $delParam, $level = 0, $ext = '', $times = 1, $limit = 0) {
    $html = '<ul class="sort">';
    foreach ((array) $data as $val) {
        // 判断是否有符号
        $isFold = empty($val['child']) ? false : true;
        $html .= '<li id="' . $stable . '_' . $val['id'] . '" class="underline" style="padding-left:' . $left . 'px;"><div class="c1">';
        if ($isFold) {
            $html .= '<a href="javascript:;" onclick="admin.foldCategory(' . $val['id'] . ')"><img id="img_' . $val['id'] . '" src="' . __THEME__ . '/admin/image/on.png" /></a>';
        }
        $html .= '<span>' . $val['title'] . '</span></div><div class="c2">';
        if ($level == 0 || $times < $level) {
            $html .= '<a href="javascript:;" onclick="admin.addTreeCategory(' . $val['id'] . ', \'' . $stable . '\', ' . $limit . ');">添加子分类</a>&nbsp;-&nbsp;';
        }
        $html .= '<a href="javascript:;" onclick="admin.upTreeCategory(' . $val['id'] . ', \'' . $stable . '\', ' . $limit . ');">编辑</a>&nbsp;-&nbsp;';
        if (empty($delParam)) {
            $html .= '<a href="javascript:;" onclick="admin.rmTreeCategory(' . $val['id'] . ', \'' . $stable . '\');">删除</a>';
        } else {
            $html .= '<a href="javascript:;" onclick="admin.rmTreeCategory(' . $val['id'] . ', \'' . $stable . '\', \'' . $delParam['app'] . '\', \'' . $delParam['module'] . '\', \'' . $delParam['method'] . '\');">删除</a>';
        }
        $ext !== '' && $html .= '&nbsp;-&nbsp;<a href="' . U('admin/Public/setCategoryConf', array('cid' => $val['id'], 'stable' => $stable)) . '&' . $ext . '">分类配置</a>';
        $html .= '</div><div class="c3">';
        $html .= '<a href="javascript:;" onclick="admin.moveTreeCategory(' . $val['id'] . ', \'up\', \'' . $stable . '\')" class="ico_top mr5"></a>';
        $html .= '<a href="javascript:;" onclick="admin.moveTreeCategory(' . $val['id'] . ', \'down\', \'' . $stable . '\')" class="ico_btm"></a>';
        $html .= '</div></li>';
        if (!empty($val['child'])) {
            $html .= '<li id="sub_' . $val['id'] . '" style="display:none;">';
            $html .= showTreeCategory($val['child'], $stable, $left + 15, $delParam, $level, $ext, $times + 1, $limit);
            $html .= '</li>';
        }
    }
    $html .= '</ul>';
    return $html;
}

/**
 * @检查是否是以手机浏览器进入(IN_MOBILE)
 * @staticvar string $mobilebrowser_list
 * @return boolean
 */
function isMobile() {
    $mobile = array();
    static $mobilebrowser_list = 'Mobile|iPhone|iPod|Android|WAP|NetFront|JAVA|OperasMini|UCWEB|WindowssCE|Symbian|Series|webOS|SonyEricsson|Sony|BlackBerry|Cellphone|dopod|Nokia|samsung|PalmSource|Xphone|Xda|Smartphone|PIEPlus|MEIZU|MIDP|CLDC';
    //note 获取手机浏览器
    if (preg_match("/$mobilebrowser_list/i", $_SERVER['HTTP_USER_AGENT'], $mobile)) {
        return true;
    } else {
        if (preg_match('/(mozilla|chrome|safari|opera|m3gate|winwap|openwave)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return false;
        } else {
            if ($_GET['mobile'] === 'yes') {
                return true;
            } else {
                return false;
            }
        }
    }
}

/**
 * @检查是否输入为汉字
 * @param type $sInBuf 数据
 * @return 
 */
function isChinese($sInBuf) {
    $iLen = strlen($sInBuf);
    for ($i = 0; $i < $iLen; $i++) {
        if (ord($sInBuf{$i}) >= 0x80) {
            if ((ord($sInBuf{$i}) >= 0x81 && ord($sInBuf{$i}) <= 0xFE) && ((ord($sInBuf{$i + 1}) >= 0x40 && ord($sInBuf{$i + 1}) < 0x7E) || (ord($sInBuf{$i + 1}) > 0x7E && ord($sInBuf{$i + 1}) <= 0xFE))) {
                if (ord($sInBuf{$i}) > 0xA0 && ord($sInBuf{$i}) < 0xAA) {
                    //有中文标点
                    return false;
                }
            } else {
                //有日文或其它文字
                return false;
            }
            $i++;
        } else {
            return false;
        }
    }
    return true;
}

function get_disk_space($letter) {
    //获取磁盘信息
    $diskct = 0;
    $disk = array();
    $diskz = 0; //磁盘总容量
    $diskk = 0; //磁盘剩余容量
    $is_disk = $letter . ':';
    if (@disk_total_space($is_disk) != NULL) {
        $diskct++;
        $disk[$letter][0] = byte_format(@disk_free_space($is_disk));
        $disk[$letter][1] = byte_format(@disk_total_space($is_disk));
        $disk[$letter][2] = round(((@disk_free_space($is_disk) / (1024 * 1024 * 1024)) / (@disk_total_space($is_disk) / (1024 * 1024 * 1024))) * 100, 2) . '%';
        $diskk += byte_format(@disk_free_space($is_disk));
        $diskz += byte_format(@disk_total_space($is_disk));
    }
    return $disk;
}

/*
 * 加密解密的参考方法暂时不启用
  //加密函数
  function jiami($txt, $key = null) {
  if (empty ( $key ))
  $key = C ( 'SECURE_CODE' );
  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
  $nh = rand ( 0, 64 );
  $ch = $chars [$nh];
  $mdKey = md5 ( $key . $ch );
  $mdKey = substr ( $mdKey, $nh % 8, $nh % 8 + 7 );
  $txt = base64_encode ( $txt );
  $tmp = '';
  $i = 0;
  $j = 0;
  $k = 0;
  for($i = 0; $i < strlen ( $txt ); $i ++) {
  $k = $k == strlen ( $mdKey ) ? 0 : $k;
  $j = ($nh + strpos ( $chars, $txt [$i] ) + ord ( $mdKey [$k ++] )) % 64;
  $tmp .= $chars [$j];
  }
  return $ch . $tmp;
  }

  //解密函数
  function jiemi($txt, $key = null) {
  if (empty ( $key ))
  $key = C ( 'SECURE_CODE' );
  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
  $ch = $txt [0];
  $nh = strpos ( $chars, $ch );
  $mdKey = md5 ( $key . $ch );
  $mdKey = substr ( $mdKey, $nh % 8, $nh % 8 + 7 );
  $txt = substr ( $txt, 1 );
  $tmp = '';
  $i = 0;
  $j = 0;
  $k = 0;
  for($i = 0; $i < strlen ( $txt ); $i ++) {
  $k = $k == strlen ( $mdKey ) ? 0 : $k;
  $j = strpos ( $chars, $txt [$i] ) - $nh - ord ( $mdKey [$k ++] );
  while ( $j < 0 )
  $j += 64;
  $tmp .= $chars [$j];
  }
  return base64_decode ( $tmp );
  }
 * 
 * 
 */

function isExistsStr($str, $search) {
    $temp = str_replace($search, '', $str);
    return $temp != $str;
}

/**
 * 异常处理
 * @param mixed $exception 异常对象
 * @author blog.snsgou.com
 */
function handle_exception($exception) {
    InkException::exceptionError($exception);
}

/**
 * 错误处理
 * @param string $errNo 错误代码
 * @param string $errStr 错误信息
 * @param string $errFile 出错文件
 * @param string $errLine 出错行
 * @author blog.snsgou.com
 */
function handle_error($errNo, $errStr, $errFile, $errLine) {
    if ($errNo) {
        InkException::systemError($errStr, false, true, false);
    }
}

/**
 * @see 替换多余指定双字符为单字符，缺省是替换所有双空格：替换所有换行符以及双空格
 * @param string $str
 * @param string $checkstr:要替换的字符
 * @return string
 */
function replaceDoubleSpace($str, $checkstr = ' ') {
    $str = str_replace(chr(13), '', $str);
    $str = str_replace("\n", '', $str);
    $str = str_replace("\t", '', $str);
    $strs = explode($checkstr, $str);
    $s = '';
    foreach ((array) $strs as $k => $v) {
        if (empty($v)) {
            unset($strs[$k]);
        }
    }
    return implode(' ', $strs);
}

/**
 * @see 替换指定字符右侧的特定字符
 * @param array $data 要替换前后特定字符的字符数组
 * @param string $checkstr 要替换的特定字符
 * @param string $str 要替换的字符串
 * @return string
 */
function deleteRightSpace($str, $data = array(), $checkstr = ' ') {
    foreach ((array) $data as $k => $value) {
        $str = str_replace($value . $checkstr, $value, $str);
    }
    return $str;
}

/**
 * @see 替换指定字符左侧侧的特定字符
 * @param array $data 要替换前后特定字符的字符数组
 * @param string $checkstr 要替换的特定字符
 * @param string $str 要替换的字符串
 * @return type
 */
function deleteLeftSpace($str, $data = array(), $checkstr = ' ') {
    foreach ((array) $data as $k => $value) {
        $str = str_replace($checkstr . $value, $value, $str);
    }
    return $str;
}

/**
 * @see 替换指定字符左侧侧的特定字符
 * @param array $data 要替换前后特定字符的字符数组
 * @param string $checkstr 要替换的特定字符
 * @param string $str 要替换的字符串
 * @return type
 */
function deleteBeforeAndAfterSpace($str, $data = array(), $checkstr = ' ') {
    foreach ((array) $data as $k => $value) {
        $str = str_replace($value . $checkstr, $value, $str);
        $str = str_replace($checkstr . $value, $value, $str);
    }
    return $str;
}

function unescape($str) {
    $str = str_replace('&quot;,', '', $str);
    $str = str_replace('&quot;', '', $str);
    $str = str_replace('&nbsp;', ' ', $str);
    $str = str_replace('\u', '%u', $str);
    $str = str_replace('\r', ' ', $str);
    $str = str_replace('\n', '<br />', $str);
    $str = str_replace('\\', '', $str);
    $str = rawurldecode($str);
    $str = html_entity_decode($str, ENT_QUOTES);
    $str = html_entity_decode($str, ENT_QUOTES);
    preg_match_all("/%u.{4}|&#x.{4};|&#\d+;|.+/U", $str, $r);
    $ar = $r [0];

    foreach ($ar as $k => $v) {
        $codetype = 'UTF-8';
//        $codetype = 'GBK';
        if (substr($v, 0, 2) == "%u")
            $ar [$k] = iconv("UCS-2", $codetype, pack("H4", substr($v, - 4)));
        elseif (substr($v, 0, 3) == "&#x")
            $ar [$k] = iconv("UCS-2", $codetype, pack("H4", substr($v, 3, - 1)));
        elseif (substr($v, 0, 2) == "&#") {
            $ar [$k] = iconv("UCS-2", $codetype, pack("n", substr($v, 2, - 1)));
        }
    }
    return join("", $ar);
}

function getOS() {
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (strpos($agent, 'windows nt')) {
        $platform = 'Windows';
    } elseif (strpos($agent, 'macintosh')) {
        $platform = 'Mac';
    } elseif (strpos($agent, 'ipod')) {
        $platform = 'Ipod';
    } elseif (strpos($agent, 'ipad')) {
        $platform = 'Ipad';
    } elseif (strpos($agent, 'iphone')) {
        $platform = 'Iphone';
    } elseif (strpos($agent, 'android')) {
        $platform = 'Android';
    } elseif (strpos($agent, 'unix')) {
        $platform = 'Unix';
    } elseif (strpos($agent, 'linux')) {
        $platform = 'Linux';
    } else {
        $platform = 'Other';
    }
    return $platform;
}
