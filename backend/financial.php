<?php
function financial_msm_grade_count(){
	global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	//所管理的客服id列表
	//if($_GET['user_sid']){
	$user_sid=MooGetGPC('user_sid','integer','G');
	$grid=MooGetGPC('groupid','integer','G');
	if($user_sid){
		$sid=MooGetGPC('user_sid','integer','G');
		$where = " AND sid=$sid";
		$effect_where = " AND uid=$sid";
	//}elseif($_GET['groupid']){
	}elseif($grid){
		$groupid=MooGetGPC('groupid','integer','G');
		$sql="SELECT manage_list FROM {$dbTablePre}admin_manage where id={$groupid}";
		$manage_list=$_MooClass['MooMySQL']->getOne($sql);
		$myservice_idlist=$manage_list['manage_list'];
		$where=  " and sid IN($myservice_idlist)";
		$effect_where = " AND uid in ($myservice_idlist)";
	}else{
		$myservice_idlist = get_myservice_idlist();
		if(!empty($myservice_idlist) && $myservice_idlist!='all' ){
			$where=  " and sid IN($myservice_idlist)";
			$effect_where = " AND uid in ($myservice_idlist)";
		}
	}
	$starttime=MooGetGPC('start_time','string','G');
	$endtime=MooGetGPC('end_time','string','G');
	if($starttime || $endtime){
		$start_time = strtotime(trim(MooGetGPC('start_time','string','G')));
		$end_time = strtotime(trim(MooGetGPC('end_time','string','G'))) + 86400;
		if($start_time){
			$where .= " AND `reptime` > $start_time ";
			$effect_where =" AND dateline >$start_time";
		}
		if($end_time){
			if($start_time && $end_time <= $start_time){
				$end_time = $start_time + 86400;
				$note = '由于结束时间不能小于开始时间，系统自动将结束时间更改为：'.date('Y-m-d',$end_time);
			}
			$where .= " AND `reptime` <= $end_time ";
			$effect_where =" AND dateline <=$end_time";
		}
	}
	
	$kefu_list = get_kefulist();
	if(!isset($effect_where))$effect_where='';
	$sql="SELECT sum(effect_num) as effect_num,uid FROM {$dbTablePre}admin_telcount where type=2 {$effect_where} group by uid";
	$effect_num_arr = $_MooClass['MooMySQL']->getAll($sql);
	
	foreach($effect_num_arr as $key=>$val){
		$effect_num[$val['uid']] = $val['effect_num'];
	}
    if(!isset($where))$where='';
	$sql="SELECT sum(rep) rep,sid,count(rep) num,sum(rep=4) rep4,sum(rep=5) rep5 FROM {$dbTablePre}uplinkdata where 1 {$where} group by sid";
	$msm_back_info = $_MooClass['MooMySQL']->getAll($sql);
	foreach($msm_back_info as $key=>$msm_back){
		$msm_back_info[$key]['div']=$msm_back['rep']/$msm_back['num'];
		$edu[$key]=$msm_back_info[$key]['div'];
	}
	array_multisort($edu, SORT_ASC, $msm_back_info);//二维数组排序
	
	$sql="SELECT * FROM {$dbTablePre}admin_manage where manage_list!=''";
	$all_group_name=$_MooClass['MooMySQL']->getAll($sql);
	//print_r($msm_back_info);
	require(adminTemplate('financial_msm_grade_count'));
}
//来自网站对红娘的评分
function financial_feedback_fraction() {
		global $_MooClass,$dbTablePre,$userid,$pagesize,$user_arr;
	//所管理的客服id列表
	//if($_GET['user_sid']){
	$user_sid=MooGetGPC('user_sid','integer','G');
	$grid=MooGetGPC('groupid','integer','G');
	if($user_sid){
		$sid=MooGetGPC('user_sid','integer','G');
		$where = " AND sid=$sid";
	//}elseif($_GET['groupid']){
	}elseif($grid){
		$groupid=MooGetGPC('groupid','integer','G');
		$sql="SELECT manage_list FROM {$dbTablePre}admin_manage where id={$groupid}";
		$manage_list=$_MooClass['MooMySQL']->getOne($sql);
		$myservice_idlist=$manage_list['manage_list'];
		$where=  " and sid IN($myservice_idlist)";
	}else{
		$myservice_idlist = get_myservice_idlist();
		if(!empty($myservice_idlist) && $myservice_idlist!='all' ){
			$where=  " and sid IN($myservice_idlist)";
		}
	}
	$starttime=MooGetGPC('start_time','string','G');
	$endtime=MooGetGPC('end_time','string','G');
	if($starttime || $endtime){
		$start_time = strtotime(trim(MooGetGPC('start_time','string','G')));
		$end_time = strtotime(trim(MooGetGPC('end_time','string','G'))) + 86400;
		if($start_time){
			$where .= " AND `submitdate` > $start_time ";
		}
		if($end_time){
			if($start_time && $end_time <= $start_time){
				$end_time = $start_time + 86400; //结束时间小于开始时间，默认搜索一天
			}
			$where .= " AND `submitdate` <= $end_time ";
		}
	}
	
	$kefu_list = get_kefulist();
	//总评分
	if(!isset($where)){$where='';}
	$sql="SELECT sum(fraction) sum, count(gid) as gid_sum, sid FROM {$dbTablePre}service_getadvice where stat2=2 and fraction >= 1 {$where} group by sid";
	$feedback_arr = $_MooClass['MooMySQL']->getAll($sql);
	$sid_arr = array();
	foreach($feedback_arr as $key => $val) {
		$sid_arr[] = $val['sid'];
	}
	$sid_str = implode(',', $sid_arr);
	if($sid_str) {
		//差评分
		$sql = "select sid, count(gid) as bad_gidsum from {$dbTablePre}service_getadvice where stat2=2 and fraction >= 1 {$where} and sid in($sid_str) and fraction <= 2 group by sid";
		$bad_feedback = $_MooClass['MooMySQL']->getAll($sql);
		$num = count($bad_feedback);
		foreach($bad_feedback as $key1 => $val1) {
			foreach($feedback_arr as $key2 => $val2) {
				if($val1['sid'] == $val2['sid']) {
					$feedback_arr[$key2] = array_merge($val1, $val2);
					continue;
				}
			}
		}
	}
	
	$sql="SELECT * FROM {$dbTablePre}admin_manage where manage_list!=''";
	$all_group_name=$_MooClass['MooMySQL']->getAll($sql);
	
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	
	require(adminTemplate('financial_feedback_fraction'));
}

