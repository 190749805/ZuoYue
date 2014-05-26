$(function(){
//显示发表咨询的界面
	$('#h2-zx').click(function(){
		if($("#session_info").val() == ''){
			window.location.href="/ZuoYue/index.php/Publishzx/error"
		}else{
			$('#h2-zx').hide();
			$('#div-zx-content').hide();
			$('#div-zx').show();
		}
	});
	$('#qx').click(function(){
		location.reload();
	});
})
//验证咨询数据
$(function(){
	$('#qr').click(function(){
		var title=$('#zx-title').val().trim();
		var content=$('#zx-content').val().trim();
		if(title==""){
			$('#span-title').html('主题必须填写');
			return false;
		}else if(content==""){
			$('#span-content').html('描述必须填写必须填写');
		}
	});
	$('#zx-title').keyup(function(){
		if($('#zx-title').val().trim()!=""){
			$('#span-title').html('');
		}
	});
	$('#zx-content').keyup(function(){
		if($('#zx-content').val().trim()!=""){
			$('#span-content').html('');
		}
	});
})
//验证评论数据
$(function(){
	$('#btn-comment').click(function(){
		var comment=$('#textarea-comment').val().trim();
		var id=$('#textarea-comment').attr('name');
		//console.log(id);
		if(comment==""){
			alert('你的输入不能为空');
			return false;
		}else{
			$.post('./add_comment',{comment:comment,zx_id:id},function(data){
				if(data==-1){
					alert('亲，你还没有登录');
				}else if(data==1){
					alert('评论成功');
					location.reload();
				}else{
					alert('评论失败');
				}
			});
		}
	});
})