<?php

class UsersController extends PublicController {
    
    private $usersdb;
    private $itemsdb;

    public function __construct($data) {
        parent::__construct($data);
        $this->cache = $this->X('Cache');
        $this->usersdb = $this->X('Filedb', 'users');
        $this->itemsdb = $this->X('Filedb', 'items');
    }

    private function getItems(){
        $items = $this->itemsdb->getAll();
//        $items = $this->getLimitItems($items);
        $this->assign('items', $items);
        return $items;
    }
    
    public function login(){
        $settingsdb = $this->X('Filedb', 'settings');
        $key = md5(doEncrypt('setting', __CODE_KEY__));
        $settings = $settingsdb->get($key);
        $this->assign('sysname', $settings['sysname']);
        $this->assign('bgclass', $_COOKIE['bgclass']);
        $this->display();
    }
    
    public function doLogin(){
        $username = $this->p['username'];
        $password = md5($this->p['password']);
//        echo $password;exit;
        $verifyCode = $this->p['verifyCode'];
        if(strtolower(trim($verifyCode)) != strtolower(trim($_SESSION['verifyCode']))){die(json_encode(array('error' => 1, 'msg' => '图片验证码输入错误', 'result' =>false)));}
//        $password = $this->p['password']; //存储时时明文存储
        $usernameCode = doEncrypt($username, __CODE_KEY__);
        $key = md5($usernameCode);
//        echo $key;exit;
        $user = $this->usersdb->get($key);
        
        if(!empty($user)){
            if($password == $user['password']){
                $this->loginCookie->setExpire(time()+365 * 86400);
                $this->loginCookie->setCookie($usernameCode);
                die(json_encode(array('error' => 0, 'msg' => '登录成功', 'result' => true)));
            }
        }
        die(json_encode(array('error' => 1, 'msg' => '用户名或密码错误', 'result' =>false)));
//        return array('result' => false);
//        die(json_encode(json_encode($data)));
    }
    
    public function doExit(){
        $this->loginCookie->deleteCookie();
        $this->jump(U('Tester/Users/login'));
    }
    
    public function userList(){
        $data = $this->usersdb->getAll();
        $this->assign('data', $data);
        $this->assign('me', $this->user);
        $this->display();
    }
    
    public function userAdd(){
        $this->getItems();
        $this->assign('isUpdate', 'false');
        $this->assign('postUrl', U('Tester/Users/doUserAdd'));
        $this->assign('oprName', '增加用户');
        $this->display();
    }
    
    public function doUserAdd(){
        $user = $this->p;
        if(empty($user['password'])){$user['password'] = '12345678';}
        $user['password'] = md5($user['password']);
        $user['role'] = 1; //观察者（第一版只能设置观察者）
        $key = md5(doEncrypt($user['username'], __CODE_KEY__));
        if($this->usersdb->isExists($key)){die(json_encode(array('result' => false, 'msg' => '该用户已经存在')));}
        if($this->checkUnique('mobile', $user['mobile'])){die(json_encode(array('result' => false, 'msg' => '手机号已经被使用')));}
        if($this->checkUnique('email', $user['email'])){die(json_encode(array('result' => false, 'msg' => '邮箱已经被使用')));}
        if($this->usersdb->set($key, $user)){die(json_encode(array('result' => true, 'msg' => '保存成功', 'returnUrl' => U('Tester/Users/userList'))));}
        die(json_encode(array('result' => false, 'msg' => '保存失败', 'returnUrl' => U('Tester/Users/userList'))));
    }
    
    private function checkUnique($key, $value, $username = ''){
        $users = $this->usersdb->getAll();
        foreach($users as $k => $user){
            if(!empty($username)){
                if($user['username'] == $username){continue;}
            }
            if($user[$key] == $value){return true;}
        }
        return false;
    }


    public function userUpdate(){
        $this->getItems();
        $username = $this->g['username'];
        $key = md5(doEncrypt($username, __CODE_KEY__));
        echo $key;
        $data = $this->usersdb->get($key);
        $this->assign('data', $data);
        $this->assign('postUrl', U('Tester/Users/doUserUpdate'));
        $this->assign('isUpdate', 'true');
        $this->assign('oprName', '编辑用户');
        $this->display('userAdd');
    }
    
    public function doUserUpdate(){
        $user = $this->p;
        $key = md5(doEncrypt($user['username'], __CODE_KEY__));
        if(empty($user['password'])){
            $u = $this->usersdb->get($key);
            $user['password'] = $u['password'];
        }else{
            $user['password'] = md5($user['password']);
        }

        if($this->checkUnique('mobile', $user['mobile'], $user['username'])){die(json_encode(array('result' => false, 'msg' => '手机号已经被使用')));}
        if($this->checkUnique('email', $user['email'], $user['username'])){die(json_encode(array('result' => false, 'msg' => '邮箱已经被使用')));}
        
        if($this->usersdb->set($key, $user)){
            die(json_encode(array('result' => true, 'msg' => '保存成功', 'returnUrl' => U('Tester/Users/userList'))));
        }
        die(json_encode(array('result' => false, 'msg' => '保存失败', 'returnUrl' => U('Tester/Users/userList'))));
    }
    
