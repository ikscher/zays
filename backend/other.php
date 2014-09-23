<?php
/**
 * 其它管理
 * @author:fanglin
 *  $Id$
 */
//note 会员分配记录

function other_member_allot_record(){	
	$pages = '';
	$groupid=MooGetGPC('groupid','integer','G');
	if($groupid ||in_array($GLOBALS['groupid'],$GLOBALS['admin_all_group'])){
		other_member_allot_record_group();
		return;
	}elseif(in_array($GLOBALS['groupid'],$GLOBALS['general_service'])){
		other_member_allot_record_user();
		return;
	}
	$condition[] = "type=1";
	$type = MooGetGPC('type','string','P');
	$keyword = MooGetGPC('keyword','string','P');	
	if(isset($_POST['allot_time1'])){
		$allot_time1=MooGetGPC('allot_time1','string','P');
		$allodate1 =  strtotime($allot_time1);
		$allodate1 = strtotime(date("Y-m-d 00:00:00",$allodate1));
		$condition[] = " dateline>='{$allodate1}'";
	}else{
		$allodate1 = strtotime(date("Y-m-d 00:00:00"))-86400;
		$condition[] = " dateline>='{$allodate1}'";
	}
	if(isset($_POST['allot_time2'])){
		$allot_time2=MooGetGPC('allot_time2','string','P');
		$allodate2 = strtotime($allot_time2);
		$allodate2 = strtotime(date("Y-m-d 23:59:59",$allodate2));
		$condition[] = " dateline<='{$allodate2}'";
	}else{
		$allodate2 = strtotime(date("Y-m-d 23:59:59"))-86400;
		$condition[] = " dateline<='{$allodate2}'";
	}
	$sql_where = 'WHERE '. implode(' AND ',$condition);
	$sql="SELECT uid,username,kefu_num,sum(allot_member) AS allot_member FROM {$GLOBALS['dbTablePre']}admin_telcount $sql_where group by uid";
	$ret = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	require_once(adminTemplate('other_member_allot_record'));
}
//个人分配记录
function other_member_allotrecord_per(){
	$page = max(1,MooGetGPC('page','integer','G'));
    $limit = 16;
    $offset = ($page-1)*$limit;
	$uid = MooGetGPC('uid','string');
	
	$order = MooGetGPC('order','string');
	$order = $order ? $order : 'DESC';
	    
    $where = "WHERE uid = '{$uid}'";
    $total = getcount('allotuser',$where);
    $currenturl = "index.php?action=other&h=member_allot_record&uid={$uid}&order={$order}";
   
    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}allotuser {$where} ORDER BY allot_time {$order} LIMIT {$offset},{$limit}";
    $ret = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$order = $order == 'ASC' ? 'DESC' : 'ASC'; 
   	$pages = multipage( $total, $limit, $page, $currenturl );
	require_once(adminTemplate('other_member_allotrecord_per'));
}

//查看组内分配记录
function other_member_allot_record_group(){
	$groupid=MooGetGPC('groupid','integer','G');
	$sid=MooGetGPC('sid','integer','G');		
	if($sid){
		other_member_allot_record_user();
		return;
	}
	if($groupid){
		$sql="SELECT manage_list FROM {$GLOBALS['dbTablePre']}admin_manage where id={$groupid}";
		$sid_arr=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$sid_list=$sid_arr['manage_list'];
	}else{
		$sid_list = get_myservice_idlist();
	}
	
	if(!empty($sid_list)){	
	    $condition[] = "type=2 and  uid in (".$sid_list.")";
	}
	$type = MooGetGPC('type','string','P');
	$keyword = MooGetGPC('keyword','string','P');
	if(!empty($_REQUEST['allot_time1'])){
		$allot_time1=MooGetGPC('allot_time1','string');
		$allodate1 =  strtotime($allot_time1);
		$allodate1 = strtotime(date("Y-m-d 00:00:00",$allodate1));
		$condition[] = " dateline>='{$allodate1}'";
	}else{
		$allodate1 = strtotime(date("Y-m-d 00:00:00"))-86400;
		$condition[] = " dateline>='{$allodate1}'";
	}
	if(!empty($_REQUEST['allot_time2'])){
		$allot_time2=MooGetGPC('allot_time2','string');
		$allodate2 = strtotime($allot_time2);
		$allodate2 = strtotime(date("Y-m-d 23:59:59",$allodate2));
		$condition[] = " dateline<='{$allodate2}'";
	}else{
		$allodate2 = strtotime(date("Y-m-d 23:59:59"))-86400;
		$condition[] = " dateline<='{$allodate2}'";
	}
	$sql_where = 'WHERE '. implode(' AND ',$condition);
	
    $sql="SELECT uid,username,member_count,sum(allot_member) AS allot_member FROM {$GLOBALS['dbTablePre']}admin_telcount $sql_where group by uid ";
    $ret = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);	
   	
	require_once(adminTemplate('other_member_allot_record_group'));
}

//查看指定客服的分配记录
function other_member_allot_record_user(){
	$page = max(1,MooGetGPC('page','integer','G'));
    $limit = 16;
    $offset = ($page-1)*$limit;
	$groupid=MooGetGPC('groupid','integer','G');
	$sid = MooGetGPC('sid','integer');
	if($sid){
	
	}else{
		$sid=$GLOBALS['adminid'];
	}
	
	$order = MooGetGPC('order','string');
	$order = $order ? $order : 'DESC';
	
	$where = "where allot_sid='{$sid}'";
    $total = getcount('allotuser',$where);
    $currenturl = "index.php?action=other&h=member_allot_record&groupid={$groupid}&sid={$sid}&order={$order}";

    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}allotuser {$where} ORDER BY allot_time {$order} LIMIT {$offset},{$limit}";
    $ret = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$order = $order == 'ASC' ? 'DESC' : 'ASC';
   	$pages = multipage( $total, $limit, $page, $currenturl );
	require_once(adminTemplate('other_member_allot_record_user'));
}
//会员分配记录汇总
function other_member_allot_record_summary(){
	$kefu_list = get_kefulist();
	$pages = '';
	$condition = array();
	if(isset($_GET['kefu_uid'])){
		$sid = MooGetGPC('kefu_uid','integer','G');
		$condition[] = "allot_sid='{$sid}'";
	}
	
	if(isset($_GET['allot_time1'])){
		$allodate1 =  strtotime(MooGetGPC('allot_time1','string','G'));
		$allodate1 = strtotime(date("Y-m-d 00:00:00",$allodate1));
		$condition[] = " allot_time>='{$allodate1}'";
	}else{
		$allodate1 = strtotime(date("Y-m-d 00:00:00"));
		$condition[] = " allot_time>='{$allodate1}'";
	}
	if(isset($_GET['allot_time2'])){
		$allodate2 = strtotime(MooGetGPC('allot_time2','string','G'));
		$allodate2 = strtotime(date("Y-m-d 23:59:59",$allodate2));
		$condition[] = " allot_time<='{$allodate2}'";
	}else{
		$allodate2 = strtotime(date("Y-m-d 23:59:59"));
		$condition[] = " allot_time<='{$allodate2}'";
	}
	$sql_where = 'WHERE '. implode(' AND ',$condition);
	
	$sql = "SELECT COUNT(*) c ,sid,allot_sid,allot_time FROM {$GLOBALS['dbTablePre']}allotuser $sql_where GROUP BY allot_sid";
	$ret = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);

	require_once(adminTemplate('other_member_allot_record_summary'));
}

