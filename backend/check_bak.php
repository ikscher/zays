<?php
/*******************************************逻辑层(M)/表现层(V)*****************************************/
//note 站内信
function check_letter(){
	global $_MooCookie;
	$lei = '站内短信';
	$sid=$GLOBALS['adminid'];
	$usersid=MooGetGPC('usersid','string','G');
	$pass = MooGetGPC('pass','integer','G');
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl2 = preg_replace("/(&pass=\d+)/","",$currenturl2);
	$page = get_page();
	$pagesize=20;
	$start=($page-1)*$pagesize;
	
	//所管理的客服id列表
    $myservice_idlist = get_myservice_idlist();
	if(isset($_GET['usersid']) && MooGetGPC('usersid', 'string', 'G') == 0) {
		salert("无所属客服");
	}
	
	if(empty($myservice_idlist)){
   		$sql_where = " AND m.sid IN({$GLOBALS['adminid']})";
		
	}elseif($myservice_idlist == 'all'){
		if(isset($_GET['usersid'])){$sql_where=" AND m.sid='$usersid'";}
			//all为客服主管能查看所有的
	}else{
		$sql_where = " AND m.sid IN($myservice_idlist)";
		if(isset($_GET['usersid'])){$sql_where .=" AND m.sid='$usersid'";}
	}
	$sql_where .= " AND s.flag='$pass'";
	$sql = "SELECT count(1) as c FROM {$GLOBALS['dbTablePre']}services s 
	LEFT JOIN {$GLOBALS['dbTablePre']}members m ON s.s_fromid = m.uid 
	WHERE s.s_cid!='50' {$sql_where}";
	$total_count=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$total = $total_count['c'];
	$sql = "SELECT s.*,m.nickname FROM {$GLOBALS['dbTablePre']}services s 
			LEFT JOIN {$GLOBALS['dbTablePre']}members m ON s.s_fromid = m.uid 
			WHERE s.s_cid!='50' {$sql_where} order by s_id desc LIMIT $start,$pagesize";

	$ser_email = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	if($ser_email){
		foreach($ser_email as $user){
			$s_uid[] = $user['s_uid'];	
		}
		$s_uid = implode(",", array_unique($s_uid));
		$sql = "SELECT `nickname`,uid FROM {$GLOBALS['dbTablePre']}members WHERE `uid` in({$s_uid})";
		if(!empty($s_uid)){
			$s_nickname = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
		}
		foreach($s_nickname as $v){
			$user_nickname[$v['uid']] = $v['nickname'];		
		}
	}
	require adminTemplate("check_letter");
	
};

