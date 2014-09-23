<?php
/**
 * 管理员列表
 * @author:fanglin
 * 2010年06月
 */

/**
 * *****************************************逻辑层(M)/表现层(V)****************************************
 */

// note 管理员列表,fanglin
function system_adminuser_list() {
    global $adminid;
	//echo $adminid;
	//echo getGroupID($adminid);
	$sql_where = '';
	$page_per = 15;
	$page = max ( 1, MooGetGPC ( 'page', 'integer' ) );
	$limit = 15;
	$offset = ($page - 1) * $limit;
	
	$currenturl = "index.php?action=system_adminuser&h=list";
	
	$username=MooGetGPC('username','string');
	$usercode=MooGetGPC('usercode','string');
	
	if(!empty($username)) {
	   $currenturl = "index.php?action=system_adminuser&h=list&username={$username}";
	   $sql_where=" WHERE username LIKE '{$username}%'";
	}
	
	if(!empty($uid)) {
	    $currenturl = "index.php?action=system_adminuser&h=list&uid={$uid}";
		$sql_where = " WHERE uid LIKE '{$uid}%'";
	}
	    
	$choose = MooGetGPC ( 'choose', 'string', 'P' );
	$content = trim ( MooGetGPC ( 'content', 'string', 'P' ) );
	
	if ($choose == 'username') {
		$sql_where = " WHERE username LIKE '{$content}%'";
		$currenturl = "index.php?action=system_adminuser&h=list&username={$content}";
	} elseif ($choose == 'usercode') {
		$sql_where = " WHERE usercode LIKE '{$content}%'";
		$currenturl = "index.php?action=system_adminuser&h=list&usercode={$content}";
	}
	/*
	 * if(!empty($username)){ $sql_where = " WHERE username LIKE
	 * '{$username}%'"; }
	 */
	
	$sql = "SELECT COUNT(uid) AS COUNT FROM {$GLOBALS['dbTablePre']}admin_user $sql_where";
	$admin_user = $GLOBALS ['_MooClass'] ['MooMySQL']->getOne ( $sql );
	$total = $admin_user ['COUNT'];
	
	if ($choose == 'username') {
		$sql_where = " WHERE a.username LIKE '{$content}%'";
	} elseif ($choose == 'userid') {
		$sql_where = " WHERE a.uid LIKE '{$content}%'";
	}
	$sql = "SELECT a.*,b.groupname FROM {$GLOBALS['dbTablePre']}admin_user as a 
    		LEFT JOIN {$GLOBALS['dbTablePre']}admin_group as b 
    		ON a.groupid=b.groupid $sql_where ORDER BY a.uid DESC
    		LIMIT {$offset},{$limit}";
	$adminuser_list = $GLOBALS ['_MooClass'] ['MooMySQL']->getAll ( $sql );
	/* $url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	echo $url; */
	
	$pages = multipage ( $total, $page_per, $page, $currenturl );
	$page_num = ceil ( $total / $limit );
	require_once (adminTemplate ( 'adminuser_list' ));
	serverlog ( 1, $GLOBALS ['dbTablePre'] . 'admin_user', "{$GLOBALS['adminid']}号客服{$GLOBALS['username']}查看会员管理员列表", $GLOBALS ['adminid'] );

	
}

