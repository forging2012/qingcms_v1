<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id: Page.class.php 2712 2012-02-06 10:12:49Z liu21st $

class Page {
    // 分页栏每页显示的页数
    public $rollPage = 5;
    // 页数跳转时要带的参数
    public $parameter  ;
    // 默认列表每页显示行数
    public $listRows = 20;
    // 起始行数
    public $firstRow	;
    // 分页总页面数
    protected $totalPages  ;
    // 总行数
    protected $totalRows  ;
    // 当前页数
    protected $nowPage    ;
    // 分页的栏的总页数
    protected $coolPages   ;
    // 分页显示定制
    //protected $config =array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页','theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
    protected $config =array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'首页','last'=>'末页','theme'=>'<a class="total">共%totalPage%页 </a> %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
    
    // 默认分页变量名
    protected $varPage;
    //传入m和a,当前所在的module和操作
    public $ma;
    //是否rewrite
    public $is_rewrite=0;

    /**
     +----------------------------------------------------------
     * 架构函数
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $totalRows  总的记录数
     * @param array $listRows  每页显示记录数
     * @param array $parameter  分页跳转的参数
     +----------------------------------------------------------
     */
    public function __construct($totalRows,$listRows='',$parameter='') {
        $this->totalRows = $totalRows;//总得页数
        $this->parameter = $parameter;//带上的参数
        $this->varPage = C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;//默认的p=1
        if(!empty($listRows)) { // 默认列表每页显示行数
            $this->listRows = intval($listRows);
        }
        $this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
        $this->coolPages  = ceil($this->totalPages/$this->rollPage);    //总页数--分页栏每页显示的页数
        $this->nowPage  = !empty($_GET[$this->varPage])?intval($_GET[$this->varPage]):1;//当前所在的页数
        
        if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
            $this->nowPage = $this->totalPages; //当输入页数超过总页数时，跳到最后一页
        }
        $this->firstRow = $this->listRows*($this->nowPage-1);//起始行数，每页的起始
    }

    public function setConfig($name,$value) {
        if(isset($this->config[$name])) {
            $this->config[$name]    =   $value;
        }
    }
    /**
     * url的处理
     * rewrite or normal的不同处理
     */
    private function formatUrl($p){
    	$how='normal';
        //rewrite
    	if($this->is_rewrite){
    		$how='rewrite';
    		$url=PATH_PATH.'/';
    		//处理m、a
    		$path=$this->ma;
    		if($path){
    			$depr='/';
    			if(0===strpos($path,$depr)){ // 定义路由
    				$m=$path;
    				$a='index';
    			}else {
    				// 解析模块和操作 m和a
    				$path = trim($path,$depr);
    				$path = explode($depr,$path);
    				$m=$path[0];
    				$a=$path[1];
    			}
    		}else{
    			return '';
    		}
    		/**
    		 * 满足以下条件的 Index/? Index/detail Space/index 则rewrite 
    		 */
    	  //内容
    	  if($m=='Index' && ($_GET["type"]=='') && ($_GET["order"]=='')  ){
    		if($a=='detail')
            $url.="detail-".$_GET['id'].'-'.$p.".html";//内容详细页
    	    else 
    	    $url.=$a."-".$p.".html";//首页内容列表
    	  }elseif($m=="Space" && $a=="index"){
    	  	//个人空间
    	  	$url.="space-uid-".$_GET['uid']."-".$p.".html";//首页内容列表
    	  }else{
    	  	//其他使用normal
    	  	$how='normal';
    	  }
    	}
    	if($how=='normal'){
    	//普通模式
        $p2 = $this->varPage;
        $nowCoolPage= ceil($this->nowPage/$this->rollPage);
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$p2]);
            $url   =  $parse['path'].'?'.http_build_query($params);
         }
         $url.='&'.$p2.'='.$p;
    	}
    	return $url;
    }
    /**
     * 输出分页html
     */
    public function show(){
    	if(0 == $this->totalRows) return '';
    	//上下翻页字符串
    	$up=$this->nowPage-1;
    	$down=$this->nowPage+1;
    	if ($up>0){
    		$upPage="<a href='".$this->formatUrl($up)."'>".$this->config['prev']."</a>";
    	}else{
    		$upPage="";
    	}
    	
    	if ($down<= $this->totalPages){
    		$downPage="<a href='".$this->formatUrl($down)."'>".$this->config['next']."</a>";
    	}else{
    		$downPage="";
    	}
    	
    	$nowCoolPage= ceil($this->nowPage/$this->rollPage);//在第几个分栏
    	// << < > >> 下8页 上8页
    	if($nowCoolPage == 1){ //第一栏
    		$theFirst = "";
    		$prePage = "";
    	}else{
    		$preRow =  $this->nowPage-$this->rollPage;
    		$prePage = "<a href='".$this->formatUrl($preRow)."' >上".$this->rollPage."页</a>"; //上8页
    		$theFirst = "<a href='".$this->formatUrl(1)."'>".$this->config['first']."</a>"; //第一页
    	}
    	if($nowCoolPage == $this->coolPages){//最后一栏
    		$nextPage = "";
    		$theEnd="";
    	}else{
    		$nextRow = $this->nowPage+$this->rollPage;
    		$theEndRow = $this->totalPages;
    		$nextPage = "<a href='".$this->formatUrl($nextRow)."' >下".$this->rollPage."页</a>";
    		$theEnd = "<a href='".$this->formatUrl($theEndRow)."' >".$this->config['last']."</a>";
    	}
    	 
    	//每页的链接 1 2 3 4 5
    	$linkPage = "";
    	for($i=1;$i<=$this->rollPage;$i++){
    		$page=($nowCoolPage-1)*$this->rollPage+$i;
    		if($page!=$this->nowPage){
    			if($page<=$this->totalPages){
    				$linkPage .= "<a href='".$this->formatUrl($page)."'>".$page."</a>";
    			}else{
    				break;
    			}
    		}else{
    			if($this->totalPages != 1){
    				$linkPage .= "<span class='current'>".$page."</span>";
    			}
    		}
    	}
    	//生成html
    	$pageStr="<div class='page'>";
    	$pageStr.=str_replace(
    			array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
    			array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),
    			$this->config['theme']);
    	$pageStr.="</div>";
    	return $pageStr;
    }
  
}