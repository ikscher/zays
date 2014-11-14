<?php
//ini_set('error_log','./error_log.log');
//error_log('allmember_ajax.php=========');

//note 加载框架配置参数
require './include/config.php';
//note 加载MooPHP框架
require '../framwork/MooPHP.php';
//note 包含共公方法
include './include/function.php';
include './include/ajax_function.php';
include './include/allmember_function.php';


//note 委托
function ajax_commission(){
    include_once './include/crontab_config.php';
    $uid = MooGetGPC('uid','integer','G');
	$sid = MooGetGPC('sid','integer','G');
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 10;
    $offset = ($page-1)*$limit;
    
    $t = MooGetGPC('t','integer','G');
    $t = $t ? $t : 1;
    
    if( $t == 1){ //我委托红娘联系TA
    	$sql = "SELECT c.*,m.uid,ml.lastvisit,m.nickname,m.gender,m.birthyear,m.usertype,m.province,m.city,m.sid FROM {$GLOBALS['dbTablePre']}service_contact c LEFT JOIN {$GLOBALS['dbTablePre']}members_search m ON c.other_contact_you = m.uid LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml ON c.other_contact_you = ml.uid WHERE c.you_contact_other = {$uid} AND c.receive_del=0 ORDER BY mid DESC LIMIT {$offset},{$limit}";
        $commissions = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true); 
        $total = getcount('service_contact','WHERE you_contact_other='.$uid);       
    }else{//TA委托红娘联系我
        $sql = "SELECT c.*,m.uid,ml.lastvisit,m.nickname,m.gender,m.birthyear,m.usertype,m.province,m.city,m.sid  FROM {$GLOBALS['dbTablePre']}service_contact c
                LEFT JOIN {$GLOBALS['dbTablePre']}members_search m
                ON c.you_contact_other = m.uid 
                LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml
                ON c.you_contact_other = ml.uid            
                WHERE c.other_contact_you = {$uid}  AND c.send_del=0 ORDER BY mid DESC LIMIT {$offset},{$limit}";
        $commissions = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true); 
        $total = getcount('service_contact','WHERE other_contact_you='.$uid);   
    }
	
	
//    error_log($sql);
    $pages = ceil($total / $limit);
    //note 委托数
    $cocount1 = getcount('service_contact',"WHERE you_contact_other=$uid AND receive_del=0");

    $cocount2 = getcount('service_contact',"WHERE other_contact_you=$uid AND send_del=0");
    //写日志
    serverlog(1,$GLOBALS['dbTablePre'].'service_contact',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}查看会员{$uid}的委托列表",$GLOBALS['adminid'],$uid);
    require_once(adminTemplate('allmember_commission'));
}
//note 秋波
function ajax_leer(){
    include_once './include/crontab_config.php';
    $uid = MooGetGPC('uid','integer','G');
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 10;
    $offset = ($page-1)*$limit;
    
    $t = MooGetGPC('t','integer','G');
    $t = $t ? $t : 1;
      
    if( $t == 1){//我发送秋波给TA
        $sql = "SELECT c.*,m.uid,m.nickname,m.gender,m.birthyear,ml.lastvisit,m.usertype,m.province,m.city,m.sid FROM {$GLOBALS['dbTablePre']}service_leer c
                LEFT JOIN {$GLOBALS['dbTablePre']}members_search m
                ON c.receiveuid = m.uid
                LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml 
                ON c.receiveuid = ml.uid 
                WHERE c.senduid = {$uid} ORDER BY lid DESC LIMIT {$offset},{$limit}";
        $leers = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
        $total = getcount('service_leer','WHERE senduid='.$uid);
    }else{//TA发送秋波给我
        $sql = "SELECT c.*,m.uid,m.nickname,m.gender,m.birthyear,ml.lastvisit,m.usertype,m.province,m.city,m.sid FROM {$GLOBALS['dbTablePre']}service_leer c
                LEFT JOIN {$GLOBALS['dbTablePre']}members_search m
                ON c.senduid = m.uid
                LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml
                ON c.senduid= ml.uid 
                WHERE c.receiveuid = {$uid} ORDER BY lid DESC LIMIT {$offset},{$limit}";
        $leers = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
        $total = getcount('service_leer','WHERE receiveuid='.$uid);
    }
    $pages = ceil($total / $limit);
    //note 秋波数
    $lcount1 = getcount('service_leer','WHERE senduid='.$uid);
    $lcount2 = getcount('service_leer','WHERE receiveuid='.$uid);
    //写日志
    serverlog(1,$GLOBALS['dbTablePre'].'service_contact',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}查看会员{$uid}的秋波列表",$GLOBALS['adminid'],$uid);
    require_once(adminTemplate('allmember_leer'));
}
//note 鲜花
function ajax_rose(){
    include_once './include/crontab_config.php';
    $uid = MooGetGPC('uid','integer','G');
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 10;
    $offset = ($page-1)*$limit;

    $t = MooGetGPC('t','integer','G');
    $t = $t ? $t : 1;
      
    if( $t == 1){//我送出的鲜花
        $sql = "SELECT c.*,m.uid,m.nickname,m.gender,m.birthyear,ml.lastvisit,m.usertype,m.province,m.city,m.sid  FROM {$GLOBALS['dbTablePre']}service_rose c
                LEFT JOIN {$GLOBALS['dbTablePre']}members_search m
                ON c.receiveuid = m.uid
                LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml
                ON c.receiveuid = ml.uid
                WHERE c.senduid = {$uid} ORDER BY rid DESC LIMIT {$offset},{$limit}";

        $roses = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
        $total = getcount('service_rose','WHERE senduid='.$uid);
    }else{//我收到的鲜花
        $sql = "SELECT c.*,m.uid,m.nickname,m.gender,m.birthyear,ml.lastvisit,m.usertype,m.province,m.city,m.sid  FROM {$GLOBALS['dbTablePre']}service_rose c
                LEFT JOIN {$GLOBALS['dbTablePre']}members_search m
                ON c.senduid = m.uid
                LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml
                ON c.senduid = ml.uid
                WHERE c.receiveuid = {$uid} ORDER BY rid DESC LIMIT {$offset},{$limit}";
        $roses = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
        $total = getcount('service_rose','WHERE receiveuid='.$uid);
    }
    $pages = ceil($total / $limit);
	
    //note 鲜花数
    $rcount1 = getcount('service_rose','WHERE senduid='.$uid);
    $rcount2 = getcount('service_rose','WHERE receiveuid='.$uid);
    //写日志
    serverlog(1,$GLOBALS['dbTablePre'].'service_contact',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}查看会员{$uid}的鲜花列表",$GLOBALS['adminid'],$uid);
    require_once(adminTemplate('allmember_rose'));  
}
//note 意中人
function ajax_friend(){
    include_once './include/crontab_config.php';
    $uid = MooGetGPC('uid','integer','G');
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 10;
    $offset = ($page-1)*$limit;
    
    $t = MooGetGPC('t','integer','G');
    $t = $t ? $t : 1;
    
    if( $t == 1){//我的意中人
        $sql = "SELECT c.fid,c.sendtime,m.uid,m.nickname,m.gender,m.birthyear,ml.lastvisit,m.usertype,m.province,m.city,m.sid FROM {$GLOBALS['dbTablePre']}service_friend c
                LEFT JOIN {$GLOBALS['dbTablePre']}members_search m
                ON c.friendid = m.uid
                LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml
                ON c.friendid = ml.uid
                WHERE c.uid = {$uid} ORDER BY fid DESC LIMIT {$offset},{$limit}";
        $friends = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
        $total = getcount('service_friend','WHERE uid='.$uid);
    }else{//谁加我为意中人
        $sql = "SELECT c.fid,c.sendtime,m.uid,m.nickname,m.gender,m.birthyear,ml.lastvisit,m.usertype,m.province,m.city,m.sid FROM {$GLOBALS['dbTablePre']}service_friend c
                LEFT JOIN {$GLOBALS['dbTablePre']}members_search m
                ON c.uid = m.uid
                LEFT JOIN {$GLOBALS['dbTablePre']}members_login ml
                ON c.uid = ml.uid
                WHERE c.friendid = {$uid} ORDER BY fid DESC LIMIT {$offset},{$limit}";
        $friends = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
        $total = getcount('service_friend','WHERE friendid='.$uid);
    }
    $pages = ceil($total / $limit);
    //note 意中人数
    $fcount1 = getcount('service_friend','WHERE uid='.$uid);
    $fcount2 = getcount('service_friend','WHERE friendid='.$uid);
    //写日志
    serverlog(1,$GLOBALS['dbTablePre'].'service_contact',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}查看会员{$uid}的意中人列表",$GLOBALS['adminid'],$uid);
    require_once(adminTemplate('allmember_friend'));
}
//note 心里的事
function ajax_lovetest(){
    $uid = MooGetGPC('uid','integer','G');
    $tc_id = MooGetGPC('tcid','integer','G');
    $tc_id || $tc_id ++;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 12;
    $offset = ($page-1)*$limit;
    $total = getcount('test_vote','WHERE uid='.$uid.' AND tc_id='.$tc_id);
    $pages = ceil($total / $limit);

    $opt_arr = array(2=>array('完全不符合','不符合','很难说','符合','完全符合'),
                    3=>array('完全同意','同意','基本同意','看具体情况','有些方面不同意','不同意','完全不同意'),
                    4=>array('完全不重要','不重要','不是很重要','看具体情况','比较重要','很重要','极其重要'));

    $sql = "SELECT tq.qid,tq.question,tq.ctype,tq.show_type,tv.opt,tv.opt_txt 
        FROM `web_test_question` as tq RIGHT JOIN `web_test_vote` as tv ON tq.qid = tv.qid 
        WHERE tv.uid='{$uid}' AND tv.tc_id='{$tc_id}' 
        LIMIT $offset,$limit ";
        //echo $sql;exit;
    $test_rs = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);

    //测试结果
    $results = array();
if( !isset($_GET['ispage']) ){
    $table = $GLOBALS['dbTablePre'] . 'test_member';
    $test_sql = "SELECT `scores_count`,`ctype`,`result_id` FROM {$GLOBALS['dbTablePre']}test_member 
        WHERE `uid`='{$uid}' AND `tc_id`='{$tc_id}' ";
    $test_info = $GLOBALS['_MooClass']['MooMySQL']->getAll($test_sql,0,0,0,true);
    if($test_info){
        foreach($test_info as $ti){
            $result_id[] = $ti['result_id'];
            if($tc_id == $ti['ctype']) $scores_count = $ti['scores_count'];//测试总分
        }
        $result_id = implode(',',$result_id);
        $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}test_result WHERE `id` IN ({$result_id})";
        $my_result = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
        $results = array();
        foreach($my_result as $k=>$v){
            $results[$v['rtype']] = array('ctypename'=>$v['ctypename'],'result'=>$v['result']);
        }
        require '../data/cache/hntest_cache.php';
        //print_r($hntest_cache['children']);
    }
}
    require_once(adminTemplate('allmember_test'));
}
//note 匹配搜索
function ajax_match(){
    $memberid = MooGetGPC('uid','integer','G');
    $page = max(1,MooGetGPC('page','integer','G'));
    
    //初始化搜索参数
	$check_age = $check_weight = $check_height = $check_haphoto = $check_workprovince = $check_marriage = $check_education = $check_smoking = $check_salary = $check_children = $check_bodys = $check_occupation = $check_hometownprovince = $check_nation = $check_drinking = 0;
    
    $limit = 10;
    $offset = ($page-1)*$limit;
    
    /*if(MOOPHP_ALLOW_FASTDB){
        $user_arr = MooFastdbGet('members','uid',$memberid);
    }else{
        $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_search WHERE uid = {$memberid}";
        $user_arr = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    }*/
    $user_arr = MooMembersData($memberid);

    //note 当前用户择偶信息
   /* if(MOOPHP_ALLOW_FASTDB){
        $user_1 = MooFastdbGet('choice','uid',$memberid);
    }else{
        $sql = "select * from {$GLOBALS['dbTablePre']}choice where `uid` = {$memberid}";
        $user_1 = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    }*/
    $user_introduce = MooGetData('members_introduce','uid',$memberid);
    $user_1 = MooGetData('members_choice','uid',$memberid);
	$user_1 = array_merge($user_1, $user_introduce);
    //note 如果会员没有选择择偶条件则不显示择偶对象
    $allitem = 0;
    foreach($user_1 as $k=>$v){
        if($k=='uid' || $k=='introduce_check') $allitem++;
        elseif($v =='-1' || empty($v) || $v=='0') $allitem++;
    }
    if(count($user_1)==$allitem) exit('该会员没有选择任何择偶条件');
    

   /* $sql = "select * from {$GLOBALS['dbTablePre']}memberfield where uid = {$memberid}";
    $memberfield = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);*/
    $memberfield = $user_arr;
    
    //note  找出符合当前会员择偶条件的会员
    //基本SQL语句
    $pre_sql = "select count(*) as count";
    
    $sql = $sql2 = " from `{$GLOBALS['dbTablePre']}members_search` as m left join `{$GLOBALS['dbTablePre']}members_base` as mf on (m.uid=mf.uid) left join {$GLOBALS['dbTablePre']}members_choice as c on (m.uid = c.uid) where m.is_lock = 1  ";
    //note 择偶对象性别
    if ($user_arr['gender'] == '0') {
        $sql .= 'and m.gender=1 ';
        $sql2 .= 'and m.gender=1 ';
    } else {
        $sql .= 'and m.gender=0 ';
        $sql2 .= 'and m.gender=0 ';
    }
    //note 择偶对象出生年区间
    $age1 = $user_1['age1'];
    $age2 = $user_1['age2'];
    if($age1 != 0 && $age2 != 0){
        $age_end = gmdate('Y', time()) - intval($age1);
        $age_begin = gmdate('Y', time()) - intval($age2);
        $sql .= " and (m.birthyear between $age_begin and $age_end) ";
        
        $age_end = gmdate('Y', time()) - intval($age1) + 5;
        $age_begin = gmdate('Y', time()) - intval($age2) - 5;
        $sql2 .= " and (m.birthyear between $age_begin and $age_end) ";
    } elseif ($age1 != 0) {
        $age_start = gmdate('Y', time()) - intval($age1);
        $sql .= " and m.birthyear <= " . $age_start;
        
        $age_start = gmdate('Y', time()) - intval($age1) - 5;
        $sql2 .= " and m.birthyear <= " . $age_start;
    } elseif ($age2 != 0) {
        $age_end = gmdate('Y', time()) - intval($age2);
        $sql .= " and m.birthyear >= " . $age_end;
        
        $age_end = gmdate('Y', time()) - intval($age2) + 5;
        $sql2 .= " and m.birthyear >= " . $age_end;
    }
        
    //note 择偶对象体重区间
    $weight1 = $user_1['weight1'];
    $weight2 = $user_1['weight2'];
    if($weight1 != 0 && $weight2 != 0){
        $sql .= " and (m.weight between $weight1 and $weight2) ";
        $weight1 = $weight1 - 5;
        $weight2 = $weight2 + 5;
        $sql2 .= " and (m.weight between $weight1 and $weight2) ";
    } elseif ($weight1 != 0) {
        $sql .= " and m.weight >= " . $weight1;
        
        $weight1 = $weight1 - 5;
        $sql2 .= " and m.weight >= " . $weight1;
        
    } elseif ($weight2 != 0) {
        $sql .= " and m.weight <= " . $weight2;
        
        $weight2 = $weight2 + 5;
        $sql2 .= " and m.weight <= " . $weight2;
    }
    //note 择偶对象身高区间
    $height1 = $user_1['height1'];
    $height2 = $user_1['height2'];
    if($height1 != 0 && $height2 != 0){
        $sql .= " and (m.height between $height1 and $height2) ";
        
        $height1 = $height1 - 5;
        $height2 = $height2 + 5;
        $sql2 .= " and (m.height between $height1 and $height2) ";
    } elseif ($height1 != 0) {
        $sql .= " and m.height >= " . $height1;
        
        $height1 = $height1 - 5;
        $sql2 .= " and m.height >= " . $height1;
    } elseif ($height2 != 0) {
        $sql .= " and m.height <= " . $height2;
        
        $height2 = $height2 + 5;
        $sql2 .= " and m.height >= " . $height2;
    }
        
        //note 是否有相片
        if($user_1['hasphoto']){
            $sql .= " and m.images_ischeck = '1' ";
        }
        
        // 省城市
        if ($user_1["workprovince"] != 0 && $user_1["workcity"] != 0) {
            $sql .= " and m.province = " . $user_1["workprovince"] . " and m.city = " . $user_1["workcity"];
            $sql2 .= " and m.province = " . $user_1["workprovince"];
        } elseif ($user_1["workprovince"] != 0) {
            $sql .= " and m.province = " . $user_1["workprovince"];
        }
        
        // 婚姻状况
        if ($user_1["marriage"] != 0) {
            $sql .= " and m.marriage = " . $user_1["marriage"];
        }
        
        if ($user_1["education"] != 0) {
            $sql .= " and m.education >= " . $user_1["education"];
        }
        
        // 是否抽烟
        if ($user_1["smoking"] != 0) {
            $sql .= " and m.smoking <= " . $user_1['smoking'];
        }
        
        // 收入
        if ($user_1["salary"] != 0) {
            $sql .= " and m.salary >= " . $user_1['salary'];
        }
        
        // 有无孩子
        if ($user_1["children"] != 0) {
            $sql .= " and m.children = " . $user_1["children"];
        }
                
        // 体形
        if ($user_1["body"] != 0) {
            $sql .= " and m.body = " . $user_1["body"];
        }
        
        // 职业
        if ($user_1["occupation"] != 0) {
            $sql .= " and m.occupation = " . $user_1["occupation"];
        }
        
        // 户口地区
        if ($user_1["hometownprovince"] != 0 && $user_1["hometowncity"] != 0) {
            $sql .= " and m.hometownprovince = " . $user_1["hometownprovince"] . " and m.hometowncity = " . $user_1["hometowncity"];
            $sql2 .= " and m.hometownprovince = " . $user_1["hometownprovince"];
        } elseif ($user_1["hometownprovince"] != 0) {
            $sql .= " and m.hometownprovince = " . $user_1["hometownprovince"];
        }
        
        // 民族
        if ($user_1["nation"] != 0) {
            $sql .= " and m.nation = " . $user_1["nation"];
        }
        
        // 是否想要孩子
//      if ($user_1["wantchildren"] != 0) {
//          $sql .= " and mf.wantchildren = " . $user_1["wantchildren"];
//      }
        
        // 喝酒
        if ($user_1["drinking"] != 0) {
            $sql .= " and m.drinking <= " . $user_1["drinking"];
        }
        
        // 对方条件
        // age
        if (!empty($user_arr["birthyear"])) {
            $member_age = date("Y") - $user_arr["birthyear"];
            $sql .= " and (c.age1 = 0 or c.age1 <= '" . $member_age . "') and (c.age2 = 0 or c.age2 >= '" . $member_age . "')";
//          
//          $start_age = date("Y") - $user_arr["birthyear"] - 5;
//          $end_age = date("Y") - $user_arr["birthyear"] + 5;
//          $sql2 .= " and (c.age1 = 0 or c.age1 >= '" . $start_age . "') and (c.age2 = 0 or c.age1 <= '" . $member_age . "')";
        }
        
        // 工作省份
        if (!empty($user_arr["provice"]) && $user_arr["provice"] != 0 && !empty($user_arr["city"]) && $user_arr["city"] != 0) {
            $sql .= " and (c.workprovice = 0 or c.workprovice = '" . $user_arr["provice"] . "') and (c.workcity = 0 or c.workcity = '" . $user_arr["city"] . "')";
            //$sql2 .= " and (c.workprovice = 0 or c.workprovice = '" . $user_arr["provice"] . "')";
        } elseif (!empty($user_arr["provice"]) && $user_arr["provice"] != 0) {
            $sql .= " and (c.workprovice = 0 or c.workprovice = '" . $user_arr["provice"] . "')";
            //$sql2 .= " and (c.workprovice = 0 or c.workprovice = '" . $user_arr["provice"] . "')";
        }
        
        // 婚姻
        if (!empty($user_arr["marriage"]) && $user_arr["marriage"] != 0) {
            $sql .= " and (c.marriage = 0 or c.marriage = '" . $user_arr["marriage"] . "')";
        }
        
        // 教育
        if (!empty($user_arr["education"]) && $user_arr["education"] != 0) {
            $sql .= " and (c.education = 0 or c.education = '" . $user_arr["education"] . "')";
        }
        
        // 收入
        if (!empty($user_arr["salary"]) && $user_arr["salary"] != 0) {
            $sql .= " and (c.salary = 0 or c.salary <= '" . $user_arr["salary"] . "')";
        }
        
        // 是否有孩子
        if (!empty($user_arr["children"]) && $user_arr["children"] != 0) {
            $sql .= " and (c.children = 0 or c.children = '" . $user_arr["children"] . "')";
        }
        
        // 身高
        if (!empty($user_arr["height"]) && $user_arr["height"] != 0) {
            $sql .= " and (c.height1 = 0 or c.height1 <= '" . $user_arr["height"] . "') and (c.height2 = 0 or c.height1 >= '" . $user_arr["height"] . "')";
        }
        
        // 图片
        $is_have = 1;
        $is_not_have  = 0;
        if (empty($user_arr["mainimg"])) {
            $sql .= " and (c.hasphoto != 0 or c.hasphoto = 1)";
        }/* else {
            $sql .= " and (c.hasphoto != 0 or c.hasphoto = 0)";
        }*/
        
        // 性格
        if (!empty($memberfield["nature"]) && $memberfield["nature"] != 0) {
            $sql .= " and (c.nature = 0 or c.nature = '" . $memberfield["nature"] . "')";
        }
        
        // 体形
        if (!empty($memberfield["body"]) && $memberfield["body"] != 0) {
            $sql .= " and (c.body = 0 or c.body = '" . $memberfield["body"] . "')";
        }
        
        // 体重
        if (!empty($memberfield["weight"]) && $memberfield["weight"] != 0) {
            $sql .= " and (c.weight1 = 0 or c.weight1 <= '" . $memberfield["weight"] . "') and (c.weight2 = 0 or c.weight2 >= '" . $memberfield["weight"] . "')";
        }
        
        // 职业
        if (!empty($memberfield["occupation"]) && $memberfield["occupation"] != 0) {
            $sql .= " and (c.occupation = 0 or c.occupation = '" . $memberfield["occupation"] . "')";
        }
        
        // 省市
        if (!empty($memberfield["hometownprovince"]) && $memberfield["hometownprovince"] != 0 && !empty($memberfield["hometowncity"]) && $memberfield["hometowncity"] != 0) {
            $sql .= " and (c.hometownprovince = 0 or c.hometownprovince = '" . $memberfield["hometownprovince"] . "') and (c.hometowncity = 0 or c.hometowncity = '" . $memberfield["hometowncity"] . "')";
        } elseif (!empty($memberfield["hometownprovince"]) && $memberfield["hometownprovince"] != 0) {
            $sql .= " and (c.hometownprovince = 0 or c.hometownprovince = '" . $memberfield["hometownprovince"] . "')";
        }
        
        // 民族
        if (!empty($memberfield["nation"]) && $memberfield["nation"] != 0) {
            $sql .= " and (c.nation = 0 or c.nation = '" . $memberfield["nation"] . "')";
        }
        
        // 是否想要孩子
        if (!empty($memberfield["wantchildren"]) && $memberfield["wantchildren"] != 0) {
            $sql .= " and (c.wantchildren = 0 or c.wantchildren = '" . $memberfield["wantchildren"] . "')";
        }
        
        // 喝酒
        if (!empty($memberfield["drinking"]) && $memberfield["drinking"] != 0) {
            $sql .= " and (c.drinking = 0 or c.drinking >= '" . $memberfield["drinking"] . "')";
        }
        
        // 抽烟
        if (!empty($memberfield["smoking"]) && $memberfield["smoking"] != 0) {
            $sql .= " and (c.smoking = 0 or c.smoking >= '" . $memberfield["smoking"] . "')";
        }
        
        //符合当前会员择偶条件的会员
        $use_sql = "sql";
        $member_count = $GLOBALS['_MooClass']['MooMySQL']->getOne($pre_sql . $sql,true);

        if ($member_count["count"] == 0) {
            $use_sql = "sql2";
            $member_count = $GLOBALS['_MooClass']['MooMySQL']->getOne($pre_sql . $sql2,true);
        }
        
        if ($member_count["count"] > 0) {
            $total = $member_count["count"];
            if ($total > 500) {
                $total = 500;
            }
            $pages = ceil($total/$limit);
            
            if ($use_sql == "sql") {
                $sql3 = "select m.* " . $sql . " limit " . $offset . ", " . $limit;
            } else {
                $sql3 = "select m.* " . $sql2 . " limit " . $offset . ", " . $limit;
            }
            //echo $sql3;
            $users = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql3,false,true,3600,true);
            
        } else {
            echo 'match error!';
            exit;
        }
        
        
    //写日志
    serverlog(1,$GLOBALS['dbTablePre'].'service_contact',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}查看会员{$memberid}的匹配搜索列表",$GLOBALS['adminid'],$memberid);
    require_once(adminTemplate('allmember_match'));
}

