<?php

/*
 * Created on 8/14/2009
 *
 * Tianyong
 *
 * module/service/leer.php
 */


//note 发送新的秋波
function sendnewleer() {
	global $_MooClass,$dbTablePre,$timestamp,$user_arr,$serverid;	
	$sendtoid = MooGetGPC('sendtoid','integer');
	$userid=$user_arr['uid'];
	//note 分配客服              
        //allotserver($userid);
	//note 获得照片总数
	//$query = $_MooClass['MooMySQL']->query("SELECT imgid FROM {$dbTablePre}pic WHERE uid = '$sendtoid'");
	//$pic_total = $_MooClass['MooMySQL']->numRows($query);
	
	$query = $_MooClass['MooMySQL']->getOne("SELECT count(1) as c FROM {$dbTablePre}pic WHERE uid = '$sendtoid'");
	$pic_total = $query['c'];
	//note 自己不能给自己发送秋波，直接转到秋波列表页面
	if($sendtoid == $userid){
		MooMessage('自己不可以给自己发过送秋波','javascript:history.go(-1);','04');
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
    
    //note 双方屏蔽不给操作
    if(MooGetScreen($userid,$sendtoid)){
        MooMessage('因特殊原因，送秋波失败',"index.php?n=service&h=rose&t=getmorerose",'03');
        exit;
    }

	//note 要做性别过滤，异性的发送秋波，直接转到秋波列表页面
	$send_user1 = leer_send_user1($sendtoid);
	$user = leer_send_user1($userid);
	if($send_user1['gender'] == $user['gender']) {
		MooMessage('同性之间不可互发秋波','javascript:history.go(-1);','02');
		exit;
	}
	
   
    //测试用的 by hwt 
    
        if($_POST['leersubmit']){
            if(empty($GLOBALS['MooUid'])){
                list($uid, $password) = explode("\t", MooAuthCode($_MooCookie['auth'], 'DECODE'));
                $uid = intval($uid);
            }else $uid=$GLOBALS['MooUid'];
            if(empty($uid)){
                MooMessage('您还没有登录','index.php?n=login');
            }
            $leer = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}service_leer WHERE receiveuid = '$sendtoid' AND senduid = '$userid' ");
            if($leer['lid']) {
                   $lid = $leer['lid'];
                   //note 如果已经发送过秋波，就增加发送秋波的次数
                   $_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET num = num + 1,receivenum = receivenum + 1,sendtime = '$timestamp',receivetime = '$timestamp',receive_del = '0' WHERE lid = '$lid'");     
                   //note 如果已经收到这个人的秋波，已经拒绝，现在改变注意，又发送秋波给这个人,拒绝状态2更改为0
                   $_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET stat = '0' WHERE senduid = '$sendtoid' AND receiveuid = '$uid' AND stat = '2'"); 
            }else {
                //note 发送新的秋波，写入数据库 发送者，接受者，发送时间
                $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}service_leer SET sendtime = '$timestamp',receivetime = '$timestamp',receivenum = '1', num = '1', senduid  = '{$uid}',receiveuid = '$sendtoid'");
            }
            //将新注册的会员更新为优质会员
            if(in_array($user_arr['sid'],array(0,52,123))&&$user_arr['is_well_user']!=1){       
                update_iswell_user($user_arr['uid']);
            }
            //发送短信和邮件
           include_once("./module/crontab/crontab_config.php");
            if(MOOPHP_ALLOW_FASTDB){
                $res = MooFastdbGet('members','uid',$sendtoid);
            }else{
               $res= $_MooClass['MooMySQL']->getOne("select * from {$dbTablePre}members where uid='$sendtoid'");
           }
           $send_user_info = $_MooClass['MooMySQL']->getOne("select c.*,b.*,a.* from `{$dbTablePre}members_search` a  left join {$dbTablePre}members_base c on a.uid=c.uid left join  {$dbTablePre}members_choice b  on a.uid=b.uid  where a.uid = '$uid' LIMIT 1");
        
           //头像路径
           $path = thumbImgPath(2,$send_user_info[pic_date],$send_user_info[pic_name]);
        
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
           //require_once MooTemplate('public/mail_space_leertpl', 'module'); //秋波模板
           require_once MooTemplate('public/mail_space_leertpl', 'module'); //秋波模板
           $body = ob_get_clean();
           MooSendMail($res['username'],"真爱一生网系统温馨提示",$body,"",false,$sendtoid);
          // MooSendMailSpace($res['username'],"真爱一生网系统温馨提示","真爱一生网提醒：有会员给您发送秋波了！请尽快查收！您的帐号是:".$res['username'],$type="1",$send_user,$send_type = "秋波",$userid,$send_user_img,$age,$tall,$area,$sendtime,$introduce);
          //每天向同一用户发送多次，短信记录数表只记一次
           $send_leer_date = isset($leer['sendtime']) ? date("Y-m-d",$leer['sendtime']) : date("Y-m-d");//
           $today_leer_count =  isset($leer['lid']) ? $leer['num'] + 1 : 1;
           if(date("Y-m-d") > $send_leer_date){
                $today_leer_count = 1;
           }
         
           
           /*if($res['is_phone']){
             SendMsg($res['telphone'],"真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送秋波,请及时把握您的缘分！4006780405");
           }*/
           
           //SendMsg($res['telphone'],"秋波","真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送秋波,请及时把握您的缘分！4006780405",$userid);
           /*
            $week_time = 24*3600*7;//一周时间秒数
            $interval_time = $timestamp - $user_arr['last_login_time'];//当前时间-最后登录时间
            $date1 = date("Y-m-d",strtotime("last Monday"));
            $date2 = date("Y-m-d",strtotime("Sunday"));
            
            if($interval_time > $week_time){//不活跃用户每周发一条短信
                    $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}today_send SET uid = '".$sendtoid."', sid = '".$userid."',phone = '".$res['telphone']."',sendtime = '".date("Y-m-d")."'" );
                    $cos = $_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}today_send where uid='$sendtoid'  and sendtime>='$date1' and sendtime<='$date2'");
                    if($cos['c']<=1){
                        //fanglin暂时屏蔽
                      Push_message_intab($sendtoid,$res['telphone'],"秋波","真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送秋波,请及时把握您的缘分！4006780405",$userid);
                   }
            }else{ //活跃用户每天一条
                 if($send_leer_date==date("Y-m-d") && $today_leer_count == 1){  //每天同一个用户发送多次秋波，短信只记一次
                   $cos = $_MooClass['MooMySQL']->getOne("select count(*) as c from {$dbTablePre}today_send where uid='$sendtoid' and sendtime='".date("Y-m-d")."'");
                   if($cos[c]<5){
                      $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}today_send SET uid = '".$sendtoid."', sid = '".$userid."',phone = '".$res['telphone']."',sendtime = '".date("Y-m-d")."'" );
                      //fanglin暂时屏蔽
                      Push_message_intab($sendtoid,$res['telphone'],"秋波","真爱一生网 用户ID：".$userid.",".$send_user_grade.",已给您发送秋波,请及时把握您的缘分！4006780405",$userid);
                   }
               }    
            }*/
            
            //提醒所属客服
            $sid = $user_arr['sid'];
            $title = '您的会员 '.$user_arr['uid'].' 向 '.$sendtoid.' 发送了秋波';
            $awoketime = $timestamp+3600;
            $sql_remark = "insert into {$dbTablePre}admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
            $res = $_MooClass['MooMySQL']->query($sql_remark);
            
            
            $users = & $res;
            
            
             MooMessage('发送秋波成功','index.php?n=service&h=leertest&t=sendnewleer');
        }else{
            require MooTemplate('public/service_leer_sendnewleer', 'module');
        }

	
}

