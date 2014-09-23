<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>意见反馈——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link rel="stylesheet" type="text/css" href="public/<?php echo $GLOBALS['style_name'];?>/css/font.css">
<link href="module/about/templates/<?php echo $GLOBALS['style_name'];?>/about.css" type="text/css" rel="stylesheet" />
<script>
// 检测输入是否为空
function checkfeedbackform() {
	var complaint_type = $("#complaint_type").val();
	if(complaint_type == 1) {
		var text1 = $("#message1").val().replace(/(^\s+|\s+$)/g, '');
		var type1 = $("#type1").val();
		if(text1 == '' || type1 == 0) {
			alert("请选择反馈内容及填写您对网站功能评价的宝贵意见");
			return false;
		}
	} else {
		var text2 = $("#message2").val().replace(/(^\s+|\s+$)/g, '');
		var type2 = $("#type2").val();
		if(text2 == '' || type2 == 0) {
			alert("请选择反馈内容及填写完整您对真爱一生人工服务功能的意见");
			return false;
		}
		var fraction = $("select[name='fraction']").val();
		if(fraction == -1) {
			alert("请为您的专属真爱一生打分");
			return false;
		}
	}
	return true;
}

//字数
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
</script>

</head>
<body>
<div class="clear"></div>
<?php include MooTemplate('system/header','public'); ?>
 <!--头部结束-->
<div class="clear"></div>
<div class="content">
		<div class="c-title">
			<span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;投诉建议</span>
			<div class="loaction_right">
				<a href="index.php?n=invite" target="_blank">邀请好友</a>
			</div>
		</div>
			<div class="content-title">
			</div><!--content-title end-->
			<div class="c-center">
			<!--左边开始-->
			<?php include MooTemplate('public/about_left','module'); ?>
			<!--左边结束-->	
			<!--右边开始-->	
			<form  action="index.php?n=about&h=getsave" method="post" onsubmit="return checkfeedbackform()">
			<div class="about-right">
				<dl class="about-right-text">
					<dt style="padding-top:0px;">投诉建议</dt>
					<dd>您对真爱一生网提出的建议和意见是我们前进的动力，请在下面填写您需要评价的内容。</dd>
					<dt style="color:#000">
                    	请选项您要反馈的内容：
                        <select name="complaint_type" id="complaint_type">
                       		<option value="1" selected="selected">对网站功能的评价</option>
                            <option value="2">对真爱一生人工服务的评价</option>
                        </select>
                    </dt>
                    <script type="text/javascript">
						$(function() {
							$("#complaint_type option").each(function() {
								if($(this).val() == 1) {
									$(this).attr("selected", "selected");
								}
							});
							$("#complaint_type").change(function() {
								if($("#complaint_type").val() == "1") {
                                  $("#web").css("display", "block");
									$("#service").css("display", "none");
								} else {
									
									$.get("index.php?n=about&h=getsid", function(res) {
										if(res) {
											
											$("#service").css("display", "block");
											$("#web").css("display", "none");
										} else {
											alert("您还没有专属真爱一生");
											$("#web").css("display", "block");
											$("#service").css("display", "none");
											$("#complaint_type option").each(function() {
												if($(this).val() == 1) {
													$(this).attr("selected", "selected");
												}
											});
										}
									});
									
								}
							});
						});
                    </script>
                    <div id="web" style="display:block">
                        <div class="fontb">为了使您的意见尽快得到处理，请您选择意见的类型:
                        <select id="type1" name="type1">
                            <option value="0" selected>请选择</option>
                            <option value="1">表扬</option>
                            <option value="2">批评</option>
                            <option value="3">建议</option>
                        </select>
                        </div>
                        <div class="fontb">请输入信息：（限 500 字，目前已经输入<span class="d22224" id="counter1"> 0 </span>字，您还可以输入 <span class="d22224" id="leavings1">500</span> 字。）</div>
                        <div class="fontb">
                            <textarea id="message1" name="message1" class="getsave-text"  onfocus="textCounter(this,'counter1','leavings1',500);" onblur="textCounter(this,'counter1','leavings1',500);" onkeyup="textCounter(this,'counter1','leavings1',500);"></textarea>
                        </div>
                    </div>
                    <div id="service" style="display:none">
                        <div class="fontb">为了使您的意见尽快得到处理，请您选择意见的类型:
                        <select id="type2" name="type2">
                            <option value="0" selected>请选择</option>
                            <option value="1">表扬</option>
                            <option value="2">批评</option>
                            <option value="3">建议</option>
                        </select>
                        请为您的真爱一生打分：
                 		<select name="fraction">
                        	<option value="0">请选择</option>
                            <option value="1">1、非常不满意</option>
                            <option value="2">2、不满意</option>
                            <option value="3">3、一般</option>
                            <option value="4">4、满意</option>
                            <option value="5">5、非常满意</option>
                        </select>
                        </div>
                        <div class="fontb">请输入信息：（限 500 字，目前已经输入<span id="counter2" class="d22224"> 0 </span>字，您还可以输入 <span class="d22224" id="leavings2">500</span> 字。）</div>
                        <div class="fontb"><textarea id="message2" name="message2" class="getsave-text"  onfocus="textCounter(this,'counter2','leavings2',500);" onblur="textCounter(this,'counter2','leavings2',500);" onkeyup="textCounter(this,'counter2','leavings2',500);"></textarea></div>
                    </div>
				</dl>
				<input type="hidden" name="ispost" value="1" id="ispost">
				<input type="submit" class="btn btn-default getsave-butt" value="提交" />
			</div>
			</form>
			<!--右边结束-->	
			<div class="clear"></div>
		</div><!--c-center end-->
			<div class="content-bottom">			
			</div>
			<!--content-bottom end-->
		<div class="clear"></div>
	<?php include MooTemplate('system/footer','public'); ?>
	</div><!--content end-->
</body>
</html>