//会员跟进步骤统计
function financial_member_grade_count(){
	global $_MooClass,$dbTablePre,$userid,$pagesize,$grade;
	$page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;

	$myservice_idlist = get_myservice_idlist();
	if(empty($myservice_idlist)){
		$condition[] = " sid IN({$GLOBALS['adminid']})";
	}elseif($myservice_idlist == 'all'){
		//all为客服主管能查看所有的
	}else{
		$condition[] = " sid IN($myservice_idlist)";
	}
	if($_POST['start_time'] || $_POST['end_time']){
		$start_time = strtotime(trim(MooGetGPC('start_time','string','P')));
		$end_time = strtotime(trim(MooGetGPC('end_time','string','P'))) + 86400;
		if($start_time){
			$condition[] = " dateline > $start_time ";
		}
		if($end_time){
			if($start_time && $end_time <= $start_time){
				$end_time = $start_time + 86400; //结束时间小于开始时间，默认搜索一天
			}
			$condition[] = " dateline <= $end_time ";
		}
	}

	if($condition){
		$result = "where ".implode(' AND ',$condition);
	}
	
	$sql="SELECT uid,sid,dateline,states FROM {$dbTablePre}change_grade {$result} order by dateline desc limit {$offset},{$limit}";
	$total = getcount('change_grade',$result);
	$currenturl = "index.php?action=financial&h=member_grade_count";
   	$pages = multipage( $total, $limit, $page, $currenturl );

	$con_info=$_MooClass['MooMySQL']->getAll($sql);
	foreach($con_info as $v){
	
	}

	//print_r($con_info);
	require(adminTemplate('financial_member_grade_count'));
}

