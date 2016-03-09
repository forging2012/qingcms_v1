<?php
/**
 * ajax操作
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class AjaxAction extends InitAction{
	/**
	 * 构架函数,只接受Ajax处理
	 */
	function _initialize(){
		// 只允许ajax
		if(!$this->isajax()){
			exit('0');
		}	
		// 用户需要登录
		if($this->mid<=0){
			exit('0');
		}	
	}
	/**
	 * 关注操作
	 */
	public function addfollow(){
		if(!$this->mid){
			exit('0');
		}	
		$fid=(int)$_POST['fid'];
		$res=D('Follow')->addAction($fid);
		if($res){
			exit('1');
		}else{
			exit('0');
		}	
	}
	/**
	 * 取消关注
	 */
	public function cancelFollow(){
		if(!$this->mid){
			exit('0');
		}	
		$fid=(int)$_POST['fid'];
		$res=D('Follow')->delAction($fid);
		if($res){
			exit('1');
		}else{
			exit('0');
		}	
	}
	/**
	 * 取得关系html
	 */
	public function getRelation(){
		$uid=(int)$_POST['fid'];
		$this->assign('uid',$uid);
		$this->display();
	}
	/**
	 * 删除关注分组
	 */
	public function delGroup(){
		$gid=(int)$_POST['gid'];
		echo (D('FollowGroup')->delAction($gid))?'1':'0';
	}
	/**
	 * 添加分组
	 */
	public function addGroup(){
		$name=(int)$_POST['name'];
		$res=D('FollowGroup')->addAction($name);
		echo $res?$res:'0';
	}
	/**
	 * 编辑分组
	 */
	public function editGroup(){
		$gid =(int)$_POST['gid'];
		$name=Filter::load()->f_safeText($_POST['name']);
		echo (D('FollowGroup')->editAction($gid,$name))?'1':'0';
	}
	/**
	 * 设置分组
	 */
	public function setGroup(){
		$fid=(int)$_POST['data'];
		$list=D('FollowGroup')->listByFid($fid);
		$this->assign('fid',$fid);
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 设置分组
	 */
	public function doSetGroup(){
		$data=$_POST['data'];
		parse_str($data,$data);
		$gids=$data['groupid']; // array
		$fid =(int)$data['fid'];
		$res=D('FollowGroup')->setUserGroup($fid,$gids);
		echo $res?'1':'0';
	}
	/**
	 * 表情
	 */
	public function smiley(){
		$list=D('Smiley')->order('displayorder asc')->select();
		$this->assign('list',$list);
		$this->display();
	}
	/**
	 * 删除文章 do=有数据操作的 ，需要登录
	 * ajax 返回 exit('1') exit(1)不能和0比较
	 */
	public function delText(){
		if($this->mid<=0){
			exit('0');
		}	
		$id=(int)$_POST['id'];
		$res=D('Content')->delAction($id);
		if($res>0){
			exit('1');
		}
		exit('0');
	}
}
?>