<?php
namespace Admin\Controller;
use Think\Exception;

class ArticleController extends CheckController {
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
        $pageSize = I('get.pageSize', 10, 'int');
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

    //保存文章信息（如果有文章id，那么就是修改，没有文章id，那么就是添加）
    public function saveArticle(){
        $data = $_POST;//获取所有提交的信息
        if(is_array($data)){
            $id = isset($data['id'])? intval($data['id']): 0;
            unset($data['id']);
            $handleName = '';//操作的类型
            if($id > 0 ){//修改
                $res = $this->updateArticle($id, $data);
                $handleName = '修改';
            }else{//新增
                $res = $this->addArticle($data);
                $handleName = '新增';
            }
            if(is_bool($res)){
                $this->ajaxError(null, $handleName . '文章提交的数据不正确');
            }else if($res > 0){
                $this->ajaxSuccess(null, $handleName . '文章成功');
            }else{
                $this->ajaxError(null, $handleName . '文章失败');
            }
            $this->ajaxSuccess($data, '保存文章信息成功');
        }else{
            $this->ajaxError(null, '提交的数据为空或者数据格式不正确');
        }
    }

    private function updateArticle($articleId, $data){
        if(is_int($articleId) && $articleId > 0 ){
            return $this->articleService->updateArticle(['id' => $articleId], $data);
        }else{
            return false;
        }
    }

    private function addArticle($data){
        if(is_array($data)){
            return $this->articleService->addArticle($data);
        }else{
            return false;
        }
    }

    //删除文章
    public function removeArticle($articleId){
        $articleId = intval($articleId);
        if($articleId > 0){
            $res = $this->articleService->removeArticle(['id' => $articleId]);
            if(is_bool($res)){
                $this->ajaxError('文章id不正确');
            }else{
                $this->ajaxSuccess(null, '删除文章成功');
            }
        }else{
            $this->ajaxError('文章id不正确');
        }
    }
}