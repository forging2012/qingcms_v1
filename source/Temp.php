<?php
/**
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class Temp{
	/**
	 * @return string
	 */
	public static function initTemp(){
		global $_G;
		$_G			=self::getSystem();
		$_G['ad']	=self::getAd();
		//define('PATH_DEFAULT_THEMES',$system_data['siteinfo']['themes']);
	}
	/**
	 * @return string
	 */
	public static function getPath(){
		return PATH_ROOT.DS.'~temp';
	}
	/**
	 * 将数据存入缓存文件
	 * $array 传入一个数组
	 * 
	 * @param mixed   $data
	 * @param string $filename
	 */
	public static function saveTemp($data,$filename){
		if(is_array($data)){
			$content=var_export($data,true);
		}else{
			$content=(string)$data;
		}
		$content="<?php\n return ".$content.";\n?>";
		//w 只写。打开并清空文件的内容；如果文件不存在，则创建新文件。
		$filename=self::getPath()."/".$filename;
		$fp=fopen($filename,"w") or die("<script>alert('写入缓存失败！');history.go(-1);</script>");
		fwrite($fp,$content);
		fclose($fp);
	}
	/**
	 * @return string
	 */
	public static function getCategory(){
		// $cate=D('ContentCate')->select();
		return self::getTemp('category');
	}
	/**
	 * @return string
	 */
	public static function getAd(){
		return self::getTemp('Ad');
	}
	/**
	 * @return string
	 */
	public static function getSystem(){
		return self::getTemp('System');
	}
	/**
	 * @return string
	 */
	public static function getTemp($filename){
		$filename=self::getPath().'/~'.$filename.'.php';
		if(is_file($filename)){
			return require $filename;
		}else{
			return array();
		}
	}
}
?>