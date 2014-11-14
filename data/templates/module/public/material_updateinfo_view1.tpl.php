<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>资料设置——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link href="module/material/templates/<?php echo $GLOBALS['style_name'];?>/material.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="./public/system/js/sys1.js"></script>
<!-- <script type="text/javascript" src="module/register/templates/public/js/syscode.js?v=1"></script> -->
</head>
<script>
//控制提示信息
$(function(){
	$('input[type=text]').focus(function(){
		$(this).addClass('public');				   
	}).blur(function(){
		$(this).removeClass('public');			
	});	
})
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
		if(!/^([a-z0-9A-Z\._-]{1,})@([a-z0-9A-Z-]{1,})\.([a-z0-9A-Z\.]{1,})$/.test(val_msn) && val_msn.length != 0){
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
<?php include MooTemplate('system/header','public'); ?>
<div class="main">
	<div class="content">
	<!-- <p class="c-title"><span class="f-000"><a href="index.php?n=service">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;资料设置</span><a href="#"></a></p> -->
	<?php include MooTemplate('public/material_public_left','module'); ?>
	<!--=====左边结束===-->
	<form id="form1" name="form1" method="post" action="index.php?n=material&amp;h=detail">
    <div class="content-right">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
			<span class="right-title f-ed0a91">资料设置</span>
			</div>
			<div class="r-center-ccontent">
			<div class="register-step"><p class="step02"><a href="index.php?n=material&h=upinfo">基本资料</a><a href="index.php?n=material&h=upinfo&actio=1" class="in-on">详细资料</a><a href="index.php?n=material&h=upinfo&actio=2">生活态度</a><a href="index.php?n=material&h=upinfo&actio=3">兴趣爱好</a></p><span>填写进度：</span>
			<div class="clear"></div>
			</div>
			<h4>&nbsp;<span class="f-aeaeae">&gt;</span>&nbsp;详细资料：</h4>
			<dl class="c-center-data">
				<dt>真实姓名：</dt>
				<dd><p style="width:160px;"><input type="text" id="truename" name="truename"  value="<?php echo $userinfo['truename'];?>"  class="c-center-data-text" onfocus="javascript:$('#show_name').css('display','')" onblur="javascript:$('#show_name').css('display','none')"/></p><p id="show_name" class="register-clue" style="display:none;">未经您同意，我们绝不会向任何人透露您的姓名</p></dd>
				<dt>Q&nbsp;&nbsp;&nbsp;Q：</dt>
				<dd><p style="width:160px;"><input id="qq" type="text" name="qq"  value="<?php echo $userinfo['qq'];?>" class="c-center-data-text" onfocus="javascript:$('#show_qq').css('display','')" onblur="javascript:$('#show_qq').css('display','none')"/></p><p id="show_qq" class="register-clue" style="display:none;">让QQ把您和TA拉得更近吧</p></dd>
				<!-- <dt>M&nbsp;S&nbsp;N：</dt>
				<dd><p style="width:160px;"><input type="text" id="msn" name="msn" value="<?php echo $userinfo['msn'];?>" class="c-center-data-text" /></p></dd> -->
				<dt>性&nbsp;&nbsp;&nbsp;&nbsp;格：</dt>
					<dd>
						<p>
							<script>getSelect('','nature3','nature3','<?php echo $userinfo['nature'];?>','1',nature);</script>
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
							<script>getSelect('selectSize','weight','weight','<?php echo $userinfo['weight'];?>','0',weight);</script>
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
					<dt>目前所在地：</dt>
					<dd>
						<p>
							<script>getProvinceSelect66('','currentprovince','currentprovince','currentcity','<?php echo isset($userinfo['currentprovince'])?$userinfo['currentprovince']:0;?>','10100000');</script>
					        <script>getCitySelect66('','currentcity','currentcity','<?php echo isset($userinfo['currentcity'])?$userinfo['currentcity']:0;?>','0');</script>
						</p>						
					</dd>
					<dt>籍贯所在地：</dt>
					<dd>
						<p>
							<script>getProvinceSelect66('','hometownProvince3','hometownProvince3','hometownCity3','<?php echo $userinfo['hometownprovince'];?>','10100000');</script>
					        <script>getCitySelect66('','hometownCity3','hometownCity3','<?php echo $userinfo['hometowncity'];?>','0');</script>
						</p>						
					</dd>
                    <?php if(empty($friendprovince)) { ?>
					  	<?php for($i=0;$i<3;$i++) {?>
							<dt>期望交友地区<?php echo $i+1?>：</dt>
                            	<dd>
                                	<p>
									<script>getProvinceSelect66('','friendprovince[<?php echo $i;?>]','friendprovince[<?php echo $i;?>]','friendcity[<?php echo $i;?>]','','10100000');</script>
							        <script>getCitySelect66('','friendcity[<?php echo $i;?>]','friendcity[<?php echo $i;?>]','','0');</script>
							    	</p>
                                </dd>
						<?php } ?>
					  <?php } else { ?>
						  <?php foreach((array)$friendprovince as $key=>$value) {?>
						  
							  <?php $province = array_keys($value);?>
							  <?php $city = array_values($value);?>
							  <?php $province['0'] = isset($province['0']) && $province['0']>0 ? $province['0'] : 0;?>
							  <?php $city[0] = isset($city[0]) && $city[0]>0 ? $city[0] : 0;?>
							  <dt>期望交友地区<?php echo $key+1?>：</dt>
                              	  <dd>
                                        <p>
                                        <script>getProvinceSelect66('','friendprovince[<?php echo $key;?>]','friendprovince[<?php echo $key;?>]','friendcity[<?php echo $key;?>]','<?php echo $province[0];?>','10100000');</script>
                                        <script>getCitySelect66('','friendcity[<?php echo $key;?>]','friendcity[<?php echo $key;?>]','<?php echo $city[0];?>','0');</script>
                                        </p>
                                  </dd>
						  <?php }?>
					  <?php } ?>
					
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
					<dd><p style="width:160px;"><input id="finishschool" name="finishschool" value="<?php echo $userinfo['finishschool'];?>" type="text" class="c-center-data-text"/></p>		
                    </dd>
                    
					<dt>兄弟姐妹：</dt>
					<dd>
						<p>
							<script>getSelect('','family','family','<?php echo $userinfo['family'];?>','1',family);</script>
						</p>						
					</dd>
					<dt>语言能力：</dt>
					<dd>
						<p style="line-height:normal;padding-top:10px;margin-left:0px;">
							<script>getCheckbox66('tonguegifts','tonguegifts[]',',<?php echo $userinfo['language'];?>,',tonguegifts);</script>
						</p>						
					</dd>
			</dl>
			<p class="p-bottom">
            	<input name="" type="submit" class=" btn btn-primary go-on" value="保存并继续" />
				<input name="actio" type="hidden" id="actio" value="2" />
                <input type="hidden" name="issubmit" value="提交" />
            </p>
		<div class="clear"></div>
			</div>
			<div class="r-center-bottom">
			</div>
			<div class="clear"></div>
		</div>
        
	</div>
    </form>
		<div class="clear"></div>
	</div>
	
	<!--content end-->
	<?php include MooTemplate('system/footer','public'); ?>
</div><!--main end-->
</body>
</html>
