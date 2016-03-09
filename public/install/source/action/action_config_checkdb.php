<?php
/**
 * 检查数据库
 */
if(!defined('IN_INSTALL_CONFIG')){exit ('Access Denied');}

$res_checkdb=array();
if(empty($db_host) || empty($db_user) || empty($db_name)){
	$res_checkdb['success']=0;//错误
	$res_checkdb['message']=icon_off("请完善数据库信息");
}else{
	$conn=mysql_connect($db_host,$db_user,$db_pwd);
	if($conn){
		$dbExist=mysql_select_db($db_name,$conn);
		if($dbExist){
			$res_checkdb['success']=1;//成功
			$res_checkdb['confirm']="数据库{$db_name}已经存在，系统将覆盖数据库，确认继续？";//需要确认
			$res_checkdb['message']=icon_off("数据库{$db_name}已经存在，系统将覆盖数据库");
		}else{
			$res_checkdb['success']=1;//成功
			$res_checkdb['message']=icon_on("数据库{$db_name}不存在,系统将自动创建");
		}
	}else{
		$res_checkdb['success']=0;//错误
		$res_checkdb['message']=icon_off('数据库连接失败！数据库用户名或密码有误');
	}
	@mysql_close($conn);
}
