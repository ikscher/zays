<?php
include "./module/{$name}/function.php";

function space_comment(){
	global $user_arr;
	$message = trim(MooGetGPC('message','string','P'));
	if(empty($message)){
		echo 'emptymessage';exit;
	}
	$type = MooGetGPC('type','string','P');
	if(in_array($type,array('video','lifetimes'))){
		$type == 'video' ? $c_type=1 : $c_type=2;
	}else{
		echo 'no';exit;
	}
	$gender_value = MooGetGPC('gender_value','integer','P');
	//得到评论包含的配置文件
	$cache_file = getCacheFile($gender_value.$c_type);
	if(!file_exists($cache_file)){
		echo 'no';exit;
	}
	
	//视频
	if($type == 'video'){
		$vid = MooGetGPC('vid','integer','P');
		if(empty($vid)){
			echo 'no';exit;
		}
		$commentid =  MooGetGPC('commentid','integer','P');
		$uid = MooGetGPC('uid','integer','P');
		$param['comment_type'] = 1; //评论类型1为视频介绍
		$param['vid'] = $vid;	//评论的视频
		$param['comment'] = ubbCode($message); //提交的评论
		$param['cid'] = $uid; //被评论人的uid
		$param['uid'] = $user_arr['uid'];//评论人uid
		$param['nickname'] = $user_arr['nickname'];//评论人的昵称
		$param['dateline'] = time();
		inserttable('diamond_comment',$param);
		echo json_encode($param);exit;
	}
	//人生经历
	if($type == 'lifetimes'){
		$commentid =  MooGetGPC('commentid','integer','P');
		$uid = MooGetGPC('uid','integer','P');
		$param['comment_type'] = 2; //评论类型2为人生经历
		$param['comment'] = ubbCode($message); //提交的评论
		$param['cid'] = $uid; //被评论人的uid
		$param['uid'] = $user_arr['uid'];//评论人uid
		$param['nickname'] = $user_arr['nickname'];//评论人的昵称
		$param['dateline'] = time();
		inserttable('diamond_comment',$param);
		echo json_encode($param);exit;
	}
}

//为您推荐的会员
function youAbleLiker(){
    global $user_arr;
	$result=array();
	//月薪
    $salary = array("0"=>"不限","1"=>"1000元以下","2"=>"1001-2000元","3"=>"2001-3000元","4"=>"3001-5000元","5"=>"5001-8000元","6"=>"8001-10000元","7"=>"10001-20000元","8"=>"20001-50000元","9"=>"50000元以上");
    $marriage = array("0"=>"不限","1"=>"未婚","3"=>"离异","4"=>"丧偶");
    $education = array("0"=>"不限","3"=>"高中及以下","4"=>"大专","5"=>"大学本科","6"=>"硕士","7"=>"博士"); 


	$userid=$user_arr['uid'];
    $offset=MooGetGPC('offset','integer','P');
	if(empty($userid)) return array();
    //note 您可能喜欢的人，匹配相同地区
	$result=youAbleLike ($l, $offset );
	
	if($offset>(int)$l){
	    unset($result);
	    $result=youAbleLike ($l, 0 );
	}elseif(($offset+5)>(int)$l){ //5是偏移量，固定取5个会员
	    $result_=youAbleLike ($l, 0 );
		$result=array_slice(array_merge($result,$result_),0,5);
	}
   
	$str='';
	foreach($result as $k=>$able_likes){
	    if ($able_likes['images_ischeck']=='1'&& $able_likes['mainimg']){
			if(MooGetphoto($able_likes['uid'],'index')){
   			    $image=IMG_SITE.MooGetphoto($able_likes['uid'],'index');
			}elseif(MooGetphoto($able_likes['uid'],'medium')){
  			    $image=IMG_SITE.MooGetphoto($able_likes['uid'],'medium');
			}elseif($able_likes['gender'] == '1'){
			    $image='public/system/images/woman_100.gif';
			}else{
			    $image='public/system/images/man_100.gif';
		    }				  
			$image="<img id='show_pic' src='{$image}' onload='javascript:DrawImage(this,100,125)' width='100'/>";
		}elseif($able_likes['mainimg']){
			if($able_likes['gender'] == '1'){
				$image='<img id="show_pic" src="public/system/images/woman_100.gif"  />';
			}else{
				$image='<img id="show_pic" src="public/system/images/man_100.gif" />';
			}
							
		}else{
			if($able_likes['gender'] == '1'){
				$image='<img src="public/system/images/nopic_woman_100.gif" />';
			}else{
				$image='<img src="public/system/images/nopic_man_100.gif" />';
				
			}
		}
		
		$nickname=$able_likes['nickname']?$able_likes['nickname']:'ID:'.$able_likes['uid'];
		$birthyear=$able_likes['birthyear']?(gmdate('Y', time()) - $able_likes['birthyear']).'岁':'年龄保密';
		$m=$able_likes['marriage'] == 0?'婚姻状况:保密':"{$marriage[$able_likes['marriage']]}";
		$e=$able_likes['education'] == 0?'保密':"{$education[$able_likes['education']]}";
		$s=$able_likes['salary'] == 0?'薪资:保密':"{$salary[$able_likes['salary']]}";
		$str.=<<<EOT
			<dl class="left-likes" data-len="{$l}">
				<dt><div class="left-likes-img"><p><a style="display:block;" href="space_{$able_likes['uid']}.html">{$image}</a></p></div></dt>
				<dd class="f-b-d73c90"><a href="space_{$able_likes['uid']}.html"  class="f-ed0a91-a"></a>{$nickname}</dd>
				<dd>{$birthyear}</dd>
				<dd>{$m}</dd>
				<dd>{$e}</dd>
				<dd>{$s}</dd>
			</dl>
	
EOT;
    }
    echo  $str;
}


$h = MooGetGPC('h', 'string');
switch ($h) {
	case 'space_comment':
		space_comment();
		break;
	case 'youAbleLiker':
	    youAbleLiker();
		break;
}
?>