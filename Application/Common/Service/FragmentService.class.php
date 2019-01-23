<?php
namespace Common\Service;
class FragmentService  extends  CommonService {
    public function __construct(){
        parent::__construct('fragment');
    }

    public function getFragments($where = [], $pageNow = 1, $pageSize = 999){
        return $this->getList($where, $pageNow, $pageSize);
    }
}