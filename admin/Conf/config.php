<?php
$config = array (
		'SHOW_PAGE_TRACE'  	=> false,
        'LANG_SWITCH_ON' 	=> true,
		// 默认语言
        'DEFAULT_LANG' 		=> 'zh-cn',
		// 自动侦测语言
        'LANG_AUTO_DETECT' 	=> false,
		//必须写可允许的语言列表
        'LANG_LIST'			=>'zh-cn',
		//设置默认主题
		'DEFAULT_THEME'    => 'default', 
		//模板替换
		TMPL_PARSE_STRING  =>array(
				'__PUBLIC__' => __QCROOT__.'/public',
				'__STATIC__' => __QCROOT__.'/static',
				// 图片储存目录
				'__PIC__' 	 => __QCROOT__.'/data/uploads',
		)
);

$array = (array)require_once( PATH_CONFIG.'/config.inc.php' );
$array = array_merge( $config,$array );
return $array;
?>