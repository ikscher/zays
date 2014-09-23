<?php
ini_set('error_log','error_log.log');
//error_log('vpuser_ajax.php---------------');

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间

//note 加载框架配置参数
require './include/config.php';
//note 加载MooPHP框架
require '../framwork/MooPHP.php';
//note 包含共公方法
include './include/function.php';
include './include/ajax_function.php';


//note 会员升级
function vipuser_upgrade(){
    $pid = MooGetGPC('pid','string','G');
    $uid = MooGetGPC('uid','string','G');
    
    $sql = "SELECT uid,username , nickname, gender, birthyear, s_cid, bgtime, endtime,telphone FROM {$GLOBALS['dbTablePre']}members_search WHERE uid = '{$uid}'";
    $user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    if(empty($user)){
        salert('无此会员,请确认');exit;
    }   
    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}payment WHERE pid = '$pid' AND uid = '$uid' LIMIT 1";
    $pay = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    if( empty($pay) || $pay['staus'] != 1 ){
        echo '操作失败!订单不存在或已经处理过了.';
        exit();
    }
    $bgtime=time();
    if( $pay['payfor'] == -1 ){ // 升级为钻石会员
        if( $user['s_cid'] == 10 && $user['endtime'] > $bgtime ){
            $bgtime  = $user['bgtime'];
            $endtime = strtotime( "+6 month", $user['endtime'] );
        }else{
            $endtime = strtotime( "+6 month" );
        }
        $s_cid = 10; 
        $content = "ID：{$user['uid']}的会员，您好，恭喜您已成功升级为铂金会员。";
    }elseif( $pay['payfor'] == 0 ){ // 升级为钻石会员
        if( $user['s_cid'] == 20 && $user['endtime'] > $bgtime ){
            $bgtime  = $user['bgtime'];
            $endtime = strtotime( "+6 month", $user['endtime'] );
        }else{
            $endtime = strtotime( "+6 month" );
        }
        $s_cid = 20; 
        $content = "ID：{$user['uid']}的会员，您好，恭喜您已成功升级为钻石会员。";
    }elseif( $pay['payfor'] == 1 ){ //高级会员
        $endtime = strtotime("+1 month");
        $s_cid = 30;
        $content = "ID：{$user['uid']}的会员，您好，恭喜您已成功升级为高级会员。";
    }elseif($pay['payfor'] == 2){ //城市之星
        $endtime = strtotime("+1 month");
        $content = "ID：{$user['uid']}的会员，您好，恭喜您已成功升级为城市之星。";

    }elseif($pay['payfor'] == 5){
        $endtime = strtotime("+3 month",$user['endtime']);
        $content = "ID：{$user['uid']}的会员，您好，恭喜您已成功升级为钻石会员。";
    }else{
        echo "操作失败!payment表pid={$pid} 记录的 字段 payfor = {$pay['payfor']} 数据错误,请联系管理员";
        exit();
    }
    
    //修改 members 表
    if($pay['payfor'] == 2){//城市之星
        $time = time();
        $sql=" update {$GLOBALS['dbTablePre']}members_search set bgtime='{$bgtime}',endtime='{$endtime}',city_star='{$time}' where uid=$uid";
        $value['city_star']=$time;
        $str_arr = array($uid=>array($bgtime,$endtime,$time));
        $field = array('bgtime','endtime','city_star');
    }elseif($pay['payfor'] == 5){ // 高级会员升钻石
        $sql = "update {$GLOBALS['dbTablePre']}members_search s left join {$GLOBALS['dbTablePre']}members_base b on s.uid=b.uid set s.s_cid='20',b.grade=5,s.bgtime='$bgtime',s.endtime='$endtime' where s.uid = $uid";
        $value['s_cid']=20;
        $value['grade']=5;
    	$str_arr = array($uid=>array(20,$bgtime,$endtime));
    	$field = array('s_cid','bgtime','endtime');
    }else{ //高级会员或钻石会员或铂金
    	if($pay['payfor']==-1){
    		$pay['payfor'] = 10;
    	}elseif ($pay['payfor'] == 0){
    		$pay['payfor'] = 20;
    	}elseif($pay['payfor'] == 1){
    		$pay['payfor'] = 30;
    	}
        $sql=" update {$GLOBALS['dbTablePre']}members_search s left join {$GLOBALS['dbTablePre']}members_base b on s.uid=b.uid set s.s_cid={$pay['payfor']},b.grade=5,s.bgtime='$bgtime',s.endtime='$endtime' where s.uid=$uid";
        $value['s_cid']=$pay['payfor'];
        $value['grade']=5;
        $str_arr = array($uid=>array($pay['payfor'],$bgtime,$endtime));
        $field = array(array('s_cid','bgtime','endtime'));
    }
    searchApi('members_man members_women')->updateAttr($field,$str_arr);
    if(MOOPHP_ALLOW_FASTDB){
        $value['bgtime']=$bgtime;
        $value['endtime']=$endtime;     
        MooFastdbUpdate('members_search','uid',$uid,$value);
        }
    
    if(!$GLOBALS['_MooClass']['MooMySQL']->query($sql)){
        echo "操作失败!无法修改用户信息.请联系管理员...";
        exit();
    }
    //短信提醒
	//$content .='【真爱一生网】';
    if(!empty($content) && !empty($user['telphone'])){
        if(SendMsg($user['telphone'],$content)){
            $GLOBALS['_MooClass']['MooMySQL']->query("INSERT INTO {$GLOBALS['dbTablePre']}smslog(sid,uid,content,sendtime) VALUES('{$GLOBALS['adminid']}','$uid','$content','{$GLOBALS['timestamp']}')");
        }
    }
    
    //if(MOOPHP_ALLOW_FASTDB){
        //MooFastdbUpdate('members','uid',$uid,'');
    //}
    serverlog(3,$GLOBALS['dbTablePre']."members_search","{$GLOBALS['username']}改变用户{$uid}的等级",$GLOBALS['adminid'],$uid);

    //修改支付信息
    $sql="update {$GLOBALS['dbTablePre']}payment set staus=2 where pid='$pid'";
    if (!$GLOBALS['_MooClass']['MooMySQL']->query($sql)){
        echo "操作失败!无法修改订单状态.请联系管理员...";
        exit();
    }
    serverlog(3,$GLOBALS['dbTablePre']."payment","{$GLOBALS['username']}改变{$uid}支付状态",$GLOBALS['adminid']);
    
    //插入数据 web_diamond 表(钻石会员)
    if( $s_cid == 20){//d. = m.nickname,d. = m.gender,d. = m.birthyear,d.=m.bgtime,d.=m.endtime
        if( $user['endtime'] > $bgtime ){ //钻石会员续费
            $status = 1;
        }else{
            $status = 1;
        }
        $sql = "REPLACE INTO {$GLOBALS['dbTablePre']}diamond (uid ,username ,skin ,status, nickname, gender, birthyear,bgtime,endtime)
                VALUES ( '{$uid}', '{$user['username']}', '".($user['gender']==0?'cyan':'red')."', '{$status}', '{$user['nickname']}','{$user['gender']}','{$user['birthyear']}','$bgtime','$endtime')";        
        if (!$GLOBALS['_MooClass']['MooMySQL']->query($sql)){
            echo "操作失败!无法添加钻石会员数据.请联系管理员...";
            exit();
        }
    }
    
    //统计系统的接口begin
    //$payKey = 'geoinbvcp';//单独接口验证
    $uidmd5 = md5($uid.MOOPHP_AUTHKEY);
    //$uidmd5 = md5($uid.$payKey);
    $apiurl = "http://tg.zhenaiyisheng.cc/hongniang/hongniang_super_import.php?uid=".$uid."&md5=".$uidmd5;
    @file_get_contents($apiurl);
    //统计系统的接口end
    //htotal
	//@file_get_contents("http://222.73.4.240/tj/index/reg?act=pay&w=1&u=".$uid);
    $sql="INSERT INTO web_test VALUES('pay1',".$uid.")";
    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    echo "ok";
    exit;
}
//note 确认订单 new
function vipuser_upgrade_order(){
    $id = MooGetGPC('pid','integer','G');
    $uid = MooGetGPC('uid','integer','G');
    $sql = "UPDATE {$GLOBALS['dbTablePre']}payment_new SET `check_order_sid`=".$GLOBALS['adminid']." ,`status`='1' WHERE id=".$id;
    $query = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    if($query){
        serverlog(3,$GLOBALS['dbTablePre'].'payment_new',"{$GLOBALS['username']}确认订单",$GLOBALS['adminid'],$uid);
        echo 1;
    }else{
        echo $sql;
    }
}
//note 会员升级 new
function vipuser_upgrade_new(){
    $pid = MooGetGPC('pid','string','G');
    $uid = MooGetGPC('uid','string','G');
    $add_money = MooGetGPC('add_money','string','G');

	
    $time = time();
    $sql = "SELECT uid,username , nickname, gender, birthyear, s_cid, bgtime, endtime,telphone,sid FROM {$GLOBALS['dbTablePre']}members_search WHERE uid = '{$uid}'";
    $user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    if(empty($user)){
        echo '无此会员，请确认';exit;
    }   
    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}payment_new WHERE id = '$pid' AND uid = '$uid' LIMIT 1";
    $pay = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    if( empty($pay) || $pay['status'] == 3 ){
        echo '操作失败!订单不存在或已经处理过了.';
        exit();
    }
    
    $bgtime=time();
    if( $pay['pay_service'] == 0 ){ // 升级为钻石会员
        if( $user['s_cid'] == 20 && $user['endtime'] > $bgtime ){
            $bgtime  = $user['bgtime'];
            $endtime = strtotime( "+6 month", $user['endtime'] );
        }else{
            $endtime = strtotime( "+6 month" );
        }
        $s_cid = 20; 
        $content = "尊敬的会员您好，恭喜您已成功升级为钻石会员。";
    }elseif( $pay['pay_service'] == -1 ){ // 升级为铂金会员
        if( $user['s_cid'] == 10 && $user['endtime'] > $bgtime ){
            $bgtime  = $user['bgtime'];
            $endtime = strtotime( "+6 month", $user['endtime'] );
        }else{
            $endtime = strtotime( "+6 month" );
        }
        $s_cid = 10; 
        $content = "尊敬的会员您好，恭喜您已成功升级为铂金会员。";
    }elseif( $pay['pay_service'] == 1 || $pay['pay_service'] == 4 ){ //高级会员
        $endtime = strtotime("+3 month");
        if($pay['pay_service'] == 4){//一个月的高级会员
            $endtime = strtotime("+1 month");
        }
        $s_cid = 30;
        $content = "尊敬的会员您好，恭喜您已成功升级为高级会员。";
    }elseif($pay['pay_service'] == 2){ //城市之星
        $endtime = strtotime("+1 month");
        $content = "尊敬的会员您好，恭喜您已成功升级为城市之星。";
    }elseif($pay['pay_service'] == 3){ //续费
        if($user['s_cid'] == 40 &&$user['endtime']<100){
            exit('普通会员不存在续费。请重新确认！');
        }
        $s_cid = $user['s_cid'] == 40 ? $pay['old_scid'] : $user['s_cid'] ;
        if($pay['plus_time'] > 0){
            $endtime = $user['endtime'] > $bgtime ? strtotime( "+{$pay['plus_time']} month", $user['endtime'] ) : strtotime( "+{$pay['plus_time']} month" );
        }else{
             $endtime = $user['endtime'] > $bgtime ? strtotime( "+3 month", $user['endtime'] ) : strtotime( "+3 month" );
        }
        $content = "尊敬的会员您好，恭喜您续费成功。";
    }elseif($pay['pay_service'] == 5){ //高级升钻石
        $sql = "SELECT id,status FROM {$GLOBALS['dbTablePre']}payment_new WHERE status = 1 AND pay_type = 2 AND  pay_service=5 AND uid = '$uid' LIMIT 1";
        $old_pay = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);

        if(empty($old_pay) && $user['s_cid'] != 30){
            exit('操作失败!此会员没有成功支付过或者不是高级会员。');
        }elseif(!empty($old_pay) && $user['s_cid'] == 30){
            //exit('操作失败!此会员已经支付过且为高级会员不能进行此操作。');
        }

        // 如果是已经支付过点击确定更新上一个订单的处理状态
        if(!empty($old_pay)){
            $sql = "update {$GLOBALS['dbTablePre']}payment_new set status = 3 where id = {$old_pay['id']}";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            $bgtime  = time();
            $endtime = strtotime( "+6 month", time() );
        }else{
            $bgtime  = $user['bgtime'];
            $endtime = strtotime( "+3 month",$user['endtime']);
        }

        /* if($GLOBALS['adminid'] != 312){ //不是447销售组长不能跳过此逻辑
            // 如果已经是高级会员，但是升级时间已经超过一周
            if($user['s_cid'] == 30 && $user['bgtime'] < time() - 60 * 24 * 3600){
                exit('此高级会员已经升级超过60天，不能升级为钻石会员。');

            }
        } */

        $s_cid = 20;
        $content = "尊敬的会员您好，恭喜您从高级会员升级为钻石会员成功。";
    
   
    
    }elseif($pay['pay_service'] == 8 ){ //高级升铂金__halt_compiler
	    if( $user['s_cid'] == 10 && $user['endtime'] > $bgtime ){
            $bgtime  = $user['bgtime'];
            $endtime = strtotime( "+6 month", $user['endtime'] );
        }else{
            $endtime = strtotime( "+6 month" );
        }
        
        $s_cid = 10;
        $content = "尊敬的会员您好，恭喜您已成功升级为铂金会员。";
	}elseif($pay['pay_service'] == 9 ){ //钻石升铂金
	    if( $user['s_cid'] == 10 && $user['endtime'] > $bgtime ){
            $bgtime  = $user['bgtime'];
            $endtime = strtotime( "+6 month", $user['endtime'] );
        }else{
            $endtime = strtotime( "+6 month" );
        }
        
        $s_cid = 10;
        $content = "尊敬的会员您好，恭喜您已成功升级为铂金会员。";
	}else{
        echo "操作失败!payment_new表id={$pid} 记录的 字段 pay_service = {$pay['pay_service']} 数据错误,请联系管理员";
        exit();
    }
    
    
    //修改 members 表
    if($pay['pay_service'] == 2){//城市之星,fanglin
        $sql=" update {$GLOBALS['dbTablePre']}members_search   set bgtime='{$bgtime}',endtime='{$endtime}',city_star='{$time}',updatetime={$time}  where uid=$uid";
            $value['bgtime']=$bgtime;
            $value['endtime']=$endtime;
            $value['city_star']=$time;
            $str_arr = array($uid=>array($bgtime,$endtime,$time));
            $field = array('bgtime','endtime','city_star');
    }elseif($pay['pay_service'] == 3){
        $sql=" update {$GLOBALS['dbTablePre']}members_search set s_cid={$s_cid},endtime='{$endtime}',updatetime={$time} where uid=$uid";
        $value['s_cid']=$s_cid;
        $value['endtime']=$endtime;
        $str_arr = array($uid => array($s_cid,$endtime));
        $field = array('s_cid','endtime');
    }else{ //高级会员或钻石会员
        $update_sql=$update_rosenumber_sql = '';
        $update_rosenumber=array();
        if($pay['give_city_star']==1){
            $city_star_time = strtotime("+1 month");
            $update_sql = ",city_star='{$city_star_time}'";
            $value['city_star']=$city_star_time;
        }
        if ($pay['pay_service'] == -1){
        	 $update_rosenumber_sql= "`rosenumber`=299";//铂金会员升级后赠送299朵鲜花
        	 $update_rosenumber['rosenumber']=299;
        }elseif($pay['pay_service']== 0){ 
             $update_rosenumber_sql= "`rosenumber`=88";//钻石会员升级后赠送88朵鲜花
             $update_rosenumber['rosenumber']=88;
        }
        
        $sql=" update {$GLOBALS['dbTablePre']}members_search set updatetime={$time}, s_cid={$s_cid},bgtime='$bgtime' ,endtime='$endtime' $update_sql  where uid=$uid";
        $value['s_cid']=$s_cid;
        $value['bgtime']=$bgtime;
        $value['endtime']=$endtime;
        if($pay['give_city_star'] == 1){
        	$str_arr = array($uid=>array($s_cid,$bgtime,$endtime,$city_star_time));
        	$field = array('s_cid','bgtime','endtime','city_star');
        }else{
        	$str_arr = array($uid=>array($s_cid,$bgtime,$endtime));
        	$field = array('s_cid','bgtime','endtime');
        }
    }
    searchApi('members_man members_women')->updateAttr($field,$str_arr);

    if(!$GLOBALS['_MooClass']['MooMySQL']->query($sql)){
        echo "操作失败!无法修改用户信息.请联系管理员...";
        exit();
    }
    if(!empty($update_rosenumber_sql)){
    	$GLOBALS['_MooClass']['MooMySQL']->query('UPDATE `'.$GLOBALS['dbTablePre'].'members_base` SET '.$update_rosenumber_sql.' WHERE `uid`='.$uid);
    }
    //短信提醒
    if(!empty($content) && !empty($user['telphone'])){
        if(SendMsg($user['telphone'],$content)){
            $GLOBALS['_MooClass']['MooMySQL']->query("INSERT INTO {$GLOBALS['dbTablePre']}smslog(sid,uid,content,sendtime) VALUES('{$GLOBALS['adminid']}','$uid','$content','{$GLOBALS['timestamp']}')");
        }
    }
    
    if(MOOPHP_ALLOW_FASTDB){
        MooFastdbUpdate('members_search','uid',$uid,$value);
        if(!empty($update_rosenumber)){
        	MooFastdbUpdate('members_base','uid',$uid,$update_rosenumber);
        }
    }
    serverlog(3,$GLOBALS['dbTablePre']."members_search","{$GLOBALS['username']}改变用户{$uid}的等级",$GLOBALS['adminid'],$uid);
    
    //更新诚信值
    $get_certi_sql = "select certification from {$GLOBALS['dbTablePre']}members_search where uid='{$uid}'";
    $certi_arr = $GLOBALS['_MooClass']['MooMySQL']->getOne($get_certi_sql,true);
    $num_certi =$certi_arr['certification']+6;//诚信值加6
                
    //修改支付信息
    $sql="update {$GLOBALS['dbTablePre']}payment_new set check_sid='{$GLOBALS['adminid']}',check_time='$time',status=3 where id='$pid'";
    if (!$GLOBALS['_MooClass']['MooMySQL']->query($sql)){
        echo "操作失败!无法修改订单状态.请联系管理员...";
        exit();
    }
    //note 写入成功订单表中
    $sql = "select * from {$GLOBALS['dbTablePre']}payment_new where id='$pid'";
    $payment_arr = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    $payment_data = array();
    
    $payment_data['uid'] =  $payment_arr['apply_sid']; //客服id
    $payment_data['orderoknum'] = 1; //成功订单数   
    $payment_data['dateline'] = $time; //成功订单时间
    $payment_data['paymentid'] = $pid; //申请支付订单id         
    
    inserttable('financial_orderok',$payment_data);
    
    serverlog(3,$GLOBALS['dbTablePre']."payment_new","{$GLOBALS['username']}改变{$uid}支付状态",$GLOBALS['adminid']);
    
    //插入数据 web_diamond 表(钻石会员)
    if( $s_cid == 20){//d. = m.nickname,d. = m.gender,d. = m.birthyear,d.=m.bgtime,d.=m.endtime
        if( $user['endtime'] > $bgtime ){ //钻石会员续费
            $status = 1;
        }else{
            $status = 1;
        }
        $sql = "REPLACE INTO {$GLOBALS['dbTablePre']}diamond (uid ,username ,skin ,status, nickname, gender, birthyear,bgtime,endtime)
                VALUES ( '{$uid}', '{$user['username']}', '".($user['gender']==0?'cyan':'red')."', '{$status}', '{$user['nickname']}','{$user['gender']}','{$user['birthyear']}','$bgtime','$endtime')";        
        if (!$GLOBALS['_MooClass']['MooMySQL']->query($sql)){
            echo "操作失败!无法添加钻石会员数据.请联系管理员...";
            exit();
        }
    }
    
    //对应的客服会员总数减1
    $sql = "UPDATE {$GLOBALS['dbTablePre']}admin_user SET member_count=member_count-1 WHERE uid='{$user['sid']}'";
    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    
    //对应的后台扩展表保留其原归属客服id
    $sql = "UPDATE {$GLOBALS['dbTablePre']}member_admininfo SET effect_grade=9,old_sid='{$user['sid']}' WHERE uid='$uid'";
    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    
    //会员sid清0，售后重新分配,更新诚信
    //$sql = "UPDATE {$GLOBALS['dbTablePre']}members SET sid=0 WHERE uid='{$uid}'";
    $sql = "UPDATE {$GLOBALS['dbTablePre']}members_search SET sid=13 ,certification=".$num_certi." WHERE uid='{$uid}'";
    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    searchApi('members_man members_women')->updateAttr(array('sid'),array($uid=>array(13)));
											
  
    
    $sql = "UPDATE {$GLOBALS['dbTablePre']}admin_user SET member_count=member_count+1 WHERE uid='13'";
    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    
    
    
    //在线客服列表,arr
    $online_service = congratulate_success_upgrade();
    $sql = "INSERT INTO {$GLOBALS['dbTablePre']}congratulate_remark(online_sid,sid,uid,dateline) VALUES";
    $sql_value=array();
    foreach($online_service as $service){
        $sql_value[]= "({$service['uid']},'{$user['sid']}','{$uid}',$time)";
    }
    if(!empty($sql_value)){
    	$GLOBALS['_MooClass']['MooMySQL']->query($sql.implode(',', $sql_value));
    }
