<?php
/*******************************************逻辑层(M)/表现层(V)*****************************************/
//note 钻石会员推荐列表

function site_recommend_diamond_list(){
	global $_MooClass,$dbTablePre;
	$diamond_filename = "../data/cache/diamond_intro.php";
	if(!file_exists($diamond_filename)){
		echo "钻石会员推荐列表没有生成！";
		exit;
	}else{
		$diamond = file($diamond_filename);
//		print_r($diamond);die;
		if($diamond){
			$diamondlist = trim($diamond['0'],',');
			$sql = "select m.uid,m.birthyear,m.province,m.city,m.marriage,m.s_cid,m.nickname,b.mainimg,m.images_ischeck,d.introduce 
			        from {$dbTablePre}members_search m 
			        left join {$dbTablePre}members_base as b on m.uid=b.uid
			        left join {$dbTablePre}members_choice c on m.uid=c.uid 
			        left join {$dbTablePre}members_introduce d on m.uid=d.uid 
			        where m.uid in($diamondlist) limit 20";
			$diamondarr = $_MooClass['MooMySQL']->getAll($sql,0,0,0,true);
		}
	}
	require_once(adminTemplate('site_recommend_diamond_list'));
}

//note 提交钻石会员推荐列表 
function site_recommendget(){
	global $_MooClass,$dbTablePre;
	$ispost = MooGetGPC('ispost','integer','G');
	if($ispost=='1'){
		$str = $_SERVER['REQUEST_URI'];
		preg_match_all('/uidlist=(\d*)/i',$str,$uid);
		$uidarr = $uid[1];
		
		$uidlist = implode(',',$uidarr);
//		echo $uidlist;
		$diamond_filename = "../data/cache/diamond_intro.php";
		if(!file_exists($diamond_filename)){
			echo "钻石会员推荐列表没有生成！";
			exit;
		}else{
			
			 	if(!file_put_contents($diamond_filename,$uidlist)){
			 		echo "写入不成功";exit;	
			 	}
			
		}
		//添加操作日志
		serverlog(4,$GLOBALS['dbTablePre']."members_backinfo","钻石会员推荐成功",$GLOBALS['adminid'],$GLOBALS['adminid']);
		salert("钻石会员推荐成功！","index.php?action=site_recommend_diamond&h=list");
		exit;
	}
}


//钻石会员推荐（地区）
//insert into web_diamond_recommend(uid,username,nickname,gender,bgtime,endtime,province,city) select uid,username,nickname,gender,bgtime,endtime,province,city from web_members where s_cid=20;
function site_recommend_diamond_district(){
	
	 //允许的排序方式
    $allow_order = array(
        's.gender' => '性别',
        's.birthyear' => '年龄',
        'd.mainimg'=>'形象照',
        's.images_ischeck' => '形象照是否审核',
        's.bgtime' => '升级开始时间',
        's.endtime' => '升级结束时间'
   
    );
	
	$rsort='';
    

    $page_per = 10;
    $page = intval(max(1,MooGetGPC('page','integer')));
    $limit = 10;
    $offset = ($page-1)*$limit;

    
    //url get 取变量值
    $province=MooGetGPC('workprovince','integer','G');
    $city=MooGetGPC('workcity','integer','G');
    $startdate=  MooGetGPC('startdate','string','G');
    $enddate =  MooGetGPC('enddate','string','G');
    $uid = MooGetGPC('uid','integer','G');
    $isindex=MooGetGPC('isindex','integer','G');
  
    
    //如果直接提交表单查询
    if($_POST){
        $province=MooGetGPC('workprovince','integer','P');
        $city=MooGetGPC('workcity','integer','P');
        $startdate=  MooGetGPC('startdate','string','P');
        $enddate =  MooGetGPC('enddate','string','P');
        $uid = MooGetGPC('uid','integer','P');
        $isindex=MooGetGPC('isindex','integer','P');
        
    }
    
      
   //note 修正广东省深圳和广州的区域查询
    if(in_array($province, array(10101201,10101002))) {
        $city = $province;
        $province = 10101000;
    }
    
    //修正直辖市查询
    if(in_array($province, array('10102000', '10103000', '10104000', '10105000'))) {
        $city = '0';
    }    
    
   
	
    $sql_where='';
    $where=$whereturl=array();
	
	
	
    if(!empty($startdate)){
    	$where[]='s.bgtime>='.strtotime($startdate);
    	$whereturl[]='startdate='.$startdate;
    }
    if(!empty($enddate)){
    	$where[]='s.endtime<='.strtotime($enddate);
    	$whereturl[]='enddate='.$enddate;
    }
    if(!empty($province)){
    	$where[]='s.province='.$province;
    	$whereturl[]='workprovince='.$province;
    }
    if(!empty($city)){
    	$where[]='s.city='.$city;
    	$whereturl[]='workcity='.$city;
    }
    if(!empty($isindex)){
    	$where[]='d.isindex=1';
    	$whereturl[]='isindex=1';
    }
    if(!empty($uid)){
    	$where[]='uid='.$uid;
    }
    $sql_where=empty($where)?'':'where '.implode(' and ', $where);
	
     //条件组合
    $query_builder = get_query_builder($sql_where, $allow_order, '', '', '', '', $rsort);
    $sql_sort = $query_builder['sort'];
    $sort_arr = $query_builder['sort_arr'];
    $rsort_arr = $query_builder['rsort_arr'];
	
	
	$sort=MooGetGPC('sql_sort','string','G');
	if(!empty($sort)){
	   $sql_sort=$sort;
	}
	
	
   
	$currenturl='index.php?action=site_recommend_diamond&h=district&'.implode('&', $whereturl)."&sql_sort={$sql_sort}";
	
	
	/* $sort=MooGetGPC('sql_sort','string','G');
	if(!empty($sort)){
	   $currenturl='index.php?action=site_recommend_diamond&h=district&'.implode('&', $whereturl)."&sql_sort={$sql_sort}";
	} */
	
	
	

    
	
	
    $sql = "SELECT COUNT(d.uid) AS COUNT FROM web_diamond_recommend as d left join web_members_search as s on d.uid=s.uid  $sql_where";
    $result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    $total = $result['COUNT'];
     
    $sql = "select d.uid,d.username,d.nickname,s.gender,d.mainimg,s.images_ischeck,s.birthyear,s.bgtime,s.endtime,s.province,s.city,d.isindex,d.sort from web_diamond_recommend as d left join web_members_search as s on d.uid=s.uid $sql_where {$sql_sort}  LIMIT {$offset},{$limit} ";
    $list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
     
     $comment=array();
     
     foreach($list as $key=>$value){
            $value['bgtime']=date('Y-m-d',$value['bgtime']);
            $value['endtime']=date('Y-m-d',$value['endtime']);
            $value['k']=$key+1;
            $comment[]=$value;
    }
    
    
    
    $pages = multipage( $total, $page_per, $page, $currenturl );
    $page_num = ceil($total/$limit);
    require_once(adminTemplate('site_recommend_diamond_district'));
}


/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string')=='' ? 'list' : MooGetGPC('h','string');
//note 动作列表
$hlist = array('list','recommendget','district');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    MooMessageAdmin('您要打开的页面不存在','index.php?action=system_admingroup&h=list');
}
switch($h){
	//note 钻石会员推荐列表
	case 'list':
		site_recommend_diamond_list();
	break;
	//note 提交钻石会员推荐列表
	case 'recommendget':
		site_recommendget();
	break;
	//NOTE 钻石会员首页推荐（地区）
	case 'district':
        site_recommend_diamond_district();
    break;
	default:
		site_recommend_diamond_list();
	break;
}



?>