//note 已发送秋波的列表
function sendleerto() {
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	$pagesize = 4;
	//note 获取删除提交的变量
	$delleer = MooGetGPC('delleer','string');
	$delleerid = MooGetGPC('delleerid','array');

	//note 删除提交的数据
	if($delleer && count($delleerid)) {
		$time = time();
		foreach($delleerid as $v) {
			//$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}service_leer WHERE lid = '$v'");
			$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET send_del=1,send_deltime = '{$time}' WHERE lid = '$v'");
		}
		MooMessage("秋波删除成功",'index.php?n=service&h=leer&t=isendleer','05');
	}
	
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delleer && !count($delleerid)) {
		MooMessage('请选择要删除秋波','index.php?n=service&h=leer&t=isendleer','01');
		exit;
	}
	
	
	//分页
	$page_per = 4;
	$page_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$page_url = preg_replace( "/(&page=\d+)/","",$page_url );
	$page_now = MooGetGPC( 'page', 'integer', 'G');
	
	$temp = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_leer WHERE senduid  = '$userid' and send_del=0");
	$item_num = $temp['num'];
	$page_num = ceil( $item_num / $page_per );
	if( $page_now > $page_num ) $page_now = $page_num;
	if( $page_now < 1 ) $page_now = 1;
	//读数据
	$start = ($page_now - 1) * $page_per;
	$leer  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_leer WHERE senduid = '$userid' and send_del=0 and is_server=0 ORDER BY sendtime desc LIMIT $start,$page_per");
	
	//note 已收到的总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when receivenum>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_leer WHERE receiveuid = '$userid' and receive_del=0");
	$receive_num = empty( $temp['num'] ) ? 0 : $temp['num'];

	//note 已发送的总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when num>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_leer WHERE senduid = '$userid' and send_del=0 and is_server=0");
	$send_num = empty( $temp['num'] ) ? 0 : $temp['num'];
	
	require MooTemplate('public/service_leer_sendleerto', 'module');
}


