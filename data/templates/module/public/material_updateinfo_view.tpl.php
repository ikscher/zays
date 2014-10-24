<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>资料设置——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link href="module/material/templates/<?php echo $GLOBALS['style_name'];?>/material.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="./public/system/js/sys.js"></script>
<!-- <script type="text/javascript" src="module/register/templates/public/js/syscode.js?v=1"></script> -->
</head>
<script>

var vals2 = '';//存储自己写内心独白内容
var run = '';
var arr_num = new Array();//用数组统计所有错误信息
//控制内心独白
function menu(i){
	var me = $('#me-write').attr('class');   
	var others = $('#others-write').attr('class');
	if(i == 1){
		$('#me-write').attr('class','on-write');
		$('#others-write').attr('class','out-write');
		$('.our-say').css('display','none');
		$('.warning').css('display','none');
		$('.you-say').css('display','');
		$('#text').css('display','');
		
		//start---在当前我帮您写选项卡按钮下点击跳转自己写选项卡时，不让vals值有变动
		if(run != 1){
			vals2 = $('textarea[name=introduce]').val();
		}//---end
		$('textarea[name=introduce]').val(vals2);
		//start---在当前自己写选项卡下点击跳转我帮您写选项卡按钮或者点击本身时候，将及时更新vals2变量值
		run = 2;//---end
		
	}else if(i == 2){
		$('#me-write').attr('class','out-write');
		$('#others-write').attr('class','on-write');
		$('.our-say').css('display','');
		$('.warning').css('display','none');
		$('.you-say').css('display','none');
		$('#text').css('display','none');
		//start---已经在自己写选项卡按钮下些好了内心独白，当点击我帮您写选项卡按钮时，将及时更新vals2变量值
		if( run != 1){
			vals2 = $('textarea[name=introduce]').val();
		}//---end
	}
}
//获取文本域内容
function get_text(){
	var vals='';
	var hobby=$('input[name=hobby]').val().replace(/\s/g,""); 
	var pastime=$('input[name=pastime]').val().replace(/\s/g,"");
	var live=$('input[name=live]').val().replace(/\s/g,"");
	var nature=$('input[name=nature]').val().replace(/\s/g,"");
	if(hobby){
		vals+='我喜欢'+hobby+'。';
	}	
	if(pastime){
		vals+='我在业余时间最大的消遣'+pastime+'。';
	}
	if(live){
		vals+='我憧憬的生活是'+live+'。';	
	}
	if(nature){
		vals+='我希望我未来的另一半最好是'+nature+'。';
	}
	vals+='真心希望在这里遇到我的那个TA！';
	if(hobby || pastime || live || nature){
		$('textarea[name=introduce]').css('display','block').val(vals);
		$('.our-say').css('display','none');
		$('.warning').css('display','none');
		$('.you-say').css('display','');
		$('#text').css('display','');
		run=1;
	}else{
		$('.warning').css('display','');
	}
}

//内心独白限制字数
function textCounter(field,countfieldId,leavingsfieldId,maxlimit) {
	var countfield = document.getElementById(countfieldId);
	var leavingsfield = document.getElementById(leavingsfieldId);
	if (field.value.length > maxlimit) // if too long...trim it!
	{
		field.value = field.value.substring(0, maxlimit);
		alert(" 限" + maxlimit + "字内！");
	} else { 
		leavingsfield.innerHTML=maxlimit - field.value.length;
		countfield.innerHTML=field.value.length;
	}
}

