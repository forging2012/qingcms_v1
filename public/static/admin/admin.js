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
 * 选择全部
 */
 function selectAll(){
	$(':checkbox').attr('checked','checked');
}
 function selectNo(){
	$(':checkbox').removeAttr('checked');
}
//获取已选择用户的ID数组
 function getChecked() {
     var ids = new Array();
     $.each($('table input:checked'), function(i, n){
         ids.push( $(n).val() );//像数组末尾添加数据
     });
     return ids;//数组
 }

/**
 * 删除
 */
 function completelyDel(ids){
	var length;
    if(ids) {
        length = 1;         
    }else {
        ids=getChecked();
        length=ids.length;
        ids=ids.toString();
    }
    if(ids=='') {
        ui.error('至少选择一个文档');
        return false;
    }
    if(confirm('您将彻底删除'+length+'条记录，删除后无法恢复，确定继续？')) {
        $.post(U('Content/completelyDel'),{ids:ids},function(res){
            if(res>'0') {
                ui.success('删除成功');
                ui.reload();
            }else {
                ui.error('删除失败');
            }
        });
    }
}
/**
 * 恢复内容
 */
 
/**
 * 审核
 */
function check(ids){
	var length;
    if(ids) {
        length = 1;         
    }else {
        ids=getChecked();
        length=ids.length;
        ids=ids.toString();
    }	
    if(ids=='') {
        ui.error('至少选择一个文档');
        return false;
    }    
    if(confirm('您将设置'+length+'条文档为审核通过')) {
        $.post(U('Content/check'),{ids:ids},function(res){
            if(res>'0') {
                ui.success('设置审核成功');
                ui.reload();
            }else {
                ui.error('设置审核失败');
            }
        });
    }    
}
/**
 * 把文档移动到其他栏目
 */
 function move(ids){
	    var length;
	    if(ids) {
	       length=1;         
	    }else {
	        ids=getChecked();
	        length=ids.length;
	        ids=ids.toString();
	    }	
	    if(ids=='') {
	        ui.error('至少选择一个文档');
	        return false;
	    } 
	   location.href=U('Content/move')+"&ids="+ids;
}
 /**
  * 删除
  */
  function del(ids){
	 var length;
     if(ids) {
         length = 1;         
     }else {
         ids=getChecked();
         length=ids.length;
         ids=ids.toString();
     }
     if(ids=='') {
         ui.error('至少选择一个文档');
         return false;
     }
     if(confirm('您将删除'+length+'条记录进回收站，确定继续？')) {
         $.post(U('Content/delText'),{ids:ids},function(res){
             if(res>'0') {
                 ui.success('删除成功');
                 ui.reload();
             }else {
                 ui.error('删除失败');
             }
         });
     }
 } 
  /**
   * 彻底删除
   */
   function completelyDel(ids){
 	 var length;
      if(ids) {
          length = 1;         
      }else {
          ids=getChecked();
          length=ids.length;
          ids=ids.toString();
      }
      if(ids=='') {
          ui.error('至少选择一个文档');
          return false;
      }
      if(confirm('您将彻底删除'+length+'条记录，删除后无法恢复，确定继续？')) {
          $.post(U('Content/completelyDel'),{ids:ids},function(res){
              if(res>'0') {
                  ui.success('删除成功');
                  ui.reload();
              }else {
                  ui.error('删除失败');
              }
          });
      }
  } 
  /**
   * 恢复内容
   */
  function recover(ids){
		 var length;
	     if(ids) {
	         length = 1;         
	     }else {
	         ids=getChecked();
	         length=ids.length;
	         ids=ids.toString();
	     }
	     if(ids=='') {
	         ui.error('至少选择一个文档');
	         return false;
	     }
	     if(confirm('您将恢复'+length+'条记录，确定继续？')) {
	         $.post(U('Content/recover'),{ids:ids},function(res){
	             if(res>'0') {
	                 ui.success('恢复成功');
	                 ui.reload();
	             }else {
	                 ui.error('恢复失败');
	             }
	         });
	     } 
  }
 
 