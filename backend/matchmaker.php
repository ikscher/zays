<?php
/**
 * 红娘币管理
 */


 /* 红娘币奖励
奖惩权限
销售一线组长：0-200红娘币/次；500红娘币/月
培训部门负责人：500红娘币/月
部门负责人：0-500红娘币/次；1000红娘币/月
质检部：0-500红娘币/次；无次数限制
总经理：无限制
总裁：无限制
*/
$reward=array(
            'gLeader'=>array('times'=>200,'month'=>500),//组长
			'tLeader'=>array('times'=>500,'month'=>1000), //部门负责人（分部主管）
			'aLeader'=>array('times'=>2000,'month'=>0), //主管
			'qLeader'=>array('times'=>500,'month'=>0) //0就是不限制
			);
			
/**
 * 
 * 奖赏理由列表
 */
function reward_config_list(){
	$page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
	$total=getcount('reward_config','');
	$sql='SELECT `id`,`type`,`money`,`title` FROM `'.$GLOBALS['dbTablePre'].'reward_config` LIMIT '.$offset.','.$limit;
	$data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$currenturl = "index.php?action=matchmaker&h=list";
   	$pages = multipage( $total, $limit, $page, $currenturl );
	$page_num = ceil($total/$limit);
	require_once(adminTemplate('matchmaker_reward_list'));
}

/**
 * 
 * 发起挑战
 */
function add_pk(){
	$recipient=MooGetGPC('recipient','integer','P');
	$amount=MooGetGPC('amount','integer','P');
	$start=MooGetGPC('start','string','P');
	$end=MooGetGPC('end','string','P');
	$msg=MooGetGPC('msg','string','P');
	$start=empty($start)?time():strtotime($start);
	if(empty($end)){
		exit(json_encode(array('flag'=>0,'msg'=>'请选择pk的结束时间')));
	}
	if(empty($recipient)){
		exit(json_encode(array('flag'=>0,'msg'=>'数据传递错误')));
	}
	if(empty($amount)){
		exit(json_encode(array('flag'=>0,'msg'=>'请填写需要pk的资本')));
	}
	if(empty($msg)){
		exit(json_encode(array('flag'=>0,'msg'=>'请填写pk的理由以及pk的依据')));
	}
	$balance=array();
	$balance_data=$GLOBALS['_MooClass']['MooMySQL']->getAll('SELECT `uid`,`balance` FROM `'.$GLOBALS['dbTablePre'].'admin_user` WHERE `uid` in ('.$GLOBALS['adminid'].','.$recipient.')');
	foreach($balance_data as $value){
		$balance[$value['uid']]=$value['balance'];
	}
	if($balance[$GLOBALS['adminid']]<$amount){
		exit(json_encode(array('flag'=>0,'msg'=>'你的 红娘币的余额不足，请降低pk资本')));
	}
	if($balance[$recipient]<$amount){
		exit(json_encode(array('flag'=>0,'msg'=>'对方的红娘币余额不足，请降低pk资本')));
	}
	$end=strtotime($end);
	$sql='INSERT INTO `'.$GLOBALS['dbTablePre'].'reward_pk` (`initiator`,`recipient`,`amount`,`start`,`end`,`msg`) VALUES (\''.$GLOBALS['adminid'].'\',\''.$recipient.'\',\''.$amount.'\',\''.$start.'\',\''.$end.'\',\''.$msg.'\')';
	if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
		$GLOBALS['_MooClass']['MooMySQL']->query('UPDATE `'.$GLOBALS['dbTablePre'].'admin_user` SET `balance`=`balance`-'.$amount.' WHERE `uid` in ('.$recipient.','.$GLOBALS['adminid'].')');
		serverlog('4','reward_pk',$GLOBALS['adminid'].'在'.date('Y-m-d H:i:s').'向'.$recipient.'发起PK', $GLOBALS['adminid']);
		exit(json_encode(array('flag'=>1,'msg'=>'PK发起成功，请等待对方的回音')));
	}else{
		exit(json_encode(array('flag'=>0,'msg'=>$sql)));
	}	
}

/**
 * 获取一条pk的详细信息
 */
