<?php
/**
 *  插件可使用的钩子
 *  把{:hook('stopScrollHere')}添加到模板中
 */
class ScrollStopPlugins extends AbstractPlugins{
	/**
	 * 返回插件信息
	 */
	public function info(){
		return array(
				'zhName'=>'右侧滚动停止',
				'author'=>'xiaowang',
				'info'=>'右侧滚动在指定的地方停止',
				'version'=>'1',
				'site'=>'http://www.qingcms.com/',
				'admin'=>0
		);
	}
	/**
	 * 键名为admin的为指定的后台管理类，类中的admin方法为后台显示方法
	 */
	public function hooksList(){
		return array('admin'=>'ScrollStopHooks');
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
