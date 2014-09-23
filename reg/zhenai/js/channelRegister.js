﻿var tip_msg_corner='<span  class="angle1"></span><span class="angle2"></span><span class="radius1"></span><span class="radius2"></span><span class="radius3"></span><span class="radius4"></span>';
var tip_common_msg='此项注册后不可更改，请慎重填写'+tip_msg_corner;



//if workCity is not null
if(!!workCityTemp && workCityTemp.length > 0){
	registerCity = workCityTemp;
}
//if workProvince is not null
else if(!!workProvinceTemp && workProvinceTemp.length > 0){
	registerCity = workProvinceTemp;
}
//get value by cookie
else{
	var cookie = document.cookie;
	var r = /\^~registerCity=(.+?)\^~;/;
	var find = r.test(cookie);
	if(!!find){
		var cookieTemp = RegExp.$1;
		if(!!cookieTemp && cookieTemp.length > 0){
			registerCity = cookieTemp;
		}
	}	
}

function showSexTip(){
	$("#sexDiv").removeClass("tip_msg3").html(tip_common_msg).css("display","");
} 
function removeSexTip(){
	$("#sexDiv").css("display","none");
} 

function strLength(s){
	return  s.replace(/[^\x00-\xff]/gi,'hi').length;
}

function channelCheckForm() {
    
	
	var year = document.getElementById("register_dateFormYear");
	var month = document.getElementById("register_dateFormMonth");
	var day = document.getElementById("register_dateFormDay"); 
	var workProvince = document.getElementById("workprovincereg");
	var workCity = document.getElementById("workcity");


	var mobile = document.getElementById("mobile");
	var marriage1 = document.getElementById("marriage1");
	var education1 = document.getElementById("education1");

	var salary1 = document.getElementById("salary1");

	var hasFocus=false;
	var hasTopFocus=false;
	
	
	if(year.value=='0'||year.value==''){
		$(year).siblings('.tip_msg').addClass('tred').html("您忘记选择“生日”这项了。"+tip_msg_corner).show();
		if(!hasFocus){hasFocus=true;}
	}
	
	if(month.value=='0'||month.value==''){
		$(month).siblings('.tip_msg').addClass('tred').html("您忘记选择“生日”这项了。"+tip_msg_corner).show();
		if(!hasFocus){hasFocus=true;}
	}
	
	if(day.value=='0'||day.value==''){
		$(day).siblings('.tip_msg').addClass('tred').html("您忘记选择“生日”这项了。"+tip_msg_corner).show();
		if(!hasFocus){hasFocus=true;}
	}
	if(month.value=='2'&&(day.value=='30'||day.value=='31')){
		$(day).siblings('.tip_msg').addClass('tred').html("请您填写正确的出生日期。"+tip_msg_corner).show();
		if(!hasFocus){hasFocus=true;}
	}
	
	if(education1.value=='0') {
		$(education1).siblings('.tip_msg').addClass('tred').html("您忘记选择“学历”这项了。"+tip_msg_corner).show();
		if(!hasFocus){hasFocus=true;}
	}else{
	
	}
	
	if(salary1.value=='0') {
		$(salary1).siblings('.tip_msg').addClass('tred').html("您忘记选择“收入”这项了。"+tip_msg_corner).show();
		if(!hasFocus){hasFocus=true;}
	}
  
	if(marriage1.value=='0') {
		$(marriage1).siblings('.tip_msg').addClass('tred').html("您忘记选择“婚姻状况”这项了。"+tip_msg_corner).show();
		if(!hasFocus){hasFocus=true;}
	}
	
	if(!chk_phone(mobile.value)){
		$(mobile).siblings('.tip_msg').addClass('tred').html("您填写“手机”的格式不正确。"+tip_msg_corner).show();
		if(!hasFocus){hasFocus=true;}
	}
	
	

	
	if(flag){
	    $(mobile).siblings('.tip_msg').addClass('tred').html("此手机号已绑定了用户。"+tip_msg_corner).show();
		if(!hasFocus){hasFocus=true;}
	}
	
	if(!$('#mysex_0').attr('checked') && !$('#mysex_1').attr('checked')){
		$('#mysex_0').siblings('.tip_msg').addClass('tred').html("您忘记选择“性别”这项了。"+tip_msg_corner).show();
		if(!hasFocus){hasFocus=true;}
	}

	if(workProvince.value !='10101201' && workProvince.value !='10102000' && workProvince.value !='10103000' && workProvince.value !='10101002' && workProvince.value !='10104000' && workProvince.value !='10105000' && workProvince.value !='10132000' && workProvince.value !='10133000' && workProvince.value !='10134000'){
		if(workCity.value=='0'){
			$(workCity).parent('span').siblings('.tip_msg').addClass('tred').html("请选择具体工作地区。"+tip_msg_corner).show();
			if(!hasFocus){hasFocus=true;}
		}
	}

	if(workProvince.value=='0' && workCity.value=='0') {
		$(workProvince).parent('span').siblings('.tip_msg').addClass('tred').html("请选择工作所在的省份。"+tip_msg_corner).show();
		if(!hasFocus){hasFocus=true;}
	}
	if(hasFocus){
		hasTopFocus=true;		
	}	
	if(hasFocus){
		if(hasTopFocus){
			window.scrollTo('0','0');
		}		
		return false;
	}
	
	
	$('#register_btn').attr('enabled',false).val('提交中...');;
	$('#register_btn').css('cursor','wait');

	return true;
}


