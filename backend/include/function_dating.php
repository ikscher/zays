<?php
//当前约会状态
function dating_status($dating){
	$expire_time = $dating['expire_time'];
	$flag = $dating['flag'];
	if($flag == 1 && $expire_time>=date("Y-m-d")){
		return '<span style="color:red">审核中</span>';
	}elseif($flag == 2 && $expire_time>=date("Y-m-d")){
		return '<span style="color:blue">约会进行中...</span>';
	}elseif($flag == 3 && $expire_time>=date("Y-m-d")){
		return '审核不通过';
	}else{
		return '约会失败';
	}
}

/*
 * 响应约会状态
 * $type 1:发起者是否同意,2:是否审核
 * */
function dating_respond_status($dating,$type){
	if($type == 1){
		$agree = $dating['agree'];
		switch($agree){
			case 1:
				return '<span style="color:red">等待回复</span>';
				break;
			case 2:
				return '同意';
				break;
			default:
				return '不同意';
				break;
		}
	}else{
		$flag = $dating['flag'];
		switch($flag){
			case 1:
				return '<span style="color:red">审核中</span>';
				break;
			case 2:
				return '审核通过';
				break;
			default:
				return '审核不通过';
				break;
		}
	}
}



?>