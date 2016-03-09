<?php 
// /**
//  * 获取系统信息
//  */
// function sysInfo($list){
// 	return D('System')->lget($list);
// }
/**
 * 由文章id获取文章内容
 * @param int $tid
 * @return char
 */
function getcontent($tid){
	$cont=D('Content')->where('id='.$tid)->find();
	if($cont>0)
	  return $cont['content'];
	return '<span class="haddel">囧，文章被作者删除了...</span>';
}
/**
 * 由commentid获取内容
 */
function getComment($id){
	$c=D('Comment')->where('id='.$id)->find();
	if($c){
		return $c['content'];
	}else{
		return '<b>评论不存在或已被删除</b>';
	}
}
/**
 * 获取回复的评论内容和back按钮
 * $id 评论的id
 */
function replyAndUrl($id,$tid,$uid){
	$c=D('Comment')->where('id='.$id)->find();
	if($c){
		 $content='回复'.$c['position'].'楼 ('.getname($uid).'):';
		 $content.=getShort($c['content'],32);
		 $u=$tid;
		 $u.="?p=".ceil($c['position']/10);
		 $u.="#lou".$c['position'];
		// $content.="<a href='__APP__/Index/detail/id/".$u."' target='_blank'><img  src='__STATIC__/image/back.gif' width='13' height='13'></a>";
	}else{
		 $content='<b>评论不存在或已被删除</b>';
	}
	return $content;
}
/**
 * 获取跳转url
 */
function backUrl($position,$page=10){
	$p=$position/$page;
	
}
/**
 * 统计数据
 */
function getCount($uid,$type){
	if($type=='pub'){
		return D('Content')->countNum($uid);
	} 
	if($type=='digg'){
		return D('Digg')->countNum($uid);
	}
	if($type=='follower'){
		return D('Follow')->countNum($uid,$type);
	}	
	if($type=='following'){
		return D('Follow')->countNum($uid,$type);
	}	
}
/**
 * 统计评论
 */
function countComment($tid){
	//return $tid;
	return D('Comment')->countNum($tid);
}
/**
 * 获取标签
 */
function getTag($tid){
	return $tag=D('Tag')->tagList($tid);	
}
/**
 * 判断是否已经投票
 * @param  $tid
 * @param  $uid
 */
function isvoted($tid,$uid){
	//find 返回一维数组 select返回二维数组
	$res=D('digg')->where("tid={$tid} AND uid={$uid}")->find();
	return $res;
}
/**
 * 是否已经登录
 */
function isLogged(){
	// 验证本地系统登录
	if (intval($_SESSION['mid']) > 0)
		return true;
	else
		return false;
}
/**
 * mid:登录用户id 
 * mid();
 * mid('name');
 */
function mid($def=''){
	if(isLogged()){
		$mid = intval($_SESSION['mid']);
		$mname=$_SESSION['mname'];
	}else{
		$mid =0;
	}
	if($def=='name'){
		return $mname;//返回用户名
	}
	return $mid;	
}
/**
 * uid,被访问的用户
 */
function uid(){
	$uid= intval($_REQUEST['uid']);
	if ($uid ==''){
		if($_GET['_URL_'][3]=='uid'){
			$uid=$_GET['_URL_'][4];
		}else{
			$uid =mid();
		}
	}
	return $uid;
}


/**
 * 友好的时间显示
 *
 * @param int    $sTime 待显示的时间
 * @param string $type  类型. normal | mohu | full | ymd | other
 */
