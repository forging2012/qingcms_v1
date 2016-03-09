<?php
/**
 * 微博模型
 * @deprecated
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class WeiboModel extends Model{
	protected $tableName='weibo';
	protected $error=''; // 错误信息
	
	/**
	 * 添加
	 * type=
	 * 0文本
	 * 1图片
	 */
	public function addAction($data){
		$data['uid']=mid();
		$data['type']=0; // 默认类型为文本
		$data['ctime']=time();
		if($data['image']!=''){
			$data['type_data']=serialize($data['image']);
			$data['type']=1;
		}
		return $this->data($data)->add();
	}
	
	/**
	 * 取得错误信息
	 */
	public function getMsg(){
		return $this->error;
	}
	/**
	 * uid
	 */
	public function getUserList($uid){
		$map='uid='.$uid;
		return $this->_get($map);
	}
	/**
	 * 获取数据
	 */
	private function _get($map,$order='id desc'){
		import('ORG.Util.Page');
		$count=$this->where($map)->count();
		$Page=new Page($count,$this->_getPageNum()); // 实例化分页类 传入总记录数和每页显示癿记录数
		$show=$Page->show(); // 分页显示输出
		$list=$this->where($map)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
		$data['page']=$show;
		$data['list']=$list;
		$data['count']=$count;
		return $data;
	}
	/**
	 * 获取分页设置条数
	 */
	private function _getPageNum(){
		return getPageNum();
	}
	/**
	 * 删除
	 */
	public function delAction($wid){
		$mid=mid();
		return $this->where('id='.$wid.' AND uid='.$mid)->delete();
	}
	/**
	 * 关联操作：添加评论
	 * 某个字段自增1
	 */
	public function Inc1($id,$field){
		return $this->where('id='.$id)->setInc($field,1);
	}
	/**
	 * 关联操作：删除操作
	 * 某个字段减1
	 */
	public function Dec1($id,$field){
		return $this->where('id='.$id)->setDec($field,1);
	}
	/**
	 * 取得分组用户的微博
	 */
	public function fromGroup($gid){
		if($gid=='all'){
			$map='';
		}else{
			$mid=mid();
			// 改组的用户
			$ulist=D('FollowLink')->where('uid='.$mid.' AND gid='.$gid)->field('fid')->select();
			$map='uid='.$ulist[0]['fid'];
			foreach($ulist as $k=>$v){
				if($k==0){
					continue;
				}
				$map.=' OR  uid='.$v['fid'];
			}
		}
		// return $map;
		return $this->where($map)->limit(10)->order('id desc')->select();
	}
}
?>