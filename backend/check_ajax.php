<?php

//note 加载框架配置参数
require './include/config.php';
//note 加载MooPHP框架
require '../framwork/MooPHP.php';
//note 包含共公方法
include './include/function.php';
include './include/allmember_function.php';



function resize_and_crop(){
	error_log(print_r($_POST,true));
	$uid = MooGetGPC('uid','integer');
	list($width, $height) = getimagesize($_POST["imageSource"]);
	$pWidth = $_POST["imageW"];
	$pHeight =  $_POST["imageH"];
	$ext = end(explode(".",$_POST["imageSource"]));
	$function = returnCorrectFunction($ext);  
	$image = $function($_POST["imageSource"]);
	$width = imagesx($image);    
	$height = imagesy($image);
	// Resample
	$image_p = imagecreatetruecolor($pWidth, $pHeight);
	setTransparency($image,$image_p,$ext);
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $pWidth, $pHeight, $width, $height);
	imagedestroy($image); 
	$widthR = imagesx($image_p);
	$hegihtR = imagesy($image_p);
	
	if($_POST["imageRotate"]){
	    $angle = 360 - $_POST["imageRotate"];
	    $image_p = imagerotate($image_p,$angle,0);
	    $pWidth = imagesx($image_p);
	    $pHeight = imagesy($image_p);
	}
	if($pWidth > $_POST["viewPortW"]){
	    $src_x = abs(abs($_POST["imageX"]) - abs(($_POST["imageW"] - $pWidth) / 2));
	    $dst_x = 0;
	}else{
	    $src_x = 0;
	    $dst_x = $_POST["imageX"] + (($_POST["imageW"] - $pWidth) / 2); 
	}
	if($pHeight > $_POST["viewPortH"]){
	    $src_y = abs($_POST["imageY"] - abs(($_POST["imageH"] - $pHeight) / 2));
	    $dst_y = 0;
	}else{
	    $src_y = 0;
	    $dst_y = $_POST["imageY"] + (($_POST["imageH"] - $pHeight) / 2); 
	}
	$viewport = imagecreatetruecolor($_POST["viewPortW"],$_POST["viewPortH"]);
	setTransparency($image_p,$viewport,$ext); 
	imagecopy($viewport, $image_p, $dst_x, $dst_y, $src_x, $src_y, $pWidth, $pHeight);
	imagedestroy($image_p);
	
	
	$selector = imagecreatetruecolor($_POST["selectorW"],$_POST["selectorH"]);
	setTransparency($viewport,$selector,$ext);
	imagecopy($selector, $viewport, 0, 0, $_POST["selectorX"], $_POST["selectorY"],$_POST["viewPortW"],$_POST["viewPortH"]);
	
	$file = "tmp/test".time().".".$ext;
	parseImage($ext,$selector,$file);
	imagedestroy($viewport);
	//Return value
	echo $file;
	
	//写水印......fanglin
	$thumb_image_name = $file;
	$first = new Imagick($thumb_image_name);//写入水印
	$second = new Imagick('../public/system/images/logo2.png');

	$dw = new ImagickDraw();
	$dw->setGravity(Imagick::GRAVITY_SOUTHEAST);//设置位置
	
	$dw->composite($second->getImageCompose(),0,0,50,0,$second);
	$first->drawImage($dw);
	$first->writeImage($thumb_image_name);
	chmod($thumb_image_name, 0777);
		            
	//imagick处理.......fanglin
	$bigphoto = $thumb_image_name;
	$thuid=$uid*3;
	$userpath="../data/upload/userimg/";
	$jpg=$ext; 
	
	$sizearray=array(0=>array('width'=>320,'height'=>400),
					 1=>array('width'=>171,'height'=>212),
					 2=>array('width'=>100,'height'=>125),
					 3=>array('width'=>60,'height'=>75));
	$namearray=array(0=>'big',1=>'mid',2=>'medium',3=>'small');
	foreach($sizearray as $k=>$size){
		$index_name=$thuid.'_'.$namearray[$k].'.'.$jpg;	
		ImagickResizeImage($bigphoto,$userpath.$index_name,$size['width'],$size['height']);
	}
	
	$index_name=$thuid.'_'.'index.'.$jpg;	
	$com_name  =$thuid.'_'.'com.'.$jpg;
	$page_name =$thuid.'_'.'page.'.$jpg;
	list($width,$height)=getimagesize($bigphoto);
	$d=$width/$height;  
	$c=100/125;//100*125
	if($d>$c){
		$g1_width=100;
		$b=$width/$g1_width;
		$g1_height=$height/$b;
	}
	else{
		$g1_height=125;
		$b=$height/$g1_height;
		$g1_width=$width/$b;
	}
	ImagickResizeImage($bigphoto,$userpath.$index_name,$g1_width,$g1_height);
	
	$c=50/63;//100*125
	if($d>$c){
		$g2_width=50;
		$b=$width/$g2_width;
		$g2_height=$height/$b;
	}else{
		$g2_height=63;
		$b=$height/$g2_height;
		$g2_width=$width/$b;
	}
	ImagickResizeImage($bigphoto,$userpath.$page_name,$g2_width,$g2_height);
	$c=110/138;//100*125
	if($d>$c){
		$g3_width=110;
		$b=$width/$g3_width;
		$g3_height=$height/$b;
	}
	else{
		$g3_height=138;
		$b=$height/$g3_height;
		$g3_width=$width/$b;
	}
	ImagickResizeImage($bigphoto,$userpath.$com_name,$g3_width,$g3_height);	
}

