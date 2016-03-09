var pub={};
jQuery.extend(pub, {
	video:function(element, options){
	   
	    
	}
});


jQuery.extend(pub.video, {
    type:'新浪播客、优酷网、土豆网、酷6网、搜狐',
	html:function(){
	    var html=
	    '<div id="video_input">'+
	    '<div><input name="publish_type_data" type="text" style="width: 320px;margin-right:10px;" class="text" value="" />'+
	    '<input type="button" class="btn_2" style="padding:4px 10px;" onclick="pub.video.add_video()" value="添加"></div>'+
	    '<div>请输入视频播放页链接：支持'+pub.video.type+'网站 </div></div>'+
	    '<input type=\'hidden\' name=\'video_data\' value="">'+
	    '<div style="display:none"  id="video_add_complete">添加完成</div>';
	    return html;

	},
	init:function(obj){
	  $(obj).html(this.html());
	},
	add_video:function(){
		var video_url = $("input[name='publish_type_data']").val();
		if(!video_url){ui.error('请输入视频播放地址');return false;}
		$.post(APP+"/Pub/parse_videoUrl",{url:video_url},function(txt){
			txt = eval('('+txt+')');
			if(txt.boolen){
				$('#video_input').hide();
				$('#video_add_complete').show();
				//把视频地址注入
				$("input[name='video_data']").val(txt.info);
				$("textarea[name=content]").val( $("textarea[name=content]").val( ) + ' ' + txt.content + ' ').keyup();//触发事件
			}else{
				ui.error("只支持"+pub.video.type);
			}
		});
	}	
});


function switchVideo(obj,host,flashvar,type){
//var box="<div class='show_box'>" +
//		"<div class='show_nav'><a>收起</a> | </a> </div>"+
//		"<div class='show_obj'>"+showFlash(host,flashvar)+
//        "</div></div>";	
if(type=='close'){
	$(obj).parents(".video_show").hide();
	$(obj).parents(".video_show").prev(".video_pic").show();
}else{
	$(obj).parents(".video_pic").hide();
	var show=$(obj).parents(".video_pic").next(".video_show");
	    show.children(".show_box").children(".show_obj").html(showFlash(host,flashvar));
	    show.show();
}
}

//显示视频
function showFlash( host, flashvar) {
	var flashAddr = {
		'youku.com' : 'http://player.youku.com/player.php/sid/FLASHVAR/v.swf',
		'ku6.com' : 'http://player.ku6.com/refer/FLASHVAR/v.swf',
		//'sina.com.cn' : 'http://vhead.blog.sina.com.cn/player/outer_player.swf?vid=FLASHVAR',
		'sina.com.cn' : 'http://you.video.sina.com.cn/api/sinawebApi/outplayrefer.php/vid=FLASHVAR/s.swf',
		//'tudou.com' : 'http://www.tudou.com/v/FLASHVAR',
		'tudou.com' : 'http://www.tudou.com/v/FLASHVAR/&autoPlay=true/v.swf',
		'youtube.com' : 'http://www.youtube.com/v/FLASHVAR',
		'5show.com' : 'http://www.5show.com/swf/5show_player.swf?flv_id=FLASHVAR',
		//'sohu.com' : 'http://v.blog.sohu.com/fo/v4/FLASHVAR',
		'sohu.com' : 'http://share.vrs.sohu.com/FLASHVAR/v.swf',
		'mofile.com' : 'http://tv.mofile.com/cn/xplayer.swf?v=FLASHVAR',
		'music' : 'FLASHVAR',
		'flash' : 'FLASHVAR'
	};
	var videoFlash = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="430" height="400">'
        + '<param value="transparent" name="wmode"/>'
		+ '<param value="FLASHADDR" name="movie" />'
		+ '<embed src="FLASHADDR" wmode="transparent" allowfullscreen="true" type="application/x-shockwave-flash" width="430" height="400"></embed>'
		+ '</object>';
	var flashHtml = videoFlash;
     //encodeURI() 函数可把字符串作为 URI 进行编码。
	flashvar = encodeURI(flashvar);
	if(flashAddr[host]) {
		var flash = flashAddr[host].replace('FLASHVAR', flashvar);
		flashHtml = flashHtml.replace(/FLASHADDR/g, flash);
	}

	return flashHtml;
}
