<?php
/*************************************** 逻辑层(M)/表现层(V) ****************************************/ 

include "./module/{$name}/function.php";
//note 测试入口
function loveing_index(){
	include MooTemplate('public/loveing_index', 'module');
}

function loveing_hntesta(){
	//$test_lists = get_test_lists(1);
	$test = get_test_one(1);
	if($test['num'] == $test['count']){
		$tc_id = $test['tc_id'];
		unset($test);
		loveing_result($tc_id);
		exit;
	}
	//echo 'aaaaaaaaaaaaaaaaa';
	include MooTemplate('public/loveing_hntesta', 'module');
}
function loveing_hntestb(){
	$test_list = get_test_lists(2);
	echo 'bbbbbbbbbbbbbbbbb';
	//include MooTemplate('public/loveing_hntestb', 'module');
}

function loveing_result($tc_id){
	global $userid;
	$test_info = $result_id = array();
	$table = $GLOBALS['dbTablePre'] . 'test_member';
	$test_sql = "SELECT `result_id` FROM {$GLOBALS['dbTablePre']}test_member 
		WHERE `uid`='{$userid}' AND `tc_id`='{$tc_id}' ";
	$test_info = $GLOBALS['_MooClass']['MooMySQL']->getAll($test_sql);
	if(empty($test_info)){
		print_r(get_info_vote($tc_id));
		$test_info = $GLOBALS['_MooClass']['MooMySQL']->getAll($test_sql);
	}
	foreach($test_info as $ti){
		$result_id[] = $ti['result_id'];
	}
	$result_id = implode(',',$result_id);
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}test_result WHERE `id` IN ({$result_id})";
	$my_result = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	print_r($my_result);
	echo '查看结果';
}
/***************************************   控制层(C)   ****************************************/

$h = MooGetGPC('h', 'string');
$typeid=MooGetGPC('typeid','integer');

if(isset($_GET['testid'])){
	$_SESSION['testid'] = (int)$_GET['testid'];
}

$back_url='index.php?'.$_SERVER['QUERY_STRING'];
if(!$userid) {header("location:index.php?n=login&back_url=".urlencode($back_url));}

switch ($h) {
	case 'default'://所有测试页面
		mdefault();
	break;
	case 'hntesta':
		loveing_hntesta();
	break;
	case 'hntestb':
		loveing_hntestb();
	break;
	case 'index':
	default :
		loveing_index();
	break;	
}


?>