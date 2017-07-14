<?php
namespace Home\Controller;
use Think\Controller\RestController;
class IndexController extends RestController {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    public function interviewer($id=0){
        switch ($this->_method){
            case 'get': // get请求处理代码
                $this->response(D('interviewer')->select(),'json');
                break;
            case 'put': // put请求处理代码
                $data=json_decode(file_get_contents("php://input"),true);
                //$map['interviewer_id'] = $id;
                $this->response(D('interviewer')->save($data),'json');
                break;
            case 'post': // post请求处理代码
                $data=json_decode(file_get_contents("php://input"),true);
                $this->response(D('interviewer')->addData($data),'json');
                break;
            case 'delete': // get请求处理代码
                $this->response(D('interviewer')->deleteData(array("interviewer_id"=>$id)),'json');
                break;
        }
    }
    public function interview($id=0,$type=0){
        switch ($this->_method){
            case 'get': // get请求处理代码
                $data=D('interview')->where(array('candidate_id'=>$id))->order('type')->select();
                $res['candidateID']=$id;
                $res['candidateName']=D('candidate')->field('name')->find($id)['name'];
                $res['interviewerID']=[0,0,0,0,0,0];
                $res['interviewerName']=["","","","","",""];
                $res['comment']=["","","","","",""];
                $res['weight']=["","","","","","","","",""];
                $res['date']='';
                $res['pos']='';
                $res['score']=[["","","","","","","","",""],["","","","","","","","",""],["","","","","","","","",""],["","","","","","","","",""],["","","","","","","","",""],["","","","","","","","",""]];
                $res['q']=[["","","","","","","","",""],["","","","","","","","",""],["","","","","","","","",""],["","","","","","","","",""],["","","","","","","","",""],["","","","","","","","",""]];
                foreach ( $data as $record){
                    $res['interviewerID'][$record[type]]=$record['interviewerID'];
                    $res['interviewerName'][$record[type]]=D('interviewer')->field('name')->find($record['interviewerID'])['name'];
                    $res['comment'][$record[type]]=$record['comment'];
                    $res['score'][$record[type]][0]=$record[score1];
                    $res['score'][$record[type]][1]=$record[score2];
                    $res['score'][$record[type]][2]=$record[score3];
                    $res['score'][$record[type]][3]=$record[score4];
                    $res['score'][$record[type]][4]=$record[score5];
                    $res['score'][$record[type]][5]=$record[score6];
                    $res['score'][$record[type]][6]=$record[score7];
                    $res['score'][$record[type]][7]=$record[score8];
                    $res['score'][$record[type]][8]=$record[score9];
                    $res['q'][$record[type]][0]=$record[q1];
                    $res['q'][$record[type]][1]=$record[q2];
                    $res['q'][$record[type]][2]=$record[q3];
                    $res['q'][$record[type]][3]=$record[q4];
                    $res['q'][$record[type]][4]=$record[q5];
                    $res['q'][$record[type]][5]=$record[q6];
                    $res['q'][$record[type]][6]=$record[q7];
                    $res['q'][$record[type]][7]=$record[q8];
                    $res['q'][$record[type]][8]=$record[q9];
                }
                $this->response(array($res),'json');
                break;
            case 'put': // put请求处理代码
//                $data=json_decode(file_get_contents("php://input"),true);
                //$map['interviewer_id'] = $id;
//                $this->response(D('interviewer')->save($data),'json');
                break;
            case 'post': // post请求处理代码
                $data=json_decode(file_get_contents("php://input"),true);
//                dump(D('interview')->field('sum')->where(array('candidate_id'=>$data['candidate_id'],'type'=>1))->find());
                $data['created_date']=date("Y-m-d");
                $data['created_by']=$_SERVER['LOGON_USER'];
                //calculate sum
                if($data['type']==0){
                    $data['sum']=$data['score1']*0.4+$data['score2']*0.1+$data['score3']+$data['score4']*0.15+$data['score5']*0.05+
                        $data['score6']*0.2+$data['score7']+$data['score8']+$data['score9'];
                }else{
                    $data['sum']=$data['score1']*0.1+$data['score2']*0.2+$data['score3']*0.1+$data['score4']*0.1+$data['score5']*0.15+
                        $data['score6']*0.025+$data['score7']*0.025+$data['score8']*0.1+$data['score9']*0.1;
                }
                $data['passed']=0;
                D('interview')->deleteData(array("candidate_id"=>$data[candidate_id],"type"=>$data[type]));
                //insert
                $i=D('interview')->addData($data);
                //call procedure to update status
                M()->execute("call update_status('$data[candidate_id]')");
                $this->response($i,'json');
                break;
            case 'delete': // get请求处理代码
//                $this->response(D('interviewer')->deleteData(array("interviewer_id"=>$id)),'json');
                break;
        }
    }

