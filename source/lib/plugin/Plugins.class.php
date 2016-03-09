<?php
/**
 * @author QingCms
 * //插件的方法转发
 */
class Plugins{

	static public $PluginNow=''; //现在所处的插件
	static public $HookNow='';   //现在所处的钩子
	static public $ActionNow=''; //现在所处的方法
	static public $path=PATH_PLUGIN;
	static public $errorMsg=array();
	static public $validListInfo=array();//有效的插件列表，只有执行validList才能产生该变量
	
	static public function hook($PH,$M,$vars= array()){
        //实例化相应插件的Hooks，实例化相应插件下Hooks类，执行相应方法
        foreach ($PH as $p=>$hArr){
        	foreach ($hArr as $h){
        		//在执行钩子方法时，指明现在所处的插件和钩子，方便在钩子方法调用
        		self::$PluginNow=$p;
        		self::$HookNow=$h;
        		//执行该钩子下的所有方法  
        		$obj=self::getObj($p,$h,'Hooks',false);//已经实例化对象了
        		$obj->$M();
        	}
        }
    }
    /**
     * getHooksList
     * 获取所有插件的所有钩子列表
     * @access public
     * @return void
     * @param  $list 已开启插件列表
     */
    static public function getHooksList($list=array()){
    	if(!is_array($list)) return false;
    	$hooksBoss= get_class_methods('Hooks'); //Hooks类中的方法，剔除由于继承Hooks类的多余方法
    	$path=self::$path;
    	//取得基本的Plugins->Hooks->Method数组
    	foreach ($list as $v){
    		$obj=self::getObj($v);
    		$hooksArr=$hooksList[$v]=$obj->hooksList(); //['list']
    		foreach ($hooksArr as $h){
    			self::getObj($v,$h,'Hooks',true);
    			$hooksMethods[$v][$h]=array_diff(get_class_methods($h),$hooksBoss);
    		}
    	}
    	//进行处理 Method->Plugins->Hooks数组
    	foreach ($hooksMethods as $p=>$h){ //$p：Plugins名称 $h为数组
    		$h1[]=$h;
    		$p1[]=$p;
    		foreach ($h as $hName=>$m){     //$hkey：Hooks名称 $m也是数组
    			$m1[]=$m;
    			foreach ($m as $mName)      //$mName 方法名   
    			  $formalList[$mName][$p][]=$hName;
    		}
    	}
    	unset($formalList['admin']);
    	unset($formalList['doAdmin']);
    	return $formalList;
    }
    /**
     * 获取对象  Plugins/Hooks
     * @param  $Pname 插件名称   Test：插件目录，后不带Plugins
     * @param  $Hname 钩子名称 $Hname=MainHooks后已经有Hooks
     * @param  $type=Plugins/Hooks
     * @param  $noNew=false/true
     * 
     */
    static public function getObj($Pname,$Hname='',$type='Plugins',$noNew=false){
    	if($type=='Plugins'){
    	   $file=self::$path.'/'.$Pname.'/'.$Pname.'Plugins.class.php';
    	   $Cname=$Pname."Plugins";
    	}elseif ($type=='Hooks' && $Hname!=''){
    	   $file=self::$path.'/'.$Pname.'/hooks/'.$Hname.'.class.php'; //$Hname=MainHooks
    	   $Cname=$Hname; //注，带了Hooks 如MainHooks
    	   //当页面存在多个相同的插件，以载入相同的hooks类
    	   if(class_exists($Cname)) return new $Cname();
    	}else return false;
    	if(!file_exists($file)) return false; //文件不存在
    	require_once($file); 
    	if ($noNew===true) return true; //不进行实例化,只载入文件
    	if(!class_exists($Cname)) return false; //类不存在
    	$obj=new $Cname();  //TestPlugin MainHooks
    	return $obj;
    }
    /**
     *  管理员的后台设置
     *  后天通过该方法调用插件自定义管理方法
     */
    static public function admin($PluginName){
    	$objHook=self::_adminToHook($PluginName);
    	$objHook->admin(); //调用指定的管理钩子类下的Admin方法
    }
    /**
     *  管理员的后台设置保存
     *  后天通过该方法调用插件自定义管理保存方法
     */
    static public function doAdmin($PluginName){
    	$objHook=self::_adminToHook($PluginName);
    	$objHook->doAdmin(); //调用指定的管理钩子类下的doAdmin方法
    }
    /**
     * 
     * @param string $PluginName
     * @return obj
     */
    static private function _adminToHook($PluginName){
    	//插件对象
    	$obj=self::getObj($PluginName); if(!$obj)  return $PluginName.'类不存在';//对象不存在时，防止调用相应方法是报错
    	$hooks=$obj->hooksList();
    	$adminHook=$hooks['admin'];
    	if(!isset($hooks['admin']))  return '<b style="color:red;">该插件没有管理面板</b>';
    	//进入插件管理页面是取得当前的数据，可以在Hooks下的admin方法和模板中使用
    	self::$PluginNow=$PluginName;
    	self::$HookNow=$adminHook;
    	$objHook=self::getObj($PluginName,$adminHook,'Hooks',false);  if(!$objHook) return $adminHook.'类不存在';//对象不存在时
    	return $objHook;   
    }
    /**
     * 检测所有插件的有效性
     */
    static public function checkValid(){
    	$path=self::$path;
    	$dir=scandir($path);
    	foreach ($dir as $k=>$v){
    		if(!is_dir($path.'/'.$v) || $v=='.' || $v=='..') //是目录，..，.
    			unset($dir[$k]);
    	}
    	foreach ($dir as $name){
    		//检测Plugins类
    		$Pobj=self::getObj($name); if(!$Pobj){ self::$errorMsg[$name]="插件".$name."，不存在类".$name."Plugins,请检查".$name."Plugins.class.php文件"; continue;}
    	    $Pinfo=$Pobj->info();
    	    $hooksList[$name]=$hooks=$Pobj->hooksList();  
    	    //检测hooks存不存在
    	    foreach ($hooks as $k=>$h){
    	    	$Hobj=self::getObj($name,$h,'Hooks');
    	    	if(!$Hobj){
    	    		self::$errorMsg[$name]="插件".$name."，不存在Hooks类".$h; continue;
    	    	}
    	    	//当hooks类为管理类，admin方法是否存在
    	    	if($k==='admin'){
    	    		if(!method_exists($Hobj,'admin')  || !method_exists($Hobj,'doAdmin')){
    	    			self::$errorMsg[$name]="插件".$name."，类".$h."中的admin或doAdmin方法不存在"; continue;
    	    		}
    	    	}   	
    	    } 
    	}
    	//取得所有插件的hookslist,检测所有的hooks类不能有同名
    	$hs=array();
    	foreach ($hooksList as $p=>$hArr){
    		 foreach ($hArr as $k=>$h){
    		 	if(in_array($h, $hs)){
    		 		self::$errorMsg=array();
    		 		self::$errorMsg[$p]='<span style="color:red">致命错误：</span>类'.$p.'下的Hooks类'.$h.'和其他插件的hooks类同名，请更改其中一个的类名';
    		 		break; 
    		 	}	
    		 	$hs[]=$h;
    		 }
    	} 	
    }
    /**
     * 返回有效的插件列表
     */
    static public function validList(){
    	$path=self::$path;
    	$dir=scandir($path);
    	foreach ($dir as $k=>$v){
    		if(!is_dir($path.'/'.$v) || $v=='.' || $v=='..') //是目录，..，.
    			unset($dir[$k]);
    	}
    	//过滤掉没有相应结构的目录
    	foreach ($dir as $k=>$name){
    		$obj=self::getObj($name); 
    		if(!$obj){ unset($dir[$k]);
    		}else{
    			self::$validListInfo[$name]=$obj->info();
    			self::$validListInfo[$name]['name']=$name;
    		}
    	}
    	return $dir;
    }
    /**
     * 调用插件类下install方法执行安装
     * @return array 返回插件信息
     */
    static public function install($name){
    	$obj=self::getObj($name); if(!$obj)  return false;
    	//执行插件里的安装方法
    	$obj_in=$obj->install(); if(!$obj_in) return false;
    	return $obj->info();//返回插件信息
    }
    /**
     * 调用插件类下unInstall方法执行卸载
     */
    static public function uninstall($name){
    	$obj=self::getObj($name); if(!$obj)  return false;
    	//执行插件里的安装方法
    	$obj_in=$obj->uninstall(); if(!$obj_in) return false;
    }
    /**
     * 插件后台管理的处理URL
     * Url参数：现在所处插件名称
     * // Action/method
     *    Admin/method
     */
    static public function U_Admin($method){
    	return $url=__APP__.'?m=Plugin&a=toPluginAdmin&plugin='.self::$PluginNow.'&method='.$method;
    }
    /**
     * // Action/method
     */
    static public function U($str){
    	return U('Plugin/doManage?&PluginNow='.self::$PluginNow);
    }
    /**
     * 需要导入的css/js
     * extend_home.css
     * extend_1.js
     */
    static public function validCssJs($filename,$ext){
    }
    /**
     * 执行插件下的方法
     * 'plugin/action/method'
     */
    static public function runAction($plugin,$action,$method){
    	//禁止前台访问插件管理方法
    	if(strtolower($action)=='admin') exit('Access Denied');
    	self::$PluginNow=$plugin;
      	define('PATH_PLUGIN_PATH',self::$path.'/'.$plugin.'/');//执行方法文件所在的路径
    	$file=self::$path.'/'.$plugin.'/action/'.$action.'Ac.class.php';  
    	if(file_exists($file)) require_once($file); else exit('02');
    	$class=$action.'Ac';  
    	if(class_exists($class)){  
    		$obj=new $class();
    		$obj->$method();
    	}
    }
    /**
     * 执行插件的管理方法
     */
    static public function runAdminAction($plugin,$method=''){
    	self::$PluginNow=$plugin;
    	$msg='该插件没有管理面板...';
    	$file=self::$path.'/'.$plugin.'/action/AdminAc.class.php';
    	if(file_exists($file)) require_once($file); else exit($msg);
    	$class='AdminAc';
    	if(class_exists($class)){
    		$obj=new $class();
    		if($method) $obj->$method();
    		else $obj->index();     //默认为index方法
    	}else{exit($msg);}
    }
    
}
