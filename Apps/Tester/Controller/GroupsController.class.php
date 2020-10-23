<?php

class GroupsController extends PublicController {
    
    private $groupsdb;
    private $itemsdb;
    
    public function __construct($data) {
        parent::__construct($data);
        $this->cache = $this->X('Cache');
        $this->groupsdb = $this->X('Filedb', 'groups');
        $this->usersdb = $this->X('Filedb', 'users');
        $this->itemsdb = $this->X('Filedb', 'items');
    }
    
    public function groupList(){
        $data = $this->groupsdb->getAll();
        $this->assign('data', $data);
        $this->display();
    }
    
    public function groupAdd(){
        $items = $this->getItems();
        $this->assign('items', $items);
        $users = $this->usersdb->getAll();
        $this->assign('users', $users);
        $this->assign('isUpdate', 'false');
        $this->assign('postUrl', U('Tester/Groups/doGroupAdd'));
        $this->assign('oprName', '增加用户');
        $this->display();
    }
    
    public function doGroupAdd(){
        $group = $this->p;
        $key = md5(doEncrypt($group['groupname'], __CODE_KEY__));
        if($this->groupsdb->isExists($key)){die(json_encode(array('result' => false, 'msg' => '该组已经存在')));}
        if($this->groupsdb->set($key, $group)){
            die(json_encode(array('result' => true, 'msg' => '保存成功', 'returnUrl' => U('Tester/Groups/groupList'))));
        }
        die(json_encode(array('result' => false, 'msg' => '保存失败', 'returnUrl' => U('Tester/Groups/groupList'))));
    }
    
    public function groupUpdate(){
        $items = $this->getItems();
        $this->assign('items', $items);
        $users = $this->usersdb->getAll();
        $this->assign('users', $users);
        $groupname = urldecode($this->g['groupname']);
        $key = md5(doEncrypt($groupname, __CODE_KEY__));
        $data = $this->groupsdb->get($key);
        $this->assign('data', $data);
        $this->assign('postUrl', U('Tester/Groups/doGroupUpdate'));
        $this->assign('isUpdate', 'true');
        $this->assign('oprName', '编辑用户');
        $this->display('groupAdd');
    }
    
    public function doGroupUpdate(){
        $group = $this->p;
        $key = md5(doEncrypt($group['groupname'], __CODE_KEY__));
        if($this->groupsdb->set($key, $group)){
            die(json_encode(array('result' => true, 'msg' => '保存成功', 'returnUrl' => U('Tester/Groups/groupList'))));
        }
        die(json_encode(array('result' => false, 'msg' => '保存失败', 'returnUrl' => U('Tester/Groups/groupList'))));
    }
    
    
    public function doGroupDelete(){
        $data = $this->p['data'];
        $result = array();
        foreach((array)$data as $k => $value){
            $key = md5(doEncrypt($value, __CODE_KEY__));
            $result[$value] = $this->groupsdb->delete($key);
        }
        die(json_encode(array('result' => true, 'msg' => '删除成功', 'returnUrl' => U('Tester/Groups/groupList'))));
    }
    
    private function getItems(){
        $data = $this->itemsdb->getAll();
        foreach($data as $key => $item){
            $data[$key]['classes'] = $this->getClasses($item);
        }
//        print_r($data);
        return $data;
    }
    
    private function getClasses($item){
        $files = $this->getItemFiles($item['itemDirs']);
        $classes = array();
        foreach($files as $k => $file){
            $c = $this->getClass($file);
            if(empty($c)){continue;}
            $classes[] = $c;
        }
        return $classes;
    }
    
    private function getItemFiles($dirs){
        $files = [];
        foreach($dirs as $k => $path){
            $fs = $this->getFiles($path);
            $files = array_merge($files, $fs);
        }
        return $files;
    }
    
    private function getFiles($path){
        $path = str_replace('\\\\', '\\', $path);
        $handler = opendir($path);
        $files = array();
        while($file = readdir($handler)){
            if($file != '.' && $file != '..'){
                $files[] = $path.'\\'.$file;
            }
        }
        return $files;
    }


    private function getClass($file){
        $content = file_get_contents($file);
        $data = $this->getClassInfo($content);
        $d = array();
        if(empty($data['class'])){
            unset($data);
        }else{
            foreach($data['class'] as $key => $value){$data[$key] = $value;}
            unset($data['class']);
        }
        return $data;
    }
    
    private function getClassInfo($content){
        $data = explode('*/', $content);
        $classInfo = array();
        $funcInfos = array();
        foreach($data as $key => $value){
            if($this->isExists('@classaccess', $value) && $this->isExists('public', $value)){
                $values = explode("/**", $value);
                $classInfo = explode("\n", $values[1]);
            }elseif($this->isExists('@access', $value) && $this->isExists('public', $value)){
                $values = explode("/**", $value);
                $funcInfo = explode("\n", $values[1]);
                $funcInfos[] = $this->processFunc($funcInfo);
            }
        }
        $d['class'] = $this->processClass($classInfo);
        $d['funcs'] = $funcInfos;
        return $d;
    }
    
