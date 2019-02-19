<?php
namespace Common\Service;
use Think\Model;
class CommonService  extends  Model{
    protected $tableObj = null;//数据库对象

    public function __construct($tableName){
        parent::__construct();
        $this->tableObj = M($tableName);
    }

    //获取一个列表
    protected function getList($where = [], $pageNow = 1, $pageSize = 999, $order = 'id desc'){
        $startRow = ($pageNow-1) * $pageSize;
        return $this->tableObj->where($where)->order($order)->limit($startRow.','.$pageSize)->select();
    }

    //查找一个项
    protected function  findItem($where = []){
        return $this->tableObj->where($where)->find();
    }

    //更新
    protected function update($condition, $data){
        return $this->tableObj->where($condition)->save($data);
    }

    //插入数据到数据表
    protected function insert($data){
        return $this->tableObj->add($data);
    }

    public function getLastSql(){
        return $this->tableObj->getLastSql();
    }

    //移除
    protected function remove($condition){
        return $this->tableObj->where($condition)->delete();
    }
}