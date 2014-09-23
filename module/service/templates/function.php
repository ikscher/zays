<?php 
	date_default_timezone_set('Asia/Shanghai');
	function data_handling($vote){
		foreach($vote as $k => $v){
			if($v["option_1"]=="0"){
				unset($vote[$k]['option_1']);
			}else{
				$vote[$k]['option']['1'] = $v['option_1'];
				unset($vote[$k]['option_1']);
			}
			if($v['option_2'] == "0"){
				unset($vote[$k]['option_2']);
			}else{
				$vote[$k]['option']['2'] = $v['option_2'];
				unset($vote[$k]['option_2']);
			}
			if($v['option_3'] == "0"){
				unset($vote[$k]['option_3']);
			}else{
				$vote[$k]['option']['3'] = $v['option_3'];
				unset($vote[$k]['option_3']);
			}
			if($v['option_4'] == "0"){
				unset($vote[$k]['option_4']);
			}else{
				$vote[$k]['option']['4'] = $v['option_4'];
				unset($vote[$k]['option_4']);
			}
			if($v['option_5'] == "0"){
				unset($vote[$k]['option_5']);
			}else{
				$vote[$k]['option']['5'] = $v['option_5'];
				unset($vote[$k]['option_5']);
			}
			if($v['option_6'] == "0"){
				unset($vote[$k]['option_6']);
			}else{
				$vote[$k]['option']['6'] = $v['option_6'];
				unset($vote[$k]['option_6']);
			}
			if($v['option_7'] == "0"){
				unset($vote[$k]['option_7']);
			}else{
				$vote[$k]['option']['7'] = $v['option_7'];
				unset($vote[$k]['option_7']);
			}
			if($v['option_8'] == "0"){
				unset($vote[$k]['option_8']);
			}else{
				$vote[$k]['option']['8'] = $v['option_8'];
				unset($vote[$k]['option_8']);
			}
			if($v['option_9'] == "0"){
				unset($vote[$k]['option_9']);
			}else{
				$vote[$k]['option']['9'] = $v['option_9'];
				unset($vote[$k]['option_9']);
			}
			if($v['option_10'] == "0"){
				unset($vote[$k]['option_10']);
			}else{
				$vote[$k]['option']['10'] = $v['option_10'];
				unset($vote[$k]['option_10']);
			}
			if($v['text'] == "0"){
				unset($vote[$k]['text']);
			}else{
				$vote[$k]['option']['text'] = $v['text'];
				unset($vote[$k]['text']);
			}
		}
		return $vote;	
	}
	
	function date_handling(){
		
		$date = DATE('YmdHi');
		return  $date;
	}
	
	
/**
* 终止程序执行，显示提示信息
* @param string $message 显示的信息文本
* @param string $urlForward 跳转地址，默认为空
* @param string $time 链接跳转时间，默认为3秒
* @return 无返回值
*/
function MooMessageAdmin($message, $urlForward = '', $time = 3) {

	$message = $message;
	$title = $message;
	$urlForward = $urlForward;
	$time = $time * 1000;

	if($urlForward) {
		$message .= "<br><br><a href=\"$urlForward\">Check Here!</a>";
	}

	if($time) {
		$message .= "<script>".
			"function redirect() {window.location.href='$urlForward';}\n".
			"setTimeout('redirect();', $time);\n".
			"</script>";
	}

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'.
		'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh">'.
		'<head profile="http://www.w3.org/2000/08/w3c-synd/#">'.
		'<meta http-equiv="content-language" content="zh-cn" />'.
		'<meta http-equiv="content-type" content="text/html;charset=UTF-8" />'.
		'<title>'.$title.'</title>'.
		'<style type="text/css">'.
		'body { text-align:center; }'.
		'.notice{ padding: .5em .8em; margin:150px auto; border: 1px solid #ddd; font-family:verdana,Helvetica,sans-serif; font-weight:bold;}'.
		'.notice{ width:500px; background: #E6EFC2; color: #264409; border-color: #C6D880; }'.
		'.notice a{ color: #8e20b2; text-decoration: underline}'.
		'.notice a:hover{text-decoration: none}'.
		'.notice p{text-align:center;}'.
		'</style>'.
		'</head>'.
		'<body>'.
		'<div class="notice">'.
		'	<p>'.$message.'</p>'.
		'</div>'.
		'</body>'.
		'</html>';
	exit;
}
	function MooMessag($message, $urlForward = '') {
	
	$message = $message;
	$title = $message;
	$urlForward = $urlForward;
	

	if($urlForward) {
		$message .= "<br><br><a href=\"$urlForward\"></a>";
	}
	echo "<script>
			function aa(){
				
				location.href = '$urlForward&is=1';
			}
			function bb(){
				location.href = '$urlForward';
			}
			
		</script>";
	

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'.
		'<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="zh" lang="zh">'.
		'<head profile="http://www.w3.org/2000/08/w3c-synd/#">'.
		'<meta http-equiv="content-language" content="zh-cn" />'.
		'<meta http-equiv="content-type" content="text/html;charset=UTF-8" />'.
		'<title>'.$title.'</title>'.
		'<style type="text/css">'.
		'body { text-align:center; }'.
		'.notice{ padding: .5em .8em; margin:150px auto; border: 1px solid #ddd; font-family:verdana,Helvetica,sans-serif; font-weight:bold;}'.
		'.notice{ width:500px; background: #E6EFC2; color: #264409; border-color: #C6D880; }'.
		'.notice a{ color: #8e20b2; text-decoration: underline}'.
		'.notice a:hover{text-decoration: none}'.
		'.notice p{text-align:center;}'.
		'</style>'.
		'</head>'.
		'<body>'.
		'<div class="notice">'.
		'	<p >'.$message.'</p>'.
		'<span style = "width:80px;height:15px;cursor:pointer;" onclick = "aa()">是</span>'.
		'<span style = "width:80px;height:15px;margin-left:50px;cursor:pointer;" onclick = "bb()">否</span>'.
		'</div>'.
		'</body>'.
		'</html>';
	exit;
}

