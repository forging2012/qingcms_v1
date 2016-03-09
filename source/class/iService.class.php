<?php
/**
 * 服务静态类
 */
class iService{
	/**
	 * 
     * 文件上传
     * $name='pic'  input字段
     * $_FILES['pic']
	 * @param unknown_type $name
	 * @param unknown_type $thumb
	 * @param unknown_type $water
	 * @param unknown_type $thumbRemoveOrigin 	 //生成缩略图后删除原图
	 */
	static function Fileupload($name,$thumb=true){
		global $globalInfo;
		$attachmentConfig=$globalInfo['attachment'];
		if(!$attachmentConfig) exit('0');
				
		$maxSize=$attachmentConfig['maxSize']; //附件最大
		$thumbRemoveOrigin=$attachmentConfig['removeOrigin']; //是否移除原图
		
		$water         =$attachmentConfig['water'];  //是否开启水印
		$waterFile     =$attachmentConfig['waterFile']; //水印图片
		$waterPosition =$attachmentConfig['waterPosition']; //水印位置
		$padding['x']  =$attachmentConfig['padding_x']; //水印边距
		$padding['y']  =$attachmentConfig['padding_y']; //水印边距
		$min['w']=$attachmentConfig['water_minWidth']; //添加水印的条件
		$min['h']=$attachmentConfig['water_minHeight']; //添加水印的条件
		$thumb_x=array($attachmentConfig['thumb_sx'],$attachmentConfig['thumb_mx']);//缩略图宽的限制
		$thumb_y=array($attachmentConfig['thumb_sy'],$attachmentConfig['thumb_my']);//缩略图高的限制
		
		//引入类文件
		require_once(PATH_CLASS."/iUploadFile.class.php");
		// 1.实例化上传对象
		$upload=new iUploadFile();
		// 2.设置对象属性,上传设置
		$upload->saveRule=substr(md5(time()), 0,20); //文件名保存规则 20位
		$upload->savePath=(defined("PATH_UPLOADS_PATH"))?PATH_UPLOADS_PATH:'./data/uploads';//上传保存路径  以主文件入口为
		$upload->savePathRule=date(Ym);//保存目录规则
		$upload->uploadReplace=true;        //同名文件是否覆盖
		$upload->maxSize=$maxSize*1024*1024; //以byte为单位，查看$_FILE
		$upload->allowExts=array('jpg','png','jpeg','gif');//准许上传文件后缀
		// 3.执行上传方法
		if($upload->upload($name)){
			$up['success']=1;
			$up['FileInfo']=$upload->getFileInfo();//文件上传成功后的信息
			$file=$up['FileInfo'];
			$image =$upload->savePath.'/'.$upload->savePathRule.'/'.$file['savename'];//文件路径
			$imagePath=$upload->savePath.'/'.$upload->savePathRule.'/';  // 文件保存目录
			//文件上传后进行缩放
			if($thumb){
				require_once(PATH_CLASS."/iImage.class.php");
				//生成两个大小的图片			
				$thumbPrefix=array('s_','m_');   //缩略图前缀
// 				$thumbMaxWidth=array(120,500);// 缩略图最大宽度
// 				$thumbMaxHeight=array(120,1000);// 缩略图最大高度
				$thumbMaxWidth=$thumb_x;// 缩略图最大宽度
				$thumbMaxHeight=$thumb_y;// 缩略图最大高度
				//Fatal error: Allowed memory size of 8388608 bytes exhausted (tried to allocate 7680 bytes) 
				//都会产生致命错误
				for($i=0;$i<=1;$i++){
				  $thumbname = $imagePath.$thumbPrefix[$i].$file['savename'];
				  $thumb_res=Image::thumb($image, $thumbname,'',$thumbMaxWidth[$i],$thumbMaxHeight[$i]);
                                  //返回小图(s_)缩放后的大小，用于瀑布流的延迟加载
				   if($i==0){
					$up['fileInfo']['width']=$thumb_res['width'];
					$up['fileInfo']['height']=$thumb_res['height'];
				   }
				}           
				//如果删除原图
				if($thumbRemoveOrigin) {
					// 生成缩略图之后删除原图
					unlink($image);
				}	
				//只在大图打上水印
				if($water){
					$waterimage=$imagePath.'m_'.$file['savename'];
					$water=Image::water($waterimage,$waterFile,$waterPosition,$padding,$min);
				}	
			}

		}else{
			$up['success']=0;
			$up['ErrorMsg']=$upload->getErrorMsg();//错误信息
		}
		return $up;
	}	

}

?>