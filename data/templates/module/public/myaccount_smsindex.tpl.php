<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>诚信认证——真爱一生网</title>

<?php include MooTemplate('system/js_css','public'); ?>
<link href="module/myaccount/templates/<?php echo $GLOBALS['style_name'];?>/myaccount.css" rel="stylesheet" type="text/css" />
<script src="module/search/templates/default/js/syscode.js?v=1" type="text/javascript"></script>

</head>

<body>

<div class="main">
	<?php include MooTemplate('system/header','public'); ?>
	<div class="content">
	<p class="c-title"><span class="f-000"><a href="index.php?n=service">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=myaccount">诚信认证</a>&nbsp;&gt;&gt;&nbsp;身份认证</span></p>
	<!--content-lift end-->
    <?php include MooTemplate('system/simp_left','public'); ?>
	<!--=====左边结束===-->
	<div class="content-right">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=myaccount" class="r-title-black">&lt;&lt;返回诚信认证</a>
			<span class="right-title f-ed0a91">身份认证</span>
			</div>
			<div class="r-center-ccontent">
				<div class="cer-conter">
                <form action="index.php?n=payment&h=validateID" method="post" onsubmit="return checkForm();">
					<ul class="cer-id">
						<?php if($user_arr['s_cid']>=40) { ?> <!-- <li><p class="cer-id-title">目前广州，广西，云南，湖南，新疆的移动用户和电信（133、153、189开头的手机号码）用户无法认证身份通。</p></li> --><?php } ?>
						<li><span class="f-14x">您的真实姓名：</span>
							<input name="userName" id="yourName" class="form-control" />
							<span class="f-ed0a91">请输入您的真实姓名</span></li>
						<li><span class="f-14x">您的身份证号：</span>
							<input name="userCode" id="yourIDCard" class="form-control"   />
							<span class="f-ed0a91">请输入与您的真实姓名相匹配的15位或者18位身份证号</span></li>
						<?php if($user_arr['s_cid']>=40) { ?>
						<li><span class="f-14x">您的手机号码：</span>
							<input name="msisdn" id="yourTelphone" class="form-control" />
							<span class="f-ed0a91">仅用于身份验证，其他用户无法看到此手机号码</span></li>
							<input type="hidden" name="CommonMember" value="youareright" />
						<?php } ?>
						<li><p class="cer-id-title"><input name="smsValidate" type="submit" class="btn btn-default" <?php if($user_arr['s_cid']>=40) { ?>value="下一步"<?php } else { ?>value="提交"<?php } ?> /><input name="is_read" id="is_read" type="checkbox" value=""  checked/>
							<span class="f-14x">我已阅读</span> <a href="index.php?n=myaccount&h=serverrule" target="_blank" class="f-ed0a91-a">身份通服务条款</a></p></li>
					</ul>
                </form>
				<div class="cer-line"></div>
				<dl class="cer-faq">
					<dt class="f-ed0a91">为什么要进行身份通认证？</dt>
					<dd>真爱一生网每天有上万用户选择身份通认证，通过真爱一生回访也发现，成功认证了身份证可以让您的交友成功率提高67%</dd>
					<dt class="f-ed0a91">什么是身份通认证？</dt>
					<dd>身份通认证是由“全国公民身份系统”提供数据支持，安全可靠。只需要填写您的真实姓名、身份证号<?php if($user_arr['s_cid']>=40) { ?>以及手机号码<?php } ?>。即可以通过认证。</dd>
				</dl>
				<div class="cer-line"></div>
				<dl class="cer-faq">
					<dd>1. 请您务必填写真实的姓名和身份证号，当您通过认证后，系统会根据您的身份证号核对您在网站的生日，如果不一致，系统会自动进行修改，并且此后您无法修改自己的生日。</dd>					
					<dd>2. 如果您已在其他网站认证了身份通，在真爱一生网也是需要您重新认证的，请按照以上的操作提示进行填写<?php if($user_arr['s_cid']>=40) { ?>，并保证您所输入的手机号码与当时认证的号码一致<?php } ?>。 </dd>
				</dl>
				</div>
				<div class="clear"></div>
			</div>
			<div class="r-center-bottom">
			</div>
		</div>
	</div>
		<div class="clear"></div>
	</div>
	
	<!--content end-->
	<?php include MooTemplate('system/footer','public'); ?>
    <!--foot end-->
</div><!--main end-->

</body>
<script type="text/javascript">
function checkForm(){
	var arr=new Array();
	var is_read= document.getElementById("is_read").checked;
	if(!is_read){
		 arr[1]="请确定已阅读身份通服务条款";
		 //document.getElementById("is_read").focus();
    }

	var userName=$("#yourName").val();
	var userCode=$("#yourIDCard").val();
	var userTel=$.trim($("#yourTelphone").val());
	 /*
	 if(!checkIDCard(userCode)){
		 arr[1]='您的身份证输入格式有误';
	 }
     */
	 
	 if(!checkName(userName)){
		 arr[0]='您的姓名输入格式有误';
	 }

     /*
	 if(!checkPhone(userTel)){
		 arr[1]='您的ddd输入格式有误';
	 }
	*/
	 
	 var message='';
	 var k=0;
	 for(var i=0;i<=arr.length;i++){
		if(arr[i]){
	      k=k+1;
	      message+= k+'.'+arr[i]+'\n';
		}
	 }	  	

	 if(message){
		 alert(message);
		 return false;
	 }else{
		 /*
		 $(function(){
				<?php if($user_arr['s_cid']<2 ) { ?>
			       $("input[name='smsValidate']").click(function(){
			          $(this).css("background",'red').width("100px").val("正在验证 . . .");
			       });
				<?php } ?>
				
		  });
		  */
	     return true;
	 }
}



function checkPhone(phone){//手机号
	var pattern = /^((1[345]\d<?php echo 9;?>)|(18[024-9]\d<?php echo 8;?>))$/;
	var my=false;
	if (pattern.test(phone))my=true;
	return my;
}
/*
function checkIDCard(IDcard){ //身份证
	 var pattern1=/^[1-9]\d<?php echo 7;?>((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d<?php echo 2;?>[1-9xX]$/;//15位的身份证
	 var pattern2=/^[1-9]\d<?php echo 5;?>[1-9]\d<?php echo 3;?>((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d<?php echo 3;?>[1-9xX]$/;//18位的身份证

	 if (pattern15.test(IDcard) || pattern18.test(IDcard)){
         return true;
	 }else{
         return false;
	 }

}
*/
function checkName(name){ //姓名
	 var pattern= /^[\u4E00-\u9FA5]{2,6}$/;
	 if (pattern.test(name)){
		 return true;
	 }else{
		 return false;
	 }

}
</script>
</html>
