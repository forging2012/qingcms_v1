<?php
/**
 * 关注模型
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class FollowModel extends Model{
	protected $tableName='follow';
	/**
	 * 执行关注操作
	 */
	public function addAction($fid){
		$mid=mid();
		// 不能关注自己
		if($mid==$fid)
			return 0;
		$data['fid']=$fid;
		$data['uid']=$mid;
		$res=$this->data($data)->add();
		if($res){
			$this->_addAfter($fid,$mid);
			return 1;
		}
		return 0;
	}
	// 每添加一个用户，将其添加到未分组
	private function _addAfter($fid,$mid){
		$data['fid']=$fid;
		$data['uid']=$mid;
		$data['gid']=0;
		D('FollowLink')->data($data)->add();
	}
	/**
	 * 取消关注
	 */
	public function delAction($fid){
		$mid=mid();
		$res=$this->where('uid='.$mid.' AND fid='.$fid)->delete();
		if($res){
			$this->_delAfter($fid,$mid);
			return 1;
		}
		return 0;
	}
	// 取消关注用户，将其与组的关联删除
	private function _delAfter($fid,$mid){
		D('FollowLink')->where('uid='.$mid.' AND fid='.$fid)->delete();
	}
	/**
	 * 检测两者关系
	 * 0:未关注
	 * 1：已关注
	 * 2：互相关注
	 */
	public function relation($uid,$fid){
		// 已关注
		$f_ing=$this->where('uid='.$uid.' AND fid='.$fid)->find();
		if($f_ing>0){
			$return=1;
			// 对方是否关注你，是否是你的粉丝
			$f_er=$this->where('uid='.$fid.' AND fid='.$uid)->find();
			if($f_er>0)
				$return=2;
		}else{
			$return=0;
		}
		return $return;
	}
	/**
	 * 取得用户的关注列表，
	 * //只能查看自己的分组情况
	 * 1：全部
	 * 2：未分组
	 * 3：某组
	 */
	public function getFollowing($gid,$mid){
		// 取得分组内用户，确该组属于用户时才能查看 uid='.$mid
		$list=D('FollowLink')->where('gid='.$gid.' AND uid='.$mid)->field('fid')->select();
		return $list;
	}
	/**
	 * 未分组
	 */
	public function getFollowingNo($mid){
		// //所有关注
		// $flist=$this->where('uid='.$mid)->order('id desc')->field('fid,uid')->select();
		// //所有的分组
		// $linklist=D('FollowLink')->where('uid='.$mid)->field('fid,uid')->select();
		// foreach ($flist as $k=>$v){
		// if(!in_array($v, $linklist)){
		// $no[$k]=$v;
		// }
		// }
		return D('FollowLink')->where('uid='.$mid.' AND gid=0')->field('fid')->select();
	}
	/**
	 * 全部
	 */
	public function getFollowingAll($mid){
		return $list=$this->where('uid='.$mid)->field('fid')->select();
	}
	
	/**
	 * 取得用户的粉丝
	 */
	public function getFollower($uid){
		return $this->where('fid='.$uid)->select();
	}
	/**
	 * 统计
	 */
	public function countNum($uid,$type){
		// 关注的
		if($type=='following'){
			return $this->where('uid='.$uid)->count();
		}
		// 粉丝
		if($type=='follower'){
			return $this->where('fid='.$uid)->count();
		}
	}
}
?>