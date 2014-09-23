<?php
/*
 * Created on 8/14/2009
 *
 * Tianyong
 *
 * module/service/liker.php
 */

//note 添加为我的意中人
function addmyfriend() {
	global $_MooClass,$dbTablePre,$pagesize,$user_arr,$timestamp,$serverid;
	$sendtoid = MooGetGPC('sendtoid','integer');
	$userid=$user_arr['uid'];
	
	if($sendtoid == $userid){	
		MooMessage('自己不可加自己为意中人','javascript:history.go(-1);','04');
		exit;
	}
	//note 双方屏蔽不给操作
	if(MooGetScreen($userid,$sendtoid)){
		MooMessage('因特殊原因，加意中人失败',"index.php?n=service&h=rose&t=getmorerose",'03');
		exit;
	}
    
	
	
    $result=$_MooClass['MooMySQL']->getOne("select groupid from web_admin_user where uid='{$serverid}'");
    $groupid=$result['groupid'];
    //系统管理员权限
    $GLOBALS['system_admin'] = array(60);
    if(in_array($groupid,$GLOBALS['system_admin'])){
        $serverid=null;
    }
    
    if($serverid){
        MooMessage('对不起您不能模拟操作','javascript:history.go(-1);','04');
        exit;
    }


	
	//note 分配客服              
    //allotserver($userid);
    
	//note 获得照片总数
	//$query = $_MooClass['MooMySQL']->query("SELECT imgid FROM {$dbTablePre}pic WHERE uid = '$sendtoid'");
	//$pic_total = $_MooClass['MooMySQL']->numRows($query);
	 $query = $_MooClass['MooMySQL']->getOne("SELECT count(1) as c FROM {$dbTablePre}pic WHERE uid = '$sendtoid'");
	 $pic_total = $query['c'];
	//note 要做性别过滤，异性的发送玫瑰，直接转到玫瑰列表页面
	$send_user1 = leer_send_user1($sendtoid);
	$user = leer_send_user1($userid);
	if($send_user1['gender'] == $user['gender']) {
		MooMessage('不可加同性为意中人','javascript:history.go(-1);','04');
		exit;
	}
	if(empty($GLOBALS['MooUid'])){
	    list($uid, $password) = explode("\t", MooAuthCode($_MooCookie['auth'], 'DECODE'));
		$uid = intval($uid);
	}else $uid=$GLOBALS['MooUid'];
	if(empty($uid)){
	    MooMessage('您还没有登录','index.php?n=login','04');
	}
	$friend = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}service_friend WHERE friendid = '$sendtoid' AND uid = '$uid'",true);
	
	if($friend['fid']) {
	   $content = '对不起，对方已经在您的意中人列表中';
	   $url = 'index.php?n=space&h=viewpro&uid='.$sendtoid;
       MooMessage($content,$url,'02');
	}else {
	   //note 加为意中人，新插入一行记录，谁加谁
	   $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}service_friend SET friendid  = '$sendtoid',uid = '{$uid}',sendtime='".time()."'");
	}

	//将新注册的会员更新为优质会员
	if(in_array($user_arr['sid'],array(0,52,123))&&$user_arr['is_well_user']!=1){		
		update_iswell_user($uid);
	}
	   //发送短信和邮件
	   include_once("./module/crontab/crontab_config.php");
		if(MOOPHP_ALLOW_FASTDB){
			$res = MooFastdbGet('members_search','uid',$sendtoid);
			$res_b = MooFastdbGet('members_base','uid',$sendtoid);
			$res = array_merge($res,$res_b);
		}else{
		   $res= $_MooClass['MooMySQL']->getOne("select s.telphone,b.is_phone,s.username from {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid where uid='$sendtoid'");
	   }
	   $send_user_info = $_MooClass['MooMySQL']->getAll("select c.*,b.*,a.* from `{$dbTablePre}members_search` a left join {$dbTablePre}members_base b on a.uid=b.uid  left join  {$dbTablePre}choice c  on a.uid=c.uid  where a.uid = '$uid'");
	   $send_user_info = $send_user_info[0];
	   //头像路径
	   $path = thumbImgPath(2,$send_user_info[pic_date],$send_user_info[pic_name],$send_user_info['gender']);
	   if(file_exists($path)){
	   	 $img_path = $path;
	   }else{
	   		if($send_user_info['gender'] == 1){
	   			$img_path = "/public/images/service_nopic_woman.gif";
	   		}else{
	   			$img_path = "/public/images/service_nopic_man.gif";
	   		}
	   }
	   $send_username = $send_user_info['nickname'] ?  $send_user_info['nickname'] : $send_user_info['uid'];//发送者用户名
	   $send_user_grade = $send_user_info['gender'] == 1 ? "女" : "男"; //发送者性别
	   $province = $provice_list[$send_user_info[province]];//省
	   $city = $city_list[$send_user_info[city]]; //市
	   $height = $send_user_info[height] ? $height_list[$send_user_info[height]] : "未知"; //身高
	   ob_start();
	   require_once MooTemplate('public/mail_space_friendtpl', 'module'); //模板
	   $body = ob_get_clean();
