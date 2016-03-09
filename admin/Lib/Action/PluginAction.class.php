<?php
/**
 * 插件操作
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class PluginAction extends InitAction{
	/**
	 * 已经安装的插件，开启或停止的
	 */
	private function PluginsInList(){
		return pluginIn();//已安装，开启或停止的
	}
	/**
	 * 插件列表
	 */
	public function index(){
		if($_GET['checkValid']){
			Plugins::checkValid();
			$msg=Plugins::$errorMsg;
			$this->assign('msg',$msg);
		}
		//已经安装的插件
		$installed=$this->PluginsInList();
		Plugins::validList();
		$all=Plugins::$validListInfo;
		foreach ($all as $k=>$v){
			$allName[]=$k;
		}
		//已安装的插件是否出错
		if(!empty($installed)){
			foreach ($installed as $k=>$v){
				if(!in_array($v['name'], $allName)) $installed[$k]['valid']=0;
				else   $installed[$k]['valid']=1;
				unset($all[$v['name']]);//从所有插件中删除已经安装的
			}			
		}
		$this->assign('yeslist',$installed);		
	    $this->assign('nolist',$all);
		$this->display('list');	 
	}
	/**
	 * 停用插件
	 */
	public function stopPlugin(){
		if($_GET['id']=='') exit('0');
		$res=D('Plugin')->stopByid($_GET['id']);
		if(!($res===false)) {
			$this->_updateCssJs();//更新css缓存
			$this->success('停用成功');
		} else {
			$this->error('停用失败');
		}
	}
	/**
	 * 启用插件
	 */
	public function startPlugin(){
		if($_GET['id']=='') exit('0');
		$res=D('Plugin')->startByid($_GET['id']);
		if(!($res===false)) {
			$this->_updateCssJs();//更新css缓存
			$this->success('启用成功');
		} else {
			$this->error('启用失败');
		}
	}
	/**
	 * 卸载插件
	 */
	public function uninstallPlugin(){
		$name=$_GET['name'];
		if($name=='') exit('0');
		Plugins::uninstall($name);
		$res=D('Plugin')->doUninstall($name);
		if(!($res===false)) {
			$this->_updateCssJs();//更新css缓存
			$this->success('卸载成功');
		} else {
			$this->error('卸载失败');
		}
	}
	/**
	 * 安装插件
	 */
	public function installPlugin(){
		$name=$_GET['name'];
		$info=Plugins::install($name);
		$info['name']=$name;	
		
		$res=D('Plugin')->doPluginInfo($info);
		if(!($res===false)) {
			$this->_updateCssJs();//更新css缓存
			$this->success('安装成功');
		} else {
			$this->error('安装失败');
		}
	}
	/**
	 * 由文件夹名字取得对象
	 */
	private function _getObj($name){
		$file=PATH_PLUGIN.'/'.$name.'/'.$name.'Plugins.class.php';
		if(!file_exists($file)) return false; //文件不存在
		include_once($file);
		$name=$name.'Plugins';
		if(!class_exists($name)) return false; //类不存在
		$obj=new $name();  //TestPlugin
		return $obj;
	}
	/**
	 * 管理插件
	 * 在模板文件manage.html中调用插件的管理界面
	 * {:Plugins::admin($name);}
	 */
	public function manage(){
		$p=$_GET['plugin'];
		if(!$p) exit('0');            
		$this->assign('p',$p);
		$this->display('manage');
	}
	/**
	 * 执行插件的管理Action下的方法
	 */
	public function toPluginAdmin(){
		$p=$_GET['plugin'];
		$m=$_GET['method'];
		if(!$p) exit('0');
		Plugins::runAdminAction($p,$m);
	}
	/**
	 * 获取所有插件的所有钩子列表
	 */
	public function getHooksList(){
//        $list=Plugins::validList();
//         dump($list);
//        $list=Plugins::getHooksList($list);
//        dump($list);
	   $l=D('Plugin')->getHooksList();
	   dump($l);
	  // dump(Plugins::$path);
	}
	/**
	 * 更新css/js缓存
	 */
	private function _updateCssJs(){
         $t=A('Tools');
         $t->updateCssJsCache();
	}

}