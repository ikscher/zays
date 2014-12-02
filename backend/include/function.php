<?php
/*
 * 此文件中方法为后台共用的方法
 */

/**
 * 获取后台管理员信息
 * @return 返回全局变量$GLOBALS['username'], $GLOBALS['adminid']
 * @param $type string 登录后台类型：客服，编辑
 */
function adminUserInfo() {
	global $_MooCookie;
	 
	if(MOOPHP_ALLOW_MYSQL && $_MooCookie) {
		if(isset($_MooCookie['admin'])){
		list($adminid,$password) = explode("\t", MooAuthCode($_MooCookie['admin'], 'DECODE'));

		}
	    if(isset($adminid)){
		$adminid = intval($adminid);
		$GLOBALS['adminid'] = $adminid;
		if($adminid && empty($GLOBALS['username'])){
			$adminuser = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT au.* ,ag.type,ag.groupname,ag.nav as nav FROM {$GLOBALS['dbTablePre']}admin_user au
							 LEFT JOIN {$GLOBALS['dbTablePre']}admin_group ag 
							 ON au.groupid=ag.groupid
							 WHERE au.uid='{$adminid}' AND au.password='{$password}'");
			
			if($adminuser['uid']) {
			    $GLOBALS['usercode'] = addslashes($adminuser['usercode']);
				$GLOBALS['username'] = addslashes($adminuser['username']);
				$GLOBALS['adminid'] = $adminid;
				$GLOBALS['groupid'] = $adminuser['groupid'];
				$GLOBALS['groupname'] = $adminuser['groupname'];
				//$GLOBALS['nav'] = $adminuser['nav'];
				$GLOBALS['ccid'] = $adminuser['ccid'];
				
				$time = time();
				//每五分钟更新客服最后活动时间
				if ($adminuser['lastaction'] + 300 <= $time) {
					$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_user SET lastaction = '{$time}' WHERE uid = '{$adminid}' LIMIT 1";
					$GLOBALS['_MooClass']['MooMySQL']->query($sql);
				}
			}else{
			    $GLOBALS['usercode'] = 0;
				$GLOBALS['adminid'] = 0;
			}
			
			check_adminuser_session($adminuser['uid'],$adminuser['groupid']);
		}
	    }
	}else {
	    $GLOBALS['adminid'] = 0;
		$GLOBALS['usercode'] = 0;
	}
}

////检查session内存表中是否有记录,没有插入新记录
function check_adminuser_session($uid,$groupid){
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_usersession WHERE uid='{$uid}'";
	$result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if(empty($result)){
		$sid_list = get_mymanage_serviceid_list($uid,$groupid);
		$time = time();
		$sql = "REPLACE INTO {$GLOBALS['dbTablePre']}admin_usersession SET uid='{$uid}',groupid='{$groupid}',dateline='{$time}',sid_list='{$sid_list}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}
}

/* 后台加载模板
 * @param string $file - 模板文件名
 * @return string 返回编译后模板的系统绝对路径
 */
function adminTemplate($file) {

	$tplfile = './templates/' . $file .'.htm';

	$objfile = './data/templates/default/'.$file.'.tpl.php';

	if(!is_file($objfile) || @filemtime($tplfile) > @filemtime($objfile)) {
		//note 加载模板类文件
		$T = MooAutoLoad('MooTemplate');
		$T->complie($tplfile, $objfile);
	}
	//echo $tplfile;
	return $objfile;
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


/**
* 客服操作统计
* @param integer $type - 操作类型
* @param string $table - 发生操作的表
* @param string $handle - 操作内容
* @param integer $sid - 客服的id
* @return integer 是否插入数据成功 1成功 0失败
*/
function serverlog($type,$table,$handle,$sid,$uid=0) {
	global $_MooClass,$dbTablePre;
	$t = time();

	if(!empty($GLOBALS['front_adminid'])){
		$sid = $GLOBALS['front_adminid'];
	}
	
	$iscj = 0;

	if($_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}server_log (type,tablename,handle,sid,time,uid,iscj) values('{$type}','{$table}','{$handle}','{$sid}','{$t}','{$uid}','{$iscj}')")){
		return true;
	}else{
		return false;
	}
}

//向admin_log插入记录，目前此表只记录客服修改的会员密码日志
function insert_admin_log($uid,$message){
	$sql = "INSERT INTO {$GLOBALS['dbTablePre']}admin_log SET sid='{$GLOBALS['adminid']}',uid='{$uid}',edit_password=1 ,message='$message',dateline='{$GLOBALS['timestamp']}'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
}
/*
 * 返回管理员能显示的导航
 * return 返回能显示的导航
 */
function returnNav(){
	$groupid = $GLOBALS['groupid'];
	$sql = "SELECT nav FROM {$GLOBALS['dbTablePre']}admin_group WHERE groupid='{$groupid}'";
	$group = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$nav = explode(",", $group['nav']);
	return $nav;
}

/*
 * 返回管理员能进行的操作
 * return 返回管理员能进行的操作数组
 */
function returnAction(){
	$groupid = $GLOBALS['groupid'];
	$sql = "SELECT action FROM {$GLOBALS['dbTablePre']}admin_group WHERE groupid='{$groupid}'";
	$group = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$action = explode(",", $group['action']);
	return $action;
}

/*
 * 判断管理员权限
 * $action 执行的动作
 * $h 操作
 * return 如果有权限返回能显示的导航，没有则返回false
 */
function checkGroup($action,$h){
	$groupid = $GLOBALS['groupid'];
	$action_h = $action . "_" . $h;
	$sql = "SELECT action,nav FROM {$GLOBALS['dbTablePre']}admin_group WHERE groupid='{$groupid}'";
	$group = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$action = explode(",", $group['action']);

	if(in_array($action_h,$action)){
		return true;
	}
	return false;
}

/*
* 退出清除Cookie
*/
function MooClearAdminCookie() {
	MooSetCookie('admin', '', -86400 * 365);
	//删除 session临时表中记录
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}admin_usersession WHERE uid='{$GLOBALS['adminid']}'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	$GLOBALS['adminid'] = 0;
	$GLOBALS['groupid'] = 0;
	$GLOBALS['nav'] = '';
}
/**
* 向会员发送站内信
* @param array $arr - 操作类型
* @return string 
*/
function sendusermessage($uid,$content,$s_title){
        global $_MooClass,$dbTablePre;
        $addtime=time();
        $_MooClass['MooMySQL']->query("INSERT INTO {$dbTablePre}services SET `s_uid`='$uid',`s_cid`='3',`s_title`='$s_title',`s_content`='$content',`s_time`='$addtime'");
}