function chk_phone(phone){
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

var flag=false;
$("#mobile").blur(function(){

	var tel = $(this).val();
	
	var telurl = "ajax.php?n=register&h=checktel";
	$.get(telurl,{tel_val:tel},function(str){
		if(str != ''){
			flag=true;
		}else{
			flag=false;
		}
	});
	
});


$(function(){
	
	$("#register_dateFormYear,#register_dateFormMonth,#register_dateFormDay").blur(function(){
		var register_dateFormYear_value =document.getElementById("register_dateFormYear").value;
		var register_dateFormMonth_value =document.getElementById("register_dateFormMonth").value;
		var register_dateFormDay_value =document.getElementById("register_dateFormDay").value;
		if("0" == register_dateFormYear_value || "0" == register_dateFormMonth_value || "0" == register_dateFormDay_value){
			$(this).siblings("div.tip_msg").addClass("tred").html("请选择“生日”" + tip_msg_corner).show();
		}else{
			$(this).siblings("div.tip_msg").hide();
		}
	});
	
	$("#workprovincereg,#workcity").blur(function(){
		var workProvince = document.getElementById("workprovincereg");
	    var workCity = document.getElementById("workcity");
	
		if(workProvince.value=='0' && workCity.value=='0') {
			$(this).siblings("div.tip_msg").addClass('tred').html("请选择具体工作地区。"+tip_msg_corner).show();
		}else if(workProvince.value !='10101201' && workProvince.value !='10102000' && workProvince.value !='10103000' && workProvince.value !='10101002' && workProvince.value !='10104000' && workProvince.value !='10105000' && workProvince.value !='10132000' && workProvince.value !='10133000' && workProvince.value !='10134000'){
			if(workCity.value=='0'){
				$(this).parent('span').siblings('.tip_msg').addClass('tred').html("请选择具体工作地区。"+tip_msg_corner).show();
			}else{
				$(this).parent('span').siblings("div.tip_msg").hide();
			}
		}else{
			$(this).parent('span').siblings("div.tip_msg").hide();
		}

	});
	
	$("#marriage1,#education1,#salary1").blur(function(){
		var $this = $(this);
		var id = $this.attr("id");
		if($this.attr("value")=="-1"){
			$(this).siblings("div.tip_msg").addClass("tred").html(errorTip[id][0] + tip_msg_corner).show();
		}else{
			$this.siblings("div.tip_msg").hide();
		}
	});
	
	$("#mobile").blur(function(){
	    var mob=$.trim($(this).val());
	    if(!chk_phone(mob)){
		    $(this).siblings("div.tip_msg").addClass("tred").html("您填写的“手机”格式不正确！" + tip_msg_corner).show();
		}else{
		    $(this).siblings("div.tip_msg").hide();
		}
	});
	
	var errorTip = {
	"marriage1" : ["请选择“婚姻状况”"],
	"education1" : ["请选择“学历”"],
	"salary1" : ["请选择“月收入”"]
	}

	
	
});

