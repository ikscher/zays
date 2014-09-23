<?php
/*
 * Created on 8/14/2009
 *
 * Tianyong
 *
 * module/service/rose.php
 */

//note 给别人送玫瑰花
function send_rose() {

	global $_MooClass,$dbTablePre,$timestamp,$user_arr,$serverid;	
	$sendtoid = MooGetGPC('sendtoid','integer');
	$userid=$user_arr['uid'];
	//note 分配客服              
    //allotserver($userid);
	//note 获得照片总数
	$query = $_MooClass['MooMySQL']->query("SELECT imgid FROM {$dbTablePre}pic WHERE uid = '$sendtoid'");
	$pic_total = $_MooClass['MooMySQL']->numRows($query);
	
	//note 自己不能给自己发送玫瑰，直接转到玫瑰列表页面
	if($userid == $sendtoid){
		MooMessage('自己不可以给自己送鲜花',"index.php?n=service&h=rose&t=getmorerose");
		exit;
	}
	
	//客服不能模拟操作
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
    
    
	//note 双方屏蔽不给操作
	if(MooGetScreen($userid,$sendtoid)){
		MooMessage('因特殊原因，送鲜花失败',"index.php?n=service&h=rose&t=getmorerose");
		exit;
	}
	//note 要做性别过滤，异性的发送玫瑰，直接转到玫瑰列表页面
	$send_user1 = leer_send_user1($sendtoid);
	$user = leer_send_user1($userid);
	
	
	if($send_user1['gender'] == $user['gender']) {
		MooMessage('不可给同性发送鲜花','javascript:history.go(-1);');
		exit;
	}
	
	//note 如果自己没有玫瑰花了，就提示没有玫瑰花了
	
	if($user['rosenumber'] <= 0) {
		MooMessage('您没有鲜花了，获取更多鲜花',"index.php?n=service&h=rose&t=getmorerose");
		exit;
	}
	
	//发送短信和邮件
	include_once("./module/crontab/crontab_config.php");
	if(MOOPHP_ALLOW_FASTDB){
		$res = MooFastdbGet('members_search','uid',$sendtoid);
		$res_b = MooFastdbGet('members_base','uid',$sendtoid);
		$res = array_merge($res,$res_b);
	}else{
	   $res= $_MooClass['MooMySQL']->getOne("select s.telphone,b.is_phone,s.username from {$dbTablePre}members_search s left join {$dbTablePre}members_base b on s.uid=b.uid  where s.uid='$sendtoid'");
	}
	$send_user_info = $_MooClass['MooMySQL']->getAll("select b.*,c.*,a.* from `{$dbTablePre}members_search` a left join {$dbTablePre}members_base c on a.uid=c.uid  left join  {$dbTablePre}members_choice b  on a.uid=b.uid  where a.uid = '$userid'");
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
	$province = $provice_list[$send_user_info['province']];//省
	$city = $city_list[$send_user_info['city']]; //市
	$height = $send_user_info['height'] ? $height_list[$send_user_info['height']] : "未知"; //身高
	ob_start();
	require_once MooTemplate('public/mail_space_rosetpl', 'module'); //模板
	$body = ob_get_clean();

	MooSendMail($res['username'],"真爱一生网系统温馨提示",$body,"",false,$sendtoid);
	if(empty($GLOBALS['MooUid'])){
	list($uid, $password) = explode("\t", MooAuthCode($_MooCookie['auth'], 'DECODE'));
		$uid = intval($uid);
	}else{
		$uid = $GLOBALS['MooUid'];
	}
	if(empty($uid)){
		MooMessage('您还没有登录','index.php?n=login');
	}

	$leer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}service_rose WHERE receiveuid = '$sendtoid' AND senduid = '$userid' ORDER BY rid DESC LIMIT 1");
	if($user['rosenumber'] > 0) {
		if($leer['rid']) {
			$rid = $leer['rid'];
			//note 如果已经发送过玫瑰，就增加发送玫瑰的次数
			$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_rose SET num = num + 1, receivenum = receivenum + 1,sendtime = '$timestamp',receivetime='$timestamp',receive_del=0,send_del=0 WHERE rid = '$rid'");
		}else {
			//note 发送新的玫瑰，写入数据库 发送者，接受者，发送时间
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}service_rose SET num = 1, receivenum = 1, sendtime = '$timestamp',receivetime='$timestamp',senduid  = '{$uid}',receiveuid = '$sendtoid' ");
		}
		
		//note 发送一朵玫瑰，自己就要减少一朵玫瑰
		//enkytest
		
		//enkytestend
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}members_base SET rosenumber = rosenumber - 1 WHERE uid = '{$uid}'");
		if(MOOPHP_ALLOW_FASTDB){
			$user_rosenum =  $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT rosenumber FROM {$GLOBALS['dbTablePre']}members_base  WHERE `uid`='{$uid}'  LIMIT 1");
			$value['rosenumber']= $user_rosenum['rosenumber'];
			MooFastdbUpdate('members_base','uid',$userid,$value);
		}
	}
	//将新注册的会员更新为优质会员
	if(in_array($user_arr['sid'],array(0,52,123))&&$user_arr['is_well_user']!=1){		
		update_iswell_user($user_arr['uid']);
	}

	//每天向同一用户发送多次，短信记录数表只记一次
	$send_rose_date = isset($leer['sendtime']) ? date("Y-m-d",$leer['sendtime']) : date("Y-m-d");//
	$today_rose_count =  isset($leer['rid']) ? $leer['num'] + 1 : 1;
	if(date("Y-m-d") > $send_rose_date){
		$today_rose_count = 1;
	}
    
	if($res['is_phone']){
	  SendMsg($res['telphone'],"真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送鲜花,请及时把握您的缘分！4006780405");
	}
	

	//提醒所属客服
	$sid = $user_arr['sid'];
	$title = '您的会员 '.$user_arr['uid'].' 向 '.$sendtoid.' 发送了鲜花';
	$awoketime = $timestamp+3600;
	$sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
	$res = $_MooClass['MooMySQL']->query($sql_remark);

	MooMessage('发送鲜花成功'.$note,"index.php?n=service&h=rose&t=isendrose","05");
	require MooTemplate('public/service_rose_sendrose', 'module');
}

