<?php
/*
 * Created on 9/25/2009
 *
 * yujun
 *
 * module/about/index.php
 */
/*************************************** 逻辑层(M)/表现层(V) ****************************************/

//情感诊所 -- 问题类型名称映射数据
$arr_clinic_type = array(
	1 => '相识问题',
	2 => '相恋问题',
	3 => '相见问题',
	4 => '其他问题',		
);


//关于我们
function about_us(){
	global $user_arr;
	$left_menu = 'us';
	include MooTemplate("public/about_us", 'module');
}

//500W赔偿基金
function about_compensation(){
    include MooTemplate('public/about_compensation', 'module');
}

//媒体关注
function about_media(){
	global $_MooClass,$dbTablePre,$user_arr;
	$left_menu = 'media';
	include MooTemplate("public/about_media", 'module');
}
//媒体关注——详细
function about_media_detail(){
	global $_MooClass,$dbTablePre,$user_arr,$name;
	include "./module/{$name}/function.php";
	//通过ID查找一条新闻的详细内容
	$id = $_GET['id'];
	$arr = explode("_",$id);
	$src = "module/about/templates/default/baodao/".$arr[0]."/" . $arr[1] . ".jpg";
	if( !file_exists($src) ){
    	MooMessage('没有这个页面', 'index.php?n=about&h=media','01');
	}
	
	$id = ( isset($arr[0]{3}) ? $arr[0] : '20'.$arr[0] ) * 100 + $arr[1];
	$comment = getComment( 1, $id );
	$left_menu = 'media';
	require MooTemplate('public/about_media_detail', 'module');
}
//媒体关注 -- 列表页  list $type为列表类型 $page为页数 $listnumber为一页条数
function about_media_list(){
	global $_MooClass,$dbTablePre,$user_arr;
	$type =safeFilter(MooGetGPC('t','string'));
	in_array($type,array('tv','new','nice')) ? $type:'tv';
	$page=MooGetGPC('page','integer');
	switch($type){
	case "tv" :
		$typename="电视报道";
		break;
	case "new" :
		$typename="最近报道";
		break;
	case "nice" :
		$typename="精品报道";
		break;
	}
	//类型的总条数
	$media_count = $_MooClass['MooMySQL']->getAll("SELECT count(*) FROM {$dbTablePre}media WHERE `type`='$type'");
	$total = $media_count[0]['count(*)'];
	//一面显示的条数
	$pagesize = 15;
	//总页数
	$pagecount = ceil($total/$pagesize);
	//页码处理
	if($page <= 0 || !is_int($page)){
		$page = 1;
	}elseif($page >= $pagecount){
		$page = $pagecount;
	}
	$start = ($page-1)*15;
	//所属类型列表
	$media_list = $_MooClass['MooMySQL']->getAll("SELECT `id`,`title`,`addtime` FROM {$dbTablePre}media WHERE `type`='$type' ORDER BY `addtime` DESC LIMIT $start,$pagesize");
	$left_menu = 'media';
	require MooTemplate('public/about_media_list', 'module');
}

//联系我们
function about_contact(){
	global $user_arr;
	$left_menu = 'contact';
	include MooTemplate("public/about_contact", 'module');
}

//合作伙伴
function about_links(){
	global $user_arr;
	$left_menu = 'links';
	include MooTemplate("public/about_links", 'module');
}

//版权政策
function about_copyright(){
	global $user_arr;
	$left_menu = 'copyright';
	include MooTemplate("public/about_copyright", 'module');
}

