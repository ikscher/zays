<?php
/**
*	客服后台的AJAX调用
*
*/


define('FROMEWORK', true);
//ini_set('error_log','error_log.log');
//error_log('ajax.php---------------');
date_default_timezone_set('Asia/Shanghai');
//note 加载框架配置参数
require './include/config.php';

//note 加载MooPHP框架
require '../framwork/MooPHP.php';

//note 包含共公方法
include './include/function.php';

include './include/ajax_function.php';



//允许的IP访问后台
require 'include/allow_ip.php';
$cur_ip = GetIP();
if(!empty($allow_ip)){
	if(!in_array($cur_ip,$allow_ip)){   
		$token=isset($_MooCookie['token'])?$_MooCookie['token']:'';
		if(empty($token) || $token!='vip999'){
			$t=isset($_GET['token'])?$_GET['token']:'';
			MooSetCookie('token',$t,21600);
		}
		
		$token=isset($_MooCookie['token'])?$_MooCookie['token']:'';
	   
		
		if(empty($token) || $token!='vip999'){
			echo '你当前的ip:  '.$cur_ip;
			exit;
		}
	}
}


//note 删除指定管理员
function del_admin_user(){
	$uid = MooGetGPC('uid','integer','P');
	if(del_record2('admin_user','uid',$uid)){
		echo 'ok';exit;
	}
	echo 'false';exit;
}

//note 删除组
function del_group(){
	$groupid = MooGetGPC('groupid','integer','P');
	if(del_record2('admin_group','groupid',$groupid)){
		echo 'ok';exit;
	}
	echo 'false';exit;
}

//note 删除所有操作中的操作
function del_action(){
	$id = MooGetGPC('actionid','integer','P');
	if(del_record2('admin_action','id',$id)){
		echo 'ok';exit;
	}
	echo 'false';exit;
}

//note 删除组成员
function remove_group(){
	$uid = MooGetGPC('uid','integer','P');
	$groupid = MooGetGPC('groupid','integer','P');
	$sql = "SELECT manage_user_list FROM {$GLOBALS['dbTablePre']}admin_group WHERE groupid='{$groupid}'";
	$uid_list = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$uid_list = explode(',',$uid_list[manage_user_list]);
	$key = array_search($uid,$uid_list);
	unset($uid_list[$key]);
	$suid = implode(',',$uid_list);
	$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_group SET manage_user_list='{$suid}' WHERE groupid='{$groupid}'";
	$query = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	if($query){
		echo 'ok';
	}
}

//note 组添加会员,搜索客服
function search_kefu(){
	$keyword = MooGetGPC('keyword','string','G');
	$general_service = implode(',',$GLOBALS['general_service']);
	$sql = "SELECT uid,usercode,username,name FROM {$GLOBALS['dbTablePre']}admin_user WHERE 
			(usercode like '%{$keyword}%' OR username LIKE '%{$keyword}%' OR name LIKE '%{$keyword}%') ORDER BY uid ASC";
	$user_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);

	echo json_encode($user_list);
}

//note 组添加会员，判断会员是否能被添加(已在其它组中不能被添加)
function judge_member(){
	$uid = MooGetGPC('uid','string','G');
	$sql = "SELECT manage_list FROM {$GLOBALS['dbTablePre']}admin_manage where type=1";
	$ret = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$user_list = '';
	$commat = '';
	foreach( $ret as $v){
		$user_list .= $commat . $v['manage_list'];
		$commat = ',';
	}
	$user_list_arr = explode(',', $user_list);
	
	$user_code_arr=array();
	foreach($user_list_arr as $v){
	    $sql = "SELECT usercode FROM {$GLOBALS['dbTablePre']}admin_user where uid='{$v}'";
	    $ret_ = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$user_code_arr[]=$ret_['usercode'];
		
	}

	if( in_array($uid , $user_code_arr)){
		echo 1;
	}else{
		echo 0;
	}
}

//note 队添加成员，搜索组
function search_group(){
	$keyword = MooGetGPC('keyword','string','G');
	$sql = "SELECT id,manage_name FROM {$GLOBALS['dbTablePre']}admin_manage WHERE 
			(id like '%{$keyword}%' OR manage_name LIKE '%{$keyword}%') AND type=1 ORDER BY id ASC";
	$group_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	echo json_encode($group_list);
}

//note 队添加组，判断此组是否已在其它队中
function judge_group(){
	$id = MooGetGPC('id','integer','G');
	$sql = "SELECT group_concat(manage_list) as manage_list FROM {$GLOBALS['dbTablePre']}admin_manage WHERE type=2";
	$result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$manage_groupid_arr = explode(',',$result['manage_list']);

	if(in_array($id,$manage_groupid_arr)){
		echo 1;exit;
	}
	echo 0;exit;
}

//成功故事推荐
function ajax_storyrecommand(){
	global $_MooClass,$dbTablePre;
    $sid = MooGetGPC('sid','string','G');
	if($sid == ""){
		return "推荐失败";
	}
	if($_MooClass['MooMySQL']->query("update {$dbTablePre}story set recommand = 1 WHERE sid='$sid'")){
		exit("推荐成功");
	}
}