function friendlyDate($sTime,$type = 'normal') {
	//Ymd 年月日
	//单位为秒
	$cTime		=	time(); //现在的时间
	$dTime		=	$cTime - $sTime;//时间差
	$dDay		=	intval(date("d",$cTime)) - intval(date("d",$sTime));//如果等于0则同一天le
	$dYear		=	intval(date("Y",$cTime)) - intval(date("Y",$sTime));//如果等于0则不显示年份
	//normal：n秒前，n分钟前，n小时前，今天，日期
	if($type=='normal'){
		if( $dTime < 60 ){  //1分钟内
			return $dTime."秒前";
		}elseif( $dTime < 3600 ){  //一个小时内
			return intval($dTime/60)."分钟前";
		}elseif($dDay==0){   //一天内
			return '今天'.date('H:i',$sTime);
		}elseif($dYear==0){
			return date("m月d日 H:i",$sTime);
		}else{
			return date("Y-m-d H:i",$sTime);
		}
	}elseif($type=='mohu'){
		if( $dTime < 60 ){
			return $dTime."秒前";
		}elseif( $dTime < 3600 ){
			return intval($dTime/60)."分钟前";
		}elseif( $dTime >= 3600 && $dDay == 0  ){
			return intval($dTime/3600)."小时前";
		}elseif( $dDay > 0 && $dDay<=7 ){
			return intval($dDay)."天前";
		}elseif( $dDay > 7 &&  $dDay <= 30 ){
			return intval($dDay/7) . '周前';
		}elseif( $dDay > 30 ){
			return intval($dDay/30) . '个月前';
		}
		//full: Y-m-d , H:i:s
	}elseif($type=='full'){
		return date("Y-m-d , H:i:s",$sTime);
	}elseif($type=='ymd'){
		return date("Y-m-d",$sTime);
	}else{
		if( $dTime < 60 ){
			return $dTime."秒前";
		}elseif( $dTime < 3600 ){
			return intval($dTime/60)."分钟前";
		}elseif( $dTime >= 3600 && $dDay == 0  ){
			return intval($dTime/3600)."小时前";
		}elseif($dYear==0){
			return date("Y-m-d H:i:s",$sTime);
		}else{
			return date("Y-m-d H:i:s",$sTime);
		}
	}
}


/**
 * 获取文章分类导航
 */
function getCateNav($cid){
	$cate=D('ContentCate')->getCateInfo($cid);
	$html=null;
	if($cate['parentid']!=0){
	$cate1=D('ContentCate')->parentCate($cate['parentid']);
	$html="<a href='".U('Index/'.$cate1[nid])."'>{$cate1['name']}</a> >>";
	}
	$html.="<a href='".U('Index/'.$cate[nid])."'>{$cate['name']}</a>";
	return $html;
}

/**
 * 
 * 
 */
// /**
//  * 添加用户消息
//  */
// function addMsg($uid,$type){
// 	return	$ret=D('Message')->addMsg($uid,$type);
// }
// /**
//  * 用户消息清零
//  */
// function setZero($type){
// 	return	$ret=D('Message')->setZero($this->mid,$type);
// }


/**
/////////////////////////////////////////////////////////////////////////////////////////
/////////////       功能函数                                    ///////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////
*/


/**
 * 转换为安全的纯文本
 *
 * @param string  $text
 * @param boolean $parse_br    是否转换换行符
 * @param int     $quote_style ENT_NOQUOTES:(默认)不过滤单引号和双引号 ENT_QUOTES:过滤单引号和双引号 ENT_COMPAT:过滤双引号,而不过滤单引号
 * @return string|null string:被转换的字符串 null:参数错误
 */
function t($text, $parse_br = false, $quote_style = ENT_NOQUOTES)
{
	if (is_numeric($text))
		$text = (string)$text;

	if (!is_string($text))
		return null;

	if (!$parse_br) {
		$text = str_replace(array("\r","\n","\t"), '', $text);
	} else {
		$text = nl2br($text);
	}

	$text = stripslashes($text);//删除由 addslashes() 函数添加的反斜杠。
	$text = htmlspecialchars($text, $quote_style, 'UTF-8');

	return $text;
}
/**
 * 获取字符串的长度
 *
 * 计算时, 汉字或全角字符占1个长度, 英文字符占0.5个长度
 * 需要和dos.getLength的处理结果对应
 * 
 * @param string  $str
 * @param boolean $filter 是否过滤html标签
 * @return int 字符串的长度
 */