function get_one_pk(){
	$pkid=MooGetGPC('id','integer','G');
	if(empty($pkid)){
		exit(json_encode(array('flag'=>0,'msg'=>'pkid数据传输错误')));
	}
	$sql='SELECT `id`,`initiator`,`recipient`,`amount`,`start`,`end`,`whether`,`victory`,`msg` FROM `'.$GLOBALS['dbTablePre'].'reward_pk` WHERE `id`='.$pkid;
	$data=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if(empty($data)){
		exit(json_encode(array('flag'=>0,'msg'=>'尚未找到你要找到PK的信息')));
	}else{
		exit(json_encode(array('flag'=>1,'msg'=>$data)));
	}
}

/**
 * 延长pk结束时间
 */
function extended_pk_end_time(){
	$pkid=MooGetGPC('pkid','integer','P');
	$end=MooGetGPC('end','string','P');
	if(empty($end)){
		exit(json_encode(array('flag'=>0,'msg'=>'请填写正确的时间')));
	}
	$sql='UPDATE `'.$GLOBALS['dbTablePre'].'reward_pk` SET `end`='.strtotime($end).' WHERE `id`='.$pkid;
	if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
		exit(json_encode(array('flag'=>1,'msg'=>'PK的延长的结束时间成功')));
	}else{
		exit(json_encode(array('flag'=>0,'msg'=>$sql)));
	}
}

/**
 * 判决胜利方
 */
function judge_one_pk(){
	$pkid=MooGetGPC('pkid','integer','P');
	$winner=MooGetGPC('winner','integer','P');
	$sql='SELECT `initiator`,`recipient`,`amount` FROM `'.$GLOBALS['dbTablePre'].'reward_pk` WHERE `id`='.$pkid;
	$data=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if(empty($data)){
		exit(json_encode(array('flag'=>0,'msg'=>'尚未找到你要找到PK的信息')));
	}
	if($winner){
		$amount=$data['amount']*2;
		$loser=($data['initiator']==$winner)?$data['recipient']:$data['initiator'];
		$sql='UPDATE `'.$GLOBALS['dbTablePre'].'reward_pk` SET `victory`='.$winner.' WHERE `id`='.$pkid;
		$winner_sql='UPDATE `'.$GLOBALS['dbTablePre'].'admin_user` SET `balance`=`balance`+'.$amount.',`victory`=`victory`+1 WHERE `uid`='.$winner;
		$loser_sql='UPDATE `'.$GLOBALS['dbTablePre'].'admin_user` SET `failure`=`failure`+1 WHERE `uid`='.$loser;
		if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
			$GLOBALS['_MooClass']['MooMySQL']->query($winner_sql);
			$GLOBALS['_MooClass']['MooMySQL']->query($loser_sql);
			serverlog('4','reward_pk',$pkid.'胜利者为'.$winner, $GLOBALS['adminid']);
			exit(json_encode(array('flag'=>$pkid,'msg'=>'操作成功')));
		}else{
			exit(json_encode(array('flag'=>0,'msg'=>$sql)));
		}
	}else{
		$sql='UPDATE `'.$GLOBALS['dbTablePre'].'reward_pk` SET `victory`=\'-1\' WHERE `id`='.$pkid;
		$admin_sql='UPDATE `'.$GLOBALS['dbTablePre'].'admin_user` SET `balance`=`balance`+'.$data['amount'].' WHERE `uid` in ('.$data['recipient'].','.$data['initiator'].')';
		if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
			$GLOBALS['_MooClass']['MooMySQL']->query($admin_sql);
			serverlog('4','reward_pk',$pkid.'平局', $GLOBALS['adminid']);
			exit(json_encode(array('flag'=>$pkid,'msg'=>'操作成功')));
		}else{
			exit(json_encode(array('flag'=>0,'msg'=>$sql)));
		}
	}
}

/**
 * 回应pk
 */