//按条件匹配搜索
function ajax_match_search(){
    $memberid = MooGetGPC('uid','integer','G');
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 10;
    $offset = ($page-1)*$limit;
    
    /*if(MOOPHP_ALLOW_FASTDB){
        $user_arr = MooFastdbGet('members','uid',$memberid);
    }else{
        $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members WHERE uid = {$memberid}";
        $user_arr = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    }*/
    $user_arr = MooMembersData($memberid);
    
    //note 当前用户择偶信息
   /* if(MOOPHP_ALLOW_FASTDB){
        $user_1 = MooFastdbGet('choice','uid',$memberid);
    }else{
        $sql = "select * from {$GLOBALS['dbTablePre']}choice where `uid` = {$memberid}";
        $user_1 = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    }*/
    $user_introduce = MooGetData('members_introduce','uid',$memberid);
    $user_1 = MooGetData('members_choice','uid',$memberid);
	$user_1 = array_merge($user_1, $user_introduce);

    //note 如果会员没有选择择偶条件则不显示择偶对象
    $allitem = 0;
    foreach($user_1 as $k=>$v){
        if($k=='uid' || $k=='introduce_check') $allitem++;
        elseif($v =='-1' || empty($v) || $v=='0') $allitem++;
    }
    if(count($user_1)==$allitem) exit('该会员没有选择任何择偶条件');
    

    /*$sql = "select * from {$GLOBALS['dbTablePre']}memberfield where uid = {$memberid}";
    $memberfield = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    */
    
    $memberfield = $user_arr;
        
    //note  找出符合当前会员择偶条件的会员
    //基本SQL语句
    $pre_sql = "select count(*) as count";
    
    $sql = $sql2 = " from `{$GLOBALS['dbTablePre']}members_search` as m left join `{$GLOBALS['dbTablePre']}members_base` as mf on (m.uid=mf.uid) left join {$GLOBALS['dbTablePre']}members_choice as c on (m.uid = c.uid) where m.is_lock = 1  ";
    //note 择偶对象性别
    if ($user_arr['gender'] == '0') {
        $sql .= 'and m.gender=1 ';
        $sql2 .= 'and m.gender=1 ';
    } else {
        $sql .= 'and m.gender=0 ';
        $sql2 .= 'and m.gender=0 ';
    }
    //note 择偶对象出生年区间
    $age1 = $user_1['age1'];
    $age2 = $user_1['age2'];
    //note 是否选中年龄搜索
    $check_age = MooGetGPC('age','string','G');
    if($check_age){
        if($age1 != 0 && $age2 != 0){
            $age_end = gmdate('Y', time()) - intval($age1);
            $age_begin = gmdate('Y', time()) - intval($age2);
            $sql .= " and (m.birthyear between $age_begin and $age_end) ";
            
            $age_end = gmdate('Y', time()) - intval($age1) + 5;
            $age_begin = gmdate('Y', time()) - intval($age2) - 5;
            $sql2 .= " and (m.birthyear between $age_begin and $age_end) ";
        } elseif ($age1 != 0) {
            $age_start = gmdate('Y', time()) - intval($age1);
            $sql .= " and m.birthyear <= " . $age_start;
            
            $age_start = gmdate('Y', time()) - intval($age1) - 5;
            $sql2 .= " and m.birthyear <= " . $age_start;
        } elseif ($age2 != 0) {
            $age_end = gmdate('Y', time()) - intval($age2);
            $sql .= " and m.birthyear >= " . $age_end;
            
            $age_end = gmdate('Y', time()) - intval($age2) + 5;
            $sql2 .= " and m.birthyear >= " . $age_end;
        }
    }
    //note 择偶对象体重区间
    $weight1 = $user_1['weight1'];
    $weight2 = $user_1['weight2'];
    //note 是否选中体重搜索
    $check_weight = MooGetGPC('weight','string','G');
    if($check_weight){
        if($weight1 != 0 && $weight2 != 0){
            $sql .= " and (m.weight between $weight1 and $weight2) ";
            $weight1 = $weight1 - 5;
            $weight2 = $weight2 + 5;
            $sql2 .= " and (m.weight between $weight1 and $weight2) ";
        } elseif ($weight1 != 0) {
            $sql .= " and m.weight >= " . $weight1;
            
            $weight1 = $weight1 - 5;
            $sql2 .= " and m.weight >= " . $weight1;
            
        } elseif ($weight2 != 0) {
            $sql .= " and m.weight <= " . $weight2;
            
            $weight2 = $weight2 + 5;
            $sql2 .= " and m.weight <= " . $weight2;
        }
    }
    //note 择偶对象身高区间
    $height1 = $user_1['height1'];
    $height2 = $user_1['height2'];
    //note 是否选中身高搜索
    $check_height = MooGetGPC('height','string','G');
    if($check_height){
        if($height1 != 0 && $height2 != 0){
            $sql .= " and (m.height between $height1 and $height2) ";
            
            $height1 = $height1 - 5;
            $height2 = $height2 + 5;
            $sql2 .= " and (m.height between $height1 and $height2) ";
        } elseif ($height1 != 0) {
            $sql .= " and m.height >= " . $height1;
            
            $height1 = $height1 - 5;
            $sql2 .= " and m.height >= " . $height1;
        } elseif ($height2 != 0) {
            $sql .= " and m.height <= " . $height2;
            
            $height2 = $height2 + 5;
            $sql2 .= " and m.height >= " . $height2;
        }
    }
        //note 是否有相片
        $check_haphoto = MooGetGPC('haphoto','string','G');
        if($check_haphoto){
            if($user_1['hasphoto']){
                $sql .= " and m.images_ischeck = '1' ";
            }
        }
        // note 是否选中省城市搜索 
        $check_workprovince = MooGetGPC('workprovince','string','G');
        if($check_workprovince){
            if ($user_1["workprovince"] != 0 && $user_1["workcity"] != 0) {
                $sql .= " and m.province = " . $user_1["workprovince"] . " and m.city = " . $user_1["workcity"];
                $sql2 .= " and m.province = " . $user_1["workprovince"];
            } elseif ($user_1["workprovince"] != 0) {
                $sql .= " and m.province = " . $user_1["workprovince"];
            }
        }
        
        // note 是否选中婚姻状况搜索
        $check_marriage = MooGetGPC('marriage','string','G');
        if($check_marriage){
            if ($user_1["marriage"] != 0) {
                $sql .= " and m.marriage = " . $user_1["marriage"];
            }
        }
        
        //note 是否选中教育程度搜索
        $check_education = MooGetGPC('education','string','G');
        if($check_education){
            if ($user_1["education"] != 0) {
                $sql .= " and m.education >= " . $user_1["education"];
            }
        }
        
        //note  是否选中是否抽烟搜索
        $check_smoking = MooGetGPC('smoking','string','G');
        if($check_smoking){
            if ($user_1["smoking"] != 0) {
                $sql .= " and f.smoking <= " . $user_1['smoking'];
            }
        }
        
        //note  是否选中月收入搜索
        $check_salary = MooGetGPC('salary','string','G');
        if($check_salary){
            if ($user_1["salary"] != 0) {
                $sql .= " and m.salary >= " . $user_1['salary'];
            }
        }
        
        // note 是否选中有无孩子搜索
        $check_children = MooGetGPC('children','string','G');
        if($check_children){
            if ($user_1["children"] != 0) {
                $sql .= " and m.children = " . $user_1["children"];
            }
        }
                
        // note 是否选中体形搜索
        $check_bodys = MooGetGPC('bodys','string','G');
        if($check_bodys){
            if ($user_1["body"] != 0) {
                $sql .= " and m.body = " . $user_1["body"];
            }
        }
        // note 是否选中职业搜索
        $check_occupation = MooGetGPC('occupation','string','G');
        if($check_occupation){
            if ($user_1["occupation"] != 0) {
                $sql .= " and m.occupation = " . $user_1["occupation"];
            }
        }
        
        // note 是否选中户口地区搜索
        $check_hometownprovince = MooGetGPC('hometownprovince','string','G');
        if($check_hometownprovince){
            if ($user_1["hometownprovince"] != 0 && $user_1["hometowncity"] != 0) {
                $sql .= " and m.hometownprovince = " . $user_1["hometownprovince"] . " and mf.hometowncity = " . $user_1["hometowncity"];
                $sql2 .= " and m.hometownprovince = " . $user_1["hometownprovince"];
            } elseif ($user_1["hometownprovince"] != 0) {
                $sql .= " and m.hometownprovince = " . $user_1["hometownprovince"];
            }
        }
        // note 是否选中民族搜索
        $check_nation = MooGetGPC('nation','string','G');
        if($check_nation){
            if ($user_1["nation"] != 0) {
                $sql .= " and m.nation = " . $user_1["nation"];
            }
        }
        // 是否想要孩子
//      if ($user_1["wantchildren"] != 0) {
//          $sql .= " and mf.wantchildren = " . $user_1["wantchildren"];
//      }
        
        // note 是否选中喝酒搜索
        $check_drinking = MooGetGPC('drinking','string','G');
        if($check_drinking){
            if ($user_1["drinking"] != 0) {
                $sql .= " and m.drinking <= " . $user_1["drinking"];
            }
        }
        
        // 对方条件
        // age
        if($check_age){
            if (!empty($user_arr["birthyear"])) {
                $member_age = date("Y") - $user_arr["birthyear"];
                $sql .= " and (c.age1 = 0 or c.age1 <= '" . $member_age . "') and (c.age2 = 0 or c.age2 >= '" . $member_age . "')";
    //          
    //          $start_age = date("Y") - $user_arr["birthyear"] - 5;
    //          $end_age = date("Y") - $user_arr["birthyear"] + 5;
    //          $sql2 .= " and (c.age1 = 0 or c.age1 >= '" . $start_age . "') and (c.age2 = 0 or c.age1 <= '" . $member_age . "')";
            }
        }
        // 工作省份
        if($check_workprovince){
            if (!empty($user_arr["provice"]) && $user_arr["provice"] != 0 && !empty($user_arr["city"]) && $user_arr["city"] != 0) {
                $sql .= " and (c.workprovice = 0 or c.workprovice = '" . $user_arr["provice"] . "') and (c.workcity = 0 or c.workcity = '" . $user_arr["city"] . "')";
                //$sql2 .= " and (c.workprovice = 0 or c.workprovice = '" . $user_arr["provice"] . "')";
            } elseif (!empty($user_arr["provice"]) && $user_arr["provice"] != 0) {
                $sql .= " and (c.workprovice = 0 or c.workprovice = '" . $user_arr["provice"] . "')";
                //$sql2 .= " and (c.workprovice = 0 or c.workprovice = '" . $user_arr["provice"] . "')";
            }
        }
        // 婚姻
        if($check_marriage){
            if (!empty($user_arr["marriage"]) && $user_arr["marriage"] != 0) {
                $sql .= " and (c.marriage = 0 or c.marriage = '" . $user_arr["marriage"] . "')";
            }
        }
        // 教育
        if($check_education){
            if (!empty($user_arr["education"]) && $user_arr["edutcation"] != 0) {
                $sql .= " and (c.education = 0 or c.education = '" . $user_arr["education"] . "')";
            }
        }
        // 收入
        if($check_salary){
            if (!empty($user_arr["salary"]) && $user_arr["salary"] != 0) {
                $sql .= " and (c.salary = 0 or c.salary <= '" . $user_arr["salary"] . "')";
            }
        }
        // 是否有孩子
        if($check_children){
            if (!empty($user_arr["children"]) && $user_arr["children"] != 0) {
                $sql .= " and (c.children = 0 or c.children = '" . $user_arr["children"] . "')";
            }
        }
        // 身高
        if($check_height){
            if (!empty($user_arr["height"]) && $user_arr["height"] != 0) {
                $sql .= " and (c.height1 = 0 or c.height1 <= '" . $user_arr["height"] . "') and (c.height2 = 0 or c.height1 >= '" . $user_arr["height"] . "')";
            }
        }
        // 图片
        $is_have = 1;
        $is_not_have  = 0;
        if($check_hasphoto){
            if (empty($user_arr["mainimg"])) {
                $sql .= " and (c.hasphoto != 0 or c.hasphoto = 1)";
            } else {
                $sql .= " and (c.hasphoto != 0 or c.hasphoto = 0)";
            }
        }
        // 性格
        if (!empty($memberfield["nature"]) && $memberfield["nature"] != 0) {
            $sql .= " and (c.nature = 0 or c.nature = '" . $memberfield["nature"] . "')";
        }
        
        // 体形
        if($check_bodys){
            if (!empty($memberfield["body"]) && $memberfield["body"] != 0) {
                $sql .= " and (c.body = 0 or c.body = '" . $memberfield["body"] . "')";
            }
        }
        // 体重
        if($check_weight){
            if (!empty($memberfield["weight"]) && $memberfield["weight"] != 0) {
                $sql .= " and (c.weight1 = 0 or c.weight1 <= '" . $memberfield["weight"] . "') and (c.weight2 = 0 or c.weight2 >= '" . $memberfield["weight"] . "')";
            }
        }
        // 职业
        if($check_occupation){
            if (!empty($memberfield["occupation"]) && $memberfield["occupation"] != 0) {
                $sql .= " and (c.occupation = 0 or c.occupation = '" . $memberfield["occupation"] . "')";
            }
        }
        
        // 省市
        if($check_hometownprovince){
            if (!empty($memberfield["hometownprovince"]) && $memberfield["hometownprovince"] != 0 && !empty($memberfield["hometowncity"]) && $memberfield["hometowncity"] != 0) {
                $sql .= " and (c.hometownprovince = 0 or c.hometownprovince = '" . $memberfield["hometownprovince"] . "') and (c.hometowncity = 0 or c.hometowncity = '" . $memberfield["hometowncity"] . "')";
            } elseif (!empty($memberfield["hometownprovince"]) && $memberfield["hometownprovince"] != 0) {
                $sql .= " and (c.hometownprovince = 0 or c.hometownprovince = '" . $memberfield["hometownprovince"] . "')";
            }
        }
        // 民族
        if($check_nation){
            if (!empty($memberfield["nation"]) && $memberfield["nation"] != 0) {
                $sql .= " and (c.nation = 0 or c.nation = '" . $memberfield["nation"] . "')";
            }
        }
        // 是否想要孩子
            if (!empty($memberfield["wantchildren"]) && $memberfield["wantchildren"] != 0) {
                $sql .= " and (c.wantchildren = 0 or c.wantchildren = '" . $memberfield["wantchildren"] . "')";
            }
        // 喝酒
        if($check_drinking){
            if (!empty($memberfield["drinking"]) && $memberfield["drinking"] != 0) {
                $sql .= " and (c.drinking = 0 or c.drinking >= '" . $memberfield["drinking"] . "')";
            }
        }
        // 抽烟
        if($check_smoking){
            if (!empty($memberfield["smoking"]) && $memberfield["smoking"] != 0) {
                $sql .= " and (c.smoking = 0 or c.smoking >= '" . $memberfield["smoking"] . "')";
            }
        }
        //分页那块需要区分开
        $mark = 1;
        //符合当前会员择偶条件的会员
        $use_sql = "sql";
        $member_count = $GLOBALS['_MooClass']['MooMySQL']->getOne($pre_sql . $sql,true);
        
        if ($member_count["count"] == 0) {
            $use_sql = "sql2";
            $member_count = $GLOBALS['_MooClass']['MooMySQL']->getOne($pre_sql . $sql2,true);
        }
        
        if ($member_count["count"] > 0) {
            $total = $member_count["count"];
            if ($total > 500) {
                $total = 500;
            }
            $pages = ceil($total/$limit);
            
            if ($use_sql == "sql") {
                $sql3 = "select m.* " . $sql . " limit " . $offset . ", " . $limit;
            } else {
                $sql3 = "select m.* " . $sql2 . " limit " . $offset . ", " . $limit;
            }
            //echo $sql3;
            $users = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql3,false,true,3600,true);
            
        } else {
            echo 'match error!';
            exit;
        }
    //写日志
    serverlog(1,$GLOBALS['dbTablePre'].'service_contact',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}查看会员{$uid}的匹配搜索列表",$GLOBALS['adminid'],$memberid);
    require_once(adminTemplate('allmember_match'));
}


