<?php

class IndexController extends PublicController {
    
    private $tmpuserdb;
    
    public function __construct($data) {
        parent::__construct($data);
        $this->tmpusersdb = $this->X('Filedb', 'tmpusers');
        $this->settingsdb = $this->X('Filedb', 'settings');
    }
    
    /*协议页面*/
    public function index(){
        $this->display();
    }
    
    /*环境(含权限)检查*/
    public function stepFirst(){
        $servers = explode(' ', $_SERVER['SERVER_SOFTWARE']);
        $os = getOS();
        $systems = array('Windows', 'Linux', 'Unix');
        $webservers = array('Apache', 'Nginx');
        $webserver = explode('/', $servers[0]);
        $phps = 5.0;
        $php = explode('/', $servers[3]);
        $v = (float)$php[1];
        $envirs = array(
            array('操作系统', $systems, $os, in_array($os, $systems)),
            array('WEB服务器', array('Apache', 'Nginx'), $webserver[0]. ' '. $webserver[1], in_array($webserver[0], $webservers)),
            array('PHP版本', array($phps), str_replace('/', ' ',$servers[3]), $v >= $phps),
        );
        $rdirs = array(
            array('./Data', ' 可读'),
            array('./Data/caches', '可读'),
            array('./Data/filedb', '可读'),
            array('./Data/filedb/settings', '可读'),
        );
        $wdirs = array(
            array('./Data', '可写'),
            array('./Data/caches', '可写'),
            array('./Data/filedb', '可写'),
            array('./Data/filedb/settings', '可写'),
        );
        foreach($rdirs as $k => $v){$rdirs[$k][] = $this->readable($v[0], $os);}
        foreach($wdirs as $k => $va){$wdirs[$k][] = $this->writeable($va[0], $os);}
        $assemblies = array(
            array('CURL', '支持', function_exists('curl_init') ? '<input type="hidden" value="'.function_exists('curl_init').'" /><span class="text-success"><i class="fa fa-check"></i> 支持</span>' : '<span class="text-danger"><i class="fa fa-remove"></i> 不支持</span>'),
            array('GD2', '支持', function_exists('imagegd') ? '<input type="hidden" value="'.function_exists('imagegd').'" /><span class="text-success"><i class="fa fa-check"></i> 支持</span>' : '<span class="text-danger"><i class="fa fa-remove"></i> 不支持</span>'),
            array('MB_STRING', '支持', function_exists('mb_strlen') ? '<input type="hidden" value="'.function_exists('mb_strlen').'" /><span class="text-success"><i class="fa fa-check"></i> 支持</span>' : '<span class="text-danger"><i class="fa fa-remove"></i> 不支持</span>'),
        );
        
        $this->assign('assemblies', $assemblies);
        $this->assign('rdirs', $rdirs);
        $this->assign('wdirs', $wdirs);
        $this->assign('envirs', $envirs);
        $this->display();
    }
    
    public function saveConfigure() {
        $item = $this->p;
        $key = md5(doEncrypt('setting', __CODE_KEY__));
        $this->copyUsers();
        if($this->settingsdb->set($key, $item)){
            die(json_encode(array('result' => true, 'msg' => '保存成功', 'returnUrl' => U('Install/Index/complete'))));
        }
        $this->installLock();
        //删除安装文件
        die(json_encode(array('result' => false, 'msg' => '保存失败')));
    }
    
    private function installLock(){
        return @file_put_contents('./Data/install.lock', 'true');
    }

    private function copyUsers(){
        $path = './Data/filedb/tmpusers';
        $dest = './Data/filedb/users';
        $handler = opendir($path);
        while($file = readdir($handler)){
            if($file != '.' && $file != '..'){
                @copy($path.'/'.$file, $dest.'/'.$file);
                @unlink($path.'/'.$file);
            }
        }
        return @rmdir($path);
    }


    public function saveUser(){
        $user = $this->p;
        if(empty($user['password'])){$user['password'] = '12345678';}
        $user['password'] = md5($user['password']);
        $user['role'] = 0; //观察者（第一版只能设置观察者）
        $key = md5(doEncrypt($user['username'], __CODE_KEY__));
        if($this->tmpusersdb->isExists($key)){$this->tmpusersdb->delete($key);}
        if($this->checkUnique('mobile', $user['mobile'])){$this->tmpusersdb->delete($key);}
        if($this->checkUnique('email', $user['email'])){$this->tmpusersdb->delete($key);}
        if($this->tmpusersdb->set($key, $user)){die(json_encode(array('result' => true, 'msg' => '保存成功', 'returnUrl' => U('Install/Index/stepThird'))));}
        die(json_encode(array('result' => false, 'msg' => '保存失败')));
    }
    
    private function checkUnique($key, $value, $username = ''){
        $users = $this->tmpusersdb->getAll();
        foreach($users as $k => $user){
            if(!empty($username)){
                if($user['username'] == $username){continue;}
            }
            if($user[$key] == $value){return true;}
        }
        return false;
    }


    private function readable($path, $os){
        if($os == 'windows'){
            $dir = @opendir($path);
            return readdir($dir) ? '<input type="hidden" value="'.readdir($dir).'" /><span class="text-success"><i class="fa fa-check"></i> 可读</span>' : '<span class="text-danger"><i class="fa fa-remove"></i> 不可读</span>';
        }else{
            return is_readable($path) ? '<input type="hidden" value="'.is_readable($path).'" /><span class="text-success"><i class="fa fa-check"></i> 可读</span>' : '<span class="text-danger"><i class="fa fa-remove"></i> 不可读</span>';
        }
    }
    
    private function writeable($path, $os){
        if($os == 'windows'){
            $test_file = $path . '/test.txt';
            $s = @file_put_contents($test_file, 'directory access testing.');
            return $s ? '<input type="hidden" value="'.$s.'" /><span class="text-success"><i class="fa fa-check"></i> 可写</span>' : '<span class="text-danger"><i class="fa fa-remove"></i> 不可写</span>';
        }else{
            return is_writable($path) ? '<input type="hidden" value="'.is_writable($path).'" /><span class="text-success"><i class="fa fa-check"></i> 可写</span>' : '<span class="text-danger"><i class="fa fa-remove"></i> 不可写</span>';
        }
    }


    /*设置超级账户*/
    public function stepSecond(){
        $this->display();
    }
    
    /*设置第一个项目*/
    public function stepThird(){
        $data = $this->g();
        print_r($data);
        $this->display();
    }
    
    /*完成安装*/
    public function complete(){
        $this->display();
    }
}