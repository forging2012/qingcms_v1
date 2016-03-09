<?php
/**
 * 执行安装
 * 提交安装信息，开始安装
 */
if(!defined('IN_INSTALL_CONFIG')){exit ('Access Denied');}

$db_host  =$_POST['db_host'];
$db_user  =$_POST['db_user'];
$db_pwd   =$_POST['db_pwd'];
$db_name  =$_POST['db_name'];
$db_prefix=$_POST['db_prefix'];

//检测数据库
include dirname(__FILE__).'/action_config_checkdb.php';
if($ac=='checkdb'){
	app::message(1,'',$res_checkdb);
	exit();
}
$return=array();
//数据库信息有误
if(!$res_checkdb['success']){
	app::error(strip_tags($res_checkdb['message']));
}
//config.db.php
$dbconfig=file_get_contents(PATH_DATA.'/config.db.php');
$dbconfig = str_replace("[db_host]"  ,$db_host,$dbconfig);
$dbconfig = str_replace("[db_user]"  ,$db_user,$dbconfig);
$dbconfig = str_replace("[db_pwd]"   ,$db_pwd,$dbconfig);
$dbconfig = str_replace("[db_name]"  ,$db_name,$dbconfig);
$dbconfig = str_replace("[db_prefix]",$db_prefix,$dbconfig);
//保存配置文件
$file =$_CONFIG['path_config'];
$fopen=fopen($file,"w");
if(!$fopen){
	app::error('写入配置失败！');
}
fwrite($fopen,$dbconfig);
fclose($fopen);
/**
 * 检测管理员信息
 */
class AdminFilter{
	public $error='';
	/**
	 * 检测email的合格性
	 */
	public function v_email($email){
		if(!preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',$email)){
			$this->error="管理员邮箱格式错误";
			return false;
		}
		return true;
	}
	/**
	 * 验证用户名是否合法
	 * 合法的用户名由2-10位的中英文/数字/下划线/减号组成
	 */
	public function v_nickname($username){
		//中英文/数字/下划线/减号组成
		if(!preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_-]{2,20}$/u",$username)){
			$this->error="管理员用户名由2-20位的中英文/数字/下划线/减号组成";
			return false;
		}
		return true;
	}
	/**
	 * 验证密码是否合格
	 * 密码格式
	 * 1:6~25位
	 * 2：只能包含 字母、数字、下划线
	 * 3：强度检测*
	 */
	public function v_password($pwd){
		//数字和字母和下划线
		if(!preg_match('/^[a-z0-9_@.]{6,40}$/i',$pwd)){
			$this->error="管理员密码由6~40位[字母、数字、下划线、@、.] 组成";
			return false;
		}
		return true;
	}
	public function v_repassword($pwd,$repwd){
		if($pwd!=$repwd){
			$this->error='管理员两次密码不一致';
			return false;
		}
		return true;
	}
}
//验证管理员信息
$email     =$_POST['admin_email'];
$password  =$_POST['admin_password'];
$repassword=$_POST['admin_repassword'];
$nickname  =$_POST['admin_nickname'];
$filter  =new AdminFilter();
if(!$filter->v_email($email)){
	app::error($filter->error);
}
if(!$filter->v_password($password)){
	app::error($filter->error);
}
if(!$filter->v_nickname($nickname)){
	app::error($filter->error);
}
if(!$filter->v_repassword($password,$repassword)){
	app::error($filter->error);
}

//保存合法信息
$_SESSION['TMP_POST']=$_POST;
app::success('通过',array('confirm'=>isset($res_checkdb['confirm'])?$res_checkdb['confirm']:''));
