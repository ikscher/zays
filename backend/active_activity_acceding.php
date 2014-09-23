<?php
/**
 * 活动参加列表
 */
//活动列表
//该函数已经不用了 mark
function active_activity_list() {
	$type=MooGetGPC('type','integer','G');
	$page=max(1,MooGetGPC('page','integer','G'));
	$limit = 20;
    $offset = ($page-1)*$limit;
    $where=empty($type)?'':'where `type`='.$type;
    $common='`'.$GLOBALS['dbTablePre'].'activity_acceding` as a left join `'.$GLOBALS['dbTablePre'].'activity` as `b` on a.channel=b.`id`  left join `'.$GLOBALS['dbTablePre'].'members_search` as c on a.uid=c.uid';
    $sql='select `id`,`type`,`title`,`price`,`province`,`city`,`regtime` from '.$common.' '.$where.' limit '.$offset.','.$limit;
    $count=$GLOBALS['_MooClass']['MooMySQL']->getOne('select count(`id`) as total from `'.$common.' '.$where);
    $count=empty($count)?0:$count['total'];
    $data=empty($count)?array():$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    serverlog(1,$GLOBALS['dbTablePre'].'activity',"{$GLOBALS['username']}查看活动列表", $GLOBALS['adminid']);
    require(adminTemplate('active_activity_list'));
}
//活动添加
function active_activity_acceding_add(){
    $data=array();
    if(!empty($_POST)){
        $uid=MooGetGPC('uid','string','P');
        if(empty($uid)){
             MooMessageAdmin('请填写用户的uid','index.php?action=active_activity_acceding&h=add');
        }
        $channel=MooGetGPC('channel','integer','P');
        if(empty($channel)){
            MooMessageAdmin('请选择活动项目','index.php?action=active_activity_acceding&h=add');
        }
        //找出已经是参加活动的会员
        $activity_all_user=$GLOBALS['_MooClass']['MooMySQL']->getAll('select `uid` from `'.$GLOBALS['dbTablePre'].'activity_acceding` where `uid` in ('.$uid.')');
        //找出已经是参加某项活动的会员
        $activity_all_channel_user=$GLOBALS['_MooClass']['MooMySQL']->getAll('select `uid` from `'.$GLOBALS['dbTablePre'].'activity_acceding` where `channel`='.$channel.' and `uid` in ('.$uid.')');
        $activity_all_users=$activity_all_channel_users=array();
        if(!empty($activity_all_user)){
            foreach($activity_all_user as $value){
                $activity_all_users[]=$value['uid'];
            }
        }
        if(!empty($activity_all_channel_user)){
            foreach($activity_all_channel_user as $value){
                $activity_all_channel_users[]=$value['uid'];
            }
        }
        
        $uids=explode(',',$uid);
        $members_uid=$GLOBALS['_MooClass']['MooMySQL']->getAll('select uid from '.$GLOBALS['dbTablePre'].'members_search');
        foreach ($members_uid as $key=>$member_uid){
        	foreach ($member_uid as $k=>$v){
        	    $memuid[]=$v;
        	}
        }

        foreach ($uids as $key=>$value){
        	if(!in_array($value, $memuid)){
        		$error_uid[]=$value;
        	}
        }
        if(isset($error_uid)){
            MooMessageAdmin('没有您添加的uid'.implode(',',$error_uid),'index.php?action=active_activity_acceding&h=add');
        }
        $up_youzhi=empty($activity_all_users)?$uids:array_diff_assoc($uids, $activity_all_users);
        $add_channel_user=empty($activity_all_channel_users)?$uids:array_diff_assoc($uids,$activity_all_channel_users);
        is_array($add_channel_user) && !empty($add_channel_user) && $sid=$GLOBALS['_MooClass']['MooMySQL']->getAll('select `uid`,`sid` from `'.$GLOBALS['dbTablePre'].'members_search` where `uid` in ('.implode(',',$add_channel_user).')');
        $sids=$add_channel=$add_msg=array();
        if(isset($sid) && is_array($sid) && !empty($sid)){
	        foreach($sid as $value){
	            if(!empty($value['sid'])){
	                $sids[$value['uid']]=$value['sid'];
	            }
	        }
        }
        $dateline=time();
        $awoketime=$dateline+120;
        foreach($add_channel_user as $value){
            $add_channel[]='('.$value.','.$channel.')';
            if(array_key_exists($value,$sids)){
                $add_msg[]='('.$sids[$value].',"会员参见活动","'.$value.'参加新的活动了",'.$awoketime.','.$dateline.')';
            }
        }
        if(!empty($add_channel)){
            if($GLOBALS['_MooClass']['MooMySQL']->query('INSERT INTO `web_activity_acceding` (`uid`, `channel`) VALUES '.implode(',',$add_channel))){
                   $GLOBALS['_MooClass']['MooMySQL']->query('INSERT INTO `web_admin_remark` (`sid`,`title`,`content`,`awoketime`,`dateline`) VALUES '.implode(',', $add_msg));
                    if(is_array($up_youzhi) && !empty($up_youzhi) && $GLOBALS['_MooClass']['MooMySQL']->query('UPDATE `web_members_search` SET `is_well_user` = \'1\'  WHERE `uid` in ('.implode(',',$up_youzhi).')')){
                    	$uid_arr = array();
                    	foreach($up_youzhi as $k=>$v){
                    		$uid_arr[$v] = array(1);
                    	}
                    	!empty($uid_arr) && searchApi("members_man members_women") -> updateAttr(array('is_well_user'),$uid_arr);
                        $is_well_user=array();
                        foreach($up_youzhi as $value){
                            $is_well_user['is_well_user']=1;
                            MooFastdbUpdate('members_search','uid',$value, $is_well_user);
                        }
                    }
                 MooMessageAdmin('操作成功','index.php?action=active_activity_acceding&h=add');
            }else{
                MooMessageAdmin('操作失败','index.php?action=active_activity_acceding&h=add');
            }
        }
    }
    $channels=$GLOBALS['_MooClass']['MooMySQL']->getAll('select `title`,`id` from `'.$GLOBALS['dbTablePre'].'activity`');
	require(adminTemplate('active_activity_acceding_detail'));
	
}
//活动编辑
function active_activity_edit(){
    $id=MooGetGPC('id','integer','G');
    $id=empty($id)?MooGetGPC('id','integer','P'):$id;
     $data=array();
    if($_POST){
        $where['id']=$id;
        $data['title']=trim(MooGetGPC('title','string','P'));
        if(empty($data['title'])){
            MooMessageAdmin('请活动主题','index.php?action=active_activity&h=add');
            exit;
        }
        $data['type']=MooGetGPC('type','integer','P');
        $data['price']=MooGetGPC('price','integer','P');
        $data['price_online']=MooGetGPC('price_online','integer','P');
        $data['starttime']=MooGetGPC('starttime','string','P');
        if(empty($data['starttime'])){
            MooMessageAdmin('请填写活动的报名开始时间','index.php?action=active_activity&h=add');
            exit;
        }
        $data['starttime']=strtotime($data['starttime'].' 0:0:0');
        $data['endtime']=MooGetGPC('endtime','string','P');
        if(empty($data['endtime'])){
            MooMessageAdmin('请填写活动的报名结束时间','index.php?action=active_activity&h=add');
            exit;
        }
        $data['endtime']=strtotime($data['endtime'].' 23:59:59');
        $data['opentime']=MooGetGPC('opentime','array','P');
        if(empty($data['opentime']['day'])){
            $data['opentime']=0;
        }else{
            $data['opentime']=strtotime($data['opentime']['day'].' '.$data['opentime']['hour'].':'.$data['opentime']['mintue'].':0');
        }
        $data['closetime']=MooGetGPC('closetime','array','P');
        if(empty($data['closetime']['day'])){
            $data['closetime']=0;
        }else{
            $data['closetime']=strtotime($data['closetime']['day'].' '.$data['closetime']['hour'].':'.$data['closetime']['mintue'].':0');
        }
        $data['issex']=MooGetGPC('issex','string','P');
        $data['issex']=($data['issex']<1)?100:($data['issex']>100?100:$data['issex']);
        $data['province']=MooGetGPC('province','integer','P');
        $data['city']=MooGetGPC('city','integer','P');
        $data['place']=htmlspecialchars(trim(MooGetGPC('place','string','P')));
        $data['profile']=htmlspecialchars(trim(MooGetGPC('profile','string','P')));
        $data['introduction']=htmlspecialchars(trim(MooGetGPC('introduction','string','P')));
        if(updatetable('activity', $data, $where)){
            MooMessageAdmin('活动修改成功','index.php?action=active_activity&h=edit&id='.$id);
        }else{
            MooMessageAdmin('活动修改失败','index.php?action=active_activity&h=edit&id='.$id);
        }
    }
    $sql='SELECT `type`,`title`,`price`,`price_online`,`starttime`,`endtime`,`opentime`,`closetime`,`issex`,`province`,`city`,`place`,`profile`,`introduction` from `'.$GLOBALS['dbTablePre'].'activity` where `id`='.$id;
    $data=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    require(adminTemplate('active_activity_detail'));
}
$h=MooGetGPC('h','string','G');
$h_array=array('list','add','edit');
$h=in_array($h, $h_array)?$h:'add';
//if(!checkGroup('active_activity_acceding',$h)){
//	MooMessageAdmin('您没有此操作的权限','index.php');
//}
switch($h){
	case 'list':
		active_activity_list();
		break;
	case 'add':
		active_activity_acceding_add();
		break;
	case 'edit':
		active_activity_edit();
		break;
}