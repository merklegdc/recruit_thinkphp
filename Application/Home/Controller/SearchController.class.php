<?php
namespace Home\Controller;
use Think\Controller\RestController;
class SearchController extends RestController {
    public function index(){
        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }
    
    public function searchCandidate($name){
        switch ($this->_method){
            case 'get': // get请求处理代码
                if(isset($_SERVER['HTTP_X_ORIGINAL_URL'])){
                    $name=urldecode(substr($_SERVER['HTTP_X_ORIGINAL_URL'],strrpos($_SERVER['HTTP_X_ORIGINAL_URL'],'/')+1)); 
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
}