/**
* js提示
* @param string $msg
* @param string $url
*/
function salert($msg,$url=''){
	if(empty($url)){
		echo "<script>alert('$msg');history.back();</script>";exit;
	}else{
		echo "<script>alert('$msg');window.location.href='{$url}&rand=".rand()."'</script>";exit;
	}
}

/**
* 删除指定表,指定记录
* @param string $table_name - 表名
* @param string $field - 字段名
* @param string $id - 条件id
* @return true or false
*/
function del_record($table_name,$field,$id){
	if(!empty($table_name) && !empty($id) && !empty($field)){
		$sql = "DELETE FROM {$GLOBALS['dbTablePre']}{$table_name} WHERE {$field}='{$id}'";
		if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
			return true;
		}
		return false;
	}
}


//指定条件获取总条数
function getcount($tablename, $where, $get='COUNT(*)') {
	$result = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT $get FROM ".$GLOBALS['dbTablePre'].$tablename." $where LIMIT 1");
	return $result[$get];
}

//手机号码查询归属地  张行军 2009-10-24 16:03:31
/**
*@param 通过手机号码获取手机号码查询归属地
*@param integer $telphone - 电话号码或前七位
*@param return string
*/
function getphone($phone){
	$dbpath="xiaolin/";
	$len=strlen($phone);
	if ( $len < 7 ){
		return "手机号码最低7位哦";
	}
	//$par="[0-9]";
	$par = "/^[0-9]{1}$/";
	for ($i=0;$i<$len;$i++){
		if(!preg_match($par,substr($phone,$i,1) ) ){
			return "手机号码只能为数字";
		}
	}
	$sunum=scandir($dbpath); //得到支持的手机号码前缀
	array_splice($sunum,0,1); //把当前目录取消
	array_splice($sunum,0,1);	//把上一级目录去掉
	$sub=substr($phone,0,3);	//取得该号码的前三位
	if (in_array($sub,$sunum) ){
		 $num=ltrim(substr($phone,3,4),"0");
		$search=file($dbpath.$sub);
		$tmp=$search[$num];
		$result=substr($tmp,strpos($tmp,"=")+1,strlen($tmp)-strpos($tmp,"=")-2); //处理数据
		if(!is_utf8($result)){
			$result = iconv("GBK","UTF-8",$result);
		}
		return (strlen($result)> 1)?$result:"无数据";
	}else{
		return "暂不支持$sub";
	}
}

//是否是utf8编码
function is_utf8($word)
{
	if(preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word))
		return true;
	else return false;
}

/**
 * 得到所管理的客服id列表
 * @return string 
 * @author fanglin
 */
function get_myservice_idlist(){
	$result = '';
//	if(!empty($GLOBALS['_MooCookie']['change_identity'])){
//		get_change_identity();
//	}
	$sql = "SELECT sid_list FROM {$GLOBALS['dbTablePre']}admin_usersession WHERE uid='{$GLOBALS['adminid']}'";

	$result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,0,0,true);

	if(empty($result)){
		//此处应该为切换身份时才会调用切换身份时用
		$sid_list = get_mymanage_serviceid_list($GLOBALS['adminid'],$GLOBALS['groupid']);
	}else{
        $sid_list=$result['sid_list'];
    }

	if(!empty($sid_list) && $sid_list!='all'){
        $sid_list_a=array();
        $sid_list_a=explode(',',$sid_list);
		
        foreach($sid_list_a as $key=>$value){
            if(empty($value) && $value!=0){
                unset($sid_list_a[$key]);
            }
        }
		
        return implode(',',$sid_list_a);
    }else{
        return $sid_list;
    }
}

/**
 * 按指定参数以分页的模式的读取数据 
 * ts24 2009-2-21
 * */
function readTable( $page_now, $page_per, $page_url, $from_where,  $order_by = '', $fd_list = '*' ){
	$from_where = str_replace( '{prefix}', $GLOBALS['dbTablePre'], $from_where );
	//分页
	$tmp = $GLOBALS['_MooClass']['MooMySQL']->getOne( 'SELECT COUNT(*) N '.$from_where );
	$item_num = $tmp['N'];
	$page_num = ceil( $item_num / $page_per );
	if( $page_now > $page_num ) $page_now = $page_num;
	if( $page_now < 1 ) $page_now = 1;
	$page_links = multipage( $item_num, $page_per, $page_now, $page_url );
	
	//读数据
	$start = ( $page_now - 1 ) * $page_per;
	$sql = "SELECT $fd_list $from_where $order_by LIMIT $start, $page_per";
	$question_list = $GLOBALS['_MooClass']['MooMySQL']->getAll( $sql );
		
	return array(
		'count'		 => $item_num,
		'page_links' => $page_links,
		'list'		 => $question_list 
	);
}