function respond_one_pk(){
	$pkid=MooGetGPC('pkid','integer','P');
	$respond=MooGetGPC('respond','integer','P');
	$sql='SELECT `initiator`,`recipient`,`amount` FROM `'.$GLOBALS['dbTablePre'].'reward_pk` WHERE `id`='.$pkid;
	$data=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if(empty($data)){
		exit(json_encode(array('flag'=>0,'msg'=>'数据查询不到')));
	}
	if(empty($respond)){
		$admin_sql='UPDATE `'.$GLOBALS['dbTablePre'].'admin_user` SET `balance`=`balance`+'.$data['amount'].' WHERE `uid` in ('.$data['recipient'].','.$data['initiator'].')';
		$sql='DELETE FROM `'.$GLOBALS['dbTablePre'].'reward_pk` WHERE `id`='.$pkid;
		if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
			$GLOBALS['_MooClass']['MooMySQL']->query($admin_sql);
			serverlog('4','reward_pk','不同意PK'.$pkid, $GLOBALS['adminid']);
			exit(json_encode(array('flag'=>$pkid,'msg'=>'操作成功')));	
		}else{
			exit(json_encode(array('flag'=>0,'msg'=>$sql)));
		}
	}else{
		$sql='UPDATE `'.$GLOBALS['dbTablePre'].'reward_pk` SET `whether`=1 WHERE `id`='.$pkid;
		if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
			serverlog('4','reward_pk','同意PK'.$pkid, $GLOBALS['adminid']);
			exit(json_encode(array('flag'=>$pkid,'msg'=>'操作成功')));	
		}else{
			exit(json_encode(array('flag'=>0,'msg'=>$sql)));
		}
	}
}

/**
 * 
 * 挑战列表
 */
function pk_list($old_initiator=0,$old_recipient=0){
	$page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
	$initiator=empty($old_initiator)?MooGetGPC('initiator','integer','G'):$old_initiator;
	$recipient=empty($old_recipient)?MooGetGPC('recipient','integer','G'):$old_recipient;
	$start=MooGetGPC('start','string','G');
	$end=MooGetGPC('end','string','G');
	$victory=MooGetGPC('victory','integer','G');
	$where=$ref=array();
	if(!empty($initiator)){
		$where[]=$ref[]='`initiator`='.$initiator;
	}
	if(!empty($recipient)){
		$where[]=$ref[]='`recipient`='.$recipient;
	}
	if(!empty($start)){
		$where[]='`start`='.strtotime($start);
		$ref[]=$start;
	}
	if(!empty($end)){
		$where[]='`end`='.strtotime($end);
		$ref[]=$end;
	}
	if(!empty($victory)){
		$where[]=$ref[]='`victory`='.$victory;
	}
	$time=time();
	$where=empty($where)?'':'WHERE '.implode(' AND ',$where);
	$currenturl = 'index.php?action=matchmaker&h='.$GLOBALS['h'].empty($ref)?'':implode('&', $ref);
	$total=getcount('reward_pk',$where);
	$sql='SELECT `id`,`initiator`,`recipient`,`start`,`end`,`amount`,`victory`,`whether` FROM `'.$GLOBALS['dbTablePre'].'reward_pk` '.$where.' LIMIT '.$offset.','.$limit;
	$data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$pages = multipage( $total, $limit, $page, $currenturl );
	$page_num = ceil($total/$limit);
	require_once(adminTemplate('matchmaker_pk_list'));
}

/**
 * 
 * 添加奖赏设置
 */
function add_config_reward(){
	$type=MooGetGPC('type','integer','P');
	$money=MooGetGPC('money','integer','P');
	$title=MooGetGPC('title','string','P');
	$msg=MooGetGPC('msg','string','P');
	if(empty($title)){
		exit(json_encode(array('flag'=>0,'msg'=>'理由不可为空,请仔细的填写')));
	}
	$sql='INSERT INTO `'.$GLOBALS['dbTablePre'].'reward_config` (`type`,`msg`,`money`,`title`) VALUES (\''.$type.'\',\''.$msg.'\',\''.$money.'\',\''.$title.'\')';
	if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
		serverlog('4','reward_config','添加奖赏理由', $GLOBALS['adminid']);
		exit(json_encode(array('flag'=>1,'msg'=>'奖赏措施添加成功')));
	}else{
		exit(json_encode(array('flag'=>0,'msg'=>$sql)));
	}
}

/**
 * 
 * 修改奖赏处罚
 */
function edit_config_reward(){
	$id=MooGetGPC('id','integer','P');
	$type=MooGetGPC('type','integer','P');
	$money=MooGetGPC('money','integer','P');
	$title=MooGetGPC('title','string','P');
	$msg=MooGetGPC('msg','string','P');
	if(empty($id)){
		exit(json_encode(array('flag'=>0,'msg'=>'数据传输错误')));
	}
	if(empty($title)){
		exit(json_encode(array('flag'=>0,'msg'=>'理由不可为空,请仔细的填写')));
	}
	if(empty($money)){
		exit(json_encode(array('flag'=>0,'msg'=>'金额不可为空,请仔细的填写')));
	}
	$sql='UPDATE `'.$GLOBALS['dbTablePre'].'reward_config` SET `type`=\''.$type.'\',`msg`=\''.$msg.'\',`money`=\''.$money.'\',`title`=\''.$title.'\' WHERE `id`='.$id;
	if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
		serverlog('3','reward_config','编辑奖赏理由', $GLOBALS['adminid']);
		exit(json_encode(array('flag'=>1,'msg'=>'编辑措施添加成功')));
	}else{
		exit(json_encode(array('flag'=>0,'msg'=>$sql)));
	}
}

