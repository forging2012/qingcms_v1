<?php
/**
 * 
 */
class Cache{
	 private $PluginDir=PATH_PLUGIN;
	 private $cssDir='{CSSDIR}'; //在Css中使用来为图片定位， {CSSDIR}代表当前css文件的路径，plugin下为tpl目录，主题为Common目录
	/**
	 * 把系统Css缓存
	 * 主程序+主题+插件
	 * @return /public/css/common.css 不能有点
	 * link_css()/merge_css()均要引用
	 */
	public function css(){
		//Public下
		$path="/public/css/";
		$ext='css';
		$list['public']=$this->publicPath($path, $ext);
		//插件
		$list['plugin']=$this->pluginPath('css');	
		return $list;
	}
	public function js(){
		$path="/public/js/";
		$ext='js';
		$list['public']=$this->publicPath($path, $ext);
		//插件
		$list['plugin']=$this->pluginPath('js');
		return $list;
	}
	// public  css/js
	private function publicPath($path,$ext){
		require_once(PATH_CLASS."/iDir.class.php");
		$Dir=new Dir();
		$PathDir='.'.$path;    // ./public/css/
		$list=$Dir->fileList($PathDir,'ext_Rule',$ext);
		foreach ($list as $v){
			$urlArr[]=$path.$v;  //相对于入口文件的地址
		}
		return $urlArr;
	}
	//.core/plugin
	private  function pluginPath($ext){
	  require_once(PATH_CLASS."/iDir.class.php");
	  $Dir=new Dir();
	  $pluginList=pluginIn(1);
	  //遍历插件tpl目录
	  foreach ($pluginList as $p){
	  	 $path=$this->PluginDir.'/'.$p['name'].'/tpl/';
	  	 $fileLi=$Dir->fileList($path,'pluginCssJs_Rule',$ext);
	  	 foreach ($fileLi as $f){
	  	 	$fileList[]=substr(PATH_PLUGIN,1).'/'.$p['name'].'/tpl/'.$f;
	  	 }
	  }
	  return $fileList;
	}
	
	
	/**
	 * 取得 debug模式下的css/js外链代码
	 */
	public function link(){
		$css=$this->link_Css(); //返回的字符串呢
		$js=$this->link_Js();   //返回的字符串呢
		return $css.$js;
	}
	/**
	 * 返回css地址集合
	 */
	private function link_Css(){
		$urlPath=PATH_PATH;
		$list=$this->css(); 
		//$list['public'] $list['plugin']
		foreach ($list['public'] as $li){
			$urlList.='<link rel="stylesheet"  type="text/css" href="'.$urlPath.$li.'" />
			';
		}
		foreach ($list['plugin'] as $li){
			$urlList.='<link rel="stylesheet"  type="text/css" href="'.$urlPath.$li.'" />
			';
		}
		return $urlList;
	}
	/**
	 * js链接集合
	 */
	private function link_Js(){
		$urlPath=PATH_PATH;
		$list=$this->js();
		//$list['public'] $list['plugin']
		foreach ($list['public'] as $li){
			$urlList.='<script type="text/javascript" src="'.$urlPath.$li.'"></script>
			';
		}
		foreach ($list['plugin'] as $li){
			   $urlList.='<script type="text/javascript" src="'.$urlPath.$li.'"></script>
			';
		}
		return $urlList;
	}
	/**
	 *  更新缓存文件
	 */
	public function _updateCache(){
		$this->merge_css();
		$this->merge_js();
	}
	/**
	 * 把css合并到一个文件
	 * 
	 */
	private function merge_css(){
		$fileList=$this->css(); 
		//$list['public'] $list['plugin']
        $content.=$this->merge_file($fileList['public'], 'css');
        $content.=$this->merge_file($fileList['plugin'], 'css');
        SaveCache($content, $this->cache_css); //保存缓存文件
	}
	private function merge_js(){
		$fileList=$this->js(); 
		//$list['public'] $list['plugin']
		$content.=$this->merge_file($fileList['public'], 'js');
		$content.=$this->merge_file($fileList['plugin'], 'js');
		SaveCache($content, $this->cache_js); //保存缓存文件
	}
	/**
	 * 合并多个文件到一个文件
	 * @param unknown_type $fileList
	 * @param unknown_type $ext
	 */
	private function merge_file($fileList,$ext){
		foreach ($fileList as $path){
			$con=file_get_contents('.'.$path);
			//取得当前路径,只有css文件才进行检测
			 if($ext=='css'){
			    $Dir=PATH_PATH.dirname($path);
			    $con=str_replace($this->cssDir,$Dir,$con);// 用正确的路径代替{IMGDIR}
			   // $con=preg_replace("/\/\*[^]*\*\//i", " ",$con);//注释内容
			   // $con=strip_tags($con);
			   // $con=str_replace("\r\n", "", $con);
			   // $con=str_replace("\s", "", $con);
			   //  $con=preg_replace('[(/*)+.+(*/)]','p', $con);		   
			   //  $con=preg_replace ('/ |　/is', '', $con);
			     $con= preg_replace ('/\\r|\\n|\\t/i', '', $con);
			   // $con=preg_replace("/\\r|\\n|\\t|\/\*[^!]*\*\//i",'',$con);//注释 
			   //  $con=preg_replace("/\/\*[^!]*\*\//i",'',$con);//注释
			     $content.=$con;
			  }elseif($ext=='js'){
			 	  $content.=$con;
			}
		} 
	    return $content;
	}
	private $cache_css='link_css.css';
	private $cache_js='link_js.js';
	/**
	 * 返回缓存状态的链接代码
	 */
	public function link_cache(){
		$Cache_path=PATH_PATH.substr(PATH_CACHE, 1).'/';
		$link='<script type="text/javascript" src="'.$Cache_path.$this->cache_js.'"></script>';
		$link.='<link rel="stylesheet"  type="text/css" href="'.$Cache_path.$this->cache_css.'" />';
		return $link;
	}
// 	private function formatArr($arr){
// 		//$list['public'] $list['plugin']
// 		foreach ($arr as $path=>$li){
// 			foreach ($li as $pArr){  
// 			  if(is_array($pArr))    //插件下文件，数组
// 				foreach ($pArr as $v)
// 				   $arr2[]=$v;
// 			  else //public 下非数组
// 			  	$arr2[]=$pArr;
// 			}	
// 		}
//         return $arr2;
// 	}
	 
	
}


?>