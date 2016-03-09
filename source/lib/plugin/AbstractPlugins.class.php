<?php
abstract  class AbstractPlugins{
    /**
     * 定义插件类必须定义的类
     */
	abstract function info();      //插件的信息
	abstract function hooksList(); // 说明插件里含有的hooks类必须使用到的
	abstract function start();     //插件的启动
	abstract function install();   //插件的安装
	abstract function uninstall(); //插件的卸载
	
 }
