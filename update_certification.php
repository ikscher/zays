<?php
require dirname(__FILE__).'/./framwork/MooPHP.php';

$start =isset($_GET['s'])?intval($_GET['s']):0;
echo $start;

// $starttime=1400601600;
// $endtime=1400688000;
// $sql = "select telphone,uid from web_members_search where regdate>={$starttime} and regdate<={$endtime} order by uid asc limit $start,20";
$sql = "select uid from web_members_search where usertype=3 order by uid asc limit $start,100";

$res = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);

if($res){	
	$start += 100;
	foreach($res as $k=>$v){
		// $telphone = $v['telphone'];
	    $uid=$v['uid'];
		
		
	    //$sql = "select telphone web_certification  where uid=".$uid;
	    //$res_=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		//if(empty($res_['telphone'])){
		    //$sql="insert into web_certification (uid,telphone) values('{$uid}','{$telphone}')";
			//$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//}
		$rand_sms=rand(0,1);
		$rand_email=rand(0,1);
		if($rand_email==0) {
		    $rand_email='yes';
		}else{
		    $rand_email='no';
		}
		$sql="update web_certification set sms='{$rand_sms}',email='{$rand_email}',video_check=1 where uid='{$uid}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		
	}
	unset($res);
	exit('<html><head><meta http-equiv="refresh" content="2;url=?s='.$start.'"> </head><body></body></html>');
}else{
		exit("ok");
}

?>