//发送短信
function ajax_sendSMS(){
    global $memcached;
	
	$sendsmsuid=$memcached->get('sendsmsuid');
	$sendsmsuid=is_array($sendsmsuid)?$sendsmsuid:array();
    $uid=MooGetGPC('uid','string','P');
	
	if(is_array($uid)){
	    foreach($uid as $v){
		    $_uid_=explode('-',$v);
		    if($_uid_[1]){
				array_push($sendsmsuid,$_uid_[0]);
			}else{
				$key=array_search($_uid_[0],$sendsmsuid);
				if(isset($key)) unset($sendsmsuid[$key]);
			}
		}
	}else{
		$flag=MooGetGPC('flag','string','P');
		if($flag){
			array_push($sendsmsuid,$uid);
			
		}else{
			$k=array_search($uid,$sendsmsuid);
			if(isset($k)) unset($sendsmsuid[$k]);
		}
	}
	$memcached->set('sendsmsuid',$sendsmsuid,0,300);
	//echo json_encode($sendsmsuid);

}

//note 会员认证  发短信 发站内信
function ajax_contact(){
    $uid = MooGetGPC('uid','integer','G');
    $tel = MooGetGPC('tel','string','G');
    $tel2 = MooGetGPC('tel2','string','G');
    $type = MooGetGPC('type','integer','G');
    $from = MooGetGPC('from','string');
    $to = MooGetGPC('to','string');
    $ispost = MooGetGPC('ispost','integer','P');

    
    //拨打电话
    if( $type ==1){
    }
    //note 发短信
    if( $type ==2 ){

        $page = max(1,MooGetGPC('page','integer','G'));
        $limit = 10;
        $offset = ($page-1)*$limit;
        $gopage = MooGetGPC('gopage','integer','G');
        if($ispost){
            $mes = MooGetGPC('tel_message','string','P');
            $dateline = time();
            $dateline2 = $dateline+300;
            
            //$sql1="SELECT * FROM {$GLOBALS['dbTablePre']}smslog where uid='$uid' and content='$mes' and sendtime<'$dateline2'";
            //error_log($sql1);
            // $msg_inf = $GLOBALS['_MooClass']['MooMySQL']->query($sql1);
            // $msg_num = $GLOBALS['_MooClass']['MooMySQL']->numRows($msg_inf);
			
			//$msg_inf = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql1);
            //$msg_num = sizeof($msg_inf);
			$mes_=$mes;
            //error_log($msg_num);
            //if($msg_num<1){
                $ret = SendMsg($tel, $mes_);
                
                if( $ret !== false){
                    $sql = "INSERT INTO {$GLOBALS['dbTablePre']}smslog(sid,uid,content,sendtime) VALUES('{$GLOBALS['adminid']}','{$uid}','{$mes}','{$dateline}')";
                    $sid = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
                    echo 1; 
                }else{
                    echo 0;
                }
                //写日志
                serverlog(3,$GLOBALS['dbTablePre'].'smslog',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}向会员{$uid}发送短信{$sid}",$GLOBALS['adminid'],$uid);
                exit;   
            //}else echo "你已经发过两条同样的短信了！";
        }
        $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}smspre WHERE target = {$GLOBALS['adminid']} OR target = 0 LIMIT {$offset},{$limit}";
        $sms = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
                
        $total = getcount('smspre',"WHERE target = {$GLOBALS['adminid']} OR target = 0");
        $pages = ceil($total/$limit);
        if( $gopage ){
            foreach( $sms as $k=>$v){
                $content = str_replace('[adminid]',$GLOBALS['adminid'],$v['content']);
                
                preg_match("/\[smstitle\](.+?)\[\/smstitle\]/isU",$content,$matche1);
                preg_match("/\[\/smstitle\](.+?)/isU",$content,$matche2);
                
                if ($matche1[1]){ 
                    echo '<div style="padding:3px; cursor:pointer;" onclick="selectThis('.(($page-1)*$limit+$k+1).');">'.(($page-1)*$limit+$k+1).'.<font color="#0033CC">'.$matche1[1].'</font><span id="sms_content_'.(($page-1)*$limit+$k+1).'">'.$matche2[1].'</span></div>';
                }else{
                    echo '<div style="padding:3px; cursor:pointer;" onclick="selectThis('.(($page-1)*$limit+$k+1).');">'.(($page-1)*$limit+$k+1).'.<span id="sms_content_'.(($page-1)*$limit+$k+1).'">'.$content.'</span></div>';
                }
            }
            exit;
        }
    }
    //note 发站内信
    if( $type ==3 && $ispost){
        $from = MooGetGPC('from','string','P');
        $uid = $to ? $to : $uid ;
        $title = MooGetGPC('title','string','P');
        $content = MooGetGPC('content','string','P');
        $filename = trim(MooGetGPC('filename','string','P'));
        $newfilename = trim(MooGetGPC('newfilename','string','P'));  
        $filesize = trim(MooGetGPC('filesize','string','P'));
        $sendmsg = MooGetGPC('sendmsg','integer','P'); //是否短信提醒
        $time = time();

        if( $from ){
            $sql = "SELECT s_cid FROM {$GLOBALS['dbTablePre']}members_search WHERE uid = '{$from}'";
            $ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
            if(empty($ret['s_cid'])){
                $mlevel = 2;
            }else{
                $mlevel = $ret['s_cid'];
            }
        }else{
            $from = $GLOBALS['adminid'];
            $mlevel = 3;
	       
        }
        
        
	    
        //note 如果上传附件，做相应处理
        $file_sql1 = '';
        $file_sql2 = '';
        if($filename && $newfilename && $filesize){
            $file_sql1 = ",filename,newfilename,filesize";
            $file_sql2 = ",'{$filename}','{$newfilename}','{$filesize}'";
        }       
        $sql = "INSERT INTO {$GLOBALS['dbTablePre']}services(s_cid,s_uid,s_fromid,s_title,s_content,s_time,is_server,sid,flag {$file_sql1})
                VALUES({$mlevel},{$uid},'{$from}','{$title}','{$content}','{$time}','1','1','1' {$file_sql2})";
        $ret = $GLOBALS['_MooClass']['MooMySQL']->query($sql);

        
        $sql = "SELECT username,telphone,sid FROM {$GLOBALS['dbTablePre']}members_search WHERE uid='{$uid}'";
        $user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
        //发站内信，勾选则短信提醒
        if($sendmsg == 1){
            if(!empty($user['telphone'])){
                $mes = '真爱一生网 有人已给您发送电子邮件,请及时到真爱一生网www.zhenaiyisheng.cc查看！4008787920(免长途)';
                //fangin暂时屏蔽
                Push_message_intab($uid,$tel['telphone'],"邮件",$mes,"system");
                //$sendmsg_ret = siooSendMsg($tel['telphone'], $mes);
                /*$dateline = time();
                if( $sendmsg_ret ){
                    $sql = "INSERT INTO {$GLOBALS['dbTablePre']}smslog(sid,uid,content,sendtime) VALUES('{$GLOBALS['adminid']}','{$uid}','{$mes}','{$dateline}')";
                    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
                }*/
            }
        }
    
        //发e-mail提醒(站内信)-------开始////////////////////////////////////////////?????????
        include_once("./include/crontab_config.php");
        //$members_search = MooMembersData($from);
        //$members_choice = MooGetData('members_choice', 'uid', $from);
        $send_user_info = array_merge((array)MooGetData('members_choice', 'uid', $from),(array)MooMembersData($from));
        /*$send_user_info = $GLOBALS['_MooClass']['MooMySQL']->getAll("select * from `{$GLOBALS['dbTablePre']}members_search` a  left join  {$GLOBALS['dbTablePre']}members_choice b  on a.uid=b.uid  where a.uid = '$from'");
        $send_user_info = $send_user_info[0];*/
        //头像路径
        $path = thumbImgPath(2,$send_user_info['pic_date'],$send_user_info['pic_name'],$send_user_info['gender']);
        if(file_exists($path)){
         $img_path = $path;
        }else{
            if($send_user_info['gender'] == 1){
                $img_path = "/public/images/service_nopic_woman.gif";
            }else{
                $img_path = "/public/images/service_nopic_man.gif";
            }
        }
        $send_username = $send_user_info['nickname'] ?  $send_user_info['nickname'] : $send_user_info['uid'];//发送者用户名
        $send_user_grade = $send_user_info['gender'] == 1 ? "女" : "男"; //发送者性别
        $province = isset($provice_list[$send_user_info['province']]) ? $provice_list[$send_user_info['province']] : '';//省
        $city = isset($city_list[$send_user_info['city']]) ? $city_list[$send_user_info['city']] : ''; //市
        $height = $send_user_info['height'] ? $height_list[$send_user_info['height']] : "未知"; //身高
        ob_start();
        require_once(adminTemplate('mail/mail_space_commissiontpl'));
        $body = ob_get_clean();
        MooSendMail($user['username'],"真爱一生网系统温馨提示",$body,"",false,$uid);
        //--------->发送邮件提醒  结束///////////////////////////////////////////
        
        
        
        if( $ret ) echo 1; else echo 0;
        //写日志
        serverlog(3,$GLOBALS['dbTablePre'].'services',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}向会员{$uid}发送站内信",$GLOBALS['adminid'],$uid);
        exit;
    }
    
    //note 会员认证
    if( $type==4){
        $change = MooGetGPC('change','string','G');
        $a = MooGetGPC('a','integer','G');
        $str = $a ? '已通过' : '未通过';
        
        //note 下面判断的作用是防止certification表中无该会员的记录，导致认证失败
        $sql = "SELECT uid FROM {$GLOBALS['dbTablePre']}certification WHERE uid = {$uid}";
        $ret = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
        if(empty($ret)){
            $sql = "INSERT INTO {$GLOBALS['dbTablePre']}certification(uid) VALUE({$uid})";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            if(MOOPHP_ALLOW_FASTDB) {
                $value['lastvisit']=$GLOBALS['timestamp'];
                MooFastdbUpdate('members_login','uid',$sendid,$value);
            }
        }

        switch($change){
            case 'telphone':
                if($a){
                    $sql = "SELECT telphone FROM {$GLOBALS['dbTablePre']}members_search WHERE uid = {$uid}";
                    $user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
                    if(empty($user['telphone'])){
                        $telphone = '12345678900';
                    }else{
                        $telphone = $user['telphone'];
                    }
                }else{
                    $telphone = '';
                }
                
                $sql = "UPDATE {$GLOBALS['dbTablePre']}certification SET telphone = '{$telphone}' WHERE uid = {$uid}";
                $ret = $GLOBALS['_MooClass']['MooMySQL']->query($sql);

                //if(MOOPHP_ALLOW_FASTDB){MooFastdbUpdate('certification','uid',$uid);}
                
                
                
                if(MOOPHP_ALLOW_FASTDB){
                    //$value =  $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT * FROM {$GLOBALS['dbTablePre']}certification  WHERE `uid`='{$uid}'  LIMIT 1");
                    $value = array();
                    $value['telphone'] = $telphone;
                    MooFastdbUpdate("certification",'uid',$uid,$value);
                }

                reset_integrity($uid);
                
                //写日志
                serverlog(2,$GLOBALS['dbTablePre'].'certification',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}将会员{$uid}的手机认证修改为{$str}",$GLOBALS['adminid'],$uid);
                echo $ret ? 'ok' : 'error';exit;
            break;
            case 'sms':
                
                $sql = "UPDATE {$GLOBALS['dbTablePre']}certification SET sms = {$a} WHERE uid = {$uid}";
                $ret = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
                //if(MOOPHP_ALLOW_FASTDB){MooFastdbUpdate('certification','uid',$uid);}
                
                
                if(MOOPHP_ALLOW_FASTDB){
                    //$value =  $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT * FROM {$GLOBALS['dbTablePre']}certification  WHERE `uid`='{$uid}'  LIMIT 1");                    
                    $value = array();
                    $value['sms'] = $a;
                    MooFastdbUpdate("certification",'uid',$uid,$value);
                }
                reset_integrity($uid);
                //写日志
                serverlog(2,$GLOBALS['dbTablePre'].'certification',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}将会员{$uid}的身份通认证修改为{$str}",$GLOBALS['adminid'],$uid);
                echo $ret ? 'ok' : 'error';exit;
            break;
            case 'email':
                $e = $a ? 'yes' : '';
                
                $sql = "UPDATE {$GLOBALS['dbTablePre']}certification SET email = '{$e}' WHERE uid = {$uid}";
                $ret = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
                //if(MOOPHP_ALLOW_FASTDB){MooFastdbUpdate('certification','uid',$uid);}
                
                
                if(MOOPHP_ALLOW_FASTDB){
                    //$value =  $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT * FROM {$GLOBALS['dbTablePre']}certification  WHERE `uid`='{$uid}'  LIMIT 1");
                    $value = array();
                    $value['email'] = $e;
                    MooFastdbUpdate("certification",'uid',$uid,$value);
                }
                reset_integrity($uid);
                //写日志
                serverlog(2,$GLOBALS['dbTablePre'].'certification',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}将会员{$uid}的邮箱认证修改为{$str}",$GLOBALS['adminid'],$uid);
                echo $ret ? 'ok' : 'error';exit;
            break;
            default:
                if(empty($change)) break;
                $v = $a ? 3 : 1;
                if($change=='video'){ $change2 = '视频';}
                elseif($change == 'house'){ $change2 = '房产';}
                elseif($change == 'salary'){ $change2 = '工资';}
                elseif($change == 'occupation'){ $change2 = '工作';}
                elseif($change == 'education'){ $change2 = '学历';}
                elseif($change == 'marriage'){ $change2 = '婚育';}
                elseif($change == 'identity'){ $change2 = '身份证';}
        
                $sql = "UPDATE {$GLOBALS['dbTablePre']}certification SET {$change}_check = {$v} WHERE uid = {$uid}";
                $ret = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
        
                //if(MOOPHP_ALLOW_FASTDB){MooFastdbUpdate('certification','uid',$uid);}
                
                
                if(MOOPHP_ALLOW_FASTDB){                    
                    //$value = array();
                    $value[$change."_check"] = $v;
                    MooFastdbUpdate("certification",'uid',$uid,$value);
                }
                reset_integrity($uid);
                
                //写日志
                serverlog(2,$GLOBALS['dbTablePre'].'certification',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}将会员{$uid}的{$change2}认证修改为{$str}",$GLOBALS['adminid'],$uid);
                echo $ret ? 'ok' : 'error';exit;
            break;
        }
        $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}certification WHERE uid = {$uid}";
        $cert = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    }
    //note 发彩信北京易速彩信
    /*if($type ==5){
        //header("Content-Type: text/html; charset=utf-8");
        $page = max(1,MooGetGPC('page','integer','G'));
        $limit = 10;
        $offset = ($page-1)*$limit;
        $gopage = MooGetGPC('gopage','integer','G');
        if($ispost){
            $mes = MooGetGPC('tel_message','string','P');
            $title = MooGetGPC('title','string','P');           
            
            $pic_id=MooGetGPC('pic_id','string','P');
            $pic_name=($pic_id*3)."_medium.jpg";
            $pic_path="./../data/upload/userimg/";
            $user_pic=$pic_path.$pic_name;
            
            $dateline = time();
            $dateline2=$dateline+300;
            
            $sql1="SELECT * FROM {$GLOBALS['dbTablePre']}mmslog where uid='$uid' and title='$title' and content='$mes' and sendtime<'$dateline2'";
            //error_log($sql1);
            $msg_inf = $GLOBALS['_MooClass']['MooMySQL']->query($sql1);
            $msg_num=$GLOBALS['_MooClass']['MooMySQL']->numRows($msg_inf);
            //error_log($msg_num);
            if($msg_num<2){ 
                $mes_64=base64_encode($mes);
                $pic_64=base64_encode(file_get_contents($user_pic));
                $content="1,txt,$mes_64;2,jpg,$pic_64";
                $ret = send_mms($title,$tel,$content);
                
                if( $ret == "ok"){
                    $sql = "INSERT INTO {$GLOBALS['dbTablePre']}mmslog(sid,uid,title,content,sendtime) VALUES('{$GLOBALS['adminid']}','{$uid}','{$title}','{$mes}','{$dateline}')";
                    $sid = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
                    echo 1; 
                }else{
                    echo 0;
                }
                //写日志
                serverlog(3,$GLOBALS['dbTablePre'].'mmslog',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}向会员{$uid}发送彩信{$sid}",$GLOBALS['adminid'],$uid);
                exit;   
            }else echo "你已经发过两条同样的彩信了！";
        }
        $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}mmspre WHERE user = {$GLOBALS['adminid']} OR user = 0 LIMIT {$offset},{$limit}";
        $sms = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
                
        $total = getcount('mmspre',"WHERE user = {$GLOBALS['adminid']} OR user = 0");
        $pages = ceil($total/$limit);
        if( $gopage ){
            foreach( $sms as $k=>$v){
                $content = str_replace('[adminid]',$GLOBALS['adminid'],$v['content']);
                
                preg_match("/\[smstitle\](.+?)\[\/smstitle\]/isU",$content,$matche1);
                preg_match("/\[\/smstitle\](.+?)/isU",$content,$matche2);
                
                if ($matche1[1]){
                    echo '<div style="padding:3px; cursor:pointer;" onclick="selectThis('.(($page-1)*$limit+$k+1).');">'.(($page-1)*$limit+$k+1).'.<font color="#0033CC">'.$matche1[1].'</font><span id="sms_content_'.(($page-1)*$limit+$k+1).'">'.$matche2[1].'</span></div>';
                }else{
                    echo '<div style="padding:3px; cursor:pointer;" onclick="selectThis('.(($page-1)*$limit+$k+1).');">'.(($page-1)*$limit+$k+1).'.<span id="sms_content_'.(($page-1)*$limit+$k+1).'">'.$content.'</span></div>';
                }
            }
            exit;
        }
    }*/
    if($type ==5){//上海亿美彩信
        //header("Content-Type: text/html; charset=GB2312");
        $page = max(1,MooGetGPC('page','integer','G'));
        $limit = 10;
        $offset = ($page-1)*$limit;
        $gopage = MooGetGPC('gopage','integer','G');        
        if($ispost){
            $dateline = time();
            $dateline2=$dateline+300;
            $today=mktime(0,0,0,date('m',time()),date('d',time()),date('Y',time()));
            $mes = MooGetGPC('tel_message','string','P');
            $title = MooGetGPC('title','string','P');
            $tel_message=$mes;
            $sql1="SELECT * FROM {$GLOBALS['dbTablePre']}mmslog where uid='$uid' and title='$title' and content='$mes' and sendtime<'$dateline2'";
            echo$sql1;
            $msg_inf = $GLOBALS['_MooClass']['MooMySQL']->query($sql1);
            $msg_num=$GLOBALS['_MooClass']['MooMySQL']->numRows($msg_inf);
            $sql2="SELECT count(1) as num from {$GLOBALS['dbTablePre']}mmslog where sid='{$GLOBALS['adminid']}' and sendtime<'{$dateline}' and sendtime>'{$today}'";
            $today_sendnum=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql2,true);
            if($today_sendnum['num']>30){echo "你今天已经发送30条了，不可以再发送彩信了！请节约使用。";exit();}
            //echo $msg_num;exit;
            if($msg_num<2){
                include("include/crontab_config.php");
                $pic_path="./../data/upload/userimg/";
                $file_path="data/upload";                 
                $mes = iconv("UTF-8", "gb2312", "$mes");
                
                $mkdirs = MooAutoLoad('MooFiles');
                $image = MooAutoLoad('MooImage');
                $pic_id_list=MooGetGPC('pic_id','string','P')."0";
                $user_image_list=MooGetGPC('image_list','string','P');
                $user_image_arr=explode(",",$user_image_list);
                $sql="SELECT m.uid,m.nickname,m.province,m.city,m.gender,m.pic_name,m.birthyear,c.age1,c.age2,c.workprovince,c.workcity FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}members_choice c on m.uid=c.uid WHERE m.uid in ($pic_id_list)";
                $userinfo_arr = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
                
                $file_name_smil='temp' . time() . rand(10000, 99999);
                $file_array=$file_path."/".$file_name_smil.".smil,".$file_path."/".$file_name_smil.".txt";
                foreach($userinfo_arr as $key=>$userinfo){
                    $file_name='temp' . time() . rand(10000, 99999).$key;
                    $need_del_file[]=$file_name;
                    $nickname1="昵称:".$userinfo['nickname'];
                    $nickname= iconv("UTF-8", "gb2312", "$nickname1");
                    $txt=" ID:".$userinfo['uid']."(".(gmdate('Y', time()) - $userinfo['birthyear'])."岁)，居住在".(($provice_list[$userinfo['province']]||$city_list[$userinfo['city']])?$provice_list[$userinfo['province']].$city_list[$userinfo['city']]:"未填")."的".($userinfo['gender']?'女士':'男士')."寻找一位年龄在".$userinfo['age1']."-".$userinfo['age2']."岁，居住".(($provice_list[$userinfo['workprovince']]||$city_list[$userinfo['workcity']])?$provice_list[$userinfo['workprovince']].$city_list[$userinfo['workcity']]:"未填")."的".($userinfo['gender']?'男士':'女士');
                    $txt1 = iconv("UTF-8", "gb2312", "$txt");
                    $txt=$nickname.$txt1;
                    $mkdirs->fileWrite($file_path."/".$file_name.".txt",$txt);
                    $pic_name_old=MooGetphotoAdmin($userinfo['uid'],'medium');
                    $pic_only_name=($userinfo['uid']*3)."_medium.gif";

                    $image->config(array('thumbDir'=>$file_path.'/','thumbStatus'=>'1','saveType'=>'0','thumbName' =>$pic_only_name,'waterMarkMinWidth' => '100', 'waterMarkMinHeight' => '125', 'waterMarkStatus' => 9));
                    $image->thumb('100','125',$pic_name_old);
                    
                    $pic_name_file=$file_path.'/'.$pic_only_name;
                   
                    
                    $pic='http://www.zhenaiyisheng.cc/'.$pic_name_old;
                    
			        $pic = file_get_contents($pic);
			        
			        file_put_contents($pic_name_file,$pic);
                    
                    
                    //echo $pic_name_file;exit;
                    //$pic_name="5226417_mid.jpg";
                    $par=$par.'<par dur="50000ms"><img src="'.$pic_only_name.'" region="Image" /><text src="'.$file_name.'.txt" region="Text" /></par>';
                    $file_array=$file_array.",".$file_path."/".$file_name.".txt,".$pic_name_file;
                    foreach($user_image_arr as $key=>$user_image){
                        if($user_image){                            
                            //$image_only_name=substr($user_image,45,36);
                            $image_only_name=$file_name.$key.".gif";
                            list($width,$height)=getimagesize("..".$user_image);
                            $d = $width / $height;
                            $c=85/100;
                            if($d<$c){
                                $thumb1_width=85;
                                $b=$width/$thumb1_width;
                                $thumb1_height=$height/$b;
                            }else{
                             $thumb1_height=100;
                             $b=$height/$thumb1_height;
                             $thumb1_width=$width/$b;
                            }
                            $big_path=$file_path.'/';

                            
                            $image->config(array('thumbDir'=>$big_path,'thumbStatus'=>'1','saveType'=>'0','thumbName' =>$image_only_name,'waterMarkMinWidth' => '82', 'waterMarkMinHeight' => '114', 'waterMarkStatus' => 9));
                            $image->thumb($thumb1_height,$thumb1_width,'..'.$user_image);
                            $need_product_img[]=$big_path.$image_only_name;

                            $file_array=$file_array.",".$file_path.'/'.$image_only_name;
                            $par=$par.'<par dur="50000ms"><img src="'.$image_only_name.'" region="Image" /></par>';
                        }
                    }
                }
                $mkdirs->fileWrite($file_path."/".$file_name_smil.".txt",$mes);
                $user_pic=$pic_path;
                
                $smil='<smil xmlns="http://www.w3.org/2000/SMIL20/CR/Language"><head><layout><root-layout width="208" height="176" /><region id="Image" left="0" top="0" width="128" height="128" fit="hidden" /><region id="Text" left="0" top="50" width="128" height="128" fit="hidden" /></layout></head><body><par dur="50000ms"><text src="'.$file_name_smil.'.txt" region="Text" /></par>'.$par.'</body></smil>';
                $mkdirs->fileWrite($file_path."/".$file_name_smil.".smil",$smil);
                
                require 'include/pclzip_new.lib.php';
                $archive = new PclZip($file_path."/".$file_name_smil.'.zip');
                $v_list = $archive->create($file_array,PCLZIP_OPT_REMOVE_ALL_PATH);
                if($v_list == 0){
                    die("Error : ".$archive->errorInfo(true));
                }
                $file_size=filesize($file_path."/".$file_name_smil.'.zip'); 
                if($file_size>45*1024){
                    echo "文件过打，请删除部分图片";
                    $mkdirs->fileDelete($file_path."/".$file_name_smil.'.zip');
                    $mkdirs->fileDelete($file_path."/".$file_name_smil.'.txt');
                    $mkdirs->fileDelete($file_path."/".$file_name_smil.'.smil');
                    $mkdirs->fileDelete($file_path."/".$pic_only_name);
                    if($need_del_file){
                        foreach($need_del_file as $del_file){
                            $mkdirs->fileDelete($file_path."/".$del_file.'.txt');
                        }
                    }
                    if($need_product_img){
                        foreach($need_product_img as $del_img){
                            $mkdirs->fileDelete($del_img);
                        }
                    }
                    exit;
                }
                $ret = send_mms_yimei($title,$tel,$file_name_smil.'.zip');              
                $ret_ok=substr($ret,0,2);               
                if( $ret_ok == "OK"){
                    $sql = "INSERT INTO {$GLOBALS['dbTablePre']}mmslog(sid,uid,title,content,sendtime,id_list) VALUES('{$GLOBALS['adminid']}','{$uid}','{$title}','{$tel_message}','{$dateline}','{$pic_id_list}')";
                    $sid = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
                    echo 1; 
                }else{
                    echo 0;
                }
                $mkdirs->fileDelete($file_path."/".$file_name_smil.'.zip');
                $mkdirs->fileDelete($file_path."/".$file_name_smil.'.txt');
                $mkdirs->fileDelete($file_path."/".$file_name_smil.'.smil');
                $mkdirs->fileDelete($file_path."/".$pic_only_name);
                if($need_del_file){
                    foreach($need_del_file as $del_file){
                        $mkdirs->fileDelete($file_path."/".$del_file.'.txt');
                    }
                }
                if($need_product_img){
                        foreach($need_product_img as $del_img){
                            $mkdirs->fileDelete($del_img);
                        }
                }
                //写日志
                serverlog(3,$GLOBALS['dbTablePre'].'mmslog',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}向会员{$uid}发送彩信{$sid}",$GLOBALS['adminid'],$uid);
                exit;   
            }else {echo "你已经发过两条同样的彩信了！";exit;}
        }
        $sql="SELECT count(distinct senduid) as num FROM {$GLOBALS['dbTablePre']}service_rose where receiveuid='{$uid}' and receive_del=0 and send_del=0";
        $send_rose = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);

        //$sql="SELECT count(1) as num FROM {$GLOBALS['dbTablePre']}service_leer where receiveuid='{$uid}' and receive_del=0 and send_del=0 and is_server=0";
        //$send_leer = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);

        $sql="SELECT count(1) as num FROM {$GLOBALS['dbTablePre']}service_contact where you_contact_other='{$uid}' and receive_del=0 and send_del=0 and is_server=0";
        $send_contact = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);

        //$sql="SELECT count(1) as num FROM {$GLOBALS['dbTablePre']}service_friend where friendid='{$uid}' ";
        //$send_friend = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);

        //$sql="SELECT count(distinct cuid) as num FROM {$GLOBALS['dbTablePre']}members_comment where uid='{$uid}' and receive_del=0 and send_del=0";
        //$send_comment = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
        
    }
    require_once(adminTemplate('allmember_contact'));
}
//为发送彩信获得用户的互动信息鲜花秋波等
function ajax_mms_getact(){
    $uid = MooGetGPC('uid','integer','G');
    $kinds=MooGetGPC('kinds','string','G');
    if($kinds=='comment'){
        $sql="SELECT cuid as uid,max(dateline) as sendtime FROM {$GLOBALS['dbTablePre']}members_comment where uid={$uid} and is_pass=1 and receive_del=0 and send_del=0 group by cuid";
        $user_active=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    }elseif($kinds=='rose'){
        $sql="SELECT senduid as uid,sendtime  FROM {$GLOBALS['dbTablePre']}service_rose where receiveuid={$uid} and receive_del=0 and send_del=0 order by sendtime desc";
        $user_active=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    }elseif($kinds=='contact'){
        $sql="SELECT other_contact_you as uid,sendtime FROM {$GLOBALS['dbTablePre']}service_contact where you_contact_other={$uid} and receive_del=0 and send_del=0 and is_server=0 order by sendtime desc";
        $user_active=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    }elseif($kinds=='leer'){
        $sql="SELECT senduid as uid,receivetime as sendtime FROM {$GLOBALS['dbTablePre']}service_leer where receiveuid={$uid} and receive_del=0 and send_del=0 order by receivetime desc";
        $user_active=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    }elseif($kinds=='friend'){
        $sql="SELECT uid,sendtime FROM {$GLOBALS['dbTablePre']}service_friend where friendid='{$uid}' order by sendtime desc";
        $user_active=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    }
    require_once(adminTemplate('allmember_userinf_mms'));
}


