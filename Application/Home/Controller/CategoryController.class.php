<?php
namespace Home\Controller;
class CategoryController extends CommonController {
    private $categoryService = null;//使用的service对象（一个model）

    public function __construct() {
        parent::__construct();
        //初始化对象
        $this->categoryService = CS('Category');//定义使用service
    }

    //获取栏目信息
    public function getCategorys(){
        $data = $this->categoryService->getCategorys(array('status' => 1));//获取生效的栏目
        if(!is_bool($data)){
            $this->ajaxSuccess($data, '获取栏目列表成功');
        }else{
            $this->ajaxError([], '获取栏目列表失败');
        }
    }
}