<?php 
/**
 * Request==Input==用户请求
 * 
 * 从用户请求中获取数据 input
 * $_REQUEST   默认情况下包含了 $_GET，$_POST 和 $_COOKIE 的数组。
 * 此方法旨在过滤 通过 get post cookie提交的数据 ，提高系统安全性
 */
class Request{
	public static $_instance=null; //实例
	/**
	 * 加载器
	 */
	public static function load(){
		if(self::$_instance==null){
			self::$_instance=new Request();
		}
		return self::$_instance;
		return new Request(); //TODO: Should Delete ! 只用于帮助ZendStudio提示
	}
	/**
	 * 返回$_REQUEST的参数值
	 * @param  string $name
	 * @param  string $filter
	 * @param  mixed $defaultValue
	 * @return mixed
	 */
	public function _request($key,$defaultValue=null){
		return $this->_param($_REQUEST,$key,$defaultValue);
	}
	/**
	 * 返回通过GET提交的参数值
	 * 
	 * $this->_get('name','abc123',"");
	 * $this->_get('id','int',0);
	 * 
	 * @param  string $name
	 * @param  string $filter
	 * @param  mixed $defaultValue
	 * @return mixed
	 */
	public function _get($key,$defaultValue=null){
		return $this->_param($_GET,$key,$defaultValue);
	}
	/**
	 * 返回通过POST提交的参数值
	 * @param  string $name
	 * @param  string $filter
	 * @param  mixed $defaultValue
	 * @return mixed
	 */
	public function _post($key,$defaultValue=null){
		return $this->_param($_POST,$key,$defaultValue);
	}
	/**
	 * 返回通过Cookie提交的参数值
	 * @param  string $name
	 * @param  string $filter
	 * @param  mixed $defaultValue
	 * @return mixed
	 */
	public function _cookie($key,$defaultValue=null){
		return $this->_param($_COOKIE,$key,$defaultValue);
	}
	/**
	 * 执行过滤
	 * @param $data 	从哪里取值 get/post/request/cookie
	 * @param $key  	键值
	 * @param $filter   过滤器
	 * @param $default  默认值
	 */
	private function _param($data,$key,$default=null){
		//键值不存在返回默认值
		if(isset($data[$key])){ 
			$value=$data[$key]; 
		}else{
			return $default;
		}
		//只对用户请求数据进行引号转义，其他复杂功能请使用filter
		return Filter::load()->f_magic_quotes_gpc($value);
	}
	/**
	 * 返回请求类型，例如GET, POST, HEAD, PUT, DELETE.
	 */
	public function requestType(){
		return strtoupper(isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:'GET');
	}
	/**
	 * @return boolean 是否是GET类型请求
	 */
	public  function isGet(){
		return  strtolower($_SERVER['REQUEST_METHOD'])==strtolower('get');
	}
	/**
	 * @return boolean 是否是POST类型请求
	 */
	public  function isPost(){
		return  strtolower($_SERVER['REQUEST_METHOD'])==strtolower('post');
	}
	/**
	 * 判断是否是ajax请求
	 * 1.在jquery框架中，对于通过它的$.ajax, $.get, or $.post方法请求网页内容时，它会向服务器传递一个HTTP_X_REQUESTED_WITH的参数
	 */
	public  function isAjax(){
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest'){
			return true;
		}
		return  false;
	}
	/**
	 * 重定向到一个指定的链接
	 * @param string $url  
	 * @param boolean $exit  终止代码运行
	 * @param integer $statusCode http code 默认为302
	 */
	public function redirect($url,$exit=true,$statusCode=302){
		redirect($url,$exit,$statusCode);
	}
}