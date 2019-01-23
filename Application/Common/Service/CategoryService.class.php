<?php
namespace Common\Service;
class CategoryService  extends  CommonService {
    public function __construct(){
        parent::__construct('category');
    }

    public function getCategorys($where = [], $pageNow = 1, $pageSize = 999){
        return $this->getList($where,$pageNow, $pageSize);
    }
}