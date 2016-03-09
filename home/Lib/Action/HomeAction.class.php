<?php
/**
 * 微博首页
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 * @deprecated
 */
// class HomeAction extends InitAction {
// 	function _initialize() {
// 		if(!$this->isLogged()){
// 			$this->needLogin('需要登录才能访问个人空间...');
// 		}
// 		//基本信息
// 		$user=D('User')->getUserInfo($this->mid);
// 		//dump($user);
// 		$this->assign('user',$user);	
		
// 		$this->setTitle(L('userhome'));
// 	}
// 	/**
// 	 * 我的首页
// 	 */
//     public function index(){	
//     	// =============导航菜单===================
//     	$tab=array('following'=>'我的关注','doing'=>'正在发生');
//     	if($_GET['ac']=='following' || $_GET['ac']=='')
//     		$tabNow='following';
//     	else if($_GET['ac']=='doing')
//     		$tabNow='doing';
    		
//     	$this->assign('action',ACTION_NAME);
//     	$this->assign('tab',$tab);
//     	$this->assign('tabNow',$tabNow);
//     	// =============导航菜单===================
//     	//正在发生
//     	if($tabNow=='doing'){
//     	 // $list=D('Content')->order('id desc')->limit(10)->select(); 
//     	}else if($tabNow=='following'){
//     	//分组
//     	    $glist=D('FollowGroup')->getList();
//             if(!isset($_GET['gid'])){
//     	    	$g='all';
//     	    }else  if($_GET['gid']==0 || $_GET['gid']==''){
//     	    	$g='no';
//     	    }else{
//     	    	$g=$_GET['gid'];
//     	    }
//     	    //关注的
//     	   // $list=D('Weibo')->where('uid='.$this->mid)->order('id desc')->limit(10)->select();
//     	     $list=D('Weibo')->fromGroup($g);
// //     	    dump($list);
// //     	    exit();
//     	}
//     	$lastId=$list[9]['id'];//第一次获取的最后一条文章id
//     	$this->assign('lastId',$lastId);
//     	$this->assign('list',$list);
//     	$this->assign('glist',$glist);
//     	$this->assign('g',$g);
//     	$this->display('home');
//     }  
//     /**
//      * 我的评论
//      */
//     public function comment(){
//     	// =============导航菜单===================
//     	$tab=array('receive'=>'收到的评论','send'=>'发出的评论');
//     	if($_GET['ac']=='receive' || $_GET['ac']=='')
//     		$tabNow='receive';
//     	else if($_GET['ac']=='send')
//     		$tabNow='send';
    	
//     	$this->assign('action',ACTION_NAME);
//     	$this->assign('tab',$tab);
//     	$this->assign('tabNow',$tabNow);
//     	// =============导航菜单===================
//    // echo 'comment';	
//    //发出的评论
//    if($tabNow=='send'){
//    	 $res=D('Comment')->send($this->mid);
//    }else{
//    //收到的评论
//    	 $res=D('Comment')->receive($this->mid);
//    }
  
//     $this->assign('list',$res['list']);
//     $this->assign('page',$res['page']);
//     $this->display('home');
//     }    
//     /**
//      * 消息中心
//      */
//     public function message(){
//     	// =============导航菜单===================
//     	$tab=array('notify'=>'系统通知');
//         $tabNow='notify';
        
//         $this->assign('action',ACTION_NAME);
//     	$this->assign('tab',$tab);
//     	$this->assign('tabNow',$tabNow);
//     	// =============导航菜单===================
//         //通知消息置零
//         D('Message')->setZero('','notify');
//     	$notify=D('Notify')->showAction($this->mid);
//     	$this->assign('list',$notify['list']);
//     	$this->assign('page',$notify['page']);
//     	$this->display('home');
//     }
//     /**
//      * 帐号设置
//      */
//     public function account(){
//     	// =============导航菜单===================
//     	$tab=array('profile'=>'个人资料','password'=>'修改密码','credit'=>'积分');
//         if($_GET['ac']=='profile' || $_GET['ac']==''){
//         	$tabNow='profile';
//         }else if($_GET['ac']=='password'){
//         	$tabNow='password';
//         }else if($_GET['ac']=='credit'){
//         	$tabNow='credit';
//         }
//         $this->assign('action',ACTION_NAME);
//     	$this->assign('tab',$tab);
//     	$this->assign('tabNow',$tabNow);
//     	// =============导航菜单===================
//     	//个人资料
//     	if($tabNow=='profile'){
//         //用户信息
//         $profile=D('UserProfile')->getProfile($this->mid);      
//         $this->assign('profile',$profile);
//     	}
//     	//积分，积分规则
//     	if($tabNow=='credit'){
//     	    $rule=D('CreditRule')->select();
//     		$this->assign('rule',$rule);
//     	}
//     	$this->display('home');
//     }
//     /**
//      * 保存基本资料
//      */
//     public function doUser(){
//     	// $_POST['data']: name=QingCms&sex=1&province=1532&city=1546
//     	// parse_str :查询字符串解析到数组data
//     	parse_str($_POST['data'],$data);
     
//     	$do=D('User')->update($data);
//     	$return['msg']=L('updateSuccess');
//     	if($do['success']>0){
//     		 $return['success']=1;
//     	}else{
//     		$return['success']=0;
//     		$return['msg']=$do['msg'];
//     	}
//     	exit(json_encode($return));
//     }
//     /**
//      * 保存用户信息
//      */
//     public function doProfile(){   
//     	$return['msg']=L('updateSuccess');
    	
//     	parse_str($_POST['data'],$data);
//     	$do=D('UserProfile')->update($data);
    	    	
//     	if($do>0){
//     		 $return['success']=1;
//     	}else if($do==0 && is_int($do)){
//             //影响条数为0
//     		$return['success']=1;
//     	}else{
//     		$return['success']=0;
//     		$return['msg']=L('error');
//     	}
//     	exit(json_encode($return));
    	
//     }
//     /**
//      * 修改密码
//      */
//     public function doChangePassword(){
//     	parse_str($_POST['data'],$data);
//     	$do=D('User')->changePassword($data);
//     	$return['msg']=L('updateSuccess');
//     	if($do['success']>0){
//     		 $return['success']=1;
//     	}else{
//     		$return['success']=0;
//     		$return['msg']=$do['msg'];
//     	}
//     	exit(json_encode($return));
//     }
    
    
//     /**
//      * loadmore
//      */
//     public function loadmore(){
//     	$lastId=$_REQUEST['lastId'];
//     	if($lastId=='') exit('0');
//     	$list=D('Content')->where('id<'.$lastId)->order('id desc')->limit(5)->select();
//     	if($list>0){
//     	  $this->assign('list',$list);
//     	  $return['list']=$this->fetch();
//     	  $return['success']=1;
//     	  $return['lastId']=$list[4]['id'];
//     	}else{
//     	   $return['success']=0;
//     	}
//     	exit(json_encode($return));
//     }
    
// }