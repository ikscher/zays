/////////////////注册js/////////////////////////////////
$(function(){
	var sec = Math.random();
	var err = '<img src="module/register/templates/default/images/err.jpg" style="width:12px;height:12px;" /> ';
	var ok = '<img src="module/register/templates/default/images/ajaxok.gif" style="width:21px;height:15px;margin-top:10px;" /> ';
	var pass;
	var pass2;
	$("#username").blur(function(){
		username = $(this).val();
		username = rltrim(username);
		$(this).removeClass('public');
		if(username == ''){
			$("#validateemailID").css('display','none');
			arr[0] = '邮箱不能为空或格式不正确';
		}else if(!chkreg_email(username)){
			$("#validateemailID").addClass('register-clue-w').html('<span style="color:red;font-size:14px;">邮箱不能为空或格式不正确</span>');
			arr[0]='邮箱不能为空或格式不正确';
		}else{
			var changeUrl = "index.php?n=register&h=validateemail&username="+username+'&sec1='+sec;  
			$.get(changeUrl,function(str){
				if(str == '1'){
					$("#validateemailID").addClass('register-clue-w').html('<span style="color:red;font-size:14px;">您输入的邮箱已存在,请重新输入</span>');
					arr[0]='您输入的邮箱已存在,请重新输入';
				}else{
					$("#validateemailID").removeClass('register-clue').html(ok);
					arr[0] = '';
				}
			})
		}
	})
   $("#password").blur(function(){
		 pass = rltrim($(this).val());
		 //pass2 = rltrim($('#password2').val());
		$(this).removeClass('public');
		if(pass == ""){
			//$("#pwdtip").html(err + '<span style="color:red;font-size:12px;">密码不能为空!</span>');
			$("#pwdtip").css('display','none');
			arr[1] = '密码不能为空';
		}else if(pass.length<6 || pass.length>20){
			$("#pwdtip").addClass('register-clue-w').html('<span style="color:red;font-size:14px;">密码必须为6到20位长度</span>');
			arr[1]='密码必须为6到20位长度';
		}else{
			$("#pwdtip").removeClass('register-clue').html(ok);
			if(rltrim($('#password2').val()) == rltrim($(this).val())){
				$("#pwdtip2").removeClass('register-clue-w').removeClass('register-clue').html(ok);
			}else if(rltrim($('#password2').val()) != rltrim($(this).val())){
				$("#pwdtip2").removeClass('register-clue').addClass('register-clue-w').html('<span style="color:red;font-size:14px;">两次输入的密码不同</span>');
			}
			arr[1] = '';
		}
   })	
   $("#password2").blur(function(){
	   pass2 = rltrim($(this).val());
	   $(this).removeClass('public');
	   if(pass2 != pass && pass2 != '' && pass != ''){
		   $("#pwdtip2").addClass('register-clue-w').html('<span style="color:red;font-size:14px;">两次输入的密码不同</span>');
	   		arr[2]='两次输入的密码不同';
	   }else{
		   if(pass2 == pass && pass2 != '' && pass != ''){
		   		$("#pwdtip2").removeClass('register-clue').html(ok);
				arr[2] = '';
		   }else{
			   $("#pwdtip2").removeClass('register-clue').html('');
			   arr[2] = '确认密码不能为空';
		   }
	   }
   })
   String.prototype.trim=function(){
	　　    return this.replace(/(^\s*)|(\s*$)/g, "");
	　　 }
	
   $("#telphone").blur(function(){
	   tel = $(this).val();
	   $(this).removeClass('public');
	   if(!tel){
		   //$("#phone_err").addClass('register-clue-w').html('<span style="color:red;font-size:14px;">手机号不正确!</span>');
	  		//$("#phone_err").css('display','none');
			$('#phone_err').removeClass('register-clue').html('');
			arr[3] = '手机号码不能为空或格式不正确';
	  }else if(!chk_phone(tel)){
		  $('#phone_err').addClass('register-clue-w').html('手机号码不能为空或格式不正确');  
	  	  arr[3]='手机号码不能为空或格式不正确';
	  }else{
			var telurl = 'ajax.php?n=register&h=checktel&sec2='+sec;
			$.get(telurl,{tel_val:tel},function(str){
					if(str.trim() != ''){	
						$("#phone_err").addClass('register-clue-w').html('<span style="color:red;font-size:14px;">'+str+'</span>');
						$("#telphone").val('');
						arr[3]='该号码已被其他用户绑定';
					}else{
						$("#phone_err").removeClass('register-clue').removeClass('register-clue-w').html(ok);
						arr[3] = '';
					}
		   })
	   }
   })
   $('#telphone_back').blur(function(){
		var tel_back = $(this).val();
		$(this).removeClass('public');
		if(!tel_back){
			$('#phoneback_err').css('display','none');
		}else if(!bek_phone(tel_back)){
			$('#phoneback_err').css('display','').addClass('register-clue-w').html('备注号码格式不正确'); 
		}else{
			$('#phoneback_err').css('display','none');	
		}		 
					 
   });
   

})