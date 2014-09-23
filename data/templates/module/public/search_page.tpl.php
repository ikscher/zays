<!DOCTYPE html>
<!--[if IE 7]>         <html class="ie7"> <![endif]-->
<!--[if IE 8]>         <html class="ie8"> <![endif]--> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>您的搜索结果——资料模式——真爱一生寻友——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>

<link href="module/search/templates/<?php echo $GLOBALS['style_name'];?>/search.css?v=13434" rel="stylesheet" type="text/css" />
<link href="module/search/templates/<?php echo $GLOBALS['style_name'];?>/h-search.css"  rel="stylesheet" type="text/css" />
<script src="module/search/templates/default/js/common.js" type="text/javascript"></script>
<script src="module/search/templates/default/js/addSearchConditions.js" type="text/javascript"></script>

</head>
<body>
<?php include MooTemplate('system/header','public'); ?>
<script type="text/javascript">
<?php $currenturl = $_SERVER["REQUEST_URI"];$currenturl2=preg_replace("/(&page=\d+)/","",$currenturl);?>
<?php if(stripos($currenturl2,'bothbelong') || stripos($currenturl2,'nickid') || stripos($currenturl2,'look')) { ?>
$(function(){
	$(".addscd").css("display","none");
})
<?php } ?>
</script>
<div class="content">
<!-- <div><img src="module/search/templates/default/images/qixi.jpg" /></div> -->
<div class="content-lift">
<div class="content-title">
<span class="right-title">搜索结果</span>
</div>
<!--content-title end-->
<div class="c-center" style="background:#FFF;">

<!-- search-left-title begin -->
<?php include MooTemplate('public/search_sort','module'); ?>
<!-- search-left-title  end -->

<!-- search-left-content -->
<?php include MooTemplate('public/search_page_left','module'); ?>
<!-- search-left-content  end-->
<?php if(!strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 6.0")) { ?>
<div class="content-bottom"></div>
<?php } else { ?>
<div style="border:1px solid #E8E5E5;"></div>
<?php } ?>
<!--content-bottom end-->
</div><!--centent-lift end-->
<!--左边结束-->
<!--右边开始-->
<?php include MooTemplate('public/search_right','module'); ?>
<!--右边结束-->
<?php include MooTemplate('system/footer','public'); ?>

</div><!--content end-->


</body>
</html>
