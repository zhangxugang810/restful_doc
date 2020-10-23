<?php
class Html {
    public function __construct() {}
    /**
     * 
     * @param type $data
     * @return string
     */
    private function input($data){
        foreach((array)$data as $key => $value){
            $str = $key.'="'.$value.'" ';
        }
        $str = '<input '.$str.'/>';
        return $str;
    }
    /**
     * 
     * @param type $data
     * @return string
     */
    public function textarea($data){
        $v = $data['value'];
        unset($data['value']);
        foreach((array)$data as $key => $value){
            $str = $key.'="'.$value.'" ';
        }
        $str = '<textarea '.$str.'>'.$v.'</textarea>';
    }
    /**
     * 单行文本
     * @param type $name
     * @param type $id
     * @param type $class
     * @param type $defaultValue
     * @return type
     */
    public function text($name, $id, $class, $defaultValue = ''){
        $data['name'] = $name;
        $data['id'] = $id;
        $data['value'] = $defaultValue;
        $data['type'] = 'text';
        $data['class'] = $class;
        $str = $this->input($data);
        return $str;
    }
    
    /**
     * 单选框
     * @param type $name
     * @param type $id
     * @param type $defaultValues
     * @return string
     */
    public function radio($name, $id, $defaultValues = array()){
        foreach((array)$defaultValues as $key => $value){
            $data['name'] = $name;
            $data['id'] = $id;
            $data['value'] = $value['value'];
            $data['type'] = 'radio';
            $str .= $this->input($data). $value['text']."\n";
        }
        return $str;
    }
    
    /**
     * 复选框
     * @param type $name
     * @param type $id
     * @param type $defaultValues
     * @return string
     */
    public function checkbox($name, $id, $defaultValues = array()){
        foreach((array)$defaultValues as $key => $value){
            $data['name'] = $name;
            $data['id'] = $id;
            $data['value'] = $value['value'];
            $data['type'] = 'checkbox';
            $str .= $this->input($data). $value['text']."\n";
        }
        return $str;
    }
    
    /**
     * 隐藏域
     * @param type $name
     * @param type $id
     * @param type $defaultValue
     * @return type
     */
    public function hidden($name, $id, $defaultValue = ''){
        $data['name'] = $name;
        $data['id'] = $id;
        $data['value'] = $defaultValue;
        $data['type'] = 'hidden';
        $str = $this->input($data);
        return $str;
    }
    
    /**
     * 下来表单
     * @param type $name
     * @param type $id
     * @param type $defaultValues
     * @return string
     */
    public function select($name, $id, $defaultValues = array()){
        $str = '<select name="'.$name.'" id="'.$id.'">';
        foreach((array)$defaultValues as $key => $value){
            $str .= '<option value="'.$value['value'].'">'.$value['text'].'</option>';
        }
        $str .= '</select>';
        return $str;
    }
    
    /**
     * 单个图片
     * @param type $name
     * @param type $id
     * @param type $class
     * @param type $defaultValue
     * @return type
     */
    public function pic($name, $id, $class, $defaultValue = ''){
        $data['name'] = $name;
        $data['id'] = $id;
        $data['value'] = $defaultValue;
        $data['type'] = 'text';
        $data['class'] = $class;
        $str = $this->input($data);
        unset($data);
        $data['name'] = $name.'_btn';
        $data['id'] = $id.'_btn';
        $data['type'] = 'button';
        $data['value'] = '上传图片';
        $data['class'] = $class.'_btn';
        $str .= $this->input($data);
        return $str;
    }
    
    /**
     * 多个图片
     * @return string
     */
    public function pics(){
        $str = '<a href="javascript:void(0);" id="uploadPics">上传多张图片</a><div id="picsFormList"></div>';
        return $str;
    }
    
    /**
     * 附件
     * @param type $name
     * @param type $id
     * @param type $class
     * @param type $defaultValue
     * @return type
     */
    public function attach($name, $id, $class, $defaultValue = ''){
        $data['name'] = $name;
        $data['id'] = $id;
        $data['value'] = $defaultValue;
        $data['type'] = 'text';
        $data['class'] = $class;
        $str = $this->input($data);
        unset($data);
        $data['name'] = $name.'_btn';
        $data['id'] = $id.'_btn';
        $data['type'] = 'button';
        $data['value'] = '上传附件';
        $data['class'] = $class.'_btn';
        $str .= $this->input($data);
        return $str;
    }
    
    /**
     * 多个附件
     * @return string
     */
    public function attaches(){
        $str = '<a href="javascript:void(0);" id="uploadAttaches">上传多个附件</a><div id="attachesFormList"></div>';
        return $str;
    }
    
    /**
     * 普通按钮
     * @param type $name
     * @param type $id
     * @param type $class
     * @param type $defaultValue
     */
    public function button($name, $id, $class, $defaultValue = ''){
        $data['name'] = $name;
        $data['id'] = $id;
        $data['value'] = $defaultValue;
        $data['type'] = 'button';
        $data['class'] = $class;
        $str = $this->input($data);
    }
    
    /**
     * 提交按钮
     * @param type $name
     * @param type $id
     * @param type $class
     * @param type $defaultValue
     */
    public function submit($name, $id, $class, $defaultValue = ''){
        $data['name'] = $name;
        $data['id'] = $id;
        $data['value'] = $defaultValue;
        $data['type'] = 'submit';
        $data['class'] = $class;
        $str = $this->input($data);
    }
    
    /**
     * 图像域
     * @param type $name
     * @param type $id
     * @param type $class
     * @param type $defaultValue
     */
    public function imageButton($name, $id, $class, $defaultValue = ''){
        $data['name'] = $name;
        $data['id'] = $id;
        $data['value'] = $defaultValue;
        $data['type'] = 'image';
        $data['class'] = $class;
        $str = $this->input($data);
    }
    
    /**
     * 表格
     * @param type $class
     * @param type $data
     * @param type $headers
     * @return string
     */
    public function table($class,$data = array(), $headers = array()){
        if(empty($headers) || empty($data)){
            return ;
        }
        $str = '<table class="'.$class.'">';
        $str .= '<tr>';
        foreach((array)$headers as $k => $v){
            $str .='<th class="'.$k.'">'.$v.'</th>';
        }
        foreach((array)$data as $key => $d){
            $str .= '<tr num="cols_'.$key.'">';
                foreach((array)$d as $ks => $vs){
                    $str .='<td class="'.$ks.'">'.$vs.'</td>';
                }
            $str .= '</tr>';
        }
        $str .= '</tr>';
        $str .='</table>';
        return $str;
    }
    
    /**
     * 搜索框
     */
    public function search(){
        
    }
    
    /**
     * 编辑器
     */
    public function editor(){
        
    }
}