<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>真爱一生网--申请会员</title>
<link href="module/index/templates/default/css_add.css" rel="stylesheet" type="text/css" />
<?php include MooTemplate('system/js_css','public'); ?>
</head>

<body>

<?php include MooTemplate('system/header','public'); ?>
<div id="govip_content">
<?php if($s_cid =='30' && $user_arr['s_cid'] == '30') { ?>
	<div class="post">
<?php } else { ?>
	<div class="posts">
<?php } ?>
    	<ul>
		
			<?php if($s_cid =='30' && $user_arr['s_cid'] == '30') { ?>
        	 <li><a href="index.php?n=payment&h=add_money"><img src="module/index/templates/default/images/space.gif" /></a><img src="module/index/templates/default/images/addvip_gaoji.png" /></li>
			<?php } else { ?>
			 <li><a href="index.php?n=payment&h=platinum"><img src="module/index/templates/default/images/space.gif" /></a><img src="module/index/templates/default/images/govip_platinum.png" /></li>
            <li><a href="index.php?n=payment&h=diamond"><img src="module/index/templates/default/images/space.gif" /></a><img src="module/index/templates/default/images/govip_diamond.png" /></li>
            <li class="nobg"><a href="index.php?n=payment"><img src="module/index/templates/default/images/space.gif" /></a><img src="module/index/templates/default/images/govip_advanced.png" /></li>
			<?php } ?>
            <li><a href="javascript:void(0);" class="addMoney"><img src="module/index/templates/default/images/space.gif" /></a><img src="module/index/templates/default/images/addvip.png" /></li>
        </ul>
    </div>
    <!-- <div class="icon1"><img src="module/index/templates/default/images/govip_icon1.jpg" /></div>
    <div class="icon2"><img src="module/index/templates/default/images/govip_icon2.jpg" /></div>
    <div class="icon3"><img src="module/index/templates/default/images/govip_icon3.jpg" /></div> -->
</div>
<div  id ='login' style="display:none;">
<span style="float:right; margin:5px; cursor:pointer;" onclick="close_money()"><img src = 'module/index/templates/default/images/close.png' /></span>
   <form  action="index.php?n=payment&h=add_money_other" method="post" onsubmit="return checkform();">
				<div>金额(元):<input type="text" name="money" id = 'money' style="width:200px;"  /><span id = 'Mtips' style="margin-left:20px; "></span>
				</div>
				<div style="margin-top:25px;">支付明细:<input type="text" name="text" style="width:200px;" id = 'text' /><span id = 'Ytips' style="margin-left:20px;"></span>
				</div>
				<div style="font-size:12px; width:320px; margin:10px auto;">请描述您支付明细的原因，例如补款，预付 <br />仅支持汉语和汉语的，。标点</div>
				
				<input class="submit"  type="submit" value="" >
	</form>
</div>

<?php include MooTemplate('system/footer','public'); ?>
</body>
<script type="text/javascript">
$('.addMoney').click(function(){
    $('#login').fadeIn();
});


function close_money(){$('#login').fadeOut();}
function checkform(val){
	var pattern1=/^[1-9]\d{0,6}(\.\d{2,2})?$/;
	var pattern2= /^[\u4E00-\u9FA5|，|,|、]{2,30}$/;
	var money=document.getElementById('money').value;
	var text=document.getElementById('text').value;
	if(!pattern1.test(money)){
	    $('#Mtips').html('<img src="module/index/templates/default/images/false.png" />'); 
		return false;
	}
	
	if(!pattern2.test(text)){
	    $('#Ytips').html('<img src="module/index/templates/default/images/false.png" />'); 
		return false;
	}
	
	return true;

}

$('#money').focus(function(){
    $('#Mtips').html('');
})/*.blur(function(){
    if(!pattern1.test(money)){
	    $('#Mtips').html('<img src="module/index/templates/default/images/false.png" />'); 
	}else{
	    $('#Mtips').html('<img src="module/index/templates/default/images/ajaxok.gif" />'); 
	}
});
*/
$('#text').focus(function(){
    $('#Ytips').html('');
})/*.blur(function(){
    if(!pattern2.test(text)){
	    $('#Ytips').html('<img src="module/index/templates/default/images/false.png" />'); 
	}else{
	    $('#Ytips').html('<img src="module/index/templates/default/images/ajaxok.gif" />'); 
	}
});

*/


</script>
</html>
