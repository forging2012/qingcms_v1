<?php 
/**
 *
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class RankWidget extends Widget{
      public function  render($data){
      	    /**
      	     * 等级转换
      	     */     	
            import_class('iRank');
      	    $r=new iRank();
            $data=$r->rank($data['score']);
      	    // dump($rank);
      	   // $html='<a class="rankBox" href='.U("User/account?ac=credit").'>';
            $html='<span class="rankBox">';
      	    for ($i=0;$i<$data['star3'];$i++){
      	    	$html.='<img src="__STATIC__/image/star_level3.gif">';
      	    }
      	    for ($i=0;$i<$data['star2'];$i++){
      	    	$html.='<img src="__STATIC__/image/star_level2.gif">';
      	    }
      	    for ($i=0;$i<$data['star1'];$i++){
      	    	$html.='<img src="__STATIC__/image/star_level1.gif">';
      	    }
      	    $html.='</span>';
   			return $html;
      }	
}
?>