    public function candidate($name='',$id=0){
        switch ($this->_method){
            case 'get': // get请求处理代码
                if(!empty($id)){
                    $this->response(D('candidate')->where(array('candidate_id'=>$id))->select(),'json');
                }
                if(empty($name)){
                    $this->response(D('candidate')->select(),'json');
                }else{
                    $this->response(D('candidate')->where(array('name'=>$name))->select(),'json');
                }
                break;
            case 'put': // put请求处理代码
                $data=json_decode(file_get_contents("php://input"),true);
                $this->response(D('candidate')->save($data),'json');
                break;
            case 'post': // post请求处理代码
                $data=json_decode(file_get_contents("php://input"),true);
                foreach ($data as $key => $value) {
                    if(empty($value)){
                        $data[$key]=null;
                    }
                }
                $data['create_date']=date("Y-m-d");
                if(empty($data["candidate_id"])){
                    $this->response(D('candidate')->addData($data),'json');
                }else{
                    $this->response(D('candidate')->save($data),'json');
                }
                break;
            case 'delete': // get请求处理代码
                $this->response(D('candidate')->deleteData(array("candidate_id"=>$id)),'json');
                break;
        }
    }

    public function searchCandidate($name){
        switch ($this->_method){
            case 'get': // get请求处理代码
                if(empty($name)){
                    //$this->response(D('candidate')->select(),'json');
                }else{
                    $map['name'] = array('like','%'.$name.'%');
                    $res=D('candidate_desc')->where($map)->order('candidate_id')->limit(10)->select();
                    if(empty($res)){
                        $res=array(array('candidate_id'=>'','name'=>'','description'=>''));
                    }
                    $this->response($res,'json');
                }
                break;
        }
    }

    public function searchInterviewer($name){
        switch ($this->_method){
            case 'get': // get请求处理代码
                if(empty($name)){
                    //$this->response(D('candidate')->select(),'json');
                }else{
                    $map['name'] = array('like','%'.$name.'%');
                    $res=D('Interviewer')->field('interviewer_id,name')->where($map)->order('interviewer_id')->limit(10)->select();
                    if(empty($res)){
                        $res=array(array('interviewer_id'=>'','name'=>''));
                    }
                    $this->response($res,'json');
                }
                break;
        }
    }

    public function upload($name){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        //$upload->exts      =     array('jpg', 'gif', 'png', 'csv');// 设置附件上传类型
        $upload->rootPath  = C('UPLOAD_PATH');//"\\\\nanitfp01\\Public\\Departments\\Public\\2016 Campus Deck\\2017\\CV\\"; // 设置附件上传根目录
        $upload->savePath  =    ''; //I('dept').'_Test\\'; // 设置附件上传（子）目录
        $upload->autoSub = true;
        $upload->subName = 'resume';

        $upload->saveName = $name.date("Y-m-d");//I('name').'_'.I('dept').'_'.date("Y-m-d");//array('filename',I('name'),I('dept'));
        // 上传文件
        $this->response($upload->upload(),'json');
    }
}