//  error_log($sql);

/*  
    //售后客服组长右下角提醒
    $sql2 = "INSERT INTO {$GLOBALS['dbTablePre']}admin_remark(groupid,title,content,awoketime,dateline) VALUES";
    foreach($GLOBALS['admin_aftersales_service'] as $sale_service){
        $dateline = time();
        $awoketime = $dateline+120;
        $sql_value2 .= "('{$sale_service}','有新的vip会员分配','{$uid}升级为vip会员了,需要分配','{$awoketime}','{$dateline}'),";
    }
    $sql_value2 = substr($sql_value2,0,-1);
    $sql2 .= $sql_value2;

    $GLOBALS['_MooClass']['MooMySQL']->query($sql2);
*/  
    //统计系统的接口begin
    $payKey = 'zaysvipchannel';//单独接口验证
    //$uidmd5 = md5($uid.MOOPHP_AUTHKEY);
    $uidmd5 = md5($uid.$payKey);
    $apiurl = "http://tg.zhenaiyisheng.cc/hongniang/hongniang_super_import.php?uid=".$uid."&md5=".$uidmd5;
    //error_log(file_get_contents($apiurl));
    if(file_get_contents($apiurl)!="ok"){
        $dateline = time();
        $sql="INSERT INTO {$GLOBALS['dbTablePre']}do_url VALUES('','{$apiurl}','{$dateline}',0,0)";
        $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    }
    //统计系统的接口end
    serverlog(3,$GLOBALS['dbTablePre'].'payment_new',"{$GLOBALS['username']}确定升级",$GLOBALS['adminid'],$uid);
	//htotal
	//@file_get_contents("http://222.73.4.240/tj/index/reg?act=pay&w=1&u=".$uid);
    $sql="INSERT INTO web_test(n,v) VALUES('pay2',".$uid.")";
    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    echo "ok";
    exit;
}

