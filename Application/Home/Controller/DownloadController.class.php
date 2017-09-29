<?php
namespace Home\Controller;
use Think\Controller\RestController;
Vendor('PHPExcel.Classes.PHPExcel');
class DownloadController extends RestController {
    
    public function downloadExcel(){
        switch ($this->_method){
            case 'options':
                $this->response(0,'json');
                break;
            case 'get':
                $TEMP_PATH = rtrim($Think.THINK_PATH,'ThinkPHP/').'Public\\temp\\';
                $http = new \Org\Net\Http();
                $objPHPexcel = \PHPExcel_IOFactory::load(rtrim($Think.THINK_PATH,'ThinkPHP/').'Public\\campus-tracking-template.xlsx');
                $objWorksheet = $objPHPexcel->getSheet(0);
                $data = M('candidate')->select();
                $i = 2;
                foreach ($data as $record ) { 
                    $objWorksheet->getCellByColumnAndRow(1, $i)->setValue($record[candidate_id]);
                    $objWorksheet->getCellByColumnAndRow(2, $i)->setValue($record[name_cn]);
                    $objWorksheet->getCellByColumnAndRow(3, $i)->setValue($record[name_en]);
                    $objWorksheet->getCellByColumnAndRow(4, $i)->setValue($record[assign_date]);
                    $objWorksheet->getCellByColumnAndRow(5, $i)->setValue($record[service_line]);
                    $objWorksheet->getCellByColumnAndRow(6, $i)->setValue($record[position]);
                    $objWorksheet->getCellByColumnAndRow(7, $i)->setValue($record[location]);
                    $objWorksheet->getCellByColumnAndRow(8, $i)->setValue($record[gender]);
                    $objWorksheet->getCellByColumnAndRow(9, $i)->setValue($record[degree]);
                    $objWorksheet->getCellByColumnAndRow(10, $i)->setValue($record[university]);
                    $objWorksheet->getCellByColumnAndRow(11, $i)->setValue($record[major]);
                    $objWorksheet->getCellByColumnAndRow(12, $i)->setValue($record[graduation_date]);
                    $objWorksheet->getCellByColumnAndRow(13, $i)->setValue($record[phone]);
                    $objWorksheet->getCellByColumnAndRow(14, $i)->setValue($record[email]);
                    $objWorksheet->getCellByColumnAndRow(15, $i)->setValue($record[receive_date]);
                    $objWorksheet->getCellByColumnAndRow(16, $i)->setValue($record[channel]);
                    $objWorksheet->getCellByColumnAndRow(17, $i)->setValue($record[recommender]);
                    $i += 1;
                }
                $fileName = rand().'.xlsx';
                // delete all files in temp folder
                $files = glob($TEMP_PATH.'*'); // get all file names
                foreach($files as $file){ // iterate files
                  if(is_file($file))
                    unlink($file); // delete file
                }
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPexcel, "Excel2007");   
                $objWriter->save($TEMP_PATH.$fileName);
                $http->download($TEMP_PATH.$fileName, 'Campus Tracking'.date("Y-m-d").'.xlsx');
                break;
        }
    }
    public function downloadData(){
        switch ($this->_method){
            case 'options':
            $this->response(0,'json');
            break;
            case 'get':
                $data = M('candidate')->select();
                $this->response($data,'json');
                break;
        }
    }
    public function downloadDoc(){
        switch ($this->_method){
            case 'options':
            $this->response(0,'json');
            break;
            case 'get':
            $http = new \Org\Net\Http();
            $TEMP_PATH = rtrim($Think.THINK_PATH,'ThinkPHP/').'Public\\doc\\';
            $fileName = 'Merkle-recruitment-manual.docx';
            $http->download($TEMP_PATH.$fileName, $fileName);
            break;
        }
    }
}