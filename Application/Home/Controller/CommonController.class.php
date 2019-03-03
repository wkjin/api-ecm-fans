<?php
//前端的公共类
namespace Home\Controller;
use Think\Controller;
header("Access-Control-Allow-Origin: http://test.ecm-fans.com");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Connection, User-Agent, Cookie");
class CommonController extends Controller {

    //定义统一的返回格式（json格式）
    private function _return($result){
        $this->ajaxReturn( $result,'JSON');
    }

    //定义ajax请求错误时候返回
    public function ajaxError($data, $message = '操作失败'){
        $this->_return( toErrorData($data, $message));
    }

    //定义ajax请求成功时候返回
    public function ajaxSuccess($data, $message = '操作成功'){
        $this->_return( toSuccessData($data, $message));
    }

    //定义ajax请求系统错误时候返回
    public function ajaxSystemError($data, $message = '系统错误'){
        $this->_return( toSysErrorData($data, $message));
    }

    //错误处理
    public function _empty(){
        $this->ajaxSystemError([
            'controller' => CONTROLLER_NAME,
            'method'=> ACTION_NAME
        ], '请求的地址不存在');
    }

}