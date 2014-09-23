<?php
/**
 * 活动项目管理
 */
//活动列表 mark
function active_activity_list() {
	$type=MooGetGPC('type','integer','G');
	$page=max(1,MooGetGPC('page','integer','G'));
	$limit = 20;
    $offset = ($page-1)*$limit;
    $where=empty($type)?'':'where `type`='.$type;
    $sql='select `id`,`type`,`title`,`price`,`province`,`city`,`regtime` from `'.$GLOBALS['dbTablePre'].'activity` '.$where.' limit '.$offset.','.$limit;
    $count=$GLOBALS['_MooClass']['MooMySQL']->getOne('select count(`id`) as total from `'.$GLOBALS['dbTablePre'].'activity` '.$where);
    $count=empty($count)?0:$count['total'];
    $data=empty($count)?array():$GLOBALS['_MooClass']['MooMySQL']->getAll($sql);
    serverlog(1,$GLOBALS['dbTablePre'].'activity',"{$GLOBALS['username']}查看活动列表", $GLOBALS['adminid']);
    require(adminTemplate('active_activity_list'));
}
//活动添加
function active_activity_add(){
    $data=array();
    if(!empty($_POST)){
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
		$data['activity_img']='module/activity/templates/default/images/activity_new/'.MooGetGPC('img','string','P');
		$data['href']='index.php?n=activity&h='.MooGetGPC('href','string','P');
        $data['place']=htmlspecialchars(trim(MooGetGPC('place','string','P')));
        $data['profile']=htmlspecialchars(trim(MooGetGPC('profile','string','P')));
        $data['introduction']=htmlspecialchars(trim(MooGetGPC('introduction','string','P')));
        $data['regtime']=time();
        $data['adminid']=$GLOBALS['adminid'];
        $id=inserttable('activity', $data, true);
        if(empty($id)){
             MooMessageAdmin('活动添加失败,请联系技术支持,或者重新填写表单项','index.php?action=active_activity&h=add');
             exit;
        }else{
            MooMessageAdmin('活动添加成功','index.php?action=active_activity&h=add');
            exit;
        }
    }
	require(adminTemplate('active_activity_detail'));
	
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
		$data['activity_img']='module/activity/templates/default/images/activity_new/'.MooGetGPC('img','string','P');
		$data['href']='index.php?n=activity&h='.MooGetGPC('href','string','P');
		$data['city']=MooGetGPC('city','integer','P');
        $data['place']=htmlspecialchars(trim(MooGetGPC('place','string','P')));
        $data['profile']=htmlspecialchars(trim(MooGetGPC('profile','string','P')));
        $data['introduction']=htmlspecialchars(trim(MooGetGPC('introduction','string','P')));

        updatetable('activity', $data, $where);
        MooMessageAdmin('活动修改成功','index.php?action=active_activity&h=edit&id='.$id);

    }
    $sql='SELECT `type`,`title`,`price`,`price_online`,`starttime`,`endtime`,`opentime`,`closetime`,`issex`,`province`,`city`,`place`,`profile`,`introduction` from `'.$GLOBALS['dbTablePre'].'activity` where `id`='.$id;
    $data=$GLOBALS['_MooClass']['MooMySQL']->getOne($sql);
    require(adminTemplate('active_activity_detail'));
}
$h=MooGetGPC('h','string','G');
$h_array=array('list','add','edit');
$h=in_array($h, $h_array)?$h:'list';
if(!checkGroup('active_activity',$h)){
	MooMessageAdmin('您没有此操作的权限','index.php?action=active_activity');
}
switch($h){
	case 'list':
		active_activity_list();
		break;
	case 'add':
		active_activity_add();
		break;
	case 'edit':
		active_activity_edit();
		break;
}