<?php
return array(
'URL_ROUTER_ON'         => true,// 开启路由
'URL_MODEL'             =>  2,//(REWRITE  模式)

'URL_ROUTE_RULES' => array( //定义路由规则
    'new/:name'    => 'Home/Index/hello',
	),

);