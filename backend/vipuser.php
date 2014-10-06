<?php
//note 已支付列表
function vipuser_pay(){
    global $bankarr;
    $arr_payfor = array(0 => '钻石会员',1 => '高级会员', 2 => '城市之星');
    
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 15;
    $offset = ($page-1)*$limit;
    
    $type = MooGetGPC('type','string');
    $keyword = trim(MooGetGPC('keyword','string'));
    $where = " WHERE p.staus = 1";
    if( !empty($keyword) ){
        if ($type == 'username' || $type == 'telphone'){
            $sql = "SELECT uid FROM {$GLOBALS['dbTablePre']}members_search WHERE {$type} = '{$keyword}'";
            $member = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
            $where .= " AND p.uid = {$member['uid']}";
        }else{
            $where .= " AND p.{$type} = {$keyword}";
        }
    }

    $sql = "SELECT COUNT(1) num FROM {$GLOBALS['dbTablePre']}payment AS p {$where}";
    $total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    
//    $sql = "SELECT p.*, m.username,m.nickname FROM {$GLOBALS['dbTablePre']}payment AS p LEFT JOIN {$GLOBALS['dbTablePre']}members AS m ON p.uid = m.uid {$where} ORDER BY pid DESC LIMIT {$offset},{$limit}";// original file
    $sql = "SELECT p.pid, p.uid, p.order_id, p.code, p.payfor, p.money, m.username,m.nickname FROM {$GLOBALS['dbTablePre']}payment AS p LEFT JOIN {$GLOBALS['dbTablePre']}members_search AS m ON p.uid = m.uid {$where} ORDER BY pid DESC LIMIT {$offset},{$limit}";// updated file
    $ret_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);

    $currenturl = "index.php?action=vipuser&type={$type}&keyword={$keyword}";
    $pages = multipage( $total['num'], $limit, $page, $currenturl );
    
    //note 插入日志
    serverlog(1,$GLOBALS['dbTablePre'].'payment',"{$GLOBALS['username']}查看会员升级中已支付列表", $GLOBALS['adminid']);
    require(adminTemplate('vipuser_paylist'));
}

//note 未支付列表
function vipuser_nopay(){
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 15;
    $offset = ($page-1)*$limit;
    
    $type = MooGetGPC('type','string');
    $keyword = trim(MooGetGPC('keyword','string'));
    $where = " WHERE a.staus = 0";
    if( !empty($keyword) ){
        if ($type == 'username' || $type == 'telphone'){
            $sql = "SELECT uid FROM {$GLOBALS['dbTablePre']}members_search WHERE {$type} = '{$keyword}'";
            $member = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
            $where .= " AND a.uid = {$member['uid']}";
        }else{
            $where .= " AND a.{$type} = {$keyword}";
        }
    }

    $sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}payment AS a {$where}";
    $total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    
//	$sql = "SELECT a.*, b.username, b.nickname, b.telphone FROM {$GLOBALS['dbTablePre']}payment AS a LEFT JOIN {$GLOBALS['dbTablePre']}members AS b  ON a.uid=b.uid {$where} order by a.pid DESC LIMIT {$offset},{$limit}"; // original file
    $sql = "SELECT a.uid, a.pid, a.order_id, a.money, b.username, b.nickname, b.telphone FROM {$GLOBALS['dbTablePre']}payment AS a LEFT JOIN {$GLOBALS['dbTablePre']}members_search AS b  ON a.uid=b.uid {$where} order by a.pid DESC LIMIT {$offset},{$limit}"; // updated file
	$pay = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);

    $currenturl = "index.php?action=vipuser&h=nopay";
    $pages = multipage( $total['num'], $limit, $page, $currenturl );
    //note 插入日志
    serverlog(1,$GLOBALS['dbTablePre'].'payment',"{$GLOBALS['username']}查看会员升级-未支付列表", $GLOBALS['adminid']);
    require(adminTemplate('vipuser_nopaylist'));
}

