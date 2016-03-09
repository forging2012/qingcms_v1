<?php 
/**
 * 验证器，验证过滤器
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class Validator{
	public static $_instance=null; //过滤器类实例
	/**
	 * 加载器
	*/
	public static function load(){
		if(self::$_instance==null){
			self::$_instance=new Validator();
		}
		return self::$_instance;
		return new Validator(); //TODO: Should Delete ! 只用于帮助ZendStudio提示
	}
	/**
	 * 正则表达式
	 * $subject = "abcdef";
	 * $pattern = '/^def/';
	 * array("abcdef","regexp" ,"邮箱格式错误"	,"filter",1,array('/^def/')),
	 *
	 * @param  $pattern 要搜索的模式，字符串类型。
	 * @param  $subject 输入字符串。
	 */
	public function v_regexp($str,$pattern){
		return preg_match($pattern, $str);
	}
	/**
	 * 必填
	 */
	public function v_required($str){
		return empty($str)?false:true;
	}
	/**
	 * 验证过滤器，是否合法，返回true or false
	 */
	public function v_int($str){
		return is_int($str);
	}
	public function v_number($str){
		return is_numeric($str);
		//		return preg_match("/[0-9]*/",$str);
	}
	public function v_float($str){
		return is_float($str);
	}
	/**
	 * 验证过滤器
	 * 只允许包含 字母和数字，下划线
	 */
	public function v_abc123($str){
		return preg_match("/^[a-zA-Z0-9_]*$/",$str);
	}
	/**
	 * 验证过滤器
	 * ipv4地址
	 */
	public function v_ip($str){
		return (ip2long($str)===false)?false:true;
	}
	/**
	 * 验证游邮箱
	 * 验证过滤器
	 */
	public function v_email($str){
		return preg_match('/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',$str)?true:false;
	}
	/**
	 * 域名
	 */
	public function v_domain($str){
		return preg_match('/^(https?://)?([0-9a-zA-Z-]+)\.(?:\w{3}|\w{2}|\w{3}\.\w{2})$/i',$str);
	}
	/**
	 * URL链接格式
	 *
	 * @param $str 		链接
	 * @param $scheme	是否验证scheme
	 */
	public function v_url($str,$scheme=true){
		$d=parse_url($str);
		if($scheme && !in_array($d['scheme'],array("http","https"))){
			return false;
		}
		return preg_match('/([0-9a-zA-Z-]+)\.(?:\w{3}|\w{2}|\w{3}\.\w{2})$/i',$d['host'])>0;
	}
	/**
	 * 验证纯中文,不包括字母数字
	 * 中英文/数字/下划线/减号组成:preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_-]{2,6}$/u",$name)
	 */
	public function v_zh($str,$min=0,$max=0){
		$min=(int)$min;
		$max=(int)$max;
		if($max>0){
			$rule='/^[\x{4e00}-\x{9fa5}]{'.$min.','.$max.'}$/u';
		}else{
			$rule='/^[\x{4e00}-\x{9fa5}]*$/u';//不限定字数{0,~}
		}
		return preg_match($rule,$str);
	}
}
 
