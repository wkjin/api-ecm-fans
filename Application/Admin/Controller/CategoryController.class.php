<?php
namespace Admin\Controller;
class CategoryController extends CheckController {
    private $categoryService = null;//使用的service对象（一个model）

    public function __construct() {
        parent::__construct();
        //初始化对象
        $this->categoryService = CS('Category');//定义使用service
    }

    //获取栏目信息(按照子菜单形式)
    public function getCategorys(){
        $data = $this->categoryService->getCategorys();//获取生效的栏目
        if(!is_bool($data)){
            $this->ajaxSuccess(toLayer($data), '获取栏目列表成功');
        }else{
            $this->ajaxError([], '获取栏目列表失败');
        }
    }

    //获取栏目信息（根据条件）
    public function getCategorysByCondition(){
        $condition = I('get.');
        $data = $this->categoryService->getCategorys($condition);//获取生效的栏目
        if(!is_bool($data)){
            $this->ajaxSuccess($data, '获取栏目列表成功');
        }else{
            $this->ajaxError([], '获取栏目列表失败');
        }
    }

    //保存栏目信息（如果有栏目id，那么就是修改，没有栏目id，那么就是添加）
    public function saveCategory(){
        $data = $_POST;//获取所有提交的信息
        if(is_array($data)){
            $id = isset($data['id'])? intval($data['id']): 0;
            unset($data['id']);
            $handleName = '';//操作的类型
            if($id > 0 ){//修改
                $res = $this->updateCategory($id, $data);
                $handleName = '修改';
            }else{//新增
                $res = $this->addCategory($data);
                $handleName = '新增';
            }
            if(is_bool($res)){
                $this->ajaxError(null, $handleName . '栏目提交的数据不正确');
            }else if($res > 0){
                $this->ajaxSuccess(null, $handleName . '栏目成功');
            }else{
                $this->ajaxError(null, $handleName . '栏目失败');
            }
            $this->ajaxSuccess($data, '保存栏目信息成功');
        }else{
            $this->ajaxError(null, '提交的数据为空或者数据格式不正确');
        }
    }

    private function updateCategory($categoryId, $data){
        if(is_int($categoryId) && $categoryId > 0 ){
            return $this->categoryService->updateCategory(['id' => $categoryId], $data);
        }else{
            return false;
        }
    }

    private function addCategory($data){
        if(is_array($data)){
            return $this->categoryService->addCategory($data);
        }else{
            return false;
        }
    }
}