<?php
namespace Common\Service;
use Think\Model;
class CommonService  extends  Model{
    protected $tableObj = null;//数据库对象

    public function __construct($tableName){
        parent::__construct();
        $this->tableObj = M($tableName);
    }

    protected function getList($where = [], $pageNow = 1, $pageSize = 999){
        $startRow = ($pageNow-1) * $pageSize;
        return $this->tableObj->where($where)->limit($startRow.','.$pageSize)->select();
    }


    protected function  findItem($where = []){
        return $this->tableObj->where($where)->find();
    }
}