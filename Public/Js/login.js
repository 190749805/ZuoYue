//验证修改界面的输入项
$(function(){
	$('button').click(function(){
		var password=$('#passwd').val().trim();
		var newpassword=$('#npasswd').val().trim();
		var confirmpassword=$('#cpasswd').val().trim();
		if(password==""){
			$('#span-passwd').html('没有输入原密码');
			return false;
		}else if(newpassword==""){
			$('#span-npasswd').html('没有输入新密码');
			return false;
		}else if(confirmpassword==""){
			$('#span-cpasswd').html('没有输入确认密码');
			return false;
		}
	});
	$('#passwd').keyup(function(){
		if($('#passwd').val().trim()!=""){
			$('#span-passwd').html('');
		}
	});
	$('#npasswd').keyup(function(){
		if($('#npasswd').val().trim()!=""){
			$('#span-npasswd').html('');
		}
	});
	$('#cpasswd').keyup(function(){
		if($('#cpasswd').val().trim()!=""){
			$('#span-cpasswd').html('');
		}
	});
})