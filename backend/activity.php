<?php
  /**
   *  安徽卫视周日我最大 活动 
   *  2011-07-02
   */
   function ahtvtj(){
       global $_MooClass;
       $channel=1;   
       $sid=$GLOBALS['adminid'];
       $page_per = 15;
       $page = max(1,MooGetGPC('page','integer'));
       $limit = 15;
       $offset = ($page-1)*$limit;
       $group= $GLOBALS['groupid'];
       $where = '';
       $purpose = 0;
       $submit = 0;
       //URL地址，页面翻页链接 处理
       if(!empty($_GET['channel'])){
          $channel=$_GET['channel'];
          $sid=isset($_GET['sid']) ? $_GET['sid'] : '';
          $purpose=isset($_GET['purpose']) ? $_GET['purpose'] : '';  
       }
   		if($group == '78'){
       		$where = "and (`sid` = '".$sid."' or `sid` = '')";
       	}
       
       //表单提交处理
       if(isset($_POST['submit'])){
          $sid=$GLOBALS['adminid'];
       	  $channel=$_POST['channel'];
       	  $purpose=$_POST['purpose'];
       	  $offset = 0;
       	  if($group != '78'){
       	  	if($purpose == 0){
       	  		
       	  	}else{
       	  		$where = " and `purpose` = '".$purpose."'";
       	  	}
       	  }else{
       	  	if($purpose == 0){
       	  	 $where = "and (`sid` = '".$sid."' or `sid` = '')";
       	  	}else{
       	 	 $where = "and (`sid` = '".$sid."' or `sid` = '') and `purpose` = '".$purpose."'";
       	  	}
       	  }
       }
	   
	       if($channel){
	         $sql = "SELECT COUNT(id) AS COUNT FROM web_ahtv_reguser where channel='".$channel."' $where order by id asc";
	       }
	       $a = $_MooClass['MooMySQL']->getOne($sql);
	       $total = $a['COUNT'];
	    
	       $reguser=array();
	
	       if($channel){
	         $sql = "SELECT a.id,a.uid,a.edit_time,a.next_time,a.username,a.gender,a.birthday,a.province,a.city,a.mobile,a.regtime,a.channel,a.isattend,a.purpose,a.income,a.note,a.sid  FROM web_ahtv_reguser a    where a.channel='".$channel."' $where  order by a.regtime desc LIMIT {$offset},{$limit}";
	       }
		 if(!empty($_REQUEST['submit'])){
		    $submit='1';
		 }
		 if(!empty($_POST['submit'])){
			$page = 1;
		 }
       $currenturl = "index.php?action=activity&h=activity_member&channel={$channel}&submit={$submit}";
   
       $pages = multipage( $total, $page_per, $page, $currenturl );
       $page_num = ceil($total/$limit);
  
       $result=$_MooClass['MooMySQL']->getAll($sql);
       $pure = $purpose;
       
       
       if(empty($result)){
       		if($purpose){
       			$pur = $purpose;
       		}else{
       			$pur = 0;
       		}
       		
       }else{
       foreach($result as $key=>$value){
 			
		    $id=$value['id'];
		    $uid=$value['uid'];
		    $username=$value['username'];
		    $gender=$value['gender'];
		    $birthday=explode('-',$value['birthday']);
		    $birthday=$birthday[0].'年'.$birthday[1].'月';
		    $province=$value['province'];
		    $city=$value['city'];
		    $mobile=  !empty($_REQUEST['submit']) ? $value['mobile'] : '';
			
		    $regtime=$value['regtime'];
		    $isattend=$value['isattend'];
			$channel=$value['channel'];
			$pur=$value['purpose'];
			if($pur == 2){
				$purpose = '不能参加';
			}else if($pur == 3){
				$purpose = '要考虑';
			}else if($pur == 4){
				$purpose = '有意向';
			}else if($pur == 5){
				$purpose = '确定参加';
			}else if($pur == 6){
				$purpose = '支付环节';
			}else{
				$purpose = '未处理';
			}
			
		    $income=$value['income'];
		    $sid=$value['sid'];
			$note=$value['note'];
	    	$edit_time=$value['edit_time'];
	    	$next_time=$value['next_time'];
	        $reguser[]=array('k'=>$key+1,'id'=>$id,'uid'=>$uid,'username'=>$username,'gender'=>$gender,'birthday'=>$birthday,'province'=>$province,'city'=>$city,'mobile'=>$mobile,'regtime'=>$regtime,'isattend'=>$isattend,'channel'=>$channel,'sid'=>$sid,'note'=>$note,'income'=>$income,'purpose'=>$purpose,'edit_time'=>$edit_time,'next_time'=>$next_time);
       }
   }
        
       require_once(adminTemplate('activity_member')); 
   }
  
   function edit(){
   	   global $_MooClass;
   	  
       if($_GET['channel']){
       $channel=$_GET['channel'];
       }else{
       $channel='1';
       }
       $id = $_GET['id'];
       $sql = "select * from web_ahtv_reguser  where id='".$id."' and channel = '".$channel."'";
       $result = $_MooClass['MooMySQL']->getOne($sql);
      
	    $uid=$result['uid'];
	    $username=$result['username'];
	    $pur=$result['purpose'];
	    
	    if($pur == 2){
			$purpose = '不能参加';
		}else if($pur == 3){
			$purpose = '要考虑';
		}else if($pur == 4){
			$purpose = '有意向';
		}else if($pur == 5){
			$purpose = '确定参加';
		}else if($pur == 6){
			$purpose = '支付环节';
		}else if($pur == 1){
			$purpose = '未处理';
		}else{
			$purpose = '请选择';
		}
		
	    $income=$result['income'];
	    $sid=$GLOBALS['adminid'];
		$note=$result['note'];
		$next_time=$result['next_time'];
		$mouth = substr($next_time,5,2);
		if($mouth<10){
		$mouth = substr($mouth,1,1);
		}
		$day = substr($next_time,8,2);
		if($day<10){
		$day = substr($day,1,1);
		}
		
   	   require_once(adminTemplate('activity_edit')); 
   }
   function update(){
   	  global $_MooClass;
   	  $sid = $_REQUEST['sid'];
   	  $uid = $_REQUEST['uid'];
   	  $id = $_REQUEST['id'];
   	  $purpose = $_REQUEST['purpose'];
   	  $note = $_REQUEST['note'];
   	  $time = date('Ymd');
   	  $channel = $_REQUEST['channel'];
   	  $month = $_REQUEST['month'];
   	  if($month<10){
   	  $month = '0'.$month;
   	  }
   	  $day = $_REQUEST['day'];
   	  if($day<10){
   	  	$day = '0'.$day;
   	  }
   	  $year = date('Y');
   	  $next_time = $year.$month.$day;
      $sql = "update web_ahtv_reguser SET sid = '$sid',purpose = '$purpose',note = '$note',edit_time = '$time',next_time = '$next_time' where id = '$id' and channel = '$channel'";
   	  $_MooClass['MooMySQL']->query($sql);
   	 MooMessageAdmin('编辑成功','index.php?action=activity&h=activity_member',1);
   }
   
   function kflist(){
		global $_MooClass;
		$channel=0;   
		$sid=$GLOBALS['adminid'];
		$page_per = 15;
		$pages = '';
		$_POST['purpose'] = isset($_POST['purpose']) ? $_POST['purpose'] : 0;
		$_POST['channel'] = isset($_POST['channel']) ? $_POST['channel'] : 0;
		if(!empty($_REQUEST['submit'])){
			$page = 1;
		}else{
			$page = max(1,MooGetGPC('page','integer'));
		}
		$limit = 15;
		$offset = ($page-1)*$limit;
		$group= $GLOBALS['groupid'];
		$sid=$GLOBALS['adminid'];
		$username = $GLOBALS['username'];
		if($group == '78' || $group == '70' || $group == '67'){
			MooMessageAdmin('权限不足','index.php?action=activity&h=activity_member',1);
			exit;
		}else{
			
			$sql = "select distinct('sid') as sid from web_ahtv_reguser";
			$a = $_MooClass['MooMySQL']->getOne($sql);
	       	$total = count($a['sid']);
	       	if($_POST['purpose'] || $_POST['channel']){
	       		$channel = $_REQUEST['channel'];
	       		$purpose = $_REQUEST['purpose'];
	       		if($purpose == '0'){
	       			$where = 'and b.channel = "'.$channel.'"';
	       		}else{
	       			$where = 'and b.purpose = "'.$purpose.'" and b.channel = "'.$channel.'"';
	       		}
	       		$sql = "select a.username,count(b.sid) as count,a.uid from web_admin_user as a left join web_ahtv_reguser as b on a.uid=b.sid where b.sid !='' $where group by a.username LIMIT {$offset},{$limit}";
	       	}else{
	       		$channel = '0';
	       		$purpose = '0';
	       		$sql = "select a.username,count(b.sid) as count,a.uid from web_admin_user as a left join web_ahtv_reguser as b on a.uid=b.sid where b.sid !='' group by a.username LIMIT {$offset},{$limit}";
	       	}
	       	$currenturl = "index.php?action=activity&h=activity_kefu&channel={$channel}&purpose={$purpose}";
	       	$result=$_MooClass['MooMySQL']->getAll($sql);
			foreach($result as $k => $v){
				$result[$k]['k'] = $k+1;
			}
			
			require_once(adminTemplate('activity_kf')); 
		}
   }
	function kefu_all(){
		global $_MooClass;
		$channel=1;   
		$sid=$GLOBALS['adminid'];
		$page_per = 15;
		$reguser = array();
		if(!empty($_REQUEST['submit'])){
			$page = 1;
		}else{
			$page = max(1,MooGetGPC('page','integer'));
		}
		$limit = 15;
		$offset = ($page-1)*$limit;
		$group= $GLOBALS['groupid'];
		$sid=$GLOBALS['adminid'];
		$username = $GLOBALS['username'];
		if($group == '78' || $group == '70' || $group == '67'){
			MooMessageAdmin('权限不足','index.php?action=activity&h=activity_member',1);
			exit;
		}else{
			$uid = $_REQUEST['uid'];
			$channel = $_REQUEST['channel'];
			$purpose = $_REQUEST['purpose'];
			if($purpose == '0' && $channel == '0'){
				$sql = "SELECT COUNT(id) AS COUNT FROM web_ahtv_reguser where  sid ='".$uid."'";
			}else if($purpose != '0' && $channel == '0'){
				$sql = "SELECT COUNT(id) AS COUNT FROM web_ahtv_reguser where purpose='".$purpose."' and sid ='".$uid."'";
			}else if($purpose == '0' && $channel != '0'){
				$sql = "SELECT COUNT(id) AS COUNT FROM web_ahtv_reguser where channel='".$channel."' and sid ='".$uid."'";
			}else{
				$sql = "SELECT COUNT(id) AS COUNT FROM web_ahtv_reguser where channel='".$channel."' and purpose='".$purpose."' and sid ='".$uid."'";
			}
			$a = $_MooClass['MooMySQL']->getOne($sql);
	       	$total = $a['COUNT'];
	       	if($purpose == '0' && $channel == '0'){
				$sql = "SELECT a.id,a.uid,a.edit_time,a.next_time,a.username,a.gender,a.birthday,a.province,a.city,a.mobile,a.regtime,a.isattend,a.channel,a.purpose,a.income,a.note,a.sid FROM web_ahtv_reguser as a where  a.sid ='".$uid."' order by a.edit_time desc LIMIT {$offset},{$limit}";
			}else if($purpose != '0' && $channel == '0'){
				$sql = "SELECT a.id,a.uid,a.edit_time,a.next_time,a.username,a.gender,a.birthday,a.province,a.city,a.mobile,a.regtime,a.isattend,a.channel,a.purpose,a.income,a.note,a.sid  FROM web_ahtv_reguser as a where  a.purpose='".$purpose."' and a.sid ='".$uid."' order by a.edit_time desc LIMIT {$offset},{$limit}";
			}else if($purpose == '0' && $channel != '0'){
				$sql = "SELECT a.id,a.uid,a.edit_time,a.next_time,a.username,a.gender,a.birthday,a.province,a.city,a.mobile,a.regtime,a.isattend,a.channel,a.purpose,a.income,a.note,a.sid  FROM web_ahtv_reguser as a where a.channel='".$channel."'  and a.sid ='".$uid."' order by a.edit_time desc LIMIT {$offset},{$limit}";
			}else{
				$sql = "SELECT a.id,a.uid,a.edit_time,a.next_time,a.username,a.gender,a.birthday,a.province,a.city,a.mobile,a.regtime,a.isattend,a.channel,a.purpose,a.income,a.note,a.sid  FROM web_ahtv_reguser as a where a.channel='".$channel."' and a.purpose='".$purpose."' and a.sid ='".$uid."' order by a.edit_time desc LIMIT {$offset},{$limit}";
			}
		 $currenturl = "index.php?action=activity&h=kefu_all&channel={$channel}&uid={$uid}&purpose={$purpose}";
   
       	 $pages = multipage( $total, $page_per, $page, $currenturl );
         $page_num = ceil($total/$limit);
  
         $result=$_MooClass['MooMySQL']->getAll($sql);
         $purs = $purpose;
	       foreach($result as $key=>$value){
			    $id=$value['id'];
			    $uid=$value['uid'];
			    $username=$value['username'];
			    $gender=$value['gender'];
			    $birthday=explode('-',$value['birthday']);
			    $birthday=$birthday[0].'年'.$birthday[1].'月';
			    $province=$value['province'];
			    $city=$value['city'];
			    $mobile=$value['mobile'];
			    $regtime=$value['regtime'];
			    $isattend=$value['isattend'];
				$channel=$value['channel'];
				$pur=$value['purpose'];
				if($pur == 2){
					$purpose = '不能参加';
				}else if($pur == 3){
					$purpose = '要考虑';
				}else if($pur == 4){
					$purpose = '有意向';
				}else if($pur == 5){
					$purpose = '确定参加';
				}else if($pur == 6){
					$purpose = '支付环节';
				}else{
					$purpose = '未处理';
				}
				
			    $income=$value['income'];
			    $sid=$value['sid'];
				$note=$value['note'];
		    	$edit_time=$value['edit_time'];
		    	$next_time=$value['next_time'];
		        $reguser[]=array('k'=>$key+1,'id'=>$id,'uid'=>$uid,'username'=>$username,'gender'=>$gender,'birthday'=>$birthday,'province'=>$province,'city'=>$city,'mobile'=>$mobile,'regtime'=>$regtime,'isattend'=>$isattend,'channel'=>$channel,'sid'=>$sid,'note'=>$note,'income'=>$income,'purpose'=>$purpose,'edit_time'=>$edit_time,'next_time'=>$next_time);
	       }
			require_once(adminTemplate('activity_kf_list')); 
		}
	
	}  
	function qunfa(){
		$uid_list = substr($_GET['a'],0,-1);
		require_once(adminTemplate('activity_sendmsm_most'));
	}

	function qunyj(){
		$uid_list = substr($_GET['a'],0,-1);
		require_once(adminTemplate('activity_sendmail_batch'));
	}
   function other_sendmsm_most(){
	$ispost=MooGetGPC('post','integer','G');
	if($ispost){
		$uid_list=MooGetGPC('uid_list','string','P');
		$content=MooGetGPC('content','string','P');
		
		$sql="SELECT uid,telphone FROM {$GLOBALS['dbTablePre']}members_search where uid in ($uid_list) and telphone!=''";
		$tel_more_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		foreach($tel_more_arr as $tel){
			$tel_arr[]=$tel['telphone'];
				$dateline=time();
				$sql = "INSERT INTO {$GLOBALS['dbTablePre']}smslog(sid,uid,content,sendtime) VALUES('{$GLOBALS['adminid']}','{$tel['uid']}','{$content}','{$dateline}')";
				$GLOBALS['_MooClass']['MooMySQL']->query($sql);			
		}
		//$tel_list=implode(',',$tel_arr);
		if($ret=SendMsg($tel_arr,$content,1)){
			salert("群发送短信成功！","index.php?action=activity");
		}else{salert("群发送短信失败！","index.php?action=activity");}
	}
	header("Location: index.php?action=activity");
	}
	function sendmsm_batch(){
		global $dbTablePre;
		
		$uid_list=MooGetGPC('uid_list','string','P');
		$title = MooGetGPC('msg_title','string','P');
		$content = MooGetGPC('msg_content','string','P');
		
		$array = explode(',',$uid_list);
		
		$arr = array();
		foreach($array as $k => $v){
			if(empty($v)) continue;
			$arr[$k]['uid'] = $v;
		}
		$sum=0;
 
        foreach($arr as $v){
           $uid=$v['uid'];
           $GLOBALS['_MooClass']['MooMySQL']->query("insert into {$dbTablePre}services (s_cid,s_uid,s_fromid,s_title,s_content,s_time,sid,flag) values ('3','{$uid}','0','{$title}','{$content}',".time().",'0','1')"); 
           $sum++;		   
        }
  
       salert("群发送邮件成功！共发出 【 $sum 】 封邮件","index.php?action=other&h=sendmail_batch");
      
    
		header("Location: index.php?action=activity");
	}

   //===========================控制层==================================================
  
   $h=MooGetGPC('h','string','G');
   $hlist = array('member','edit','update','activity_kefu','qunfa','kefu_all');
	switch($h){
	    //note 已支付列表
	    case 'activity_member': 		
	    	ahtvtj();
	    	break;
	    case 'edit':
	    	edit();
	    	break;
	    case 'update':
	    	update();
	    	break;
	    case 'activity_kefu';
	    	kflist();
	    	break;
	    case 'kefu_all';
	    	kefu_all();
	    	break;
		case 'qunfa';
	    	qunfa();
	    	break;
		case 'sendmsm_most':
			other_sendmsm_most();
			break;
		case 'qunyj';
	    	qunyj();
	    	break;
		case 'sendmsm_batch';
	    	sendmsm_batch();
	    	break;
	    default:
	        ahtvtj();
            break;
	}   

 ?>
