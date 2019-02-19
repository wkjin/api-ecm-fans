<?php
namespace Common\Service;
class FragmentService  extends  CommonService {
    public function __construct(){
        parent::__construct('fragment');
    }

    public function getFragments($where = [], $pageNow = 1, $pageSize = 999){
        return $this->getList($where, $pageNow, $pageSize);
    }

    public function updateFragment($condition, $data){
        if(empty($condition)){
            return false;
        }
        $data = empty($data)?[]: $data;
        $data['update_time'] = time();
        return $this->update($condition, $data);
    }

    public function addFragment($data){
        if(empty($data) || !is_array($data)){
            return false;
        }
        $data['update_time'] = $data['create_time'] = time();
        return $this->insert($data);
    }
}