//  锁定会员
function ajax_lockuser(){
        global $_MooClass,$dbTablePre;
        $ruid=MooGetGPC('ruid','integer');
        $uid=MooGetGPC('uid','integer');
        if($ruid==0){echo "errors";exit();}
        if($_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}members_search set is_lock='0' WHERE uid='$ruid'")){
			searchApi("members_man members_women") -> updateAttr(array('is_lock'),array($ruid=>array(0)));
        	if(MOOPHP_ALLOW_FASTDB){
				$value['is_lock']=0;
				MooFastdbUpdate('members_search','uid',$ruid,$value);
			}
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}services(s_cid,s_uid,s_fromid,s_title,s_content,s_time) VALUES('3','$uid','$GLOBALS[MooSid]','投诉回复','你好，你投诉会员$ruid的事我们已经受理:已经$ruid将封锁！','$t')");
			echo "ok";
		};
        serverlog(3,$dbTablePre."members_search","{$GLOBALS['username']}封锁用户{$uid}",$GLOBALS['adminid'],$uid);
		exit();
}

/*
*       受理投诉
*/
function ajax_dealuser(){
        global $_MooClass,$dbTablePre;
        $dealuid=MooGetGPC('dealuid','integer');
        $senduid=MooGetGPC('senduid','integer');
        $id=MooGetGPC('id','integer');
        $type=MooGetGPC('type','integer');
        $content=MooGetGPC('content','string');
        if($dealuid==''||$senduid==''||$id==''||$type==''||$content==''){echo 'errors';exit;}
        $t = time();
        if($type==4){
                $_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}members_search SET is_lock='0'  WHERE uid='$dealuid'");
                searchApi("members_man members_women") -> updateAttr(array('is_lock'),array($dealuid=>array(0)));
				if(MOOPHP_ALLOW_FASTDB){
					$value['is_lock']=0;
					MooFastdbUpdate('members_search','uid',$dealuid,$value);
				}      
		        
        }elseif ($type!=4&&$type!=1){
                $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}services(s_cid,s_uid,s_fromid,s_title,s_content,s_time) VALUES('3','$dealuid','{$senduid}','会员投诉','尊敬的红娘用户，您已被会员投诉，我们非常期望红娘会员之间相互尊重,您的一言一行将可能影响您在其他会员中的印象，谢谢您的合作。','$t')");
        }
        $results=$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}services (s_cid,s_uid,s_fromid,s_title,s_content,s_time) VALUES('3','$senduid','','投诉回复','$content','$t')");
        $_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}report SET is_disabled='1' WHERE id='$id'");
        if($results){echo 'ok';exit;}else{echo 'errors';exit;}
}

function ajax_report(){
        global $_MooClass,$dbTablePre;
        $id=MooGetGPC('id','integer');
        $uid=MooGetGPC('uid','integer');
        $ruid=MooGetGPC('ruid','integer');
		$t = time();
        if($id==0){echo "errors";exit();}
        if($_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}report SET is_disabled='1' WHERE id='$id'")){
		$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}services (s_cid,s_uid,s_fromid,s_title,s_content,s_time) VALUES('3','$uid','$GLOBALS[MooSid]','投诉回复','尊敬的红娘会员你好，你投诉会员{$uid}的事我们已经受理','$t')");
		echo "ok";
		};
        serverlog(3,$dbTablePre."report","{$GLOBALS['username']}受理投诉{$uid}",$GLOBALS['adminid'],$uid);
		exit();
}

//修改站内电子邮件的内容
function ajax_change_email(){
	global $_MooClass,$dbTablePre;
    $s_id = MooGetGPC('s_id','integer','P');
	$title = MooGetGPC('title','string','P');
    $cont = MooGetGPC('cont','string','P');
	if($s_id == 0 || $cont =='' || $title == ''){
		echo "修改失败";exit;
	}
	if($_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}services SET s_title='{$title}',s_content='{$cont}' WHERE s_id='$s_id'")){
		exit("修改成功");
	}
}



