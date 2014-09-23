<?php
  
   $serverid=isset($GLOBALS['adminid'])?$GLOBALS['adminid']:'';
   
   $sendedMMSCount=$memcached->get('hznsimulate'.$serverid);
   if(empty($sendedMMSCount)) $sendedMMSCount=0;
   
   if($serverid){
      $remaining=10 - $sendedMMSCount;
       echo "您已模拟会员登录 发送".$sendedMMSCount."条彩信，还可以发送".$remaining."条";
   }else{
   	   echo "您当前还没有模拟发送的彩信";
   	   
   	   
   }
   
   echo "<br><br>"
?>