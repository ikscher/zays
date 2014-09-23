<?php
include "module/andriod/function.php";

function shadow_change1(){
	global $_MooClass,$dbTablePre,$userid,$user_arr,$memcached;
	
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$checkuuid = check_uuid($and_uuid,$userid);
	if(!$checkuuid){
	    $error = "uuid_error";
	    echo return_data($error,false);exit;	
	}
	$user_arr = MooMembersData($userid);
	
	$members_search=array();
	
	$members_search['updatetime'] = time();
	$members_search['nickname'] = safeFilter(MoogetGPC('nickname','string','P'));
	//$members_search['telphone'] = MoogetGPC('telphone','string','P');
	$members_search['marriage'] = MoogetGPC('marriage1','integer','P');
	$members_search['height'] = MoogetGPC('height','integer','P');
	$members_search['salary'] = MoogetGPC('salary','integer','P');
	$members_search['education'] = MoogetGPC('education1','integer','P');
	$members_search['children'] = MoogetGPC('children1','integer','P');
	$members_search['house'] = MoogetGPC('house','integer','P');
	//$members_base['oldsex'] = MoogetGPC('oldsex','integer','P');
	$members_search['province'] = MoogetGPC('province','integer','P');
	$members_search['city'] = MoogetGPC('city','integer','P');
	if( in_array( $members_search['province'],array( 10101201, 10101002)) )
	{
		//note 修正广东省深圳和广州的区域查询 2010-09-04
		$members_search['city'] = $members_search['province'];
		$members_search['province'] = 10101000;
	}
	
	if(!rtrim($members_search['nickname'])){
			$error = "昵称必填";
	    	echo return_data($error,false);exit;
		}
	if(preg_match('/^(1[345]\d{9})|(18[024-9]\d{8})|(010-?\d{8})|(02)[012345789]-?\d{8}|(0[3-9]\d{2,2}-?\d{7,8})|(.*@.*)$/',$members_search['nickname'])){
		//MooMessage("昵称不符合规范！", "javascript:history.go(-1)");
		$error = "昵称不符合规范！";
	    echo return_data($error,false);exit;
	}
	$where_arr = array('uid'=>$uid);
	//updatetable('members_base',$members_base,$where_arr); 
	updatetable('members_search',$members_search,$where_arr);
		
	if(MOOPHP_ALLOW_FASTDB){			
		//MooFastdbUpdate('members_base','uid',$uid,$members_base);
		MooFastdbUpdate('members_search','uid',$uid,$members_search);
	}
	$error = "修改成功！";
	echo return_data($error,true);exit;
	
}