//note 检测用户ID信息
function ajax_check_searchid(){
    $idname = MooGetGPC('idname','string','G');
    $id = MooGetGPC('id','integer','G');
    $kinds=MooGetGPC('kinds','string','G');
    $sql = ($idname == 'recommend') ? "SELECT m.uid,m.nickname,m.gender,m.usertype,m.birthyear,m.telphone,m.username,f.qq FROM {$GLOBALS['dbTablePre']}members_search m left join {$GLOBALS['dbTablePre']}members_base f on m.uid=f.uid WHERE m.uid='{$id}'" : "SELECT uid,nickname,gender,usertype,birthyear,pic_name,pic_num,province,city FROM {$GLOBALS['dbTablePre']}members_search WHERE uid='{$id}'";
    $userinfo = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    if(empty($userinfo)){
        echo json_encode(array('0'=>'no'));
    }else{
        if($userinfo['pic_num']>0){
            $sql="SELECT * FROM {$GLOBALS['dbTablePre']}pic where uid={$id} and isimage=0 and syscheck=1";
            $pic_info=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
            foreach($pic_info as $key=>$image){
                $pic_file[$key]=thumbImgPath("2",$image['pic_date'],$image['pic_name']);
            }
        }
        $sql="SELECT title,content FROM {$GLOBALS['dbTablePre']}mmspre WHERE is_read=0 and type='{$kinds}'";
        $mmspre_info = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
        $pic_filename=MooGetphotoAdmin($id,'small','jpg');
        echo json_encode(array('0'=>$userinfo,'1'=>$idname,'2'=>$pic_filename,'3'=>$pic_file,'4'=>$mmspre_info));
    }
}
//note 短信记录
function ajax_message(){    
    $t=MooGetGPC('t','integer','G');    
    $t=$t?$t:1;
    $uid = MooGetGPC('uid','integer','G');
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 10;
    $offset = ($page-1)*$limit; 
    if($t==1){      
        $sql = "SELECT s.*, u.name FROM {$GLOBALS['dbTablePre']}smslog s LEFT JOIN {$GLOBALS['dbTablePre']}admin_user u ON s.sid = u.uid                WHERE s.uid = {$uid} LIMIT {$offset},{$limit}";             
        $messages = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);        
    }elseif($t==2){
        $sql="SELECT * FROM {$GLOBALS['dbTablePre']}smslog_sys where uid='$uid' LIMIT {$offset},{$limit}";
        $messages = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);        
    }else{
        $kefu_list = get_kefulist();
        $sql="SELECT * FROM {$GLOBALS['dbTablePre']}uplinkcontent where uid='$uid' LIMIT {$offset},{$limit}";
        $messages = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    }
    
    $stotal = getcount('smslog_sys','WHERE uid = '.$uid);
    $total = getcount('smslog','WHERE uid = '.$uid);
    $mtotal = getcount('uplinkcontent','WHERE uid = '.$uid);
    
    if($t==1){
        $pages = ceil($total/$limit);
    }elseif($t==2){
    	$pages = ceil($stotal/$limit);
    }elseif($t==3){
    	$pages = ceil($mtotal/$limit);
    }
    
    require_once(adminTemplate('allmember_message'));
}
//note 聊天记录
function ajax_chatrecord(){
    $uid = MooGetGPC('uid','integer','G');
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 10;
    $offset = ($page-1)*$limit;
    
	
    $data = getChatList($uid,false,0,$page,$limit);
    if(is_array($data) && !empty($data)){
	    $chats = $data['data'];
	    if(is_array($chats) && !empty($chats)){
		 	foreach($chats as $k=>$v){
		    	$chats2[$k]['s_id'] = $v['id'];
		    	$chats2[$k]['s_uid'] = $v['toid'];
		    	$chats2[$k]['s_fromid'] = $v['fromid'];
		    	$chats2[$k]['s_content'] = str_replace('src="module/chat','src="http://'.$_SERVER['HTTP_HOST'].'/module/chat',$v['content']);
		    	$chats2[$k]['s_time'] = $v['time'];
		    	$chats2[$k]['s_status'] = $v['status'];
		    	$chats2[$k]['is_server'] = $v['serverid'];
		    	$chats2[$k]['dealstate'] = $v['isdeal'];

		    }
	    }
	    $chats = $chats2;
	    $total = $data['total'];
    }else{
    	$chats = array();
    	$total = 0;
    }
    
    /*$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}service_chat WHERE s_uid = {$uid} OR s_fromid = {$uid} ORDER BY s_id DESC LIMIT {$offset},{$limit}";
    $chats = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    $total = getcount('service_chat',"WHERE s_uid = {$uid} OR s_fromid = {$uid}");*/
    $pages = ceil($total/$limit);
    require_once(adminTemplate('allmember_chatrecord'));
}
//note 站内信箱
function ajax_letter(){
    $uid = MooGetGPC('uid','integer','G');
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 10;
    $offset = ($page-1)*$limit;
    $t = MooGetGPC('t','integer','G');
    $t = $t ? $t : 1;
    
    if($t==1){//收件箱,过滤出系统自动发送的
        $sql = "SELECT s.*,m.lastvisit FROM {$GLOBALS['dbTablePre']}services s
                LEFT JOIN {$GLOBALS['dbTablePre']}members_login m
                ON s.s_fromid = m.uid
                WHERE s.s_uid={$uid} AND s.s_fromid !=0 ORDER BY s_id DESC LIMIT {$offset},{$limit}";
        $letters = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
        $total = getcount('services','WHERE s_fromid !=0 AND s_uid='.$uid);     
    }else{//发件箱
        $sql = "SELECT s.*,m.lastvisit FROM {$GLOBALS['dbTablePre']}services s
                LEFT JOIN {$GLOBALS['dbTablePre']}members_login m
                ON s.s_uid = m.uid
                WHERE s.s_fromid={$uid} ORDER BY s_id DESC LIMIT {$offset},{$limit}";
        $letters = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
        $total = getcount('services','WHERE s_fromid='.$uid);
    }
    $scount = getcount('services','WHERE s_fromid !=0 AND s_uid='.$uid);
    $fcount = getcount('services','WHERE s_fromid='.$uid);
    $pages = ceil($total / $limit);
    require_once(adminTemplate('allmember_letter'));
}
//note 发送委托、秋波、鲜花
function ajax_sendactive(){
    $type = MooGetGPC('type','string','G');
    $sendid = MooGetGPC('from','integer','G');
    $receiveuid = MooGetGPC('to','integer','G');

    if(empty($type)||empty($sendid)||empty($receiveuid)){exit('errors');}
    if(!in_array($type, array('commission','leer','rose'))){exit('errors');}
    $nowtime=time();
    $user = MooMembersData($receiveuid);
    $senduser = MooMembersData($sendid);
 
    $gender = $senduser['gender']==1?"美女":"帅哥";
	
	//全权会员操作日志，白名单
    if($user['usertype']!=3 && $senduser['usertype']==3){
    	$full_log=$GLOBALS['_MooClass']['MooMySQL']->getOne ( 'select uid from web_full_log where uid =' . $sendid ,true);
    	if(empty($full_log)){
    		$GLOBALS['_MooClass']['MooMySQL']->query ('INSERT INTO web_full_log (uid,action_time) values('.$sendid.','.time().')' );
    	}
    	$white=$GLOBALS['_MooClass']['MooMySQL']->getOne ( 'select id from web_white_list where uid =' . $sendid.' and anotheruid='.$receiveuid ,true);
    	if(empty($white)){
    		$GLOBALS['_MooClass']['MooMySQL']->query('insert INTO `web_white_list` (`uid`,`anotheruid`) VALUES ('.$sendid.','.$receiveuid.')');
    	}
    }

    switch($type){
        case 'commission':
            $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}service_contact WHERE you_contact_other = '$sendid' AND other_contact_you = '$receiveuid' ";
            $com = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
            
            if(empty($com) ){
                $sql = "INSERT INTO {$GLOBALS['dbTablePre']}service_contact SET `you_contact_other`='".$sendid."',`other_contact_you`='".$receiveuid."',`stat`=1,`syscheck`=1,`sendtime`='".$nowtime."',`is_server`=1";
                $result=$GLOBALS['_MooClass']['MooMySQL']->query($sql);
            }else{
                echo 'ok';exit;
            }

            //更改最后活动时间,上线
            $sql = "UPDATE {$GLOBALS['dbTablePre']}members_login SET lastvisit='{$GLOBALS['timestamp']}' WHERE uid='{$sendid}'";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            
            //发e-mail提醒开始
            include_once("./include/crontab_config.php");
            
            $send_user_info = array_merge(MooGetData('members_choice', 'uid', $sendid), MooMembersData($sendid));
            
           /* $send_user_info = $GLOBALS['_MooClass']['MooMySQL']->getAll("select * from `{$GLOBALS['dbTablePre']}members` a  left join  {$GLOBALS['dbTablePre']}choice b  on a.uid=b.uid  where a.uid = '$sendid'");
            $send_user_info = $send_user_info[0];*/
            //头像路径
            $path = thumbImgPath(2,$send_user_info['pic_date'],$send_user_info['pic_name'],$send_user_info['gender']);
            if(file_exists($path)){
             $img_path = $path;
            }else{
                if($send_user_info['gender'] == 1){
                    $img_path = "/public/images/service_nopic_woman.gif";
                }else{
                    $img_path = "/public/images/service_nopic_man.gif";
                }
            }
            $send_username = $send_user_info['nickname'] ?  $send_user_info['nickname'] : $send_user_info['uid'];//发送者用户名
            $send_user_grade = $send_user_info['gender'] == 1 ? "女" : "男"; //发送者性别
            $province = $provice_list[$send_user_info['province']];//省
            $city = $city_list[$send_user_info['city']]; //市
            $height = $send_user_info['height'] ? $height_list[$send_user_info['height']] : "未知"; //身高
            ob_start();
            require_once(adminTemplate('mail/mail_space_commissiontpl'));
            $body = ob_get_clean();
            MooSendMail($user['username'],"真爱一生网系统温馨提示",$body,"",false,$receiveuid);
            //发送邮件提醒结束
 
            Push_message_intab($receiveuid,$user['telphone'],"委托", "尊敬的会员您好! ID：{$sendid}的{$gender}委托红娘联系您，请及时登录www.zhenaiyisheng.cc把握您的缘分，幸福热线：400-8787-920",$sendid);
            //写日志
            serverlog(3,$GLOBALS['dbTablePre'].'service_contact',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台以会员{$sendid}的身份向{$receiveuid}会员发送委托",$GLOBALS['adminid'],$receiveuid);
            echo 'ok';exit;
        break;
        case 'leer':
            $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}service_leer WHERE senduid = '$sendid' AND receiveuid= '$receiveuid' ";
            $leer = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
            if(!empty($leer)) {
               $lid = $leer['lid'];
               //note 如果已经发送过秋波，就增加发送秋波的次数
               $sql = "UPDATE {$GLOBALS['dbTablePre']}service_leer SET fakenum = fakenum + 1,num = num + 1,receivenum = receivenum + 1,sendtime = '$nowtime',receivetime = '$nowtime',receive_del = '0' WHERE lid = '$lid'";
               $GLOBALS['_MooClass']['MooMySQL']->query($sql);      
               //note 如果已经收到这个人的秋波，已经拒绝，现在改变注意，又发送秋波给这个人,拒绝状态2更改为0
               if($leer['receive_del'] == 1 || $leer['stat'] == 2){
                    $sql = "UPDATE {$GLOBALS['dbTablePre']}service_leer SET stat = '0',receive_del = 0 WHERE senduid = '$sendid' AND receiveuid = '$receiveuid' AND stat = '2'";
                    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
               }
            }else {
                //note 发送新的秋波，写入数据库 发送者，接受者，发送时间
                $sql = "INSERT INTO {$GLOBALS['dbTablePre']}service_leer SET sendtime = '$nowtime',receivetime = '$nowtime',fakenum = '1', receivenum = '1', num = '1', senduid  = '{$sendid}',receiveuid = '$receiveuid',is_server=1";
                $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            }
            
            //更改最后活动时间,上线
            $sql = "UPDATE {$GLOBALS['dbTablePre']}members_login SET lastvisit='{$GLOBALS['timestamp']}' WHERE uid='{$sendid}'";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);

            //发e-mail提醒开始
            include_once("./include/crontab_config.php");
            $send_user_info = array_merge(MooGetData('members_choice', 'uid', $sendid), MooMembersData($sendid));
            /*$send_user_info = $GLOBALS['_MooClass']['MooMySQL']->getAll("select * from `{$GLOBALS['dbTablePre']}members` a  left join  {$GLOBALS['dbTablePre']}choice b  on a.uid=b.uid  where a.uid = '$sendid'");
            $send_user_info = $send_user_info[0];*/
            //头像路径
            $path = thumbImgPath(2,$send_user_info['pic_date'],$send_user_info['pic_name'],$send_user_info['gender']);
            if(file_exists($path)){
             $img_path = $path;
            }else{
                if($send_user_info['gender'] == 1){
                    $img_path = "/public/images/service_nopic_woman.gif";
                }else{
                    $img_path = "/public/images/service_nopic_man.gif";
                }
            }
            $send_username = $send_user_info['nickname'] ?  $send_user_info['nickname'] : $send_user_info['uid'];//发送者用户名
            $send_user_grade = $send_user_info['gender'] == 1 ? "女" : "男"; //发送者性别
            $province = $provice_list[$send_user_info['province']];//省
            $city = $city_list[$send_user_info['city']]; //市
            $height = $send_user_info['height'] ? $height_list[$send_user_info['height']] : "未知"; //身高
            ob_start();
            require_once(adminTemplate('mail/mail_space_leertpl'));
            $body = ob_get_clean();
            MooSendMail($user['username'],"真爱一生网系统温馨提示",$body,"",false,$receiveuid);
            //发送邮件提醒结束
            
			
            Push_message_intab($receiveuid,$user['telphone'],"秋波", "尊敬的会员您好!ID：{$sendid}的{$gender}给您发送了秋波，请及时登录www.zhenaiyisheng.cc把握您的缘分，幸福热线：400-8787-920",$sendid);
            //写日志
            serverlog(3,$GLOBALS['dbTablePre'].'service_leer',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台以会员{$sendid}的身份向{$receiveuid}会员发送秋波",$GLOBALS['adminid'],$receiveuid);
            echo 'ok';exit;
        break;
        case 'rose':
            $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}service_rose WHERE senduid = '$sendid' AND receiveuid= '$receiveuid' ";
            $rose = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    
            if(!empty($rose)) {
            $rid = $rose['rid'];
               //note 如果已经发送过鲜花，就增加发送鲜花的次数
               $sql = "UPDATE {$GLOBALS['dbTablePre']}service_rose SET fakenum = fakenum + 1,num = num + 1,receivenum = receivenum + 1,sendtime = '$nowtime',receivetime = '$nowtime',receive_del = '0' WHERE rid = '$rid'";
               $GLOBALS['_MooClass']['MooMySQL']->query($sql);
               //note 如果用户已经将该鲜花记录删除，则要改变删除状态，不然鲜花不会在前台显示
               if($rose['receive_del'] == 1){
                    $sql = "UPDATE {$GLOBALS['dbTablePre']}service_leer SET receive_del = 0 WHERE senduid = '$sendid' AND receiveuid = '$receiveuid'";
                    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
               }
            }else{
                //note 发送新的鲜花，写入数据库 发送者，接受者，发送时间
                $sql = "INSERT INTO {$GLOBALS['dbTablePre']}service_rose SET sendtime = '$nowtime',receivetime = '$nowtime',fakenum = '1', receivenum = '1', num = '1', senduid  = '{$sendid}',receiveuid = '$receiveuid',is_server=1";
                $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            }
    
            //更改最后活动时间,上线
            $sql = "UPDATE {$GLOBALS['dbTablePre']}members_login SET lastvisit='{$GLOBALS['timestamp']}' WHERE uid='{$sendid}'";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            
            //发e-mail提醒开始
            include_once("./include/crontab_config.php");
            $send_user_info = array_merge(MooGetData('members_choice', 'uid', $sendid), MooMembersData($sendid));
            /*$send_user_info = $GLOBALS['_MooClass']['MooMySQL']->getAll("select * from `{$GLOBALS['dbTablePre']}members` a  left join  {$GLOBALS['dbTablePre']}choice b  on a.uid=b.uid  where a.uid = '$sendid'");*/
            $send_user_info = $send_user_info[0];
            //头像路径
            $path = thumbImgPath(2,$send_user_info['pic_date'],$send_user_info['pic_name'],$send_user_info['gender']);
            if(file_exists($path)){
             $img_path = $path;
            }else{
                if($send_user_info['gender'] == 1){
                    $img_path = "/public/images/service_nopic_woman.gif";
                }else{
                    $img_path = "/public/images/service_nopic_man.gif";
                }
            }
            $send_username = $send_user_info['nickname'] ?  $send_user_info['nickname'] : $send_user_info['uid'];//发送者用户名
            $send_user_grade = $send_user_info['gender'] == 1 ? "女" : "男"; //发送者性别
            $province = $provice_list[$send_user_info['province']];//省
            $city = $city_list[$send_user_info['city']]; //市
            $height = $send_user_info['height'] ? $height_list[$send_user_info['height']] : "未知"; //身高
            ob_start();
            require_once(adminTemplate('mail/mail_space_rosetpl'));
            $body = ob_get_clean();
            MooSendMail($user['username'],"真爱一生网系统温馨提示",$body,"",false,$receiveuid);
            //发送email结束

            Push_message_intab($receiveuid,$user['telphone'], "鲜花","尊敬的会员您好!ID：{$sendid}的{$gender}给您发送了鲜花，请及时登录www.zhenaiyisheng.cc把握您的缘分，幸福热线：400-8787-920",$sendid);
            //写日志
            serverlog(3,$GLOBALS['dbTablePre'].'service_rose',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台以会员{$sendid}的身份向{$receiveuid}会员发送鲜花",$GLOBALS['adminid'],$receiveuid);
            echo 'ok';exit;
            break;
        default:
               echo "errors";
            break;
        }
        exit;
}
//note 删除委托、秋波、鲜花
function ajax_delActive(){
    $type = MooGetGPC('type','string','G');
    $id = MooGetGPC('id','integer','G');
    $uid = MooGetGPC('uid','integer','G');
    switch($type){
        case 'commission':
            $sql = "DELETE FROM {$GLOBALS['dbTablePre']}service_contact WHERE mid = {$id}"; 
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            //写日志
            serverlog(4,$GLOBALS['dbTablePre'].'service_contact',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台删除委托{$id}",$GLOBALS['adminid'],$uid);
            echo 'ok';exit;
        break;
        case 'leer':
            $sql = "DELETE FROM {$GLOBALS['dbTablePre']}service_leer WHERE lid = {$id}";    
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            //写日志
            serverlog(4,$GLOBALS['dbTablePre'].'service_leer',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台删除秋波{$id}",$GLOBALS['adminid'],$uid);
            echo 'ok';exit;
        break;
        case 'rose':
            $sql = "DELETE FROM {$GLOBALS['dbTablePre']}service_rose WHERE rid = {$id}";    
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            //写日志
            serverlog(4,$GLOBALS['dbTablePre'].'service_rose',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台删除鲜花{$id}",$GLOBALS['adminid'],$uid);
            echo 'ok';exit;
        break;
        case 'friend':
            $sql = "DELETE FROM {$GLOBALS['dbTablePre']}service_friend WHERE fid = {$id}";  
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            //写日志
            serverlog(4,$GLOBALS['dbTablePre'].'service_friend',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台删除意中人{$id}",$GLOBALS['adminid'],$uid);
            echo 'ok';exit;
        break;      
    }
    echo 'err';exit;
}


//note 上传附件
function ajax_uploadfile(){
    global $_MooClass;
    //note 允许的文件后缀,增加允许文件后缀还要更改checkFileTitle函数里面的对应文件类型检测
    $allow_ext = array("jpg","gif","rar","png","bmp","wma","wav","mp3");
    //$allow_ext = array("wma","wav","mp3");
    if(isset($_FILES['upfile'])){
        $attachment = $_FILES['upfile'];
        $filename = trim($attachment['name']);
        $filesize = $attachment['size'];
        if(!$filesize){
            $filesize = 0;
        }
        $fileext = substr(strrchr($filename,'.'),1);
    
        //note 根据文件后缀名判断这个文件类型
        if(!in_array($fileext,$allow_ext)){
            echo "<script>window.parent.notUpload('2','".$fileext."')</script>";
            exit;
        }
        
        //note 根据文件二进制前两位标识判断这个文件类型
        $check_ext = checkFileTitle($attachment['tmp_name']);
        if(!in_array($check_ext,$allow_ext) || $check_ext == 'unknown'){
            echo "<script>window.parent.notUpload('3','".$filename."')</script>";
            exit;
        }
        
        //note 上传路径
        $path = "./../data/upload/emailattachment/";
    
        //note 调用文件操作类库，建立无限级目录
        $mkdirs = MooAutoLoad('MooFiles');
        !is_dir($path) && $mkdirs->fileMake($path,0777);
    
        //note 生成文件名
        $file_full_name = 'temp_' . date("Ymd") . '_' . time() . '_' . rand(10000, 99999) . '.' . $fileext;
        $file = $path . $file_full_name;
        while(file_exists($file)){
            $file = $path . $file_full_name;
        }
    
        //note 移动文件
        $is_ok = false;
        if (!move_uploaded_file($attachment['tmp_name'], $file)) {
            if (copy($attachment['tmp_name'], $file)) {
                $is_ok = true;
            } else {
                $is_ok = false;
            }
        } else {
            $is_ok = true;
        }
        if($is_ok){
            //写日志
            serverlog(3,$GLOBALS['dbTablePre'].'service',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台上传文件",$GLOBALS['adminid']);
            $filename = iconv("utf-8","gb2312", $filename);
            echo "<script>window.parent.UpdateMsg('".$filename."','".$file_full_name."','".$filesize."')</script>";
            exit;
        }
    }
}
//note 匹配搜索中获取会员头像
function ajax_getimg(){
    $uid=MooGetGPC('uid','integer','G');
    echo $img=MooGetphotoAdmin($uid,'big');
    serverlog(1,"","{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在匹配搜索中查看会员{$uid}的头像",$GLOBALS['adminid'],$uid);
}

//修改备注电话号码
function ajax_submitcallno(){
    $uid = MooGetGPC('uid','integer','G');
    $callno = trim(MooGetGPC('callno','string','G'));
    if(empty($uid)){
        echo 'no';exit;
    }
	
	
    
    if(!empty($callno)){
        $regex = "/^1[3|5]\d{9}$|^18\d{9}$/";
        $regph = "/^(\d{3}-)(\d{8})$|^(\d{4}-)(\d{7})$|^\d{8}$|^\d{7}$/";
        if(!preg_match($regex,$callno) && !preg_match($regph,$callno)){
            echo 'no';exit;
        }
    }
	
	$index_search = 'members_women members_man';
	$cl = searchApi($index_search);
	$cond = array();
	$cond[] = array('telphone',$callno,false);
	
	$rs2 = $cl -> getResultOfReset($cond);
	$total = 0;
	if(isset($rs2["total_found"])) $total = $rs2["total_found"];

	if(!empty($callno)){
		$result=$GLOBALS['_MooClass']['MooMySQL']->getOne("select uid from {$GLOBALS['dbTablePre']}members_base  where callno='$callno'");
		if (!empty($result['uid'])){
		   exit("yes");
		}
    }
	
    if($GLOBALS['_MooClass']['MooMySQL']->query("UPDATE {$GLOBALS['dbTablePre']}members_base SET callno='$callno' WHERE uid='$uid'")){
        serverlog(2,"{$GLOBALS['dbTablePre']}members_base","{$GLOBALS['adminid']}号客服{$GLOBALS['username']}将会员{$uid}的备用号码修改为{$callno}",$GLOBALS['adminid'],$uid);
        echo iconv("gb2312","UTF-8",getphone($callno));exit;//输出号码所在地
    }
}

//九型处理
function ajax_enneagram(){
    $h = MooGetGPC('h','string','G');
    $type = MooGetGPC('type','integer','G');
    if($h == 'list'){
        require_once(adminTemplate('allmember_enneagram'));
    }
    
    if($h == 'update'){
        $uid = MooGetGPC('uid','string','G');
        
        $query = $GLOBALS['_MooClass']['MooMySQL']->query("replace into {$GLOBALS['dbTablePre']}enneagram (uid,type) values ('{$uid}','{$type}')");

        if($query) {
            echo "1"; //note 操作成功
        }else{
            echo "0"; //note 操作失败
        }
    }
}
//note 客服操作记录
function ajax_kfmanage(){
    $uid = MooGetGPC('uid','integer','G');
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 15;
    $offset = ($page-1)*$limit;
    
    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}server_log  WHERE  type !=1 AND uid = '{$uid}' ORDER BY slid DESC LIMIT {$offset},{$limit}";
    $logs = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    
    $total = getcount('server_log','WHERE type !=1 AND uid='.$uid);
    $pages = ceil($total / $limit);
    require_once(adminTemplate('allmember_kfmanage'));
}

//note 电话流程
function ajax_telphone_order(){
    require_once(adminTemplate('allmember_telphone_order'));
}

//note 评价
function ajax_pingjia(){
    $uid = MooGetGPC('uid','integer','G');
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 8;
    $offset = ($page-1)*$limit;

    $t = MooGetGPC('t','integer','G');
    $t = $t ? $t : 1;
    $where_who = $t == 1 ? 'uid' : 'cuid' ;
    $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_comment 
        WHERE {$where_who} = '$uid' order by id desc LIMIT {$offset},{$limit}";
    $pingjia_list  = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    $total = getcount('members_comment', 'WHERE '.$where_who.'='.$uid );
    //var_dump( $total);
    $pages = ceil($total / $limit);
    $cocount1 = getcount('members_comment',"WHERE uid = '$uid'");
    $cocount2 = getcount('members_comment',"WHERE cuid = '$uid'");
    require_once(adminTemplate('allmember_comment'));
}

//
function chang_isphone(){
    $uid = MooGetGPC('uid','integer','G');
    $action_type = MooGetGPC('action_type','integer','G');
    //echo $action_type;
    $reslute=$GLOBALS['_MooClass']['MooMySQL']->query("update {$GLOBALS['dbTablePre']}members_base set is_phone='$action_type' where uid='$uid'");
    if($reslute){
        echo $action_type?"开启成功":"关闭成功";
    }
}

//得到模拟用户聊天的信息
function ajax_get_muli_uidinfo(){
    $uid = MooGetGPC('uid','integer','G');
    $sql = "SELECT uid,nickname FROM {$GLOBALS['dbTablePre']}members_search WHERE uid='{$uid}'";
    $userinfo = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    if(empty($userinfo)){
        echo json_encode(array('0'=>'no'));
    }else{
        echo json_encode($userinfo);
    }
}

//note 售后发送委托、秋波、鲜花
function ajax_sellsendactive(){
    $type = MooGetGPC('type','string','G');
    $sendid = MooGetGPC('from','integer','G');
    $receiveuid = MooGetGPC('to','integer','G');
    if($type==''||$sendid==''||$receiveuid==''||$sendid==0||$receiveuid==0){echo 'errors';}
    $nowtime=time();
    $user = MooMembersData($receiveuid);
    $senduser = MooMembersData($sendid);
    /*if(MOOPHP_ALLOW_FASTDB){
        $user = MooFastdbGet('members','uid',$receiveuid);
        $senduser = MooFastdbGet('members','uid',$sendid);
    }else{
        $sql = "SELECT username,telphone FROM {$GLOBALS['dbTablePre']}members WHERE `uid`='".$receiveuid."'";
        $user=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
        $sql = "SELECT gender FROM {$GLOBALS['dbTablePre']}members WHERE `uid`='".$sendid."'";
        $senduser=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    }*/
    $gender = $senduser['gender']==1?"女":"男";

    switch($type){
        case 'commission':
            //$sql = "SELECT mid,sendtime FROM {$GLOBALS['dbTablePre']}service_contact WHERE you_contact_other='$sendid' AND other_contact_you = '$userid' and receive_del=0 and send_del=0 and is_server=0";
            $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}service_contact WHERE you_contact_other = '$receiveuid' AND other_contact_you = '$sendid' ";
            $com = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
            if(empty($com) ){
                $sql = "INSERT INTO {$GLOBALS['dbTablePre']}service_contact SET `you_contact_other`='".$receiveuid."',`other_contact_you`='".$sendid."',`stat`=1,`syscheck`=1,`sendtime`='".$nowtime."',`is_server`=1";
                $result=$GLOBALS['_MooClass']['MooMySQL']->query($sql);
                echo 'ok';exit;
            }else{
                echo 'having';exit;
            }

            //更改最后活动时间,上线
            $sql = "UPDATE {$GLOBALS['dbTablePre']}members_login SET lastvisit='{$GLOBALS['timestamp']}' WHERE uid='{$sendid}'";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            if(MOOPHP_ALLOW_FASTDB) {
                $value['lastvisit']=$GLOBALS['timestamp'];
                MooFastdbUpdate('members_login','uid',$sendid,$value);
                }

            //发e-mail提醒(委托)-------开始////////////////////////////////////////////
            include_once("./include/crontab_config.php");
            $send_user_info = array_merge(MooGetData('members_choice', 'uid', $sendid), MooMembersData($sendid));
            /*$send_user_info = $GLOBALS['_MooClass']['MooMySQL']->getAll("select * from `{$GLOBALS['dbTablePre']}members` a  left join  {$GLOBALS['dbTablePre']}choice b  on a.uid=b.uid  where a.uid = '$sendid'");
            $send_user_info = $send_user_info[0];*/
            //头像路径
            $path = thumbImgPath(2,$send_user_info['pic_date'],$send_user_info['pic_name'],$send_user_info['gender']);
            if(file_exists($path)){
             $img_path = $path;
            }else{
                if($send_user_info['gender'] == 1){
                    $img_path = "/public/images/service_nopic_woman.gif";
                }else{
                    $img_path = "/public/images/service_nopic_man.gif";
                }
            }
            $send_username = $send_user_info['nickname'] ?  $send_user_info['nickname'] : $send_user_info['uid'];//发送者用户名
            $send_user_grade = $send_user_info['gender'] == 1 ? "女" : "男"; //发送者性别
            $province = $provice_list[$send_user_info['province']];//省
            $city = $city_list[$send_user_info['city']]; //市
            $height = $send_user_info['height'] ? $height_list[$send_user_info['height']] : "未知"; //身高
            ob_start();
            require_once(adminTemplate('mail/mail_space_commissiontpl'));
            $body = ob_get_clean();
            MooSendMail($user['username'],"真爱一生网系统温馨提示",$body,"",false,$receiveuid);
            //--------->发送邮件提醒  结束///////////////////////////////////////////
            //fangin暂时屏蔽
            Push_message_intab($receiveuid,$user['telphone'],"委托", "尊敬的会员您好!".$sendid."，".$gender."，委托我们联系您，请及时登录www.zhenaiyisheng.cc查收，把握您的缘分。【真爱一生网】",$sendid);
            //写日志
            serverlog(3,$GLOBALS['dbTablePre'].'service_contact',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台以会员{$sendid}的身份向{$receiveuid}会员发送委托",$GLOBALS['adminid'],$receiveuid);
            echo 'ok';exit;
        break;
        case 'leer':
            $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}service_leer WHERE senduid = '$sendid' AND receiveuid= '$receiveuid' ";
            $leer = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
            if(!empty($leer)) {
               $lid = $leer['lid'];
               //note 如果已经发送过秋波，就增加发送秋波的次数
               $sql = "UPDATE {$GLOBALS['dbTablePre']}service_leer SET fakenum = fakenum + 1,num = num + 1,receivenum = receivenum + 1,sendtime = '$nowtime',receivetime = '$nowtime',receive_del = '0' WHERE lid = '$lid'";
               $GLOBALS['_MooClass']['MooMySQL']->query($sql);      
               //note 如果已经收到这个人的秋波，已经拒绝，现在改变注意，又发送秋波给这个人,拒绝状态2更改为0
               if($leer['receive_del'] == 1 || $leer['stat'] == 2){
                    $sql = "UPDATE {$GLOBALS['dbTablePre']}service_leer SET stat = '0',receive_del = 0 WHERE senduid = '$sendid' AND receiveuid = '$receiveuid' AND stat = '2'";
                    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
               }
            }else {
                //note 发送新的秋波，写入数据库 发送者，接受者，发送时间
                $sql = "INSERT INTO {$GLOBALS['dbTablePre']}service_leer SET sendtime = '$nowtime',receivetime = '$nowtime',fakenum = '1', receivenum = '1', num = '1', senduid  = '{$sendid}',receiveuid = '$receiveuid',is_server=1";
                $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            }
            
            //更改最后活动时间,上线
            $sql = "UPDATE {$GLOBALS['dbTablePre']}members_login SET lastvisit='{$GLOBALS['timestamp']}' WHERE uid='{$sendid}'";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            if(MOOPHP_ALLOW_FASTDB) {
                $value['lastvisit']=$GLOBALS['timestamp'];
                MooFastdbUpdate('members_login','uid',$sendid,$value);
                }

            //发e-mail提醒(委托)-------开始////////////////////////////////////////////
            include_once("./include/crontab_config.php");
			$send_user_info = array_merge(MooGetData('members_choice', 'uid', $sendid), MooMembersData($sendid));
            /*$send_user_info = $GLOBALS['_MooClass']['MooMySQL']->getAll("select * from `{$GLOBALS['dbTablePre']}members` a  left join  {$GLOBALS['dbTablePre']}choice b  on a.uid=b.uid  where a.uid = '$sendid'");
            $send_user_info = $send_user_info[0];*/
            //头像路径
            $path = thumbImgPath(2,$send_user_info['pic_date'],$send_user_info['pic_name'],$send_user_info['gender']);
            if(file_exists($path)){
             $img_path = $path;
            }else{
                if($send_user_info['gender'] == 1){
                    $img_path = "/public/images/service_nopic_woman.gif";
                }else{
                    $img_path = "/public/images/service_nopic_man.gif";
                }
            }
            $send_username = $send_user_info['nickname'] ?  $send_user_info['nickname'] : $send_user_info['uid'];//发送者用户名
            $send_user_grade = $send_user_info['gender'] == 1 ? "女" : "男"; //发送者性别
            $province = $provice_list[$send_user_info['province']];//省
            $city = $city_list[$send_user_info['city']]; //市
            $height = $send_user_info['height'] ? $height_list[$send_user_info['height']] : "未知"; //身高
            ob_start();
            require_once(adminTemplate('mail/mail_space_leertpl'));
            $body = ob_get_clean();
            MooSendMail($user['username'],"真爱一生网系统温馨提示",$body,"",false,$receiveuid);
            //--------->发送邮件提醒  结束///////////////////////////////////////////
            //fangin暂时屏蔽
            Push_message_intab($receiveuid,$user['telphone'],"秋波", "尊敬的会员您好!ID".$sendid."，".$gender."，给您发送秋波，请及时登录www.zhenaiyisheng.cc查收，把握您的缘分。【真爱一生网】",$sendid);
            //写日志
            serverlog(3,$GLOBALS['dbTablePre'].'service_leer',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台以会员{$sendid}的身份向{$receiveuid}会员发送秋波",$GLOBALS['adminid'],$receiveuid);
            echo 'ok';exit;
        break;
        case 'rose':
            $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}service_rose WHERE senduid = '$sendid' AND receiveuid= '$receiveuid' ";
            $rose = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    
            if(!empty($rose)) {
            $rid = $rose['rid'];
               //note 如果已经发送过鲜花，就增加发送鲜花的次数
               $sql = "UPDATE {$GLOBALS['dbTablePre']}service_rose SET fakenum = fakenum + 1,num = num + 1,receivenum = receivenum + 1,sendtime = '$nowtime',receivetime = '$nowtime',receive_del = '0' WHERE rid = '$rid'";
               $GLOBALS['_MooClass']['MooMySQL']->query($sql);
               //note 如果用户已经将该鲜花记录删除，则要改变删除状态，不然鲜花不会在前台显示
               if($rose['receive_del'] == 1){
                    $sql = "UPDATE {$GLOBALS['dbTablePre']}service_leer SET receive_del = 0 WHERE senduid = '$sendid' AND receiveuid = '$receiveuid'";
                    $GLOBALS['_MooClass']['MooMySQL']->query($sql);
               }
            }else{
                //note 发送新的鲜花，写入数据库 发送者，接受者，发送时间
                $sql = "INSERT INTO {$GLOBALS['dbTablePre']}service_rose SET sendtime = '$nowtime',receivetime = '$nowtime',fakenum = '1', receivenum = '1', num = '1', senduid  = '{$sendid}',receiveuid = '$receiveuid',is_server=1";
                $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            }
    
            //更改最后活动时间,上线
            $sql = "UPDATE {$GLOBALS['dbTablePre']}members_login SET lastvisit='{$GLOBALS['timestamp']}' WHERE uid='{$sendid}'";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            if(MOOPHP_ALLOW_FASTDB) {
                $value['lastvisit']=$GLOBALS['timestamp'];
                MooFastdbUpdate('members_login','uid',$sendid,$value);
            }
            
            //发e-mail提醒--->开始///////////////////////////////////
            include_once("./include/crontab_config.php");
            $send_user_info = array_merge(MooGetData('members_choice', 'uid', $sendid), MooMembersData($sendid));
            /*$send_user_info = $GLOBALS['_MooClass']['MooMySQL']->getAll("select * from `{$GLOBALS['dbTablePre']}members` a  left join  {$GLOBALS['dbTablePre']}choice b  on a.uid=b.uid  where a.uid = '$sendid'");
            $send_user_info = $send_user_info[0];*/
            //头像路径
            $path = thumbImgPath(2,$send_user_info['pic_date'],$send_user_info['pic_name'],$send_user_info['gender']);
            if(file_exists($path)){
             $img_path = $path;
            }else{
                if($send_user_info['gender'] == 1){
                    $img_path = "/public/images/service_nopic_woman.gif";
                }else{
                    $img_path = "/public/images/service_nopic_man.gif";
                }
            }
            $send_username = $send_user_info['nickname'] ?  $send_user_info['nickname'] : $send_user_info['uid'];//发送者用户名
            $send_user_grade = $send_user_info['gender'] == 1 ? "女" : "男"; //发送者性别
            $province = $provice_list[$send_user_info['province']];//省
            $city = $city_list[$send_user_info['city']]; //市
            $height = $send_user_info['height'] ? $height_list[$send_user_info['height']] : "未知"; //身高
            ob_start();
            require_once(adminTemplate('mail/mail_space_rosetpl'));
            $body = ob_get_clean();
            MooSendMail($user['username'],"真爱一生网系统温馨提示",$body,"",false,$receiveuid);
            //----->发送email-->结束/////////////////////////
            //fangin暂时屏蔽
            Push_message_intab($receiveuid,$user['telphone'], "鲜花","尊敬的会员您好!ID".$sendid."，".$gender."，给您发送鲜花，请及时登录www.zhenaiyisheng.cc查收，把握您的缘分。【真爱一生网】",$sendid);
            //写日志
            serverlog(3,$GLOBALS['dbTablePre'].'service_rose',"{$GLOBALS['adminid']}号客服{$GLOBALS['username']}在后台以会员{$sendid}的身份向{$receiveuid}会员发送鲜花",$GLOBALS['adminid'],$receiveuid);
            echo 'ok';exit;
            break;
        default:
               echo "errors";
            break;
        }
        exit;
}

//售后
function ajax_sell(){
    global $sell_after_grade,$renewals_status_grade,$member_progress_grade,$reprating;
    $uid = MooGetGPC('uid','integer','G');
    //note 客服ID
    $adminid = $GLOBALS['adminid'];
    $admingroup = $GLOBALS['groupid'];

    /*$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members WHERE uid={$uid}";
    $member = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);*/
    $member = MooMembersData($uid);
    $adminright = '';
    /*$sql = "SELECT s_cid FROM {$GLOBALS['dbTablePre']}members WHERE uid={$uid}";
    $user_type = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);*/
    $user_type = array();
    $user_type['s_cid'] = $member['s_cid'];

    //note 高级会员开始结束时间
    $begintime = $member['bgtime'] ? $member['bgtime'] : '1';
    $endtime = $member['endtime'] ? $member['endtime'] : '1';
    $alreadytime = time()-$begintime;
    $alreadyday = $alreadytime/86400;
    $alreadyday = round($alreadyday,2);

    //note 升级后统计SQL
    $sql_all = "SELECT * FROM web_member_admininfo a WHERE a.uid = '{$uid}'";
    $res_all = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql_all,true);

    //note 统计此会员升级后收到聊天的总数
    $count_chat = $res_all['chat'];
    
    //note 统计此会员升级后收到的邮件总数
    $count_email = $res_all['email'];
                
    //note 统计此会员升级后收到的委托总数
    $count_commiss = $res_all['mandate'];
    
    //note 统计此会员升级后收到的秋波总数
    $count_leer = $res_all['leer'];

    //note 统计此会员升级后收到的鲜花总数
    $count_rose = $res_all['rose'];
    
    //note 统计此会员升级后 推荐总数
    $count_recommend = $res_all['recommend'];

    //note 模拟总数
    $simulated_count_all = $count_chat+$count_email;
    //note 关注总数
    $oncern_count_all = $count_commiss+$count_rose+$count_leer;
    
    $danger_leval = $res_all['danger_leval'];
    
    //续费状态
    $renewals_status = $res_all['renewalstatus'];
    //会员进展
    $member_progress = $res_all['memberprogress'];

    //完成三大步骤的时间
    $finishedperiod = $res_all['period']/86400;
    $finishedperiod = round($finishedperiod,2);

    require_once(adminTemplate('allmember_sell'));  
}
//note 售后小记分页
function ajax_sell_page(){
    global $sell_after_grade;
    $t = MooGetGPC('t','string','G');
    $t = $t ? $t : 1;
    $uid = MooGetGPC('uid','integer','G');
    //分页
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 8;
    $offset = ($page-1)*$limit;
    //售后信息
    $sql_sellafter = "SELECT * FROM {$GLOBALS['dbTablePre']}member_sellinfo WHERE uid='{$uid}' ORDER BY id DESC LIMIT {$offset},{$limit}";
    $res_sellafter = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql_sellafter,0,0,0,true);
    $total = getcount('member_sellinfo','WHERE uid='.$uid);
    $pages = ceil($total / $limit);
    require_once(adminTemplate('allmember_sell_page'));
}

//会员资料页关键词搜索
function open_search(){
    
    $type=MooGetGPC("search_type","integer",'G');   
    header("Content-Type: text/html; charset=GB2312");
    $value=MooGetGPC("search_value","string",'G');
    $value=iconv("UTF-8","GB2312",$value);

    if($type==1){
        
        echo '<body onload="document.a.submit()">';
        //echo '<body>';
        echo '<form method="get" action="http://www.baidu.com/baidu" name="a">';
        echo '<input type="hidden" name ="word" type="text" value="' . $value . '">';
        echo '</form>';
        exit();
    }
if($type==5){
    
    echo '<body onload="document.a.submit()">';
        echo '<form method="" action="http://www.soso.com/q?" name="a">';
        echo '<input  type="hidden" name ="w" type="text" value="' . $value . '">';
        echo '<input type="hidden"  name ="pid" type="text" value="s.idx">';
        echo '<input type="hidden" id="unc" value="s300000_1" name="unc">';
        echo '</form>';
        exit();
    }
if($type==6){
    
    echo '<body onload="document.a.submit()">';
        echo '<form method="" action="http://www.sogou.com/web" name="a">';
        echo '<input type="hidden"  name ="query" type="text" value="' . $value . '">';
        echo '<input type="hidden" value="www.sogou.com" name="_asf">';
        echo '<input type="hidden" value="01019900" name="w">';
        echo '<input type="hidden" value="40040100" name="p">';
        echo '</form>';
        exit();
    }
}


//使用的采集的手册
function ajax_user_member(){
    global $adminid,$groupid,$dbTablePre;
    $uid = MooGetGPC('uid','string','G');   
    $sql = "select mid from {$dbTablePre}member_admininfo where uid={$uid}";
    $user_member = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
    $val = '此会员您可以操作了';
    if($user_member){
        if($user_member['mid'] == 999999999){
            //echo 'block';
            $val = '此会员已被封锁!';
        }elseif(!empty($user_member['mid']) && $user_member['mid'] != $adminid){
            //echo 'nouser';
            $val = '此会员其他客服正在使用！';
        }elseif($user_member['mid'] == 0){
            $sql = "update {$dbTablePre}member_admininfo set mid={$adminid} where uid={$uid}";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
            $val = '此会员您可以操作了';
        }
    }else{
        $sql = "insert into {$dbTablePre}member_admininfo set uid='{$uid}',mid='{$adminid}',effect_grade=1";
        $GLOBALS['_MooClass']['MooMySQL']->query($sql); 
        $val = '此会员您可以操作了';
    }
    salert($val,'index.php?action=allmember&h=view_info&uid='.$uid);
}


//封锁此采集会员
function ajax_block_member(){
    global $adminid,$groupid,$dbTablePre;
    $uid = MooGetGPC('uid','string','G');   
    $sql2 = "update {$dbTablePre}member_admininfo set mid=999999999 where uid={$uid}";  
    $sql3 = "update {$dbTablePre}members_search s inner join {$dbTablePre}members_base b on s.uid=b.uid set s.showinformation=0,b.showinformation_val=2 where s.uid={$uid}";
    if($GLOBALS['_MooClass']['MooMySQL']->query($sql2) && $GLOBALS['_MooClass']['MooMySQL']->query($sql3)){
        //echo 'ok';
        $val = '此会员已被封锁!';
    }
    salert($val,'index.php?action=allmember&h=view_info&uid='.$uid);
}


//开启此采集会员
function ajax_open_member(){
    global $adminid,$groupid,$dbTablePre;
    $uid = MooGetGPC('uid','string','G');   
    $sql2 = "update {$dbTablePre}member_admininfo set mid=0 where uid={$uid}";
    $sql3 = "update {$dbTablePre}members_search s,{$dbTablePre}members_base b on s.uid=b.uid set s.showinformation=1,b.showinformation_val=0 where s.uid={$uid}";
    if($GLOBALS['_MooClass']['MooMySQL']->query($sql2) && $GLOBALS['_MooClass']['MooMySQL']->query($sql3)){
        //echo 'ok';
        $val = '此会员已开启!';
    }
    salert($val,'index.php?action=allmember&h=view_info&uid='.$uid);
}


function ajax_change_public_members(){
    global $dbTablePre;
    $uid = MooGetGPC('uid','integer','G');  
    $puid = MooGetGPC('puid','integer','G');    
    $photo = MooGetGPC('photo','integer','G');  
    $age2 = 24;
    $tmp_end = gmdate('Y',time())-$age2;
    $age_condition = " birthyear>='{$tmp_end}'";
    $sql = "update {$dbTablePre}members_search set sid='{$uid}'  where sid='{$puid}' and".$age_condition." and gender='1'";
    $sql2 = "select uid from {$dbTablePre}members_search where sid='{$puid}' and".$age_condition." and gender=1";
    if($photo=='1'){
        $sql .=  "and images_ischeck='1'";
        $sql2 .=  "and images_ischeck='1'";
    }else{
        $sql .=  "and images_ischeck!='1'";
        $sql2 .=  "and images_ischeck!='1'";
    }

    $res = $GLOBALS['_MooClass']['MooMySQL']->query($sql);
    $res2 = $GLOBALS['_MooClass']['MooMySQL']->query($sql2);
    if(is_array($res2) && !empty($res2)){
    	$str_arr = array();
		foreach($res2 as $k=>$v){
	    	$str_arr[$v['uid']] = array($uid);
	    }
	    !empty($str_arr) && searchApi("members_man members_women") -> updateAttr(array('sid'),$str_arr);
    }
    
    serverlog(3,$dbTablePre.'members_search',$GLOBALS['adminid']."号客服一键将公海会员库的24岁以下女会员转到252名下",$GLOBALS['adminid']);
    salert("转换成功",'index.php?action=allmember&h=advancesearch');
    exit;
}
//删除指定会员的指定聊天记录
function ajax_delchatinfo(){
    global $dbTablePre;
    $chat_id=MooGetGPC('chatid','integer','G');
    $sql="DELETE FROM {$dbTablePre}service_chat where s_id='{$chat_id}'";
    if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
        echo "OK";
        serverlog(3,$dbTablePre.'service_chat',$GLOBALS['adminid']."将ID：{$chat_id}的聊天记录删除",$GLOBALS['adminid']);
    }
}

