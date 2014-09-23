<?php
/************************ 逻辑层(M)/表现层(V) ***************************/ 

include "./module/{$name}/function.php";

function ajax_actio(){
	global $userid;
	//global $_MooClass,$dbTablePre,$userid,$user_arr;
	$qid = (int)$_GET['qid'];
	$tc_id = (int)$_GET['tc_id'];
	$ctype = (int)$_GET['ctype'];
	$scores = (int)$_GET['scores'];
	insert_vote($qid, $tc_id, $ctype, $scores);
	echo $userid;
}

/********************* 控制层(C) **************************/
$h = MooGetGPC('h', 'string');

if(!$userid) {exit();}
switch ($h) {   
    case "actio":
		ajax_actio();
	break;
	default :
		exit();
	break;	
}
?>