function get_str_length($str, $filter = false)
{
	if ($filter) {
		$str = html_entity_decode($str, ENT_QUOTES);
		$str = strip_tags($str);
	}
	//echo ceil(4.3);    // 5
    //echo ceil(9.999);  // 10
    // ceil返回的是浮点数  float
    // intval 返回 int
	return intval( ceil( (strlen($str) + mb_strlen($str, 'UTF8')) / 4 ) );
}
/**
 +----------------------------------------------------------
 * 删除html标签，得到纯文本。可以处理嵌套的标签
 +----------------------------------------------------------
 * @access public
 +----------------------------------------------------------
 * @param string $string 要处理的html
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function deleteHtmlTags($string) {
	while(strstr($string, '>')) {
		$currentBeg = strpos($string, '<');
		$currentEnd = strpos($string, '>');
		$tmpStringBeg = @substr($string, 0, $currentBeg);
		$tmpStringEnd = @substr($string, $currentEnd + 1, strlen($string));
		$string = $tmpStringBeg.$tmpStringEnd;
	}
	return $string;
}
/**
 * 获取栏目列表
 */
function cateList(){
	/*
	 * 获取所有栏目列表
	*/
	$cate=D('ContentCate')->select();
	//提取顶级栏目
	foreach ($cate as $k=>$v){
		if($v['parentid']==0){
			$cate1[$k]=$v;
		}
	}
	//把二级栏目插入顶级栏目
	foreach ($cate1 as $k1=>$v1){
		foreach ($cate as $k=>$v){
			if($v['parentid']==$v1['id']){
				$cate1[$k1]['cate2'][$k]=$v;
			}
		}
	
	}
	return $cate1;
}
/**
 * 获取表前缀
 */
function tablePre(){
	return C('DB_PREFIX');//表前缀
}
/**
 * 载入Block方法
 */
function iB($tpl,$data){
	return false;//废弃
	//只能传入数组
	if(is_array($data)){
		foreach ($data as $k=>$v){
			$$k=$v;
		}
	}
	//传入的数据要解析才能使用，不能再使用thinkphp模板解析，还是使用w方法吧
	include(THEME_PATH."Block/".$tpl.'.php');
}

/**
 * 更新系统设置缓存
 */
function updateSysCache(){
	echo "3131";
}
/**
 * 前台页面的url处理
 */
/**
 * URL生成
 */
function U($url, $vars = '') {
	global $system_data;
	if($system_data['view']['isRewrite'])
	   return url_rewrite($url, $vars);
	else 
	   return url_normal($url, $vars);
	
// 	$suffix = false;
// 	$redirect = false;
// 	$domain = false;
// 	$URL_MODEL = 0;
// 	return Url ( $url, $vars, $suffix, $redirect, $domain, $URL_MODEL );
}

