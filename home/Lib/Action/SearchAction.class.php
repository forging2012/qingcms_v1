<?php
/**
 * 搜索/标签
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class SearchAction extends InitAction{
	function _initialize(){}
	/**
	 * 首页
	 */
	function index(){
		if(isset($_GET['keyword'])){
			$keyword=Filter::load()->f_safeText($_GET['keyword']);
			$this->so($keyword);
		}else if(isset($_GET['tag'])){
			$tagid=(int)$_GET['tag'];
			$this->tag($tagid);
		}else{
			$this->so('');
		}
	}
	/**
	 * 搜索
	 */
	private function so($value){
		// 内容搜索
		$this->assign('tab_name','搜索');
		$this->assign('ac','so');
		$adv=$_GET['adv'];
		// 是否高级搜索
		if($adv=='yes_temp' || $adv=='yes'){
			$this->highSearch();
		}
		if($adv!='yes_temp'){
			if($value){
				$list=D('Content')->doSearch($value);
			}
		}
		$this->assign('value',$value);
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->assign('count',$list['count']);
		// 设置标题
		$this->setTitle('搜索::'.$value.'-');
		$this->display('search');
	}
	/**
	 * 列出标签内容
	 */
	private function tag($value){
		$this->assign('tab_name','标签');
		$this->assign('ac','tag');
		if($value){
			$tag_name=D('Tag')->where('tag_id='.$value)->field('name')->find();
			$tag_name=$tag_name['name'];
			$tag=D('Tag')->by_tag_id($value);
			foreach($tag['list'] as $v){
				$idlist[]=$v['content_id'];
			}
			$list['page']=$tag['page'];
			$list['count']=count($tag['list']);
			$list['list']=D('Content')->byIdList($idlist);
		}
		$this->assign('value',$tag_name);
		$this->assign('list',$list['list']);
		$this->assign('page',$list['page']);
		$this->assign('count',$list['count']);
		// 设置标题
		$this->setTitle('标签::'.$tag_name.'-');
		$this->display('search');
	}
	/**
	 * 高级搜索选项
	 */
	public function highSearch(){
		// 栏目限制
		$cate=cateList();
		// dump($cate);
		// 时间限制
		// $time=array(
		// '8h'=>'8小时',
		// '24h'=>'24小时',
		// 'week'=>'7天',
		// 'month'=>'30天',
		// );
		$time=array(8*60*60=>'8小时',24*60*60=>'24小时',7*24*60*60=>'7天',30*24*60*60=>'30天');
		// 排序限制
		$sort=array('ctime'=>'发布时间','up'=>'推荐人数','comment'=>'评论条数');
		$this->assign('adv','yes');
		$this->assign('time',$time);
		$this->assign('cate',$cate);
		$this->assign('sort',$sort);
	}
}