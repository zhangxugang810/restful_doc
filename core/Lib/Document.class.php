<?php

header("Content-Type: text/html; charset=UTF-8");
include "phpword/PHPWord.php";
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Document
 *
 * @author ikang
 */
class Document {

    private $filePath = './Document/';
    private $ikangFilePath = './DocumentIkang/';
    private $tplVar;
    
    public function __construct() {
        $this->tplVar = array();
    }
    
    public function createIkangWord($fileName){
        if (!file_exists($this->ikangFilePath)) {
            @mkdir($this->ikangFilePath);
        }
        $PHPWord = new PHPWord();
        $PHPWord->setDefaultFontName('仿宋'); // 全局字体
        $PHPWord->setDefaultFontSize(9);     // 全局字号为3号
        $section = $PHPWord->createSection(); //创建新页面
        $styleTable = array('borderSize'=>6, 'borderColor'=>'000000', 'cellMargin'=>80, 'width' => 9200, 'fontSize' => 12);
        $styleFirstRow = array('borderBottomSize'=>18, 'borderBottomColor'=>'0000FF', 'bgColor'=>'66BBFF');
        $PHPWord->addTableStyle('default', $styleTable, $styleFirstRow);
        $PHPWord->addTitleStyle(1, array('size'=>20, 'color'=>'333333', 'bold'=>true));
        $section->addTitle($this->tplVar['desc']['see'].'（'.strtolower($this->tplVar['controller']).'/'.$this->tplVar['funcname'].'）', 1);
        $section->addText('');
        $envir = [];//环境
        foreach((array)$this->tplVar['envir'] as $k => $v){
            $envir[$k] = [str_replace('环境','',$v['name']), $v['url']];
        }
        
        $headers = [];//header参数
        foreach($this->tplVar['desc']['header'] as $key => $value){
            $headers[] = [$value[0], ($value[2] == 'required') ? 'true' : 'false', $value[1], $value[3].(!empty($value[4]) ? '&nbsp('.$value[4].')' : '')];
        }
        $params = [];//参数
        foreach($this->tplVar['desc']['param'] as $key => $value){
            $params[] = [$value[0], ($value[2] == 'required') ? 'true' : 'false', $value[1], $value[3].(!empty($value[4]) ? '&nbsp('.$value[4].')' : '')];
        }
        
        //返回参数
        
        $this->setIkangTable($section, $this->tplVar['desc']['see'], $envir, $headers, $params, $this->tplVar['desc']['requestType'], $this->tplVar['desc']['method']);
        
        
        $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $objWriter->save($this->ikangFilePath.$fileName.'.docx');
        return $this->ikangFilePath.$fileName.'.docx';
    }