// ThinkPHP common/function.php
// URL组装 支持不同模式
// 格式：U('[分组/模块/操作]?参数','参数','伪静态后缀','是否跳转','显示域名')
function Url($url, $vars = '', $suffix = true, $redirect = false, $domain = false, $URL_MODEL = 0) {
	// 解析URL
	$info = parse_url ( $url );
	$url = ! empty ( $info ['path'] ) ? $info ['path'] : ACTION_NAME;
	// 解析子域名
	if ($domain === true) {
		$domain = $_SERVER ['HTTP_HOST'];
		if (C ( 'APP_SUB_DOMAIN_DEPLOY' )) { // 开启子域名部署
			$domain = $domain == 'localhost' ? 'localhost' : 'www' . strstr ( $_SERVER ['HTTP_HOST'], '.' );
			// '子域名'=>array('项目[/分组]');
			foreach ( C ( 'APP_SUB_DOMAIN_RULES' ) as $key => $rule ) {
				if (false === strpos ( $key, '*' ) && 0 === strpos ( $url, $rule [0] )) {
					$domain = $key . strstr ( $domain, '.' ); // 生成对应子域名
					$url = substr_replace ( $url, '', 0, strlen ( $rule [0] ) );
					break;
				}
			}
		}
	}

	// 解析参数
	if (is_string ( $vars )) { // aaa=1&bbb=2 转换成数组
		parse_str ( $vars, $vars );
	} elseif (! is_array ( $vars )) {
		$vars = array ();
	}
	if (isset ( $info ['query'] )) { // 解析地址里面参数 合并到vars
		parse_str ( $info ['query'], $params );
		$vars = array_merge ( $params, $vars );
	}

	// URL组装
	$depr = C ( 'URL_PATHINFO_DEPR' );
	if ($url) {
		if (0 === strpos ( $url, '/' )) { // 定义路由
			$route = true;
			$url = substr ( $url, 1 );
			if ('/' != $depr) {
				$url = str_replace ( '/', $depr, $url );
			}
		} else {
			if ('/' != $depr) { // 安全替换
				$url = str_replace ( '/', $depr, $url );
			}
			// 解析分组、模块和操作
			$url = trim ( $url, $depr );
			$path = explode ( $depr, $url );
			$var = array ();
			$var [C ( 'VAR_ACTION' )] = ! empty ( $path ) ? array_pop ( $path ) : ACTION_NAME;
			$var [C ( 'VAR_MODULE' )] = ! empty ( $path ) ? array_pop ( $path ) : MODULE_NAME;
			if (C ( 'URL_CASE_INSENSITIVE' )) {
				$var [C ( 'VAR_MODULE' )] = parse_name ( $var [C ( 'VAR_MODULE' )] );
			}
			if (C ( 'APP_GROUP_LIST' )) {
				if (! empty ( $path )) {
					$group = array_pop ( $path );
					$var [C ( 'VAR_GROUP' )] = $group;
				} else {
					if (GROUP_NAME != C ( 'DEFAULT_GROUP' )) {
						$var [C ( 'VAR_GROUP' )] = GROUP_NAME;
					}
				}
			}
		}
	}

	if (C ( 'URL_MODEL' ) == 0 && $URL_MODEL == 0) { // 普通模式URL转换
		$url = __APP__ . '?' . http_build_query ( $var );
		if (! empty ( $vars )) {
			$vars = http_build_query ( $vars );
			$url .= '&' . $vars;
		}
	} else { // PATHINFO模式或者兼容URL模式
		if (isset ( $route )) {
			$url = __APP__ . '/' . $url;
		} else {
			$url = __APP__ . '/' . implode ( $depr, array_reverse ( $var ) );
		}
		if (! empty ( $vars )) { // 添加参数
			$vars = http_build_query ( $vars );
			$url .= $depr . str_replace ( array (
					'=',
					'&'
			), $depr, $vars );
		}
		if ($suffix) {
			$suffix = $suffix === true ? C ( 'URL_HTML_SUFFIX' ) : $suffix;
			if ($suffix) {
				$url .= '.' . ltrim ( $suffix, '.' );
			}
		}
	}
	if ($domain) {
		$url = 'http://' . $domain . $url;
	}
	if ($redirect) // 直接跳转URL
		redirect ( $url );
	else
		return $url;
}
/**
 * 普通url
 * @param string $url
 * @param  $vars
 * @return string
 */
function url_normal($url, $vars=null){
	$url_format=url_format($url, $vars);
	$m=$url_format['m'];
	$a=$url_format['a'];
	$vars=$url_format['vars'];
	
	$url=__APP__.'?m='.$m.'&a='.$a;
	//带上Url上的变量
	if (!empty($vars)) {
		$vars = http_build_query($vars);
		$url.='&'.$vars;
	}
	return $url;
}
/**
 * url重写
 */
