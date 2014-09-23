<?php
$username = $_GET['username'];
if ($username != ''){
	$user = $_MooClass['MooMySQL']->getOne("SELECT * FROM web_members_search WHERE username='$username'");
}
if ($user){
	echo 1;
}else{
	echo 0;
}
exit();
?>