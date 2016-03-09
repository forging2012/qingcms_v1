<?php
/**
 * QingCms安装程序;路由
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
if(!defined('USE_INSTALL')) {
	exit('Access Denied');
}
header('Content-Type: text/html; charset=utf-8');
session_start();

define('IN_INSTALL',true);
defined('DS') 			  or define('DS',DIRECTORY_SEPARATOR);
defined('INSTALL_DEBUG')  or define('INSTALL_DEBUG' ,true);
//错误报告等级，后面的设置会覆盖前面的设置
if(INSTALL_DEBUG){
	if(defined('INSTALL_ERROR_LEVEL')){
		//自定义报告等级:define('APP_ERROR_LEVEL',E_ALL ^ E_NOTICE ^ E_WARNING)
		error_reporting(INSTALL_ERROR_LEVEL);
	}else{
		error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^E_DEPRECATED);
	}
}else{
	error_reporting(0);
}

defined('PATH_INSTALL') or define('PATH_INSTALL', realpath(dirname(__FILE__)) );   //安装根路径
defined('PATH_DATA')    or define('PATH_DATA',    PATH_INSTALL.'/data' );   
defined('PATH_SOURCE')  or define('PATH_SOURCE',  PATH_INSTALL.'/source' );
defined('PATH_TPL')     or define('PATH_TPL',     PATH_SOURCE.'/tpl' );   	       //视图路径
defined('PATH_ACTION')  or define('PATH_ACTION',  PATH_SOURCE.'/action' );

//访问相对根路径
if(dirname($_SERVER['SCRIPT_NAME'])==DS){
	$rootpath='/';
}else{
	$rootpath=dirname($_SERVER['SCRIPT_NAME']);
}
//网站根目录，包含域名，http://localhost/qingphp/
defined('__INSTALL__')  or define('__INSTALL__','http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);
defined('__ROOT__')     or define('__ROOT__','http://'.$_SERVER['HTTP_HOST'].$rootpath);
defined('__STATIC__')   or define('__STATIC__',__ROOT__.'/static');

require_once(PATH_SOURCE.'/config.php');//安装配置
require_once(PATH_SOURCE.'/function.php');
require_once(PATH_SOURCE.'/app.php');

//-----------------------------------------------------------------------------

if(file_exists($_CONFIG['lockFile'])){
	exit(" 程序已安装，如果你确定要重新安装，请先从FTP中删除 install/".$_CONFIG['lockFile']."！");
}
$step_list				=array();
$step_list['index']		=array('许可协议',1);
$step_list['env']  		=array('环境检测',2);
$step_list['config']	=array('参数配置',3);
$step_list['installing']=array('正在安装',4);
$step_list['done']		=array('安装完成',5);

//判断步骤是否合法
$step=isset($_GET['step']) && !empty($_GET['step'])?$_GET['step']:'index';
//检测是否跨步
$step_id_save=$_SESSION['TMP_STEPID'];	//上一步
$step_id_curr=(int)$step_list[$step][1];//当前步
$step_file=PATH_SOURCE."/action/action_{$step}.php";
if($step_id_curr<=$step_id_save+1 && key_exists($step,$step_list) && is_file($step_file)){//限制只能向前跨一步
	//保存当前步状态
	$_SESSION['TMP_STEPID']=$step_id_curr;
}else{
	//跨步，返回上一步
	exit("<script>history.go(-1);</script>");	
}
app::assign('step_id_curr',$step_id_curr);
app::assign('step_list',$step_list);
app::assign('step_curr',$step);
app::assign('_CONFIG',$_CONFIG);
include $step_file;
