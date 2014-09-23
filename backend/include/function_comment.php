<?php
//与性别对应的固定评语
function comment_list(){
	$gender_value_arr = array(1,2,3,4);//1:男对女，2:女对男，3:男对男，4:女对女
	$type_arr = array(1,2);	//1:视频介绍，2:人生经历
	foreach($type_arr as $type_value){
		foreach($gender_value_arr as $gender_value){
			$cache_file = getCacheFile($gender_value.$type_value);
			$sql = "SELECT id,comment FROM {$GLOBALS['dbTablePre']}manage_comment where type=1 and comment_type='$type_value' and (comment_sort='$gender_value' or comment_sort='0') ORDER BY id DESC";
			$comment_list = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
			foreach($comment_list as $key=>$value){
			   $comment_list_tmp[$value['id']]=$value['comment'];
			} 
			setArrayCache($gender_value.$type_value,$comment_list_tmp);
			$comment_list_tmp=array();
			$comment_list = array();
		}
		//echo "正在生成缓存文件，请稍等....";
	}
	return 'ok';
}


/** 
 * 创建数组缓存
 * int setArrayCache( string $name, array $arr )
 * @param	string $name 缓存名称,尽量长点避免重名,用于读取缓存
 * @param	array  $arr 要缓存的数组
 * @return	integer 0不成功,非0成功
 * @author 	xiaowu
 * */
function setArrayCache( $name, $arr ){
	$cache_dir = getCacheFile();
	$cache_file = getCacheFile($name);

	MooMakeDir($cache_dir);

	$str = "<?php\n//Array Cache File, Do Not Modify Me!\n//Created: ".gmdate("Y-m-d H:i:s")."\n
		if(!defined('IN_MOOPHP')) exit('Access Denied');\n\$data=".var_export($arr,true).";\n?>";
	return file_put_contents( $cache_file, $str );
}


//得到缓存文件名
function getCacheFile($name=""){
	$cache_dir = '../'.MOOPHP_DATA_DIR.'/cache';
	if(empty($name)){
		return $cache_dir;
	}
	$cache_file = $cache_dir.'/array_gender_value_'.$name.'.php';
	return $cache_file;
}

?>