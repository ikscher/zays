<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>成功故事——真爱一生网</title>
<link href="module/story/templates/<?php echo $GLOBALS['style_name'];?>/story.css" rel="stylesheet" type="text/css" />
</head>
<?php include MooTemplate('system/js_css','public'); ?>
<script src="module/search/templates/default/js/syscode.js?v=1" type="text/javascript"></script>
<body>
<div class="main">
	<?php include MooTemplate('system/header','public'); ?>
	<div class="content">
		<p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=story">成功故事</a>&nbsp;&gt;&gt;&nbsp;故事列表</span></p>
		<div class="clear"></div>
		<div class="story">
		    
			<div class="marry_top">
			    <div class="float"></div>
				<div class="updata-box">
				    <input name="uploadStory" type="button" class="btn btn-default"  value="上传成功故事" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="marrybig">
				<div class="marrysmall">
                	<?php foreach((array)$love_story as $love_storys) {?>
                      <?php //恋爱、订婚、结婚样式?>
                      <?php if($love_storys['state'] == 1) { ?>
                      <?php $class = 'engagement';$sta = '恋爱'?>
                      <?php } elseif ($love_storys['state'] == 2) { ?>
                      <?php $class = 'love';$sta = '订婚'?>
                      <?php } elseif ($love_storys['state'] == 3) { ?>
                      <?php $class = 'marriage';$sta = '结婚'?>
                      <?php } elseif ($love_storys['state'] == 4) { ?>
                      <?php $class = 'firstLook';$sta = '第一次见面'?>
                      <?php } ?>
                    
					<div class="detailbox">
						<div class="detailbox-left">
							<div class="<?php echo $class;?>">
								<div class="lovesamll">
									<a style="display:none;" href="#" rel="group" class="zoom">										   
									<img src="module/story/templates/default/images/pic2.jpg">
									</a>
                                    <a target="_blank" href="index.php?n=story&h=content&sid=<?php echo $love_storys['sid'];?>&uid=<?php echo $love_storys['uid'];?>">
                                    <?php $picmedium = MooGetstoryphoto($love_storys['sid'],$love_storys['uid'],'medium');?>
                    
                                    <?php if($picmedium) { ?>
                                    <img src="<?php echo $picmedium;?>" />
                                    <?php } else { ?>
                                    <img src="module/story/templates/default/images/pic2.jpg" />
                                    <?php } ?>
                                    </a>
								</div>
								<span><?php echo $sta;?>纪念日：<?php echo date("Y-n-j",$love_storys['story_date']);?></span> 
							</div>
							<div class="clear"></div>
						</div>
						<h3><?php echo MooCutstr($love_storys['title'],36,"……")?></h3>
						<dl class="story-text">
							<dt> <font class="f-ed0a91"><?php echo $love_storys['name1'];?></font> 与<font class="f-ed0a91"><?php echo $love_storys['name2'];?></font> 的爱情故事</dt>
							<dd><?php echo MooCutstr($love_storys['content'],200,"……")?></dd>
							<dd><a href="index.php?n=story&h=content&sid=<?php echo $love_storys['sid'];?>">[查看详情]</a></dd>
						</dl>
						<div class="clear"></div>
					</div>
                    
                    <?php } ?>
                    
					<div class="pages">
					<?php echo storyPage($total,$pagesize,$page,$currenturl); ?>
					<div class="clear"></div>
					</div>			
				</div>
				<div class="clear"></div>
		</div>	
	</div>		
	</div><!--content end-->
	<?php include MooTemplate('system/footer','public'); ?><!--foot end-->
</div><!--main end-->
<script type="text/javascript">
    $('input[name=uploadStory]').on('click',function(){
        location.href='index.php?n=story&h=upload';
    });
</script>
</body>
</html>
