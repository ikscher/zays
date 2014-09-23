var arrCredit=new Array();
function checkCredit(){ //信用卡支付
	var creditName = $('#creditName').val();
	var creditCard = $('#creditCard').val();
	var creditTelphone = $('#creditTelphone').val();
	var creditBank=$('input[name="creditBank"]:checked').val();
	
	if(!creditBank){
	   arrCredit[0] = "请选择付款银行";
	
	}

	if(creditName == ''){
		if(!arrCredit[1]){
			arrCredit[1] = "姓名为空或格式不正确";
		}
	} 

	if(creditCard == ''){
		if(!arrCredit[2]){
			arrCredit[2] = "身份证号码为空或格式不正确";
		}
	} 
	if(creditTelphone == ''){
		if(!arrCredit[3]){
			arrCredit[3] = "手机号码不能为空或格式不正确";
		}
	} 
	if(arrCredit){
		return validateCredit();
	}
	return true;
}

function selectRadio(value){
	//document.getElementById("formDeposit").style.display = 'block';
	$("#formDeposit").css("display","block");
}

var arrDeposit=new Array();
function checkDeposit(){

	var depositBank = $("input[name='depositBank']:checked").val();
	var depositCard = $('#depositCard').val();
	var depositIDCard = $('#depositIDCard').val();
	var depositTelphone = $('#depositTelphone').val();
	
	if(depositCard == ''){
		if(!arrDeposit[0]){
			arrDeposit[0] = "银行卡号为空或格式不正确";
		}
	} 
		
	if(depositTelphone == ''){
		if(!arrDeposit[1]){
			arrDeposit[1] = "手机号码为空或格式不正确";
		}
	} 
	if(depositIDCard == ''){
		if(!arrDeposit[2]){
			arrDeposit[2] = "身份证号码不能为空或格式不正确";
		}
	} 	
	if(depositBank == ''){
	    //alert('请选择付款银行!');
		return false;
	}
	if(arrDeposit){
		return validateDeposit();
	}
	return true;
}

function validateCredit(){ //信用卡
	var val_html='错误提示:';
	var k=0;
	for(var i=0;i<=arrCredit.length;i++){
		if(arrCredit[i]){
		    k=k+1;	
			val_html+= '<li>'+k+'.'+arrCredit[i]+'</li>';
		}
	}

	if(val_html == '错误提示:'){
		//$('#payCredit').attr('disabled','disabled').val('提交中...');
		return true;
	}else{

		return false;
	}
}


function validateDeposit(){ //借记卡

	var val_html='错误提示:';
	var k=0;
	for(var i=0;i<=arrDeposit.length;i++){
		if(arrDeposit[i]){
		k=k+1;	
			val_html+= '<li>'+k+'.'+arrDeposit[i]+'</li>';
		}
	}
	
	if(val_html == '错误提示:'){
		//$('#payDeposit').attr('disabled','disabled').val('提交中...');
		//return true;
	}else{
	  
		return false;
	}
}


function checkPhone(phone){//手机号

	 var reg0 = /^((1[345]\d{9})|(18[024-9]\d{8}))$/;
	 var my=false;
	 if (reg0.test(phone))my=true;
	 return my;
}
function checkIDCard(card){ //身份证
	 var pattern1=/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{2}[1-9xX]$/;//15位的身份证
	 var pattern2=/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}[1-9xX]$/;//18位的身份证
	 
	 var my=false;
	 if (pattern1.test(card))my=true;
	 if (pattern2.test(card))my=true;
	 return my;
}
function checkName(name){ //姓名
	 var pattern1= /^[\u4E00-\u9FA5]{2,6}$/;
	 var my=false;
	 if (pattern1.test(name))my=true;
	 return my;
}
function checkCard(num){ //卡号
	 var pattern=/^[0-9]\d{12,25}$/;
	 var my=false;
	 if (pattern.test(num))my=true;
	 return my;
}


