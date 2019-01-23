<?php
namespace Home\Controller;
class FragmentController extends CommonController {
    private $fragmentService = null;//使用的service对象（一个model）

    public function __construct() {
        parent::__construct();
        //初始化对象
        $this->fragmentService = CS('Fragment');//定义使用service
    }

    //获取碎片信息
    public function getFragments(){
        $data = $this->fragmentService->getFragments();
        if(!is_bool($data)){
            $this->ajaxSuccess($data, '获取碎片列表成功');
        }else{
            $this->ajaxError([], '获取碎片列表失败');
        }
    }
}