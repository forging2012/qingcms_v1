<?php
/**
 * 控制器基类
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class InitAction extends Action{
	//判断了当前的登录用户id-mid  和访问的用户空间 uid
	protected	$mid; //当前登录用户id
	protected	$mname; //当前登录用户name
	/**
	 * 初始化整站程序
	 */
	public function _init(){
		//初始化
		$this->_initSession();
		$this->_initSite();
		$this->_initLogin();
	}
	/**
	 * 初始化Session
	 */
	 private function _initSession(){
	 	initSession(); 	
	 }
	 /**
	  * 初始化登录操作 
	  */
	 public function _initLogin(){
	 	// 验证本地系统登录
	 	if (intval($_SESSION['mid'])>0 && intval($_SESSION['is_admin'])==1 && intval($_SESSION['admin_logged'])==1 ){
	 		$this->mid=$_SESSION['mid'];
	 		$this->mname=$_SESSION['mname'];
	 		$this->assign('mid',$this->mid);	
	 		$this->assign('mname',$this->mname);
	 	}else{	
	 		$this->redirect(U('Public/login'));
	 		exit();
	 	}
	 }
	 /**
	  * 初始化网站信息
	  */
	 private  function _initSite(){
		global $qc;
		$qc=initSite();
	 }
	/**
	 * 信息提示 
	 */
	protected function info($msg){
		$this->setTitle('提示信息-');
		//保证输出不受静态缓存影响
		C('HTML_CACHE_ON',false);
		if($msg=='') $msg='您需要登录才能继续此操作...';
		$this->assign('isPop',1);//告诉对方，这是一个提示
		$this->assign('msg',$msg);
		$this->display('./home/Tpl/info.html');	
		exit();
	}
	/**
	 * 成功提示
	 */
	protected function success_lock($msg,$url){		
		$this->setTitle('提示信息-');
		//保证输出不受静态缓存影响
		C('HTML_CACHE_ON',false);		
		if($url=='') $url=$_SERVER["HTTP_REFERER"];		
		$this->assign('url',$url);
		$this->assign('msg',$msg);
		$this->assign('isture',1);
		$this->display(C('TMPL_MESSAGE'));
		exit();	
	}
	/**
	 * 失败提示
	 */
	protected function error_lock($msg){
		$this->setTitle('提示信息-');
		//保证输出不受静态缓存影响
		C('HTML_CACHE_ON',false);
		$this->assign('msg',$msg);
		$this->assign('isture',0);
		$this->display(C('TMPL_MESSAGE'));
		exit();
	}
	protected function setTitle(){}
	
}
?>