//note 形象照
function check_photo(){
	$sid=$GLOBALS['adminid'];
	$pass = MooGetGPC('pass','integer');
	$type=MooGetGPC('type','string')=='' ? 'list' : MooGetGPC('type','string');
	$usersid=MooGetGPC('usersid','string','G');
	$lei="会员形象照审核";
	$uid = MooGetGPC('uid','integer');
	$id=MooGetGPC('id','integer');
	$checkArr = array(1=>'审核通过',0=>'未审核');
	switch ($type){
        case 'list': 
			//note 获得当前url
			$currenturl = "index.php?action=check&h=photo"; 
			$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
			$currenturl2 = preg_replace("/(&pass=\d+)/","",$currenturl2)."&pass=$pass";
			$currenturl2 = preg_replace("/(&usersid=\d+)/", '', $currenturl2);
	
			$page = get_page();
			$prepage=20;
			$start=($page-1)*$prepage;
			$sql_where = $sql_and = '';
			$condition[] = " WHERE images_ischeck={$pass}";
			//所管理的客服id列表
			if(isset($_GET['usersid']) && MooGetGPC('usersid', 'string', 'G') == 0) {
				salert("无所属客服");
			}			
			if(empty($myservice_idlist)){
				$condition[] = " sid IN({$GLOBALS['adminid']})";
			}elseif($myservice_idlist == 'all'){
				if(isset($_GET['usersid'])){$condition[]=" sid='$usersid'";}
					//all为客服主管能查看所有的
			}else{
				$condition[] = " sid IN({$myservice_idlist})";
				if(isset($_GET['usersid'])){$condition[]=" sid='$usersid'";}
			}
			if(!empty($uid)){
				$condition[] = " uid='{$uid}'";
			}
			$sql_where = implode(' AND',$condition);
			
			$sql = "SELECT count(1) as c FROM {$GLOBALS['dbTablePre']}members {$sql_where}";
			$total_count=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
//			echo $sql;
//			echo '<br />';
			$total = $total_count['c'];
			$sql = "SELECT `mainimg`,`lastvisit`,`sid`,`uid`,`uid` AS id,`images_ischeck` AS syscheck,`nickname`,`birthyear`,`gender` FROM {$GLOBALS['dbTablePre']}members {$sql_where} ORDER BY `lastvisit`,`uid` DESC LIMIT $start,$prepage";
			$list=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
//			echo $sql;
			echo "<span style='display:none;'>$sql</span>";
			require adminTemplate("check_list");
        
        break;
        case 'show': 
        	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间
        	$bili="4:5";
			$photo=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT `mainimg`,`images_ischeck` AS syscheck,`birthyear`,`gender` FROM {$GLOBALS['dbTablePre']}members WHERE `uid`='$uid'");
			$mainimg_preg = preg_replace("(\.)","_nowater.",$photo['mainimg']);
			
			$photo_src = $mainimg_preg;
			$photo_arr = explode('.', $photo_src);
			$count = count($photo_arr);
			$num = MooGetGPC('num', 'integer', 'G');
			
			if(isset($_GET['num'])) {
				if(file_exists('../'.$mainimg_preg)) {
					$src = $photo_arr[0].'.'.($num + 1) % 4 .'.'.$photo_arr[$count - 1];
				} else {
					$src = preg_replace("(_nowater)",'.'.($num + 1) % 4,$mainimg_preg);
				}
				if(file_exists('../'.$src)) {
					$photo['mainimg'] = $src;
				} else {
					
				}
			} else {
				$src = $mainimg_preg;
				if(file_exists('../'.$src)) {
					$photo['mainimg'] = $src;
				}
			}
			
			$url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
            //$user_introduce=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT `introduce`,`introduce_check` FROM {$GLOBALS['dbTablePre']}choice WHERE `uid`='$uid'");
            serverlog(1,$dbTablePre."members","{$GLOBALS['username']}查询{$uid}会员印象照",$GLOBALS['adminid'],$uid);
            require adminTemplate("check_show");
        
        break;
        case 'submit' : 
			$is_pass=MooGetGPC('pass','string');
			$is_onpass=MooGetGPC('nopass','string');
        	if($is_onpass){
	        	global $_MooClass,$dbTablePre;
		        MooAutoLoad('MooFiles','libraries');
		        if($uid==''){salert('没用传入参数','index.php?action=check&h=list',1);}
		        $GLOBALS['_MooClass']['MooMySQL']->query("UPDATE {$GLOBALS['dbTablePre']}members SET `mainimg` = '',`pic_date` = '',`pic_name` = '',`images_ischeck`='-1' WHERE `uid`='$uid'");
				if(MOOPHP_ALLOW_FASTDB){					
					$value['mainimg']='';
					$value['pic_date']='';
					$value['images_ischeck']='';
					MooFastdbUpdate("members",'uid',$uid,$value);
					}
				serverlog(2,$dbTablePre."members","{$GLOBALS['username']}未通过会员{$uid}的形象照".$id,$GLOBALS['adminid'],$uid);
				if(MooGetGPC("userphoto",'integer')){
					sendusermessage($uid,"您的形象照不符合图片要求，已被红娘删除，请您重新上传","形象照审核");
					//salert('成功设置形象照未通过');
					$alert = '成功设置形象照未通过';
				}
				
	        }else if($is_pass){
				$userphoto=MooGetGPC('userphoto','string');
				$incoduce=MooGetGPC('incoduce','string');
				$contentincoduce=MooGetGPC('contentincoduce','string');
				if($userphoto){
					//保存形象照
					savePhoto($uid);
					$filename = MooGetGPC('photoimage');
					if(file_exists($filename)) {
						$file_arr = explode('.', $filename);
						$num = count($file_arr) - 2;
						if(strlen($file_arr[$num]) == 1) {
							$_MooClass['MooFiles']->fileDelete($filename);
						}
					}
				}
				//更新诚信值
				reset_integrity($uid);
				$alert = '审核成功';
			
				//note 审核通过后，写入前台搜索表
				fastsearch_update($uid,1); //note 写入常用搜索表
				fastsearch_update($uid,2); //note 写入高级搜索表
        }
		salert($alert,'index.php?action=check&h=photo');
		break;
	}
};
//note 内心独白
function check_monolog(){
	$sid=$GLOBALS['adminid'];
	$pass = MooGetGPC('pass','integer','G');
	$usersid=MooGetGPC('usersid','string','G');//makui
	$type=MooGetGPC('type','string')=='' ? 'list' : MooGetGPC('type','string');
	$lei="内心独白验证";
	$uid = MooGetGPC('uid','integer');
	$id=MooGetGPC('id','integer');
	$checkArr = array(1=>'审核通过',0=>'审核未通过','-1'=>'暂无');
	switch ($type){
        case 'list': 
			//note 获得当前url
			$currenturl = "index.php?action=check&h=monolog"; 
			$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
			$currenturl2 = preg_replace("/(&field=(sid|lastvisit)&order=(desc|asc))/","",$currenturl2);
			$currenturl2 = preg_replace("/(&pass=\d+)/","",$currenturl2)."&pass=$pass";
			$currenturl2 = preg_replace("/(&usersid=\d+)/", '', $currenturl2);
			
			$page = get_page();
			$prepage=20;
			$start=($page-1)*$prepage;
			//所管理的客服id列表
			if(isset($_GET['usersid']) && MooGetGPC('usersid', 'string', 'G') == 0) {
				salert("无所属客服");
			}
			$myservice_idlist = get_myservice_idlist();
			if(empty($myservice_idlist)){
				$condition[] = "a.sid IN({$GLOBALS['adminid']}) ";
			}elseif($myservice_idlist == 'all'){
				if(isset($_GET['usersid'])){$condition[]=" sid='$usersid'";}
				
					//all为客服主管能查看所有的
			}else{
				$condition[] = "a.sid IN($myservice_idlist) ";
				if(isset($_GET['usersid'])){$condition[]=" sid='$usersid'";}
			}
			
			if($uid > 0){
				$condition[] = " a.uid=$uid";
			}else{
				if($pass == 0){
					//$condition[] = " b.introduce_check != '' ";
					$condition[] = " b.introduce_pass = '2' ";
				}else{
					$condition[] = " b.introduce_pass = '1' ";
				}
			}
			if(!empty($condition)){
				$sql_where = ' WHERE '.implode(' AND ',$condition);
			}
			$order = 'desc';
			if(in_array($_GET['field'],array('sid','lastvisit'))){
				$order_str = " a.".$_GET['field']." ".$_GET['order'].',';
				if($order == $_GET['order']){$order = 'asc';}
				$currenturl2 .= "&field={$_GET['field']}&order={$_GET['order']}";
			}
			
			$sql = ("SELECT count(1) as c FROM {$GLOBALS['dbTablePre']}members AS a LEFT JOIN {$GLOBALS['dbTablePre']}choice AS b ON a.uid=b.uid {$sql_where}");
			$total_count = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			$total = $total_count['c'];
			
			$sql = ("SELECT a.uid as uid,b.uid as id,b.introduce as syscheck,b.introduce_pass,b.introduce_check,a.nickname,a.birthyear,a.sid as sid,a.gender,a.lastvisit FROM {$GLOBALS['dbTablePre']}members AS a  LEFT JOIN {$GLOBALS['dbTablePre']}choice AS b ON a.uid=b.uid {$sql_where} ORDER BY {$order_str} a.uid ASC LIMIT $start,$prepage");
            $list=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
            //echo ("SELECT a.uid as uid,b.uid as id,b.introduce as syscheck,b.introduce_check,a.nickname,a.birthyear,a.gender,a.lastvisit FROM {$GLOBALS['dbTablePre']}members AS a JOIN {$GLOBALS['dbTablePre']}choice AS b WHERE {$sql_where} a.uid=b.uid ORDER BY b.introduce ASC,b.introduce_check DESC,a.lastvisit DESC LIMIT $start,$prepage");
			echo "<span style='display:none'>$sql</span>";
            require adminTemplate("check_list_monolog");
        
        break;
        case 'show': 
        	$monolog=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT a.introduce,a.introduce_check,b.birthyear,b.gender FROM {$GLOBALS['dbTablePre']}choice as a join {$GLOBALS['dbTablePre']}members as b WHERE a.uid=b.uid and a.uid=".$uid);
            serverlog(1,$dbTablePre."choice","{$GLOBALS['username']}查询会员{$uid}内心独白",$GLOBALS['adminid'],$uid);
            require adminTemplate("check_show");
        
        break;
        case 'submit' : 
			$ajax = MooGetGPC('ajax','integer','P');
			$pass = MooGetGPC('pass','integer','P');
	        $introduce=MooGetGPC('introduce','string');
	       
			
			if($ajax == 1){
				if($pass == 1){
					$sql="SELECT uid FROM {$GLOBALS['dbTablePre']}choice where uid='{$uid}' and introduce_pass=1 and introduce!=''";
					$pass_have=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
					if($pass_have['uid']){echo $pass;exit;}
					$sql = "UPDATE {$GLOBALS['dbTablePre']}choice SET `introduce`=`introduce_check`,`introduce_check`='',`introduce_pass`='1' WHERE `uid`='$uid'";
					$GLOBALS['_MooClass']['MooMySQL']->query($sql);
					serverlog(3,$dbTablePre."choice",$GLOBALS['username']."审核会员".$uid."的内心独白为通过",$GLOBALS['adminid'],$uid);
					if(MOOPHP_ALLOW_FASTDB){
						$old_introduce=MooFastdbGet("choice",'uid',$uid);
						$value['introduce'] = $old_introduce['introduce_check'];
						$value['introduce_check']='';
						$value['introduce_pass']=1;
						MooFastdbUpdate("choice",'uid',$uid,$value);
					}
				}else{
					$sql = "UPDATE {$GLOBALS['dbTablePre']}choice SET `introduce_check`='',`introduce`='',`introduce_pass`='0' WHERE `uid`='$uid'";
					$GLOBALS['_MooClass']['MooMySQL']->query($sql);
					serverlog(3,$dbTablePre."choice",$GLOBALS['username']."审核会员".$uid."的内心独白为不通过",$GLOBALS['adminid'],$uid);
					if(MOOPHP_ALLOW_FASTDB){					
						$value['introduce'] = '';
						$value['introduce_check']='';
						$value['introduce_pass']=0;
						MooFastdbUpdate("choice",'uid',$uid,$value);
					}
				}								
				echo $pass;
				exit;
			}
			/*
			$sql = "UPDATE {$GLOBALS['dbTablePre']}choice SET `introduce`='". $introduce ."',`introduce_check`='' ,`introduce_pass`='1' WHERE `uid`='$uid'";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
			*/
			
			$updatecarr = array();
			$updatecarr['introduce'] = $introduce;
			$updatecarr['introduce_check'] = '';
			$updatecarr['introduce_pass'] = '1';
						
     		$where_arr=array('uid'=>$uid);
			updatetable('choice',$updatecarr,$where_arr);
			
            if(MOOPHP_ALLOW_FASTDB){
				$value['introduce'] = $introduce;
				$value['introduce_check'] = '';
				$value['introduce_pass'] = '1';
				MooFastdbUpdate("choice",'uid',$uid,$value);
			}
            if($introduce!='' || $pass == 1){
            	sendusermessage($uid,"尊敬的红娘会员，您的内心独白已经过红娘的审核","内心独白审核");
				//salert('内心独白审核通过');
				$alert = '内心独白审核通过';
            }else {
				sendusermessage($uid,"尊敬的红娘会员，您的内心独白不符合要求，请按要求填写","内心独白审核");
				//salert('内心独白审核未通过');
				$alert = '内心独白审核未通过';
			}
			
			//note 审核通过后，写入前台搜索表
			fastsearch_update($uid,1); //note 写入常用搜索表
			fastsearch_update($uid,2); //note 写入高级搜索表
			
            serverlog(3,$dbTablePre."choice",$GLOBALS['username']."审核会员".$uid."的内心独白",$GLOBALS['adminid'],$uid);
			if($ajax){
				echo $pass;
				exit;
			}
			salert($alert,'index.php?action=check&h=monolog');
			break;
	}
	
};
//note 相册图片
function check_image(){
	global $_MooClass;
	$sid=$GLOBALS['adminid'];
	$type=MooGetGPC('type','string')=='' ? 'list' : MooGetGPC('type','string');
	$lei="会员相册图片审核";
	$uid = MooGetGPC('uid','integer');
	$usersid=MooGetGPC('usersid','string','G');//makui
	$id=MooGetGPC('id','integer');
	$checkArr = array(1=>'审核通过',0=>'未审核');
	switch($type){
		//相册列表
		case 'list':
			//note 获得当前url
			$pass = MooGetGPC('pass','integer','G');
			$currenturl = "index.php?action=check&h=image"; 
			$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
			$currenturl2 = preg_replace("/(&pass=\d+)/","",$currenturl2)."&pass=$pass";
			$currenturl2 = preg_replace("/(&usersid=\d+)/", '', $currenturl2);
			
			$page = get_page();
			$prepage=20;
			$start=($page-1)*$prepage;
			$sql_where = '';
			//所管理的客服id列表
			if(isset($_GET['usersid']) && MooGetGPC('usersid', 'string', 'G') == 0) {
				salert("无所属客服");
			}
			$myservice_idlist = get_myservice_idlist();
			
			if(empty($myservice_idlist)){
				$sql_where = " AND b.sid IN({$GLOBALS['adminid']})"; 
			}elseif($myservice_idlist == 'all'){
				if(isset($_GET['usersid'])){$sql_where=" AND b.sid='$usersid'";}
					//all为客服主管能查看所有的
			}else{
				$sql_where = " AND b.sid IN($myservice_idlist) ";
				if(isset($_GET['usersid'])){$sql_where .=" AND b.sid='$usersid'";}
			}
			if($uid > 0){
				$sql_where .= " AND b.uid=".$uid;
			}else{
				$sql_where .= " AND a.syscheck=".$pass;
			}
			$total_count=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT count(1) as c FROM {$GLOBALS['dbTablePre']}pic AS a left  JOIN {$GLOBALS['dbTablePre']}members AS b on a.uid=b.uid WHERE 1 {$sql_where}");
			$total = $total_count['c'];
			$list=$GLOBALS['_MooClass']['MooMySQL']->getAll("SELECT a.uid,a.imgid as id,a.syscheck AS syscheck,b.nickname,b.birthyear,b.sid as sid,b.gender,b.lastvisit FROM {$GLOBALS['dbTablePre']}pic AS a left JOIN {$GLOBALS['dbTablePre']}members AS b on a.uid=b.uid WHERE 1 {$sql_where} ORDER BY b.lastvisit DESC LIMIT $start,$prepage ");
			//echo ("SELECT a.uid,a.imgid as id,a.syscheck AS syscheck,b.nickname,b.birthyear,b.gender,b.lastvisit FROM {$GLOBALS['dbTablePre']}pic AS a JOIN {$GLOBALS['dbTablePre']}members AS b WHERE a.uid=b.uid {$sql_where} ORDER BY a.syscheck,a.imgid DESC LIMIT $start,$prepage ");
			require adminTemplate("check_list");
		break;
		//会员相册查看222
		case 'show':
			$pass = MooGetGPC('pass', 'integer', 'G');
			//获取待审核列表
			$images = array();
			if($_COOKIE['images']) {
				$imagedata_dir = MOOPHP_DATA_DIR.'/block/'.'story'.$sid.'.data';
				if(file_exists($imagedata_dir)) {
					$images = unserialize(file_get_contents($imagedata_dir));
				}
			}
		
			if(!$images) {
				$images = $GLOBALS['_MooClass']['MooMySQL']->getAll("SELECT a.*,b.birthyear,b.gender,b.mainimg FROM {$GLOBALS['dbTablePre']}pic as a join {$GLOBALS['dbTablePre']}members as b  WHERE a.uid=b.uid and a.uid='$uid' and a.syscheck=$pass");
			}
			if($id && $images) {
				//数组重排
				$sortarr = array();
				foreach($images as $img) {
					if($img['mid'] == $id) {
						$sortarr[] = -1;
					} else {
						$sortarr[] = $img['mid'];
					}
				}
				array_multisort($sortarr, SORT_ASC, $images);
			}
			//取出待审核照片
			$image = $images[0];
			$_MooClass['MooCache']->setBlockCache('story'.$sid, $images);
			$id = $image['imgid'];

            serverlog(1,$dbTablePre."pic",$GLOBALS['username']."查询会员相册",$GLOBALS['adminid']);
            require adminTemplate("check_show");
		break;
		//审核
		case 'submit';
			$is_pass=MooGetGPC('pass','string');
			$is_onpass=MooGetGPC('nopass','string');
			if($is_onpass){
				$imagesname=MooGetGPC('imagesname','string');
				$pic_date=MooGetGPC('pic_date','string');
				$imagessrc1="../data/upload/images/photo/".$pic_date."/orgin/".$imagesname;
				$GLOBALS['_MooClass']['MooMySQL']->query("DELETE FROM {$GLOBALS['dbTablePre']}pic WHERE `imgid`='$id'");

				if(file_exists("..".thumbImgPath("1",$pic_date,$imagesname))){
				unlink("..".thumbImgPath("1",$pic_date,$imagesname));
				}
				if(file_exists("..".thumbImgPath("2",$pic_date,$imagesname))){
				unlink("../".thumbImgPath("2",$pic_date,$imagesname));
				}
				if(file_exists("..".thumbImgPath("3",$pic_date,$imagesname))){
				unlink("..".thumbImgPath("3",$pic_date,$imagesname));
				}
				if(file_exists($imagessrc1)){
				unlink($imagessrc1);
				}
				serverlog(2,$dbTablePre."pic",$GLOBALS['username']."未通过用户的图片".$id,$GLOBALS['adminid'],$uid);
				sendusermessage($uid,"您的图片不符合图片要求，已被红娘删除，请您按要求进行操作","图片审核");
				//salert('审核图片删除成功');
				$alert = '审核图片删除成功';

			}elseif($is_pass){
				//用户相册图片总数加1
				$sql = "SELECT COUNT(*) AS num FROM {$GLOBALS['dbTablePre']}pic 
				WHERE syscheck=1 and isimage='0' and uid='$uid'";
				$pic_count = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
				$pc = $pic_count['num'] + 1;
				$sql = "update {$GLOBALS['dbTablePre']}members set pic_num='{$pc}' where uid='$uid'";
				$GLOBALS['_MooClass']['MooMySQL']->query($sql);

				if(MOOPHP_ALLOW_FASTDB){
					$value['pic_num']= $pc;
					MooFastdbUpdate('members','uid',$uid,$value);
				}

				$GLOBALS['_MooClass']['MooMySQL']->query("UPDATE {$GLOBALS['dbTablePre']}pic SET `syscheck`=1 WHERE `imgid`='$id'");
				serverlog(3,$dbTablePre."pic",$GLOBALS['username']."通过用户的图片".$id,$GLOBALS['adminid'],$uid);
				sendusermessage($uid,"您的图片已经过红娘的审核，您还可以继续上传您的照片","图片审核");
				//salert('审核图片成功');
				$alert = '审核图片成功';
				
			}
			//更新缓存
			$imagedata_dir = MOOPHP_DATA_DIR.'/block/'.'story'.$sid.'.data';
			$images = array();
			if(file_exists($imagedata_dir)) {
				$images = unserialize(file_get_contents($imagedata_dir));
			}

			$image = array_shift($images);
			$_MooClass['MooCache']->setBlockCache('story'.$sid, $images);
			if($images) {
				$url = 'index.php?action=check&h=image&type=show&id='.$image['imgid'];
			} else {
				$url = 'index.php?action=check&h=image';
			}
			$imgkey = $images ? 1 : 0;
			setcookie('images', $imgkey);
			salert($alert,$url);
		break;
	}
	
};
//note 毕业院校
function check_school(){
	$sid=$GLOBALS['adminid'];
	$isschool = MooGetGPC('isschool','integer','G');
	$type=MooGetGPC('type','string')=='' ? 'list' : MooGetGPC('type','string');
	$usersid=MooGetGPC('usersid','string','G');//makui
	$lei="会员成功故事审核";
	$uid = MooGetGPC('uid','integer','P');
	$id=MooGetGPC('id','integer');
	$checkArr = array(1=>'已通过',0=>'未通过');
	//note 获得当前url
	$currenturl = "index.php?action=check&h=school"; 
	$currenturl2 = preg_replace("/(&page=\d+)|(&isschool=\d+)/","",$currenturl)."&isschool=$isschool";
	$page = get_page();
	$prepage=20;
	$start=($page-1)*$prepage;
	$sql_where = '';
	//所管理的客服id列表
	if(isset($_GET['usersid']) && MooGetGPC('usersid', 'string', 'G') == 0) {
		salert("无所属客服");
	}
    $myservice_idlist = get_myservice_idlist();
	
	if(empty($myservice_idlist)){
		$sql_where = " AND b.sid IN({$GLOBALS['adminid']})";
	}elseif($myservice_idlist == 'all'){
		if(isset($_GET['usersid'])){$sql_where=" AND b.sid='$usersid'";}//查看某一客服的会员
			//all为客服主管能查看所有的
	}else{
		$sql_where = " AND b.sid IN($myservice_idlist)";
		if(isset($_GET['usersid'])){$sql_where .=" AND b.sid='$usersid'";}
	}
	$sql_where .= ' AND a.isschool='.$isschool;
	$total_count = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT count(1) as c FROM {$GLOBALS['dbTablePre']}memberfield AS a JOIN {$GLOBALS['dbTablePre']}members AS b WHERE a.uid=b.uid {$sql_where}");
	$total = $total_count['c'];
	$sql = "SELECT a.uid,a.finishschool,a.isschool AS syscheck,b.nickname,b.birthyear,b.gender,b.sid AS sid,b.lastvisit FROM {$GLOBALS['dbTablePre']}memberfield AS a JOIN {$GLOBALS['dbTablePre']}members AS b WHERE a.finishschool != '' AND a.uid=b.uid {$sql_where} ORDER BY a.isschool,b.lastvisit,b.uid DESC LIMIT $start,$prepage";
	if($uid>0){
		$sql = "SELECT a.uid,a.finishschool,a.isschool AS syscheck,b.nickname,b.sid AS sid,b.birthyear,b.gender 
				FROM {$GLOBALS['dbTablePre']}memberfield AS a JOIN {$GLOBALS['dbTablePre']}members AS b 
				WHERE a.uid={$uid} AND a.finishschool != '' AND a.uid=b.uid".$sql_where;
		$lei="会员毕业院校审核（搜索用户：{$uid}）";
	}else{
		$pageList = multipage($total,$prepage,$page,$currenturl2);
	}
	//echo $sql;
	$school = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	
	require adminTemplate("check_school");
};
//note 成功故事
function check_story(){
	$sid=$GLOBALS['adminid'];
	$type=MooGetGPC('type','string')=='' ? 'list' : MooGetGPC('type','string');
	$lei="会员成功故事审核";
	$usersid=MooGetGPC('usersid','string','G');//makui
	$uid = MooGetGPC('uid','integer');
	$id=MooGetGPC('id','integer');
	$checkArr = array(1=>'审核通过',0=>'未审核');
	switch($type){
		//会员成功故事列表
		case 'list':
			$pass = MooGetGPC('pass','integer','G');
			//note 获得当前url
			$currenturl = "index.php?action=check&h=story"; 
			$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
			$currenturl2 = preg_replace("/(&pass=\d+)/","",$currenturl2)."&pass=$pass";
			$currenturl2 = preg_replace("/(&usersid=\d+)/", '', $currenturl2);
			
			$page = get_page();
			$prepage=20;
			$start=($page-1)*$prepage;
			if(isset($_GET['usersid']) && MooGetGPC('usersid', 'string', 'G') == 0) {
				salert("无所属客服");
			}
			//所管理的客服id列表
			$myservice_idlist = get_myservice_idlist();
			if(empty($myservice_idlist)){
				$sql_where = " b.sid IN({$GLOBALS['adminid']}) AND ";
			}elseif($myservice_idlist == 'all'){
				if(isset($_GET['usersid'])){
					$sql_where=" b.sid='$usersid' AND";
				}//查看某一客服的会员
				//all为客服主管能查看所有的
			}else{
				$sql_where = " b.sid IN($myservice_idlist) AND ";
				if(isset($_GET['usersid'])){$sql_where .=" b.sid='$usersid'  AND";}//查看某一客服的会员
				
			}
			if($uid > 0){
				$sql_where .= " b.uid=".$uid." AND ";
			}else{
				$sql_where .= " a.syscheck=".$pass." AND ";
			}
			$total_count=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT count(1) as c FROM {$GLOBALS['dbTablePre']}story AS a JOIN {$GLOBALS['dbTablePre']}members AS b WHERE {$sql_where} a.uid=b.uid");
			$total = $total_count['c'];
			$sql = "SELECT a.uid,a.sid as id,a.syscheck AS syscheck,b.nickname,b.birthyear,b.sid as sid,b.gender,b.lastvisit FROM {$GLOBALS['dbTablePre']}story AS a JOIN {$GLOBALS['dbTablePre']}members AS b WHERE {$sql_where} a.uid=b.uid ORDER BY a.syscheck,a.submit_date DESC LIMIT $start,$prepage";
			$list=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
			require adminTemplate("check_list");
		
		break;
		case 'show':
			$story=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT a.*,b.birthyear,b.gender FROM {$GLOBALS['dbTablePre']}story as a join {$GLOBALS['dbTablePre']}members as b WHERE a.uid=b.uid and a.sid='$id'");
			serverlog(1,$dbTablePre."story",$GLOBALS['username']."查询会员爱情故事",$GLOBALS['adminid']);
			
			//print_r($story);exit;
			require adminTemplate("check_show");
		
		break;
		
		case 'submit':
			if(isset($_POST['nopass'])){
				$GLOBALS['_MooClass']['MooMySQL']->query("DELETE FROM {$GLOBALS['dbTablePre']}story WHERE `sid`='$id'");
				serverlog(2,$dbTablePre."story",$GLOBALS['username']."未通过用户的成功故事".$id,$GLOBALS['adminid'],$uid);
				sendusermessage($uid,"您的成功故事不符合成功故事要求，已被红娘删除，请您按要求进行操作","成功故事审核");
				//salert('成功删除此会员的成功故事！');
				$alert = '成功删除此会员的成功故事！';
				
			}elseif(isset($_POST['pass'])){
				$GLOBALS['_MooClass']['MooMySQL']->query("UPDATE {$GLOBALS['dbTablePre']}story SET `syscheck`=1 WHERE `sid`='$id'");
				serverlog(3,$dbTablePre."story",$GLOBALS['username']."通过用户的成功故事".$id,$GLOBALS['adminid'],$uid);
				sendusermessage($uid,"您的成功故事已经过红娘的审核，希望您和您的另一半白头偕老，同时也感谢您对红娘的支持","成功故事审核");
				//salert('审核通过此会员成功故事');
				$alert = '审核通过此会员成功故事';
			}
			salert($alert,'index.php?action=check&h=story');
		break;
		
		
	}
	
};