//检测表单
function checkForm(){
	arr_num = new Array();//用数组统计所有错误信息
	var tel = $('#telphone').val();	
	
	var nickname = $('#nickname').val();
	//var introduce = $('#introduce').val();
	
	
	if(!nickname){
		arr_num[0] = '一个好的昵称可以表达自我，吸引更多人的注意';
	}else{
		arr_num[0] = '';	
	}
   
	if(tel == '' && !arr_num[1]){
		
		arr_num[1] = '手机号码便于我们及时将她的信息告诉给您';
	}
	

	/**
	if(introduce.replace(/\s/g,'').length < 30){
		arr_num[2] = '内心独白内容不能少于30字';
	}else if(!introduce){
		arr_num[2] = '内心独白有便于未来的TA更进一步了解您';
	}else{
		arr_num[2] = '';
	}
    */
	
	if(age1 < age2){
		arr_num[3] = '';
	}else if(age1 > age2){
		arr_num[3] = '您选择的择偶条件年龄范围不正确，请重新选择';
	}
	
	if(height1 < height2){
		arr_num[4] = '';
	}
	
	if(weight1 < weight2){
		arr_num[5] = '';
	}
	return prompt_err();
}

function prompt_err(){
	var val_html='注册信息错误提示：';
	var k=0;
	for(i=0;i<arr_num.length;i++){
		if(arr_num[i]){
			k=k+1;
			val_html+="<li>"+k+'.'+arr_num[i]+"</li>";
		}
		
	}
	
	if(val_html == '注册信息错误提示：'){
		
		return true;
	}else{
		
		$('#show_html').css('display','');
		$('#show_err').html(val_html);
		return false;
	}
}
function ischinese(str){//判断是否都是汉字
	 //var lst = /[u00-uFF]/;
	 var st = /.*[\u4e00-\u9fa5]+.*$/;
	 //var strlength = 0;
    str_chinese = 1;
    for(i = 0;i < str.length;i++){
   	 //strlength = strlength + 1;
   	  //if(){}

   	 if(st.test(str.charAt(i))){
   		 continue;
   	 }else{
   		 str_chinese = 0;
		 return str_chinese;
		
   	 }
   	 
    }
	 return str_chinese;  
}
function isch(str){//判断是否为汉字
	var st = /.*[\u4e00-\u9fa5]+.*$/;
	return st.test(str);	
}
function isnumber6(str){//连续六个数字0
	
	var Regx = /\d{6,}/;
	
	return Regx.test(str);
}
function strlen(str){//判断字符串长度
	
	var result='';
	var str = str.replace(/\s/g,'');
    var chin = ischinese(str);
    
    if(chin == 1){
       var chinlength = 0;
       for(i = 0;i < str.length;i++){
    	   chinlength = chinlength + 2;    	
       }
       
       if(chinlength > 12){
    	   var result = '中文昵称必须小于六个汉字';
       }
    }else if(isnumber6(str)){
    	
    	   var result = '昵称不能有六个连续的数字';
    }else{
    	var strlength = 0;
    	for(i = 0;i < str.length;i++){
    		if(isch(str.charAt(i))){
    			strlength = strlength + 2;
    		}else{
    			strlength = strlength + 1;
    		}
    		
    	}
    	if(strlength > 12){
    		var result = '昵称长度不能超过12字符';
    	}
    	
    }
    return result;
	
}

