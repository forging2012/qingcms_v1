<?php
/**
 * 系统通知
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class NotifyModel extends Model{
	protected $tableName='notify'; // 数据库表名
	private $type=array('digg'=>'推荐了你的文章：','follow'=>'关注了你');
	/**
	 * 显示通知
	 * 
	 * @param        	
	 *
	 */
	public function showAction($uid){
		$map='receive='.$uid;
		$order='notify_id desc';
		import('ORG.Util.Page');
		$count=$this->where($map)->count();
		$Page=new Page($count,10); // 实例化分页类 传入总记录数和每页显示癿记录数
		$show=$Page->show(); // 分页显示输出
		$list=$this->where($map)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
		if(!$list){
			return false;
		}
		
		foreach($list as $k=>$v){
			$list[$k]['data']=unserialize($v['data']);
			$list[$k]['type']=$this->type[$list[$k]['type']];
		}
		$return['list']=$list;
		$return['page']=$show;
		return $return;
	}
	/**
	 * 插入数据
	 */
	private function _insert($data){
		$data['ctime']=time();
		$data['data']=serialize($data['data']);
		$res=$this->data($data)->add();
		if($res>0){
			// 通知提醒
			D('Message')->addMsg($data['receive'],'notify');
		}
		return $res;
	}
	/**
	 * 推荐通知
	 * 只通知up，down无通知
	 */
	public function diggNotify($from,$receive,$tid){
		$data['from']=$from;
		$data['receive']=$receive;
		$data['type']='digg';
		$data['data']['tid']=$tid;
		$res=$this->_insert($data);
	}
}
?>