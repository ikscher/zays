<style type="text/css">
#contact_float{
	border:1px solid #1C3F80;
	background:#CEDAEA;
	position:absolute;
	left:400px;
	top:200px;
	width:400px;
}
#contact_float1{
	border:1px solid #1C3F80;
	background:#CEDAEA;
	position:absolute;
	left:300px;
	top:200px;
	width:500px;
}
.over{
	background:#C4F0FF;
}
</style>
<script type="text/javascript">
//note 注册拖动
$(function(){
	//var myDrag=new Endrag("contact_movetop","contact_float",0,0); 
})
//note 关闭图层
var iscontact = 1;
$('.closeSMS').click(function(e){
    var e=e||window.event;
	$("#contact_float").remove();
	iscontact = 0;
});

function contact_hideDiv1(){
	$("#contact_float1").remove();
	iscontact = 0;
}
</script>


<?php if($type ==1) { ?>
<script type="text/javascript">
function callcenter(){
	<?php if($tel2) { ?>
	var tel = $("#tel").val();
	<?php } else { ?>
	var tel = '<?php if(strstr(iconv("gb2312","UTF-8",getphone($tel)),'合肥')) { ?>9<?php echo $tel;?><?php } else { ?>90<?php echo $tel;?><?php } ?>';
	<?php } ?>
	$("#telnum").html(tel);
	$("#callbtn").html("正在拨打...");
	parent.call(tel);
}
</script>
<div id="contact_float" style="top:180px;background-color:#3E679A; height:25px; width:290px; padding:3px;color:#FFF;font-weight:bold"><span id="contact_movetop" style="cursor:move" >拨打电话：</span><span id="telnum">
<?php if($tel2) { ?>
<select id="tel">
<option value="<?php if(strstr(iconv("gb2312","UTF-8",getphone($tel)),'合肥')) { ?>9<?php echo $tel;?><?php } else { ?>90<?php echo $tel;?><?php } ?>" 
selected="selected"><?php if(strstr(iconv("gb2312","UTF-8",getphone($tel)),'合肥')) { ?>9<?php echo $tel;?><?php } else { ?>90<?php echo $tel;?><?php } ?></option>
<option value="<?php if(strstr(iconv("gb2312","UTF-8",getphone($tel2)),'合肥')) { ?>9<?php echo $tel2;?><?php } else { ?>90<?php echo $tel2;?><?php } ?>">
<?php if(strstr(iconv("gb2312","UTF-8",getphone($tel2)),'合肥')) { ?>9<?php echo $tel2;?><?php } else { ?>90<?php echo $tel2;?><?php } ?></option>
</select>
<?php } else { ?><?php if(strstr(iconv("gb2312","UTF-8",getphone($tel)),'合肥')) { ?>9<?php echo $tel;?><?php } else { ?>90<?php echo $tel;?><?php } ?><?php } ?></span>&nbsp;&nbsp;&nbsp;<span id="callbtn"><input type="button" value="拨 打" onclick="callcenter()"/></span><div style="float:right; width:30px; position:absolute;top:2px; right:0px;"><a href="javascript:contact_hideDiv();" style=" color:#CCC">关闭</a></div></div>



<?php } elseif ($type==2) { ?>
<script type="text/javascript">
var sendnum=0;
function sendMes(){
	var tel = $("#tel").val();
	//var mes = $("#tel_message").val();
	var mes=document.getElementById('tel_message').value;
	/*if(mes.indexOf(' ') != -1){
		alert('不能有空格');
		return;
	}*/
	if(!mes || mes == "输入短信内容"){
		alert("短信内容不能为空");
		$("#tel_message").val("");
		$("#tel_message").focus();
		return;
	}
	$("#send_button").attr('disabled','disabled');
	var url = "./allmember_ajax.php?n=contact&uid=<?php echo $uid;?>&tel="+tel+"&type=2";
	$.post(url,{ispost:1,tel_message:mes},function(data){
		sendnum++;
		if(data==1){
			$("#sendret").html($("#sendret").html()+"<div style='margin-top:10px;'>&nbsp;"+sendnum+".&nbsp;(<span style='color:#090'>发送成功</span>)"+mes+"<div>");	
		}else if(data==0){
			$("#sendret").html($("#sendret").html()+"<div style='margin-top:10px;'>&nbsp;"+sendnum+".&nbsp;(<span style='color:#F00'>发送失败</span>)"+mes+"<div>");	
		}else{$("#sendret").html($("#sendret").html()+"<div style='margin-top:10px;'>&nbsp;"+sendnum+".&nbsp;(<span style='color:#F00'>你已经发过两条同样的短信了！</span>)"+mes+"<div>");}
		$("#send_button").attr('disabled','');
		$("#tel_message").val("");
	});
}

function checklen(){
	var mes = $("#tel_message").val();
	var len = mes.length;
	if(len > 63){
	var mesnum=Math.ceil(len/63);
		$("#tip").html("短信内容长度<span style='color:#F00'>"+len+"</span>字，<span style='color:#F00'>继续输入将会以"+mesnum+"条发送！</span>");	
	}else{
		$("#tip").html("短信内容长度<span style='color:#F00'>"+len+"</span>字");
	}
}

function changeColor(){
	$("#showarea div").mouseover(function(){
		$(this).addClass("over");
	}).mouseout(function(){
		$(this).removeClass("over");	
	})
}
changeColor();

var onoff = 0;
function opensms(){
	if(onoff==0){
		$("#smsarea").css("display","block");
		$("#onoff").html("关闭预设短信");
	}else{
		$("#smsarea").css("display","none");
		$("#onoff").html("展开预设短信");	
	}
	onoff = onoff ? 0:1;
}
function selectThis(v){
	$("#tel_message").val($("#sms_content_"+v).html());
	checklen();
}
function smsgopage(p){
	var url = './allmember_ajax.php?n=contact&gopage=1&type=2&page='+p;
	$.get(url,function(data){
		$("#showarea").html(data);
		changeColor();
	});	
}

$('input[name=rb]').click(function(){
    $('#send_button').removeAttr('disabled');
    var rid=$('input[name=replaceid]').val();
	if(!/^\d{8,8}([,，和、]\d{8,8})*$/.test(rid) && !/^\d{3,4}$/.test(rid)){
	    alert('输入的格式不正确(多个ID中间只能以 逗号 和 、隔开，红娘编号只能是3-4位数字)');
		return false;
	}
	
	var content=$('#tel_message').val();
	
	if(/^\d{8,8}([,，和、]\d{8,8})*$/.test(rid)){  content=content.replace(/\d{8,8}([,，和、]\d{8,8})*/g,rid);}
	if(/^\d{3,4}$/.test(rid)){ content=content.replace(/\d{3,4}号/g,rid+'号');}
	
	$('#tel_message').val(content);
	
});



//以下部分实现弹出层的拖拽效果
var posX;
var posY;
Idiv=document.getElementById('contact_movetop');
f=document.getElementById('contact_float');
Idiv.style.cursor = "move";//鼠标样式
Idiv.onmousedown=function(e){
	if(!e) e = window.event; //IE
	posX = e.clientX - parseInt(f.offsetLeft);
	posY = e.clientY - parseInt(f.offsetTop);
	document.onmousemove =function(ev){
	    var ev=ev||window.ev;
		f.style.left=ev.clientX - posX +'px';
		f.style.top =ev.clientY - posY +'px';
		return false;
	}
	
	document.onmouseup = function(){
		document.onmousemove = null;
		document.onmouseup=null;
	}
	
}





 function stopBubble(e){
	//一般用在鼠标或键盘事件上
	if(e && e.stopPropagation){
		//W3C取消冒泡事件
		e.stopPropagation();
	}else{
		//IE取消冒泡事件
		window.event.cancelBubble = true;
	}
};

</script>
<div id="contact_float" style="top:180px;">
    <div  class="closeSMS"  style="width:30px;height:30px;z-index:100;display:inline-block; position:absolute;top:-15px; right:-25px;cursor:pointer;background:url(./templates/images/fancy_closebox.png) no-repeat;"></div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr id="contact_movetop" >
		
		<th height="29" style="background-color:#3E679A;text-align:left;"><span style="color:#FFF;">
		<?php if(empty($tel2) && empty($tel)) { ?>
		<input type="text" id="tel" />
		<?php } else { ?>
		<?php if($tel2) { ?><select id="tel"><option value="<?php echo $tel;?>" selected="selected"><?php echo $tel;?></option><option value="<?php echo $tel2;?>"><?php echo $tel2;?></option></select><?php } else { ?><select id="tel"><option value="<?php echo $tel;?>" selected="selected"><?php echo $tel;?></option></select><?php } ?>
		<?php } ?>
		</span>
		<span style="color:#FFF;"><input name="replaceid" style="width:150px;" type="text" value="" /><input type="button" name="rb" value="替换" /></span>
		</th>
		
		</tr>
	<tr>
		<td><textarea name="tel_message" id="tel_message" style="width:394px; height:60px;max-width:394px;" disabled="disabled"  onfocus="if(this.value=='输入短信内容') this.value=''" onkeyup="checklen();">输入短信内容</textarea></td>
		</tr>
	<tr>
		<td><input name="按钮" id="send_button" type="button" value="发 送"  disabled="disabled" onclick="sendMes()"/><span id="tip"></span><span style="float:right; padding-top:5px;"><a id="onoff" href="javascript:opensms();">展开预设短信</a></span></td>
		</tr>
</table>
<div id="sendret"></div>
<div id="smsarea" style="display:none;">
	<div id="showarea">
	<?php foreach((array)$sms as $k=>$v) {?>
		<?php $content = str_replace('[adminid]',$GLOBALS['adminid'],$v['content']);?>       
        <?php //note 第一个匹配是否有短信标题提醒，客服什么情况下发这条短信，第二个匹配发短信内容
        preg_match("/\[smstitle\](.+?)\[\/smstitle\]/isU",$content,$matche1);preg_match("/\[\/smstitle\](.+?)/isU",$content,$matche2);?>
        <?php if(isset($matches[1])) { ?>
        <div style="padding:3px; cursor:pointer;" onclick="selectThis(<?php echo $k;?>);"><?php echo $k+1;?>.<font color="#0033CC"><?php echo $matche1[1];?>:</font><span id="sms_content_<?php echo $k;?>"><?php echo $matche2[1];?></span></div>
        <?php } else { ?>
		<div style="padding:3px; cursor:pointer;" onclick="selectThis(<?php echo $k;?>);"><?php echo $k+1;?>.<span id="sms_content_<?php echo $k;?>"><?php echo $content;?></span></div>
        <?php } ?>
	<?php }?>
	</div>
	<?php if($pages>=2) { ?><div style="text-align:center">第&nbsp;<?php for($i=1;$i<=$pages;$i++) echo '<a href="javascript:smsgopage('.$i.');">&nbsp;'.$i .'&nbsp;</a>';?>&nbsp;页</div><?php } ?>
</div>
</div>

<?php } elseif ($type==3) { ?>
<script type="text/javascript">
function UpdateMsg(filename,newfilename,filesize){//此函数用来提供给提交到的页面如upload.ashx输出js的回调，更新当前页面的信息
   if(filename==''){alert('未上传文件！');return false;}
   document.getElementById('ajaxMsg').innerHTML += "文件名："+filename+" 文件大小："+filesize+"字节  <br></div>";
   $("#newfilenamelist").html($("#newfilenamelist").html()+newfilename+" ");
   $("#filenamelist").html($("#filenamelist").html()+filename+" ");
   $("#filesizelist").html($("#filesizelist").html()+filesize+" ");
   $("#finput").html('<input id="upfile" type="file" name="upfile" value="" style="width:325px;"><input type="submit" value="上传附件">');
}
function notUpload(stat,fileext){
	if(stat == '2'){
		alert("不允许上传"+fileext+"文件");
		return false;
	}
	if(stat == '3'){
		alert(fileext+"文件无法识别，请联系管理员");
		return false;
	}
}
var sendnum=0;
function sendMail(){
	if( errsender ){
		alert("发件人不正确");
		return;
	}
	var from = $("#from").val();
	var to = $("#to").val();
	var title = $("#title").val();
	var content = $("#content").val();
	var newfilename = $("#newfilenamelist").html();
	var filename = $("#filenamelist").html();
	var filesize = $("#filesizelist").html();
	var is_sendmsg = $("#is_sendmsg").attr("checked");
	sendmsg = 0;
	if(is_sendmsg){
		var sendmsg=1;
	}
	if( !title || !content){
		alert("标题件内容不能为空");
		return;
	}
	if( title == "输入标题" || content == "输入内容"){
		alert("请输入标题和内容");
		return;
	}
	var url = "./allmember_ajax.php?n=contact&uid=<?php echo $uid;?>&username=<?php echo isset($username) ? $username : '';?>&type=3";
	$.post(url,{ispost:1,from:from,to:to,title:title,content:content,filename:filename,newfilename:newfilename,filesize:filesize,sendmsg:sendmsg},function(data){
		alert(data);
		if(data==1){
			sendnum++;
			$("#mailtip").html($("#mailtip").html()+"<br/>"+sendnum+". (发送成功) "+ title);
			$("#title").val("");
			$("#content").val("");
		    $("#newfilenamelist").html("");
		    $("#filenamelist").html("");
		    $("#filesizelist").html("");
		    $("#ajaxMsg").html("<br>");
		    $("#upfile").val("");
		    $("#finput").html('<input id="upfile" type="file" name="upfile" value=""><input type="submit" value="上传附件">');
		}else{
			$("#mailtip").html("发送失败");
		}
	})
}
function changeSender(){
	var str = '<input id="newvalue" type="text" value="'+$("#sender").html()+'" style="width:80px;" onblur="changeValue()"/>';
	$("#sender").html(str);
	$("#sender").removeAttr("href");
}
var errsender = false;
function changeValue(){
	var value = $("#newvalue").val();
	if(!parseInt(value)){
		alert("错误的发件人ID");
		//$("#newvalue").focus();
		errsender = true;
		return;
	}
	$.getJSON('./allmember_ajax.php?n=get_muli_uidinfo&uid='+value,function(data){
		if(data == 'no'){
			alert('此会员不存在');
			$("#muli_info").css("display","none");
			return;
		}else if(data == 'mis'){
			alert('您不可以模拟本站注册会员');
			$("#muli_info").css("display","none");
			$("#sender").html('<input id="newvalue" type="text" value="" style="width:80px;" onblur="changeValue()"/>');
			$("#sender").removeAttr("href");
			$("#newvalue").focus();
			return;
		}else{
			$("#muli_uid").html(data.uid);
			$("#muli_nickname").html(data.nickname);
			$("#muli_info").css("display","");
		}
	})
	errsender = false;
	$("#from").val(value);
	$("#sender").html(value);
	$("#sender").attr("href","javascript:changeSender()");
}
</script>
<div id="contact_float">
    <div  class="closeSMS"  style="width:30px;height:30px;z-index:100;display:inline-block; position:absolute;top:-15px; right:-25px;cursor:pointer;background:url(./templates/images/fancy_closebox.png) no-repeat;"></div>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr id="contact_movetop" >
		<th height="29" style="background-color:#3E679A;">
			<b>
				<span style="color:#FFF;">
				发站内信(FROM:
				<?php if($from !=0) { ?>
					<?php echo $from;?>
				<?php } else { ?>
					<a id="sender" style="color:#FFF; text-decoration:underline" href="javascript:changeSender();">红娘<?php echo $GLOBALS['adminid'];?></a>
				<?php } ?>
				&nbsp;&nbsp;
				TO:<?php echo $to ? $to : $uid?>)</span>
				</b>
				<div id="muli_info" style="color:yellow;display:none;">您模拟的用户ID为<span id="muli_uid"></span>，昵称为<span id="muli_nickname"></span>,请确认</div>
				<input name="from" type="hidden" id="from" value="<?php if(isset($other)) { ?><?php echo $other;?><?php } else { ?><?php echo $from;?><?php } ?>" />
				<input name="to" type="hidden" id="to" value="<?php echo $to ? $to : $uid?>" />
			    <!-- <div style="float:right; width:30px; position:absolute;top:2px; right:0px;"><a href="javascript:contact_hideDiv();" style=" color:#CCC">关闭</a></div> -->
		</th>
	</tr>
	<tr>
		<td height="26">
			<input name="title" type="text" id="title" style="width:394px" onfocus="if(this.value=='输入标题')this.value=''" value="输入标题"/>
		</td>
	</tr>
	<tr>
		<td>
			<textarea name="content" id="content" style="width:394px; height:150px;" onfocus="if(this.value=='输入内容')this.value=''" onkeyup="if(this.value.length>1200)alert('站内信内容不能超过1200个字')">输入内容</textarea>
		</td>
	</tr>
	<?php if($from ==0) { ?>		
	<tr>
		<td>
		  <div id="ajaxMsg"><br/></div>
		  <div id="newfilenamelist" style="display:none"></div> 
		  <div id="filenamelist" style="display:none"></div> 
		  <div id="filesizelist" style="display:none"></div>  
      	</td>
	</tr>	
	<tr>
		<td>		
			<iframe name="ajaxifr" style="display:none;"></iframe>
			<form action="allmember_ajax.php?n=uploadfile" method="post" target="ajaxifr" enctype="multipart/form-data">
			<div id="finput">
			 <input id="upfile" type="file" name="upfile" value="" style="width:325px;"/>
			 <input type="submit" value="上传附件" />
			 </div>
			</form> 
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td>
			<input type="button" value="发 送" onclick="sendMail()" />
			<input type="checkbox" value="1" name="is_sendmsg" id="is_sendmsg" />短信提醒
		</td>
	</tr>
</table>
<div id="mailtip"></div>
</div>



<?php } elseif ($type==4) { ?>
<script type="text/javascript">
function cert(change,a){
	var url = './allmember_ajax.php?n=contact&uid=<?php echo $uid;?>&type=4&change='+change+'&a='+a;
	var str = a ? '已通过' : '未通过';
	$.get(url,function(data){
		if(data=='ok'){
			$("#"+change+"_tip").html(str+"更改成功");
		}else{
			$("#"+change+"_tip").html(str+"更改失败");
		}	   
	})
}
</script>
<div id="contact_float" style="top:100px;">
<div  class="closeSMS"  style="width:30px;height:30px;z-index:100;display:inline-block; position:absolute;top:-15px; right:-25px;cursor:pointer;background:url(./templates/images/fancy_closebox.png) no-repeat;"></div>
	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr id="contact_movetop" >
		<th height="30" colspan="2" style="background-color:#3E679A;"><b><span style="color:#FFF;">会员认证(<?php echo $uid;?>)</span></b>
		<!-- <div style="float:right; width:30px; position:absolute;top:2px; right:0px;"><a href="javascript:contact_hideDiv();" style=" color:#CCC">关闭</a></div> -->
		</th>
	</tr>
	<tr>
		<td width="35%" height="30" align="right" valign="bottom">手机认证：</td>
		<td width="65%" valign="bottom"><span id="telphone_tip"><?php if($cert['telphone']) { ?>已通过<?php } else { ?>未通过<?php } ?></span>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('telphone',1)" name="telphone" type="radio" value="1" <?php if($cert['telphone']) { ?>checked="checked"<?php } ?> />已通过</label>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('telphone',0)" name="telphone" type="radio" value="0" <?php if(!$cert['telphone']) { ?>checked="checked"<?php } ?> />未通过</label></td>
	</tr>
	<tr>
		<td width="35%" height="30" align="right" valign="bottom">邮箱认证：</td>
		<td width="65%" valign="bottom"><span id="email_tip"><?php if($cert['email']=='yes') { ?>已通过<?php } else { ?>未通过<?php } ?></span>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('email',1)" name="email" type="radio" value="1" <?php if($cert['email']=='yes') { ?>checked="checked"<?php } ?> />已通过</label>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('email',0)" name="email" type="radio" value="0" <?php if(!$cert['email']=='yes') { ?>checked="checked" <?php } ?>/>未通过</label></td>
	</tr>
	<tr>
		<td width="35%" height="30" align="right" valign="bottom">视频认证：</td>
		<td width="65%" valign="bottom"><span id="video_tip"><?php if($cert['video_check']==3) { ?>已通过<?php } else { ?>未通过<?php } ?></span>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('video',1)" name="video" type="radio" value="1" <?php if($cert['video_check']==3) { ?>checked="checked" <?php } ?>/>已通过</label>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('video',0)" name="video" type="radio" value="0" <?php if($cert['video_check']!=3) { ?>checked="checked" <?php } ?>/>未通过</label></td>
	</tr>
	<tr>
		<td width="35%" height="30" align="right" valign="bottom">身份通认证：</td>
		<td width="65%" valign="bottom"><span id="sms_tip"><?php if($cert['sms']) { ?>已通过<?php } else { ?>未通过<?php } ?></span>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('sms',1)" name="sms" type="radio" value="1" <?php if($cert['sms']) { ?>checked="checked"<?php } ?> />已通过</label>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('sms',0)" name="sms" type="radio" value="0" <?php if(!$cert['sms']) { ?>checked="checked"<?php } ?> />未通过</label></td>
	</tr>
	<tr>
		<td align="right" height="30" valign="bottom">身份证认证：</td>
		<td valign="bottom"><span id="identity_tip"><?php if($cert['identity_check']==3) { ?>已通过<?php } else { ?>未通过<?php } ?></span>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('identity',1)" name="identity" type="radio" value="1" <?php if($cert['identity_check']==3) { ?>checked="checked" <?php } ?>/>已通过</label>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('identity',0)" name="identity" type="radio" value="0" <?php if($cert['identity_check']!=3) { ?>checked="checked" <?php } ?>/>未通过</label></td>
	</tr>
	<tr>
		<td align="right"  height="30" valign="bottom">婚育认证：</td>
		<td valign="bottom"><span id="marriage_tip"><?php if($cert['marriage_check']==3) { ?>已通过<?php } else { ?>未通过<?php } ?></span>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('marriage',1)" name="marriage" type="radio" value="1" <?php if($cert['marriage_check']==3) { ?>checked="checked" <?php } ?>/>已通过</label>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('marriage',0)" name="marriage" type="radio" value="0" <?php if($cert['marriage_check']!=3) { ?>checked="checked" <?php } ?>/>未通过</label></td>
	</tr>
	<tr>
		<td align="right"  height="30" valign="bottom">学历认证：</td>
		<td valign="bottom"><span id="education_tip"><?php if($cert['education_check']==3) { ?>已通过<?php } else { ?>未通过<?php } ?></span>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('education',1)" name="education" type="radio" value="1" <?php if($cert['education_check']==3) { ?>checked="checked" <?php } ?>/>已通过</label>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('education',0)" name="education" type="radio" value="0" <?php if($cert['education_check']!=3) { ?>checked="checked" <?php } ?>/>未通过</label></td>
	</tr>
	<tr>
		<td align="right"  height="30" valign="bottom">工作认证：</td>
		<td valign="bottom"><span id="occupation_tip"><?php if($cert['occupation_check']==3) { ?>已通过<?php } else { ?>未通过<?php } ?></span>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('occupation',1)" name="occupation" type="radio" value="1" <?php if($cert['occupation_check']==3) { ?>checked="checked" <?php } ?>/>已通过</label>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('occupation',0)" name="occupation" type="radio" value="0" <?php if($cert['occupation_check']!=3) { ?>checked="checked" <?php } ?>/>未通过</label></td>
	</tr>
	<tr>
		<td align="right"  height="30" valign="bottom">工资认证：</td>
		<td valign="bottom"><span id="salary_tip"><?php if($cert['salary_check']==3) { ?>已通过<?php } else { ?>未通过<?php } ?></span>&nbsp;&nbsp;&nbsp;
			<label><input onclick="cert('salary',1)" name="salary" type="radio" value="1" <?php if($cert['salary_check']==3) { ?>checked="checked" <?php } ?>/>已通过</label>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('salary',0)" name="salary" type="radio" value="0" <?php if($cert['salary_check']!=3) { ?>checked="checked" <?php } ?>/>未通过</label></td>
	</tr>
	<tr>
		<td align="right"  height="30" valign="bottom">房产认证：</td>
		<td valign="bottom"><span id="house_tip"><?php if($cert['house_check']==3) { ?>已通过<?php } else { ?>未通过<?php } ?></span>&nbsp;&nbsp;&nbsp;
			<label><input onclick="cert('house',1)" name="house" type="radio" value="1" <?php if($cert['house_check']==3) { ?>checked="checked" <?php } ?>/>已通过</label>&nbsp;&nbsp;&nbsp;<label><input onclick="cert('house',0)" name="house" type="radio" value="0" <?php if($cert['house_check']!=3) { ?>checked="checked" <?php } ?>/>未通过</label></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
</div>

<?php } elseif ($type==5) { ?>
<!--  彩信-->
<script type="text/javascript">
function userarr(number,arrayobj) {
	var arrname = arrayobj;
		for(i=0;i<arrayobj.length;i++) {
			var valueArray  = arrayobj[i].split(",");
			if(valueArray[0] == number) {
				if(valueArray[0] == '0' && valueArray[1] != '男士') {
					return ("未选择");
				} else {
					return (valueArray[1]);
				}
			}
	}
}

var sendnum=0;
function sendMes(){
	var tel = $("#tel").val();
	if(!tel){alert("没有手机号");return;}
	var mes=document.getElementById('tel_message').value;
	var title=document.getElementById('mms_title').value;
	var uid_list_pic=document.getElementById('uid_list_pic').value;
	var user_image_list=document.getElementById('uid_list_image').value;
	if(!title || title == "输入彩信标题"){
		alert("输入彩信标题");
		$("#mms_title").val("");
		$("#mms_title").focus();
		return;
	}
	if(!mes || mes == "输入彩信文字"){
		alert("输入彩信文字");
		$("#tel_message").val("");
		$("#tel_message").focus();
		return;
	}
	if(!uid_list_pic){
		alert("请添加图片！");
		return;
	}
	$("#send_button").attr('disabled','disabled');
	var url = "./allmember_ajax.php?n=contact&uid=<?php echo $uid;?>&tel="+tel+"&type=5";
	$.post(url,{ispost:1,tel_message:mes,title:title,pic_id:uid_list_pic,image_list:user_image_list},function(data){
		sendnum++;
		if(data==1){
			$("#sendret").html($("#sendret").html()+"<div style='margin-top:10px;'>&nbsp;"+sendnum+".&nbsp;(<span style='color:#090'>发送成功</span>)"+title+"<div>");			
			$("#tel_message").val("");
			$("#mms_title").val("");
			$("#uid_list_pic").val("");
			$("#uid_list_image").val("");
			$("#show_user_pic").html("");
		}else if(data==0){
			$("#sendret").html($("#sendret").html()+"<div style='margin-top:10px;'>&nbsp;"+sendnum+".&nbsp;(<span style='color:#F00'>发送失败</span>)"+title+"<div>");			
		}else{
			$("#sendret").html($("#sendret").html()+"<div style='margin-top:10px;'>&nbsp;"+sendnum+".&nbsp;(<span style='color:#F00'>"+data+"</span>)"+title+"<div>");		
		}
		$("#send_button").attr('disabled','');
	});
}

function checklen(){
	var mes = $("#tel_message").val();
	var len = mes.length;
	if(len >600){
		$("#tip").html("短信内容长度"+len+"字，请不要发送过多的文字！");	
	}else{
		$("#tip").html("");
	}
}

function selectThis(v){
	$("#tel_message").val($("#sms_content_"+v).html());
	$("#mms_title").val($("#sms_title_"+v).html());
}

function check_member_inf(uid,kinds){
	if(uid==<?php echo $uid;?>){
		alert("不可以把本人推荐给本人！");
		$("#user_inf_alts").val("");return;
	}
	var url="./allmember_ajax.php?n=check_searchid&id="+uid+"&kinds="+kinds;
	$.getJSON(url,function(data){
		if(data['0']=='no'){$("#user_inf_alts").html("无此会员!");}else if(data['0'].pic_name==''){
			$("#user_inf_alts").html("没有形象照！");
		}else{
			if(data['0'].gender==1){var gen="女"}else{var gen="男"}
			$("#user_inf_alts").html(data['0'].nickname+"("+gen+")");
			var age = '<?php echo date("Y")?>'-data['0'].birthyear;
			var image_list='';
			$("#uid_list_image").val('');
			if(data['3']){			
				for(var i=0;i<data['3'].length;i++){	
					image_list+="<ul class='pht2' id='image_"+uid+i+"'><li><a href='#' onclick=del_one(1,"+uid+i+",'"+data['3'][i]+"')><img alt='点击删除' src='"+data['3'][i]+"'></a></li></ul>";
					$("#uid_list_image").val($("#uid_list_image").val()+data['3'][i]+',');
				}
			}
			$("#show_user_pic").html("<ul class='pht' id='img_pic_"+uid+"'><li><a href='#'  id='img_pic_"+uid+"' onclick='del_one(2,"+uid+")'><img alt='点击删除' src='"+data['2']+"'></a></li><li>"+userarr(data['0'].province,provice)+","+userarr(data['0'].city,city)+"</li><li>("+age+")岁</li></ul>"+image_list);
			$("#uid_list_pic").val(uid+',');
			$("#mms_title").val(data['4'].title);
			<?php $content = str_replace('[adminid]',$GLOBALS['adminid'],$v['content']);?>
			if(kinds=='rose'){
				var content=data['4'].content.replace('[send_rose]',uid);	
			}else if(kinds=='leer'){
				var content=data['4'].content.replace('[send_leer]',uid);	
			}else if(kinds=='contact'){
				var content=data['4'].content.replace('[send_contact]',uid);	
			}else if(kinds=='friend'){
				var content=data['4'].content.replace('[send_friend]',uid);	
			}else if(kinds=='comment'){
				var content=data['4'].content.replace('[send_comment]',uid);	
			}
			
			$("#tel_message").val(content);
		}
	});
}
//删除指定照片
function del_one(type,pic_uid,image_name){
	if(type==1){
		$("#image_"+pic_uid).remove();
		var pic_uid_list= $("#uid_list_image").val();
		
		var pic_uid_arr = pic_uid_list.split(',');
		new_picid_arr = new Array();
		for(var i=0,n=0;i<pic_uid_arr.length;i++){
			if(pic_uid_arr[i]!=image_name){
				new_picid_arr[n++]=pic_uid_arr[i];
			}
		}
		var new_picid_list=new_picid_arr.join(",");
		$("#uid_list_image").val(new_picid_list);
	}else{
		$("#show_user_pic").html('');
		$("#uid_list_pic").html('');
		$("#uid_list_image").html('');
	}
}
//note 菜单切换
function mms_menu(a){
	$("td[id^=mmsitem]").css("display","none");
	$("td[id^=mmsitem"+a+"]").css("display","block");
	$("li[id^=mmsmenu]").attr("class","");
	$("#mmsmenu"+a).attr("class","currentLink");
	//note 鲜花
	if(a==1) mms_get('comment',a);
	//note 鲜花
	if(a==2) mms_get('rose',a);
	//note 委托
	if(a==3) mms_get('contact',a);
	//note 秋波
	if(a==4) mms_get('leer',a);
	//note 意中人
	if(a==5) mms_get('friend',a);	
}
function mms_get(n,a){
//	if( $("#item"+a).html() != "" ) return;
	$("#mmsitem"+a).html("<div style='text-align:center'><br/>页面加载中...</div>");	
	var url = './allmember_ajax.php?n=mms_getact&kinds='+n+'&uid=<?php echo $uid;?>';
	$.get(url,function(data){
		$("#mmsitem"+a).html(data);
	});
}
</script>
<style>
img{ border:0;}
.pht,.pht li,.pht2,.pht2 li{ padding:0; margin:0; list-style:none}
.pht{
	text-align:center;
	line-height:18px;
	font-size:12px;
	padding:5px;
	float:left;
	}
.pht2,.pht2 li{float:left; list-style:none}
</style>
<div id="contact_float1" style="top:180px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr id="contact_movetop" style="cursor:move;" >
		<th height="29" style="background-color:#3E679A;"><b><span style="color:#FFF;">发送彩信<?php if($tel2) { ?><select id="tel"><option value="<?php echo $tel;?>" selected="selected"><?php echo $tel;?></option><option value="<?php echo $tel2;?>"><?php echo $tel2;?></option></select><?php } else { ?><select id="tel"><option value="<?php echo $tel;?>" selected="selected"><?php echo $tel;?></option></select><?php } ?></span></b>
		<div style="float:right; width:30px; position:absolute;top:2px; right:0px;"><a href="javascript:contact_hideDiv1();" style=" color:#CCC">关闭</a></div></th>
		</tr>
		<tr><td>发送彩信标题 : <input name="mms_title" type="text" id="mms_title" size="56" value="输入彩信标题" onfocus="if(this.value=='输入彩信标题') this.value=''"></input></td></tr>
		<tr>
		<td><textarea name="tel_message" id="tel_message" style="width:475px; height:100px;" onfocus="if(this.value=='输入彩信文字') this.value=''" onkeyup="checklen();">输入彩信文字</textarea></td>
		</tr>
		
		<tr><td id="show_user_pic"></td><input  type="hidden" id="uid_list_pic" ><input type="hidden" id="uid_list_image"></tr>		
	<tr>
		<td><input name="按钮" id="send_button" type="button" value="发 送" onclick="sendMes()"/><span id="tip"></span></td>
		</tr>
		<tr><td id="user_inf_alts" style="color:red;"></td></tr>
		<tr><td id="sendret"></td></tr>
		<tr><td>
		<div class="list-div">
			<div class="userOtherFun">
				<ul>
				 <!--  <li id="mmsmenu1" class="currentLink"><a href="javascript:mms_menu(1)">评价(<?php echo $send_comment['num'];?>人)</a></li> -->
				  <li id="mmsmenu2"><a href="javascript:mms_menu(2)">鲜花(<?php echo $send_rose['num'];?>人)</a></li>
				  <li id="mmsmenu3"><a href="javascript:mms_menu(3)">委托(<?php echo $send_contact['num'];?>人)</a></li>
				  <!-- <li id="mmsmenu4"><a href="javascript:mms_menu(4)">秋波(<?php echo $send_leer['num'];?>人)</a></li> -->
				  <!-- <li id="mmsmenu5"><a href="javascript:mms_menu(5)">意中人(<?php echo $send_friend['num'];?>人)</a></li> -->
			  </ul>
			</div>
		</div></td>
		</tr>

		<!--评价begin-->
		<tr ><td id="mmsitem1" style="width:98%"></td></tr>
		<!--评价end-->
		<!--鲜花begin-->
		<tr ><td id="mmsitem2" style="width:98%"></td></tr>
		<!--鲜花end-->
		<!--委托begin-->
		<tr ><td id="mmsitem3" style="width:98%"></td></tr>
		<!--委托end-->
		<!--秋波begin-->
		<tr ><td id="mmsitem4" style="width:98%"></td></tr>
		<!--秋波end-->		
		<!--意中人begin-->
		<tr ><td id="mmsitem5" style="width:98%"></td></tr>
		<!--意中人end-->
</table>
</div>
<?php } ?>