//	   MooSendMailSpace2($res['username'],"真爱一生网系统温馨提示","真爱一生网提醒：有会员将您添加为意中人了！请尽快查收！您的帐号是:".$res['username'],$type="1",$body);
	    MooSendMail($res['username'],"真爱一生网系统温馨提示",$body,"",false,$sendtoid);
	  // MooSendMailSpace($res['username'],"真爱一生网系统温馨提示","真爱一生网提醒：有会员给您发送秋波了！请尽快查收！您的帐号是:".$res['username'],$type="1",$send_user,$send_type = "秋波",$userid,$send_user_img,$age,$tall,$area,$sendtime,$introduce);
	  
	   	$week_time = 24*3600*7;//一周时间秒数
		$interval_time = $timestamp - $user_arr['last_login_time'];//当前时间-最后登录时间
		$date1 = date("Y-m-d",strtotime("last Monday"));
		$date2 = date("Y-m-d",strtotime("Sunday"));
		
		if($interval_time > $week_time){//不活跃用户每周发一条短信
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}today_send SET uid = '".$sendtoid."', sid = '".$userid."',phone = '".$res['telphone']."',sendtime = '".date("Y-m-d")."'" );
			 $cos = $_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}today_send where uid='$sendtoid'  and sendtime>='$date1' and sendtime<='$date2'");
			if($cos[c] <= 1){
		   		//fanglin暂时屏蔽
				Push_message_intab($sendtoid,$res['telphone'],"意中人","真爱一生网 用户ID：".$userid.",".$send_user_grade.",已将您添加为意中人,请及时把握您的缘分！4006780405",$userid);
		     }
		}else{
			   //每天该用户超过5条信息不发送短信
			   $cos = $_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}today_send where uid='$sendtoid' and sendtime='".date("Y-m-d")."'");
			   if($cos[c]<5){
			   	  $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}today_send SET uid = '".$sendtoid."', sid = '".$userid."',phone = '".$res['telphone']."',sendtime = '".date("Y-m-d")."'" );
				//fanglin暂时屏蔽
				  Push_message_intab($sendtoid,$res['telphone'],"意中人","真爱一生网 用户ID：".$userid.",".$send_user_grade.",已将您添加为意中人,请及时把握您的缘分！4006780405",$userid);
			   }
		}
	

   require  MooTemplate('public/service_friend_addmyfriend', 'module');
}

//note 我的意中人列表
function whoismyfriend() {
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	
	$pagesize = 4;
	//note 获取删除提交的变量
	$delfriend = MooGetGPC('delfriend','string');
	$delfriendid = MooGetGPC('delfriendid','array');
	
	//note 删除提交的数据
	if($delfriend && count($delfriendid)) {
		foreach($delfriendid as $v) {
			$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}service_friend WHERE fid = '$v'");
		}
		MooMessage("删除意中人成功",'index.php?n=service&h=liker','05');
	}
	
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delfriend && !count($delfriendid)) {
		MooMessage('请选择要删除的意中人','index.php?n=service&h=liker','01');
		exit;
	}
	
	
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl2 = preg_replace("/7651/","7652",$currenturl2);
		
	//note 获得第几页
	$page = max(1,intval($_GET['page']));
		
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;

	//note 已收到的统计总数
	$query = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_friend WHERE uid = '$userid'",true);
	$total = $query['num'];
	$total2Arr = $_MooClass['MooMySQL']->getOne("SELECT count(*) FROM {$dbTablePre}service_friend WHERE friendid = '$userid'");
	$total2 = $total2Arr['count(*)'];
	//note 查询出意中人相关信息
	if($total){
		$friend  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_friend WHERE uid = '$userid' order by sendtime desc LIMIT $start,$pagesize");
	}
	require  MooTemplate('public/service_friend_myfriendlist', 'module');
}
//note 谁加我为意中人
function whoaddme(){
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	
	$pagesize = 4;
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl2 = preg_replace("/7651/","7652",$currenturl2);
		
	//note 获得第几页
	$page = max(1,intval($_GET['page']));
		
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;

	//note 查出谁加我为意中人的总数
	$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_friend WHERE friendid = '$userid'",true);
	$total = $ret_count['c'];
	$total2Arr = $_MooClass['MooMySQL']->getOne("SELECT count(*) FROM {$dbTablePre}service_friend WHERE uid = '$userid'");
	$total2 = $total2Arr['count(*)'];
	//note 
	if($total){
		$friend  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_friend WHERE friendid = '$userid' order by sendtime desc LIMIT $start,$pagesize");
	}
	require MooTemplate('public/service_friend_whoaddmylist', 'module');
}
/*****************************************************************************************************/

//控制部分
switch ($_GET['t']) {
	case "iaddliker" :
		whoismyfriend();
		break;
		
	case "appendfriend" :
		addmyfriend();
		break;
	
	case 'whoaddme':
		whoaddme();
		break;
	default :
		whoismyfriend();
}

