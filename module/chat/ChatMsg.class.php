<?php

class ChatMsg{
	
	var $conn;
	
	public function __construct($conn=''){
		$this -> conn = $conn;
		$this -> conn ->query('set names utf8');
		//print_r($conn);
	}
	
	/**
	 * 得到该会员的聊天记录
	 */
	public function getAllMsg($uid,$pagenum,$pagesize,$uid2=0,$onlyTotal=false){
		global $dbTablePre;
		if(!$this->conn) return array('errmsg' => "Fatal Error : database error!");
		
		if(!$uid2) $cond = "where (toid=$uid or fromid=$uid)";
		else{
			$cond = "where toid in ($uid,$uid2) and fromid in ($uid,$uid2)";
		}
		//total
		$sql_total = "select count(*) as c from ".$dbTablePre."chat_message ".$cond;
		//$rs_total = @mysql_query($sql_total);
		$rs_total = $this -> conn -> getOne($sql_total);
		if($rs_total){
			//$rs_total = mysql_fetch_array($rs_total,MYSQL_ASSOC);
			$total = (int)$rs_total['c'];
		}else $total = 0;
		
		if($onlyTotal) return $total;
	
		$return = array();
		
		if($total){
			//计算页数有没有超过正常范围
			$max_total = ceil($total/$pagesize);
			if($pagenum > $max_total) return array('errmsg' => "Fatal Error : Illegal parameter!");
			$offset = ($pagenum-1)*$pagesize;
			
			$return['total'] = $total;
			$return['pagenum'] = $pagenum;
			//data
			$sql = "select * from ".$dbTablePre."chat_message $cond order by time desc limit $offset,$pagesize";
			//$rs = @mysql_query($sql);
			$rs = $this -> conn -> getAll($sql);
			$data = array();
			if($rs){
//				while($row = mysql_fetch_array($rs,MYSQL_ASSOC)){
//					$data[] = $row;
//				}
				$data = $rs;
				$return['data'] = $data;
			}
		}
		return $return;
	}
	
	/**
	 * 删除表里面的数据
	 * @param $id  聊天记录的流水ID
	 */
	public function delData($id){
		global $dbTablePre;
		if(!$this->conn) return array('errmsg' => "Fatal Error : database error!");
		
		$sql = "delete from ".$dbTablePre."chat_message where id=$id";
		return $this->conn->getOne($sql);//@mysql_query($sql);
	}
	
    public function UserNewMsg($uid){
    	global $dbTablePre;
    	if(!$this->conn) return array('errmsg' => "Fatal Error : database error!");
    	
    	$sql = "SELECT toid,fromid FROM `".$dbTablePre."chat_message` where toid=$uid and `status`=0";
    	$rs = $this -> conn -> getAll($sql);
		if($rs){
			$data = $rs;
		}else $data = array();
		
		return $data;
    }
}