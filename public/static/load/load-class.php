<?php 
/**
 * 加载js/css文件处理类
 * 
 * 1.可以取出注释，换行等
 * @author xiaowang <736523132@qq.com>
 * @copyright 2013 http://qingcms.com All rights reserved.
 */
class LoadStyles{

	private $fileExt   ="";			//限制载入的文件后缀  js css
	private $cacheFile =null;		//生成的缓存文件
	private $clearComment=true;		//是否清除注释
	private $_content  ="";			//合并后的内容
	private $_imports  ="";			//导入的文件列表
	private $fileList  =array();	//需要导入的文件列表

	/**
	 * @param  $fileList		需要导入的文件列表
	 * @param  $fileExt			限制载入的文件后缀  js css
	 * @param  $clearComment	是否清除注释
	 * @param  $cacheFile		生成的缓存文件
	 */
	public function __construct($fileList,$fileExt="css",$clearComment=true,$cacheFile=null){
		$this->fileList		=$fileList;
		$this->fileExt		=$fileExt;
		$this->clearComment =$clearComment;
		$this->cacheFile	=$cacheFile;
		
		if(!is_array($fileList) && is_dir($fileList)){
		//1.如果传入的是一个目录，则扫描
			$fileDir=$fileList;$fileList=array();
			foreach (scandir($fileDir) as $k=>$v){
				$fileName=realpath($fileDir.'/'.$v);
				if(!is_file($fileName)){//包括 . ..
					continue;
				}
				$fileList[]=$fileName;
			}
		}
		
		$this->mergeFile($fileList);
		$this->buildCache();
		
		//显示debug信息
		echo "/** \n";
		echo "导入的文件列表:\n\n";
		echo $this->_imports;
		echo "\n*/\n\n";
		
		//显示内容
		echo $this->get_file_contents($this->cacheFile);
	}
	
	/**
	 * 合并js或css文件
	 * 
	 * @param  $fileList 需要载入的文件列表
	 */
	private function mergeFile($fileList){
		$content="";
		foreach ($fileList as $v){
			$fileName=realpath($v);
			if($fileName==false){ //文件不存在
				exit("line[".__LINE__."]文件不存在".$v);
			}
			$pathinfo=pathinfo($fileName);
			if($this->fileExt!=$pathinfo['extension']){continue;} //不是规定的文件后缀
			if($this->clearComment){
			//1.清除注释,只有css和js后缀的文件才会	
				if($this->fileExt=="css"){
					$tmp=$this->get_css_contents($fileName);
				}elseif($this->fileExt=="js"){
					$tmp=$this->get_js_contents($fileName);
				}
			}else{
			//2.直接导入文件内容	
				$tmp.="\n\n/*\n++++++++++++++++++++++++++++++++++++++++\n";
				$tmp.="+ FILE:".$fileName."";
				$tmp.="\n++++++++++++++++++++++++++++++++++++++++\n*/\n\n";
				$tmp.=$this->get_file_contents($fileName);
			}
			$content.=$tmp;$tmp="";
			$this->_imports.=$fileName."\n";
		}
		$this->_content=$content;
	}
	/**
	 * 获取css文件内容，并格式化
	 */
	private function get_css_contents($fileName){
		$tmp=$this->get_file_contents($fileName);
		 $pattern=array(
	     		'/[\n\r\t\f]+/',
	     		'/ {2,}/',
	     		'/\/\*.*?\*\//'
	     );
	     $replacement=array(
	     		'',
	     		' ',
	     		''
	     );			     
	     return preg_replace($pattern,$replacement,$tmp);
	}
	/**
	 * 获取js文件内容，并格式化
	 * 
	 * 后瞻断言 中的正面断言以”(?<=”开始, 消极断言以”(?<!”开始。 
	 * 
	 * (?<!foo)bar 用于查找任何前面不是 ”foo” 的 ”bar”。 
	 * (?<=bullock|donkey)bar    查找任何前面是 ”bullock或 donkey ” 的 ”bar”。
	 * 
	 */
	private function get_js_contents($fileName){
		 $content_one='';   //置零，暂存变量，只装载一个文件的内容
		  //将一个文件逐行读取
		  $file=fopen($fileName,"r");
		  if($file===false) continue;
		  while(!feof($file)){
			$con=fgets($file);                      //$con为每行的内容    
			$con=preg_replace('/(?<!http:)\/\/.+/s','',$con); //删除单号注释			  	  	
			$content_one.=$con;                     //在循环中慎用.=
		  }
		  fclose($file);
		  $pattern=array(
			  '/[\n\r\t\f]/',                // '/[\n\r\f\t]+/',
			  '/ {2,}/',
			  '/\/\*.*?\*\//'
		   );
		  $replacement=array(
			  ' ',
			  ' ',
			  ''
		   );
		  return preg_replace($pattern,$replacement,$content_one); //只处理了一个文件
	}
	//读取文件的内容
	private function get_file_contents($filePath){
		return file_get_contents($filePath);
	}
	//生成缓存
	private function buildCache(){
		if($this->cacheFile==null){return;}
		$cacheName=$this->cacheFile;
		//w 只写。打开并清空文件的内容；如果文件不存在，则创建新文件。
		$fp=fopen($cacheName,"w") or die("<script>alert('写入文件缓存{$cacheName}失败！');history.go(-1);</script>");
		fwrite($fp,$this->_content);
		fclose($fp);
	}

}