<?php
$username = MooGetGPC('username', 'string');
$username_arr=array('ikscher@163.com');
if (!empty($username) && !in_array($username,$username_arr)){
	$query = $_MooClass['MooMySQL']->getOne("select count(1) as num FROM {$dbTablePre}members_search WHERE username='$username'",true);
	$nR = $query['num'];
}
if ($nR) {
	echo 1;
}else{
	echo 0;
}
exit();
?>