//修改内心独白
function ajax_check_introduce(){
	  global $dbTablePre;
      $ajax = MooGetGPC('ajax','integer','P');
      $pass = MooGetGPC('pass','integer','P');
      $introduce=MooGetGPC('introduce','string','P');
      $uid=MooGetGPC('uid','integer','P');
	  $sid=MooGetGPC('sid','integer','P');
      
	
	 
	 if(in_array($GLOBALS['groupid'],array('67','70')) && $sid==1) {
	     echo -1;exit;
	  }
			
      //审核内心独白
	  if($ajax == 1){
			if($pass == 1){ //审核通过
				$sql="SELECT uid FROM {$GLOBALS['dbTablePre']}members_introduce where uid='{$uid}' and introduce_pass=1 and introduce!=''";
				$pass_have=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
				if(isset($pass_have['uid'])) {echo $pass;exit;} //已经审核通过的直接退出
				$sql = "UPDATE {$GLOBALS['dbTablePre']}members_introduce SET `introduce`=`introduce_check`,`introduce_check`='',`introduce_pass`='1' WHERE `uid`='$uid'";
				$GLOBALS['_MooClass']['MooMySQL']->query($sql);
				serverlog(3,$dbTablePre."members_introduce",$GLOBALS['username']."审核会员".$uid."的内心独白为通过",$GLOBALS['adminid'],$uid);
				if(MOOPHP_ALLOW_FASTDB){
					$old_introduce=MooFastdbGet("members_introduce",'uid',$uid);
					$value['introduce'] = $old_introduce['introduce_check'];
					$value['introduce_check']='';
					$value['introduce_pass']=1;
					MooFastdbUpdate("members_introduce",'uid',$uid,$value);
				}
			}else{ //审核不通过
				$sql = "UPDATE {$GLOBALS['dbTablePre']}members_introduce SET `introduce_check`=`introduce`,`introduce`='',`introduce_pass`='0' WHERE `uid`='$uid'";
				$GLOBALS['_MooClass']['MooMySQL']->query($sql);
				serverlog(3,$dbTablePre."members_introduce",$GLOBALS['username']."审核会员".$uid."的内心独白为不通过",$GLOBALS['adminid'],$uid);
				if(MOOPHP_ALLOW_FASTDB){                    
					$value['introduce'] = '';
					$value['introduce_check']='';
					$value['introduce_pass']=0;
					MooFastdbUpdate("members_introduce",'uid',$uid,$value);
				}
			}                               
			echo $pass;
			exit;
		}
		
		
        //修改内心独白
		if($ajax==2){ 
			/* $updatecarr = array();
			$updatecarr['introduce_check'] = $introduce;
						
			$where_arr=array('uid'=>$uid);
			updatetable('members_introduce',$updatecarr,$where_arr);
			
			//更新缓存
			if(MOOPHP_ALLOW_FASTDB){
				$value['introduce_check'] = $introduce;
				MooFastdbUpdate("members_introduce",'uid',$uid,$value);
			}
			echo 'ok';exit; */
		
	    
			//修改内心独白并提交
			$updatecarr = array();
			$updatecarr['introduce'] = $introduce;
			$updatecarr['introduce_check'] = '';
			$updatecarr['introduce_pass'] = '1';
						
			$where_arr=array('uid'=>$uid);
			updatetable('members_introduce',$updatecarr,$where_arr);
			
			//更新缓存
			if(MOOPHP_ALLOW_FASTDB){
				$value['introduce'] = $introduce;
				$value['introduce_check'] = '';
				$value['introduce_pass'] = '1';
				MooFastdbUpdate("members_introduce",'uid',$uid,$value);
			}
			
			echo 1;exit;
		
		}
		
		//提醒
		if($introduce!='' || $pass == 1){
			sendusermessage($uid,"尊敬的红娘会员，您的内心独白已经过红娘的审核","内心独白审核");
		}else {
			sendusermessage($uid,"尊敬的红娘会员，您的内心独白不符合要求，请按要求填写","内心独白审核");
		}
		
        serverlog(3,$dbTablePre."members_introduce",$GLOBALS['username']."审核会员".$uid."的内心独白",$GLOBALS['adminid'],$uid);
      
}


//审核电子邮件  通过或否
function ajax_agreeemail(){
	global $_MooClass,$dbTablePre;
    $s_id = MooGetGPC('s_id','integer','G');
    $agree = MooGetGPC('agree','string','G');
    $s_fromid = MooGetGPC('s_fromid','string','G');
    $s_uid = MooGetGPC('s_uid','string','G');
	if($s_id == 0 || $agree ==''){
		return "审核失败";
	}
	if($_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}services SET flag='{$agree}' WHERE s_id='$s_id'")){
		if($agree == '1'){
			$tel = $_MooClass['MooMySQL']->getOne("SELECT telphone from {$dbTablePre}members_search WHERE uid='$s_uid' LIMIT 1");
			//fangin暂时屏蔽
			Push_message_intab($s_uid,$tel['telphone'],"邮件", "尊敬的会员您好！您有一封新邮件，请登录 www.zhenaiyisheng.cc真爱一生网读取。如需咨询请致电4008787920","system");
			serverlog(4,$GLOBALS['dbTablePre'].'services',"{$GLOBALS['adminid']}号客服审核通过{$s_fromid}发给{$s_uid}的站内信",$GLOBALS['adminid'],$s_fromid);
			echo '审核通过';

		}elseif($agree == '2'){
			$con = $_MooClass['MooMySQL']->getOne("SELECT s_title,s_content FROM {$dbTablePre}services  WHERE s_id='$s_id' LIMIT 1");
			$t = time();
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}services(s_cid,s_uid,s_title,s_content,s_time,is_server) VALUES('3','{$s_fromid}','红娘来信','尊敬的会员，系统检测出您的邮件（标题：".$con['s_title']."。正文：".$con['s_content']."）内含有非法字符，建议您更改后重新发送，如需咨询请致电红娘网4008787920','{$t}','1') ");
			serverlog(4,$GLOBALS['dbTablePre'].'services',"{$GLOBALS['adminid']}号客服审核不通过{$s_fromid}发给{$s_uid}的站内信",$GLOBALS['adminid'],$s_fromid);
			echo '审核未通过';
		}
		//exit("已审核");
	}
}