/**
 * ajax获取某一条奖赏条例配置
 */
function get_config_reward(){
	$id=MooGetGPC('id','integer','G');
	if(empty($id)){
		exit(json_encode(array('flag'=>0,'msg'=>'数据传递错误')));
	}
	$sql='SELECT `id`,`type`,`money`,`title`,`msg` FROM `'.$GLOBALS['dbTablePre'].'reward_config` WHERE `id`='.$id;
	$data=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if(empty($data)){
		exit(json_encode(array('flag'=>0,'msg'=>'尚未找到')));
	}else{
		exit(json_encode(array('flag'=>1,'msg'=>$data)));
	}	
}



/**
* 
*   奖惩前的判断
* 
*
*/
function isAuthRewards(){
   global $reward;
   $adminid=$GLOBALS['adminid'];
   $groupid=$GLOBALS['groupid'];
   $amount=MooGetGPC('amount','integer','P');//金额
   $type=MooGetGPC('type','integer','P');//类型
   $begintime=strtotime(date('Ym').'01');//当月第一天
   $endtime=  strtotime(date('Ym').date('t')."+1 day");  //下月第一天
   $sql="select sum(amount) as total from {$GLOBALS['dbTablePre']}reward_log where adminid='{$adminid}' and type=1 and time<$endtime and time>=$begintime";
   
   $result=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
   
   if(in_array($groupid,$GLOBALS['admin_all_group'])){//组长
      if($amount>$reward['gLeader']['times']){ exit(json_encode(array('flag'=>0,'msg'=>'组长每次最多200红娘币！'))); }
      if($result['total']>$reward['gLeader']['month']) { exit(json_encode(array('flag'=>0,'msg'=>'组长每月最多奖赏500红娘币！'))); }
   }elseif($groupid==61){
      if($amount>$reward['aLeader']['times']){ exit(json_encode(array('flag'=>0,'msg'=>'客服主管每次最多2000红娘币！'))); }
   }elseif($groupid==76){ //分部主管
      if($amount>$reward['tLeader']['times']){ exit(json_encode(array('flag'=>0,'msg'=>'分部主管每次最多500红娘币！'))); }
      if($result['total']>$reward['tLeader']['month']) { exit(json_encode(array('flag'=>0,'msg'=>'分部主管每月最多奖赏1000红娘币！')));}
   }elseif($groupid==62){ //质检
      if($amount>$reward['qLeader']['times']){ exit(json_encode(array('flag'=>0,'msg'=>'组长每次最多500红娘币！'))); }
	  exit(json_encode(array('flag'=>1,'msg'=>'')));
   }elseif(in_array($groupid,array('60','61','75'))){ //主管以上权限
      exit(json_encode(array('flag'=>1,'msg'=>'')));
   }/* else{
      exit(json_encode(array('flag'=>0,'msg'=>'没有奖励权限！')));
   } */
   
   exit(json_encode(array('flag'=>1,'msg'=>'')));

}


/**
 * 
 * 发布奖赏
 */
