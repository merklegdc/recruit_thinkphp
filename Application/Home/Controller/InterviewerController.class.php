<?php
namespace Home\Controller;
use Think\Controller\RestController;
class InterviewerController extends RestController {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    public function interviewer($id=0){
        switch ($this->_method){
            case 'get': // get请求处理代码
                $this->response(D('interviewer')->select(),'json');
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
}