//修改手机号码的时候，检测是否已存在此手机号码
   $(function(){
		$('#telphone').blur(function(){
			var tel=$('#telphone').val();
			//var url='ajax.php?n=material&h=telphone&tel='+tel+'&updata='+Math.random();
			var url = 'index.php?n=material&h=telphone&update='+Math.random();
			$(this).removeClass('public');
			if(!tel){
				arr_num[1] = '';
				$('#red').removeClass('register-clue').removeClass('register-clue-w').html('');
			}else{
				$.get(url,{tel:tel},function(data){
								  //alert(data);
					if(data == '1'){
						$('#telphone').val('');
						$('#red').removeClass('register-clue').addClass('register-clue-w').html('这个号码已经存在了，请重新输入！');
						arr_num[1]='这个号码已经存在了，请重新输入';
					}else if(data == '3'){
						$('#red').removeClass('register-clue').addClass('register-clue-w').html('您输入的手机号码格式不正确!');
						
						arr_num[1]='您输入的手机号码格式不正确';
						//location.reload();
					}else if(data == '2' || data == '4'){
						arr_num[1] = '';
						//$('#telphone').attr('disabled','disabled');
						$('#red').removeClass('register-clue').removeClass('register-clue-w').html("<span style='color:red;' onclick=\"javascript:$('#telphone').attr('disabled','')\">修改</span>");
					}
				});
			}
		});		  
   });
   
   //控制昵称字数
   $(function(){
		var name;
		$('#nickname').keyup(function(){
			name = $('#nickname').val();
			result = strlen(name); 
			//if(name.replace(/\s/g,'').length > 12){
			//	alert('昵称长度不能超过12字符');	
			//	$('#nickname').val(name.substring(0,12));
			//}	   
			
			if(result!=''){
					alert(result);	
					$('#nickname').val(name.substring(0,12));
			}
			
		}).blur(function(){
			name = $('#nickname').val();
			if( name.search('@') != -1 || /^([0-9a-zA-Z_\-\.]{1,}[@][0-9a-zA-Z_\-\.]{1,}[\.][0-9a-zA-Z_\-\.]{1,})$/.test(name)){
				arr_num[6]='用户名格式不正确';
			}else{
				arr_num[6]='';	
			}
			if(/^[0-9]{1,}$/.test(name) || /^(([0-9]{5,}.+)|(.+[0-9]{5,}.+)|(.+[0-9]{5,}))$/.test(name)){
				arr_num[6]='用户名格式不正确';
			}else{
				arr_num[6]='';	
			}	
		})		  
   })
   //控制择偶年龄范围
   var age1 = '<?php echo $userchoice['age1'];?>',age2 = '<?php echo $userchoice['age2'];?>';
   $(function(){
		$('select[id=age1]').change(function(){
			age1 = $(this).val();
			if(age2 <= age1 && age1 != '0' && age2 != '0'){
				arr_num[3] = '年龄范围前者不能大于或者等于后者'; 
			}
		});	
		$('select[id=age2]').change(function(){
			age2 = $(this).val();
			if(age2 <= age1 && age1 != '0' && age2 != '0'){
				arr_num[3] = '年龄范围前者不能大于或者等于后者'; 
			}
		});	
   })
   
   //控制择偶身高范围
   var height1 = '<?php echo $userchoice['height1'];?>',height2 = '<?php echo $userchoice['height2'];?>';
   $(function(){ 
		$('#height1').change(function(){
			height1 = $(this).val();
			//height1 = $('#height1').val();
			//var len = $('#height1 option').size();
			if(height2 <= height1 && height1 != '0' && height2 != '0'){
				arr_num[4] = '身高范围前者不能大于或者等于后者'; 
			}
		});	
		$('#height2').change(function(){
			height2 = $(this).val();
			//height2 = $('#height2').val();
			if(height2 <= height1 && height1 != '0' && height2 != '0'){
				arr_num[4] = '身高范围前者不能大于或者等于后者'; 
			}
		});	
   })
   
   //控制择偶体重范围
   var weight1 = '<?php echo $userchoice['weight1'];?>',weight2 = '<?php echo $userchoice['weight2'];?>';
   $(function(){
		$('select[id=weight1]').change(function(){
			weight1 = $(this).val();
			if(weight2 <= weight1 && weight1 != '0' && weight2 != '0'){
				arr_num[5] = '体重范围前者不能大于或者等于后者'; 
			}
		});	
		$('select[id=weight2]').change(function(){
			weight2 = $(this).val();
			if(weight2 <= weight1 && weight1 != '0' && weight2 != '0'){
				arr_num[5] = '体重范围前者不能大于或者等于后者'; 
			}
		});	
   })
