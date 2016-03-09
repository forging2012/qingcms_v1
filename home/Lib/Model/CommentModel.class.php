<?php
/**
 * 评论内容
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class CommentModel extends Model{
	protected $tableName='comment';
	public $page=10;
	/**
	 * 获取分页设置条数
	 */
	private function _getPageNum(){
		return $this->page;
	}
	/**
	 * 获取收到的评论
	 */
	public function receive($uid){
		// 把当前登录用户的评论消息置零
		D('Message')->setZero($uid,'comment');
		$map="reply_uid=".$uid;
		return $this->_get($map);
	}
	/**
	 * 发出的评论
	 */
	public function send($uid){
		$map="uid=".$uid;
		return $this->_get($map);
	}
	/**
	 * 显示评论
	 */
	public function showTid($tid){
		$map="tid=".$tid;
		return $this->_get($map,'id asc');
	}
	/**
	 * 取出数据
	 */
	private function _get($map='',$order='id desc'){
		// 导入分页类 实现分页
		require_once (C('Class_iPage'));
		$count=$this->where($map)->count();
		$Page=new Page($count,$this->_getPageNum()); // 实例化分页类 传入总记录数和每页显示癿记录数
		$Page->ma=MODULE_NAME."/".ACTION_NAME;
		global $system_data;
		if($system_data['view']['isRewrite'])
			$Page->is_rewrite=1;
		$show=$Page->show(); // 分页显示输出
		
		$list=$this->where($map)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
		$data['page']=$show;
		$data['list']=$list;
		return $data;
	}
	/**
	 * 统计数据
	 */
	public function countNum($tid){
		$res=$this->where('tid='.$tid)->count();
		return $res;
	}
	/**
	 * 插入数据
	 * 进行评论操作的同时应该进行的操作
	 * 1.文章表 Content 评论字段自增1
	 * 2.消息表 Message 表添加消息
	 */
	public function addAction($data){
		if($data['uid']==''){
			$data['uid']=mid();
		}
		if($data['ctime']==''){
			$data['ctime']=time();
		}
		$check=$this->Icheck($data);
		if(!$check['success']){
			$return['success']=0;
			$return['msg']=$check['msg'];
			return $return;
		}
		// 处理用户输入
		//$data['content']=t($data['content']);
		
		// position处理
		$position=$this->where('tid='.$data['tid'])->max('position');
		if($position){
			$data['position']=intval($position)+1; // 返回最大数值
		}else{
			$data['position']=1;
		}
		$res=$this->data($data)->add();
		if($res>0){
			$return['success']=1;
			$this->_addAfter($data);
		}else{
			$return['success']=0;
		}
		return $return;
	}
	/**
	 * 在成功插入数据后进行的
	 */
	private function _addAfter($data){
		// 文章评论数加1
		D('Content')->Inc1($data['tid'],'comment');
		// 用户评论消息加1
		D('Message')->addMsg($data['reply_uid'],'comment');
		// 积分操作
		D('CreditUser')->action($data['uid'],'comment');
	}
	/**
	 * 检查提交是否正确
	 * return:msg/success
	 */
	private function Icheck($data){
		global $Sys;
		$minlength=$Sys['view']['minComLen'];
		$length=$Sys['view']['comLen'];
		
		$return['success']=0;
		$len=get_str_length($data['content']);
		if($len<$minlength){
			$return['msg']=L('tooshort');
		}else if($len>$length){
			$return['msg']=L('toolong');
		}else if($len<=$length){
			$return['success']=1;
		}
		return $return;
	}
	/**
	 * 删除操作
	 * 关联操作：
	 * 1.文章表 Content 评论字段自-1
	 *
	 * 必须传入commentid 和tid才能删除，关联处理
	 * 由要删除的评论id获取需要自增文章的id
	 * 而不是前台提供，防止用户恶意使用，减少他人的comment字段
	 *
	 * 评论需要是本人的才可以删除
	 */
	public function delAction($id,$mid,$tid){
		if($id==''||$tid==''){
			return 0;
		}
		if($mid=='')
			$mid=mid();
			// 属于本人的评论 uid=$mid
		$res=$this->where(' id='.$id.' AND uid='.$mid.' AND tid='.$tid)->delete();
		if($res){
			$this->_delAfter($tid,$mid);
			return 1;
		}else{
			return 0;
		}
	}
	/**
	 * 删除数据后的操作
	 */
	private function _delAfter($tid,$mid){
		// 文章的评论数减1
		D('Content')->Dec1($tid,'comment');
		// 积分操作
		D('CreditUser')->action($mid,'delcomment');
	}
}
?>