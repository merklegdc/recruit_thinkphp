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
                if($id==0)
                    $this->response(D('interviewer')->addData($data),'json');
                else
                    $this->response(D('interviewer')->save($data),'json');
                break;
            case 'delete': // get请求处理代码
                $this->response(D('interviewer')->deleteData(array("interviewer_id"=>$id)),'json');
                break;
        }
    }
    public function interview($id=-1,$type=-1){
        switch ($this->_method){
            case 'get': // get请求处理代码
                if($id==-1||$type==-1)
                    $this->response(null,'json');
                $all=M('vw_interview')->where(array('candidate_id'=>$id,'type'=>$type))->find();
                if(empty($all))
                    $this->response(array(0),'json');
                $interview=M('vw_interview')->distinct(true)->field('interview_id,interviewer_id,interviewer_name,type,sum,passed,comment')->where(array('candidate_id'=>$id,'type'=>$type))->find();
                $score=M('vw_interview')->distinct(true)->field('score_cd,score,question,weight')->where(array('candidate_id'=>$id,'type'=>$type))->select();
                $common=M('vw_interview')->distinct(true)->field('score_cd,score,weight')->where(array('candidate_id'=>$id,'type'=>6))->select();
                $this->response(array(array('interview'=>$interview,'score'=>$score,'common'=>$common)),'json');
                break;
            case 'put': // put请求处理代码
                $data=json_decode(file_get_contents("php://input"),true);
                $interview_id=$data['interview1']['interview_id'];
                if(empty($interview_id))
                    $this->response(array(0),'json');
                D('interview')->field('interview_id,candidate_id,interviewer_id,type,comment,created_date,created_by')
                    ->save(array('interview_id'=>$interview_id,'candidate_id'=>$data['candidate_id'],'interviewer_id'=>$interview1[interviewer_id],
                        'type'=>$data['interview1'][type], 'comment'=>$data['interview1'][comment],'created_date'=>date("Y-m-d"),'created_by'=>$_SERVER['LOGON_USER']));
                $i=$interview_id;
                D('score')->where(array('interview_id'=>$i))->delete();
                for($x=0;$x<count($data['interview1'][score]);$x++){
                    if(!empty($data['interview1'][weight][$x])){
                        M('score')->add(array('interview_id'=>$i,'score_cd'=>$x,'score'=>$data['interview1'][score][$x],
                            'question'=>$data['interview1'][q][$x],'weight'=>$data['interview1'][weight][$x]));
                    }
                }
                $j=D('interview')->where(array('candidate_id'=>$data['candidate_id'],'type'=>6))->find();
                $j=$j['interview_id'];
                if(empty($j)){
                    $j=D('interview')->field('candidate_id,interviewer_id,type,created_date,created_by')
                        ->addData(array('candidate_id'=>$data['candidate_id'],'interviewer_id'=>$data['interview1'][interviewer_id],'type'=>6,
                            'created_date'=>date("Y-m-d"),'created_by'=>$_SERVER['LOGON_USER']));
                }
                D('score')->where(array('interview_id'=>$j))->delete();
                for($x=0;$x<count($data['interview2'][score]);$x++){
                    if(!empty($data['interview2'][weight][$x])) {
                        M('score')->add(array('interview_id' => $j, 'score_cd' => $x, 'score' => $data['interview2'][score][$x],
                            'question' => $data['interview2'][q][$x], 'weight' => $data['interview2'][weight][$x]));
                    }
                }
                //call procedure to update status
                M()->execute("call update_status('$data[candidate_id]')");
                break;
            case 'post': // post请求处理代码
                $data=json_decode(file_get_contents("php://input"),true);
                $data['created_date']=date("Y-m-d");
                $data['created_by']=$_SERVER['LOGON_USER'];
                if($id == -1)
                    $i=D('interview')->field('candidate_id,interviewer_id,type,comment,created_date,created_by')
                    ->addData(array('candidate_id'=>$data['candidate_id'],'interviewer_id'=>$data['interview1'][interviewer_id],'type'=>$data['interview1'][type],
                        'comment'=>$data['interview1'][comment],'created_date'=>date("Y-m-d"),'created_by'=>$_SERVER['LOGON_USER']));
                else{
                    $i=$id;
                    D('interview')->field('interview_id,candidate_id,interviewer_id,type,comment,created_date,created_by')
                    ->save(array('interview_id'=>$id,'candidate_id'=>$data['candidate_id'],'interviewer_id'=>$data['interview1'][interviewer_id],
                        'type'=>$data['interview1'][type], 'comment'=>$data['interview1'][comment],'created_date'=>date("Y-m-d"),'created_by'=>$_SERVER['LOGON_USER']));
                }
                D('score')->where(array('interview_id'=>$i))->delete();
                for($x=0;$x<count($data['interview1'][score]);$x++){
                    if(!empty($data['interview1'][weight][$x])) {
                        M('score')->add(array('interview_id' => $i, 'score_cd' => $x, 'score' => $data['interview1'][score][$x],
                            'question' => $data['interview1'][q][$x], 'weight' => $data['interview1'][weight][$x]));
                    }
                }
                $j=D('interview')->where(array('candidate_id'=>$data['candidate_id'],'type'=>6))->find();
                $j=$j['interview_id'];
                if(empty($j)){
                    $j=D('interview')->field('candidate_id,interviewer_id,type,created_date,created_by')
                        ->addData(array('candidate_id'=>$data['candidate_id'],'interviewer_id'=>$data['interview1'][interviewer_id],'type'=>6,
                            'created_date'=>date("Y-m-d"),'created_by'=>$_SERVER['LOGON_USER']));
                }
                D('score')->where(array('interview_id'=>$j))->delete();
                for($x=0;$x<count($data['interview2'][score]);$x++){
                    if(!empty($data['interview2'][weight][$x])) {
                        M('score')->add(array('interview_id' => $j, 'score_cd' => $x, 'score' => $data['interview2'][score][$x],
                            'question' => $data['interview2'][q][$x], 'weight' => $data['interview2'][weight][$x]));
                    }
                }
                //calculate sum
                /*if($data['type']==0){
                    $data['sum']=$data['score1']*0.4+$data['score2']*0.1+$data['score3']+$data['score4']*0.15+$data['score5']*0.05+
                        $data['score6']*0.2+$data['score7']+$data['score8']+$data['score9'];
                }else{
                    $data['sum']=$data['score1']*0.1+$data['score2']*0.2+$data['score3']*0.1+$data['score4']*0.1+$data['score5']*0.15+
                        $data['score6']*0.025+$data['score7']*0.025+$data['score8']*0.1+$data['score9']*0.1;
                }
                $data['passed']=0;
                D('interview')->deleteData(array("candidate_id"=>$data[candidate_id],"type"=>$data[type]));
                //insert
                $i=D('interview')->addData($data);*/
                //call procedure to update status
                M()->execute("call update_status('$data[candidate_id]')");
                $this->response($i,'json');
                break;
            case 'delete': // get请求处理代码
//                $this->response(D('interviewer')->deleteData(array("interviewer_id"=>$id)),'json');
                break;
            case 'options':
                $this->response(0,json);
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
                if(isset($_SERVER['HTTP_X_ORIGINAL_URL'])){
                    $name=substr($_SERVER['HTTP_X_ORIGINAL_URL'],strrpos($_SERVER['HTTP_X_ORIGINAL_URL'],'/')); 
                }
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
    public function searchCandidateCN($name){
        switch ($this->_method){
            case 'get': // get请求处理代码
                if(isset($_SERVER['HTTP_X_ORIGINAL_URL'])){
                    $name=urldecode(substr($_SERVER['HTTP_X_ORIGINAL_URL'],strrpos($_SERVER['HTTP_X_ORIGINAL_URL'],'/')+1)); 
                }
                $this->response($name,'json');
                if(empty($name)){
                    //$this->response(D('candidate')->select(),'json');
                }else{
                    $map['name'] = array('like','%'.$name.'%');
                    $res=D('candidate_desc_cn')->where($map)->order('candidate_id')->limit(10)->select();
                    if(empty($res)){
                        $res=array(array('candidate_id'=>'','name'=>'','description'=>''));
                    }
                    $this->response($res,'json');
                }
                break;
        }
    }
    public function searchCandidateEN($name){
        switch ($this->_method){
            case 'get': // get请求处理代码
                if(empty($name)){
                    //$this->response(D('candidate')->select(),'json');
                }else{
                    $map['name'] = array('like','%'.$name.'%');
                    $res=D('candidate_desc_en')->where($map)->order('candidate_id')->limit(10)->select();
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
        $this->response($upload->upload(),'json');
    }
}