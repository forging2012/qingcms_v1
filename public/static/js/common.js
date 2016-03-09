
//创建对象
var dos=window.dos ||{
   switchPic:function(obj,path,name,action){
	   if(action=='close'){
		   $(obj).parent('.pic_big').prev('.pic').show();
		   $(obj).parent('.pic_big').hide();
		   return false;
	   }  	   
	    var bigImg=$(obj).parent('.pic').next('.pic_big').children('img');
	    
	   if(bigImg.attr('src')==''){
		 //  alert(111);  //判断是否重载图片
	     bigImg.attr('src',PIC+'/'+path+'/m_'+name); 
	//   bigImg.attr('src','http://img1.gtimg.com/news/pics/hv1/189/80/1071/69662364.jpg'); 
	   }	    
	    $(obj).parent('.pic').next('.pic_big').show();
		$(obj).parent('.pic').hide();			
  }	
};

/**
 * 投票操作
 */
var vote=window.vote ||{
	/**
	 * add:投票
	 */
	add:function(id,type,num,uid){	
		//test1();
//		test02();
//		ui.test();
//		return;
//		test.test1();
//		return ;
		if( !(vote.check(uid)) ){ return false;}
		$.getJSON(APP+'/Public/vote/',{id:id,type:type},function(txt){	
			if(txt['success']>0){			
				vote.success(id,type,num);
			}else{
				ui.error(txt['msg']);
			}
		});
	},
	/**
	 * 投票前 前台检测
	 */
	check:function(uid){
		//未登录
		if(MID<=0){
			ui.error('请先登录');
			return false;
		}
		//不能给自己投票
		if(MID==uid){
			ui.error('不能给自己投票');
			return false;
		}		
        return true;
	},
	/**
	 * 成功后操作
	 */
	success:function(id,type,num){

		$('#down_'+id).removeAttr('onclick').addClass('isvoted');
		$('#up_'+id).removeAttr('onclick').addClass('isvoted');
	   if(type==1){
		   $('#up_'+id).append("<span id='addvote' style='position: absolute;z-index:1000;top:-10px;left:40px;font-size:12px;'>+1</span>");
		   $("#addvote").animate({fontSize:"30px"},'fast').fadeOut('fast',function(){$('#up_'+id).html(num+1).attr('class','isvoted barbox uped');});		
	    }else{
	       $('#down_'+id).append("<span id='addvote' style='position: absolute;z-index:1000;top:-10px;left:40px;font-size:12px;'>+1</span>");
		   $("#addvote").animate({fontSize:"30px"},'fast').fadeOut('fast',function(){$('#down_'+id).html('-'+(num+1)).attr('class','isvoted barbox downed');});	
	  }	
	   
	}
	
};



/**
 * 用户消息处理
 */
var message=window.message ||{
    show:function(){
		$.getJSON(APP+'/Public/getMsg',function(txt){
		        var list = {		    		      
                             comment:{url:APP+"/User/comment",name:"条新评论"},
                             notify:{url:APP+"/User/message",name:"条新通知"}
                             };
			if(txt.total!="0"){
			    var html2='';
			    for(var one in list){
			        if(txt[one] != undefined && parseInt(txt[one]) >0){
			            html2+="<li><a href='"+list[one].url+"'>"+txt[one]+list[one].name+"</a></li>";
			        }
			    }
			    $(".message_list").html(html2);
			    $('.message_bar').fadeIn();
			}else{$('.message_bar').hide();}

		});
	},
	close:function(obj){
		$('.message_bar').fadeOut();
	}
	
	
};


/**
 * 评论处理
 */
