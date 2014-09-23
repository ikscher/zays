<?php
require dirname(__FILE__).'/./framwork/MooPHP.php';

$start =isset($_GET['s'])?intval($_GET['s']):0;
echo $start;

// $starttime=1400601600;
// $endtime=1400688000;
// $sql = "select telphone,uid from web_members_search where regdate>={$starttime} and regdate<={$endtime} order by uid asc limit $start,20";
$sql = "select allot_sid,uid from web_allotuser  where  allot_time>=1401552000  order by aid asc limit $start,1";

$res = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);

if($res){	
	$start ++;
	

	$uid=$res['uid'];
	$sid=$res['allot_sid'];
	
	$r=array();
	$sql="select sid from web_members_search where uid={$uid}";
	$r=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	
	if($r['sid']<=0){
	
		$sql="update web_members_search set sid={$sid} where uid={$uid}";

		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
    }
	
	unset($res);
	exit('<html><head><meta http-equiv="refresh" content="0;url=?s='.$start.'"> </head><body></body></html>');
}else{
		exit("ok");
}

?>