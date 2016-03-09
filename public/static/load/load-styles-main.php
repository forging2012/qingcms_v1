<?php 
include dirname(__FILE__)."/load-class.php";
error_reporting(0);
header('Content-Type: text/css; charset=UTF-8;');
header("Cache-Control: public");

$cssDir=dirname(__FILE__)."/../css";
$fileList=array(
		$cssDir."/layout.css",
		$cssDir."/page.css",
		$cssDir."/pop.css",
		$cssDir."/style.css",
		$cssDir."/tab.css",
		$cssDir."/ui.css",
		$cssDir."/widget.css",
		$cssDir."/input.css",
		
		$cssDir."/common.css",
		$cssDir."/home.css",
		$cssDir."/user.css",
		$cssDir."/login_reg.css",
		$cssDir."/weibo.css",
);
$clearComment=(bool)@$_GET['clear'];
//new LoadStyles($fileList,"css",false,dirname(__FILE__)."/../_cache/_debug.css");
new LoadStyles($fileList,"css",$clearComment,dirname(__FILE__)."/../_cache/_main.css");


