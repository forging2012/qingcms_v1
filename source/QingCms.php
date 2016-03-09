<?php
/**
 * QingCms初始化
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
if(!defined('IN_WWW')) {
	exit('Access Denied');
}
defined('DS') 		  or define("DS",DIRECTORY_SEPARATOR);

//错误报告等级，后面的设置会覆盖前面的设置
if(defined('APP_DEBUG') && APP_DEBUG){
	if(defined('APP_ERROR_LEVEL')){
		//自定义报告等级:define('APP_ERROR_LEVEL',E_ALL ^ E_NOTICE ^ E_WARNING)
		error_reporting(APP_ERROR_LEVEL);
	}else{
		//error_reporting(E_ALL);
		error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
// 		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^E_DEPRECATED);
	}
	//在窗口显示错误信息
	@ini_set("display_errors","On");
}else{
	//保存到服务器日志的错误等级
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
	//关闭在窗口显示错误信息
	@ini_set("display_errors","Off");
}

// 调试时 编译代码不去除空格
define ( 'STRIP_RUNTIME_SPACE',false);

// ****************定义核心目录**********************

//.是相对于当前访问的php文件index.php=dirname(__FILE__).'/..'
defined( 'PATH_ROOT')  or define('PATH_ROOT' ,realpath('./..'));

define ( 'PATH_PUBLIC'		,PATH_ROOT.'/public' );
define ( 'PATH_DATA'		,PATH_PUBLIC.'/data' );
define ( 'PATH_UPLOADS'		,PATH_DATA.'/uploads' );//上传目录
define ( 'PATH_THEMES'	  	, PATH_ROOT.'/themes' );	//主题文件夹路径（*），用于admin检测存在的主题
define ( 'PATH_RUNTIME'		, PATH_ROOT.'/~runtime' );

define ( 'PATH_SOURCE'		, PATH_ROOT.'/source' );
define ( 'PATH_PLUGIN'		, PATH_SOURCE.'/plugin' );
define ( 'PATH_CONFIG'		, PATH_SOURCE.'/config' );
define ( 'PATH_LIB'			, PATH_SOURCE.'/lib' );
define ( 'PATH_FILTER'		, PATH_SOURCE.'/filter' );
define ( 'PATH_CLASS'		, PATH_SOURCE.'/class' );
define ( 'PATH_FUNCTION'	, PATH_SOURCE.'/function' );

// 加载公共 函数库
require_once(PATH_FUNCTION."/common.php");
require_once(PATH_FUNCTION."/function.php");
//加载类
require_once(PATH_LIB."/plugin/AbstractPlugins.class.php");
require_once(PATH_LIB."/plugin/Plugins.class.php");
require_once(PATH_LIB."/plugin/Hooks.class.php");

//自动加载映射
$autoloads=array();
$autoloads['Request']		=PATH_LIB."/Request.php";
$autoloads['StaticPlugin']	=PATH_LIB."/StaticPlugin.php";
$autoloads['UrlHelper']   	=PATH_LIB."/UrlHelper.php";
$autoloads['Filter'] 		=PATH_FILTER."/Filter.php";
$autoloads['Validator'] 	=PATH_FILTER."/Validator.php";

// 注册AUTOLOAD方法；可以使用spl_autoload_register注册多个处理方法
spl_autoload_register('qc_autoload');
function qc_autoload($fullClass){
	global $autoloads;
	if(key_exists($fullClass,$autoloads)){
		require_once($autoloads[$fullClass]);
	}
}
//#缓存
require_once(__DIR__.'/Temp.php');
if(APP_NAME=='home'){
	Temp::initTemp();
}
//#全局url
require_once(__DIR__.'/UrlGenerator.php');
UrlGenerator::defineGlobalUrl();
//#安全
require_once __DIR__.'/Secure.php';

require_once(__DIR__.'/ThinkPHP/ThinkPHP.php');
?>