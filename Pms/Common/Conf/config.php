<?php
return array(
	//设定默认访问目录
	'DEFAULT_MODULE' => 'Home',
	// 开启路由
	'URL_ROUTER_ON'         => true,
	//(REWRITE  模式)
	'URL_MODEL'             =>  2,
	//定义路由规则
	'URL_ROUTE_RULES' => array(
	    'new/:name' => 'Home/Index/hello',
	),
	//加密字符
	'PWD_KEY'=>'wuxingcaishang',
);