//note 已收到秋波的列表
function getmyleers() {
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	
	//note 处理回复秋波
	$repeatleer = MooGetGPC('repeatleer','string');
	$repeatleerid = MooGetGPC('repeatleerid','integer');
	if($repeatleer && $repeatleerid) {
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET stat = '$repeatleer' WHERE senduid = '$repeatleerid' AND receiveuid = '$userid'");
		header("location:index.php?n=service&h=leer");
	}
	
	//note 处理委婉拒绝秋波
	$refuseleer = MooGetGPC('refuseleer','string');
	$refuseleerid = MooGetGPC('refuseleerid','integer');
	if($refuseleer && $refuseleerid) {
		$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET stat = '2' WHERE senduid = '$refuseleerid' AND receiveuid = '$userid'");
		header("location:index.php?n=service&h=leer");
	}
	
	
	//note 获取删除提交的变量
	$delleer = MooGetGPC('delleer','string');
	$delleerid = MooGetGPC('delleerid','array');
	
	//note 删除提交的数据
	if($delleer && count($delleerid)) {
		$time = time();
		foreach($delleerid as $v) {
			//$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}service_leer WHERE lid = '$v'");
			$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_leer SET receive_del=1,receive_deltime='{$time}',receivenum = '0' WHERE lid = '$v'");
		}
		MooMessage("秋波删除成功",'index.php?n=service&h=leer');
	}
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delleer && !count($delleerid)) {
		MooMessage('请选择要删除的秋波','index.php?n=service&h=leer','01');
		exit;
	}
	
	
	//分页
	$page_per = 4;
	$page_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$page_url = preg_replace( "/(&page=\d+)/","",$page_url );
	$page_now = MooGetGPC( 'page', 'integer', 'G');
	
	$temp = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_leer WHERE receiveuid  = '$userid' and receive_del=0");
	$item_num = $temp['num'];
	$page_num = ceil( $item_num / $page_per );
	if( $page_now > $page_num ) $page_now = $page_num;
	if( $page_now < 1 ) $page_now = 1;
	//读数据
	$start = ($page_now - 1) * $page_per;
	$leer  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_leer WHERE receiveuid = '$userid' and receive_del=0  ORDER BY receivetime desc LIMIT $start,$page_per");
	
	//note 已收到的总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when receivenum>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_leer WHERE receiveuid = '$userid' and receive_del=0");
	$receive_num = empty( $temp['num'] ) ? 0 : $temp['num'];

	//note 已发送的总数
	$temp = $_MooClass['MooMySQL']->getOne("SELECT sum(case when num>=1 then 1 else 0 end) as num FROM {$dbTablePre}service_leer WHERE senduid = '$userid' AND send_del = 0 AND is_server=0");
	$send_num = empty( $temp['num'] ) ? 0 : $temp['num'];
	
	require MooTemplate('public/service_leer_getmyleers', 'module');
}

//控制部分
switch ($_GET['t']) {
	//note 我收到的秋波列表
	case "igetleer" :
		getmyleers();
		break;
		
	//note 我发出的秋波列表	
	case "isendleer" :
		sendleerto();
		break;
		
	//note 发新的秋波	
	case "sendnewleer" :
		sendnewleer();
		break;
		
	//note 默认页面跳转到我收到的秋波列表
	default :
		getmyleers();
}

