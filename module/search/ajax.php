<?php
/*
 * Created on 10/14/2009
 *
 * yujun
 *
 * module/service/ajax.php
 */
/***************************************功能层******************************/
//浮动DIV显示内容——消息、被谁访问空间、在线聊天
function public_showmsg($uid){
	header("Expires: Mon, 26 Jul 1970 05:00:00  GMT");  
	header("Cache-Control:no-cache, must-revalidate");  
	header("Pragma:no-cache");  
	global $_MooClass,$dbTablePre,$timestamp,$memcached,$user_arr;
	//会员ID
	//$uid = MooGetGPC('uid','integer');
	//初始化显示数据
	$str = '';
	$n = 0;
	
	//未读消息条数
	//$msg = $_MooClass['MooMySQL']->getOne("select count(*) from {$dbTablePre}services where s_uid={$uid} and s_status='0' and flag = '1' and s_uid_del='0'");
	//$msg_count = $msg['count(*)'];
	$msg_count = header_show_total($uid);

	
	//前台显示
	if($msg_count){
		//3个发件人的ID
		$user_msg_id = $_MooClass['MooMySQL']->getAll("select distinct s_fromid from {$dbTablePre}services where s_uid={$uid} and s_status='0' and s_uid_del='0' order by s_time desc limit 3");
		$inidArr = array();
		foreach($user_msg_id as $v){
			if($v['s_fromid'] == 0) {
				$from_kefu = 1;
			} else {
				$inidArr[] = $v['s_fromid'];
			}
		}
		$inid = implode(',',$inidArr);
		//提示信息
		$user_msg = array();
		if(MOOPHP_ALLOW_FASTDB){
			foreach($inidArr as $arruid){
				$user_msg[]=MooFastdbGet('members_search','uid',$arruid);
			}
		}else{
			$user_msg = $_MooClass['MooMySQL']->getAll("select uid,nickname,s_cid from {$dbTablePre}members_search where uid in ($inid)");
		}
		$str .= '<p>您有<a href="index.php?n=service&h=message">'.$msg_count.'条新消息</a></p>';
		foreach($user_msg as $vv){
			//设置不同链接
			if($vv){
				//$nickname=$vv['nickname2']?$vv['nickname2']:$vv['nickname'];
					$nickname=$vv['nickname'];
				if($nickname){
					
					$str .= '<p>查看<a href="index.php?n=service&h=message&t=membermessage">'.$nickname.'给您发的新邮件</a></p>';
				}else{
					$str .= '<p>查看<a href="index.php?n=service&h=message&t=membermessage">会员ID '.$vv['uid'].'给您发的新邮件</a></p>';
				}
			}
		}
		if($from_kefu){				
			$str .= '<p>查看<a href="index.php?n=service&h=message&t=hlmessage">真爱一生网发新邮件</a></p>';	
		}
		$n++;
	}

	//提示被哪些用户访问主页
	if($user_arr['regdate']<(time()-7200)){
		if($user_arr['showinformation']){
			$visitor_msg = public_showmsg_two($uid);
		}
		//$nickname=$visitor_msg['nickname2']?$visitor_msg['nickname2']:$visitor_msg['nickname'];
		$nickname=$visitor_msg['nickname'];
		$nc = $nickname?$nickname:'ID:'.$visitor_msg['uid'];
		
		if($visitor_msg){
			//伪造查看共多少秒,存memcached
			$makevisit_sec = $memcached->get('makevisit'.$visitor_msg['uid'].'_'.$uid);
			if($makevisit_sec){
				$sec = $makevisit_sec;
			}else{
				$sec = rand(100,200);
				$memcached->set('makevisit'.$visitor_msg['uid'].'_'.$uid,$sec,60);
			}
			$img=MooGetphoto($visitor_msg['uid'],'page');//显示访问者的头像
			$mid_img=MooGetphoto($visitor_msg['uid'],'mid');
			if($mid_img&&!$img){
				$image = MooAutoLoad('MooImage');					
				$pic_dir=substr($mid_img,0,28);
				$new_file_name=($visitor_msg['uid']*3)."_page.jpg";				
				$image->config(array('thumbDir'=>$pic_dir,'thumbStatus'=>'1','saveType'=>'0','thumbName' =>$new_file_name,'waterMarkMinWidth' => '82', 'waterMarkMinHeight' => '114', 'waterMarkStatus' => 9));
				$image->thumb(49,62,$mid_img);
				$img=$page_img;
			}
		if($img){
			$photo="<a target='_blank' href='index.php?n=space&h=viewpro&uid=".$visitor_msg['uid']."'><img src=".$img." class='u-photo'></a>";
			$str .= '<div class="c-line"></div><p>您的资料正在被:</p>'.$photo.'<p><a target="_blank" href="index.php?n=space&h=viewpro&uid='.$visitor_msg['uid'].'">'.$nc.'</a>查看,</p><p>已查看'.$sec.'秒,</p><p><a target="_blank" href="index.php?n=space&h=viewpro&uid='.$visitor_msg['uid'].'">查看Ta的资料</a></p><div style="clear:both"></div>';
		}else{
			$str .= '<p>您的资料正在被'.$photo.'<a target="_blank" href="index.php?n=space&h=viewpro&uid='.$visitor_msg['uid'].'">'.$nc.'</a>查看,已查看'.$sec.'秒,<a target="_blank" href="index.php?n=space&h=viewpro&uid='.$visitor_msg['uid'].'">查看Ta的资料</a></p>';
		}
			$n++;
		}
	}
	//提示当前会员有未读在线聊天消息
	$chat_msg = public_showmsg_three($uid);
	$new_message = '';
	if($chat_msg){
		$str .= '<p>您有<a style="cursor:pointer" onclick="javascript:window.open(\'index.php?n=service&h=chat&chatorid='.$chat_msg['s_fromid'].'\', \'_blank\', \'height=500, width=700, toolbar =no, menubar=no, scrollbars=no, resizable=yes, location=no, status=no\')">新的在线消息</a></p>';
		$new_message = '|new_message';
		$n++;
	}

	//委托真爱一生联系TA消息提醒,十五天后自动消失
	$expires_time = time()-3600*24*15;
	$sql_contact = "SELECT count(*) as yc_count,other_contact_you,you_contact_other FROM {$dbTablePre}service_contact WHERE is_read=0 and you_contact_other = '$uid' AND syscheck = '1' AND stat = '1' AND sendtime > ".$expires_time;
	$ret = $_MooClass['MooMySQL']->getOne($sql_contact);
	if($uid == $ret['you_contact_other']){
		$you_contact_other_msg_count = $ret['yc_count'];
		if($you_contact_other_msg_count>0){
			$str .= '<p><a style="cursor:pointer" onclick="javascript:window.open(\'index.php?n=space&h=viewpro&uid='.$ret['other_contact_you'].'\', \'newwindow\', \'height=480, width=680, toolbar =no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no\'),update_read(type=2,uid='.$uid.')">ID为'.$ret['other_contact_you'].'</a>委托真爱一生联系您</p>';
			$n++;
		}
	}
	
	//秋波发送提醒,十五天后自动消失
	$sql_leer = "SELECT receiveuid,senduid FROM web_service_leer WHERE is_read=0 and receiveuid = '" . $uid . "' AND  receivetime > ".$expires_time; 
	$ret_leer = $_MooClass['MooMySQL']->getOne($sql_leer);
	
	if($uid == $ret_leer['receiveuid']){
		$str .= '<p><a style="cursor:pointer" onclick="javascript:window.open(\'index.php?n=space&h=viewpro&uid='.$ret_leer['senduid'].'\', \'newwindow\', \'height=480, width=680, toolbar =no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no\'),update_read(type=0,uid='.$uid.')">ID为'.$ret_leer['senduid'].'</a>向您送出了一个秋波</p>';
		$n++;
	}
	//鲜花发送提醒
	$sql_rose = "SELECT receiveuid,senduid,num FROM web_service_rose WHERE is_read=0 and receiveuid = '" . $uid . "' AND receivetime > ".$expires_time;
	$ret_rose = $_MooClass['MooMySQL']->getOne($sql_rose);
	if($uid == $ret_rose['receiveuid']){
		$str .= '<p><a style="cursor:pointer" onclick="javascript:window.open(\'index.php?n=space&h=viewpro&uid='.$ret_rose['senduid'].'\', \'newwindow\', \'height=480, width=680, toolbar =no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no\'),update_read(type=1,uid='.$uid.')">ID为'.$ret_rose['senduid'].'</a>向您送出了鲜花</p>';
		$n++;
	}

	//意中人提醒
	$sql_liker = "SELECT * FROM web_service_friend WHERE is_read=0 and friendid = '".$uid."' AND sendtime > " .$expires_time; 
	$ret_liker = $_MooClass['MooMySQL']->getOne($sql_liker);
	if($uid == $ret_liker['friendid']){
		$str .= '<p><a style="cursor:pointer" onclick="javascript:window.open(\'index.php?n=space&h=viewpro&uid='.$ret_liker['uid'].'\', \'newwindow\', \'height=480, width=680, toolbar =no, menubar=no, scrollbars=yes, resizable=yes, location=no, status=no\'),update_read(type=3,uid='.$uid.')">ID为'.$ret_liker['uid'].'</a>将您添加为意中人了</p>';
		$n++;
	}

	//输出
	if($str){
		$str .= "|最新提醒($n){$new_message}";
		echo $str;
	}else{
		echo $n;
	}
}

