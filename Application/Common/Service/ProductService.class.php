<?php
namespace Common\Service;
class ProductService  extends  CommonService {
    public function __construct(){
        parent::__construct('product p');
    }

    //做联合查询用
    private function tj(){
        $this->tableObj = $this->tableObj->join('left join __ARTICLE__ as a on p.article_id = a.id');
        return $this;
    }

    //需要与文章表联合查询获取产品信息
    public function getProducts($where = [], $pageNow = 1, $pageSize = 999){
        return $this->tj()->getList($where, $pageNow, $pageSize);
    }

    //需要与文章表联合查询获取产品信息
    public function getProductDetail($where = []){
        return $this->tj()->findItem($where);
    }
}