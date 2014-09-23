<?php
/************************ 逻辑层(M)/表现层(V) ***************************/ 

include "./module/{$name}/function.php";

function ajax_vote(){
	global $_MooClass,$dbTablePre,$userid;

	$qid = '';
	$ans = '';
	if(isset($_GET['qid'])) $qid = (int)$_GET['qid'];
	if(isset($_GET['ans'])) $ans = (int)$_GET['ans'];
	$ans && $ans--;
	//insert_vote($qid, $tc_id, $ctype, $scores);
	$sql = 'SELECT `qid`,`tc_id`,`ctype`,`show_type`,`str_scores` 
		FROM '.$dbTablePre.'test_question WHERE `qid`='.$qid.' LIMIT 1 ';
	$question = $_MooClass['MooMySQL']->getOne($sql,true);
	$tc_id = $question['tc_id'];
	$ctype = $question['ctype'];
	$scores = explode(',', $question['str_scores']);
	if(isset($scores[$ans])) $scores = $scores[$ans];
	//$opt_txt = $opt_arr[$question['show_type']][$ans];
	$opt_txt = '';

	if($question['show_type'] <= 1){
		$sql = "SELECT `option`,`scores` FROM {$dbTablePre}test_question_option WHERE `qid`={$qid} ";
		$opt_scores = $_MooClass['MooMySQL']->getAll($sql,true);
		$scores = $opt_scores[$ans]['scores'];
		$opt_txt = $opt_scores[$ans]['option'];
		unset($opt_scores);
	}
	unset($question);
	$ans++;
	$table = $dbTablePre . 'test_vote';
	$sql = "SELECT * FROM {$table} 
		WHERE `uid`='{$userid}' AND `qid`='{$qid}' AND `tc_id`='{$tc_id}' LIMIT 1 ";
		//echo $sql;exit;
	if( $_MooClass['MooMySQL']->getOne($sql) ){
		$sql = "UPDATE {$table} SET `opt`='{$ans}',`scores`='{$scores}',`opt_txt`='{$opt_txt}' 
			WHERE `uid`='{$userid}' AND `qid`='{$qid}' AND `tc_id`='{$tc_id}'";
	}else{
		$sql = "INSERT INTO {$table} (`uid`,`tc_id`,`qid`,`opt`,`scores`,`ctype`,`opt_txt`) 
			VALUES('{$userid}','{$tc_id}','{$qid}','{$ans}','{$scores}','{$ctype}','{$opt_txt}') ";
	}
	//echo $sql;exit;
	$_MooClass['MooMySQL']->query($sql);
	echo 'ok';
}

/********************* 控制层(C) **************************/
$h = MooGetGPC('h', 'string');

if(!$userid) {exit();}
switch ($h) {   
    case "vote":
		ajax_vote();
	break;
	default :
		exit();
	break;	
}
?>