//note 放弃会员列表
function other_giveup_member(){
	$page = max(1,MooGetGPC('page','integer','G'));
    $limit = 16;
    $offset = ($page-1)*$limit;
	$type = MooGetGPC('type','string');
	$keyword = MooGetGPC('keyword','string');
	$order = MooGetGPC('order','string');
	$order = $order ? $order : 'DESC';
	
	$where = 'WHERE effect_grade = 10';
    $total = getcount('member_backinfo',$where);
    $currenturl = "index.php?action=other&h=giveup_member&order={$order}";
    
    if(!empty($type) && !empty($keyword)){
    	$where .= " AND {$type} = '{$keyword}'";
    	$total = getcount('member_backinfo',$where);
    	$currenturl = "index.php?action=other&h=giveup_member&type={$type}&keyword={$keyword}&order={$order}";
    }
    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}member_backinfo {$where} ORDER BY dateline {$order} LIMIT {$offset},{$limit}";
    $ret = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    //echo $sql;
	$order = $order == 'ASC' ? 'DESC' : 'ASC'; 
   	$pages = multipage( $total, $limit, $page, $currenturl );
	require_once(adminTemplate('other_giveup_member'));
}
//note 预设短信列表
function other_smspre_list(){
	$page = max(1,MooGetGPC('page','integer','G'));
    $limit = 16;
    $offset = ($page-1)*$limit;
    
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}smspre WHERE (`target` = '0' OR `target` = '{$GLOBALS['adminid']}' ) ORDER BY id DESC LIMIT {$offset},{$limit}";
	$sms = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$where = "WHERE `target` = '0' OR `target` = '{$GLOBALS['adminid']}'";
	$total = getcount('smspre',$where);
	$currenturl = "index.php?action=other&h=smspre_list";
   	$pages = multipage( $total, $limit, $page, $currenturl );
	require_once(adminTemplate('other_smspre_list'));
}
//note 添加预设短信
function other_smspre_add(){
	$ispost = MooGetGPC('ispost','integer','P');
	if( $ispost ){
		$content = MooGetGPC('content','string','P');
		$target = MooGetGPC('target','integer','P');
		
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}smspre(content,target) VALUES('{$content}',{$target})";
		$ret= $GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//写日志
		serverlog(3,$GLOBALS['dbTablePre'].'smspre',"客服{$GLOBALS['username']}添加预设短信ID{$ret}",$GLOBALS['adminid']);
		salert("添加成功","index.php?action=other&h=smspre_add");
	}
	require_once(adminTemplate('other_smspre_add'));
}
//note 编辑预设短信
function other_smspre_edit(){
	$isedit = 1;
	$id= MooGetGPC('id','integer');
	$ispost = MooGetGPC('ispost','integer','P');
	if($ispost){
		$content = MooGetGPC('content','string','P');
		$target = MooGetGPC('target','integer','P');
		$sql = "UPDATE {$GLOBALS['dbTablePre']}smspre SET content = '{$content}',target = {$target} WHERE id = {$id}";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//写日志
		serverlog(2,$GLOBALS['dbTablePre'].'smspre',"客服{$GLOBALS['username']}修改预设短信ID{$ret}",$GLOBALS['adminid']);
		salert("修改成功");
	}
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}smspre WHERE id = {$id}";
	$sms = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);	
	require_once(adminTemplate('other_smspre_add'));
}

//note 删除预设短信
function other_smspre_del(){
	$id= MooGetGPC('id','integer','G');
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}smspre WHERE id = {$id}";
	$ret = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	//写日志
	serverlog(4,$GLOBALS['dbTablePre'].'smspre',"客服{$GLOBALS['username']}删除预设短信ID{$id}",$GLOBALS['adminid']);
	salert("删除成功","index.php?action=other&h=smspre_list");
}

//note设置删除会员去向
function set_del_user(){
	$is_post=MooGetGPC('is_post','integer');
	if(file_exists("data/del_to_user.txt")){
		$file_kefuid=fopen("data/del_to_user.txt",'r');
		$del_to_sid=fread($file_kefuid,11);
		fclose($file_kefuid);
	}else{$file_kefuid=fopen("data/del_to_user.txt",'w');}
	if($is_post){
		$new_to_sid=MooGetGPC('user_to_sid','integer');
		$file_kefuid=fopen("data/del_to_user.txt",'w');
		if(fwrite($file_kefuid,$new_to_sid)){
			serverlog(3,'txt',"客服{$GLOBALS['username']}修改删除会员去向ID为{$new_to_sid}",$GLOBALS['adminid']);
			salert("添加成功","index.php?action=other&h=set_del_user");}
		fclose($file_kefuid);
	}
	require_once(adminTemplate('other_set_del_user'));
}
//会员联系情况组统计设置
function count_num_set(){
	$is_post=MooGetGPC('is_post','integer');
	if(file_exists("data/valid_group.txt")){
		$file_kefuid=fopen("data/valid_group.txt",'r');
		$del_to_sid=fread($file_kefuid,255);
		fclose($file_kefuid);
	}else{$file_kefuid=fopen("data/valid_group.txt",'w');}
	if($is_post){
		$new_to_sid=MooGetGPC('user_to_sid','string');
		$file_kefuid=fopen("data/valid_group.txt",'w');
		if(fwrite($file_kefuid,$new_to_sid)){
			serverlog(3,'txt',"客服{$GLOBALS['username']}修改组统计有效ID为{$new_to_sid}",$GLOBALS['adminid']);
			salert("添加成功","index.php?action=other&h=count_num_set");}
		fclose($file_kefuid);
	}
	require_once(adminTemplate('count_num_set'));
}

