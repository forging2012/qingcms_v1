<?php
/********************************* 
 *   公共函数库                   扩展性函数                                            
 *   QingCms.com  logo234.com
 *   全局函数 所有项目都可以调用
 *            
 *********************************/
/**
 * 格式化时间
 */
function normaltime($time) {
	return date ( "Y-m-d H:i", $time );
}
/**
 * 有用户id获取用户名
 * 
 * @param int $uid        	
 * @return char
 */
function getname($uid) {
	$name = D ( 'home://User' )->getusername ( $uid );
	return $name;
}
/**
 * 截取内容
 */
function getShort($str, $length = 40, $ext = '...') {
	$str = htmlspecialchars ( $str );
	$str = strip_tags ( $str );
	$str = htmlspecialchars_decode ( $str );
	$strlenth = 0;
	$out = '';
	preg_match_all ( "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/", $str, $match );
	foreach ( $match [0] as $v ) {
		preg_match ( "/[\xe0-\xef][\x80-\xbf]{2}/", $v, $matchs );
		if (! empty ( $matchs [0] )) {
			$strlenth += 1;
		} elseif (is_numeric ( $v )) {
			// $strlenth += 0.545; // 字符像素宽度比例 汉字为1
			$strlenth += 0.5; // 字符字节长度比例 汉字为1
		} else {
			// $strlenth += 0.475; // 字符像素宽度比例 汉字为1
			$strlenth += 0.5; // 字符字节长度比例 汉字为1
		}
		
		if ($strlenth > $length) {
			$output .= $ext;
			break;
		}
		
		$output .= $v;
	}
	return $output;
}
/**
 * 格式化微博,替换表情/@用户/话题
 *
 * @param string  $content 待格式化的内容
 * @param boolean $url     是否替换URL
 * @return string
 */
function formatWeibo($content,$url=false){
	if($url){
		$content = preg_replace('/((?:https?|ftp):\/\/(?:www\.)?(?:[a-zA-Z0-9][a-zA-Z0-9\-]*\.)?[a-zA-Z0-9][a-zA-Z0-9\-]*(?:\.[a-zA-Z0-9]+)+(?:\:[0-9]*)?(?:\/[^\x{4e00}-\x{9fa5}\s<\'\"“”‘’]*)?)/u', '<a href="\1" target="_blank">\1</a>\2', $content);
	}
	$content = preg_replace_callback("/(?:#[^#]*[^#^\s][^#]*#|(\[.+?\]))/is",replaceSmiley,$content);
// 	$content = preg_replace_callback("/#([^#]*[^#^\s][^#]*)#/is",themeformat,$content);
// 	$content = preg_replace_callback("/@([\w\x{4e00}-\x{9fa5}\-]+)/u",getUserId,$content);
// 	$content = keyWordFilter($content);
	return $content;
}
/**
 * 表情替换 [格式化微博与格式化评论专用]
 *
 * @param array $data
 * @see format()
 * @see formatComment()
 */
function replaceSmiley($data) {

	if(preg_match("/#.+#/i",$data[0])) {
		return $data[0];
	}

	$smiley=D('Smiley')->where('code="'.$data[1].'"')->find();
	if($smiley){
		return "<img src='".__STATIC__."/image/smiley/".$smiley['filename']." ' />";
		return $smiley['filename'];
	}else{
		return $data[1];
	}
}
/**
 * 格式化评论, 替换表情和@用户
 *
 * @param string  $content 待格式化的内容
 * @param boolean $url     是否替换URL
 * @return string
 */
function formatComment($content){
	$content = preg_replace_callback("/(?:#[^#]*[^#^\s][^#]*#|(\[.+?\]))/is",replaceSmiley,$content);
	return $content;
}
/**
 * 由uid获取用户的空间地址
 */
function getSpaceLink($uid){
	$href=U('Space/index?uid='.$uid);
	$html="<a href=".$href.">".getname($uid).":</a>";
	return $html;
}
/**
 * 取得转发数据
 * 递归函数
 */
function getTranspondData($wid,$html){
	//echo '!';
	$one=D('Weibo')->where('id='.$wid)->find();
	if($one['transpond_id']>0){
		$html.=" || @".getSpaceLink($one['uid']).formatWeibo($one['content']);
		//递归
		return getTranspondData($one['transpond_id'],$html);
	}else{
		$return['wid']=$one['id'];
		$return['html']=$html;
		return $return;
	}
}

/**
 * 删除图片
 */
function delPic($path,$name){
	$s=$path.'s_'.$name;
	$m=$path.'m_'.$name;
	if(is_file($s))  unlink($s);
	if(is_file($m))  unlink($m);	
}

/**
 * 将服务器路径转换为url路径
define ( 'PATH_PLUGIN', './core/plugin' );  ./core/plugin->/core/plugin
define ( 'PATH_LIB', './core/lib' );
define ( 'PATH_CLASS', './core/class' );
   即把前面的点去掉
 */
function toUrl($path){
	return substr($path,1);
}
/**
 * 导入函数库文件
 */
function import_function($name){
	require_once PATH_FUNCTION.'/'.'home-'.$name.'.php';
}
/**
 * 导入类库文件
 */
function import_class($name){
	require_once PATH_CLASS.'/'.$name.'.class.php';
}


?>