//投诉建议
function about_getsave() {
	global $_MooClass,$dbTablePre,$userid,$timestamp,$user_arr;
	$sid = $user_arr['sid'];
	$uid = $user_arr['uid'];
	if(empty($uid)){ header("location:login.html");}
	$returnurl =  'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$ispost = MooGetGPC('ispost','integer','P');
	if($ispost==1){
		$type1 = MooGetGPC('type1','integer','P');
		$type2 = MooGetGPC('type2','integer','P');
		$complaint_type = MooGetGPC('complaint_type','integer','P');
		// 提交的内容不能超过600长度的字符
		//$message1 = MooCutstr(MooGetGPC('message1','string'),600);
		//$message2 = MooCutstr(MooGetGPC('message2','string'),600);
		$message1 =safeFilter(MooGetGPC('message1','string','P'));
		$message2 =safeFilter(MooGetGPC('message2','string','P'));
		$message1 = MooHtmlspecialchars($message1);
		$message2 = MooHtmlspecialchars($message2);
		
		if($complaint_type == 1) {
			if($type1 == 0) {
				//提示消息
				MooMessage("请选择对网站功能的评价反馈意见类型！", $returnurl,'02');
				exit;
			}
			if($message1==''){
				//提示消息
				MooMessage("请提供宝贵的对网站功能评价反馈的意见！", $returnurl,'02');
				exit;
			}
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}service_getadvice SET uid='$userid',stat1='$type1',stat2='1',content='$message1',submitdate='$timestamp'");
		} else {
			if(!$sid) {
				MooMessage("您还没有专属真爱一生，不能进行此操作！", $returnurl,'02');
				exit;
			}
			$fraction = MooGetGPC('fraction','string','P');
			if($type2 == 0) {
				//提示消息
				MooMessage("请选择反馈对真爱一生人工服务意见类型！", $returnurl,'02');
				exit;
			}
			if($fraction < 1) {
				//提示消息
				MooMessage("请给您的专属真爱一生打分！", $returnurl,'02');
				exit;
			}
			if($message2==''){
				//提示消息
				MooMessage("请提供宝贵的对真爱一生人工服务的评价！", $returnurl,'02');
				exit;
			}
			$fraction_arr = array(
				1 => '非常不满意',
				2 => '不满意',
				3 => '一般',
				4 => '满意',
				5 => '非常满意'
			);
			$_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}service_getadvice SET uid='$userid',stat1='$type2',stat2='2',content='$message2',submitdate='$timestamp', fraction='$fraction', sid='$sid'");
			$awoketime = time()+3600;
			$reptime = time();
			if($fraction < 3) {
				//查此客服的组长
				$sql = "SELECT manage_list FROM web_admin_manage WHERE type=1 AND find_in_set($sid,manage_list) LIMIT 1";
				$manage_list = $_MooClass['MooMySQL']->getOne($sql);
				if(!empty($manage_list)){
					$group_leader = implode(',',$GLOBALS['admin_leader']);
					$sql = "SELECT uid,groupid FROM web_admin_user WHERE uid IN({$manage_list['manage_list']}) AND groupid IN({$group_leader})";
					$admin_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
				}
				$leaderid = $admin_user['uid'];
				$title = '会员 '.$uid.' 对客服 '.$sid.' 的服务评价：'.$fraction_arr[$fraction];
				
				$sql_remark = "insert into web_admin_remark set sid='{$leaderid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$reptime}'";
				$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql_remark);
			}
			$title = '会员 '.$uid.' 对您的服务评价：'.$fraction_arr[$fraction];
			$sql_remark = "insert into web_admin_remark set sid='{$sid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$reptime}'";
			$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql_remark);
		}
		
		if($type2 == 1 || $type1 == 1) {
			$msg = "感谢您对真爱一生网一如既往的支持与厚爱,愿您早日找到属于自己的缘分！服务热线400-8787-920，祝您生活愉快！";
		} elseif ($type2 == 2 || $type1 == 2) {
			$msg = "感谢您对真爱一生网的关注，给您带来的不便，请您谅解！您的情况相关部门会进行严肃核查并给您合理答复。真爱一生网一直致力于改善和提高服务质量，同时也希望得到您的监督。服务热线400-8787-920，祝您生活愉快！";
		} elseif ($type2 == 3 || $type1 == 3) {
			$msg = "感谢您提出的宝贵意见，您的意见将会及时的反馈到相关部门，感谢您的大力支持，服务热线400-8787-920，祝您生活愉快!";
		}

		//邮件回复提醒
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}services(s_cid,s_uid,s_fromid,s_title,s_content,s_time,is_server,sid,flag)
				VALUES(3,{$uid},'{$sid}','真爱一生消息','{$msg}','{$GLOBALS['timestamp']}','1','{$sid}','1')";
		$ret = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
		//短信回复提醒
		$msg = "您的意见已收到，我们会第一时间处理。感谢您的支持。祝您生活愉快！客服热线：４００６７８０４０５";
		Push_message_intab($user_arr['uid'],$user_arr['telphone'],"邮件回复",$msg,"system");
		
		/*if($type1!='0' && $type2=='0'){
			if($message1==''){
				//提示消息
				MooMessage("请提供宝贵的对网站功能评价反馈的意见！", $returnurl,'02');
				exit;
			}	
		}
		if($type1=='0' && $type2!='0'){
			if($message2==''){
				//提示消息
				MooMessage("请提供宝贵的对真爱一生人工服务的评价！", $returnurl,'02');
				exit;
			}	
		}
		if($type1!='0' && $type2!='0'){
			if($message1==''){
				//提示消息
				MooMessage("请提供宝贵的对网站功能评价反馈的意见！", $returnurl,'02');
				exit;
			}	
			if($message2==''){
				//提示消息
				MooMessage("请提供宝贵的对真爱一生人工服务的评价！", $returnurl,'02');
				exit;
			}	
		}
		if($type1=='0' && $type2=='0'){
			//提示消息
			MooMessage("请选择反馈意见类型！", $returnurl,'02');
			exit;
		}
		if($message1!='' && $type1=='0'){
			//提示消息
			MooMessage("请选择对网站功能的评价反馈意见类型！", $returnurl,'02');
			exit;
		}
		if($message2!='' && $type2=='0'){
			//提示消息
			MooMessage("请选择对真爱一生人工服务的评价类型！", $returnurl,'02');
			exit;
		}
*/

		//note 	如果提交成功显示页面
		if($message1 || $message2) {
			//提示消息
			MooMessage("您的反馈成功，感谢您的反馈！", "index.php?n=service",'05');
		}
		exit;
	}
	$left_menu = 'getsave';
	require MooTemplate('public/about_getsave', 'module');
}