//note 线下支付
function vipuser_downline(){
    global $bankarr;

    $ispost = MooGetGPC('ispost','integer','P');
    if( $ispost ){
        $uid = MooGetGPC('uid','integer','P');
        $code = MooGetGPC('code','string','P');
        $upgrade = MooGetGPC('upgrade','string','P');
        $order_id = MooGetGPC('order_id','integer','P');
        $staus = MooGetGPC('staus','integer','P');
        $money = MooGetGPC('money','string','P');
        $validate = false;
        $tips = "";
        //验证参数
        if($money <= 0){
            $validate = true;
            $tips .= "支付金额填写有误\\n";
        }
        if($staus != 0 && $staus != 1){
            $validate = true;
            $tips .= "状态选择有误\\n";
        }
        if($uid == 0 || $order_id == 0){
            $validate = true;
            $tips .= "填写有误\\n";
        }
        $sql = "SELECT uid FROM {$GLOBALS['dbTablePre']}members_search WHERE uid={$uid}";
        $co = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
        if($co['uid'] == 0||$co==''){
            $validate = true;
            $tips .= "没有该用户";
        }
        
        if($validate) salert($tips);
        
        $sql = "INSERT INTO {$GLOBALS['dbTablePre']}payment (uid,code,order_id,staus,money,payfor) VALUES('$uid','$code','$order_id','$staus','$money','$upgrade')";
        $total = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
        
        //note 插入日志
        serverlog(3,$GLOBALS['dbTablePre'].'payment',$GLOBALS['username'].'线下支付会员ID为'.$uid, $GLOBALS['adminid']);
        
        salert("添加成功","index.php?action=vipuser&h=downline");
        
        //统计系统的接口begin
        //$uidmd5 = md5($uid.MOOPHP_AUTHKEY);
        //$apiurl = "http://mycount.7652.com/hongniang/hongniang_super_import.php?uid=".$uid."&md5=".$uidmd5;
        //@file_get_contents($apiurl);
        //统计系统的接口end
    }
    
    require(adminTemplate('vipuser_downline'));
}
//会员升级管理
function vipuser_upgrade_apply(){

    $db=$GLOBALS['_MooClass']['MooMySQL'];
    $t = MooGetGPC('t','string');
    if($t == 'remark'){
        vipuser_upgrade_remark();
        exit;
    }
    if($_POST){
        $time = time();
        $hide_reamrk = MooGetGPC('hide_reamrk','string','P');
        $data['uid'] = MooGetGPC('uid','integer','P');
        $data['order_id'] = date('YmdHms',$time).$data['uid'];
        $data['apply_time'] = strtotime(MooGetGPC('apply_time','string','P'));
//      $data['pay_type'] = MooGetGPC('pay_type','integer','P');
        $data['pay_type'] = 1; //线下
        $data['pay_bank'] = MooGetGPC('pay_bank','string','P');
        $data['pay_money'] = trim($_POST['pay_money']);
        if(!preg_match ("/^[1-9]\d{2,3}\.?\d?\d?$/", $data['pay_money'])){
            salert('请填写有效的金额如：2344.99或234');exit;
        }
        $data['pay_service'] = MooGetGPC('pay_service','string','P');
        if($data['pay_service']==6){ //铂金会员
        	$data['pay_service']=-1;
        }
        $data['give_city_star'] = MooGetGPC('give_city_star','integer','P');
        $data['pay_time'] = strtotime(MooGetGPC('pay_time','string','P'));
        $data['plus_time'] = MooGetGPC('plustime','integer','P');
        if($data['pay_service'] == 3){
            $data['old_scid'] = MooGetGPC('old_scid','integer','P');
        }
        $data['contact'] = MooGetGPC('contact','string','P');
        $data['pay_info'] = MooGetGPC('pay_info','string','P');
        $data['apply_note'] = MooGetGPC('apply_note','string','P').$hide_reamrk;
        $data['apply_sid'] = $GLOBALS['adminid'];
        if(empty($data['uid']) || empty($data['pay_type']) || $data['pay_service']=='' || empty($data['pay_money'])){
            salert('请填写完整');exit;
        }
        if($data['pay_service'] != 3 && $data['plus_time'] > 0){
            $data['plus_time'] = 0;
        }
		
		//避免前台支付会员提交，后台客服修改备注信息 产生重复记录begin
		$eid=$db->getOne("select id,apply_time,pay_time from web_payment_new where uid={$data['uid']} and pay_time>{$data['pay_time']}-28800  and status in (1,2)");
		if(!empty($eid)){
		  $data['id']=$eid['id'];
		  $data['apply_time']=$eid['apply_time'];
		  $data['pay_time']=$eid['pay_time'];
		  inserttable('payment_new',$data,0,true);
		}else{
          inserttable('payment_new',$data); //插入数据
		}
		//end
		
        $awoketime=$time+60;
        $sql="insert into {$GLOBALS['dbTablePre']}admin_remark set sid=231,send_id={$GLOBALS['adminid']},title='会员升级申请，请查账',content='{$GLOBALS['adminid']}号客服发出了会员升级申请，请查账后确认！',awoketime={$awoketime},dateline={$time}";
        $db->query($sql);
        salert('申请成功，等待申核','index.php?action=vipuser&h=upgrade_apply&t=remark&uid='.$data['uid'].'&payments='.$data['pay_money']);exit;
    }
    require(adminTemplate('vipuser_upgrade_apply'));
}
function vipuser_upgrade_remark(){
    $t = 'remark';
    $uid = MooGetGPC('uid','integer','G');
    $payments = MooGetGPC('payments','integer','G');
    if(isset($_POST['ispost'])?$_POST['ispost']:''){
        $time = time();
        $data['sid'] = $GLOBALS['adminid'];
        $data['uid'] = MooGetGPC('uid','integer','P');
        $data['servicetime'] = MooGetGPC('servicetime','integer','P');
        $data['payments'] = MooGetGPC('payments','integer','P');
//      $data['pay_type'] = MooGetGPC('pay_type','integer','P');
        $data['otheruid'] = MooGetGPC('otheruid','integer','P');
        $data['chatnotes'] = MooGetGPC('chatnotes','string','P');
        $data['intro'] = MooGetGPC('intro','string','P');
        $data['otherintro'] = MooGetGPC('otherintro','string','P');
        $data['lastcom'] = MooGetGPC('lastcom','string','P');
        $data['remark'] = MooGetGPC('remark','string','P');
        $data['dateline'] = $time;
		
        inserttable('members_transfer',$data);
        salert('交接信息填写成功！','index.php?action=vipuser&h=upgrade_apply&t=remark');exit;
    }
    require(adminTemplate('vipuser_upgrade_apply'));
}
//升级支付列表
function vipuser_apply_list(){
    global $bankarr,$tel_bankarr;
    $check_order = false;
    if(in_array($GLOBALS['groupid'],$GLOBALS['admin_vip_level_check'])){//会员升级帐目核对权限(常总21)
        $check_order = true;
    }
    $page_per = 20;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
    
	// $where = " ";
	
    $status = $check_order_sid = 0;
    if(isset($_GET['status'])){
        $status = MooGetGPC('status','integer','G');
    }
    
    
	$check_order_sid=MooGetGPC('check_order_sid','integer','G');
    
	if($check_order_sid == 1){
		$where = " status > '0'";
	}else{
		$where = " status='0' and pay_type=1";
	}
    
	$apply_date1=MooGetGPC('apply_time1','string','G');
    if(!empty($apply_date1)){
        $apply_time1 =  strtotime($apply_date1);
        $condition[] = " apply_time>='{$apply_time1}'";
    }
    
	$apply_date2=MooGetGPC('apply_time2','string','G');
    if(!empty($apply_date2)){
        $apply_time2 = strtotime($apply_date2);
        $condition[] = " apply_time<='{$apply_time2}'";
    }
    
    if(!empty($_GET['apply_sid'])){
        $apply_sid = MooGetGPC('apply_sid','integer','G');
        $condition[] = " apply_sid='{$apply_sid}'";
    }
	
	if(!empty($_GET['group'])){
        $id = MooGetGPC('group','integer','G');
        $group = get_group_type($id);
        $manage_list = $group[0]['manage_list'];
		if (empty($apply_sid))  $condition[] = " apply_sid IN($manage_list)";
    }
	
    if(!empty($_GET['uid'])){
        $uid = MooGetGPC('uid','integer','G');
        $condition[] = " uid='{$uid}'";
    }
    
    if(!$check_order){
        $condition[] = " status > 0";
        //$condition[] = " status in(1,3)";
    }
    
    if(empty($condition)){
        if(isset($_GET['status'])){
            $condition[] = " status='{$status}'";
        }else{
            $condition[] = $where;
        }
    }
    $where = ' WHERE '.implode('AND',$condition);
	


    $total = getcount('payment_new',$where);
	

    $sql = "SELECT apply_sid, apply_time, uid, pay_service, plus_time, give_city_star, pay_money, pay_time, pay_type, pay_bank, check_order_sid, check_sid, check_time, contact, pay_info, apply_note, status, id FROM {$GLOBALS['dbTablePre']}payment_new $where ORDER BY id DESC LIMIT {$offset},{$limit}"; // updated file
    $payment_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    
	
	$currenturl="index.php?action=vipuser&h=apply_list&check_order_sid=$check_order_sid&group=$id&apply_sid=$apply_sid&apply_time1=$apply_date1&apply_time2=$apply_date2";
	
    $page_links = multipage( $total, $page_per, $page, $currenturl );   
    
    $group_list = get_group_type();

    require(adminTemplate('vipuser_apply_list'));
}

