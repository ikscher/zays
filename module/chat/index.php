<?php
include_once 'module/chat/ChatAction.class.php';

/**
 * 验证聊天双方是否合法，合法则返回双方的基本信息
 * @param $fromuid
 * @param $touid
 * @param $serverid
 */
function ChatUser($fromuid,$touid,$serverid){
	$chatcheck = new ChatAction();
	$data = $chatcheck -> getUserInfo($fromuid,$touid,$serverid);
	if(!is_array($data) && $data){
		switch ($data){
			case '1':
				$msg = '不能向自己发起聊天请求！';
				break;
			case '2':
				$msg = '只有高级会员才可以发起在线聊天！';
				break;
			case '3':
				$msg = '不能向同性会员发起在线聊天！';
				break;
			case '4':
				$msg = '没有该会员！';
				break;
			case '5':
				$msg = '你可能屏蔽了这个会员，不能发起在线聊天！';
				break;
			case '6':
				$msg = '对不起您不能模拟操作！';
				break;
			case '7':
				$msg = '请先登录！';
				break;
			case '8':
				$msg = '用户的用户ID为空！';
				break;
			default:
				$msg = '操作错误！';
				break;
		}
		$return = array('msg'=>$msg,'status'=>0);
	}else{
		$return = array('data'=>$data,'status'=>1);
	}
	return $return;
}

$h = MooGetGPC('h','string','G');

switch($h){
	case 'check':
		$fromuid = MooGetGPC('fid','string','G');
		$touid = MooGetGPC('tid','string','G');
		$serverid = MooGetGPC('sid','string','G');
		
		$return = ChatUser($fromuid,$touid,$serverid);
		header("Content-type: text/html; charset=utf-8"); 
		echo serialize($return);
		break;
	case 'inline_chat':
		$fromuid = MooGetGPC('fid','string','G');
		$touid = MooGetGPC('tid','string','G');
		$serverid = MooGetGPC('sid','string','G');
		$return = ChatUser($fromuid,$touid,$serverid);
		//var_dump($return);
		include 'module/chat/chat_win.php';
		break;
	case 'newinlinemsg':
		$fromuid = MooGetGPC('fid','string','G');
		$touid = MooGetGPC('tid','string','G');
		
		$chatcheck = new ChatAction();
		$boo = $chatcheck -> InlineChatMsg($touid,$fromuid);
		if($boo) echo '1';
		else echo '0';
		break;
	case 'hasinlinemsg':
		$touid = MooGetGPC('touid','string','G');
		
		$chatcheck = new ChatAction();
		$data = $chatcheck -> hasInlineChat($touid);
		header("Content-type: text/html; charset=utf-8"); 
		if(!$data) echo '0';
		else echo serialize($data);
		break;
	case 'isread':
		$touid = MooGetGPC('tid','string','G');
		$fromuid = MooGetGPC('fid','string','G');
		$chatcheck = new ChatAction();
		$chatcheck -> isRead($fromuid,$touid);
		break;
	/*case 'clear'://清空某会员的在线聊天提示信息
		$touid = MooGetGPC('tid','string','G');
		$chatcheck = new ChatAction();
		$chatcheck -> hasReadMsg($touid);
		break;*/
	case 'test':
		include 'module/chat/test/getuserdata.php';
		break;
	default:
		echo 'do nothing...';
		break;
}
