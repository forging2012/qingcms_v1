<?php
/**
 * 用户积分
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class CreditUserModel extends Model{
	protected $tableName='credit_user';
	/**
	 * 取得用户积分
	 */
	public function getUserScore($uid){
		$credit=$this->where('uid='.$uid)->find();
		$score=$credit['gold'];
		// $score=689;
		$return['gold']=$credit['gold'];
		$return['score']=$score;
		return $return;
	}
	/**
	 * 积分操作
	 */
	public function action($uid,$action){
		$pre=tablePre();
		$sql="select * from ".$pre."credit_rule where action='".$action."' ";
		$rule=$this->query($sql);
		if($rule>0){
			$gold=intval($rule[0]['gold']);
			$q="UPDATE ".$pre."credit_user SET gold=gold+{$gold} WHERE uid=".$uid;
			$res=$this->execute($q);
		}
	}
	/**
	 * 创建表,初始积分10
	 */
	public function creatTable($uid){
		$pre=tablePre();
		$q="REPLACE INTO ".$pre."credit_user (uid,gold) VALUES ({$uid},10) ";
		$this->execute($q);
	}
}
?>