//note 故事封面图
function check_storyfirst(){
	$sid=$GLOBALS['adminid'];
	$type=MooGetGPC('type','string')=='' ? 'list' : MooGetGPC('type','string');
	$usersid=MooGetGPC('usersid','string','G');//makui
	$lei="故事封面图审核";
	$uid = MooGetGPC('uid','integer');
	$id=MooGetGPC('id','integer');
	$checkArr = array(1=>'审核通过',0=>'未审核');
	switch($type){
		case 'list':
			$pass = MooGetGPC('pass','integer','G');
			//note 获得当前url
			$currenturl = "index.php?action=check&h=storyfirst";
			$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
			$currenturl2 = preg_replace("/(&pass=\d+)/","",$currenturl2)."&pass=$pass";
			$currenturl2 = preg_replace("/(&usersid=\d+)/", '', $currenturl2);
			
			$page = get_page();
			$prepage=20;
			$start=($page-1)*$prepage;
			if(isset($_GET['usersid']) && MooGetGPC('usersid', 'string', 'G') == 0) {
				salert("无所属客服");
			}
			//所管理的客服id列表
			$myservice_idlist = get_myservice_idlist();
			if(empty($myservice_idlist)){
				$sql_where = " AND c.sid IN({$GLOBALS['adminid']})";
			}elseif($myservice_idlist == 'all'){
					//all为客服主管能查看所有的
					if(isset($_GET['usersid'])){$sql_where=" AND c.sid='$usersid'";}//查看某一客服的会员
			}else{
				$sql_where = " AND c.sid IN($myservice_idlist)";
				if(isset($_GET['usersid'])){$sql_where .=" AND c.sid='$usersid'";}//查看某一客服的会员
			}
			if($uid > 0){
				$sql_where .= " AND c.uid=".$uid;
			}else{
				$sql_where .= " AND a.syscheck=".$pass;
			}
			
			$total_count=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT count(1) as c FROM {$GLOBALS['dbTablePre']}story_pic AS a,{$GLOBALS['dbTablePre']}story AS b JOIN {$GLOBALS['dbTablePre']}members AS c WHERE a.uid=b.uid AND a.uid=c.uid {$sql_where} AND a.sid=b.sid AND a.mid=b.is_index");
			$total = $total_count['c'];
			$list=$GLOBALS['_MooClass']['MooMySQL']->getAll("SELECT a.uid,a.mid as id,a.syscheck AS syscheck,c.nickname,c.birthyear,c.gender,c.sid,c.lastvisit FROM {$GLOBALS['dbTablePre']}story_pic AS a,{$GLOBALS['dbTablePre']}story AS b JOIN {$GLOBALS['dbTablePre']}members AS c WHERE a.uid=b.uid AND a.uid=c.uid {$sql_where} AND a.sid=b.sid AND a.mid=b.is_index ORDER BY a.syscheck,a.submit_date DESC LIMIT $start,$prepage");
			require adminTemplate("check_list");
		break;
		case 'show':
			$bili="5:3";
			$storyfirst=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT a.*,b.birthyear,b.gender FROM {$GLOBALS['dbTablePre']}story_pic as a join {$GLOBALS['dbTablePre']}members as b WHERE a.uid=b.uid and a.mid='$id'");
			serverlog(1,$dbTablePre."story_pic",$GLOBALS['username']."查询会员爱情故事图片",$GLOBALS['adminid']);
			require adminTemplate("check_show");
		break;
		case 'submit':
			$is_pass=MooGetGPC('pass','string');
			$is_onpass=MooGetGPC('nopass','string');
			if($is_onpass){
				$storyfirstsrc=MooGetGPC('storyfirstsrc','string');
				$GLOBALS['_MooClass']['MooMySQL']->query("DELETE FROM {$GLOBALS['dbTablePre']}story_pic WHERE `mid`='$id'");
				$_MooClass['MooFiles']->fileDelete($storyfirstsrc);
				serverlog(2,$dbTablePre.$GLOBALS['username']."story_pic","未通过用户的成功故事封面照".$id,$GLOBALS['adminid'],$uid);
				sendusermessage($uid,"您的成功故事封面照不符合图片要求，已被红娘删除，请您按要求进行操作","成功故事封面照审核");
				//salert('故事封面照审核不成功');
				$alert = '故事封面照审核不成功';
			}elseif($is_pass){
				$x1=MooGetGPC('x1','integer');
				$y1=MooGetGPC('y1','integer');
				$x2=MooGetGPC('x2','integer');
				$y2=MooGetGPC('y2','integer');
				$width=MooGetGPC('width','integer');
				$height=MooGetGPC('height','integer');
				$storyid=MooGetGPC('storyid','integer');
				$storyfirstsrc=MooGetGPC('storyfirstsrc','string');
				//$newimages=resizeStoryImage($storyfirstsrc,$width,$height,$x1,$y1,$uid);
				$sizearray=array(0=>array('width'=>280,'height'=>168),1=>array('width'=>252,'height'=>151),2=>array('width'=>150,'height'=>90));
				$namearray=array(0=>'big',1=>'medium',2=>'small');
				$newimages=changesize($storyfirstsrc,'../data/upload/story',$x1,$y1,$width,$height,$storyid,$sizearray,$namearray);
				$GLOBALS['_MooClass']['MooMySQL']->query("UPDATE {$GLOBALS['dbTablePre']}story_pic SET `syscheck`=1 WHERE `mid`='$id'");
				serverlog(3,$dbTablePre.$GLOBALS['username']."story_pic","通过用户的故事封面照".$id,$GLOBALS['adminid'],$uid);
				sendusermessage($uid,"您的成功故事封面照已经过红娘的审核，希望您和您的另一半白头偕老，同时也感谢您对红娘的支持","成功故事封面照审核");
				//salert('故事封面照审核通过成功');
				$alert = '故事封面照审核通过成功';
			}
		salert($alert,'index.php?action=check&h=storyfirst');
		break;
	}
};
//note 故事图片
function check_storyimage(){
	global $_MooClass, $adminid;
	$sid=$GLOBALS['adminid'];
	$type=MooGetGPC('type','string')=='' ? 'list' : MooGetGPC('type','string');
	$usersid=MooGetGPC('usersid','string','G');//makui
	$lei="故事图片审核";
	$uid = MooGetGPC('uid','integer');
	$id=MooGetGPC('id','integer');
	$checkArr = array(1=>'审核通过',0=>'未审核');
	switch($type){
		case 'list':
			$pass = MooGetGPC('pass','integer','G');
			//note 获得当前url
			$currenturl = "index.php?action=check&h=storyimage";
			$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
			$currenturl2 = preg_replace("/(&pass=\d+)/","",$currenturl2)."&pass=$pass";
			$currenturl2 = preg_replace("/(&usersid=\d+)/", '', $currenturl2);
			
			$page = get_page();
			$prepage=20;
			$start=($page-1)*$prepage;
			if(isset($_GET['usersid']) && MooGetGPC('usersid', 'string', 'G') == 0) {
				salert("无所属客服");
			}
			//所管理的客服id列表
			$myservice_idlist = get_myservice_idlist();

			if(empty($myservice_idlist)){
				$sql_where = " m.sid IN({$GLOBALS['adminid']}) AND ";
			}elseif($myservice_idlist == 'all'){
					//all为客服主管能查看所有的
					if(isset($_GET['usersid'])){$sql_where=" m.sid='$usersid' AND";}//查看某一客服的会员
			}else{
				$sql_where = " m.sid IN($myservice_idlist) AND ";
				if(isset($_GET['usersid'])){$sql_where .=" m.sid='$usersid' AND";}//查看某一客服的会员
			}
			if($uid > 0){
				$sql_where .= ' m.uid='.$uid.' AND ';
			}else{
				$sql_where .= ' p.syscheck='.$pass.' AND ';
			}
			$sql1="select count(1) as c from web_story_pic as p inner join web_members as m on (p.uid = m.uid) where {$sql_where} p.mid != (select is_index from web_story as s where p.sid = s.sid)";
			$total_count=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql1);
			$total = $total_count['c'];
			$sql="select p.uid,p.mid as id,p.syscheck,m.nickname,m.birthyear,m.sid,m.gender,m.lastvisit from web_story_pic as p inner join web_members as m on (p.uid = m.uid) where {$sql_where} p.mid != (select is_index from web_story as s where p.sid = s.sid) order by p.syscheck,p.submit_date desc limit $start,$prepage";

			$list=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
            require adminTemplate("check_list");
		break;
		case 'show':
			$pass = MooGetGPC('pass', 'integer', 'G');
			//查询出该会员的所有待审核照片222
			$storyimages = array();
			if($_COOKIE['storyimages']) {
				$storydata_dir = MOOPHP_DATA_DIR.'/block/'.'story'.$adminid.'.data';
				if(file_exists($storydata_dir)) {
					$storyimages = unserialize(file_get_contents($storydata_dir));
				}
			}
			
			if(!$storyimages) {
				$storyimages = $GLOBALS['_MooClass']['MooMySQL']->getAll("SELECT a.*,b.birthyear,b.gender, a.mid FROM {$GLOBALS['dbTablePre']}story_pic as a inner join {$GLOBALS['dbTablePre']}members as b WHERE a.uid=b.uid and a.uid={$uid} and a.syscheck=$pass");
			}
			if($id && $storyimages) {
				//数组重排
				$sortarr = array();
				foreach($storyimages as $img) {
					if($img['mid'] == $id) {
						$sortarr[] = -1;
					} else {
						$sortarr[] = $img['mid'];
					}
				}
				array_multisort($sortarr, SORT_ASC, $storyimages);
			}
			//取出待审核照片
			$storyimage = $storyimages[0];
			$_MooClass['MooCache']->setBlockCache('story'.$adminid, $storyimages);
			$id = $storyimage['mid'];

			serverlog(1,$dbTablePre.$GLOBALS['username']."story_pic","查询会员爱情故事图片",$GLOBALS['adminid']);
			require adminTemplate("check_show");
		break;
		case 'submit':
			$is_pass=MooGetGPC('pass','string');
			$is_onpass=MooGetGPC('nopass','string');
			if($is_onpass) {
				$storyimagessrc=MooGetGPC('storyimagessrc','string');
				
				$GLOBALS['_MooClass']['MooMySQL']->query("DELETE FROM {$GLOBALS['dbTablePre']}story_pic WHERE `mid`='$id'");
				$files = MooAutoLoad('MooFiles');
				$files->fileDelete($storyimagessrc);
				serverlog(2,$dbTablePre."story_pic",$GLOBALS['username']."未通过用户的故事图片".$id,$GLOBALS['adminid'],$uid);
				sendusermessage($uid,"您的成功故事图片不符合图片要求，已被红娘删除，请您按要求进行操作","成功故事图片审核");
				//salert('成功删除故事图片！');
				$alert = '成功删除故事图片';
			}elseif($is_pass){
				$GLOBALS['_MooClass']['MooMySQL']->query("UPDATE {$GLOBALS['dbTablePre']}story_pic SET `syscheck`=1 WHERE `mid`='$id'");
				serverlog(3,$dbTablePre."story_pic",$GLOBALS['username']."通过用户的故事图片".$id,$GLOBALS['adminid'],$uid);
				sendusermessage($uid,"您的成功故事图片已经过红娘的审核，希望您和您的另一半白头偕老，同时也感谢您对红娘的支持","成功故事图片审核");
				//salert('审核成功故事图片成功');
				$alert = '审核成功故事图片成功';
			}
			$storydata_dir = MOOPHP_DATA_DIR.'/block/'.'story'.$adminid.'.data';
			$storyimages = array();
			if(file_exists($storydata_dir)) {
				$storyimages = unserialize(file_get_contents($storydata_dir));
			}

			$sotryimage = array_shift($storyimages);
			$_MooClass['MooCache']->setBlockCache('story'.$adminid, $storyimages);
			if($storyimages) {
				$url = 'index.php?action=check&h=storyimage&type=show&id='.$sotryimage['mid'];
			} else {
				$url = 'index.php?action=check&h=storyimage';
			}
			$storykey = $storyimages ? 1 : 0;
			setcookie('storyimages', $storykey);
			salert($alert,$url);
		break;	
	}
};
//note 会员证件
function check_paper(){
	$sid=$GLOBALS['adminid'];
	$type=MooGetGPC('type','string')=='' ? 'list' : MooGetGPC('type','string');
	$usersid=MooGetGPC('usersid','string','G');//makui
	$lei="会员证件审核";
	$uid = MooGetGPC('uid','integer');
	$id=MooGetGPC('id','integer');
	$checkArr = array(1=>'审核通过',0=>'审核未通过');
	$check=MooGetGPC('check','integer','G');
	switch($type){
		case 'list':
			$paper_type = MooGetGPC('paper_type','string');
			$type_as    = MooGetGPC('typeas','integer');
			//note 获得当前url
			$currenturl = "index.php?action=check&h=paper"; 
			$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
			$page = get_page();
			$prepage=20;
			$start=($page-1)*$prepage;
			//所管理的客服id列表
			$myservice_idlist = get_myservice_idlist();

			if(empty($myservice_idlist)){
				$sql_where .= " b.sid IN({$GLOBALS['adminid']}) AND ";
			}elseif($myservice_idlist == 'all'){
				if(isset($_GET['usersid'])){$sql_where=" b.sid='$usersid' AND";}//查看某一客服的会员
					//all为客服主管能查看所有的
			}else{
				$sql_where .= " b.sid IN($myservice_idlist) AND ";
				if(isset($_GET['usersid'])){$sql_where .=" b.sid='$usersid' AND";}//查看某一客服的会员
			}
			$n = 0;
			if($uid > 0){
				$sql_where .= " b.uid=".$uid." AND ";
				$n++;
			}
			if( !empty($paper_type) && $type_as > 0 ){
				$sql_where .= "a.".$paper_type."_check=".$type_as." AND ";
				$currenturl2 = "index.php?action=check&h=paper&paper_type=". $paper_type ."&typeas=".$type_as;
				$n++;
			}
			
			if($n == 0) {
				$sql_where .= "(a.identity_check = 2 or a.marriage_check = 2 or a.education_check = 2 or a.occupation_check = 2 or a.salary_check = 2 or a.house_check = 2 or a.video_check = 2) and ";
			}
			
			$total_count=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT count(1) as c FROM {$GLOBALS['dbTablePre']}certification AS a JOIN {$GLOBALS['dbTablePre']}members AS b WHERE {$sql_where} a.uid=b.uid");
			$total = $total_count['c'];
			$pageLists = multipage($total,$prepage,$page,$currenturl2);
			$list=$GLOBALS['_MooClass']['MooMySQL']->getAll("SELECT a.*,b.nickname,b.birthyear,b.sid,b.gender FROM {$GLOBALS['dbTablePre']}certification AS a JOIN {$GLOBALS['dbTablePre']}members AS b WHERE {$sql_where} b.uid=a.uid ORDER BY b.last_login_time DESC LIMIT $start,$prepage");  
		
			//echo ("SELECT a.*,b.nickname,b.birthyear,b.gender FROM {$GLOBALS['dbTablePre']}certification AS a JOIN {$GLOBALS['dbTablePre']}members AS b WHERE {$sql_where} a.uid=b.uid ORDER BY b.last_login_time DESC LIMIT $start,$prepage");
			require adminTemplate("check_certification");
		break;
		case 'show':
			$mintype=MooGetGPC('mintype','string');
			$paper=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT a.{$mintype} as mintype,b.birthyear,b.gender FROM {$GLOBALS['dbTablePre']}certification as a join {$GLOBALS['dbTablePre']}members as b WHERE a.uid=b.uid and a.uid='$uid'");
			serverlog(1,$dbTablePre.$GLOBALS['username']."certification","查询会员认证",$GLOBALS['adminid']);
			require adminTemplate("check_show");
		break;
		case 'submit':
			$is_pass=MooGetGPC('pass','string');
			$is_onpass=MooGetGPC('nopass','string');
			if($is_onpass){
				$mintype=MooGetGPC('mintype','string');
				$checktype=$mintype."_check";
				$paperimagessrc=MooGetGPC('paperimagessrc','string');
				$GLOBALS['_MooClass']['MooMySQL']->query("UPDATE {$GLOBALS['dbTablePre']}certification SET {$checktype}=4 WHERE `uid`='$uid'");
				if(MOOPHP_ALLOW_FASTDB){
					$value[$checktype]= 4;
					MooFastdbUpdate('certification','uid',$uid,$value);
					}
				serverlog(2,$dbTablePre."certification",$GLOBALS['username']."未通过用户的证件认证".$uid,$GLOBALS['adminid'],$uid);
				sendusermessage($uid,"您的证件图片不符合要求，请您按要求进行操作","证件审核");
				salert('证件审核不通过');
			}elseif($is_pass){
				$mintype=MooGetGPC('mintype','string');
				$checktype=$mintype."_check";
				$GLOBALS['_MooClass']['MooMySQL']->query("UPDATE {$GLOBALS['dbTablePre']}certification SET {$checktype}=3 WHERE `uid`='$uid'");
				if(MOOPHP_ALLOW_FASTDB){					
					$value[$checktype]= 3;
					MooFastdbUpdate('certification','uid',$uid,$value);
				}

				serverlog(3,$dbTablePre."certification",$GLOBALS['username']."通过用户的证件认证".$uid,$GLOBALS['adminid'],$uid);
				sendusermessage($uid,"您的证件图片已经过红娘的审核，您还可以继续上传您的其他证件照片，以提高您的诚信度","证件图片审核");
				//更新诚信值
				reset_integrity($uid);
				salert('审核证件图片成功');
				
			}
			echo "<script>window.history.go(-2);</script>";
		break;
	}
};
//note 举报受理
function check_report(){
	$sid=$GLOBALS['adminid'];
	$type=MooGetGPC('type','string')=='' ? 'list' : MooGetGPC('type','string');
	$usersid=MooGetGPC('usersid','string','G');//makui
	$lei="举报受理";
	$uid = MooGetGPC('uid','integer');
	$id=MooGetGPC('id','integer');
	$checkArr = array(1=>'审核通过',0=>'审核未通过');
	switch($type){
		case 'list':
			$pass = MooGetGPC('pass','integer','G');
			//note 获得当前url
			$currenturl = "index.php?action=check&h=report";
			$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
			$currenturl2 = preg_replace("/(&pass=\d+)/","",$currenturl2)."&pass=$pass";
			$currenturl2 = preg_replace("/(&usersid=\d+)/", '', $currenturl2);
			
			$page = get_page();
			$prepage=20;
			$start=($page-1)*$prepage;
			if(isset($_GET['usersid']) && MooGetGPC('usersid', 'string', 'G') == 0) {
				salert("无所属客服");
			}
			//所管理的客服id列表
			$myservice_idlist = get_myservice_idlist();

			if(empty($myservice_idlist)){
				$sql_where = " AND m.sid IN({$GLOBALS['adminid']})";
			}elseif($myservice_idlist == 'all'){
					//all为客服主管能查看所有的
					if(isset($_GET['usersid'])){$sql_where=" AND m.sid='$usersid'";}//查看某一客服的会员
			}else{
				$sql_where = " AND m.sid IN($myservice_idlist)";
				if(isset($_GET['usersid'])){$sql_where=" AND m.sid='$usersid' AND";}//查看某一客服的会员
			}
			$sql_where .= " AND r.is_disabled=".$pass;
			
			$total_count=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT count(1) as c FROM {$GLOBALS['dbTablePre']}report as r,{$GLOBALS['dbTablePre']}members as m WHERE r.uid=m.uid {$sql_where}");
			$total = $total_count['c'];
			$list=$GLOBALS['_MooClass']['MooMySQL']->getAll("SELECT *,m.sid,r.is_disabled as syscheck FROM {$GLOBALS['dbTablePre']}report  as r,{$GLOBALS['dbTablePre']}members as m WHERE r.uid=m.uid {$sql_where} ORDER BY r.addtime DESC LIMIT $start,$prepage"); 
			require adminTemplate("check_list");
		break;
		case 'show':
			$report=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT * FROM {$GLOBALS['dbTablePre']}report WHERE `id`='$id'");
			serverlog(1,$dbTablePre."",$GLOBALS['username']."查询会员举报",$GLOBALS['adminid']);
			require adminTemplate("check_report");
		break;
		case 'submit':
			$is_pass=MooGetGPC('pass','string');
			$is_onpass=MooGetGPC('nopass','string');
			if($is_onpass){
				
			}elseif($is_pass){
				
			}
		break;
	}
};
//note 意见反馈搜索
function check_feedback_s(){
	$lei = '意见反馈搜索';
	$sid=$GLOBALS['adminid'];
	$uid = MooGetGPC('s_uid','integer');
	$s_stat1 = MooGetGPC('s_stat1','integer');
	$s_stat2 = MooGetGPC('s_stat2','integer');
	$fraction_arr = array(
		1 => '非常不满意',
		2 => '不满意',
		3 => '一般',
		4 => '满意',
		5 => '非常满意'
	);
	$str_s = '';
	$where = '';
	$and   = '';
	if($s_stat1>0 || $s_stat2>0){
		$where = " WHERE";
		if($s_stat1>0){
			$s_stat1 -= 1;
			$where .= " g.stat1={$s_stat1} ";
			$str_s .= '&s_stat1='.($s_stat1+1);
			$and = ' AND ';
		}
		if($s_stat2>0){
			$where .= $and." g.stat2={$s_stat2} ";
			$str_s .= '&s_stat2='.$s_stat2;
		}
	}
	//所管理的客服id列表
	if(isset($_GET['usersid']) && MooGetGPC('usersid', 'string', 'G') == 0) {
		salert("无所属客服");
	}
	$myservice_idlist = get_myservice_idlist();
	if(empty($myservice_idlist)){
		$where_1 = " AND m.sid IN({$GLOBALS['adminid']})";
		if($where){
			$where .= " AND m.sid IN({$GLOBALS['adminid']}) ";
		}else{
			$where .= " WHERE m.sid IN({$GLOBALS['adminid']}) ";
		}
	}elseif($myservice_idlist == 'all'){
			//all为客服主管能查看所有的
	}else{
		$where_1 = " AND m.sid IN($myservice_idlist)";
		if($where){
			$where .= " AND m.sid IN($myservice_idlist) ";
		}else{
			$where .= " WHERE m.sid IN($myservice_idlist) ";
		}
	}

	// 系统
	$checkArr = array(1=>'已回复',0=>'未回复');
	$serArr = array(1=>'网站功能',2=>'红娘服务');
	$attArr = array(0=>'其他',1=>'表扬',2=>'批评',3=>'建议');
	//note 获得当前url
	$currenturl = "index.php?action=check&h=feedback_s";
	$currenturl2 = preg_replace("/(&page=\d+)|(&pass=\d+)|(&s_stat1=\d+)|(&h=[A-z]+)|(&s_stat2=\d+)/","",$currenturl);
	$currenturl2 .= "&h=feedback";
	$page = get_page();
	$prepage=20;
	$start=($page-1)*$prepage;
	
	$sql = "SELECT g.gid,g.uid,g.stat1,g.stat2,g.content,g.submitdate,g.syscheck, m.sid, g.fraction FROM {$GLOBALS['dbTablePre']}service_getadvice g
	       	LEFT JOIN {$GLOBALS['dbTablePre']}members m ON g.uid=m.uid
	       	{$where}
	        ORDER BY g.gid DESC LIMIT $start,$prepage";
	
	
	if($uid > 0){
		$sql = "SELECT g.gid,g.uid,g.stat1,g.stat2,g.content,g.submitdate,g.syscheck, g.fraction, m.sid FROM {$GLOBALS['dbTablePre']}service_getadvice g
	       		LEFT JOIN {$GLOBALS['dbTablePre']}members m ON g.uid=m.uid WHERE g.uid = {$uid} ".$wherer_1;
		$str_s = '&uid='.$uid;
	}else{
		$sql_t = "SELECT count(1) as c FROM {$GLOBALS['dbTablePre']}service_getadvice g
	        		LEFT JOIN {$GLOBALS['dbTablePre']}members m ON g.uid=m.uid
	        		$where";
		$total_re=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql_t);  
		$total = $total_re['c'];	
	}
	$advice=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	if($str_s){$currenturl2 .= $str_s;}
	require adminTemplate("check_advice_list");
}


