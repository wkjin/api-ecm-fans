<?php
namespace Home\Controller;
use Think\Exception;

class ArticleController extends CommonController {
    private $articleService = null;//使用的service对象（一个model）

    public function __construct() {
        parent::__construct();
        //初始化对象
        $this->articleService = CS('Article');//定义使用service
    }

    //获取文章列表
    public function getArticles(){
        $category_id = I('get.category_id', 0, 'int');
        $where = [];//查询条件
        if($category_id > 0){
            $where['category_id'] = intval($category_id);
        }
        $pageNow = I('get.pageNow', 1, 'int');
        $pageSize = I('pageSize', 10, 'int');
        $data = $this->articleService->getArticles($where, $pageNow, $pageSize);
        if(!is_bool($data)){
            $this->ajaxSuccess($data, '获取文章列表成功');
        }else{
            $this->ajaxError([], '获取文章列表失败');
        }
    }

    //获取文章的详情
    public function getArticleDetail(){
        $category_id = I('get.category_id',0, 'int');
        $article_id = I('get.article_id', 0, 'int');
        $where = [];
        if($category_id <= 0 && $article_id <=0){
            $this->ajaxError([], '栏目id、文章id之一才能获取到文章详情');
            return;
        }else if($article_id > 0){
            $where['id'] = $article_id;
        }else if($category_id > 0){
            $where['category_id'] = $category_id;
        }else{
            throw new Exception('无知错误');
        }
        $res = $this->articleService->getArticleDetail($where);
        if(is_array($res)){
            $this->ajaxSuccess($res, '获取文章详情成功');
        }else{
            $this->ajaxError(['condition' => $where], '获取文章详情失败');
        }
    }
}