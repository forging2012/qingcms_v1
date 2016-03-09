<?php
/**
 * 公共控制器
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class PublicAction extends InitAction{
	/**
	 * _initialize
	 *
	 * 初始化
	 *
	 * @return void
	 */
	function _initialize(){
		//import('ORG.Util.Session');
	}
	/**
	 * 执行插件的方法
	 */
	public function toPlugin(){
		$p=$_GET['plugin'];
		$a=$_GET['p_action'];
		$m=$_GET['p_method'];
		if(empty($p) || empty($a) || empty($m)){
			exit('403');
		}	
		Plugins::runAction($p,$a,$m);
	}
	/**
	 * 投票操作
	 */
	public function vote(){
		$tid =(int)$_GET['id'];
		$type=(int)$_GET['type'];
		$return=D('Digg')->addAction($tid,$type);
		if($return['success']){
			// 投票成功
			$res['success']=1;
		}else{
			// 投票失败
			$res['msg']=$return['msg'];
		}
		exit(json_encode($res));
	}
	/**
	 * 获取用户的消息
	 */
	public function getMsg(){
		/*
		 * notify 系统通知：有超过100人推荐了你的文章，你的文章进入热榜 comment who评论了你的文章 回复了你
		 */
		$res=array('notify'=>0,'comment'=>0,'total'=>0);
		if(!($this->isLogged())){
			exit(json_encode($res));
		}
		$msg=D('Message')->where("uid=".$this->mid)->find();
		$res['comment']=$msg['comment'];
		$res['notify'] =$msg['notify'];
		
		$res['total']=array_sum($res);
		exit(json_encode($res));
	}
	/**
	 * 评论操作 添加评论
	 */
	public function addComment(){
		$contentid=(int)$_POST['tid'];
		$reply_uid=(int)$_POST['reply_uid'];
		$content  =$_POST['content'];
		
		$return['success']=0;
		if(!($this->isLogged())){
			$return['msg']=L('nologin');
			exit(json_encode($return));
		}
		
		if($content==''){
			$return['msg']=L('empty');
			exit(json_encode($return));
		}
		
		if($contentid==0 || $reply_uid==0){
			$return['msg']=L('error');
			exit(json_encode($return));
		}
		
		$data['ctime']		=time();
		$data['uid']		=$this->mid;
		$data['tid']		=$contentid;
		$data['reply_uid']  =$reply_uid;
		$data['content']	=Filter::load()->f_safeText($content);
		
		$res=D('Comment')->addAction($data);
		
		if($res['success']>0){
			$return['success']=1;
		}else{
			$return['msg']=$res['msg'];
		}
		exit(json_encode($return));
	}
	/**
	 * 删除评论 ajax,不能使用return
	 * $_REQUEST['id'];
	 * $_REQUEST['tid'];
	 */
	public function delComment(){
		if(!($this->isLogged())){
			exit(0);
		}
		// 登录用户只能删除自己添加的评论
		$mid=$this->mid;
		$id =(int)$_POST['id'];
		$tid=(int)$_POST['tid'];
		if($id==0 || $tid==0){
			exit('0');
		}	
		$res=D('Comment')->delAction($id,$mid,$tid);
		if($res){
			exit('1');
		}else{
			exit('0');
		}
	}
	/**
	 * 通过ajax请求评论数据
	 */
	public function comment(){
		$tid=(int)$_POST['tid'];
		$uid=(int)$_POST['uid'];
		
		if($tid==0 || $uid==0){
			exit(0);
		}
		/**
		 * 说明：传入的comment数组必须包含项
		 * comment['tid']
		 * comment['uid']
		 * comment['list']
		 * comment['page']
		 * comment['num']
		 */
		$comment['list']=D('Comment')->where('tid='.$tid)->order('id desc')->limit(5)->select();
		$comment['page']=null;
		$comment['tid']=$tid;
		$comment['uid']=$uid;
		$comment['num']=(int)$_POST['comment'];
		$comment['ajax']=1;
		
		$this->assign('comment',$comment);
		$this->display();
	}
	
	/**
	 * 回复评论
	 * ajax
	 */
	public function replycomment(){
		$data=$_POST['data'];
		$commentid  =(int)$data[0];
		$type		=$data[1];
		$com=D('Comment')->where('id='.$commentid)->find();
		$this->assign('type',$type);
		$this->assign('comment',$com);
		$this->display();
	}
	/**
	 * 回复评论操作
	 */
	public function doreply(){
		// data
		$data=array();
		$data['reply_comment_id']=(int)$_POST['r_reply_comment_id'];
		$data['content']		 =Filter::load()->f_safeText($_POST['r_content']);
		
		$return['success']=0;
		if(!($this->isLogged())){
			$return['msg']=L('nologin');
			exit(json_encode($return));
		}
		
		if($data['content']==''){
			$return['msg']=L('empty');
			exit(json_encode($return));
		}
		
		if($data['reply_comment_id']==0){
			$return['msg']=L('error');
			exit(json_encode($return));
		}
		// data
		$rc=D('Comment')->where('id='.$data['reply_comment_id'])->find();
		$data['tid']	  =$rc['tid'];
		$data['reply_uid']=$rc['uid'];
		// do
		$res=D('Comment')->addAction($data);
		// done
		if($res['success']>0){
			$return['success']=1;
		}else{
			$return['msg']=$res['msg'];
		}
		exit(json_encode($return));
	}
	/**
	 * 显示地区
	 */
	public function showArea(){
		$area=D('Area')->MidAreaTree();
		$this->assign('area',$area);
		$this->display('showArea');
	}
	/**
	 * 显示某省的城市
	 */
	public function getCity(){
		$p=(int)$_REQUEST['province'];
		$city=D('Area')->getCity($p);
		$this->assign('city',$city);
		$this->display('showArea');
	}
	/**
	 * 举报评论,需要登录
	 */
	public function report(){
		$id=(int)$_POST['data'];
		$this->assign('id',$id);
		$this->display();
	}
	public function doreport(){
		if($this->mid<=0){
			exit('0');
		}	
		if($_POST['data']==''){
			exit('0');
		}
		$data=array();
		parse_str($_POST['data'],$data);
		$data['id']   =(int)$data['id'];
		$data['guest']=Filter::load()->f_safeText($data['guest']);
		echo (D('FeedBack')->addReport($data))?'1':'0';
	}
}
?>