<?php
/**
 * 应用首页
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class IndexAction extends InitAction{
	private $cateList=null; // 现在所处的分类列表
	private $cate2=null; // 二级分类
	/**
	 * 伪构造函数
	 */
	function _initialize(){
		/*
		 * 内容过滤菜单
		 */
		$typeNow=Filter::load()->f_abc123($_GET['type']);
		$typeNow=strtolower($typeNow);
		$typeTab=array('text'=>'文字','pic'=>'图片');
		$this->assign('typeTab',$typeTab);
		$this->assign('typeNow',$typeNow);
	}
	/**
	 * 空操作
	 * @param $cate
	 */
	public function _empty($cate=''){
		$cate=Filter::load()->f_abc123($cate);
		// $cateNow 为一维二元素数组 返回id、parentid,name
		$cateNow=D("ContentCate")->byNid($cate);
		
		if(empty($cateNow)){
			// 获取默认栏目
			//$cateNow=D("ContentCate")->getDefCate();
			$this->error('文章分类不存在');
		}
		
		// 生成位置,顶级栏目
		if($cateNow['parentid']==0){
			$LocTop['id']=$cateNow['id'];
			$LocTop['nid']=$cateNow['nid'];
			$LocTop['name']=$cateNow['name'];
			$Loc2=array();
		}else{
			// 当前为二级栏目时，取得父级栏目
			$LocTop=D("ContentCate")->byId($cateNow['parentid'],'id,nid,name');
			$Loc2['nid']=$cateNow['nid'];
			$Loc2['name']=$cateNow['name'];
		}
		$this->assign('LocTop',$LocTop);
		$this->assign('Loc2',$Loc2);
		$this->setTopNav($LocTop['nid']);
		$cate2=D('ContentCate')->getCate2($LocTop['id']);
		$this->cate2=$cate2;
		// 在生成$this->cate2后才根据其获取内容
		$list=$this->_getContent($cateNow);
		// 在$this->_getContent生成$this->cateList后才根据其获取内容
		$this->_Top10($this->cateList,$cateNow['name'].'&nbsp;&nbsp;');
		// 设置标题
		// 设置页面头信息
		if($Loc2['name']!=''){
			$title=$LocTop['name'].'-'.$Loc2['name'].'-';
			$meta['key']=$LocTop['name'].','.$Loc2['name'].',';
			$meta['des']=$LocTop['name'].','.$Loc2['name'].',';
		}else{
			$title=$LocTop['name'].'-';
			$meta['key']=$LocTop['name'].',';
			$meta['des']=$LocTop['name'].',';
		}
		$this->setTitle($title);
		$this->setMeta($meta);
		
		$this->assign('cate2',$cate2);
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->display('list');
	}
	/**
	 * 获取某个分类下的内容
	 */
	private function _getContent($cateInfo){
		// 不是顶级栏目
		if($cateInfo['parentid']!=0){
			$this->cateList=$cateInfo['id'];
			return D('Content')->byCateids($cateInfo['id']);
		}
		// 顶级栏目时,同时显示该栏目下的子内容
		$cate2=D('ContentCate')->getCate2($cateInfo['id']);
		$cate2=$this->cate2?$this->cate2:array();
		// 栏目id数组
		$catelist[0]=$cateInfo['id'];
		foreach($cate2 as $k=>$v){
			$catelist[$k+1]=$v['id'];
		}
		$this->cateList=$catelist;
		return D('Content')->byCateids($catelist);
	}
	/**
	 * 首页
	 */
	public function index(){
		$this->_Top10('');
		// 获取友情链接
		$flink=D('friendlink')->order('displayorder asc')->select();
		$this->assign('flist',$flink);
		$list=D('Content')->getAll();
		
		$cate2=D('ContentCate')->getIndexCate();
		$this->setTopNav('index');
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->assign('cate2',$cate2);
		$this->display('list');
	}
	/**
	 * top10
	 */
	private function _Top10($cateList,$t=''){
		$top10=$this->top10();
		$time=$top10['time'];
		$title=$top10['title'];
		$top=D('Content')->topList($cateList,$time);
		$this->assign('topTitle',$t.$title);
		$this->assign('topList',$top);
	}
	/**
	 * 文章详细显示页面
	 */
	public function detail(){
		$id=(int)$_GET['id'];
		$temp=D('Content')->getone($id);
		if(!$temp){
			$this->error('该文章不存在');
		}
		/**
		 * 说明：传入的comment数组必须包含项
		 * comment['tid']
		 * comment['uid']
		 * comment['list']
		 * comment['page']
		 * comment['num']
		 */
		// 返回 list、page
		$obj=D('Comment');
		$comment=$obj->showTid($id);
		$comment['tid']=$id;
		$comment['uid']=$temp['uid'];
		$comment['num']=$temp['comment'];
		$tag=D('Tag')->tagList($id);
		
		// 设置标题
		// 设置页面头信息
		// ----------------------------------------------------
		if($tag!=''){
			foreach($tag as $k=>$v){
				$meta['key'].=$v['tag_name'].',';
			}
		}
		// $meta['key']=substr($meta['key'],0,-1);//去掉最后的那个逗号,不替换关键字不需要去掉
		$title=getShort($temp['content'],40).'-';
		$meta['des']=getShort($temp['content'],100);
		$this->setTitle($title);
		$this->setMeta($meta,0,1);
		// ----------------------------------------------------
		
		// 推荐过该文章的用户
		$diggUser=D('Digg')->diggThisUser($id);
		$this->assign('diggUser',$diggUser);
		$this->assign('tag',$tag);
		$this->assign('detail',$temp);
		$this->assign('comment',$comment);
		$this->display();
	}
}