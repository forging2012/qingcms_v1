<?php
/**
 *
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class CommentWidget extends Widget{
	/**
	 * 说明：传入的comment数组必须包含项
	 *  comment['tid']
	 *  comment['uid']
	 *  comment['list']
	 *  comment['page']
	 *  comment['num']
	 *  $mid
	 */
      public function  render($data){
      $content=$this->renderFile('',$data);
      return $content;
      }	
}
?>