//售后组的添加
function count_after_num(){
	$is_post=MooGetGPC('is_post','integer');
	if(file_exists("data/group_after.txt")){
		$file_kefuid=fopen("data/group_after.txt",'r');
		$del_to_sid=fread($file_kefuid,255);
		fclose($file_kefuid);
	}else{$file_kefuid=fopen("data/group_after.txt",'w');}
	if($is_post){
		$new_to_sid=MooGetGPC('user_to_sid','string');
		$file_kefuid=fopen("data/group_after.txt",'w');
		if(fwrite($file_kefuid,$new_to_sid)){
			serverlog(3,'txt',"客服{$GLOBALS['username']}修改组统计有效ID为{$new_to_sid}",$GLOBALS['adminid']);
			salert("添加成功","index.php?action=other&h=count_after_num");}
		fclose($file_kefuid);
	}
	require_once(adminTemplate('count_after_num'));
}

//编辑预设彩信
function other_mmspre_edit(){
	$isedit = 1;
	$id= MooGetGPC('id','integer');
	$ispost = MooGetGPC('ispost','integer','P');
	if($ispost){
		$content = MooGetGPC('content','string','P');
		$title = MooGetGPC('title','string','P');
		$sql = "UPDATE {$GLOBALS['dbTablePre']}mmspre SET content = '{$content}',title = '{$title}' WHERE id = {$id}";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//写日志
		serverlog(2,$GLOBALS['dbTablePre'].'mmspre',"客服{$GLOBALS['username']}修改预设短信ID{$id}",$GLOBALS['adminid']);
		salert("修改成功");
	}
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}mmspre WHERE id = {$id}";
	$sms = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);	
	require_once(adminTemplate('other_mmspre_add'));
}

//note 添加预设短信
function other_mmspre_add(){
	$ispost = MooGetGPC('ispost','integer','P');
	if( $ispost ){
		$content = MooGetGPC('content','string','P');
		$title = MooGetGPC('title','string','P');
		$time=time();
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}mmspre(title,content,user,dateline,is_read) VALUES('{$title}','{$content}',{$GLOBALS['adminid']},'{$time}',0)";
		$ret= $GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//写日志
		serverlog(3,$GLOBALS['dbTablePre'].'mmspre',"客服{$GLOBALS['username']}添加预设彩信ID{$ret}",$GLOBALS['adminid']);
		salert("添加成功","index.php?action=other&h=mmspre_add");
	}
	require_once(adminTemplate('other_mmspre_add'));
}
//删除预设彩信
function other_mmspre_del(){
	$id= MooGetGPC('id','integer','G');
	$sql = "update {$GLOBALS['dbTablePre']}mmspre set is_read=1 WHERE id = {$id}";
	$ret = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	//写日志
	serverlog(4,$GLOBALS['dbTablePre'].'mmspre',"客服{$GLOBALS['username']}删除预设彩信ID{$id}",$GLOBALS['adminid']);
	salert("删除成功","index.php?action=other&h=mmspre_list");
}
//预设彩信内容
function other_mmspre_list(){
	$page = max(1,MooGetGPC('page','integer','G'));
    $limit = 16;
    $offset = ($page-1)*$limit;
    
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}mmspre where is_read=0 ORDER BY id DESC LIMIT {$offset},{$limit}";
	$sms = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$total = getcount('mmspre','');
	$currenturl = "index.php?action=other&h=mmspre_list";
   	$pages = multipage( $total, $limit, $page, $currenturl );
	require_once(adminTemplate('other_mmspre_list'));
}
//群发短信
function other_sendmsm_most(){
    global $memcached;
	$ispost=MooGetGPC('ispost','integer','P');
	if($ispost){
		$uid_list=MooGetGPC('uid_list','string','P');
		
		//过滤黑名单 BEGIN BY HWT
		$uid_arr=explode(',',$uid_list);
		
		$uid=array();

		foreach ($uid_arr as $v){
			$sql = "select id from web_members_blacklist where uid={$v}";
            $result=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql); 
            if(empty($result['id'])){
            	$uid[]= $v;
            }
		}
		$uid=implode(',',$uid);

		$content=MooGetGPC('content','string','P');
		$content.='【真爱一生网】';
		$sql="SELECT telphone,uid FROM {$GLOBALS['dbTablePre']}members_search where uid in ($uid) and telphone!=''";
		$tel_more_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		foreach($tel_more_arr as $tel){
			$tel_arr[]=$tel['telphone'];
				$dateline=time();
				$sql = "INSERT INTO {$GLOBALS['dbTablePre']}smslog(sid,uid,content,sendtime) VALUES('{$GLOBALS['adminid']}','{$tel['uid']}','{$content}','{$dateline}')";
				$GLOBALS['_MooClass']['MooMySQL']->query($sql);			
		}

		if($ret=SendMsg($tel_arr,$content)){
			salert("群发送短信成功！","index.php?action=other&h=sendmsm_most");
		}else{salert("群发送短信失败！","index.php?action=other&h=sendmsm_most");}
	}
	$uid=$memcached->get('sendsmsuid');
	if(is_array($uid)) $uid=implode(',',$uid);
	require_once(adminTemplate('other_sendmsm_most'));
}


