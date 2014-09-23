<?php 
/*
 * Created on 8/14/2009
 *
 * Tianyong yujun
 * @Modify:2010-02-10
 * @Modifier:fang
 * module/service/index.php
 */
/***************************************一些模板用的函数******************************/

date_default_timezone_set('Asia/shanghai');

//线下活动
function below_activity($h,$channel){
		GLOBAL $user_arr;
		GLOBAL $memcached,$_MooClass;
		$where='';
		//$db = $GLOBALS['db'];
		//$memcached = new memcached();
		$occupation_list = array("1" => "金融业", "2" => "计算机业", "3" => "商业", "4" => "服务行业", "5" => "教育业", "6" => "工程师", "7" => "主管，经理", "8" => "政府部门", "9" => "制造业", "10" => "销售/广告/市场", "11" => "资讯业", "12" => "自由业", "13" => "农渔牧", "14" => "医生", "15" => "律师", "16" => "教师", "17" => "幼师", "18" => "会计师", "19" => "设计师", "20" => "空姐", "21" => "护士", "22" => "记者", "23" => "学者", "24" => "公务员", "26" => "职业经理人", "27" => "秘书", "28" => "音乐家", "29" => "画家", "30" => "咨询师", "31" => "审计师", "32" => "注册会计师", "33" => "军人", "34" => "警察", "35" => "学生", "36" => "待业中", "37" => "消防员", "38" => "经纪人", "39" => "模特", "40" => "教授", "41" => "IT工程师", "42" => "摄影师", "43" => "企业高管", "44" => "作家", "99" => "其他行业");
		$page_per = 9;
	    $page = intval(max(1,MooGetGPC('page','integer')));
	    $limit = 4;
	    $offset = ($page-1)*$limit;
	     $sql = "SELECT COUNT(uid) AS COUNT FROM web_ahtv_remark where channel='".$channel."' and ispass=1 ";
	  
	    $result = $_MooClass['MooMySQL']->getOne($sql);
	    $total = $result['COUNT'];
    
  
        $sql="select username,operationtime,remark  from web_ahtv_remark where channel='".$channel."' and ispass=1 order by operationtime desc  LIMIT {$offset},{$limit} ";
        $result=$_MooClass['MooMySQL']->getAll($sql);
        
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$seccode1 = strtolower(MooGetGPC('seccode','string','P'));
			$seccode2 = MooGetGPC('seccode','string','C');
			$session_seccode = $memcached->get($seccode2);

	        if($seccode1 != $session_seccode){
			   MooMessage("验证码填写不正确，请确认。", 'index.php?n=activity&h='.$h.'','','1',1);
			}

			$content=$_POST['content'];
			$date=date('Y-m-d H:i:s');
			$nickname=$user_arr['nickname'];

			$query=$_MooClass['MooMySQL']->query("insert into  web_ahtv_remark(uid,username,remark,operationtime,channel) values('$userid','$nickname','$content','$date','$channel')");

			if($query){
		        MooMessage("评论成功。", 'index.php?n=activity&h='.$h.'','','3',3);
			}

		}else{
	        $seccode = md5(uniqid(rand(), true));
			MooSetCookie('seccode',$seccode,3600,'');
			$session_seccode = $memcached->set($seccode , '' , 0 , 300);
		}
		$sql = "select * from web_activity where id = '$channel' limit 1";
		
		 
		$activity = $_MooClass['MooMySQL']->getOne($sql);
		
		$last_time = floor(($activity['closetime']-strtotime(date("Ymd")))/(60*60*24));
		
		if(empty($activity['closetime'])){
			$start_time = '不限';
		}else{
        	$start_time = date('Y年m月d日',$activity['closetime']);
		}
		$place = $activity['place'];
		if($last_time<0){
			$last_time = '该活动报名已经结束';
			$last_place = '保密';
		}else{
			$last = $last_time;
			$last_time =  '距活动报名结束时间还有：'.'<span>'.$last.'</span>'.'天';
			$last_place = $place;
		}
		
		$time = time();
		$sql = "select * from web_activity where closetime>='$time' $where order by closetime asc limit 0,5";
		$content1 = $_MooClass['MooMySQL']->getAll($sql);
		$act_con = array();
		foreach($content1 as $k => $v){
			$act_con[$k]['time'] = date('Y-m-d',$v['closetime']);
			$act_con[$k]['title'] = mb_substr($v['title'],0,8,'utf-8').'..';
			$act_con[$k]['href'] = $v['href'];
		}
		$count_act = count($act_con);
		$limit = 6-count($act_con);
		$sql = "select * from web_activity where closetime<='$time' $where order by closetime desc limit 0,$limit";
		$content_last = $_MooClass['MooMySQL']->getAll($sql);
		$act_con_last = array();
		foreach($content_last as $k => $v){
			$act_con_last[$k]['time'] = date('Y-m-d',$v['closetime']);
			$act_con_last[$k]['title'] = mb_substr($v['title'],0,8,'utf-8').'..';
			$act_con_last[$k]['href'] = $v['href'];
		}
		
		$sql = "select * from web_activity where closetime<'$time' $where order by closetime desc limit 0,10";
		$content2 = $_MooClass['MooMySQL']->getAll($sql);
		$act_con2 = array();
		foreach($content2 as $k => $v){
			$act_con2[$k]['time'] = date('Y-m-d',$v['closetime']);
			$act_con2[$k]['title'] = $v['title'];
			$act_con2[$k]['href'] = $v['href'];
		}
		
        $qixi_regest_count=array();
        $qixi_regest_counts=$_MooClass['MooMySQL']->getAll('SELECT count(`id`) AS count ,`gender`  FROM `web_ahtv_reguser`  WHERE `uid`!=0 and `channel`="'.$channel.'" group by `gender`');
        foreach($qixi_regest_counts as $value){
            $qixi_regest_count[$value['gender']]=$value['count'];
        }
        if(empty($qixi_regest_count[0])){
        	$qixi_regest_count[0] = '0';
        }
		if(empty($qixi_regest_count[1])){
        	$qixi_regest_count[1] = '0';
        }
        $num_all = $qixi_regest_count[1]+$qixi_regest_count[0];
        $man_qixi_regest=$_MooClass['MooMySQL']->getAll('SELECT a.`uid`,b.`nickname`,b.`education`,b.`occupation`,b.`birthyear`,b.`occupation` FROM `web_ahtv_reguser` as a left join web_members_search as b  on a.`uid`=b.`uid` WHERE a.`uid`!=0 and a.`channel`="'.$channel.'" and a.`gender`=0 and b.nickname!=\'\' order by a.`regtime` desc limit ' . $page_per);
        $woman_qixi_regest=$_MooClass['MooMySQL']->getAll('SELECT a.`uid`,b.`nickname`,b.`education`,b.`occupation`,b.`birthyear`,b.`occupation` FROM `web_ahtv_reguser` as a left join web_members_search as b  on a.`uid`=b.`uid` WHERE a.`uid`!=0 and a.`channel`="'.$channel.'" and a.`gender`=1 and b.nickname!=\'\' order by a.`regtime` desc limit ' .$page_per);
        
		$page_num = array(ceil($qixi_regest_count[0] / $page_per), ceil($qixi_regest_count[1] / $page_per));
		
        require MooTemplate('public/service_'.$h.'','module');
	    exit;
}




