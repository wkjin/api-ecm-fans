<?php
namespace Admin\Controller;
use Think\Exception;
class ProductController extends CheckController {
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
        $pageNow = I('get.pageNow', 1, 'int');
        $pageSize = I('pageSize', 10, 'int');
        $data = $this->productService->getProducts($where, $pageNow, $pageSize);
        if(!is_bool($data)){
            $this->ajaxSuccess($data, '获取产品列表成功');
        }else{
            $this->ajaxError([], '获取产品列表失败');
        }
    }

    //保存产品信息（如果有产品id，那么就是修改，没有产品id，那么就是添加）
    public function saveProduct(){
        $data = $_POST;//获取所有提交的信息
        if(is_array($data)){
            $id = isset($data['id'])? intval($data['id']): 0;
            unset($data['id']);
            $handleName = '';//操作的类型
            if($id > 0 ){//修改
                $res = $this->updateProduct($id, $data);
                $handleName = '修改';
            }else{//新增
                $res = $this->addProduct($data);
                $handleName = '新增';
            }
            if(is_bool($res)){
                $this->ajaxError(null, $handleName . '产品提交的数据不正确');
            }else if($res > 0){
                $this->ajaxSuccess(null, $handleName . '产品成功');
            }else{
                $this->ajaxError(null, $handleName . '产品失败');
            }
            $this->ajaxSuccess($data, '保存产品信息产品成功');
        }else{
            $this->ajaxError(null, '提交的数据为空或者数据格式不正确');
        }
    }

    private function updateProduct($productId, $data){
        if(is_int($productId) && $productId > 0 ){
            return $this->productService->updateProduct($productId, $data);
        }else{
            return false;
        }
    }

    private function addProduct($data){
        if(is_array($data)){
            return $this->productService->addProduct($data);
        }else{
            return false;
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

    //删除文章
    public function removeProduct($productId){
        $productId = intval($productId);
        if($productId > 0){
            $res = $this->productService->removeProduct(['id' => $productId]);
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