var comment=window.comment ||{
	/**
	 * 检测长度的结果
	 * 0:过短
	 * 1：合法
	 * 2：超长
	 */
	checkLenRes:1,
	/**
	 * 删除评论
	 * @param txt
	 */
	del:function(id,tid){
	    $.post(APP+'/Public/delComment',{id:id,tid:tid},function(txt){
	    	if(txt>0){
	    		$('.comment_li_'+id).slideUp();
//	    		ui.success('删除评论成功');
//	    		setTimeout('location.reload()',600);
	    		//location.reload();//刷新页面
	    	}else{ui.error('删除失败');}
	    });
	},
	/**
	 * 显示评论
	 * @param id:文章id
	 * @param uid:用户id
	 * @param comment:评论数量
	 */
	show:function(id,uid,comment){
		    //|| comment=='' 评论数可能为0
		    if(id=='' || uid=='' ){ui.error('数据错误');return false;}
            var obj = $("#comment_list_"+id);
            //当还没有请求过内容时
            if(obj.html()==''){ 
     		    //先关闭其他评论框  遍历对象
            	$('.comment_list').each(function(){
                 	if( $(this).html()!='' ){
                 		$(this).slideUp('fast').html('');
                	  }           		
            	});
            	//显示
                obj.before('<div class="loading"></div>');
               // return false;
                $.post(APP+'/Public/comment',{tid:id,uid:uid,comment:comment},function(txt){   
                	if(txt){
                    	obj.html(txt);
                    	obj.slideDown('normal',function(){$('.loading').slideUp(function(){$(this).remove();});});
                       //  obj.show('normal',function(){$('.loading').slideUp(function(){$(this).remove();});});  	 
                	}else{
                		$('.loading').slideUp();
                		ui.error('获取评论失败');
                	}
                });	            	            
            }else{
            	obj.slideUp('normal',function(){obj.html('');});
            }   
	},
	/**
	 * ajax 添加评论
	 * id:
	 * type:detail/ajax
	 */
	add:function(id,type){
		if( !(comment.check()) ){ return false;}
		$('.comment_submit_'+id).val('发表中...');
		var options = {
				dataType:'json',
			    success: function(txt) {
			    	if(txt['success']){
						if(type=='detail'){
							ui.success('发布成功');
							setTimeout('location.reload()',600);//刷新页面	
						}else if(type='ajax'){
                            comment.reload(id);
						}
					}else{
						ui.error(txt['msg']);
						$('.comment_submit_'+id).val('发表');
					}
			    	
			    }
		};		
		$('#commentForm').ajaxSubmit(options);		
	},
	/**
	 * 发表前检测
	 */
	check:function(){
		//未登录
		if(MID<=0){
			ui.error('请先登录');
			return false;
		}
		//输入为空
		if($('.comment_ta').val()==''){
			ui.error('请输入内容');
			return false;
		} 
		if(comment.checkLenRes==0){ui.error('输入内容过短');return false; }	
		if(comment.checkLenRes==2){ui.error('输入内容过长');return false; }	 		
		return true;
	},
	/**
	 * 检测长度
	 */
	checkLen:function(){
		 var obj=$('#comment_ta');
		 obj.keyup(function(){
			  var len=getLength(obj.val());
			  if(len<minComLen){
				   comment.checkLenRes=0;//过短
				   $('.comLen_span').html('至少还需要输入<strong style="color:blue;" id="comLen">'+(minComLen-len)+'</strong>字');			   
			  }else if(len<=comLen){
				  comment.checkLenRes=1;//合法
				  $('.comLen_span').html('你还可以输入<strong  id="comLen">'+(comLen-len)+'</strong>字');
			   }else if(len>comLen){
				  comment.checkLenRes=2;//超长
				  $('.comLen_span').html('已超过<strong style="color:red;" id="comLen">'+(len-comLen)+'</strong>字');
			   }
		  });
			 
	},
	/**
	 * 重载评论页面
	 * 
	 */
	reload:function(tid){
		//提交成功后，comment评论数加1
		var obj=$('#comment_'+tid);
		var uid=obj.attr('tuid');
		var comment=obj.attr('tcomment');
		    comment=parseInt(comment)+1;
		    obj.attr('tcomment',comment);
		    obj.html(comment);
	    //|| comment=='' 评论数可能为0
	    if(tid=='' || uid=='' ){ui.error('数据错误');return false;}
        var obj = $("#comment_list_"+tid);
            $.post(APP+'/Public/comment',{tid:tid,uid:uid,comment:comment},function(txt){   
            	if(txt){
                	obj.html(txt);
             	}
            });	            	             
     },
     /**
      * 回复评论
      */
     reply:function(id,type){
    		//未登录
 		if(MID<=0){
 			ui.error('请先登录');
 			return false;
 		}
 		var type='noload';
 		var data=new Array();
 		    data[0]=id;
 		    data[1]=type;
        ui.load('回复评论',APP+'/Public/replycomment',data);
     },
     /**
      * 回复评论 插入数据
      */
     doreply:function(type){
    	 var r_reply_comment_id=$('input[name="r_reply_comment_id"]').val();
    	 var r_content=$('textarea[name="r_content"]').val();
    	 var url=$('#repley_comment_form').attr('action');
    	 $.post(url,{r_reply_comment_id:r_reply_comment_id,r_content:r_content},function(txt){
    		 //document.write(txt);//{"success":0,"msg":"\u5185\u5bb9\u4e3a\u7a7a"}
    		 txt = eval("(" + txt + ")");//txt=eval('success':0,'msg':'fddfdf');
    		 if(txt['success']>0){
    			 ui.success('回复成功');
    			 $('.ui_load').remove();   							
    		 }else{
    			 ui.error(txt['msg']);
    		 }
    		 
    	 });	 
     },
     /**
      * 初始化操作
      */
     init:function(){
    	comment.checkLen();
        $('.comment_block').mouseover(function(){   	   
     	    $(this).find('.report').show();
        });
        $('.comment_block').mouseout(function(){   	   
     	    $(this).find('.report').hide();
        });
     },
     /**
      * 举报评论
      */
     report:function(id){
 		//未登录
  		if(MID<=0){
  			ui.error('请先登录');
  			return false;
  		}
    	 var data=id;
    	 ui.load('确定举报此评论？',APP+'/Public/report',data);
      },
     doreport:function(obj){
    	 var str=$(obj).serialize();
    	 var action=$(obj).attr('action');
    	 $.post(action,{data:str},function(txt){
    		  if(txt>0){
    			  ui.load_close();
    			  ui.success('发送成功');
    		  }else{ui.error('发送失败'); }
    	 });
     }

};

