<?php
/**
 * 支持函数
 * @author xiaowang <736523132@qq.com>
 * @copyright 2013 http://qingcms.com All rights reserved.
 */

/**
 * icon对
 * @param string $msg
 * @return string
 */
function icon_on($msg=''){
	if(!empty($msg)){
		$msg="<font color='green' class=''>{$msg}</font>";
	}
	return '<span class="on"></span>'.$msg;
}
/**
 * icon错
 * @param string $msg
 * @return string
 */
function icon_off($msg){
	if(!empty($msg)){
		$msg="<font color='red' class='stop'>{$msg}</font>";
	}
	return '<span class="off"></span>'.$msg;
}
/**
 * 检测文件或目录是否可读
 */
function isreadable($file){
	//清除缓存并再次检查文件大小
	clearstatcache();
	//本函数的结果会被缓存
	$res=is_readable($file)?true:false;
	return $res?icon_on('可读'):icon_off('不可读');
}
/**
 * 路径可写
 */
function iswriteable($file){
	//清除缓存并再次检查文件大小
	clearstatcache();
	//本函数的结果会被缓存
	$res=is_writeable($file)?true:false;
	return $res?icon_on('可写'):icon_off('不可写');
}
/**
 * 下一步的链接
 * @return string
 */
function url_nextstep($step_curr,$action=''){
	if(empty($action)){
		return __INSTALL__.'?step='.$step_curr;
	}else{
		return __INSTALL__.'?step='.$step_curr.'&ac='.$action;
	}
}
/**
 * css连接
 * @return string
 */
function url_stylesheet(){
	return __STATIC__.'/_cache/main.css';
	//return __STATIC__.'/load/load-styles-main.php?clear=1';
}
/**
 * 执行每行信息
 */
function execute_line($msg,$color=''){
	if(!empty($color)){
		$color=" style='color:{$color}' ";
	}
	$msg="<li {$color}>{$msg}</li>";
	iflush($msg);
}
/**
 * 即时输出提示信息
 * @return
 */
function iflush($msg){
	echo $msg;
	ob_flush();
	flush();
}

function dump($var){
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}