//人事群发短信
function other_sendmsm(){
    $dateline=time();
	$ispost=MooGetGPC('post','integer','G');
	$tel_arr=array();
	if($ispost){
		$tel_list=MooGetGPC('tel_list','string','P');
		$content=MooGetGPC('content','string','P');
		$content .='【真爱一生网】';
		$tel_arr=explode(',',$tel_list);
		if($ret=SendMsg($tel_arr,$content,1)){
		    foreach ($tel_arr as $tel){
		       $sql = "INSERT INTO {$GLOBALS['dbTablePre']}resource_sendmsg(tel,content,dateline) VALUES('{$tel}','{$content}','{$dateline}')";
			   $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            }			   
			salert("群发送短信成功！","index.php?action=other&h=sendmsm");
		}else{salert("群发送短信失败！","index.php?action=other&h=sendmsm");}
	}
	require_once(adminTemplate('other_sendmsm'));
}

//人事短信发送查询记录
function other_sendmsg_record(){
	$telphone=MooGetGPC('search_tel','string','P');
	
	$data=array();
	$page_per = 5;
	$page = max(1,MooGetGPC('page','integer'));
	$limit = 5;
	$total=0;
	$offset = ($page-1)*$limit;
	
	if ($_POST['post']==1){
		if(!empty($telphone)){
			$sql='SELECT id,tel,content,dateline from web_resource_sendmsg WHERE  tel='.$telphone.' LIMIT '.$offset.','.$limit;
			$data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		}
	}else{
	
	      $sql='SELECT id,tel,content,dateline from web_resource_sendmsg  LIMIT '.$offset.','.$limit;
			$data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
			$sql="SELECT count(id) as total from web_resource_sendmsg";
			
			$rs=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			$total=$rs['total'];
	}
	
	$currenturl = "index.php?action=other&h=sendmsg_record";
	$pages = multipage( $total, $limit, $page, $currenturl );
	$total = ceil($total/$limit);
	require_once(adminTemplate('other_sendmsg_record'));

}

//群发邮件
function other_sendmail_batch(){
	global $dbTablePre,$timestamp;
    $ispost=MooGetGPC('post','string','P');
    //echo $dbTablePre;exit;
    if($ispost){
    	
        $province=MooGetGPC('workprovince','string','P');
        $city=MooGetGPC('workcity','string','P');
        $age1=MooGetGPC('age_start','string','P');
        $age2=MooGetGPC('age_end','string','P');
        
        $gender=MooGetGPC('gender','string','P');
        $title=MooGetGPC('msg_title','string','P');
        $content=MooGetGPC('msg_content','string','P');
        
        $start=date('Y')-$age1;
        $end=date('Y')-$age2;
        
        if($city==0){
           $sql="SELECT uid FROM {$GLOBALS['dbTablePre']}members_search where province='{$province}' and birthyear<='$start' and birthyear>='$end' and gender='$gender'";
        }else{
           $sql="SELECT uid FROM {$GLOBALS['dbTablePre']}members_search where province='{$province}' and city='{$city}' and birthyear<='$start' and birthyear>='$end' and gender='$gender'";
        
        }
		
        
        $arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		
		$sum=0;
        
		$uid='';
        foreach($arr as $v){
           $uid=$v['uid'] ;
           
           //过滤黑名单
           $sql = "select id from web_members_blacklist where uid={$uid}";
           $result=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql); 
           if(empty($result['id'])){
	           $GLOBALS['_MooClass']['MooMySQL']->query("insert into {$dbTablePre}services (s_cid,s_uid,s_fromid,s_title,s_content,s_time,sid,flag) values ('3','{$uid}','0','{$title}','{$content}','{$timestamp}','0','1')"); 
	           $sum++;
           }		   
        }
		
  
       salert("群发站内邮件成功！共发出 【 $sum 】 封邮件","index.php?action=other&h=sendmail_batch");
      
    }
    require_once(adminTemplate('other_sendmail_batch'));
}
function other_sendmail_batch_2(){
	global $dbTablePre,$timestamp,$_MooClass;
	$uid_list=MooGetGPC('uid_list','string','P');
		
		//过滤黑名单 BEGIN BY HWT
		$uid_arr=explode(',',$uid_list);
		
		$uid=array();
		
		foreach ($uid_arr as $k => $v){
			$sql = "select username,uid from web_members_search where uid= $v";
            $result=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql); 
			if(!empty($result['username'])){
            	$username[$k]['username'] = $result['username'];
				$username[$k]['uid'] = $result['uid'];
            }
		}
		
		$content=MooGetGPC('content','string','P');
		$title=MooGetGPC('title','string','P');
	
		foreach($username as $key => $value){
			MooSendMail($value['username'],$title,$content,$type='',$is_template = true,$uid=$value['uid']);
		}
		
}

