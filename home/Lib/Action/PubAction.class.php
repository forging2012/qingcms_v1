<?php
/**
 * 发布文章
 * 
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class PubAction extends InitAction{
	function _initialize(){
		if(!$this->isLogged()){
			$this->needLogin();
		}
	}
	public function index(){
		$this->pub();
	}
	/**
	 * 显示发布页面
	 */
	public function pub(){
		/**
		 * 字数限制和分页限制
		 */
		global $_G;
		$v=$_G['view'];
		$this->assign('minlength',$v['minlength']); // 文章字数限制
		$this->assign('length',$v['length']); // 文章字数限制
		$this->assign('cateMust',$v['cateMust']);
		/*
		 * 设置标题
		 */
		$this->setTitle(L('Pub'));
		// 标签
		$tag_list=D('Tag')->limit(30)->order('count desc')->select();
		$this->assign('tag_list',$tag_list);
		// dump($tag_list);
		/*
		 * 获取所有栏目列表
		 */
		import_function('category');
		$cate=Temp::getCategory();
		$cate=category_format($cate);
		// dump($cate);exit();
		$cate_tab	=array('0'=>'普通文章','2'=>'gif图','3'=>'视频');
		$typeNow	=(int)$_GET['type'];
		$typeNowName=$cate_tab[$typeNow];
		$cate1		=$cate[$typeNow];
		$this->setTopNav('Pub');
		$this->assign('cate1',$cate1);
		$this->assign('cate_tab',$cate_tab);
		$this->assign('typeNowName',$typeNowName);
		$this->assign('typeNow',$typeNow);
		$this->display('pub');
	}
	
	/**
	 * 发布操作 通过ajax
	 */
	public function doPub(){
		$data['content']	 =$_POST['content'];
		$data['ctime']		 =time();
		$data['uid']		 =$this->mid;
		$data['cateid'] 	 =$_POST['cate'];
		$data['content_type']=$_POST['content_type']; // dump($data);exit();
		/**
		 * 保存前 对内容和上传进行检测
		 */
		$contentCheck=D('Content')->Icheck($data);
		// 内容输入不合格
		if(!$contentCheck['success']){
			$return['success']=0;
			$return['msg']=$contentCheck['msg'];
			exit(json_encode($return));
		}
		$name='pic';
		// gif图输入时的处理
		if($data['content_type']==2){
			if(!$_FILES[$name]['name']){
				$return['success']=0;
				$return['msg']='gif图片必选...';
				exit(json_encode($return));
			}
			$thumb=false;
		}else{
			// 定制是否进行缩略
			$thumb=true;
		}
		// video输入时的处理
		if($data['content_type']==3){
			if(!$_POST['video_data']){
				$return['success']=0;
				$return['msg']='没有输入视频播放页链接，或获取视频信息失败';
				exit(json_encode($return));
			}
			$data['video_data']=stripcslashes($_POST['video_data']);
		}
		if($_FILES[$name]['name']!=''){
			// 上传处理
			require_once (C('iServicePATH'));
			$File=iService::Fileupload($name,$thumb);
			if($File['success']){
				// 上传成功
				$image['path']=$File['FileInfo']['savepath']; // 保存路径
				$image['name']=$File['FileInfo']['savename']; // 保存名称
				$image['width']=$File['fileInfo']['width'];
				$image['height']=$File['fileInfo']['height'];
				$data['pic']=$image;
			}else{
				// 图片上传失败
				$return['msg']=$File['ErrorMsg'];
				exit(json_encode($return));
			}
		}
		// tid msg
		$res=D('Content')->addAction($data);
		// 内容插入失败
		if(!$res['tid']){
			$return['success']=0;
			$return['msg']=$res['msg'];
			exit(json_encode($return));
		}
		$tags=$_POST['tags'];
		// 文章插入成功 且标签不为空 插入标签
		if($tags>''&&$res['tid']>0){
			D('Tag')->addAction($tags,$res['tid']);
		}
		$return['tid']=$res['tid'];
		$return['success']=1;
		exit(json_encode($return));
	}
	/**
	 * 未发布前检测标签可行性
	 */
	public function ajaxCheckTag(){
		$tag=$_POST['tag'];
		// $tag="发动机，奋斗奋斗，fdvddf，";
		$tags=(array)D('Tag')->_checkTag($tag); // 返回的是数组
		if(D('Tag')->_checkTagLength($tags)){
			exit('标签超过了5个，请修改...');
		}
		foreach($tags as $k=>$v){
			$html.="<em class='tag_pre_a'>".$v."</em>";
		}
		exit($html);
	}
	/**
	 * 发布视频处理
	 */
	public function parse_videoUrl(){
		// 服务器端引用（Server Side Includes）
		// 单一入口 所有类、函数都是在用口文件index.php上运行的，相对路径应该相对于index.php
		// LIB_PATH:项目类库路径 ./home/Lib/
		// dirname() 函数返回路径中的目录部分。
		include (PATH_CLASS.'/PubVideo.class.php');
		$v=new PubVideo();
		$v->paramUrl();
	}
}