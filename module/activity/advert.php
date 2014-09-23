<?php
/*
 * Created on 8/14/2009
 *
 * Tianyong
 *
 * module/service/liker.php
 */
//note 获得我访问过谁
function iadvertwho() {
	global $_MooClass,$dbTablePre,$userid,$timestamp,$user_arr;
	$pagesize = 4;
	//note 获取删除提交的变量
	$delvisitor = MooGetGPC('delvisitor','string');
	$delvisitorid = MooGetGPC('delvisitorid','array');
	
	//note 删除提交的数据
	if($delvisitor && count($delvisitorid)) {
		/*
		foreach($delvisitorid as $v) {
			$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}service_visitor WHERE vid = '$v'");
		}*/
		for($i=0;$i<count($delvisitorid);$i++){
		      $result=$_MooClass['MooMySQL']->getOne("SELECT `who_del` FROM `". $dbTablePre ."service_visitor` WHERE `vid`=".$delvisitorid[$i],true);
		      if($result['who_del']!=0){$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}service_visitor WHERE vid = '$delvisitorid[$i]'");}else{$_MooClass['MooMySQL']->query("UPDATE `". $dbTablePre ."service_visitor` SET `who_del`=1 WHERE `vid`=".$delvisitorid[$i]);}
		}
		//for(){
		 //     
		//}
		//$result=$_MooClass['MooMySQL']->query("SELECT `who_del` FROM `". $dbTablePre ."service_visitor` WHERE `vid` IN ".$whereid);
		//$_MooClass['MooMySQL']->query("UPDATE {$dbTablePre}service_visitor SET who_del=1 WHERE vid IN $whereid");
		
		MooMessage("删除成功",'index.php?n=service&h=mindme&t=iadvertwho','05');
	}
	
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delvisitor && !count($delvisitorid)) {
		MooMessage('请选择要删除选项','index.php?n=service&h=mindme&t=iadvertwho','01');
		exit;
	}
	
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
		
	//note 获得第几页
	$page = empty($_GET['page']) ?  '1' : $_GET['page'];
		
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;
	
	//note 已收到多少个人统计总数
	$query = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}service_visitor WHERE uid = '$userid' AND `who_del`!=1",true);
	$total = $query['num'];
	$towhoArr = $_MooClass['MooMySQL']->getOne("SELECT count(*) FROM {$dbTablePre}service_visitor WHERE visitorid = '$userid' AND `who_del`!=2",true);
	$towho = $towhoArr['count(*)'];
	if($towho > 200){
		$towho = 200;
	}
	//note 查询出我放过谁相关信息
	if($total){
		$visitor  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_visitor WHERE uid = '$userid' AND who_del !=1 order by visitortime desc LIMIT $start,$pagesize");
	}		
	require MooTemplate('public/service_visitor_iadvertwho', 'module');
}

//note 获得最近访问我的人
function getlatestvisitor() {
	global $_MooClass,$dbTablePre,$userid,$timestamp,$user_arr;
	$pagesize = 4;
	//note 获取删除提交的变量
	$delvisitor = MooGetGPC('delvisitor','string');
	$delvisitorid = MooGetGPC('delvisitorid','array');
	
	//取得会员的认证信息
	//$in_ask=$_MooClass['MooMySQL']->getOne("select count(*)as c from {$dbTablePre}certification where uid='$userid' and email!='' and telphone!='' ");
	//echo "select count(*)as c from {$dbTablePre}certification where uid='$userid' and email!='' and telphone!='' ";
	//$certificate=$in_ask[c];
	//echo $certificate;
	//note 删除提交的数据
	if($delvisitor && count($delvisitorid)) {
		/*
		foreach($delvisitorid as $v) {
			$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}service_visitor WHERE vid = '$v'");
		}*/
		for($i=0;$i<count($delvisitorid);$i++){
		      $result=$_MooClass['MooMySQL']->getOne("SELECT `who_del` FROM `". $dbTablePre ."service_visitor` WHERE `vid`=".$delvisitorid[$i],true);
		      if($result['who_del']!=0){$_MooClass['MooMySQL']->query("DELETE FROM {$dbTablePre}service_visitor WHERE vid = '$delvisitorid[$i]'");}else{$_MooClass['MooMySQL']->query("UPDATE `". $dbTablePre ."service_visitor` SET `who_del`=2 WHERE `vid`=".$delvisitorid[$i]);}
		}
		MooMessage("删除成功",'index.php?n=service&h=mindme','05');
	}
	
	//note 如果提交的数据不存在，或者是提交的时候没有选中
	if($delvisitor && !count($delvisitorid)) {
		MooMessage('请选择要删除选项','index.php?n=service&h=mindme','01');
		exit;
	}
	
	
	//note 获得当前url
	$currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl2 = preg_replace("/(&page=\d+)/","",$currenturl);
		
	//note 获得第几页
	$page = empty($_GET['page']) ?  '1' : $_GET['page'];
		
	//note limit查询开始位置
	$start = ($page - 1) * $pagesize;

	//note 多少个人访问统计总数
	$ret_c = $_MooClass['MooMySQL']->getOne("SELECT count(*) as c FROM {$dbTablePre}service_visitor WHERE visitorid = '$userid' AND who_del !=2");
	$total = $ret_c['c'];
	if($total > 200) $total = 200;//更改最近留意我的人最我显示200个
	
	$tomeArr = $_MooClass['MooMySQL']->getOne("SELECT count(*) FROM {$dbTablePre}service_visitor WHERE uid = '$userid' AND who_del !=1");
	$tome = $tomeArr['count(*)'];
	//note 查询出谁访问过我相关信息
	if($total){
		$visitor  = $_MooClass['MooMySQL']->getAll("SELECT * FROM {$dbTablePre}service_visitor WHERE visitorid = '$userid' AND who_del !=2 order by visitortime desc LIMIT $start,$pagesize");
	}
	require MooTemplate('public/service_visitor_latestvisitor', 'module');
}

//控制部分
switch ($_GET['t']) {
	case "iadvertwho" :
		iadvertwho();
		break;
	case "whoadverti" :
		getlatestvisitor();
		break;

	default :
		getlatestvisitor();
}