/**
 * 发布文章操作
 */

var publish=window.publish || {
	/**
	 * 检测长度的结果
	 * 0:过短
	 * 1：合法
	 * 2：超长
	 */
	checkLenRes:1,
	/**
	 * 提交操作
	 */
	ajaxSubmit:function(){
		var options = {
				dataType:'json',
				// dataType:'script',
				type:'POST',
			    success: function(txt) {
			    	if(txt['success']>0){
						ui.success('恭喜，发布成功');
						//跳转到刚发布的文章预览
						setTimeout('reurl('+txt['tid']+')',500);	
			    	}else{
						ui.error(txt['msg']);
						publish.reable();
			    	}			   	
			    }
		};	
		//执行检测
		if( !(publish.check()) ) return false;
		//视图操作
		$('input[type="submit"]').val('发表中...').attr('disabled','disabled');
		$('.Pub').append('<div class="loading"></div>');
		//进行提交
		$('#pubForm').ajaxSubmit(options);
		return false;
	},
	/**
	 * 恢复
	 */
	reable:function(){
		$('input,select,textarea').removeAttr('disabled');
		$('input[type="submit"]').val('发表');
		$('.loading').remove();
	},
	/**
	 * 添加前检测内容
	 */
	check:function(){
	   if($('.pub_area').val()==''){ui.error('内容不能为空');return false;}
	   if(publish.checkLenRes==0){ui.error('输入内容过短');return false; }	
	   if(publish.checkLenRes==2){ui.error('输入内容过长');return false; }	 
	   if($('select').val()==0 && cateMust==1){ui.error('分类必填');return false;}
	   return true;
	},
	/**
	 * 自动检测长度
	 */
	checkLen:function(){
	  var obj=$('.pub_area');
	  obj.keyup(function(){
		   var len=getLength(obj.val());
		   if(len<minlength){
			   publish.checkLenRes=0;//过短
			   $('.length_span').html('至少还需要输入<strong style="color:blue;" id="length">'+(minlength-len)+'</strong>字');			   
		   }else if(len<=length){
			   publish.checkLenRes=1;//合法
			  $('.length_span').html('你还可以输入<strong  id="length">'+(length-len)+'</strong>字');
		   }else if(len>length){
			  publish.checkLenRes=2;//超长
			  $('.length_span').html('已超过<strong style="color:red;" id="length">'+(len-length)+'</strong>字');
		   }
	  });
		 
	}
};
/**
 * 发布页面的标签检测
 */
