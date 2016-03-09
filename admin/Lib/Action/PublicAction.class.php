<?php
/**
 * 公共控制器
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class PublicAction extends Action{
	/**
	 * 构架函数
	 */
	function _initialize(){
		initSession();
		global $qc;
		$qc=initSite();
	}
	/**
	 * 登录
	 */
	public function login(){	
		if (intval($_SESSION['mid'])>0 && intval($_SESSION['is_admin'])==1 && $_SESSION['email']>''){
			//如果已经登录后台
			if(intval($_SESSION['admin_logged'])==1) $this->redirect(U('Index/index'));				
			$this->assign('email',$_SESSION['email']);		  
		}
		$this->display();
	}
	public function doLogin(){	

	   if($_POST["email"]=='' || $_POST["password"]=='') $this->error("请完善登录信息...");
	   //验证码
	   if($_SESSION['verify'] != md5($_POST['verify'])) {
	   	$this->error('验证码错误...');
	   }
	   
	   $email=$_POST["email"];
	   $password=md5($_POST["password"]);
	   $user = D("User")->where('email="'.$email.'" AND password="'.$password.'" AND is_admin=1')->field("*")->find();
	   if($user) {
	   	//管理员登录
	   	$_SESSION['mid']=$user['uid'];
	   	$_SESSION['mname']=$user['name'];
	   	$_SESSION['email']=$user['email'];
	   	$_SESSION['is_admin']=1;
	   	$_SESSION['admin_logged']=1;
	   	//更改session标志
	   	session_regenerate_id();
	   	$this->success("成功登录...",U('Index/index'));
	   }else {
	     $this->error("登录失败，邮箱或密码错误...");
	   }
	}
	public function logout(){
		unset($_SESSION['mid']);
		unset($_SESSION['mname']);
		unset($_SESSION['email']);
		unset($_SESSION['is_admin']);
		unset($_SESSION['admin_logged']);
		$_SESSION = array();
		$this->success("成功退出...",U('Index/index'));
	}
	/**
	 * 验证码
	 */
	Public function verify(){
		require_once(PATH_CLASS."/ORG/Image.class.php");
		require_once(PATH_CLASS."/ORG/String.class.php");
		Image::buildImageVerify();//静态方法
	}
}
?>