//审核毕业院校
function ajax_check_school(){
	global $_MooClass,$dbTablePre;
	$uid = MooGetGPC('uid','integer','P');
	$pass = MooGetGPC('pass','integer','P');
	if(empty($uid)){
		exit('0');
	}
	$school = trim(MooGetGPC('school','string','P'));
	if(!empty($school)){
		$sql = "UPDATE {$dbTablePre}members_base SET finishschool='{$school}' WHERE uid='{$uid}'";
		$value['finishschool']=$school;
	}else{
		$sql = "UPDATE {$dbTablePre}members_base SET isschool='{$pass}' WHERE uid='{$uid}'";
		$value['isschool']=$pass;
	}
	//echo $sql;exit;
	$query = $_MooClass['MooMySQL']->query($sql);
	if(MOOPHP_ALLOW_FASTDB){
		MooFastdbUpdate('members_base','uid',$uid,$value);
	}
	if($query){
	    serverlog(4,$GLOBALS['dbTablePre'].'members_base',"{$GLOBALS['adminid']}号客服审核会员{$uid}的毕业院校",$GLOBALS['adminid'],$uid);
			
		echo '1';	
	}else{
		echo '0';
	}
}

/**
* 输出所有备注,后台右下角提醒
* @author:fanglin
* 可对组，所有客服，某个id客服提醒
*/
function ajax_remark(){
    $result='';
	$sid=MooGetGPC('sid','integer','G');
	$type=MooGetGPC('type','string','G');
	$time = time();
	$myservice_idlist = get_myservice_idlist();
	if($type == 'timeover'){	//最近备注
		if(empty($myservice_idlist) || $myservice_idlist===$GLOBALS['adminid']){
		   $sql = "SELECT id,sid,groupid,title,content,status,awoketime,dateline,dealstate,send_id,flag FROM {$GLOBALS['dbTablePre']}admin_remark WHERE
				`status`='0' AND `awoketime`>='$time' AND (`sid`='{$GLOBALS['adminid']}')  ORDER BY `awoketime` ASC limit 30";// OR groupid='{$GLOBALS['groupid']}' OR groupid='-1')
		}elseif($myservice_idlist=='all'){
			$sql = "SELECT id,sid,groupid,title,content,status,awoketime,dateline,dealstate,send_id,flag FROM {$GLOBALS['dbTablePre']}admin_remark WHERE
				`status`='0' AND `awoketime`>='$time' AND (`sid`='{$GLOBALS['adminid']}')  ORDER BY `awoketime` ASC limit 30";// OR groupid='{$GLOBALS['groupid']}' OR groupid='-1') 
		}else{
			if(!in_array($GLOBALS['groupid'],$GLOBALS['admin_userinfo_check']) && !in_array($GLOBALS['groupid'],$GLOBALS['admin_service_team'])){
			   $sql = "SELECT id,sid,groupid,title,content,status,awoketime,dateline,dealstate,send_id,flag FROM {$GLOBALS['dbTablePre']}admin_remark WHERE
				`status`='0' AND `awoketime`>='$time' AND  (`sid` in ({$myservice_idlist}))  ORDER BY `awoketime` ASC limit 30";//OR groupid='{$GLOBALS['groupid']}' OR groupid='-1') 
		    }else{
		    	$sql = "SELECT id,sid,groupid,title,content,status,awoketime,dateline,dealstate,send_id,flag FROM {$GLOBALS['dbTablePre']}admin_remark WHERE
				`status`='0' AND `awoketime`>='$time' AND (`sid`='{$GLOBALS['adminid']}')   ORDER BY `awoketime` ASC limit 30";//OR groupid='{$GLOBALS['groupid']}' OR groupid='-1') 
		    }
		}
		$remark=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	}elseif($type == 'notime'){	//备注
		if(empty($myservice_idlist)){
		   $sql = "SELECT id,sid,groupid,title,content,status,awoketime,dateline,dealstate,send_id,flag FROM {$GLOBALS['dbTablePre']}admin_remark WHERE 
				 `status`='0' AND `awoketime`='' AND flag>0  and  (`sid`='{$GLOBALS['adminid']}') ORDER BY id desc limit 10";//OR groupid='{$GLOBALS['groupid']}' OR groupid='-1'
		}elseif($myservice_idlist=='all'){
			 $sql = "SELECT id,sid,groupid,title,content,status,awoketime,dateline,dealstate,send_id,flag FROM {$GLOBALS['dbTablePre']}admin_remark WHERE 
				 `status`='0' AND `awoketime`='' AND flag>0  and  (`sid`='{$GLOBALS['adminid']}') 	ORDER BY id desc limit 10"; //OR groupid='{$GLOBALS['groupid']}' OR groupid='-1'
		}else{
			if(!in_array($GLOBALS['groupid'],$GLOBALS['admin_userinfo_check']) && !in_array($GLOBALS['groupid'],$GLOBALS['admin_service_team'])){
			   $sql = "SELECT id,sid,groupid,title,content,status,awoketime,dateline,dealstate,send_id,flag FROM {$GLOBALS['dbTablePre']}admin_remark WHERE 
				`status`='0' AND  `awoketime`='' AND flag>0   and (`sid` in ({$myservice_idlist})) ORDER BY id desc limit 10";// OR groupid='{$GLOBALS['groupid']}' OR groupid='-1') 
			}else{
				 $sql = "SELECT id,sid,groupid,title,content,status,awoketime,dateline,dealstate,send_id,flag FROM {$GLOBALS['dbTablePre']}admin_remark WHERE 
				 `status`='0' AND `awoketime`='' AND flag>0  and  (`sid`='{$GLOBALS['adminid']}') ORDER BY id desc limit 10";//OR groupid='{$GLOBALS['groupid']}' OR groupid='-1') 
				
			}
		}
		$remark=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	}elseif($type == 'all'){ //所有备注
		$sql = "SELECT id,sid,groupid,title,content,status,awoketime,dateline,dealstate,send_id,flag FROM {$GLOBALS['dbTablePre']}admin_remark WHERE 
				(`sid`='{$GLOBALS['adminid']}') AND status=0 ORDER BY id desc limit 50";// OR groupid='{$GLOBALS['groupid']}' OR groupid='-1'
		$remark=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	}
	
	$six_minute = time()-300;
	$sql = "select s_fromid from {$GLOBALS['dbTablePre']}service_chat where s_uid='{$GLOBALS['adminid']}' and s_status=0 and s_time>'$six_minute' GROUP BY s_fromid";
	$chatmsg = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    
	//=============回电总数单独统计====================
	if(empty($myservice_idlist) || $myservice_idlist===$GLOBALS['adminid']){
	    $sql = "SELECT id,sid,groupid,title,content,status,awoketime,dateline,dealstate,send_id,flag FROM {$GLOBALS['dbTablePre']}admin_remark WHERE
					(`sid`='{$GLOBALS['adminid']}') AND `status`='0' AND `awoketime`>='$time' limit 10";//  OR groupid='{$GLOBALS['groupid']}' OR groupid='-1' ORDER BY `awoketime` ASC";
	}elseif($myservice_idlist=='all'){
		$sql = "SELECT id,sid,groupid,title,content,status,awoketime,dateline,dealstate,send_id,flag FROM {$GLOBALS['dbTablePre']}admin_remark WHERE (`sid`='{$GLOBALS['adminid']}') AND `status`='0' AND `awoketime`>='$time' limit 10";// OR groupid='{$GLOBALS['groupid']}' OR groupid='-1' ORDER BY `awoketime` ASC";
	}else{
		if(!in_array($GLOBALS['groupid'],$GLOBALS['admin_userinfo_check']) && !in_array($GLOBALS['groupid'],$GLOBALS['admin_service_team'])){
		   $sql = "SELECT id,sid,groupid,title,content,status,awoketime,dateline,dealstate,send_id,flag FROM {$GLOBALS['dbTablePre']}admin_remark WHERE
					`status`='0' AND `awoketime`>='$time' and (`sid` in ({$myservice_idlist})) limit 10";// OR groupid='{$GLOBALS['groupid']}' OR groupid='-1'  ORDER BY `awoketime` ASC";
		}else{
			$sql = "SELECT id,sid,groupid,title,content,status,awoketime,dateline,dealstate,send_id,flag FROM {$GLOBALS['dbTablePre']}admin_remark WHERE
					(`sid`='{$GLOBALS['adminid']}')  AND `status`='0' AND `awoketime`>='$time' limit 10";// OR groupid='{$GLOBALS['groupid']}' OR groupid='-1'
		}
		
	}
	
    $remark_timeover=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    
    if(empty($myservice_idlist) || $myservice_idlist===$GLOBALS['adminid']){
	    $sql = "SELECT id FROM {$GLOBALS['dbTablePre']}admin_remark WHERE (`sid`='{$GLOBALS['adminid']}') and flag>0  AND  `status`='0' AND `awoketime`='' 	ORDER BY id desc limit 10";// OR groupid='{$GLOBALS['groupid']}' OR groupid='-1'
    }elseif($myservice_idlist=='all'){
		 $sql = "SELECT id FROM {$GLOBALS['dbTablePre']}admin_remark WHERE (`sid`='{$GLOBALS['adminid']}') and flag>0 AND `status`='0' AND `awoketime`='' ORDER BY id desc limit 10";//OR groupid='{$GLOBALS['groupid']}' OR groupid='-1'
	}else{
		if(!in_array($GLOBALS['groupid'],$GLOBALS['admin_userinfo_check']) && !in_array($GLOBALS['groupid'],$GLOBALS['admin_service_team'])){
		   $sql = "SELECT id FROM {$GLOBALS['dbTablePre']}admin_remark WHERE  `status`='0' AND `awoketime`=''  and flag>0 AND 
				(`sid` in ({$myservice_idlist})) ORDER BY id desc limit 10"; // OR groupid='{$GLOBALS['groupid']}' OR groupid='-1'
		}else{
			 $sql = "SELECT id FROM {$GLOBALS['dbTablePre']}admin_remark WHERE (`sid`='{$GLOBALS['adminid']}') and flag>0 AND `status`='0' AND `awoketime`='' 	ORDER BY id desc limit 10";// OR groupid='{$GLOBALS['groupid']}' OR groupid='-1'
		}
	}
	
    $remark_notime=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	if(count($remark_timeover)==0 && empty($chatmsg) && count($remark_notime)==0){
		echo 0;
		exit;
	}
	
	if(!empty($chatmsg)){
		$result .= "<ul class=\"bgon\">";
		foreach($chatmsg as $msg){
			$result .= "<li><a href='index.php?n=chat&chatorid=".$msg['s_fromid']."' target='_brank' onclick=\"javascript:$(this).empty()\">客服ID:".$msg['s_fromid']."发给你在线消息</a></li>";
		}
		$result .= "</ul>";
	}
	
	if(empty($remark)){
		$result .= "<p>您有<a href='#'>0条备注</a></p>";
	}else{
		$result .= "<ul class=\"remark\">";
		foreach($remark as $key=>$arr){
			if($arr['status']==0) 
				$result.="<li class=\"title state-{$arr['id']}\"  onclick=\"showcontent(".$key.");\"><a href=\"javascript:deal(".$arr['id'].")\" id=\"state-".$arr['id']."\" style=\"float:right;\">未解决</a>"; 
			else 
				$result.="<li class=\"title\" onclick=\"showcontent(".$key.");\"><a href=\"javascript:aworke()\" style=\"float:right;\">已解决</a>";
			if($arr['title']=='会员付款') 
				$result.=($key+1).".<span style='color:#ff0000;font-weight:bold;'>".$arr['sid'].":".MooCutstr($arr['title'],20,"…")."</span></li>";
			else 
				$result.=($key+1).".".$arr['sid'].":".MooCutstr($arr['title'],20,"…")."</li>";
				
			$result.="<li class=\"content state-{$arr['id']}\"  id=\"content-".$key."\">".$arr['content']."</li>";
		}
		$result .= "</ul>";
   }
   
   
   
   /*if($type!='notime'){
   	 $count=count($remark);
   }elseif($type=='notime'){
   	 $count=0;
   }*/
   $result .= "|最近备注(".count($remark_timeover).")";

   $result .= "|备注回电(".count($remark_notime).")";
    /*if ($type=='timeover'){
	    $result .= "|最近备注(".count($remark).")";
    }elseif ($type=='notime'){
    	$result .= "|备注回电(".count($remark).")";
    }*/
	echo $result;
	exit;
}

