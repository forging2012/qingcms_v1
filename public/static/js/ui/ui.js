
var ui={
   success:function(message,error){
		var s_e_class= (error==1)?"ui_success_box ui_error_box":"ui_success_box";
		var html="<div id='ui_box' class='"+s_e_class+"'><div id='ui_messagecontent'>"+message+"</div></div>";
		var show=function(){	
			
		var bg='<div class="box_bg" style="width: 100%; height:'+jQuery(document).height()+'px; z-index: 998;filter: alpha(opacity=35);-moz-opacity: 0.35;opacity: 0.35;"></div>';		
		$('body').append(html); //添加提示框到页面
		$('body').append(bg);	//添加透明背景到页面
			//给提示框定义和样式
	 	var v =  ui._viewport() ;
			$( '#ui_box' ).css({
		    'left':( v.left + v.width/2  - $( '#ui_box' ).outerWidth()/2 ) + "px",
			'top':(  v.top  + v.height/2 - $( '#ui_box' ).outerHeight()/2 ) + "px"
					});		 
		};
		var close=function(){
			setTimeout( function(){  
				$( '#ui_box' ).fadeOut("fast",function(){
					jQuery('.box_bg').remove(); 
					jQuery('#ui_box').remove(); 
				});
			} , 1000);
			
		};
		show();
		close();
		
	},
	/**
	 * 错误的弹出框
	 */
	error:function(message){
		ui.success(message,1);
	},
	/**
	 * 确认 弹出框
	 */
	confirm:function(obj,text){
		var callback=$(obj).attr('callback');
		text = text || '确定执行此操作？';
		this.html = '<div id="qc_ui_confirm" class="ui_confirm">'+
			'<a class="del" href="javascript:void(0)" onclick="$(\'#qc_ui_confirm\').remove()"></a>'+
			'<div class="txt"></div>'+
			'<div class="button"><input type="button" value="确定"  class="btn"><input type="button" value="取消"  class="btn_w"></div>'+
			'</div>';

		$('body').append(this.html);
		var position = $(obj).offset();	
		$('#qc_ui_confirm').css({"top":position.top+"px","left":position.left-($("#qc_ui_confirm").width()/2)+"px","display":"none"});
		$("#qc_ui_confirm .txt").html(text);
		$('#qc_ui_confirm').fadeIn("fast");
		
		$("#qc_ui_confirm .btn_w").one('click',function(){
			$('#qc_ui_confirm').fadeOut("fast");
			$('#qc_ui_confirm').remove();
		});
		$("#qc_ui_confirm .btn").one('click',function(){
			$('#qc_ui_confirm').fadeOut("fast");
			$('#qc_ui_confirm').remove();
			eval(callback);//执行callback内的操作
		});
	},
	load:function(title,url,data,inputhtml){
		$('.ui_load').remove();
		var loading='<div class="ui_loading"></div>';
	    var	html = '<div  class="ui_load">'+
			'<div class="title"><h3>'+title+'</h3><a class="del" href="javascript:void(0)" onclick="$(\'.ui_load\').remove()"></a></div>'+
			'<div class="ui_load_content">'+loading+'</div>'+
			'</div>';

		$('body').append(html);
	 	var v =ui._viewport();
	 	var objBox=$('.ui_load');
  	 	objBox.css({'left':( v.left + v.width/2  - objBox.outerWidth()/2 ) + "px",'top':(  v.top  + v.height/2 - objBox.outerHeight()/2 ) + "px"});		
  	 	//数据在客户端
  	 	if(inputhtml>0 || inputhtml>''){
  			$('.ui_load_content').html(inputhtml);
  		 	objBox.css({
  		 	    'left':( v.left + v.width/2  - objBox.outerWidth()/2 ) + "px",
  		 		'top':(  v.top  + v.height/2 - objBox.outerHeight()/2 ) + "px" //不能有逗号
  		 		});			
  	 	}else{
  	 		//通过ajax请求数据
  		    $.post(url,{data:data},function(txt){
  			
  			$('.ui_load_content').html(txt);
  		 	objBox.css({
  		 	    'left':( v.left + v.width/2  - objBox.outerWidth()/2 ) + "px",
  		 		'top':(  v.top  + v.height/2 - objBox.outerHeight()/2 ) + "px"
  		 		});	
  		    });
  	 	}
	},
	/**
	 * 关闭load窗口
	 */
	load_close:function(){
		$('.ui_load').remove();
	},
	_viewport: function() {
	    var d = document.documentElement, b = document.body, w = window;
	    return jQuery.extend(
	        jQuery.browser.msie ?
	            { left: b.scrollLeft || d.scrollLeft, top: b.scrollTop || d.scrollTop } :
	            { left: w.pageXOffset, top: w.pageYOffset },
	        !ui._u(w.innerWidth) ?
	            { width: w.innerWidth, height: w.innerHeight } :
	            (!ui._u(d) && !ui._u(d.clientWidth) && d.clientWidth != 0 ?
	                { width: d.clientWidth, height: d.clientHeight } :
	                { width: b.clientWidth, height: b.clientHeight }) );
	},
	_u: function() {
	    for (var i = 0; i < arguments.length; i++)
	        if (typeof arguments[i] != 'undefined') return false;
	    return true;
	},
	/**
	 * 刷新界面
	 */
	reload:function(time){
		//延迟600秒后刷新
		if(time=='' || time==undefined ) time=600;
		setTimeout('location.reload()',time);//刷新页面	
	}
};