<?php
/**
 * 后台首页
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class IndexAction extends InitAction{
	/**
	 * 首页
	 */
	public function index(){
		$this->display();
	}
	/**
	 * 主页
	 */
	public function main(){
		// mySql的版本
		$mysqlinfo=M('')->query("SELECT VERSION() as version");
		// 数据库的大小 查询每个表的大小相加 Data_length(数据大小) Index_length(索引大小)
		$t = M ( '' )->query ( "SHOW TABLE STATUS LIKE '" . C ( 'DB_PREFIX' ) . "%'" );
		foreach ( $t as $k ) {
			$dbsize += $k ['Data_length'] + $k ['Index_length'];
		}
		
		$serverInfo ['QingCms程序版本：'] = 'QingCms v1.0';
		$serverInfo ['服务器软件：'] = $_SERVER ['SERVER_SOFTWARE'];
		$serverInfo ['服务器系统及 PHP：'] = PHP_OS . ' / PHP v' . PHP_VERSION;
		$serverInfo ['MySQL版本：'] = $mysqlinfo [0] ['version'];
		$serverInfo ['最大上传许可：'] = (@ini_get ( 'file_uploads' )) ? ini_get ( 'upload_max_filesize' ) : '<font color="red">no</font>';
		$serverInfo ['当前数据库尺寸：'] = byte_format ( $dbsize );
		
		$link ['QingCms'] = 'http://www.qingcms.com/';
		$link ['Logo234'] = 'http://www.logo234.com/';
		
		$this->assign ( 'link', $link );
		$this->assign ( 'serverInfo', $serverInfo );
		$this->display ();
	}

}