//note 会员间评价审核
function check_comment(){//makui
	$sid=$GLOBALS['adminid'];
	$pass = MooGetGPC('pass','integer','G');
	$usersid=MooGetGPC('usersid','string','G');
	$type=MooGetGPC('type','string')=='' ? 'list' : MooGetGPC('type','string');
	$lei="会员间评价审核";
	$uid = MooGetGPC('uid','integer');
	$id=MooGetGPC('id','integer');
	$checkArr = array(1=>'审核通过',0=>'审核未通过','-1'=>'暂无');
	switch ($type){
        case 'list': 
			//note 获得当前url
			$currenturl = "index.php?action=check&h=comment";
			$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
			$currenturl2 = preg_replace("/(&field=(sid|lastvisit)&order=(desc|asc))/","",$currenturl2);
			$currenturl2 = preg_replace("/(&pass=\d+)/","",$currenturl2)."&pass=$pass";
			$currenturl2 = preg_replace("/(&usersid=\d+)/", '', $currenturl2);
			
			$page = get_page();
			$prepage=20;
			$start=($page-1)*$prepage;
			if(isset($_GET['usersid']) && MooGetGPC('usersid', 'string', 'G') == 0) {
				salert("无所属客服");
			}
			//所管理的客服id列表
			$myservice_idlist = get_myservice_idlist();
			if(empty($myservice_idlist)){
				$condition[] = "a.sid IN({$GLOBALS['adminid']}) ";
			}elseif($myservice_idlist == 'all'){
				if(isset($_GET['usersid'])){$condition[]=" sid='$usersid'";}
				
					//all为客服主管能查看所有的
			}else{
				$condition[] = "a.sid IN($myservice_idlist) ";
				if(isset($_GET['usersid'])){$condition[]=" sid='$usersid'";}
			}
			
			if($uid > 0){
				$condition[] = " a.uid=$uid";
			}else{
				if($pass == 0){
					$condition[] = " b.is_pass = '0' ";
				}else{
					$condition[] = " b.is_pass > '0' ";
				}
			}
			if(!empty($condition)){
				$sql_where = ' WHERE '.implode(' AND ',$condition);
			}
			$order = 'desc';
			if(in_array($_GET['field'],array('sid','lastvisit'))){
				$order_str = " a.".$_GET['field']." ".$_GET['order'].',';
				if($order == $_GET['order']){$order = 'asc';}
				$currenturl2 .= "&field={$_GET['field']}&order={$_GET['order']}";
			}
			
			$sql = ("SELECT count(1) as c FROM {$GLOBALS['dbTablePre']}members AS a LEFT JOIN {$GLOBALS['dbTablePre']}members_comment AS b ON a.uid=b.uid {$sql_where}");
			$total_count = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			$total = $total_count['c'];
			
			$sql = ("SELECT a.uid as uid,b.id as id,b.content,b.cuid,b.is_pass,a.nickname,a.birthyear,a.sid as sid,a.gender,b.dateline FROM {$GLOBALS['dbTablePre']}members AS a  LEFT JOIN {$GLOBALS['dbTablePre']}members_comment AS b ON a.uid=b.uid {$sql_where} ORDER BY {$order_str} a.uid ASC LIMIT $start,$prepage");
            $list=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
            //echo ("SELECT a.uid as uid,b.uid as id,b.introduce as syscheck,b.introduce_check,a.nickname,a.birthyear,a.gender,a.lastvisit FROM {$GLOBALS['dbTablePre']}members AS a JOIN {$GLOBALS['dbTablePre']}choice AS b WHERE {$sql_where} a.uid=b.uid ORDER BY b.introduce ASC,b.introduce_check DESC,a.lastvisit DESC LIMIT $start,$prepage");
			echo "<span style='display:none'>$sql</span>";
            require adminTemplate("check_list_comment");
        
        break;
        case 'show': 
        	$comment=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT a.content,a.cuid,b.birthyear,b.gender FROM {$GLOBALS['dbTablePre']}members_comment as a join {$GLOBALS['dbTablePre']}members as b WHERE a.uid=b.uid and a.id=".$id);
            serverlog(1,$dbTablePre."choice","{$GLOBALS['username']}查询会员{$uid}会员评价",$GLOBALS['adminid'],$uid);
            require adminTemplate("check_show");
        
        break;
        case 'submit' : 
			$pass = MooGetGPC('pass','integer','P');
			$ajax = MooGetGPC('ajax','integer','P');
			$content = MooGetGPC('content','string','P');
			if($ajax){	        	       			
				if($pass == 1){					
					$sql = "UPDATE {$GLOBALS['dbTablePre']}members_comment SET `is_pass`='1' WHERE `id`='$id'";
					sendusermessage($uid,"尊敬的红娘会员，您的会员评价已经过红娘的审核","会员评价审核");					
				}else{
					$sql = "UPDATE {$GLOBALS['dbTablePre']}members_comment SET `is_pass`='2' WHERE `id`='$id'";
					sendusermessage($uid,"尊敬的红娘会员，您的会员评价不符合要求，请按要求填写","会员评价审核");					
				}
				$GLOBALS['_MooClass']['MooMySQL']->query($sql);
				serverlog(3,$dbTablePre."members",$GLOBALS['username']."审核会员".$uid."的会员评价",$GLOBALS['adminid']);
				/*if($members['usertype']==1){
				//暂时屏蔽
					Push_message_intab($sendtoid,$members['telphone'],"评价","红娘网 用户ID：".$userid.",".$send_user_gender.",已给对您进行了评价，请速查看！4006780405(免长途)",$userid);
				}*/
				echo $pass;exit;
			}
			if($content){
				$sql = "UPDATE {$GLOBALS['dbTablePre']}members_comment SET `content`='". $content ."',`is_pass`='1' WHERE `id`='$id'";
	            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
               	sendusermessage($uid,"尊敬的红娘会员，您的会员评价已经过红娘的审核","会员评价审核");					
				$alert = '会员评价审核通过';
	           
	            serverlog(3,$dbTablePre."members",$GLOBALS['username']."审核会员".$uid."的会员评价",$GLOBALS['adminid']);
				salert($alert,'index.php?action=check&h=comment');
			}
			break;
	}
};

