<?php
return array(
    // 添加数据库配置信息
    'DB_TYPE'=>'mysql',// 数据库类型
//    'DB_HOST'=>'112.74.73.22',// 服务器地址
    'DB_HOST'=>'localhost',// 服务器地址
    'DB_NAME'=>'pms',// 数据库名
    'DB_USER'=>'pms',// 用户名
    'DB_PWD'=>'fqtmms136',// 密码
    'DB_PORT'=>3306,// 端口
    'DB_PREFIX'=>'',// 数据库表前缀
    'DB_CHARSET'=>'utf8',// 数据库字符集
    
    // 配置邮件发送服务器
    'MAIL_HOST' =>'smtp.exmail.qq.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    'MAIL_USERNAME' =>'39623162@qq.com',//你的邮箱名
    'MAIL_FROM' =>'39623162@qq.com',//发件人地址
    'MAIL_FROMNAME'=>'聚丰集团',//发件人姓名
    'MAIL_PASSWORD' =>'fengbo81859149',//邮箱密码
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
);