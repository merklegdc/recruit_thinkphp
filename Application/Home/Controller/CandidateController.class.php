<?php
namespace Home\Controller;
use Think\Controller\RestController;
class CandidateController extends RestController {
    public function index(){
        $this->ajaxReturn(C('ENV'));
    }
    public function candidate($id=-1){
        $c = D('candidate');
        $condition['candidate_id'] = $id;
        switch ($this->_method){
            case 'get': // get请求处理代码
            if($id == -1) $this->ajaxReturn('error');
            $data=$c->where($condition)->select();
            $this->ajaxReturn($data);
            break;
            case 'post': // post请求处理代码
                $data=json_decode(file_get_contents("php://input"),true);
                foreach ($data as $key => $value) {
                    if(empty($value)){
                        $data[$key]=null;
                    }
                }
                $data['if_group'] = ($data['service_line']=='Analytics and Data Products') ? 'Y' : 'N';
                $data['created_date']=date("Y-m-d");
                $data['created_by']=$_SERVER['LOGON_USER'];
                if($res=$c->field('candidate_id')->where(array('name_cn'=>$data[name_cn],'phone'=>$data[phone]))->select()){
                    $c->field('if_active')->where(array('candidate_id'=>$res[0][candidate_id]))->save(array('if_active'=>'N'));
                }
                if(empty($data["candidate_id"])){
                    $this->response($c->add($data),'json');
                }else{
                    $this->response($c->save($data),'json');
                }
                break;
            case 'delete': 
                $this->response($c->where($condition)->delete(),'json');
                break;
        }
    }
}