//会员参加的活动
function ajax_activity(){
    $uid=MooGetGPC('uid','integer','G');
//    $common='`'.$GLOBALS['dbTablePre'].'activity_acceding` as a left join `'.$GLOBALS['dbTablePre'].'activity` as `b` on a.`channel`=b.`id`';
//    $sql='select b.`type`,b.`title`,b.`opentime`,b.`province`,b.`city`,b.`place` from '.$common.' where a.`uid`='.$uid;
//    $data=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	$data = $GLOBALS['_MooClass']['MooMySQL']->getAll("select b.title, b.opentime, b.place from {$GLOBALS['dbTablePre']}ahtv_reguser a left join {$GLOBALS['dbTablePre']}activity b on a.channel=b.id where a.uid={$uid} and a.isattend=1");
	require_once(adminTemplate('allmember_activity'));
    exit;
}

//更新优质会员
function ajax_excellent(){
	$sql="DELETE FROM web_members_excellent";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	
	$sql="select * from web_members_search ";
	
	/*$sql = "SELECT m.*,a.isattend FROM {$GLOBALS['dbTablePre']}members m 
            LEFT JOIN {$GLOBALS['dbTablePre']}ahtv_reguser a
            ON m.uid=a.uid and a.uid<>0 GROUP BY m.uid ";*/
	$result=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	
	
	if(empty($result)){exit;}
	foreach($result as $v){
		$grade=0;
		//年龄
		if (empty($v['birthyear'])){
			$age=0;
		}else{
		   $age=Date('Y')-$v['birthyear'];
		}
		if ($age>=25) $grade++;
		
		//收入
		$salary=$v['salary'];
		if($salary>=4)  $grade++;
		
		//地区
		$province=$v['province'];
		$city=$v['city'];
		
		$array_district[]=array("10102000","10103000","10101201","10101002","10101000","10105000","10127001");
		
		if(in_array($province, $array_district) || in_array($city, $array_district)) $grade++;
		
		
		/*$sql="select count(uid) as num from web_members_excellent where uid={$v['uid']}";
		$excellent=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		if($excellent['num']>0){
			$sql="update web_members_excellent set grade={$grade} where uid={$v['uid']}";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		}else{*/
		if($grade){
			$sql="insert into web_members_excellent(uid,username,s_cid,birthyear,salary,lastvisit,login_meb,province,city,source,allotdate,regdate,sid,islock,grade) values('{$v['uid']}','{$v['username']}','{$v['s_cid']}','{$v['birthyear']}','{$v['salary']}','{$v['lastvisit']}','{$v['login_meb']}','{$v['province']}','{$v['city']}','{$v['source']}','{$v['allotdate']}','{$v['regdate']}','{$v['sid']}','{$v['islock']}','{$grade}')";
			$GLOBALS['_MooClass']['MooMySQL']->query($sql);
			
		
		}
		
		
		//}
		
		
		
	}
	
	
	
	$sql = "SELECT a.isattend FROM web_members_excellent m 
            LEFT JOIN web_ahtv_reguser a
            ON m.uid=a.uid ";
    $result=$GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
    
    foreach ($result as $v){
    	//是否参加活动
        $isattend=$v['isattend'];
        
        if($isattend) {
        	$sql="update web_members_excellent set grade=grade + 1 where uid={$v['uid']}";
            $GLOBALS['_MooClass']['MooMySQL']->query($sql);
        }
        
        
    }
	
	echo 'ok';
	
	
}

