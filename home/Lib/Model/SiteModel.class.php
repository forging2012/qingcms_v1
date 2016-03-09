<?php
/**
 * site
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 * @deprecated
 */
class SiteModel_OFF extends Model{
	protected $tableName='site';
	public function getusername($uid){
		$name=D('user')->where('uid='.$uid)->field('name')->find();
		return $name['name'];
	}
	public function isuser($uid){
		$isno=D('user')->where('uid='.$uid)->field('uid')->find();
		if(!$isno){
			return false;
		}
		return true;
	}
}
?>