function put_rewards(){
	$uid=MooGetGPC('uid','integer','P');
	$amount=MooGetGPC('amount','integer','P');
	$type=MooGetGPC('type','integer','P');
	$rewardid=MooGetGPC('rewardid','string','P');//奖惩缘由
	$msg=MooGetGPC('msg','string','P');
	if(empty($uid)){
		exit(json_encode(array('flag'=>0,'msg'=>'数据传输有误')));
	}
	if(empty($amount)){
		exit(json_encode(array('flag'=>0,'msg'=>'请填写赏罚金额')));
	}
	if(empty($rewardid) && empty($msg)){
		exit(json_encode(array('flag'=>0,'msg'=>'请填写赏罚理由')));
	}
	$my_config=array();
	$my_config=$GLOBALS['_MooClass']['MooMySQL']->getOne('SELECT `balance`,`allot`,`amount` FROM `'.$GLOBALS['dbTablePre'].'admin_user` WHERE `uid`='.$GLOBALS['adminid'],true);
	$my_allot=$GLOBALS['fastdb']->get('admin_allot_'.$GLOBALS['adminid']);//红娘币发送计数器，每日清空一次
	$my_allot=intval($my_allot);
	if(($my_config['allot']!=-1 && $GLOBALS['groupid']!=60) && $my_allot>$my_config['allot']){
		exit(json_encode(array('flag'=>0,'msg'=>'超出当日奖励次数')));
	}
	if(($my_config['balance']!=-1 && $GLOBALS['groupid']!=60) && $my_config['balance']<$amount && !empty($type)){
		exit(json_encode(array('flag'=>0,'msg'=>'你的余额不足不能进行奖励')));
	}
	if(($my_config['amount']!=-1 && $GLOBALS['groupid']!=60) && $my_config['amount']<$amount && !empty($type)){
		exit(json_encode(array('flag'=>0,'msg'=>'奖励的金额超出了配额'.$my_config['amount'])));
	}
	/*插入红娘币配置表*/
	$title="无标题";
	$sql='INSERT INTO `'.$GLOBALS['dbTablePre'].'reward_config` (`type`,`msg`,`money`,`title`) VALUES (\''.$type.'\',\''.$rewardid.'\',\''.$amount.'\',\''.$title.'\')';
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
    $insertId = $GLOBALS['_MooClass']['MooMySQL']->insertId();//获取 红娘币配置表插入产生的id
    
    $sql='INSERT INTO `'.$GLOBALS['dbTablePre'].'reward_log` (`uid`,`adminid`,`amount`,`time`,`type`,`rewardid`,`msg`) VALUES (\''.$uid.'\',\''.$GLOBALS['adminid'].'\',\''.$amount.'\',\''.time().'\',\''.$type.'\',\''.$insertId.'\',\''.$msg.'\')';
	if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
		$GLOBALS['_MooClass']['MooMySQL']->query('UPDATE `'.$GLOBALS['dbTablePre'].'admin_user` SET `balance`=`balance`'.(empty($type)?'-':'+').$amount.' WHERE `uid`='.$uid);//奖罚下面的员工
		if($my_config['balance']!=-1 && $GLOBALS['groupid']!=60 && !empty($type)){
			$GLOBALS['_MooClass']['MooMySQL']->query('UPDATE `'.$GLOBALS['dbTablePre'].'admin_user` SET `balance`=`balance`-'.$amount.' WHERE `uid`='.$GLOBALS['adminid']);
		}
		$GLOBALS['fastdb']->get('admin_allot_'.$GLOBALS['adminid'],$my_allot+1);
		serverlog('4','reward_log',(empty($type)?'处罚':'奖励').$uid.'红娘币'.$amount, $GLOBALS['adminid']);
		exit(json_encode(array('flag'=>1,'msg'=>(empty($type)?'处罚成功':'奖励成功'))));
	}else{
		exit(json_encode(array('flag'=>0,'msg'=>$sql)));
	}
}

/**
 * 
 * 可以奖惩的列表
 */
function to_rewards(){
	$page_per = 15;
    $page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
	$myservice=get_myservice_idlist();
	
	 if(isset($_GET['action']) && $_GET['action'] == 'matchmaker') {
		 $choose = MooGetGPC('choose','string');
		 $keyword = MooGetGPC('keyword','string');
		if(!empty($choose) && !empty($keyword)) {
			$condition  =" ".$choose ." = '".$keyword."' ";
		}
	}
	   $where = '';
	if(!empty($condition)){
       $where= " where ".$condition;
   	}else{
   		
   		$where ='';
   		
   		
   	}
	
    //$total=getcount('admin_user','WHERE '.((empty($myservice)||$myservice=='all')?'':'`uid` IN ('.$myservice.') AND ').'`uid`!='.$GLOBALS['adminid']);
     $sql_count = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}admin_user ".$where;
     $total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_count);
     
    $sql='SELECT `uid`,`username`,`name`,`balance`,`groupid` FROM `'.$GLOBALS['dbTablePre'].'admin_user`  '.$where.' LIMIT '.$offset.','.$limit;
	$data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    $currenturl = "index.php?action=matchmaker&h=to_rewards";
    $currenturl2 = $currenturl="index.php?action=matchmaker&h=to_rewards&choose={$choose}&keyword={$keyword}";
    
    //$currenturl = $currenturl."&type=$type";
    
    $pages = multipage( $total['num'], $limit, $page, $currenturl );
    
    //note 跳转到某一页
    $page_num = ceil($total['num']/$limit);
    
    //$pages = multipage( $total, $limit, $page, $currenturl );
	//$page_num = ceil($total/$limit);
    require_once(adminTemplate('matchmaker_to_rewards_list'));
}

