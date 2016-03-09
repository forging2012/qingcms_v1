<?php

function getnavtype($type) {
	if ($type == 1) {
		return '内置';
	} else {
		return '自定义';
	}
}
/**
 * id 获取栏目名称
 */
function getCateName($cateid) {
	return D ( "home://ContentCate" )->getCateName ( $cateid );
}
/**
 * 统计栏目的文档总数
 */
function countContent($cateid) {
	return M ( "Content" )->where ( "cateid=" . $cateid )->count ();
}

/**
 * +----------------------------------------------------------
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
 * +----------------------------------------------------------
 * 
 * @return string +----------------------------------------------------------
 */
function byte_format($size, $dec = 2) {
	$a = array (
			"B",
			"KB",
			"MB",
			"GB",
			"TB",
			"PB" 
	);
	$pos = 0;
	while ( $size >= 1024 ) {
		$size /= 1024;
		$pos ++;
	}
	return round ( $size, $dec ) . " " . $a [$pos];
}
/**
 * 初始化网站信息
 */
function initSite(){
	//获取站点信息
	$siteinfo=D('System')->lget('siteinfo');
	//$siteinfo=sysInfo('siteinfo');
	$qc['site_name']=$siteinfo['site_name']; //网站名称
	return $qc;
}
function initSession(){
	//导入Session管理类
	//require_once("./core/Class/iSession.class.php");
	//Session::start();//开启Session
	//session_regenerate_id();
	ini_set('session.auto_start', 0);//关闭自动启动
	session_name('QINGCMS');
	//需要设置完成后才能启动
	session_start();
}
/**
 * URL生成
 */
function U($url, $vars = '') {
	$suffix = false;
	$redirect = false;
	$domain = false;
	$URL_MODEL =0;
	return Url ( $url, $vars, $suffix, $redirect, $domain, $URL_MODEL );
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
 * 更新css/jss缓存
 */

/**
 * 插件里的url处理器
 * $string='plugin/action/method';
 */
function U_P_A($str){
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
		return $url;
	}else{
		return false;
	}
}
/**
 * 将数据存入缓存文件
 * $array 传入一个数组
 */
function SaveTemp($data,$filename){
	Temp::saveTemp($data,$filename);
}
/**
 * 将数据存入缓存文件
 * 
 * @param mixed   $data
 * @param string $filename
 */
function SaveCache($content,$filename){
	//w 只写。打开并清空文件的内容；如果文件不存在，则创建新文件。
	$fp=fopen(PATH_CACHE."/".$filename,"w") or die("<script>alert('写入缓存失败！');history.go(-1);</script>");
	fwrite($fp,$content);
	fclose($fp);
}
/**
 * 删除缓存
 */
function delTemp($filename){
	if(file_exists(PATH_TEMP."/".$filename))
		unlink(PATH_TEMP."/".$filename);
}



?>