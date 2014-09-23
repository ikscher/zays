<?php
function financial_tele_info_list(){
	 $groupid=MooGetGPC('groupid','integer','G');
	 $userid=MooGetGPC('userid','string','G');
	 $end_time=mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()));
	 $begin_time=MooGetGPC('time','integer','G');

	 $time_array=array(2=>array($end_time-3600*24,$end_time,date('d',strtotime("-1 days")).'号数据'),3=>array($end_time-3600*48,$end_time-3600*24,date('d',strtotime("-2 days")).'号数据'),4=>array($end_time-3600*72,$end_time-3600*48,date('d',strtotime("-3 days")).'号数据'),5=>array($end_time-3600*96,$end_time-3600*72,date('d',strtotime("-4 days")).'号数据'),6=>array($end_time-3600*120,$end_time-3600*96,date('d',strtotime("-5 days")).'号数据'),7=>array($end_time-3600*144,$end_time-3600*120,date('d',strtotime("-6 days")).'号数据'),8=>array($end_time-3600*166,$end_time-3600*144,date('d',strtotime("-7 days")).'号数据'));
	if($begin_time){
		 $time_between="b.dateline>".$time_array[$begin_time]['0']." and b.dateline<".$time_array[$begin_time]['1'];
		 $now_start=$time_array[$begin_time]['2'];
	}else{
		 //$time_between="b.dateline>".mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()));
		 //$time_between="dateline>1244377384";
		 $time_between="b.dateline>".$time_array[2]['0']." and b.dateline<".$time_array[2]['1'];
		 $now_start="昨天数据";
	}
 
	if(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr'])&&empty($groupid)&&empty($userid)){
	 //客服主管看各个组和总的
		if(file_exists("data/valid_group.txt")){
					$file_kefuid=fopen("data/valid_group.txt",'r');
					$valid_group=fread($file_kefuid,25);
					fclose($file_kefuid);
		 }
		 $sql="SELECT * FROM {$GLOBALS['dbTablePre']}admin_manage WHERE type=1 and id in ($valid_group) and manage_list!=''";
		 $datax = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	 
		foreach($datax as $key=> $datax_user){//获得组
			//$group_user_name[]=$datax_user['manage_name'];
			//echo $datax_user['manage_list'];
			 $admin_user_array[$datax_user['id']]=$datax_user['manage_list'];
		}
		
		$sql="SELECT b.* FROM  {$GLOBALS['dbTablePre']}admin_telcount b WHERE uid in ($valid_group) and type=1 and ".$time_between ." GROUP BY uid";
		$all_group_inf_arr=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		$all_user_count = $group_user_name =  $del_user_num = array();
			foreach($all_group_inf_arr as $key=>$all_group_inf){
				$group_user_name[]=$all_group_inf['username'];
				$del_user_num[$key]=$all_group_inf['del_user_num'];		 
				$all_user_count[$key]['all'] = $all_group_inf['member_count'];
				$all_user_count[$key]['havetel'] = $all_group_inf['tele_num'];
				$sql="SELECT  count(b.uid) as tnum FROM {$GLOBALS['dbTablePre']}members_search m  left join {$GLOBALS['dbTablePre']}member_admininfo b on b.uid=m.uid  where m.sid in (".$admin_user_array[$all_group_inf['uid']].") and ".$time_between;
				$num_distinct=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
				//print_r($num_distinct);
				$user_num_distinct= $num_distinct['tnum'];
				$all_user_count[$key]['distinct'] = $user_num_distinct;		
			}
		
	 }
	 if(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_group'])||$groupid){
	 //售前组长
		if(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_group'])){
			$sql="SELECT * FROM {$GLOBALS['dbTablePre']}admin_manage WHERE FIND_IN_SET(".$GLOBALS['adminid'].",manage_list)";		
		}else{
			$sql="SELECT * FROM {$GLOBALS['dbTablePre']}admin_manage WHERE id='$groupid'";
		}
		$datax_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		//print_r($datax_user);
        if(empty($datax_user['manage_list'])){$datax_user['manage_list']='0';}
		$sql="SELECT * FROM  {$GLOBALS['dbTablePre']}admin_telcount b WHERE uid in ({$datax_user['manage_list']}) and type=2 and ".$time_between;
		$all_group_user_arr=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		

		foreach($all_group_user_arr as  $key=>$v){
				 $group_user_name[]= $v['username'];
				 //$member_count_array[]=$member_count;
				 $all_user_count[$key]['all']=$v['member_count'];//获得每个客服总的会员数量人数
				
				 $sql="SELECT  count(b.uid) as tnum FROM {$GLOBALS['dbTablePre']}members m left join {$GLOBALS['dbTablePre']}member_admininfo b on b.uid=m.uid  where m.sid='{$v['uid']}' and ".$time_between;
				 //echo $sql;
				 $num_distinct=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
				 $all_user_count[$key]['distinct']= $num_distinct['tnum'];//获得每个组总的未重复联系会员数

				 $all_user_count[$key]['havetel']= $v['tele_num'];//获得每个组总的联系会员数

				$del_user_num[$key]=$v['del_user_num'];
				//echo $num['count(uid)'];
			 }
			//print_r($all_user_count);
	}

	if(in_array($GLOBALS['groupid'],$GLOBALS['general_service_pre'])||$userid){
	 //售前客服
		global $grade;
		$page_per = 20;
		$page = max(1,MooGetGPC('page','integer','G'));
		$limit = 20;
		$offset = ($page-1)*$limit;

		 $user_id=$userid?$userid:$GLOBALS['adminid'];
		 $sql="SELECT member_count FROM {$GLOBALS['dbTablePre']}admin_user where uid='{$user_id}'";
		 $member_count=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);

		 $sql="SELECT  b.uid,b.dateline FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}member_admininfo b on b.uid=m.uid  where m.sid='{$user_id}' and ".$time_between."  LIMIT {$offset},{$limit}";
		 $user_last_time=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		 
		 foreach($user_last_time as $v){
			$arruid[]=$v['uid'];
			$arrdateline[]=$v['dateline'];
		 }
		 if($arruid){
			$uid_list=implode(",",$arruid);
			$dateline=implode(',',$arrdateline);
			$sql="SELECT  * FROM  {$GLOBALS['dbTablePre']}member_backinfo  where  uid IN ({$uid_list}) and dateline IN ({$dateline}) ";
			$user_con_inf=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
			 //print_r($user_con_inf);
		 }

		 foreach($time_array as $key=>$every_time){
			 //echo $every_time[0];
			 $sql="SELECT  count(b.uid) num FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}member_admininfo b on b.uid=m.uid  where m.sid='{$user_id}'  and b.dateline>".$every_time[0]." and b.dateline<".$every_time[1];
			 //echo $sql;
			 $user_count_num_distinct=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			 $every_time_array[$key]['distinct']=$user_count_num_distinct['num'];

			 $sql="SELECT  count(b.uid) num FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}member_backinfo b on b.uid=m.uid  where m.sid='{$user_id}'  and b.dateline>".$every_time[0]." and b.dateline<".$every_time[1];
			 //echo $sql;
			 $user_count_num=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			 $every_time_array[$key]['alltele']=$user_count_num['num'];

			 $sql="SELECT count(1) num FROM {$GLOBALS['dbTablePre']}admin_deluser b where sid ='$user_id' and b.dateline>".$every_time[0]." and b.dateline<".$every_time[1];
			
			$del_user_num_arr=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			$del_user_num[$key]=$del_user_num_arr['num'];
		 }
		 //print_r($every_time_array);
		
		$sql="SELECT  count(b.uid) as tnum FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}member_admininfo b on b.uid=m.uid  where m.sid='{$user_id}'  and ".$time_between;
		$total_all=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$total=$total_all['tnum'];
		$currenturl = "index.php?action=financial_tele_info&h=list&userid=$user_id&time=$begin_time";
		$page_links = multipage( $total, $page_per, $page, $currenturl);
		//print_r($user_con_inf);
	 }
	 require_once(adminTemplate("financial_tele_info_list"));		
}

