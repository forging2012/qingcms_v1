<?php
/**
 * hook的抽象类
 * 类似于Action类，独立于主程序小线程
 * 实现模板解析等功能 
 * 
 * 注意Widget和Action的不同
 */
class Hooks
{  
	private $view;         //视图实例对象
	protected $PluginPath='';//现在所在的插件位置	
	//访问到Hooks类时，插件和hooks位置一定已经确定，在hook类和继承类下任何位置都可以调用
	protected  $PluginNow=''; //现在所处的插件
	protected  $HookNow='';   //现在所处的钩子
	protected  $TplUrl='';   
	protected  $TplPath='';    //插件模板tpl的路径
	/**
	 * 架构函数 
	 */
	public function __construct()
	{ 
		$this->PluginNow=Plugins::$PluginNow;
		$this->HookNow=Plugins::$HookNow;	   
	    $this->PluginPath=PATH_PLUGIN.'/'.$this->PluginNow;
	    //Url地址     //substr($path,1) 把点去掉
	    $this->TplPath=PATH_PATH.substr(PATH_PLUGIN,1).'/'.$this->PluginNow.'/tpl';
	    $this->view = Think::instance ( 'View' ); // 实例化视图类
	}
	/**
	 * 同Action的assign方法
	 */
	public function assign($name, $value='')
	{
		$this->view->assign($name, $value);
	}
	/**
	 +----------------------------------------------------------
	 * 模板显示
	 * 调用内置的模板引擎显示方法，
	 * $jump=true,使用$templateFile里面的全路径
	 +----------------------------------------------------------
	 */
    protected function display($templateFile='',$jump=false) {
    	$charset='';
    	$contentType='';
    	if(!$jump)
    	  $templateFile=$this->PluginPath."/tpl/".$templateFile.".html";    		
        $this->view->display($templateFile,$charset,$contentType);
    }
    
	/**
	 * 获取该插件目录下面的model模型文件。同D（）函数的作用;
	 */
	protected function model($name, $class = "Model")
	{
		$className = ucfirst($name) . $class;
		require_cache($this->path . DIRECTORY_SEPARATOR . $className . '.class.php');
		return new $className();
	}
    /**
     +----------------------------------------------------------
     * 操作成功跳转的快捷方法    
     +----------------------------------------------------------
     */
    protected function success($msg,$url=''){
    	$this->setTitle('提示信息-');
    	//保证输出不受静态缓存影响
    	C('HTML_CACHE_ON',false);
    	if($url=='') $url=$_SERVER["HTTP_REFERER"];
    	$this->assign('url',$url);
    	$this->assign('msg',$msg);
    	$this->assign('isture',1);
    	$this->display(THEME_PATH.'message.html',true);
    	exit();
    }
    protected function error($msg,$url='') {
    	$this->setTitle('提示信息-');
		//保证输出不受静态缓存影响
		C('HTML_CACHE_ON',false);
		$this->assign('msg',$msg);
		$this->assign('isture',0);
		$this->display(THEME_PATH.'message.html',true);
		exit();
    }
    /**
     * 设置标题
     * @param  $input
     */
    protected function setTitle($input)
    {
    	global $globalInfo;
    	$globalInfo['title'] = $input;
    }
    /**
     * 返回该插件的缓存数据
     */
    protected function data($key=null){
    	global $_P; //插件数据，可全局调用
    	//还没有使用过$_P
    	if(!isset($_P)){
    	 	$file_plugin_data=PATH_TEMP.'/~plugin_data.php';
    		if(file_exists($file_plugin_data)){
    		$_P=include_once($file_plugin_data);
    		}else{
    			return false;
    		}	
    	}
    	if($key)
    	   return $_P[$this->PluginNow][$key];
    	else
    	   return $_P[$this->PluginNow];
    }
}
