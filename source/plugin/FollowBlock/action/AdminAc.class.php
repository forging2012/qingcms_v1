<?php
class AdminAc extends Hooks{
    
	/**
	 * 后台管理方法，指定的管理Hooks类下的admin方法
	 */
	public function index(){
		$list=D('Plugin')->Out($this->PluginNow);
		$this->assign('Tpl',$this->TplUrl);
		$this->assign('list',$list);
		$this->display('admin');
	}	
	/**
	 * 保存数据
	 */
	public function doAdmin(){
		$del=$_POST['del']; unset($_POST['del']);
		$post=$_POST;
		if($del)
			foreach ($del as $k=>$v){
			unset($post['name'][$v]);
			unset($post['url'][$v]);
		}
		$post=$this->_formalData($post);
		$p=$this->PluginNow; if(!$p) $this->error ('参数丢失' );	
		$res=D('Plugin')->In($p, $post);
		if($res){
			$this->success ( '保存成功' );
		}else{
			$this->error ( '保存失败' );
		}
	}
	private function _formalData($list){
		foreach ($list['name'] as $k=>$v){
			$l[$k]['name']=$v;
			$l[$k]['url']=$list['url'][$k];
		}
		return $l;
	}
	
}

