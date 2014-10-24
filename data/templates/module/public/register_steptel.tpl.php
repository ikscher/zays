<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员注册--基本资料填写——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<!-- <link href="public/default/css/global_import1.css" rel="stylesheet" type="text/css" /> -->
<!--  -->
<link href="module/register/templates/<?php echo $GLOBALS['style_name'];?>/css/register-info.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="module/register/templates/public/js/commonValidate.js?v=1"></script>
</head>
<script>
var run = '';
var arr_num = new Array();//用数组统计所有错误信息


//防止恶意点击按钮获取验证码
var num=60;
var t;

function reSend(){
		if(num == -1){
			num = 60;
		}
		//获取手机验证码
		var url,sec,truetelphone_val;
		truetelphone_val = '<?php echo $userinfo['uid'];?>'+','+'<?php echo $userinfo['telphone'];?>'+','+'<?php echo $password;?>';
		url = 'ajax.php?n=register&h=truetelphone&sec='+Math.random();
		$.get(url,{truetelphone_val:truetelphone_val},function(){
		    number();	
		})
}
//重新获取手机验证码
function number(){
	num--;
	if(num>=0){
	    
	    //如果一分钟内未收到确认码，请50秒后点此重发
		$('#telmessage span').html('如果一分钟内未收到确认码，请'+num+'秒后点此重发');
		$('#telmessage span').css('color','gray');
		t = setTimeout('number()',1000);

	}else{
		if (t) clearTimeout(t);
		$('#telmessage span').html("如果一分钟内未收到确认码，<a href='javascript:void(0);' id='reSend' onClick='javascript:reSend();' style='text-decoration:underline' class='f-21ef43'>点击重发>></a>");
	}	
}
number();

//表单验证
function checkForm(){
    if(!$('#telphonemack').val()){
        $('#validateMobile').css('display','inline-block').addClass('register-clue-w').html('请填写手机验证码！'); 
	}
	if($('#validateMobile').css('display')=='inline-block' ) return false;
}
	

</script>
<body>
<div class="main">

    <!--头部结束-->
	<div class="content">
		<div class="header"><span class="logo"></span></div>
		<form action="index.php?n=register&h=steptel" method="post" onSubmit="return checkForm();"> 
			<div class="content-title">
				<span class="right-title">手机验证信息,请确认您的联系方式</span>
			</div><!--content-title end-->
			<div class="c-center">
			    <!-- <div class="register-step"><p class="step_"></p></div> -->
			    
				<div id="dl_1" class="fl">
					<div style="padding-left:25px;padding-top:10px;">
						<p style="margin-bottom:10px;">
						<span style="width:125px;"><span class="f-ed0a91">*</span> 手机号码：</span>
						<span style="color:#000;font-weight:bold;"><span  class="telphone"  style="margin-right:20px;font-size:14px;"><?php echo $telphone;?></span><input id="editMobile" name="editMobile" type="button" class="btn btn-default" value="修改号码" /></span>
						</p>
						<p>
						<span style="width:125px;"><span class="f-ed0a91">*</span> 手机验证码：</span>
						
						  <span>
							<input id="telphonemack" name="telphonemack" value="" type="text"  class="c-center-data-text"  maxlength="6"/>
							<input name="telcheck" type="hidden" value="yes" />
							<input name="truetelphone" value="<?php echo $telphone;?>" type="hidden" />
							<!--<input id="checka" name="checka" type="hidden" value="errors" />-->
						  </span>
						  <span id="validateMobile" class="register-clue" style="display: none;"><font style="font-size:14px;">请输入常用的邮箱</font></span>
						</p>
						
					</div>

					<p style="margin-left:150px;margin-bottom:15px;"><input id="register" name="register" type="submit"  class="btn btn-primary" value="注册完成"/></p>	
					<p class="f-ed0a91 ml" id="telmessage"><span>如果一分钟内未收到确认码，<a href="javascript:void(0)" id="reSend" onClick="javascript:reSend()" style="text-decoration:underline" class="f-21ef43">点击重发>></a></span></p>
                    
			   </div>
				<div id="dl_2"  class="fl" style="display:none">
				   
					<div style="margin-left:20px;margin-top:10px;">
						<p>
						<span style="width:125px;"><span class="f-ed0a91">*</span> 手机号码：</span>
						<input type="text" maxlength="11" id="tel" name="tel" value="<?php echo $telphone;?>" onkeypress="if(event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" />										
							<span class="public_bun1" id="cancelA">取消修改</span>
						　　
							<span id="tipMsg" style="display:none;color:red;margin-left:135px;">错误的手机号码!</span></p>　
						<span style="display:inline-block;margin-top:20px">　
							<a  href="javascript:;"><input type="button" id="phoneBtn" class="btn btn-primary ml80" value="保存新号码" /></a>
						</span>
					</div>	
													
				
				</div>
				
				<div class="reg_right2"> </div>
				
			</div>
			
		    <div class="content-bottom"></div>		
		</form>

		<!--content-bottom end-->
		<div class="clear"></div>
	</div><!--content end-->
	<div class="foot-nav f-999">
	    <div>品牌：8年专业婚恋服务&nbsp; 专业：庞大的资深红娘队伍&nbsp; 真实：诚信会员验证体系&nbsp; </div>
		<div>Copyright 2006-2014 真爱一生网.All Right Reserved</div>
		<div>客服热线：400-8787-920 （周一至周日：9：00-21：00）客服信箱：kefu@zhenaiyisheng.cc</div>
	</div>
