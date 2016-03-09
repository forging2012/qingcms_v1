<?php
/**
 * 插件静态文件合并/css/js
 */
class StaticPlugin{
	public static $_instance=null; //实例
	/**
	 * 加载器
	 */
	public static function load(){
		if(self::$_instance==null){
			self::$_instance=new StaticPlugin();
		}
		return self::$_instance;
		return new StaticPlugin(); //TODO: Should Delete ! 只用于帮助ZendStudio提示
	}
	//插件目录
	private $pluginPath=PATH_PLUGIN;
	private $cssDir	   ='{CSSDIR}'; //在Css中使用来为图片定位， {CSSDIR}代表当前css文件的路径，plugin下为tpl目录，主题为Common目录
	//css
	public function getCss(){
		$fileList=StaticPlugin::load()->getList('css');
		$content =$this->merge_file($fileList,true);
		return $content;
	}
	//js
	public function getJs(){
		$fileList=StaticPlugin::load()->getList('js');
		$content =$this->merge_file($fileList);
		return $content;
	}
	/**
	 * 合并多个文件到一个文件
	 * @param  $fileList
	 * @param  $ext
	 */
	private function merge_file($fileList,$isCss=false){
		$content='';
		foreach ($fileList as $path){
			$one=file_get_contents($path);
			if($isCss){
				$url=UrlHelper::load()->getUrlByPath($path);
				//替换css文件所在的url路径
				$one=str_replace($this->cssDir,dirname($url),$one);
			}
			$content.=$one;
		}
		return $content;
	}
	/**
	 * 根据文件前缀后缀获取文件列表
	 * 
	 * @param string $suffix 文件后缀/扩展名
	 * @param string $prefix 文件前缀
	 * @return string
	 */
	public function getList($suffix='css',$prefix='extend_'){
		//返回开启的插件，不只是安装
		$pluginList=pluginIn(true);
		if(empty($pluginList)){
			return array();
		}
		$fileList=array();
		// 遍历插件tpl目录
		foreach($pluginList as $p){
			//插件tpl目录
			$path =$this->pluginPath.'/'.$p['name'].'/tpl';
			$files=$this->fileList($path,$suffix,$prefix);
			foreach($files as $f){
				$fileList[]=realpath($path.'/'.$f);
			}
		}
		return $fileList;
	}
	
	/**
	 * 获取某个目录下的文件列表
	 * 不返回目录
	 *  $rule文件名的规则名称
	 */
	public function fileList($directory,$suffix,$prefix){
		$dir=scandir($directory);
		foreach ($dir as $k=>$v){
			if(!is_file($directory.'/'.$v) || $v=='.' || $v=='..' || (!$this->file_Rule($v,$suffix,$prefix))){ //是目录，..，.不符合文件名规则
				unset($dir[$k]);
			}	
		}
		return $dir;
	}
	/**
	 * 插件的css/js文件名规则验证
	 * extend_common.css
	 * extend_1.js
	 */
	public function file_Rule($filename,$suffix,$prefix){
		return preg_match("/^{$prefix}.*{$suffix}$/", $filename);
	}
	
}


?>