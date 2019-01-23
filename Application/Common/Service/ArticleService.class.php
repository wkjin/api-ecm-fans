<?php
namespace Common\Service;
class ArticleService  extends  CommonService {
    public function __construct(){
        parent::__construct('article');
    }

    public function getArticles($where = [], $pageNow = 1, $pageSize = 999){
        return $this->getList($where, $pageNow, $pageSize);
    }

    public function getArticleDetail($where = []){
        return $this->findItem($where);
    }


}