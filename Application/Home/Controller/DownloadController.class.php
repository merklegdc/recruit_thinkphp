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
                $j = 2;
                foreach ($data as $record ) { 
                    $objWorksheet->getCellByColumnAndRow($j-1, $i)->setValue($record[status]);
                    $objWorksheet->getCellByColumnAndRow($j, $i)->setValue($record[if_active]);
                    $objWorksheet->getCellByColumnAndRow($j+1, $i)->setValue($record[candidate_id]);
                    $objWorksheet->getCellByColumnAndRow($j+3, $i)->setValue($record[name_en]);
                    $objWorksheet->getCellByColumnAndRow($j+2, $i)->setValue($record[name_cn]);
                    $objWorksheet->getCellByColumnAndRow($j+4, $i)->setValue($record[assign_date]);
                    $objWorksheet->getCellByColumnAndRow($j+5, $i)->setValue($record[service_line]);
                    $objWorksheet->getCellByColumnAndRow($j+6, $i)->setValue($record[position]);
                    $objWorksheet->getCellByColumnAndRow($j+7, $i)->setValue($record[location]);
                    $objWorksheet->getCellByColumnAndRow($j+8, $i)->setValue($record[gender]);
                    $objWorksheet->getCellByColumnAndRow($j+9, $i)->setValue($record[degree]);
                    $objWorksheet->getCellByColumnAndRow($j+10, $i)->setValue($record[university]);
                    $objWorksheet->getCellByColumnAndRow($j+11, $i)->setValue($record[major]);
                    $objWorksheet->getCellByColumnAndRow($j+12, $i)->setValue($record[graduation_date]);
                    $objWorksheet->getCellByColumnAndRow($j+13, $i)->setValue($record[phone]);
                    $objWorksheet->getCellByColumnAndRow($j+14, $i)->setValue($record[email]);
                    $objWorksheet->getCellByColumnAndRow($j+15, $i)->setValue($record[receive_date]);
                    $objWorksheet->getCellByColumnAndRow($j+16, $i)->setValue($record[channel]);
                    $objWorksheet->getCellByColumnAndRow($j+17, $i)->setValue($record[recommender]);
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
                $data = M('vw_candidate')->select();
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