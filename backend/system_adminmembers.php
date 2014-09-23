<?php
function system_adminmembers_move(){
	global $dbTablePre,$_MooClass;
	$kefu_list = get_kefulist();
	$is_post=MooGetGPC('ispost','integer','G');
	$fromuser=MooGetGPC('fromuser','integer','G');
	if($is_post&&$fromuser){
		$grade=MooGetGPC('grade','integer','G');
		$getuser=MooGetGPC('getuser','integer','G');
		$move_num=MooGetGPC('move_num','integer','G');
		if($move_num){
			$limit="limit $move_num";
		}
		if($grade){
			$sql="update {$dbTablePre}members_search m left join {$dbTablePre}member_admininfo a on m.uid=a.uid set m.sid={$getuser}  where m.sid={$fromuser} and a.effect_grade={$grade} {$limit}";
			$sql2="select m.uid as uid from {$dbTablePre}members_search m left join {$dbTablePre}member_admininfo a on m.uid=a.uid where m.sid={$fromuser} and a.effect_grade={$grade} {$limit}";
		}else{
			$sql="update {$dbTablePre}members_search  set sid={$getuser}  where sid={$fromuser} {$limit}";
			$sql2="select uid from {$dbTablePre}members_search where sid={$fromuser} {$limit}";
		}
		$rs = $_MooClass['MooMySQL']->getAll($sql2);
		if(isset($rs) && $rs){
			foreach($rs as $k=>$v){
				$str_arr[$v] = array($getuser);
			}
			searchApi('members_man members_women') -> updateAttr(array('sid'),$str_arr);
		}
		$_MooClass['MooMySQL']->query($sql);
		$num=$_MooClass['MooMySQL']->affectedRows();
		if($num){
			$sql="update {$dbTablePre}admin_user set member_count=member_count-'{$num}' where uid={$fromuser}";
			$_MooClass['MooMySQL']->query($sql);
			$sql="update {$dbTablePre}admin_user set member_count=member_count+'{$num}' where uid={$getuser}";
			$_MooClass['MooMySQL']->query($sql);
			serverlog(4,$GLOBALS['dbTablePre']."members_search","{$GLOBALS['username']}将{$fromuser}号客服的{$num}个会员转出",$GLOBALS['adminid'],$uid);
		}
		salert("成功转移".$num."名会员","index.php?action=system_adminmembers&h=move");
	}
	require_once(adminTemplate('system_adminmembers_move'));
}

/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string');

//note 动作列表
$hlist = array('move');

//note 判断页面是否存在
if(!in_array($h,$hlist)){
	salert('您要打开的页面不存在');
}
//note 判断是否有权限
if(!checkGroup('system_adminmembers',$h)){
	salert('您没有修改此操作的权限');
}

switch($h){
	//note 红娘意见箱
	case 'move':
		system_adminmembers_move();
	break;	
    default:
    	exit('文件不存在');
    break;
    
}
?>