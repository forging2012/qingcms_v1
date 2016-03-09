<?php
/**
 * ad模型
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class AdModel extends Model{
	protected $tableName='ad';
	/**
	 */
	public function getAd($place){
		$map='is_active=1 AND place='.$place;
		return $this->where($map)->select();
	}
}
?>