//补款预付
function vipuser_pay_other(){
	global $bankarr,$kefu_arr,$tel_bankarr;
	
	$url='';
	$condition=array();
	$where ='';

	
    $page_per = 20;
	
    if(MooGetGPC('page','integer','R')){
		$page = MooGetGPC('page','integer','R');
		$url .= '&page='.$page;
	}else{
		$page = '1';
		$url .= '&page=1';
	}
    $limit = 20;
    $offset = ($page-1)*$limit;
    
	
    $condition[] = " status=1 ";
    
    
	
	$apply_time1=MooGetGPC('apply_time1','string','P');
	if(!empty($apply_time1)){
	  $startTime =  strtotime($apply_time1);
	  $condition[] = " apply_time>='{$startTime}' ";
	  $url .= '&apply_time1='.$apply_time1;
    }
	
    $apply_time2=MooGetGPC('apply_time2','string','P');
	if(!empty($apply_time2)){
	  $endTime=strtotime($apply_time2);
	  $condition[] = " apply_time<'{$endTime}' ";
	  $url .='$apply_time2='.$apply_time2;
	}
 
    
	$sid=$workgroup=$manage_list=$groupid='';
	$sid=MooGetGPC('sid','integer','P');
	$groupid=MooGetGPC('group','integer','P');

    if($sid){
        $condition[] = " apply_sid='{$sid}'";
		$url .= '&sid='.$sid;
    }elseif($groupid){
        $workgroup = get_group_type($groupid);
        $manage_list = $workgroup[0]['manage_list'];
        $condition[] = " apply_sid IN($manage_list)";
		$url .= '&group='.$groupid;
    }

	
	$uid='';
	$uid=MooGetGPC('uid','integer','P');
    if($uid){
        $condition[] = " uid='{$uid}'";
		$url .= '&uid='.$uid;
    }
    
    
    if (!empty($condition)){
      $where = ' WHERE '.implode('AND',$condition);
    }
    $total = getcount('payment_other',$where);
    
    $sql = "SELECT apply_sid, apply_time, uid, pay_service, plus_time, give_city_star, pay_money, pay_time, pay_type, pay_bank, check_order_sid, check_sid, check_time, contact, pay_info, apply_note, status, id,note FROM {$GLOBALS['dbTablePre']}payment_other $where ORDER BY id DESC LIMIT {$offset},{$limit}"; // updated file
   
 
    $payment_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
   
    //note 获得当前的url 去除多余的参数page=
     $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"].$url; 
    //$currenturl = preg_replace("/(&page=\d+)/","",$currenturl);
    $url2="apply_time1={$apply_time1}&apply_time2={$apply_time2}&sid={$apply_sid}&uid={$uid}&group={$_GET['group']}";
	$currenturl="index.php?action=vipuser&h=pay_other&".$url2;

    $page_links = multipage( $total, $page_per, $page, $currenturl );   
    
    $group_list = get_group_type();
	require(adminTemplate('vipuser_pay_other'));
}
//组内会员升级列表
function vipuser_getvip_group(){
	
    $groupid=MooGetGPC('groupid','integer','G');
    if($groupid){
        $sql="SELECT manage_list FROM {$GLOBALS['dbTablePre']}admin_manage where id={$groupid}";
        $sid_arr=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
        $sid_list=$sid_arr['manage_list'];
    }else{
    	
        $sid_list = get_myservice_idlist();     
    }   
    $pay_service=MooGetGPC('pay_service','integer','G');
    $count = array();
    $where = $note = '';
    
    if(!empty($_GET['start_time']) || !empty($_GET['end_time'])){
        $start_time = strtotime(trim(MooGetGPC('start_time','string','G')));
        $end_time = strtotime(trim(MooGetGPC('end_time','string','G'))) + 86400;
        if($start_time){
            $where .= " AND `apply_time` > $start_time ";
        }
        if($end_time){
            if($start_time && $end_time <= $start_time){
                $end_time = $start_time + 86400;
                $note = '由于结束时间不能小于开始时间，系统自动将结束时间更改为：'.date('Y-m-d',$end_time);
            }
            $where .= " AND `apply_time` <= $end_time ";
        }
    }
    if(isset($_GET['pay_service'])){        
        $where .= " AND pay_service={$pay_service}";
    }
    if(!empty($sid_list)){
    $where .=" AND apply_sid in (".$sid_list.")";
    }
    //总体
    $sql = "SELECT COUNT(*) AS c,SUM(`pay_money`) AS qian,`pay_service` FROM {$GLOBALS['dbTablePre']}payment_new WHERE `status` IN(1,3) {$where} GROUP BY `pay_service`";
    $totalCount = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    //每个客服
    $sql = "SELECT sum(`pay_money`) AS total,COUNT(*) AS num ,`apply_sid` FROM {$GLOBALS['dbTablePre']}payment_new WHERE `status` IN(1,3) {$where} GROUP BY `apply_sid`";
    $everyCount = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    
    require(adminTemplate('vipuser_summary_group'));
}
//会员升级列表的汇总信息 return $array
function getvipSummary(){
	
    if(!in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr'])){
    	
        vipuser_getvip_group();
        return;
    }
    
    $pay_service=MooGetGPC('pay_service','integer','G');
    $count = array();
    $where = $note = '';
    
    if(isset($_GET['start_time']) || isset($_GET['end_time'])){
        $start_time = strtotime(trim(MooGetGPC('start_time','string','G')));
        $end_time = strtotime(trim(MooGetGPC('end_time','string','G'))) + 86400;
        if($start_time){
            $where .= " AND `apply_time` > $start_time ";
        }
        if($end_time){
            if($start_time && $end_time <= $start_time){
                $end_time = $start_time + 86400;
                $note = '由于结束时间不能小于开始时间，系统自动将结束时间更改为：'.date('Y-m-d',$end_time);
            }
            $where .= " AND `apply_time` <= $end_time ";
        }
    }
    if(isset($_GET['pay_service'])){
        $where.= " AND pay_service={$pay_service}";
    }
    //总体
    
    $sql = "SELECT COUNT(*) AS c,SUM(`pay_money`) AS qian,`pay_service` FROM {$GLOBALS['dbTablePre']}payment_new WHERE `status` IN(1,3) {$where} GROUP BY `pay_service`";
    $totalCount = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    //每个客服
    $sql = "SELECT sum(`pay_money`) AS total,COUNT(*) AS num ,`apply_sid` FROM {$GLOBALS['dbTablePre']}payment_new WHERE `status` IN(1,3) {$where} GROUP BY `apply_sid`";
    $everyCount = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    //每个组的sid = manage_list
    $sql = "SELECT `id`,`manage_name`,`manage_list` FROM `web_admin_manage` WHERE `type`='1'";
    $manage = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    //每个组
    
    foreach($manage as $m){
        if(empty($m['manage_list'])) continue;
        
        $sql = "SELECT sum(`pay_money`) AS total,COUNT(*) AS num FROM {$GLOBALS['dbTablePre']}payment_new WHERE `status` IN(1,3) AND `apply_sid` IN ({$m['manage_list']}) {$where}";
        $groupCount[$m['manage_name']] = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
        $groupCount[$m['manage_name']]['id']=$m['id'];
    }
    //print_r($groupCount);
    require(adminTemplate('vipuser_summary'));
}

