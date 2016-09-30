<?php
return array(
    // 添加数据库配置信息
    'DB_TYPE'=>'mysql',// 数据库类型
//    'DB_HOST'=>'112.74.73.22',// 服务器地址
    'DB_HOST'=>'127.0.0.1',// 服务器地址
    'DB_NAME'=>'pms',// 数据库名
    'DB_USER'=>'pms',// 用户名
    'DB_PWD'=>'fqtmms136',// 密码
    'DB_PORT'=>3306,// 端口
    'DB_PREFIX'=>'',// 数据库表前缀
    'DB_CHARSET'=>'utf8',// 数据库字符集
    // 配置邮件发送服务器
    'MAIL_HOST' =>'smtp.163.com',//smtp服务器的名称
    'MAIL_SMTPAUTH' =>TRUE, //启用smtp认证
    'MAIL_USERNAME' =>'wuxingcaishang@163.com',//你的邮箱名
    'MAIL_FROM' =>'wuxingcaishang@163.com',//发件人地址
    'MAIL_FROMNAME'=>'五行财商',//发件人姓名
//    'MAIL_PASSWORD' =>'123456abc',//邮箱密码
    'MAIL_PASSWORD' =>'wxcs16',//邮箱密码-smtp授权码
    'MAIL_CHARSET' =>'utf-8',//设置邮件编码
    'MAIL_ISHTML' =>TRUE, // 是否HTML格式邮件
    
    //发送短信账户信息
    'WINIC_UID'=>"wuxing" ,//账号
    'WINIC_PWD'=>"wuxing123456",//密码

    //模板文件后缀名（U方法的伪静态）
    'URL_HTML_SUFFIX' => '.html',
    'URL_MODLE' => 1,
);