<?php
namespace Home\Controller;
class CategoryController extends CommonController {
    private $categoryService = null;//使用的service对象（一个model）

    public function __construct() {
        parent::__construct();
        //初始化对象
        $this->categoryService = CS('Category');//定义使用service
    }

    //内部获取栏目信息
    private function _getCategorys($condition = []){
        $condition['status'] = 1;
        return $this->categoryService->getCategorys($condition);//获取生效的栏目
    }

    //获取栏目信息(按照子菜单形式)
    public function getCategorys(){
        $data = $this->_getCategorys();
        if(!is_bool($data)){
            $this->ajaxSuccess(toLayer($data), '获取栏目列表成功');
        }else{
            $this->ajaxError([], '获取栏目列表失败');
        }
    }

    public function getCategorysByOrCondition(){
        $condition = I('get.');
        $condition['_logic'] = 'or';
        $map['_complex'] = $condition;
        $data = $this->_getCategorys($condition);
        if(!is_bool($data)){
            $this->ajaxSuccess(toLayer($data), '获取栏目列表成功');
        }else{
            $this->ajaxError([], '获取栏目列表失败');
        }
    }

    //获取栏目信息（根据条件）
    public function getCategorysByCondition(){
        $data = $this->_getCategorys(I('get.'));
        if(!is_bool($data)){
            $this->ajaxSuccess($data, '获取栏目列表成功');
        }else{
            $this->ajaxError([], '获取栏目列表失败');
        }
    }
}