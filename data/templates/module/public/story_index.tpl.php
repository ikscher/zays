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
<?php include MooTemplate('system/header','public'); ?>
<div class="main"><!--head-search end-->
	<div class="content">
		<p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;成功故事</span></p>
		<div class="clear"></div>
		<div class="story">
			<div class="marry_top">
			    <div class="float"></div>
				<div class="updata-box">
				    <input name="uploadStory" type="button" class="btn btn-default"  value="上传成功故事" />
           			<!-- <a href="index.php?n=search">寻找真爱</a> -->
				</div>
				<div class="clear"></div>
				<div class="marry">
					<div class="m_fleft">
						<img src="module/story/templates/default/images/story-title.gif">
					</div>
					<div class="m_fright">
					 <a href="index.php?n=story&h=list">见证更多成功故事&gt;&gt;</a>
					</div>
               </div>
			</div>
			<div class="marrybig">
			<div class="marrysmall">
				<div class="hot-story">
					<div class="picbig ">
					 <div class="lovebox">
					 <a target="_blank" href="index.php?n=story&h=content&sid=<?php echo $hot_story['sid'];?>">
                     <?php $picbig = MooGetstoryphoto($hot_story['sid'],$hot_story['uid'],'big')?>
    
                     <?php if($picbig) { ?>
                     <img src="<?php echo $picbig;?>"  />
                     <?php } else { ?>
                     <img src="module/story/templates/default/images/story_hot.jpg" />
                     <?php } ?>
                     </a>
					 </div>
                 <p><?php if($hot_story['state'] == 1) { ?>恋爱<?php } elseif ($hot_story['state'] == 2) { ?>订婚<?php } elseif ($hot_story['state'] == 3) { ?>结婚<?php } ?>纪念日：<?php echo date("Y-n-j",$hot_story['story_date']);?></p>
                 </div>
				 <div class="m_text">
                     <h3><?php echo $hot_story['title'];?></h3>
                      <div class="detail">
                          <div class="m_title">
                            <span><?php echo $hot_story['name1'];?></span> 与 <span><?php echo $hot_story['name2'];?></span> 的爱情故事
                          </div>
                          <p>
                          <?php echo MooCutstr($hot_story['content'],270,"……")?><a target="_blank" href="index.php?n=story&h=content&sid=<?php echo $hot_story['sid'];?>">
                          [查看详情]</a></p>
                      </div>
                 </div>
				 <div class="clear"></div>
				</div>	
				<div class="storys-box">
				<?php foreach((array)$index_story as $index_storys) {?>
					<div class="singmarry">
                     <?php //恋爱、订婚、结婚样式?>
                     <?php if($index_storys['state'] == 1) { ?>
                     <?php $class = 'engagement';$sta = '恋爱'?>
                     <?php } elseif ($index_storys['state'] == 2) { ?>
                     <?php $class = 'love';$sta = '订婚'?>
                     <?php } elseif ($index_storys['state'] == 3) { ?>
                     <?php $class = 'marriage';$sta = '结婚'?>
                     <?php } ?>
                         <div class="<?php echo $class;?>"><!--已经结婚-->
                         <div class="lovesamll">
                         <a target="_blank" href="index.php?n=story&h=content&sid=<?php echo $index_storys['sid'];?>">
                         <?php $picmedium = MooGetstoryphoto($index_storys['sid'],$index_storys['uid'],'medium')?>
        
                         <?php if($picmedium) { ?>
                         <img src="<?php echo $picmedium;?>" />
                         <?php } else { ?>
                         <img src="module/story/templates/default/images/pic2.jpg" />
                         <?php } ?>
                         </a></div><span><?php echo $sta;?>纪念日：<?php echo date("Y-n-j",$index_storys['story_date']);?></span>                 </div>
                     <p><a class="f146" target="_blank" href="index.php?n=story&h=content&sid=<?php echo $index_storys['sid'];?>"><?php echo $index_storys['title'];?></a></p>
                     <p><?php echo $index_storys['name1'];?> <font color="#d22224">和</font> <?php echo $index_storys['name2'];?></p>
                     </div>
				<?php } ?>
				<div class="clear"></div>
				</div>			
			</div>
		</div>	
	</div>		
	</div><!--content end-->
	
</div><!--main end-->
<?php include MooTemplate('system/footer','public'); ?><!--foot end-->
<script type="text/javascript">
    $('input[name=uploadStory]').on('click',function(){
        location.href='index.php?n=story&h=upload';
    });
</script>
</body>
</html>
