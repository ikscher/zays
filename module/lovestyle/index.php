<?php
/*************************************** 逻辑层(M)/表现层(V) ****************************************/ 

include "./module/{$name}/function.php";
//note 测试入口
function lovestyle_index(){
	include MooTemplate('public/lovestyle_index', 'module');
}

function lovestyle_love(){
	global $userid, $tc_id;
	$sql = "SELECT `qid`,`opt` FROM {$GLOBALS['dbTablePre']}test_vote 
		WHERE `uid`='{$userid}' AND `tc_id`='{$tc_id}' ";
	$selected = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$comp_num = sizeof($selected);

	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}test_question WHERE `tc_id`='{$tc_id}' ";
	$questions = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    
	$total = sizeof($questions);
	if($total>0) {
	    $percent = (int)($comp_num / $total * 100);
	    if($percent >= 100) {MooMessage("恭喜您的测试已完成",'index.php?n=lovestyle&h=result&tcid=1');}
    }
	
	include MooTemplate('public/lovestyle_love', 'module');
}

function lovestyle_interest(){
	global $userid,$tc_id,$_MooCookie,$hntest_cache;
	$sql = "SELECT COUNT(*) AS c FROM {$GLOBALS['dbTablePre']}test_vote 
		WHERE `uid`='{$userid}' AND `tc_id`='{$tc_id}' ";
	$comp_num = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	$comp_num = $comp_num['c'];
	if(!isset($_MooCookie['test_index'])){
		MooSetCookie('test_index',$comp_num);
		$_MooCookie['test_index'] = $comp_num;
	}
	$read_vote = 0;
	if($_MooCookie['test_index'] >= 0 && $_MooCookie['test_index'] < $comp_num){
		$comp_num = $_MooCookie['test_index'];
		$read_vote = 1;
	}else{
		MooSetCookie('test_index',$comp_num);
	}
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}test_question 
		WHERE `tc_id`='11' LIMIT {$comp_num},1 ";
	$question = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	if($read_vote){
		$sql = "SELECT `opt` FROM {$GLOBALS['dbTablePre']}test_vote 
			WHERE `uid`='{$userid}' AND `qid`='{$question['qid']}' AND `tc_id`='{$tc_id}' LIMIT 1 ";
		$this_vote = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$this_vote = $this_vote['opt'];
	}
	if($question['show_type'] >= 3){
		$scores_arr = explode(',',$question['str_scores']);
	}
	//print_r($question);
/*
	$sql = "SELECT COUNT(*) AS c FROM {$GLOBALS['dbTablePre']}test_question 
		WHERE `tc_id`='{$tc_id}' ";
	$temp = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$total = $temp['c'];
*/
	$total = $hntest_cache['count'][$tc_id];
	$percent = (int)($comp_num / $total * 100);
	if($percent >= 100) {
		MooSetCookie('test_index',-1);
		MooMessage("恭喜您的测试已完成",'index.php?n=lovestyle&h=result&tcid=2');
	}
	switch($question['show_type']){
		case '3':
			$temp_style = '02';
		break;
		case '4':
			$temp_style = '01';
		break;
		case '0':
		case '1':
			$sql = "SELECT `op_id`,`option`,`scores` FROM {$GLOBALS['dbTablePre']}test_question_option 
				WHERE `qid`={$question['qid']} ORDER BY `op_id` ASC";
			$options = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
			$options_count = sizeof($options);
			$temp_style = $options_count == 2 ? '04' : '03' ;
		break;
	}
	include MooTemplate('public/lovestyle_interest', 'module');
}
//重新测试
function lovestyle_restart(){
	global $userid,$tc_id_arr;
	$tcid = MooGetGPC('tcid','integer','G');
	$tcid--;
	if($tcid < 0){ $tcid = 0;}
	if($tcid >= sizeof($tc_id_arr)){ $tcid = sizeof($tc_id_arr)-1;}
	$tc_id = $tc_id_arr[$tcid];

	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}test_vote 
		WHERE `uid`='{$userid}' AND `tc_id`='{$tc_id}' ";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql,true);
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}test_member 
		WHERE `uid`='{$userid}' AND `tc_id`='{$tc_id}' ";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql,true);
	header("Location:index.php?n=lovestyle");
}