//拒绝升级会员
function vipuser_upgrade_new2(){
    $pid = MooGetGPC('pid','string','G');
    $uid = MooGetGPC('uid','string','G');
    //修改支付信息
    $sql="update {$GLOBALS['dbTablePre']}payment_new set check_sid='{$GLOBALS['adminid']}',check_time='$time',status=2 where id='$pid'";
    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    serverlog(3,$GLOBALS['dbTablePre'].'payment_new',"{$GLOBALS['username']}无效申请",$GLOBALS['adminid'],$uid);
    echo 'ok';exit;
}



//确认汇款改成 未汇款
function vipuser_downgrade_status(){
    $pid = MooGetGPC('pid','string','G');

    //修改支付信息
    $sql="update {$GLOBALS['dbTablePre']}payment_new set check_sid='',check_time='',status='0' where id='$pid'";
    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    serverlog(3,$GLOBALS['dbTablePre'].'payment_new',"{$GLOBALS['username']}未付款",$GLOBALS['adminid'],$uid);
    echo 'ok';exit;
}



//note 会员到期设为普通会员
function vipuser_expire(){
    $uid = MooGetGPC('uid','integer','G');
    $sql = "UPDATE {$GLOBALS['dbTablePre']}members_search SET s_cid=40  WHERE uid = {$uid}";
    if(MOOPHP_ALLOW_FASTDB){
    	$value = array();
        $value['s_cid']=40;
        MooFastdbUpdate('members_search','uid',$uid,$value);
    }
    serverlog(3,$GLOBALS['dbTablePre']."members_search","{$GLOBALS['username']}将用户ID:{$uid}设为普通会员",$GLOBALS['adminid'],$uid);
    if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
    	searchApi('members_man members_women') -> updateAttr(array('s_cid'),array($uid=>array(40)));
        echo "ok";
    }else{
        echo "error";
    }
    exit;
}