var tag=window.tag ||{
    check:function(){ 
    	$("#tag_input").change(function(){
    		var t=$(this).val();
    		t=tag.dt(t);
    		$.post(APP+"/Pub/ajaxCheckTag",{'tag':t},function(txt){
    			$('.tag_pre_list').html(txt);
    		});
    	});		
    },
    dt:function(tag){
    	var arrTag= tag.split(',');
    	var newTag = '';
    	for(key in arrTag){
    		//去掉单个标签首尾的空格    ---， ---，  最后会多出一个，由slice过滤
    		newTag = newTag+arrTag[key].replace(/(^\s*)|(\s*$)/g,"") + ',';
    	}
    	var r=newTag.slice(0,-1);
    	return r;
    }
};

/**
 * 重定向函数
 * @param tid
 */
function reurl(tid){
	window.location.href=APP+"/Index/detail/id/"+tid;	
}

/**
 * 个人中心的 loadmore
 */
var loadmore={
	check:function(){
        var bodyTop = document.documentElement.scrollTop + document.body.scrollTop;
        //滚动到底部时出发函数
        //滚动的当前位置+窗口的高度 >= 整个body的高度
        if(bodyTop+$(window).height() >= $(document.body).height()){
            loadmore.load();
        }
	},
	load:function(){
		  var obj=$('#loadMore');
		  var lastId=obj.attr('lastId');
		  obj.html('加载中...');	
          $.post(APP+'/User/loadmore',{lastId:lastId},function(txt2){
        	  var txt=eval("("+txt2+")");
        	  if(txt['success']>0){
        	      $('.doingBox').append(txt['list']);
           	      obj.attr('lastId',txt['lastId']);
        	      obj.html('<span class="ico_loadmore"></span>更多');	
        	  }else{
        		  obj.html('没有更多内容了...'); 
        	  }
          });
	},
	init:function(){
		$(document).ready(function(){
			$(window).bind('scroll resize', function(e){
				loadmore.check();
			});
		});
	}
};
/**
 * 地区操作
 */
var area={
	show:function(){
		$.post(APP+'/Public/showArea',function(txt){
			var obj=$("#editarea");
			$(obj).html('取消');
			$(obj).attr('onclick','area.cancel();');
			$('.location').html(txt).slideDown();	
		});		
	},
	init:function(){
		$(document).ready(function(){
			$('#area_province').change(function(){
				var province=$('#area_province').val();
				$('#area_city').remove();
				$.post(APP+'/Public/getCity',{province:province},function(txt){
					$('#area_province').after(txt);
				});	
				
			});
		});
	},
	cancel:function(){
		var obj=$("#editarea");
		$('.location').slideUp(function(){$(this).html('');});	
		$(obj).html('修改');
		$(obj).attr('onclick','area.show();');
	}
};

/**
 * 帐号设置
 */
var account={
	init:function(){
		$(document).ready(function(){
			//切换分类监听
			$('.account h3').click(function(){	
				var obj=$(this);
				var m_box=obj.next('.m-box');
				var dis=obj.attr('class');
				if(dis=='' || dis==undefined){
					//已经隐藏  class=''
					obj.addClass('hover');
					m_box.slideDown();
				}else{
					//正显示 class='hover'				
					m_box.slideUp(function(){ obj.removeClass('hover');  });
				}
				
			});
			//表单提交监听
			$('form').submit(function(){
				var str=$(this).serialize();
				var action=$(this).attr('action');
                $.post(action,{data:str},function(txt){
//                	document.write(txt);
//                	alert(txt);
//                	return false;
                	
                	 txt=eval("("+txt+")");
                	 if(txt['success']){
                		 ui.success(txt['msg']);
                	 }else{
                		 ui.error(txt['msg']);
                	 }
                });
				return false;
			});
			//监听表单域
			$('input').focus(function(){
			      $(this).addClass("text_focus");
			 }).blur(function(){
		          $(this).removeClass("text_focus");
	        });
			
		});
		
	}	
};

/**
 * 删除文章
 */
function delText(id){
	 $.post(U('Ajax/delText'),{id:id},function(txt){
		 //  alert(txt); return false;
		  if(txt>0){
           $('.text_list_'+id).slideUp(function(){$(this).next('.shadow').hide(); ui.success('删除成功'); });
		  }else{ui.error('删除失败'); }
	 });
}

/**
 * 关注操作
 */
