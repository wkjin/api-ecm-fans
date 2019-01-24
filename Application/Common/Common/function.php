<?php
//这是是公共的函数库

//快速连接service的方法
function CS($serviceName){
    return D($serviceName, 'Service', 'Common');
}

//定义返回的数据（$status 1、成功 0失败 -1系统失败）
function toReturnData($status = 1,$data = array(), $message = ''){
    return array(
        'status'    => $status,
        'data'      => $data,
        'message'   => $message
    );
}

//定义成功的返回
function toSuccessData($data = array(), $message = '操作成功'){
    return toReturnData(1, $data, $message);
}

//定义失败的返回
function toErrorData($data = array(), $message = '操作失败'){
    return toReturnData(0, $data, $message);
}

//定义失败的返回
function toSysErrorData($data = array(), $message = '系统错误'){
    return toReturnData(-1, $data, $message);
}

//把数组进行层级堆叠与排序
function toLayer($data, $idColumn = 'id', $sortColumn='sort', $order='desc', $childName='_child', $pidColumn='pid', $pidStart = 0){
    if(!is_array($data)){
        return null;
    }
    $treeData = [];
    foreach ($data as $key => $value){
        if(intval($pidStart) === intval($value[$pidColumn])){
            $value[$childName] = toLayer($data, $idColumn, $sortColumn, $order, $childName, $pidColumn, $value[$idColumn]);
            $treeData[intval($value[$sortColumn])] = $value;
        }
    };
    if($order === 'desc'){
        ksort($treeData);
    }else{
        krsort($treeData);
    }
    return array_values($treeData);
}