function vipuser_get_groupmember(){
    global $kefu_arr;
    $id = MooGetGPC('id','integer','G');
    $groupmember = get_group_type($id);
    
    if(empty($groupmember[0]['manage_list'])){
        echo 'no';exit;
    }
    
    $str = "<option value=''>请选择</option>";
    $arr = explode(',',$groupmember[0]['manage_list']);

    foreach($arr as $v){
        $str .= "<option value='{$v}'>$kefu_arr[$v]</option>";
    }
    echo $str;
}


//补款预付款 确认已付款
function vipuser_upgrade_pay(){
    $id=$uid=$sql='';
    $id = MooGetGPC('pid','integer','G');
    $uid = MooGetGPC('uid','integer','G');
    $sql = "UPDATE {$GLOBALS['dbTablePre']}payment_other SET `check_order_sid`=".$GLOBALS['adminid']." ,`status`='1' WHERE id=".$id;
    $query = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    if($query){
        serverlog(3,$GLOBALS['dbTablePre'].'payment_other',"{$GLOBALS['username']}确认订单",$GLOBALS['adminid'],$uid);
        echo 1;
    }else{
        echo $sql;
    }
}

//补款预付款 无效申请
function vipuser_upgrade_nopay(){
    global $timestamp;
    $pid=$uid='';
	
    $pid = MooGetGPC('pid','string','G');
    $uid = MooGetGPC('uid','string','G');
    //修改支付信息状态
    $sql="update {$GLOBALS['dbTablePre']}payment_other set check_sid='{$GLOBALS['adminid']}',check_time='$timestamp',status=2 where id='$pid'";
    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    serverlog(3,$GLOBALS['dbTablePre'].'payment_other',"{$GLOBALS['username']}无效申请",$GLOBALS['adminid'],$uid);
    echo 'ok';exit;
}


