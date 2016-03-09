<?php
/**
 * 系统配置模型
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class SystemModel extends Model{
	protected $tableName='system'; // 数据库表名
	protected $noSeriArr=array('shareCode','countCode'); // 不进行序列化的段
	/**
	 * 写入参数列表
	 */
	public function lput($listName='',$listData=array()){
		// 初始化list_name
		$listName=$this->_strip_key($listName);
		$result=false;
		
		// 格式化数据
		if(is_array($listData)){
			$insert_sql.="REPLACE INTO __TABLE__ (`list`,`keyword`,`value`,`mtime`) VALUES ";
			foreach($listData as $key=>$data){
				if($key=='__hash__')
					continue;
				if(in_array($key,$this->noSeriArr)){ // 不进行序列化
					$data=addcslashes($data,"'");
					$insert_sql.=" ('$listName','$key','".$data."','".date('Y-m-d H:i:s')."') ,";
				}else
					$insert_sql.=" ('$listName','$key','".serialize($data)."','".date('Y-m-d H:i:s')."') ,";
			}
			
			$insert_sql=rtrim($insert_sql,','); // 删除最后一个逗号
			                                     // 插入数据列表
			$result=$this->execute($insert_sql);
		}
		// 缓存数据
		$this->saveTemp();
		return $result;
	}
	/**
	 * 读取参数列表
	 */
	public function lget($list_name='',$pluginName=''){
		if(!$list_name){
			return false;
		}
		$list_name=$this->_strip_key($list_name);
		// 取得插件的设置信息
		if($pluginName){
			// key在mysql中属于关键字，作为字段时要加引号，相应的有order等
			$map=' list="'.$list_name.'" AND  keyword="'.$pluginName.'" '; // 字符串要加双引号
			$result=$this->where($map)->find();
			return unserialize($result['value']);
		}
		// 其他设置信息
		$data=array();
		$map='list="'.$list_name.'"'; // 字符串要加双引号
		$result=$this->order('id')->where($map)->select();
		
		if($result){
			foreach($result as $v){
				if(!in_array($v['keyword'],$this->noSeriArr)){ // 不进行序列化
					$data[$v['keyword']]=unserialize($v['value']);
				}else{
					$data[$v['keyword']]=$v['value'];
				}
			}
		}
		return $data;
	}
	
	/**
	 * 过滤key
	 *
	 * @param string $key
	 *        	只允许格式 数字字母下划线，list:key 不允许出现html代码 和这些符号 ' " & * % ^ $ ? ->
	 * @return string
	 */
	protected function _strip_key($key=''){
		return $key;
		if($key==''){
			return $this->list_name;
		}else{
			$key=strip_tags($key);
			$key=str_replace(array('\'','"','&','*','%','^','$','?','->'),'',$key);
			return $key;
		}
	}
	/**
	 * 把系统设置缓存,序列花后的数据
	 */
	private function saveTemp(){
		// $d=addcslashes(serialize($this->format()),"'");
		// $d2='<?php $system_cache=\''.$d.'\'; ';
		// SaveTemp($d2, '~System.php');
		$d=$this->format();
		SaveTemp($d,'~System.php');
	}
	// 把数据格式化
	public function format(){
		$sys=$this->select();
		foreach($sys as $v){
			if(!in_array($v['keyword'],$this->noSeriArr)) // 不进行序列化
				$value=unserialize($v['value']);
			else
				$value=$v['value'];
			$data[$v['list']][$v['keyword']]=$value;
		}
		return $data;
	}
}
?>