//note 不可回收会员的来源统计
function financial_delstatistics(){
	global $_MooClass,$dbTablePre,$del_cause_arr;
	$condition = array();
	
	
	/*$wf = $st = '';
	if($_GET['wf']){
		$wf = MooGetGPC('wf','string', 'G');
		$wf = 'wf='.$wf;
	}
	if($_GET['st']){
		$st = MooGetGPC('st','string', 'G');
		$st = 'st='.$st;
	}
	
	if($wf || $st){
		$source = $wf.'&'.$st;
		$condition[] = " source='{$source}'";
	}*/
	
    
    $source = MooGetGPC('source','string','G');
    if(!empty($source)){
      $condition[] = " source='{$source}'";
    }

	
    $regdate1 = strtotime(MooGetGPC('regdate1','string','G'));
    if(!empty($regdate1)){
		$condition[] = " regdate>='{$regdate1}'";
    }
    
	
    $regdate2 = strtotime(MooGetGPC('regdate2','string','G'));
    if(!empty($regdate2)){
		$condition[] = " regdate<='{$regdate2}'";
    }
    
	
	$reason = MooGetGPC('reason','integer','G');
	if(!empty($reason)){
		$condition[] = " reason='{$reason}'";
	}
	
	
	
	if(!empty($condition)){
		$condition_sql = implode(' and ',$condition);
		$sqlc = "SELECT count(uid) count FROM {$dbTablePre}delstatistics WHERE $condition_sql";
		$resc = $_MooClass['MooMySQL']->getOne($sqlc);
		$count = $resc['count'];

		$sql = "SELECT count(uid) count,reason FROM {$dbTablePre}delstatistics WHERE $condition_sql group by reason";
		$res = $_MooClass['MooMySQL']->getAll($sql);
		$one = $two = $three = $four = $five = $six = $seven = 0;
		foreach($res as $k=>$v){
			if($v['reason']==1) $one = $v['count'];
			if($v['reason']==2) $two = $v['count'];
			if($v['reason']==3) $three = $v['count'];
			if($v['reason']==4) $four = $v['count'];
			if($v['reason']==5) $five = $v['count'];
			if($v['reason']==6) $six = $v['count'];
			if($v['reason']==7) $seven = $v['count'];
		}
//		echo $one."<br>".$two."<br>".$three."<br>".$four."<br>".$five."<br>".$six."<br>".$seven;
		$sqlcount = "SELECT count(uid) count,source FROM {$dbTablePre}delstatistics WHERE $condition_sql group by source";
		$countotal = $_MooClass['MooMySQL']->getAll($sqlcount);
		$total = count($countotal);
		$page_per = 20;
		$page = max(1,MooGetGPC('page','integer','G'));
		$limit = 20;
		$offset = ($page-1)*$limit;
		
		$sqls = "SELECT count(uid) count,source FROM {$dbTablePre}delstatistics WHERE $condition_sql group by source limit $offset,$limit";
		$ress = $_MooClass['MooMySQL']->getAll($sqls);

		$arr = array();
		$i = 1;
		foreach($ress as $k=>$v){
			$source = $v['source'];
			$condition_sql.$i = " and source='{$source}'";
			$sqlr = "SELECT count(uid) count,reason FROM {$dbTablePre}delstatistics WHERE ".$condition_sql.$i." group by reason";
			
			//echo $sqlr.'<br>';
			$resr = $_MooClass['MooMySQL']->getAll($sqlr);
			foreach($resr as $key=>$value){
				if($value['reason']==1) $v['one'] = $value['count'];
				if($value['reason']==2) $v['two'] = $value['count'];
				if($value['reason']==3) $v['three'] = $value['count'];
				if($value['reason']==4) $v['four'] = $value['count'];
				if($value['reason']==5) $v['five'] = $value['count'];
				if($value['reason']==6) $v['six'] = $value['count'];
				if($value['reason']==7) $v['seven'] = $value['count'];
			}
			$i++;
			unset($resr);
			$arr[] = $v;
		}
		
		
		
	}
	
	$sql = "select distinct(source) as source from {$dbTablePre}delstatistics where source!='' and source!='&'";
    $result=$_MooClass['MooMySQL']->getAll($sql);

	
	
	
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	if(empty($total))$total=0;
	if(empty($page_per))$page_per=20;
	if(empty($page))$page=1;
	$pages = multipage($total, $page_per, $page, $currenturl);
    if(empty($count)){
    $count=0;
    $one = $two = $three = $four = $five = $six = $seven = 0;
    }
	require(adminTemplate('financial_delstatistics'));
}


function financial_showreason(){
	global $_MooClass,$dbTablePre;
	$condition = array();
	$wf = $st = '';
	if($_GET['wf']){
		$wf = MooGetGPC('wf','string', 'G');
		$wf = 'wf='.$wf;
	}
	if($_GET['st']){
		$st = MooGetGPC('st','string', 'G');
		$st = 'st='.$st;
	}
	$source = $wf.'&'.$st;
	if($source!='&'){
		$condition[] = " d.source='{$source}'";
	}
	if($_GET['reason']){
		$reason = MooGetGPC('reason','string', 'G');
		$condition[] = " d.reason='{$reason}'";
	}
	if($_GET['regdate1']){
		$regdate1 = strtotime(MooGetGPC('regdate1','string','G'));
		$condition[] = " d.regdate>='{$regdate1}'";
	}
	if($_GET['regdate2']){
		$regdate2 = strtotime(MooGetGPC('regdate2','string','G'));
		$condition[] = " d.regdate<='{$regdate2}'";
	}

	$condition_sql = '';
	if($condition){
		$condition_sql = implode(' and ',$condition);
	}
//	echo $condition_sql;die;
	if($condition_sql){
		$count =$_MooClass['MooMySQL']->getOne("select count(*) count from {$dbTablePre}delstatistics d left join {$dbTablePre}member_backinfo b on d.uid=b.uid where $condition_sql and b.comment!=''");
		$total = $count['count'];
		$page_per = 20;
		$page = max(1,MooGetGPC('page','integer','G'));
		$limit = 20;
		$offset = ($page-1)*$limit;
	
		$sqlu = "select b.uid,b.comment from {$dbTablePre}delstatistics d left join {$dbTablePre}member_backinfo b on d.uid=b.uid where $condition_sql and b.comment!='' limit $offset,$limit";
		$resu = $_MooClass['MooMySQL']->getAll($sqlu);
		
		$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		$pages = multipage($total, $page_per, $page, $currenturl);

		require(adminTemplate('financial_showreason'));
	}
}