</script>
<body>
<div class="main">
	<?php include MooTemplate('system/header','public'); ?>
	<div class="content">
	<!-- <p class="c-title"><span class="f-000"><a href="index.php?n=service">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;资料设置</span><a href="#"></a></p> -->
	 <!--左边菜单-->
   <?php include MooTemplate('public/material_public_left','module'); ?>
    <!--content-lift end-->
	<!--=====左边结束===-->
	<div class="content-right">
		<div class="c-right-content">
			<div class="r-center-tilte">
			<a href="index.php?n=service" class="r-title-black">&lt;&lt;返回我的真爱一生</a>
			<span class="right-title f-ed0a91">资料设置</span>
			</div>
            <form id="form1" name="form1" method="post" action="index.php?n=material&h=upinfo" onsubmit="return checkForm(this);">
			<div class="r-center-ccontent">
			<div class="register-step"><p class="step01"><a href="index.php?n=material&h=upinfo" class="in-on">基本资料</a><a href="index.php?n=material&h=upinfo&actio=1">详细资料</a><a href="index.php?n=material&h=upinfo&actio=2">生活态度</a><a href="index.php?n=material&h=upinfo&actio=3">兴趣爱好</a></p><span>填写进度：</span>
			<div class="clear"></div>
			</div>
			<!--<h4>&nbsp;<span class="f-aeaeae">&gt;</span>&nbsp;手机验证码：</h4>
			<dl class="c-center-data">
				<dt><span class="f-ed0a91">*</span> 手机验证码：</dt>
				<dd><p style="width:160px;"><input name="" type="text" class="c-center-data-text" /></p><p class="f-ed0a91">手机验证码即将发送到您的手机，本验证不会收取您任何费用。</p></dd>
				<dt>&nbsp;</dt>
				<dd><p class="f-ed0a91">*手机验证码即将发送到您的手机，本验证不会收取您任何费用。</p></dd>
				<dt>&nbsp;</dt>
				<dd><p class="f-ed0a91">*手机验证码即将发送到您的手机，本验证不会收取您任何费用。</p></dd>
			</dl>
			<div class="clear"></div>-->
            <a name="person_message"></a>
			<h4>&nbsp;<span class="f-aeaeae">&gt;</span>&nbsp;基本资料：</h4>	
			<dl class="c-center-data">
				<dt><span class="f-f00">*</span>会员昵称：</dt>
				<dd><p style="width:160px;"><input name="nickname" id="nickname" type="text" value="<?php echo $userinfo['nickname'];?>" class="c-center-data-text" onfocus="javascript:$(this).addClass('public');$('#pet_name').css('display','')" onblur="javascript:$(this).removeClass('public');$('#pet_name').css('display','none')" /></p><p  id="pet_name" class="register-clue" style="display:none;font-size:12px">一个好的昵称可以表达自我，吸引更多人的注意</p></dd>
				<dt>手&nbsp;&nbsp;&nbsp;&nbsp;机：</dt>
				<dd><p style="width:160px;">
                <input id="telphone" name="telphone" value="<?php echo $userinfo['telphone'];?>" <?php if($userinfo['telphone']) { ?>disabled="disabled"<?php } ?> type="text" class="c-center-data-text" onfocus="javascript:$(this).addClass('public');$('#red').removeClass('register-clue-w').addClass('register-clue').html('手机号码便于我们及时将TA的信息告诉给您');"/></p>
				<p id="red" style="cursor:pointer;"><?php if($userinfo['telphone']) { ?> <span onclick="javascript:$('#telphone').attr('disabled','');" style="color:#F00">修改</span><?php } else { ?>填写您的手机号码，获得红娘免费牵线<?php } ?> 
                </p></dd>
                <dt>婚姻状况：</dt>
				<dd>
					<p>
						<script>getSelect('','marriage1','marriage1','<?php echo $userinfo['marriage'];?>','1',marriage);</script>
					</p>
				</dd>
				<dt>身&nbsp;&nbsp;&nbsp;&nbsp;高：</dt>
				<dd>
					<p>
						<script>getSelect('','height','height','<?php echo $userinfo['height'];?>','0',height);</script>
					</p>
				</dd>
                <dt>年&nbsp;&nbsp;&nbsp;&nbsp;龄：</dt>
				<dd>
					<p>
						<script>getYearsSelect('','year','year',"<?php echo $userinfo['birthyear'];?>",'');</script>年&nbsp;
                        <script>getMonthsSelect('','month','month',"<?php echo intval($userinfo['birthmonth'])?>",'');</script>月&nbsp;
                        <script>getDaysSelect('','days','days',"<?php echo intval($userinfo['birthday'])?>",'');</script>日
					</p>
				</dd>
				<dt>月&nbsp;收&nbsp;入：</dt>
				<dd>
					<p>
						<script>getSelect('','salary','salary','<?php echo $userinfo['salary'];?>','0',salary1);</script>
					</p>
				</dd>
                <dt>工作所在地：</dt>
				<dd>
					<p>
						<script>getProvinceSelect43rds('','province','province','city','<?php echo $userinfo['province'];?>','10100000');</script>
					    <script>getCitySelect43rds('','city','city','<?php echo $userinfo['city'];?>','0');</script>
					  
					</p>
				</dd>
				<dt>最高学历：</dt>
				<dd>
					<p>
						<script>getSelect('','education1','education1','<?php echo $userinfo['education'];?>','0',education);</script>
					</p>
				</dd>
				<dt>有无小孩：</dt>
				<dd>
					<p>
						<script>getSelect('','children1','children1','<?php echo $userinfo['children'];?>','0',children);</script>
					</p>
				</dd>
				<dt>住房情况：</dt>
				<dd>
					<p>
						<script>getSelect('','house','house','<?php echo $userinfo['house'];?>','0',house);</script>
					</p>
				</dd>
				<dt>您期望在多久时间内找到交往对象：</dt>
				<dd>
					<p>
						<script>getSelect('','expectlovedateid','oldsex','<?php echo $userinfo['oldsex'];?>','0',expectlovedate);</script> 
					</p>
					<p class="f-ed0a91">您的回答将不会出现在您的资料页，仅供真爱一生参考所用</p>
				</dd>
			</dl>
			<div class="clear"></div>
            <a name="choose_person"></a>
			<h4>&nbsp;<span class="f-aeaeae">&gt;</span>&nbsp;您的择偶条件：</h4>
			<dl class="c-center-data">
				<dt>性&nbsp;&nbsp;&nbsp;&nbsp;别：</dt>
					<dd>
						<p>
							<script>getSelectSex('','objectSex','sex','<?php echo $userinfo['gender'] ? 0:1;?>','-1',sex);</script>
						</p>						
					</dd>
				<dt>年&nbsp;&nbsp;&nbsp;&nbsp;龄：</dt>
					<dd>
						<p>
							<script>getSelect('','age1','age1','<?php echo $userchoice['age1'];?>','19',agebuxian);</script>
							至
							<script>getSelect('','age2','age2','<?php echo $userchoice['age2'];?>','25',agebuxian);</script>
						</p>						
					</dd>
				<dt>工作地区：</dt>
					<dd>
						<p>
							<script>getProvinceSelect43rds('','','workProvince','workCity2','<?php echo isset($userchoice['workProvince'])?$userchoice['workProvince']:0;?>','10100000');</script>

							<script>getCitySelect43rds('','workCity2','workCity','<?php echo isset($userchoice['workCity'])?$userchoice['workCity']:0?>','0');</script>
						</p>						
					</dd>
				<dt>婚姻状况：</dt>
					<dd>
						<p>
							<script>getSelect('','marriage2','marriage2','<?php echo $userchoice['marriage'];?>','1',marriage);</script>
						</p>						
					</dd>
				<dt>学&nbsp;&nbsp;&nbsp;&nbsp;历：</dt>
					<dd>
						<p>
							<script>getSelect('','education2','education2','<?php echo $userchoice['education'];?>','0',educationbuxian);</script>
						</p>						
					</dd>
				<dt>月&nbsp;&nbsp;&nbsp;&nbsp;薪：</dt>
					<dd>
						<p>
							<script>getSelect('','salary','salary1','<?php echo $userchoice['salary'];?>','0',salary1buxian);</script>
						</p>						
					</dd>
				<dt>有无小孩：</dt>
					<dd>
						<p>
							<script>getSelect('','children2','children2','<?php echo $userchoice['children'];?>','0',childrenbuxian);</script>
						</p>						
					</dd>
					<dt>身&nbsp;&nbsp;&nbsp;&nbsp;高：</dt>
					<dd>
						<p>
						<script>getSelect('','height1','height1','<?php echo $userchoice['height1'];?>','0',heightbuxian);</script>
						至
						<script>getSelect('','height2','height2','<?php echo $userchoice['height2'];?>','0',heightbuxian);</script><span style="color:#000; margin-left:5px;">厘米</span>
						</p>						
					</dd>
					<dt>有无照片：</dt>
					<dd>
						<p>
						  <label for="have"><input name="hasphoto" type="radio" id="have" value="1" <?php if($userchoice['hasphoto'] == 1) { ?>checked="checked"<?php } ?>/>有</label>&nbsp;&nbsp;
						  <label for="haveno"><input name="hasphoto" type="radio" id="haveno" value="2" <?php if($userchoice['hasphoto'] == 2) { ?>checked="checked"<?php } ?>/>无</label>
						</p>						
					</dd>
					<dt>性&nbsp;&nbsp;&nbsp;&nbsp;格：</dt>
					<dd>
						<p>
							<script>getSelect('','nature2','nature2','<?php echo $userchoice['nature'];?>','0',naturebuxian);</script>
						</p>						
					</dd>
					<dt>体&nbsp;&nbsp;&nbsp;&nbsp;型：</dt>
					<dd>
						<p>
							<?php if($userinfo['gender'] == 0) { ?>
		    					<script>getSelect('','body2','body2','<?php echo $userchoice['body'];?>','0',body1buxian);</script>
							<?php } else { ?>
							 	<script>getSelect('','body2','body2','<?php echo $userchoice['body'];?>','0',body0buxian);</script>
							<?php } ?>
						</p>						
					</dd>
					<dt>体&nbsp;&nbsp;&nbsp;&nbsp;重：</dt>
					<dd>
						<p>
							<script>getSelect('','weight1','weight1','<?php echo $userchoice['weight1'];?>','0',weightbuxian);</script>
          						到
          					<script>getSelect('','weight2','weight2','<?php echo $userchoice['weight2'];?>','0',weightbuxian);</script><span style="color:#000; margin-left:5px;">千克</span>
						</p>						
					</dd>
					<dt>职&nbsp;&nbsp;&nbsp;&nbsp;业：</dt>
					<dd>
						<p>
							<script>getSelect('','occupation2','occupation2','<?php echo $userchoice['occupation'];?>','0',occupationbuxian);</script>
						</p>						
					</dd>
					<dt>民&nbsp;&nbsp;&nbsp;&nbsp;族：</dt>
					<dd>
						<p>
							<script>getSelect('','stock2','stock2','<?php echo $userchoice['nation'];?>','0',stockbuxian);</script>
						</p>						
					</dd>
					<dt>是否想要孩子：</dt>
					<dd>
						<p>
							 <script>getSelect('','wantchildren2','wantchildren2','<?php echo $userchoice['wantchildren'];?>','0',wantchildrenbuxian);</script>
						</p>						
					</dd>
					<dt>户口地区：</dt>
					<dd>
						<p>
							<script>getProvinceSelect43rds('','hometownProvince2','hometownProvince2','hometownCity2','<?php echo isset($userchoice['hometownprovince'])?$userchoice['hometownprovince']:0;?>','10100000');</script>
          					<script>getCitySelect43rds('','hometownCity2','hometownCity2','<?php echo isset($userchoice['hometowncity'])?$userchoice['hometowncity']:0;?>','0');</script>
						</p>						
					</dd>
					<dt>是否吸烟：</dt>
					<dd>
						<p>
							<script>getSelect('','issmoking','issmoking','<?php echo $userchoice['smoking'];?>','-1',smokingbuxian);</script>
						</p>						
					</dd>
					<dt>是否喝酒：</dt>
					<dd>
						<p>
							<script>getSelect('','isdrinking','isdrinking','<?php echo $userchoice['drinking'];?>','-1',drinkingbuxian);</script>
						</p>						
					</dd>						
			</dl>
		<div class="clear"></div>
        <a name="person_introduce"></a>
		<h4>&nbsp;<span class="f-aeaeae">&gt;</span>&nbsp;内心独白：</h4>
		<div  class="register-clue-bottom">
				<div class="heart-say">
					<div class="heart-say">
					<div class="heart-say-title">
					  <span id="me-write" class="on-write" onclick="javascript:menu(1)">自己写</span>
					  <span id="others-write" class="out-write" onclick="javascript:menu(2)">我帮您写<i><img src="module/register/templates/default/images/new.gif" /></i></span>
						<a href="index.php?n=register&h=incenter" target="_blank">看看别人怎么写</a>
					</div>
                  <p id="text" style=" padding:10px 0;"><span class="f-f00">*</span><span class="f-ed0a91">为保证您的信息安全，请勿在填写内容中透露您的姓名和联系方式。</span></p>
				  <div class="warning" style="display:none">
					对未来的TA不想说点什么吗？请至少输入一项吧!
					</div>
			  		<div class="you-say" style="display:block">
						<textarea id="introduce" name="introduce" class="you-say-in"  onfocus="textCounter(this,'counter2','leavings2',1500);$(this).addClass('public')" onblur="textCounter(this,'counter2','leavings2',1500);$(this).removeClass('public')" onkeyup="textCounter(this,'counter2','leavings2',1500);"><?php echo $userchoice['introduce']?$userchoice['introduce']:$userchoice['introduce_check']?></textarea>
						<p style="padding:10px 0 20px">限30-1500字，目前已输入 <strong id="counter2" class="f-ed0a91">0</strong> 字，您还可以输入 <strong id="leavings2" class="f-ed0a91">1500</strong> 字。 </p>
					</div>
				  <div class="our-say" style="display:none">
					<div class="our-say-side"></div>
					<div class="our-say-center">
						<ul>
							<li><h6>先来简单作一下自我介绍吧：</h6></li>
							<li>我的兴趣爱好是<input name="hobby" type="text"  class="write-text" style="width:350px"/>。</li>
							<li>我在业余时间最大的消遣是<input name="pastime" type="text"  class="write-text" style="width:285px"/>。</li>
							<li>我憧憬的生活是<input name="live" type="text"  class="write-text" style="width:350px"/>。</li>
							<li><h6>我希望我未来的另一半最好满足以下条件：</h6></li>
							<li>TA的性格和品行最好是<input name="nature" type="text"  class="write-text" style="width:310px"/>。</li>
							<li>真心希望在这里遇到我的那个TA！</li>
							<li style="text-align:right;"><input name="button2" type="button" class="write-text-button" value="点击生成内心独白" onclick="javascript:get_text()" /></li>
						</ul>
					</div>
					<div class="our-say-side" style="background-position:bottom;"></div>
					</div>
				</div>
                    <div id="show_html" style="padding-bottom:20px;display:none">
                        <div class="r-clue-side"></div>
                        <div class="r-clue-center">
                            <b></b>
                            <ul id="show_err">
                                
                            </ul>
                        </div>				
                        <div class="r-clue-side" style="background-position:bottom;"></div>
                        <div class="clear"></div>
                    </div>
				</div>
				<p class="fleft">
                	<input type="hidden" name="issubmit" value="提交">
                    <input name="submit" type="submit" class="btn btn-primary go-on" value="确定提交" />
					<input name="actio" type="hidden" id="actio" value="1" />
                </p>
			</div>
				<div class="clear"></div>
			</div>
            </form>
			<div class="r-center-bottom">
			</div>
			<div class="clear"></div>
		</div>
	</div>
		<div class="clear"></div>
        
	</div>
	
	<!--content end-->
	<?php include MooTemplate('system/footer','public'); ?>
</div><!--main end-->
</body>
</html>
