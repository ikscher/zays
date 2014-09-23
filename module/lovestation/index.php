<?php
/**
 * 疯狂粉丝会
 */
function crazyfan() {
	$tb_pre = $GLOBALS['dbTablePre'];
	$memcache = $GLOBALS['memcached'];
	$db = $GLOBALS['_MooClass']['MooMySQL'];
	$year = (int)date('Y');
	$month = (int)date('m');
	$fullyear = $year * 12 + $month;
	$history_month = 6; // 得到$history_month个月历史记录
	
	// 得到推荐会员
	$user_list = $db->getAll("select uid, score, gender, nickname from {$tb_pre}carcrazyfan where sort > 0");
	foreach ($user_list as $user) {
		$uid = $user['uid'];
		$score = unserialize($user['score']);
        $cur_score = $score[$year][$month];
        if ($cur_score === null) {
            $cur_score = 0;
            $score[$year][$month] = 0;
            $score = addslashes(serialize($score));
            $db->query("update {$tb_pre}carcrazyfan set score='$score' where uid=$uid");
        }
		$fans_list[$user['gender']][$uid] = $cur_score;
		$photo_list[$uid] = MooGetphoto($uid);
	}
	arsort($fans_list[0]); // 得分最高排在最前
	arsort($fans_list[1]);
	
	// 历届月度冠军 memcache
	$needupdate = false;
	$mem_month = $memcache->get('lovestation_history_month');
	if ($mem_month != $fullyear) { $memcache->set('lovestation_history_month', $fullyear, 0, time() + 86400*31); }
	$history_score = unserialize($memcache->get('lovestation_history_score'));
	// 去除超过$history_month个月的记录
	if (is_array($history_score)) {
		foreach ($history_score as $key => $value) {
			if ($key < $fullyear - $history_month) {
				unset($history_score[$key]);
			}
		}
	}
	if ($mem_month != $fullyear || !$history_score) { // 需要更新memcache
		$all_user_list = $db->getAll("select uid, score, gender from {$tb_pre}carcrazyfan");
		for ($i = 0; $i < $history_month; ++$i) {
			$pre_fullyear = $fullyear - $history_month + $i;
			if (!isset($history_score[$pre_fullyear])) {
				$needupdate = true;
				$max_score = array(0 => array(0, 0), 1 => array(0, 0)); // 最高票数为0认为当月没有数据
				foreach ($all_user_list as $user) {
					$gender = $user['gender'];
					$pre_year = floor($pre_fullyear / 12);
					$pre_month  = $pre_fullyear % 12;
					if ($pre_month == 0) { // eg: 2012年12月
						$pre_year--;
						$pre_month = 12;
					}
					
					$score = unserialize($user['score']);
					$pre_score = $score[$pre_year][$pre_month]; // 对应历史年月的得分
					if ($pre_score === null) { $pre_score = 0; }
					// 得到分数最大的会员
					if ($pre_score > $max_score[$gender][1]) {
						$max_score[$gender] = array($user['uid'], $pre_score);
					}
				}
				$history_score[$pre_fullyear] = array($max_score[0][0], $max_score[1][0]); // 历届月度冠军 array(男，女)
			}
		}
		if ($needupdate) {
			$memcache->set('lovestation_history_score', serialize($history_score), 0, time() + 86400 * 31);
		}
	}
	
	require MooTemplate('public/lovestation_crazyfan', 'module');
}

/**
 * 用户投票 
 */
function vote() {
	global $userid;
	$tb_pre = $GLOBALS['dbTablePre'];
	$db = $GLOBALS['_MooClass']['MooMySQL'];
	$year = (int)date('Y');
	$month = (int)date('m');
	$day = (int)date('d');
	$max_daily_vote = 100; // 每天最大投票数量
	
	$uid = MooGetGPC('uid', 'integer', 'G');
	$mode = MooGetGPC('mode', 'string', 'G');
	$vote = $mode == 'flower' ? 1 : ($mode == 'egg' ? -1 : 0);

	if(!$userid) { exit("gotourl:index.php?n=login&back_url=".urlencode('index.php?n=lovestation&h=crazyfan'));}
	if ($uid == $userid) { exit('不能给自己投票！'); }
	
	if ($vote != 0) {
		$user = $db->getOne("select score from {$tb_pre}carcrazyfan where uid=$uid");
		$score = unserialize($user['score']);
		$cur_score = $score[$year][$month] + $vote;
		if ($cur_score >= 0) {
			$count = MooGetGPC(MOOPHP_COOKIE_PRE . 'crazyfan_vote_num', 'integer', 'C');
			$pre_day = MooGetGPC(MOOPHP_COOKIE_PRE . 'crazyfan_vote_day', 'integer', 'C');
			if ($pre_day != $day) {
				$count = 0;
				MooSetCookie('crazyfan_vote_day', $day, 86400);
			}
			if ($count == $max_daily_vote) { exit('maxvote'); }
			MooSetCookie('crazyfan_vote_num', $count + 1, 86400);
			
			$score[$year][$month] = $cur_score;
			$score = addslashes(serialize($score));
			$db->query("update {$tb_pre}carcrazyfan set score='$score' where uid=$uid");
            echo 'ok';            
        } else 	echo 'iszero';
	}
	exit;
}

