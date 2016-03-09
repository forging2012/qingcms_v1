<?php
/**
 * 用户资料模型
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class UserProfileModel extends Model{
	protected $tableName='user_profile';
	/**
	 * 更新个人信息
	 * 注意点：
	 * 1：插入字符串时，必须加上双引号
	 * 2：serialize化的分号两边不能有空格，否则无法逆解析unserialize
	 * 3.//返回false、0、1
	 */
	public function update($data){
		$mid=mid();
		// 判断字段 contact
		if($data['type']=='contact'){
			unset($data['type']); // 销毁单个数组元素
			$sql="UPDATE  __TABLE__ SET  contact='".serialize($data)."' WHERE uid=".$mid;
			$res=$this->execute($sql);
		}
		// intro
		if($data['type']=='intro'){
			unset($data['type']); // 销毁单个数组元素
			$sql="UPDATE  __TABLE__ SET intro='".serialize($data)."' WHERE uid=".$mid;
			$res=$this->execute($sql);
		}
		return $res;
	}
	/**
	 * 获取信息,用户空间，或者帐号设置
	 */
	public function getProfile($uid){
		if($uid==''){
			return false;
		}
		$l=$this->where('uid='.$uid)->field('contact,intro')->find();
		if($l>0){
			// 取得联系方式 contact
			$c=unserialize($l['contact']);
			// 取得个人信息 intro
			$i=unserialize($l['intro']);
		}else if(MODULE_NAME=='User'&&ACTION_NAME=='account'&&$uid==mid()){
			// 为新用户插入新行,在帐号设置若登录者没有建表则
			$this->_insertNew();
		}
		/**
		 * 取得系统现在设置的字段
		 */
		$field=D('admin://UserField')->getAll();
		// 数据插入
		if($l>0){
			foreach($field as $k=>$v){
				// $v['key'] 字段键值 contact intro
				if($v['module']=='contact'){
					$field[$k]['value']=$c[$v['key']];
					$field[$k]['privacy']=$c[$v['key'].'privacy'];
				}else if($v['module']=='intro'){
					$field[$k]['value']=$i[$v['key']];
					$field[$k]['privacy']=$i[$v['key'].'privacy'];
				}
			}
		}
		return $field;
	}
	/**
	 * 插入新行，数据为空
	 */
	private function _insertNew(){
		$mid=mid();
		$insert_sql="INSERT INTO __TABLE__ (`uid`) VALUES (".$mid.')';
		$this->execute($insert_sql);
	}
}
?>