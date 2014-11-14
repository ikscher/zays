<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>您的搜索结果——相册模式——真爱一生寻友——真爱一生网</title>
<?php include MooTemplate('system/js_css','public'); ?>
<link href="module/search/templates/<?php echo $GLOBALS['style_name'];?>/search.css" rel="stylesheet" type="text/css" />
<link href="module/search/templates/<?php echo $GLOBALS['style_name'];?>/h-search.css"  rel="stylesheet" type="text/css" />
<script src="module/search/templates/default/js/common.js" type="text/javascript"></script>
<script src="module/search/templates/default/js/addSearchConditions.js" type="text/javascript"></script>
<!-- <link href="public/default/css/bootstrap.min.css" rel="stylesheet" type="text/css" />-->
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
<!-- <div class="c-title">
<span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;搜索结果</span> 
<div class="loaction_right">
	<a href="index.php?n=invite" target="_blank">邀请好友</a>
</div> 
</div> -->
<div class="content-lift">
<!-- <div class="content-title">
<span class="right-title">搜索结果</span>
</div> -->
<!--content-title end-->
<div class="c-center" style="background:#FFF;">
<!-- search-left-title begin -->
<?php include MooTemplate('public/search_sort','module'); ?>
<!-- search-left-title  end -->

<!-- search-left-content -->
<?php include MooTemplate('public/search_photo_left','module'); ?>
<!-- search-left-content  end-->

<div class="content-bottom">	</div>
<!--content-bottom end-->
</div><!--centent-lift end-->
<!--左边结束-->

<!--右边开始-->
<?php include MooTemplate('public/search_right','module'); ?>
<!--右边结束-->

<?php include MooTemplate('system/footer','public'); ?>
</div><!--content end-->
<!-- <script src="public/system/js/bootstrap.min.js" type="text/javascript"></script> -->
<script src="module/search/templates/default/js/common.js" type="text/javascript"></script>
<script src="module/search/templates/default/js/addSearchConditions.js" type="text/javascript"></script>
</body>
</html>
