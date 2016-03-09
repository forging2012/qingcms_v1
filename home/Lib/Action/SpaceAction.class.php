<?php
/**
 * 用户空间
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class SpaceAction extends InitAction {
	private $title;
	//覆盖了继承的_initialize方法
	function _initialize() {
        if(!(D('User')->isuser($this->uid))){
        	$this->error('您指定的用户不存在');
        }
		//基本信息
		$user=D('User')->getUserInfo($this->uid);
		//dump($user);
		$this->assign('user',$user);		
		// =============导航菜单===================
		$tab=array('pub'=>'发布','digg'=>'推荐','profile'=>'个人资料');
		if(strtolower(ACTION_NAME)=='index')
			$type='pub';
		else
			$type=strtolower(ACTION_NAME);
		$this->assign('tab',$tab);
		$this->assign('type',$type);	
		//===============获取该用户的热门列表===========
        $top10=$this->top10();
        $time=$top10['time'];
        $title=$top10['title'];
		$hotTitle="TA的发布&nbsp;".$title.'';
		$hotList=D('Content')->getUserHot($this->uid,$time);
		$this->assign('hotList',$hotList);
		$this->assign('hotTitle',$hotTitle);
		//储存标题
		$this->title=$user['name'].L('uspace');
	}


    public function index(){
        $this->pub();
     }
     /**
      *  用户的发布
      */
     public function pub(){
     	$list=D('Content')->getUserPub($this->uid);
     	$this->assign('list',$list['list']);
     	$this->assign('page',$list['page']);
     	$this->setTitle($this->title);
     	$this->display('space');     	
     }
     /**
      * 用户的推荐
      */
     public function digg(){
     	$list=D('Digg')->getUserDigg($this->uid);	     	
     	foreach ($list['list'] as $k=>$v){
     		 $idList[$k]=$v['tid'];
     	}
     	$digglist=D('Content')->byIdList($idList);     	
     	$this->assign('list',$digglist);
     	$this->assign('page',$list['page']);
     	$this->setTitle($this->title.'推荐-');
     	$this->display('space');	
     }
     /**
      * 用户的评论
      */
     public function comment(){
     	$this->display('space');
     }
     /**
      * 个人信息
      */
     public function profile(){
     	//用户信息
     	$profile=D('UserProfile')->getProfile($this->uid);
     	//dump($profile);
     	$this->assign('profile',$profile);
     	$this->setTitle($this->title.'个人资料-');
     	$this->display('space');
     }
     /**
      * 关注
      */
     public function following(){
     	//已经登录 只能查看自己的分组
     	if($this->mid>0 && $this->mid==$this->uid){ 
     		$group=D('FollowGroup')->getList();     		
     	}
     	
     	$groupall=isset($_GET["gid"])?false:true; //全部分组
     	$groupid =(int)$_GET["gid"];			  //分组
     	
     	if($groupall){
     	//全部
     		$groupNow='all';
     		$list=D('Follow')->getFollowingAll($this->uid);
     	}elseif($groupid==0){
     	//未分组
     		$groupNow='no';
     		$list=D('Follow')->getFollowingNo($this->mid);
     	}else{
     		$list=D('Follow')->getFollowing($groupid,$this->mid);
     	}
     	$this->assign('groupNow',$groupNow);
     	$this->assign('list',$list);
     	$this->assign('group',$group);
        $this->follow_tab();
        $this->setTitle($this->title.'关注-');
     	$this->display('space');
     }
     /**
      * 粉丝
      */
     public function follower(){
     	$list=D('Follow')->getFollower($this->uid);
     	$this->follow_tab();
     	$this->assign('list',$list);
     	$this->setTitle($this->title.'粉丝-');
     	$this->display('space');
     }
     private function follow_tab(){
     	if(!$this->isLogged()){ $this->needLogin('需要登录才能查看用户的关注和粉丝...'); }
     	$tab=array('pub'=>'发布','digg'=>'推荐','profile'=>'个人资料','following'=>'关注','follower'=>'粉丝');   	
     	$this->assign('tab',$tab);
     }
     /**
      * 微博
      */
     public function weibo(){
     	$list=D('Weibo')->getUserList($this->uid);

     	$this->assign('list',$list['list']);
     	$this->assign('page',$list['page']);
     	$this->display('space');
     }
     
 
     
     
     
}