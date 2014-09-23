<?php
//include './include/active_function.php';
//note上线列表 uplink
function active_uplink_list(){
    $condition=array();
    $type = MooGetGPC('type','string',"G");

   //if(isset($_GET['xx']) && !empty($_GET['xx'])) {
    	$choose = MooGetGPC('choose','string',"G");
    	$keyword = MooGetGPC('keyword','string',"G");
    	$startdate=MooGetGPC('startdate','string',"G");
    	$enddate=MooGetGPC('enddate','string',"G");
        $starttime= strtotime($startdate);
    	$endtime= strtotime($enddate);
         
		if(!empty($starttime)) $condition[]="reptime>".$starttime;
		if(!empty($endtime)) $condition[]="reptime<".$endtime;
        if(!empty($choose)){
     	
     	  if(!empty($startdate)&&!empty($enddate)){
            if(!empty($keyword)){
			   $condition[]=$choose ."= ".$keyword;
    	    }
    	  }
    	          
        }

    //}
	
    //note分页处理
	$page = max(1,MooGetGPC('page','integer'));
    $limit = 15;
    $offset = ($page-1)*$limit;
	
	
	//得到所属客服下的会员
    $myservice_idlist = get_myservice_idlist();
    $myservice_idlist=empty($myservice_idlist)?'all':$myservice_idlist;
    //计算总记录数
    //note 所管理的用户列表
    $adminid=$GLOBALS['adminid'];
    if(empty($myservice_idlist)){
	   $condition[]="sid={$adminid}";
    }elseif($myservice_idlist == 'all'){
	   //all为客服主管能查看所有的 
    }else{
	   $condition[]= "  sid in ({$myservice_idlist})";  
    }
	
    $where=implode(" and ",$condition);	
   
	if(!empty($where))  $where=" where ".$where;

	
	if(!empty($where)){
	   $sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}uplinkcontent ".$where;	
	   $sql_detail = "SELECT * FROM {$GLOBALS['dbTablePre']}uplinkcontent ".$where ." order by reptime desc  LIMIT {$offset},{$limit}";
	}else{
	   $sql = "SELECT COUNT(*) num FROM {$GLOBALS['dbTablePre']}uplinkcontent {$where}";
	   $sql_detail = "SELECT * FROM {$GLOBALS['dbTablePre']}uplinkcontent  {$where} order by reptime desc  LIMIT {$offset},{$limit}";	
	}
	
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);

			
	$user_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql_detail);

	$currenturl="index.php?action=active_uplink&h=list&choose={$choose}&keyword={$keyword}&startdate={$startdate}&enddate={$enddate}";

	$currenturl = $currenturl."&type=$type";
	
	$pages = multipage( $total['num'], $limit, $page, $currenturl );
	
	//note 跳转到某一页
   	$page_num = ceil($total['num']/$limit);
	
   	//note 插入日志
	serverlog(1,$GLOBALS['dbTablePre'].'servies',"{$GLOBALS['username']}查看会员上行列表", $GLOBALS['adminid']);
	//note 载入模块
	require(adminTemplate('active_uplink_list'));
}

/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'list' : MooGetGPC('h','string','G');

//note 动作列表
$hlist = array('list');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=site_media');
}

//note 判断是否有权限
if(!checkGroup('active_uplink',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?action=site&h=site_media');
}

switch( $h ){
	//note 委托列表
	case 'list':
		active_uplink_list();	
	break;
}
?>