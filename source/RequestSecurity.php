<?php 
/**
 * Request==Input==用户请求
 * 
 * 从用户请求中获取数据 input
 * $_REQUEST   默认情况下包含了 $_GET，$_POST 和 $_COOKIE 的数组。
 * 此方法旨在过滤 通过 get post cookie提交的数据 ，提高系统安全性
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class RequestSecurity{
	public static $_instance=null; //过滤器类实例
	/**
	 * 加载器
	 */
	public static function load($className=__CLASS__){
		if(self::$_instance==null){
			self::$_instance=new $className();
		}
		return self::$_instance;
		return new RequestSecurity();
	}
	/**
	 * 强制安全验证用户请求数据,在应用开始处理http请求的时候，执行该方法
	 * public $forceSecurity=false; 
	 * 
	 *  简单转义用户提交的数据
	 * 1.Sql安全:转义引号，过滤 select,delete词语
	 * 2. 高级的安全功能请使用Security
	 * 3. 多维尽量避免不使用
	 * 
	 * qingphp约定 $_REQUEST 只包含$_GET和$_POST
	 * 具体需要设置 php.ini variables_order
	 * 
	 * 影响较大，给get/post/request转义引号,容易导致二次转义 @deprecated 
	 * @param $type  post/get/cookie/all 需要安全处理的数据
	 * @return 不返回数据
	 */
	public function clearData($type="post"){
		$Ofilter=$this;
		switch (strtolower($type)){
			case "get":
				$_GET	 =array_map(array($Ofilter,"_safeFilter"), $_GET);
				return $_GET;
				break;
			case "post":
				$_POST	 =array_map(array($Ofilter,"_safeFilter"), $_POST);
				return $_POST;
				break;
			case "cookie":
				$_COOKIE =array_map(array($Ofilter,"_safeFilter"), $_COOKIE);
				return $_COOKIE;
				break;
			case "request":
				$_REQUEST =array_map(array($Ofilter,"_safeFilter"), $_REQUEST);
				return $_REQUEST;
				break;
			case "all":
			default	  :
				$_GET	  =array_map(array($Ofilter,"_safeFilter"), $_GET);
				$_POST	  =array_map(array($Ofilter,"_safeFilter"), $_POST);
				$_COOKIE  =array_map(array($Ofilter,"_safeFilter"), $_COOKIE);
				$_REQUEST =array_map(array($Ofilter,"_safeFilter"), $_REQUEST);
				return true;
				break;
		}
	}
	/**
	 * 安全过滤方法
	 * @param  $str
	 */
	private function _safeFilter($str){
		return $this->f_abc123($str,'-');
	}
	/**
	 *  净化过滤器
	 *  只返回字母（大写 小写）和数字和下划线
	 *  附加字符
	 */
	public function f_abc123($str,$plus=''){
		$plus	=preg_quote($plus);
		$pattern='[^a-zA-Z0-9_'.$plus.']';
		return preg_replace('/'.$pattern.'/','',$str);
	}
}
?>