//高级会员
function vipuser_high(){
    $page_per = 20;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
    $timenow=time();
    $type = MooGetGPC('type','string','G');
    $keyword = trim(MooGetGPC('keyword','string','G'));
    $bgtime = strtotime(MooGetGPC('bgtime','string','G'));
    $endtime = strtotime(MooGetGPC('endtime','string','G'));
    if(!empty($type) && in_array($type,array('telphone','uid','username')) && !empty($keyword)){
        $condition[] = " $type like '%{$keyword}%'";
    }
    if($bgtime) $condition[] = " bgtime>='{$bgtime}'";
    if($endtime) $condition[] = " endtime<='{$endtime}'";

      

    if(isset($_GET['afterday'])){
        $afterday = MooGetGPC('afterday','string','G');
        $time2 = strtotime("$afterday")-1;
        $time1 = $time2-86399;
        $condition[] = " endtime>='$time1'";
        $condition[] = " endtime<='$time2'";
    }

    if(empty($condition)){
        //$c = '';
        $c = 'AND endtime<'.$timenow;
    }else{
        $c = ' AND ' . implode(' AND ',$condition);
    }
    
    $where = ' WHERE s_cid = 30  AND usertype !=3 '.$c;
    
    $total = getcount('members_search',$where);
    $sql = "SELECT uid,username,s_cid,nickname,bgtime,endtime FROM {$GLOBALS['dbTablePre']}members_search {$where}  ORDER BY endtime ASC LIMIT {$offset},{$limit}";

    $user = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    $url2="afterday={$_GET['afterday']}&bgtime={$bgtime}&endtime={$endtime}&type={$type}&renew={$_GET[renew]}&keyword={$keyword}";
	$currenturl="index.php?action=vipuser&h=high&".$url2;

    $pages = multipage( $total, $limit, $page, $currenturl );
    
    //查询续费日期
    $current_month = date('n');
    $current_day = '7';
    serverlog(1,$GLOBALS['dbTablePre']."members_search","{$GLOBALS['username']}查看已到期的高级会员列表",$GLOBALS['adminid']);
    require(adminTemplate('vipuser_high'));
}