//情感诊所--  首页
function about_clinic(){
	global $_MooClass, $dbTablePre;
	$title_n = 6;// 设置每种类型的问题显示多少个标题
	
	$sql = "SELECT id,title,type,sort FROM {$dbTablePre}love_clinic WHERE sid > '0' and sort <= '$title_n' ORDER BY sort";
	$param = ("type=query/name=about_clinic/sql=$sql/cachetime=86400");
	$_MooClass['MooCache']->getBlock($param);
	$temp = $GLOBALS['_MooBlock']['about_clinic'];
	
	$love_clinic_list = array();
	foreach ( $temp as $v ){
		if( !isset($love_clinic_list[$v['type']]) ) $love_clinic_list[$v['type']] = array();
		$love_clinic_list[$v['type']][] = $v;
	}
//	echo $GLOBALS['arr_clinic_type'][1];die;
//	echo "<pre>";
//	print_r($love_clinic_list);
//	echo "</pre>";die;

	$left_menu = 'clinic';
	require MooTemplate( 'public/about_clinic', 'module' );
}
//情感诊所 -- 列表页
function about_clinic_list(){
	include "./module/{$GLOBALS['name']}/function.php";
	$type_id  = MooGetGPC('type', 'integer', 'G');
	if( !in_array( $type_id, array(1,2,3,4) ) ){
		header("location:index.php?n=about&h=clinic");
		exit();
	} 
	$page_now = MooGetGPC('page', 'integer', 'G');
	$page_per = 15;
	$page_url = 'index.php?n=about&amp;h=clinic_list&amp;type='.$type_id;
	
	$title_n = 6;// 设置每种类型的问题显示多少个标题
	$from_where = "FROM {prefix}love_clinic WHERE type = '$type_id' and sid>'0' ";
	$data = readTable( $page_now, $page_per, $page_url, $from_where, 'ORDER BY sort', 'id,title,sort' );
	$type_name = $GLOBALS['arr_clinic_type'][$type_id];
	$left_menu = 'clinic';
	require MooTemplate( 'public/about_clinic_list', 'module' );
}
//情感诊所 -- 内容页
function about_clinic_content(){
	global $_MooClass, $dbTablePre, $arr_clinic_type, $userid;
	$id = MooGetGPC('id', 'integer');
	
	$sql = "SELECT * FROM {$dbTablePre}love_clinic WHERE id='$id'";
	$content = $_MooClass['MooMySQL']->getOne($sql); 
	$content['answer'] 	 = nl2br( str_replace( ' ', '&nbsp;', htmlspecialchars($content['answer']) ) );
	$content['question'] = nl2br( str_replace( ' ', '&nbsp;', htmlspecialchars($content['question']) ) );
	$type_name = $GLOBALS['arr_clinic_type'][$content['type']];

	
	$sql = "SELECT id,title FROM {$dbTablePre}love_clinic WHERE type='{$content['type']}' AND id!='$id' limit 10";
	$temp = $_MooClass['MooMySQL']->getAll($sql);
	$temp_n = count($temp);
	$rand_keys = array_rand( $temp, $temp_n>6?6:$temp_n );
	$list = array();
	foreach( $rand_keys as $k ){
		$list[] = $temp[$k]; 
	}
	unset($temp);
	$left_menu = 'clinic';
	require MooTemplate( 'public/about_clinic_content', 'module' );
}

