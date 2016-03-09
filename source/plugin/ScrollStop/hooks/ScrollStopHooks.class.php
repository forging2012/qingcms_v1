<?php
class ScrollStopHooks extends Hooks{
	/**
	 *  在这里停止滚动
	 */
	public function stopScrollHere(){
	     echo "<div class='stopScrollHere' style='height:0px;display:block;'></div>";
	     echo "<script type='text/javascript'>ScrollStop();</script>";
	}
	public function admin(){
		
	}
	public function doAdmin(){
	
	}
}