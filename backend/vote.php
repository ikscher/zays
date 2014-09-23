<?php 
//note 配置文件
require './include/config.php';

//note 加载Moo框架
require '../framwork/MooPHP.php';

//note 加载后台共用方法文件
require './include/function.php';

//note 权限菜单分类配置文件  
require './include/menu.inc.php';

$db = $_MooClass['MooMySQL'];
function lister(){
	
	GLOBAL $db;
	$sql = "select count(vid) as count from vote";
	$count = $db->getOne($sql);
	$page_num = 5;
	$page_all =  $count['count']/$page_num;
	for($i=0;$i<$page_all;$i++){
		$page[$i] = $i+1;
	}
	if(empty($_REQUEST['page_now'])){
		$page_now = 1;
	}else{
		$page_now = $_REQUEST['page_now'];
	}
	$page_one = ($page_now-1)*$page_num;
	$sql = "select * from vote order by vid desc limit $page_one,$page_num";
	$content = $db->getAll($sql);
	foreach($content as $k => $v){
		$content[$k]['vote_endtime'] = substr($v['vote_endtime'],0,4).'-'.substr($v['vote_endtime'],4,2).'-'.substr($v['vote_endtime'],6,2).' '.substr($v['vote_endtime'],8,2).':'.substr($v['vote_endtime'],10,2);
		$content[$k]['vote_starttime'] = substr($v['vote_starttime'],0,4).'-'.substr($v['vote_starttime'],4,2).'-'.substr($v['vote_starttime'],6,2).' '.substr($v['vote_starttime'],8,2).':'.substr($v['vote_starttime'],10,2);
	}
	$sql = "select distinct(`group`) from vote";
	$group = $db->getAll($sql);
	
	
	adminTemplate('vote_list');
	require_once(adminTemplate('vote_list'));
	
}

//逻辑显示层！！！！！！！！！！！！！！！！！！！！！！！！！
function edit($vid){
	GLOBAL $db;
	$sql = "select vote_style from vote where vid= $vid";
	$style = $db->getOne($sql);
	
	$sql = "select t1.*,t2.content from vote as t1 left join vote_content as t2 on t1.vid = t2.vid where t1.vid = $vid";
	$content = $db->getAll($sql);

	
	foreach($content[0] as $k => $v){
		if($content[0][$k] == '0'){
			$content[0][$k] = "";
		}
		if($content[0]['vote_style'] == ""){
			$content[0]['vote_style'] = 0;
		}
	}
	foreach($content as $k => $v){
		$content[$k]['vote_endtime'] = substr($v['vote_endtime'],0,4).'-'.substr($v['vote_endtime'],4,2).'-'.substr($v['vote_endtime'],6,2).' '.substr($v['vote_endtime'],8,2).':'.substr($v['vote_endtime'],10,2);
		$content[$k]['vote_starttime'] = substr($v['vote_starttime'],0,4).'-'.substr($v['vote_starttime'],4,2).'-'.substr($v['vote_starttime'],6,2).' '.substr($v['vote_starttime'],8,2).':'.substr($v['vote_starttime'],10,2);
		$content_ = explode(';',$v['content']);	
	
	}
	array_pop($content_);
	
	foreach($content_ as $k => $v){
		$content_1[] = explode(':',$v);
	}
	if(is_array($content_1)){
		foreach($content_1 as $k => $v){
			$result[$v[0]] = $v[1];
		}
		foreach($result as $k => $v){
			if($v == '0'){
				$result[$k] = "";
			}
		}
	}
	adminTemplate('vote_edit');
	require_once(adminTemplate('vote_edit'));
}
function update($array){
	GLOBAL $db;
	$array = array_handle($array);
	
	if($array['is_dele'] == 0){
		$sql = "update vote SET vote_title='$array[title]',`group`='$array[group]',vote_style='$array[style]',is_show='$array[is_show]',vote_starttime='$array[start_time]',vote_endtime='$array[end_time]' WHERE vid = $array[vid]";
		$db->query($sql);
		
		$sql = "update vote_content SET content = '$array[content]' WHERE vid = $array[vid]";
		$db->query($sql);
	}else if($array['is_dele'] == 1){
		$sql = "update vote SET vote_title='$array[title]',vote_style='$array[style]',is_show='$array[is_show]',vote_starttime='$array[start_time]',vote_endtime='$array[end_time]' WHERE vid = $array[vid]";
		$db->query($sql);
		
		$sql = "update vote_content SET content = '$array[content]' WHERE vid = $array[vid]";
		$db->query($sql);
		
		$sql = "update vote_result SET option1='0',option2='0',option3='0',option4='0',option5='0',option6='0',option7='0',option8='0',option9='0',option10='0',text='0',num_people='0' WHERE vid = $array[vid]";
		$db->query($sql);
		
		$sql = "delete FROM vote_sub WHERE vid = $array[vid]";
		$db->query($sql);
	}
	MooMessageAdmin('编辑成功','vote.php',1);
	
}
function array_handle($array){
	
	foreach($array as $k => $v){
		if($array[$k] == ''){
			$array[$k] =0;
		}
		$array['start_time'] = Time_handling($array['start_time']);
		$array['end_time'] = Time_handling($array['end_time']);
	}
	
	foreach($array as $k => $v){
		if(substr($k,0,6) =='option'){
			$array['content'] .=  substr($k,6).':'.$v.';';
		}
		
	}
	return $array;
	
}
function update_view($arr){
	GLOBAL $db;
	$number = $_REQUEST[number];
	$sql = "update vote_view SET number = '$number',`group` = '$_REQUEST[group]'";
	$db->query($sql);
	MooMessageAdmin('修改成功','vote.php',1);
}
function dele($vid){
	GLOBAL $db;
	
	$sql = "DELETE FROM vote WHERE vid = $vid";
	$db->query($sql);
	$sql = "DELETE FROM vote_result WHERE vid = $vid";
	$db->query($sql);
	$sql = "DELETE FROM vote_content WHERE vid = $vid";
	$db->query($sql);
	$sql = "DELETE FROM vote_sub WHERE vid = $vid";
	$db->query($sql);
	MooMessageAdmin('删除成功','vote.php',1);
}