/**
 * 此处主要是登录及切换身份时调用
 * 当前用户所管理客服ID列表,fanglin
 * @param $uid
 * @param $groupid
 * @return string,客服id列表
 * @author fanglin
 */
function get_mymanage_serviceid_list($uid,$groupid){

	$sql = "SELECT action,nav FROM {$GLOBALS['dbTablePre']}admin_group WHERE groupid='{$groupid}'";
	$group = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$action = explode(",", $group['action']);

	//客服主管或经理级别权限，能查看所有会员列表
	if(in_array($groupid,$GLOBALS['admin_service_arr']) || in_array($groupid,$GLOBALS['admin_vip_level_check']) || in_array($groupid,$GLOBALS['admin_complaints'])){
		$sid_list = 'all'; //表示客服主管
	}elseif(in_array($groupid,$GLOBALS['admin_service_after'])){
		$sql="SELECT manage_list from {$GLOBALS['dbTablePre']}admin_manage WHERE manage_type=2 and manage_list!=''";
		$manage_list_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		//print_r($manage_list_arr['manage_list']);
		foreach($manage_list_arr as $manage_list){
			$manage_list_sid[]=$manage_list['manage_list'];
		}
		$sid_list=implode(",",$manage_list_sid);
		
	
		
	}elseif(in_array($groupid,$GLOBALS['admin_userinfo_check'])){
		$sid_list="0,1,52,23,123";
	}else{
		
		//有队所属会员管理权限
		if(in_array($groupid,$GLOBALS['admin_service_team']) || in_array($groupid,$GLOBALS['admin_service_manager'])){			
			$sql = "SELECT manage_list FROM {$GLOBALS['dbTablePre']}admin_manage WHERE type=2 AND FIND_IN_SET({$uid},leader_uid) LIMIT 1";
			$admin_manage = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);				
			if($admin_manage['manage_list']){
				//查队所管理的组
					$sql = "SELECT group_concat(manage_list) as manage_list FROM {$GLOBALS['dbTablePre']}admin_manage WHERE id IN ({$admin_manage['manage_list']})";
			
					$manage_service_list = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);					
					if(!empty($manage_service_list['manage_list'])){
						
						$sid_list = $manage_service_list['manage_list'];			
					}
				}
			//有组所属会员管理权限
			    

		}elseif(in_array('init_group_member_manage',$action)){

			$sql = "SELECT id,manage_list FROM {$GLOBALS['dbTablePre']}admin_manage WHERE type=1 AND FIND_IN_SET({$uid},manage_list) LIMIT 1";
			$admin_manage = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			if($admin_manage){
				$sid_list = $admin_manage['manage_list'];	
			
			}
			
		}else{//只能查看自己的会员
			$sid_list = $uid;
		}
	}
	return $sid_list;
}


/**
 * 切换以某会员登录时，得到身份标识
 * @return void
 * @author fanglin
 */
function get_change_identity(){
	list($change_uid,$change_groupid) = explode("\t", MooAuthCode($GLOBALS['_MooCookie']['change_identity'], 'DECODE'));
	 
	
	if(empty($GLOBALS['front_adminid'])){
		$GLOBALS['front_adminid']= $GLOBALS['adminid'];	//切换身份前的adminid
		$GLOBALS['front_username']= $GLOBALS['username'];	//切换身份前的username
		$GLOBALS['front_groupname'] = $GLOBALS['groupname'];
	}
//	$sql = "SELECT groupname FROM {$GLOBALS['dbTablePre']}admin_group WHERE groupid='{$chang_user['groupid']}'";
//	$change_group = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//	
//	$GLOBALS['groupname'] = $change_group['groupname'];
//	$GLOBALS['adminid'] = $change_uid;
//	$GLOBALS['groupid'] = $change_groupid;
	return $change_uid;
}

/**
 * 检查切换身份是否合法
 * 合法则改变当前登录者$GLOBALS['uid'],$GLOBALS['groupid'],生成标识cookie
 * @return array
 * @author fanglin
 */
