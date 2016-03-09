<?php
/**
 * 关注组
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class FollowGroupModel extends Model{
	protected $tableName='follow_group';
	
	/**
	 * 取得用户的分组,只有查看自己的分组
	 */
	public function getList(){
		$map='uid='.mid();
		return $this->where($map)->select();
	}
	/**
	 * 取得某用户所在组情况
	 */
	public function listByFid($fid){
		$mid=mid(); // 登录用户的分组
		$list=$this->where('uid='.$mid)->select();
		foreach($list as $k=>$v){
			// 判断用户是否在改组
			$gid=$v['id'];
			$r=D('FollowLink')->where('gid='.$gid.' AND uid='.$mid.' AND fid='.$fid)->find();
			if($r>0){
				$list[$k]['checked']=1;
			}else{
				$list[$k]['checked']=0;
			}
		}
		return $list;
	}
	/**
	 * 删除操作
	 */
	public function delAction($gid){
		$mid=mid();
		$res=$this->where('id='.$gid.' AND uid='.$mid)->delete();
		if($res){
			$this->_delAfter($mid,$gid);
			return 1;
		}
		return 0;
	}
	/**
	 * 删除分组后
	 * 删除该用户，该分组的管理数据
	 */
	private function _delAfter($mid,$gid){
		D('FollowLink')->where('uid='.$mid.' AND gid='.$gid)->delete();
	}
	/**
	 * 添加分组
	 */
	public function addAction($name){
		$data['name']=$name;
		$data['uid']=mid();
		$res=$this->data($data)->add();
		return $res; // 返回插入id
	}
	/**
	 * 编辑分组
	 */
	public function editAction($gid,$name){
		$mid=mid();
		$data['name']=$name;
		$data['ctime']=time();
		$res=$this->where('id='.$gid.' AND uid='.$mid)->save($data);
		return $res?1:0;
	}
	/**
	 * 取得该用户所在的分组
	 */
	function getGroupList($fid){
		$mid=mid();
		// 取得该用户所在的分组
		$list=D('FollowLink')->where('uid='.$mid.' AND fid='.$fid)->field('gid')->select();
		$map='id='.$list[0]['gid'];
		foreach($list as $k=>$v){
			if($k==0){
				continue;
			}
			$map.=' OR  id='.$v['gid'];
		}
		$glist=$this->where($map)->order('id desc')->select();
		return $glist;
	}
	/**
	 * 把用户添加到某些组
	 * //操作：follow_link
	 * //unique：uid fid gid
	 */
	public function setUserGroup($fid,$gids){
		$mid=mid();
		// 删除之前的记录
		D('FollowLink')->where('uid='.$mid.' AND fid='.$fid)->delete();
		// 更新数据,把分组置0
		if(!$gids)
			return 1;
		$pre=tablePre();
		if(is_array($gids)){
			foreach($gids as $k=>$v){
				$values.='('.$mid.','.$fid.','.(int)$v.'),';
			}
			$values=rtrim($values,','); // 删除最后一个逗号
			$q="REPLACE INTO ".$pre."follow_link (uid,fid,gid) VALUES ".$values;
		}
		$res=$this->execute($q);
		return ($res==false&&is_bool($res))?0:1;
	}
}

?>