<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $user['nickname']?$user['nickname']:'ID:'.$user['uid']?>的征婚交友信息——成就美好姻缘——真爱一生网</title>
<link rel="shortcut icon" href="public/default/images/favicon.ico" type="image/x-icon"/>
<link rel="icon" href="public/default/images/favicon.ico" type="image/x-icon">
<link href="public/default/css/global_import_new.css" rel="stylesheet" type="text/css" />
<link href="module/material/templates/default/skin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="public/system/js/fancybox/jquery.fancybox-1.2.5.css" media="screen" />
<?php include MooTemplate('system/js_css','public'); ?>
</head>
<script>
//控制选择皮肤,并且改变a链接

$(function(){
	$('input[id^=skiname]').click(function(){
		var skiname = $(this).val();
		//$('#loop_skin').attr('href','index.php?n=space&skiname='+skiname);		
		//$('#loop_skin2').attr('href','index.php?n=space&skiname='+skiname);
		$.cookie('skin', skiname); // 存储 cookie 
	})		   

})

//控制点击图片换肤
$(function(){
	$('img[id^=imgs]').each(function(i){
		$(this).click(function(){
			$('#skiname'+i).attr('checked','checked');
			var skiname = $('#skiname'+i).val();
			$.cookie('skin', skiname); // 存储 cookie 
			//$('#loop_skin').attr('href','index.php?n=space&skiname='+skiname);		
			//$('#loop_skin2').attr('href','index.php?n=space&skiname='+skiname);
		})			  
	})		   
})

//预览皮肤
function loop_skin(){
//	var skiname2 = $('input[id^=skiname]:checked').val();
//	alert(skiname2);
//	return false;
    var skiname=$.cookie('skin');
	
	if(!skiname){
		alert('请选择一个您喜欢的皮肤风格预览！');
		return false;
	}
	//window.location.href='index.php?n=space&skiname='+skiname;
	window.location.href='space_'+skiname+'.html';
}

//保存皮肤
function save_skin(){
    var skiname=$.cookie('skin');
	if(skiname){
		var url = 'ajax.php?n=material&h=save_skin&update_skin='+Math.random();
		$.get(url,{skiname:skiname},function(data_skin){
			if(/ok/.test(data_skin)){
				alert('保存成功！');
			}		 
		})
	}else{
		alert('请选择一个您喜欢的皮肤风格预览！');	
	}
}
</script>
<body>
<?php include MooTemplate('system/header','public'); ?>
<div class="main">
	<div class="content">
	<!-- <p class="c-title"><span class="f-000"><a href="index.php?n=service">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<?php if($uid != $userid) { ?><?php echo $user['nickname']?MooCutstr($user['nickname'], 12, ''):'ID:'.$user['uid']?><?php } else { ?>我<?php } ?>的个人主页</span><a href="#"></a></p> -->

	<div class="content-right">
		
		<div class="r-center-tilte">
		<span class="right-title f-ed0a91">个人主页皮肤设置</span>
		</div>
		
			<div class="right-box-in">
				<!-- <div class="skin-title"><a href="index.php?n=payment&h=diamond" class="go-dm" target="_blank"></a><a href="index.php?n=payment" class="go-hm" target="_blank"></a> -->
				<!-- <div class="clear"></div>				
				</div> -->	
				<div class="skinlist-box">
					<ul class="skinlist">
					<?php foreach((array)$skin_style as $n=>$v) {?>
						<li>
							<img src="<?php echo $v['image'];?>" id="imgs<?php echo $n;?>" width="150" height="100" />
							<span><input name="skiname" type="radio" value="<?php echo $v['skin_style'];?>" id="skiname<?php echo $n;?>"/><label for="skiname<?php echo $n;?>"><?php echo $v['skiname'];?></label></span>
						</li>
					<?php }?>
					</ul>	
					<div class="clear"></div>	
					<?php if($user['s_cid'] == 20 || $user['s_cid'] == 30) { ?>
						<p>
							<input type="button" class="btn btn-default fr" onclick="javascript:save_skin();return false;" value="保存 &gt;&gt;" />
							<input  type="button" class="btn btn-default fr" onclick="javascript:return loop_skin();" value="预览 &gt;&gt;" />
							<div class="clear"></div>
						</p>				
					<?php } ?>
				</div>					
			</div>
		
		<div class="r-center-bottom">
		</div>
	
		<div class="r-c-bottom-banner">
			 <?php if($GLOBALS['style_user_arr']['s_cid'] == 20 && $_GET['n']=='space') { ?>
				<a href="index.php?n=payment&h=city_star_intro" target="_blank"><img src="module/space/templates/default/images/citystar-banner.gif" /></a>
			 <?php } ?>
		</div>
	</div>
		<div class="clear"></div>
	</div>
	<?php include MooTemplate('system/footer','public'); ?>
</div><!--main end-->
 
</body>
</html>
