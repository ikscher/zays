<?php
/*******************************************逻辑层(M)/表现层(V)*****************************************/
function hntest_question_list(){
	global $hntest_cache;
	$page_size = 16;
	$show_type_arr = array('未知','自定义','符合（5级）','同意（7级）','重要（7级）');
	$table = $GLOBALS['dbTablePre'].'test_question';
	$page = max(1,MooGetGPC('page','integer','G'));
	$offset = ($page - 1)*$page_size;

	$sql = "SELECT COUNT(*) AS c FROM {$table}";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$total = (int)$total['c'];

    $sql = "SELECT * FROM {$table} ORDER BY ctype,qid DESC LIMIT {$offset},{$page_size}";
    $lists = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    
	$currenturl = 'index.php?action=hntest&h=question_list';
    $page_links = multipage( $total, $page_size, $page, $currenturl );
	require adminTemplate('hntest_question_list');
}

function hntest_class_list(){
	$page_size = 16;
	$table = $GLOBALS['dbTablePre'].'test_class';
	$parent = MooGetGPC('parent','integer','G');
	$page = max(1,MooGetGPC('page','integer','G'));
	$offset = ($page - 1)*$page_size;

	$sql = "SELECT COUNT(*) AS c FROM {$table} WHERE `parent`='{$parent}' ";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$total = (int)$total['c'];

    $sql = "SELECT * FROM {$table} 
		WHERE `parent`='{$parent}' 
		ORDER BY 'tc_id' ASC 
		LIMIT {$offset},{$page_size}";
    $lists = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    
	$currenturl = 'index.php?action=hntest&h=class_list';
    $page_links = multipage( $total, $page_size, $page, $currenturl );
	require adminTemplate('hntest_class_list');
}

function hntest_result_list(){
	global $hntest_cache;
	$page_size = 16;
	$table = $GLOBALS['dbTablePre'].'test_result';
	$parent = MooGetGPC('parent','integer','G');
	$page = max(1,MooGetGPC('page','integer','G'));
	$offset = ($page - 1)*$page_size;

	$sql = "SELECT COUNT(*) AS c FROM {$table} ";
	$total = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$total = (int)$total['c'];

    $sql = "SELECT * FROM {$table} 
		ORDER BY 'tc_id' ASC 
		LIMIT {$offset},{$page_size}";
    $lists = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);

	$currenturl = 'index.php?action=hntest&h=result_list';
    $page_links = multipage( $total, $page_size, $page, $currenturl );
	require adminTemplate('hntest_result_list');
}

function hntest_class_modify(){
	$table = $GLOBALS['dbTablePre'].'test_class';
	$tc_id = MooGetGPC('tc_id','integer');
	$parent = MooGetGPC('parent','integer','G');
	if($_POST){
		$test_name = trim(MooGetGPC('test_name','string','P'));
		$parent = MooGetGPC('parent','integer','P');
		$sql = "INSERT INTO {$table} (`test_name`,`parent`) 
			VALUES('{$test_name}','{$parent}') ";
		$msg = '添加成功。';
		if($tc_id > 0){
			$sql = "UPDATE {$table} SET `test_name`='{$test_name}' WHERE `tc_id`='{$tc_id}' ";
			$msg = '修改成功。';
		}
		//echo $sql;exit;
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		MooMessageAdmin($msg,'',1);
	}
	$parent_class = get_father_class();
	$sql = "SELECT * FROM {$table} WHERE `tc_id`='{$tc_id}' ";
	$class = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	require adminTemplate('hntest_class_modify');
}

function hntest_result_modify(){
	$table = $GLOBALS['dbTablePre'].'test_result';
	if($_POST){
		$id = MooGetGPC('id','integer','P');
		$tc_id = MooGetGPC('father_id','integer','P');
		$rtype = MooGetGPC('tc_id','integer','P');
		if($rtype == 0)$rtype = $tc_id;//这里作为综合结果
		$ctypename = trim(MooGetGPC('ctypename','string','P'));
		$st_scores = MooGetGPC('st_scores','integer','P');
		$ed_scores = MooGetGPC('ed_scores','integer','P');
		$result = MooGetGPC('result','string','P');
		$sql = "INSERT INTO {$table} (`tc_id`,`st_scores`,`ed_scores`,`result`,`rtype`,`ctypename`) 
			VALUES ('{$tc_id}','{$st_scores}','{$ed_scores}','{$result}','{$rtype}','{$ctypename}') ";
		$msg = '添加';
		//`tc_id`='{$tc_id}',`rtype`='{$rtype}' 
		if($id > 0){
			$sql = "UPDATE {$table} SET 
					`st_scores`='{$st_scores}',
					`ed_scores`='{$ed_scores}',
					`result`='{$result}', 
					`ctypename`='{$ctypename}' 
				WHERE `id`='{$id}' ";
			$msg = '修改';
		}
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		MooMessageAdmin($msg.'成功','',1);
	}
	$id = MooGetGPC('id','integer','G');
	$sql = "SELECT * FROM {$table} WHERE `id`='{$id}' ";
	$results = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$sql = "SELECT `ed_scores` FROM {$table} 
		WHERE `id`<'{$id}' 
			AND `tc_id`='{$results['tc_id']}' 
			AND `rtype`='{$results['rtype']}' 
		LIMIT 1 ";
	$last_ed_scores = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$last_ed_scores = (int)$last_ed_scores['ed_scores'] + 1;
	$parent_class = get_father_class();
	require adminTemplate('hntest_result_modify');
}