//note 获得更多的玫瑰
function get_more_rose() {
	global $_MooClass,$dbTablePre,$userid,$user_arr;
	//note 已收到的玫瑰总数
	$rose_num1 = $_MooClass['MooMySQL']->getOne("SELECT sum(receivenum) num FROM {$dbTablePre}service_rose WHERE receiveuid = '$userid' and receive_del=0 and is_server=0 LIMIT 1");
	$num1 = $rose_num1['num'];
	
	//note 已发送的玫瑰总数
	$rose_num2 = $_MooClass['MooMySQL']->getOne("SELECT sum(num) num FROM {$dbTablePre}service_rose WHERE senduid = '$userid' and is_server=0");
	$num2 = $rose_num2['num'];
	
	require  MooTemplate('public/service_rose_getmorerose', 'module');	
}

//note 发送的玫瑰花
function get_send_rose() {
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;

	//note 获取删除提交的变量
	$delrose = MooGetGPC('delrose','string');
	$delroseid = MooGetGPC('delroseid','array');
	$time = time(); 
	//note 删除提交的数据
	if($delrose && count($delroseid)) {
		foreach($delroseid as $v) {
			$_MooClass['MooMySQL']->query("UPDATE  {$dbTablePre}service_rose SET send_del=1,send_deltime='{$time}' WHERE rid = '$v'");
		}
		MooMessage("鲜花删除成功",'index.php?n=service&h=rose&t=isendrose','05');
	}
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delrose && !count($delroseid)) {
		MooMessage('请选择要删除的鲜花','index.php?n=service&h=rose&t=isendrose','01');
		exit;
	}
	
	
	//分页
	$page_per = 4;
	$page_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$page_url = preg_replace( "/(&page=\d+)/","",$page_url );
	$page_now = MooGetGPC( 'page', 'integer', 'G');
	
	$temp = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_rose WHERE senduid  = '$userid' and send_del=0 and is_server=0");
	$item_num = $temp['num'];
	$page_num = ceil( $item_num / $page_per );
	if( $page_now > $page_num ) $page_now = $page_num;
	if( $page_now < 1 ) $page_now = 1;
	
	$start = ($page_now - 1) * $page_per;
	$rose  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_rose WHERE senduid = '$userid' and send_del=0 and is_server=0 ORDER BY sendtime desc LIMIT $start,$page_per");
	
	//note 已收到的玫瑰总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when receivenum>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_rose WHERE receiveuid = '$userid' and receive_del=0");
	$receive_num = empty( $temp['num'] ) ? 0 : $temp['num'];

	//note 已发送的玫瑰总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when num>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_rose WHERE senduid = '$userid' AND is_server=0 and send_del=0");
	$send_num = empty( $temp['num'] ) ? 0 : $temp['num'];

	require MooTemplate('public/service_rose_getsendrose', 'module');
	
}


