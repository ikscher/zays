<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员注册-详细资料填写——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link href="module/register/templates/<?php echo $GLOBALS['style_name'];?>/css/register-info.css" type="text/css" rel="stylesheet" />
</head>
<script>
//控制真实姓名长度
$(function(){
	$('#truename').keyup(function(){
		var val_name = $('#truename').val().replace(/\s/g,'');
		var val_search = val_name.search(/[0-9]/);
		if( val_search > -1){
			$('#truename').val(val_name.substring(0,val_search));
		}else{
			$('#truename').val(val_name.substring(0,8));	
		}
	})		   
})
//控制qq长度
$(function(){
	$('#qq').keyup(function(){
		var val_qq = $('#qq').val().replace(/\s/g,'');				   
		if(!/(^\d{0,}$)/.test(val_qq)){
			$('#qq').val('');
		}else{
			$('#qq').val(val_qq.substring(0,11));
		}
	})		   
})
//控制MSN长度
$(function(){
	$('#msn').blur(function(){
		var val_msn = $('#msn').val().replace(/\s/g,'');
		if(!/^\w+([-_]\w+)*@(\w{2,}\.)+[a-zA-Z]{2,}$/.test(val_msn) && val_msn.length != 0){
			alert('MSN的格式不正确');
			$(this).focus();
		}
	})		   
})
//控制毕业院校名长度
$(function(){
	$('#finishschool').keyup(function(){
		var val_school = $('#finishschool').val().replace(/\s/g,'');
		if(val_school.length >15){
			$('#finishschool').val(val_school.substring(0,15));
		}
	})		   
})
</script>
<body>
<div class="main">
	<?php include MooTemplate('system/header','public'); ?>
