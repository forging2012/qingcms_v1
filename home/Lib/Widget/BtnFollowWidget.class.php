<?php 
/**
 *
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class BtnFollowWidget extends Widget{
      public function  render($data){
      	/**
      	 * 两者的关系检测
	 * 0:未关注
	 * 1：已关注
	 * 2：互相关注
      	 * @var  
      	 */
      	$data['relation']=D('Follow')->relation($data['mid'],$data['uid']);
      	
      	$content=$this->renderFile('',$data);
      	return $content;
 
      }	
}
?>