<?php
/**
 * 微博评论模型
 * @deprecated
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class WeiboCommentModel extends Model{
	protected $tableName='weibo_comment';
	protected $error=''; // 错误信息
	/**
	 * 发表评论
	 * 
	 * @param unknown_type $data        	
	 * @return number
	 */
	public function addAction($data){
		$data['uid']=mid();
		$res=$this->data($data)->add();
		if($res){
			$this->_addAfter($data['wid']);
			return 1;
		}else
			return 0;
	}
	/**
	 * 发布评论后的操作
	 */
	private function _addAfter($wid){
		// 微博评论数增1
		D('Weibo')->Inc1($wid,'comment');
		// 发出通知
	}
}
?>