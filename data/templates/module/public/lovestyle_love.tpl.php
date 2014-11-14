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
(function($){
$.fn.jqDrag=function(h){return i(this,h,'d');};
$.fn.jqResize=function(h){return i(this,h,'r');};
$.jqDnR={dnr:{},e:0,
 drag:function(v){var left=M.X+v.pageX-M.pX;if(left > 400 || left < 10){return false;};E.css({left:left});return false;},
 stop:function(v){E.css('opacity',M.o);$().unbind('mousemove',J.drag).unbind('mouseup',J.stop);var t = parseInt((M.X+v.pageX-M.pX+7) / 84);if(t>4) t=4;optSelect(t+1,0);}};
var J=$.jqDnR,M=J.dnr,E=J.e,i=function(e,h,k){return e.each(function(){h=(h)?$(h,e):e;h.bind('mousedown',{e:e,k:k},function(v){var d=v.data,p={};E=d.e;if(E.css('position') != 'relative'){try{E.position(p);}catch(e){}}M={X:p.left||f('left')||0,Y:p.top||f('top')||0,W:f('width')||E[0].scrollWidth||0,H:f('height')||E[0].scrollHeight||0,pX:v.pageX,pY:v.pageY,k:d.k,o:E.css('opacity')};E.css({opacity:0.8});$().mousemove($.jqDnR.drag).mouseup($.jqDnR.stop);return false;
 });
});},
f=function(k){return parseInt(E.css(k))||false;};
})(jQuery);

$(document).ready(function(){
	$("#select_options>li>a").bind("click",function(){
		var li_index = $("#select_options>li>a").index(this);
		optSelect(li_index+1,1);
		if($("#autoNext").attr('checked')) {questionPage('next');}
	});
	$('#select_perfix').jqDrag();
});
var qInfo = {index:<?php echo $comp_num+1;?>,total:<?php echo $total;?>,send:0
};
function optSelect(n,t){
	if(!isNaN(n) && n>0){
		$("#question_option_"+qInfo.index).val(n--);
		$("#select_options>li>a").removeClass('choice').eq(n).addClass("choice");
		if(t)$("#select_perfix").css('left',n*84+35);
	}else{
		$("#select_options>li>a").removeClass('choice');
		$("#select_perfix").css('left',0);
	}
}
function sendVote(qid,answer){
	var url = 'ajax.php?n=lovestyle&h=vote&t=love&qid='+qid+'&ans='+answer;
	if(qInfo.send === 1){qInfo.send = 1; return false;}
	$.get(url,function(str){
		if(qInfo.index == qInfo.total && $("#question_option_"+qInfo.total).val()){
			window.location.href = 'index.php?n=lovestyle&h=result&tcid=1';}
		qInfo.send = 0;});
}
function questionPage(flag){
	var flag = flag;
	var index = qInfo.index;
	if(flag == 'next'){
		var askObj = $("#question_option_"+index);
		if( !askObj.val() ){ alert("请选择答案");return false;}
		sendVote(askObj.attr('title'),askObj.val());
		}
	var _index = flag == 'next' ? ++qInfo.index : --qInfo.index ;
	optSelect($("#question_option_"+_index).val(),1);
	if(_index <= 1 || _index >= 60) {
		var off = 1;
		if(_index == 1){$("#a_prev").hide();off=0;}
		if(_index == 60){$("#a_next").html('查看结果');off=0;}
		if(off){flag == 'next' ? --qInfo.index : ++qInfo.index;return false;}
	}
	(_index != 1 && $("#a_prev").show());
	(_index != 60 && $("#a_next").html('下一题'));
	$("#question_"+index).hide();
	$("#question_"+_index).show();
	var percent = parseInt(_index/60*100);
	$("#percent_bar").css('width',percent+'%');
	$("#percent_num").html(percent);
	$("#comp_num").html(index);
	$("#have_num").html(60-index);//$("#question_option").val()
	$("#question_option").val('')
}
function reStart(){
	$("#question_1").show();
	$("#question_"+qInfo.index).hide();
	qInfo.index = 1;
}
</script>
<body>
<?php include MooTemplate('system/header','public'); ?>
<div class="main"><!--head-search end-->
	<div class="content">
		<p class="c-title"><span class="f-000"><a href="index.php">真爱一生首页</a>&nbsp;&gt;&gt;&nbsp;<a href="index.php?n=lovestyle">爱情测试</a>&nbsp;&gt;&gt;&nbsp;真爱一生爱情测试</span></p>
		<div class="lt-t-banner">
		</div>
		<div class="lovestyle-box">
			<div class="lovestyle-top lt-top-bg01">
				<p class="lovestyle-top-text">有<strong><?php echo test_online();?></strong>位用户和您一起在做爱情测试</p>
				<div class="lovestyle-point">
					<div class="lovestyle-point-in">
						<span>完成率：</span>
						<div class="lt-point points-bar01">
						<div id="percent_bar" class="lt-point-in points-bar-in01" style="width:<?php echo $percent;?>%"></div>
						</div>
						<span>完成<b id="percent_num"><?php echo $percent;?></b>%</span>											
					<div class="clear"></div>
					<p class="lt-point-text01">您已完成 <strong id="comp_num"><?php echo $comp_num;?></strong> 道题，还有 <strong id="have_num"><?php echo $total - $comp_num;?></strong> 道题未完成</p>	
					</div>
				</div>
			</div><!--lovestyle-top 结束-->
			<div class="lovestyle-box-in">
            	<?php foreach((array)$questions as $k=>$q) {?>
				<p id="question_<?php echo $k+1;?>"<?php if($k!=$comp_num) { ?> style="display:none;"<?php } ?> class="lovestyle-box-title">
                <?php echo $k+1;?>.<?php echo $q['question'];?>
                <input id="question_option_<?php echo $k+1;?>" title="<?php echo $q['qid'];?>" type="hidden" value="<?php if(isset($selected[$k]['opt'])) echo $selected[$k]['opt'];?>" /><?php if(isset($selected[$k]['opt'])) { ?><?php echo $selected[$k]['opt']?><?php } ?>
                </p>
                <?php }?>
				<p class="lt-que">觉得与现实的我：</p>
				<div class="lt-que-box">
					<div class="lt-que-bar">
						<div class="choice-box" id="select_perfix" style="cursor: pointer;"></div>
						<ul id="select_options">
							<li><a href="javascript:;">完全不符合</a></li>
							<li><a href="javascript:;">不符合</a></li>
							<li><a href="javascript:;">很难说</a></li>
							<li><a href="javascript:;">符合</a></li>
							<li><a href="javascript:;">完全符合</a></li>
						</ul><input type="hidden" id="question_option" value="" />
						<div class="clear"></div>												
					</div>				
				</div>	
					<div class="bottom-next">
						<a href="javascript:;" id="a_prev" class="next-que" onclick="questionPage('prev');">上一题</a><a href="javascript:;" id="a_next" onclick="questionPage('next');" class="next-que">下一题</a>
						<div class="clear"></div>
					</div>			
				<div class="lt-bottom">
					<label for="autoNext"><input id="autoNext" type="checkbox" />&nbsp;选中答案自动跳转到下一题</label>
                    <a href="javascript:;" onclick="reStart();" class="f-ed0a91-a" style="margin-left:10px;">返回第一题</a>
				</div>
			</div>
			<div class="clear"></div>
			<div class="lovestyle-box-bottom">
			</div>
		</div>			
		<div class="clear"></div>
	</div><!--content end-->
	<?php include MooTemplate('system/footer','public'); ?>
    <!--foot end-->
</div><!--main end-->
</body>
</html>