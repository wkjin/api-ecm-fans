<?php
namespace Home\Controller;
use Think\Exception;
class ProductController extends CommonController {
    private $productService = null;//使用的service对象（一个model）

    public function __construct() {
        parent::__construct();
        //初始化对象
        $this->productService = CS('Product');//定义使用service
    }

    //获取文章列表
    public function getProducts(){
        $category_id = I('get.category_id', 0, 'int');
        $where = [];//查询条件
        if($category_id > 0){
            $where['category_id'] = intval($category_id);
        }
        $condition = I('get.');
        unset($condition['category_id']);
        foreach ($condition as $key => $value){
            if(empty($value)){
                unset($condition[$key]);
            }
        }
        if(count($condition) > 0){
            $where = array_merge($condition, $where);
        }
        $pageNow = I('get.pageNow', 1, 'int');
        $pageSize = I('pageSize', 10, 'int');
        $data = $this->productService->getProducts($where, $pageNow, $pageSize);
        if(!is_bool($data)){
            $this->ajaxSuccess($data, '获取产品列表成功');
        }else{
            $this->ajaxError([], '获取产品列表失败');
        }
    }

    //获取文章的详情
    public function getProductDetail(){
        $category_id = I('get.category_id',0, 'int');
        $article_id = I('get.article_id', 0, 'int');
        $product_id = I('get.product_id', 0, 'int');
        $where = [];
        if($category_id <= 0 && $article_id <=0 && $product_id <= 0){
            $this->ajaxError([], '栏目id、文章id、产品id之一才能获取到文章详情');
            return;
        }else if($product_id > 0){
            $where['p.id'] = $product_id;
        }else if($article_id > 0){
            $where['$article_id'] = $article_id;
        }else if($category_id > 0){
            $where['category_id'] = $category_id;
        }else{
            throw new Exception('无知错误');
        }
        $res = $this->productService->getProductDetail($where);
        if(is_array($res)){
            $this->ajaxSuccess($res, '获取产品文章详情成功');
        }else{
            $this->ajaxError(['condition' => $where], '获取产品详情失败');
        }
    }
}