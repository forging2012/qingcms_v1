<?php
/**
 * 内容分类
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class ContentCateModel extends Model{
	protected	$tableName	=	'content_cate';
	/**
	 *获取顶级导航 包括外部链接
	 */
	 public function getCate1(){
	  return 	$this->where("parentid=0")->order('displayorder')->select();	 	 
	 }
	 /**
	  * 获取所有栏目 不包括外部链接
	  */
	 public function getIndexCate(){
	 	return 	$this->where("parentid=0 AND type!=5")->order('displayorder')->select(); 
	 }
	 
	/**
	 * 获取2级栏目
	 * @param int $cateid
	 */
	 
	public function getCate2($Topid){
	 return	$this->where('parentid='.$Topid)->order('displayorder')->select();
	}
	/**
	 * 判断 是否是正确 可访问栏目  link=null
	 * field("id,name")
	 * 	//字符型需要带 引号
	 */
	public function byNid($name){
		$cate=$this->where('nid="'.$name.'"')->find();
		return $cate;
	}
	public function byId($id,$field=''){
		return $this->where('id='.$id)->field($field)->find();
	}
	/**
	 * 获得默认分类
	 */
	public function getDefCate(){
		return $this->where('def=1')->find();
	}
	/**
	 * 获取某个分类信息
	 */
	public function getCateInfo($cateid){
	   return $this->where('id='.$cateid)->find(); //一维数组
	}
	/**
	 * 有nid 返回id
	 * @param  int
	 */
	public function getCateId($name){
		$id=$this->where('nid="'.$name.'"')->field('id')->find(); //一维数组
		return intval($id['id']);  //返回id 强制整型 不能返回数组
	}
	/**
	 * 由 id 返回name
	 */
	public function getCateName($cateid){
		$name=$this->where('id='.$cateid)->field('name')->find(); //一维数组
		return $name['name'];  // 不能返回数组	
	}
	
	/**
	 * 获取上一级栏目信息
	 */
	public function parentCate($parentid){
		return $this->where('id='.$parentid)->field('nid,name')->find();
	}
	/**
	 * 移动到新的栏目
	 */
	public function moveFromAdmin($ids,$cate){
		$ids =explode(',',$ids);
		if(!empty($ids)){
			foreach($ids as $v){
				$map.='OR id='.$v.' ';
			}
			$map = substr($map,2); // 删除多余的OR
			$pre=C('DB_PREFIX');
			$sql="update ".$pre."content_cate  set parentid=".$cate." where ".$map;
			$res=M()->execute($sql);//返回影响列数
			return $res?1:0;
		} else {
			return false;
		}
	}
	/**
	 * 删除栏目
	 */
	public function delFromAdmin($ids){
		$ids =explode(',',$ids);
		if(!empty($ids)){
			foreach($ids as $v){
				$map.='OR id='.$v.' ';
			}
			$map = substr($map,2); // 删除多余的OR
			$pre=C('DB_PREFIX');
			$sql="delete from ".$pre."content_cate  where ".$map;
			$res=M()->execute($sql);//返回影响列数
			return $res?1:0;
		} else {
			return false;
		}
	}	
}
?>