//更改或取消为全权会员
function ajax_changeAcq(){
	global $fastdb;
	$value=MooGetGPC('value','string','P');
	$uid=MooGetGPC('uid','string','P');

	if(empty($value) || empty($uid)) {
		echo 'no';exit;
	}
	
	if($value=='noquanquan'){ //非全权会员
	  $pwd=md5('hzn123456');
	  
	  $sql="update web_members_search set usertype=3,password='{$pwd}',telphone='',username=concat(username,'|cj'),sid=1 where uid={$uid}";
	  $GLOBALS['_MooClass']['MooMySQL']->query($sql);

	  $sql="update web_members_base set qq=concat(qq,'|cj'),callno=concat(callno,'|cj'), msn=concat(msn,'|cj')  where uid={$uid}";
	  $GLOBALS['_MooClass']['MooMySQL']->query($sql);
	  
	  serverlog(3,$dbTablePre.'members_search',$GLOBALS['adminid']."将ID：{$uid}更改或取消为全权会员",$GLOBALS['adminid']);
	  
	  echo 'update';
	  
	}/*elseif($value=='quanquan'){
		
      echo 'cancel';exit;
	}*/
	
}

/**
 * 修改全权会员过期时间
 */
function ajax_change_overdue(){
	$uid=MooGetGPC('uid','integer','P');
	$action_time=MooGetGPC('action_time','string','P');//全权会员首次使用时间，为空则是直接标注过期
	if(empty($uid)){
		exit(json_encode(array('flag'=>0,'msg'=>'会员id不可以为空')));
	}
	$user=$GLOBALS['_MooClass']['MooMySQL']->getOne('SELECT `usertype` FROM `'.$GLOBALS['dbTablePre'].'members_search` WHERE `uid`='.$uid,true);
	if(empty($user) || $user['usertype']!=3){
		exit(json_encode(array('flag'=>0,'msg'=>'无此会员或者该会员不是全权会员')));
	}
	$timestamp=empty($action_time)?($GLOBALS['timestamp']-3888000):strtotime($action_time);
	$action_time=$GLOBALS['_MooClass']['MooMySQL']->getOne('SELECT `action_time` FROM `'.$GLOBALS['dbTablePre'].'full_log` WHERE `uid`='.$uid,true);
	$sql=empty($action_time)?('INSERT INTO `'.$GLOBALS['dbTablePre'].'full_log` (`uid`, `action_time`) VALUES (\''.$uid.'\',\''.$timestamp.'\')'):('UPDATE `'.$GLOBALS['dbTablePre'].'full_log` SET `action_time` = \''.$timestamp.'\' WHERE `uid` ='.$uid);
	if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
		exit(json_encode(array('flag'=>1,'msg'=>'此全权会员已经注册，距离首次登陆（访问）已<span class="action_msg">超过45天</span>')));
	}else{
		exit(json_encode(array('flag'=>0,'msg'=>$sql)));
	}
}

