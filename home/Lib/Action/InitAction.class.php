<?php
/**
 * 控制器基类
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class InitAction extends Action{
	//判断了当前的登录用户id-mid  和访问的用户空间 uid
	protected	$mid; //当前登录用户id
	protected	$mname; //当前登录用户name
	protected	$uid;   //正在访问的用户uid
	protected   $tab_key=array(); //tab菜单的英文名称
	/**
	 * 初始化整站程序
	 */
	public function _init(){
		//初始化
		$this->_initSession();
		$this->_initUser();
		$this->_initSite();
		$this->_initTopNav();
		$this->_safeSetting();
	}
	/**
	 * 安全设置
	 */
	private function _safeSetting(){
		if(!defined('SAFESETTING') || !SAFESETTING){
			return true;
		}
		$mod   =strtolower(MODULE_NAME);
		$action=strtolower(ACTION_NAME);
		$message='为安全起见，禁止该页面的访问...';
		$errorMessage="<span style='color:#FB4343;'>{$message}</span>";
		$method=strtolower($_SERVER['REQUEST_METHOD']);
		$ispost=($method=='post')?true:false;
		$isget =($method=='get')?true:false;
		
		if($ispost){
		//post请求-----------------------------------------
		//禁止POST访问
		
			//评论，举报，回复评论
			if($mod=='public' && ( $action=='comment' || $action=='report' || $action=='replycomment') ){
				//评论
				$html="<div style='background: #F9BABA;padding: 20px 0;text-align: center;color: #333;'>{$message}</div>";
				exit($html);
			}
			
			//登录，注册
			if($mod=='login'){
				$this->error($errorMessage);
			}
			
			//禁止POST访问
			$return['success']=0;
			$return['msg']	  ='禁止POST访问...';
			exit(json_encode($return));
			exit('0');
		}
		
		if($isget){
		//get请求-----------------------------------------
			//运行发布,登录，注册页面,用户空间
			if($mod=='pub' || $mod=='login' || $mod=='space'){
				return true;
			}
			//允许标签
			if($mod=='search' &&  $action=='index' && empty($_GET['keyword']) && $_GET['tag']>''){
				return true;
			}
			//禁止除了index以外的控制器
			if($mod!='index'){
				$this->error($errorMessage);
			}
		}
		
	}
	/**
	 * 初始化Session
	 */
	 private function _initSession(){
	 	//导入Session管理类
	 	//require_once("./core/Class/iSession.class.php");
	 	//Session::start();//开启Session
	 	//session_regenerate_id();
	 	ini_set('session.auto_start', 0);//关闭自动启动
	 	session_name('PATH_PHPSESSID');//PHPSESSID	
	 	//需要设置完成后才能启动
	 	session_start();
	 }
	/**
	 * 初始化顶部导航
	 * 返回二维数组
	 *  系统顶级栏目开启导航的  和外部链接合并
	 */
	private  function _initTopNav(){
		$c=getCatelist();
		$n=D('Nav')->where("disabled=1")->field("name,nid,url")->order('displayorder')->select();
		if($n!='')
		  $m=array_merge($c,$n);
		else
		  $m=$c;
		$this->assign('topCateNav',$m);
	}
	/**
	 * Css/js文件引入处理
	 */
	private function _initCssJs(){
		$link='';
		//主题
// 		$link.='<script type="text/javascript" src="http://debug.qingcms.pw/static/load/load-styles-js.php?clear=1"></script>';
// 		$link.='<link rel="stylesheet"  type="text/css" href="http://debug.qingcms.pw/static/load/load-styles-main.php?clear=1" />';
		$link.='<script type="text/javascript" src="__STATIC__/_cache/_main.js"></script>';
		$link.='<link rel="stylesheet"  type="text/css" href="__STATIC__/_cache/_main.css" />';
		
		
		//插件
		$link.='<script type="text/javascript" src="__STATIC__/_cache/plugin.js"></script>';
		$link.='<link rel="stylesheet"  type="text/css" href="__STATIC__/_cache/plugin.css" />';
		
		//css/js在头部的外部链接
		$this->assign('headlink',$link);
	}
	/**
	 * 初始化网站信息
	 */
	private  function _initSite(){
		$this->_initCssJs();
		//动态改变系统默认主题,这里改变模板替换中的__THEMES__，定位到正在使用的主题的css、image、tpl等主题文件
		C('TMPL_PARSE_STRING.__THEMES__',PATH_PATH.'/themes/'.PATH_DEFAULT_THEMES);
		//$_G系统配置
		global $_G;//index.php定义的$_G进不来，这是一个类中的一个方法内，局部变量的概念
		$siteinfo=$_G['siteinfo'];
		$v=$_G['view'];
		//获取站点信息
		//$siteinfo=D('System')->lget('siteinfo');
		global $globalInfo;
		//标题
		$globalInfo['title']='';
		//分页显示
		$page=(int)$_GET['p'];
		if($page==0){
		   $globalInfo['page']='';
		}else{ 
			$globalInfo['page']='第'.$page.'页-';
		}
		$globalInfo['site_name']=$siteinfo['site_name']; //网站名称
		$globalInfo['cms_name']=$siteinfo['cms_name']; //站点名称
		$globalInfo['url']=$siteinfo['url']; //网站url
		$globalInfo['icp']=$siteinfo['icp']; //网站备案信息
		//第三方工具
		$globalInfo['shareCode']=$_G['tools']['shareCode']; //网站第三方分享
		$globalInfo['countCode']=$_G['tools']['countCode']; //网站第三方统计
		//广告
		$globalInfo['ad']=$_G['ad'];
		//top10
		$globalInfo['top10']=$_G['view']['top10'];
		//附件设置
		$globalInfo['attachment']=$_G['attachment'];
		//所有插件的数据
		$globalInfo['plugin']=$_G['plugin'];
        //每页显示条数
		$globalInfo['textPage']=$_G['view']['textPage'];
		
		$globalInfo['site_keywords']=$siteinfo['keywords'];//关键字
		$globalInfo['site_description']=$siteinfo['description'];//描述	
		
		/**
		 * 字数限制和分页限制
		 */
		//$v=D('System')->lget('view');
		$this->assign('minlength',$v['minlength']); //文章字数限制
		$this->assign('length',$v['length']); //文章字数限制
		$this->assign('textPage',$v['textPage']);//文章分页
		
		$this->assign('minComLen',$v['minComLen']);  
		$this->assign('comLen',$v['comLen']);  //评论字数
		$this->assign('comPage',$v['comPage']); //评论分页
		$this->assign('comAjaxLen',$v['comAjaxLen']); //评论ajax条数
		
		//销毁$_G
		global $Sys;
		$Sys['view']=$v;
		global $glob;
		$glob['mid']=$this->mid;
		$glob['mname']=$this->mname;	
	}
	/**
	 * 初始化当前登录用户信息
	 */
	private function _initUser(){
		$this->mid =mid();
		$this->mname=mid('name');
		$this->uid =uid();
		if($_SESSION["is_admin"]==1){
			$this->assign('is_admin',1);
		}			
		$this->assign('mid',$this->mid);
		$this->assign('mname',$this->mname);
		$this->assign('uid',$this->uid);
	}
	/**
	 * 是否已经登录
	 * @return boolean
	 */
	protected  function isLogged(){
		return isLogged();
	}
	/**
	 * 设置顶部导航 聚焦
	 */
	protected function setTopNav($nid){
		$this->assign('TopNav',$nid);
	}
	/**
	 * 设置标题
	 * @param  $input
	 */
	protected function setTitle($input){
		global $globalInfo;
		$globalInfo['title'] = $input;
	}
	/**
	 * 设置页面头信息
	 * @param  $input
	 */
	protected function setMeta($meta,$k=0,$d=0){
		global $globalInfo;
		//是否替换关键字
		if($k==0){
		  $globalInfo['site_keywords']=$meta['key'].$globalInfo['site_keywords'];//关键字
		}else if($k==1){
		  $globalInfo['site_keywords']=$meta['key'];
		}
		//是否替换描述
		if($d==0){
		  $globalInfo['site_description']=$meta['des'].$globalInfo['site_description'];//描述
		}else if($d==1){
			$globalInfo['site_description']=$meta['des'];//描述	
		}			
	}
	/**
	 * page
	 * @param $input
	 */
	protected function setPage($input){
		global $globalInfo;
		$globalInfo['page'] = $input;
	}    
	/**
	 * 生成位置
	 */
	protected function Location(){
			
	}
	protected function needLogin($msg=''){
	   $this->info($msg);
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
		$this->display(THEME_PATH.'info.html');	
		exit();
	}
	/**
	 * 成功提示
	 */
	protected function success($msg,$url='',$ajax=false){
		$this->setTitle('提示信息-');
		//保证输出不受静态缓存影响
		C('HTML_CACHE_ON',false);
		if($url=='') $url=$_SERVER["HTTP_REFERER"];
		//$url=$_SERVER["HTTP_REFERER"];
		
		$this->assign('url',$url);
		$this->assign('msg',$msg);
		$this->assign('isture',1);
		$this->display(THEME_PATH.'message.html');
		exit();	
	}
	/**
	 * 失败提示
	 */
	protected function error($msg,$url='',$ajax=false){
		$this->setTitle('提示信息-');
		//保证输出不受静态缓存影响
		C('HTML_CACHE_ON',false);
		
		$this->assign('msg',$msg);
		$this->assign('isture',0);
		$this->display(THEME_PATH.'message.html');
		exit();
	}
	/**
	 * top10处理
	 */
	protected function top10(){
	  $timeArr=array(
				array('time'=>'28800','title'=>'8小时最热'), //60*60*8
				array('time'=>'86400','title'=>'24小时最热'),   //60*60*24
				array('time'=>'604800','title'=>'7天最热'),      //60*60*24*7
				array('time'=>'2592000','title'=>'30天最热'),    //60*60*24*30
				array('time'=>'0','title'=>'全部时间')
		);
	  global $globalInfo;
	  $r['time']=$timeArr[$globalInfo['top10']]['time'];
	  $r['title']=$timeArr[$globalInfo['top10']]['title'];
	  return $r;
	}
	
	
}
?>