//荣誉
function media_glory(){
	global $_MooClass,$dbTablePre,$user_arr;
	$left_menu = 'glory';
	require MooTemplate('public/about_media_glory', 'module');
}

//荣誉详细
function media_glorycontent(){
//	$id = MooGetGPC('id','string','G');
//	if($id == 0){
//		$id = 1;
//	}
	$page = MooGetGPC('page','integer','G');
	if($page == 0) $page = 1;
	$num = 9;
	$perpage = 1;
	$mpurl = "index.php?n=about&amp;h=glorycontent";
	$pagebar = multipage($num, $perpage, $page, $mpurl);
	require MooTemplate('public/about_media_glorycontent', 'module');
}

//note 添加评论
function about_media_addcomment(){
	global $name;
	if( !empty($_POST['content']) ){
		include "./module/{$name}/function.php";
		$id = $_GET['id'];
		addComment( 1, $id);
	}
	header("location:".$_SERVER['HTTP_REFERER']);
}
//note 提交感情问题
function about_clinic_add(){
	global $timestamp,$user_arr,$arr_clinic_type;
	//print_r($user_arr);exit;
	if($_POST){
		$date = array();
		$date['type'] = MooGetGPC('clinictype','integer','P');
		$date['uid'] = $user_arr['uid'];
		$date['title'] = safeFilter(MooGetGPC('title','string','P'));
		$date['question'] = safeFilter(MooGetGPC('clinic','string','P'));
		$date['add_time'] = $timestamp;
		$sql = "SELECT sort FROM {$GLOBALS['dbTablePre']}love_clinic WHERE type = {$date['type']} ORDER BY sort DESC LIMIT 0,1";
		$ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$date['sort'] = ++$ret['sort'];
		inserttable('love_clinic',$date);

		MooMessage("您的情感问题已经提交成功，真爱一生情感专家会在24小时内解决您的问题。\n感谢您对真爱一生网的信任与支持。","index.php?n=about&h=clinic",'03');
	}
}
//招聘
function recruit(){
    $left_menu = 'recruit';
    require MooTemplate('public/about_recruit', 'module');
}

//隐私保护
function about_privacy(){
    $left_menu = 'privacy';
    require MooTemplate('public/about_privacy', 'module');
}

/***************************************   控制层(C)   ****************************************/
$h = MooGetGPC('h', 'string');

if(!$userid && in_array($h,array('getsave','clinic_add'))) {header("location:login.html");}
switch($h){
	case'us':			//关于我们
		about_us();
		break;
	case 'compensate':
	    about_compensation();
		break;
	case'media': 		//媒体关注
		about_media();
		break;
	case 'media_list':	//媒体关注--列表页
		about_media_list();
		break;
	case 'media_detail'://媒体关注--内容页
		about_media_detail();
		break;
	case 'addcomment':	//媒体关注--评论
		about_media_addcomment();
		break;
	case 'glory':		//荣誉
		media_glory();
		break;
	case 'glorycontent':		//荣誉
		media_glorycontent();
		break;
	case 'contact':		//联系我们
		about_contact();
		break;
	case 'links': 		//合作伙伴
		about_links();
		break;
	case 'getsave'://note 意见反馈页面
		require_once (dirname(__FILE__)."./../../backend/include/config.php");
		about_getsave();
		break;
	case 'clinic':
		about_clinic();
		break;
	case 'clinic_list':
		about_clinic_list();
		break;
	case 'clinic_content':
		about_clinic_content();
		break;
	case 'clinic_add':
		about_clinic_add();
		break;
	case 'copyright':
		about_copyright();
	    break;
	case 'recruit':
	    recruit();
		break;
	case 'privacy':
	    about_privacy();
		break;
	case 'getsid':
		echo $user_arr['sid'];
		die;
	case "exec":
	    require("about_exec.php");
		break;
	default :
		about_us();
}
?>