    /**
     * @desc 方法一、生成word文档
     * @param $content
     * @param string $fileName
     */
    public function createWord($fileName) {
        if (!file_exists($this->filePath)) {
            @mkdir($this->filePath);
        }
        $PHPWord = new PHPWord();
        $section = $PHPWord->createSection(); //创建新页面
        $styleTable = array('borderSize'=>6, 'borderColor'=>'000000', 'cellMargin'=>80, 'width' => 9200);
        $styleFirstRow = array('borderBottomSize'=>18, 'borderBottomColor'=>'0000FF', 'bgColor'=>'66BBFF');
        $PHPWord->addTableStyle('default', $styleTable, $styleFirstRow);
        $PHPWord->addTitleStyle(1, array('size'=>20, 'color'=>'333333', 'bold'=>true));
        $section->addTitle($this->tplVar['desc']['see'].'（'.strtolower($this->tplVar['controller']).'/'.$this->tplVar['funcname'].'）', 1);
        $envir = [];
        foreach((array)$this->tplVar['envir'] as $k => $v){
            $envir[] = str_replace('环境','',$v['name']).'环境：'.$v['url'];
        }
        $this->setItems($section, $envir);
        $section->addText($this->tplVar['desc']['describe']);
        $section->addText('');
        
        $section->addTitle('HEADER参数', 1);
        $section->addText('');
        $tableData = [];
        foreach($this->tplVar['desc']['header'] as $key => $value){
            $tableData[] = [$value[0], ($value[2] == 'required') ? 'true' : 'false', $value[1], $value[3].(!empty($value[4]) ? '&nbsp('.$value[4].')' : '')];
        }
        $this->setTable($section, $tableData, ['参数名','必选','类型','说明'],[1500, 800, 800, 6100]);
        $section->addText('');
        
        $section->addTitle('HTTP提交方式', 1);
        $section->addText('');
        $httpgo = empty($this->tplVar['desc']['method']) ? 'POST' : strtoupper($this->tplVar['desc']['method']).'发送'.$this->tplVar['desc']['requestType'].'数据';
        $section->addText($httpgo);
        $section->addText('');
        $section->addText('');
        
        
        $section->addTitle('请求参数', 1);
        $section->addText('');
        $paramData = [];
        foreach($this->tplVar['desc']['header'] as $key => $value){
            $paramData[] = [$value[0], ($value[2] == 'required') ? 'true' : 'false', $value[1], $value[3].(!empty($value[4]) ? '&nbsp('.$value[4].')' : '')];
        }
        $this->setTable($section, $paramData, ['参数名','必选','类型','说明'],[1500, 800, 800, 6100]);
        $section->addText('');
        
        $section->addTitle('返回参数基本格式', 1);
        $section->addText('');
        $bData = [];
        foreach((array)$this->tplVar['formats'] as $k => $v){
            $bData[] = [$v[0], $v[1], $v[2]];
        }
        $this->setTable($section, $bData, ['参数名','类型','说明'],[1500, 800, 6900]);
        $section->addText('');
        
        $section->addTitle('返回参数（'.trim($bData[2][0]).'）', 1);
        $section->addText('');
        if(!empty($this->tplVar['desc']['return'])){
            if(empty($this->tplVar['desc']['return'][0]) && isset($this->tplVar['desc']['return'][0]) && $this->tplVar['desc']['return'][1] == null){ $section->addText('无返回'); }else{
                $returnData = [];
                foreach((array)$this->tplVar['desc']['return'] as $key => $value){
                    $returnData[] = [$value[0], ($value[1] == 'table' || $value[1] == 'array') ? 'object' : $value[1], ($value[1] == 'table') ? $value[4] : $value[2]];
                }
                $this->setTable($section, $returnData, ['参数名','类型','说明'],[1500, 800, 6900]);
            }
        }else{
            $section->addText('未定义返回参数');
        }
        
        //返回参数详情
        foreach((array)$this->tplVar['desc']['returnarray'] as $key => $v){
            $keylist = explode('_', $key);
            $keyname = $keylist[count($keylist)-1];
            unset($keylist[count($keylist)-1]);
            $deskey = implode('_', $keylist);
            if($deskey != 'return'){
                $descarray = $this->tplVar['desc']['returnarray'][$deskey];
            }else{
                $descarray = $this->tplVar['desc']['return'];
            }
            foreach((array)$descarray as $descval){
                if($descval[0] == $keyname){
                    if($descval[1] == 'table'){
                        $descript = $descval[4];
                    }else{
                        $descript = $descval[2];
                    }
                }
            }
            $section->addTitle($descript.'('.$keyname.')', 1);
            $section->addText('');
            $rData = [];
            foreach((array)$v as $k => $value){
                $rData = [$value[0], ($value[1] == 'table' || $value[1] == 'array') ? 'object' : $value[1], ($value[1] == 'table') ? $value[4] : $value[2]];
            }
            $this->setTable($section, $rData, ['参数名','类型','说明'],[1500, 800, 6900]);
            $section->addText('');
        }
        
        $section->addTitle('返回参数基本格式', 1);
        $section->addText('');
        $section->addText('JSON');
        $section->addText('');
        $section->addText('');
        
        $section->addTitle('更新记录', 1);
        $section->addText('');
        $authorData = [];
        foreach((array)$this->tplVar['desc']['author'] as $k => $v){
            $authorData[] = [$v['v'], $v['author'], $v['time'], $v['desc']];
        }
        $this->setTable($section, $authorData, ['版本','作者','修改时间', '修改原因'], [1200, 1200, 2000, 4800]);
        $section->addText('');
        
        if(!empty($this->tplVar['desc']['notice'])){
            $section->addTitle('注意事项', 1);
            $section->addText($this->tplVar['desc']['notice']);
            $section->addText('');
        }
        
        
        $section->addTitle('错误代码说明', 1);
        $errData = [];
        foreach($this->tplVar['desc']['errorCode'] as $key => $errorCode){
            $errData[] = [$errorCode[0], $errorCode[1].(empty($errorCode[2]) ? '' : '('.errorCode[2].')')];
        }
        $this->setTable($section, $errData, ['错误代码','错误说明'], [1500, 7700]);
//        
        
        
        $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $objWriter->save($this->filePath.$fileName.'.docx');
        return $this->filePath.$fileName.'.docx';
    }
    
