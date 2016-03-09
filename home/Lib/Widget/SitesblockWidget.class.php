<?php 
class SitesblockWidget extends Widget{
      public function  render($data){
      	$data='<div class="sites_class left">
      	    <h1><a target="_blank" href="__URL__/view/class/'.$data['classid'].'">
      		'.$data['classname'].'</a></h1>
      		<div class=\'sites_s\'>
      		'.$data['insite'].'
      		</div></div>';
   			return $data;
      }
	
	
	
	
}
?>