<?php 
	if(isset($_GET['tid']) && $_GET['tid'])
		$tid = $_GET['tid'];
	else
 		$tid = '0';
	if(isset($_GET['fid']) && $_GET['fid'])
 		$fid = $_GET['fid'];
	else
 		$fid = '0';
	if(isset($_GET['sid']) && $_GET['sid'])
		$sid = $_GET['sid'];
	else
 		$sid = '0';
 		
	//$c = MooGetGPC('c')?'1':'0';
	if (!empty($return['msg'])){
		header("content-type:text/html;charset=utf-8");
		echo "<script>alert('".$return['msg']."');close();</script>";
		exit;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>在线聊天——真爱一生网</title>
<link href="module/chat/css/main.css?v=8"  rel="stylesheet" type="text/css" />
<script type="text/javascript" src="public/system/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="public/system/js/jquery.cookie.js"></script>
<script src="module/chat/js/server-push.js?v=6" type="text/javascript"></script>
<script src="module/chat/js/chat.js?v=11" type="text/javascript"></script> 
<script type="text/javascript">
<?php if(isset($_GET['isjump'])) echo 'window.resizeTo(530,520);';	?>
$(window).resize(function(){$(".call").css({'top':($(window).height()-$(".call").height())/2+"px",'left':($(window).width()-$(".call").width())/2+"px"});});
//根据连接状态执行相应操作
function doSuccess(){
	clearInterval(link_timer);
	clearTimeout(link_timeout);
	_box.ps.text('连接成功！');
	_box.ps.fadeOut(3000);
	_chat.addChatNum();
}

function userOffline(id,toId){
	$.get('index.php?n=chat&h=newinlinemsg&fid='+id+'&tid='+toId);
}

$(function() {
    //ape
	_chat._url = "http://zhenaiyisheng.cc/module/chat/";
	//_chat._apeServer = 'ape.0019.com';
	//_chat._apeServer = 'ape.7651.com';
	_chat._apeServer = 'ape.zhenaiyisheng.cc';

	var fid = <?php echo $fid;?>;
	var tid = <?php echo $tid;?>;
	var sid = <?php echo $sid;?>;
	var channel = '';
	
	if(fid > tid) channel = tid+'_'+fid;
	else channel = fid+'_'+tid;

	//判断链接数量是否超限制
	/*var chatLimit = sid ? 157 : 153;
	if(_chat.chatNum()>=chatLimit){
		alert('您已经达到开启聊天窗口的上限值，如果你想和此会员联系，请关闭其他任一个会员的聊天窗口');
		close();
		return;
	} 
    */
	
	
	_chat._userId = '<?php echo $fid;?>';
	_chat._userToId = '<?php echo $tid;?>';
	_chat._serverId = '<?php echo $sid;?>';
	_chat._toName   = '<?php $name = $return['data']['to']['nickname'] ? $return['data']['to']['nickname']:$return['data']['to']['uid'];echo $name;?>';
	_chat._channel = channel;
    
	
	
    $.ape.bindMsgTipsFn(userOffline);
	
	
    $.ape.bindConnectSuccess(doSuccess);
	//关闭
	$.ape.bindClose($(".close"));
	$.ape.bindCloseFn("_chat.removeChatNum()");
	$(".close").click(function(){close();});
	_chat.open();

	//消息相关
	$(".send").click(function(){
        _chat.send();
		});
	$("#choice").change(function(){
		var txt = $("#choice").val();
		if(txt=='1'){
			_box.ps.text('在线状态设置成功');
			 $.ape.fireFn('_box.prompt("对方已经上线");');
		}else{
			_box.ps.text('离开状态设置成功');
			 $.ape.fireFn('_box.prompt("对方己离开");');
		}
		//_box.ps.show();
		_box.ps.fadeIn(1000,function(){
			_box.ps.fadeOut(2000);
		});
		
	});
	//消息框
	_box.msg_box=$("#messages_outer");
	_box.ps=$(".ps");

	//连接状态
	link_timer = setInterval(_chat.tipsMsg,500);
	link_timeout = setTimeout(function(){
		_box.ps.text('连接失败，请刷新或关闭聊天窗口，重新打开');
		clearTimeout();
		clearInterval(link_timer);
		},20000);


});
</script>
</head>

<body>
    <div class="call">
        <div class="title">
            <div class="title_left"></div>
            <div class="title_mid"><span style="font-weight:600;"><?php echo $return['data']['to']['nickname'];?></span><span>(<?php echo $return['data']['to']['uid'];?>)</span></div>
            <div class="title_right"><img src="module/chat/images/x03.gif" /></div>
        </div>
        <div class="container">
            <div class="cen_top">
                <div class="top_left">
                    <div class="answer">
                        <div id="messages_outer" name="messages_outer">
                        <div class='ps'></div>
                            <div id="messages" name="messages">
                                <div class="careful">
                                    <img src="module/chat/images/careful.gif" /><span style="color:#636363;">交谈中勿轻信汇款、中奖信息，发现异常请联系客服。</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="show_face_img" style="display:none;">
                        <!-- <div class="titlebg"><span>添加表情</span></div>
                        <div class="titlecen">请在此设置表情</div> -->
                    </div>
                    <div class="record">
                        <div class="expression"><a href="#" ><img src="module/chat/images/expression.gif" onmouseover="this.src='module/chat/images/expression0.gif';" onmouseout="this.src='module/chat/images/expression.gif';" /></a></div>
                        <div class="line"></div>
                        <?php if(isset($_GET['isback'])){ ?>
                        <div class="cord">
                            <div class="cord_pic"><a href="javascript:window.open('ework/chat_ajax.php?n=menu&sender=<?php echo $fid; ?>&receiver=<?php echo $tid; ?>','查看<?php echo $fid; ?>与<?php echo $tid; ?>的聊天记录','height=450,width=600')"><img src="module/chat/images/record.gif" onmouseover="this.src='module/chat/images/record0.gif';" onmouseout="this.src='module/chat/images/record.gif';" /></a></div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="problem">
                        <div class="chat-text-into" id="chat-text-into">
						    <iframe id='editor_frame' name='editor_frame' scrolling="auto"></iframe>
						</div>
                    </div>
                    <div class="button">
                        <div class="choice">
                            <select class="selectSize" id=choice name="choice"><option value="1">在线</option><option value="2">离开</option></select>
                        </div>
                        <div class="ico">
                            <div class="close"><a href="#"><img src="module/chat/images/x.gif" onMouseOver="this.className='htmer_img'" onMouseOut="this.className='none'" /></a></div>
                            <div class="send"><a href="#"><img src="module/chat/images/f.gif" onMouseOver="this.className='htmer_img'" onMouseOut="this.className='none'" /></a></div>
                        </div>
                    </div>
                </div>
                <div class="top_right">
                    <div class="you">
                        <div class="you_title"><span>对方形象</span></div>
                        <div class="you_cen"><img style="width:136px; height:175px;" src="<?php 
                        $userphoto = '';
                        if($return['data']['to']['images_ischeck']==1){
                        	if(!$userphoto = MooGetphoto($return['data']['to']['uid'])) $userphoto = MooGetphoto($return['data']['to']['uid'], 'index');
                        	if(!empty($userphoto)) echo IMG_SITE.$userphoto;
                        }
                        if(empty($userphoto)){
                        	echo $return['data']['to']['gender']==1 ? 'public/system/images/nopic_big_woman.gif' : 'public/system/images/nopic_big_man.gif';
                        }?>" /></div>
                    </div>
                    <div class="my">
                        <div class="you_title"><span>我的形象</span></div>
                        <div class="you_cen"><img style="width:136px; height:175px;" src="<?php 
                        $userphoto = '';
                        if($return['data']['from']['images_ischeck']==1){
                        	if(!$userphoto = MooGetphoto($return['data']['from']['uid'])) $userphoto = MooGetphoto($return['data']['from']['uid'], 'index');
                        	if(!empty($userphoto)) echo IMG_SITE.$userphoto;
                        }
                        if(empty($userphoto)){
                        	echo $return['data']['from']['gender']==1 ? 'public/system/images/nopic_big_woman.gif' : 'public/system/images/nopic_big_man.gif';
                        }?>" /></div>
                    </div>
                </div>
            </div>
            <div class="cen_bottom"></div>
        </div>
        <div id="log_box">
            <div class="box_top"></div>
            <div class="box_cen"><div class="box_content"></div></div>
            <div class="box_bottom"></div>
        </div>
    </div>
</body>
</html>