//批量审核评价
function check_content(){
	$uidlist = MooGetGPC('uidlist','string','G');
	if(empty($uidlist)){
		salert('请选择审核的会员ID','index.php?action=check&h=monolog');
	}
	$uid_arr = explode(',',$uidlist);

	foreach($uid_arr as $v){
		$sql = "UPDATE {$GLOBALS['dbTablePre']}members_comment SET `is_pass`='1' WHERE `id`='{$v}'";

		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		 
         
            sendusermessage($uid,"尊敬的红娘会员，您的会员评价已经过红娘的审核","内心独白审核");
			$alert = '会员评价审核通过';
         
        serverlog(3,$dbTablePre."members",$GLOBALS['username']."审核会员".$uid."的会员评价",$GLOBALS['adminid'],$v['uid']);
	}
	salert('审核成功','index.php?action=check&h=comment');
}


//note 意见反馈
function check_feedback(){
	global $adminid, $kefu_arr, $groupid;
	$sid=$GLOBALS['adminid'];
	$type=MooGetGPC('type','string')=='' ? 'list' : MooGetGPC('type','string');
	$usersid=MooGetGPC('usersid','string','G');//makui
	$lei="意见反馈";
	$id=MooGetGPC('id','integer');
	$gid=MooGetGPC('gid','integer');
	$uid=MooGetGPC('uid','integer');
	// 系统
	$checkArr = array(1=>'已回复',0=>'未回复');
	$serArr = array(1=>'网站功能',2=>'红娘服务');
	$attArr = array(0=>'其他',1=>'表扬',2=>'批评',3=>'建议');
	$fraction_arr = array(
		1 => '非常不满意',
		2 => '不满意',
		3 => '一般',
		4 => '满意',
		5 => '非常满意'
	);
	switch($type){
		case 'list':
			$pass = MooGetGPC('pass','integer','G');
			
			
			//note 获得当前url
			$currenturl = "index.php?action=check&h=feedback";
			$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
			$currenturl2 = preg_replace("/(&pass=\d+)/","",$currenturl2)."&pass=$pass";
			$currenturl2 = preg_replace("/(&usersid=\d+)/", '', $currenturl2);
			
			$page = get_page();
			$prepage=20;
			$start=($page-1)*$prepage;
			if(isset($_GET['usersid']) && MooGetGPC('usersid', 'string', 'G') == 0) {
				salert("无所属客服");
			}
	  	    //所管理的客服id列表
			$myservice_idlist = get_myservice_idlist();

			if(empty($myservice_idlist)){
				$sql_where = " WHERE m.sid IN({$GLOBALS['adminid']})";
			}elseif($myservice_idlist == 'all'){
					//all为客服主管能查看所有的
					if(isset($_GET['usersid'])){$sql_where="where m.sid='$usersid'";}//查看某一客服的会员
			}else{
				$sql_where = " WHERE m.sid IN($myservice_idlist)";
				if(isset($_GET['usersid'])){$sql_where .=" AND m.sid='$usersid'";}//查看某一客服的会员
			}
			if(empty($sql_where)){
				$sql_where .= " WHERE g.syscheck='$pass'";
			}else{
				$sql_where .= " AND g.syscheck='$pass'";
			}
			
			$s_stat1 = MooGetGPC('s_stat1','integer');
			$s_stat2 = MooGetGPC('s_stat2','integer');
			if($s_stat1>0 || $s_stat2>0){
				if($s_stat1>0){
					$s_stat1 -= 1;
					$sql_where .= " and g.stat1={$s_stat1} ";
				}
				if($s_stat2>0){
					$sql_where .= $and." and g.stat2={$s_stat2} ";
				}
			}
	        $sql = "SELECT count(1) as c FROM {$GLOBALS['dbTablePre']}service_getadvice g
	        		LEFT JOIN {$GLOBALS['dbTablePre']}members m ON g.uid=m.uid
	        		$sql_where";
			$total_re=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);  
			$total = $total_re['c'];
	        //serverlog(1,$dbTablePre."service_getadvice","查询意见反馈",$GLOBALS['adminid']);
	        $sql = "SELECT g.gid,g.uid,g.stat1,g.stat2,g.content,g.submitdate,g.syscheck,m.sid, g.fraction FROM {$GLOBALS['dbTablePre']}service_getadvice g
	       			LEFT JOIN {$GLOBALS['dbTablePre']}members m ON g.uid=m.uid
	       			$sql_where
	        		ORDER BY g.gid DESC LIMIT $start,$prepage";
	        $advice=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	        require adminTemplate("check_advice_list");
		break;
		case 'show':
        	$showadvice=$GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT s.*, m.sid FROM {$GLOBALS['dbTablePre']}service_getadvice s left join {$GLOBALS['dbTablePre']}members m on s.uid=m.uid WHERE s.`gid`='$gid'");
        	//serverlog(1,$dbTablePre."service_getadvice","查看意见反馈",$GLOBALS['adminid']);

			require adminTemplate("check_show");
		break;
		case 'submit':
        	$content=MooGetGPC('content','string');
			$is_pass=MooGetGPC('pass','string');
			$is_onpass=MooGetGPC('nopass','string');
			if($is_onpass){
				if($gid==''||$uid==''){salert('参数错误');}
                $GLOBALS['_MooClass']['MooMySQL']->query("DELETE FROM {$GLOBALS['dbTablePre']}service_getadvice WHERE `gid`='$gid'");
                salert('删除成功！','index.php?action=check&h=feedback');
			}elseif($is_pass){
				if($gid==''||$content==''||$uid==''){salert('参数错误');}
                $GLOBALS['_MooClass']['MooMySQL']->query("UPDATE {$GLOBALS['dbTablePre']}service_getadvice SET `syscheck`=1 WHERE `gid`='$gid'");
                sendusermessage($uid,$content,"回复意见反馈");
                serverlog(1,$dbTablePre."service_getadvice",$GLOBALS['username']."回复意见反馈".$gid,$GLOBALS['adminid']);
                salert('回复此信息成功','index.php?action=check&h=feedback');
			}
			//echo "<script>window.history.go(-2);</script>";
		break;
		
	}
};