function hntest_question_modify(){
	$table = $GLOBALS['dbTablePre'].'test_question';
	//$tc_id = MooGetGPC('tc_id','integer');
	$qid = MooGetGPC('qid','integer');
	if($_POST){
		$tc_id = MooGetGPC('father_id','integer','P');
		$ctype = MooGetGPC('tc_id','integer','P');
		$question = trim(MooGetGPC('question','string','P'));
		$parent = MooGetGPC('parent','integer','P');
		$option = MooGetGPC('option','array','P');
		$scores = MooGetGPC('scores','array','P');
		$option_box = MooGetGPC('option_box','string','P');
		$show_type = MooGetGPC('show_type','integer','P');//str_scores
		$str_scores = '';
		if($show_type != 1){
			$str_scores = implode(',',$scores);
		}

		$sql = "INSERT INTO {$table} (`tc_id`,`question`,`ctype`,`show_type`,`str_scores`) 
			VALUES('{$tc_id}','{$question}','{$ctype}','{$show_type}','{$str_scores}') ";
			//echo $sql;exit;
		$msg = '添加成功。';
		if($qid > 0){
			$sql = "UPDATE {$table} 
				SET `question`='{$question}', 
					`ctype`='{$ctype}', 
					`show_type`='{$show_type}', 
					`str_scores`='{$str_scores}' 
				WHERE `qid`='{$qid}' ";
			$msg = '修改成功。';
		}
		else if ($tc_id == 0)
		{
			MooMessageAdmin('题目类别错误','',1);
		}
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		if($qid == 0){
			$qid = $GLOBALS['_MooClass']['MooMySQL']->insertId();
		}
		if($show_type == 1){//只有为自定义的时候才写表
			check_option($qid, $option, $scores, $option_box);
		}
		MooMessageAdmin($msg,'',1);
	}
	$parent_class = get_father_class();
	$sql = "SELECT * FROM {$table} WHERE `qid`='{$qid}' ";
	$question = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	//测试题目选项
	if($question['show_type'] == 1 || $question['show_type'] == 0){
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}test_question_option 
		WHERE `qid`='{$qid}' ";
	$options = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	}else{
		//赋值个页面js变量
		$arr = array('','','fuhe','tongyi','zhongyao');
		$targ = $arr[$question['show_type']];
		$str_scores = '['.$question['str_scores'].']';
	}
	require adminTemplate('hntest_question_modify');
}

function hntest_question_del(){
	$show_type = MooGetGPC('show_type','integer','G');
	$qid = MooGetGPC('qid','integer','G');
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}test_question WHERE qid={$qid} ";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	if($show_type == '1' || $show_type == '0'){
		$sql = "DELETE FROM {$GLOBALS['dbTablePre']}test_question_option WHERE qid={$qid} ";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}
	MooMessageAdmin('删除成功','index.php?action=hntest&h=question_list',1);
}

function hntest_result_del(){
	$id = MooGetGPC('id','integer','G');
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}test_result WHERE id={$id} ";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	MooMessageAdmin('删除成功','index.php?action=hntest&h=result_list',1);
}

function hntest_class_del(){
	$tc_id = MooGetGPC('tc_id','integer','G');
	$parent = MooGetGPC('parent','integer','G');
	$table = $GLOBALS['dbTablePre'].'test_class';
	if($parent == 0){
		$sql = "DELETE FROM {$table} WHERE parent={$tc_id}";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}
	$sql = "DELETE FROM {$table} WHERE tc_id={$tc_id}";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	MooMessageAdmin('删除成功','index.php?action=hntest&h=class_list',1);
}

function hntest_cache(){
	$table = $GLOBALS['dbTablePre'].'test_class';
	$cache_file = '../data/cache/hntest_cache.php';
	$sql = "SELECT * FROM {$table} ";
	$temp = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$data = array();
	foreach($temp as $class){
		$key = $class['parent'] == 0 ? 'parent' : 'children' ;
		$data[$key][$class['tc_id']] = $class['test_name'];
	}

	$sql = "SELECT COUNT(*) AS total,tc_id FROM {$GLOBALS['dbTablePre']}test_question GROUP BY `tc_id`";
	$temp = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$total_arr = array();
	foreach($temp as $v){
		$data['count'][$v['tc_id']] = $v['total'];
	}
	file_put_contents( $cache_file, "<?php\n\$hntest_cache=".var_export($data,true).";\n?>");
	require $cache_file;
	if( !empty($hntest_cache) ){
		echo '缓存重建成功，请重新刷新页面 <br>';
		print_r($hntest_cache);
	}else{
		echo '缓存生成失败！';
	}
}
/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string')=='' ? 'question_list' : MooGetGPC('h','string');
//note 判断是否有权限
//echo 'hntest_'.$h;exit;
require 'include/hntest_function.php';
if( !file_exists('../data/cache/hntest_cache.php') ){
	hntest_cache();
}
require '../data/cache/hntest_cache.php';
if(!checkGroup('hntest',$h)){
    MooMessageAdmin('您没有此操作的权限','index.php?action=admin&h=index',1);
}
if(isset($_GET['cache'])){$h = 'cache';}
switch($h){
	case 'question_list':
		hntest_question_list();
	break;
	case 'question_modify':
		hntest_question_modify();
	break;
	case 'question_del':
		hntest_question_del();
	break;
	case 'result_list':
		hntest_result_list();
	break;
	case 'result_modify':
		hntest_result_modify();
	break;
	case 'result_del':
		hntest_result_del();
	break;
	case 'class_list':
		hntest_class_list();
	break;
	case 'class_modify':
		hntest_class_modify();
	break;
	case 'class_del':
		hntest_class_del();
	break;
	case 'cache'://测试用到的数据
		hntest_cache();
	break;
}
?>