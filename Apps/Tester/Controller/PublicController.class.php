<?php
/*
 * 
                        _ooOoo_
                       o8888888o
                       88" . "88
                       (| -_- |)
                       O\  =  /O
                    ____/`---'\____
                  .'  \\|     |//  `.
                 /  \\|||  :  |||//  \
                /  _||||| -:- |||||-  \
                |   | \\\  -  /// |   |
                | \_|  ''\---/''  |   |
                \  .-\__  `-`  ___/-. /
              ___`. .'  /--.--\  `. . __
           ."" '<  `.___\_<|>_/___.'  >'"".
          | | :  `- \`.;`\ _ /`;.`/ - ` : | |
          \  \ `-.   \_ __\ /__ _/   .-` /  /
     ======`-.____`-.___\_____/___.-`____.-'======
                        `=---='
     ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
              佛祖保佑       永无BUG
 * 
 */
class PublicController extends Controller {

    protected $systemoption = array();
    protected $file;
    protected $user;
    protected $loginCookie;

    public function __construct($data) {
        parent::__construct($data);
        if(!file_exists('./Data/install.lock')){
            header('Location:'.U('Install/Index/index'));
        }
        $this->file = $this->X('File');
        $this->loginCookie = $this->X('Cookie', 'DSEGDESDE');
        $this->checkLogin();
        $this->checkLimit();
    }
    
    private function checkLimit(){
        if($this->user['role'] == 1){
            $act = MODEL_NAME. '/' .ACTION_NAME;
            $noLimits = core::getConfig('noLimits');
            if(in_array($act, $noLimits)){
                echo '您没有权限噢！';
                die;
            }
        }
    }

    private function checkLogin() {
        $usernameCode = $this->loginCookie->getCookie();
        $nologins = core::getConfig('nologins');
        $userdb = $this->X('Filedb', 'users');
        $this->user = $userdb->get(md5($usernameCode));
        unset($this->user['password']);
        if (!empty($this->user)) {
            $this->user['groups'] = $this->getGroup($this->user['username']);
            $this->assign('user', $this->user);
        }
        $act = MODEL_NAME . '/' . ACTION_NAME;
        if (empty($this->user) && !in_array($act, $nologins)) {
            $url = U('Tester/Users/login');
            $this->jump($url);
        }
    }

    public function getGroup($username) {
        $groupdb = $this->X('Filedb', 'groups');
        $data = $groupdb->getAll();
        $groups = array();
        foreach ((array) $data as $key => $group) {
            if (in_array($username, $group['groupUsers'])) {
                $groups[] = $group;
            }
        }
        return $groups;
    }

    public function settingFieldType($str) {
        return 'string'; //因为php将数据库的值查出来，会将所有的值类型变成string类型，所有暂时这里先不用转换（这一点太扯淡啦！（- -））
        if (isExistsStr($str, '(')) {
            $temp = explode('(', $str);
            $str = $temp[0];
        }
        $str = strtoupper($str);
        $str_arr = array('CHAR', 'VARCHAR', 'TEXT', 'TINYBLOB', 'TINYTEXT', 'BLOB', 'ENUM', 'SET');
        $int_arr = array('INT', 'TINYINT', 'SMALLINT', 'MEDIUMINT', 'INTEGER');
        $float_arr = array('FLOAT', 'DOUBLE', 'DECIMAL');
        if (in_array($str, $str_arr)) {
            return 'string';
        } else if (in_array($str, $int_arr)) {
            return 'int';
        } else if (in_array($str, $float_arr)) {
            return 'float';
        } else {
            return 'Unknow type';
        }
    }

    protected function curl_get($url) {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
//        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_HEADER, "");
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return ($data);
    }

    //参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
    function curl_post($url, $d = '', $cookie = '', $returnCookie = 0) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://xxx");

        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($d));
        }
        if ($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if ($returnCookie) {
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie'] = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        } else {
            return $data;
        }
    }

    function curlRequest($url, $method = 'GET', $rtype = 'FORM', $d = '', $cookie = '', $returnCookie = 0) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://xxx");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        switch ($method) {
            case 'GET' :
                $requestData = http_build_query($d);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $requestData);
                break;
            case 'POST' :
                if ($rtype == 'FORM') {
                    $requestData = http_build_query($d);
                } else {
                    $requestData = $d;
                }
                curl_setopt($curl, CURLOPT_POSTFIELDS, $requestData);
                break;
            case 'PUT':
                if ($rtype == 'FORM') {
                    $requestData = http_build_query($d);
                } else {
                    $requestData = $d;
                }
                curl_setopt($curl, CURLOPT_POSTFIELDS, $requestData);
                break;
            case 'PATCH':
                if ($rtype == 'FORM') {
                    $requestData = http_build_query($d);
                } else {
                    $requestData = $d;
                }
                curl_setopt($curl, CURLOPT_POSTFIELDS, $requestData);
                break;
            case 'DELETE':
                $requestData = http_build_query($d);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $requestData);
                break;
            case 'HEAD':
                $requestData = http_build_query($d);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $requestData);
                break;
            case 'JSONP':
                $requestData = http_build_query($d);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $requestData);
                break;
            default :
                if ($rtype == 'FORM') {
                    $requestData = http_build_query($d);
                } else {
                    $requestData = $d;
                }
                curl_setopt($curl, CURLOPT_POSTFIELDS, $requestData);
                break;
        }

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($d));
        }
        if ($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if ($returnCookie) {
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie'] = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        } else {
            return $data;
        }
    }

    protected function sendEmail($to_mail, $subject = 'test', $body = 'test', $cc = '', $fromname = 'INKPHP', $from_email = 'zhangxugang810@163.com', $attachment = null) {
        $settingdb = $this->X('Filedb', 'settings');
        $key = md5(doEncrypt('setting', __CODE_KEY__));
        $conf = $settingdb->get($key);
        $mail = $this->X('Mailer');
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $conf['mailServerHost'];
        $mail->CharSet = $conf['mailCharSet'];
        $mail->FromName = $conf['mailFromName'];
        $mail->Username = $conf['mailServerUsername'];
        $mail->Password = $conf['mailServerPassword'];
        $mail->From = $from_email;
        $mail->isHTML(true);
        $mail->addAddress($to_mail);
        if(!empty($cc)){$mail->AddCC($cc);}
        $mail->Subject = $subject;
        $mail->Body = $body;
        if(!empty($attachment)){
            $files = explode(',', $attachment);
            foreach($files as $k => $v){$mail->addAttachment($v);}
        }
        $status = $mail->send();
        return $status ? true : false;
    }
    
    protected function showVerify(){
        $verify = $this->X('Code');
        return $verify->showImage();
    }

}
