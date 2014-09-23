<?php

/**
 *  安徽卫视周日我最大 活动 
 *  2011-07-02
 */
function ahtvtj() {
	global $_MooClass;
	$adminid = $GLOBALS['adminid'];
	$dbpre = $GLOBALS['dbTablePre'];
	$page_per = 15;
	
	$channel = MooGetGPC('channel', 'integer', 'G');
	$startdate = MooGetGPC('startdate', 'string', 'G');
	$enddate = MooGetGPC('enddate', 'string', 'G');
	$choose = MooGetGPC('choose', 'string', 'G');
	$keyword = trim(MooGetGPC('keyword', 'string', 'G'));
	$notsite = MooGetGPC('notsite', 'string', 'G');
	$attenduser = MooGetGPC('attenduser', 'string', 'G');

//	   $channel_names = array(1 => '周日我最大', 2 => '幸福来敲门', 3 => '相亲齐上阵', 4 => '七夕活动');
	$channels = $_MooClass['MooMySQL']->getAll("select id, title from {$dbpre}activity");
	foreach ($channels as $v) {
		if ($v['id'] == 0)
			continue;
		$channel_names[$v['id']] = $v['title'];
	}

	$condition = array();
	if ($notsite) {
		$condition[] = "a.uid=0";
	}
	if ($attenduser) {
		$condition[] = "a.isattend=1";
	} // else $condition[] = "a.isattend=0";
	if ($channel >= 1) {
		$condition[] = "a.channel=" . $channel;
	}
	if (!empty($startdate)) {
		$condition[] = "a.regtime>='" . $startdate . "'";
	}
	if (!empty($enddate)) {
		$condition[] = "a.regtime<='" . $enddate . "'";
	}

	if (!empty($choose) && $keyword !== '') {
		if ($choose == 'username') {
			$condition[] = "a.$choose like '%$keyword%'";
		} else {
			$condition[] = "a.$choose='$keyword'";
		}
	}
	$select = "select a.id, a.uid, a.username, a.gender, a.birthday, a.province, a.city, a.mobile, a.regtime, a.channel, a.isattend";
	$from = "from {$dbpre}ahtv_reguser a left join {$dbpre}members_search b on a.uid=b.uid";
	$sql_condition = implode(' and ', $condition);
	if (!$sql_condition) {
		$sql_condition = 'true';
	}

	//得到所属客服下的会员
	$myservice_idlist = get_myservice_idlist();
	$myservice_idlist = empty($myservice_idlist) ? 'all' : $myservice_idlist;
	//计算总记录数
	//note 所管理的用户列表

	if (empty($myservice_idlist)) {
		$sql = "select count(id) as count {$from} where {$sql_condition} and b.sid={$adminid} order by id";
	} elseif ($myservice_idlist == 'all') {
		//all为客服主管能查看所有的
		$sql = "select count(id) as count from {$dbpre}ahtv_reguser a where {$sql_condition} order by id";
	} else {
		$sql = "select count(id) as count {$from} where {$sql_condition} and b.sid in ({$myservice_idlist}) order by id";
	}

	$a = $_MooClass['MooMySQL']->getOne($sql);
	$total = $a['count'];
	
	$currenturl = "index.php?action=financial_ahtv_reguser&h=list&channel={$channel}&startdate={$startdate}&enddate={$enddate}&choose={$choose}&keyword={$keyword}&notsite={$notsite}&attenduser={$attenduser}";

	$page_num = ceil($total / $page_per);	
	$page = max(1, min(MooGetGPC('page', 'integer'), $page_num));
	$offset = ($page - 1) * $page_per;
	$pages = multipage($total, $page_per, $page, $currenturl);
	

	$limit = "limit $offset, $page_per";
	//详细记录查询
	if (empty($myservice_idlist)) {
		$sql = $select . " " . $from . " where b.sid={$adminid} and {$sql_condition} {$limit}";
	} elseif ($myservice_idlist == 'all') {
		//all为客服主管能查看所有的
		$sql = $select . " from {$dbpre}ahtv_reguser a where {$sql_condition} {$limit}";
	} else {
		$sql = $select . " " . $from . " where b.sid in ({$myservice_idlist}) and {$sql_condition} {$limit}";
	}

	$reguser = $_MooClass['MooMySQL']->getAll($sql);

	foreach ($reguser as &$value) {
		$birthday_arr = explode('-', $value['birthday']);
		$birthday = $birthday_arr[0] . '年';
		if (!empty($birthday_arr[1])) {
			$birthday .= $birthday_arr[1] . '月';
		}
		$value['birthday'] = $birthday;
	}

	require_once(adminTemplate('financial_ahtv_reguser'));
}

//===========================控制层==================================================

$h = MooGetGPC('h', 'string', 'G');

switch ($h) {
	//note 已支付列表
	case 'list':
		ahtvtj();
		break;

	default:
		ahtvtj();
		break;
}
?>
