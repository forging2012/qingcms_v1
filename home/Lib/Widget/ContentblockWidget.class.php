<?php
/**
 *
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class ContentblockWidget extends Widget{
      public function  render($data){
      	    /**
      	     * video:
      	     * flashvar:flash id
      	     * host   youku.com
      	     * img    缩略图
      	     * title  标题
      	     */
      	        
      	    $data['t']['video']=unserialize($data['t']['video']);  
	        $content=$this->renderFile('',$data);
   			return $content;
      }	
}
?>