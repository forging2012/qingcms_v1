<?php 
function category_format($cate){
	//提取顶级栏目对内容模型分类
	foreach ($cate as $k=>$v){
		if($v['parentid']==0){
			//$cate1
			$cate1[$v['type_id']][]=$v;
		}
	}
	$cate_type=$cate1;	
	//把二级栏目插入 顶级栏目
	foreach ($cate1 as $kt=>$vt){
	 foreach ($vt as $k1=>$v1)
		foreach ($cate as $k=>$v){
			if($v['parentid']==$v1['id']){
				$cate_type[$kt][$k1]['cate2'][]=$v;
			}
		}
	
	}
	return $cate_type;
}
?>