function savePhoto($uid){
	$x1=MooGetGPC('x1','integer');
	$y1=MooGetGPC('y1','integer');
	$x2=MooGetGPC('x2','integer');
	$y2=MooGetGPC('y2','integer');
	$width=MooGetGPC('width','integer');
	$height=MooGetGPC('height','integer');
	$photoimage=MooGetGPC('photoimage','string');
	$first_dir=substr($uid,-1,1);
	$secend_dir=substr($uid,-2,1);
	$third_dir=substr($uid,-3,1);
	$forth_dir=substr($uid,-4,1);
	$userpath="../data/upload/userimg/".$first_dir."/".$secend_dir."/".$third_dir."/".$forth_dir."/";
	//$userpath="../data/upload/userimg/5/2/3/4/";
	//echo $userpath;

	if(!is_dir($userpath)){
		$array = explode('/', $userpath);
		$count = count($array);
		for($i = 0; $i < $count; $i++) {
				$msg .= $array[$i];
				if(!file_exists($msg)) {
					mkdir($msg, 0777);
				}
				$msg .= '/';
			}
	}
	//$newimages=resizeImage($photoimage,$width,$height,$x1,$y1,$uid);
	$sizearray=array(0=>array('width'=>320,'height'=>400),
					 1=>array('width'=>171,'height'=>212),
					 2=>array('width'=>100,'height'=>125),
					 3=>array('width'=>60,'height'=>75),
					 4=>array('width'=>50,'height'=>63),
					 5=>array('width'=>110,'height'=>138),
					 6=>array('width'=>100,'height'=>125)
					);
	$namearray=array(0=>'big',1=>'mid',2=>'medium',3=>'small',4=>'page',5=>'com',6=>'index');
	$newimages=changesize($photoimage,$userpath,$x1,$y1,$width,$height,$uid,$sizearray,$namearray);//生成另外三种形象照
	/*$bigphoto=MooGetphotoAdmin($uid,'big');

	$thuid=$uid*3;
	//$userpath="../data/upload/userimg/";
	$arr=explode('.',$bigphoto);
	$jpg=$arr[count($arr)-1];
	$index_name=$thuid.'_'.'index.'.$jpg;
	$com_name  =$thuid.'_'.'com.'.$jpg;
	$page_name =$thuid.'_'.'page.'.$jpg;
	list($width,$height)=getimagesize($bigphoto);
	$d=$width/$height;
	$c=100/125;//100*125
	if($d>$c){
		$g1_width=100;
		$b=$width/$g1_width;
		$g1_height=$height/$b;
	}else{
		$g1_height=125;
		$b=$height/$g1_height;
		$g1_width=$width/$b;
	}
	ImagickResizeImage($bigphoto,$userpath.$index_name,$g1_width,$g1_height);
	$c=50/63;//100*125
	if($d>$c){
		$g2_width=50;
		$b=$width/$g2_width;
		$g2_height=$height/$b;
	}else{
		$g2_height=63;
		$b=$height/$g2_height;
		$g2_width=$width/$b;
	}
	ImagickResizeImage($bigphoto,$userpath.$page_name,$g2_width,$g2_height);
	$c=110/138;//100*125
	if($d>$c){
		$g3_width=110;
		$b=$width/$g3_width;
		$g3_height=$height/$b;
	}else{
		$g3_height=138;
		$b=$height/$g3_height;
		$g3_width=$width/$b;
	}
	ImagickResizeImage($bigphoto,$userpath.$com_name,$g3_width,$g3_height);	*/
	$sql = "UPDATE {$GLOBALS['dbTablePre']}members SET `images_ischeck`=1 WHERE `uid`='$uid'";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	if(MOOPHP_ALLOW_FASTDB) {		
		$value['images_ischeck']= 1;
		MooFastdbUpdate("members",'uid',$uid,$value);
		}
	$image_type_arr=array('mid','medium');
	/*foreach($image_type_arr as $image_type){
		$image_file=substr(MooGetphotoAdmin($uid,$image_type),3);		
		@file_get_contents("http://control.wjmax.com/cdn/services/rsPushService/addRefreshUrl?userName=zikou&password=L0ciFer&hasEncrypt=false&pushUrl=http://www.7651.com/".$image_file);
	}*/
	sendusermessage($uid,"您的形象照已经通过红娘的审核","形象照审核");
	serverlog(3,$dbTablePre."members",$GLOBALS['username']."审核会员".$uid."的形象照成功",$GLOBALS['adminid'],$uid);	
}

