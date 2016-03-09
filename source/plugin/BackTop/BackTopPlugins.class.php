<?php
/**
 * 无钩子，只需引入js文件
 */
class BackTopPlugins extends AbstractPlugins{
	/**
	 * 返回插件信息
	 */
	public function info(){
		return array(
				'zhName'=>'返回顶部',
				'author'=>'xiaowang',
				'info'=>'返回顶部',
				'version'=>'1.0',
				'site'=>'http://www.qingcms.com/',
				'admin'=>0);		// 没有后台管理
		
	}
	/**
	 * 键名为admin的为指定的后台管理类，类中的admin方法为后台显示方法
	 */
	public function hooksList(){
		return array();
	}
	public function start(){
		return true;
	}
	public function install(){
		return true;
	}
	public function uninstall(){
		return true;
	}
}