    public function processFunc($data){
        $d = array();
        foreach($data as $k => $v){
            $v = str_replace('*', '', $v);
            $v = str_replace('@', '', $v);
            $v = trim($v);
            if($v != ''){
                $ds = explode(' ', $v);
                $s = explode('|', $ds[1]);
                if(count($s) == 1){$s = $ds[1];}
                $d[$ds[0]] = $s;
            }
        }
        return $d;
    }


    public function processClass($data){
        $d = array();
        foreach($data as $k => $v){
            $v = str_replace('* @', '', $v);
            $v = str_replace('*', '', $v);
            $v = str_replace('\r', '', $v);
            $v = trim($v);
            if($v != ''){
                $ds = explode(' ', $v);
                $d[$ds[0]] = $ds[1];
            }
        }
        return $d;
    }

    private function getFuncs($content){
        
    }
    
    private function isExists($search, $str){
        $s = str_replace($search, '', $str);
        return $s != $str;
    }
}


//Array ( 
//    [0] => Array ( 
//        [itemtag] => test2 
//        [itemname] => 测试项目二 
//        [itemDirs] => Array ( [0] => ) 
//        [filepre] => 
//        [fileback] => Controller 
//        [devurl] => http://dev.ink.com 
//        [testurl] => http://test.ink.com 
//        [uaturl] => http://uat.ink.com 
//        [produrl] => http://www.ink.com 
//        [username] => zhangxugang810 
//        [classes] => Array ( ) 
//    ) 
//    [1] => Array ( 
//        [itemtag] => test1 
//        [itemname] => 测试项目一 
//        [itemDirs] => Array ( [0] => D:\\xampp\\htdocs\\inkcms1.0\\Apps\\Home\\Controller ) 
//        [filepre] => 
//        [fileback] => Controller 
//        [devurl] => http://dev.ink.com 
//        [testurl] => http://test.ink.com 
//        [uaturl] => http://uat.ink.com 
//        [produrl] => http://www.ink.com 
//        [username] => zhangxugang810 
//        [classes] => Array ( 
//            [0] => Array ( 
//                [class] => Array ( 
//                    [0] => Array ( [classname] => 测试类 ) 
//                    [1] => Array ( [classaccess] => public ) 
//                    [2] => Array ( [decription] => 测试类 ) 
//                    [3] => Array ( [author] => 张旭刚 ) 
//                    [4] => Array ( [updateTime] => 2016年9月3日 )
//                ) 
    //            [funcs] => Array ( 
    //                [0] => Array ( 
    //                    [0] => Array ( [see] => 测试函数 ) 
    //                    [1] => Array ( [access] => public ) 
    //                    [2] => Array ( [name] => index ) 
    //                    [3] => Array ( [method] => POST ) 
    //                    [4] => Array ( [requestType] => FORM ) 
    //                    [5] => Array ( [defget] => no ) 
    //                    [6] => Array ( [header] => Array ( [0] => abc [1] => int [2] => unrequired ) ) 
    //                    [7] => Array ( [baseAuth] => Array ( [0] => aaa [1] => string [2] => required ) ) 
    //                    [8] => Array ( [param] => Array ( [0] => page [1] => int [2] => unrequired [3] => 页码 ) ) 
    //                    [9] => Array ( [param] => Array ( [0] => picid [1] => int [2] => required [3] => 图片id ) ) 
    //                    [10] => Array ( [return] => Array ( [0] => user_id [1] => int [2] => 用户id ) )
    //                    [11] => Array ( [return] => Array ( [0] => users [1] => table [2] => users [3] => [4] => 说明 ) ) 
    //                    [12] => Array ( [return] => Array ( [0] => users [1] => array [2] => 说明 ) ) 
    //                    [13] => Array ( [return_users] => Array ( [0] => id [1] => int [2] => 编号 ) ) 
    //                ) 
    //                [1] => Array ( 
    //                    [0] => Array ( [see] => 测试函数 ) 
    //                    [1] => Array ( [access] => public ) 
    //                    [2] => Array ( [name] => index ) 
    //                    [3] => Array ( [method] => POST ) 
    //                    [4] => Array ( [requestType] => FORM ) 
    //                    [5] => Array ( [defget] => no ) 
    //                    [6] => Array ( [header] => Array ( [0] => abc [1] => int [2] => unrequired ) ) 
    //                    [7] => Array ( [baseAuth] => Array ( [0] => aaa [1] => string [2] => required ) ) 
    //                    [8] => Array ( [param] => Array ( [0] => page [1] => int [2] => unrequired [3] => 页码 ) ) 
    //                    [9] => Array ( [param] => Array ( [0] => picid [1] => int [2] => required [3] => 图片id ) ) 
    //                    [10] => Array ( [return] => Array ( [0] => user_id [1] => int [2] => 用户id ) ) 
    //                    [11] => Array ( [return] => Array ( [0] => users [1] => table [2] => users [3] => [4] => 说明 ) ) 
    //                    [12] => Array ( [return] => Array ( [0] => users [1] => array [2] => 说明 ) ) 
    //                    [13] => Array ( [return_users] => Array ( [0] => id [1] => int [2] => 编号 ) ) 
    //                )
    //            )
    //        )
    //    )
    //)
//) 