/**
 * 
 * 根据操作类型获取列表
 */
function get_reward_list(){
	$type =MooGetGPC('type','integer','G');
	$sql='SELECT `id`,`money`,`title` FROM `'.$GLOBALS['dbTablePre'].'reward_config` WHERE `type`='.$type;
	$data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$data=empty($data)?array():$data;
	exit(json_encode($data));
}

/**
 * 
 * 可以pk的人员
 */
function want_pk(){
	$page_per = 15;
    $page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
    $total=getcount('admin_user','WHERE `groupid`='.$GLOBALS['groupid'].' AND `uid`!='.$GLOBALS['adminid']);
    $sql='SELECT `uid`,`username`,`name`,`balance`,`victory`,`failure` FROM `'.$GLOBALS['dbTablePre'].'admin_user` WHERE `groupid`='.$GLOBALS['groupid'].' AND `uid`!='.$GLOBALS['adminid'].' LIMIT '.$offset.','.$limit;
    $data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$currenturl = "index.php?action=matchmaker&h=want_pk";
   	$pages = multipage( $total, $limit, $page, $currenturl );
	$page_num = ceil($total/$limit);
	require_once(adminTemplate('matchmaker_reward_pk_list'));
}

/**
 * 红娘币奖赏查询
 * @param integer $my
 */
function reward_log($my=0){
	global $str_;$sid_user="请选择";$mange_list=array();
	$page_per = 15;
	$page = max(1,MooGetGPC('page','integer'));
	$limit = 15;
	$offset = ($page-1)*$limit;
	$start=MooGetGPC('start','string','G');
	$end=MooGetGPC('end','string','G');
	$uid=MooGetGPC('sid','integer','G');
	$groupid=MooGetGPC('groupid','string','G');
	
	if($uid!=""){
	$sql_s="SELECT *FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid=".$uid;   
    $sid_u=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql_s); 
    $sid_user=$sid_u[0]['username'];
       }
    
 	
     $where=$data=$ref=array();
     $total=0;
    
    if($groupid){
    	//$mange_list=array();
    	$sql_="SELECT *FROM {$GLOBALS['dbTablePre']}admin_manage where id=".$groupid;    //这个查询出用户的manage_list
    	$mange_list=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql_);
    
    	$man_id=$mange_list['manage_list'];   //查询到这组的id
    	
       	$sql_user="SELECT *FROM {$GLOBALS['dbTablePre']}admin_user where uid in (".$man_id.")";    //这个查询用户的id名字
    	 $mang_list=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql_user);
         $group = get_group_type($groupid);
         $where[]="uid in (".$man_id.")";
         $ref[]='groupid='.$groupid;
     }
     $group_list = get_group_type();

       $ins=rtrim($str_,",");
    if(!empty($start) || !empty($end)){
        if(!empty($start)){
            $ref[]='start='.$start;
        }
        if(!empty($end)){
            $ref[]='end='.$end;
        }
		if(!empty($start) && !empty($end)){
			$where[]='`time` BETWEEN '.strtotime($start).' AND '.strtotime($end);
		}else{
			$time=empty($start)?$end:$start;
			$where[]='`time` BETWEEN '.strtotime($time).' AND '.strtotime($time.' 23:59:59');
		}
	}
	
	     if(!empty($uid)){
		 $where[]='`uid`='.$uid;
         $ref[]='sid='.$uid;
       
           }

	    $wheres=empty($where)?'':'where '.implode(' and ', $where);
         $total=getcount('reward_log',$wheres);
	    $sql='SELECT `id`,`uid`,`adminid`,`amount`,`time`,`type` ,`rewardid` FROM `'.$GLOBALS['dbTablePre'].'reward_log` '.$wheres.' LIMIT '.$offset.','.$limit;
		$data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
        $myservice=get_myservice_idlist();
    $myservices=($myservice=='all')?$GLOBALS['kefu_arr']:explode(',',$myservice);
    $currenturl = "index.php?action=matchmaker&h=".$GLOBALS['h'].(empty($ref)?'':'&'.implode('&',$ref))."&groupid=".$groupid;
   	$pages = multipage( $total, $limit, $page, $currenturl );
	$page_num = ceil($total/$limit);
	require_once(adminTemplate('matchmaker_reward_log_list'));	
}


