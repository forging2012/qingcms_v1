<?php 
class WeiboWidget extends Widget{
      public function  render($data){
            /**
             * 反序列号type_data
             */
      	        
      	    $data['weibo']['type_data']=($data['weibo']['type_data'])?unserialize($data['weibo']['type_data']):null;  
	        $content=$this->renderFile('',$data);
   			return $content;
      }	
}
?>