<?php
/**
 * 每天0:05执行
 * 初始化红娘币相关数据
 */
set_time_limit(0);
$name=null;
require '../config.php';
require '../framwork/MooPHP.php';
$hauth=MooGetGPC('hauth','string','G');
$authkey=MooGetGPC('authkey','string','G');
$mk=MooGetGPC('mk','integer','G');
if(empty($authkey) || $authkey!=MOOPHP_AUTHKEY){
    exit('you must transfer the value of authkey ');
}
$document_root=substr(MOOPHP_ROOT,0,-9);
$matchmaker_auth_file=$document_root.'data'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'matchmaker_auth.php';
if(!empty($mk)){
   //$hash_txt=md5(MooAuthCode(time() . "|" . MOOPHP_AUTHKEY, "ENCODE"));
   //is_file($matchmaker_auth_file)?unlink($matchmaker_auth_file):'';
   //error_log("<?php\n\$hash_txt='".$hash_txt."';",3,$matchmaker_auth_file);
   $db->query('UPDATE `'.$dbTablePre.'admin_user` SET `interval_t`=0');
   exit('not find');
}
if(is_file($matchmaker_auth_file)){
    require $matchmaker_auth_file;
}else{
    exit('not find the "matchmaker_auth.php"');
}
if($hauth!=$hash_txt && empty($hauth)){
    exit('you must transfer the value of hauth ');
}
$lock=$document_root.'data'.DIRECTORY_SEPARATOR.'change_matchmaker.lock';
//$lock_time=is_file($lock)?filemtime($lock):$timestamp;
$kefu_cache=array();
$kefu_count=$db->getOne('SELECT COUNT(`uid`) as count FROM `'.$dbTablePre.'admin_user`');
$kefu_count=empty($kefu_count)?0:$kefu_count['count'];
$limit=30;
$pageall=intval($kefu_count/$limit);
$pageall=($kefu_count%$limit)?($pageall+1):$pageall;
for($i=0;$i<$pageall;$i++){
	$sql='SELECT `uid`,`interval`,`money`,`interval_t` FROM `'.$dbTablePre.'admin_user` ORDER BY `uid` ASC LIMIT '.($i*$limit).','.$limit;
	$kefu_data=$db->getAll($sql);
	$data=array();
	foreach($kefu_data as $value){
		$fastdb->delete('admin_allot_'.$value['uid']);//删除红娘币计数器
		$interval_t=empty($value['interval_t'])?$timestamp:$value['interval_t'];
		$nexttime=strtotime('+'.$value['interval'].' month',strtotime(date('Y-m',$interval_t)));
		if(($nexttime<=$timestamp || empty($value['interval_t'])) && $value['interval']>0 && $value['money']>-1){
				$data[]='(\''.$value['uid'].'\',\''.$value['money'].'\',\''.$nexttime.'\') ';
		}
	}
	if(!empty($data)){
		$sql='INSERT INTO `'.$dbTablePre.'admin_user` (`uid`,`balance`,`interval_t`) VALUES '. implode ( ',',$data ) .' on duplicate key update `balance`=values(`balance`),`interval_t`=values(`interval_t`)';
        echo $sql.'<br/>';
		unset($data);
		$db->query($sql);
	}
}
touch($lock);