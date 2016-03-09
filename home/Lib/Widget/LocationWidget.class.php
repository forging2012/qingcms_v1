<?php 
/**
 *
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class LocationWidget extends Widget{
      public function  render($data){
	        $content=$this->renderFile('',$data);
   			return $content;
      }	
}
?>