<!--头部结束-->
	<div class="content">
    <form name="register_stepthree" action="index.php?n=register&h=stepthree" method="post">
		<p class="c-title"><span class="f-000"><a href="index.php?n=service">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;会员注册&nbsp;&gt;&gt;&nbsp;详细资料</span><a href="#"></a></p>
		
			<div class="content-title">
				<span class="right-title">完善注册信息</span>
			</div><!--content-title end-->
			<div class="c-center">
			<div class="register-step"><p class="step02"><a href="#">基本资料</a><a href="#" class="in-on">详细资料</a><a href="#">生活态度</a><a href="#">兴趣爱好</a></p>
			<div class="clear"></div>
			</div>
			<h4>&nbsp;<span class="f-aeaeae">&gt;</span>&nbsp;详细资料：</h4>
			<dl class="c-center-data">
				<dt>真实姓名：</dt>
				<dd><p style="width:160px;"><input id="truename" name="truename"  value="<?php echo $userinfo['truename'];?>" type="text" class="c-center-data-text" onfocus="javascript:$(this).addClass('public');" onblur="javascript:$(this).removeClass('public');"/></p><p class="f-ed0a91">在征得您许可之前，真爱一生不会向任何人透露您的姓名。</p></dd>
				<dt>Q&nbsp;&nbsp;&nbsp;Q：</dt>
				<dd><p style="width:160px;"><input type="text" name="qq" id="qq" value="<?php echo isset($userinfo['qq'])?$userinfo['qq']:'';?>" class="c-center-data-text" onfocus="javascript:$(this).addClass('public');" onblur="javascript:$(this).removeClass('public');"/></p></dd>
				<dt>M&nbsp;S&nbsp;N：</dt>
				<dd><p style="width:160px;"><input type="text" name="msn" id="msn" value="<?php echo isset($userinfo['msn'])?$userinfo['msn']:'';?>" class="c-center-data-text" onfocus="javascript:$(this).addClass('public');" onblur="javascript:$(this).removeClass('public');"/></p></dd>
				<dt>性&nbsp;&nbsp;&nbsp;&nbsp;格：</dt>
					<dd>
						<p>
							<script>getSelect('','nature3','nature3','<?php echo isset($userinfo['nature'])?$userinfo['nature']:'';?>','1',nature);</script>
						</p>						
					</dd>
					<dt>体&nbsp;&nbsp;&nbsp;&nbsp;型：</dt>
					<dd>
						<p>
                        	<?php if($userinfo['gender'] == 0) { ?>
							  <script>getSelect('','body3','body3','<?php echo $userinfo['body'];?>','1',body0);</script>
							<?php } else { ?>
							<script>getSelect('','body3','body3','<?php echo $userinfo['body'];?>','1',body1);</script>
							<?php } ?>
							
						</p>						
					</dd>
					<dt>体&nbsp;&nbsp;&nbsp;&nbsp;重：</dt>
					<dd>
						<p>
                        <script>getSelect('selectSize','weight','weight','<?php echo $userinfo['weight'];?>','0',weight);</script>KG
						</p>						
					</dd>
					<dt>生&nbsp;&nbsp;&nbsp;&nbsp;肖：</dt>
					<dd>
						<p>
							 <script>getSelect('','animals','animals','<?php echo $userinfo['animalyear'];?>','0',animals);</script>
						</p>						
					</dd>
					<dt>星&nbsp;&nbsp;&nbsp;&nbsp;座：</dt>
					<dd>
						<p>
							<script>getSelect('','constellation','constellation','<?php echo $userinfo['constellation'];?>','0',constellation);</script>
						</p>						
					</dd>
					<dt>血&nbsp;&nbsp;&nbsp;&nbsp;型：</dt>
					<dd>
						<p>
							<script>getSelect('','bloodtype','bloodtype','<?php echo $userinfo['bloodtype'];?>','0',bloodtype);</script>
						</p>						
					</dd>
					<!--<dt>目前所在地：</dt>
					<dd>
						<p>
							 <script>getProvinceSelect43rds('','currentprovince','currentprovince','currentcity','','10100000');</script>
				   			 <script>getCitySelect43rds('','currentcity','currentcity','','');</script>
						</p>						
					</dd>
					<dt>籍贯所在地：</dt>
					<dd>
						<p>
							<script>getProvinceSelect43rds('','hometownProvince3','hometownProvince3','hometownCity3','<?php echo $userinfo['hometownprovince'];?>','10100000');</script>
					        <script>getCitySelect43rds('','hometownCity3','hometownCity3','<?php echo $userinfo['hometowncity'];?>','0');</script>
						</p>						
					</dd>-->
					<dt>期望交友地区1：</dt>
					<dd>
						<p>
							<script>getProvinceSelect43rds('','friendprovince[0]','friendprovince[0]','friendcity[0]','','10100000');</script>
				  			<script>getCitySelect43rds('','friendcity[0]','friendcity[0]','',''); </script> 
						</p>						
					</dd>
					<dt>期望交友地区2：</dt>
					<dd>
						<p>
							<script>getProvinceSelect43rds('','friendprovince[1]','friendprovince[1]','friendcity[1]','','10100000');</script>
				   			 <script>getCitySelect43rds('','friendcity[1]','friendcity[1]','','');</script>
						</p>						
					</dd>
					<dt>期望交友地区3：</dt>
					<dd>
						<p>
							<script>getProvinceSelect43rds('','friendprovince[2]','friendprovince[2]','friendcity[2]','','10100000');</script>
				  			<script>getCitySelect43rds('','friendcity[2]','friendcity[2]','','');</script>
						</p>						
					</dd>
					<dt>民&nbsp;&nbsp;&nbsp;&nbsp;族：</dt>
					<dd>
						<p>
							<script>getSelect('','stock3','stock3','<?php echo $userinfo['nation'];?>','1',stock);</script>
						</p>						
					</dd>
					<dt>宗教信仰：</dt>
					<dd>
						<p>
							<script>getSelect('','belief','belief','<?php echo $userinfo['religion'];?>','0',belief);</script>
						</p>						
					</dd>
					<dt>毕业院校：</dt>
					<dd><p style="width:160px;"><input id="finishschool" name="finishschool" value="<?php echo isset($userinfo['finishschool'])?$userinfo['finishschool']:'';?>" type="text" class="c-center-data-text" onfocus="javascript:$(this).addClass('public');" onblur="javascript:$(this).removeClass('public');"/></p>						
					</dd>
					<dt>兄弟姐妹：</dt>
					<dd>
						<p>
							<script>getSelect('','family','family','<?php echo $userinfo['family'];?>','1',family);</script>
						</p>						
					</dd>
					<dt>语言能力：</dt>
					<dd>
						<p style=" line-height:normal;padding-top:10px;">
							<script>getCheckbox('tonguegifts','tonguegifts[]',',<?php echo $userinfo['language'];?>,',tonguegifts);</script>
						</p>						
					</dd>
			</dl>
			<p class="p-bottom"><input name="register_submitthree" type="submit"  class="btn btn-primary go-on" value="保存并继续"/></p>
		<div class="clear"></div>
			</div><!--c-center-data end-->
			<div class="content-bottom">			
			</div>
         </form>  
			<!--content-bottom end-->
		<div class="clear"></div>
	</div><!--content end-->
	<?php include MooTemplate('system/footer','public'); ?>
</div><!--main end-->
</body>
</html>
