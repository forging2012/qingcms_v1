<?php
/**
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
if(defined('SECURE_ON') && SECURE_ON){
	//2015.01.31----------------------------
	//禁用post/files/cookie/request
	$_POST	=array();
	$_FILES	=array();
	$_COOKIE=array();
	$_REQUEST=array();
	//过滤get数据
	include_once __DIR__.'/RequestSecurity.php';
	RequestSecurity::load()->clearData('get');
}
?>