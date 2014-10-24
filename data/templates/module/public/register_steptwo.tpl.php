<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员注册--基本资料填写——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>

<link href="module/register/templates/<?php echo $GLOBALS['style_name'];?>/css/register-info.css" rel="stylesheet" type="text/css" />

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



//表单验证
function checkForm(){
	var telphonemack = $('#telphonemack').val();
	var introduce = $('#introduce').val();
	var nickname = $('#nickname').val();
	

	if(nickname){
		arr_num[0] = '';
	}else{
		arr_num[0] = '昵称不能为空';	
	}
	
	if(arr_num){
		return prompt_err();
	}
}
	


function prompt_err(){
	var val_html='注册信息错误提示：';
	var k=0;
	for(i=0;i<arr_num.length;i++){
		if(arr_num[i]){
			k=k+1;
			val_html+= '<li>'+k+'.'+arr_num[i]+'</li>';
		}
	}
	if(val_html == '注册信息错误提示：'){
		$('#show_err').css('display','none');
		return true;
	}else{
		$('#show_err').css('display','');
		$('#html_ul').html(val_html);	
		return false;
	}
	
}

//控制昵称字数
   $(function(){
		var name;	  
		$('#nickname').keyup(function(){
			name = $('#nickname').val();
			if(name.replace(/\s/g,'').length > 12){
				$('#nickname').val(name.substring(0,12));
			}	   
		}).blur(function(){
			name = $('#nickname').val();
			if( name.search('@') != -1 || /^([0-9a-zA-Z_\-\.]{1,}[@][0-9a-zA-Z_\-\.]{1,}[\.][0-9a-zA-Z_\-\.]{1,})$/.test(name)){
				arr_num[3]='用户名格式不正确';
			}else{
				arr_num[3]='';	
			}
			if(/^[0-9]{1,}$/.test(name) || /^(([0-9]{5,}.+)|(.+[0-9]{5,}.+)|(.+[0-9]{5,}))$/.test(name)){	
				arr_num[3]='用户名格式不正确';
			}else{
				arr_num[3]='';	
			}
		})		  
   })
   

//控制择偶身高范围
	var height1,height2;
	$(function(){
		$('select[id=height1]').change(function(){
			height1 = $(this).val();
			height2 = Number(height1)+4;
			$('select[id=height2] option[value='+height2+']').attr('selected','selected');
		});	
	})
	
//控制择偶年龄范围
   var age1=19,age2=25;
   $(function(){
		$('select[id=age1]').change(function(){
			age1 = $(this).val();

			age2 =Number(age1)+1;
			$('#age2 option[value='+age2+']').attr('selected','selected');
		});	

   })


//控制择偶体重范围
   var weight1,weight2;
   $(function(){
	   $('select[id=weight1]').change(function(){
			weight1 = $(this).val();

			weight2 = Number(weight1)+4;
			$('select[id=weight2] option[value='+weight2+']').attr('selected','selected');
	   });	

   })
</script>
<body>
<div class="main">
	<?php include MooTemplate('system/header','public'); ?>
