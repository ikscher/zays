<?php
/**
*	客服后台的AJAX调用
*	此处主要是ajax中需要调用的方法/函数
*/

/**
* 删除指定表,指定记录
* @param string $table_name - 表名
* @param string $field - 字段名
* @param string $id - 条件id
* @return true or false
*/
function del_record2($table_name,$field,$id){
	if(!empty($table_name) && !empty($id) && !empty($field)){
		$sql = "DELETE FROM {$GLOBALS['dbTablePre']}{$table_name} WHERE {$field}='{$id}'";
		if($GLOBALS['_MooClass']['MooMySQL']->query($sql)){
			return true;
		}
		return false;
	}
}


function congratulate_success_upgrade(){
	$sql = "SELECT uid FROM {$GLOBALS['dbTablePre']}admin_usersession";
	$online_service = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql,0,0,0,true);
	return $online_service;
}