var follow={
	/**
	 * 关注
	 */	
	add:function(fid,obj){
		$.post(U('Ajax/addfollow'),{fid:fid},function(txt){
			 if(txt>0){
				  follow.reload(fid,obj);
			 }else{
				 ui.error('关注失败');
			 }
	
		});
	},
	/**
	 * 取消操作
	 * 
	 */
	cancel:function(fid,obj){
		$.post(U('Ajax/cancelFollow'),{fid:fid},function(txt){
			 if(txt>0){
				 follow.reload(fid,obj);
			 }else{
				 ui.error('取消失败');
			 }
	
		});		
	},
	/**
	 * 重新取得关系
	 */
	reload:function(fid,obj){
		$.post(U('Ajax/getRelation'),{fid:fid},function(txt){
           $(obj).parent('.followBox').html(txt);	
		});		
	}
};
/**
 * 关注分组
 */
var group={
	/**
	 * 删除
	 */
	del:function(gid){
		//if(confirm("确认删除？该分组下的关注会被移到未分组..."))
			 $.post(U('Ajax/delGroup'),{gid:gid},function(txt){
				 if(txt>0){
					 window.location.href=U('Space/following');
				 }
			 });
	},
	html:function(action,value){
		 if(action==undefined) action="group.doadd();";
		 if(value==undefined) value="";
		 var html="<div class='addGroup'>";
	     html+="<div>";
	     html+="名称：<input name='name' type='text' value='"+value+"' class='text' id='addGroupInput'/>";
	     html+="</div>";
	     html+="<div class='b'>";
	     html+="<input type='button' class='btn' value='确定' onclick='"+action+"'/>";
	     html+="<input type='button' class='btn_w' value='取消' onclick='ui.load_close();'/>";
	     html+="</div>";
	     html+="</div>";
	     return html;
	},
	/**
	 * 添加
	 */
	add:function(){
		 var html=group.html();
		 ui.load('添加分组','','',html);
	},
	doadd:function(){
	  var name=$('#addGroupInput').val();
		 $.post(U('Ajax/addGroup'),{name:name},function(txt){
			 if(txt){
				 window.location.href=U('Space/following')+'&gid='+txt;
			 }
		 });	  
	},
	/**
	 * 编辑改组
	 */
	edit:function(gid){
		var name=$('.groupid_'+gid).html();
		var html=group.html('group.doedit('+gid+');',name);
		//alert(html);
		ui.load('修改分组','','',html);
	},
	doedit:function(gid){
		 var name=$('#addGroupInput').val();
		 $.post(U('Ajax/editGroup'),{name:name,gid:gid},function(txt){
			 if(txt){
				 window.location.href=U('Space/following');
			 }
		 });	
	},
	/**
	 *设置分组 
	 */
	set:function(fid){
		 ui.load('修改分组',U('Ajax/setGroup'),fid);
	},
	doset:function(obj){
   	 var str=$(obj).serialize();
	 var action=$(obj).attr('action');
	 $.post(action,{data:str},function(txt){
          if(txt){
        	  ui.load_close();
        	  ui.success('更新成功');
          }else{
        	  ui.error('更新失败');
          }
	 });
 }		
};



/**
 * 语言
 */
function L(){
//不懂写
}

/**
 * Url转换,暂时只适用前台，index.php,只能传入action和module
 * U('Account/profile')
 * index.php?a=profile&m=Account
 */
function U(url){
	url = url.split('/');
	//module
	if(url[0]=='') return false;
	//action
	if(url[1]==undefined || url[1]=='') url[1]='index';
	url= APP+'?a='+url[1]+'&m='+url[0];
	return url;
}

/**
 * 字符串长度-中文和全角符号为1，英文、数字和半角为0.5
 */
var getLength = function(str, shortUrl) {
	if (true == shortUrl) {
		// 一个URL当作十个字长度计算
		return Math.ceil(str.replace(/((news|telnet|nttp|file|http|ftp|https):\/\/){1}(([-A-Za-z0-9]+(\.[-A-Za-z0-9]+)*(\.[-A-Za-z]{2,5}))|([0-9]{1,3}(\.[0-9]{1,3}){3}))(:[0-9]*)?(\/[-A-Za-z0-9_\$\.\+\!\*\(\),;:@&=\?\/~\#\%]*)*/ig, 'xxxxxxxxxxxxxxxxxxxx')
							.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	} else {
		//Math.ceil 方法可对一个数进行上舍入。
		return Math.ceil(str.replace(/^\s+|\s+$/ig,'').replace(/[^\x00-\xff]/ig,'xx').length/2);
	}
};



