<?php
class MainHooks extends Hooks{
	/**
	 * 关注模块
	 */
	public function followBlock(){
        $list=$this->data(); //取出该插件的数据
		$this->assign('list',$list);
        $this->display('followBlock');
	}
}