/**
 * 注意：1.代码只运行一次的问题，要不断获取某值，需要进行监听
 *     2.不在监听器里面的代码只运行一次
 *     3.监听器里不能返回值$(window).bind('scroll resize', function(e){ return 1;}); return值不能被获取，如果代码运行时监听器不工作，就没有值返回
 */
function ScrollStop(){
			var where=$('.main_right');
			var topMargin=where.offset().top;//main-right 距窗口顶部的距离
			var obj=$('.stopScrollHere');
			var position=obj.offset();//需要停止的块在文档中的位置	
			var y=position.top;   
			var x=position.left;
			var winTemp=$(window).width();//当前窗口大小
			$(window).bind('scroll resize', function(){
				//当窗口大小变化时，改变左边的距离
				var win2=$(window).width(); //窗口大小改变后当前窗口大小
				var win1=winTemp;
				if(win2!=win1){
					winTemp=win2;
					x=x+(win2-win1)/2; 
				}
                //滚动条位置
				var top=$(window).scrollTop(); //获取当前滚动条高度  ，监听，top大小不断变化的
				if(top>y){	 
					where.css({'position':'fixed','top':'-'+(y-topMargin)+'px','left':x+'px'});
				}else{
					where.css({'position':'static'});
				}
			});				
}
