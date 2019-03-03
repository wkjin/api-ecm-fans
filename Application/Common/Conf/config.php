<?php
return array(
    'DB_TYPE'   => 'mysqli', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址
    'DB_NAME'   => 'enterprise_haoyuan', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'Mysql!!2018', // 密码
    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => 's_', // 数据库表前缀 
    'DB_CHARSET'=> 'utf8', // 字符集
    'DB_DEBUG'  =>  TRUE, // 数据库调试模式 开启后可以记录SQL日志

    'SESSION_TYPE' => 'DB',//将session保存到数据库（解决权限问题）

    //邮件发送配置
    'THINK_EMAIL' => array(
        'SMTP_HOST' => 'smtp.163.com', //SMTP服务器
        'SMTP_PORT' => '465', //SMTP服务器端口
        'SMTP_USER' => 'a573807412@163.com', //SMTP服务器用户名
        'SMTP_PASS' => 'a573807412', //SMTP服务器密码
        'FROM_EMAIL' => 'a573807412@163.com', //发件人EMAIL
        'FROM_NAME' => 'wkj', //发件人名称
        'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
        'REPLY_NAME' => 're_wkj', //回复名称（留空则为发件人名称）
    ),
);