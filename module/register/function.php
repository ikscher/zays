<?PHP
function user_login_isRight($click_login, $loginname, $loginpwd) {

	if ($click_login == 1) {
		$_SESSION['session_login'] = 0;
		$_SESSION['session_salesid'] = 0;
		$_SESSION['session_salesname'] ='';
		$_SESSION['session_accountno'] ='';
		$_SESSION['session_loginname'] ='';
		$_SESSION['session_salesarea'] ='';
		//����״̬��Ϣ		
		//û����д����		
		if (empty ($loginname) or empty ($loginpwd)) {
			return -3;
		}
		
		if( ($loginname!="1")||($loginpwd!="1")  ){
		return -2;
		}
		/*$sql = "select loginPwd,isStop,isDeleted from AuthSales  where loginName='".$loginname."'";
		$result = site_get_array($sql);
		
		//û�������Ա
		if ($result == 0) {
			return -2;
		}
		//���벻��
		if ($result[0]['loginPwd'] != md5($loginpwd)) {
			return -1;
		}
		//������
		if($result[0]['isStop']==1){
			return -6;
		}
		//��ɾ��
		if($result[0]['isDeleted']==1){
			return -7;
		}*/
		return 1;

	}
}



function user_login_readSession($loginname_input) {
	
	/*if ($loginname_input != '')
		$loginname = $loginname_input;
	$sql = "select * from AuthSales where loginName='".$loginname."' limit 1";
	$result = site_get_array($sql);
	if ($result != 0) { //����session
		$_SESSION['session_login'] = 1;
		$_SESSION['session_salesid'] = $result[0]['salesId'];
		$_SESSION['session_salesname'] = $result[0]['salesName'];
		$_SESSION['session_accountno'] = $result[0]['accountNo'];
		$_SESSION['session_loginname'] = $result[0]['loginName'];
		$_SESSION['session_salesarea'] = $result[0]['salesArea'];
		return 1;
	} else {
		return -4;
	}*/
	$_SESSION['session_login'] = 1;
	$_SESSION['session_loginname'] =$loginname_input;
	return 1;
}
?>