//红娘意见箱
function other_complaint_box() {
	global $_MooClass, $dbTablePre, $_MooCookie, $areaid_arr, $foreground, $background, $accept_arr, $status_arr, $kefu_arr, $adminid, $groupname;
	$type = MooGetGPC('type', 'string', 'G');
	$type = $type ? $type : 'list';
	$allow_type = array('list', 'show', 'submit', 'del');
	if(!in_array($type, $allow_type)) {
		salert('没有该操作', 'index.php?action=other&h=complaint_box');
	}

	switch($type) {
		case 'list':
			$page = get_page();
			$starttime = MooGetGPC('starttime', 'string', 'G');
			$endtime = MooGetGPC('endtime', 'string', 'G');
			$uid = MooGetGPC('uid', 'integer', 'G');
			$condition = array(
				'module' => MooGetGPC('module', 'integer', 'G'),
				'areaid' => MooGetGPC('areaid', 'integer', 'G'),
				'status' => MooGetGPC('status', 'integer', 'G'),
				'accept' => MooGetGPC('accept', 'integer', 'G')
			);
			
			//意见
			$sql = "select * from {$dbTablePre}complaint_box ";
			//意见数量
			$sql_count = "select count(1) as count from {$dbTablePre}complaint_box ";
			$sql_where = "";
			$n = 0;
			
			$field = MooGetGPC('field', 'string', 'G');
			$value = MooGetGPC('value', 'string', 'G');
			//提交者查询
			$where = array();

			if($field == 'uid' && $value) {
				foreach($kefu_arr as $key => $val) {
					if($value == $val) {
						$isuid = $key;
						break;
					}
				}
				$where[] = "uid = '{$isuid}'";
			}
			
			//意见ID查询
			if($field == 'cid' && $value) {
				$where[] = "cid = '$value'";
			}
			
			//模块及处理结果
			foreach($condition as $key => $val) {
				if(isset($_GET[$key]) && $val >= 0) {
					$where[] = "`{$key}` = '{$val}'";
				}
			}
			//时间条件
			if($starttime) {
				$starttime = strtotime($starttime); 
				$where[] = "submittime >= '{$starttime}'";
				$n++;
			}
			if($endtime) {
				$endtime = strtotime($endtime);
				$where[] = "submittime <= '{$endtime}'";
			}
			$sql_where = implode(' and ', $where);

			//所有排序方式
			$allow_order = array(
				'submittime' => '提交时间',
				'replaytime' => '回复时间',
				'areaid' => '反馈区域',
				'status' => '处理结果',
				'accept' => '是否采纳',
				'cid' => '意见ID'
			);

			//获取查询条件，及排序方式
			$query_builder = get_query_builder($sql_where, $allow_order, 'accept', 0, 'submittime', 'desc', array(), 1);
			$sql_where = 'where '.$query_builder['where'];
			$sql_order = $query_builder['sort'];
			$sort_arr = $query_builder['sort_arr'];
			$rsort_arr = $query_builder['rsort_arr'];
			//获取意见数量
			$total = $_MooClass['MooMySQL']->getOne($sql_count.$sql_where);
			$total = $total['count'];

			//分页显示
			$limit = 15;
			$offset = ($page - 1) * $limit;
			$sql_where .= "{$sql_order} limit {$offset}, {$limit}";
			$complaint = array();
			$complaint = $_MooClass['MooMySQL']->getAll($sql.$sql_where);
			//echo $sql.$sql_where;
			$currenturl = "index.php?".$_SERVER['QUERY_STRING'];
			include_once(adminTemplate('other_complaint_list'));
		break;
		case 'show':
			$cid = MooGetGPC('cid', 'integer', 'G');
			if($cid) {
				$sql = "select * from {$dbTablePre}complaint_box where cid = '{$cid}'";
				$complaint = $_MooClass['MooMySQL']->getOne($sql);
				$lei = '查看意见反馈';
			} else {
				$lei = '意见反馈提交';
			}
			include_once(adminTemplate('check_show'));
		break;
		case 'submit':
			//提交
			$cid = MooGetGPC('cid', 'integer', 'P');
			$replay = MooGetGPC('replay', 'string', 'P');
			$accept = MooGetGPC('accept', 'integer', 'P');
			$status = MooGetGPC('status', 'integer', 'P');
			$module = MooGetGPC('module', 'integer', 'P');
			$uid = MooGetGPC('uid', 'integer', 'P');
			$areaid = MooGetGPC('areaid', 'ingeter', 'P');
			$content = MooGetGPC('content', 'string', 'P');
			$complaint = MooGetGPC('complaint', 'string', 'P');

			if($cid) {
				if($complaint == '回复') {
					$time = time();
					$sql = "update {$dbTablePre}complaint_box set `replay` = '{$replay}', `accept` = '{$accept}', `status` = '{$status}', replaytime = '{$time}' where cid = {$cid}";
					$_MooClass['MooMySQL']->query($sql);
					salert("回复成功", "index.php?action=other&h=complaint_box&type=list");
				} else {
					if($adminid == $uid) {
						$timetype = 'submittime';
					} else {
						$timetype = 'replaytime';
					}
					$sql = "update {$dbTablePre}complaint_box set content = '{$content}', `replay` = '{$replay}', `accept` = '{$accept}', `status` = '{$status}', {$timetype} = '{$time}' where cid = {$cid}";
					$_MooClass['MooMySQL']->query($sql);
					salert("修改成功", "index.php?action=other&h=complaint_box&type=show&cid={$cid}");
				}
			} else {
				$time = time();
				$sql = "insert into {$dbTablePre}complaint_box(uid, areaid, `module`, content, submittime) values('{$adminid}', '{$areaid}', '$module', '$content', '$time')";
				$_MooClass['MooMySQL']->query($sql);
				$cid = $_MooClass['MooMySQL']->insertId();
				salert("发表成功", "index.php?action=other&h=complaint_box&type=list");
			}
		break;
		case 'del':
			$cid = MooGetGPC('cid', 'integer', 'G');
			$sql = "delete from {$dbTablePre}complaint_box where cid = '{$cid}'";
			$_MooClass['MooMySQL']->query($sql);
			salert("删除成功", "index.php?action=other&h=complaint_box&type=list");
		break;
	}
}



//更新指定的fsatdb
function other_update_fastdb(){
	global $_MooClass, $dbTablePre,$fastdb;
	$ispost=MooGetGPC('ispost','integer','G');
	if($ispost){
		$table=MooGetGPC('table','string','P');
		$field=MooGetGPC('field','string','P');
		$key=MooGetGPC('key','string','P');
		$fbvalue = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}{$table} WHERE {$field}='{$key}'  LIMIT 1");
		if($fbvalue){
			if( $fastdb->get($table.$field.$key) ){
				$fastdb->replace($table.$field.$key,serialize($fbvalue));
				$return = "更新成功";
			}else{
				$fastdb->set($table.$field.$key,serialize($fbvalue));
				$return = "更新成功";
			}
		}else{
			$return = "更新失败请查证后操作！";
		}
		salert($return, "index.php?action=other&h=update_fastdb");
	}
	require_once(adminTemplate('other_update_fastdb'));
}