function check_imagick_rotate(){
	
	$pic_path = MooGetGPC('pic_path','string','G');

	$id = MooGetGPC('id','integer','G');
	$uid = MooGetGPC('uid','integer','G');
	$degrees = 90;
	$file = explode('.',$pic_path);
//	var_dump($file);exit;
	$new_file = '../'.$file[0].'.'.$file[1];
	$pic_path = '../'.$pic_path;
	$source = imagecreatefromjpeg($pic_path);
	$rotate = imagerotate($source, $degrees, 0);
//	var_dump($pic_path);
	

	  
	list($imagewidth, $imageheight, $imageType) = getimagesize($pic_path);
	$imageType = image_type_to_mime_type($imageType);

	switch($imageType) {
		case "image/gif":
			imagegif($rotate,$new_file,100);
			break;
		case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			imagejpeg($rotate,$new_file,100);
			break;
		case "image/png":
		case "image/x-png":
			imagepng($rotate,$new_file,100);
			break;
		case "image/bmp":
			imagebmp($rotate,$new_file,100);
			break;
	}
//var_dump($new_file);exit;
	header('location:index.php?action=check&h=photo&type=show&id=100004&uid=100004');
}

//批量审核内心独白
function check_introduce(){
	$uidlist = MooGetGPC('uidlist','string','G');
	if(empty($uidlist)){
		salert('请选择审核的会员ID','index.php?action=check&h=monolog');
	}
	$uid_arr = explode(',',$uidlist);

	foreach($uid_arr as $v){
		$sql = "UPDATE {$GLOBALS['dbTablePre']}choice SET `introduce`=`introduce_check`,`introduce_check`='',`introduce_pass`='1' WHERE `uid`='{$v}'";

		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		 if(MOOPHP_ALLOW_FASTDB){
			$old_introduce=MooFastdbGet("choice",'uid',$v);
			$value['introduce'] = $old_introduce['introduce_check'];
			$value['introduce_chack']='';
			$value['introduce_pass']=1;
			MooFastdbUpdate("choice",'uid',$uid,$value);
		}
        sendusermessage($uid,"尊敬的红娘会员，您的内心独白已经过红娘的审核","内心独白审核");
		$alert = '内心独白审核通过';         
        serverlog(3,$dbTablePre."choice",$GLOBALS['username']."批量审核会员".$uid."的内心独白",$GLOBALS['adminid'],$v['uid']);
	}
	salert('审核成功','index.php?action=check&h=monolog');
}


