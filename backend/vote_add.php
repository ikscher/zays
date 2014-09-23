<?php
//note 配置文件
require './include/config.php';

//note 加载Moo框架
require '../framwork/MooPHP.php';

//note 加载后台共用方法文件
require './include/function.php';

//note 权限菜单分类配置文件  
require './include/menu.inc.php';

function questions_style($style,$vid){		
		require_once(adminTemplate('vote_style'));
}

/*if($act != 'add'){
	MooMessageAdmin('拜托，请按流程，谢谢','vote.php',1);
}*/
$db = $_MooClass['MooMySQL'];
if($_REQUEST['action']){
	$action = $_REQUEST['action'];
}else{
	$action = 'list';
}
if($action == 'list'){
	require_once(adminTemplate('vote_add'));
}else if($action == 'vote' && empty($_REQUEST['h'])){
	if($_REQUEST['check'] != 1){
		MooMessageAdmin('请填写前一表单','vote.php',1);
	}
	$style = $_REQUEST['style'];
	
	
	if($_REQUEST['is_show'] == 'on' || $_REQUEST['is_show'] == '1'){
		$is_show = 1;
	}else{
		$is_show = 0;
	}
	$sql = "select vid from vote where vote_title = '$_REQUEST[title]'";
	$vote_vid = $db->getOne($sql);
	$vid = $vote_vid['vid'];
	
	if($vid){	
		$sql = "select * from vote_content where vid = '$vid'";
		$content = $db->getOne($sql);
		
		if(empty($content)){
			
		}else{
			MooMessageAdmin('该题目已经存在','vote.php',1);
		}
	}else{
		
			$start_time = Time_handling($_REQUEST['start_time']);
			$end_time = Time_handling($_REQUEST['end_time']);
			$sql = "insert into vote(vote_title,vote_style,is_show,`group`,vote_starttime,vote_endtime) values ('$_REQUEST[title]','$style','$is_show','$_REQUEST[group]','$start_time','$end_time')";
	    	$db->query($sql);
	}
	
	$sql = "select vid,vote_style from vote where vote_title = '$_REQUEST[title]'";
	$vote_vid = $db->getOne($sql);
	
	$vid = $vote_vid['vid'];
	if($vote_vid['vote_style']){
		$style = $vote_vid['vote_style'];
	}else{
		$style = $_REQUEST['style'];
	}
	
	require_once(adminTemplate('vote_style'));
	
}else if($action == 'vote' && $_REQUEST['h']){
	if($_REQUEST['check'] != 1){
		MooMessageAdmin('请填写前一表单','vote.php',1);
	}
	$number = count($_REQUEST) - 3;
	$h = $_REQUEST['h']?$_REQUEST['h']:0;
	if(empty($_REQUEST['text'])){
		for($i=1;$i<=$number;$i++){
			$option = 'option'.$i;
			$option = $_REQUEST[$option]?$_REQUEST[$option]:0;
			$content .= $i.':'.$option.';';
			
		}
	}else{
		$content .= 'text:'.$_REQUEST['text'].';';
	}
	
	$sql = "select vid from vote_content where vid = '$h'";
	$option1 = $_REQUEST['option1']?$_REQUEST['option1']:0;
	$option2 = $_REQUEST['option2']?$_REQUEST['option2']:0;
	$option3 = $_REQUEST['option3']?$_REQUEST['option3']:0;
	$option4 = $_REQUEST['option4']?$_REQUEST['option4']:0;
	$option5 = $_REQUEST['option5']?$_REQUEST['option5']:0;
	$option6 = $_REQUEST['option6']?$_REQUEST['option6']:0;
	$option7 = $_REQUEST['option7']?$_REQUEST['option7']:0;
	$option8 = $_REQUEST['option8']?$_REQUEST['option8']:0;
	$option9 = $_REQUEST['option9']?$_REQUEST['option9']:0;
	$option10 = $_REQUEST['option10']?$_REQUEST['option10']:0;
	//$text = $_REQUEST['text']?$_REQUEST['text']:0;
	if(!($db->getOne($sql))){		
		//$sql = "insert into vote_content(vid,option_1,option_2,option_3,option_4,option_5,option_6,option_7,option_8,option_9,option_10,text) values ('$h','$option1','$option2','$option3','$option4','$option5','$option6','$option7','$option8','$option9','$option10','$text')";
		$sql = "insert into vote_content(vid,content) values ('$h','$content')";
		$db->query($sql);
		$sql = "insert into vote_result(vid,option1,option2,option3,option4,option5,option6,option7,option8,option9,option10,text,num_people) values ('$h','0','0','0','0','0','0','0','0','0','0','0','0')";
		$db->query($sql);
		MooMessageAdmin('提交成功','vote.php',1);	
		
	}else{
		MooMessageAdmin('该题目已有内容','vote.php',1);
	}	
}

?>