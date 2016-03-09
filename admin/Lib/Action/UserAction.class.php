<?php
/**
 * 用户管理
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class UserAction extends InitAction{
	public function index() {
		echo MODULE_NAME;
		// $this->display();
	}
	public function newUser() {
		echo 'newUser';
	}
	
	public function userManager() {
		$list = D('User')->order('uid asc')->select();
		$this->assign ( 'userlist', $list );
		$this->display ();
	}
	/**
	 * 激活用户
	 * ajax
	 */
	public function active(){
		$uids=$_POST['ids'];
		$res=D('home://User')->activeFromAdmin($uids);
		echo $res?'1':'0';
	}
	/**
	 * 删除用户
	 */
	public function del(){
		$uids=$_POST['ids'];
		$res=D('home://User')->delFromAdmin($uids);
		echo $res?'1':'0';
	}
	/**
	 * 添加用户
	 */
	public function addUser(){
		$this->display('userBlock');
	}
	/**
	 * 编辑用户
	 */
	public function editUser(){
	    $u=D('User')->where('uid='.$_GET['uid'])->find();
	    $this->assign('u',$u);
		$this->display('userBlock');
	}
	/**
	 * 保存用户
	 */
	public function saveUser(){
		//dump($_POST);
		$data=$_POST;
		$res=D('home://User')->addEditFromAdmin($data);
		if($res['success'])
			$this->success('保存成功');
		else 
			$this->error($res['msg']);
	}
	
	/**
	 * 用户资料设置
	 */
	public function setField() {
		$list = D ( 'UserField' )->getAll ();
		// dump($list);
		$this->assign ( 'list', $list );
		$this->display ();
	}
	/**
	 * 删除用户资料字段
	 */
	public function deleteField() {
		$res = D ( 'UserField' )->deleteField ( $_REQUEST ['ids'] );
		echo $res ? '1' : '0';
	}
	/**
	 * 添加用户字段
	 */
	public function addField() {
		echo 'not yet';
	}

}