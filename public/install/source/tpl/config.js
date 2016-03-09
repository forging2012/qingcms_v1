
//检测数据库
function checkDb(){
	var params={};
	    params['db_host']=$(':input[name="db_host"]').val();
	    params['db_name']=$(':input[name="db_name"]').val();
		params['db_user']=$(':input[name="db_user"]').val();
		params['db_pwd'] =$(':input[name="db_pwd"]').val();
    $.post(__J_checkdb__,params,function(data){
    	data=eval('('+data+')');
    	$('#J-checkdb-msg').html(data['message']);
    });
}
//执行安装
function install(){
var action =__J_checkinstall__;
var formDom='#J-form-doinstall';
var	params =$(formDom).serializeArray(); //系列化各个表单域
$.post(action,params,function(data){
	data=eval('('+data+')');
	var success =data.success;
	var message =data.message;
	var iconfirm=data.confirm;
	if(success){
		var icontinue=true;
		if(iconfirm!=undefined && iconfirm>''){
			if(!confirm(iconfirm)){
				icontinue=false;
			}
		}
		if(icontinue){
			window.location.href=__J_installing__;
		}
	}else{
		alert(message);
	}
});
}

$(document).ready(function(){
	$(':input[name="db_name"]').bind('change',function(){
		checkDb();
	});
});