//note  客服 当日新分 会员 类 统计报表 
function financial_NewClassStat(){
	global $_MooClass,$dbTablePre,$grade;
	unset($grade[2]);unset($grade[3]);unset($grade[7]);unset($grade[8]);unset($grade[9]);unset($grade[10]);unset($grade[11]);unset($grade[12]);
	$condition = array();
    $id="";
	$total="";
	$page_per=0;
	$page=0;

	
	//判断当前登录客服 所在的组
	$sql = "SELECT id,manage_list FROM {$dbTablePre}admin_manage WHERE type=1";
	$ret = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	$user_list = '';
    //echo $GLOBALS['adminid'];
	foreach( $ret as $v){
	    $user_list_arr=array();
		
		$user_list = $v['manage_list'];
		$user_list_arr = explode(',', $user_list);
        
		if(in_array($GLOBALS['adminid'] , $user_list_arr)){
		   $id=$v['id'];
		   break;
	    }

	}

	unset($ret);
    unset($user_list_arr);
    // print_r($user_list_arr);exit('dddd');
	
    //按所属组检索
    $groupid = MooGetGPC('groupid','string','G');
	
	
    if(empty($groupid)){
      $groupid=$id;
    }
	
	//既不属于当前组，也没选择组
	if(empty($groupid)) {
	   $sql = "SELECT id FROM {$dbTablePre}admin_manage WHERE type=1 order by id limit 1";
	   $ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	   $groupid=$ret['id'];
	}

	
	//返回所选组的客服ID列表
    $sql = "SELECT id,manage_list FROM {$dbTablePre}admin_manage WHERE id={$groupid}";
	$ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	$user_list=$ret['manage_list'];
	$user_list_arr=explode(',',$user_list);

	
	$time=date('Y-m-d');

    //今天
	$tEnd=strtotime ("+1 day");
	$tStart=strtotime(date('Y-m-d')); //相对今天的昨天
	//$alloctime=date("Y-m-d");

		  
	   

	$where_byt ="";
    if(!empty($tEnd) && !empty($tStart)){
		$where_byt = " and ma.dateline<'{$tEnd}' and ma.dateline>='{$tStart}'";//昨天今日之间
    }
	
	
	$where_t='';
	if(!empty($tEnd)){
		$where_t = " and ma.dateline<'{$tEnd}'";//截止今日23点59分59秒
    }
    
	$where_y='';
	if(!empty($tStart)){
		$where_y = " and ma.dateline<'{$tStart}'";//截止昨日
    }
	
	
	$effectGradeArr=array();
	ksort($grade);
   
	foreach($user_list_arr as $sid){//当前组下所有客服
	    $res_t=$res_y=$res_t_=array();
	    
		$sql="select uid from {$dbTablePre}allotuser  where  allot_time<'{$tEnd}' and allot_time>='{$tStart}' and  allot_sid={$sid}";
		$_r_ = $_MooClass['MooMySQL']->getAll($sql,0,0,0,true);

		//$todayAllocate=$_r_['num'];//当天新分的
		$todayAllocateArr=array();
		foreach($_r_ as $_v_){
		    if(empty($_v_['uid'])) continue;
		    $todayAllocateArr[$_v_['uid']]=1;
		}
		
		unset($_r_);
		unset($_v_);
		
		$sql="select uid from {$dbTablePre}allotuser  where  allot_time<'{$tEnd}' and allot_time>='{$tStart}' and  allot_sid={$sid}";
		$r = $_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
		
		$comma='';
		$useridlist='';
		foreach($r as $v){
		    if(empty($v)) continue;
			$useridlist.=$comma;
		    $useridlist.=$v['uid'];
			$comma=',';
		}
		unset($r);
        
		//新分删除的
		//$todayADelete=0;
		
		if (!empty($useridlist)) $sql="select m.uid,effect_grade from {$dbTablePre}member_admininfo ma left join {$dbTablePre}members_search m on ma.uid=m.uid where m.uid in({$useridlist})";
        $__r__ = $_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
	    
		$todayAlocatedeleteArr=array();
		foreach($__r__ as $__v__){
		    if(empty($__v__['uid'])) continue;
		    if($__v__['effect_grade']==10)  { $todayAlocatedeleteArr[$__v__['uid']]=1;}//$todayADelete++;
			//if($v__['sid']=='123') $todayADelete++;
		}
	    
		
		
		unset($__r__);
		unset($__v__);
		
		//0类原有的
		$res_original=$r_o=array();
		$sql="select m.uid from {$dbTablePre}member_admininfo ma left join {$dbTablePre}members_search m on ma.uid=m.uid where ma.effect_grade=1 and  m.sid={$sid}  {$where_y} ";
		$res_original = $_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
		
		foreach($res_original as $v_o){
			$r_o[$v_o['uid']]=1;
		}
		
		unset($v_o);
		unset($res_original);
		
		/*********当日跟进的**********/
		//(1)当日0类跟进的
		$todayZeroTelArr=array();
		$sql="select m.uid from {$dbTablePre}member_admininfo ma left join {$dbTablePre}members_search m on ma.uid=m.uid where m.sid={$sid}  {$where_byt} and m.uid in({$useridlist})";
        
		$r_=$_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
		foreach($r_ as $v_){
		    if(empty($v_['uid'])) continue;
		    $todayZeroTelArr[$v_['uid']]=1;
		}
		
		unset($r_);
		unset($v_);
		//$todayZeroTel=$r_['t']?$r_['t']:0;
		//(2)其他类跟进,新开的
		
		$effectGrade_=array();
		
		$effectGrade_[1][0]=$r_o;
	    $effectGrade_[1][1]=$todayAllocateArr;
		$effectGrade_[1][2]=$todayZeroTelArr;
		$effectGrade_[1][3]=$todayAlocatedeleteArr;
	
		foreach($grade as $k=>$_v_){
		    if($k==1) continue;
		    $res_t=$res_y=$res_t_=array();
			$r_t=$r_y=$r_t_=array();
			$sql="select effect_grade,m.uid  as uid from {$dbTablePre}member_admininfo ma left join {$dbTablePre}members_search m on ma.uid=m.uid where ma.effect_grade={$k} and  m.sid={$sid}   {$where_t}";
			$res_t = $_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
		   
			foreach($res_t as $v_t){
			    if(empty($v_t['effect_grade'])) continue;
			    $r_t[$v_t['uid']]=$v_t['effect_grade'];
			}
			
			unset($res_t);
            unset($v_t);
			
			$sql="select effect_grade,m.uid as uid from {$dbTablePre}member_admininfo ma left join {$dbTablePre}members_search m on ma.uid=m.uid where ma.effect_grade={$k} and  m.sid={$sid}   {$where_byt}";
			$res_t_ = $_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
		    
			foreach($res_t_ as $v_t_){
			    if(empty($v_t_['effect_grade'])) continue;
			    $r_t_[$v_t_['uid']]=$v_t_['effect_grade'];
			}
			unset($res_t_);
            unset($v_t_);
			

			$sql="select effect_grade,m.uid,ma.dateline from {$dbTablePre}member_backinfo ma left join {$dbTablePre}members_search m on ma.uid=m.uid where   m.sid={$sid}  {$where_y} order by m.uid,ma.dateline ";
			$res_y = $_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
			
			foreach($res_y as $v_y){
			    $r_y[$v_y['uid']]=$v_y['effect_grade'];
			}
			
			foreach($r_y as $ka=>$va){
			    if($va!=$k) unset($r_y[$ka]);
			}
		    
			unset($res_y);
            unset($v_y);

		    $newGradeArr=$deleteGradeArr=$followupGradeArr=array();
			$effectGrade_[$k][0]=$r_y;  //原有的
			$newGradeArr=array_diff_key($r_t, $r_y);$effectGrade_[$k][1]=$newGradeArr;//当日新开的
			$deleteGradeArr=array_diff_key($r_y,$r_t);$effectGrade_[$k][3]=$deleteGradeArr;//当日删除的
			$followupGradeArr=array_intersect_assoc($r_t_, $r_y);$effectGrade_[$k][2]=$followupGradeArr;//当日跟进的
			
			
			unset($r_t_);
			unset($r_t);
			unset($r_y);
		}
		
		
	
		
		
	
		$sql="select usercode,username from web_admin_user where uid='{$sid}'";
		$kefu=$_MooClass['MooMySQL']->getOne($sql,true);
		
		//输出总的变量		
		$effectGradeArr[]=array("sid"=>$sid,"usercode"=>$kefu['usercode'],"username"=>$kefu['username'],"effectGrade"=>$effectGrade_);

	}
	
	
	

	//组
	if(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr'])){//主管
	   $sql = "select id,manage_name  from {$dbTablePre}admin_manage where type=1";
       $group=$_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
    }elseif(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_team'])){//队长
	   $sql = "select id,manage_list,leader_uid from {$dbTablePre}admin_manage where type=2";
       $group=$_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
	   

	   foreach($group as $value){
		   $leader_uid=$value['leader_uid'];
		   $leader_uid=explode(',',$leader_uid);

		   if(in_array($GLOBALS['adminid'],$leader_uid)){
		      $manage_list=$value['manage_list'];
			  $manage_arr=explode(',',$manage_list);
			  break;
		   }
	   }  
	   
	   $group=array();
	   if(!empty($manage_arr)){
		   foreach($manage_arr as $id){
			  $sql = "select id,manage_name  from {$dbTablePre}admin_manage where id={$id}";
			  $res=$_MooClass['MooMySQL']->getOne($sql);
			  $group[]=array("id"=>$res['id'],"manage_name"=>$res['manage_name']);
			  
		   }
		}
	}
	unset($res);
    $currenturl="index.php?action=financial&h=NewClassStat";
    
	//加载模板
	require(adminTemplate('financial_NewClassStat'));
}




