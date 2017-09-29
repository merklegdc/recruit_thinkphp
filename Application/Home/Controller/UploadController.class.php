<?php
namespace Home\Controller;
use Think\Controller;
Vendor('PHPExcel.Classes.PHPExcel');
class UploadController extends Controller {
    
    public function upload($name,$dept){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        //$upload->exts      =     array('jpg', 'gif', 'png', 'csv');// 设置附件上传类型
        $upload->rootPath  = C('UPLOAD_PATH');//"\\\\nanitfp01\\Public\\Departments\\Public\\2016 Campus Deck\\2017\\CV\\"; // 设置附件上传根目录
        $upload->savePath  =    ''; //I('dept').'_Test\\'; // 设置附件上传（子）目录
        $upload->autoSub = true;
        $upload->subName = $dept;

        $upload->saveName = $name.' '.date("Y-m-d");//I('name').'_'.I('dept').'_'.date("Y-m-d");//array('filename',I('name'),I('dept'));
        // 上传文件
        //$this->response($upload->upload(),'json');
    }

    public function uploadExcel(){
        unlink(C('UPLOAD_PATH').'upload\\excel123.xlsx');
        $upload = new \Think\Upload();// 实例化上传类
        //$upload->maxSize   =     3145728 ;// 设置附件上传大小
        //$upload->exts      =     array('jpg', 'gif', 'png', 'csv');// 设置附件上传类型
        $upload->rootPath  = C('UPLOAD_PATH');//"\\\\nanitfp01\\Public\\Departments\\Public\\2016 Campus Deck\\2017\\CV\\"; // 设置附件上传根目录
        $upload->savePath  = ''; //I('dept').'_Test\\'; // 设置附件上传（子）目录
        $upload->autoSub = true;
        $upload->subName = 'upload';
        $upload->saveName = 'excel123';//I('name').'_'.I('dept').'_'.date("Y-m-d");//array('filename',I('name'),I('dept'));
        // 上传文件
        $res=$upload->upload();
        if($res){
            $objPHPExcelReader = \PHPExcel_IOFactory::load(C('UPLOAD_PATH').'upload\\excel123.xlsx');   
            $sheet = $objPHPExcelReader->getSheet(0); 
            $highestRow = $sheet->getHighestRow();           // 取得总行数  
            $highestColumn = $sheet->getHighestColumn();     // 取得总列数  
          
            $arr = array('A','B','C','D','E','F','G','H','I','J','K','L','M', 'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK');  
            // 一次读取一列  
            $res_arr = array();
            for ($row = 2; $row <= $highestRow; $row++) {  
                $row_arr = array();  
                for ($column = 0; $arr[$column] != 'AK'; $column++) {  
                    $val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
                    $row_arr[] = $val;  
                }  
                $res_arr[] = $row_arr;  
            }  
            $this->ajaxReturn($res_arr);
        }
        
    }
    public function uploadData(){
        $data=json_decode(file_get_contents("php://input"),true);
        foreach ($data as $arr) {
            $i = 1;
            if(empty($arr[$i+2])){
                continue;
            }
            $if_group = ($arr[$i+5]=='Analytics and Data Products') ? 'Y' : 'N';
            if(empty($arr[$i+1])){
                $record=array('name_cn'=>$arr[$i+2], 'name_en'=>$arr[$i+3], 'assign_date'=>$arr[$i+4], 'service_line'=>$arr[$i+5], 
                'position'=>$arr[$i+6],'location'=>$arr[$i+7], 'gender'=>$arr[$i+8], 'degree'=>$arr[$i+9], 'university'=>$arr[$i+10], 
                'major'=>$arr[$i+11], 'graduation_date'=>$arr[$i+12], 'phone'=>$arr[$i+13], 'email'=>$arr[$i+14], 
                'receive_date'=>$arr[$i+15], 'channel'=>$arr[$i+16],'recommender'=>$arr[$i+17], 'if_group'=>$if_group,
                'created_date'=>date("Y-m-d"),'created_by'=>$_SERVER['LOGON_USER']);
                M('candidate')->add($record);
            }else{
                $record=array('candidate_id'=>$arr[$i+1],'name_cn'=>$arr[$i+2], 'name_en'=>$arr[$i+3], 'assign_date'=>$arr[$i+4], 'service_line'=>$arr[$i+5], 
                'position'=>$arr[$i+6],'location'=>$arr[$i+7], 'gender'=>$arr[$i+8], 'degree'=>$arr[$i+9], 'university'=>$arr[$i+10], 
                'major'=>$arr[$i+11], 'graduation_date'=>$arr[$i+12], 'phone'=>$arr[$i+13], 'email'=>$arr[$i+14], 
                'receive_date'=>$arr[$i+15], 'channel'=>$arr[$i+16],'recommender'=>$arr[$i+17], 'if_group'=>$if_group,
                'created_date'=>date("Y-m-d"),'created_by'=>$_SERVER['LOGON_USER']);
                M('candidate')->save($record);
            }
        }
        $this->ajaxReturn('success');
    }
}