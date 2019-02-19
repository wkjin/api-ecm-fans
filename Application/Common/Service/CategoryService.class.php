<?php
namespace Common\Service;
class CategoryService  extends  CommonService {
    public function __construct(){
        parent::__construct('category');
    }

    public function getCategorys($where = [], $pageNow = 1, $pageSize = 999, $order = 'id asc'){
        return $this->getList($where,$pageNow, $pageSize, $order);
    }

    public function updateCategory($condition, $data){
        if(empty($condition) || !is_array($condition)){
            return false;
        }
        $data = empty($data)?[]: $data;
        $data['update_time'] = time();
        return $this->update($condition, $data);
    }

    public function addCategory($data){
        if(empty($data) || !is_array($data)){
            return false;
        }
        $data['update_time'] = $data['create_time'] = time();
        return $this->insert($data);
    }
}