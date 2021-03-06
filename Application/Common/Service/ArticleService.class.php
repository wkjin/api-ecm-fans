<?php
namespace Common\Service;
class ArticleService  extends  CommonService {
    public function __construct(){
        parent::__construct('article');
    }

    //做联合查询用
    private function tj(){
        $this->tableObj = $this->tableObj
            -> alias('a')
            ->join('left join __CATEGORY__ as c on c.id = a.category_id')
            ->field('a.*,c.name_cn as c_name_cn, c.name_en as c_name_en');
        return $this;
    }

    public function getArticles($where = [], $pageNow = 1, $pageSize = 999){
        if(!is_array($where)){
            $where = [];
        }
        $where['is_article'] = 1;//只是获取文章信息，如果不是文章的不用获取
        return $this->tj()->getList($where, $pageNow, $pageSize,array('sort' => 'asc','id'=>'desc'));
    }

    public function getArticleDetail($where = []){
        foreach ($where as $key => $value){
            unset($where[$key]);
            $where['a.' . $key] = $value;
        }
        return $this->tj()->findItem($where);
    }

    public function updateArticle($condition, $data){
        if(empty($condition) || !is_array($condition)){
            return false;
        }
        $data = empty($data)?[]: $data;
        $data['update_time'] = time();
        if(isset($data['content_cn'])){
            $data['abstract_cn'] = $this->getAbstract($data['content_cn']);
        }
        if(isset($data['content_en'])){
            $data['abstract_en'] = $this->getAbstract($data['content_en']);
        }
        return $this->update($condition, $data);
    }

    public function addArticle($data){
        if(empty($data) || !is_array($data)){
            return false;
        }
        $data['update_time'] = $data['create_time'] = time();
        if(!isset($data['is_article'])){
            $data['is_article'] = 1;//是文章
        }
        if(isset($data['content_cn'])){
            $data['abstract_cn'] = $this->getAbstract($data['content_cn']);
        }
        if(isset($data['content_en'])){
            $data['abstract_en'] = $this->getAbstract($data['content_en']);
        }
        return $this->insert($data);
    }

    //获取文章的简介
    private function getAbstract($content){
        return substr(str_replace('&nbsp;', ' ', strip_tags($content)), 0, 360);
    }

    public function removeArticle($condition){
        return $this->remove($condition);
    }
}