//更新该消息为已读
function update_read(){
	global $_MooClass;
	$message_type=$_GET['type'];
	$uid=$_GET['uid'];
	$array_table=array('leer','rose','contact','friend');
	$id_limit_array=array('receiveuid','receiveuid','you_contact_other','friendid');
	echo $array[$message_type];
	$sql="update web_service_".$array_table[$message_type]." set is_read=1 where ".$id_limit_array[$message_type]."=".$uid;
	$_MooClass['MooMySQL']->query($sql);
}

//浮动DIV显示内容——提示被哪些用户访问主页
function public_showmsg_two($uid){
	global $_MooClass,$dbTablePre,$timestamp,$memcached,$user_arr;
	$time = time();

	$lastmake = $memcached->get('lastmake'.$GLOBALS['MooUid']);
	//echo "<br />".$lastmake;
	//if(($lastmake+3)>$time){
		$get_visitor_uid = $_MooClass['MooMySQL']->getOne("select uid from {$dbTablePre}service_visitor where  visitorid={$uid} and who_del!=2 and visitortime>".(time()-600)." and visitortime < ".(time()-60)." order by visitortime desc limit 1");
		if($get_visitor_uid){
			if(MOOPHP_ALLOW_FASTDB){
				$msg = MooFastdbGet('members_search','uid',$get_visitor_uid['uid']);
			}else{
				$sql="SELECT * FROM {$dbTablePre}members_search where uid='{$get_visitor_uid['uid']}'";
				$msg=$_MooClass['MooMySQL']->query($sql);
			}
		}else{
			//note 伪造
			if($user_arr['s_cid'] ==40){
				make($uid,1);
			}else{
				make($uid,2);
			}		
		}
	//}
	//}
	if($msg){
		return $msg;
	}else{
		return 0;
	}
}
//浮动DIV显示内容——提示当前会员有未读在线聊天消息
function public_showmsg_three($uid){
	global $_MooClass,$dbTablePre;
	$msg = $_MooClass['MooMySQL']->getOne("select s_fromid from {$dbTablePre}service_chat where s_uid='$uid' and s_status=0 and s_time>".mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time())));
	if($msg){
		return $msg;
	}else{
		return 0;
	}
}

