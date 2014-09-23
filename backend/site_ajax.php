<?php
/**
*	客服后台的网站管理下AJAX调用
*
*/

//note 加载框架配置参数
require './include/config.php';
//note 加载MooPHP框架
require '../framwork/MooPHP.php';

//note 包含共公方法
include './include/function.php';
//城市列表
//include './include/city_list.php';

function search_member(){
	$keyword = MooGetGPC('keyword','string','G');
	$sql = "SELECT uid,nickname,province,city FROM {$GLOBALS['dbTablePre']}members_search WHERE uid LIKE '%{$keyword}%' OR nickname LIKE '%{$keyword}%'";
	 

	$user_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	echo json_encode($user_list);	
}

function search_add_recommend(){
    global $timestamp;
	include './include/crontab_config.php';
	
	$uid = MooGetGPC('uid','integer','G');
	$index_userlist = MooGetGPC('index_userlist','string','G');
	$index_userlist_array = explode(',',$index_userlist);
	
	if(in_array($uid,$index_userlist_array)){
		echo 'exists';exit;
	}
	$province = MooGetGPC('province', 'integer', 'G') > 0 ? MooGetGPC('province', 'integer', 'G'): '0';
	$city = MooGetGPC('city', 'integer', 'G') > 0 ? MooGetGPC('city', 'integer', 'G'): '0';
	if(in_array( $province, array(10101201,10101002))){
		//note 修正广东省深圳和广州的区域查询 
		$city = $province;
		$province = 10101000;
	}
	
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_search WHERE uid='{$uid}' and is_lock = 1";
	$members_list = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if(!$members_list) {
		echo 'error';exit;
	}
	
	//判断是否已有
	$sql = "select uid from {$GLOBALS['dbTablePre']}members_recommend where uid='$uid' and city='$city' and province='$province'";
	$result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if($result) {
		echo 'repeat';exit;
	}
	//判断是否已满
	$result = $GLOBALS['_MooClass']['MooMySQL']->getOne("select count(uid) as count from {$GLOBALS['dbTablePre']}members_recommend where city='$city' and province='$province'");
	if($result['count'] >= 25) {
		echo 'full';exit;
	}
	$sql = "insert into {$GLOBALS['dbTablePre']}members_recommend(uid, province, city, dateline) values('$uid', '$province', '$city', '$timeStamp')";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	$age = date("Y")-$members_list['birthyear'];
	$marrage = $members_list['marriage']==1 ? '未婚' : '离异';
	$member_level = $members_list['city_star'] > 0 ? '城市之星' : $GLOBALS['member_level'][$members_list['s_cid']];
	$img = MooGetphotoAdmin($members_list['uid'],'small');
	$dateline=date('Y-m-d H:i:s',$timestamp);
	
	$td_bgcolor = "style='background-color:#cfeefe'";
	echo "
		<tr id='uid_{$members_list['uid']}'>
		    <td align='center' class='first-cell' $td_bgcolor>{$members_list['uid']}</td>
		    <td align='center' $td_bgcolor>{$members_list['nickname']}</a>
		</td>
	    <td align='center' $td_bgcolor>{$age}</td>
	    <td align='center' $td_bgcolor>{$marrage}</td>
		<td align='center' $td_bgcolor>{$member_level}</td>
		<td align='center' $td_bgcolor>{$dateline}</td>
		<td align='center' $td_bgcolor>";
		if($img) {
			echo "<img src='{$img}'/>";
		} else {
			echo "无";
		}
		echo "</td>
		<td align='center' $td_bgcolor>
			<input type='text' style='width:50px;' id='sort_{$members_list['uid']}' name='sort_{$members_list['uid']}' />
			<input type='button' value='修改' onclick='modify_sort({$members_list['uid']})' />
		</td>
		<td align='center' $td_bgcolor><a href='#' onclick='del_recommend({$members_list['uid']})'>删除</a></td>
		</tr>";
			
	//note 插入日志
	serverlog(3,'members_recommend','添加首页推荐会员uid='.$uid, $GLOBALS['adminid']);
}


