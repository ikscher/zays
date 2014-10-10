<meta name="keywords" content="真爱一生,真爱一生网,红娘,征婚,征婚网,婚介,婚恋,婚姻,交友,交友网,相亲,征婚,姻缘,牵线,寻爱" />
<meta name="description" content="真爱一生网是由专业的红娘团队以及情感专家组成的严肃正规的婚恋网站, 针对城市白领提供专业的婚恋服务，帮助单身会员组成美满幸福家庭。" />
<link rel="shortcut icon" href="public/default/images/favicon.ico" type="image/x-icon"/>
<link rel="icon" href="public/default/images/favicon.ico" type="image/x-icon">
<script type="text/javascript" src="public/system/js/jquery-1.8.3.min.js"></script>
<link href="public/default/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="public/default/css/bootstrap-ie6.css" />

<link rel="stylesheet" type="text/css" href="public/default/css/ie.css" />

<![endif]-->
<script src="public/system/js/bootstrap.min.js" type="text/javascript"></script>
<?php if(!isset($diamond) || $diamond!=1) { ?>
<link rel="stylesheet" type="text/css" href="public/<?php echo $GLOBALS['style_name'];?>/css/global_import_new.css">
<?php } ?>


<!--浮动DIV的js-->
<?php if($GLOBALS['MooUid'] && MooGetGPC('h','string')!='return'&&MooGetGPC('h','string')!='pay'&& MooGetGPC('h','string') != 'picflash' &&  MooGetGPC('h','string') != 'makepic' &&MooGetGPC('h','string') != 'history' && MooGetGPC('h','string') != 'chat') { ?>
<script type="text/javascript" src="public/system/js/jquery.floatDiv.js"></script>
<script type="text/javascript" src="public/system/js/rightbottom.js?v=1"></script>
<script type="text/javascript" src="public/system/js/jquery.cookie.js"></script>

<?php } ?>
<!--浮动DIV的js-->
<script src="public/system/js/sys3.js"></script>
<script type="text/javascript">
  function userdetail(number,arrayobj) {
  	var arrname = arrayobj;
	number = number ? number : 0;
  	for(i=0;i<arrayobj.length;i++) {
		var valueArray  = arrayobj[i].split(",");
		if(valueArray[0] == number) {
			if(valueArray[0] == '0' && valueArray[1] != '男士') {
				document.write("不限");
			} else {
				document.write(valueArray[1]);
			}
		}
  	}
  }
</script>