$(function(){
	var sec = Math.random();
	var err = '<img src="module/register/templates/default/images/err.jpg" style="width:12px;height:12px;" /> ';
	var ok = '<img src="module/register/templates/default/images/ajaxok.gif" style="width:21px;height:15px;margin-top:10px;" /> ';

	//信用卡
   $("#creditTelphone").blur(function(){
	   arrCredit[3] = '';
	   creditTelphone = $(this).val();
	   $(this).removeClass('public');
	   if(!creditTelphone){
		  $('#creditTelphone_err').removeClass('register-clue').html('您还没有填写手机号码');
		  arrCredit[3] = '手机号码不能为空或格式不正确';
	   }else if(!checkPhone(creditTelphone)){
		  $('#creditTelphone_err').addClass('register-clue-w').html('手机号码不能为空或格式不正确');  
	  	  arrCredit[3]='手机号码不能为空或格式不正确';
	   }else{
		  $('#creditTelphone_err').addClass('register-clue-w').html('手机号码输入格式正确');  
		  $arrCredit[3] = ''; 
	   }
   });
   
   $("#creditIDCard").blur(function(){
		 arrCredit[2] = '';
		 creditIDCard = $(this).val();
	     $(this).removeClass('public');
	     if(!creditIDCard){
			$('#creditIDCard_err').removeClass('register-clue').html('您还没有填写身份证号码');
			arrCredit[2] = '身份证号码不能为空或格式不正确';
		 }else if(!checkIDCard(creditIDCard)){
			$('#creditIDCard_err').addClass('register-clue-w').html('身份证号码不能为空或格式不正确');  
		  	arrCredit[2]='身份证号码不能为空或格式不正确';
		 }else{
			$('#creditIDCard_err').addClass('register-clue-w').html('身份证号码输入格式正确');  
			$arrCredit[2] = ''; 
		 }
   });
    
   $("#creditName").blur(function(){
	   arrCredit[1] = '';
	   creditName = $(this).val();
	   if(!creditName){
			$('#creditName_err').removeClass('register-clue').html('您还没有填写真实姓名');
			arrCredit[1] = '姓名不能为空或格式不正确';
	  }else if(!checkName(creditName)){
		    $('#creditName_err').addClass('register-clue-w').html('姓名不能为空或格式不正确');  
		    arrCredit[1]='姓名不能为空或格式不正确';
	  }else{
		    $('#creditName_err').addClass('register-clue-w').html('姓名格式正确');  
		    arrCredit[1] = '';
	  }
   });
   
   
   $("#creditCard").blur(function(){
	   arrCredit[0] = '';
	   creditCard = $(this).val();
	   if(!creditCard){
			$('#creditCard_err').removeClass('register-clue').html('您还没有填写信用卡号');
			arrCredit[0] = '信用卡不能为空或格式不正确';
	  }else if(!checkCard(creditCard)){
		    $('#creditCard_err').addClass('register-clue-w').html('信用卡不能为空或格式不正确');  
		    arrCredit[0]='信用卡不能为空或格式不正确';
	  }else{
		    $('#creditCard_err').addClass('register-clue-w').html('信用卡格式正确');  
		    arrCredit[0] = '';
	  }
   });
	 
   
   //借记卡
    $("#depositIDCard").blur(function(){
		arrDeposit[2] = '';
		depositIDCard = $(this).val();
	    $(this).removeClass('public');
	    if(!depositIDCard){
			$('#depositIDCard_err').removeClass('register-clue').html('您还没有填写身份证号码');
			arrDeposit[2] = '身份证号码不能为空或格式不正确';
	    }else if(!checkIDCard(depositIDCard)){
		    $('#depositIDCard_err').addClass('register-clue-w').html('身份证号码不能为空或格式不正确');  
		    arrDeposit[2]='身份证号码不能为空或格式不正确';
	    }else{
		    $('#depositIDCard_err').addClass('register-clue-w').html('身份证号码输入格式正确');  
		    arrDeposit[2] = ''; 
	    }
   });
	  
   $("#depositTelphone").blur(function(){
	   arrDeposit[1] = '';
	   depositTelphone = $(this).val();
	   $(this).removeClass('public');
	   if(!depositTelphone){
			$('#depositTelphone_err').removeClass('register-clue').html('您还没有填写手机号码');
			arrDeposit[1] = '手机号码不能为空或格式不正确';
	   }else if(!checkPhone(depositTelphone)){
		    $('#depositTelphone_err').addClass('register-clue-w').html('手机号码不能为空或格式不正确');  
		    arrDeposit[1]='手机号码不能为空或格式不正确';
	   }else{
		    $('#depositTelphone_err').addClass('register-clue-w').html('手机号码输入格式正确');  
		    arrDeposit[1] = ''; 
	   }
   });
	  
   $("#depositCard").blur(function(){
	   arrDeposit[0] = '';
	   depositCard = $(this).val();
	   $(this).removeClass('public');
	   if(!depositCard){
		   //$("#phone_err").addClass('register-clue-w').html('<span style="color:red;font-size:14px;">手机号不正确!</span>');
	  		//$("#phone_err").css('display','none');
			$('#depositCard_err').html('您还没有填写银行卡号');
			//$('#depositP1').addClass('p_red');
			arrDeposit[0] = '银行卡号不能为空或格式不正确';
	  }else if(!checkCard(depositCard)){
		    $('#depositCard_err').html('银行卡号不能为空或格式不正确');  
		    arrDeposit[0]='银行卡号不能为空或格式不正确';
	  }else{
		    $('#depositCard_err').html('银行卡号输入格式正确');  
		    arrDeposit[0] = ''; 
	  }
   });
   
   //信用卡提示选择银行
   $("#cc_BC").click(function(){
      var isCreditBank=$(this).val();
	  if(isCreditBank){
	     $("#creditBank_list").html("");
	    // $("#credit>ol>li:eq(2)").html("");
	  }
    });
	
	$("#cc_ICBC").click(function(){
      var isCreditBank=$(this).val();
	  if(isCreditBank){
	     $("#creditBank_list").html("");
	    // $("#credit>ol>li:eq(2)").html("");
	  }
    });
	
	$("#cc_ABC").click(function(){
      var isCreditBank=$(this).val();
	  if(isCreditBank){
	     $("#creditBank_list").html("");
	    // $("#credit>ol>li:eq(2)").html("");
	  }
    });
	
	$("#cc_CCB").click(function(){
      var isCreditBank=$(this).val();
	  if(isCreditBank){
	     $("#creditBank_list").html("");
	    // $("#credit>ol>li:eq(2)").html("");
	  }
    });
   

});﻿