function lovestyle_result(){
	global $userid,$tc_id_arr;
	//preg_match("/&h=(.*)/i",$_SERVER['HTTP_REFERER'],$harr);
	$tcid = MooGetGPC('tcid','integer','G');
	$tcid--;
	if($tcid < 0){ $tcid = 0;}
	if($tcid >= sizeof($tc_id_arr)){ $tcid = sizeof($tc_id_arr)-1;}
	$tc_id = $tc_id_arr[$tcid];
	$test_info = $result_id = array();
	$table = $GLOBALS['dbTablePre'] . 'test_member';
	$test_sql = "SELECT `scores_count`,`ctype`,`result_id` FROM {$GLOBALS['dbTablePre']}test_member 
		WHERE `uid`='{$userid}' AND `tc_id`='{$tc_id}' ";
	$test_info = $GLOBALS['_MooClass']['MooMySQL']->getAll($test_sql);
	if(empty($test_info)){
		set_info_vote($tc_id);
		$test_info = $GLOBALS['_MooClass']['MooMySQL']->getAll($test_sql);
	}
	if(empty($test_info)){
		MooMessage("请完成测试再来查看结果。",'index.php?n=lovestyle');
	}
	//print_r($test_info);
	//note 综合评测结果 tc_id === ctype 虽然我不知道有什么好处，但是也没有坏处 ps:dsk
	foreach($test_info as $ti){
		$result_id[] = $ti['result_id'];
		if($tc_id == $ti['ctype']) $scores_count = $ti['scores_count'];//测试总分
	}
	$result_id = implode(',',$result_id);
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}test_result WHERE `id` IN ({$result_id})";
	$my_result = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$results = array();
	foreach($my_result as $k=>$v){//结果如果需要段落显示可以按照 '<br />'拆分 参考后台添加测试结果
		$results[$v['rtype']] = array('ctypename'=>$v['ctypename'],'result'=>$v['result']);
	}
	//print_r($results);
	include MooTemplate('public/lovestyle_result_'.++$tcid, 'module');
}

//note 星座分析
function lovestyle_constellate(){
	//lovestyle-cst1-01.htm
	$type = 'constellate';
	$typenum = 1;
	if(isset($_GET['type']))
		$typenum =  (int)$_GET['type'];
	$tag = 1;
	if(isset($_GET['tag']))
		$tag = (int)$_GET['tag'];
	if($typenum < 1 || $typenum > 12) $typenum = 1;
	if($tag < 1 || $tag > 6) $tag = 1;
	include MooTemplate('public/lovestyle_cst'.$typenum.'_0'.$tag, 'module');
}

//note 生肖分析
function lovestyle_animals(){
	$type = 'animals';
	$typenum = 1;
	if(isset($_GET['type']))
		$typenum = (int)$_GET['type'];
	$tag = 1;
	if(isset($_GET['animalstag']))
		$tag = (int)$_GET['animalstag'];
	if($typenum < 1 || $typenum > 12) $typenum = 1;
	if($tag < 1 || $tag > 4) $tag = 1;
	include MooTemplate('public/lovestyle_ani'.$typenum.'_0'.$tag, 'module');
}

//note 血型分析
function lovestyle_btyanalysis(){
	$type = 'bty';
	$typenum = isset($_GET['type']) ? trim(MooGetGPC('type', 'integer', 'G')) : '1';
	$tag = isset($_GET['btytag']) ? (int)$_GET['btytag'] : 1;
	if($typenum < 1 || $typenum > 4) $typenum = 1;
	if($tag < 1 || $tag > 3) $tag = 1;
	include MooTemplate('public/lovestyle_bty'.$typenum.'_0'.$tag,'module');
}

/***************************************   控制层(C)   ****************************************/

$h = MooGetGPC('h', 'string');

$back_url='index.php?'.$_SERVER['QUERY_STRING'];
if(!$userid) {header("location:login.html");} //&back_url=".urlencode($back_url)

require 'data/cache/hntest_cache.php';//此文件后台生成 $hntest_cache

//note 本来我是把测试的tc_id直接写死的（上线改动不大），但现在不是的（灵活）。ps:dsk
$tc_id_arr = array_keys($hntest_cache['parent']);

switch ($h) {
	case 'love'://爱情测试
		$tc_id = $tc_id_arr[0];
		lovestyle_love();
	break;
	case 'interest'://趣味爱情测试
		$tc_id = $tc_id_arr[1];
		lovestyle_interest();
	break;
	case 'result':
		lovestyle_result();
	break;
	case 'restart':
		lovestyle_restart();
	break;
	case 'index':
		lovestyle_index();
	break;
	//note 星座分析
	case 'constellate':
		lovestyle_constellate();
	break;
	//note 生肖分析
	case 'animals':
		lovestyle_animals();
	break;
	//note 血型分析
	case 'btyanalysis':
		lovestyle_btyanalysis();
	break;
	default :
		
		lovestyle_index();
	break;	
}


?>