function check_change_identity(){
	$chang_user = array();
	if(!empty($_GET['change_uid'])){
		$sql = "SELECT sid_list FROM {$GLOBALS['dbTablePre']}admin_usersession sid_list WHERE uid='{$GLOBALS['adminid']}'";
		$manage_sid_list = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		
		//主要是判断该uid客服是否归当前用户所管
		if($manage_sid_list['sid_list']=='all'){ 	//all 客服主管能以所有客服身份登录
			$sql = "SELECT uid,groupid,username FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$_GET['change_uid']}'";
			$chang_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			if(empty($chang_user)){
				MooMessageAdmin('您要访问的用户不存在','index.php');
			}
			$sql = "SELECT groupname FROM {$GLOBALS['dbTablePre']}admin_group WHERE groupid='{$chang_user['groupid']}'";
			$change_group = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			
			$GLOBALS['groupid'] = $chang_user['groupid'];
			$GLOBALS['usercode'] = $chang_user['usercode'];
			$GLOBALS['username'] = $chang_user['username'];
			$GLOBALS['adminid'] = $_GET['change_uid'];
			$GLOBALS['groupname'] = $change_group['groupname'];

			MooSetCookie('change_identity',MooAuthCode("{$_GET['change_uid']}\t{$chang_user['groupid']}",'ENCODE'),86400);
		}else{ 			//只能查看所管理范围内的
			$sid_arr = explode(',',$manage_sid_list['sid_list']);
			if(in_array($_GET['change_uid'],$sid_arr)){
				$sql = "SELECT uid,groupid,username FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$_GET['change_uid']}'";
				$chang_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
				
				$sql = "SELECT groupname FROM {$GLOBALS['dbTablePre']}admin_group WHERE groupid='{$chang_user['groupid']}'";
				$change_group = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
				
				$GLOBALS['groupid'] = $chang_user['groupid'];
				$GLOBALS['usercode'] = $chang_user['usercode'];
				$GLOBALS['username'] = $chang_user['username'];
				$GLOBALS['adminid'] = $_GET['change_uid'];
				$GLOBALS['groupname'] = $change_group['groupname'];
				MooSetCookie('change_identity',MooAuthCode("{$_GET['change_uid']}\t{$chang_user['groupid']}",'ENCODE'),86400);
			}
		}
	}else{
		if(!empty($GLOBALS['_MooCookie']['change_identity'])){
			$change_uid = get_change_identity();
			$sql = "SELECT uid,groupid,username FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$change_uid}'";
			$chang_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			
			$sql = "SELECT groupname FROM {$GLOBALS['dbTablePre']}admin_group WHERE groupid='{$chang_user['groupid']}'";
			$change_group = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			
			$GLOBALS['groupid'] = $chang_user['groupid'];
			$GLOBALS['usercode'] = $chang_user['usercode'];
			$GLOBALS['username'] = $chang_user['username'];
			$GLOBALS['adminid'] = $change_uid;
			$GLOBALS['groupname'] = $change_group['groupname'];
			
			$change_user['groupname'] = $change_group['groupname'];
			return $chang_user;
			
		}
	}
	return $chang_user;
}


//得到组别--所有的分组
function get_group_type($id=''){
	$where = '';
	if(!empty($id)){
		$where = " AND id='{$id}'";
	}
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_manage WHERE type=1 $where";
	$result = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	return $result;
}


/*
* 图片裁减函数
* @param $image 原图片
* @param $path  裁减后图片路径
* @param $x     裁减左上角X坐标
* @param $y     裁减左上角Y坐标
* @param $width 裁减宽度
* @param $height 裁减高度
* @param $uid   用户id
* @param $sizearray 裁减尺寸数组
* @param $namearray 命名数组
*/
function changesize($image,$path,$x,$y,$width,$height,$uid,$sizearray,$namearray){
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);
        $userpath=$uid*3;
        if(!file_exists($path)) {mkdir($path, 0777);}
        
        for($i=0;$i<count($sizearray);$i++){
                  $new_Image=imagecreatetruecolor($sizearray[$i]['width'],$sizearray[$i]['height']);
              switch($imageType){
	              case "image/gif":
		             $source=imagecreatefromgif($image); 
		             $thumb_image_name=$path."/".$userpath."_".$namearray[$i].".gif";
		          break;
	              case "image/pjpeg":
	              case "image/jpeg":
	              case "image/jpg":
		             $source=imagecreatefromjpeg($image);
		             $thumb_image_name=$path."/".$userpath."_".$namearray[$i].".jpg";
		          break;
	              case "image/png":
	              case "image/x-png":
		             $source=imagecreatefrompng($image);
		             $thumb_image_name=$path."/".$userpath."_".$namearray[$i].".png";
		          break;
                  default: exit("不支持该图片格式：$imageType ，请禁止通过该图片的审核");
			  /* case "image/x-bmp":
			  case "image/x-ms-bmp":
		          case "image/bmp":
		             $source=ImageCreateFromBMP($image);
		             $thumb_image_name=$path."/".$userpath."_".$namearray[$i].".bmp"; */
              }
              imagecopyresampled($new_Image,$source,0,0,$x,$y,$sizearray[$i]['width'],$sizearray[$i]['height'],$width,$height);
              switch($imageType) {
	              case "image/gif":
					imagegif($new_Image,$thumb_image_name);
					break;
	              case "image/pjpeg":
	              case "image/jpeg":
	              case "image/jpg":
					imagejpeg($new_Image,$thumb_image_name,90);
		          break;
	              case "image/png":
	              case "image/x-png":
					imagepng($new_Image,$thumb_image_name);
					break;
		      case "image/x-bmp":
			  case "image/x-ms-bmp":
		     	  case "image/bmp":
					imagebmp($new_Image,$thumb_image_name);
					break;
              }
		        /*$first = new Imagick($thumb_image_name);//写入水印
		        $second = new Imagick('../public/system/images/logo_xxz.png');
		        $second->setImageOpacity (0.4);//设置透明度
		        $dw = new ImagickDraw();
		        $dw->setGravity(Imagick::GRAVITY_SOUTHEAST);//设置位置
		        $dw->composite($second->getImageCompose(),0,0,50,0,$second);
		        $first->drawImage($dw);
		        $first->writeImage($thumb_image_name);*/
	            chmod($thumb_image_name, 0777);
		}
        return true;
}

