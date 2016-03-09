<?php
/**
 * 正在安装
 */
if(!defined('IN_INSTALL')){exit ('Access Denied');}

if('do'!=$_GET['ac']){
	app::display('installing');
	exit();
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo url_stylesheet();?>" />
<ul class='installing_line'>
<?php
//保存合法信息
$POST=$_SESSION['TMP_POST'];
if(empty($POST)){
	exit("<script>alert('数据信息缺失，请重试');</script>");
}
$db_host  =$POST['db_host'];
$db_user  =$POST['db_user'];
$db_pwd   =$POST['db_pwd'];
$db_name  =$POST['db_name'];
$db_prefix=$POST['db_prefix'];

//创建数据库
$conn = mysql_connect($db_host,$db_user,$db_pwd);
if(!$conn){
	exit("<script>alert('数据库服务器或登录密码无效，\\n\\n无法连接数据库，请重新设定！');</script>");
}
mysql_query("CREATE DATABASE IF NOT EXISTS `".$db_name."` DEFAULT CHARACTER SET utf8 ");
mysql_query("SET character_set_connection=utf8, character_set_results=utf8, character_set_client=binary");

$res=mysql_select_db($db_name);
if(!$res){
	exit("<script>alert('选择数据库失败，可能是你没权限，请预先创建一个数据库！');</script>");
}
execute_line("开始安装...","blue");

//读入表文件
$sql_table=file_get_contents($_CONFIG['file_table'],'r');
$sql_table=str_replace("\r\n", "\n", $sql_table);
//读入数据文件
$sql_data=file_get_contents($_CONFIG['file_data'],'r');
$sql_data=str_replace("\r\n", "\n", $sql_data);
//默认的表前缀
$table_prefix=$_CONFIG['db_default']['db_prefix'];
//循环插入表
$list_table=explode(";\n", trim($sql_table));
foreach($list_table as $k=>$query){
	//usleep(0.1*1000000);
	//替换表前缀
	if($db_prefix!=$table_prefix){
		$query=str_replace('`'.$table_prefix,'`'.$db_prefix,$query);
	}
	$match=preg_match("/CREATE TABLE `([a-z0-9_]+)` .*/is",$query,$matches);
	if($match>0){
		$table=$matches[1];
		$res=mysql_query($query,$conn);
		if($res){
			execute_line("创建表成功:`{$table}`...","green");
		}else{
			execute_line("创建表失败:`{$table}`...","red");
			execute_line("ERROR: ".mysql_error(),'red');
			execute_line("SQL: {$query}",'red');
			exit();
		}
	}else{
		$res=mysql_query($query,$conn);
		if($res){
			//echo "执行成功：{$query}";
		}else{
			execute_line("执行失败：{$query}","red");
			execute_line("ERROR: ".mysql_error(),'red');
			execute_line("SQL: {$query}",'red');			
			exit();
		}
	}
}

//循环插入数据
$list_data=explode(";\n", trim($sql_data));
foreach($list_data as $query){
	//usleep(0.1*1000000);
	if($db_prefix!=$table_prefix){
		$query=str_replace('`'.$table_prefix,'`'.$db_prefix,$query);//替换表前缀
	}
	$match=preg_match("/INSERT INTO `([a-z0-9_]+)` .*/is",$query,$matches);
	if($match>0){
		$table=$matches[1];
		$res=mysql_query($query,$conn);
		if($res){
			execute_line("插入表数据成功:`{$table}`...",'green');
		}else{
			execute_line("插入表数据失败:`{$table}`...",'red');
			execute_line("ERROR: ".mysql_error(),'red');
			execute_line("SQL: {$query}",'red');
			exit();
		}
	}else{
		$res=mysql_query($query,$conn);
		if($res){
			// echo "执行成功：{$query}";
		}else{
			execute_line("执行失败：{$query}",'red');
			execute_line("ERROR: ".mysql_error(),'red');
			execute_line("SQL: {$query}",'red');
			exit();
		}
	}	
}
//增加管理员帐号
$email   =$POST['admin_email'];
$password=$POST['admin_password'];
$nickname=$POST['admin_nickname'];
$adminsql="  REPLACE INTO {$db_prefix}user (uid,email,password,name,is_admin,is_active,ctime)  VALUES (1,'{$email}','".md5($password)."','{$nickname}',1,1,'".time()."'); ";
$res=mysql_query($adminsql,$conn);
if($res){
	execute_line("增加管理员帐号成功...","blue");
}else{
	execute_line("增加管理员帐号失败...","red");
	execute_line("ERROR: ".mysql_error(),'red');
	execute_line("SQL: {$query}",'red');
	exit();
}
//给管理员积分
$adminsql="  REPLACE INTO {$db_prefix}credit_user (uid,gold)  VALUES (1,100); ";
$res=mysql_query($adminsql,$conn);
if($res){
	execute_line("增加管理员积分成功...","blue");
}else{
	execute_line("增加管理员积分失败...","red");
	execute_line("ERROR: ".mysql_error(),'red');
	execute_line("SQL: {$query}",'red');	
	exit();
}

@mysql_close($conn);
execute_line("安装完成...","blue");

//--------------------------------------
//删除缓存数据
$_SESSION['TMP_POST']=array();

?>
</ul>
<script type="text/javascript">
//调用顶部窗口的函数
setTimeout('top.window.stopEvent();',500);//500毫秒=0.5s
</script>


