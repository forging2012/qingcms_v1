<?php 
if(!defined('THINK_PATH')){
	exit();
}

$inc=array (		
		'IDB_PREFIX' 		=> '_PRE_', 		// iDbMysqli替换的表前缀
		'TOKEN_ON' 			=> false, 			// 是否开启令牌验证
		'URL_HTML_SUFFIX' 	=> '.html', 		// 伪静态后缀
		'URL_MODEL' 		=>0, 				//采用传统的URL参数模式
        /**
         * 各个类的存放位置
         */
		'UploadFilePATH'	=>PATH_CLASS.'/ORG/UploadFile.class.php',
		'ImagePATH'			=>PATH_CLASS.'/ORG/Image.class.php',
		'iServicePATH'		=>PATH_CLASS.'/iService.class.php',		
		'Class_iPage'		=>PATH_CLASS.'/iPage.class.php',
);

$db= (array)require_once( __DIR__.'/config.db.php' );
return array_merge($inc,$db);
?>