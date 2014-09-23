<?php

function get_father_class(){
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}test_class WHERE `parent`='0' ";
	$temp = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	return $temp;
}

//处理问题选项（更新、删除、增加）
function check_option($qid, $option, $scores, $option_box){
	//if($qid) return;
	$rub_str = $sql = '';
	$rub_qid = array();
	$option_box = explode(',',$option_box);
	foreach($option as $k=>$v){
		if (empty($v) || empty($scores[$k])) {continue;}
		if( in_array( (string)$k, $option_box) ){//bug (string)$k in_array(0,$option_box)
			$sql = "UPDATE {$GLOBALS['dbTablePre']}test_question_option 
				SET `option`='{$v}',`scores`='{$scores[$k]}' 
				WHERE `op_id`='{$k}' AND `qid`='{$qid}' ";
			$rub_qid[] = $k;
		}else{
			$sql = "INSERT INTO {$GLOBALS['dbTablePre']}test_question_option 
				(`qid`,`option`,`scores`)
				VALUES('{$qid}','{$v}','{$scores[$k]}') ";
			
		}
		echo $sql.'<br>';
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}
	//去掉$option_box在$ub_qid中出现相同值
	$rub_str = implode(',',array_diff($option_box,array_intersect($option_box,$rub_qid)));
	if(!empty($rub_str)){
		$sql = "DELETE FROM {$GLOBALS['dbTablePre']}test_question_option 
			WHERE `op_id` IN ($rub_str) AND `qid`='{$qid}' ";
			echo $sql;
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}
}
?>