//获取一条奖励详情
function get_log(){
    $id=MooGetGPC('id','integer','G');
    $data2=$data=array();
    if(empty($id)){
        exit(json_encode(array('flag'=>0,'msg'=>'数据传输有问题')));
    }
    $sql='SELECT `id`,`uid`,`adminid`,`amount`,`time`,`type`,`rewardid`,`msg` FROM `'.$GLOBALS['dbTablePre'].'reward_log` WHERE `id`='.$id;
    $data=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    if(empty($data)){
        exit(json_encode(array('flag'=>0,'msg'=>'数据传输有问题')));
    }
    if(!empty($data['rewardid'])){
    	$data2=$GLOBALS['_MooClass']['MooMySQL']->getOne('SELECT `msg`,`title` FROM `'.$GLOBALS['dbTablePre'].'reward_config` WHERE `id`='.$data['rewardid']);
    }
    $data['uid_name']=$GLOBALS['kefu_arr'][$data['uid']];
    $data['adminid_name']=empty($data['adminid'])?'系统':$GLOBALS['kefu_arr'][$data['adminid']];
    $data['time']=date('Y-m-d H:i:s',$data['time']);
    $data['type_str']=$GLOBALS['matchmaker_msg_array'][$data['type']];
    $data['msg']=(empty($data2['title'])?'':$data2['title'].'<br/>').(empty($data2['msg'])?'':$data2['msg'].'<br/>').$data['msg'];
    exit(json_encode(array('flag'=>1,'msg'=>$data)));
}

/**
 * 创建红娘币变更信息
 */
function bulidmsg(){
	$msg_array=array(0=>'处罚',1=>'奖励','3'=>'PK胜利','4'=>'PK失败','5'=>'PK资本支付','6'=>'PK资本返还');
}

$h=MooGetGPC('h','string','G');
//日志变更类型
$matchmaker_msg_array=array(0=>'处罚',1=>'奖励','3'=>'PK胜利','4'=>'PK失败','5'=>'PK资本支付','6'=>'PK资本返还');
if(!checkGroup('matchmaker',$h)){
	if(in_array($h,array('config_list','to_rewards','want_pk','my_pk','pk_me','pk_list','mylog','reward_log','isAuthRewards'))){
		exit('您没有此操作的权限');
	}else{
		exit(json_encode(array('flag'=>0,'msg'=>'您没有此操作的权限')));
	}
}
if(in_array($h, array('put_rewards','add_pk','respond','judge'))){
	$now=intval(date('Hi'));
	if($now>'2330' || $now<'130'){
		exit(json_encode(array('flag'=>0,'msg'=>'朋友天色不早了，还是洗洗睡吧。')));
	}
}
switch ($h){
	case 'config_list':
		reward_config_list();
		break;
	case 'to_rewards':
		to_rewards();
		break;
	case 'want_pk':
		want_pk();
		break;
	case 'edit_config_reward':
		edit_config_reward();
		break;
	case 'add_config_reward':
		add_config_reward();
		break;
	case 'get_config_reward':
		get_config_reward();
		break;
	case 'get_reward_list':
		get_reward_list();
		break;
	case 'put_rewards':
		put_rewards();
		break;
	case 'add_pk':
		add_pk();
		break;
	case 'my_pk':
		pk_list($GLOBALS['adminid'],0);
		break;
	case 'pk_me':
		pk_list(0,$GLOBALS['adminid']);
		break;
	case 'pk_list':
		pk_list();
		break;
	case 'get_one_pk':
		get_one_pk();
		break;
	case 'extended':
		extended_pk_end_time();
		break;
	case 'respond':
		respond_one_pk();
		break;
	case 'judge':
		judge_one_pk();
		break;
	case 'mylog':
		reward_log($GLOBALS['adminid']);
		break;
	case 'reward_log':
		reward_log();
		break;
	case 'isAuthRewards':
	    isAuthRewards();
		break;
	case 'get_log':
		get_log();
		break;
	default:
		exit('找不到你要操作的内容');
		break;
}