function text_show_list(){
	global $_MooClass;
	$pages= '';
	$page_per = 15;
	if(isset($_REQUEST['submit'])){
		$page = 1;
	}else{
		$page = max(1,MooGetGPC('page','integer'));
	}
	$limit = 15;
	$offset = ($page-1)*$limit;
	$sql = "select count(id) as count from {$GLOBALS['dbTablePre']}text_show order by reg_time desc";
	$a = $_MooClass['MooMySQL']->getOne($sql);
	$total = $a['count'];
	$currenturl = "index.php?action=other&h=text_show";
	$sql = "select * from {$GLOBALS['dbTablePre']}text_show order by reg_time desc LIMIT {$offset},{$limit}";
	$result=$_MooClass['MooMySQL']->getAll($sql);
	foreach($result as $k => $v){
		$result[$k]['k'] = $k+1;
		$result[$k]['start_time'] = date('Y-m-d H:i:s',$v['start_time']);
		$result[$k]['end_time'] = date('Y-m-d H:i:s',$v['end_time']);
		$result[$k]['reg_time'] = date('Y-m-d H:i:s',$v['reg_time']);
		if($v['type'] == 1){
			$result[$k]['type'] = '结婚';
		}else if($v['type'] == 2){
			$result[$k]['type'] = '恋爱';
		}else if($v['type'] == 3){
			$result[$k]['type'] = '约会';
		}else if($v['type'] == 4){
			$result[$k]['type'] = '成功故事';
		}
		if($v['show'] == 1){
			$result[$k]['show'] = '显示';
		}else{
			$result[$k]['show'] = '不显示';
		}
	}
	require_once(adminTemplate('text_show_list'));
}
function text_show_edit(){
	global $_MooClass;
	$id = $_REQUEST['id'];
	$sql = "select * from {$GLOBALS['dbTablePre']}text_show where id = $id";
	$result = $_MooClass['MooMySQL']->getOne($sql);
	
	$data['starttime'] = $result['start_time'];
	$data['endtime'] = $result['end_time'];
	$data['content'] = $result['content'];
	$data['order'] = $result['order'];
	$data['type'] = $result['type'];
	$data['uid'] = $result['uid'];
	$data['show'] = $result['show'];
	require_once(adminTemplate('text_show_add'));
}
function text_show_add(){
	global $_MooClass;
	$content=MooGetGPC('content','string','P');
	
	if(isset($content)){
		$starttime = $_REQUEST['starttime'];
		$endtime = $_REQUEST['endtime'];
		$content = $_REQUEST['content'];
		$start_time =mktime('0','0','0',substr($starttime,5,2),substr($starttime,8,2),substr($starttime,0,4)); 
		$end_time=mktime('23','59','59',substr($endtime,5,2),substr($endtime,8,2),substr($endtime,0,4));
		$reg_time = time();
		
		$uid = $_REQUEST['uid'];
		$show = $_REQUEST['show'];
		$type = $_REQUEST['type'];
		$order = $_REQUEST['order'];
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}text_show(start_time,end_time,content,reg_time,username,uid,`show`,`type`,`order`) VALUES('$start_time','$end_time','$content','$reg_time','{$GLOBALS['username']}','$uid','$show','$type','$order')";
		$_MooClass['MooMySQL']->query($sql);
		MooMessageAdmin('添加成功','index.php?action=other&h=text_show',1);
	}else{
	     /*    echo 'sdfsdf';
			$data['starttime'] = '';
			$data['endtime'] = '';
			$data['content'] = '';
			$data['order'] = '';
			$data['type'] = '';
			$data['uid'] = '';
			$data['show'] = '';
			$id           = ''; */
		require_once(adminTemplate('text_show_add'));
	}
}

function text_show_update(){
	global $_MooClass;
	$id = $_REQUEST['id'];
	$starttime = $_REQUEST['starttime'];
	$endtime = $_REQUEST['endtime'];
	$start_time =mktime('0','0','0',substr($starttime,5,2),substr($starttime,8,2),substr($starttime,0,4)); 
	$end_time=mktime('23','59','59',substr($endtime,5,2),substr($endtime,8,2),substr($endtime,0,4));
	$content = $_REQUEST['content'];
	$reg_time = time();
	$uid = $_REQUEST['uid'];
	$show = $_REQUEST['show'];
	$type = $_REQUEST['type'];
	$order = $_REQUEST['order'];
	$sql = "update {$GLOBALS['dbTablePre']}text_show SET start_time = '$start_time',end_time = '$end_time',reg_time = '$reg_time',content = '$content',username = '{$GLOBALS['username']}',`uid` = '$uid',`show` = '$show',`type` = '$type',`order` = '$order' where id = '$id'";
	$_MooClass['MooMySQL']->query($sql);
	MooMessageAdmin('修改成功','index.php?action=other&h=text_show',1);
}
function text_show_dele(){
	global $_MooClass;
	$id = $_REQUEST['id'];
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}text_show WHERE id = $id"; 
	$_MooClass['MooMySQL']->query($sql);
	MooMessageAdmin('删除成功','index.php?action=other&h=text_show',1);
}

//删除会员列表
function other_delete_member(){
	$title = '删除会员列表';
	require_once(adminTemplate('other_delete_member'));
}




//note 黑名单管理
function other_blacklist(){
	$sql_where= '';
	
    //提交删除（从黑名单中移除会员）
    if(isset($_POST['submit'])){
        $id=$_POST['changesid'];

        foreach($id as $uid){
            $sql="delete from {$GLOBALS['dbTablePre']}members_blacklist where uid='{$uid}'";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
        } 
    }
    
    //单条 移除
    $uid=MooGetGPC('uid','integer','G');
    if(!empty($uid)){
       $sql="delete from {$GLOBALS['dbTablePre']}members_blacklist where uid='{$uid}'";
       $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    }
    
    $page_per = 15;
    $page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
    
    
    //选择条件
    $keyword = trim(MooGetGPC('keyword','string','P'));
    $choose = MooGetGPC('choose','string','P');
    
    
    if(!empty($choose) && !empty($keyword)){
       $sql_where = "where b.$choose ='$keyword'";  
    }
    
    
    
    $sql = "SELECT COUNT(a.uid) AS COUNT FROM {$GLOBALS['dbTablePre']}members_blacklist as a 
            LEFT JOIN {$GLOBALS['dbTablePre']}members_search as b 
            ON a.uid=b.uid $sql_where";
    $blacklist = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    $total = $blacklist['COUNT'];
    
    $sql = "SELECT b.* FROM {$GLOBALS['dbTablePre']}members_blacklist as a 
            LEFT JOIN {$GLOBALS['dbTablePre']}members_search as b 
            ON a.uid=b.uid $sql_where ORDER BY a.uid DESC
            LIMIT {$offset},{$limit}";
    $list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    $list_count=count($list);
    //enky change   filter"uid=null" 
    for($i=0;$i<$list_count;$i++){
    	if($list[$i]['uid']==null){
    		unset($list[$i]);
    	}
    }
    $currenturl = "index.php?action=other&h=blacklist";
    $page_links = multipage( $total, $page_per, $page, $currenturl );
    $page_num = ceil($total/$limit);
    require_once(adminTemplate('blacklist'));
    
}


