<?php
/**
 * 内容类型
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class ContentTypeModel extends Model{
	protected	$tableName	=	'content_type';
	public function getType($typeid){
		return $this->where('typeid='.$typeid)->field('nid,name')->find();		
	}
}
?>