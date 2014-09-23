<?php
$username = MooGetGPC('username', 'string');
if ($username != ''){
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