function modify_sort(){
	$uid = MooGetGPC('uid','integer','G');
	$sort = MooGetGPC('sort','integer','G');
	if($sort >=25) {
		echo 'error';exit;
	}
	$time = time();
	
	$sql = "SELECT uid FROM {$GLOBALS['dbTablePre']}members_recommend WHERE uid='{$uid}'";
	$member = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	if(!empty($member)){
		$sql = "UPDATE {$GLOBALS['dbTablePre']}members_recommend SET sort='{$sort}' WHERE uid='{$uid}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}else{
		$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}members_search WHERE uid='{$uid}'";
		$member = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		$sql = "INSERT INTO {$GLOBALS['dbTablePre']}members_recommend SET
				uid='{$uid}',province='{$member['province']}',city='{$member['city']}',sort='{$sort}',dateline='{$time}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	}
	echo 'ok';
	//note 插入日志
	serverlog(4,"members_recommend","修改首页推荐会员排序为{$sort},uid={$uid}", $GLOBALS['adminid']);
}

//删除首页推荐会员
function del_recommend(){
	$uid = MooGetGPC('uid','integer','G');
	$sql = "DELETE FROM {$GLOBALS['dbTablePre']}members_recommend WHERE uid = {$uid}";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	echo 'ok';
	//note 插入日志
	serverlog(4,$GLOBALS['dbTablePre'].'members_recommend','删除首页推荐会员uid='.$uid, $GLOBALS['adminid']);
}

//note 统计故事标题数量
function countstorytitle(){
	$title = MooGetGPC('title','string','G');
	$sql = "SELECT count(*) count FROM {$GLOBALS['dbTablePre']}story WHERE title='{$title}'";
	$res = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	echo $res['count'];
}

//note 恋爱一方数量
function countstoryname1(){
	$name1 = MooGetGPC('name1','string','G');
	$sql1 = "SELECT count(*) count FROM {$GLOBALS['dbTablePre']}story WHERE name1='{$name1}'";
	$res1 = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql1);
	$sql2 = "SELECT count(*) count FROM {$GLOBALS['dbTablePre']}story WHERE name2='{$name1}'";
	$res2 = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql2);
	if($res1['count']>=1 || $res2['count']>=1){
		echo '1';
	}
}

//note 恋爱另一方数量
function countstoryname2(){
	$name2 = MooGetGPC('name2','string','G');
	$sql1 = "SELECT count(*) count FROM {$GLOBALS['dbTablePre']}story WHERE name1='{$name2}'";
	$res1 = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql1);
	$sql2 = "SELECT count(*) count FROM {$GLOBALS['dbTablePre']}story WHERE name2='{$name2}'";
	$res2 = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql2);
	if($res1['count']>=1 || $res2['count']>=1){
		echo '1';
	}
}


//首页钻石会员推荐
function index_diamond_recommend(){
     global $_MooClass;
	 $isIndex=$_POST['isIndex'];
	 $uid=$_POST['value'];
	
	 if($uid){
	    
	    $sql="update web_diamond_recommend set isindex='{$isIndex}' where uid={$uid}";
	   
	    if($_MooClass['MooMySQL']->query($sql)){
	        echo 'ok';
	    }else{
	        echo 'no';
	    }
	   

	 }else{
	    echo 'no';
	 }
	
    
}


