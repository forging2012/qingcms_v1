<?php
/**
 * 内容分类模型
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class ContentCateModel extends Model{
	protected $tableName='content_cate';
	
	/**
	 * 移动到新的栏目
	 */
	public function moveFromAdmin($ids,$cate){
		$ids=explode(',',$ids);
		if(!empty($ids)){
			foreach($ids as $v){
				$map.='OR id='.$v.' ';
			}
			$map=substr($map,2); // 删除多余的OR
			$pre=C('DB_PREFIX');
			$sql="update ".$pre."content_cate  set parentid=".$cate." where ".$map;
			$res=M()->execute($sql); // 返回影响列数
			if($res)
				$this->saveTemp();
			return $res?1:0;
		}else{
			return false;
		}
	}
	/**
	 * 删除栏目
	 */
	public function delFromAdmin($ids){
		$ids=explode(',',$ids);
		if(!empty($ids)){
			foreach($ids as $v){
				$map.='OR id='.$v.' ';
			}
			$map=substr($map,2); // 删除多余的OR
			$pre=C('DB_PREFIX');
			$sql="delete from ".$pre."content_cate  where ".$map;
			$res=M()->execute($sql); // 返回影响列数
			if($res)
				$this->saveTemp();
			return $res?1:0;
		}else{
			return false;
		}
	}
	/**
	 * 更新缓存
	 */
	public function saveTemp(){
		$list=$this->select();
		SaveTemp($list,'~category.php');
	}
/**
 * 栏目缓存数据处理
 */
	// private function format(){
	// $list=$this->select();
	// foreach ($list as $v){
	// if($v['type_id']>0){
	// $type_list[$v['type_id']]=$v['id'];
	// }
	// }
	
	// }
}
?>