    private function setItems($section, $data){
        $section->addText('');
        foreach((array)$data as $k => $v){
            $section->addListItem($v, 0);
        }
        $section->addText('');
    }

    private function setTable($section, $tableData, $tableHeader, $widths = null) {
        $leftStyleCell = array('valign'=>'left');
        $centerStyleCell = array('valign'=>'center');
        $headercenterStyleCell = array('valign'=>'center', 'bgcolor' => '#666666');
        $section->addText('');
        $table = $section->addTable('default');
        if(!empty($tableHeader)){
            $table->addRow();
            $c = 0;
            foreach((array)$tableHeader as $key => $v){
                $w = empty($widths) || !isset($widths[$key]) ? 2300 : $widths[$key];
                $table->addCell($w, $headercenterStyleCell)->addText($v);
            }
        }
        if(!empty($tableData)){
            foreach ((array)$tableData as $k => $row) {
                $table->addRow();
                foreach ($row as $n => $text) {
                    $w = empty($widths) || !isset($widths[$n]) ? 2300 : $widths[$n];
                    $table->addCell($w, $centerStyleCell)->addText($text);
                }
            }
        }
        $section->addText('');
    }
    
    private function setIkangTable($section, $desc, $envir, $headers, $params, $requestType, $method){
        $table = $section->addTable('default');
        //描述
        $table->addRow();
        $headercenterStyleCell = ['valign'=>'center', 'bgcolor' => '#ffffff', 'fontSize' => '小五', 'fontFamily' => '仿宋'];
        $headerStyleCell = ['valign'=>'center', 'bgcolor' => '000000', 'fontSize' => '小五', 'fontFamily' => '仿宋', 'fontWeight' => 'bold'];
        $table->addCell(2000, $headercenterStyleCell)->addText('描述');
        $table->addCell(7200, $headercenterStyleCell)->addText($desc);
        //环境
        foreach($envir as $k => $v){
            $table->addRow();
            if($k == 0){
                $table->addCell(800, $headercenterStyleCell)->addText('地址');
            } else {
                $table->addCell(800, $headercenterStyleCell)->addText('');
            }
            $table->addCell(1200, $headercenterStyleCell)->addText($v[0]);
            $table->addCell(7200, $headercenterStyleCell)->addText($v[1]);
        }
        //传入Header参数
        if(!empty($headers)){
            $table->addRow();
            $table->addCell(2000, $headercenterStyleCell)->addText('传入Header参数');
            $table->addCell(2000, $headerStyleCell)->addText('参数名');
            $table->addCell(2000, $headerStyleCell)->addText('类型');
            $table->addCell(1200, $headerStyleCell)->addText('必须');
            $table->addCell(2000, $headerStyleCell)->addText('备注');
            foreach($headers as $k => $v){
                $table->addRow();
                $table->addCell(2000, $headercenterStyleCell)->addText('');
                $table->addCell(2000, $headercenterStyleCell)->addText($v[0]);
                $table->addCell(2000, $headercenterStyleCell)->addText($v[2]);
                $table->addCell(1200, $headercenterStyleCell)->addText($v[1]);
                $table->addCell(2000, $headercenterStyleCell)->addText($v[3]);
            }
        }
        //传入参数
        if(!empty($params)){
            $table->addRow(['bgcolor' => '#dddddd']);
            $table->addCell(2000, $headercenterStyleCell)->addText('传入参数');
            $table->addCell(2000, $headerStyleCell)->addText('参数名');
            $table->addCell(2000, $headerStyleCell)->addText('类型');
            $table->addCell(1200, $headerStyleCell)->addText('必须');
            $table->addCell(2000, $headerStyleCell)->addText('备注');
            foreach($params as $k => $v){
                $table->addRow();
                $table->addCell(2000, $headercenterStyleCell)->addText('');
                $table->addCell(2000, $headercenterStyleCell)->addText($v[0]);
                $table->addCell(2000, $headercenterStyleCell)->addText($v[2]);
                $table->addCell(1200, $headercenterStyleCell)->addText($v[1]);
                $table->addCell(2000, $headercenterStyleCell)->addText($v[3]);
            }
        }
        //传入参数类型
        $table->addRow();
        $table->addCell(2000, $headercenterStyleCell)->addText('传入参数类型');
        $table->addCell(7200, $headercenterStyleCell)->addText($requestType);
        //提交方法
        $table->addRow();
        $table->addCell(2000, $headercenterStyleCell)->addText('提交方法');
        $table->addCell(7200, $headercenterStyleCell)->addText($method);
        //请求协议
        $table->addRow();
        $table->addCell(2000, $headercenterStyleCell)->addText('请求协议');
        $table->addCell(7200, $headercenterStyleCell)->addText('HTTP');
        //返回结果
        $table->addRow();
        $table->addCell(2000, $headercenterStyleCell)->addText('返回结果');
        $table->addCell(2000, $headerStyleCell)->addText('参数');
        $table->addCell(3200, $headerStyleCell)->addText('参数说明');
        $table->addCell(2000, $headerStyleCell)->addText('备注');
        $table->addRow();
        $table->addCell(2000, $headercenterStyleCell)->addText('');
        $table->addCell(2000, $headercenterStyleCell)->addText('code');
        $table->addCell(3200, $headercenterStyleCell)->addText('成功状态');
        $table->addCell(2000, $headercenterStyleCell)->addText('1：成功，0：失败');
        $table->addRow();
        $table->addCell(2000, $headercenterStyleCell)->addText('');
        $table->addCell(2000, $headercenterStyleCell)->addText('msg');
        $table->addCell(3200, $headercenterStyleCell)->addText('成功状态提示');
        $table->addCell(2000, $headercenterStyleCell)->addText('');
        $table->addRow();
        $table->addCell(2000, $headercenterStyleCell)->addText('');
        $table->addCell(2000, $headercenterStyleCell)->addText('ret');
        $table->addCell(3200, $headercenterStyleCell)->addText('接口调用成功返回内容');
        $table->addCell(2000, $headercenterStyleCell)->addText('');
        $table->addRow();
        $table->addCell(2000, $headercenterStyleCell)->addText('');
        $table->addCell(2000, $headercenterStyleCell)->addText('errorCode');
        $table->addCell(3200, $headercenterStyleCell)->addText('错误代码');
        $table->addCell(2000, $headercenterStyleCell)->addText('');
        //二级参数
        if(!empty($this->tplVar['desc']['return'])){
            $table->addRow();
            $table->addCell(2000, $headercenterStyleCell)->addText('ret - 参数详情');
            $table->addCell(2000, $headerStyleCell)->addText('参数');
            $table->addCell(3200, $headerStyleCell)->addText('参数说明');
            $table->addCell(2000, $headerStyleCell)->addText('备注');
            foreach($this->tplVar['desc']['return'] as $k => $v){
                $table->addRow();
                $table->addCell(2000, $headercenterStyleCell)->addText('');
                $table->addCell(2000, $headercenterStyleCell)->addText($v[0]);
                $table->addCell(3200, $headercenterStyleCell)->addText($v[2]);
                $table->addCell(2000, $headercenterStyleCell)->addText('');
            }
        }
        //三级及以上一别参数
        if(!empty($this->tplVar['desc']['returnarray'])){
            foreach($this->tplVar['desc']['returnarray'] as $k => $v){
                $table->addRow();
                $str = str_replace('return_', '', $k);
                $strs = explode('_', $str);
                $str = implode(' > ', $strs);
                $table->addCell(2000, $headercenterStyleCell)->addText('ret > '.$str.' - 参数详情');
                $table->addCell(2000, $headerStyleCell)->addText('参数');
                $table->addCell(3200, $headerStyleCell)->addText('参数说明');
                $table->addCell(2000, $headerStyleCell)->addText('备注');
                foreach($v as $key => $value){
                    $table->addRow();
                    $table->addCell(2000, $headercenterStyleCell)->addText('');
                    $table->addCell(2000, $headercenterStyleCell)->addText($value[0]);
                    $table->addCell(3200, $headercenterStyleCell)->addText($value[2]);
                    $table->addCell(2000, $headercenterStyleCell)->addText('');
                }
            }
        }
        
    }


    public function assign($key, $val){
        $this->tplVar[$key] = $val;
    }

}
