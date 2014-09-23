/////////////////注册js/////////////////////////////////
$(function(){
	var err = '<img src="reg/css/images/error.gif" style="width:12px;height:12px;vertical-align:middle;" /> ';
	var ok = '<img src="reg/css/images/ok.gif" style="width:12px;height:12px;vertical-align:middle;" /> ';
	$("#username").focus(function(){
	    var username=$.trim($("#username").val());
	    if(username=='请输入您的email地址') $("#username").attr("value","");
		if(username=='您输入的邮箱已存在,请重新输入') $("#username").attr("value","").css("color","#666");
	});
    
	$("#username").blur(function(){
		username = $(this).val();
		username = rltrim(username);
		if(username == ''){
		    $("#username").attr('value',"请输入您的email地址").css("color","red");
			$("#validateemailID").html(err);
		}else if(!chkreg_email(username)){
			$("#validateemailID").html(err);
		}else{
			var changeUrl = "index.php?n=register&h=validateemail&username="+username;  
			$.get(changeUrl,function(str){
			if(str == '1'){
				$("#validateemailID").html(err);$("#username").attr('value',"您输入的邮箱已存在,请重新输入").css("color","red");
			}else{
				$("#validateemailID").html(ok);$("#emailmessage").attr('value',"ok");
			}
			})
		}
	})
    $("#password").blur(function(){
		pass = $.trim($(this).val());
		if(pass == ""){
			$("#pwdtip").html(err);
		}else if(pass.length<6 || pass.length>20){
			alert("密码必须为6到20位长度!");$("#pwdtip").html(err);$("#password").attr('value','');$("#passmessage").attr('value',"密码必须为6到20位长度!");
		}else{
			$("#pwdtip").html(ok);
		}
    })
    $("#password2").blur(function(){
	   pass2 = $.trim($(this).val());
	   if(!pass2){
			$("#pwdtip2").html(err);
	   }else{
		   if(pass2 != pass){
			   alert("两次输入的密码不同!");$("#password").attr('value','');$("#password2").attr('value','');$("#pwdtip").html(err);$("#passmessage").attr('value',"两次输入的密码不同!");
		   }else{
			   $("#pwdtip2").html(ok);$("#passmessage").attr('value',"ok");
		   }
	   }
    })
    $("#telphone").focus(function(){
	       if($("#telphone").val()=='填入手机号码获免费牵线')$("#telphone").attr("value","");
		   if($("#telphone").val()=='该号码已被其他用户绑定') $("#telphone").attr("value","").css("color","#666");
	});
    //$("#telphone").focus(function(){$("#telphone").attr("value","");})
    $("#telphone").blur(function(){

	    var tel = $(this).val();
	    if(!chk_phone1(tel)){
		   $("#telphonemessage").attr('value',"手机号不正确!");
		   $("#validatetelphoneID").html(err);
	    }else{
			var telurl = "ajax.php?n=register&h=checktel";
			$.get(telurl,{tel_val:tel},function(str){
				if(str != ''){
					$("#telphonemessage").attr('value',str);
					$("#validatetelphoneID").html(err);
					$("#telphone").attr('value',str).css("color","red");
				}else{
					$("#validatetelphoneID").html(ok);
					$("#telphonemessage").attr('value',"ok");
				}
			});
	    }
    })

})


function chk_phone1(phone){
	 var reg0 = /^13\d{9}$/;   //130--139。至少7位
	 var reg1 = /^15[0-35-9]\d{8}$/;  //150-159(154除外)。至少7位
	 //var reg2 = /^18[8-9]\d{8}$/;  //188-189。
	 var reg2 = /^18\d{9}$/;   //180--189。至少7位
	 var reg3 = /^14\d{9}$/;
	 var my=false;
	 if (reg0.test(phone))my=true;
	 if (reg1.test(phone))my=true;
	 if (reg2.test(phone))my=true;
	 if (reg3.test(phone))my=true;
	 return my;
}