//note 添加黑名单
function add_blacklist(){
	global $timestamp;
    if(MooGetGPC('ispost','integer')){
        $userid = trim(MooGetGPC('userid','string','P'));
       

        if(empty($userid)){
            salert('请填写会员ID');
        }
       
        $currenturl = "index.php?action=other&h=add_blacklist";
        
        $sql = "SELECT username FROM {$GLOBALS['dbTablePre']}members_search WHERE uid='{$userid}'";
        $result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
        
        if(empty($result['username'])) salert('不存在这个会员，请确认！',$currenturl);
        
        
        $sql = "SELECT id FROM {$GLOBALS['dbTablePre']}members_blacklist WHERE uid='{$userid}'";
        $result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
        
        
        if(!empty($result['id'])) {
        	salert('此会员已经存在黑名单，请查证！',$currenturl);
        }else{
        	$sid=$GLOBALS['adminid'];
        	//s$time=time();
        	$sql=" insert into web_members_blacklist set uid='{$userid}',sid='{$sid}',optime='{$timestamp}'";
        	$bool=$GLOBALS['_MooClass']['MooMySQL']->query($sql);
        	if($bool){
        	  salert('添加成功！',$currenturl);
        	}
        }
        
        
        
       
        
    }
    require_once(adminTemplate('blacklist_add'));
}


//note 秋波鲜花发送信息编辑
function other_sendinfo(){
    
    
    //提交删除（从黑名单中移除会员）
    if(isset($_POST['submit'])){
        $aid=$_POST['changesid'];

        foreach($aid as $id){
            $sql="delete from {$GLOBALS['dbTablePre']}members_sendinfo where id='{$id}'";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
        } 
    }
    
    //单条 移除
    $id=MooGetGPC('id','integer','G');
    if(!empty($id)){
       $sql="delete from {$GLOBALS['dbTablePre']}members_sendinfo where id='{$id}'";
       $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    }
    
    $page_per = 15;
    $page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
    
    
    //选择条件
    
    $type = MooGetGPC('type','string','P');
    
    
    if(!empty($type)){
       $sql_where = "where type = $type";  
    }else{
       $sql_where='';
    }
    
    
    
    $sql = "SELECT COUNT(id) AS COUNT FROM {$GLOBALS['dbTablePre']}members_sendinfo  $sql_where";
    $sendinfo = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    $total = $sendinfo['COUNT'];
    
    $sql = "SELECT id,content,type,isShow FROM {$GLOBALS['dbTablePre']}members_sendinfo   $sql_where ORDER BY id DESC  LIMIT {$offset},{$limit}";
    
    $list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    $currenturl = "index.php?action=other&h=sendinfo";
    $page_links = multipage( $total, $page_per, $page, $currenturl );
    $page_num = ceil($total/$limit);
    require_once(adminTemplate('other_sendinfo'));
    
}


//note 添加  秋波 ，鲜花 编辑信息
function other_add_sendinfo(){
    global $timestamp;
    if(MooGetGPC('ispost','integer')){
        $content = trim(mysql_real_escape_string(MooGetGPC('content','string','P')));
        $type = trim(MooGetGPC('type','string','P'));
        $isShow = trim(MooGetGPC('isShow','string','P'));
       

        /*if(empty($content)){
            salert('请填写内容！');
        }*/
       
        $currenturl = "index.php?action=other&h=add_sendinfo";
        
        $sid=$GLOBALS['adminid'];
        $sql=" insert into web_members_sendinfo set content='{$content}',sid='{$sid}',isShow='{$isShow}',type='{$type}'";
        $bool=$GLOBALS['_MooClass']['MooMySQL']->query($sql);
        if($bool){
           salert('添加成功！',$currenturl);
        }
    }

    
    require_once(adminTemplate('other_sendinfo_add'));
}

/**为会员赠送鲜花或者减少鲜花**/
function other_add_rose(){
	if(empty($_POST)){
		require_once(adminTemplate('other_add_rose'));
	}else{
		$uid=MooGetGPC('uid','integer','P');
		$rosenumber=MooGetGPC('rosenumber','integer','P');
		//enky add 防止人为输入过大,输入过大则设置为10万
		if($rosenumber>100000){
			$rosenumber=100000;
		}
		//add end
		if(empty($uid) || empty($rosenumber)){
			exit(json_encode(array('flag'=>0,'msg'=>'请确认鲜花熟练或者用户')));
		}else{
			if($GLOBALS['_MooClass']['MooMySQL']->query('UPDATE `'.$GLOBALS['dbTablePre'].'members_base` SET `rosenumber`=`rosenumber`+('.$rosenumber.') WHERE `uid`='.$uid)){
				MooFastdbUpdate('members_base','uid',$uid);
				exit(json_encode(array('flag'=>1,'msg'=>'花朵赠送成功')));
			}else{
				exit(json_encode(array('flag'=>0,'msg'=>'花朵赠送失败')));
			}
		}
	}
}