/*
 * 此函数已经无用，可删除
 */
	/*function member_add($add){
		GLOBAL $db;
		GLOBAL $smarty;
		//GLOBAL $mem;
		//var_dump($add);
		$sql = "select nick_name from  vote_member where nick_name = '$add[nickname]'";
		//$one = $db->getOne($sql);
		
		if($one){
			$year = year();
			$mouth = mouth();
			$date = date1();
			
			$text = '昵称重复';
			$sex = $add['sex'];
			$region = $add['region'];
			$password = $add['passwd'];
			$region_city = $add['region_city'];
			$birthdatey = $add['birthdatey'];
			$birthdatem = $add['birthdatem'];
			$birthdated = $add['birthdated'];
			$cellphone = $add['cellphone'];
			$phone = $add['phone'];
			$marriage = $add['marriage'];
			$education = $add['education'];
			$email = $add['email'];
			
			
			$time = date_handling();
			$sql = "select * from vote_view";
			$view = $db->getOne($sql);
			if(is_numeric($view['number']) && is_numeric($view['group'])){
				$sql = "select t1.*,t2.* from vote as t1 left join vote_content as t2 on t1.vid = t2.vid where t1.is_show = 1 and t2.content != '0' and t1.is_show =1 and t1.vote_starttime<=$time and t1.vote_endtime>=$time and t1.`group` = '$view[group]' order by t1.vid desc   limit 0,$view[number]";
			}else{
				$sql = "select t1.*,t2.* from vote as t1 left join vote_content as t2 on t1.vid = t2.vid where t1.is_show = 1 and t2.content != '0' and t1.is_show =1 and t1.vote_starttime<=$time and t1.vote_endtime>=$time order by t1.vid desc   limit 0,4";
			}
			$vote = $db->getAll($sql);
			
			$vote = data_handling($vote);
			
			if($_REQUEST['user_id']){
				$uid = $_REQUEST['user_id'];
				$sql = "select nick_name from vote_member where uid = '$uid'";
				$user_name = $db->getOne($sql);
				$nickname = $user_name['nick_name'];
			}else{
				$uid = '0';
			}
			$vote = content($vote);
			foreach($vote as $key => $value){
				$vote_up[$key+1] = $vote[$key];
			}
	        exit;
			
		}else{
			if($add['birthdatem'] == '-1'){
				$birthdatem = '00';
			}else if($add['birthdatem'] < '10'){
				$birthdatem = '0'.$add['birthdatem'];
			}else{
				$birthdatem = $add['birthdatem'];
			}
			if($add['birthdated'] == '-1'){
				$birthdated = '00';
			}else if($add['birthdated'] < '10'){
				$birthdated = '0'.$add['birthdated'];
			}else{
				$birthdated = $add['birthdated'];
			}
			if($add['birthdatey'] == '-1'){
				$birthdatey = '1900';
			}else{
				$birthdatey = $add['birthdatey'];
			}
			$birthdate = $birthdatey.$birthdatem.$birthdated;
			$passwd = md5($add[passwd]);
			$sql = "insert into vote_member (nick_name,password,sex,region,region_city,birthdate,cellphone,phone,marriage,education,email) values ('$add[nickname]','$passwd','$add[sex]','$add[region]',$add[region_city],'$birthdate','$add[cellphone]','$add[phone]','$add[marriage]','$add[education]','$add[email]')";
			$db->query($sql);
			$sql = "select uid from vote_member where nick_name = '$add[nickname]'";
			
			$uid = $db->getOne($sql);
			$user_id = $uid['uid'];
			MooSetCookie('user_id',$user_id,time()+3600*24);
			MooMessageAdmin('注册成功，欢迎参与投票','index.php?n=service&h=qixi&user_id='.$user_id,1);
		}

	}	
*/
	function year(){
		
		$array = array('2011','2010','2009','2008','2007','2006','2005','2004','2003','2002','2001','2000','1999','1998','1997','1996','1995','1994','1993','1992','1991','1990','1989','1988','1987','1986','1985','1984','1983','1982','1981','1980','1979','1978','1977','1976','1975','1974','1973','1972','1971','1970','1969','1968','1967','1966','1965','1964','1963','1962','1961','1960','1959','1958','1957','1956','1955','1954','1953','1952','1951','1950','1949','1948','1947','1946','1945','1944','1943','1942','1941','1940');
		return $array;
	}
	function mouth(){
		$array = array('01','02','03','04','05','06','07','08','09','10','11','12');
		return $array;
	}
	function date1(){
		$array = array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');
		return $array;
	}

	function sub_insert($vote_sub,$id = '0'){
		GLOBAL $db;
		
		unset($vote_sub['action']);
		unset($vote_sub['check']);
		if($id != '0' || empty($id)){
			$uid = $id;
		}
		if($uid == '0' || empty($uid)){
			$uid = $vote_sub['uid'];
		}
		
		unset($vote_sub['uid']);
		unset($vote_sub['htotal_guid_1']);
		$ip = $_SERVER["REMOTE_ADDR"]; 
		$sub_time  = DATE('Y-m-d H:i:s');
		
		
		foreach($vote_sub as $k => $v){
			$sql = "select content from  vote_content where  vid = '$k'";
			$content = $db->getOne($sql);
			
			$str = cont($content,$v);
			if(!empty($str)){
			$sql = "insert into vote_sub (uid,ip,sub_time,vid,vote_result) values ('$uid','$ip','$sub_time','$k','$str')";
			
			$db->query($sql);
			}
			$value = "";
			$optionk = "";
			$optionv = "";
		}

		result_insert($vote_sub);
	
		foreach($vote_sub as $k => $v){
			$str1.=$k.'.';
			if(is_array($v)){
				foreach($v as $kk => $vv){
					$str1.=$vv.' ';
				}
			}
			$str1 .=',';
		}
		//echo $str1 ;
		$str = UrlEncode($str1);
		
		header("Location: index.php?n=service&h=qixi&action=result&uid='.$uid.'&sub='.$str"); 
		
		//results_show($vote_sub);
	}
	
	function result_insert($vote_sub){
		GLOBAL $db;
		
		foreach($vote_sub as $key => $value){
			$count = count($value);
			
			$sql = "select num_people  from vote_result where vid = '$key'";			
			$result_last = $db->getAll($sql);
			
			$people = $result_last[0]['num_people']+'1';
			$update .= "num_people = '$people'";	
			
			$sql = "update vote_result set $update where vid = '$key'";
			$db->query($sql);
			$select = "";
			$update = "";
		}
		
	}
	

	
	function results_show($vote_sub){
		GLOBAL $db;
		GLOBAL $smarty;
		
		$sql = "select distinct(ip) from vote_sub";
		$ip = $db->getAll($sql);
		$count = count($ip);
		foreach($vote_sub as $key => $value){
			$sql = "select t1.vote_title,t1.vid,t3.vote_result,t2.num_people  from vote as t1 left join vote_result as t2 on t1.vid=t2.vid left join vote_sub as t3 on t2.vid = t3.vid  where t1.vid = '$key'"	;
			$array[] = $db->getAll($sql);
		}
		
		$arr = "";
		
		foreach($array as $key5 => $value5){
			foreach($value5 as $k5 => $v5){
				$cont1 = "";
				$conn = "";
				$cont = "";
				$result_2 = "";
				
				$arr['vote_title'][$v5[vid]] = $v5['vote_title'];
				$arr['num_people'][$v5[vid]] = $v5['num_people'];
				
				
				$conn[] = explode(';',$v5["vote_result"]);
				//var_dump($conn);
				array_pop($conn[0]);
				
				$conn1 = $conn[0];
				
				foreach($conn1 as $k => $v){
					$cont[] = explode(':',$v);
				}
				
				if(is_array($cont)){
					foreach($cont as $k => $v){
						$cont1[$k][] = $v[0];
						if($v[1] == '0'  || empty($v[1])){
							unset($cont[$v[0]]);
						}
					}
					
					foreach($cont1 as $k => $v){
						$array1[$v5[vid]][] = $v[0];

					}
				}	
			}
			
			$arr['content'][$v5['vid']] = $result_2;
		}
		
		foreach($array1 as $k => $v){
			$result_2 = array_count_values($v);
			
			$arr['content'][$k] = $result_2;
		}
		//var_dump($arr);
		foreach($arr as $k => $v){
			foreach($v as $key => $value){
				if($value == '0'){
					unset($arr[$k]);
				}
			}
			if($v[1] == '0'){
				unset($arr[$k][1]);
			}
			if($v[2] == '0'){
				unset($arr[$k][2]);
			}
			if($v[3] == '0'){
				unset($arr[$k][3]);
			}
			if(empty($arr[$k])){
				unset($arr[$k]);
			}
		}
		
		
		foreach($arr as $k => $v){
			foreach($v as $key => $value){
				$list[$key][$k] =  $value;
			}
		}
		foreach($vote_sub as $k => $v){
			
			if($v['1'] == 0 || empty($v['1'])){
				unset($vote_sub[$k]);
			}
		}
		$num = count($vote_sub);
		foreach($vote_sub as $k1 => $v1){
			$list_1 = $list[$k1];
			$list_one['vote_title'][] = $list_1['vote_title'];
			$list_one['num_people'][] = $list_1['num_people'];
			unset($list_1['num_people']);
			unset($list_1['vote_title']);
			$list_one[] = $list_1;
		}
		
		for($i=1;$i<=$num;$i++){
			$j=$i-1;
			$list = "";
			$height = '';
			$title = $list_one[vote_title][$j];
			
				$num_people = $list_one[num_people];
				
				$list .= "<graph caption='$title, $num_people[$j]人已参与了调查'  decimalPrecision='0' formatNumberScale='0' chartRightMargin='30' baseFontSize='15' outCnvBaseFontSize='13'>";
				$color = array('F6BD0F','8BBA00','FF8E46','008E8E','D64646','8E468E','588526','B3AA00','008ED6','9D080D','A186BE');
	
				$key = 0;
				$height = 100+count($list_one[$j]['content'])*25;
				
				foreach($list_one[$j]['content'] as $k => $v){
					$key++;
					if($k == ""){
						$k = 'text';
					}
					
					$list .="<set name='$k' value='$v' color='$color[$key]'/>";
				}
				$list .="</graph>";
			
			$array_1[$j][] = $list;
			$array_1[$j][] = $height;
		}
		
		require MooTemplate('public/service_back','module');
	    exit;
	
	}
	
	function check_vote($arr,$uid = '0'){
		GLOBAL $db;
		if($uid != 0){
			$user_id= $uid['uid'];
		}else{
			$user_id = $arr[uid];
		}
		
		
				
			
		$where .= 'and'.' (';
		foreach($arr as $k => $v){
			if(is_numeric($k)){
				 $where .=  'vid ='."'$k'".'or'.' ';
			}
		}		
		$where = substr($where,0,-3);
		$where .=')';
		$sql = "select uid,vid from vote_sub where uid = '$user_id' $where";
		
		$last_vote = $db->getOne($sql);
		if($last_vote){
				MooMessageAdmin('请不要重复投票','index.php?n=service&h=qixi&user_id='.$user_id,1);
		}else{
			
		}
		$where = '';
	}
	function content($vote){
		
		foreach($vote as $k => $v){
			$conn = "";
			$cont = "";
			$content_ = explode(';',$v['content']);	
			array_pop($content_);
			$content_1[] = $content_;
			foreach($content_ as $key => $value){
				$conn[] = explode(':',$value);
			}
			foreach($conn as $k1 => $v1){
				$cont[$v1[0]] = $v1[1];
				if($v1[1] == '0'  || empty($v1[1])){
					unset($cont[$v1[0]]);
				}
			}
			
			
			$vote[$k]['content'] = $cont;
			
		}
		return $vote;
		
	}
	function cont($content,$v){
		
		$content_ = explode(';',$content['content']);	
		array_pop($content_);
		foreach($content_ as $k=> $v1){
			$conn[] = explode(':',$v1);
		}
		if(is_array($conn)){
		foreach($conn as $k1 => $v2){
				$cont[$v2[0]] = $v2[1];
				if($v2[1] == '0'  || empty($v2[1])){
					unset($cont[$v2[0]]);
				}
			}
		
		foreach($v as $k1 => $v1){
			if(is_numeric($v1) || strlen($v1)<2){
				$str .= $cont[$v1].':'.'1'.';';
			}else{
				$str .= $v1.':'.'1'.';';
			}
		}
		}
		
		return $str;
	}
	
	
	
?>