<?php

class ItemsController extends PublicController {
    private $itemsdb;
    
    public function __construct($data) {
        parent::__construct($data);
        $this->cache = $this->X('Cache');
        $this->usersdb = $this->X('Filedb', 'users');
        $this->itemsdb = $this->X('Filedb', 'items');
    }
    
    public function itemList(){
        $data = $this->itemsdb->getAll();
        $this->assign('data', $data);
        $this->display();
    }
    
    public function itemAdd(){
        $users = $this->usersdb->getAll();
        $this->assign('users', $users);
        $this->assign('projectId', uniqid());//生成项目标识
        $this->assign('isUpdate', 'false');
        $this->assign('postUrl', U('Tester/Items/doItemAdd'));
        $this->assign('oprName', '增加项目');
        $this->display();
    }
    
    public function doItemAdd(){
        $item = $this->p;
        $key = md5(doEncrypt($item['itemtag'], __CODE_KEY__));
        
        if($this->itemsdb->isExists($key)){die(json_encode(array('result' => false, 'msg' => '该组已经存在')));}
        if($this->itemsdb->set($key, $item)){
            die(json_encode(array('result' => true, 'msg' => '保存成功', 'returnUrl' => U('Tester/Items/itemList'))));
        }
        die(json_encode(array('result' => false, 'msg' => '保存失败', 'returnUrl' => U('Tester/Items/itemList'))));
    }
    
    public function itemUpdate(){
        $users = $this->usersdb->getAll();
        $this->assign('users', $users);
        $itemtag = $this->g['itemtag'];
        $key = md5(doEncrypt($itemtag, __CODE_KEY__));
        $data = $this->itemsdb->get($key);
        $this->assign('data', $data);
        $this->assign('postUrl', U('Tester/Items/doItemUpdate'));
        $this->assign('isUpdate', 'true');
        $this->assign('oprName', '编辑项目');
        $this->assign('projectId', $itemtag);//生成项目标识
        $this->display('itemAdd');
    }
    
    public function doItemUpdate(){
        $item = $this->p;
        $key = md5(doEncrypt($item['itemtag'], __CODE_KEY__));
        if($this->itemsdb->set($key, $item)){
            die(json_encode(array('result' => true, 'msg' => '保存成功', 'returnUrl' => U('Tester/Items/itemList'))));
        }
        die(json_encode(array('result' => false, 'msg' => '保存失败', 'returnUrl' => U('Tester/Items/itemList'))));
    }
    
    public function doItemDelete(){
        $data = $this->p['data'];
        $result = array();
        foreach((array)$data as $k => $value){
            $key = md5(doEncrypt($value, __CODE_KEY__));
            $result[$value] = $this->itemsdb->delete($key);
        }
        die(json_encode(array('result' => true, 'msg' => '删除成功', 'returnUrl' => U('Tester/Items/itemList'))));
    }
}
