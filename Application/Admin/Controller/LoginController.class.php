<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {

    //登录页面
    public function index(){
        $this->display('admin');
    }


    //登录处理
    public function login(){
        var_dump($_POST);
    }
}