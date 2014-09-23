<?php set_time_limit(0);
header("content-Type:text/html; charset=utf-8");
//过期全权
//查询出过期4个月的全权会员
$usertype = 3;
$sql_where = 'where m.usertype='.$usertype ;
$expiretime=strtotime("-120 day");
$sql_where .=" and f.action_time<$expiretime"; //超过四个月的全权会员
$sql = "select m.uid from web_members_search as m left join web_full_log  f on m.uid=f.uid ".$sql_where;

$collet_user = $_MooClass['MooMySQL']->getAll($sql);
$num=0;
$num1=0;
$num2=0;
$num3=0;
$num4=0;
$num5=0;
foreach($collet_user as $v){
    //choice表记录
   $sql="select uid from web_members_choice where uid=".$v['uid'];
   $rs = $_MooClass['MooMySQL']->getOne($sql);
   if(isset($rs)&&!empty($rs)){
     $num++;
       
   }  
   //base表记录
   $sql="select uid from web_members_base where uid=".$v['uid'];
   $rs_base = $_MooClass['MooMySQL']->getOne($sql);
   if(isset($rs_base)&&!empty($rs_base)){
       $num1++;
   
   }
   //certification表记录
   $sql="select uid from web_certification where uid=".$v['uid'];
   $rs_cer = $_MooClass['MooMySQL']->getOne($sql);
   if(isset($rs_cer)&&!empty($rs_cer)){
       $num2++;
   
   } 
   
   //certification表记录
   $sql="select uid from web_pic where uid=".$v['uid'];
   $rs_pic = $_MooClass['MooMySQL']->getOne($sql);
   if(isset($rs_pic)&&!empty($rs_pic)){
       $num3++;
   
   } 
   
  // members_introduce
   //certification表记录
   $sql="select uid from web_members_introduce where uid=".$v['uid'];
   $rs_int = $_MooClass['MooMySQL']->getOne($sql);
   if(isset($rs_int)&&!empty($rs_int)){
       $num4++;
   
   }
   //certification表记录
   $sql="select uid from web_members_login where uid=".$v['uid'];
   $rs_login = $_MooClass['MooMySQL']->getOne($sql);
   if(isset($rs_login)&&!empty($rs_login)){
       $num5++;
   
   }
   

}

echo "<hr><br/>";
echo "search 表全权4个月的记录：".count($collet_user)."<br>";

echo "choice 表全权4个月的记录：".$num."<br>";

echo "base 表全权4个月的记录：".$num1."<br>";

echo "certification 表全权4个月的记录：".$num2."<br>";

echo "pic 表全权4个月的记录有相册存在会员个数：".$num3."<br>";
echo "introduce表全权4个月的记录：".$num4."<br>";
echo "login 表全权4个月的记录：".$num5."<br>";





