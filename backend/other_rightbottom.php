<?php
function active_rightbottom_message(){
	$type = MooGetGPC('type','string');  
	$sql_where = ' WHERE  1=1 ';
	//note 显示全部
	if(isset($_GET['type']) == 'all'){
		$sql_where .= "";
	//note 显示已处理过的
	}else if((isset($_GET['type'])?$_GET['type']:'') == 'dealed'){
		$sql_where .= " AND dealstate = '1'";	
	//note 显示未处理过的	
	}else{
		$sql_where .= " AND dealstate = '0'";
	}
	
	//分页
	$page_per =20;
    $page = max(1,MooGetGPC('page','integer','G'));
    $limit = 20;
    $offset = ($page-1)*$limit;
	
   //所管理的客服id列表
   $myservice_idlist = get_myservice_idlist(); 
   
   if(empty($myservice_idlist)){
   		$sql_where .= " AND sid IN({$GLOBALS['adminid']})";
   }elseif($myservice_idlist == 'all'){
   		//all为客服主管能查看所有的
   }else{
   		$sql_where .= " AND sid IN($myservice_idlist)";
   }
   if(isset($_GET['id'])){
		$id = MooGetGPC('id','integer','G');
		$sql = "UPDATE {$GLOBALS['dbTablePre']}admin_remark SET status=1 WHERE id='{$id}'";
		$GLOBALS['_MooClass']['MooMySQL']->query($sql);
		salert('操作成功','index.php?action=other_rightbottom&h=message');
   }
   $total = getcount('admin_remark',$sql_where);

   $sql = "SELECT * FROM {$GLOBALS['dbTablePre']}admin_remark $sql_where ORDER BY id DESC LIMIT {$offset},{$limit}";
   $remark_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql); 
   
    //note 获得当前的url 去除多余的参数
	/* $currenturl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$currenturl = preg_replace("/(&page=\d+)/","",$currenturl);
	$currenturl = preg_replace("/(&type=undealed)/","",$currenturl);
	$currenturl = preg_replace("/(&type=dealed)/","",$currenturl);
	$currenturl = preg_replace("/(&type=all)/","",$currenturl);
	$currenturl2 = $currenturl;
	$currenturl = $currenturl."&type=$type";
	echo $currenturl; */
	$currenturl="index.php?action=other_rightbottom&h=message&type=$type";
	
	//note 跳转到某一页
	$page_num = ceil($total/$limit);
	$page_links = multipage( $total, $page_per, $page, $currenturl );
	//note 调用模板
	require(adminTemplate('other_rightbottom_message'));
}

//红娘站内邮件
function active_rightbottom_sitemail(){
	if($_POST){
		$sid = MooGetGPC('msg_sid','integer','P');
		
		if(empty($_POST['msg_sid']) && !empty($_POST['msg_username'])){
			$username = MooGetGPC('msg_username','string','P');
			$sql = "SELECT uid FROM {$GLOBALS['dbTablePre']}admin_user WHERE username='$username'";
			$admin_user = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
			if(empty($admin_user)){
				salert('此用户不存在');
				exit;	
			}
			$sid = $admin_user['uid']; 
		}
	
		$data['sid'] = $sid;
		$data['title'] = "{$GLOBALS['adminid']}号客服:".MooGetGPC('msg_title','string','P'); 
		$data['content'] = MooGetGPC('msg_content','string','P'); 
		$data['awoketime'] = time()+120;
		$data['dateline'] = time();
		$data['send_id'] = $GLOBALS['adminid'];
		inserttable('admin_remark',$data);
		salert('发送成功');exit;
	}
	$group_list = get_group_type();
	require(adminTemplate('other_rightbottom_sitemail'));
}


/***********************************************控制层(C)*****************************************/
$h=MooGetGPC('h','string','G')=='' ? 'message' : MooGetGPC('h','string','G');

//note 动作列表
$hlist = array('message','sitemail');
//note 判断页面是否存在
if(!in_array($h,$hlist)){
    salert('您要打开的页面不存在','index.php');
}

//note 判断是否有权限
if(!checkGroup('other_rightbottom',$h)){
    salert('您没有此操作的权限','index.php');
}

switch( $h ){
	//note 右下角提醒列表
	case 'message':
		active_rightbottom_message();break;
	case 'sitemail':
		active_rightbottom_sitemail();break;

}
?>