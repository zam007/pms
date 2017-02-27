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
    'TMPL_CACHE_ON' => false,//禁止模板编译缓存
    'HTML_CACHE_ON' => false,//禁止静态缓存 
    'APP_DEBUG' => true,//
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
            1 => array( 'key' => 1, 'value' =>'城镇' ),
            2 => array( 'key' => 2, 'value' =>'乡村' ),
        ),
        //性别
        'sex' => array(
            0 => array( 'key' => 0, 'value' =>'男' ),
            1 => array( 'key' => 1, 'value' =>'女' ),
        ),
        //团体性质
        'nature' => array(
            0 => array( 'key' => 0, 'value' =>'爱好' ),
            1 => array( 'key' => 1, 'value' =>'公司' ),
            2 => array( 'key' => 2, 'value' =>'学校' ),
            3 => array( 'key' => 3, 'value' =>'社团' ),
        ),
        //行业属性
        'attribute' => array(
            0 => array( 'key' => 0, 'value' =>'餐饮' ),
            1 => array( 'key' => 1, 'value' =>'金融' ),
            2 => array( 'key' => 2, 'value' =>'IT' ),
            3 => array( 'key' => 3, 'value' =>'建筑' ),
        ),
        //试题分类
        'questionType' => array(
            0 => array( 'key' => 0, 'value' =>'文字' ),
            1 => array( 'key' => 1, 'value' =>'图片' ),
            2 => array( 'key' => 2, 'value' =>'语音' ),
            3 => array( 'key' => 3, 'value' =>'视频' ),
        ),
    ),
    
);
