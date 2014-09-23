<?php
function getone(){
	global $_MooClass,$dbTablePre,$userid,$memcached;
	
	$sendid = MooGetGPC('sendid','integer','G');
	//$sendid = '21691205';
	$user = MooMembersData($sendid);
	
	if($user['uid'] != $sendid || $sendid == ''){
		$error = '查无此人';
		echo return_data($error,false);  
    	exit;
	}
	
	if(MooGetphoto($user['uid'],'com')){
		$mainimg = MooGetphoto($userid,'com');
		$user['mainimg'] = $mainimg;
	} 
	
	$user['password'] = '';
	$user['regip'] = '';
	$user['qq'] = '';
	$user['msn'] = '';
	$user['telphone'] = '';
	$user['username'] = '';
	//print_r($user);
	echo return_data($user,true);  
    exit;

}

//未处理的邮件，秋波，委托
function count_no(){
global $_MooClass,$dbTablePre,$userid,$memcached;
$num_li = array();
//秋波
$temp = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_leer WHERE receiveuid  = '$userid' and receive_del=0 AND stat = '0'");
$num_li['qiubo'] = $temp['num']?$temp['num']:0;

//邮件
$ret_count1 = $_MooClass['MooMySQL']->getOne("SELECT count(s_uid) as c FROM {$dbTablePre}services WHERE s_uid = '$userid' and flag = '1' and s_uid_del='0' and s_status = '0' ",true);
$num_li['youjian'] = $ret_count1['c']?$ret_count1['c']:0;//2009-11-22日修改(得到总数)

//SELECT mid,stat,other_contact_you as uid FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid' AND stat < 4 and send_del=0 and is_server=0 order by sendtime desc LIMIT $start,$pagesize
//委托
$contact = $_MooClass['MooMySQL']->getOne("SELECT count(other_contact_you) as c FROM {$dbTablePre}service_contact WHERE you_contact_other = '$userid' AND stat = '1' and send_del=0 and is_server=0");
$num_li['weituo'] = $contact['c']?$contact['c']:0;

//1分钟内浏览量
$time = time()-61;
$ret_count = $_MooClass['MooMySQL']->getOne("SELECT count(uid) as c FROM {$dbTablePre}service_visitor WHERE uid >0 and visitorid = '$userid' AND who_del !=2 and visitortime > '$time' ");
$num_li['oneminite'] = $ret_count['c']?$ret_count['c']:0;

echo return_data($num_li,true);
//print_r($num_li); 
exit;
}




/**************************************************控制层*******************************/

$c = $_GET['c'] = empty($_GET['c'])? '': $_GET['c'];

switch ($c){
	case 'getone' :
		getone();
		break;
	case 'count':
		count_no();
		break;
		
	default:
		getone();
		break;	
	

}