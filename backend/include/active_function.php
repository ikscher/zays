<?php
/**
 * 全互动管理用到的方法
 */


/**
 * 登陆系统后客服显示自己范围内的用 主管显示全部
 * @param string $where 查询条件 组合语句
 * @param string $keyword 查询条件 
 */
function active_leer_loginlist($where,$keyword){
	$member_arr = array();

	//note 所管理的客服id列表
    $myservice_idlist = get_myservice_idlist();
     
     //note 所管理的用户列表
 	 if(empty($myservice_idlist)){
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search WHERE sid IN ({$GLOBALS['adminid']})";
	 }elseif($myservice_idlist == 'all'){
	 	//all为客服主管能查看所有的
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search";
	 }else{
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search WHERE sid IN ({$myservice_idlist})";
	 }
	 $members = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
   	 foreach($members as $v) {
    	$member_arr[] =  $v['uid'];	
     }
     $member_list = implode(",",$member_arr);

     //note 组装sql语句
     if(empty($member_list)){
     	$where .= " AND (receiveuid IN (null) OR senduid IN (null))";
     }else {
     	$where .= " AND (receiveuid IN ({$member_list}) OR senduid IN ({$member_list}))";
     }
      
     //note 返回客服查看用户范围
     $return_arr = array();
     $return_arr['where'] = $where;
     $return_arr['members'] = $member_arr;
       
     return $return_arr;
}    

/**
 * 登陆系统后客服显示自己范围内的用 主管显示全部
 * @param string $where 查询条件 组合语句
 * @param string $keyword 查询条件 
 */
function active_liker_loginlist($where,$keyword){
	$member_arr = array();
	//note 所管理的客服id列表
    $myservice_idlist = get_myservice_idlist();
     
     //note 所管理的用户列表
 	 if(empty($myservice_idlist)){
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search WHERE sid IN ({$GLOBALS['adminid']})";
	 }elseif($myservice_idlist == 'all'){
	 	//all为客服主管能查看所有的
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search";
	 }else{
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search WHERE sid IN ({$myservice_idlist})";
	 }
	 $members = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
   	 foreach($members as $v) {
    	$member_arr[] =  $v['uid'];	
     }
     $member_list = implode(",",$member_arr);

     //note 组装sql语句
     if(empty($member_list)){
     	$where .= " AND (uid IN (null) OR friendid IN (null))";
     }else {
     	$where .= " AND (uid IN ({$member_list}) OR friendid IN ({$member_list}))";
     }
	
     //note 返回客服查看用户范围
     $return_arr = array();
     $return_arr['where'] = $where;
     $return_arr['members'] = $member_arr;
       
     return $return_arr;
}


/**
 * 登陆系统后客服显示自己范围内的用 主管显示全部
 * @param string $where 查询条件 组合语句
 * @param string $keyword 查询条件 
 */
function active_rose_loginlist($where,$keyword){
	$member_arr = array();
    //note 所管理的客服id列表
    $myservice_idlist = get_myservice_idlist();
     
     //note 所管理的用户列表
 	 if(empty($myservice_idlist)){
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search WHERE sid IN ({$GLOBALS['adminid']})";
	 }elseif($myservice_idlist == 'all'){
	 	//all为客服主管能查看所有的
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search LIMIT 100";
	 }else{
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search WHERE sid IN ({$myservice_idlist})";
	 }
echo $sql;
	 $members = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	
   	 foreach($members as $v) {
    	$member_arr[] =  $v['uid'];	
     }
     $member_list = implode(",",$member_arr);
	
     //note 组装sql语句
     if(empty($member_list)){
     	$where .= " AND (receiveuid IN (null) OR senduid IN (null))";
     }else {
     	$where .= " AND (receiveuid IN ({$member_list}) OR senduid IN ({$member_list}))";
     }
      
     //note 返回客服查看用户范围
     $return_arr = array();
     $return_arr['where'] = $where;
     $return_arr['members'] = $member_arr;
       
     return $return_arr;
}    


