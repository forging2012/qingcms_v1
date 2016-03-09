<?php
/**
 * 登录控制器
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class LoginAction extends InitAction{
	/**
	 * _initialize
	 *
	 * 初始化
	 *
	 * @return void
	 */
	function _initialize(){
		import('ORG.Util.Session');
	}
	/**
	 * login
	 *
	 * 登陆页面
	 *
	 * @return void
	 */
	public function login(){
		// 已经登录
		if($this->isLogged()){
			$this->success(L('MsgLoged'),U('User/index'));
		}	
		$this->display();
	}
	/**
	 * 注册
	 */
	public function register(){
		// 已经登录
		if($this->isLogged()){
			$this->success(L('MsgLoged'),U('User/index'));
		}	
		$this->display();
	}
	/**
	 * 处理注册操作
	 */
	public function doReg(){
		if($_POST["email"]=='' || $_POST["name"]==''){
			$this->error("请完善注册信息...");
		}	
		// 验证码
		if($_SESSION['verify']!=md5($_POST['verify'])){
			$this->error('验证码错误...');
		}
		// 两次密码一致
		if($_POST['repassword']!=$_POST['password']){
			$this->error('两次密码输入不一致...');
		}
		// 检测email
		// 检测用户名
		// 检测密码
		$data['email']	 =$_POST['email'];
		$data['name']    =$_POST['name'];
		$data['password']=$_POST['password'];
		$res=D('User')->Reg($data);
		if($res['success']){
			$_SESSION["mname"]=$data['name'];
			$_SESSION["mid"]  =$res['uid'];
			$_SESSION["email"]=$data['email'];
			// 更改session标志
			session_regenerate_id();
			$this->success('注册成功,正带您进入首页，请稍后...',U('Index/index'));
		}else{
			$this->error($res['msg']);
		}
	}
	/**
	 * 验证码
	 */
	Public function verify(){
		require_once (PATH_CLASS."/ORG/Image.class.php");
		require_once (PATH_CLASS."/ORG/String.class.php");
		Image::buildImageVerify(); // 静态方法
	}
	/**
	 * doLogin
	 *
	 * 登陆操作
	 *
	 * @return void
	 */
	public function doLogin(){
		if($_POST["email"]=='' || $_POST["password"]==''){
			$this->error("请完善登录信息...");
		}
		$email	 =Request::load()->_post('email');
		$password=md5($_POST["password"]);
		
		if(!Validator::load()->v_email($email)){
			$this->error("邮箱格式错误");
		}
		
		$userModel=D("User");
		$user=$userModel->where('email="'.$email.'" AND password="'.$password.'"')->field("*")->find();
		
		if(!empty($user)){
			if($user['is_admin']==1){
				$_SESSION["is_admin"]=1;
			}
			// serialize — 产生一个可存储的值的表示
			$_SESSION["mname"]=$user['name'];
			$_SESSION["mid"]  =$user['uid'];
			$_SESSION["email"]=$user['email'];
			// 更改session标志
			session_regenerate_id();
			// 是否由提示窗口登录
			$isPop=$_POST['isPop'];
			if($isPop>''){
				// 由提示登录窗口，成功后返回登录前页面
				$this->success("成功登录，正返回登录前页面，请稍后...");
			}else{
				// 由登录中心，成功后进入个人中心
				$this->success("成功登录，进入个人中心，请稍后...",U('User/index'));
			}
		}else{
			$this->error("登录失败，邮箱或密码错误...");
		}
	}
	/**
	 * logout
	 * 退出
	 */
	public function logout(){
		// if($this->mid<=0) $this->needLogin();
		unset($_SESSION['mid']);
		unset($_SESSION['mname']);
		unset($_SESSION['email']);
		unset($_SESSION['is_admin']);
		Session::clear();
		$this->success("成功退出，正在返回首页，请稍后...",U('Index/index'));
	}
}
?>