//note 仿造XX访问当前会员主页
//function make_visitor(){
function make($uid,$usertype){
	global $_MooClass,$dbTablePre,$timestamp,$memcached;
	$time = time();

	$randdo = rand(1,10);
	
	if($randdo > 8){
	  return 0;
	}elseif($randdo>5){//省内
		$address="province";	
	}else{//本市
		$address="ctiy";
	}
	
	$lastmake = $memcached->get('lastmake'.$GLOBALS['MooUid']);

	if(empty($lastmake)){
		$memcached->set('lastmake'.$GLOBALS['MooUid'],$time);
	}else{
		if($lastmake + rand(100,180) > $time){
			return 0;
		}else{
		$memcached->replace('lastmake'.$GLOBALS['MooUid'],$time);
		}
	}
	
	//note 当前会员工作地
	if(MOOPHP_ALLOW_FASTDB){
		$current_user = MooFastdbGet('members_search','uid',$uid);
	}else{
		$current_user = $_MooClass['MooMySQL']->getOne("select province,city,gender,birthyear from {$dbTablePre}members_search where uid='$uid'");
	}

	//note 与当前会员在同一工作地或同一省的会员
	$fgender = $current_user['gender']=='0'?'1':'0';//取当前用户相反性别
	//note 与会员年龄上下相差5岁左右的
	if($fgender==1){
	$age1 = $current_user['birthyear']-1;
	$age2 = $current_user['birthyear']+9;
	}else{
	$age1 = $current_user['birthyear']-9;
	$age2 = $current_user['birthyear']+1;
	}

	MooPlugins('ipdata');
	$ip=GetIP();
	$user_address = convertIp($ip);
	//$user_address="安徽省合肥市 电信";
	include("ework/include/crontab_config.php");	
	
	foreach($provice_list as $key=>$provice_arr){
		if(strpos($user_address,$provice_arr)!==false){
			$province=$key;
			break;
		}
	}
	if(!$province){
		$province=$current_user['province'];
	}
	$age_between = " AND mes1.birthyear>='$age1' AND mes1.birthyear<='$age2'";
		
	if($fgender == '0'){
		//if($usertype=='1'){
			//$sql = "SELECT count(1) AS num FROM {$dbTablePre}membersfastsort_man WHERE  province='{$province}'  AND birthyear>='$age1' AND birthyear<='$age2' and images_ischeck=1";
		//}else{
			$sql = "SELECT count(1) AS num FROM {$dbTablePre}members_search WHERE  province='{$province}'  AND birthyear>='$age1' AND birthyear<='$age2' and images_ischeck=1 and usertype=1";
		//}
	}
	if($fgender == '1'){
		//if($usertype=='1'){//普通会员
			//$sql = "SELECT count(1) AS num FROM {$dbTablePre}membersfastsort_women WHERE  province='{$province}'  AND birthyear>='$age1' AND birthyear<='$age2' and images_ischeck=1";
		//}else{//高级会员产生的浏览为本站的
			$sql = "SELECT count(1) AS num FROM {$dbTablePre}members_search WHERE  province='{$province}'  AND birthyear>='$age1' AND birthyear<='$age2' and images_ischeck=1 and usertype=1";
		//}
	}
	
	if(MOOPHP_ALLOW_FASTDB){
		global $fastdb;
		$md5_sql1=md5($sql);
		$inprovince=unserialize($fastdb->get($md5_sql1));
		if(!$inprovince||$inprovince['last_time']<time()){
			$inprovince = $_MooClass['MooMySQL']->getOne($sql);
			$inprovince['last_time']=time()+(3600*24*7);
			$fastdb->set($md5_sql1,serialize($inprovince));
			}
	}else{
		$inprovince = $_MooClass['MooMySQL']->getOne($sql);
	}
	
    $rand = $inprovince['num']>60 ? $inprovince['num']:30000;
	$rand = mt_rand(1,$rand);
	
    $arrUser = array();
	/*if($inprovince['num']>60){	
		if($fgender == '0'){
			//if($usertype=='1'){
				//$sql = "SELECT mes1.uid as uid FROM {$dbTablePre}membersfastsort_man AS mes1 WHERE   (mes1.province='{$province}') and mes1.is_lock=1  $age_between and images_ischeck=1  LIMIT {$rand},1";
			//}else{
				$sql = "SELECT mes1.uid as uid FROM {$dbTablePre}members_search AS mes1 WHERE   (mes1.province='{$province}') and mes1.is_lock=1  $age_between and images_ischeck=1 and usertype=1 LIMIT {$rand},1";
			//}
		}
		
		if($fgender == '1'){
			//if($usertype=='1'){
				//$sql = "SELECT mes1.uid as uid FROM {$dbTablePre}membersfastsort_women AS mes1 WHERE   (mes1.province='{$province}') and mes1.is_lock=1  $age_between and images_ischeck=1 LIMIT {$rand},1";
			//}else{				
				$sql = "SELECT mes1.uid as uid FROM {$dbTablePre}members_search AS mes1 WHERE   (mes1.province='{$province}') and mes1.is_lock=1  $age_between and images_ischeck=1 and usertype=1 LIMIT {$rand},1";
			//}
		}

		$arrUser = $_MooClass['MooMySQL']->getOne($sql);//mes1.uid >= mes2.uid		
    }else{
		if($fgender == '0'){
			//if($usertype=='1'){
				//$sql = "SELECT mes1.uid as uid FROM {$dbTablePre}membersfastsort_man AS mes1 WHERE   mes1.is_lock=1  $age_between and images_ischeck=1 LIMIT {$rand},1";
			//}else{				
				$sql = "SELECT mes1.uid as uid FROM {$dbTablePre}members_search AS mes1 WHERE   mes1.is_lock=1  $age_between and images_ischeck=1 and usertype=1 LIMIT {$rand},1";
			//}
		}
		
		if($fgender == '1'){
			//if($usertype=='1'){
				//$sql = "SELECT mes1.uid as uid FROM {$dbTablePre}membersfastsort_women AS mes1 WHERE   mes1.is_lock=1  $age_between and images_ischeck=1 LIMIT {$rand},1";
			//}else{				
				$sql = "SELECT mes1.uid as uid FROM {$dbTablePre}members_search AS mes1 WHERE   mes1.is_lock=1  $age_between and images_ischeck=1 and usertype=1 LIMIT {$rand},1";
			//}
		}		
		$arrUser = $_MooClass['MooMySQL']->getOne($sql);//mes1.uid >= mes2.uid
		
	}*/
	
	$cl = searchApi('members_man members_women');
	if($user['num'] > 60){
		if($fgender == '0' || $fgender == '1'){
			$cond[] = array('gender',$fgender,false);
			$cond[] = array('is_lock','1',false);
			$cond[] = array('province',$province,false);
			$cond[] = array('birthyear',array($age1,$age2),false);
			$cond[] = array('images_ischeck','1',false);
			$cond[] = array('showinformation','1',false);
			if(!empty($current_user) && $current_user ['s_cid'] < 40){
				$cond[] = array('usertype','1',false);
			}else{
				$cond[] = array('usertype','3',false);
			}
			$sort = 'city desc';
			$rs_total = $cl -> getResult($cond,array(),$sort);
			if($rs_total['total_found'] >= 1) $rand = mt_rand(1,$rs_total['total_found']);
			$limit = array($rand,1);
			$rs = $cl -> getResult($cond,$limit,$sort);
		}
	}else{
		if($fgender == '0' || $fgender == '1'){
			$cond[] = array('gender',$fgender,false);
			$cond[] = array('is_lock','1',false);
			//$cond[] = array('province',$province,false);
			$cond[] = array('birthyear',array($age1,$age2),false);
			$cond[] = array('images_ischeck','1',false);
			$cond[] = array('showinformation','1',false);
			if(!empty($current_user) && $current_user ['s_cid'] < 40){
				$cond[] = array('usertype','1',false);
			}else{
				$cond[] = array('usertype','3',false);
			}
			$sort = 'province desc,city desc';
			$rs_total = $cl -> getResult($cond,array(),$sort);
			if($rs_total['total_found'] >= 1) $rand = mt_rand(1,$rs_total['total_found']);
			$limit = array($rand,1);
			$rs = $cl -> getResult($cond,$limit,$sort);
		}
	}
	
	if(is_array($rs) && isset($rs['matches']) && !empty($rs['matches'])){
		$ids = $cl -> getIds();
		//print_r($ids);
		if(!empty($ids)) $arrUser['uid'] = $ids[0];
		//$arrUser = $_MooClass ['MooMySQL']->getOne($sql,true);
	}

	if($arrUser && !MooGetScreen($arrUser['uid'],$uid)){
		$nowtime=time();
		
	   
		if(MOOPHP_ALLOW_FASTDB){			
			$user = MooFastdbGet('members_search','uid',$arrUser['uid']);
			$time=time()-600;
			if($user['lastvisit']<$time){
				$_MooClass['MooMySQL']->query("UPDATE `{$dbTablePre}members_login` SET `lastvisit`=".$nowtime." WHERE `uid`=".$arrUser['uid']);
			}
			$val = array();
			$val['lastvisit'] = $nowtime;
			MooFastdbUpdate('members_login','uid',$arrUser['uid'], $val);//!!
		}else{
			$_MooClass['MooMySQL']->query("UPDATE `{$dbTablePre}members_login` SET `lastvisit`=".$nowtime." WHERE `uid`=".$arrUser['uid']);
		}
		$result=$_MooClass['MooMySQL']->getOne("SELECT `vid` FROM `{$dbTablePre}service_visitor` WHERE uid='{$arrUser['uid']}' AND visitorid='{$uid}' AND `who_del`!=2");
		if($result['vid']){
			$_MooClass['MooMySQL']->query("UPDATE `{$dbTablePre}service_visitor` SET `visitortime`='{$nowtime}' WHERE `vid`={$result['vid']}");
		}else{
			$_MooClass['MooMySQL']->query("INSERT INTO `{$dbTablePre}service_visitor` SET `uid`='{$arrUser['uid']}',`visitorid`='{$GLOBALS['MooUid']}',`visitortime`='{$nowtime}',`who_del`=1");
		}
		
	}

/*	return 	$arrUser?$arrUser:0;*/
}


/***************************************控制层****************************************/
include "./module/{$name}/function.php";


//note 初始化用户id
MooUserInfo();
$userid = $GLOBALS['MooUid'];
//$user = $_MooClass['MooMySQL']->getOne("SELECT mthumbimg FROM {$dbTablePre}members WHERE uid = '$userid'");
if(!$userid) {echo 'no_login';exit;}

$h=MooGetGPC('h','string');
switch ($h) {
	//note 显示
	case "showmsg" :
		public_showmsg($userid);
		break;
	
	//note 仿造XX访问当前会员主页
	case "makevisitor" :
		make_visitor();
		break;
	case "update_read":
		update_read();
		break;
	//note 
	default :
		public_showmsg($userid);
		break;
}

?>