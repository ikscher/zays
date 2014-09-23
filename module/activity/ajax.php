<?php
/*
 * Created on 10/12/2009
 *
 * yujun
 *
 * module/service/ajax.php
 */
/***************************************功能层******************************/

//note 消息记录以日期作为列表显示
function service_storylist($uid){
	global $_MooClass,$dbTablePre,$user_arr,$userid,$serverid;
	
	if(!$serverid){
	
	//所有发件人ID
	$service_msg_send = $_MooClass['MooMySQL']->getAll("SELECT distinct s_fromid FROM {$dbTablePre}service_chat WHERE is_server=0 and s_uid = '$uid'",false,true);
	//所有收件人ID
	$service_msg_get = $_MooClass['MooMySQL']->getAll("SELECT distinct s_uid FROM {$dbTablePre}service_chat WHERE is_server=0 and s_fromid = '$uid'",false,true);
	
	}else{
	
	//所有发件人ID
	$service_msg_send = $_MooClass['MooMySQL']->getAll("SELECT distinct s_fromid FROM {$dbTablePre}service_chat WHERE s_uid = '$uid'",false,true);
	//所有收件人ID
	$service_msg_get = $_MooClass['MooMySQL']->getAll("SELECT distinct s_uid FROM {$dbTablePre}service_chat WHERE s_fromid = '$uid'",false,true);
	
	}
	
	/*
	//所有发件人ID
	$service_msg_send = $_MooClass['MooMySQL']->getAll("SELECT distinct s_fromid FROM {$dbTablePre}service_chat WHERE s_uid = '$uid'");
	//所有收件人ID
	$service_msg_get = $_MooClass['MooMySQL']->getAll("SELECT distinct s_uid FROM {$dbTablePre}service_chat WHERE s_fromid = '$uid'");
	*/
	
	if(!$service_msg_send && !$service_msg_get){
		echo 'no_data';
		exit;
	}
	//所有发件人的相关信息
	foreach($service_msg_send as $v){
		$form_id_Arr[] = $v['s_fromid'];
	}
	foreach($service_msg_get as $v){
		$form_id_Arr[] = $v['s_uid'];
	}
	//去重复ID
	$form_id_Arr = array_keys(array_count_values($form_id_Arr));
	$fromid = implode(',',$form_id_Arr);
	$from_user = $_MooClass['MooMySQL']->getAll("SELECT uid,nickname FROM {$dbTablePre}members_search WHERE uid in ($fromid)",false,true);
	//note 选定会员ID
	$get_uid = MooGetGPC('uid','integer');
	//note 会员昵称
	$get_username = MooGetGPC('username','string');
	//以时间分隔数据
	$story_msg = $_MooClass['MooMySQL']->getAll("select s_time from {$dbTablePre}service_chat where (s_fromid='$get_uid' and s_uid='$uid') or (s_fromid='$uid' and s_uid='$get_uid') order by s_time desc",false,true);
	//所有时间的集合 $timeArr
	//所有日期的集合 $timeArr_date
	foreach($story_msg as $v){
		$timeArr[] = $v['s_time'];
		$timeArr_date[] = date('Y-m-d',$v['s_time']);
	}
	//相同日期的个数
	$date_count = array_count_values($timeArr_date);
	//$storylistArr = array();
	$n = 0;//列表下标
	$time1 = 0;//时间下标
	$time2 = 0;//时间下标
	if(is_array($date_count)){
		foreach($date_count as $k=>$v){
			$storylistArr[$n]['date'] = $k;
			if($get_username){
				$storylistArr[$n]['uname'] = $get_username;
			}else{
				$storylistArr[$n]['uname'] = 'ID'.$get_uid;
			}
			$storylistArr[$n]['num'] = $v;
			$time2 = $time1 + $v;
			$storylistArr[$n]['between'] = "between (".$timeArr[$time2-1].") and ({$timeArr[$time1]})";
			$time1 = $time2;
			$n ++;
		}
	}
	require MooTemplate('public/ajax_service_storylist', 'module');
	exit;
}
//note 某一天内的消息记录
function service_storydetail($uid){
	global $_MooClass,$dbTablePre,$user_arr,$serverid;
	//note 选定会员ID
	$get_uid = MooGetGPC('uid','integer');
	//note 页面显示记录数
	$get_pagesize = MooGetGPC('pagesize','integer');
	if(!$get_pagesize){
		$get_pagesize = 20;
	}
	//note 会员昵称
	$get_username = MooGetGPC('username','string');
	//时间
	$between = MooGetGPC('between','string');
	//echo $get_uid.'<br>'.$get_pagesize.'<br>'.$get_page.'<br>'.$between.'<br>'.$get_username.'<br>';
	
	/*
	//聊天内容条数
	$story_num_msg = $_MooClass['MooMySQL']->getOne("select count(*) from {$dbTablePre}service_chat where ((s_fromid='$get_uid' and s_uid='$uid') or (s_fromid='$uid' and s_uid='$get_uid')) and s_time $between");
	*/
	/*		
	if($serverid){
	$story_num_msg = $_MooClass['MooMySQL']->getone("select count(*) from {$dbTablePre}service_chat where ((s_fromid='$get_uid' and s_uid='$uid') or (s_fromid='$uid' and s_uid='$get_uid')) and s_time $between");
	}else{
	$story_num_msg = $_MooClass['MooMySQL']->getOne("select count(*) from {$dbTablePre}service_chat where ((s_fromid='$get_uid' and s_uid='$uid') or (s_fromid='$uid' and s_uid='$get_uid' and is_server=0)) and s_time $between");
	}
	*/
	if($serverid){
	$story_num_msg = $_MooClass['MooMySQL']->getone("select count(*) from {$dbTablePre}service_chat where ((s_fromid='$get_uid' and s_uid='$uid') or (s_fromid='$uid' and s_uid='$get_uid')) and s_time $between",true);
	}else{
	$story_num_msg = $_MooClass['MooMySQL']->getOne("select count(*) from {$dbTablePre}service_chat where ((s_fromid='$get_uid' and s_uid='$uid') or (s_fromid='$uid' and s_uid='$get_uid')) and is_server!='$uid' and s_time $between",true);
	}
	//var_dump($story_num);exit;
	$story_num = $story_num_msg['count(*)'];
	//总页数
	$pagecount = ceil($story_num/$get_pagesize);
	//页码
	$get_page = MooGetGPC('page','integer');
	if(!$get_page || $get_page < 1){
		$get_page = 1;
	}elseif($get_page >= $pagecount){
		$get_page = $pagecount;
	}
	$start = ($get_page-1)*$get_pagesize;
	if($start<0){$start=0;}
	$end = $start+$get_pagesize;
	/*
	//聊天内容
	$story_msg = $_MooClass['MooMySQL']->getAll("select s_content,s_time,s_fromid from {$dbTablePre}service_chat where ((s_fromid='$get_uid' and s_uid='$uid') or (s_fromid='$uid' and s_uid='$get_uid')) and s_time $between order by s_time desc limit $start,$get_pagesize");
	*/
	
	/*			
	if($serverid){
	$story_msg = $_MooClass['MooMySQL']->getAll("select s_content,s_time,s_fromid from {$dbTablePre}service_chat where ((s_fromid='$get_uid' and s_uid='$uid') or (s_fromid='$uid' and s_uid='$get_uid')) and s_time $between order by s_time desc limit $start,$get_pagesize");
	}else{
	$story_msg = $_MooClass['MooMySQL']->getAll("select s_content,s_time,s_fromid from {$dbTablePre}service_chat where ((s_fromid='$get_uid' and s_uid='$uid') or (s_fromid='$uid' and s_uid='$get_uid' and is_server=0)) and s_time $between order by s_time desc limit $start,$get_pagesize");
	}
	*/
	
	if($serverid){
	$story_msg = $_MooClass['MooMySQL']->getAll("select s_content,s_time,s_fromid from {$dbTablePre}service_chat where ((s_fromid='$get_uid' and s_uid='$uid') or (s_fromid='$uid' and s_uid='$get_uid')) and s_time $between order by s_time desc limit $start,$get_pagesize",false,true);
	}else{
	$story_msg = $_MooClass['MooMySQL']->getAll("select s_content,s_time,s_fromid from {$dbTablePre}service_chat where ((s_fromid='$get_uid' and s_uid='$uid') or (s_fromid='$uid' and s_uid='$get_uid')) and is_server!='$uid' and s_time $between order by s_time desc limit $start,$get_pagesize",false,true);
	}
	
	//输出内容
	require MooTemplate('public/ajax_service_storydetail', 'module');
}
//note 显示当天聊天消息内容（第一次打开聊天窗口）
function service_chat_show_first($uid){
	global $_MooClass,$dbTablePre,$user_arr;
	//对方ID
	$chatorid = MooGetGPC('chatorid','integer');
	//对方昵称
	$chatornickname = MooGetGPC('chatornickname','string');
	//当天双方消息
	/*原来的
	$chatmsg = $_MooClass['MooMySQL']->getAll("select * from {$dbTablePre}service_chat where ((s_uid='$uid' and s_fromid='$chatorid') or (s_uid='$chatorid' and s_fromid='$uid')) and s_time>".mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()))." order by s_time asc");
	*/
	/*	
	if($serverid){
	$chatmsg = $_MooClass['MooMySQL']->getAll("select s_id from {$dbTablePre}service_chat where ((s_uid='$uid' and s_fromid='$chatorid') or (s_fromid='$uid' and s_uid='$chatorid')) and s_status=0");
	}else{
	$chatmsg = $_MooClass['MooMySQL']->getAll("select s_id from {$dbTablePre}service_chat where ((s_uid='$uid' and s_fromid='$chatorid') or (s_fromid='$uid' and s_uid='$chatorid' and is_server=0)) and s_status=0");
	}*/
	if($serverid){
	$chatmsg = $_MooClass['MooMySQL']->getAll("select s_id from {$dbTablePre}service_chat where ((s_uid='$uid' and s_fromid='$chatorid') or (s_fromid='$uid' and s_uid='$chatorid')) and s_status=0");
	}else{
	$chatmsg = $_MooClass['MooMySQL']->getAll("select s_id from {$dbTablePre}service_chat where ((s_uid='$uid' and s_fromid='$chatorid') or (s_fromid='$uid' and s_uid='$chatorid')) and is_server!='$uid' and s_status=0");
	}
	
	
	if($chatmsg){
		$str = '';
		foreach($chatmsg as $v){
			if($v['s_fromid'] == $chatorid){
				//$str .= '<p>'.$chatornickname.'（'.date('H:i:s',$v['s_time']).'）说：</p><p class="other">'.MooStrReplace(nl2br(htmlspecialchars($v['s_content']))).'</p>';
				$str .= '<p>'.$chatornickname.'（'.date('H:i:s',$v['s_time']).'）说：</p><p class="other">'.nl2br(htmlspecialchars($v['s_content'])).'</p>';
			}elseif($v['s_fromid'] == $uid){
				//$str .= '<p>我对'.$chatornickname.'（'.date('H:i:s',$v['s_time']).'）说：</p><p class="myself">'.MooStrReplace(nl2br(htmlspecialchars($v['s_content']))).'</p>';
				$str .= '<p>我对'.$chatornickname.'（'.date('H:i:s',$v['s_time']).'）说：</p><p class="myself">'.nl2br(htmlspecialchars($v['s_content'])).'</p>';
			}
			//消息主键集合（当前会员收信主键）
			if($v['s_status'] == 0 && $v['s_fromid'] == $chatorid){
				$s_id[] = $v['s_id'];
			}
		}
		//改消息的读取状态
		if($s_id){
			$_MooClass['MooMySQL']->query("update {$dbTablePre}service_chat set s_status=1 where s_id in (".implode(',',$s_id).")");
		}
	}
	echo $str;
}
//note 显示在线聊天消息内容（当前会员未读消息）
function service_chat_show($uid){
	global $_MooClass,$dbTablePre,$user_arr,$serverid;
	//对方ID
	$chatorid = MooGetGPC('chatorid','integer');
	//对方昵称
	$chatornickname = MooGetGPC('chatornickname','string');

	$chat_id = MooGetGPC('chat_id','integer');
	//对方发的消息（查找出当前会员未读的消息）
	/*原来的
	$chatmsg = $_MooClass['MooMySQL']->getAll("select * from {$dbTablePre}service_chat where s_uid='$uid' and s_fromid='$chatorid' and s_status=0 and is_server='$serverid' and s_time>".mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()))." order by s_time asc");
	*/
	$chatmsg = $_MooClass['MooMySQL']->getAll("select * from {$dbTablePre}service_chat where s_uid='$uid' and s_fromid='$chatorid' and s_status=0 and s_id>{$chat_id}  order by s_time asc");
	if($chatmsg){
		$str = '';
		foreach($chatmsg as $v){
				//$str .= '<p>'.$chatornickname.'（'.date('H:i:s',$v['s_time']).'）说：</p><p class="other">'.MooStrReplace(nl2br(htmlspecialchars($v['s_content']))).'</p>';
				$str .= '<p>'.$chatornickname.'（'.date('H:i:s',$v['s_time']).'）说：</p><p class="other">'.\nl2br(htmlspecialchars($v['s_content'])).'</p>';
			//消息主键集合
			$s_id[] = $v['s_id'];
		}
		//改消息的读取状态
		$_MooClass['MooMySQL']->query("update {$dbTablePre}service_chat set s_status=1 where s_id in (".implode(',',$s_id).")");
	}
	$mes['content']=$str;
	$mes['s_id']= $v['s_id'];
	echo json_encode($mes);
}
//note 添加在线聊天消息内容
function service_chat_add($uid){
	global $_MooClass,$dbTablePre,$user_arr,$serverid;
	//对方ID
	$chatorid = MooGetGPC('chatorid','integer');
	//对方昵称
	$chatornickname = MooGetGPC('chatornickname','string');
	//当前会员提交的新消息
	$content = MooGetGPC('content','string');
	//添加内容
	$str = '';
	if($content && $chatorid){
		$time = time();		
		if($serverid){
			$_MooClass['MooMySQL']->query("insert into {$dbTablePre}service_chat (`s_uid`,`s_fromid`,`s_content`,`s_time`,`is_server`) values ('$chatorid','$uid','$content','".$time."','$serverid')");
		}else{			
			$_MooClass['MooMySQL']->query("insert into {$dbTablePre}service_chat (`s_uid`,`s_fromid`,`s_content`,`s_time`,`is_server`) values ('$chatorid','$uid','$content','".$time."','0')");			
		}
		//当前用户刚插入表中的内容
		//$str = '<p>我对'.$chatornickname.'（'.date('H:i:s',$time).'）说：</p><p class="myself">'.MooStrReplace(nl2br(htmlspecialchars($content))).'</p>';
		$str = '<p>我对'.$chatornickname.'（'.date('H:i:s',$time).'）说：</p><p class="myself">'.nl2br(htmlspecialchars($content)).'</p>';
	}
	echo $str;
}

