<?PHP

function ArrToStr($arr) {
	$string = implode(",",$arr);
	return $string;
}
/**
 * 限制图片的最大$wdith,$height不大于$loc
 * return 等比例宽高 array()
 */
function off_WH($width, $height, $loc = 500){
	$re = array();
	$rat = $width / $height;
	if($rat >= 1 && $width > $loc){
		$height = $height * $loc / $width;
		$width = $loc;
	}
	if($rat < 1 && $height > $loc){
		$width = $width * $loc / $height;
		$height = $loc;
	}
	$re['width'] = (int)$width;
	$re['height'] = (int)$height;
	return $re;
}

/**
 * 在指定宽高内等比例缩略原图
 * get_thumb_HW(原宽,原高,缩略宽,缩略高)
 * return array()
 */
function get_thumb_HW($width, $height, $w, $h)
{
	if($width * $h > $height * $w)
	{
		$h = $height * $w / $width;
	}else{
		$w = $width * $h / $height;
	}
	return array('width' => (int)$w, 'height' => (int)$h);
}

/**
 * 获取指定路径下文件名称
 *
 * @param string;
 * return array;
 */
function getFileList($path)
{
	if(!is_dir($path)) return;
	$path = rtrim($path,'/').'/';
	$arr_file = array();

	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {
			if($file != "." && $file != ".."){
				is_file($path.$file) ? 
					$arr_file[]= $file : 
					$arr_file[$file] = getFileList($path.$file) ;
			}
		}
		closedir($handle);
	}
	asort($arr_file);
	return $arr_file;
}
/**
 * 得到编辑图片素材
 *
 * @param string;
 * return array;
 */
function get_imgedit_cache($cache_file, $path)
{
	if(!file_exists($cache_file)){
		$data = getFileList($path);
		file_put_contents( $cache_file, "<?php\n\$data=".var_export($data,true).";\n?>");
		//echo 'creat<br>';
		return $data;
	}
	require $cache_file;
	return $data;
}
/**
 * 删除形象照缩略图
 *
 */
function unlink_mainimg($userid){
	//note 形象照缩略图删除(big,small,medium,mid)
	$thumb_big = MooGetphoto($userid,'big');
	file_exists($thumb_big) && unlink($thumb_big);
	$thumb_small = MooGetphoto($userid,'small');
	file_exists($thumb_small) && unlink($thumb_small);
	$thumb_medium = MooGetphoto($userid,'medium');
	file_exists($thumb_medium) && unlink($thumb_medium);
	$thumb_mid = MooGetphoto($userid,'mid');
	file_exists($thumb_mid) && unlink($thumb_mid);
	$thumb_page = MooGetphoto($userid,'page');
	file_exists($thumb_page) && unlink($thumb_page);
	$thumb_index = MooGetphoto($userid,'index');
	file_exists($thumb_index) && unlink($thumb_index);
	$thumb_com = MooGetphoto($userid,'com');
	file_exists($thumb_com) && unlink($thumb_com);
}

/**
 * 客服模拟登录 没有权限修改删除 会员资料
 */
function checkAuthMod($url){
   	global $dbTablePre,$_MooClass;
	$serverid=Moo_is_kefu();
	$sql="select groupid from {$dbTablePre}admin_user where uid='{$serverid}'";
	$result=$_MooClass['MooMySQL']->getOne($sql);
	
	if(empty($result)) return;
	if(!in_array($result['groupid'],array(60,61,76,75,82,84,67,70)) && !empty($serverid)){
	   MooMessage ( "sorry，您没有操作权限", $url );
	   return ;
	}
	
}
?>