function vipuser_viped(){
    global $timestamp;
    $page_per = 20;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
    
    $type = MooGetGPC('type','string','G');
    $keyword = trim(MooGetGPC('keyword','string','G'));
    $bgtime = strtotime(MooGetGPC('bgtime','string','G'));
    $endtime = strtotime(MooGetGPC('endtime','string','G'));
    if(in_array($type,array('telphone','uid','username')) && !empty($keyword)){
        $condition[] = " $type like '%{$keyword}%'";
    }
    if($bgtime) $condition[] = " bgtime>='{$bgtime}'";
    if($endtime){
        
        $condition[] = $endtime < $timestamp ? " endtime<='{$endtime}'" : " endtime<'{$timestamp}'" ;
    
    }

    if(isset($_GET['afterday'])){
        $afterday = MooGetGPC('afterday','string','G');
        $time2 = strtotime("$afterday")-1;
        $time1 = $time2-86399;
        $condition[] = " endtime>='$time1'";
        $condition[] = " endtime<='$time2'";
    }

    if(empty($condition)){
        $c = " AND `endtime` < '".$timestamp ."' ";
    }else{
        $c = ' AND ' . implode(' AND ',$condition);
    }
    $where = " WHERE s_cid != 40 and s_cid !=50 AND `usertype` = '1' " . $c;
    
    $total = getcount('members_search',$where);
    $sql = "SELECT uid,username,s_cid,nickname,bgtime,endtime FROM {$GLOBALS['dbTablePre']}members_search {$where} ORDER BY endtime ASC LIMIT {$offset},{$limit}";
  
    $user = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    
	$currenturl="index.php?action=vipuser&h=viped";

    $pages = multipage( $total, $limit, $page, $currenturl );
    
    
    $current_month = date('n');
    $current_day = '7';
  
    serverlog(1,$GLOBALS['dbTablePre']."members_search","{$GLOBALS['username']}查看已到期的高级会员列表",$GLOBALS['adminid']);
    require(adminTemplate('vipuser_viped'));
}

