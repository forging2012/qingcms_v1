<?php
/**
 * 微博模块
 * 已弃用
 * 
 * @deprecated
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
// class WeiboAction extends InitAction{
// 	/**
// 	 * 构架函数,只接受Ajax处理
// 	 */
// 	function _initialize(){
// 		// 只允许ajax
// 		// if(!$this->isajax()) exit('0');
// 		// 用户需要登录
// 		if($this->mid<=0)
// 			exit('0');
// 	}
// 	/**
// 	 * 发微博
// 	 */
// 	public function WeiboPub(){
// 		$return['success']=0;
// 		$data['content']=$_POST['content'];
// 		// 如果有选择文件
// 		$name='pic';
// 		if($_FILES[$name]['name']!=''){
// 			// 上传处理
// 			require_once (C('iServicePATH'));
// 			$File=iService::Fileupload($name);
// 			if($File['success']){
// 				// 上传成功
// 				$image['path']=$File['FileInfo']['savepath']; // 保存路径
// 				$image['name']=$File['FileInfo']['savename']; // 保存名称
// 				$data['image']=$image;
// 			}else{
// 				// 图片上传失败
// 				$return['msg']=$File['ErrorMsg'];
// 				exit(json_encode($return));
// 			}
// 		}
// 		$res=D('Weibo')->addAction($data);
// 		if($res){
// 			$return['tid']=$res;
// 			$return['success']=1;
// 		}else{
// 			$return['success']=0;
// 			$return['msg']='发布失败';
// 		}
// 		exit(json_encode($return));
// 	}
// 	/**
// 	 * 获取刚发布的微博
// 	 */
// 	public function getJustOne(){
// 		$tid=$_POST['tid'];
// 		$one=D('Weibo')->where('id='.$tid)->find();
// 		$this->assign('one',$one);
// 		$this->display();
// 	}
// 	/**
// 	 * 显示转播
// 	 * ajax
// 	 */
// 	public function showForwarding(){
// 		$wid=$_POST['wid'];
// 		// $wid=64;
// 		$one=D('Weibo')->where('id='.$wid)->find();
// 		// dump($one);
// 		$this->assign('wid',$wid);
// 		$this->assign('one',$one);
// 		$this->display();
// 	}
// 	/**
// 	 * 处理转播
// 	 */
// 	public function doForward(){
// 		$data['transpond_id']=$_POST['wid'];
// 		$data['content']=$_POST['content'];
// 		$res=D('Weibo')->addAction($data);
// 		if($res){
// 			$return['success']=1;
// 		}else{
// 			$return['success']=0;
// 			$return['msg']='发布失败';
// 		}
// 		exit(json_encode($return));
// 	}
// 	/**
// 	 * 删除微博
// 	 */
// 	public function delweibo(){
// 		$wid=$_POST['wid'];
// 		$res=D('Weibo')->delAction($wid);
// 		if($res){
// 			$return['success']=1;
// 			$return['msg']='删除成功';
// 		}else{
// 			$return['success']=0;
// 			$return['msg']='删除失败';
// 		}
// 		exit(json_encode($return));
// 	}
// 	/**
// 	 * 显示评论
// 	 */
// 	public function showComment(){
// 		$wid=$_POST['wid'];
// 		// $wid=64;
// 		$list=D('WeiboComment')->where('wid='.$wid)->select();
// 		// dump($list);
// 		$this->assign('wid',$wid);
// 		$this->assign('list',$list);
// 		$this->display();
// 	}
// 	/**
// 	 * 处理评论
// 	 */
// 	public function doComment(){
// 		$data['wid']=$_POST['wid'];
// 		$data['content']=$_POST['content'];
// 		$res=D('WeiboComment')->addAction($data);
// 		if($res){
// 			$return['success']=1;
// 		}else{
// 			$return['success']=0;
// 			$return['msg']='发布失败';
// 		}
// 		exit(json_encode($return));
// 	}
// }
?>