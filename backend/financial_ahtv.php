<?php
  /**
   *  安徽卫视周日我最大 活动 
   *  2011-07-02
   */
   function ahtvtj(){
       global $_MooClass;
       $sum=array();
       $channel='1';
       
       $page_per = 15;
       $page = max(1,MooGetGPC('page','integer'));
       $limit = 15;
       $offset = ($page-1)*$limit;
       $total=30;
       
       //if($_POST['submit']){
       $submit=MooGetGPC('submit','string','P');
       if($submit){
           $startdate=$_POST['startdate'];
           $enddate=$_POST['enddate'];
           $channel=$_POST['channel'];
           
           if(!empty($startdate) || !empty($enddate)){
              $sql="select id,uid,ip,clicknum,regnum,upgradenum,action,operationtime,channel from web_ahtv where operationtime>='".$startdate."' and operationtime<='".$enddate."' group by operationtime  LIMIT {$offset},{$limit}";
            
           }elseif(empty($startdate) && empty($enddate) && $channel ){
           	  $sql="select id,uid,ip,clicknum,regnum,upgradenum,action,operationtime,channel from web_ahtv where channel='$channel' group by operationtime  LIMIT {$offset},{$limit}";
           	
           }elseif(!empty($startdate) && !empty($enddate) && $channel ){
           	  $sql="select id,uid,ip,clicknum,regnum,upgradenum,action,operationtime,channel from web_ahtv where operationtime>='".$startdate."' and operationtime<='".$enddate." and channel='$channel' group by operationtime  LIMIT {$offset},{$limit}";
           	
           }
       }else{ 
       	   //$startdate=date('Y-m-d');
       	   //$enddate=date('Y-m-d');
           $sql="select id,uid,ip,clicknum,regnum,upgradenum,action,operationtime,channel from web_ahtv where channel=1 group by operationtime  LIMIT {$offset},{$limit}";
          
       }
       
       $result=$_MooClass['MooMySQL']->getAll($sql);
    
       foreach($result as $key=>$value){
            
		   $date=$value['operationtime'];
		   
		   //当天的点击数
//		   if($channel==2){ //辽宁TV
//		      $sql_click="select sum(clicknum) as clicksum from web_ahtv where channel=2 and operationtime='$date'";
//		      $result_click=$_MooClass['MooMySQL']->getOne($sql_click);
//           }elseif($channel==1){ //安徽TV
//              $sql_click="select sum(clicknum) as clicksum from web_ahtv where channel=1 and operationtime='$date'";
//              $result_click=$_MooClass['MooMySQL']->getOne($sql_click);
//           }elseif($channel==3){ //湖北TV
//              $sql_click="select sum(clicknum) as clicksum from web_ahtv where channel=3 and operationtime='$date'";
//              $result_click=$_MooClass['MooMySQL']->getOne($sql_click);
//           }
//           
//		   //当天的注册数
//		   if($channel==1){
//			   $sql_register="select sum(regnum) as regsum from web_ahtv where channel=1 and  operationtime='$date'";
//			   $result_register=$_MooClass['MooMySQL']->getOne($sql_register);
//		   }elseif($channel==2){
//		       $sql_register="select sum(regnum) as regsum from web_ahtv where channel=2 and operationtime='$date'";
//               $result_register=$_MooClass['MooMySQL']->getOne($sql_register);
//		   }elseif($channel==3){
//		       $sql_register="select sum(regnum) as regsum from web_ahtv where channel=3 and operationtime='$date'";
//               $result_register=$_MooClass['MooMySQL']->getOne($sql_register);
//		   }
//		   //当天的升级数
//		    if($channel==1){
//			    $sql_upgrade="select sum(upgradenum) as upgradesum from web_ahtv where channel=1 and  operationtime='$date'";
//			    $result_upgrade=$_MooClass['MooMySQL']->getOne($sql_upgrade);
//		    }elseif($channel==2){
//		    	$sql_upgrade="select sum(upgradenum) as upgradesum from web_ahtv where channel=2 and operationtime='$date'";
//                $result_upgrade=$_MooClass['MooMySQL']->getOne($sql_upgrade);
//		    }elseif($channel==3){
//		    	$sql_upgrade="select sum(upgradenum) as upgradesum from web_ahtv where channel=3 and operationtime='$date'";
//                $result_upgrade=$_MooClass['MooMySQL']->getOne($sql_upgrade);
//		    }
		    
		    switch ($channel){
		    	case 1:
		    		 $sql_c_r_u="select sum(clicknum) as clicksum,sum(regnum) as regsum,sum(upgradenum) as upgradesum from web_ahtv where channel=1 and operationtime='$date'";
		             $result_c_r_u=$_MooClass['MooMySQL']->getOne($sql_c_r_u);
		        break;
		    	case 2:
		    		 $sql_c_r_u="select sum(clicknum) as clicksum,sum(regnum) as regsum,sum(upgradenum) as upgradesum from web_ahtv where channel=2 and operationtime='$date'";
		             $result_c_r_u=$_MooClass['MooMySQL']->getOne($sql_c_r_u);
		    	case 3:
		    		 $sql_c_r_u="select sum(clicknum) as clicksum,sum(regnum) as regsum,sum(upgradenum) as upgradesum from web_ahtv where channel=3 and operationtime='$date'";
		             $result_c_r_u=$_MooClass['MooMySQL']->getOne($sql_c_r_u);
		        break;
		        default:	
		    }
		    $result_click['clicksum']=$result_c_r_u['clicksum'];
		    $result_register['regsum']=$result_c_r_u['regsum'];
		    $result_upgrade['upgradesum']=$result_c_r_u['upgradesum'];
 
	        $sum[]=array('k'=>$key+1,'date'=>$date,'click'=>$result_click['clicksum'],'reg'=>$result_register['regsum'],'upgrade'=>$result_upgrade['upgradesum']);
       }
       
       $currenturl = "index.php?action=financial_ahtv&h=list&channel={$channel}";
       
       $pages = multipage( $total, $page_per, $page, $currenturl );
       $page_num = ceil($total/$limit);
  
       require_once(adminTemplate('financial_ahtv')); 
   }
   //===========================控制层==================================================
  
   $h=MooGetGPC('h','string','G');

	switch($h){
	    //note 已支付列表
	    case 'list':
	    	ahtvtj();
	    	break;
	    default:
	        ahtvtj();
            break;
	}   

 ?>
