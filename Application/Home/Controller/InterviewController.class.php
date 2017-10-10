<?php
namespace Home\Controller;
use Think\Controller\RestController;
class InterviewController extends RestController {
    public function __construct(){
        // $this->ajaxReturn(D('candidate')->field('name_cn')->find()['name_cn']);
        if(C('ENV') != 'dev'){
            // $name = D('vw_employee')->field('name')->where(array('code'=>$_SERVER['LOGON_USER']))->find()['name'];
            // $map['level'] = array('gt', 0);
            // if(!D('permission')->where($map)->find()){
            //     $this->ajaxReturn('access denied');
            // }
        }
    }
    public function interview($id,$type){
        $c = D('candidate');
        $condition['candidate_id'] = $id;
        $condition['type'] = $type;
        switch ($this->_method){
            case 'get': // get请求处理代码
            $data = D('interview')->where($condition)->select();
            $this->ajaxReturn($data);
            break;
            case 'post': // post请求处理代码
            $data=json_decode(file_get_contents("php://input"),true);
            // convert '' to null
            foreach ($data as $key => $value) {
                if(empty($value)){
                    $data[$key]=null;
                }
            }
            $data['created_date']=date("Y-m-d");
            $data['created_by']=$_SERVER['LOGON_USER'];
            // D('interview')->field('score10,score11,score12,score13')->where(array('candidate_id'=>$data['candidate_id']))->save($data);
            $sql = 'update interview set score5='.$data['score5'].',score11='.$data['score11'].',score12='.$data['score12'].
            ',score13='.$data['score13'].' where candidate_id='.$data['candidate_id'];
            M()->execute($sql);
            // $sql = 'IF '.$type.' >=  COALESCE((SELECT last_type FROM candidate WHERE candidate.candidate_id='.$data[candidate_id].'),0) THEN
            // UPDATE candidate c SET last_type='.$type.', status=(SELECT type_desc FROM interview_type where type='.$type.')
            // WHERE candidate_id='.$data[candidate_id].'
            // END IF;';
            D('interview')->where($condition)->save($data);
            M()->execute('call update_status('.$data[candidate_id].','.$type.',"'.$data[status].'")');
            // if (D('interview')->where($condition)->select()) { //如果存在记录则更新否则添加
            //     D('interview')->where($condition)->save($data);
            // }else {
            //     D('interview')->add($data);
            // }
            $this->ajaxReturn('success');
            break;
        }
    }
}