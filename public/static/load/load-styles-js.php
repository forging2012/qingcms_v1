<?php 
include dirname(__FILE__)."/load-class.php";
error_reporting(0);
header('Content-Type: text/css; charset=UTF-8;');
header("Cache-Control: public");

$fileDir=dirname(__FILE__)."/../js";
$fileList=array(
		$fileDir."/common.js",
		$fileDir."/pub_video.js",
		$fileDir."/ui/ui.js",
);

//清除注释
$clearComment=(bool)@$_GET['clear'];
//new LoadStyles($fileList,"js",false,dirname(__FILE__)."/../_cache/_debug.js");
new LoadStyles($fileList,"js",$clearComment,dirname(__FILE__)."/../_cache/_main.js");
