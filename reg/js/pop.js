
var c_provice = "";
var c_city = "";
len = provice.length;
for (i = 0; i < len; i++) {
	provice_arr = provice[i].split(",");
	if (curent_area.indexOf(provice_arr[1]) != -1) {
		c_provice = provice_arr[0];
		break;
	}
}
len = city.length;
for (i = 0; i < len; i++) {
	city_arr = city[i].split(",");
	if (curent_area.indexOf(city_arr[1]) != -1) {
		c_city = city_arr[0];
		break;
	}
}
function checkForm() {
    var email = document.getElementById("username");
    var year = document.getElementById("year");
    var month = document.getElementById("month");
    var day = document.getElementById("day"); 
    var workProvince = document.getElementById("workprovincereg");
    var workCity = document.getElementById("workcity");
    var pwd = document.getElementById("password");
    var agree = document.getElementById("agree");
    var actio=document.getElementById("actio");
    var pwd2=document.getElementById("password2");
    var telphone = document.getElementById('telphone');
    var emailmessage=document.getElementById('emailmessage');
    var passmessage=document.getElementById('passmessage');
    var telphonemessage=document.getElementById('telphonemessage');
    if(email.value==""){
        alert("邮箱不能为空");
        email.focus();
        return false;
    }
    if(email.value.indexOf('@')<0){
        //alert("请输入邮箱");
        email.focus();
        return false;
    }
   if(!chk_phone1(telphone.value)){
        alert("请输入真实的手机号！");
        return false;
    }
    if(emailmessage.value!="ok" && emailmessage.value!="false"){
        alert(emailmessage.value);
        email.value='';
        email.focus();
        return false;
    }
    if(pwd.value==''){
           alert("密码不能为空");
           pwd.focus();
           return false;
    }
    if(pwd2.value==''){
           alert("密码不能为空");
           pwd2.focus();
           return false;
    }
    if(passmessage.value!='ok'){
           alert(passmessage.value);
           pwd.value='';
           pwd.focus();
           return false;
    }
    if(telphonemessage.value!='ok' && telphonemessage.value!='false'){
           alert(telphonemessage.value);
           telphone.value='';
           telphone.focus();
           return false;
    }
    if(year.value=='-1'||year.value==''){
        alert("请选择出生日期！");
        year.focus();
        return false;
    }
    if(month.value=='-1'||month.value==''){
        alert("请选择出生日期！");
        month.focus();
        return false;
    }
    if(day.value=='-1'||day.value==''){
        alert("请选择出生日期！");
        day.focus();
        return false;
    }
    
    if(workProvince.value=='-1' && workCity.value=='-1') {
       alert("您忘记选择工作地区这项了!");
        workProvince.focus();
        return false;   
    }
    if(workProvince.value !='10101201' && workProvince.value !='10102000' && workProvince.value !='10103000' && workProvince.value !='10101002' && workProvince.value !='10104000' && workProvince.value !='10105000' && workProvince.value !='10132000' && workProvince.value !='10133000' && workProvince.value !='10134000'){
        if(workCity.value=='-1' || workCity.value==''){
            alert("您忘记选择工作地区这项了!");
            return false;
        }
    }
    refreshSubmit();
	
    return true;
}


var collapseT=30;
function refreshSubmit(){
  if(collapseT<=0){
	$.cookie('s',null);
	 clearTimeout(b);
	 $('.tg_login_btn').css({'background-image':'url(images/tg_btn.jpg)'});
   }
  else{
	collapseT--;
	$.cookie('s',collapseT);
	//$("#sign_id").html("如果没有获取到短信,请在"+a+"秒后重新获取");
	$('.tg_login_btn').css({'background-image':'url(images/tg_btn_submiting.jpg)'});
	var b=setTimeout("refreshSubmit",1000);
  }
}
