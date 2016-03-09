<?php
/**
 * 工具
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class ToolsAction extends InitAction{
	
    public function index(){}
    /**
     * 顶部导航
     */
    public function nav(){
       $cate=D("Content_cate")->where("parentid=0 AND isnav=1")->select();
       $navtop=D("nav")->select();
       
       $this->assign('cate',$cate);
       $this->assign('nav',$navtop);
       $this->display();
    }
    /**
     * 更新缓存
     */
    public function updateCache(){
    	$this->display();
    }
    public function doUpCache(){
    	$p=$_POST;
    	if(!$p) $this->error('请选择要更新的选项');
    	//更新运行缓存文件
    	if($p['admin'] || $p['home']){
    		require_once(PATH_CLASS."/iDir.class.php");
    		$Dir=new Dir();
    		if($p['home']){
    		  $Path='./~runtime/~home/';
    		  $Dir->delDir($Path);
    		}
    		if($p['admin']){
    		    $Path='./~runtime/~admin/';
    			$Dir->delDir($Path);
    		}    		
    	}
    	//更新Css/Js缓存
    	if($p['cssJs']){
            $this->updateCssJsCache(); //更新缓存
    	}
    	$this->success('更新成功');
    }
    /**
     * 更新css/js三中模式切换的缓存,数据库或Cache文件
     * updateCssJsCache
     * _updateCssJsCache
     */
    public function updateCssJsCache(){
    	//css
    	$content=StaticPlugin::load()->getCss();
    	$path=PATH_ROOT.'/static/_cache/plugin.css';
    	if(!is_dir(dirname($path))){
    		mkdir(dirname($path),0644,true);
    	}
    	file_put_contents($path, $content);
    	
    	//js
    	$content=StaticPlugin::load()->getJs();
    	$path=PATH_ROOT.'/static/_cache/plugin.js';
    	if(!is_dir(dirname($path))){
    		mkdir(dirname($path),0644,true);
    	}
    	file_put_contents($path, $content);
    }
    
    public function saveNav(){
    	foreach ($_POST['id'] as $k=>$v){
    		//删除记录
    		if(intval($_POST['delete'][$k])==1){
    			D('Nav')->where('id='.$k)->delete();	
    			continue;
    		}
    		//新插入数据
    		if(intval($v)==0 && $_POST['name'][$k]!=''){
    				$data3['displayorder']=intval($_POST['displayorder'][$k]);
    				$data3['url']=$_POST['url'][$k];
    				$data3['name']=$_POST['name'][$k];
    				$data3['nid']=$_POST['nid'][$k];
    				$data3['disabled']=intval($_POST['disabled'][$k]);
    				$add=D('Nav')->data($data3)->add();
    		}else{
    		//更新原有数据
     				$data['displayorder']=intval($_POST['displayorder'][$k]);
    				$data['url']=$_POST['url'][$k];
    				$data['name']=$_POST['name'][$k];
    				$data['nid']=$_POST['nid'][$k];
    				$data['disabled']=intval($_POST['disabled'][$k]);
    				$res=D('Nav')->where("id=".$k)->data($data)->save();
    			 }
    		}
       $this->success('更新成功');
    }
 
}