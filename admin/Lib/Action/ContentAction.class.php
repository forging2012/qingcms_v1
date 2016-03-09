<?php
/**
 * 内容管理
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class ContentAction extends InitAction{
    public function index(){
    echo MODULE_NAME;
   // $this->display();
    }
    /**
     * 文档列表
     */
    public function contentlist(){
    	//跨项目实例化model    	
    	$t=D("home://Content");          
    	
    	if($_GET['cid']>''){
    		$list=$t->byCateids($_GET['cid']);
    		$cate=getCateName($_GET['cid']);
    	}else if($_GET['uid']>''){
    		$list=$t->getUserPub($_GET['uid']);
    		$cate=getname($_GET['uid']).'的文档';
    	}elseif($_GET['tid']>''){
    		$list['list'][0]=D('Content')->where('id='.$_GET['tid'])->find();
    		$cate="id=".$_GET['tid'];
    	}else{
    		$list=$t->getAll();
    		$cate='全部';
    	}
    	$this->assign('cate',$cate);  
    	$this->assign('page',$list['page']);
    	$this->assign('list',$list['list']);
    	$this->display();
    }
    /**
     * 删除文章
     * 
     */
    public function delText(){
    	$ids=$_POST['ids'];
    	$res=D('home://Content')->del_recover($ids,'del');
    	echo $res?'1':'0';   	
    }
    /**
     * 编辑文章
     */
    public function editText(){
    	if($_POST['content']>''){
    		$res=D('home://Content')->editText($_POST);
    		if($res)
    			$this->success('保存成功');
    		else 
    			$this->error('编辑出错');
    		exit();
    	}
    	
    	$id=$_GET['id'];
    	if($id=='') exit('0');
    	$one=D('home://Content')->where('id='.$id)->find();   	
    	$cate=getCatelist();
    	$this->assign('cate',$cate);
    	$this->assign('cateid',$one['cateid']);
    	$this->assign('one',$one);
    	$this->display();
    }
    /**
     * 审核
     */
    public function check(){
    	$ids=$_POST['ids'];
    	$res=D('home://Content')->checkFromAdmin($ids);
    	echo $res?'1':'0';
    }
    /**
     * 把文档移动到其他栏目
     */
    public function move(){
    	$ids=$_GET['ids'];
    	$cate=$_GET['cate'];
    	if($cate>''){
    		$res=D('home://Content')->moveFromAdmin($ids,$cate);
    		if($res){
    			$this->success('移动成功',U('Content/contentlist'));
    		}else{ $this->error('移动失败');}
    		exit();
    	}
    	
    	$cate=D('home://ContentCate')->select();
    	//提取顶级栏目
    	foreach ($cate as $k=>$v){
    		if($v['parentid']==0){
    			$cate1[$k]=$v;
    		}
    	}
    	//把二级栏目插入顶级栏目
    	foreach ($cate1 as $k1=>$v1){
    		foreach ($cate as $k=>$v){
    			if($v['parentid']==$v1['id']){
    				$cate1[$k1]['cate2'][$k]=$v;
    			}
    		}
    	
    	}
    	$this->assign('cate1',$cate1);
    	$this->assign('ids',$ids);
    	$this->display();
    }   
    /**
     * 内容模型
     * 0:图文
     * 1：图文
     * 2：gif图
     * 3：视频
     * qc_content 中 0：仅文本，1：图片
     */
    public function content_type(){
    	$type=D('Content_type')->select();
    	$this->assign('type',$type);
    	$this->display();
    }
    /**
     * 栏目管理
     */
    public function cate(){
    	$cate=D("content_cate")->order('displayorder')->select();
    	
    	foreach ($cate as $k=>$v){
    		if($v['parentid']==0){
    			$cateTop[$k]=$v;
    		}
    	}
    	foreach ($cateTop as $key=>$value){
    		foreach ($cate as $k=>$v){
    			if($v['parentid']==$value['id']){
    				$cateTop[$key]['cate2'][$k]=$v;
    			}
    		}
    	}    	
    	$this->assign('cateTop',$cateTop);
    	$this->display();
    }
    /**
     * 移动栏目
     */
   public function cateMove(){
   	$ids=$_GET['ids'];
   	$cate=$_GET['cate'];
   	if(isset($cate)){
   		$res=D('ContentCate')->moveFromAdmin($ids,$cate);
   		if($res){
   			$this->success('移动成功',U('Content/cate'));
   		}else{ $this->error('移动失败');}
   		exit();
   	}
   	$ids_string=$ids;
   	$ids =explode(',',$ids);
   	if(!empty($ids)){
   		foreach($ids as $v){
   			$names.=getCateName($v).',';
   		}
   		$names=rtrim($names,',');
   	}
   	$cate=D('ContentCate')->select();
   	//提取顶级栏目
   	foreach ($cate as $k=>$v){
   		//排除已经选择的顶级栏目
   		if(in_array($v['id'], $ids)) continue;
   		if($v['parentid']==0){
   			$cate1[$k]=$v;
   		}
   	}
   	$this->assign('cate1',$cate1);
   	$this->assign('ids',$ids_string);
   	$this->assign('names',$names);
   	$this->display();
   }
   /**
    * 删除栏目
    * ajax
    */
   public function delCate(){
   	$ids=$_POST['ids'];
    $res=D('ContentCate')->delFromAdmin($ids);
    echo $res?'1':'0';
   }
    /**
     *  网站栏目  快捷管理
     */
    public function qcate(){
    	//内容模型
    	$type=D('Content_type')->select(); 
    	$this->assign('content_type',$type);
    	$cate=D("content_cate");
    	$cateTop=$cate->where('parentid=0')->order('displayorder')->select();
    	foreach ($cateTop as $k=>$v){
    		$cateTop[$k]['cate2']=$cate->where("parentid=".$v['id'])->order('displayorder')->select();
    	}
    	$this->assign('cateTop',$cateTop);
    	$this->display();
    }
    /**
     * 添加 、更新栏目 
     */
    public function doQcate(){  
     	//nid唯一
    	$nidArr=$_POST['nid'];
     	foreach ($nidArr as $k=>$v){
     		unset($nidArr[$k]);
     		if(in_array($v, $nidArr)){
     			$this->error('标识符'.$v.'重复,请修改后再提交');
     			exit();
     		}
     	}
     	$nameArr=$_POST['name'];
    	foreach ($nameArr as $k=>$v){
    		if($v=='' || $_POST['nid'][$k]=='') continue;//没有填写名称则跳过
    		//新增加的id不存在,进行修改而不是新增
    		if(intval($_POST['id'][$k])>0){
    			//单选按钮，默认
    			if($_POST['def']==$_POST['id'][$k]){
    			   $def=1;
    			}else{ $def=0;}
    			//多选按钮，是否导航
    			if(in_array($_POST['id'][$k],$_POST['isnav'])){
    				$isnav=1;
    			}else{ $isnav=0;}
    			//内容模型
    			if(array_key_exists($_POST['id'][$k], $_POST['content_type'])){
    				$content_type=$_POST['content_type'][$_POST['id'][$k]];
    			}else $content_type=0;
    			$values_re.='("'.$_POST['id'][$k].'","'
    			               .$_POST['parentid'][$k].'","'
    		                   .$_POST['name'][$k].'","'
    		                   .$_POST['nid'][$k].'","'
    		                   .$_POST['displayorder'][$k].'","'
    		                   .$content_type.'","'
    		                   .$def.'","'
    		                   .$isnav.'"),';
    		}else{
    			$values_in.='("'
    			               .$_POST['parentid'][$k].'","'
    		                   .$_POST['name'][$k].'","'
    		                   .$_POST['nid'][$k].'","'
    		                   .$_POST['displayorder'][$k].'"),';  	
    		}	 
    		//处理选择删除的
    		if(intval($_POST['delete'][$k])>0){
    			$delmap.=" id=".$_POST['delete'][$k].' OR';
    		}
    	
    	}
//     	dump($values_re);exit();
    	$values_re=rtrim($values_re,',');
    	$values_in=rtrim($values_in,',');
    	$delmap=rtrim($delmap,'OR');
    	$pre=C('DB_PREFIX');
    	$sql_re="REPLACE INTO ".$pre."content_cate  (`id`,`parentid`,`name`,`nid`,`displayorder`,`type_id`,`def`,`isnav`) VALUES".$values_re;//更新更改的数据
    	$sql_in="INSERT INTO ".$pre."content_cate  (`parentid`,`name`,`nid`,`displayorder`) VALUES".$values_in;//插入新数据
    	$resre=1;$in=1;$del=1;
    	if($values_in>''){
    		$in=M()->execute($sql_in);//返回影响列数
    	}
    	if($values_re>''){
    		$resre=M()->execute($sql_re);//返回影响列数
    	}
    	if($delmap>''){
    		$del=D('ContentCate')->where($delmap)->delete();//返回影响列数
    	}
    	
    	if($resre===false){
    		$this->error('更新栏目失败');
    	}else if($in===false){
    		$this->error('添加栏目失败');
    	}else if($del===false){
    		$this->error('删除栏目失败');
    	}else{
    		D('ContentCate')->saveTemp();//更新缓存
    		$this->success('栏目更新成功');
    	}
    	exit();
    }
    /**
     * 回收站
     */
    public function recycleBin(){
    	$t=D("home://Content");       
    	$list=$t->where('is_del=1')->order('id desc')->select();
    	$this->assign('list',$list);
    	$this->display();
    }
    /**
     * 彻底删除
     */
    public function completelyDel(){
    	//清空回收站
    	if($_GET['clear']){
    		$res=D('home://Content')->completelyDelFromAdmin('0',1);
    		if(!($res===false)){
    			$this->success('清空回收站成功');
    		}else{
    			$this->error('清空回收站失败');    			
    		}
    		exit();
    	}
    	$ids=$_POST['ids'];
    	$res=D('home://Content')->completelyDelFromAdmin($ids);
    	echo $res?'1':'0';
    }
    /**
     * 恢复内容
     */
    public function recover(){
    	$ids=$_POST['ids'];
    	$res=D('home://Content')->del_recover($ids,'recover');
    	echo $res?'1':'0';   
    }
    public function test(){
    	$res=D('home://Content')->_delAttachment('0',1);
    	dump($res);
    }
    
}