/*
*	改变后台右下角 备注状态
*/
function ajax_change_remark_status(){
	$id=MooGetGPC('id','integer','G');
	$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_remark SET `status`='1' WHERE `id`='$id'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	serverlog(3,"{$GLOBALS['dbTablePre']}admin_remark","{$GLOBALS['username']}修改备注编号{$id}为已解决",$GLOBALS['adminid']);
	echo 'ok';
}

//检查约会
function ajax_check_dating(){
	$uid = MooGetGPC('uid','integer','P');
	$did = MooGetGPC('did','integer','P');
	$pass = MooGetGPC('pass','interger','P');
	if(!empty($did) && !empty($pass)){
		//$pass == 1 ? $flag=2 : $flag=3;
		$sql = "UPDATE {$GLOBALS['dbTablePre']}dating SET flag='{$pass}' WHERE did='{$did}'";
		$query = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
		if($query){
			echo $pass;
		}
	}else{
		echo 0;
	}
}
//检查约会响应
function ajax_check_redating(){
	$uid = MooGetGPC('uid','integer','P');
	$rid = MooGetGPC('rid','integer','P');
	$pass = MooGetGPC('pass','integer','P');
	if(!empty($rid) && !empty($pass)){
		//$d == 'ok' ? $flag=2 : $flag=3;
		$sql = "UPDATE {$GLOBALS['dbTablePre']}dating_respond SET flag='{$pass}' WHERE rid='$rid'";
		$query = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
		if($query){
			echo $pass;	
		}
	}else{
		echo 0;
	}
}
//上传视频
function ajax_add_video() {
	$attachment = $_FILES['Filedata'];
	$uid = MooGetGPC('uid', 'integer', 'G');
	$filename = $attachment['name'];
	$fileext = substr(strrchr($filename,'.'),1);

	$path = '../data/videotemps/'.date("Ym");
	$path_convert = '../data/videos/'.date("Ym");
	if(!is_dir($path)){
		mkdir($path,0777,true);
		touch($path.'/index.html');
	}

	// 生成文件名
	$filename2 = time() . '_' . rand(10000, 99999) . '.' . $fileext;
	$file = $path . '/' . $filename2;
	while(file_exists($file)){
		$file = $path . '/' . $filename2;
	}

	// 移动文件
	$is_ok = false;

	if (!move_uploaded_file($attachment['tmp_name'], $file)) {
		if (copy($attachment['tmp_name'], $file)) {
			$is_ok = true;
		} else {
			$is_ok = false;
		}
	} else {
		$is_ok = true;
	}

	if ($is_ok) {
		$sql = "insert into {$GLOBALS['dbTablePre']}video set uid='$uid',filepath='$path_convert',filename='$filename2',pic='2'";

		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		$vid = $GLOBALS['_MooClass']['MooMySQL']->insertId();
		if($vid){
			$r_filename = $path . '/' .(md5($vid . MOOPHP_AUTHKEY)) . '.' . $fileext;
			if (file_exists($r_filename)) {
				unlink($r_filename);
			}
			if (rename($file, $r_filename)) {
				echo 'ok|'. $file.'|'.$vid;
				exit;
			} else {
				echo 'fail';
			}
		}
	} else {
		echo 'fail';
		exit;
	}
}


