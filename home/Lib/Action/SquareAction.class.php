<?php
/**
 * 内容广场
 * 未完成
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 * @deprecated
 */
// class SquareAction extends InitAction{
// 	public function index(){
// 		$this->display();
// 	}
// 	// 网址集
// 	public function sites(){
// 		$list=D('Class')->getclass('','square');
// 		$this->setTitle(L('square'));
// 		$this->assign('class',$list['class']);
// 		$this->assign('page',$list['page']);
// 		$this->display('index');
// 	}
// 	// 样式列表
// 	public function yang(){}
// 	// digg
// 	public function digg(){}
// 	// 查看某个分类的网址
// 	public function view(){
// 		if($_GET['class']=='')
// 			$this->error('请选择正确的分类');
// 		else
// 			$class=$_GET['class'];
// 		$sites=D('site_admin')->where('class='.$class)->select();
// 		$this->assign('sites',$sites);
// 		$this->display();
// 	}
// }