//更新钻石会员信息
function update_diamond(){
     global $_MooClass;

     $sql="select s.uid,s.username,s.nickname,s.birthyear,s.gender,b.mainimg,s.images_ischeck,s.bgtime,s.endtime,s.province,s.city 
           	from web_members_search as s 
           	left join web_members_base as b on s.uid=b.uid
           	where s.s_cid=20";  
     $result=$_MooClass['MooMySQL']->getAll($sql);
     
     foreach($result as $value){
        $uid=$value['uid'];
     	$username=addslashes($value['username']);
     	$nickname=addslashes($value['nickname']);
     	$gender=$value['gender'];
     	$mainimg=$value['mainimg'];
     	$images_ischeck=$value['images_ischeck'];
     	$birthyear=$value['birthyear'];
     	$bgtime=$value['bgtime'];
     	$endtime=$value['endtime'];
     	$province=$value['province'];
     	$city=$value['city'];
     	
     	$sql="select count(uid) as num from web_diamond_recommend where uid='{$uid}'";
     	$diamond=$_MooClass['MooMySQL']->getOne($sql);
     	if($diamond['num']==1){
     		$sql="update web_diamond_recommend set username='{$username}',nickname='{$nickname}',birthyear='{$birthyear}',gender='{$gender}',mainimg='{$mainimg}',images_ischeck='{$images_ischeck}',bgtime='{$bgtime}',endtime='{$endtime}',province='{$province}',city='{$city}' where uid='{$uid}'";
            $_MooClass['MooMySQL']->query($sql);

     	}else{
     		$sql="insert into web_diamond_recommend (uid,username,nickname,birthyear,gender,mainimg,images_ischeck,bgtime,endtime,province,city) values('{$uid}','{$username}','{$nickname}','{$birthyear}','{$gender}','{$mainimg}','{$images_ischeck}','{$bgtime}','{$endtime}','{$province}','{$city}')";
     	    $_MooClass['MooMySQL']->query($sql);
     	}
   
     }
     
     echo "ok";

}

//删除首页推荐钻石会员缓存
function clear_cache(){
    global $_MooClass;
	
	//$_MooClass['MooCache']->setCache('cacheFile');
	$dir = '../data/block/';
	if (!file_exists($dir)){
		exit;
	}
	if ($dh = opendir($dir)) {
	    while (($file = readdir($dh)) !== false) {
	        if ($file == "." || $file == "..") continue;
	        
	        if(strpos($file,'web_star_recommend')!==false)
	        {   
	             echo $dir.$file."ddd<br>";exit;
	            //$newfile = str_replace('[1]', '', $file);
	            //rename($dir . $file, $dir . $newfile);3
	            unlink($dir.$file);
	        }
	    }
	    closedir($dh);
	}
	echo "ok";
}


//批量钻石会员首页推荐
function batch_diamond_recommend(){
     global $_MooClass;
     $isIndex=$_POST['isIndex'];
     $uid=$_POST['uid'];
     $uid=implode(',',$uid);
     
     if($uid){
        
        $sql="update web_diamond_recommend set isindex='{$isIndex}' where uid in ({$uid})";
        
        if($_MooClass['MooMySQL']->query($sql)){
            echo 'ok';
        }else{
            echo 'no';
        }

     }else{
        echo 'no';
     }

	
}

function change_diamond_sort(){
    $uid = MooGetGPC('uid','integer','P');
	$sort = MooGetGPC('sort','integer','P');
    $sql='update web_diamond_recommend set sort='.$sort.' where uid='.$uid;
    echo $GLOBALS['_MooClass']['MooMySQL']->query($sql)?1:0;
    exit;
}


/***************************************控制层(V)***********************************/
$n=MooGetGPC('n','string');
//判断是否登录
adminUserInfo();
if(empty($GLOBALS['adminid'])){
	echo 'nologin';exit;
}
switch( $n ){
	//首页钻石会员推荐
	case 'index_diamond_recommend':
		 index_diamond_recommend();
		 break;
    //更新钻石会员信息
    case 'update_diamond':
         update_diamond();
         break;
    //清空首页钻石会员缓存
    case 'clear_cache':
    	clear_cache();
    	break;
    //批量钻石会员首页推荐
    case 'batch_diamond_recommend':
    	batch_diamond_recommend();
    	break;
	case 'searchMember':
		search_member();
	    break;
	case 'add_recommend':
		search_add_recommend();
	    break;
	case 'modify_sort':
		modify_sort();
	    break;
	case 'del_recommend':
		del_recommend();
	    break;
	//note 统计故事标题数量
	case 'countstorytitle':
		countstorytitle();
	    break;
	//note 恋爱一方数量
	case 'countstoryname1':
		countstoryname1();
	    break;
	//note 恋爱另一方数量
	case 'countstoryname2':
		countstoryname2();
	   break;
	case 'update':
		home_update();
	    break;
    case 'change_diamond_sort':
        change_diamond_sort();
    break;
}
?>