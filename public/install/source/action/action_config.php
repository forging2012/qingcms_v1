<?php
/**
 * 参数配置
 */
if(!defined('IN_INSTALL')){exit ('Access Denied');}
define('IN_INSTALL_CONFIG',true);
$ac=isset($_GET['ac'])?$_GET['ac']:'';

if($ac=='checkinstall' || $ac=='checkdb'){
//检测安装或者检测数据库	
 include dirname(__FILE__).'/action_config_checkinstall.php';
 exit();
}

//删除缓存数据
$_SESSION['TMP_POST']=array();
app::display('config');