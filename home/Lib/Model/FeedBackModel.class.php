<?php
/**
 * 建议反馈
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class FeedBackModel extends Model{
	protected $tableName='feedback';
	
	/**
	 * 添加反馈
	 */
	
	/**
	 * 添加举报
	 * 
	 * @param
	 *        	id,guest
	 */
	public function addReport($con){
		$data['type'] ='report';
		$data['uid']  =mid();
		$data['ctime']=time();
		$data['data'] =serialize($con);
		$res=$this->data($data)->add();
		if($res>0){
			return 1;
		}else{
			return 0;
		}	
	}
}
?>