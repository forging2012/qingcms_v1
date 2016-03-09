<?php
/**
 * 用户资料字段模型
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class UserFieldModel extends Model{
	protected $tableName='user_field'; // 数据库表名
	
	/**
	 * 获取全部
	 */
	public function getAll(){
		return $this->order('module, displayorder ASC')->select();
	}
	/**
	 * 删除字段
	 */
	public function deleteField($ids){
		$ids=explode(',',$ids);
		if(!empty($ids)){
			foreach($ids as $v){
				$map.='OR id='.$v.' ';
			}
			$map=substr($map,2); // 删除多余的OR
			return $this->where($map)->delete();
		}else{
			return false;
		}
	}
/**
 */
}
?>