//电视相亲活动s
function TV_activity($h,$channel){
	GLOBAL $user_arr;
	GLOBAL $memcached;
	$db = $GLOBALS['db'];
	 put_activity_click($channel);
        $click_num=get_activity_click($channel);
        $activity_register_count=get_activity_register($channel);
		$male_num=$activity_register_count[0];
        $female_num=$activity_register_count[1];
        //报名数
	    $num=$male_num+$female_num;
		$sql="select username,operationtime,remark  from web_ahtv_remark where channel='".$channel."' and ispass=1 order by operationtime desc";
        $result=$db->getAll($sql);
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$seccode1 = strtolower(MooGetGPC('seccode','string','P'));
			$seccode2 = MooGetGPC('seccode','string','C');
			$session_seccode = $memcached->get($seccode2);
	        if($seccode1 != $session_seccode){
			   MooMessage("验证码填写不正确，请确认。", 'index.php?n=activity&h='.$h.'','','1',1);
			}
			$content=$_POST['content'];
			$date=date('Y-m-d H:i:s');
			$nickname=$user_arr['nickname'];
			$userid = $user_arr['uid'];
			$query=$db->query("insert into  web_ahtv_remark(uid,username,remark,operationtime,channel) values('$userid','$nickname','$content','$date','$channel')");

			if($query){
		        MooMessage("评论成功。", 'index.php?n=activity&h='.$h.'','','3',3);
			}
		}else{
	        $seccode = md5(uniqid(rand(), true));
			MooSetCookie('seccode',$seccode,3600,'');
			$session_seccode = $memcached->set($seccode , '' , 0 , 300);
		}
		require MooTemplate('public/service_'.$h.'', 'module');
        exit;

}
//安徽卫视携手真爱一生网报名活动
function service_register(){
    global $_MooClass,$dbTablePre,$user_arr,$userid;
    $channel=$_GET['channel'];
    $username=empty($_POST['username'])?'':$_POST['username'];
    $gender=empty($_POST['gender'])?'0':$_POST['gender'];
    $birthday_post = empty($_POST['year'])?'':$_POST['year'];
    $birthday=$birthday_post.'-'.$birthday_post;
    $workprovince=empty($_POST['workprovince'])?'':$_POST['workprovince'];
    $workcity=empty($_POST['workcity'])?'':$_POST['workcity'];
    $mobile=isset($_POST['mobile'])?$_POST['mobile']:'';
    $date=date('Y-m-d');
    $ip=GetIP();
   //echo $user_arr['s_cid'];exit;
    if($user_arr['s_cid']=='40'){ //普通会员 转向升级页面
    	$username=$user_arr['username'];
    	$gender=$user_arr['gender'];
//    	$birthday=$user_arr['birthyear'].'-'.(date('m',$user_arr['birth'])+0);
		$birthday= $user_arr['birth'];//birth modify
    	$workprovince=$user_arr['province'];
        $workcity=$user_arr['city'];
        $mobile=$user_arr['telphone'];
    	if($channel=='2'){
	        $_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,upgradenum,action,operationtime,channel) values('$ip','$userid',1,3,'$date','2')");
	        
	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid='$userid' and channel='2'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','2')");
	        }
    	}elseif($channel=='3'){
	        $_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,upgradenum,action,operationtime,channel) values('$ip','$userid',1,3,'$date','3')");
	        
	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid='$userid' and channel='3'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','3')");
	        }
    	}elseif($channel=='4'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','4')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='4'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','4')");
	        }
        }elseif($channel=='5'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','5')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='5'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','5')");
	        }
        }elseif($channel=='6'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','6')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='6'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','6')");
	        }
        }elseif($channel=='7'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','7')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='7'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','7')");
	        }
        }elseif($channel=='8'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','8')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='8'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','8')");
	        }
        }elseif($channel=='9'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','9')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='9'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','9')");
	        }
        }elseif($channel=='10'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','10')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='10'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','10')");
	        }
        }elseif($channel=='11'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','11')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='11'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','11')");
	        }
        }elseif($channel=='12'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','12')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='12'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','12')");
	        }
        }elseif($channel=='13'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','13')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='13'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','13')");
	        }
        }elseif($channel=='14'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','14')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='14'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','14')");
	        }
        }elseif($channel=='15'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','15')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='15'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','15')");
	        }
        }elseif($channel=='16'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','16')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='16'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','16')");
	        }
        }elseif($channel=='17'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','17')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='17'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','17')");
	        }
        }elseif($channel=='18'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','18')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='18'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','18')");
	        }
        }elseif($channel=='19'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','19')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='19'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','19')");
	        }
        }elseif($channel=='20'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','20')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='20'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','20')");
	        }
        }elseif($channel=='21'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','21')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='21'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','21')");
	        }
        }elseif($channel=='22'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','22')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='22'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','22')");
	        }
        }elseif($channel=='23'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','23')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='23'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','23')");
	        }
        }elseif($channel=='24'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','24')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='24'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','24')");
	        }
        }elseif($channel=='25'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','25')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='25'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','25')");
	        }
        }elseif($channel=='26'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','26')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='26'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','26')");
	        }
        }elseif($channel=='27'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','27')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='27'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','27')");
	        }
        }elseif($channel=='28'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','28')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='28'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','28')");
	        }
        } elseif($channel=='29'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','29')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='29'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','29')");
	        }
        } elseif($channel=='30'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date',$channel)");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel=$channel",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date',$channel)");
	        }
        }elseif($channel=='31'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date',$channel)");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel=$channel",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date',$channel)");
	        }
        }elseif($channel=='32'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date',$channel)");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel=$channel",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date',$channel)");
	        }
        }else {
            $_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,upgradenum,action,operationtime,channel) values('$ip','$userid',1,3,'$date','1')");
            
            $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid='$userid' and channel='1'",true);
            if(empty($user['id'])){
              $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','1')");
            }
    	}
    	if($channel==4){
    		$url = 'index.php?n=payment&h=channel_qixi';
    	}else if($channel==5){
    		$url = 'index.php?n=payment&h=channel_xuanyan';
    	}else if($channel==6){
    		$url = 'index.php?n=payment&h=channel_eyes';
    	}else if($channel==8){
    		$url = 'index.php?n=payment&h=channel_perfume';
    	}else if($channel==12){
    		$url = 'index.php?n=payment&h=channel_azlt';
    	}else if($channel==13){
    		$url = 'index.php?n=payment&h=channel_jiaren';
    	}else if($channel==19){
    		$url = 'index.php?n=payment&h=channel_hand';
    	}else if($channel==25){
    		$url = 'index.php?n=payment&h=channel_fakeface';
    	}else if($channel==21){
    		$url = 'index.php?n=payment&h=channel_yz';
    	}else if($channel==22){
    		$url = 'index.php?n=payment&h=channel_spring';
    	}else if($channel==23){
    		$url = 'index.php?n=payment&h=channel_ruby';
    	}else if($channel==24){
    		$url = 'index.php?n=payment&h=channel_sliver';
    	}else if($channel==26){
    		$url = 'index.php?n=payment&h=channel_midsummer';
    	}else if($channel==27){ // 免费
    		$url = 'index.php?n=activity&h=butterfly';
    	}else if($channel==28){ // 免费
    		$url = 'index.php?n=activity&h=jhreg';
    	}else if($channel==29){ // 免费
    		$url = 'index.php?n=activity&h=encounter';
    	}else if($channel==30){ // 免费
    		$url = 'index.php?n=activity&h=hztv';
    	}else if($channel==31){ // 免费
    		$url = 'index.php?n=payment&h=channel_dusk';
    	}else if($channel==32){ // 
		    if($user_arr['gender']==1){
    		   $url = 'index.php?n=activity&h=rlsl';
			}else{
			   $url = 'index.php?n=payment&h=channel_rlsl';
			}
    	}else{
    		$url = 'index.php?n=payment&h=diamond';
    	}
    	MooMessage("您已成功报名参加活动！",$url,'03');
        
    }elseif($user_arr['s_cid']=='30' || $user_arr['s_cid']=='20' || $user_arr['s_cid']=='10' ){ //钻石高级会员转向 联系真爱一生 提示
    	$username=$user_arr['username'];
        $gender=$user_arr['gender'];
         $barr = explode('-',$user_arr['birth']);//birth modify
        $birthmonth = $barr[1];
        $birthmonth = empty($birthmonth)?'':$birthmonth;
        $birthday=$user_arr['birthyear'].'-'.$birthmonth;
        $workprovince=$user_arr['province'];
        $workcity=$user_arr['city'];
        $mobile=$user_arr['telphone'];
        
        if($channel=='2'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','2')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='2'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','2')");
	        }
        }elseif($channel=='3'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','3')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='3'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','3')");
	        }
        }elseif($channel=='4'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','4')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='4'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','4')");
	        }
        }elseif($channel=='5'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','5')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='5'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','5')");
	        }
        }elseif($channel=='6'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','6')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='6'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','6')");
	        }
        }elseif($channel=='7'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','7')");
	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='7'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','7')");
	        }
        }elseif($channel=='8'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','8')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='8'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','8')");
	        }
        }elseif($channel=='9'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','9')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='9'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','9')");
	        }
        }elseif($channel=='10'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','10')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='10'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','10')");
	        }
        }elseif($channel=='11'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','11')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='11'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','11')");
	        }
        }elseif($channel=='12'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','12')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='12'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','12')");
	        }
        }elseif($channel=='13'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','13')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='13'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','13')");
	        }
        }elseif($channel=='14'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','14')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='14'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','14')");
	        }
        }elseif($channel=='15'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','15')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='15'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','15')");
	        }
        }elseif($channel=='16'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','16')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='16'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','16')");
	        }
        }elseif($channel=='17'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','17')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='17'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','17')");
	        }
        }elseif($channel=='18'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','18')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='18'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','18')");
	        }
        }elseif($channel=='19'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','19')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='19'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','19')");
	        }
        }elseif($channel=='20'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','20')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='20'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','20')");
	        }
        }elseif($channel=='21'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','21')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='21'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','21')");
	        }
        }elseif($channel=='22'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','22')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='22'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','22')");
	        }
        }elseif($channel=='23'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','23')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='23'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','23')");
	        }
        }elseif($channel=='24'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','24')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='24'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','24')");
	        }
        }elseif($channel=='25'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','25')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='25'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','25')");
	        }
        }elseif($channel=='26'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','26')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='26'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','26')");
	        }
        }elseif($channel=='27'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','27')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='27'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','27')");
	        }
        }elseif($channel=='28'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','28')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='28'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','28')");
	        }
        }elseif($channel=='29'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','29')");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='29'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','29')");
	        }
        }elseif($channel=='30'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date',$channel)");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel=$channel",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date',$channel)");
	        }
        }elseif($channel=='31'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date',$channel)");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel=$channel",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date',$channel)");
	        }
        }elseif($channel=='32'){
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date',$channel)");

	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel=$channel",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date',$channel)");
	        }
        }else{
			$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','1')");

            $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where uid=$userid and channel='1'",true);
            if(empty($user['id'])){
              $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','1')");
            }
        }
   		if($channel==4){
    		$url = 'index.php?n=payment&h=channel_qixi';
    	}else if($channel==5){
    		$url = 'index.php?n=payment&h=channel_xuanyan';
    	}else if($channel==6){
    		$url = 'index.php?n=payment&h=channel_eyes';
    	}else if($channel==8){
    		$url = 'index.php?n=payment&h=channel_perfume';
    	}else if($channel==12){
    		$url = 'index.php?n=payment&h=channel_azlt';
    	}else if($channel==13){
    		$url = 'index.php?n=payment&h=channel_jiaren';
    	}else if($channel==15){
    		$url = 'index.php?n=payment&h=channel_wink';
    	}else if($channel==16){
    		$url = 'index.php?n=payment&h=channel_sing';
    	}else if($channel==17){
    		$url = 'index.php?n=payment&h=channel_luck';
    	}else if($channel==18){
    		$url = 'index.php?n=payment&h=channel_gourd';
    	}else if($channel==19){
    		$url = 'index.php?n=payment&h=channel_hand';
    	}else if($channel==20){
    		$url = 'index.php?n=payment&h=channel_spa';
    	}else if($channel==21){
    		$url = 'index.php?n=payment&h=channel_yz';
    	}else if($channel==22){
    		$url = 'index.php?n=payment&h=channel_spring';
    	}else if($channel==23){
    		$url = 'index.php?n=payment&h=channel_ruby';
    	}else if($channel==24){
    		$url = 'index.php?n=payment&h=channel_sliver';
    	}else if($channel==25){
    		$url = 'index.php?n=payment&h=channel_fakeface';
    	}else if($channel==26){
    		$url = 'index.php?n=payment&h=channel_midsummer';
    	}else if($channel==27){ // 免费
    		$url = 'index.php?n=activity&h=butterfly';
    	}else if($channel==28){ // 免费
    		$url = 'index.php?n=activity&h=jhreg';
    	}else if($channel==29){ // 免费
    		$url = 'index.php?n=activity&h=encounter';
    	}else if($channel==30){ // 免费
    		$url = 'index.php?n=activity&h=hztv';
    	}else if($channel==31){ 
    		$url = 'index.php?n=payment&h=channel_dusk';
    	}else if($channel==32){ 
		    if($user_arr['gender']==1){
			  $url = 'index.php?n=activity&h=rlsl';
    		}else $url = 'index.php?n=payment&h=channel_rlsl';
    	}else{
    		$url = 'index.php?n=payment&h=diamond';
    	}
        MooMessage("请拨打  4006780405 找您的专线真爱一生联系！",$url,'03');
    }elseif(empty($userid)){
    	$ip=GetIP();
        if($channel=='2'){
        	$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','2')");
        	
	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where mobile='$mobile' and channel='2'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','2')");
	        }
        }elseif($channel=='3'){
        	$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','3')");
        	
	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where mobile='$mobile' and channel='3'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','3')");
	        }
        }elseif($channel=='7'){
        	$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','7')");
        	
	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where mobile='$mobile' and channel='7'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','7')");
	        }
        }elseif($channel=='10'){
        	$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','10')");
        	
	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where mobile='$mobile' and channel='10'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','10')");
	        }
        }elseif($channel=='11'){
        	$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','11')");
        	
	        $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where mobile='$mobile' and channel='11'",true);
	        if(empty($user['id'])){
	          $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','11')");
	        }
        }elseif($channel=='4' || $channel=='5' || $channel=='6'|| $channel=='8' || $channel=='12' || $channel=='13'|| $channel=='14'|| $channel=='15'|| $channel=='16' || $channel=='17'|| $channel=='18'|| $channel=='25'|| $channel=='20'|| $channel=='21' || $channel=='22' || $channel=='24' || $channel=='26' || in_array($channel, array(27, 28, 29, 30,31,32))){
        	MooMessage("尊敬的游客您好,本次活动仅限注册用户才可以,请您先注册或者登录后才可以参见！",'index.php?n=register','03');
        }else{
        	$_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,regnum,action,operationtime,channel) values('$ip','$userid',1,2,'$date','1')");
        	
            $user=$_MooClass['MooMySQL']->getOne("select id from web_ahtv_reguser where mobile='$mobile' and channel='1'",true);
            if(empty($user['id'])){
              $_MooClass['MooMySQL']->query("insert into web_ahtv_reguser(uid,username,gender,birthday,province,city,mobile,regtime,channel) values('$userid','$username','$gender','$birthday','$workprovince','$workcity','$mobile','$date','1')");
            }
        }
        MooMessage("您已成功报名参加活动,现在跳转到真爱一生网注册页面！",'index.php?n=register','03');
    }
}
//发帖验证码
function post_code(){
	//验证码
	$img = MooAutoLoad('MooSeccode');
	$img -> outCodeImage(125,30,4);
}