//成功升级会员祝贺提醒
function ajax_congratulate_remark(){
	$sql = "SELECT sid,uid FROM {$GLOBALS['dbTablePre']}congratulate_remark WHERE online_sid='{$GLOBALS['adminid']}' AND status=0";
//	error_log($sql);
	$remark = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if(!empty($remark)){
//		$sql = "SELECT sid FROM {$GLOBALS['dbTablePre']}members WHERE uid='{$remark['uid']}'";
//		$members = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		echo $remark['sid'];exit;
	}
	echo 'no';
}

//取消升级会员祝贺提醒
function congratulate_remark_hidden(){
	$sql = "UPDATE {$GLOBALS['dbTablePre']}congratulate_remark SET status=1 WHERE online_sid='{$GLOBALS['adminid']}'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	echo 'ok';exit;
}


//客服三分钟没操作后台提醒
function service_nooperation_remark(){
	$adminid = MooGetGPC('adminid','integer','G');
	$sec = MooGetGPC('sec','integer','G');//没操作秒数
	
	//查此客服所属组成员
	$sql = "SELECT manage_list FROM {$GLOBALS['dbTablePre']}admin_manage WHERE type=1 AND find_in_set({$adminid},manage_list) LIMIT 1";
	$manage_list = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	
	if(!empty($manage_list)){
		//查此组成员中的组长
		$group_leader = implode(',',$GLOBALS['admin_service_group']);
		$sql = "SELECT uid,groupid FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid IN({$manage_list['manage_list']}) AND groupid IN({$group_leader})";
		$admin_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		if(!empty($admin_user)){
			$minutes = ceil($sec/60);
			$time = time();
			$awoketime = $time+120;
			$sql = "INSERT INTO `{$GLOBALS['dbTablePre']}admin_remark` 
					SET sid='{$admin_user['uid']}',
					title='{$adminid}号客服{$minutes}分钟没有操作后台',
					content='{$adminid}号客服已有{$minutes}分钟没有操作后台',
					awoketime='{$awoketime}',
					dateline='{$time}'";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			echo 'ok';
		}else{
			echo '没有组长';exit;
		}
	}else{
		echo '没有组成员';exit;
	}
}

