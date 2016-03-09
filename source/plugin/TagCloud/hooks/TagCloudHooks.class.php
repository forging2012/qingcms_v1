<?php
class TagCloudHooks extends Hooks{
	private $tagnum='30';
	/**
	 * 关注模块
	 */
	public function TagCloud(){
		// 取前100个标签的总数
		// $pre=C('DB_PREFIX');
		// $sql='SELECT SUM(count) AS countTotal FROM '.$pre.'tag ';
		// $total=M()->query($sql);
		// $total=$total[0]['countTotal'];//含有标签的文章的数量
		// 标签个数
		// $total_tag=D('Tag')->count();
		$list=D('Tag')->limit($this->tagnum)->order('count desc')->select();
		// foreach ($list as $k=>$tag){
		// $list[$k]['font-size']=$this->format_font_size($tag['count'],$total);
		// }
		$this->assign('list',$list);
		$this->display('tags');
	}
	/**
	 * 格式化数据
	 */
	private function format_font_size($count,$total){
		$ratio=$count/$total;
	}
}