/**
 * 登陆系统后客服显示自己范围内的用 主管显示全部
 * @param string $where 查询条件 组合语句
 * @param string $keyword 查询条件 
 */
function active_websms_loginlist($where,$keyword){
	$member_arr = array();
    //note 所管理的客服id列表
    $myservice_idlist = get_myservice_idlist();
     
     //note 所管理的用户列表
 	 if(empty($myservice_idlist)){
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search WHERE sid IN ({$GLOBALS['adminid']})";
	 }elseif($myservice_idlist == 'all'){
	 	//all为客服主管能查看所有的
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search";
	 }else{
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search WHERE sid IN ({$myservice_idlist})";
	 }
	 $members = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
   	 foreach($members as $v) {
    	$member_arr[] =  $v['uid'];	
     }
     $member_list = implode(",",$member_arr);

     //note 组装sql语句
     if(empty($member_list)){
     	$where .= " AND (s_fromid IN (null) OR s_uid IN (null))";
     }else {
     	$where .= " AND (s_fromid IN ({$member_list}) OR s_uid IN ({$member_list}))";
     }
	
     //note 返回客服查看用户范围
     $return_arr = array();
     $return_arr['where'] = $where;
     $return_arr['members'] = $member_arr;
       
     return $return_arr;
}


/**
 * 登陆系统后客服显示自己范围内的用 主管显示全部
 * @param string $where 查询条件 组合语句
 * @param string $keyword 查询条件 
 */
function active_chat_loginlist($where,$keyword){
	$member_arr = array();
    //note 所管理的客服id列表
    $myservice_idlist = get_myservice_idlist();
     
     //note 所管理的用户列表
 	 if(empty($myservice_idlist)){
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search WHERE sid IN ({$GLOBALS['adminid']})";
	 }elseif($myservice_idlist == 'all'){
	 	//all为客服主管能查看所有的
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search";
	 }else{
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search WHERE sid IN ({$myservice_idlist})";
	 }
	 $members = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
   	 foreach($members as $v) {
    	$member_arr[] =  $v['uid'];	
     }
     $member_list = implode(",",$member_arr);
	
 	 //note 组装sql语句
     if(empty($member_list)){
     	$where .= " AND (s_fromid IN (null) OR s_uid IN (null))";
     }else {
     	$where .= " AND (s_fromid IN ({$member_list}) OR s_uid IN ({$member_list}))";
     }
      
     //note 返回客服查看用户范围
     $return_arr = array();
     $return_arr['where'] = $where;
     $return_arr['members'] = $member_arr;
       
     return $return_arr;
}    


/**
 * 登陆系统后组长，客服显示自己范围内的用户， 主管显示全部
 * @param string $where 查询条件 组合语句
 * @param string $keyword 查询条件 
 */
function active_commission_loginlist($where,$keyword){
	$member_arr = array();
	//note 所管理的客服id列表
    $myservice_idlist = get_myservice_idlist();
     
     //note 所管理的用户列表
 	 if(empty($myservice_idlist)){
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search WHERE sid IN ({$GLOBALS['adminid']})";
	 }elseif($myservice_idlist == 'all'){
	 	//all为客服主管能查看所有的
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search limit 100";
	 }else{
	 	 $sql = "select uid FROM {$GLOBALS['dbTablePre']}members_search WHERE sid IN ({$myservice_idlist})";
	 }
	 $members = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
   	 foreach($members as $v) {
    	$member_arr[] =  $v['uid'];	
     }
     $member_list = implode(",",$member_arr);
     
     //note 组装sql语句
     if(empty($member_list)){
     	$where .= " AND other_contact_you IN (null)";
     }else {
     	$where .= " AND other_contact_you IN ({$member_list})";
     }

     //note 返回客服查看用户范围
     $return_arr = array();
     $return_arr['where'] = $where;
     $return_arr['members'] = $member_arr;
       
     return $return_arr;
}    

?>