//钻石会员
function vipuser_diamond(){
    $page_per = 20;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
    $timenow=time();

    $type = MooGetGPC('type','string','G');
    $keyword = trim(MooGetGPC('keyword','string','G'));
    $bgtime = strtotime(MooGetGPC('bgtime','string','G'));
    $endtime = strtotime(MooGetGPC('endtime','string','G'));
      
    if(!empty($type) && in_array($type,array('telphone','uid','username')) && !empty($keyword)){
        $condition[] = " $type like '%{$keyword}%'";
    }
    if($bgtime) $condition[] = " bgtime>='{$bgtime}'";
    if($endtime) $condition[] = " endtime<='{$endtime}'";
    
    //查询续费日期
    $current_month = date('n');
    $current_day = '7';

    if(isset($_GET['afterday'])){
        $afterday = MooGetGPC('afterday','string','G');
        $time2 = strtotime("$afterday")-1;
        $time1 = $time2-86399;
        $condition[] = " endtime>='$time1'";
        $condition[] = " endtime<='$time2'";
    }

    if(empty($condition)){
        //$c = ''; enky note
        $c = 'AND endtime<'.$timenow;
    }else{
        $c = ' AND ' . implode(' AND ',$condition);
    }
    //$where = ' WHERE s_cid = 20 AND endtime<'.$timenow.' '. $c;  enky note
    $where = ' WHERE s_cid = 20  '. $c;
    $total = getcount('members_search',$where);
    $sql = "SELECT uid,username,s_cid,nickname,bgtime,endtime FROM {$GLOBALS['dbTablePre']}members_search {$where} ORDER BY endtime ASC LIMIT {$offset},{$limit}";

    $user = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);

	$currenturl="index.php?action=vipuser&h=diamond";
  
    $pages = multipage( $total, $limit, $page, $currenturl );
    
    serverlog(1,$GLOBALS['dbTablePre']."members_search","{$GLOBALS['username']}查看已到期的钻石会员列表",$GLOBALS['adminid']);
    require(adminTemplate('vipuser_diamond'));
}

//城市之星
function vipuser_city_star(){
    $page_per = 20;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
    
    $type = MooGetGPC('type','string','G');
    $keyword = trim(MooGetGPC('keyword','string','G'));
    $bgtime = strtotime(MooGetGPC('bgtime','string','G'));
    $endtime = strtotime(MooGetGPC('endtime','string','G'));
      
    if(!empty($type) && in_array($type,array('telphone','uid','username')) && !empty($keyword)){
        $condition[] = " $type like '%{$keyword}%'";
    }
    if($bgtime) $condition[] = " bgtime>='{$bgtime}'";
    if($endtime) $condition[] = " endtime<='{$endtime}'";
    
    //查询续费日期
    $current_month = date('n');
    $current_day = '7';

    if(isset($_GET['afterday'])){
        $afterday = MooGetGPC('afterday','string','G');
        $time2 = strtotime("$afterday")-1;
        $time1 = $time2-86399;
        $condition[] = " endtime>='$time1'";
        $condition[] = " endtime<='$time2'";
    }

    if(empty($condition)){
        $c = '';
    }else{
        $c = ' AND ' . implode(' AND ',$condition);
    }
    $where = ' WHERE city_star > 0 ' . $c;
    //echo $where;
    $total = getcount('members_search',$where);
    $sql = "SELECT uid,username,s_cid,nickname,bgtime,endtime FROM {$GLOBALS['dbTablePre']}members_search {$where} ORDER BY endtime ASC LIMIT {$offset},{$limit}";
    $user = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);

    //note 获得当前的url 去除多余的参数page=
    //$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
    //$currenturl = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl="index.php?action=vipuser&h=city_star";
    
    $pages = multipage( $total, $limit, $page, $currenturl );
    
    serverlog(1,$GLOBALS['dbTablePre']."members_search","{$GLOBALS['username']}查看已到期的高级会员列表",$GLOBALS['adminid']);
    require(adminTemplate('vipuser_city_star'));
}


//note 即将到期的高级会员
function vipuser_hurryhigh(){
    global $h,$renewals_status_grade,$renewalslink;
    $membersexpired=$afterday = '';
    
    $page_per = 20;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
    $type = MooGetGPC('type','string','G');
    $keyword = trim(MooGetGPC('keyword','string','G'));
    $bgtime = strtotime(MooGetGPC('bgtime','string','G'));
    $endtime = strtotime(MooGetGPC('endtime','string','G'));
    $expired = MooGetGPC('expired','integer','G');
    $time=time();
    if(!empty($type) && in_array($type,array('telphone','uid','username')) && !empty($keyword)){
        $condition[] = " $type like '%{$keyword}%'";
    }
    if($bgtime) $condition[] = " m.bgtime>='{$bgtime}'";
    if($endtime) $condition[] = " m.endtime<='{$endtime}'";

    if(isset($_GET['afterday'])){
        $afterday = MooGetGPC('afterday','string','G');
        $time2 = strtotime("$afterday")-1;
        $time1 = $time2-86399;
        $condition[] = " m.endtime>='$time1'";
        $condition[] = " m.endtime<='$time2'";
    }
    if(isset($_GET['membersexpired'])){
        $membersexpired =  trim(MooGetGPC('membersexpired','integer','G'));
        $condition[] = " m.endtime>0";
        $condition[] = " m.endtime<".$time;
    }
    if(isset($_GET['renewals'])){
        $renewals = MooGetGPC('renewals','integer','G');
        $condition[] = " a.renewalstatus=".$renewals;
    }

    if(empty($condition)){
        $c = '';
    }else{
        $c = ' AND ' . implode(' AND ',$condition);
    }
    
    $adminid = $GLOBALS['adminid'];
    $manager = $GLOBALS['username'];

    $sql_admin = "SELECT groupid FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$adminid}'";
    $res_admin = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_admin,true);
    $where =  'WHERE'; 
    if(in_array($res_admin['groupid'],$GLOBALS['general_service'])){
        $where .= ' m.sid='.$adminid.' AND m.s_cid=30 AND m.usertype=1'.$c;
    }else{
        $where .= ' m.s_cid=30 AND m.usertype=1'.$c;
    }

    $resto = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT count(*) count FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}member_admininfo a on m.uid=a.uid {$where}",true);
    $total = $resto['count'];

    $sql = "SELECT m.uid,m.username,m.s_cid,m.gender,m.nickname,m.bgtime,m.endtime,a.renewalstatus FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}member_admininfo a on m.uid=a.uid {$where}  ORDER BY m.endtime ASC LIMIT {$offset},{$limit}";
