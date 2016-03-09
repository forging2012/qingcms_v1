<?php
//域名跳转
//if($_SERVER['HTTP_HOST']!='www.qingcms.com'){
//  $url='http://www.qingcms.com/';
//  header("Location:$url");
//  exit();
//}


define("IN_WWW"			,true);
define('APP_DEBUG'		,false);
define('APP_ERROR_LEVEL',E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);
define("SECURE_ON"		, true);

define('APP_NAME'		, 'home');
define('APP_PATH'		, __DIR__.'/');
define('RUNTIME_PATH'	, __DIR__.'/../~runtime/~home/');
define('TMPL_PATH'		, __DIR__.'/../themes/');			//项目模板目录

//默认的主题
//主题定义必须先于载入thinkphp
if(!defined('THEME_DEFAULT')){
	define('THEME_DEFAULT', 'default');
}
// 加载框架入口文件
require_once(__DIR__.'/../source/QingCms.php');
?>