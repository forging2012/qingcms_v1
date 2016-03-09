<?php
/**
 * 环境检测
 */
if(!defined('IN_INSTALL')){exit ('Access Denied');}

//服务器信息
$serverInfo				  = array();
$serverInfo ['服务器域名：']  = $_SERVER['SERVER_NAME'];
$serverInfo ['服务器软件：']  = $_SERVER ['SERVER_SOFTWARE'];
$serverInfo ['服务器系统：']  = PHP_OS;
$serverInfo ['PHP版本：']   = PHP_VERSION;
$serverInfo ['应用安装目录：'] = $_CONFIG['path_app'];
if(function_exists('disk_free_space')){
	$serverInfo ['安装目录空间：'] = floor( disk_free_space($_CONFIG['path_app'])/(1024*1024) ).'M';
}else{
	$serverInfo ['安装目录空间：'] = 'unknow';
}
//环境检测
$list_checkenv=array();
$mysql=function_exists('mysql_connect')?icon_on().mysql_get_server_info():icon_off();

$gd=gd_info();	
$gd=isset($gd['GD Version'])?icon_on().$gd['GD Version']:icon_off();
$up=(@ini_get('file_uploads'))?icon_on().ini_get('upload_max_filesize'):icon_off();

$list_checkenv['MySQL 支持']=$mysql.'&nbsp;&nbsp;(不支持程序无法使用)';
$list_checkenv['GD 库']    =$gd.'&nbsp;&nbsp;(不支持将导致部分功能无法使用，如：验证码功能)';
$list_checkenv['附件上传']   =$up.'&nbsp;&nbsp;(不支持程序无法上传图片、附件)';

//目录、文件权限检查
$list_checkauth=array(
	$_CONFIG['path_app'].'/data',	
	$_CONFIG['path_app'].'/../~runtime',
	//$_CONFIG['path_app'].'/../config.inc.php'
);

app::assign('serverInfo',$serverInfo);
app::assign('list_checkenv',$list_checkenv);
app::assign('list_checkauth',$list_checkauth);
app::display('env');
	