function url_rewrite($url, $vars){
	$html_suffix='.html';
	$url_format=url_format($url, $vars);
	$m=$url_format['m'];
	$a=$url_format['a'];
	$vars=$url_format['vars'];
	$url2=PATH_PATH.'/';
	//return $url2;
	switch($m){
		//内容列表
		case 'Index':
			//内容详细页面
			if($a=='detail')
			$url2.=$a.'-'.$vars['id'].$html_suffix;
			else if(!$vars)
			$url2.=$a.$html_suffix;
			else
			$url2=url_normal($url,$vars);
			break;
		//用户空间
		case 'Space':
			if($a=='index')
			$url2.='space-uid-'.$vars['uid'].$html_suffix;
			else 
			$url2=url_normal($url,$vars);
			break;
		default:
			$url2=url_normal($url,$vars);
			break;
	}
	return $url2;
}
/**
 * 格式化url，返回信息
 */
function url_format($url, $vars=null){
	// 解析参数
	if(is_string($vars) && $vars!=null){ // aaa=1&bbb=2 转换成数组
		parse_str($vars,$vars);
	}
	$info=parse_url($url);// path=Index/detail query=a=34343&b=3232(问号做间隔)
	// 解析地址里面参数 合并到vars
	if(!empty($info ['query'])) {
		parse_str($info ['query'],$params );
		$vars =$vars?array_merge($params, $vars):$params;
	}
	$path=$info ['path'];
	if($path){
		if(0===strpos($path,'/')){ // 定义路由
			$m=$path;
			$a='index';
		}else {
			// 解析模块和操作 m和a
			$depr='/';
			$path = trim($path,$depr);
			$path = explode($depr,$path);
			$m=$path[0];
			$a=$path[1];
		}
	}
    $url_format['m']=$m;
    $url_format['a']=$a;
    $url_format['vars']=$vars;
	return $url_format;
}

/**
 * 插件里的url处理器
 * $string='plugin/action/method';
 */
function PU($url,$vars=''){
	// 解析参数
	if(is_string($vars) && $vars!=null){ // aaa=1&bbb=2 转换成数组
		parse_str($vars,$vars);
	}
	$info=parse_url($url);// path=Index/detail query=a=34343&b=3232(问号做间隔)
	// 解析地址里面参数 合并到vars
	if(!empty($info ['query'])) {
		parse_str($info['query'],$params);
		$vars =$vars?array_merge($params,$vars):$params;
	}
	$str=$info ['path'];
	if(strpos($str,'/')>''){ // 定义路由
		// 解析模块和操作 m和a
		$depr='/';
		$str = trim($str,$depr); //删除尾部的/
		$strArr = explode($depr,$str);
		if(count($strArr)!=3) return false;
		$p=$strArr[0];
		$a=$strArr[1];
		$m=$strArr[2];
		$url=__APP__.'?m=Public&a=toPlugin&plugin='.$p.'&p_action='.$a.'&p_method='.$m;
		//带上Url上的变量
		if (!empty($vars)) {
			$vars = http_build_query($vars);
			$url.='&'.$vars;
		}
		return $url;
	}else{
		return false;
	}
}

/**
 * 钩子存在且安装了相应的插件时载入相关的类库
 * @param unknown_type $name 钩子名称
 * @param unknown_type $vars 传入数据
 */
function hook($name,$vars=array()){
	global $_Hooks; //插件数据，可全局调用
	//还没有使用过$_P
	if(!isset($_Hooks)){
		$file_hooks=PATH_TEMP.'/~hooks.php';
		if(file_exists($file_hooks)){
			$_Hooks=include_once($file_hooks);
		}else{
			return false;
		}
	}
	if($_Hooks[$name]=='') return false;//当不存在此插件接口
	Plugins::hook($_Hooks[$name],$name,$vars);
}

/**
 * 载入文件
 */
function loadTemp(){
	
}



?>