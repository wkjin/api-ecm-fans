<?php
namespace Admin\Controller;
class CheckController extends CommonController {

    //检测是否登录
    public function _initialize(){
        if(!isset($_SESSION['userInfo'])){
            $this->ajaxError(null, '还没有登录');
        }
    }
}