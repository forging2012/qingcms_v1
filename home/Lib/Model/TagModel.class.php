<?php
/**
 * 标签模型
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class TagModel extends Model{
	protected $tableName='tag';
	// public function
	public function tagList($content_id){
		$list=D('TagRelationship')->where('content_id='.$content_id)->select();
		if(!$list)
			return null;
			// 进行处理
		foreach($list as $v){
			$map.='OR tag_id='.$v['tag_id'].' ';
		}
		$map=substr($map,2); // 删除多余的OR
		$list=$this->where($map)->field('tag_id,name')->select();
		return $list;
	}
	/**
	 * 插入数据操作
	 */
	public function addAction($tags,$tid){
		$tags=$this->_checkTag($tags); // 返回的是数组
		if($this->_checkTagLength($tags)){
			// 标签大于5时只取前五个
			$tag5=array();
			for($i=0;$i<5;$i++){
				$tag5[$i]=$tags[$i];
			}
			$tags=$tag5;
		}
		$tag_id=$this->saveTag($tags,$tid);
	}
	/**
	 * 保存标签
	 * 传入数组
	 */
	public function saveTag($tagArr,$content_id){
		foreach($tagArr as $tag){
			$had=$this->where(' name="'.$tag.'" ')->find();
			// 标签已经存在
			if($had){
				$tag_id=$had['tag_id'];
				$this->where('tag_id='.$tag_id)->setInc('count',1);
			}else{
				// 不存在时插入并返回id
				$t['name']=$tag;
				$t['count']=1;
				$tag_id=$this->data($t)->add();
			}
			// 插入关系表
			$r['content_id']=$content_id;
			$r['tag_id']=$tag_id;
			D('TagRelationship')->data($r)->add();
		}
	}
	/**
	 * 检测标签的长度
	 */
	public function _checkTagLength($res){
		// 标签最多只能有五个
		if(count($res)>5){
			return true;
		}	
		return false;
	}
	/**
	 * //过滤标签 返回数组
	 */
	public function _checkTag($tag){
		// 将标签字符的中文，会空格替换为英文,
		$tag=str_replace('，',',',$tag);
		$tag=str_replace(' ',',',$tag);
		if(!$tag)
			return false;
			// 以逗号为分割把字符串分割为数组
		$tag=explode(',',$tag);
		$res=array();
		foreach($tag as $k=>$v){
			// mb_strlen中文和英文的占用一致 不能超过10个字符,过滤超过10个字符 和为空的值
			if(mb_strlen($v,'UTF-8')>'10' || $v==''){
				continue;
			}
			$v=Filter::load()->f_zhabc123($v);
			$res[].=$v;
		}
		// 标签不能重复
		$tagArr=$res;
		foreach($tagArr as $k=>$t){
			unset($tagArr[$k]);
			if(in_array($t,$tagArr)){
				unset($res[$k]);
			}
		}
		return $res; // 数组
	}
	/**
	 * 获取分页设置条数
	 */
	private function _getPageNum(){
		return getPageNum();
	}
	/**
	 * 取出数据
	 * 
	 * @param
	 *        	$map
	 * @param
	 *        	$order
	 */
	private function _get($map,$order='tag_id desc',$table=''){
		// 导入分页类 实现分页
		// import('ORG.Util.Page');
		import_class('iPage');
		$count=$this->where($map)->count();
		$Page=new Page($count,$this->_getPageNum()); // 实例化分页类 传入总记录数和每页显示癿记录数
		$show=$Page->show(); // 分页显示输出
		if(!$table)
			$list=$this->where($map)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
		else
			$list=D($table)->where($map)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
		$data['page']=$show;
		$data['list']=$list;
		$data['count']=$count;
		return $data;
	}
	/**
	 * 搜索
	 */
	public function doSearch($keyword){
		$map=" tag_name LIKE '%{$keyword}%' ";
		return $this->_get($map,'');
	}
	public function by_tag_id($id){
		$map='tag_id='.$id;
		return $this->_get($map,'content_id desc','TagRelationship');
		// return D('TagRelationship')->where()->select();
	}
}
?>