</div><!--main end-->
<script type="text/javascript">
   
$('#telphonemack').blur(function(){
	var tel_mack = $(this).val();
	var update = Math.random();
	if(!tel_mack){
		$('#validateMobile').css('display','inline-block').addClass('register-clue-w').html('请输入您手机收到的数字确认码！'); ;
	}else if(!/^\d{4,4}$/.test(tel_mack)){
		$('#validateMobile').css('display','inline-block').addClass('register-clue-w').html('填入的手机验证码 格式不正确！'); 
	}else{
	    $.get('ajax.php?n=register&h=checkmack&update='+update,{mack:tel_mack},function(str){		
			if(str!='ok'){
				$('#validateMobile').css('display','inline-block').addClass('register-clue-w').html('手机验证码 输入错误'); 
			}else{
				$('#validateMobile').css('display','none');
			}
		});
    }	
});


$("#editMobile").on('click',function(){
   $('#dl_1').css('display','none');
   $('#dl_2').css('display','block');
});

$("#cancelA").on('click',function(){
   $('#dl_1').css('display','block');
   $('#dl_2').css('display','none');
});

var MA;
function checkMobile(){
    MA=new Array();
    var tel=$('#tel').val();
    $.ajax({
	    url:'ajax.php?n=register&h=checktel',
		data:{tel_val:tel},
		async: false,
		type:'GET',
		success:function(str){		
			if(str!=''){
			
				MA.push('该手机号码已经绑定！');
			}
		}
	});
	
	$.ajax({
	    url:'ajax.php?n=register&h=modifytel',
		data:{tel_val:tel},
		type:'GET',
		async: false,
		success:function(str){
			if(str!='ok') {
				//$('#tipMsg').css('display','block');
				
				MA.push('手机号码修改失败，请重试！');
			}
		}
	});
}

$('#phoneBtn').on('click',function(){
    $('#tipMsg').html('');
    var tel=$('#tel').val();
	if($.trim(tel)==0) return false;
	if(chk_phone(tel)){
	    $('#tipMsg').css('display','none');
	}else{
	    $('#tipMsg').css('display','block');
		$('#tipMsg').html('错误的手机号码！');
		return false;
	}
    checkMobile();
	var msg='';
	if(MA.length>0){
	    for(var i in MA){
	        msg+=MA[i];
	    }
		$('#tipMsg').css('display','block');
		$('#tipMsg').html(msg);
		
		return false;
	}else{
	    $('#tipMsg').css('display','none');
			
				
	    $('#dl_1').css('display','block');
		$('#dl_2').css('display','none');
		$('span.telphone').html(tel);
		$("input[name='truetelphone']").val(tel);
	
	}
	
	
});
</script>
</body>
</html>
