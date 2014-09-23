<?php
/**
 * 系统管理或右下角提醒的方法添加到此处
 */

//得到组或队管理的成员
function get_manage_member($id){
	$sql = "SELECT manage_list FROM {$GLOBALS['dbTablePre']}admin_manage WHERE id='{$id}'";
	$result = $GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
	return $result['manage_list'];
}

/**
 * 得到组列表
 * @param string
 * @return array
 */
function get_group($groupid_list){
	$sql = "SELECT groupid,groupname FROM {$GLOBALS['dbTablePre']}admin_group WHERE groupid IN({$groupid_list})";
	$result = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
	return $result;
}



?>