/**
 * 会员交接表
 */
function ajax_join() {
	$id = MooGetGPC('id', 'integer');
	$uid = MooGetGPC('uid', 'integer'); // first get method
	$sid = MooGetGPC('sid', 'integer'); // first get method
	$action = MooGetGPC('act', 'string');
	$db = $GLOBALS['_MooClass']['MooMySQL'];
	$dbpre = $GLOBALS['dbTablePre'];
	
	
	$bgtime = MooMembersData($uid,'bgtime'); // 提交升级时间
	$allotdate = MooMembersData($uid,'allotdate'); // 分配时间
	if (empty($bgtime)) { $bgtime = time(); }
	$bgtime = date('Y-m-d H:i:s', $bgtime);
	if (!empty($allotdate)) {
		$allotdate = date('Y-m-d H:i:s', $allotdate);
	} else $allotdate = '';
	
	$modes = array(1 => '全权', 2 => '全权1+1', 3 => '炫服务', 4 => '信息刺激', 5 => '本站牵线');
    $periods = array(1 => '一个月', 2 => '三个月', 3 => '五个月', 4 =>'六个月');
		
	if ($action == 'query') {
		$join = $db->getOne("select * from {$dbpre}admin_join where id={$id}");
		if (!empty($join)) {
			$join['bgtime'] = date('Y-m-d H:i:s', $join['bgtime']);
			$join['allottime'] = date('Y-m-d H:i:s', $join['allottime']);
			$arr_convert = array('uid' => 'localid', 'gid' => 'manageid', 'sid' => 'kefuid', 'fid' => 'joinid',	'mid' => 'delegateid',
				'bgtime' => 'uptime', 'isfake' => 'hasfake', 'istalk' => 'hastalk', 'isnegative' => 'hasnegative');
			$keys = array_keys($join);
			$key = 0;
			foreach ($join as $k => $v) {
				if (isset($arr_convert[$k])) {
					$keys[$key] = $arr_convert[$k];
				}
				++$key;
			}
			$join = array_combine($keys, $join);
			echo json_encode($join);
		} else { echo '"fail"'; }
		exit;
	} elseif ($action == 'delete') {
		$db->query("delete from {$dbpre}admin_join where id={$id}");
		if (isset($_GET['from'])) exit('ok'); // come from financial_joinstatistics
	}

		
	if(!empty($_POST)) { // post
		//if ($id === 0) { $id = 'null'; }
		$uid = MooGetGPC('localid', 'integer', 'P');
		$sid = MooGetGPC('kefuid', 'integer', 'P');
		$gid = MooGetGPC('manageid', 'integer', 'P');
		$fid = MooGetGPC('joinid', 'string', 'P');
		$mid = MooGetGPC('delegateid', 'string', 'P');
		$telephone = MooMembersData($uid,'telphone');
		$money = (float)MooGetGPC('money', 'string', 'P');
		$bgtime = MooGetGPC('uptime', 'string', 'P');
		$allottime = MooGetGPC('allottime', 'string', 'P');
		$period = MooGetGPC('period', 'integer', 'P');
		$mode = MooGetGPC('mode', 'integer', 'P');
		$isfake = MooGetGPC('hasfake', 'integer', 'P');
		$istalk = MooGetGPC('hastalk', 'integer', 'P');
		$isnegative = MooGetGPC('hasnegative', 'integer', 'P');
		$maininfo = MooGetGPC('maininfo', 'string', 'P');
		$simulateinfo = MooGetGPC('simulateinfo', 'string', 'P');
		$lastinfo = MooGetGPC('lastinfo', 'string', 'P');
		$remark = MooGetGPC('remark', 'string', 'P');
		
		$time = time();
		$bgtime = strtotime($bgtime);
		$allottime = strtotime($allottime);
		$db->query("replace into {$dbpre}admin_join values($id, $uid, $gid, $sid, $money, $period, $bgtime, $allottime, $mode, $isfake, $istalk, '$telephone', '$fid', '$mid', '$maininfo', '$simulateinfo', '$lastinfo', '$remark', $isnegative, $time)");
	} elseif (empty($action)) {
		$manage_list = $db->getAll("select id, manage_name, manage_list from {$dbpre}admin_manage where type=1 order by manage_name");
		$server_list = $db->getAll("select uid, username from {$dbpre}admin_user");
		

		foreach ($server_list as $v) {
			$server_arr[$v['uid']] = $v['username'];
		}
		
		$manageids = '{';
  
		$founded = false;
		$current_group_kefu_list=array();
		foreach ($manage_list as $v) {
		    
			$single_manage_arr = explode(',', $v['manage_list']);
			$manageids .= $v['id'] . ':{';
			foreach ($single_manage_arr as $v2) {
			    if(empty($v2)) $v2=0;
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
	
	$join_list = $db->getAll("select * from {$dbpre}admin_join where uid={$uid}");
	
	include adminTemplate('allmember_join');
}

function letmesee(){
	global $_SCONFIG, $_SGLOBAL;
	var_dump($_SCONFIG);
	echo '<br />';
	var_dump($_SGLOBAL);
}

function ajax_total(){
    $uid=MooGetGPC('uid','string','P');
    //note 委托数
	$cocount1 = getcount('service_contact',"WHERE you_contact_other='{$uid}' AND receive_del=0");
	$cocount2 = getcount('service_contact',"WHERE other_contact_you='{$uid}' AND send_del=0");
	//note 秋波数
	$leercount1 = getcount('service_leer','WHERE senduid='.$uid);
	$leercount2 = getcount('service_leer','WHERE receiveuid='.$uid);
	//note 鲜花数
	$rcount1 = getcount('service_rose','WHERE senduid='.$uid);
	$rcount2 = getcount('service_rose','WHERE receiveuid='.$uid);
	//note 意中人数
	$fcount1 = getcount('service_friend','WHERE uid='.$uid);
	$fcount2 = getcount('service_friend','WHERE friendid='.$uid);
	//note 会员评价
	$pingjia1 = getcount('members_comment','WHERE uid='.$uid);
	$pingjia2 = getcount('members_comment','WHERE cuid='.$uid);
	//note 心理测试
	$testcount = getcount('love_vote','WHERE uid='.$uid);
	//note 短信记录
	$mescount = getcount('smslog','WHERE uid = '.$uid);
	//note 聊天记录
	//$chatcount = getcount('service_chat',"WHERE s_uid = {$uid} OR s_fromid = {$uid}");
	$chatcount = getChatTotal($uid);	
	//note 站内信箱
	$lettercount1 = getcount('services','WHERE s_fromid !=0 AND s_uid='.$uid);
    $lettercount2 = getcount('services','WHERE s_fromid='.$uid);
    //note 红娘来信 会员来信 发件箱数
    $hncount = getcount('services','WHERE s_uid='.$uid .' AND is_server=1');
	$hycount = getcount('services','WHERE s_uid='.$uid .' AND ( s_cid=30 OR s_cid=40 )');
	$facount = getcount('services','WHERE s_fromid='.$uid);
	//note 分配次数
	$fpcount = getcount('allotuser','WHERE uid = '.$uid);
	//note 放弃次数
	$fqcount = getcount('member_backinfo','WHERE effect_grade = 10 AND uid = '.$uid);
	// 参加过的活动数
	$activitycount = getcount('ahtv_reguser', "where uid=$uid and isattend=1");
	// 会员交接数
	$joincount = getcount('admin_join', 'where uid='.$uid);
	//小记总数
	$notestotal = getcount('member_backinfo','WHERE uid='.$uid);
	
	
	$count=array();
	$count=array('notestotal'=>$notestotal,'cocount1'=>$cocount1,'cocount2'=>$cocount2,'leercount1'=>$leercount1,'leercount2'=>$leercount2,'rcount1'=>$rcount1,'rcount2'=>$rcount2,'pingjia1'=>$pingjia1,'pingjia2'=>$pingjia2,'testcount'=>$testcount,'mescount'=>$mescount,'chatcount'=>$chatcount,'lettercount1'=>$lettercount1,'lettercount2'=>$lettercount2,'hncount'=>$hncount,'hycount'=>$hycount,'facount'=>$facount,'fpcount'=>$fpcount,'fqcount'=>$fqcount,'activitycount'=>$activitycount,'joincount'=>$joincount,'fcount1'=>$fcount1,'fcount2'=>$fcount2);
    echo json_encode($count);
}

//查看所有小记
function ajax_notes(){
    global $grade,$slave_mysql;
    $uid=MooGetGPC('uid','string','G');
    //小记
	$page = get_page();
	$total = getcount('member_backinfo','WHERE uid='.$uid);
    $limit = 5;
	
	$page_total = max(1, ceil($total/$limit));
	$page = min($page, $page_total);
    $offset = ($page-1)*$limit;

	$sql = "SELECT dateline, mid, manager, effect_grade, effect_contact, master_member, next_contact_time, interest, different, service_intro, next_contact_desc, comment FROM {$GLOBALS['dbTablePre']}member_backinfo WHERE uid = {$uid} ORDER BY id DESC LIMIT {$offset},{$limit}"; // updated file
	//$sql = "SELECT dateline, mid, manager, effect_grade, effect_contact, master_member, next_contact_time, interest, different, service_intro, next_contact_desc, comment FROM {$GLOBALS['dbTablePre']}member_backinfo WHERE uid = {$uid} ORDER BY id DESC"; // updated file
	
	$notes = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);	

	//$currenturl = "index.php?action=allmember&h=view_info&uid={$uid}";
	$currenturl="./allmember_ajax.php?n=notes&uid={$uid}";
   	$pages = multipage( $total, $limit, $page, $currenturl ); 
	
	
	include adminTemplate('allmember_notes');
	//$json=array();
	//$json=array('grade'=>$grade,'total'=>$total,'notes'=>$notes,'pages'=>$pages,'page'=>$page,'limit'=>$limit);
	//echo json_encode($json);
}

//添加（保存）小记
function saveNotes(){
	global $reprating,$adminid,$timestamp;
	
	
	$uid = MooGetGPC('uid','integer','P');
	$grade = MooGetGPC('grade','integer','P');
	$contact = MooGetGPC('contact','integer','P');
	$master = MooGetGPC('master','integer','P');
	$time = strtotime(MooGetGPC('time','string','P'));
	$interest = MooGetGPC('interest','string','P');
	$different = MooGetGPC('different','string','P');
	$service = MooGetGPC('service','string','P');
	$desc = MooGetGPC('desc','string','P');
	$comment = MooGetGPC('comment','string','P');
	$phonecall = MooGetGPC('phonecall','integer','P');
	//note 手机是否验证原因
	$checkreason = MooGetGPC('checkreason','integer','P');
	//note 手机号码
	$telphone = MooGetGPC('telphone','string','P');
	
	//采集使用
	$usertype = MooGetGPC('usertype','string','P');
	//如果客服已经给全权会员做了小记，那么这个会员就处于使用状态
	if($usertype==3){
		$action_time= $GLOBALS['_MooClass']['MooMySQL']->getOne('SELECT `action_time` FROM `web_full_log` WHERE `uid`=\''.$uid.'\'',true);
		if(empty($action_time)){
			$GLOBALS['_MooClass']['MooMySQL']->query('INSERT INTO `web_full_log` (`uid`, `action_time`) VALUES (\''.$uid.'\', \''.$timestamp.'\')');
		}
		
	}
	
	$sql = "INSERT INTO {$GLOBALS['dbTablePre']}member_backinfo(mid,manager,uid,effect_grade,effect_contact,master_member,next_contact_time,interest,different,service_intro,next_contact_desc,comment,dateline,phonecall)
			VALUES({$GLOBALS['adminid']},'{$GLOBALS['username']}',{$uid},{$grade},{$contact},{$master},'{$time}','{$interest}','{$different}','{$service}','{$desc}','{$comment}','{$timestamp}','{$phonecall}')";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);

	$sql = "SELECT uid FROM {$GLOBALS['dbTablePre']}member_admininfo WHERE uid='{$uid}'"; // updated file
	$member_admininfo = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql,true);
	if(!empty($member_admininfo)){
		
		$sql = "UPDATE {$GLOBALS['dbTablePre']}member_admininfo SET effect_grade='{$grade}',effect_contact='{$contact}',master_member='{$master}',next_contact_time='{$time}',dateline='{$timestamp}',checkreason='{$checkreason}' WHERE uid='{$uid}'";
	}else{
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}member_admininfo(uid,effect_grade,effect_contact,master_member,next_contact_time,dateline,checkreason)
			VALUES('{$uid}','{$grade}','{$contact}','{$master}','{$time}','{$timestamp}','{$checkreason}')";
	}

	
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	//NOTE 3,4,5类会员保存小记后台提醒客服组长
	$gid = getGroupID($adminid);
	$wokao=$grade - 1;
	if(in_array($wokao,array('3','4','5'))){
		$title = '会员'.$uid.'转为 '.$wokao.'类';
		$awoketime = $timestamp+3600;
		$sql_remark = "insert into {$GLOBALS['dbTablePre']}admin_remark set sid='{$gid}',title='{$title}',content='{$title}',awoketime='{$awoketime}',dateline='{$timestamp}'";
		$res = $GLOBALS['_MooClass']['MooMySQL']->query($sql_remark);
	}
	
	// 会员跟踪步骤 mycount 统计
	try {
		$m = $GLOBALS['_MooClass']['MooMySQL']->getOne("SELECT b.source as source FROM web_members_search s left join web_members_base b on s.uid=b.uid  where s.uid='{$uid}' ",true);
		// 只统计有渠道的
		$source = trim($m['source']);
		if ( !empty($source) ) {
			$str_md5 = md5($uid . ($grade - 1) . MOOPHP_AUTHKEY);
			$apiurl  = "http://tg.zhenaiyisheng.cc/hongniang/hongniang_effect_grade_import.php?uid=" . $uid . "&md5=" . $str_md5 . "&grade=" . ($grade - 1);
			$result  = file_get_contents($apiurl);
		}
	}catch (Exception $e) {
		//var_dump($e->getMessage());
	}
	
}
/***************************************控制层(V)***********************************/
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间

