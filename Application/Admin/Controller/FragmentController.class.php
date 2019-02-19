<?php
namespace Admin\Controller;
class FragmentController extends CheckController {
    private $fragmentService = null;//使用的service对象（一个model）

    public function __construct() {
        parent::__construct();
        //初始化对象
        $this->fragmentService = CS('Fragment');//定义使用service
    }

    //获取碎片信息
    public function getFragments(){
        $data = $this->fragmentService->getFragments();
        if(!is_bool($data)){//把key转化为键值
            foreach ($data as &$value) {
                //字段类型：（1、text 2、textArr 3、img 4、imgArr 5、color 6、textarea）
                switch (intval($value['column_type'])){
                    case 1: $ct = 'text';break;
                    case 2: $ct = 'textArr';break;
                    case 3: $ct = 'img';break;
                    case 4: $ct = 'imgArr';break;
                    case 5: $ct = 'color';break;
                    default: $ct = 'textarea';break;
                }
                $value['column_type'] = $ct;
            }
            unset($value); // 最后取消掉引用
            $this->ajaxSuccess($data, '获取碎片列表成功');
        }else{
            $this->ajaxError([], '获取碎片列表失败');
        }
    }

    //保存碎片信息（如果有碎片id，那么就是修改，没有碎片id，那么就是添加）
    public function saveFragment(){
        $data = $_POST;//获取所有提交的信息
        if(is_array($data)){
            $id = isset($data['id'])? intval($data['id']): 0;
            unset($data['id']);
            $handleName = '';//操作的类型
            if($id > 0 ){//修改
                $res = $this->updateFragment($id, $data);
                $handleName = '修改';
            }else{//新增
                $res = $this->addFragment($data);
                $handleName = '新增';
            }
            if(is_bool($res)){
                $this->ajaxError(null, $handleName . '碎片提交的数据不正确');
            }else if($res > 0){
                $this->ajaxSuccess(null, $handleName . '碎片成功');
            }else{
                $this->ajaxError(null, $handleName . '碎片失败');
            }
            $this->ajaxSuccess($data, '保存碎片信息成功');
        }else{
            $this->ajaxError(null, '提交的数据为空或者数据格式不正确');
        }
    }

    private function updateFragment($fragmentId, $data){
        if(is_int($fragmentId) && $fragmentId > 0 ){
            return $this->fragmentService->updateFragment(['id' => $fragmentId], $data);
        }else{
            return false;
        }
    }

    private function addFragment($data){
        if(is_array($data)){
            return $this->fragmentService->addFragment($data);
        }else{
            return false;
        }
    }
}