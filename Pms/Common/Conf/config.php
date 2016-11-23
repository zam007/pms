<?php
return array(
	//设定默认访问目录
	'DEFAULT_MODULE' => 'Home',
	//URL不区分大小写
	'URL_CASE_INSENSITIVE'  =>  true,
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

//        'LOG_RECORD'=>true, // 进行日志记录 
//        'LOG_RECORD_LEVEL' => array('EMERG','ALERT','CRIT','ERR','WARN','NOTIC','INFO','DEBUG','SQL'), // 允许记录的日志级别 
//        'DB_FIELDS_CACHE'=> false, //数据库字段缓存 
//        'SHOW_RUN_TIME'=>true, // 运行时间显示 
//        'SHOW_ADV_TIME'=>true, // 显示详细的运行时间 
//        'SHOW_DB_TIMES'=>true, // 显示数据库查询和写入次数 
//        'SHOW_CACHE_TIMES'=>true, // 显示缓存操作次数 
//        'SHOW_USE_MEM'=>true, // 显示内存开销 
//        'SHOW_PAGE_TRACE'=>true, // 显示页面Trace信息 由Trace文件定义和Action操作赋值 
//        'APP_FILE_CASE' => true, // 是否检查文件的大小写 对Windows平台有效 );
    
    'constant' => array(
        //来源地
        'from' => array(
            0 => '城镇',
            1 => '乡村',
        ),
        //团体性质
        'nature' => array(
            0 => '公司性质1',
            1 => '公司性质2',
            2 => '公司性质3',
            3 => '公司性质4',
        ),
        //行业属性
        'attribute' => array(
            0 => '行业属性1',
            1 => '行业属性2',
            2 => '行业属性3',
            3 => '行业属性4',
        ),
    ),
    
);