/**
 * 获取活动的关注数
 * $channel 活动项目
 */
function get_activity_click($channel){
    global $_MooClass;
     $count=$_MooClass['MooMySQL']->getOne('select count(`id`) as `count` from `web_ahtv` where channel='.$channel);
     return $count['count'];
}

/**
 * 添加关注数
 * $channel 活动项目
 */
function put_activity_click($channel){
    global $_MooClass,$userid;
     $ip=GetIP();
	 $date=date('Y-m-d');
	 $_MooClass['MooMySQL']->query("insert into web_ahtv(ip,uid,clicknum,action,operationtime,channel) values('$ip','$userid',1,1,'$date','$channel')");
}

/**
 *  获取活动参加数(男,女);
 * $channel 活动项目
 */
function get_activity_register($channel){
    global $_MooClass;
    $regest_count=array();
    $count=$_MooClass['MooMySQL']->getAll('select count(`id`) as `count`,`gender` from `web_ahtv_reguser` where channel='.$channel.' group by `gender`');
    foreach($count as $value){
            $regest_count[$value['gender']]=$value['count'];
    }
    return $regest_count;
}

/***************************************控制层****************************************/ 
$pagesize = 10;         //服务中心信息每页显示的条目数

include "./module/{$name}/function.php";

$back_url='index.php?'.$_SERVER['QUERY_STRING'];