//note 通话信息
function ajax_callinfo(){
        global $_MooClass,$dbTablePre;
        $uid=MooGetGPC('uid','integer');
        $return="";
        $user=$_MooClass['MooMySQL']->getOne("SELECT `telphone` FROM ". $dbTablePre ."members_search WHERE `uid`=".$uid);

		if($user['telphone']){
			//$result=$_MooClass['MooMySQL']->getOne("SELECT count(1) as num FROM ". $dbTablePre ."members WHERE is_lock=1 and `telphone`=".$user['telphone']);
			//$num=$result['num'];
			
			$num = '1'; //note 上面语句影响速度，暂时赋值为1
			
			$tele=$user['telphone']."(".$num.")";
		}else{$tele=$user['telphone'];}
        $usercause=$_MooClass['MooMySQL']->getOne("SELECT * FROM ". $dbTablePre ."member_backinfo WHERE `uid`=".$uid." ORDER BY id DESC");
		if($user['telphone']==NULL)$return.="无1||";else $return.=$tele."||";
		if(!empty($usercause)){
			
			if($usercause['comment']==NULL)$return.="无2||";else $return.=$usercause['comment']."||";
			if($usercause['next_contact_time']=='0'){$return.="无3||";}else {$time=date("Y-m-d H:i:s",$usercause['next_contact_time']);				 		$return.=$time."||";}
			if(empty($usercause['next_contact_desc']))$return.="无4<br/>";else $return.=$usercause['next_contact_desc'].'<br/>';
			echo $return;exit;
		}else{
			$return.="未联系||未联系||未联系<br/>";
			echo $return;exit;
		}
}


//note 客服5分钟检查一次下次提醒
function check_next_contact(){
	$time = time();
	$st = $time;
	$et = $time + 300;
	$time2 = $time + 300;//右下角消息显示5分钟
	$sql = "SELECT a.uid,a.next_contact_time FROM {$GLOBALS['dbTablePre']}member_admininfo a
			LEFT JOIN {$GLOBALS['dbTablePre']}members_search m
			ON a.uid = m.uid
			WHERE m.sid = {$GLOBALS['adminid']} AND a.next_contact_time BETWEEN {$st} AND {$et}";
	$ret =  $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
	foreach($ret as $v){
		$date = date("Y-m-d H:i:s",$v['next_contact_time']);
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}admin_remark(sid,title,content,awoketime,dateline,send_id)
				VALUES({$GLOBALS['adminid']},'联系会员的时间到了','会员{$v['uid']}的下次联系时间{$date}快到了!',{$time2},{$time},{$GLOBALS['adminid']})";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}
}


