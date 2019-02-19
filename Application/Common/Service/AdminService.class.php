<?php
namespace Common\Service;
class AdminService  extends  CommonService {
    public function __construct(){
        parent::__construct('admin');
    }

    public function getAdmins($where = [], $pageNow = 1, $pageSize = 999){
        return $this->getList($where, $pageNow, $pageSize);
    }

    //登录
    public function login($username, $password){
        $condition = array(
            'username' => trim($username),
            'passoword' => md5(trim($password))
        );
        return $this->findItem($condition);
    }


}