//note 获得玫瑰花
function get_receive_rose() {
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	
	//note 获取删除提交的变量
	$delrose = MooGetGPC('delrose','string');
	$delroseid = MooGetGPC('delroseid','array');
	//note 删除提交的数据
	if($delrose && count($delroseid)) {
		$ids = "'".implode( "','", $delroseid )."'";
		$time = time();
		$_MooClass['MooMySQL']->query("UPDATE  {$dbTablePre}service_rose SET receive_del=1,receive_deltime='{$time}', receivenum = 0 WHERE rid  IN ( $ids )");
		MooMessage("鲜花删除成功",'index.php?n=service&h=rose&t=igetrose','05');
	}
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delrose && !count($delroseid)) {
		MooMessage('请选择要删除的鲜花','index.php?n=service&h=rose&t=igetrose','01');
		exit;
	}
	
	//分页
	$page_per = 4;
	$page_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$page_url = preg_replace( "/(&page=\d+)/","",$page_url );
	$page_now = MooGetGPC( 'page', 'integer', 'G');
	
	$temp = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_rose WHERE receiveuid  = '$userid' and send_del=0");
	$item_num = $temp['num'];
	$page_num = ceil( $item_num / $page_per );
	if( $page_now > $page_num ) $page_now = $page_num;
	if( $page_now < 1 ) $page_now = 1;
	
	$start = ($page_now - 1) * $page_per;
	$rose  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_rose WHERE receiveuid = '$userid' and receive_del=0 ORDER BY receivetime desc LIMIT $start,$page_per");
	
	//note 已收到的玫瑰总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when receivenum>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_rose WHERE receiveuid = '$userid' and receive_del=0");
	$receive_num = empty( $temp['num'] ) ? 0 : $temp['num'];

	//note 已发送的玫瑰总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when num>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_rose WHERE senduid = '$userid' AND is_server=0 and send_del=0");
	$send_num = empty( $temp['num'] ) ? 0 : $temp['num'];

	require MooTemplate('public/service_rose_getreceiverose', 'module');
	
}

//控制部分
switch ($_GET['t']) {
	//note 收到的鲜花列表
	case "igetrose" :
		get_receive_rose();
		break;

	//note 	发送的鲜花列表
	case "isendrose" :
		get_send_rose();
		break;
	
	//note 	获得更多的鲜花
	case "getmorerose" : 
		get_more_rose();
		break;

	//note 发送新的鲜花给别人	
	case "sendrose"	:
		send_rose();
		break;
		
	//note 默认跳转到收到的鲜花列表
	default :
		get_receive_rose();
}

?>