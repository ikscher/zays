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
	$tc_id = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
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
	$question = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
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
function get_total( $tc_id )
{
	global $userid;
	$sql = "SELECT COUNT(*) AS c FROM {$GLOBALS['dbTablePre']}test_vote 
		WHERE `uid`='{$userid}' AND `tc_id`='{$tc_id}' ";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	return $total['c'];
}

function get_count( $tc_id )
{
	$sql = "SELECT COUNT(*) AS c FROM {$GLOBALS['dbTablePre']}test_question 
		WHERE `tc_id`='{$tc_id}' ";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	return $total['c'];
}

/**
 * 测试选择结果入表test_vote
 */
function insert_vote($qid, $tc_id, $ctype, $scores)
{
	global $userid;
	$table = $GLOBALS['dbTablePre'] . 'test_vote';
	$sql = "SELECT * FROM {$table} 
		WHERE `uid`='{$userid}' AND `qid`='{$qid}' AND `tc_id`='{$tc_id}' ";
		//echo $sql;exit;
	if( $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true) ){
		$sql = "UPDATE {$table} SET `scores`='{$scores}' 
			WHERE `uid`='{$userid}' AND `qid`='{$qid}' AND `tc_id`='{$tc_id}'";
	}else{
		$sql = "INSERT INTO {$table} (`uid`,`tc_id`,`qid`,`scores`,`ctype`) 
			VALUES('{$userid}','{$tc_id}','{$qid}','{$scores}','{$ctype}') ";
	}
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
}

function get_info_vote( $tc_id )
{
	global $userid;
	$votes = array();
	$sql = "SELECT SUM(scores) AS scores,`ctype` FROM {$GLOBALS['dbTablePre']}test_vote 
			WHERE `uid`='{$userid}' AND `tc_id`='{$tc_id}' 
			GROUP BY `ctype` ";
	$votes = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	foreach($votes as $vo){
		$sql = "SELECT `id` FROM {$GLOBALS['dbTablePre']}test_result 
			WHERE `tc_id`='{$tc_id}' 
				AND `rtype`='{$vo['ctype']}' 
				AND `st_scores` <= {$vo['scores']}
				AND `ed_scores` >= {$vo['scores']} ";
		$temp = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		if(!empty($temp)){
			$sql = "INSERT INTO {$GLOBALS['dbTablePre']}test_member 
				(`uid`,`tc_id`,`ctype`,`result_id`) 
				VALUES ('{$userid}','{$tc_id}','{$vo['ctype']}','{$temp['id']}')";
			//echo $sql;
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		}
	}
	return $votes;
}
?>