//note 委托填号码
function addphone(){
	global $_MooClass,$dbTablePre,$user_arr,$userid,$serverid;
	$phone = $_GET['phone'];
	$value = array();
	if($phone && preg_match('/^((1[35][\d]{9})|(18[4689][\d]{8}))$/',$phone)){
		 if($_MooClass['MooMySQL']->query("update {$dbTablePre}members_search set telphone='$phone' where uid='$userid'")){
			if(MOOPHP_ALLOW_FASTDB){
				$value['phone'] = $phone;
				MooFastdbUpdate('members_search','uid',$userid,$value);
			}
		 	echo $phone;
		 }
	}else{
		echo 0;
	}
}

//读取身份证信息
function view_request_sms(){
	global $_MooClass,$dbTablePre,$user_arr,$userid,$serverid;
	
	$member_level = get_userrank($userid);
	if($member_level==0){
		echo 'no_vip';exit;
	}
	$uid = MooGetGPC('uid','integer','G');
	
	//判断是否有权限查看
	$sql = "SELECT id FROM {$dbTablePre}members_requestsms WHERE uid='{$uid}'  AND ruid='{$userid}' AND status=1";
	$requestsms = $_MooClass['MooMySQL']->getOne($sql,true);
	if(empty($requestsms)){
		return false;
	}
	//$uid = 2146973;
//	if(MOOPHP_ALLOW_FASTDB){
//		$members = MooFastdbGet('members_base','uid',$uid);
//	}else{
		$sql = "SELECT gender,birth FROM {$dbTablePre}members_base WHERE uid='{$uid}'";
		list($members['birthyear'],$members['birthmonth'],$members['birthday']) = explode('-',$members['birth']); //birth modify
		$members = $_MooClass['MooMySQL']->getOne($sql);
//	}
	
	if($members['gender'] == 1) $gender='女';
	else $gender = '男';
	
	$sql = "SELECT realname,idcode FROM {$dbTablePre}smsauths WHERE uid='{$uid}'";
	$sms = $_MooClass['MooMySQL']->getOne($sql,true);
	if(empty($sms)){
		echo 'no';exit;
	}
	//$filename = "data/sms/{$uid}.jpg";
	//if(!file_exists($filename)){
		//$get_pic = get_pic($sms['idcode'],$filename);
		$get_pic = get_pic2($sms['idcode'],$filename);

	//}
	
	echo "<div>
			<table>
				<tr><td>姓名:</td><td>{$sms['realname']}</td></tr>
				<tr><td>性别:</td><td>{$gender}</td></tr>
				<tr><td>出生年月:</td><td>{$members['birthyear']}年{$members['birthmonth']}月{$members['birthday']}日</td></tr>
				<tr><td>照片:</td><td><img src='$get_pic' /></td></tr>
			</table>
		</div>";
}