/**白名单**/
function other_white_list(){
	$uid=MooGetGPC('uid','integer','G');
	$user=$data=array();
	$page_per = 15;
	$page = max(1,MooGetGPC('page','integer'));
	$limit = 15;
	$total=0;
	$offset = ($page-1)*$limit;
	if(!empty($uid)){
        $sql='SELECT w.`id`,ms.uid,ms.gender,ms.username,ms.birthyear,ms.s_cid,ms.salary,mb.mainimg,ms.province,ms.city,ms.regdate,ms.sid,mb.allotdate,ma.effect_grade,ma.real_lastvisit,ma.checkreason,ma.renewalstatus,ma.old_sid FROM `'.$GLOBALS['dbTablePre'].'white_list` AS w LEFT JOIN `'.$GLOBALS['dbTablePre'].'members_search` AS ms ON w.anotheruid=ms.uid LEFT JOIN `'.$GLOBALS['dbTablePre'].'members_base` AS mb ON w.anotheruid=mb.uid LEFT JOIN '.$GLOBALS['dbTablePre'].'member_admininfo AS ma ON w.anotheruid=ma.uid WHERE w.uid='.$uid.' LIMIT '.$offset.','.$limit;
		//$sql='SELECT w.`id`,m.uid,m.gender,m.username,m.birthyear,m.s_cid,m.mainimg,m.salary,m.province,m.city,m.regdate,m.sid,m.allotdate,ma.effect_grade,ma.real_lastvisit,ma.checkreason,ma.renewalstatus,ma.old_sid FROM '.$GLOBALS['dbTablePre'].'white_list AS w LEFT JOIN '.$GLOBALS['dbTablePre'].'members m ON w.anotheruid=m.uid LEFT JOIN '.$GLOBALS['dbTablePre'].'member_admininfo ma ON w.anotheruid=ma.uid WHERE w.uid='.$uid.' LIMIT '.$offset.','.$limit;
		$data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	}
	$currenturl = "index.php?action=other&h=white_list".empty($uid)?'':'&uid='.$uid;
	$pages = multipage( $total, $limit, $page, $currenturl );
	$page_num = ceil($total/$limit);
	require_once(adminTemplate('other_white_list'));
}

/**白名单添加**/
function other_add_white(){
	$uid=MooGetGPC('uid','integer','P');
	$anotheruid=MooGetGPC('anotheruid','integer','P');
	if(empty($uid)||empty($anotheruid)){
		exit(json_encode(array('flag'=>0,'msg'=>'数据不完整')));
	}
	if($uid==$anotheruid){
		exit(json_encode(array('flag'=>0,'msg'=>'UID不可以相同')));
	}
	if($GLOBALS['_MooClass']['MooMySQL']->getOne('select screenid from '.$GLOBALS['dbTablePre'].'screen where uid='.$uid.' and mid='.$anotheruid)){
		exit(json_encode(array('flag'=>0,'msg'=>'其中一方屏蔽了另一方')));
	}
	if($GLOBALS['_MooClass']['MooMySQL']->getOne('select id from '.$GLOBALS['dbTablePre'].'white_list where uid='.$uid.' and anotheruid='.$anotheruid)){
		exit(json_encode(array('flag'=>0,'msg'=>'此条记录已经存在')));
	}
	if($GLOBALS['_MooClass']['MooMySQL']->query('INSERT INTO '.$GLOBALS['dbTablePre'].'white_list (`uid`,`anotheruid`) VALUES ('.$uid.','.$anotheruid.')')){
		exit(json_encode(array('flag'=>0,'msg'=>'数据出现问题')));
	}else{
		exit(json_encode(array('flag'=>0,'msg'=>'数据出现问题')));
	}
}

/***********************************************控制层(C)*****************************************/

$h=MooGetGPC('h','string');
//note 动作列表
$hlist = array('member_allot_record','sendmsm','sendmsg_record','smspre_list','smspre_add','smspre_edit','smspre_del','mmspre_list','mmspre_del','mmspre_edit','mmspre_add','count_num_set','set_del_user','giveup_member','member_allot_record_summary','count_after_num', 'blacklist','add_blacklist','delete_member','complaint_box','sendmsm_most','sendmail_batch','member_allotrecord_per','sendinfo','add_sendinfo','update_fastdb','text_show','text_show_list','text_show_add','text_show_dele','text_show_edit','text_show_update','sendmail_batch_2','add_rose','add_white','white');

//note 判断页面是否存在
if(!in_array($h,$hlist)){
	salert('您要打开的页面不存在');
}
//note 判断是否有权限

/*if(!checkGroup('other',$h)){
	salert('您没有修改此操作的权限');
}*/

switch($h){
	//note 红娘意见箱
	case 'complaint_box':
		other_complaint_box();
	    break;
	//note 会员分配记录
	case 'member_allot_record':
		other_member_allot_record();
	    break;
	//note 短信预设列表
	case 'smspre_list':
		other_smspre_list();	
	    break;
	//note 添加预设短信
	case 'smspre_add':
		other_smspre_add();	
	    break;
	//note 删除预设短信
	case 'smspre_del':
		other_smspre_del();	
	    break;
	//note 编辑预设短信
	case 'smspre_edit':
		other_smspre_edit();	
	    break;
	//note 放弃会员列表
	case 'giveup_member':
		other_giveup_member();	
	    break;
	//note客服删除会员去向设置
	case 'set_del_user':
		set_del_user();
	    break;
	//note统计各组设置
	case 'count_num_set':
		count_num_set();
	    break;
	//note添加售后组
	case 'count_after_num':
		count_after_num();
	    break;
	case 'mmspre_list':
		other_mmspre_list();
	    break;
	case 'mmspre_del':
		other_mmspre_del();
	    break;
	case 'mmspre_edit':
		other_mmspre_edit();
	    break;
	case 'mmspre_add':
		other_mmspre_add();	
	    break;	
	//note 会员分配记录汇总
	case 'member_allot_record_summary':
		other_member_allot_record_summary();
	    break;
	case 'sendmsm_most':
		other_sendmsm_most();
	    break;
	case 'sendmsm':

		other_sendmsm();
	    break;
	case 'sendmsg_record':
		other_sendmsg_record();
	    break;
	case 'sendmail_batch':
        other_sendmail_batch();
        break;
	case 'sendmail_batch_2':
        other_sendmail_batch_2();
        break;
	case 'update_fastdb':
		other_update_fastdb();
	    break;
	case 'member_allotrecord_per':
		other_member_allotrecord_per();
	    break;
	case 'delete_member':
		other_delete_member();
		break;
	case 'blacklist':
		other_blacklist();
		break;
	case 'add_blacklist':
		add_blacklist();
		break;
	case 'text_show':
		text_show_list();
		break;
	case 'text_show_add':
		text_show_add();
		break;
	case 'text_show_dele':
		text_show_dele();
		break;
	case 'text_show_edit':
		text_show_edit();
		break;
	case 'text_show_update':
		text_show_update();
		break;
	case 'sendinfo':
		other_sendinfo();
		break;
	case 'add_sendinfo':
		other_add_sendinfo();
		break;
	case 'add_rose':
		other_add_rose();
		break;
	case 'white':
		other_white_list();
		break;

    default:
    	exit('文件不存在');
        break;  
}