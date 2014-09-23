<?php

/**
 *  会员交接表统计
 *  
 */
function get_joinlist() {
	$db = $GLOBALS['_MooClass']['MooMySQL'];
	$adminid = $GLOBALS['adminid'];
	$dbpre = $GLOBALS['dbTablePre'];
	$page_per = 12;
		
	$gid = MooGetGPC('manageid', 'integer', 'G');
	$sid = MooGetGPC('kefuid', 'integer', 'G');
	$mode = MooGetGPC('mode', 'integer', 'G');
	$period = MooGetGPC('period', 'integer', 'G');
	$startdate = MooGetGPC('startdate', 'string', 'G');
	$enddate = MooGetGPC('enddate', 'string', 'G');
	$choose = MooGetGPC('choose', 'string', 'G');
	$keyword = trim(MooGetGPC('keyword', 'string', 'G'));

	$modes = array(1 => '全权', 2 => '全权1+1', 3 => '炫服务', 4 => '信息刺激', 5 => '本站牵线');
	$periods = array(1 => '一个月', 2 => '三个月', 3 => '五个月', 4 =>'六个月');
	
	$condition = array();
	
	foreach (array('mode', 'period', 'gid', 'sid') as $v) {
		if ($$v >= 1) { $condition[] = $v . "=" . $$v; }
	}
	if (!empty($startdate)) {
		$condition[] = "bgtime>='" . strtotime($startdate) . "'";
	}
	if (!empty($enddate)) {
		$condition[] = "bgtime<='" . strtotime($enddate) . "'";
	}
	if (!empty($choose) && $keyword !== '') {
		if ($choose == 'username') {
			$condition[] = "$choose like '%$keyword%'";
		} else {
			$condition[] = "$choose='$keyword'";
		}
	}

	$sql_condition = implode(' and ', $condition);
	if (!$sql_condition) {
		$sql_condition = 'true';
	}
	
	$a = $db->getOne("select count(id) as count from {$dbpre}admin_join where {$sql_condition}");
	$total = $a['count'];
	
	$page_num = ceil($total / $page_per);	
	$page = max(1, min(MooGetGPC('page', 'integer'), $page_num));
	$offset = ($page - 1) * $page_per;
		
	$sql = "select * from {$dbpre}admin_join where {$sql_condition} limit {$offset}, {$page_per}";
	$join_list = $db->getAll($sql);
	
	if (!isset($_GET['ajax'])) {
		$manage_list = $db->getAll("select id, manage_name, manage_list from {$dbpre}admin_manage where type=1 order by manage_name");
		$server_list = $db->getAll("select uid, username from {$dbpre}admin_user");

		foreach ($server_list as $v) {
			$server_arr[$v['uid']] = $v['username'];
		}
		$manageids = '{';
		$founded = false;
		foreach ($manage_list as $v) {
			$single_manage_arr = explode(',', $v['manage_list']);
			$manageids .= $v['id'] . ':{';
			foreach ($single_manage_arr as $v2) {
				$manageids .= $v2 . ':"' . $server_arr[$v2] . '",';
			}
			$manageids = substr($manageids, 0, -1) . '},';
			if (!$founded && in_array($sid, $single_manage_arr)) {
				$founded = true;
				$manageid = $v['id'];
				$current_group_kefu_list = $single_manage_arr;
			}
		}
		$manageids = substr($manageids, 0, -1) . '}';		
	}
	
	$currenturl = "index.php?action=financial_joinstatistics&ajax=&startdate={$startdate}&enddate={$enddate}&choose={$choose}&keyword={$keyword}&mode={$mode}&period={$period}&manageid={$gid}&kefuid={$sid}";

	$pages = multipage($total, $page_per, $page, '#currenturl#');

	$pages = preg_replace('/#currenturl#\?page=(\d*)/', 'javascript:gotoPage($1)', $pages);

	
	require_once(adminTemplate('financial_joinstatistics'));
	exit;
}

//===========================控制层==================================================

$h = MooGetGPC('h', 'string', 'G');

switch ($h) {
	//note 已支付列表
	case 'list':
		get_joinlist();
		break;
	default:
		get_joinlist();
		break;
}
?>
