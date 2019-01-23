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