/**
* ImageCreateFromBMP() - 支持BMP图片函数
* $filename - BMP图形文件
* */
function ImageCreateFromBMP( $filename ) {
// Ouverture du fichier en mode binaire
if ( ! $f1 = @fopen ($filename, "rb")) return FALSE ;
// 1 : Chargement des ent?tes FICHIER
$FILE = unpack ( "vfile_type/Vfile_size/Vreserved/Vbitmap_offset" , fread($f1 ,14));
if ( $FILE ['file_type'] != 19778 ) return FALSE ;
// 2 : Chargement des ent?tes BMP
$BMP = unpack ( 'Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' .
'/Vcompression/Vsize_bitmap/Vhoriz_resolution' .
'/Vvert_resolution/Vcolors_used/Vcolors_important' , fread ( $f1 , 40 ));
$BMP [ 'colors' ] = pow ( 2 , $BMP['bits_per_pixel ' ]);
if ( $BMP ['size_bitmap'] == 0 ) $BMP ['size_bitmap']=$FILE ['file_size']-$FILE ['bitmap_offset'];
$BMP ['bytes_per_pixel'] = $BMP ['bits_per_pixel'] / 8 ;
$BMP ['bytes_per_pixel2'] = ceil ( $BMP ['bytes_per_pixel']);
$BMP ['decal'] = ( $BMP ['width']*$BMP ['bytes_per_pixel'] / 4 );
$BMP ['decal'] -= floor ( $BMP ['width'] * $BMP ['bytes_per_pixel'] / 4 );
$BMP ['decal'] = 4 - ( 4 * $BMP ['decal']);
if ( $BMP ['decal'] == 4 ) $BMP ['decal'] = 0 ;
// 3 : Chargement des couleurs de la palette
$PALETTE = array ();
if ( $BMP ['colors'] < 16777216 ){
$PALETTE = unpack ( 'V' . $BMP ['colors'] , fread ( $f1 , $BMP ['colors'] * 4 ));
}
// 4 : Cr?ation de l'image
$IMG = fread ( $f1 , $BMP ['size_bitmap']);
$VIDE = chr ( 0 );
$res = imagecreatetruecolor( $BMP ['width'] , $BMP ['height']);
$P = 0 ;
$Y = $BMP ['height'] - 1 ;
while ( $Y >= 0 ){
$X = 0 ;
while ( $X < $BMP ['width']){
if ( $BMP ['bits_per_pixel'] == 24 )
$COLOR = @unpack ( "V" , substr($IMG,$P,3).$VIDE );
elseif ( $BMP['bits_per_pixel']== 16 ){
$COLOR = unpack ( "n" , substr ( $IMG , $P , 2 ));
$COLOR [1] = $PALETTE [ $COLOR [ 1 ] + 1 ];
}elseif ( $BMP['bits_per_pixel']== 8 ){
$COLOR = unpack ( "n" , $VIDE . substr ( $IMG , $P , 1 ));
$COLOR [1] = $PALETTE [ $COLOR [ 1 ] + 1 ];
}elseif ( $BMP['bits_per_pixel']== 4 ){
$COLOR = unpack ( "n" , $VIDE . substr ( $IMG , floor ( $P ) , 1 ));
if (( $P * 2 ) % 2 == 0 )
$COLOR [1] = ( $COLOR [1] >> 4 ) ;
else
$COLOR [1] = ( $COLOR [1] & 0x0F );
$COLOR [1] = $PALETTE [ $COLOR [1] + 1 ];
}elseif ( $BMP['bits_per_pixel']== 1 ){
$COLOR = unpack ( "n" , $VIDE . substr ( $IMG , floor ( $P ) , 1 ));
if (( $P * 8 ) % 8 == 0 ) $COLOR [ 1 ] = $COLOR [ 1 ] >> 7 ;
elseif (( $P * 8 ) % 8 == 1 ) $COLOR [1] = ( $COLOR [1] & 0x40 ) >> 6 ;
elseif (( $P * 8 ) % 8 == 2 ) $COLOR [1] = ( $COLOR [1] & 0x20 ) >> 5 ;
elseif (( $P * 8 ) % 8 == 3 ) $COLOR [1] = ( $COLOR [1] & 0x10 ) >> 4 ;
elseif (( $P * 8 ) % 8 == 4 ) $COLOR [1] = ( $COLOR [1] & 0x8 ) >> 3 ;
elseif (( $P * 8 ) % 8 == 5 ) $COLOR [1] = ( $COLOR [1] & 0x4 ) >> 2 ;
elseif (( $P * 8 ) % 8 == 6 ) $COLOR [1] = ( $COLOR [1] & 0x2 ) >> 1 ;
elseif (( $P * 8 ) % 8 == 7 ) $COLOR [1] = ( $COLOR [1] & 0x1 );
$COLOR [1] = $PALETTE [ $COLOR [1] + 1 ];
}else return FALSE ;
imagesetpixel( $res , $X , $Y , $COLOR [ 1 ]);
$X ++ ;
$P += $BMP['bytes_per_pixel'];
}
$Y -- ;
$P += $BMP['decal'];
}
// Fermeture du fichier
fclose ( $f1 );
return $res ;
} 

/**
 * 不允许上传的文件检测
 * @param $filename 检测的文件
 * @return string  检测通过返回文件类型，不通过返回unknown
 */
function checkFileTitle($filename) {
	//note 读取文件
	$file = fopen($filename, 'rb');
	$bin  = fread($file, 2); //只读2字节
	fclose($file);
	//note 解压缩位字符串资料
	$strInfo  = @unpack('c2chars', $bin);
	$typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
	$fileType = '';
	//note 如果要添加判断的文件类型，可以上传测试typeCode代码获得具体的值
	switch ($typeCode){
		/*
		case 8297:
		$fileType = 'rar';
		break;
		case 255216:
		$fileType = 'jpg';
		break;
		case 7173:
		$fileType = 'gif';
		break;
		case 6677:
		$fileType = 'bmp';
		break;
		case 13780:
		$fileType = 'png';
		break;
		case 13780:
		$fileType = 'png';
		break;
		*/
		case 4838:
		$fileType = 'wma';
		break;
		case 8273:
		$fileType = 'wav';
		break;
		case 7368:
		$fileType = 'mp3';
		break;
		default:
		$fileType = 'unknown';
	}
	/*
	//note 遇到特殊的头字节情况，在下面补充
	if ($strInfo['chars1']=='-1' && $strInfo['chars2']=='-40' ) {
		return 'jpg';
	}
	if ($strInfo['chars1']=='-119' && $strInfo['chars2']=='80' ) {
		return 'png';
	}
	*/
	return $fileType;
}


