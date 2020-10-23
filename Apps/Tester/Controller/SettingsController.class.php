<?php

class SettingsController extends PublicController {
    
    private $settingsdb;
    
    public function __construct($data) {
        parent::__construct($data);
        $this->cache = $this->X('Cache');
        $this->settingsdb = $this->X('Filedb', 'settings');
    }
    
    public function setting(){
        $key = md5(doEncrypt('setting', __CODE_KEY__));
        $data = $this->settingsdb->get($key);
        $this->assign('data', $data);
        $this->assign('postUrl', U('Tester/Settings/doSetting'));
        $this->assign('isUpdate', 'true');
        $this->display();
    }
    
    public function doSetting(){
        $item = $this->p;
        $key = md5(doEncrypt('setting', __CODE_KEY__));
        if($this->settingsdb->set($key, $item)){
            die(json_encode(array('result' => true, 'msg' => '保存成功', 'returnUrl' => U('Tester/Settings/setting'))));
        }
        die(json_encode(array('result' => false, 'msg' => '保存失败', 'returnUrl' => U('Tester/Settings/setting'))));
    }
}