function shadow_change2(){
	global $_MooClass,$dbTablePre,$userid,$user_arr,$memcached;
	
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$checkuuid = check_uuid($and_uuid,$userid);
	if(!$checkuuid){
	    $error = "uuid_error";
	    echo return_data($error,false);exit;	
	}
	$user_arr = MooMembersData($userid);
	
	
	$members_choice=array();
	$members_introduce=array();
	
	//note choice表字段
	$gender = $_MooClass['MooMySQL']->getOne("select gender from {$dbTablePre}members_search WHERE uid='$uid'",true);
    if($gender['gender']==0){
    	$members_choice['gender']=1;
    }else{
    	$members_choice['gender']=0;
    }
	//$update2_arr['sex'] = MoogetGpc('sex','integer','p');
	$members_choice['age1'] = MoogetGPC('age1','integer','P');
	$members_choice['age2'] = MoogetGPC('age2','integer','P');
	$members_choice['workprovince'] = MoogetGPC('workProvince','integer','P');
	$members_choice['workcity'] = MoogetGPC('workCity','integer','P');

	if( in_array( $members_choice['workprovince'],array( 10101201, 10101002)) )
	{
		//note 修正广东省深圳和广州的区域查询 2010-09-04
		$members_choice['workcity'] = $members_choice['workprovince'];
		$members_choice['workprovince'] = 10101000;
	}

	$members_choice['marriage'] = MoogetGPC('marriage2','integer','P');
	$members_choice['education'] = MoogetGPC('education2','integer','P');
	$members_choice['children'] = MoogetGPC('children2','integer','P');
	
	$members_choice['salary'] = MoogetGPC('salary1','integer','P');
	$members_choice['height1'] = MoogetGPC('height1','integer','P');
	$members_choice['height2'] = MoogetGPC('height2','integer','P');
	$members_choice['hasphoto'] = MoogetGPC('hasphoto','integer','P');
	$members_choice['nature'] = MoogetGPC('nature2','integer','P');
	$members_choice['body'] = MoogetGPC('body2','integer','P');
	$members_choice['weight1'] = MoogetGPC('weight1','integer','P');
	$members_choice['weight2'] = MoogetGPC('weight2','integer','P');
	$members_choice['occupation'] = MoogetGPC('occupation2','integer','P');
	$members_choice['nation'] = MoogetGPC('stock2','integer','P');
	$members_choice['hometownprovince'] = MoogetGPC('hometownProvince2','integer','P');
	$members_choice['hometowncity'] = MoogetGPC('hometownCity2','integer','P');

	if( in_array( $members_choice['hometownprovince'],array( 10101201, 10101002)) )
	{
		//note 修正广东省深圳和广州的区域查询 2010-09-04
		$members_choice['hometowncity'] =$members_choice['hometownProvince'];
		$members_choice['hometownprovince'] = 10101000;
	}

	$members_choice['wantchildren'] = MoogetGPC('wantchildren2','integer','P');
	$members_choice['smoking'] = MoogetGPC('issmoking','integer','P');
	$members_choice['drinking'] = MoogetGPC('isdrinking','integer','P');
	$members_introduce['introduce_check'] =safeFilter(trim(MoogetGPC('introduce','string','P')));
	
	$members_choice['updatetime'] = time();
	$where_arr = array('uid'=>$uid);
	
	updatetable('members_choice',$members_choice,$where_arr);		
	updatetable('members_introduce',$members_introduce,$where_arr);
			
	if(MOOPHP_ALLOW_FASTDB){
		MooFastdbUpdate('members_choice','uid',$uid,$members_choice);
		MooFastdbUpdate('members_introduce','uid',$uid,$members_introduce);
	}
	
	$error = "修改成功！";
	echo return_data($error,true);exit;
	
}
//note 修改个人资料--提交
function material_upinfo_submit() {
	global $_MooClass,$dbTablePre,$userid,$user_arr,$memcached;
	
	$and_uuid = isset($_GET['uuid'])? $_GET['uuid'] : '';
	$uid =  $_GET['uid'] = isset($_GET['uid'])?$_GET['uid']:'';
	if($uid){
		$userid = $mem_uid = $memcached->get('uid_'.$uid);
	}
	$checkuuid = check_uuid($and_uuid,$userid);
	if(!$checkuuid){
	    $error = "uuid_error";
	    echo return_data($error,false);exit;	
	}
	$user_arr = MooMembersData($userid);
	
    //checkAuthMod('index.php?n=material');//客服模拟登录操作没有修改权限
	
	$validation = MooAutoLoad('MooValidation');

	$uid = $userid;
	//$user_rank_id=get_userrank($userid);
	//var_dump($user_rank_id);
	//$update1_arr = $update2_arr = $update3_arr = array();
	//note members表字段
	$members_search=array();
	$members_base=array();
	$members_choice=array();
	$members_introduce=array();
	$birthyear = MooGetGPC('year','string','P');
	$members_search['birthyear'] = $birthyear;
	$birthmonth = MooGetGPC('month','string','P');
	$birthday = MooGetGPC('days','string','P');
	$members_search['updatetime'] = time();
	$members_search['nickname'] = safeFilter(MoogetGPC('nickname','string','P'));
	$members_search['telphone'] = MoogetGPC('telphone','string','P');
	$members_search['marriage'] = MoogetGPC('marriage1','integer','P');
	$members_search['height'] = MoogetGPC('height','integer','P');
	$members_search['salary'] = MoogetGPC('salary','integer','P');
	$members_search['education'] = MoogetGPC('education1','integer','P');
	$members_search['children'] = MoogetGPC('children1','integer','P');
	$members_search['house'] = MoogetGPC('house','integer','P');
	$members_base['oldsex'] = MoogetGPC('oldsex','integer','P');
	$members_search['province'] = MoogetGPC('province','integer','P');
	$members_search['city'] = MoogetGPC('city','integer','P');
	if( in_array( $members_search['province'],array( 10101201, 10101002)) )
	{
		//note 修正广东省深圳和广州的区域查询 2010-09-04
		$members_search['city'] = $members_search['province'];
		$members_search['province'] = 10101000;
	}
	
	//note choice表字段
	$gender = $_MooClass['MooMySQL']->getOne("select gender from {$dbTablePre}members_search WHERE uid='$uid'",true);
    if($gender['gender']==0){
    	$members_choice['gender']=1;
    }else{
    	$members_choice['gender']=0;
    }
	//$update2_arr['sex'] = MoogetGpc('sex','integer','p');
	$members_choice['age1'] = MoogetGPC('age1','integer','P');
	$members_choice['age2'] = MoogetGPC('age2','integer','P');
	$members_choice['workprovince'] = MoogetGPC('workProvince','integer','P');
	$members_choice['workcity'] = MoogetGPC('workCity','integer','P');

	if( in_array( $members_choice['workprovince'],array( 10101201, 10101002)) )
	{
		//note 修正广东省深圳和广州的区域查询 2010-09-04
		$members_choice['workcity'] = $members_choice['workprovince'];
		$members_choice['workprovince'] = 10101000;
	}

	$members_choice['marriage'] = MoogetGPC('marriage2','integer','P');
	$members_choice['education'] = MoogetGPC('education2','integer','P');
	$members_choice['children'] = MoogetGPC('children2','integer','P');
	
	$members_choice['salary'] = MoogetGPC('salary1','integer','P');
	$members_choice['height1'] = MoogetGPC('height1','integer','P');
	$members_choice['height2'] = MoogetGPC('height2','integer','P');
	$members_choice['hasphoto'] = MoogetGPC('hasphoto','integer','P');
	$members_choice['nature'] = MoogetGPC('nature2','integer','P');
	$members_choice['body'] = MoogetGPC('body2','integer','P');
	$members_choice['weight1'] = MoogetGPC('weight1','integer','P');
	$members_choice['weight2'] = MoogetGPC('weight2','integer','P');
	$members_choice['occupation'] = MoogetGPC('occupation2','integer','P');
	$members_choice['nation'] = MoogetGPC('stock2','integer','P');
	$members_choice['hometownprovince'] = MoogetGPC('hometownProvince2','integer','P');
	$members_choice['hometowncity'] = MoogetGPC('hometownCity2','integer','P');

	if( in_array( $members_choice['hometownprovince'],array( 10101201, 10101002)) )
	{
		//note 修正广东省深圳和广州的区域查询 2010-09-04
		$members_choice['hometowncity'] =$members_choice['hometownProvince'];
		$members_choice['hometownprovince'] = 10101000;
	}

	$members_choice['wantchildren'] = MoogetGPC('wantchildren2','integer','P');
	$members_choice['smoking'] = MoogetGPC('issmoking','integer','P');
	$members_choice['drinking'] = MoogetGPC('isdrinking','integer','P');
	$members_introduce['introduce_check'] =safeFilter(trim(MoogetGPC('introduce','string','P')));
	
	
	$rs = $user_arr;
	
	//note 验证状态
	if(MOOPHP_ALLOW_FASTDB){
		$sta = MooFastdbGet('certification','uid',$uid);
	}else{
		$sta = $_MooClass['MooMySQL']->getOne("select telphone from {$dbTablePre}certification WHERE uid='$uid'",true);
	}
	$where_arr = array('uid'=>$uid);
	foreach($members_search as $key=>$val){
		//无手机号吗
		if($key == 'telphone' && $val == ''){		
			continue;
		}
		$memberssearch[$key]=$val;		
	}
	
//	foreach ($members_base as $key=>$val){
//		$membersbase[$key]=$val;
//	}

	if(count($members_base)>=1 || count($memberssearch)>=1){
		$members_search['updatetime'] = time();
		if(!rtrim($members_search['nickname'])){
			$error = "昵称必填";
	    	echo return_data($error,false);exit;
		}
		if(preg_match('/^(1[345]\d{9})|(18[024-9]\d{8})|(010-?\d{8})|(02)[012345789]-?\d{8}|(0[3-9]\d{2,2}-?\d{7,8})|(.*@.*)$/',$members_search['nickname'])){
			//MooMessage("昵称不符合规范！", "javascript:history.go(-1)");
			$error = "昵称不符合规范！";
	    	echo return_data($error,false);exit;
		}
		//echo 'sss';exit;
		str_length($members_search['nickname']);
		//note 昵称截取
		$members_search['nickname'] = MooCutstr($members_search['nickname'], 18, $dot = '');
		
		if($members_search['telphone'] && !preg_match('/^((1[345]\d{9})|(18[0-9]\d{8}))$/',$members_search['telphone'])){
			//MooMessage('请输入正确的手机号码','javascript:history.go(-1)');
			$error = "请输入正确的手机号码";
	    	echo return_data($error,false);exit;
		}
		//$birth=strtotime("$birthyear/$birthmonth/$birthday");
		$birth = "$birthyear-$birthmonth-$birthday";
		$members_base['birth']=$birth;
		updatetable('members_base',$members_base,$where_arr); 
		updatetable('members_search',$memberssearch,$where_arr);
		
		if(MOOPHP_ALLOW_FASTDB){			
			MooFastdbUpdate('members_base','uid',$uid,$members_base);
			MooFastdbUpdate('members_search','uid',$uid,$memberssearch);
		}
		
		//searchApi("members_man members_women")->UpdateAttributes(array($uid=>$members_search));
	
	}
		//提交会员动态makui
		UpdateMembersSNS($uid,'修改了资料');
		//内心独白必填
		//if(rtrim($update2_arr['introduce_check'] != '')){
			$members_introduce['introduce'] = '';
			$members_introduce['introduce_pass'] = '2';
			//if(isset($members_choice)){
				$members_choice['updatetime'] = time();
			    updatetable('members_choice',$members_choice,$where_arr);
			//}
			updatetable('members_introduce',$members_introduce,$where_arr);
			
			if(MOOPHP_ALLOW_FASTDB){
				$members_choice['uid'] = $uid;
				$members_introduce['uid'] = $uid;
				//print_r($update2_arr);exit;
				if(isset($members_choice)){
					$members_choice['updatetime']=time();
				    MooFastdbUpdate('members_choice','uid',$uid,$members_choice);
				}
				MooFastdbUpdate('members_introduce','uid',$uid,$members_introduce);
			}
			//searchApi("members_man members_women")->UpdateAttributes(array($uid=>$members_choice));
			if(MOOPHP_ALLOW_FASTDB){
				$userchoice = MooFastdbGet('members_choice','uid',$uid);
				$introduce = MooFastdbGet('members_introduce', 'uid', $uid);
				$userchoice = array_merge($userchoice,$introduce);
			}else{
				$userchoice = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}members_choice mc left join {$dbTablePre}members_introduce mi WHERE mc.uid=mi.uid AND uid = '$uid'",true);
			}
		    
		//}else{
		//	MooMessage("内心独白必填！", "index.php?n=material&h=upinfo");
		//}
		/*
		//低质量会员自动分配
		if($user_rank_id == 0){
			//以下信息都没选，都规为垃圾会员,自动分配给普通客服
			if($update1_arr['height']=='-1' || $update1_arr['salary']=='-1' || $update1_arr['children']=='-1' || $update1_arr['oldsex']=='-1' || $update2_arr['age1']=='-1' || $update2_arr['age2']=='-1' || $update2_arr['marriage'] == '-1' || $update2_arr['children'] == '-1' || $update2_arr['body'] == '-1'){
				invalid_user_allotserver($uid);
			}
		}
		*/
       
       
		    
					if($rs['telphone'] == $members_search['telphone'] || $sta['telphone'] == '' || $members_search['telphone'] == ''){
		      			$error = "修改成功";
	    				echo return_data($error,true);exit;
			 		}
					else {//重新手机认证
						$sql="update {$dbTablePre}certification  set telphone='' where uid='$uid'";
						$_MooClass['MooMySQL']->query($sql);
						$certif_arr['telphone'] = '';
						MooFastdbUpdate('certification','uid',$uid,$certif_arr);

						if(MOOPHP_ALLOW_FASTDB){
							if(MOOPHP_ALLOW_FASTDB){
								$certification_1 = MooFastdbGet('certification','uid',$userid);
							}else{
								$certification_1 = $_MooClass['MooMySQL']->getOne("SELECT * FROM {$dbTablePre}certification  WHERE uid = '$userid'",true);
							}
							
						}
						//Message("您的手机信息有变动请再次通过我们的验证",'index.php?n=myaccount&h=telphone');
						$error = "您的手机信息有变动请再次通过我们的验证";
	    				echo return_data($error,true);exit;     
					}
				
	  
	
	 //note 快速常用搜索表更新
	//fastsearch_update($userid,'1');
		
	//note 快速高级搜索表更新
	//fastsearch_update($userid,'2');
	
}
/**
 * 客服模拟登录 没有权限修改删除 会员资料
 */
function checkAuthMod($url){
   	global $dbTablePre,$_MooClass;
	$serverid=Moo_is_kefu();
	$sql="select groupid from {$dbTablePre}admin_user where uid='{$serverid}'";
	$result=$_MooClass['MooMySQL']->getOne($sql);
	
	if(!in_array($result['groupid'],array('60','61','76','75','65','66','68','69','82','81')) && !empty($serverid)){
	   //MooMessage ( "sorry，您没有操作权限", $url );
	   $error = "sorry，您没有操作权限";
	   echo return_data($error,true);exit;  
	   return ;
	}
	
}

/*
 * ***********************************************************控制层*****************************************/

$h = $_GET['m'] = isset($_GET['m'])?$_GET['m']:'';

switch ($h){
	case 'upinfo':
		shadow_change1();
		/*
		$issubmit=MooGetGPC('issubmit','string','P');
		if($issubmit){
            material_upinfo_submit();
		}else{
			material_upinfo_view();
		}
		*/
		break;
	case 'upinfo1':
		shadow_change2();
		break;
}
