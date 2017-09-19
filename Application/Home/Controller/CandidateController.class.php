<?php
namespace Home\Controller;
use Think\Controller\RestController;
class CandidateController extends RestController {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
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
            case 'post': // post请求处理代码
                $data=json_decode(file_get_contents("php://input"),true);
                foreach ($data as $key => $value) {
                    if(empty($value)){
                        $data[$key]=null;
                    }
                }
                $data['created_date']=date("Y-m-d");
                $data['created_by']=$_SERVER['LOGON_USER'];
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
}