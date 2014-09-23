<?php
  
  
  
  //$sql = "select count(s.uid) as cnt from web_members_base b left join web_members_search s on s.uid=b.uid where s.regdate>='1347292800' and s.regdate<'1347379200' and instr(source,'wf=wwc')>0 ";
	         
  $sql="delete from web_payment_new where id=96965";
  $_MooClass['MooMySQL']->query( $sql );
  
  $sql="delete from web_payment_new where id=96958";
  $_MooClass['MooMySQL']->query( $sql );
	
  /*
  $sql="update web_admin_user set member_count=15 where uid=555 ";
   if ($_MooClass['MooMySQL']->query( $sql )) {
   
      echo "yes";
}else{

       echo "no";
	} 
   
   $sql="update web_admin_user set member_count=20 where uid=564 ";
   if($_MooClass['MooMySQL']->query( $sql )){
   
       echo "yes";
	}else{
	
	   echo "no";
	 }
   
   $sql="update web_admin_user set member_count=5 where uid=520 ";
   if($_MooClass['MooMySQL']->query( $sql )){
            echo "yes";  
   }else{
            echo "no"; 
   }  
  */
   
   
 //$_MooClass ['MooMySQL']->query( "use hzn" ); 
   
    /*$sql="show tables";
	
    $result=$_MooClass ['MooMySQL']->getAll( $sql ); 
   
   
 
    foreach($result as $v){
	
	    echo $v['Tables_in_hzn']."<br>";
	}  */
	
	 // $_MooClass ['MooMySQL']->query( "use hzn" ); 
	 
	// $_MooClass ['MooMySQL']->query("ALTER TABLE web_admin_join MODIFY column fid VARCHAR(255) DEFAULT NOT NULL");
	// $_MooClass ['MooMySQL']->query("ALTER TABLE web_admin_join modify column mid varchar(255) default not null");
	 
	  /* $desc=$_MooClass ['MooMySQL']->getAll( "DESC web_admin_join");
	  
	  
	   foreach ($desc as $value){
	      echo "field:".$value['Field'] .',  type:'.$value['Type']."<br>";
		
	   
	   }
   */
   