//视频审核
function check_video(){
	$uid = MooGetGPC('uid','integer','P');
	$usersid = MooGetGPC('usersid','string','G');
	$pass = MooGetGPC('pass','integer') ? MooGetGPC('pass','integer') : 0;

	if(empty($pass)){
		$sql_where = 'where b.toshoot_video_check=1';
	}else{
		$sql_where = 'where b.toshoot_video_check=2';	
	}
	
	if(!empty($uid)){
		$sql_where.=" and b.uid='{$uid}'";
	}
	
	if(isset($usersid) && $usersid != ''){
		$sql_where.=" and a.sid='{$usersid}'";
	}
	
	$sql = "select count(*) as c from {$GLOBALS['dbTablePre']}certification b left join {$GLOBALS['dbTablePre']}members a on a.uid=b.uid {$sql_where}";
	$count = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	//分页
	$page_per = 20;
    $page = get_page();
    $limit = 20;
	$total = $count['c']; 
	$offset = ($page-1)*$limit;
	$myurl = explode('|',getUrl());
	$page_links = multipage($total,$page_per,$page,$myurl[1]);
	$sql = "select a.uid,a.nickname,a.gender,a.birthyear,a.sid,a.lastvisit,b.toshoot_video_check,b.toshoot_video_time from {$GLOBALS['dbTablePre']}certification b left join {$GLOBALS['dbTablePre']}members a on a.uid=b.uid {$sql_where} limit {$offset},20";
	$check_video = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	require_once(adminTemplate('check_video'));
}

//查看会员的视频并审核
function check_member_video(){
	$uid =  MooGetGPC('uid','string');
	$sql = "select a.birthyear,a.gender,b.uid,b.toshoot_video_url,b.toshoot_video_check from {$GLOBALS['dbTablePre']}certification b left join {$GLOBALS['dbTablePre']}members a on a.uid=b.uid where b.uid={$uid}";
	$member_video = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$value = array();
	//视频存储路径
	$path_url = strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/'))).'://'.$_SERVER['HTTP_HOST'];
	$cs_path = videoPathEncrypt($path_url.'/'.$member_video['toshoot_video_url'].'/mov_'.$uid.'.flv');
	if($_POST){
		$is_pass=MooGetGPC('pass','string');
		$is_onpass=MooGetGPC('nopass','string');
		if($is_onpass){
			if($member_video['toshoot_video_check'] == 2){
				$sql = "update {$GLOBALS['dbTablePre']}certification set toshoot_video_check='1',video_check='2' where uid={$uid}";
				$GLOBALS['_MooClass']['MooMySQL']->query($sql);	
			}
			if(MOOPHP_ALLOW_FASTDB){
				$value['toshoot_video_check']=1;
				$value['video_check']=2;
				MooFastdbUpdate('certification','uid',$uid,$value);
			}
			sendusermessage($uid,"尊敬的红娘会员，您的视频不符合要求，请重新录制视频！","视频审核");
			echo "<script>alert('审核不通过！')</script>";exit;
		}elseif($is_pass){
			$sql = "update {$GLOBALS['dbTablePre']}certification set toshoot_video_check='2',video_check='3' where uid={$uid}";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			if(MOOPHP_ALLOW_FASTDB){
				$value['toshoot_video_check']=2;
				$value['video_check']=3;
				MooFastdbUpdate('certification','uid',$uid,$value);
			}
            sendusermessage($uid,"尊敬的红娘会员，您的视频已经通过红娘的审核！","视频审核");
			echo "<script>alert('审核通过!');window.history.go(-1);</script>";exit;
		}
	}
	
	require_once(adminTemplate('check_member_video'));
}

//审核录音
function check_voice(){
	$uid = MooGetGPC('uid','integer','P');
	$usersid = MooGetGPC('usersid','string','G');
	$pass = MooGetGPC('pass','integer') ? MooGetGPC('pass','integer') : 0;

	if(empty($pass)){
		$sql_where = 'where b.toshoot_voice_check=1';
	}else{
		$sql_where = 'where b.toshoot_voice_check=2';	
	}
	
	if(!empty($uid)){
		$sql_where.=" and b.uid='{$uid}'";
	}
	
	if(isset($usersid) && $usersid != ''){
		$sql_where.=" and a.sid='{$usersid}'";
	}
	
	$sql = "select count(*) as c from {$GLOBALS['dbTablePre']}certification b left join {$GLOBALS['dbTablePre']}members a on a.uid=b.uid {$sql_where}";
	$count = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	//分页
	$page_per = 20;
    $page = get_page();
    $limit = 20;
	$total = $count['c']; 
	$offset = ($page-1)*$limit;
	$myurl = explode('|',getUrl());
	$page_links = multipage($total,$page_per,$page,$myurl[1]);
	$sql = "select a.uid,a.nickname,a.gender,a.birthyear,a.sid,a.lastvisit,b.toshoot_voice_check,b.toshoot_voice_time from {$GLOBALS['dbTablePre']}certification b left join {$GLOBALS['dbTablePre']}members a on a.uid=b.uid {$sql_where} limit {$offset},20";
	$check_voice = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	require_once(adminTemplate('check_voice'));
}

//查看会员的录音并审核
function check_member_voice(){
	$uid =  MooGetGPC('uid','string');
	$sql = "select a.birthyear,a.gender,b.uid,b.toshoot_voice_check,b.toshoot_voice_url from {$GLOBALS['dbTablePre']}certification b left join {$GLOBALS['dbTablePre']}members a on a.uid=b.uid where b.uid={$uid}";
	$member_voice = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	$value = array();
	//录音存储路径
	$path_url = strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/'))).'://'.$_SERVER['HTTP_HOST'];
	$voice_path = $path_url.'/'.$member_voice['toshoot_voice_url'].'/voi_'.$uid.'.flv';
	if($_POST){
		$is_pass=MooGetGPC('pass','string');
		$is_onpass=MooGetGPC('nopass','string');
		if($is_onpass){
			if($member_voice['toshoot_voice_check'] == 2){
				$sql = "update {$GLOBALS['dbTablePre']}certification set toshoot_voice_check='1' where uid={$uid}";
				$GLOBALS['_MooClass']['MooMySQL']->query($sql);	
			}
			if(MOOPHP_ALLOW_FASTDB){
				$value['toshoot_video_check']=1;				
				MooFastdbUpdate('certification','uid',$uid,$value);
			}
			sendusermessage($uid,"尊敬的红娘会员，您的录音不符合要求，请重新录音！","录音审核");
			echo "<script>alert('审核不通过！')</script>";exit;
		}elseif($is_pass){
			$sql = "update {$GLOBALS['dbTablePre']}certification set toshoot_voice_check='2' where uid={$uid}";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			if(MOOPHP_ALLOW_FASTDB){
				$value['toshoot_video_check']=2;
				MooFastdbUpdate('certification','uid',$uid,$value);
			}
			sendusermessage($uid,"尊敬的红娘会员，您的录音已经通过红娘的审核！","录音审核");
			echo "<script>alert('审核通过!');window.history.go(-1);</script>";exit;
		}
	}
	require_once(adminTemplate('check_member_voice'));
}

//获取路径
function getUrl(){
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl = preg_replace("/(&orderlogintime=(desc|asc))/","",$currenturl2);
	$currenturl = preg_replace("/(&orderreal_lastvisit=(desc|asc))/","",$currenturl);
	return $currenturl.'|'.$currenturl2;
}
/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string')=='' ? 'letter' : MooGetGPC('h','string');
//note 动作列表
$hlist = array('letter','photo','monolog','image','school','story','storyfirst','storyimage','paper','report','feedback','feedback_s','imagick_rotate','check_introduce','comment','check_content','video','member_video','voice','member_voice');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    salert('您要打开的页面不存在');exit;
}
//note 判断是否有权限
if(!checkGroup('check',$h)){
//	salert('您没有此审核操作的权限');exit;
}
//note Control Case:
switch($h){
    //note 站内信
    case 'letter':
    	check_letter();
   	break;
   	//note 形象照
    case 'photo':
    	check_photo();
    break;
    //note 内心独白
    case 'monolog':
    	check_monolog();
    break;
    //note 相传图片
    case 'image':
    	check_image();
    break;
    //note 毕业院校
    case 'school':
    	check_school();
    break;
    //note 成功故事
    case 'story':
    	check_story();
    break;
    //note 故事封面图
    case 'storyfirst':
    	check_storyfirst();
    break;
    //note 故事图片
    case 'storyimage':
    	check_storyimage();
    break;
    //note 会员证件
    case 'paper':
    	check_paper();
    break;
    //note 举报受理
    case 'report':
    	check_report();
    break;
	//note 会员评价审核
    case 'comment':
		check_comment();
	break;
    //note 意见反馈s
    case 'feedback':
    	check_feedback();
    break;
	//note 意见反馈搜索
	case 'feedback_s':
		check_feedback_s();
	break;
	case 'imagick_rotate':
		check_imagick_rotate();
	break;
	case 'check_introduce':
		check_introduce();
	break;
	case 'check_content':
		check_content();
	break;
	//视频审核
	case 'video':
		check_video();
	break;
	//查看会员的视频并审核
	case 'member_video':
		check_member_video();
	break;
	//录音审核
	case 'voice':
		check_voice();
	break;
	//查看会员的录音并审核
	case 'member_voice':
		check_member_voice();
	break;	
}
?>