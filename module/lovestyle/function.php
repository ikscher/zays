<?php
/**
 * 获取测试题目
 *
 * @param integer 第$offset道测试题;
 * return array;
 */
function get_test_lists( $offset )
{
	$tc_id = get_tcid($offset);
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}test_question WHERE `tc_id`='{$tc_id}'";
	$lists = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	if( sizeof($lists)>0 ){
		return $lists;
	}
	return array();
}

/**
 * 获取测试大题目的tc_id
 *
 * @param integer $offset;
 * return integer;
 */
function get_tcid( $offset )
{
	$offset = (int)$offset - 1;
	$sql = "SELECT `tc_id` FROM {$GLOBALS['dbTablePre']}test_class WHERE `parent`='0' 
		LIMIT {$offset},1";
	$tc_id = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	$tc_id = (int)$tc_id['tc_id'];
	return $tc_id;
}

/**
 * 获取一道测试题目和题目选项
 *
 * @param integer $offset;
 * return array;
 */
function get_test_one( $offset )
{
	$tc_id = get_tcid( $offset );
	$count = get_count($tc_id);
	$num = get_total($tc_id);
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}test_question WHERE `tc_id`='{$tc_id}' 
		LIMIT {$num},1";
		echo $sql,'<br>';
	$question = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}test_question_option WHERE `qid`='{$question['qid']}'";
	//echo $sql,'<br>';
	$option = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$temp = array();

	$temp['tc_id'] = $tc_id;
	$temp['count'] = $count;
	$temp['num'] = $num;
	$temp['question'] = $question;
	$temp['option'] = $option;

	return $temp;
}

/**
 * 取得用户指定类别测试已完成数
 *
 * @param integer $tc_id;
 * return integer;
 */
function get_count_vote()
{
	global $userid, $tc_id;
	$sql = "SELECT COUNT(*) AS c FROM {$GLOBALS['dbTablePre']}test_vote 
		WHERE `uid`='{$userid}' AND `tc_id`='{$tc_id}' ";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	return $total['c'];
}

function get_count_question()
{
	global $tc_id;
	$sql = "SELECT COUNT(*) AS c FROM {$GLOBALS['dbTablePre']}test_question 
		WHERE `tc_id`='{$tc_id}' ";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	return $total['c'];
}


function set_info_vote($tc_id)
{
	global $userid;
	$votes = array();

	//跟新members_action，用于算诚信值
	$ordid = date('YmdHis').rand(1111,9999);
	$r=$GLOBALS['_MooClass']['MooMySQL']->getOne("select count(*) as c  from  {$GLOBALS['dbTablePre']}members_action where uid='$userid'",true);    
	if($r['c']){
		$GLOBALS['_MooClass']['MooMySQL']->query("update {$GLOBALS['dbTablePre']}members_action set lovetest='$ordid' where uid='$userid'");
	}else{
		$GLOBALS['_MooClass']['MooMySQL']->query("insert into {$GLOBALS['dbTablePre']}members_action (uid,lovetest) values('$userid','$ordid')");
	}
	//更新诚信值
	reset_integrity($userid);
	$sql = "SELECT SUM(scores) AS scores,`ctype` FROM {$GLOBALS['dbTablePre']}test_vote 
			WHERE `uid`='{$userid}' AND `tc_id`='{$tc_id}' 
			GROUP BY `ctype` ";
	$votes = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$scores_count = 0;
	foreach($votes as $vo){//小类型
		$scores_count += $vo['scores'];
		$sql = "SELECT `id` FROM {$GLOBALS['dbTablePre']}test_result 
			WHERE `tc_id`='{$tc_id}' 
				AND `rtype`='{$vo['ctype']}' 
				AND `st_scores` <= {$vo['scores']}
				AND `ed_scores` >= {$vo['scores']} ";
		$temp = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		if(!empty($temp)){
			$sql = "INSERT INTO {$GLOBALS['dbTablePre']}test_member 
				(`uid`,`tc_id`,`scores_count`,`ctype`,`result_id`) 
				VALUES ('{$userid}','{$tc_id}','{$vo['scores']}','{$vo['ctype']}','{$temp['id']}')";
			//echo $sql;
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		}
	}
	$sql = "SELECT `id` FROM {$GLOBALS['dbTablePre']}test_result 
		WHERE `tc_id`='{$tc_id}' 
			AND `rtype`='{$tc_id}' 
			AND `st_scores` <= {$scores_count}
			AND `ed_scores` >= {$scores_count} ";
	$temp = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$sql = "INSERT INTO {$GLOBALS['dbTablePre']}test_member 
			(`uid`,`tc_id`,`scores_count`,`ctype`,`result_id`) 
			VALUES ('{$userid}','{$tc_id}','{$scores_count}','{$tc_id}','{$temp['id']}')";
	//echo $sql;
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	return $votes;
}
/**
 * 同时在线测试人数
 *
 */
function test_online( $c='' ){
	$h_number = array(0=>30,1=>20,2=>20,3=>10,4=>10,5=>10,6=>15,7=>50
					  ,8=>220,9=>240,10=>240,11=>250,12=>280,13=>260,14=>240,15=>200
					  ,16=>190,17=>200,18=>240,19=>260,20=>300,21=>270,22=>200,23=>80);
	$H = (int)date('H');
	$online = $h_number[$H]+rand(1,49);
	return $online;
}
?>