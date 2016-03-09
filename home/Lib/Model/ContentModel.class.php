<?php
/**
 * 文章内容
 * @author xiaowang <736523132@qq.com>
 * @copyright 2012 http://qingcms.com All rights reserved.
 */
class ContentModel extends Model{
	protected	$tableName	=	'content';
	private     $HotOrderRule=' (up*0.5+down*0.1+comment*0.4) DESC ';//热门排行规则
		
	/**
	 * 获取分页设置条数
	 */
	private function _getPageNum(){
         return getPageNum();
	}
	/**
	 * 获取分类列表中的内容
	 * @param array  $ids
	 * @return 
	 */
	public function byCateids($ids){
		if(!is_array($ids)) return $this->get('cateid='.$ids);
		$map='cateid='.$ids[0];
		foreach ($ids as $k=>$v){
			if($k>0)
				$map.=" OR cateid={$v} ";
		}
		$data=$this->get($map);
		return $data;
	}
	/**
	 * 获得toplist排行榜 8th 24th 7day 30day ||最新排行
	 * @param unknown_type $cateList
	 * @param unknown_type $type new||hot
	 * @param unknown_type $time 8th 24th 7day 30day
	 * @param unknown_type $num 
	 */
	public function topList($cateList,$time=0,$num=10){
		//热门排行规则
		$order=$this->HotOrderRule;
		if($cateList==''){
			$map='';
		}else if(is_array($cateList)){
			$map='( cateid='.$cateList[0];
			foreach ($cateList as $k=>$v){
				if($k>0)
					$map.=" OR cateid={$v} ";
			}	
			$map.=")";
		}else{ 
			$map=" cateid=".$cateList;
		}
		if($time){ 
			if($map>'') 
				$map.=" AND ".time()."-ctime<=".$time;
		    else
			    $map="  ".time()."-ctime<=".$time;
		}
		$data=$this->where($map)->order($order)->limit($num)->select();
		return $data;
	}
	/**
	 *  根据id数组获取批量内容
	 */
	public function byIdList($idList){
		$map='id='.$idList[0];
		foreach ($idList as $k=>$v){
			if($k>0)
				$map.=" OR id={$v} ";
		}
		$data=$this->where($map)->order('id desc')->select();
		return $data;	
	}
	/**
	 * 返回用户的统计数据
	 */
	public function countNum($uid){
		return $this->where('uid='.$uid)->count();
	}
	/**
	 * 获取某个用户的发布
	 */
	public function getUserPub($userid){
		$map='uid='.$userid;
		return  $this->get($map);
	}
	/**
	 * 获取某个用户的热门排行
	 */
	public function getUserHot($userid,$time=0,$limitNum=10){
		$map='uid='.$userid;
		$order=$this->HotOrderRule;
		if($time)
			$map.=" AND ".time()."-ctime<=".$time;
		$list=$this->where($map)->order($order)->limit($limitNum)->select();
	 	return $list;
	}
	/**
	 * 由文章id获取文章内容
	 * @param unknown_type $id
	 */
	public function getone($id){
		$one=$this->where('id='.$id)->find();
		return $one;
	}
	/**
	 * 获取所有行
	 * @return string
	 */
	public function getAll(){
		return $this->get('','id desc');
	}
	/**
	 * 取出数据
	 */
	private  function get($map,$order='id desc'){
		/**
		 * 内容过滤 text:文字  pic：图片
		 * type=0 文字 1 图片
		 */
		$type=$_GET['type'];
		if($type=='text'){
			if($map==''){
			  $map="  type=0 ";
			}else{
			  $map=" (".$map.")  AND type=0 ";
			}
		    
		}else if($type=='pic'){
			if($map==''){
			   $map="  type=1  ";
			}else{
			   $map=" (".$map.")  AND type=1 ";
			}
		} 
		//没有删除的
		if($map)
		   $map.=' AND is_del=0 ';
		else
		   $map=' is_del=0 ';
		/** 
		 *  最新 热门
		 */
		$r=$_GET['order'];
		if($r=='new'){
			$order='id desc';			
		}else if($r=='hot'){ 
			$order=$this->HotOrderRule;
		}else if($order==''){$order='id desc';	}
		
		//导入分页类   实现分页
        import_class('iPage');
		$count= $this->where($map)->count();
		$Page = new Page($count,$this->_getPageNum());// 实例化分页类 传入总记录数和每页显示癿记录数
		$Page->ma=MODULE_NAME."/".ACTION_NAME;
		global $system_data;
		if($system_data['view']['isRewrite']){
		//伪静态重定向
			$Page->is_rewrite=1;
		}
		$show= $Page->show();// 分页显示输出
		//分页结束
		$list=$this->where($map)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
		$data['page'] =$show;
		$data['list'] =$list;
		$data['count']=$count;
		return $data;
	}
	/**
	 * 关联操作：添加评论、投票
	 * 某个字段自增1
	 */
	public function Inc1($id,$field){
		return	$this->where('id='.$id)->setInc($field,1);
	}
	/**
	 * 关联操作：删除操作
	 * 某个字段减1
	 */
	public function Dec1($id,$field){
		return	$this->where('id='.$id)->setDec($field,1);
	} 
	/**
	 *  查询上一条、下一条
	 */
	public function PreNext($tid,$cateid,$type){
		//LIMIT 不是与id主键同步的，查询出来的行数
		$dbPre=C('DB_PREFIX');
		$Model = new Model();
		if($type=='Next')
		$res=$Model->query("SELECT * FROM `".$dbPre."content` WHERE cateid={$cateid} AND id>{$tid}   ORDER BY  `id` ASC LIMIT 1 ");
		//$res=$Model->query("SELECT * FROM `".$dbPre."content` WHERE cateid={$cateid}  LIMIT {$tid},1 ");
		//$pre=$this->where('cateid='.$cateid)->order('id desc')->limit($tid.',2')->select();
		else 
			$res=$Model->query("SELECT * FROM `".$dbPre."content` WHERE cateid={$cateid} AND id<{$tid}  ORDER BY  `id` DESC LIMIT 1 ");		
		//return $res;
		return $res[0]['id'];
	}
    /**
     * 该表下执行query操作
     */
	public function iquery($sql){
		$Model =M('Content');
        return $Model->query($sql);		
	}
	/**
	 * 添加数据操作
	 * 输入：$data  
	 * 返回：tid、msg
	 */
	public function addAction($data){
		$return['tid']=0;
		$check=$this->Icheck($data);
		if(!$check['success']){
			$return['msg']=$check['msg'];
			return $return;
		}
		//内容模型  0：普通图文  2：gif 3：视频  1：普通图文中含有图片
		$data['type']=$data['content_type'];
		//处理输入的内容
		$data['content']=t($data['content']);
		//普通图文含图片
		if($data['pic']){
			//当普通文档存在图片时
			if($data['type']==0) $data['type']=1;  
			$data['type_data']=serialize($data['pic']);
		}
		//含视频
		if($data['type']==3){
			$data['type_data']=$data['video_data'];
		}		
		$res=$this->data($data)->add();		
		if($res>0){
			 $this->_addAfter(mid());
			 $return['tid']=$res;
		}else{
			 $return['tid']=0;
		}
		return $return;
	}
	/**
	 * 添加文章后的关联操作
	 *
	 */
	private function _addAfter($mid){	
		//积分操作
		D('CreditUser')->action($mid,'post');
	}
	
