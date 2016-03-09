<?php 
/**
 * 一些帮助方法
 * @author xiaowang <736523132@qq.com>
 * @copyright 2013 http://qingcms.com All rights reserved.
 */
class UrlHelper{
	public static $_instance=null; //实例
	/**
	 * 加载器
	 */
	public static function load(){
		if(self::$_instance==null){
			self::$_instance=new UrlHelper();
		}
		return self::$_instance;
		return new UrlHelper(); //TODO: Should Delete ! 只用于帮助ZendStudio提示
	}
	/**
	 * 更新url参数;
	 * 存在参数则更新，否则添加;
	 *
	 * createUrlByQueryString
	 * createUrlByQueryString
	 * $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].$_SERVER['PATH_INFO'].'?'.$_SERVER['QUERY_STRING'];
	 *
	 * @param  $params  QueryString参数
	 * @param  $url		指定url
	 */
	public function updateUrlParams($params){
		parse_str($_SERVER['QUERY_STRING'],$queryStringArray);
		$queryStringArray=array_merge($queryStringArray,$params);
		$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].$_SERVER['PATH_INFO'].'?'.http_build_query($queryStringArray);
		return $url;
	}
	/**
	 * 根据服务器路径获取URL地址
	 * PATH_WEB:程序入口文件
	 *
	 * @param  $path 	   路径，不需要是绝对路径
	 * @param  $rootPath 相对的根路径
	 * @param  $rootUrl  url开头
	 */
	public function getUrlByPath($path,$rootPath=null,$rootUrl=null){
		static $_url=array();
		if(($realpath=realpath($path))===false){
			exit("路径不存在{$path}");
		}
		if(isset($_url[$realpath])){
			return $_url[$realpath];
		}
		if(is_null($rootPath)){
			$rootPath=dirname($_SERVER['SCRIPT_FILENAME']);
		}
		if(is_null($rootUrl)){
			$rootUrl=dirname($_SERVER['SCRIPT_NAME']);
			$rootUrl=str_replace("\\", "/", $rootUrl);
		}
		$url=str_replace(realpath($rootPath),"",$realpath);
		$url=str_replace("\\", "/", $url);
		$url=rtrim($rootUrl,"/").$url;
		$_url[$realpath]=$url;
		return $url;
	}
	
}

 