function financial_deluser_count(){
	$groupid=MooGetGPC('kefu_list','string','G');
	$kefu_id=MooGetGPC('kefuid','integer','G');
	$end_time=mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()));
	$begin_time=MooGetGPC('time','integer','G');

	$time_array=array(2=>array($end_time-3600*24,$end_time,date('d',strtotime("-1 days")).'号数据'),3=>array($end_time-3600*48,$end_time-3600*24,date('d',strtotime("-2 days")).'号数据'),4=>array($end_time-3600*72,$end_time-3600*48,date('d',strtotime("-3 days")).'号数据'),5=>array($end_time-3600*96,$end_time-3600*72,date('d',strtotime("-4 days")).'号数据'),6=>array($end_time-3600*120,$end_time-3600*96,date('d',strtotime("-5 days")).'号数据'),7=>array($end_time-3600*144,$end_time-3600*120,date('d',strtotime("-6 days")).'号数据'),8=>array($end_time-3600*166,$end_time-3600*144,date('d',strtotime("-7 days")).'号数据'));
	 if($begin_time){
		 $time_between="dateline>".$time_array[$begin_time]['0']." and dateline<".$time_array[$begin_time]['1'];
		 $now_start=$time_array[$begin_time]['2'];
	 }else{
		 //$time_between="dateline>".mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()));
		 //$time_between="dateline>1244377384";
		 $time_between="b.dateline>".$time_array[2]['0']." and b.dateline<".$time_array[2]['1'];
		 $now_start="昨天数据";
	}
	if($kefu_id){
		$page_per = 20;
		$page = max(1,MooGetGPC('page','integer','G'));
		$limit = 20;
		$offset = ($page-1)*$limit;
		$sql="SELECT * FROM {$GLOBALS['dbTablePre']}admin_deluser b where sid='{$kefu_id}' and ".$time_between." LIMIT {$offset},{$limit}";		
		$del_user_num_arr=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);

		$sql="SELECT count(*) num FROM {$GLOBALS['dbTablePre']}admin_deluser b where sid='{$kefu_id}' and ".$time_between;		
		$total_all=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		
		$total=$total_all['num'];
		$currenturl = "index.php?action=financial_tele_info&h=delcount&kefuid=$kefu_id&time=$begin_time";
		$page_links = multipage( $total, $page_per, $page, $currenturl );
	}else{
		$sql="SELECT manage_list FROM {$GLOBALS['dbTablePre']}admin_manage where id = '$groupid'";		
		$manage_list=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		if($manage_list){
			$sql="SELECT * FROM {$GLOBALS['dbTablePre']}admin_user where uid in ({$manage_list['manage_list']}) ";		
			$kefe_inf_arr=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
			
			foreach($kefe_inf_arr as $key=>$kefu_info){
				$sql="SELECT count(1) num FROM {$GLOBALS['dbTablePre']}admin_deluser b where sid='{$kefu_info['uid']}' and ".$time_between;
				
				$del_user_num_arr=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
				$del_user_num[$key]=$del_user_num_arr['num'];
			}
		}
	}
	require_once(adminTemplate("financial_deluser_count"));
}
/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'list' : MooGetGPC('h','string','G');

//note 动作列表
$hlist = array('list','delcount');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
	salert('您要打开的页面不存在');
}
//note 判断是否有权限
if(!checkGroup('financial_tele_info',$h)){
  salert('您没有此操作的权限');
}

switch( $h ){
	//note 委托列表
	case 'list':
		financial_tele_info_list();	
	break;
	case 'delcount':
		financial_deluser_count();
	break;

}
?>