//note  客服 名下 会员 类 统计报表 
function financial_classStatistics(){
	global $_MooClass,$dbTablePre,$grade;
	$condition = array();
    $id="";
	$total="";
	$page_per=0;
	$page=0;
	
	//判断当前登录客服 所在的组
	$sql = "SELECT id,manage_list FROM {$dbTablePre}admin_manage WHERE type=1";
	$ret = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	$user_list = '';
    //echo $GLOBALS['adminid'];
	foreach( $ret as $v){
	    $user_list_arr=array();
		
		$user_list = $v['manage_list'];
		$user_list_arr = explode(',', $user_list);
        
		if(in_array($GLOBALS['adminid'] , $user_list_arr)){
		   $id=$v['id'];
		   break;
	    }

	}
	
	unset($ret);
    unset($user_list_arr);
    // print_r($user_list_arr);exit('dddd');
	
    //按所属组检索
    $groupid = MooGetGPC('groupid','string','G');
	
	
    if(empty($groupid)){
      $groupid=$id;
    }
	
	//既不属于当前组，也没选择组
	if(empty($groupid)) {
	   $sql = "SELECT id FROM {$dbTablePre}admin_manage WHERE type=1 order by id limit 1";
	   $ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	   $groupid=$ret['id'];
	}
	
	/* $myservice_idlist = get_myservice_idlist();
	
	echo $myservice_idlist;exit; */
	
	
	//返回所选组的客服ID列表
    $sql = "SELECT id,manage_list FROM {$dbTablePre}admin_manage WHERE id={$groupid}";
	$ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	$user_list=$ret['manage_list'];
	$user_list_arr=explode(',',$user_list);
	
	
	require 'data/kefulist_cache.php';
	
	$userArr=array();
	foreach($user_list_arr as $v){
	    foreach ($kefu_arr as $k_=>$v_){
	        if($v==$k_){
			    $userArr[$v]=$v_;
			}
	    }
	}
	
	//$condition[] = " am.id='{$groupid}'";//web_admin_manage表

	//开始时间（最后一次分配时间）
	/* $startTime=MooGetGPC('alloctimeStart','string','G');
    $alloctimeStart = strtotime($startTime);
	
    if(!empty($alloctimeStart)){
		$condition[] = " ma.allto_time>='{$alloctimeStart}'"; //web_member_admininfo表
    } */
    
	//结束时间（最后一次分配时间）
	$alloctimeStart=$alloctimeEnd='';
	$curdate='';
	
	$time=date('Y-m-d');

	$endTime=MooGetGPC('alloctimeEnd','string','G');
	$alloctimeStart=strtotime($endTime);//当日0时0分
	
	
	if(empty($endTime)) {
	    $alloctimeEnd =strtotime($time." +1 day");//今天最后截止
		$relativeAlloctimeEnd=strtotime(date('Y-m-d')); //相对今天的昨天
		$alloctimeStart=strtotime(date('Y-m-d'));
	}else{
        $alloctimeEnd = strtotime ($endTime."+1 day");
	    $relativeAlloctimeEnd=strtotime($endTime);
    }
	//今天,昨天，前天
	$curdate=MooGetGPC('day','string','G');

    switch($curdate){
	   case "today": //今天
	      $alloctimeEnd=strtotime ("+1 day");
		  $endTime=date("Y-m-d");
		  $relativeAlloctimeEnd=strtotime(date(	'Y-m-d')); //相对今天的昨天
		  break;
	   case "yesterday":
	      $alloctimeEnd=strtotime(date('Y-m-d'));
		  $relativeAlloctimeEnd=strtotime("-1 day");
		  $endTime=date("Y-m-d",$relativeAlloctimeEnd);
		  break;
	   case "beforeyesterday": //前天
	      $alloctimeEnd=strtotime("-1 day");
		  $relativeAlloctimeEnd=strtotime("-2 day");//相对前天的昨天
		  $endTime=date("Y-m-d",$relativeAlloctimeEnd);
	      break;
	  /*  case 'bigbeforeyesterday':  //大前天
	      $alloctimeEnd=strtotime("-2 day");
		  $endTime=date("Y-m-d",$alloctimeEnd);
		  break; */
	}

	
    if(!empty($alloctimeEnd)){
		$condition[] = " ma.allto_time<'{$alloctimeEnd}'";//web_member_admininfo表
    }
     // echo $startTime.'and'.$alloctimeStart.'and'.$alloctimeEnd;
	
	$isDo = MooGetGPC('isDo','integer','G');
	if(!empty($isDo)){
		$condition[] = " reason='{$isDo}'";
	}
	
	
	$effectGradeArr=array();
	
	if(!empty($condition)){
		$condition_sql = implode(' and ',$condition);
	    $where = " and  $condition_sql";
	}else{
	    $where ="";
    }
    
	// echo $where;exit;
	$sum=$sum1=$sum0=$sum2=$sum3=$sum4=$sum5=$sum6=$sum7=$sum8=$sum9=$sum10=0;
    
	$res=array();
	foreach($userArr as $sid=>$username){//当前组下所有客服

		$sql="select effect_grade,count(m.uid) as cGrade from {$dbTablePre}member_admininfo ma left join {$dbTablePre}members_search m on ma.uid=m.uid where m.sid={$sid} {$where} group by ma.effect_grade";
		$res = $_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
		
		$sumGrade=0;//客服名下会员总数
		$effectGrade=array();//重新开始
		
		if(empty($res)) continue;
		 // print_r($res);exit;
		foreach($res as $value){
		  
			
			if($value['effect_grade']>=1){
			   //$effect_grade=$value['effect_grade']-1;
			   $effect_grade=$value['effect_grade'];
			   $cGrade=$value['cGrade'];
			   
			   $sumGrade=$sumGrade+$cGrade;
			   
			   
			   $effectGrade[]=array("effect_grade"=>$effect_grade,"cGrade"=>$cGrade);
			   
			   
			}
			
		}
		
		
		
		
		
		$KFC=array(); //重新开始一次循环初始化 
		$McDonald=array();//重新开始一次循环初始化 
		
		//这里的键值是从0开始的
		foreach($effectGrade as $value){
			$KFC[]=$value['effect_grade'];
		}
		 // print_r($effectGrade);exit;
		$usa=implode(',',$KFC);
		
		//GRADE数组键值是 从1开始的 ,改变为0，1，... ，10
		$McDonald=array_keys($grade);
		/* foreach($McDonald as $value){
		   $tmp[]=$value - 1;
		} 
		
		$china=implode(',',$tmp);
		*/
		
		
		//转换
		/* $McDonald=explode(',',$china);
		$KFC=explode(',',$usa); */
		
		//找出没有的类
		$restKeys=array();
		foreach($McDonald as $you){
		   if(!in_array($you,$KFC)){
			  $restKeys[]=$you;
		   }  	
		}
		//print_r($restKeys);exit;
				 
		   
		
		
		//插入按照 2类，0个 或者 3类，0个
		$iphone=sizeof($effectGrade);
		$android=sizeof($grade);
		if($iphone<$android){

		   for($i=0;$i<$android - $iphone;$i++){
			   $effectGrade[]=array("effect_grade"=>$restKeys[$i],"cGrade"=>0);
			}
		} 
		
		unset($restKeys);
		
		//排序 
		//print_r($effectGrade);exit;
		foreach($effectGrade as $t_k=>$t_v){
			$hongkong[$t_k]=$t_v['effect_grade'];
			
			//$beijing[$t_k]=$t_v['cGrade'];
		}
		array_multisort($hongkong, SORT_ASC,  $effectGrade);
		
		
		
		foreach($effectGrade as $value){
			if($value['effect_grade']==1)   $sum0+=$value['cGrade'];
			if($value['effect_grade']==2)   $sum1+=$value['cGrade'];
			if($value['effect_grade']==3)   $sum2+=$value['cGrade'];
			if($value['effect_grade']==4)   $sum3+=$value['cGrade'];
			if($value['effect_grade']==5)   $sum4+=$value['cGrade'];
			if($value['effect_grade']==6)   $sum5+=$value['cGrade'];
			if($value['effect_grade']==7)   $sum6+=$value['cGrade'];
			if($value['effect_grade']==8)   $sum7+=$value['cGrade'];
			if($value['effect_grade']==9)   $sum8+=$value['cGrade'];
			if($value['effect_grade']==10)  $sum9+=$value['cGrade'];
			if($value['effect_grade']==11)  $sum10+=$value['cGrade'];
		}	
		
		//是否盘库(组长当日是否给客服有评语）
		$effectgrade_id='';
		$curtime=strtotime(date('Y-m-d'));
		$nexttime=strtotime("+1 day");
	    $sql="select id from {$dbTablePre}member_effectgrade where sid='{$sid}' and dateline>={$alloctimeStart} and dateline<{$alloctimeEnd}";
		$remarkWritten=$_MooClass['MooMySQL']->getOne($sql,true);
		if(!empty($remarkWritten)){
		   $effectgrade_id=$remarkWritten['id'];
        }		
		
		//今天的3类会员数量
		$res3=array();
		$sql="select count(m.uid) as tGrade from {$dbTablePre}member_admininfo ma left join {$dbTablePre}members_search m on ma.uid=m.uid where m.sid={$sid} and ma.effect_grade=4 and ma.allto_time>='{$relativeAlloctimeEnd}' and ma.allto_time<'{$alloctimeEnd}'";
		$res3 = $_MooClass['MooMySQL']->getOne($sql,0,0,0,true);
		if(!empty($res3)) $effectGrade3=$res3['tGrade'];
		
		//输出总的变量		
		$effectGradeArr[]=array("effectgrade_id"=>$effectgrade_id,"sid"=>$sid,"username"=>$username,"effectGrade"=>$effectGrade,"sumGrade"=>$sumGrade,'effectGrade3'=>$effectGrade3);

		// print_r($effectGradeArr);echo "<br>"."<br>";
	    
		
		

	}

    $sum=$sum0+$sum1+$sum2+$sum3+$sum4+$sum5+$sum6+$sum7+$sum8+$sum9+$sum10;   //当前客服组的所有会员数  
	if($sum>0){
		$p0=round(($sum0/$sum),3)*100;
		$p1=round(($sum1/$sum),3)*100;
		$p2=round(($sum2/$sum),3)*100;
		$p3=round(($sum3/$sum),3)*100;
		$p4=round(($sum4/$sum),3)*100;
		$p5=round(($sum5/$sum),3)*100;
		$p6=round(($sum6/$sum),3)*100;
		$p7=round(($sum7/$sum),3)*100;
		$p8=round(($sum8/$sum),3)*100;
		$p9=round(($sum9/$sum),3)*100;
		$p10=round(($sum10/$sum),3)*100;
    }       		 

		
	
	
	//组
	if(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_arr'])){//主管
	   $sql = "select id,manage_name  from {$dbTablePre}admin_manage where type=1";
       $group=$_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
    }elseif(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_team'])){//队长
	   $sql = "select id,manage_list,leader_uid from {$dbTablePre}admin_manage where type=2";
       $group=$_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
	   
	  /*  $manage_list='';
	   $commat = '';
	   foreach($group as $value){
	       $manage_list.=$commat .$value['manage_list'];
		  $commat = ',';
	   } */
	   foreach($group as $value){
		   $leader_uid=$value['leader_uid'];
		   $leader_uid=explode(',',$leader_uid);
		   // print_r($leader_uid);exit;
		   // echo $GLOBALS['adminid'];exit;
		   if(in_array($GLOBALS['adminid'],$leader_uid)){
		      $manage_list=$value['manage_list'];
			  $manage_arr=explode(',',$manage_list);
			  break;
		   }
	   }  
	   
	   $group=array();
	   if(!empty($manage_arr)){
		   foreach($manage_arr as $id){
			  $sql = "select id,manage_name  from {$dbTablePre}admin_manage where id={$id}";
			  $res=$_MooClass['MooMySQL']->getOne($sql);
			  $group[]=array("id"=>$res['id'],"manage_name"=>$res['manage_name']);
			  
		   }
		}
	}
	unset($res);
	// print_r($group);
	
	/* $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	$currenturl=preg_replace("/www./",'',$currenturl);
	$currenturl=preg_replace("/&day=\w+/",'',$currenturl);
	$currenturl=preg_replace("/&alloctimeEnd=\w+/",'',$currenturl); */
	$currenturl="index.php?action=financial&h=classStatistics";
	
	// echo $currenturl;
	$pages = multipage($total, $page_per, $page, $currenturl);
    
	//加载模板
	require(adminTemplate('financial_classStatistics'));
}




/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string');

//note 动作列表
$hlist = array('msm_grade_count', 'feedback_fraction','member_grade_count','delstatistics','showreason','classStatistics','NewClassStat');

//note 判断页面是否存在
if(!in_array($h,$hlist)){
	salert('您要打开的页面不存在');
}
//note 判断是否有权限
if(!checkGroup('financial',$h)){
	salert('您没有修改此操作的权限');
}

switch($h){
	//note 来自网站对红娘的评分
	case 'feedback_fraction':
		financial_feedback_fraction();
	    break;
	//note 短信评分统计
	case 'msm_grade_count':
		financial_msm_grade_count();
	    break;
	//会员跟进步骤统计
	case 'member_grade_count':
		financial_member_grade_count();
	    break;
	//note 不可回收会员的来源统计
	case 'delstatistics':
		financial_delstatistics();
	    break;
	case 'showreason':
		financial_showreason();
	    break;
	//会员当日新分类会员统计
	case 'NewClassStat':
	    financial_NewClassStat();
		break;
	
	//会员名下类统计
	case 'classStatistics':
	    financial_classStatistics();
		break;

}
?>