//组长给客服的当天评论（盘库）
function ajax_write_remark(){
     global $_MooClass,$dbTablePre,$timestamp;
	 $gid=$GLOBALS['adminid'];
     $sid=MooGetGPC('sid','string','P');
     $content_val=MooGetGPC('content','string','P');
	 $curday=strtotime(date("Y-m-d"));
	 $nextday=strtotime("+1 day");
	 // echo $sid.'and'.$content_val;exit;

	  $_MooClass['MooMySQL']->query("update {$dbTablePre}member_effectgrade set remark='$content_val' where sid='$sid' and dateline>='$curday' and dateline<'$nextday'");          
	  if(!$_MooClass['MooMySQL']->affectedRows()){
		 $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}member_effectgrade (gid,sid,remark,dateline) VALUES('$gid','$sid','$content_val','$timestamp')");
	  }
	  
	  echo "ok";
	  
	  
}
//note 设置红娘币的初始值
function ajax_up_moy(){
	global $_MooClass,$dbTablePre,$money_;
	
	//组长权限$GLOBALS['admin_all_group'];
	//普通客服权限$GLOBALS['general_service'];
	
	$sql="select * from web_admin_user";

	$arr= $_MooClass['MooMySQL']->getAll( $sql );

	foreach($arr as $v) {
	
       if(in_array($v['groupid'],$GLOBALS['admin_all_group'])){//组长
       $money_="500";
    
       }else if(in_array($v['groupid'],$GLOBALS['general_service'])){//客服
          $money_="0";
       }else{
          $money_="-1";
       }
       $sql = "UPDATE {$dbTablePre}admin_user SET money='{$money_}' where uid={$v['uid']}";


	   if($_MooClass['MooMySQL']->query($sql)){
	   }else{
		  echo 'no';exit;
	   }

	 echo 'ok';
	
    }
}


//删除评论
function del_comment(){
   global $_MooClass;
   $id=array();
   $id=$_POST['id'];
   $idlist=implode(",",$id);
   
   // echo $idlist;exit;
   
   $sql="delete from web_members_comment where id in({$idlist})";
   
   if($_MooClass['MooMySQL']->query($sql)){
      echo 1;exit;
   }else{
   
      echo 0;exit;
   } 
   


}

function lostContact(){
    $file='include/allow_ip.php';
    $handle = fopen("include/allow_ip.php", "rb");

	$contents = '';
	if($handle){
		while (!feof($handle)) {
		    $content = fgets($handle, 4096);
			if(strpos($content,'<?php')!==false  ||  strpos($content,'?>')!==false || trim($content)=='') continue;
			if(strpos($content,'#')!==false){
			    $content=str_replace('#','',$content);
			}else{
			    $content='#  '.$content;
			}
			
		    $contents .= $content;
		    
		}
	}
	fclose($handle);
	$contents="<?php \r\n".$contents. "\r\n ?>";

	file_put_contents($file, $contents);
}

/***************************************控制层(V)***********************************/
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间

$n=MooGetGPC('n','string','R');
//判断是否登录
adminUserInfo();
if(empty($GLOBALS['adminid'])){
	echo 'nologin';exit;
}



switch($n){
	case 'delAdminUser':
		del_admin_user();
		break;
	case 'lostContact':
	    lostContact();
		break;
	case 'deleteComment':
	    del_comment();
		break;
	case 'delGroup':
		del_group();
		break;
	case 'del_action':
		del_action();
		break;
	case 'searchKefu':
		search_kefu();	
		break;
	case 'remove_group':
		remove_group();	
		break;
	case 'judgeMember':
		judge_member();
		break;
	case 'search_group':
		search_group();
	break;
	case 'judge_group':
		judge_group();
	break;
	case 'storyrecommand' : 
		ajax_storyrecommand();
	break;
	case 'lockuser': 
		ajax_lockuser();
	break;
	case 'dealuser' : 
		ajax_dealuser(); 
	break;
	case 'agreeemail' : 
		ajax_agreeemail();
	break;
	case 'change_email' : 
		ajax_change_email();
	break;
	case 'check_school' : 
		ajax_check_school();
	break;
	case 'remark':
		ajax_remark();
	break;
	case 'change_remark_status':
		ajax_change_remark_status();
	break;
	case 'checkdating':
		ajax_check_dating();
	break;
	case 'checkredating':
		ajax_check_dating();
	break;
	case 'add_video':
		ajax_add_video();
	break;
	case 'congratulate_remark':
		ajax_congratulate_remark();
	break;
	case 'callinfo' : ajax_callinfo();
	break;
	case 'congratulate_remark_hidden':
		congratulate_remark_hidden();
	break;
	case 'nooperation_remark':
		service_nooperation_remark();
	break;
	//note 检查下次提醒
	case 'check_next_contact':
		check_next_contact();
	break;
	case 'change_introduce':
		ajax_check_introduce();
	break;
	
	case 'write_remark': //组长给客服的评语
	    ajax_write_remark();
		break;
    case 'upmoy': //更新初始的红娘币
			ajax_up_moy();
			break;
		
		
}
?>