$n=MooGetGPC('n','string');
//判断是否登录
adminUserInfo();
if(empty($GLOBALS['adminid'])){
    echo 'nologin';exit;
}

switch($n){
    //note 委托
    case 'commission':
        ajax_commission();
		break;
    //note 秋波
    case 'leer':
        ajax_leer();    
		break;  
    //保存小记
    case 'saveNotes':
	    saveNotes();
		break;
	//note 鲜花
    case 'rose':
        ajax_rose();    
		break;
    //note 意中人
    case 'friend':
        ajax_friend();  
		break;
    //note 匹配搜索
    case 'match':
        ajax_match();   
		break;
    case 'match_search':
        ajax_match_search();
		break;
    //note 心理测试
    case 'test':
        ajax_lovetest();
		break;
	//发送短信
	case 'sendsms':
	    ajax_sendSMS();
		break;
    //note 短信记录
    case 'message':
        ajax_message(); 
		break;
    //note 聊天记录
    case 'chatrecord':
        ajax_chatrecord();  
		break;
    //note 站内信箱
    case 'letter':
        ajax_letter();  
		break;
    //note 会员认证 发短信 发站内信
    case 'contact':
        ajax_contact();
		break;
    //note 上传附件
    case 'uploadfile':
        ajax_uploadfile();  
		break;
    //note 发送委托、秋波、鲜花
    case 'sendactive':
        ajax_sendactive();  
		break;
    //note 删除委托、秋波、鲜花
    case 'delactive':
        ajax_delactive();
		break;
    //note 匹配搜索中获取会员头像
    case 'getimg':
        ajax_getimg();  
		break;
    //note 备用号码
    case 'submitcallno':
        ajax_submitcallno();
		break;
    //note 九型处理
    case 'enneagram':
        ajax_enneagram();   
		break;
    case 'get_vil_user':
        ajax_get_vil_user();
		break;
    //note 客服操作
    case 'kfmanage':
        ajax_kfmanage();
		break;
    //note 电话流程
    case 'telphone_order':
        ajax_telphone_order();
		break;
    case 'get_muli_uidinfo':
        ajax_get_muli_uidinfo();
		break;
    case 'chang_isphone':
        chang_isphone();
		break;
    case 'check_searchid':
        ajax_check_searchid();
		break;
    case 'pingjia':
        ajax_pingjia();
		break;
    case 'ajax_send_mms':
        ajax_send_mms();
		break;
    //note 售后发送委托、秋波、鲜花
    case 'sellsendactive':
        ajax_sellsendactive();
		break;
    //note 售后
    case 'sell':
        ajax_sell();
		break;
    //note 售后分页
    case 'sell_page':
        ajax_sell_page();
		break;
    case 'mms_getact':
        ajax_mms_getact();
		break;
    //关键词搜索
    case 'open_search':
        open_search();
		break;
    //采集会员的使用手册
    case 'user_member':
        ajax_user_member();
        break;
    //封锁此采集会员
    case 'block_member':
        ajax_block_member();
        break;
    //开启此采集会员
    case 'open_member':
        ajax_open_member();
        break;
    //一键将公海会员库的24岁以下女会员转到252名下
    case 'change_public_members':
        ajax_change_public_members();
        break;
    case 'delchatinfo':
        ajax_delchatinfo();
        break;
    case 'activity':
        ajax_activity();
        break;
    case 'excellent':
    	ajax_excellent();
    	break;
    case 'changeAcq':  //更改  或 取消为 全权 会员
    	ajax_changeAcq();
    	break;
    case 'overdue':
    	ajax_change_overdue();
    	break;
	// 会员交接表
    case 'join':
    	ajax_join();
    	break;
	case 'letmesee':
		letmesee();
		break;
	case 'total':
	    ajax_total();
		break;
	case 'notes':
	    ajax_notes();
		break;
    default:
        echo 'no method';exit;
        break;
}
?>
