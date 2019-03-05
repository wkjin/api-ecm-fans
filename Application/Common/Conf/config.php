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
    'DB_DEBUG'  =>  false, // 数据库调试模式 开启后可以记录SQL日志

    //邮件发送配置
    'THINK_EMAIL' => array(
        'SMTP_HOST' => 'smtp.exmail.qq.com', //SMTP服务器
        'SMTP_PORT' => '465', //SMTP服务器端口
        'SMTP_USER' => 'sent@ecm-fans.com', //SMTP服务器用户名
        'SMTP_PASS' => 'Sent1234', //SMTP服务器密码
        'FROM_EMAIL' => 'sent@ecm-fans.com', //发件人EMAIL
        'FROM_NAME' => 'sent@ecm-fans', //发件人名称
        'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
        'REPLY_NAME' => 're_sent@ecm-fans', //回复名称（留空则为发件人名称）
    ),
);