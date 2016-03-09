<?php
class SystemModel extends Model {
	protected	$tableName	=	'system';	// 数据库表名
    protected   $noSeriArr=array('countcode');//不进行序列化的段
	/**
	 * 写入参数列表
	 */
	public function lput($listName='',$listData=array()) {
		//初始化list_name
		$listName	=	$this->_strip_key($listName);
		$result = false;

		//格式化数据
		if(is_array($listData)){
			$insert_sql	.=	"REPLACE INTO __TABLE__ (`list`,`key`,`value`,`mtime`) VALUES ";
			foreach($listData as $key=>$data){
				if($key=='__hash__') continue;
				if(in_array($key, $this->noSeriArr))//不进行序列化
					$insert_sql	.=	" ('$listName','$key','".$data."','".date('Y-m-d H:i:s')."') ,";
				else
				  $insert_sql	.=	" ('$listName','$key','".serialize($data)."','".date('Y-m-d H:i:s')."') ,";
			}
			
			$insert_sql	=rtrim($insert_sql,','); //删除最后一个逗号
			//插入数据列表
			$result	=$this->execute($insert_sql);	
		}
		return $result;
	}

	/**
	 * 读取参数列表
	 *
	 */
	public function lget($list_name='') {
		$list_name = $this->_strip_key($list_name);
			$data = array();
			$map='list="'.$list_name.'"'; //字符串要加双引号			
			$result	= $this->order('id')->where($map)->select();				
			if($result){
				foreach($result as $v){
					if(!in_array($v['key'], $this->noSeriArr))//不进行序列化
					    $data[$v['key']]=unserialize($v['value']);		
					else 
						$data[$v['key']]=$v['value'];
			      }
			}
		return $data;
	}

	/**
	 * 过滤key
	 *
	 * @param string  $key 只允许格式 数字字母下划线，list:key 不允许出现html代码 和这些符号 ' " & * % ^ $ ? ->
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
		
}
?>