// note 添加(修改)管理员,fanglin
function system_adminuser_add() {
	if (MooGetGPC ( 'ispost', 'integer' )) {
		$userid = trim ( MooGetGPC ( 'aid', 'string', 'P' ) );
		$usercode = trim ( MooGetGPC ( 'usercode', 'string', 'P' ) );
		$username = trim ( MooGetGPC ( 'username', 'string', 'P' ) );
		$name = trim ( MooGetGPC ( 'name', 'string', 'P' ) );
		$groupid = trim ( MooGetGPC ( 'groupid', 'string', 'P' ) );
		$password = trim ( MooGetGPC ( 'password', 'string', 'P' ) );
		$desc = MooGetGPC ( 'desc', 'string', 'P' );
		$isedit = MooGetGPC ( 'isedit', 'string', 'P' );
		$is_allot = MooGetGPC ( 'is_allot', 'integer', 'P' ); // 是否自动分配客服
		$sale_commission = MooGetGPC ( 'sale_commission', 'integer', 'P' );
		$ccid = MooGetGPC ( 'ccid', 'integer', 'P' );
		$addtime = time ();
		
		if (empty ( $username ) || empty ( $groupid ) || empty ( $name )) {
			salert ( '请将信息填写完整' );
		}
		if (empty ( $password ) && ! $isedit)
			salert ( '请填写密码' );
		
		$sql = "SELECT username FROM {$GLOBALS['dbTablePre']}admin_user WHERE username='{$username}'";
		$admin_user = $GLOBALS ['_MooClass'] ['MooMySQL']->getOne ( $sql );
		
		$password = md5 ( $password );
		
		$sql = "";
		if (empty ( $userid )) {
			if (! empty ( $admin_user )) {
				salert ( '此用户名已经存在!' );
				exit ();
			}
			$sql = "INSERT INTO {$GLOBALS['dbTablePre']}admin_user SET `usercode`='{$usercode}',username='{$username}',name='{$name}',groupid='{$groupid}',password='{$password}',userdesc='{$desc}',is_allot='{$is_allot}',sale_commission='{$sale_commission}',dateline='{$addtime}'";
			$note = '添加管理员成功';
			$errors = '添加管理员失败';
			
			serverlog ( 1, $GLOBALS ['dbTablePre'] . 'members', "{$GLOBALS['adminid']}号客服{$GLOBALS['username']}创建客服", $GLOBALS ['adminid'] );
		} else {
			if (empty ( $_POST ['password'] )) {
				$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_user SET `usercode`='{$usercode}',username='{$username}',name='{$name}',groupid='{$groupid}',userdesc='{$desc}',is_allot='{$is_allot}',sale_commission='{$sale_commission}',ccid = '{$ccid}' WHERE uid='{$userid}'";
			} else {
				$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_user SET `usercode`='{$usercode}',username='{$username}',name='{$name}',groupid='{$groupid}',password='{$password}',userdesc='{$desc}',is_allot='{$is_allot}',sale_commission='{$sale_commission}',ccid = '{$ccid}' WHERE uid='{$userid}'";
			}
			$note = '修改管理员成功';
			$errors = '修改管理员失败';
			
			serverlog ( 1, $GLOBALS ['dbTablePre'] . 'members', "{$GLOBALS['adminid']}号客服{$GLOBALS['username']}修改{$userid}号客服信息", $GLOBALS ['adminid'] );
		}
		$result = $GLOBALS ['_MooClass'] ['MooMySQL']->query ( $sql );
		if ($result) {
			salert ( $note );
		} else {
			salert ( $errors );
		}
	}
	if ($GLOBALS ['groupid'] == 60) {
		$sql = "SELECT groupid,groupname FROM {$GLOBALS['dbTablePre']}admin_group ";
	} else {
		$sql = "SELECT groupid,groupname FROM {$GLOBALS['dbTablePre']}admin_group where groupid in (62,63,64,65,66,67,68,69,70,71)";
	}
	$group_list = $GLOBALS ['_MooClass'] ['MooMySQL']->getAll ( $sql );
	// print_r($group_list);
	
	require_once (adminTemplate ( 'adminuser_add' ));
}

// note 修改管理员信息,fanglin
function system_adminuser_edit() {
	$userid = MooGetGPC ( 'aid', 'integer', 'G' );
	$isedit = 1;
	if (empty ( $userid )) {
		MooMessageAdmin ( '参数错误', 'index.php?action=adminuser&h=list', 1 );
	}
	$sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$userid}'";
	$adminuser = $GLOBALS ['_MooClass'] ['MooMySQL']->getOne ( $sql );
	$sql = "SELECT groupid,groupname FROM {$GLOBALS['dbTablePre']}admin_group";
	$group_list = $GLOBALS ['_MooClass'] ['MooMySQL']->getAll ( $sql );
	require_once (adminTemplate ( 'adminuser_add' ));
}

