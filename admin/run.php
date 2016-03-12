<?php
/**
 * 后台应用入口
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
define("IN_WWW", true);
define('APP_DEBUG'		,false);
define('APP_ERROR_LEVEL',E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);
define("SECURE_ON"		,false);

// 定义项目名称和路径
define('APP_NAME'		, 'admin' );
define('APP_PATH'		, __DIR__.'/');
define('RUNTIME_PATH'	, __DIR__.'/../~runtime/~admin/');

// 加载框架入口文件
require_once(__DIR__.'/../source/QingCms.php');
?>