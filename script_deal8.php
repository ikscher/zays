<?php
error_reporting(E_ALL);
$time_start = microtime_float();//开始时间
//写一个8月份以前采集形象照处理有问题的脚本
require 'framwork/MooPHP.php';


$eclapsetime=isset($_GET['eclapsetime'])?$_GET['eclapsetime']:0;
$page=isset($_GET['page'])?$_GET['page']:0;
$limit=10;
$offset=$limit*$page;
$sql="select ms.uid,mb.mainimg from web_members_search ms left join web_members_base mb on ms.uid=mb.uid where ms.usertype=3 and username<>1 and ms.regdate>1421683200 order by ms.uid asc limit 0,10";

$res = $GLOBALS['_MooClass']['MooMySQL']->getAll($sql);

//var_dump($res);

if(empty($res)) {  echo "已花费时间:".$eclapsetime; exit('处理完毕！');}

$uidlist='';
$comma='';
foreach($res as $k=>$v){
	$filename=$v['mainimg'];
	if(!file_exists('/wwwroot/zays/'.$filename)){
		$_r_=array();
	    $sql="select `imgurl` from web_pic where uid={$v['uid']}";
	    $_r_=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
		if(!empty($_r_)) $filename=$_r_['imgurl'];
	}
	
    if(file_exists('/wwwroot/zays/'.$filename)) savePhoto($v['uid'],$filename);
   
    
	$filename = str_replace(".","_nowater.",$filename);
	if(file_exists('/wwwroot/zays/'.$filename)) {
	    @unlink('/wwwroot/zays/'.$filename);
	}
					
   $uidlist.=$comma;
   $uidlist.=$v['uid'];
   $comma=',';
}

//echo $uidlist;exit;

if($uidlist){
	$sql = "update web_members_search set username=1 where uid in ({$uidlist})";
	$GLOBALS['_MooClass']['MooMySQL']->query($sql);
	
	//形象照通过审核
	$GLOBALS['_MooClass']['MooMySQL']->query("update `web_members_search` set images_ischeck=1 where uid in ({$uidlist})");
}

//$page++;

$time_end = microtime_float();//结束时间
$time=$time_end - $time_start;

$eclapsetime +=$time;

echo '已花费时间：'.$eclapsetime;

//echo "<script type='text/javascript'>window.location.href='script_deal8.php?page={$page}&eclapsetime={$eclapsetime}';</script>";
echo "<script type='text/javascript'>window.location.href='script_deal8.php?eclapsetime={$eclapsetime}';</script>";


function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function savePhoto($uid,$photoimage){
    $x1=128;
    $y1=0;
    $x2=494;
    $y2=457;
    $width=366;
    $height=457;
    $photoimage= '/wwwroot/zays/'.$photoimage;
    $first_dir=substr($uid,-1,1);
    $secend_dir=substr($uid,-2,1);
    $third_dir=substr($uid,-3,1);
    $forth_dir=substr($uid,-4,1);
    $userpath="/wwwroot/zays/data/upload/userimg/".$first_dir."/".$secend_dir."/".$third_dir."/".$forth_dir."/";

    
    if(!is_dir($userpath)){
		mkdir($userpath,0777,true);
        /* $array = explode('/', $userpath);
        $count = count($array);
        for($i = 0; $i < $count; $i++) {
			$msg .= $array[$i];
			if(!file_exists($msg)) {
				mkdir($msg, 0777);
			}
			$msg .= '/';
		} */
    }

    $sizearray=array(0=>array('width'=>320,'height'=>400),
                     1=>array('width'=>171,'height'=>212),
                     2=>array('width'=>100,'height'=>125),
                     3=>array('width'=>60,'height'=>75),
                     4=>array('width'=>50,'height'=>63),
                     5=>array('width'=>110,'height'=>138),
                     6=>array('width'=>100,'height'=>125)
                    );
    $namearray=array(0=>'big',1=>'mid',2=>'medium',3=>'small',4=>'page',5=>'com',6=>'index');
    
    $newimages=changesize($photoimage,$userpath,$x1,$y1,$width,$height,$uid,$sizearray,$namearray);//生成另外三种形象照
   
    //$sql = "UPDATE web_members_search SET `images_ischeck`=1  WHERE `uid`='$uid'";
    //$GLOBALS['_MooClass']['MooMySQL']->query($sql);
    //searchApi('members_man members_women') -> updateAttr(array('images_ischeck'),array($uid=>array(1)));
     //更新索引数据的属性值
    //if(MOOPHP_ALLOW_FASTDB) {       
    //    $value['images_ischeck']= 1;
    //    MooFastdbUpdate("members_search",'uid',$uid,$value);
    //}

}