//note 会员到期设为普通会员
function vipuser_star(){
    $uid = MooGetGPC('uid','integer','G');
    $sql = "UPDATE {$GLOBALS['dbTablePre']}members_search SET city_star=0 WHERE uid = {$uid}";
    if(MOOPHP_ALLOW_FASTDB){
        $value['s_cid']=40;
        MooFastdbUpdate('members_search','uid',$uid,$value);
    }
    serverlog(3,$dbTablePre."members_search","{$GLOBALS['username']}将用户ID:{$uid}的城市之星取消",$GLOBALS['adminid'],$uid);
    if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
    	searchApi('members_man members_women')->updateAttr(array('city_star'),array($uid=>array(0)));
        echo "ok";
    }else{
        echo "error";
    }
    exit;
}


/***************************************控制层(V)***********************************/
$n=MooGetGPC('n','string');
//判断是否登录
adminUserInfo();
if(empty($GLOBALS['adminid'])){
    echo 'nologin';exit;
}

switch( $n ){
    //note 会员升级
    case 'upgrade':
        vipuser_upgrade();
        break;
    //note 会员升级 new
    case 'upgrade_new':
        vipuser_upgrade_new();
        break;
    case 'upgrade_new2':
        vipuser_upgrade_new2();
        break;
    //note 会员到期设为普通会员
    case 'expire':
        vipuser_expire();
        break;
    case 'star':
        vipuser_star();
        break;
    case 'get_groupmember':
        vipuser_get_groupmember();
        break;
    case 'upgrade_order':
        vipuser_upgrade_order();
        break;
	case 'upgrade_pay':
        vipuser_upgrade_pay();
        break;
	case 'upgrade_nopay':
        vipuser_upgrade_nopay();
        break;
	case 'downgrade_status':
	    vipuser_downgrade_status();
		break;
}
?>
