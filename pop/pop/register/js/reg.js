/////////////////注册js/////////////////////////////////
$(function(){
        $("#username").focus(function(){$("#username").attr("value","");})
	$("#username").blur(function(){
		username = $(this).val();
		username = rltrim(username);
		if(username == ''){
			alert("邮箱不能为空!");
		}else if(!chkreg_email(username)){
			alert("邮箱格式不正确!");
			$("#username").attr('value','');
		}else{
			var changeUrl = "../index.php?n=register&h=validateemail&username="+username;  
			$.get(changeUrl,function(str){
			if(str == '1'){
				alert("您输入的邮箱已存在,请重新输入!");$("#username").attr('value','');
			}
			})
		}
	})
   $("#password").blur(function(){
		pass = rltrim($(this).val());
		if(pass == ""){
			alert("密码不能为空!");
		}else if(pass.length<6 || pass.length>20){
			alert("密码必须为6到20位长度!");$("#password").attr('value','');
		}
   })
   $("#password2").blur(function(){
	   pass2 = rltrim($(this).val());
	   if(pass2 != pass){
		   alert("两次输入的密码不同!");$("#password").attr('value','');
	   }
   })
   $("#telphone").focus(function(){$("#telphone").attr("value","");})
   $("#telphone").blur(function(){
	   tel = $(this).val();
	   if(!chk_phone(tel)){
		   alert("手机号不正确!");$("#telphone").attr('value','');
	   }
   })

})