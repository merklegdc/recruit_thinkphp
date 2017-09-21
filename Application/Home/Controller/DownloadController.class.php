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
                $i = 0;
                $j = 0;
                $response = array(array());
                foreach ($data as $record ) { 
                    $response[$i][$j+0] = '';
                    $response[$i][$j+1] = '';
                    $response[$i][$j+2] = $record[candidate_id];
                    $response[$i][$j+3] = $record[name_cn];
                    $response[$i][$j+4] = $record[name_en];
                    $response[$i][$j+5] = $record[assign_date];
                    $response[$i][$j+6] = $record[service_line];
                    $response[$i][$j+7] = $record[position];
                    $response[$i][$j+8] = $record[location];
                    $response[$i][$j+9] = $record[gender];
                    $response[$i][$j+10] = $record[degree];
                    $response[$i][$j+11] = $record[university];
                    $response[$i][$j+12] = $record[major];
                    $response[$i][$j+13] = $record[graduation_date];
                    $response[$i][$j+14] = $record[phone];
                    $response[$i][$j+15] = $record[email];
                    $response[$i][$j+16] = $record[receive_date];
                    $response[$i][$j+17] = $record[channel];
                    $response[$i][$j+18] = $record[recommender];
                    $i += 1;
                }
                $this->response($response,'json');
                break;
        }
    }
}