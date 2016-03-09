<?php 
include dirname(__FILE__)."/load-class.php";
error_reporting(0);
header('Content-Type: text/css; charset=UTF-8;');
header("Cache-Control: public");

$cssDir=dirname(__FILE__)."/../css";
$fileList=array(
		$cssDir."/style.css",
		$cssDir."/btn.css",
		$cssDir."/input.css",
		$cssDir."/table.css",
		$cssDir."/top.css",
		$cssDir."/foot.css",
		$cssDir."/agreement.css",
		$cssDir."/installing.css",
		$cssDir."/done.css",
);

//清除注释
$clearComment=(bool)@$_GET['clear'];
new LoadStyles($fileList,"css",$clearComment,dirname(__FILE__)."/../_cache/main.css");
//new LoadStyles($fileList,"css",true,dirname(__FILE__)."/../css/_cache.css");