$type=$_GET['h'];

switch ($type) {
     //note实名制
    case 'realname':
		require MooTemplate('public/service_realname','module');
		break;
    case 'scheme':
    //note 宣传页面--500万基金计划
        require MooTemplate('public/service_scheme', 'module');
		break;
	//note 宣传马尔代之旅
    case 'travel':
	    require MooTemplate('public/service_travel','module');
	    break;
	case 'ggj':
	    require MooTemplate('public/service_ggj','module');
	    break;
	case "sevenanniversary":
	    require MooTemplate('public/service_anniversary','module');
		break;
	case "springDate":
	    require MooTemplate('public/service_springDate','module');
	    break;
	case "tf":
	    require MooTemplate('public/service_tf','module');
	    break;
	case "laborday":
	    require MooTemplate('public/service_laborday','module');
	    break;
	case "motherday":
	    require MooTemplate('public/service_motherday','module');
	    break;
	case "fatherday":
	    require MooTemplate('public/service_fatherday','module');
	    break;
	case "dragon":
	    require MooTemplate('public/service_dragon','module');
	    break;
	case "sliverTemp":
	    require MooTemplate('public/service_sliverTemp','module');
	    break;
	case "olympics":
	    require MooTemplate('public/service_olympicsGame','module');
	    break;
	case "fakeface":
	    require MooTemplate('public/service_fakeface','module');
	    break;
	case "dusk":
	    require MooTemplate('public/service_dusk','module');
	    break;
	case "rlsl":
	    require MooTemplate('public/service_rlsl','module');
	    break;
	case "midsummer":
	    require MooTemplate('public/service_midsummer','module');
	    break;
	case "caidie":
	    require MooTemplate('public/service_caidie','module');
	    break;
	case "midautumn":
	    require MooTemplate('public/service_midautumn','module');
	    break;
	case 'meetfate':
		require MooTemplate('public/service_meetfate', 'module');
		 break;
	case "upgrade":
	    require MooTemplate('public/service_upgrade','module');
	    break;
	case "ruby":
	    require MooTemplate('public/service_ruby','module');
	    break;
    case "valentine":
	    require MooTemplate('public/service_valentine','module');
		break;
	case "cvd":
	    require MooTemplate('public/service_chinaValentinesDay','module');
		break;
	case "womwenday":
		require MooTemplate('public/service_womwenday','module');
		break;
   case "chinaglod":
		require MooTemplate('public/service_chinaglod','module');
		break;
	
	//note 安徽卫视
   case 'ahtv':
	    put_activity_click(1);
        $click_num=get_activity_click(1);
        $activity_register_count=get_activity_register(1);
		$male_num=$activity_register_count[0];
        $female_num=$activity_register_count[1];
        $num=$male_num+$female_num;
        
        $page_per = 4;
	    $page = intval(max(1,MooGetGPC('page','integer')));
	    $limit = 4;
	    $offset = ($page-1)*$limit;
	    
	    
	    $sql = "SELECT COUNT(uid) AS COUNT FROM web_ahtv_remark where channel=1 and ispass=1 ";
	    $result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	    $total = $result['COUNT'];
    
    
        $sql="select username,operationtime,remark  from web_ahtv_remark where channel=1 and ispass=1 order by operationtime desc  LIMIT {$offset},{$limit} ";
        $result=$_MooClass['MooMySQL']->getAll($sql);
        
       
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$seccode1 = strtolower(MooGetGPC('seccode','string','P'));
			$seccode2 = MooGetGPC('seccode','string','C');
			$session_seccode = $memcached->get($seccode2);

	        if($seccode1 != $session_seccode){
			   MooMessage("验证码填写不正确，请确认。", "index.php?n=activity&h=ahtv",'','1',1);
			}

			$content=$_POST['content'];
			$date=date('Y-m-d H:i:s');
			$nickname=$user_arr['nickname'];

			$query=$_MooClass['MooMySQL']->query("insert into  web_ahtv_remark(uid,username,remark,operationtime,channel) values('$userid','$nickname','$content','$date','1')");

			if($query){
		        MooMessage("评论成功。", "index.php?n=activity&h=ahtv",'','3',3);
			}

		}else{
	        $seccode = md5(uniqid(rand(), true));
			MooSetCookie('seccode',$seccode,3600,'');
			$session_seccode = $memcached->set($seccode , '' , 0 , 300);
		}
		
		$currenturl = "index.php?n=activity&h=ahtv";
		$pages = multipage( $total, $page_per, $page, $currenturl );
      
    
        require MooTemplate('public/service_ahtv','module');
		break;
   //note 辽宁卫视
   case 'lntv': 
        put_activity_click(2);
        $click_num=get_activity_click(2);
        $activity_register_count=get_activity_register(2);
		$male_num=$activity_register_count[0];
        $female_num=$activity_register_count[1];
        //报名数
	    $num=$male_num+$female_num;
	    
	     $page_per = 5;
        $page = intval(max(1,MooGetGPC('page','integer')));
        $limit = 5;
        $offset = ($page-1)*$limit;
        
        
        $sql = "SELECT COUNT(uid) AS COUNT FROM web_ahtv_remark where channel=2 and ispass=1 ";
        $result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
        $total = $result['COUNT'];
        
		$sql="select username,operationtime,remark  from web_ahtv_remark where channel=2 and ispass=1 order by operationtime desc  LIMIT {$offset},{$limit} ";
        $result=$_MooClass['MooMySQL']->getAll($sql);
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$seccode1 = strtolower(MooGetGPC('seccode','string','P'));
			$seccode2 = MooGetGPC('seccode','string','C');
			$session_seccode = $memcached->get($seccode2);
	        if($seccode1 != $session_seccode){
			   MooMessage("验证码填写不正确，请确认。", "index.php?n=activity&h=lntv",'','1',1);
			}

			$content=$_POST['content'];
			$date=date('Y-m-d H:i:s');
			$nickname=$user_arr['nickname'];

			$query=$_MooClass['MooMySQL']->query("insert into  web_ahtv_remark(uid,username,remark,operationtime,channel) values('$userid','$nickname','$content','$date','2')");

			if($query){
		        MooMessage("评论成功。", "index.php?n=activity&h=lntv",'','3',3);
			}

		}else{
	        $seccode = md5(uniqid(rand(), true));
			MooSetCookie('seccode',$seccode,3600,'');
			$session_seccode = $memcached->set($seccode , '' , 0 , 300);
		}
		
		$currenturl = "index.php?n=activity&h=lntv";
        $pages = multipage( $total, $page_per, $page, $currenturl);
       

        require MooTemplate('public/service_lntv','module');
		break;
   case 'register':
        service_register();
        break;

  //活动专区
   case 'activity':
		$where = '';
        $channel_count=array();
        $count=$_MooClass['MooMySQL']->getAll('SELECT count(id) AS count,`channel` FROM `web_ahtv_reguser` WHERE `uid`!=0 group by `channel` ');
        foreach($count as $value){
               $channel_count[$value['channel']]=$value['count'];
        }
        if(isset($_REQUEST['group'])){
				$group = $_REQUEST['group'];
		}else{
			$group = 1;
		}
		$page_per = 6;
        $page = max(1,MooGetGPC('page','GET'));
		
        $limit = 6;
        $offset = ($page-1)*$limit;
		$offset_last = $offset+$limit-1;
       	if($group == 2){
       		//$sql = "select * from web_activity_img where `group` = '2' order by sort asc limit 0,7";
			$sql = "select * from web_activity_img   order by sort asc limit 0,8";
       		$result=$_MooClass['MooMySQL']->getAll($sql);
			$where = 'and type = 1';
       	
       	}else if($group == 3){
       		//$sql = "select * from web_activity_img where `group` = '3' order by sort asc limit 0,7";
			$sql = "select * from web_activity_img   order by sort asc limit 0,8";
       		$result=$_MooClass['MooMySQL']->getAll($sql);
			$where = 'and type = 0';
       	
       	}else if($group == 5){
       		//$sql = "select * from web_activity_img where `group` = '3' order by sort asc limit 0,7";
			$sql = "select * from web_activity_img   order by sort asc limit 0,8";
       		$result=$_MooClass['MooMySQL']->getAll($sql);
			$where = 'and type = 2';
       	
       	}else{
       		$sql = "select * from web_activity_img  order by sort asc limit 0,8";
       		$result=$_MooClass['MooMySQL']->getAll($sql);
    	}
		
		if(isset($_REQUEST['city']) || isset($_REQUEST['province'])){
			$province = $_REQUEST['province'];
			$city = !empty($_REQUEST['city'])?$_REQUEST['city']:'';
			if(empty($province)){
				$where .= " and city='$city'";
			}else{
				$where .= " and province='$province'";
			}
			
		}
			$time = time();
			$sql = "select count(id) as count from web_activity where title !='' $where";
			$a = $_MooClass['MooMySQL']->getOne($sql);
	        $total = $a['count'];
			$currenturl = "index.php?n=activity&h=activity&page={$page}";
		    $pages = multipage( $total, $page_per, $page, $currenturl );
		    $page_num = ceil($total/$limit);
			
			$sql = "select * from web_activity where closetime>='$time' $where order by closetime asc";
			$content1 = $_MooClass['MooMySQL']->getAll($sql);
			
			$sql = "select * from web_activity where closetime<'$time' $where order by closetime desc";
			$content2 = $_MooClass['MooMySQL']->getAll($sql);
			$content3 = array_merge_recursive($content1,$content2);
			
			for($i=$offset;$i<=$offset_last;$i++){
				if(empty($content3[$i])){
				
				}else{
					$content[] = $content3[$i];
				}
				
			}
			
			if(is_array(isset($content)?$content:'')){
				foreach($content as $k => $v){	
					if($v['closetime'] != 0){
						$timeH1 = date('H:i',$v['opentime']);
						$timeH2 = date('H:i',$v['closetime']);
						$timed1 = date('d',$v['opentime']);
						$timed2 = date('d',$v['closetime']);
						if($timeH1 == $timeH2 && $timed1 != $timed2){
							$content[$k]['time'] = date('Y年m月d日',$v['opentime']).'-'.date('d日',$v['closetime']);
						}else if($timeH1 != $timeH2 && $timed1 == $timed2){
							if($timeH2 == '00:00'){
								$content[$k]['time'] = date('Y-m-d H:i',$v['opentime']);
							}else{
								$content[$k]['time'] = date('Y-m-d H:i',$v['opentime']).'-'.date('H:i',$v['closetime']);
							}
							
						}else if($timeH1 == $timeH2 && $timed1 == $timed2){
							$content[$k]['time'] = date('Y-m-d',$v['closetime']);
						}else if($timeH1 != $timeH2 && $timed1 != $timed2){
							$content[$k]['time'] = date('Y-m-d H:i',$v['opentime']).'-'.date('Y-m-d H:i',$v['closetime']);
						}else{
							$content[$k]['time'] = '待定';
						}
					}else{
						
						$content[$k]['time'] = '待定';
					}
					
				}
			}else{
				$content ='该地区暂时没有活动';
			}
		$pages = array();
		for($i=1;$i<=$page_num;$i++){
			$pages[] = $i;
		}
		if($page>1){
			$up = $page-1;
		}
		if($page<$page_num){
			$down = $page+1;
		}
	
	    require MooTemplate('public/service_activity','module');
        break;

   //湖北卫视
   case 'hbtv':
	   TV_activity('hbtv','3');
	   break;
	//活动影集
	case 'album':
        $channel=MooGetGPC('channel','intval','G');//活动项目
        $term=MooGetGPC('term','intval','G');//活动日期
        include "./module/activity/album_config.php";
        $channel=array_key_exists($channel,$album_channel_array)?$channel:0;
        $term=in_array($term,$album_channel_term_array[$channel])?$term:$album_channel_term_array[$channel][0];
        if(in_array($channel,array(2,3))){
            MooMessage("您要关注的活动还没有开始", "index.php?n=activity&h=album",'','1',1);
            exit;
        }