/*
* 图片裁减函数
* @param $image 原图片
* @param $path  裁减后图片路径
* @param $x     裁减左上角X坐标
* @param $y     裁减左上角Y坐标
* @param $width 裁减宽度
* @param $height 裁减高度
* @param $uid   用户id
* @param $sizearray 裁减尺寸数组
* @param $namearray 命名数组
*/
function changesize($image,$path,$x,$y,$width,$height,$uid,$sizearray,$namearray){
	$imageType_arr=array(1=>IMAGETYPE_GIF,2=>IMAGETYPE_JPEG,3=>IMAGETYPE_PNG,4=>IMAGETYPE_SWF,5=>IMAGETYPE_PSD,6=>IMAGETYPE_BMP);
	
	list($imagewidth, $imageheight, $imageType_) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType_arr[$imageType_]);
	/* echo $image.'<br/>';
    echo $imagewidth.'<br/>';
    echo $imageheight.'<br/>';
    echo $imageType_.'<br/>';
    echo $imageType_arr[$imageType_];
	echo $imageType.'<br/>';
	exit(); */ 
	
	$userpath=$uid*3;
	if(!file_exists($path)) {mkdir($path, 0777);}
   
	for($i=0;$i<count($sizearray);$i++){
	    $new_Image=imagecreatetruecolor($sizearray[$i]['width'],$sizearray[$i]['height']);
		if($imageType=="image/gif"){
				 $source=imagecreatefromgif($image); 
				 $thumb_image_name=$path."/".$userpath."_".$namearray[$i].".gif";
		} elseif($imageType=="image/jpeg"){
			     $source=imagecreatefromjpeg($image);
				 $thumb_image_name=$path."/".$userpath."_".$namearray[$i].".jpg";
		}elseif ($imageType== "image/jpg"){
				 $source=imagecreatefromjpeg($image);
				 $thumb_image_name=$path."/".$userpath."_".$namearray[$i].".jpg";
		}elseif($imageType=="image/png"){
			     $source=imagecreatefrompng($image);
				 $thumb_image_name=$path."/".$userpath."_".$namearray[$i].".png";
		}elseif($imageType=="image/x-png"){
				 $source=imagecreatefrompng($image);
				 $thumb_image_name=$path."/".$userpath."_".$namearray[$i].".png";
		}
			     //exit("$image,不支持该图片格式：$imageType ，请禁止通过该图片的审核");
		  /* case "image/x-bmp":
		  case "image/x-ms-bmp":
			  case "image/bmp":
				 $source=ImageCreateFromBMP($image);
				 $thumb_image_name=$path."/".$userpath."_".$namearray[$i].".bmp"; */
		  
		  imagecopyresampled($new_Image,$source,0,0,$x,$y,$sizearray[$i]['width'],$sizearray[$i]['height'],$width,$height);
		  if($imageType=="image/gif"){
				imagegif($new_Image,$thumb_image_name);
		  }elseif($imageType=="image/jpeg"){
			    imagejpeg($new_Image,$thumb_image_name,90);
		  }elseif($imageType== "image/jpg"){
				imagejpeg($new_Image,$thumb_image_name,90);
		  }elseif($imageType=="image/png"){
			    imagepng($new_Image,$thumb_image_name);
		  }elseif($imageType== "image/x-png"){
				imagepng($new_Image,$thumb_image_name);
		  }elseif($imageType== "image/bmp"){
				//imagebmp($new_Image,$thumb_image_name);
				//break;
		  }
			/*$first = new Imagick($thumb_image_name);//写入水印
			$second = new Imagick('../public/system/images/logo_xxz.png');
			$second->setImageOpacity (0.4);//设置透明度
			$dw = new ImagickDraw();
			$dw->setGravity(Imagick::GRAVITY_SOUTHEAST);//设置位置
			$dw->composite($second->getImageCompose(),0,0,50,0,$second);
			$first->drawImage($dw);
			$first->writeImage($thumb_image_name);*/
			//chmod($thumb_image_name, 0777);
			
	}
	//return true;
}


?>