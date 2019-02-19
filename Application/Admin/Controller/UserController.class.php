<?php
//用户信息处理控制器
namespace Admin\Controller;
class UserController extends CheckController {

    public function getUserInfo(){
        $this->ajaxSuccess($_SESSION['userInfo']);
    }
}