//        var_dump($term);exit;
        $image_root_dir='module/activity/templates/default/images/album/'.$album_channel_array[$channel]['dir'].'/'.$term.'/';
        include './'.$image_root_dir.'album_data_config.php';
        $type= MooGetGPC('album_type','intval','G');
        $type=array_key_exists($type,$album_type)?$type:0;
		require MooTemplate('public/service_album', 'module');
		break;
     case "seccode":
		post_code();
		break;
     case "qixi":	
       below_activity('qixi','4');
	   break;
	 case 'near':
		below_activity('near','14');
		break;
	 case 'wink':
		below_activity('wink','15');
		break;
	 case 'sing':
		 below_activity('sing','16');
		 break;
	 case 'luck':
		 below_activity('luck','17');
		 break;
	case 'spring':
		 below_activity('spring','22');
		 break;
	case 'jhreg':
	     below_activity('jhreg','28');
		 break;
	case 'reddiamond':
		 below_activity('reddiamond','23');
		 break;
	case 'sliver':
		 below_activity('sliver','24');
		 break;
	case 'fake':
		 below_activity('fake','25');
		 break;
	case 'summer':
		 below_activity('summer','26');
		 break;
	case 'butterfly':
		 below_activity('butterfly','27');
		 break;
    case 'jianghuai':
		 below_activity('jianghuai','28');
		 break;
    case 'encounter':
		 below_activity('encounter','29');
		 break;
	case 'dusklove':
	     below_activity('dusklove','31');
		 break;
	case 'rlslake':
	     below_activity('rlslake','32');
		 break;
     case "xuanyan":
         below_activity('xuanyan','5');
		 break;
     case "jiaren":
         below_activity('jiaren','13');
		 break;
     case "xscm":
		 TV_activity('xscm','10');
		 break;
     case "perfume":
         below_activity('perfume','8');
		 break;
     case "azlt":
         below_activity('azlt','12'); 
         break;		 
	 case "eyes":
        below_activity('eyes','6');   
        break;        
	 case "zyzhuodong":
		 require MooTemplate('public/service_zyzhuodong','module');
		 break;
	 case "tytv":
        TV_activity('tytv','9');
		break;
	 case "njtv":
        TV_activity('njtv','11');
		break;
     case "sctv":
         TV_activity('sctv','7');
		 break;
     case "hztv":
         TV_activity('hztv','30');
		 break;
	 case "gourd":
         below_activity('gourd','18');
		 break;
	 case "hand":
         below_activity('hand','19');
		 break;
	 case "spa":
		below_activity('spa','20');
		break;
	 case "yz":
		below_activity('yz','21');
		break;
} 
?>