//当前在线会员
function get_online_member_total($where){
	$condition[] = $where;
	
   //所管理的客服id列表
   $myservice_idlist = get_myservice_idlist(); 
   
   if(empty($myservice_idlist)){
   		$condition[] = " m.sid IN({$GLOBALS['adminid']})";
   }elseif($myservice_idlist == 'all'){
   		//all为客服主管能查看所有的
   }else{
   		$condition[] = " m.sid IN($myservice_idlist)";
   }
   if(!empty($condition)){
   		$sql_where = implode(' AND ',$condition);	
   }
   
   
   $time = time()-100;
   $sql = "SELECT COUNT(*) AS c FROM {$GLOBALS['dbTablePre']}members_search m
   		   LEFT JOIN 
   		   {$GLOBALS['dbTablePre']}member_admininfo ma
   		   ON m.uid=ma.uid
   		   $sql_where 
   		   AND real_lastvisit>'{$time}'";
   /*
   $sql = "SELECT COUNT(*) as c FROM {$GLOBALS['dbTablePre']}membersession mb
   		   LEFT JOIN 
   		   {$GLOBALS['dbTablePre']}member_admininfo ma
   		   ON mb.uid=ma.uid
   		   $sql_where";
   */
   $result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
   echo '<br />';
   //echo $sql;
   return $result['c'];
}

//生成后台客服名缓存文件
function create_kefuuser_cachefile(){
	//所有客服列表
	$sql = "SELECT uid,usercode,username,name FROM {$GLOBALS['dbTablePre']}admin_user ORDER BY uid ASC";
	$kefu_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	$kefu_arr = array('无');
	$kefu_arr_ = array('null');
	foreach($kefu_list as $kefu){
		$kefu_arr[$kefu['uid']] = $kefu['username'];
		$kefu_arr_[$kefu['uid']] = $kefu['usercode'].','.$kefu['username'];
	}
	
	file_put_contents('./data/kefulist_cache.php','<?php  $kefu_arr=' .var_export($kefu_arr,true).'; ?>');
	file_put_contents('./data/kefulist_cache_.php','<?php $kefu_arr_=' .var_export($kefu_arr_,true).'; ?>');
	return true;
}


//得到客服列表
function get_kefulist(){
	$sql_where = '';
	//所管理的客服id列表
    $myservice_idlist = get_myservice_idlist();
	
	
	if(empty($myservice_idlist)){
		$sql_where = " WHERE uid IN({$GLOBALS['adminid']})";
	}elseif($myservice_idlist == 'all'){
		//all为客服主管能查看所有的
        $sql_where = " where uid>1";
	}else{
		$sql_where = " WHERE uid IN({$myservice_idlist})";
	}
    
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_user $sql_where ORDER BY uid ASC";
    
	$result = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	// if(in_array($GLOBALS['groupid'],$GLOBALS['admin_service_team'])) print_r($result);
	return $result;
}

//视频路径加密，先base64加密后打乱顺序
function videoPathEncrypt( $videopath='' ){
	if(empty($videopath)) return;
	$str = base64_encode($videopath);
	$step = 4;
	$len = strlen($str);
	if ( $len <= $step ) return strrev($str);
	$n_block  = $len % $step + 3; //小块的字符数
	$y  = $len % $n_block;    //不足一小块余下字符数

	$str_y  = substr($str, $y, $y);
	$arr_block  = str_split( substr($str, 0, $y).substr($str, $y+$y), $n_block );
	foreach( $arr_block as &$v ){
		$v = strrev($v);
	}
	$out = implode( '', $arr_block ); 
	return substr($out, 0, $y ).strrev($str_y).substr($out, $y);
}

