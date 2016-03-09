<?php
/**
 * 用户模型
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class UserModel extends Model{
	protected $tableName='user';
	public $errorMsg=''; // 错误信息
	public function getusername($uid){
		$name=D('user')->where('uid='.$uid)->field('name')->find();
		return $name['name'];
	}
	public function isuser($uid){
		$isno=D('user')->where('uid='.$uid)->field('uid')->find();
		if(!$isno){
			return false;
		}
		return true;
	}
	/**
	 * 获取用户设置
	 */
	public function getUserInfo($uid,$from=''){
		// field('name,sex,location,zonetitle,zonename')->
		$u=$this->where('uid='.$uid)->find();
		if($u['location']){
			$u['location']=unserialize($u['location']);
		}else{
			$u['location']['location']=L('no');
		}
		// 获取用户积分
		$score=D('CreditUser')->getUserScore($uid);
		$u['score']=$score;
		return $u;
	}
	/**
	 * 更新用户信息
	 * 
	 * @return bool 0,1,2,msg
	 */
	public function update($data){
		// 重置session用户名，有可能用户已经改名
		$_SESSION["mname"]=$data['name'];
		
		$return['success']=0;
		$c=$this->_checkName($data['name']);
		if($c['success']==0){
			$return['msg']=$c['msg'];
			return $return;
		}
		$mid=mid();
		
		if($data['province']=='no'&&$data['city']=='no'){
			$data['location']=null;
		}else if($data['province']>0||$data['city']>0){
			if($data['province']!='no')
				$p=D('Area')->where('area_id='.$data['province'])->find();
			else
				$p=null;
			if($data['city']!='no')
				$c=D('Area')->where('area_id='.$data['city'])->find();
			else
				$c=null;
			
			$location['location']=$p['title'].' '.$c['title'];
			
			$location['province']=$data['province'];
			$location['city']=$data['city'];
			$data['location']=serialize($location);
		}
		
		$res=$this->where('uid='.$mid)->data($data)->save();
		if($res>0){ // 1
			$return['success']=1;
		}else if($res==0&&is_int($res)){ // 0
			$return['success']=2;
			$return['msg']=L('noChange');
		}else{ // false
			$return['msg']=L('error');
		}
		return $return;
	}
	/**
	 * 更改密码
	 * oldpassword password repassword
	 * 1.新密码是否合格
	 * 2.新密码和原始密码不能相同
	 * 3.原始密码是否正确
	 */
	public function changePassword($data){
		$mid=mid();
		$o=$data['oldpassword'];
		$p=$data['password'];
		$r=$data['repassword'];
		
		$return['success']=0;
		// 两次密码输入不相同
		if($p!=$r){
			$return['msg']=L('differentPw');
			return $return;
		}
		// 密码不合法
		$cp=$this->_checkPassword($p);
		if(!$cp['success']){
			$return['msg']=$cp['msg'];
			return $return;
		}
		// 旧密码不正确
		if(!$this->_Password($o)){
			$return['msg']=L('errorOldPw');
			return $return;
		}
		$sql="UPDATE  __TABLE__ SET  password='".md5($p)."' WHERE uid=".$mid;
		$res=$this->execute($sql);
		if($res){
			$return['success']=1;
		}else{
			$return['msg']=L('error');
		}
		return $return;
	}
	/**
	 * 注册操纵
	 */
	public function Reg($data){
		$return['success']=0;
		// 检查email
		if(!($this->_checkEmail($data['email']))){
			$return['msg']=L('WrongEmail');
			return $return;
		}
		// 昵称
		$name=$this->_checkName($data['name']);
		if(!$name['success']){
			$return['msg']=$name['msg'];
			return $return;
		}
		// 密码
		$pw=$this->_checkPassword($data['password']);
		if(!$pw['success']){
			$return['msg']=$pw['msg'];
			return $return;
		}
		// email的唯一性
		if(!$this->UniqueEmail($data['email'])){
			$return['msg']=L('EmailHad');
			return $return;
		}
		$data['password']=md5($data['password']);
		$data['ctime']=time();
		// 微博登录时
		if($data['location']){
			$loc['location']=$data['location'];
			$data['location']=serialize($loc);
		}
		$res=$this->data($data)->add();
		if($res){
			$return['uid']=$res; // 返回用户id
			$return['success']=1;
		}else{
			$return['msg']=L('error');
		}
		return $return;
	}
	/**
	 * 检查email的唯一性
	 */
	private function UniqueEmail($email){
		return ($this->where('email="'.$email.'"')->find())?0:1;
	}
	/**
	 * 验证用户名是否合法
	 * 合法的用户名由2-6位的中英文/数字/下划线/减号组成
	 */
	private function _checkName($name){
		$return['success']=0;
		// 错误，strlen（）一个汉字为3，mb_strlen才为1
		$len=mb_strlen($name,'UTF-8');
		// 长度2~10
		if($len<2||$len>10){
			$return['msg']=L('wrongLenName');
			return $return;
		}
		// 中英文/数字/下划线/减号组成
		if(!preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_-]+$/u",$name)){
			$return['msg']=L('spewordName');
			return $return;
		}
		$return['success']=1;
		return $return;
	}
	// /**
	// * 检查给定的用户名是否合法
	// *
	// * 合法的用户名由2-6位的中英文/数字/下划线/减号组成
	// *
	// * @param string $username
	// * @return boolean
	// */
	// private function _name($username){
	// // GB2312: preg_match("/^[".chr(0xa1)."-".chr(0xff)."A-Za-z0-9_-]+$/", $username)
	// return preg_match("/^[\x{4e00}-\x{9fa5}A-Za-z0-9_-]+$/u", $username) &&
	// mb_strlen($username, 'UTF-8') >= 2 &&
	// mb_strlen($username, 'UTF-8') <= 6;
	// }
	/**
	 * 验证密码是否合格
	 * 密码格式
	 * 1:6~16位
	 * 2：只能包含 字母、数字、下划线
	 * 3：强度检测*
	 */
	private function _checkPassword($pw){
		$return['success']=0;
		$len=strlen($pw);
		// 长度
		if($len<6||$len>18){
			$return['msg']=L('wrongLenPw');
			return $return;
		}
		// 数字和字母和下划线
		if(!preg_match('/^\w+$/',$pw)){
			$return['msg']=L('wrongzcPw');
			return $return;
		}
		$return['success']=1;
		return $return;
	}
	/**
	 * 检测email的合格性
	 */
	private function _checkEmail($email){
		// preg_match("/[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/i", $email)
		if(!preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',$email)){
			return false; // 不正确
		}
		return true; // 正确
	}
	/**
	 * 验证密码是否正确
	 */
	private function _Password($pw){
		$mid=mid();
		$p=$this->where('uid='.$mid)->field('password')->find();
		if(md5($pw)==$p['password']){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 激活
	 */
	public function activeFromAdmin($uids){
		$uids=explode(',',$uids);
		if(!empty($uids)){
			foreach($uids as $v){
				$map.='OR uid='.$v.' ';
			}
			$map=substr($map,2); // 删除多余的OR
			$pre=C('DB_PREFIX');
			$sql="update ".$pre."user  set is_active=1 where ".$map;
			$res=M()->execute($sql); // 返回影响列数
			                         // update时数据不改变时会返回影响行数为0，判断false
			return ($res===false)?0:1;
		}else{
			return false;
		}
	}
	/**
	 * 删除
	 */
	public function delFromAdmin($uids){
		$uids=explode(',',$uids);
		if(!empty($uids)){
			foreach($uids as $v){
				$map.='OR uid='.$v.' ';
			}
			$map=substr($map,2); // 删除多余的OR
			$pre=C('DB_PREFIX');
			$sql="delete from ".$pre."user  where ".$map;
			$res=M()->execute($sql); // 返回影响列数
			                         // update时数据不改变时会返回影响行数为0，判断false
			return ($res===false)?0:1;
		}else{
			return false;
		}
	}
	/**
	 * 添加用户、编辑用户
	 */
	public function addEditFromAdmin($data){
		$return['success']=0;
		if($data['email']==''||$data['password']==''||$data['name']==''){
			$return['msg']='邮箱、密码、昵称不能为空';
			return $return;
		}
		$uid=$data['uid'];
		unset($data['uid']);
		// 编辑
		if($uid){
			$res=D('User')->where('uid='.$uid)->data($data)->save();
			// 添加
		}else{
			// email的唯一性
			if(!$this->UniqueEmail($data['email'])){
				$return['msg']='邮箱已经被注册';
				return $return;
			}
			$res=D('User')->data($data)->add();
		}
		
		if(!($res===false))
			$return['success']=1;
		else
			$return['msg']='保存失败';
		return $return;
	}
}
?>