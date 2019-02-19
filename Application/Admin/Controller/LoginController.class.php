<?php
namespace Admin\Controller;
class LoginController extends CommonController {
    private $adminService = null;//使用的service对象（一个model）

    public function __construct() {
        parent::__construct();
        //初始化对象
        $this->adminService = CS('Admin');//定义使用service
    }

    //登录处理
    public function login($username, $password){
        $res = $this->adminService->login(trim($username), trim($password));
        if(!empty($res) && $res['username'] === $username){
            unset($res['password']);//清除密码
            session('userInfo', $res);
            $this->ajaxSuccess(null,'登录成功');
        }else{
            $this->ajaxError(null,'用户名与密码不匹配');
        }
    }

    public function debug(){
        session('user', array(
           'id' => 1,
           'username' => 'wkj'
        ));
        $this->ajaxSuccess($_SESSION,'debug中');
    }
}