/**获取查询条件及其排序方式
*$sql_where当前查询条件
*$allow_order所允许的所有排序方式
*$field默认查询字段
*$value默认查询字段对应值
*$sort_field默认排序字段
*$method默认排序方式
*$update_arr需要修正的排序字段如：年龄对应的出生年（字段作为键，值为1的数组）
*$first是否允许默认字段排在第一位
*/
function get_query_builder($sql_where, $allow_order, $field = '', $value = '', $sort_field = '', $method = 'desc', $update_arr = array(), $first = 0) {
	 global $_MooCookie, $_MooClass, $adminid;
	 $iserror=$sql_where1=$sql_order='';
	 
	 
	 $_MooCookie['sql_where']= isset($_MooCookie['sql_where'])?$_MooCookie['sql_where']:'';
	//判断是否用已有条件
	$h = MooGetGPC('h', 'string', 'G');
	$clear = MooGetGPC('clear', 'integer', 'G');
	$handle = isset($_MooCookie['handle']) ? stripslashes($_MooCookie['handle']) : '';
	$sql_where1 = isset($_MooCookie['sql_where']) ? stripslashes($_MooCookie['sql_where']) : '';

	$sql_where1 = stripslashes($sql_where1);

	if(!$sql_where && !$clear) {
		if($sql_where1 && $h == $handle) {
			$sql_where = $sql_where1;
		} elseif($field) {
			$sql_where = "`{$field}` = {$value}";
		}
		
	}

	//排序条件
	//获取存储的查询条件
	$order_arr = array();
	$order_str='';
//	if(file_exists(MOOPHP_DATA_DIR.'/block/'.$adminid.'order_str.data')) {
//		$order_str = file_get_contents(MOOPHP_DATA_DIR.'/block/'.$adminid.'order_str.data');
//	}
	
	//提取本次的排序方式
	$order = array();
	foreach($allow_order as $key => $val) {
		if((isset($_GET['order'])?$_GET['order']:'') == $key) {
			$order[$key] = MooGetGPC('method', 'string', 'G');
		}
	}
	//获取排序方式
	if($h == $handle && $sql_where1 == $sql_where && !$clear) {
		//获取已有排序条件
		$order_arr = unserialize($order_str) ? unserialize($order_str) : array();

		if(count($order_arr) == 1 && isset($order_arr[$sort_field]) && $first == 0) {
			$order_arr = array();
		}
		if($order) {
			foreach($order as $key2 => $val2) {
			}
			if($order_arr) {
				foreach($order_arr as $key1 => $val1) {
					if($key1 == $key2) {
						if($val2 == 'replace') {
							$vala = ($val1 == 'desc') ? 'asc' : 'desc';
							$order_arr[$key1] = $vala;
							break;
						}
						unset($order_arr[$key1]);
						if($val2 == 'del') {
							break;
						}
					}
					if($val2 == 'desc' || $val2 == 'asc'){
						if(count($order_arr) > 0) {
							foreach($order_arr as $key => $val) {
								unset($order_arr[$key]);
								break;
							}
						}
						$order_arr[$key2] = $val2;
					}
				}
			} else {
				foreach($order as $key => $val) {
					if($val == 'desc' || $val == 'asc') {
						$order_arr[$key] = $val;
					}
				}
			}
		}
	} else {
		foreach($order as $key => $val) {
			if($val != 'desc' && $val != 'asc') {
				$iserror = 1;
			}
		}
		if($iserror != 1) {
			$order_arr = $order ? $order : array();
		}
	}
	if(empty($order_arr) && $sort_field) {
		$order_arr = array($sort_field => $method);
		
	}
	$sql_order = '';
	if($order_arr) {
		$sql_order = ' order by';
	}
	$n = 0;

	foreach($order_arr as $key => $val) {
		$n++;
		$sql_order .= " {$key} {$val}";
		if($n != count($order_arr)) {
			$sql_order .=',';
		}
	}

	//存储排序
	MooSetCookie('handle', $h);
	MooSetCookie('sql_where', $sql_where);
//	$_MooClass['MooCache']->setBlockCache($adminid.'order_str', $order_arr);

	//排序修正
    if(!empty($update_arr)){
        foreach($update_arr as $key2 => $val2) {
            foreach($order_arr as $key1 => $val1) {
                if(isset($order_arr[$key2])) {
                    $order_arr[$key2] = $order_arr[$key2] == 'desc' ? 'asc' : 'desc';
                    break;
                }
            }
        }
    }

	//获取相反排序
	$rsort_arr = array();
	foreach($allow_order as $key => $val) {
		if(isset($order_arr[$key])) {
			if(isset($update_arr[$key])) {
				$rsort_arr[$key] = $order_arr[$key];
			} else {
				$rsort_arr[$key] = $order_arr[$key] == 'desc' ? 'asc' : 'desc';
			}
		} else {
			$rsort_arr[$key] = 'desc';
		}
	}
/*	echo "<pre>";
	print_r($rsort_arr);*/
	return array(
		'where' => $sql_where,
		'sort' => $sql_order,
		'sort_arr' => $order_arr,
		'rsort_arr' => $rsort_arr
	);
}

//获取当前页码
function get_page() {
	global $_MooCookie;
	$clear = MooGetGPC('clear', 'integer', 'G');
	$page = MooGetGPC('page','integer', 'G');
	if($page == 0 && !$clear) {
		$page1 = isset($_MooCookie['page']) ? $_MooCookie['page'] : 0;
		$h1 = 0;
		if(isset($_MooCookie['h'])) $h1 = $_MooCookie['h'];
		
		if(isset($_GET['h']) && $h1 == $_GET['h'] && empty($_GET['order'])) {
			$page = $page1;
		}
	}
	$page = $page < 1 ? 1 : $page;
	MooSetCookie('page', $page);
	MooSetCookie('h', $_GET['h']);
	return $page;
}

//获取查询条件
function get_condition($where) {
	$where = str_replace('WHERE','where',$where);
	$where = str_replace('AND','and',$where);
	$where = str_replace('LIKE','like',$where);
	$where = str_replace('IN','in',$where);
	$where = preg_replace("([`'%<>]+)", '', $where);

	$where_arr = explode('where', $where);
	if(!isset($where_arr[1])) return array();
	$where_arr = explode('and', $where_arr[1]);

	$condition = array();
	foreach($where_arr as $where1) {
		if(strpos($where1,  ' like ') != false){
			$where1 = str_replace(' like ', '=', $where1);
		}
		$condition1 = explode('=', $where1);
		$key = '.'.trim($condition1[0]);
		$key = explode('.', $key);
		$num = count($key) - 1;
		$key = $key[$num];
		/*if(strstr($key, ' like ')) {
			$arr = explode(' like ', $key);
			$key = $arr[0];
			$val = $arr[1];
		} else {*/
		$val = '';
		isset($condition1[1]) && $val = trim($condition1[1]);

		//}
		
		if(!isset($condition[$key])){
			$condition[$key] = $val;
		}else{
			if ($key=='sid'){
				$condition[$key] = $val;continue;
			}
			if(!is_array($condition[$key])){
				$condition[$key] = array($condition[$key], $val);
			}else{
				$condition[$key][] = $val;
			}
		}
	}
	if(isset($condition['sid']) && !$condition['sid']) {
		$condition['sid'] = '0';
	}
	
	return $condition;
}

