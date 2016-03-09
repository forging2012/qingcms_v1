<?php
/**
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class ClassModel extends Model{
	protected $tableName='class';
	// protected $tableName = 'class_admin';
	
	// public function getclass($uid,$start='0',$length='20'){
	// //if($uid=='') return false;
	// $class=$this->where('uid='.$uid)->limit($start.','.$length)->field("classid,classname")->select();
	// return $class;
	// }
	public function getclass($uid,$type='space'){
		$data=array();
		import('ORG.Util.Page');
		
		if($uid!=''&&$type='space'){
			$count=$this->where('uid='.$uid)->count();
			$Page=new Page($count,8); // 实例化分页类 传入总记录数和每页显示癿记录数
			$list=$this->order('sitenum DESC')->where('uid='.$uid)->field("classid,parentid,classname")->limit($Page->firstRow.','.$Page->listRows)->select();
			// $list= $class->order('classid')->field("classid,parentid,classname")->limit("15,8")->select();
		}else if($type=='square'){
			$count=$this->count();
			$Page=new Page($count,8); // 实例化分页类 传入总记录数和每页显示癿记录数
			$list=$this->order('sitenum DESC')->field("classid,parentid,classname")->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		if(!$list)
			return false;
		$show=$Page->show(); // 分页显示输出
		$site=D('site');
		foreach($list as $key=>$value){
			$classid=$value['classid'];
			$site_c=$site->where('class='.$classid)->field("url,name")->limit("0,20")->select();
			// var_dump($site_c);
			foreach($site_c as $k=>$v){
				$list[$key]['insite'].="<a target='_blank' href='".$v['url']."'>".$v['name']."</a>";
			}
		}
		$data['page']=$show;
		$data['class']=$list;
		
		return $data;
	}
}
?>