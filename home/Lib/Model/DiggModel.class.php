<?php
/**
 * 赞过的文章
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class DiggModel extends Model{
	protected $tableName='digg';
	/**
	 * 获取分页设置条数
	 */
	private function _getPageNum(){
		return getPageNum();
	}
	/**
	 * 获取统计数据
	 */
	public function countNum($uid){
		return $this->where('uid='.$uid)->count();
	}
	/**
	 * 获取用户推荐的列表
	 */
	public function getUserDigg($uid){
		$map='uid='.$uid.' AND vote=1';
		$order='id desc';
		return $this->_get($map,$order,'tid');
	}
	/**
	 * 从表中取出数据
	 * 
	 * @param
	 *        	$map
	 * @param
	 *        	$order
	 * @return
	 *
	 */
	private function _get($map,$order='id desc',$field=''){
		// 导入分页类 实现分页
		import('ORG.Util.Page');
		$count=$this->where($map)->count();
		$Page=new Page($count,$this->_getPageNum()); // 实例化分页类 传入总记录数和每页显示癿记录数
		$show=$Page->show(); // 分页显示输出
		$list=$this->where($map)->order($order)->limit($Page->firstRow.','.$Page->listRows)->field($field)->select();
		$data['page']=$show;
		$data['list']=$list;
		return $data;
	}
	/**
	 * 推荐过该文章的用户
	 * 
	 * @return int:uid
	 */
	public function diggThisUser($tid){
		return $this->where('tid='.$tid.' AND vote=1')->field('uid')->select();
	}
	/**
	 * 投票操作
	 */
	public function addAction($tid,$type){
		$mid=mid(); // 当前登录用户
		$return['success']=0;
		if(!(isLogged())){
			// 未登录
			$return['msg']=L('nologin');
			return $return;
		}
		
		$t=D('Content')->where("id=".$tid)->find();
		// 不能给自己投票
		if($t['uid']==$mid){
			$return['msg']=L('noself');
			return $return;
		}
		// 已经投过票了
		if(isvoted($tid,$mid)){
			$return['msg']=L('hadvote');
			return $return;
		}
		$data['uid']=$mid;
		$data['tid']=$tid;
		$data['vote']=$type;
		$data['ctime']=time();
		$res=$this->data($data)->add();
		if($res>0){
			$return['success']=1;
			// 关联操作 顶
			if($type==1){
				// 发出推荐通知，只通知up，down无通知
				D('Notify')->diggNotify($mid,$t['uid'],$tid);
				D('Content')->Inc1($tid,'up');
			}else if($type==-1){
				// 踩
				D('Content')->Inc1($tid,'down');
			}
		}else{
			$return['msg']=L('error');
		}
		
		return $return;
	}
	/**
	 * 添加后的管理操作
	 */
	private function _addAfter($sw){}
}
?>