<?php
/********************************* 
 *   系统函数                                            
 *   QingCms.com  logo234.com
 *            
 *********************************/
/**
 * 已经安装的插件
 * $on 返回开启的插件，不只是安装
 */
function pluginIn($onStatus=false){
	if($onStatus){
	//已安装，限定开启的	
		return D('Plugin')->where('status=1')->order('status desc')->select();
	}else{
	//已安装，开启或停止的
		return D('Plugin')->order('status desc')->select();
	}	
}
/**
 * 取得栏目，并且格式化
 */
function getCatelist(){
	$cate=D("content_cate")->order('displayorder')->select();
	foreach ($cate as $k=>$v){
		if($v['parentid']==0){
			$cate1[$k]=$v;
		}
	}
	foreach ($cate1 as $key=>$value){
		foreach ($cate as $k=>$v){
			if($v['parentid']==$value['id']){
				$cate1[$key]['cate2'][$k]=$v;
			}
		}
	}
	return $cate1;
}

function getPageNum(){
	global $globalInfo;
	return $globalInfo['textPage'];
}
//隐藏用户邮箱的部分
function hiddenEmail($email){   
	$eArr=explode('@',$email);
	$e1=$eArr[0];
	$e2=$eArr[1];
	$e1_len=strlen($e1);
	if($e1_len>=3){
		if($e1_len>=5){
			for($i=0;$i<$e1_len-4;$i++)
			   $star.='*';
	       $e1=substr($e1,0,2).$star.substr($e1,-2);
		}else{
			for($i=0;$i<$e1_len-2;$i++)
				$star.='*';
			$e1=substr($e1,0,2).$star;
		}
	}
	$email=$e1.'@'.$e2;
	return $email;
}

?>