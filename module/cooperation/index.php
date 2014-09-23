<?php
$cooperation_config=array('0'=>'志愿者服务','1'=>'合作举办活动','2'=>'活动赞助','3'=>'员工联谊','4'=>'策划企业活动','5'=>'其他','6'=>'主持人');
$activities = array('0'=>'爱的宣言','1'=>'只因在人群中多看您一眼','2'=>'太原倾城热恋','3'=>'成都线下相亲享有派对','4'=>'香水魔咒','6'=>'爱的足迹','7'=>'北方有佳人','8'=>'原来，幸福离我们那么近','9'=>'瞬间情意，一生记忆','10'=>'有没有那么一首歌会让您想起我','11'=>'缘分，只有今生没有来世','12'=>'弱水三千，我只取一瓢','13'=>'牵手的那一刻，爱已永恒');
function personal(){
       global $user_arr,$userid,$memcached,$activities;	
       $active = isset($_REQUEST['a']) ? $activities[$_REQUEST['a']] : '';
       $cooperation=0;
       $disabled=empty($userid)?'disabled="disabled"':'';
      	$seccode = md5(uniqid(rand(), true));
		MooSetCookie('seccode',$seccode,3600,'');
		$session_seccode = $memcached->set($seccode , '' , 0 , 300);
		
       require MooTemplate('public/cooperation', 'module');
}
function company(){
    global $cooperation_config,$user_arr,$userid,$memcached;
    $cooperation=MooGetGPC('cooptype','integer','G');
    $cooperation=empty($cooperation)?5:$cooperation;
    $cooperation=array_key_exists($cooperation,$cooperation_config)?$cooperation:5;
    $disabled='';
    $user_arr=array();
    $seccode = md5(uniqid(rand(), true));
	MooSetCookie('seccode',$seccode,3600,'');
	$session_seccode = $memcached->set($seccode , '' , 0 , 300);
    require MooTemplate('public/cooperation', 'module');
}
function register(){
    global $memcached,$user_arr,$userid,$_MooClass,$dbTablePre;
    $cooperation=MooGetGPC('cooperation','integer','P');
    $request_url=empty($cooperation)?'index.php?n=cooperation&h=personal':'index.php?n=cooperation&h=company';
    $seccode=strtolower(MooGetGPC('seccode','string','P'));
    $seccode2 = MooGetGPC('seccode','string','C');
    $session_seccode = $memcached->get($seccode2);
	if($seccode != $session_seccode){
	    MooMessage("验证码填写不正确，请确认。",$request_url,'','1',1);
    }
    $data=array();
    $data['activities']=MooGetGPC('active','string','P');
    $data['sid']=MooGetGPC('sid','string','P');
   
    $data['contact']=MooGetGPC('contact','string','P');
    $data['cooperation']=$cooperation;
    $data['phone']=MooGetGPC('phone','string','P');
    $data['province']=empty($cooperation)?$user_arr['province']:MooGetGPC('province','integer','P');
    $data['city']=empty($cooperation)?$user_arr['city']:MooGetGPC('city','integer','P');
    $data['mail']=MooGetGPC('mail','string','P');
    $data['msn']=MooGetGPC('msn','string','P');
    $data['qq']=MooGetGPC('qq','string','P');
    $data['message']=MooGetGPC('message','string','P');
    if(empty($data['contact'])){
        MooMessage("请填写用联系人名称",$request_url,'','1',1);
    }
    if(empty($data['phone']) || !preg_match('/^1[3|4|5|8][0-9]\d{4,8}$/',$data['phone'])){
        MooMessage("手机号码格式不正确",$request_url,'','1',1);
    }
    $province_array=array('10101201','10102000','10103000','10101002','10104000','10105000','10132000','10133000','10134000');
    if(!in_array($data['province'],$province_array) && ($data['city']==0 || empty($data['city']))){
        MooMessage("请选择所在地区",$request_url,'','1',1);
    }
    if(empty($data['phone']) || !preg_match('/^1[3|4|5|8][0-9]\d{4,8}$/',$data['phone'])){
        MooMessage("手机号码格式不正确",$request_url,'','1',1);
    }
    if(!empty($data['mail'])){
        if(!preg_match('/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/',$data['mail'])){
            MooMessage("mail地址不正确",$request_url,'','1',1);
        }
    }
    if(!empty($data['msn'])){
        if(!preg_match('/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/',$data['msn'])){
            MooMessage("msn账号格式不正确",$request_url,'','1',1);
        }
    }
    if(!empty($data['qq'])){
        if(!preg_match('/[1-9][0-9]{4,}/',$data['qq'])){
            MooMessage("QQ账号格式不正确",$request_url,'','1',1);
        }
    }
    $data['uid']=empty($userid)?0:$userid;
    $data['time']=time();
    if($_MooClass['MooMySQL']->getOne('select id from '.$dbTablePre.'cooperation where activities = "'.$data['activities'].'" and cooperation='.$data['cooperation'].' and phone='.$data['phone'],true)){
         MooMessage("报名成功", "index.php");
    }else{
        if(inserttable('cooperation',$data, 1)){
                MooMessage("报名成功", "index.php");
        }else{
             MooMessage("数据出错",$request_url,'','1',1);
        }
    }
    
}
//注册验证码
function code(){
	//验证码
	$img = MooAutoLoad('MooSeccode');
	$img -> outCodeImage(125,30,4);
}
$h=MooGetGPC('h','string','G');
switch($h){
    case 'personal':
        personal();
    break;
    case 'company':
        company();
    break;
    case 'register':
        register();
    break;
    case 'seccode':
        code();
    break;
    default:
        personal();
    break;

}