//  echo $sql;die;
    $user = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);

    $url2="afterday={$_GET['afterday']}&bgtime={$bgtime}&endtime={$endtime}&type={$type}&renew={$_GET[renew]}&keyword={$keyword}&membersexpired={$_GET[membersexpired]}&renewals={$_GET[renewals]}";
    
  
	$currenturl="index.php?action=vipuser&h=hurryhigh&".$url2;

    $pages = multipage( $total, $limit, $page, $currenturl );
    
    //查询续费日期
    $current_month = date('n');
    $current_day = '7';

    serverlog(1,$GLOBALS['dbTablePre']."members_search","{$GLOBALS['username']}查看已到期的高级会员列表",$GLOBALS['adminid']);
    require(adminTemplate('vipuser_hurrymembers'));
}

//note 即将到期的钻石会员
function vipuser_hurrydiamond(){
    global $h,$renewals_status_grade,$renewalslink;
    $membersexpired=$afterday='';
    $page_per = 20;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
    
    $type = MooGetGPC('type','string','G');
    $keyword = trim(MooGetGPC('keyword','string','G'));
    $bgtime = strtotime(MooGetGPC('bgtime','string','G'));
    $endtime = strtotime(MooGetGPC('endtime','string','G'));
    $expired = MooGetGPC('expired','integer','G');
//  echo $expired;die;
    $time=time();
    if(!empty($type) && in_array($type,array('telphone','uid','username')) && !empty($keyword)){
        $condition[] = " $type like '%{$keyword}%'";
    }
    if($bgtime) $condition[] = " bgtime>='{$bgtime}'";
    if($endtime) $condition[] = " endtime<='{$endtime}'";
    
    //查询续费日期
    $current_month = date('n');
    $current_day = '7';

    if(isset($_GET['afterday'])){
        $afterday = MooGetGPC('afterday','string','G');
        $time2 = strtotime("$afterday")-1;
        $time1 = $time2-86399;
        $condition[] = " m.endtime>='$time1'";
        $condition[] = " m.endtime<='$time2'";
    }
    if((isset($_GET['membersexpired'])?$_GET['membersexpired']:'')=='1'){
        $membersexpired = MooGetGPC('membersexpired','string','G');
        $condition[] = " m.endtime>0";
        $condition[] = " m.endtime<".$time;
    }
    if(isset($_GET['renewals'])){
        $renewals = MooGetGPC('renewals','integer','G');
        $condition[] = " a.renewalstatus=".$renewals;
    }

    if(empty($condition)){
        $c = '';
    }else{
        $c = ' AND ' . implode(' AND ',$condition);
    }
    
    $adminid = $GLOBALS['adminid'];
    $manager = $GLOBALS['username'];
    
    $sql_admin = "SELECT groupid FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$adminid}'";
    $res_admin = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_admin,true);
    $where =  'WHERE'; 
    if(in_array($res_admin['groupid'],$GLOBALS['general_service'])){
        $where .= ' m.sid='.$adminid.' AND m.s_cid=20 AND m.usertype=1'.$c;
    }else{
        $where .= ' m.s_cid=20 AND m.usertype=1'.$c;
    }
    
//    $total = getcount('members m',$where);
    $resto = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT count(*) count FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}member_admininfo a on m.uid=a.uid {$where}",true);
    $total = $resto['count'];

    $sql = "SELECT m.uid,m.username,m.s_cid,m.gender,m.nickname,m.bgtime,m.endtime,a.renewalstatus FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}member_admininfo a on m.uid=a.uid {$where}  ORDER BY m.endtime ASC LIMIT {$offset},{$limit}";
    $user = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);

    
    $url2="afterday={$_GET['afterday']}&bgtime={$bgtime}&endtime={$endtime}&type={$type}&renewals={$_GET[renewals]}&keyword={$keyword}&membersexpired={$_GET[membersexpired]}";
    
    
	$currenturl="index.php?action=vipuser&h=hurrydiamond&".$url2;
    
    $pages = multipage( $total, $limit, $page, $currenturl );
    
    serverlog(1,$GLOBALS['dbTablePre']."members_search","{$GLOBALS['username']}查看已到期的钻石会员列表",$GLOBALS['adminid']);
    require(adminTemplate('vipuser_hurrymembers'));
}

