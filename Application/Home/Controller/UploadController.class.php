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
            'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM');  
            // 一次读取一列  
            $res_arr = array();
            $field = array();
            $map = array(
                'Candidate ID'=>'candidate_id',
                'Name (CN)'=>'name_cn',
                'Name (Eng)'=>'name_en',
                'Assign Date'=>'assign_date',
                'Service Line'=>'service_line',
                'Position'=>'position',
                'Location'=>'location',
                'Gender'=>'gender',
                'Degree'=>'degree',
                'University'=>'university',
                'Major'=>'major',
                '毕业时间'=>'graduation_date',
                'Cell Phone'=>'phone',
                'Email'=>'email',
                'Received Date'=>'receive_date',
                'Channel'=>'channel',
                'Channel Detail'=>'recommender',
            );
            for ($column = 0; $arr[$column] != 'AM'; $column++) {  
                $val = $sheet->getCellByColumnAndRow($column, 1)->getValue();
                $field[$column] = $map[$val];
            }
            // $this->ajaxReturn($field);  
            for ($row = 2; $row <= $highestRow; $row++) {  
                $row_arr = array();  
                for ($column = 0; $arr[$column] != 'AK'; $column++) {  
                    $val = $sheet->getCellByColumnAndRow($column, $row)->getValue();
                    $row_arr[$field[$column]] = $val;  
                }
                $row_arr['check'] = null;
                $row_arr['active'] = 'Y';
                $row_arr['status'] = null;  
                $res_arr[] = $row_arr;  
            }  
            $this->ajaxReturn($res_arr);
        }
        
    }
    public function uploadData(){
        $data=json_decode(file_get_contents("php://input"),true);
        foreach ($data as $item) {
            $item['if_group'] = $item['service_line']=='Analytics and Data Products' ? 'Y' : 'N';
            $item['created_date']=date("Y-m-d");
            $item['created_by']=$_SERVER['LOGON_USER'];
            if(empty($item['name_cn']))
                continue;
            if(empty($item['candidate_id'])){
                M('candidate')->add($item);
            }else{
                M('candidate')->save($item);
            }
        }
        $this->ajaxReturn('success');
    }
}