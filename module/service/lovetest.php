<?php
$answernum = 3;		//可以选择数量

$sql = "select * from web_lovetest where t_uid=".$userid;
$result = $_MooClass['MooMySQL']->getOne($sql);
//$result = $_MooClass['MooMySQl']->getOne($sql);
$testnum=count($result)-3;
if(!empty($_POST['radio1'])){
	for($i=1;$i<=$testnum;$i++){
		$sql="update web_lovetest set t_field".$i."=".$_POST['radio'.$i];
		$_MooClass['MooMySQL']->query($sql);
	}
	$sql="update web_lovetest set t_iscomplete =1";
	$_MooClass['MooMySQL']->query($sql);
}
$sql = "select * from web_lovetest where t_uid=".$userid;
$result = $_MooClass['MooMySQL']->getOne($sql);
//$result = $_MooClass['MooMySQl']->getOne($sql);
$testnum=count($result)-3;

if($result['t_iscomplete']==1){
	$n=0;
	for($i=0;$i<$testnum;$i++){
		$n=$n+$result["t_field".($i+1)];
	}
	if($n>=10)
		$content="结果1";
	else if($n>=5)
		$content="结果2";
	else
		$content="结果3";
}

//echo $content;
require MooTemplate('public/service_love_test', 'module');

