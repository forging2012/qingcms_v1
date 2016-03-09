<?php
/**
 * 地区模型
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class AreaModel extends Model{
	protected $tableName='area';
	/**
	 * 获取登录用户的地区html
	 */
	public function MidAreaTree(){
		$mid=mid();
		$userArea=D('User')->where('uid='.$mid)->field('location')->find();
		$location=unserialize($userArea['location']);
		$province=$location['province']; // 省
		$city=$location['city']; // 城市
		
		$list['iprovince']=$province;
		$list['icity']=$city;
		$list['province']=$this->where('pid=0')->select();
		$list['city']=$this->where('pid='.$province)->select();
		return $list;
	}
	/**
	 * 显示某省的城市
	 */
	public function getCity($p){
		$c=$this->where('pid='.$p)->select();
		return $c;
	}
}
?>