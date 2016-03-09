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
    protected $config =array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'首页','last'=>'末页','theme'=>'共%totalPage%页  %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
    
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
     */
    private function formatUrl($p){
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
    		return 0;
    	}
    	$url=dirname(__APP__).'/'; 
    	if($m=='Index' && ($_GET["type"]=='') && ($_GET["order"]=='') && ($this->is_rewrite) ){
    		//若开启rewrite 
    		if($a=='detail')
            $url.="detail-".$_GET['id'].'-'.$p.".html";//内容详细页
    	    else 
    	    $url.=$a."-".$p.".html";//首页内容列表
    	}else{
    	//普通模式
        $p2 = $this->varPage;
        $nowCoolPage      = ceil($this->nowPage/$this->rollPage);
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
     * rewrite or normal
     * @return string
     */
    public function show(){
    	return $this->show_rewrite();
//     	if($this->is_rewrite)
//     		return $this->show_rewrite();
//     	else 
//     		return $this->show_normal();
    }
    /**
     * 基于rewrite规则输出
     */
    public function show_rewrite(){
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
    /**
     +----------------------------------------------------------
     * 分页显示输出
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function show_normal() {
        if(0 == $this->totalRows) return '';
        $p = $this->varPage;
        $nowCoolPage      = ceil($this->nowPage/$this->rollPage);
        $url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
        $parse = parse_url($url);
        if(isset($parse['query'])) {
            parse_str($parse['query'],$params);
            unset($params[$p]);
            $url   =  $parse['path'].'?'.http_build_query($params);
        }
        //上下翻页字符串
        $upRow   = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        if ($upRow>0){
            $upPage="<a href='".$url."&".$p."=$upRow'>".$this->config['prev']."</a>";
        }else{
            $upPage="";
        }

        if ($downRow <= $this->totalPages){
            $downPage="<a href='".$url."&".$p."=$downRow'>".$this->config['next']."</a>";
        }else{
            $downPage="";
        }
        // << < > >> 下8页 上8页
        if($nowCoolPage == 1){
            $theFirst = "";
            $prePage = "";
        }else{
            $preRow =  $this->nowPage-$this->rollPage;
            $prePage = "<a href='".$url."&".$p."=$preRow' >上".$this->rollPage."页</a>";
            $theFirst = "<a href='".$url."&".$p."=1' >".$this->config['first']."</a>";
        }
        if($nowCoolPage == $this->coolPages){
            $nextPage = "";
            $theEnd="";
        }else{
            $nextRow = $this->nowPage+$this->rollPage;
            $theEndRow = $this->totalPages;
            $nextPage = "<a href='".$url."&".$p."=$nextRow' >下".$this->rollPage."页</a>";
            $theEnd = "<a href='".$url."&".$p."=$theEndRow' >".$this->config['last']."</a>";
        }
        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++){
            $page=($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage){
                if($page<=$this->totalPages){
                    $linkPage .= "&nbsp;<a href='".$url."&".$p."=$page'>&nbsp;".$page."&nbsp;</a>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1){
                    $linkPage .= "&nbsp;<span class='current'>&nbsp;".$page."&nbsp;</span>";
                }
            }
        }
        $pageStr="<div class='page'>";
        $pageStr.=str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);
        $pageStr.="</div>";
        return $pageStr;
    }

}