function vipuser_pay_query(){
    global $bankarr,$kefu_arr,$tel_bankarr;
	
	$url='';
	$condition=array();
	$where ='';

	
    $page_per = 20;
	
    if(MooGetGPC('page','integer','G')){
		$page = MooGetGPC('page','integer','G');
		$url .= '&page='.$page;
	}else{
		$page = '1';
		$url .= '&page=1';
	}
    $limit = 20;
    $offset = ($page-1)*$limit;
    
	
    $condition[] = " pn.status=3 ";
    
    
	
	$apply_time1=MooGetGPC('apply_time1','string','R');
	if(!empty($apply_time1)){
	  $startTime =  strtotime($apply_time1);
	  $condition[] = " pn.apply_time>='{$startTime}' ";
	  $url .= '&apply_time1='.$apply_time1;
    }
	
    $apply_time2=MooGetGPC('apply_time2','string','R');
	if(!empty($apply_time2)){
	  $endTime=strtotime($apply_time2);
	  $condition[] = " pn.apply_time<'{$endTime}' ";
	  $url .='$apply_time2='.$apply_time2;
	}
 
    $sid=MooGetGPC('sid','integer','G');
    if($sid){
        $condition[] = " pn.apply_sid='{$sid}'";
		$url .= '&sid='.$sid;
	}
	
	$uid='';
	$uid=MooGetGPC('uid','integer','R');
    if($uid){
        $condition[] = " pn.uid='{$uid}'";
		$url .= '&uid='.$uid;
    }
	
	$source=urlencode(MooGetGPC('source','string','G'));
	if($source){
	    $condition[] = " instr(mb.source,'{$source}')>0";
		$url .= '&source='.$source;
	}
    
    
    if (!empty($condition)){
      $where = ' WHERE '.implode('AND',$condition);
    }
  
    $sql="SELECT count(pn.id) as total FROM web_payment_new pn left join web_members_base mb on pn.uid=mb.uid left join web_members_search ms on pn.uid=ms.uid $where LIMIT 1";

	$result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    $total=isset($result['total'])?$result['total']:0;
    
    $sql = "SELECT pn.apply_sid, pn.apply_time, pn.uid, pn.pay_service, pn.plus_time, pn.give_city_star, pn.pay_money, pn.pay_time, pn.pay_type, pn.pay_bank, pn.check_order_sid, pn.check_sid, pn.check_time, pn.contact, pn.pay_info, pn.apply_note, pn.status, pn.id,mb.source,ms.gender,mb.birth FROM web_payment_new  pn left join web_members_base mb on pn.uid=mb.uid  left join web_members_search ms on pn.uid=ms.uid $where ORDER BY pn.id DESC LIMIT {$offset},{$limit}"; // updated file

    $payment_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	

    $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"].$url; 
    $url2="apply_time1={$apply_time1}&apply_time2={$apply_time2}&sid={$apply_sid}&uid={$uid}&source={$source}";
	$currenturl="index.php?action=vipuser&h=pay_query&".$url2;

    $page_links = multipage( $total, $page_per, $page, $currenturl );   

	require(adminTemplate('vipuser_pay_query'));
}

/***********************************************控制层(C)*****************************************/

$h=MooGetGPC('h','string','G')=='' ? 'pay' : MooGetGPC('h','string','G');
//note 动作列表
$hlist = array('pay','nopay','downline','upgrade_apply','pay_other','apply_list','high','diamond','city_star','vip_summary','viped','hurryhigh','hurrydiamond','getvip_group','pay_query');
//note 判断页面是否存在

if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=vipuser');
}

//note 判断是否有权限
if(!checkGroup('vipuser',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?action=vipuser');
}

switch($h){
    case 'pay': //note 已支付列表
        vipuser_pay();
        break;
   
    case 'nopay': //note 未支付列表
        vipuser_nopay();
        break;
    
    case 'downline'://note 线下支付
        vipuser_downline();
        break;
    
    case 'upgrade_apply'://note 会员升级管理
        vipuser_upgrade_apply();
		break;
    
    case 'apply_list'://note 升级会付列表
        vipuser_apply_list();
		break;
    
    case 'viped'://note 曾经是高级会员或钻石会员（等待续费的）
        vipuser_viped();
		break;
    
    case 'high'://note 到期的高级会员
        vipuser_high();
		break;
    case 'diamond':
        vipuser_diamond();
		break;
    case 'city_star':
        vipuser_city_star();
		break;
    case 'vip_summary':
        getvipSummary();
        break;
    
    case 'hurryhigh'://note 即将到期高级会员
        vipuser_hurryhigh();
        break;
    
    case 'hurrydiamond'://note 即将到期钻石会员
        vipuser_hurrydiamond();
        break;
	
	case 'pay_other'://补款预付
        vipuser_pay_other();
        break;
    case 'getvip_group':
        vipuser_getvip_group();
		break;
	case 'pay_query': //支付会员来源
	    vipuser_pay_query();
		break;
}
?>