/**
 * 会员推荐用户 
 */
function recommend() {
	global $userid;
	$tb_pre = $GLOBALS['dbTablePre'];
	$db = $GLOBALS['_MooClass']['MooMySQL'];
	$userid = $GLOBALS['userid'];
	$max_recommend_num = 1000; // 最大可推荐会员数	
	$recommend_uid = MooGetGPC('recommenduid', 'integer', 'G');
	
	if(!$userid) { exit("gotourl:index.php?n=login&back_url=".urlencode('index.php?n=lovestation&h=crazyfan'));}
	if ($recommend_uid == $GLOBALS['userid']) { exit('不能给自己投票！'); }
	
	$user = $db->getOne("select count(*) as count from {$tb_pre}carrecommend where uid=$userid and ruid=$recommend_uid limit 1");
	if ($user['count'] == 0) {
		$user = $db->getOne("select count(*) as count from {$tb_pre}carrecommend where uid=$userid");
		if ($user['count'] < $max_recommend_num) {
			$time = time();
			$user = $db->getOne("select count(*) as count from {$tb_pre}members_search where uid=$recommend_uid limit 1");
			if ($user['count'] == 1) { // 被推荐会员是否存在
				$db->query("insert into {$tb_pre}carrecommend(uid, ruid, dateline) values($userid, $recommend_uid, $time)");
			} else { exit('没有该会员！'); }
		}
	}
	exit('已成功推荐!');
}

/**
 *  真爱一生爱车会 
 */
function lovecar() {
	$tb_pre = $GLOBALS['dbTablePre'];
	$db = $GLOBALS['_MooClass']['MooMySQL'];
//	$dirname = 'module/lovestation/templates/default/images/carimg/'; // 车型图片存储路径
	
	$car_list = $db->getAll("select car, info, imgurl from {$tb_pre}carhnlove where isdisplay > 0 limit 2");
	foreach ($car_list as $key => $car) {
		if (!empty($car['imgurl'])) {
			$car_list[$key]['imgurl'] = explode('||', $car['imgurl']);
		} else $car_list[$key]['imgurl'] = array();
	}
	require MooTemplate('public/lovestation_lovecar', 'module');
}

/*
 * 真爱一生支招
 */
function carfaq(){
    $sql='SELECT  nickname,username,question,answer FROM `'.$GLOBALS['dbTablePre']."carfaq` as faq left join ".$GLOBALS['dbTablePre']."members_search  as search  on faq.uid =search.uid  where faq.deleted = 0 order by dateline desc LIMIT 3";
    $data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	foreach($data as $key=>$val){
        $val = preg_replace("/\n/","<br>",$val);
        $val = preg_replace("/ /","&nbsp",$val);
        $data[$key]=$val;
    }
    include MooTemplate('public/lovestation_carfaq', 'module');

}

/*
 * 趣味游戏
 */
function  carrelaxbargame(){
    $id = MooGetGPC("id","integer","G");
    $sql='SELECT  href FROM `'.$GLOBALS['dbTablePre']."cargames` where  id=$id LIMIT 1";
    $data=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    $href = $data['href'];
    if(!$href){
        MooMessage("你所请求的游戏不存在",'index.php?n=lovestation&h=carrelaxbar');
        exit;
    }
    $sql='SELECT *  FROM `'.$GLOBALS['dbTablePre']."cargames`  order by dateline desc LIMIT 3";
    $games=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    include MooTemplate('public/lovestation_carrelaxbargame', 'module');
}

/*
 *休闲吧
 */
function carrelaxbar(){
    $sql='SELECT content  FROM `'.$GLOBALS['dbTablePre']."carrelaxbar` where  deleted = 0 order by dateline desc LIMIT 6";
    $data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    foreach($data as $key=>$val){
        $val=preg_replace("/\n/","<br>",$val);
        $val = preg_replace("/ /","&nbsp",$val);
        $data[$key]=$val;
    }
    $sql='SELECT *  FROM `'.$GLOBALS['dbTablePre']."cargames`  order by dateline desc LIMIT 3";
    $games=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);

    include MooTemplate('public/lovestation_carrelaxbar', 'module');
}

/*
导航页
*/
function lovestation(){
   include MooTemplate('public/loveStation', 'module');

}
/***********************************************控制层(C)*****************************************/

$h = MooGetGPC('h', 'string');
switch ($h) {
	case 'crazyfan':
		crazyfan();
		break;
	case 'vote':
		vote();
		break;
	case 'recommend':
		recommend();
		break;
	case 'lovecar':
		lovecar();
		break;
	case "carrelaxbar" :
        carrelaxbar();
		break;
    case "carrelaxbargame" :
        carrelaxbargame();
        break;
    case "carfaq" :
        carfaq();
        break;
	case "ls":
	    lovestation();
		break;
	default :
	    lovestation();
		break;
}
?>