/**
* 创建bmp格式图片
*
* @param resource $im          图像资源
* @param string   $filename    如果要另存为文件，请指定文件名，为空则直接在浏览器输出
* @param integer  $bit         图像质量(1、4、8、16、24、32位)
* @param integer  $compression 压缩方式，0为不压缩，1使用RLE8压缩算法进行压缩
*
* @return integer
*/
function imagebmp(&$im, $filename = '', $bit = 8, $compression = 0)
{
    if (!in_array($bit, array(1, 4, 8, 16, 24, 32)))
    {
        $bit = 8;
    }
    else if ($bit == 32) // todo:32 bit
    {
        $bit = 24;
    }
 
    $bits = pow(2, $bit);
   
    // 调整调色板
    imagetruecolortopalette($im, true, $bits);
    $width  = imagesx($im);
    $height = imagesy($im);
    $colors_num = imagecolorstotal($im);
   
    if ($bit <= 8)
    {
        // 颜色索引
        $rgb_quad = '';
        for ($i = 0; $i < $colors_num; $i ++)
        {
            $colors = imagecolorsforindex($im, $i);
            $rgb_quad .= chr($colors['blue']) . chr($colors['green']) . chr($colors['red']) . "\0";
        }
       
        // 位图数据
        $bmp_data = '';
 
        // 非压缩
        if ($compression == 0 || $bit < 8)
        {
            if (!in_array($bit, array(1, 4, 8)))
            {
                $bit = 8;
            }
 
            $compression = 0;
           
            // 每行字节数必须为4的倍数，补齐。
            $extra = '';
            $padding = 4 - ceil($width / (8 / $bit)) % 4;
            if ($padding % 4 != 0)
            {
                $extra = str_repeat("\0", $padding);
            }
           
            for ($j = $height - 1; $j >= 0; $j --)
            {
                $i = 0;
                while ($i < $width)
                {
                    $bin = 0;
                    $limit = $width - $i < 8 / $bit ? (8 / $bit - $width + $i) * $bit : 0;
 
                    for ($k = 8 - $bit; $k >= $limit; $k -= $bit)
                    {
                        $index = imagecolorat($im, $i, $j);
                        $bin |= $index << $k;
                        $i ++;
                    }
 
                    $bmp_data .= chr($bin);
                }
               
                $bmp_data .= $extra;
            }
        }
        // RLE8 压缩
        else if ($compression == 1 && $bit == 8)
        {
            for ($j = $height - 1; $j >= 0; $j --)
            {
                $last_index = "\0";
                $same_num   = 0;
                for ($i = 0; $i <= $width; $i ++)
                {
                    $index = imagecolorat($im, $i, $j);
                    if ($index !== $last_index || $same_num > 255)
                    {
                        if ($same_num != 0)
                        {
                            $bmp_data .= chr($same_num) . chr($last_index);
                        }
 
                        $last_index = $index;
                        $same_num = 1;
                    }
                    else
                    {
                        $same_num ++;
                    }
                }
 
                $bmp_data .= "\0\0";
            }
           
            $bmp_data .= "\0\1";
        } 
       
        $size_quad = strlen($rgb_quad);
        $size_data = strlen($bmp_data);
    }
    else
    {
        // 每行字节数必须为4的倍数，补齐。
        $extra = '';
        $padding = 4 - ($width * ($bit / 8)) % 4;
        if ($padding % 4 != 0)
        {
            $extra = str_repeat("\0", $padding);
        }
 
        // 位图数据
        $bmp_data = '';
 
        for ($j = $height - 1; $j >= 0; $j --)
        {
            for ($i = 0; $i < $width; $i ++)
            {
                $index  = imagecolorat($im, $i, $j);
                $colors = imagecolorsforindex($im, $index);
               
                if ($bit == 16)
                {
                    $bin = 0 << $bit;
 
                    $bin |= ($colors['red'] >> 3) << 10;
                    $bin |= ($colors['green'] >> 3) << 5;
                    $bin |= $colors['blue'] >> 3;
 
                    $bmp_data .= pack("v", $bin);
                }
                else
                {
                    $bmp_data .= pack("c*", $colors['blue'], $colors['green'], $colors['red']);
                }
               
                // todo: 32bit;
            }
 
            $bmp_data .= $extra;
        }
 
        $size_quad = 0;
        $size_data = strlen($bmp_data);
        $colors_num = 0;
    }
 
    // 位图文件头
    $file_header = "BM" . pack("V3", 54 + $size_quad + $size_data, 0, 54 + $size_quad);
 
    // 位图信息头
    $info_header = pack("V3v2V*", 0x28, $width, $height, 1, $bit, $compression, $size_data, 0, 0, $colors_num, 0);
   
    // 写入文件
    if ($filename != '')
    {
        $fp = fopen("test.bmp", "wb");
 
        fwrite($fp, $file_header);
        fwrite($fp, $info_header);
        fwrite($fp, $rgb_quad);
        fwrite($fp, $bmp_data);
        fclose($fp);
 
        return 1;
    }
   
    // 浏览器输出
    header("Content-Type: image/bmp");
    echo $file_header . $info_header;
    echo $rgb_quad;
    echo $bmp_data;
   
    return 1;
} 


?>