/* Functions */
	
	function returnCorrectFunction($ext){
	    $function = "";
	    switch($ext){
	        case "png":
	            $function = "imagecreatefrompng"; 
	            break;
	        case "jpeg":
	            $function = "imagecreatefromjpeg"; 
	            break;
	        case "jpg":
	            $function = "imagecreatefromjpeg";  
	            break;
	        case "gif":
	            $function = "imagecreatefromgif"; 
	            break;
	    }
	    return $function;
	}
	
	function parseImage($ext,$img,$file = null){
	    switch($ext){
	        case "png":
	            imagepng($img,($file != null ? $file : '')); 
	            break;
	        case "jpeg":
	            imagejpeg($img,($file ? $file : ''),90); 
	            break;
	        case "jpg":
	            imagejpeg($img,($file ? $file : ''),90);
	            break;
	        case "gif":
	            imagegif($img,($file ? $file : ''));
	            break;
	    }
	}
	
	function setTransparency($imgSrc,$imgDest,$ext){
	   
	        if($ext == "png" || $ext == "gif"){
	            $trnprt_indx = imagecolortransparent($imgSrc);
	            // If we have a specific transparent color
	            if ($trnprt_indx >= 0) {
	                // Get the original image's transparent color's RGB values
	                $trnprt_color    = imagecolorsforindex($imgSrc, $trnprt_indx);
	                // Allocate the same color in the new image resource
	                $trnprt_indx    = imagecolorallocate($imgDest, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
	                // Completely fill the background of the new image with allocated color.
	                imagefill($imgDest, 0, 0, $trnprt_indx);
	                // Set the background color for new image to transparent
	                imagecolortransparent($imgDest, $trnprt_indx);
	            } 
	            // Always make a transparent background color for PNGs that don't have one allocated already
	            elseif ($ext == "png") {
	               // Turn off transparency blending (temporarily)
	               imagealphablending($imgDest, true);
	               // Create a new transparent color for image
	               $color = imagecolorallocatealpha($imgDest, 0, 0, 0, 127);
	               // Completely fill the background of the new image with allocated color.
	               imagefill($imgDest, 0, 0, $color);
	               // Restore transparency blending
	               imagesavealpha($imgDest, true);
	            }
	            
	        }
	}

//形象照旋转
function imagick_rotate(){
    global $_MooClass;
    $pic_path = MooGetGPC('pic_path','string','G');

    //$id = MooGetGPC('id','integer','G');
    //$uid = MooGetGPC('uid','integer','G');
    $degrees = 90;
    $file = explode('.',$pic_path);
    
    $count = count($file);
    if($count == 2) {
        $new_file = '../'.$file[0].'.1.'.$file[1];
    } elseif ($count == 3) {
        $new_file = '../'.$file[0].'.'.($file[1] + 1) % 4 .'.'.$file[2];
    }
    $pic_path = '../'.$pic_path;
    
    $source = imagecreatefromjpeg($pic_path);
    $rotate = imagerotate($source, $degrees, 0);
//  var_dump($pic_path);
    
    list($imagewidth, $imageheight, $imageType) = getimagesize($pic_path);
    $imageType = image_type_to_mime_type($imageType);

    switch($imageType) {
        case "image/gif":
            imagegif($rotate,$new_file,100);
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            imagejpeg($rotate,$new_file,100);
            break;
        case "image/png":
        case "image/x-png":
            imagepng($rotate,$new_file,100);
            break;
        case "image/bmp":
            imagewbmp($rotate,$new_file,100);
            break;
    }
    if($count == 3) {
        $files = MooAutoLoad("MooFiles");
        $files->fileDelete('../'.$file[0].'.'.$file[1] % 4 .'.'.$file[2]);
        echo $file[1];
    } else {
        echo 0;
    }
}

/***************************************控制层(V)***********************************/
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // 过去的时间

//判断是否登录
adminUserInfo();
if(empty($GLOBALS['adminid'])){
	echo 'nologin';exit;
}

$n = MooGetGPC('n','string');

switch($n){
	//note 检查会员ID
	case 'resize_and_crop':
		resize_and_crop();	
	break;
	case 'imagick_rotate':
		imagick_rotate();
	break;
}
?>