    public function doUserDelete(){
        $data = $this->p['data'];
        $result = array();
        foreach((array)$data as $k => $value){
            if($value == $this->user['username']){continue;}
            $key = md5(doEncrypt($value, __CODE_KEY__));
            $user = $this->usersdb->get($key);
            if($user['founder'] == 'yes'){continue;}
            $result[$value] = $this->usersdb->delete($key);
        }
        die(json_encode(array('result' => true, 'msg' => '删除成功', 'returnUrl' => U('Tester/Users/userList'))));
    }
    
    public function forgot(){
        $settingsdb = $this->X('Filedb', 'settings');
        $key = md5(doEncrypt('setting', __CODE_KEY__));
        $settings = $settingsdb->get($key);
        $this->assign('sysname', $settings['sysname']);
        $this->display();
    }
    
    public function doForgot(){
        $email = $this->p['email'];
        $verifyCode = $this->p['verifyCode'];
        $users = $this->usersdb->getAll();
        $status = false;
        foreach($users as $k => $user){
            if($user['email'] == $email){$status = true;}
        }
        if(!$status){echo json_encode(array('error' => 1, 'msg' => '邮箱地址未在本系统中出现', 'result' => array('result' =>false)));exit;}
        if(strtolower(trim($verifyCode)) != strtolower(trim($_SESSION['verifyCode']))){echo json_encode(array('error' => 1, 'msg' => '图片验证码输入错误', 'result' => array('result' =>false)));exit;}
        $verify = rand(100000, 999999);
        $_SESSION['verify'] = $verify;
        $subject = 'INKPHP - 忘记密码 - 邮箱验证码';
        $body = '<html>'
                . '<head>'
                . '    <meta charset="UTF-8" />'
                . '    <title>忘记密码 - 验证码 - REST接口文档系统 - INKPHP</title>'
                . '    <style type="text/css">'
                . '        html, body{margin:0;padding:0;background:#ddd;}'
                . '        .main{margin:0 auto; width:800px;padding:50px 20px;}'
                . '        .main .content{background:#fff; padding:50px 20px; border-radius:10px; margin:20px 0;}'
                . '        .main .content .logo{background:rgba(0,0,0, 0.2); width:200px;height:100px; margin:0 auto;}'
                . '        .main .content .verify{text-align:center}'
                . '        .main .content .verify h1{text-align:center}'
                . '        .main .content .verify span{display:block; height:30px; line-height:30px; padding:10px;text-align:center}'
                . '        .main .content .verify em{padding:10px 30px;font-size:20px; background:#0f7d48;color:#FFF;border-radius:5px;font-style:normal;}'
                . '        .main h3{text-align:center;}'
                . '        .main .copyright{padding:20px 0; text-align:center;}'
                . '    </style>'
                . '</head>'
                . '<body>'
                . '    <div class="main">'
                . '        <div class="content">'
                . '            <div class="logo"></div>'
                . '            <div class="verify">'
                . '                <h1>邮箱验证码</h1>'
                . '                <span>复制以下验证码，完成密码修改</span>'
                . '                <em>'.$verify.'</em>'
                . '            </div>'
                . '        </div>'
                . '        <h3>声明：如果该邮件来自于{INKPHP - REST文档管理系统}，如果不是您主动发送，请不必理会即可。</h3>'
                . '        <div class="copyright">@copy; 2017 - 2020 INKPHP. 保留所有权利。</div>'
                . '    </div>'
                . '</body>'
                . '</html>';
        if($this->sendEmail($email, $subject, $body)){
            echo json_encode(array('error' => 0, 'msg' => '发送邮件成功', 'result' => array('result' =>true, 'email' => $email)));exit;
        }
        echo json_encode(array('error' => 1, 'msg' => '发送邮件失败', 'result' => array('result' =>false)));exit;
    }
    
    public function changePwd(){
        $email = $this->g['email'];
        $this->assign('email', $email);
        $this->display();
    }
    
    public function doChangePwd(){
        $email = $this->p['email'];
        $verify = $this->p['verify'];
        $password = $this->p['password'];
        $repassword = $this->p['repassword'];
        if(empty($email)){echo json_encode(array('error' => 1, 'msg' => '请您按照流程执行程序，忘记密码请先获取邮箱验证码', 'result' => array('result' =>false)));exit;}
        if(empty($verify)){echo json_encode(array('error' => 1, 'msg' => '请您输入正确的邮箱验证码', 'result' => array('result' =>false)));exit;}
        if(empty($password)){echo json_encode(array('error' => 1, 'msg' => '请您输入您的密码', 'result' => array('result' =>false)));exit;}
        if(empty($repassword)){echo json_encode(array('error' => 1, 'msg' => '请您输入您的确认密码', 'result' => array('result' =>false)));exit;}
        if($repassword != $password){echo json_encode(array('error' => 1, 'msg' => '两次输入的密码不一致，请您重新输入密码和确认密码', 'result' => array('result' =>false)));exit;}
        if($verify != $_SESSION['verify']){echo json_encode(array('error' => 1, 'msg' => '邮箱验证码错误，请您重新输入', 'result' => array('result' =>false)));exit;}
        $users = $this->usersdb->getAll();
        foreach($users as $k => $user){
            if($user['email'] == $email){break;}
        }
        $user['password'] = md5($password);
        $key = md5(doEncrypt($user['username'], __CODE_KEY__));
        $this->usersdb->set($key, $user);
        echo json_encode(array('error' => 0, 'msg' => '修改密码成功', 'result' => array('result' =>true)));
    }
}