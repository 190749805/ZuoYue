$(function(){
	var str=window.location.href;
	var m;
	if(str.search(/index.php/)!=-1){
		m=str.search(/index.php/);
	}else{
		m=str.length;
	}
	//点击显示发表主题的界面
	$('#h2-title').click(function(){
		if($('#hidden_input').val() == ''){
			window.location.href="/ZuoYue/index.php/Discuss/error";
		}else{
			$('#div-title-content').hide();
			$('#h2-title').hide();
			$('#div-title').show();
		}
	});
	//点击取消返回讨论页面
	$('#qx').click(function(){
		//window.location.href='./index.php/Discuss/index';
		location.reload();
	});
	//点击确认进行验证，成功后返回讨论页面
	var addr1=str.substr(0,m)+'index.php/Discuss/commit_title';
	var addr2=str.substr(0,m)+'index.php/Discuss/index';
	$('#qr').click(function(){
		var content=$('#content').val();
		var title=$('#title').val();
		if(content.trim()=="" || title.trim()==""){
			alert("你输入不能为空");
			return false;
		}else{
			$.post(addr1,{tcontent:content,ttitle:title},function(data){
				if(data==1){
					alert('发表成功');
					window.location.href=addr2;
				}else if(data==-1){
					alert('请先登录');
				}else{
					alert('发表失败');
				}
			});
		}
	});
	$('#title').blur(function(){
		var title=$('#title').val();
		if(title.length>16){
			$('#span-title').text('你输入主题不能超过16个字');
			$('#qr').attr('disabled',"true");
			//console.log('fsd');
		}else{
			$('#span-title').text("");
			$('#qr').removeAttr('disabled');
		}
	});
})
//针对评论及其回复
$(function(){
	//var id;
	var str=window.location.href;
	var m;
	if(str.search(/index.php/)!=-1){
		m=str.search(/index.php/);
	}else{
		m=str.search.length;
	}
	$(document).on('click',".a-reply",function(){
		var id=this.id;
		var name=this.name;
		//console.log(nclass);
		$('#span-'+id).html('<p><textarea id="textarea-'+name+'" style="width:500px;height:80px"></textarea></p>'+
		'<p><button class="btn-qr btn btn-primary" name="'+name+'">确认</button>&nbsp;&nbsp;<button class="btn-qx btn btn-danger">取消</button></p>');
	});
	//取消
	$(document).on('click',".btn-qx",function(){
		$('.a-span').html("");
	});
	//确认就接收值并且传递后台存储在数据库
	$(document).on('click',".btn-qr",function(){
		var strid=this.name;
		//console.log(strid);
		var content=$('#textarea-'+strid).val();
		if(content==""){
			alert('你的输入为空，不能提交');
			return false;
		}else{
			var $id=strid.substr(1,2);
			//console.log(id);
			//return false;
			var addr1=str.substr(0,m)+'index.php/Discuss/commit_reply';
			//console.log(addr1);
			$.post(addr1,{ccontent:content,cid:$id},function(data){
				if(data==-1){
					var addr2=str.substr(0,m)+'index.php/Login/index';
					window.location.href=addr2;
				}else if(data!=""){
					alert('回复成功');
					var data=JSON.parse(data);
					if(($('#ul'+$id).children('li:last').val())){
						$('#ul'+$id).children('li:last').append('<li>'+data['cusername']+':'+data['ccontent']+'  '+data['ctime']+
						'<a href="#" class="a-reply" name="a'+data['cid']+'" id="'+data['id']+'">回复</a></li>'+
						'<span class="a-span" id="span-'+data['id']+'"></span>');
							//console.log('false');
					}else{
						$('#ul'+$id).append('<li>'+data['cusername']+':'+data['ccontent']+'  '+data['ctime']+
							'<a href="#" class="a-reply" name="a'+data['cid']+'" id="'+data['id']+'">回复</a></li>'+
							'<span class="a-span" id="span-'+data['id']+'"></span>');
					}
					$('.a-span').html("");
					location.reload();
				}else{
					alert('回复失败');
					return false;
				}
			});
		}
	});
	//发表评论
	$('#btn-comment').click(function(){
		//alert('fdas');
		var content=$('#textarea-comment').val();
		var id=$('#textarea-comment').attr('name');
		//console.log(id);
		if(content.trim()==""){
			alert('你的输入为空');
			return false;
		}else{
			addr3=str.substr(0,m)+'index.php/Discuss/commit_comment';
			$.post(addr3,{ccontent:content,tid:id},function(data){
				//console.log(data);
				if(data==-1){
					alert('请先登录用户');
				}else if(data==1){
					alert('评论成功');
					location.reload();
				}else{
					alert('error');
				}
			});
		}
	});
	//记录浏览次数
	$('.a').click(function(){
		var csid=this.name;
		//console.log(m);
		addr4=str.substr(0,m)+'index.php/Discuss/title_cs'
		$.post(addr4,{id:csid},function(data){
			if(data==1){
			
			}else{
				return false;
			}
		});
	});
})	