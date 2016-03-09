<?php 
/**
 *  热门列表、最新列表、右侧的小文本显示
 */
class MicroTextWidget extends Widget{
      public function  render($data){
	        $content=$this->renderFile('',$data);
   			return $content;
      }	
}
?>