function see($vid){
	GLOBAL $db;
	$vid = $vid;
	adminTemplate('vote_see');
	require_once(adminTemplate('vote_see'));
}
function view($arr){
	GLOBAL $db;
	$check = '1';
	$sex = $arr['sex'];
	
	$age_min = $arr['age_min'];
	$age_max = $arr['age_max'];
	$vid = $arr['vid'];
	$agemin = (DATE(Y)-$age_min).'0000';
	$agemax = (DATE(Y)-$age_max).'0000';
	$sql = "select * from vote_content where vid = '$vid'";
	$option = $db->getOne($sql);
	/*foreach($option as $k => $v){
		if(substr($k,0,6) =='option'){
			$array []=  substr($k,7).':'.$v.';';
		}
		
	}
	var_dump($array);
	*/
	$a = $option['option_1'];
	$b = $option['option_2'];
	$c = $option['option_3'];
	$d = $option['option_4'];
	$e = $option['option_5'];
	$f = $option['option_6'];
	$g = $option['option_7'];
	$h = $option['option_8'];
	$i = $option['option_9'];
	$j = $option['option_10'];
	if($sex != '' && $sex != '0'){
		$where.= "and t1.sex = '$sex'";
	}else{
		$where .= "";
	}
	
		if($age_min == "" && $age_max == ""){
			$where .= "";
		}else if($age_min != "" && $age_max == ""){
			$where .= "and t1.birthdate<'$agemin'";
		}else if($age_min == "" && $age_max != ""){
			$where .= "and t1.birthdate>'$agemax'";
		}else{
			$where .= "and t1.birthdate<'$agemin' and t1.birthdate>'$agemax'";
		}
	
	$sql = "select t2.vote_result from vote_member as t1 left join vote_sub as t2 on t1.uid = t2.uid where t2.vid = '$vid' $where";
	$con = $db->getAll($sql);
	
	foreach($con as $k => $v){
		if(strpos($v['vote_result'],',')){
			echo "a";
		}else{
			if($v['vote_result'] == '0' || $v['vote_result'] == ''){
				unset($con[$k]);
			}
			//array_unique($con[$k]);
		}
	}
	//var_dump($con);
	
	foreach($con as $k => $v){
		$conn[] = explode(';',$v["vote_result"]);
	}
	if(is_array($conn)){
	foreach($conn as $k => $v){
		array_pop($v);
		foreach($v as $key => $value){
			$cont[] = explode(':',$value);
		}
	}
	
	//var_dump($cont);
		foreach($cont as $k => $v){
			$cont1[$k][] = $v[0];
			
			
			if($v[1] == '0'  || empty($v[1])){
					unset($cont[$v[0]]);
				}
			}
		foreach($cont1 as $k => $v){
			$array1[]= $v[0];
			
		}
		
	$result_2 = array_count_values($array1);
	
	
	//t1.sex = '$sex' and t1.birthdate<'$agemin' and t1.birthdate>'$agemax'
	$sql = "select SUM(t2.A) as '$a',SUM(t2.B) as '$b',SUM(t2.C) as '$c',SUM(t2.D) as '$d',SUM(t2.E) as '$e',SUM(t2.F) as '$f',SUM(t2.G) as '$g',SUM(t2.H) as '$h',SUM(t2.I) as '$i',SUM(t2.J) as '$j',SUM(t2.TEXT) as text from vote_member as t1 left join vote_sub as t2 on t1.uid = t2.uid where t2.vid = '$vid' $where";
	$result = $db->getAll($sql);
	
	/*foreach($result['0'] as $k => $v){
		if($v == '0'){
			unset($result['0'][$k]);
		}
		
	}
	$result_1 = $result['0'];*/
	$sql = "select vote_title from vote where vid = '$vid'";
	$vote_title = $db->getOne($sql);
	$title_1 = $vote_title['vote_title'];
		$num_people_1 = $list_one[num_people];
		$list_1 .= "<graph caption='$title_1'  decimalPrecision='0' formatNumberScale='0' chartRightMargin='30' baseFontSize='15'>";
		$color = array('F6BD0F','8BBA00','FF8E46','008E8E','D64646','8E468E','588526','B3AA00','008ED6','9D080D','A186BE');

		$key = 0;
		$height_1 = 150+count($result_2)*30;
		foreach($result_2 as $k => $v){
			$key++;
			$list_1 .="<set name='$k' value='$v' color='$color[$key]'/>";
		}
	$list_1 .="</graph>";
	}
	adminTemplate('vote_see');
	require_once(adminTemplate('vote_see'));
}


//下面代码为控制层代码！！！！！！！！！！！！！！！！！！！！！
if($_REQUEST['act']=='edit'){
	edit($_REQUEST[vid]);
}else if($_REQUEST['act']=='delect'){
	dele($_REQUEST[vid]);
}else if($_REQUEST['act'] == 'edit_sub'){
	update($_REQUEST);	
}else if($_REQUEST['act'] == 'see'){
	see($_REQUEST[vid]);
}else if($_REQUEST['act'] == 'view'){
	view($_REQUEST);
}else if($_REQUEST['act'] == 'update_view'){
	update_view($_REQUEST);
}else{
	lister();
}



?>