//读取验证码是否填写正确
function remark_seccode(){
	global $memcached;
	$seccode1 = strtolower(MooGetGPC('seccode','string','G'));
	$seccode2 = MooGetGPC('seccode','string','C');
	$session_seccode = $memcached->get($seccode2);

	if($session_seccode != $seccode1){
		echo '2';
	}else if($session_seccode != '' && $seccode1 && $session_seccode == $seccode1){
		echo '1';	
	}
}

function activity_regist_page() {
    global $_MooClass;
    $channel=MooGetGPC('channel','intval','G');//活动项目
    $sex=MooGetGPC('sex','intval','G');//性别
	$uid=MooGetGPC('uid','integer','G');
    $page = intval(max(1,MooGetGPC('page','integer','G')));
    $offer=($page-1)*9;
    $edu_array=array('3'=>'高中及以下','4'=>'大专','5'=>'大学本科','6'=>'硕士','7'=>'博士');
    $occupation_list = array("1" => "金融业", "2" => "计算机业", "3" => "商业", "4" => "服务行业", "5" => "教育业", "6" => "工程师", "7" => "主管，经理", "8" => "政府部门", "9" => "制造业", "10" => "销售/广告/市场", "11" => "资讯业", "12" => "自由业", "13" => "农渔牧", "14" => "医生", "15" => "律师", "16" => "教师", "17" => "幼师", "18" => "会计师", "19" => "设计师", "20" => "空姐", "21" => "护士", "22" => "记者", "23" => "学者", "24" => "公务员", "26" => "职业经理人", "27" => "秘书", "28" => "音乐家", "29" => "画家", "30" => "咨询师", "31" => "审计师", "32" => "注册会计师", "33" => "军人", "34" => "警察", "35" => "学生", "36" => "待业中", "37" => "消防员", "38" => "经纪人", "39" => "模特", "40" => "教授", "41" => "IT工程师", "42" => "摄影师", "43" => "企业高管", "44" => "作家", "99" => "其他行业");
	$man_qixi_regest=$_MooClass['MooMySQL']->getAll('SELECT a.`uid`,b.`nickname`,b.`education`,b.`occupation`,b.`birthyear` FROM `web_ahtv_reguser` as a left join web_members_search as b  on a.`uid`=b.`uid` WHERE a.`uid`!=0 and a.`channel`='.$channel.' and a.`gender`='.$sex.' and b.nickname!=\'\' order by a.`regtime` desc limit '.$offer.',9 ');
    $html=array();
    $img=$sex?'girl_icon.jpg':'man.jpg';
	if($sex == '1'){
	 $html[]='<dl class="apply" id="cont11" style="height:285px;">';
	}else{
	 $html[]='<dl class="apply" id="cont22" style="height:285px;">';
	}
	
<<<<<<< .mine
	$new_channels = array(26, 27, 29,31); // $channel 在其中时 显示 “昵称 年龄 职业” 三项 其他显示 “年龄和学历两项”
=======
	$new_channels = array(26, 27, 29,31,32); // $channel 在其中时 显示 “昵称 年龄 职业” 三项 其他显示 “年龄和学历两项”
>>>>>>> .r6337
	
	if (in_array($channel, $new_channels)) {
		$html[] = '<dt><span class="name">呢称</span><span class="age">年龄</span><span class="education">职业</span></dt>';
	} else $html[] = '<dt><span class="age">年龄</span><span class="education">学历</span></dt>'; //<span class="name">呢称</span><span class="sex">性别</span>
    foreach($man_qixi_regest as $key=>$nv){		
//        if ($key == 0) { $html[]='<dd class="clear">'; } else 
        $html[]='<dd>';
	   if ($uid=='20310462'){
          $html[]='<span class="name"><a href="index.php?n=space&uid='.$nv['uid'].'" target="_blank">'.MooCutstr($nv[nickname], 8,'').'</a></span>';
          $html[]='<span class="sex"><img src="module/activity/templates/default/images/activity_new/'.$img.'" /></span>';
	   }
	   if (in_array($channel, $new_channels)) {
		   $html[] = '<span class="name"><a href="index.php?n=space&uid=' . $nv[uid] . '" target="_blank">' . MooCutstr($nv[nickname], 8,'') . '</a></span>';
	   }
	   $html[]='<span class="age">'.(empty($nv[birthyear])?'保密':(date('Y')-$nv[birthyear])).'</span>';
	   if (in_array($channel, $new_channels)) {
		   $html[] = '<span class="education">' . (empty($occupation_list[$nv[occupation]])?'保密':$occupation_list[$nv[occupation]]) . '</span>';
	   } else $html[]='<span class="education">'.(empty($edu_array[$nv[education]])?'保密':$edu_array[$nv[education]]).'</span>';
       $html[]='</dd>';
	   
    }
	$html[]='</dl>';
	
    echo implode('',$html);
    exit;
}