<!--头部结束-->
	<div class="content">
		<p class="c-title"><span class="f-000"><a href="index.php?n=service">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;会员注册&nbsp;&gt;&gt;&nbsp;基本资料</span><a href="#"></a></p>
		<FORM action="index.php?n=register&h=steptwo" method="post" onSubmit="return checkForm();"> 
			<div class="content-title">
				<span class="right-title">完善注册信息</span>
			</div><!--content-title end-->
			<div class="c-center">
			<div class="register-step"><p class="step01"><a href="#" class="in-on">基本资料</a><a href="#">详细资料</a><a href="#">生活态度</a><a href="#">兴趣爱好</a></p>
			<div class="clear"></div>
			</div>
		
			<div class="clear"></div>
			<h4>&nbsp;<span class="f-aeaeae">&gt;</span>&nbsp;基本资料：</h4>	
			<dl class="c-center-data">
				<dt><span class="f-ed0a91">*</span> 会员昵称：</dt>
				<dd><p style="width:160px;"><input name="nickname" id="nickname" type="text" value="<?php echo isset($members_search['nickname'])?$members_search['nickname']:''?>" class="c-center-data-text" onfocus="javascript:$(this).addClass('public');$('#pet_name').css('display','')" onblur="javascript:$(this).removeClass('public');$('#pet_name').css('display','none')"/></p><p id="pet_name" class="register-clue" style="display:none;font-size:12px">一个好的昵称可以表达自我，吸引更多人的注意</p></dd>
				<dt>婚姻状况：</dt>
				<dd>
					<p>
					  <script>getSelect('','marriage1','marriage1','<?php echo isset($members_search['marriage'])?$members_search['marriage']:''?>','1',marriage);</script>
					</p>
				</dd>
				<dt>身&nbsp;&nbsp;&nbsp;&nbsp;高：</dt>
				<dd>
					<p>
					  <script>getSelect('','height','height','<?php echo isset($members_search['height'])?$members_search['height']:''?>','0',height);</script>厘米
					</p>
				</dd>
				<dt>月&nbsp;收&nbsp;入：</dt>
				<dd>
					<p>
					  <script>getSelect('','salary','salary','<?php echo isset($members_search['salary'])?$members_search['salary']:''?>','0',salary1);</script>
					</p>
				</dd>
				<dt>最高学历：</dt>
				<dd>
					<p>
					  <script>getSelect('','education1','education1','<?php echo isset($members_search['education'])?$members_search['education']:''?>','0',education);</script>
					</p>
				</dd>
				<dt>有无小孩：</dt>
				<dd>
					<p>
					  <script>getSelect('','children1','children1','<?php echo isset($members_search['children'])?$members_search['children']:''?>','0',children);</script>
					</p>
				</dd>
				<dt>住房情况：</dt>
				<dd>
					<p>
					  <script>getSelect('','house','house','<?php echo isset($members_search['house'])?$members_search['house']:''?>','0',house);</script>
					</p>
				</dd>
				<dt>您期望在多久时间内找到交往对象：</dt>
				<dd>
					<p>
					  <script>getSelect('','expectlovedateid','oldsex','<?php echo isset($members_base['oldsex'])?$members_base['oldsex']:''?>','0',expectlovedate);</script>
					</p>
					<p class="f-ed0a91">您的回答将不会出现在您的资料页，仅供真爱一生参考所用</p>
				</dd>
                
			</dl>
            <div class="clear"></div>
			<h4>&nbsp;<span class="f-aeaeae">&gt;</span>&nbsp;您的择偶条件：</h4>
			<dl class="c-center-data">
				<dt>性&nbsp;&nbsp;&nbsp;&nbsp;别：</dt>
					<dd>
						<p>
						  <script>getSelectSex('','objectSex','sex','<?php echo $userinfo['gender'] ? 0 : 1;?>','-1',sex);</script>
						</p>						
					</dd>
				<dt>年&nbsp;&nbsp;&nbsp;&nbsp;龄：</dt>
					<dd>
						<p>
						  <script>getSelect('','age1','age1','<?php echo isset($members_choice['age1'])?$members_choice['age1']:''?>','19',agebuxian);</script>
							至
						  <script>getSelect('','age2','age2','<?php echo isset($members_choice['age2'])?$members_choice['age2']:''?>','25',agebuxian);</script>
						</p>						
					</dd>
				<dt>工作地区：</dt>
					<dd>
						<p>
						
						  <script>getProvinceSelect43rds('','','workProvince','workCity3','<?php echo isset($members_choice['workProvince'])?$members_choice['workProvince']:''?>','10100000');</script>
				   		  <script>getCitySelect43rds('','workCity3','workCity','<?php echo isset($members_choice['workcity'])?$members_choice['workcity']:''?>','0');</script> 
                        </p>						
					</dd>
				<dt>婚姻状况：</dt>
					<dd>
						<p>
						  <script>getSelect('','marriage2','marriage2','<?php echo isset($members_choice['marriage'])?$members_choice['marriage']:''?>','1',marriage);</script>
						</p>						
					</dd>
				<dt>学&nbsp;&nbsp;&nbsp;&nbsp;历：</dt>
					<dd>
						<p>
						  <script>getSelect('','education2','education2','<?php echo isset($members_choice['education'])?$members_choice['education']:''?>','0',educationbuxian);</script>
						</p>						
					</dd>
				<dt>月&nbsp;&nbsp;&nbsp;&nbsp;薪：</dt>
					<dd>
						<p>
						  <script>getSelect('','salary','salary1','<?php echo isset($members_choice['salary'])?$members_choice['salary']:''?>','0',salary1buxian);</script>
						</p>						
					</dd>
				<dt>有无小孩：</dt>
					<dd>
						<p>
						  <script>getSelect('','children2','children2','<?php echo isset($members_choice['children'])?$members_choice['children']:''?>','0',childrenbuxian);</script>
						</p>						
					</dd>
					<dt>身&nbsp;&nbsp;&nbsp;&nbsp;高：</dt>
					<dd>
						<p>
						  <script>getSelect('','height1','height1','<?php echo isset($members_choice['height1'])?$members_choice['height1']:''?>','0',heightbuxian);</script>
							至
						<script>getSelect('','height2','height2','<?php echo isset($members_choice['height2'])?$members_choice['height2']:''?>','0',heightbuxian);</script>厘米
						</p>						
					</dd>
					<dt>有无照片：</dt>
					<dd>
						<p>
						  <input name="hasphoto" type="radio" value="1" <?php if(isset($members_choice['hasphoto'])) { ?><?php if($members_choice['hasphoto'] == 1) { ?>checked="checked"<?php } ?><?php } ?>/>有
						  <input name="hasphoto" type="radio" value="2" <?php if(isset($members_choice['hasphoto'])) { ?><?php if($members_choice['hasphoto'] == 2) { ?>checked="checked"<?php } ?><?php } ?>/>无
						</p>						
					</dd>
					<dt>性&nbsp;&nbsp;&nbsp;&nbsp;格：</dt>
					<dd>
						<p>
						  <script>getSelect('','nature2','nature2','<?php echo isset($members_choice['nature'])?$members_choice['nature']:''?>','0',naturebuxian);</script>
						</p>						
					</dd>
					<dt>体&nbsp;&nbsp;&nbsp;&nbsp;型：</dt>
					<dd>
						<p>
							<?php if($userinfo['gender'] == 0) { ?>
						  <script>getSelect('','body2','body2','<?php echo isset($members_choice['body'])?$members_choice['body']:''?>','0',body1buxian);</script>
							<?php } else { ?>
						  <script>getSelect('','body2','body2','<?php echo isset($members_choice['body'])?$members_choice['body']:''?>','0',body0buxian);</script>
							<?php } ?>
						</p>						
					</dd>
					<dt>体&nbsp;&nbsp;&nbsp;&nbsp;重：</dt>
					<dd>
						<p>
						  <script>getSelect('','weight1','weight1','<?php echo isset($members_choice['weight1'])?$members_choice['weight1']:''?>','0',weightbuxian);</script>
          						到
   						  <script>getSelect('','weight2','weight2','<?php echo isset($members_choice['weight2'])?$members_choice['weight2']:''?>','0',weightbuxian);</script><span style="color:#000; margin-left:5px;">千克</span>
						</p>						
					</dd>
					<dt>职&nbsp;&nbsp;&nbsp;&nbsp;业：</dt>
					<dd>
						<p>
						  <script>getSelect('','occupation2','occupation2','<?php echo isset($members_choice['occupation'])?$members_choice['occupation']:''?>','0',occupationbuxian);</script>
						</p>						
					</dd>
					<dt>民&nbsp;&nbsp;&nbsp;&nbsp;族：</dt>
					<dd>
						<p>
						  <script>getSelect('','stock2','stock2','<?php echo isset($members_choice['nation'])?$members_choice['nation']:''?>','0',stockbuxian);</script>
						</p>						
					</dd>
					<dt>是否想要孩子：</dt>
					<dd>
						<p>
						  <script>getSelect('','wantchildren2','wantchildren2','<?php echo isset($members_choice['wantchildren'])?$members_choice['wantchildren']:''?>','0',wantchildrenbuxian);</script>
						</p>						
					</dd>
					<dt>户口地区：</dt>
					<dd>
						<p>
						  <script>getProvinceSelect43rds('','hometownProvince2','hometownProvince2','hometownCity2','<?php echo isset($members_choice['hometownprovince'])?$members_choice['hometownprovince']:''?>','10100000');</script>
       					  <script>getCitySelect43rds('','hometownCity2','hometownCity2','<?php echo isset($members_choice['hometowncity'])?$members_choice['hometowncity']:''?>','0');</script>
						</p>						
					</dd>
					<dt>是否吸烟：</dt>
					<dd>
						<p>
						  <script>getSelect('','issmoking','issmoking','<?php echo isset($members_choice['smoking'])?$members_choice['smoking']:''?>','-1',smokingbuxian);</script>
						</p>						
					</dd>
					<dt>是否喝酒：</dt>
					<dd>
						<p>
						  <script>getSelect('','isdrinking','isdrinking','<?php echo isset($members_choice['drinking'])?$members_choice['drinking']:''?>','-1',drinkingbuxian);</script>
						</p>						
					</dd>
                    <dt style="text-align:left">&nbsp; 
                	
                </dt>
			</dl>
		<div class="clear"></div>
            		<h4>&nbsp;<span class="f-aeaeae">&gt;</span>&nbsp;内心独白：</h4>
		<div  class="register-clue-bottom">
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
						<textarea id="introduce" name="introduce" class="you-say-in"  onfocus="textCounter(this,'counter2','leavings2',1500);$(this).addClass('public')" onblur="textCounter(this,'counter2','leavings2',1500);$(this).removeClass('public')" onkeyup="textCounter(this,'counter2','leavings2',1500);"><?php echo isset($members_introduce['introduce_check'])?$members_introduce['introduce_check']:''?></textarea>
						<p style="padding:10px 0 20px">限30-1500字，目前已输入 <strong id="counter2" class="f-ed0a91">0</strong> 字，您还可以输入 <strong id="leavings2" class="f-ed0a91">1500</strong> 字。 </p>
					</div>
				  <div class="our-say" style="display:none">
					<div class="our-say-side"></div>
					<div class="our-say-center">
						<ul>
							<li><h6>先来简单作一下自我介绍吧：</h6></li>
							<li>我的兴趣爱好是<input name="hobby" type="text"  class="write-text" style="width:355px"/>。</li>
							<li>我在业余时间最大的消遣是<input name="pastime" type="text"  class="write-text" style="width:285px"/>。</li>
							<li>我憧憬的生活是<input name="live" type="text"  class="write-text" style="width:355px"/>。</li>
							<li><h6>我希望我未来的另一半最好满足以下条件：</h6></li>
							<li>TA的性格和品行最好是<input name="nature" type="text"  class="write-text" style="width:315px"/>。</li>
							<li>真心希望在这里遇到我的那个TA！</li>
							<li style="text-align:right;"><input name="button2" type="button" class="write-text-button" value="点击生成内心独白" onclick="javascript:get_text()" /></li>
						</ul>
					</div>
					<div class="our-say-side" style="background-position:bottom;"></div>
					</div>
				</div>
				<div  id="show_err" style="display:none">
                    <div class="r-clue-side"></div>
                    <div  class="r-clue-center">
                        <b></b>
                        <ul id="html_ul">
                        </ul>
                    </div>				
                    <div class="r-clue-side" style="background-position:bottom;"></div>
				</div>
				<p class="fleft"><input id="register_submittwo" name="register_submittwo" type="submit"  class="btn btn-primary go-on" value="保存并继续"/></p>
			</div>

			</div><!--c-center-data end-->
			<div class="content-bottom"></div>			
            </form>
			<!--content-bottom end-->
		<div class="clear"></div>
	</div><!--content end-->
	<?php include MooTemplate('system/footer','public'); ?>
</div><!--main end-->
</body>
</html>
