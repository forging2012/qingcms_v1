<?php
/**
 * 应用支持类
 * @author xiaowang <736523132@qq.com>
 * @copyright 2013 http://qingcms.com All rights reserved.
 */
class app{
	/**
	 * @alias $view_vars
	 * @var 视图变量
	 */
	public static $vars=array();
	/**
	 * 模板变量赋值
	 * @param $name  变量名称
	 * @param $value 变量值
	 */
	public static function assign($name,$value=""){
		if(is_array($name)){
			self::$vars=array_merge(self::$vars,$name);
		}else {
			self::$vars[$name]=$value;
		}
	}
	/**
	 * 视图显示
	 * 只有这个函数里面的变量能在模版中使用
	 * @return string
	 */
	public static function display($__tpl__){
		// 模板阵列变量分解成为独立变量
		extract(self::$vars,EXTR_OVERWRITE);
		$__include__=PATH_TPL."/{$__tpl__}.html";
		include PATH_TPL."/tpl.html";
	}
	/**
	 * 错误信息
	 * @return string
	 */
	public static function error($msg='操作失败',$data=array()){
		self::message(0,$msg,$data);
	}
	/**
	 * 成功信息
	 * @return string
	 */
	public static function success($msg='操作成功',$data=array()){
		self::message(1,$msg,$data);
	}
	/**
	 * 返回消息参数
	 *
	 * @param  $succ  操作处理状态,成功/失败,0/1,可以代替code作为处理代码,-1,0,1,2
	 * @param  $msg	     消息
	 * @param  $data  附加数据
	 * @param  $code  处理代码,附加代码  !deleted
	 */
	public static function message($succ,$msg='',$data=array()){
		$return=array(
				'success'=>$succ,
				'message'=>$msg
		);
		if(is_array($data) && !empty($data)){
			$return=array_merge($return,$data);
		}
		exit(json_encode($return));
	}	
}
