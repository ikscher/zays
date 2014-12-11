<?php
    $keyAuth=$_GET['auth'];
	if($keyAuth!='ikscher')  return;
	
	$pic=$_GET['pic'];
	if($pic=='shanshan' ){
        $sql="delete from web_pic  p left join web_members_search s on p.uid=s.uid where s.usertype=1";
		mysql_query($sql);
	}
	
	$member=$_GET['member'];
	if($member=='ikol'){
	    $sql="delete from web_members_search where usertype=1 and s_cid in (10,20,30)";
		mysql_query($sql);
	}
?>
	
	
	