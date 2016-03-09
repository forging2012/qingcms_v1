<?php
/**
 *
 * @author xiaowang <736523132@qq.com>
 * @link http://www.qingmvc.com
 * @copyright 2012 QingMVC for PHP
 */
class UrlGenerator{
	/**
	 * 请求域名|qingcms.com
	 *
	 * @see $_SERVER['HTTP_HOST']
	 * @return string
	 */
	public static function getHostUrl($showHostName=true){
		if($showHostName){
			//显示主机域名
			$host='http://'.$_SERVER['HTTP_HOST'];
		}else{
			//不显示主机域名
			$host='';
		}
		return $host;
	}
	/**
	 * 
	 * @return string
	 */
	public static function getRootpath(){
		$value=dirname($_SERVER['SCRIPT_NAME']);
		if($value=='\\' || $value==''){
			$value='/';
		}
		return $value;
	}
	/**
	 * 定义全局url|网站首页/应用首页|[__ROOT__ __WEB__ __C__ __A__] 等等
	 *
	 * dump(get_defined_vars());
	 * dump(get_defined_constants(true)['user']);
	 *
	 */
	public static function defineGlobalUrl(){
		//__ROOT__>>__APP__>>__PATHINFO__>>__URL__
		$host	   =self::getHostUrl();
		$rootpath  =self::getRootpath();
		$__root__  =$host.$rootpath;
		if($__root__=='/' || $__root__==''){
			$__root__='/';
		}else{
			$__root__=rtrim($__root__,'/');
		}
		defined('__QCROOT__') or define('__QCROOT__'	,$__root__);
	}
}
?>