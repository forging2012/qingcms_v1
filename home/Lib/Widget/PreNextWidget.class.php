<?php 
/**
 *
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class PreNextWidget extends Widget{
      public function  render($data){
      	    $preId=D('Content')->PreNext($data['id'],$data['cateid'],'Pre');
      	    $nextId=D('Content')->PreNext($data['id'],$data['cateid'],'Next');
      	    $data['pre']=$preId;
      	    $data['next']=$nextId;  
	        $content=$this->renderFile('',$data);
            return $content;
      }	
}
?>