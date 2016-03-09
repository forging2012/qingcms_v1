<?php
/**
 * 全局设置
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class GlobAction extends InitAction{
	public function index(){
		echo MODULE_NAME;
	}
	/**
	 * 站点信息
	 */
	public function siteInfo(){
		$site_info=D('System')->lget('siteinfo');
		// 风格包列表
		$dir=scandir(PATH_THEMES);
		foreach($dir as $k=>$v){
			if(!is_dir(PATH_THEMES.'/'.$v)||$v=='.'||$v=='..')
				unset($dir[$k]);
		}
		$site_info['themesArr']=$dir;
		$this->assign($site_info);
		$this->display();
	}
	/**
	 * 设置站点信息
	 */
	public function doSetSiteInfo(){
		$res=D('System')->lput('siteinfo',$_POST);
		if($res){
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}
	/**
	 * 注册和登录控制
	 */
	public function reglogin(){
		$this->display();
	}
	/**
	 * SEO设置
	 */
	public function seo(){
		$this->display();
	}
	/**
	 * 广告管理
	 */
	public function ad(){
		$res=false;
		/*
		 * 删除操作
		 */
		if($_GET['del']==1 && $_GET['id']!=''){
			$dbPre=C('DB_PREFIX');
			$Model=new Model();
			$res=$Model->query("DELETE FROM `".$dbPre."ad` WHERE id=".$_GET['id']);
		}
		/*
		 * 开启/关闭操作
		 */
		if($_GET['sw']==1  && $_GET['is_active']!='' && $_GET['id']!=''){
			if($_GET['is_active']==0){
				$is_active=1;
			}else{
				$is_active=0;
			}
			$dbPre=C('DB_PREFIX');
			$Model=new Model();
			$res=$Model->query("UPDATE `".$dbPre."ad` SET is_active=".$is_active." WHERE id=".$_GET['id']);
		}
		if(!($res===false)){
			$this->_updateAdTemp(); // 更新缓存
		}	
		// 广告分类
		$adplace=array('right_1'=>'右侧-1','right_2'=>'右侧-2','bottom'=>'底部');
		$adlist=D('Ad')->order('is_active desc')->select();
		$this->assign('adlist',$adlist);
		$this->assign('adplace',$adplace);
		$this->display();
	}
	/**
	 * 添加广告
	 */
	public function addAd(){
		$request=Request::load();
		$title  =$request->_post('title');
		$place  =$request->_post('place');
		$content=$request->_post('content');
		
		// 获取表前缀
		$dbPre=C('DB_PREFIX');
		$Model=new Model();
		$res=$Model->query("INSERT INTO `".$dbPre."ad` (`title`, `place`, `content`) VALUES ('{$title}','{$place}','{$content}')");
		if($res>0){
			// 更新缓存
			$this->_updateAdTemp();
			$this->success('添加成功');
		}else{
			$this->error('添加失败');
		}
	}
	/**
	 * 更新广告缓存保存
	 */
	private function _updateAdTemp(){
		$ad=D('Ad')->where('is_active>0')->field('place,content')->select();
		if(!$ad){
			return null;
		}	
		foreach($ad as $k=>$v){
			$data[$v['place']][]=$v['content']; // $data['right'][0]$data['right'][1]
		}	
		/*
		 * $data=addcslashes(serialize($data),"'"); $content='<?php $ad_cache=\''.$data.'\'; ?>';
		 */
		SaveTemp($data,'~Ad.php');
	}
	/**
	 * 显示设置
	 */
	public function view(){
		$top10List=array(array('t'=>'8小时最热','id'=>0),array('t'=>'24小时最热','id'=>1),array('t'=>'7天最热','id'=>2),array('t'=>'30天最热','id'=>3),array('t'=>'全部时间','id'=>4));
		$this->assign('top10List',$top10List);
		$view=D('System')->lget('view');
		// dump($view);
		$this->assign($view);
		$this->display();
	}
	/**
	 * 设置显示设置
	 */
	public function setView(){
		// 移动.htaccess文件
		if($_POST['isRewrite']){
			if(!file_exists("./.htaccess")){ // 相对于admin.php文件路径
				copy("./install/data/.htaccess","./.htaccess");
			}
		}else{
			if(file_exists("./.htaccess"))
				unlink("./.htaccess");
		}
		$res=D('System')->lput('view',$_POST);
		if($res){
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}
	/**
	 * 友情链接
	 */
	public function flink(){
		$list=D('Friendlink')->order('displayorder asc')->select();
		// dump($list);
		$this->assign('list',$list);
		$this->display();
	}
	public function doflink(){
		// dump($_POST);
		// exit();
		$nameArr=$_POST['name'];
		foreach($nameArr as $k=>$v){
			if($_POST['name'][$k]=='')
				continue; // 没有填写名称则跳过
					          // 新增加的id不存在
			if($_POST['id'][$k]>0)
				$values_re.='('.$_POST['id'][$k].',"'.$_POST['name'][$k].'","'.$_POST['url'][$k].'","'.$_POST['displayorder'][$k].'","'.$_POST['description'][$k].'","'.$_POST['logo'][$k].'",'.time().'),';
			else
				$values_in.='("'.$_POST['name'][$k].'","'.$_POST['url'][$k].'","'.$_POST['displayorder'][$k].'","'.$_POST['description'][$k].'","'.$_POST['logo'][$k].'",'.time().'),';
				
				// 处理选择删除的
			if(intval($_POST['del'][$k])>0){
				$delmap.=" id=".$_POST['del'][$k].' OR';
			}
		}
		$values_re=rtrim($values_re,',');
		$values_in=rtrim($values_in,',');
		$delmap=rtrim($delmap,'OR');
		// dump($delmap);
		// exit();
		$pre=C('DB_PREFIX');
		$sql_re="REPLACE INTO ".$pre."friendlink  (`id`,`name`,`url`,`displayorder`,`description`,`logo`,`ctime`) VALUES".$values_re; // 更新更改的数据
		$sql_in="INSERT INTO ".$pre."friendlink  (`name`,`url`,`displayorder`,`description`,`logo`,`ctime`) VALUES".$values_in; // 插入新数据
		if($values_in>''){
			$in=M()->execute($sql_in); // 返回影响列数
		}
		if($values_re>''){
			$resre=M()->execute($sql_re); // 返回影响列数
		}
		if($delmap>''){
			$del=D('Friendlink')->where($delmap)->delete(); // 返回影响列数
		}
		if($resre||$in||$del){
			$this->success('友情链接更新完成');
		}else{
			$this->error('友情链接更新失败');
		}
	}
	/**
	 * 第三方工具
	 */
	public function tools(){
		$temp=D('System')->lget('tools');
		$this->assign($temp);
		$this->display();
	}
	public function setTools(){
		$res=D('System')->lput('tools',$_POST);
		if($res){
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}
	/**
	 * 附件设置
	 */
	public function attachment(){
		$waterPositionArr=array('0'=>'随机','1'=>'顶端居左','2'=>'顶端居中','3'=>'顶端居右','4'=>'中部居左','5'=>'中部居中','6'=>'中部居右','7'=>'底端居左','8'=>'底端居中','9'=>'底端居右');
		$this->assign('waterPositionArr',$waterPositionArr);
		$attachment=D('System')->lget('attachment');
		$this->assign($attachment);
		$this->display();
	}
	public function setAttachment(){
		$res=D('System')->lput('attachment',$_POST);
		if($res){
			$this->success('保存成功');
		}else{
			$this->error('保存失败');
		}
	}
}