// note 修改密码
function system_adminuser_password() {
	$ispost = MooGetGPC ( 'ispost', 'integer', 'P' );
	if ($ispost) {
		$pw = trim ( MooGetGPC ( 'pw', 'string', 'P' ) );
		$newpw = trim ( MooGetGPC ( 'newpw', 'string', 'P' ) );
		$uid = trim ( MooGetGPC ( 'uid', 'integer', 'P' ) );
		
		$sql = "SELECT password FROM {$GLOBALS['dbTablePre']}admin_user WHERE uid='{$uid}'";
		$adminuser = $GLOBALS ['_MooClass'] ['MooMySQL']->getOne ( $sql );
		
		if (md5 ( $pw ) != $adminuser ['password']) {
			salert ( '原密码不正确' );
		} else {
			$newpw = md5 ( $newpw );
			$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_user SET password = '{$newpw}' WHERE uid='{$uid}'";
			$GLOBALS ['_MooClass'] ['MooMySQL']->query ( $sql );
			salert ( '密码修改成功' );
		}
	}
	require_once (adminTemplate ( 'adminuser_password' ));
}

// 生成客服缓存文件列表
function system_adminuser_kefucache() {
	create_kefuuser_cachefile ();
	echo '<h2>仅当客服用户名更改或新增客服时才需要执行些操作。</h2>';
	echo '<span style="color:red;">缓存文件已成功生成</span>';
}

/**
 * 红娘币基础参数设置-个人
 */
function system_adminuser_change() {
	$uid = MooGetGPC ( 'uid', 'array', 'P' );
	$interval = MooGetGPC ( 'interval', 'array', 'P' );
	$allot = MooGetGPC ( 'allot', 'array', 'P' );
	$amount = MooGetGPC ( 'amount', 'array', 'P' );
	$money = MooGetGPC ( 'money', 'array', 'P' );
	$data = array ();
	foreach ( $uid as $value ) {
		$data [] = '(\'' . $value . '\',\'' . $interval [$value] . '\',\'' . $allot [$value] . '\',\'' . $amount [$value] . '\',\'' . $money [$value] . '\')';
	}
	if (! empty ( $data )) {
		// 批量更新避免锁表
		$sql = 'insert into `' . $GLOBALS ['dbTablePre'] . 'admin_user` (`uid`,`interval`,`allot`,`amount`,`money`) values  ' . implode ( ',', $data ) . ' on duplicate key update `interval`=values(`interval`),`allot`=values(`allot`),`amount`=values(`amount`),`money`=values(`money`)';
		if ($GLOBALS ['_MooClass'] ['MooMySQL']->query ( $sql )) {
			MooMessageAdmin ( '设置成功', $_SERVER ['HTTP_REFERER'] );
		} else {
			MooMessageAdmin ( '更新失败SQL=>' . $sql, $_SERVER ['HTTP_REFERER'] );
		}
	} else {
		MooMessageAdmin ( '数据传递失败', $_SERVER ['HTTP_REFERER'] );
	}
}

/**
 * *********************************************控制层(C)****************************************
 */
$h = MooGetGPC ( 'h', 'string', 'G' ) == '' ? 'list' : MooGetGPC ( 'h', 'string', 'G' );
// note 动作列表
$hlist = array ('list', 'edit', 'add', 'password', 'kefucache', 'change' );
// note 判断页面是否存在
if (! in_array ( $h, $hlist )) {
	MooMessageAdmin ( '您要打开的页面不存在', 'index.php?action=system_adminuser&h=list' );
}
// note 判断是否有权限
if (! checkGroup ( 'system_adminuser', $h )) {
	MooMessageAdmin ( '您没有此操作的权限', 'index.php?action=system_admin&h=index', 1 );
}
switch ($h) {
	// note 管理员列表
	case 'list' :
		system_adminuser_list ();
		break;
	// note 添加管理员
	case 'add' :
		system_adminuser_add ();
		break;
	// note 修改管理员信息
	case 'edit' :
		system_adminuser_edit ();
		break;
	// note 修改密码
	case 'password' :
		system_adminuser_password ();
		break;
	// note 生成客服缓存文件
	case 'kefucache' :
		system_adminuser_kefucache ();
		break;
	case 'change' :
		system_adminuser_change ();
		break;
	default :
		system_adminuser_list ();
   	break;
}

?>