	/**
	 * 检查提交是否正确
	 * return:msg/success
	 * model类有check方法
	 */
	public  function Icheck($data){
		global $_G;
		$must=$_G['view']['cateMust'];
		$length=$_G['view']['length'];
		$minlength=$_G['view']['minlength'];
		$return['success']=0;
		$len=get_str_length($data['content']);
		if($len<$minlength){
			$return['msg']=L('tooshort');
		}else if($len>$length){
			$return['msg']=L('toolong');
		}else if($must>0 && $data['cateid']<=0){
			//分类必填
			$return['msg']=L('catemust');
		}else if($len<=$length){
			$return['success']=1;
		}
		return $return;
	}
	/**
	 * 搜索
	 */
	public function doSearch($keyword){
	  $map=" content LIKE '%{$keyword}%' "; 
	  $order='';
	  //高级搜索
	  $cate=$_GET['cate'];
	  $time=$_GET['time'];
	  $sort=$_GET['sort'];
	  $adv=$_GET['adv'];
	  if($adv=='yes'){
	  	if($cate!='')
	  		$map.='AND cateid='.$cate;
	  	if($time!='')
	  		$map.=' AND '.time().'-ctime<='.$time;	  	
	  	if($sort!='')
	  		$order=$sort.' desc';	  	
	  	
	  }
	  return  $this->get($map,$order);
	}
	/**
	 *  前台用户的删除操作，只能删除自己发布的文章
	 */
	public function delAction($tid){
		//只能删除自己发布的文章
		$mid=mid();
		$map=' id='.$tid.' AND uid='.$mid.' ';
		$pre=C('DB_PREFIX');
		$sql=" update ".$pre."content  set is_del=1 where ".$map;
		$res=$this->execute($sql);//返回影响列数
		if($res>0){
			$this->_delAfter($tid,$mid);//tip:操作在return前
			return 1;
		}
		return 0;
	}
	/**
	 * 删除文章后的管理操作
	 * 
	 */
	private function _delAfter($tid,$mid){
		//删除与该文章有关的评论
		//1.更改：相应评论不会被删除，发出用户仍可见，但是文章显示已经删除
		
		//删除digg记录 
		//2.不删除，只有推荐过的用户登录后才能看到被删除信息
		
		//积分操作
		D('CreditUser')->action($mid,'delpost');
	}
	/**
	 *  删除或恢复内容
	 *  @type=del/recover 
	 *  $ids=1,2,3,4 字符串，不是数组
	 */
	public function del_recover($ids,$type){  
		if(!$type) return false;
		if($ids!=''){
			$ids =explode(',',$ids);
			foreach($ids as $v){
				$map .= 'OR id=' . $v . ' ';
			}
			$map = substr($map, 2 ); // 删除多余的OR
			$pre=C('DB_PREFIX');
			if($type=='del')
			  $sql="update ".$pre."content  set is_del=1 where ".$map;
			else if($type=='recover')
			  $sql="update ".$pre."content  set is_del=0 where ".$map;
			else return false;
			$res=$this->execute($sql);//返回影响列数
			return $res;
		} else {
			return false;
		}	
	}
	/**
	 * 彻底删除
	 * $ids='1,2,3,4'; 字符串，不是数组
	 */
	public function completelyDelFromAdmin($ids,$clear=0){
		if($clear){
			$this->_delAttachment(0,1);  //取得附件信息后再删除数据库
			return $this->where(' is_del=1 ')->delete();//影响的行数
		}
		if(!empty($ids)){
			$ids =explode(',',$ids);
			foreach($ids as $v){
				$map .= 'OR id=' . $v . ' ';
			}
			$map = substr($map, 2 ); // 删除多余的OR
			$map.=' AND is_del=1 ';
			$this->_delAttachment($map,0);  //取得附件信息后再删除数据库
			$res=$this->where($map)->delete();//影响的行数
			return $res;
		} else {
			return false;
		}
	}
	/**
	 * 删除附件
	 * 只有图片内容有附件
	 * $map='id=1 OR id=2 AND is_del=1';
	 */
	public function _delAttachment($map,$clear=0){
		//清空已经删除到回收站的内容的附件
		if($clear){
			$datalist=$this->where(' is_del=1 AND type=1 ')->field('type_data')->select();
		}else if($map>''){
			$map.=" AND type=1 ";
			$datalist=$this->where($map)->field('type_data')->select();
		}else{
			exit();
		}
		foreach ($datalist as $k=>$v){
			$data=unserialize($v['type_data']);
			$file_origin=PATH_UPLOADS_PATH.'/'.$data['path'].'/'.$data['name'];
			$file_s=PATH_UPLOADS_PATH.'/'.$data['path'].'/s_'.$data['name'];
			$file_m=PATH_UPLOADS_PATH.'/'.$data['path'].'/m_'.$data['name'];
 			if(file_exists($file_origin)) unlink($file_origin);				
 			if(file_exists($file_s))	unlink($file_s);
 			if(file_exists($file_m))	unlink($file_m);
		}
	}
	/**
	 * 后台的审核操作
	 */
	public function checkFromAdmin($ids){
		$ids =explode(',',$ids);
		if(!empty($ids)){
			foreach($ids as $v){
				$map.='OR id='.$v.' ';
			}
			$map = substr($map,2); // 删除多余的OR
			$pre=C('DB_PREFIX');
			$sql="update ".$pre."content  set is_check=1 where ".$map;
			$res=M()->execute($sql);//返回影响列数
			//update时数据不改变时会返回影响行数为0，判断false
			return ($res===false)?0:1;
		} else {
			return false;
		}
	}
	/**
	 * 移动文档到新的栏目
	 */
	public function moveFromAdmin($ids,$cate){
		$ids =explode(',',$ids);
		if(!empty($ids)){
			foreach($ids as $v){
				$map.='OR id='.$v.' ';
			}
			$map = substr($map,2); // 删除多余的OR
			$pre=C('DB_PREFIX');
			$sql="update ".$pre."content  set cateid=".$cate." where ".$map;
			$res=M()->execute($sql);//返回影响列数
			//update时数据不改变时会返回影响行数为0，判断false
			return ($res===false)?0:1;
		} else {
			return false;
		}
	}	
	/**
	 * 编辑文档
	 */
	public function editText($data){
		$id=$data['tid'];
		unset($data['tid']);
		//删除图片
		if(intval($data['delpic'])>0){
			delPic($data['pic_path'],$data['pic_name']);
			$data['pic_path']='';
			$data['pic_name']='';
		}else{
			unset($data['pic_path']);
			unset($data['pic_name']);
		}
		unset($data['delpic']);
		$res=$this->where('id='.$id)->data($data)->save();
		//update时数据不改变时会返回影响行数为0，判断false
		return ($res===false)?0:1;
	}
}
?>