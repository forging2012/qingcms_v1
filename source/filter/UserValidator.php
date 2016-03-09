<?php
/**
 * 1.验证提示   邮箱格式错误，用户名格式错误
 * 2.数据过滤   发布文章过滤
 * 3.需要过滤的先过滤再验证。
 * 
 * 有关用户帐号的过滤器
 */
class UserValidator{
	/**
	 * 验证器,按照验证器的顺序，必须验证的字段，就算data未传入该字段
	 * "iEmail"=>"email"
	 *  别名方法    =>对应的字段
	 */
// 	protected $validator=array("iEmail"=>"email","username","password","nickname");
// 	protected $validator=array(array('email','iEmail',false),array('username',null,false),"password",array('nickname',null,false));
// 	protected $validator=array("iEmail"=>"email","password");
	/**
	 * 检测email的合格性
	 */
	public function v_iEmail($email){
        if(!$this->v_email($email)){
        	$this->error="邮箱格式错误";
        	return false;
        } 
		return true;//正确
	}
	/**
	 * 验证username合格性
	 */
	public function v_username_OFF($username){
		//数字和字母和下划线
		if(!preg_match('/^[a-z0-9_-]{6,25}$/i',$username)){
			$this->error="用户名由6~25位字母、数字、下划线、减号组成";
			return false;
		}
		return true;
	}
	/**
	 * 验证用户名是否合法
	 * 合法的用户名由2-10位的中英文/数字/下划线/减号组成
	 */
	public function v_username($username){
		//中英文/数字/下划线/减号组成
		if(!preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_-]{2,20}$/u",$username)){
			$this->error="用户名由2-20位的中英文/数字/下划线/减号组成";
			return false;
		}
		return true;
	}
	/**
	 * 验证密码是否合格
	 * 密码格式
	 * 1:6~25位
	 * 2：只能包含 字母、数字、下划线
	 * 3：强度检测*
	 */
	public function v_password($pw){
		//数字和字母和下划线
		if(!preg_match('/^[a-z0-9_]{6,25}$/i',$pw)){
			$this->error="密码由6~25位字母、数字、下划线组成";
			return false;
		}
		return true;
	}
	/**
	 * 编码后的密码，md5字串，32位小写数字
	 */
	public function v_codepassword($codepassword){
		if(!preg_match('/^[a-z0-9]{32}$/',$codepassword)){
			$this->error="密码编码有误";
			return false;
		}
		return true;
	}
	
}


?>