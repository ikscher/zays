<?php
/*
 * 服务于图片远程备份应用
 */	
!defined('IN_MOOPHP') && exit('Access Denied');

class UserImage{
    private   $path_for_logandimage = "/var/www/hzn_upload/";//同步命令所在地址
    private   $path_for_log = "/var/www/hzn_upload/logs/";//日志存放所在地址
    //private   $path_for_image = "/var/www/hzn_upload/data/";
   
	/*
	 * 生成日志
	 * $path:日志生成的路径如 data/upload/userimg/
	 */
	 function makeFile($log_type) {

		$last_suffix = date("Ymd");
		$fp = @fopen($this->path_for_log."del_img_".$last_suffix.".txt", "a");
		@fclose($fp);
		return $filename = $log_type."_img_".$last_suffix.".txt";
	}
	/*
	 * 日志上传记载
	 * $imagename:data/upload/userimg/a.jpg上传的图片地址及名称
	 * $logpath：日志的路径 如：data/upload/userimg/
	 */
     function writeuploadLog($imagename,$logname) {
	    date_default_timezone_set('PRC'); 
	    
		$str_geshi = "[".date('Y-m-d H:i:s')."]".' '.$imagename;
		$fp = @fopen($this->path_for_log.$logname, "a");
		@fwrite($fp,$str_geshi."\n");
		@fclose($fp);
		return true;

	}
	
    /*
     * 同步文件命令
     * $imagepath:图片所在地址如 data/upload/userimg/a.jpg
     * 
     */
	 function usersynImage($imagepath){
		$return_status_boole = exec($this->path_for_logandimage."/bin/"."operating $imagepath up");
		if($return_status_boole){
			return true;
		}
	    return false;
	}
	/*
	 * 下载图片
	 * $url_get：图片所在的远程地址如：http://www.baidu.com/img/baidu_jgylogo3.gif
	 * $filename：图片的名称如 a.jpg
	 * $image_path: 图片下载到的地址 data/upload/story/heh.gif
	 */
	 function usergetImage($url_get,$image_path,$filename=null) {
		if(!$url_get) return false;
		$image_styles_arr = array(".gif",".jpg",".png","jpeg","bmp");
		if(!$filename) {
			$ext=strrchr(strtolower($url_get),".") ;
			foreach ($image_styles_arr as $val){
				if($val==$ext){
					break;
				}else{
					return false ;
				}
			}
			$str=explode('/',$url_get) ;
			$filename=$str[count($str)-1] ;
		}
		ob_start();
		readfile($url_get);
		$img = ob_get_contents();
		ob_end_clean();
		
		$fp2=@fopen("../../".$image_path.$filename, "a") ;
		@fwrite($fp2,$img);
		@fclose($fp2);
		return $filename;
	}

}