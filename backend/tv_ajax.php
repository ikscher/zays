<?php

	//note 加载框架配置参数
	require './include/config.php';
	//note 加载MooPHP框架
	require '../framwork/MooPHP.php';
	
	function ajax_attend(){
	     global $_MooClass,$timestamp;
	     $isattend=$_POST['isAttend'];
	     $id=$_POST['aid'];
		 $uid=$_POST['uid'];
		 
	     if($id){
	     	
	        $sql="update web_ahtv_reguser set isattend=$isattend where id='$id'";
	        $sql2 = "select certification from web_members_search where uid=$uid";
	        $rs = $_MooClass['MooMySQL']->getOne($sql2);
	   		$new_certi = isset($rs['certification']) && $rs['certification'] ? $rs['certification']:0;
			
	        if($_MooClass['MooMySQL']->query($sql)){
	        	if($isattend==1){
	        	  $sql = "UPDATE web_members_search SET `certification`=`certification`+3  
	              WHERE uid='{$uid}' ";
	        	  $str_arr = array($uid=>array($new_certi+3));
	        	}else{
	        	   $sql = "UPDATE web_members_search SET `certification`=`certification`-3 WHERE uid='{$uid}' ";
	        	   if($new_certi-3<0) $new_certi = 0;
	        	   else $new_certi = $new_certi-3;
	        	   $str_arr = array($uid => array($new_certi));
	        	}
	        	$_MooClass['MooMySQL']->query($sql);
	        	searchApi('members_man members_women') -> updateAttr(array('certification'),$str_arr);
	            echo 'ok';
	        }else{
	        	echo 'no';
	        }
	        //reset_integrity($uid);
	     }else{
	     	echo 'no';
	     }
    
   }
   
   //活动评论是否通过审核
   function ajax_remark(){
         global $_MooClass,$timestamp;
         $isPass=$_POST['isPass'];
         $id=$_POST['value'];
         
         if($id){
            
            $sql="update web_ahtv_remark set isPass=$isPass where id={$id}";
            
            if($_MooClass['MooMySQL']->query($sql)){
                echo 'ok';
            }else{
                echo 'no';
            }
            //reset_integrity($uid);
         }else{
            echo 'no';
         }
    
   }
   
   
   //活动评论删除
   function ajax_delete(){
         global $_MooClass;
         
         $id=$_POST['id'];
         
         if($id){
            
            $sql="delete from web_ahtv_remark  where id={$id}";
            
            if($_MooClass['MooMySQL']->query($sql)){
                echo 'ok';
            }else{
                echo 'no';
            }
            //reset_integrity($uid);
         }else{
            echo 'no';
         }
    
   }
   
    
    /*$activity = $_MooClass['MooMySQL']->getOne("select action,isattend from {$dbTablePre}ahtv_reguser where uid='$uid'");
    if($activity['action'] && $activity['isattend']=='1'){
       $start +=3  ;//参加过活动
    }elseif($activity['action'] && $activity['isattend']=='0'){
        $start -=3;//参加过活动
    }*/
   
   //=========================控制层====================================
   $n=MooGetGPC('n','string','G');

    switch($n){
    	case 'attend':
    		ajax_attend();
    		break;
    	case 'remark':
    		ajax_remark();
            break;
		case 'deleteremark':
		    ajax_delete();
		    break;
        default:
            ajax_attend();
            break;
    }
   
 ?>

