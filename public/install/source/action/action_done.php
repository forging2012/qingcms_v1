<?php
/**
 * 安装完成
 */
if(!defined('IN_INSTALL')){exit ('Access Denied');}

//锁定安装
$fp = fopen(PATH_INSTALL.'/'.$_CONFIG['lockFile'],'w');//"w" 写入方式打开，将文件指针指向文件头并将文件大小截为零。如果文件不存在则尝试创建之。
fwrite($fp,'lock');
fclose($fp);

//重命名
// if(is_file(PATH_APP."/index.sample.php")){
// 	if(!rename(PATH_APP."/index.sample.php", PATH_APP."/index.php")){
// 		echo "<script>alert('请手动将主目录下的index.sample.php重命名为index.php...');</script>";
// 	}
// }

//去掉安装验证
$file_index=realpath(PATH_INSTALL.'/../index.php');
$content=file_get_contents($file_index);
$search ="if(!file_exists('./install/install_lock.txt')){header('Location:./install');exit();}";
$replace="//if(!file_exists('./install/install_lock.txt')){header('Location:./install');exit();}";
$content=str_replace($search,$replace, $content);
//保存内容
file_put_contents($file_index, $content);

app::display('done');
