<?php
/**
 *  插件可使用的钩子
 *  把{:hook('followBlock')}添加到模板中
 */
class FollowBlockPlugins extends AbstractPlugins{
	/**
	 * 返回插件信息
	 */
	public function info(){
		return array(
				'zhName'=>'Follow关注模块',
				'author'=>'xiaowang',
				'info'=>'方便用户关于网站相关的微博或空间',
				'version'=>'1',
				'site'=>'http://www.qingcms.com/',
				'admin'=>1  //有后台管理
		);
	}
	/**
	 * 键名为admin的为指定的后台管理类，类中的admin方法为后台显示方法
	 */
	public function hooksList(){
		return array('admin'=>'MainHooks');
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
