<?php

$config=array(
		'SHOW_PAGE_TRACE'  	=> false,
		'LANG_SWITCH_ON' 	=> true,
		// 默认语言
		'DEFAULT_LANG' 		=> 'zh-cn',
		// 自动侦测语言
		'LANG_AUTO_DETECT' 	=> false,
		// 必须写可允许的语言列表
		'LANG_LIST'			=>'zh-cn',
		//设置默认主题,不能再使用,config配置会缓存到~runtime.php文件不能及时更新
		'DEFAULT_THEME'    	=> 'default',
		//模板替换
		TMPL_PARSE_STRING  =>array(
				// 更改默认的__PUBLIC__ 替换规则
				'__PUBLIC__' => __QCROOT__.'/public',
				'__STATIC__' => __QCROOT__.'/static',
				// 图片储存目录
				'__PIC__' 	 => __QCROOT__.'/data/uploads',
				
				// 当前风格路径，会在init中动态改变，否则只有删除~runtime.php才会更新，模板定位就会出错
// 				'__THEMES__' => __QCROOT__.'/themes/'.THEME_DEFAULT,
		)
);

$array = (array)require_once( PATH_CONFIG.'/config.inc.php' );
$array = array_merge( $config,$array );
return $array;
?>