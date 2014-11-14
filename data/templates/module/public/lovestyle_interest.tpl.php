<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>爱情测试——真爱一生网</title>
<link href="module/lovestyle/templates/<?php echo $GLOBALS['style_name'];?>/lovestyle.css" rel="stylesheet" type="text/css" />
</head>
<?php include MooTemplate('system/js_css','public'); ?>
<script src="module/search/templates/default/js/syscode.js?v=1" type="text/javascript"></script>
<script type="text/javascript">
var islock = false;
function sendVote(qid,answer){
	if(islock){return false;}
	islock = true;
	upCookie('next2');
	var url = 'ajax.php?n=lovestyle&h=vote&t=interest&qid='+qid+'&ans='+answer+'&rand='+Math.random();
	$.get(url,function(str){
		if(str == 'ok'){
			window.location.href = 'index.php?n=lovestyle&h=interest';
		}
		islock = false;
	});
}
function SetCookie(name,value)
{
	var argv = arguments;  
	var argc = arguments.length;  
	var expires = (argc > 2) ? argv[2] : null;  
	var path = (argc > 3) ? argv[3] : '/';  
	var domain = (argc > 4) ? argv[4] : '<?php echo MOOPHP_COOKIE_DOMAIN;?>';  
	var secure = (argc > 5) ? argv[5] : false;  
		document.cookie = name + "=" + escape (value) + ((expires == null) ? "" : ("; expires=" + expires.toGMTString())) + ((path == null) ? "" : ("; path=" + path)) + ((domain == null) ? "" : ("; domain=" + domain)) + ((secure == true) ? "; secure" : "");
}
function getCookie(name)
{
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
     if(arr != null) return unescape(arr[2]); return null;
}
function upCookie(flag){
	var pix = "<?php echo MOOPHP_COOKIE_PRE;?>";
	var c = getCookie(pix+'test_index');
	flag == 'prev' ? c-- : c++;
	SetCookie(pix+'test_index',c);
	if(flag != 'next2') window.location.href = 'index.php?n=lovestyle&h=interest';
}
$(document).ready(function(){
	window.location.href = '#locality';
});
</script>
<body>
<?php include MooTemplate('system/header','public'); ?>
<div class="main"><!--head-search end-->
	<div class="content">
		<p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=lovestyle">爱情测试</a>&nbsp;&gt;&gt;&nbsp;趣味爱情测试</span></p><a id="locality"></a>
		<div class="lovestyle-box">
			<div class="lovestyle-top lt-top-bg<?php echo $temp_style;?>">
				<div class="lovestyle-top-text">有<strong><?php echo test_online();?></strong>位用户和您一起在做爱情测试<div class="lovestyle-point-in">
						<span>完成率：</span>
						<div class="lt-point points-bar<?php echo $temp_style;?>">
						<div class="lt-point-in points-bar-in<?php echo $temp_style;?>" style="width:<?php echo $percent;?>%"></div>
						</div>
						<span>完成<?php echo $percent;?>%</span></div></div>
				<div class="lovestyle-point">
					
					<p class="lt-point-text<?php echo $temp_style;?>">您已完成 <strong><?php echo $comp_num;?></strong> 道题，还有 <strong><?php echo $total - $comp_num?></strong> 道题未完成</p>	
					</div>
				</div>
			</div>
			<div class="lovestyle-left t-left-bg<?php echo $temp_style;?>">
				<div class="left-tips<?php echo $temp_style;?>"><span class="f-f00">*</span> 小提示：鼠标点击您选择的答案会自动进入下一题</div>
				<div class="lovestyle-left-box">
				<h5><?php echo ++$comp_num?>.<?php echo $question['question'];?></h4>
                <?php if($question['show_type'] == 4) { ?>
				<ul class="lt-answer01">
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon1-01.gif" />
						<label for="a1"><input type="radio" name="Important" onclick="sendVote(<?php echo $question['qid'];?>,1);" id="a1"<?php if(isset($this_vote) && $this_vote == 1) { ?> checked="checked"<?php } ?>/>完全不重要</label>
					</li>
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon1-02.gif" />
						<label for="a2"><input type="radio" name="Important" onclick="sendVote(<?php echo $question['qid'];?>,2);" id="a2"<?php if(isset($this_vote) && $this_vote == 2) { ?> checked="checked"<?php } ?>/>不重要</label>
					</li>
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon1-03.gif" />
						<label for="a3"><input type="radio" name="Important" onclick="sendVote(<?php echo $question['qid'];?>,3);" id="a3"<?php if(isset($this_vote) && $this_vote == 3) { ?> checked="checked"<?php } ?>/>不是很重要</label>
					</li>
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon1-04.gif" />
						<label for="a4"><input type="radio" name="Important" onclick="sendVote(<?php echo $question['qid'];?>,4);" id="a4" <?php if(isset($this_vote) && $this_vote == 4) { ?> checked="checked"<?php } ?>/>看具体情况</label>
					</li>
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon1-05.gif" />
						<label for="a5"><input type="radio" name="Important" onclick="sendVote(<?php echo $question['qid'];?>,5);" id="a5"<?php if(isset($this_vote) && $this_vote == 5) { ?> checked="checked"<?php } ?>/>比较重要</label>
					</li>
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon1-06.gif" />
						<label for="a6"><input type="radio" name="Important" onclick="sendVote(<?php echo $question['qid'];?>,6);" id="a6"<?php if(isset($this_vote) && $this_vote == 6) { ?> checked="checked"<?php } ?>/>很重要</label>
					</li>
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon1-07.gif" />
						<label for="a7"><input type="radio" name="Important" onclick="sendVote(<?php echo $question['qid'];?>,7);" id="a7"<?php if(isset($this_vote) && $this_vote == 7) { ?> checked="checked"<?php } ?>/>极其重要</label>
					</li>
				</ul>
                <?php } elseif ($question['show_type'] == 3) { ?>
                <ul class="lt-answer02">
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon2-01.gif" />
						<label for="a1"><input type="radio" name="Agree" onclick="sendVote(<?php echo $question['qid'];?>,1);" id="a1"<?php if(isset($this_vote) && $this_vote == 1) { ?> checked="checked"<?php } ?>/> 完全同意</label>
					</li>
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon2-02.gif" />
						<label for="a2"><input type="radio" name="Agree" onclick="sendVote(<?php echo $question['qid'];?>,2);" id="a2"<?php if(isset($this_vote) && $this_vote == 2) { ?> checked="checked"<?php } ?>/> 同意</label>
					</li>
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon2-03.gif" />
						<label for="a3"><input type="radio" name="Agree" onclick="sendVote(<?php echo $question['qid'];?>,3);" id="a3"<?php if(isset($this_vote) && $this_vote == 3) { ?> checked="checked"<?php } ?>/> 基本同意</label>
					</li>
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon2-04.gif" />
						<label for="a4"><input type="radio" name="Agree" onclick="sendVote(<?php echo $question['qid'];?>,4);" id="a4"<?php if(isset($this_vote) && $this_vote == 4) { ?> checked="checked"<?php } ?>/> 看具体情况</label>
					</li>
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon2-05.gif" />
						<label for="a5"><input type="radio" name="Agree" onclick="sendVote(<?php echo $question['qid'];?>,5);" id="a5"<?php if(isset($this_vote) && $this_vote == 5) { ?> checked="checked"<?php } ?>/> 有些方面不同意</label>
					</li>
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon2-06.gif" />
						<label for="a6"><input type="radio" name="Agree" onclick="sendVote(<?php echo $question['qid'];?>,6);" id="a6" <?php if(isset($this_vote) && $this_vote == 6) { ?> checked="checked"<?php } ?>/> 不同意</label>
					</li>
					<li>
						<img src="module/lovestyle/templates/default/images/lt-icon2-07.gif" />
						<label for="a7"><input type="radio" name="Agree" onclick="sendVote(<?php echo $question['qid'];?>,7);" id="a7"<?php if(isset($this_vote) && $this_vote == 7) { ?> checked="checked"<?php } ?>/> 完全不同意</label>
					</li>
				</ul>
                <?php } elseif ($options_count > 2) { ?>
                <dl class="lt-answer03">
					<dt><img src="module/lovestyle/templates/public/img/<?php echo $question['qid'];?>_1.jpg" /></dt>
                    <?php foreach((array)$options as $k=>$opt) {?>
					<dd><label for="ask<?php echo $k;?>"><input id="ask<?php echo $k;?>" onclick="sendVote(<?php echo $question['qid'];?>,<?php echo $k+1?>);" name="Answer" type="radio"<?php if(isset($this_vote) && $this_vote == $k+1) { ?> checked="checked"<?php } ?> />
                    <?php echo $opt['option'];?></label></dd>
                    <?php }?>
				</dl>
                <?php } else { ?>
                <div class="lt-answer04">
				<dl>
					<dt><img src="module/lovestyle/templates/public/img/<?php echo $question['qid'];?>_1.jpg" /></dt>
					<dd><label for="ask1"><input id="ask1" name="Answer" onclick="sendVote(<?php echo $question['qid'];?>,1);" type="radio"<?php if(isset($this_vote) && $this_vote == 1) { ?> checked="checked"<?php } ?> />
                    <?php echo $options[0]['option'];?></label></dd>
				</dl>
				<dl>
					<dt><img src="module/lovestyle/templates/public/img/<?php echo $question['qid'];?>_2.jpg" /></dt>
					<dd><label for="ask2"><input id="ask2" name="Answer" onclick="sendVote(<?php echo $question['qid'];?>,2);" type="radio"<?php if(isset($this_vote) && $this_vote == 2) { ?> checked="checked"<?php } ?> />
                    <?php echo $options[1]['option'];?></label></dd>
				</dl>
				<div class="clear"></div>
				</div>
                <?php } ?>
				<div class="clear"></div>
				</div>
                <div class="pagebar"><a href="index.php?n=lovestyle" class="goout">退出以后再做</a>
                <?php if($comp_num > 1) { ?><input name="prev" type="button" class="btn btn-default" onclick="upCookie('prev');" value="上一题" /><?php } ?>
                <?php if($read_vote) { ?>
                <input name="next" type="button" class="btn btn-default" onclick="upCookie('next');" value="下一题"  /><?php } ?>
                <?php if($comp_num > 1) { ?>
				<a href="index.php?n=lovestyle&h=restart&tcid=2" onclick="return confirm('全部重做系统会清除已做题目记录？');"><img src="module/lovestyle/templates/default/images/doagain.gif" /></a>
                <?php } ?>
                </div>
			</div>
			<!--测试左边结束-->
			<div class="lovestyle-right lt-right-bg<?php echo $temp_style;?>">
				<div class="lovestyle-right-box">
				<p><span class="lt-r-title<?php echo $temp_style;?>">要想获得幸福婚姻</span>，抓住浪漫的爱情，就要摸清自己的婚姻恋爱心理，自己内心深处到底渴望着怎样的伴侣，到底想要什么。</p>
				<p>为此真爱一生网精心为您准备了趣味爱情题，帮您了解您真实的婚姻恋爱心理，测试轻松、愉快、有趣、快乐也很科学，测试完，即可随意与真爱一生网上的异性会员进行匹配。<span class="lt-r-title<?php echo $temp_style;?>">匹配结果超专业，报告详细</span>。</p>
				<p>在您填写完全部的问题后一份完整和精彩的测试结果就将为您完美呈现，<span class="lt-r-title<?php echo $temp_style;?>">它将为您深刻剖析您的恋爱类型、婚姻类型、人格特征、匹配类型</span>。让您在爱情的迷雾中一眼相中您的那个TA！</p>
				</div>
			</div>
		</div>			
		<div class="clear"></div>
	</div><!--content end-->
	<?php include MooTemplate('system/footer','public'); ?><!--foot end-->
</div><!--main end-->
</body>
</html>
