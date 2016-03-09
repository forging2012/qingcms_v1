<?php
/**
 * 插件模型
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class PluginModel extends Model{
	protected $tableName='plugin'; // 数据库表名
	                                 // 停止
	public function stopByid($id){
		$res=$this->where("id=".$id)->setField('status','0');
		$this->_saveTemp(); // 状态改变后再缓存
		return $res;
	}
	// 启动
	public function startByid($id){
		$res=$this->where("id=".$id)->setField('status','1');
		$this->_saveTemp(); // 状态改变后再缓存
		return $res;
	}
	// 插入插件信息
	public function doPluginInfo($info){
		if(!is_array($info)){
			return false;
		}	
		$infoArr=array('name','zhName','author','info','version','site','qcVersion','admin');
		foreach($infoArr as $v){
			$data[$v]=$info[$v];
		}
		$data['status']='1';
		$res=$this->data($data)->add();
		if(!($res===false)){
			$this->_saveTemp(); // 状态改变后再缓存
		}	
		return $res;
	}
	// 卸载
	public function doUninstall($name){
		$res=$this->where("name='".$name."'")->delete();
		$this->_saveTemp(); // 状态改变后再缓存
		return $res;
	}
	/**
	 * 获取所有开启的插件的所有钩子列表
	 */
	public function getHooksList(){
		$list=$this->where('status=1')->select();
		if($list=='')
			return false; // 没有开启插件，不处理
		foreach($list as $v){
			$Plist[]=$v['name'];
		}
		$Hlist=Plugins::getHooksList($Plist);
		return $Hlist;
	}
	/**
	 * 缓存钩子数组
	 */
	private function _saveTemp(){
		$list=$this->getHooksList();
		SaveTemp($list,'~hooks.php');
	}
	/**
	 * 更新插件数据缓存
	 */
	private function _updateData(){
		$pre=C('DB_PREFIX');
		$sql=" SELECT * FROM ".$pre."plugin_data ";
		$res=$this->query($sql);
		foreach($res as $v){
			$data[$v['plugin']]=unserialize($v['data']);
		}
		SaveTemp($data,'~plugin_data.php');
	}
	/**
	 * 插件的数据管理
	 */
	public function In($plugin,$data){
		if(!$plugin)
			return false;
		$plugin=$this->_strip_key($plugin);
		// $data=addcslashes($data,"'");
		$pre=C('DB_PREFIX'); // 当前model为plugin，不是plugin_data 只能使用原生sql__Table__表示的是plugin表
		$insert_sql="REPLACE INTO ".$pre."plugin_data (`plugin`,`data`,`mtime`) VALUES ";
		$insert_sql.=" ('".$plugin."','".serialize($data)."','".date('Y-m-d H:i:s')."');";
		// 插入数据列表
		$result=$this->execute($insert_sql);
		if(!($result===false))
			$this->_updateData(); // 状态改变后再缓存
		return $result;
	}
	/**
	 * 取出插件数据
	 * 
	 * @param unknown_type $plugin        	
	 * @param unknown_type $data
	 *        	//当前model为plugin，不是plugin_data 只能使用原生sql
	 *        	//数据插入时是什么样就返回什么样
	 */
	public function Out($plugin){
		if(!$plugin)
			return false;
		$pre=C('DB_PREFIX');
		$sql=" SELECT * FROM ".$pre."plugin_data WHERE plugin='".$plugin."' ";
		$res=$this->query($sql);
		return unserialize($res[0]['data']);
	}
	/**
	 * 过滤key
	 * 
	 * @param string $key
	 *        	只允许格式 数字字母下划线，list:key 不允许出现html代码 和这些符号 ' " & * % ^ $ ? ->
	 * @return string
	 */
	protected function _strip_key($key=''){
		$key=strip_tags($key);
		$key=str_replace(array('\'','"','&','*','%','^','$','?','->'),'',$key);
		return $key;
	}
}
?>