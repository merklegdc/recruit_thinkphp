<?php
namespace Home\Controller;
use Think\Controller\RestController;
class InterviewController extends RestController {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
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
                M()->execute("call update_status('$data[candidate_id]')");
                $this->response($i,'json');
                break;
            case 'delete': // get请求处理代码
//                $this->response(D('interviewer')->deleteData(array("interviewer_id"=>$id)),'json');
                break;
            case 'options':
                $this->response(0,'json');
                break;
        }
    }
}