/***************************************控制层****************************************/ 

//require 'function.php';
include "./module/{$name}/function.php";

//note 初始化用户id
MooUserInfo();
$userid = $GLOBALS['MooUid'];
//$user = $_MooClass['MooMySQL']->getOne("SELECT mthumbimg FROM {$dbTablePre}members WHERE uid = '$userid'");

$h=$_GET['h'];
if(!$userid && $h!='page') {
echo $_GET['h']=='show'?json_encode(array('content'=>'no_login','s_id'=>'0')):'no_login';
exit;
}

$serverid = Moo_is_kefu();

switch ($_GET['h']) {
	//note 聊天内容以日期显示的列表项
	case "storylist" :
		service_storylist($userid);
		break;
	
	//note 具体某一天的聊天内容
	case "storydetail" :
		service_storydetail($userid);
		break;
		
	//note 具体某一天的聊天内容
	case "show" :
		service_chat_show($userid);
		break;
	//note 添加具体某一天的聊天内容
	case "add" :
		service_chat_add($userid);
		break;
	//note 显示当天聊天消息内容（第一次打开聊天窗口）
	case "showfirst" :
		service_chat_show_first($userid);
		break;
		
	//note 委托填号码
	case "addphone" :
		addphone();
		break;
	case 'view_request_sms':
		view_request_sms();
		break;
    case 'seccode':
		remark_seccode();
	    break;
    case 'page':
        activity_regist_page();
    break;
	//note 
	default :
		service_storylist($userid);
		break;
}
?>
