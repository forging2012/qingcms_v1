<?php
/**
 * 消息模型
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class MessageModel extends Model{
	protected $tableName='message';
	/**
	 * 添加统计数据
	 *
	 * @param int|array $uid
	 *        	用户ID
	 * @param string $field
	 *        	字段
	 * @param int $IncNum
	 *        	默认1
	 * @return void
	 */
	function addMsg($uid,$field,$IncNum=1){
		// 已存在表中，自增1
		if($this->where('uid='.$uid)->find()){
			return $this->where('uid='.$uid)->setInc($field,$IncNum);
		}else{
			// 新插入数据
			$data['uid']=$uid;
			$data[$field]=1;
			return $this->add($data);
		}
	}
	/**
	 * 清零
	 *
	 * @param int|array $uid
	 *        	用户ID
	 * @param string $type        	
	 * @return void
	 */
	function setZero($uid,$field){
		if($uid==''){
			$uid=mid();
		}
		return $this->where('uid='.$uid)->setField($field,0);
	}
}
?>