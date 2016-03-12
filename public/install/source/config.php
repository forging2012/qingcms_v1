<?php
/**
 * 各项配置
 * @author xiaowang <736523132@qq.com>
 * @copyright 2013 http://qingcms.com All rights reserved.
 */
$_CONFIG=array(
	'lockFile'=>'install_lock.txt', 			//锁定安装文件
	'site'	  =>'http://www.qingcms.com/',		//官网
	'bbs'	  =>'http://www.dev234.com/',    	//论坛
	'weibo'	  =>'http://weibo.com/xiaowangzh'
);
//数据库默认设置
$db_default=array();
$db_default['db_host']  ='localhost';
$db_default['db_user']	='root';
$db_default['db_pwd']	='';
$db_default['db_name']	='qingcms_open';
$db_default['db_prefix']='pre_';
$_CONFIG['db_default']  =$db_default;
//数据表文件
$_CONFIG['file_table']  =PATH_DATA.'/db_table.sql';
//数据文件
$_CONFIG['file_data']  	=PATH_DATA.'/db_data.sql';
//要安装的应用目录
$_CONFIG['path_app']	=realpath(PATH_INSTALL.'/../..');
//应用配置要保存的位置
$_CONFIG['path_config'] =$_CONFIG['path_app'].'/source/config/config.db.php';
//qingcms版本
$_CONFIG['version'] 	='QingCms V1.0 UTF-8';     					
