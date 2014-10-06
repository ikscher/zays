<?php
/*
    More & Original PHP Framwork
    Copyright (c) 2007 - 2008 IsMole Inc.

    $Id:config.php 395 2010-06-15 
    @author:fanglin
*/
//note 后台模板目录
!defined('MOOPHP_ADMIN_DIR') && define('MOOPHP_ADMIN_DIR','templates');
//note 定义加密所需Key，请修改此处为唯一
// !defined('MOOPHP_AUTHKEY') && define('MOOPHP_AUTHKEY', '0051master');
//note 定义COOKIE加密的密钥
!defined('HLW_KEY') && define('HLW_KEY', 'e4r9O87dQfZdNdK3t5D4u9a038Edp8F6w684P8m9OcY5fdg801v76biehcr758Ja');

//note 大图图片路径
!defined('IMG_ROOT') && define("IMG_ROOT",dirname(__FILE__)."/../../data/upload/images/story/");
//note 小图图片路径
!defined('TO_IMG_CHANGE_ROOT') && define("TO_IMG_CHANGE_ROOT",dirname(__FILE__)."/../../data/upload/story/");

//note 包含缓存文件--后台客服列表,$kefu_arr
$file_kefulist_cache = 'data/kefulist_cache.php';
if(file_exists($file_kefulist_cache)){
    include 'data/kefulist_cache.php';  
}


                
//note 目前所存在的组类别type  数组
//$admingroup_type = array(1=>'客服主管',2=>'队长',3=>'组',4=>'普通客服',5=>'队');


//note 红娘网后台添加好组之后，此处配置组ID-groupid

//系统管理员权限
$GLOBALS['system_admin'] = array(60);

//系统管理员/客服主管权限/分部主管/75是超级主管
$GLOBALS['admin_service_arr'] = array(60,61,76,75);

//客服主管权限
$GLOBALS['admin_the_service_arr'] = array(61,76);

//售后主管权限
$GLOBALS['admin_service_after']=array(77,76);

//普通售前客服权限
$GLOBALS['general_service_pre'] = array(67);

//售后客服权限
$GLOBALS['general_service_sale'] = array(70);

//普通客服/售后客服权限
$GLOBALS['general_service'] = array(67,70);

//售后普通客服，我的用户标识（交接信息，显示服务时间段）
$GLOBALS['admin_aftersales_service2'] = array(70,60);

//队长/副队长权限
$GLOBALS['admin_service_team'] = array(63,64);

//
$GLOBALS['admin_service_team'] = array(63,64);

//销售(售前)组长/副组长权限
$GLOBALS['admin_service_group'] = array(65,66);

//组长权限
$GLOBALS['admin_all_group'] = array(65,66,68,69);

//售前所有人
$GLOBALS['admin_service_pre'] = array(65,66,67);

//售后组长,处理分配高级会员,右下角提醒
$GLOBALS['admin_aftersales_service'] = array(68);
//售后副组长权限
$GLOBALS['admin_aftersales_service_fu'] = array(69);
//售后组长副组长权限
$GLOBALS['admin_aftersales_leader'] = array(68,69);


//售前、售后组长
$GLOBALS['admin_leader'] = array(65,68);

//财务提成报表查看权限
$GLOBALS['admin_finance'] = array(73);

//会员升级帐目核对权限
$GLOBALS['admin_vip_level_check'] = array(74,72,60,75);

//资料审核专员权限
$GLOBALS['admin_userinfo_check'] = array(62);

//售后
$GLOBALS['admin_aftersales'] = array(77,70,69,68,61,60,76,78,79);

//客诉
$GLOBALS['admin_complaints'] = array(78,79);

//通道权限
$GLOBALS['admin_channel'] = array(60,61,76,77,63,64,65,66,68,69);

//售后连长权限
$GLOBALS['admin_service_manager']=array(81);

// 资源管理权限
$GLOBALS['admin_resource_manager']=array(82);



//会员等级
$member_level = array(20=>'钻石会员',30=>'高级会员',40=>'普通会员',50=>'红娘',10=>'铂金会员');//这里必须设置为大于=0

//申请会员等级
$apply_member_level = array(0=>'钻石会员',1=>'（三个月）高级会员',4=>'（一个月）高级会员',2=>'城市之星',3=>'续费',5=>'高级会员升钻石会员',6=>'补款（预付）',-1=>'（六个月）铂金会员',8=>'高级会员升级铂金',9=>'钻石会员升级铂金');//这里必须设置为大于=0


//右下角是否提醒显示
$GLOBALS['userWarning']=array('522');

$bankarr = array('yeepay'=>'易宝支付',
                 'tenpay'=>'财付通',
                 'alipay'=>'支付宝',
                 'yeepayepos'=>'易宝刷卡支付',
                 'ALIPAY'=>'支付宝',
                 'BC'=>'中国银行',
                 'EMS'=>'中国邮政',
                 'CEB'=>'中国光大银行',
                 'ABC'=>'中国农业银行',
                 'ICBC'=>'中国工商银行',
                 'CITIC'=>'中信银行',
                 'BCM'=>'交通银行',
                 'CMBC'=>'招商银行',
                 'CMSB'=>'中国民生银行',
                 'CIB'=>'兴业银行',
                 'SPDB'=>'浦发银行',
                 'CCB'=>'中国建设银行',
				 'GDB'=>'广东发展银行',
				 'HXB'=>'华夏银行',
				 'RCC'=>'农村信用社',
				 'TEL'=>'电话支付');      
				 

$tel_bankarr = array('cc_BC'=>'中国银行',
                 'dc_EMS'=>'中国邮政',
                 'dc_ABC'=>'中国农业银行',
				 'cc_ABC'=>'中国农业银行',
                 'cc_ICBC'=>'中国工商银行',
                 'dc_CITIC'=>'中信银行',
                 'dc_BCM'=>'交通银行',
                 'dc_CMBC'=>'招商银行',
                 'dc_HXB'=>'华夏银行',
                 'dc_GDB'=>'广发银行',
                 'cc_CCB'=>'中国建设银行'); 				 
				 

//跟进步骤
$grade = array(1=>'0：新分客户',    //2默认的会员
                2=>'1：可以继续沟通', //8可能会员
                3=>'2：有急迫动机需求',//3正在犹豫的会员
                4=>'3：认可服务和网站', //7垃圾会员
                5=>'4：认可价格，7天内付款',    //4快成功会员
                6=>'5：确定付款时间',
                7=>'6：毁单',   //6很垃圾会员
                8=>'7：倒退会员',
                9=>'8：放弃(留本人名下)',//放弃后留在本人名下
                10=>'9：放弃(不可回收)',   //不可能升级的会员
                11=>'10:联系不上',
				12=>'11:服务期内的会员');
//售后跟进步骤
$sell_after_grade = array(1=>'1：推荐',
                                    2=>'2：模拟',
                                    3=>'3：关注',
                                    4=>'4：回访',
                                    5=>'5：客诉');
//vip会员是否认可续费等级
$renewals_status_grade = array(0=>'0：默认',
                                            1=>'1：服务有异议',
                                            2=>'2：有重大投诉',
                                            3=>'3：已找到',
                                            4=>'4：认可续费');
$renewalslink = array(0=>'默认',1=>'有异议',2=>'重大投诉',3=>'已找到',4=>'认可续费');

//vip会员  推荐会员进展
$member_progress_grade = array(0=>'0：默认',
                                                 1=>'1：与推荐会员沟通不合适',
                                                 2=>'2：与推荐会员见面',
                                                 3=>'3：与推荐会员交往中',
                                                 4=>'4：与推荐会员确定关系');


//会员进展链接
$processlink = array(0=>'默认',1=>'沟通不合适',2=>'见面',3=>'交往中',4=>'确定关系');

//测试类型
$love_type=array(1=>'理想的爱情',
                2=>'吸引您的他/她',
                3=>'爱情的习性(初相识)',
                4=>'爱情的习性(相恋中)',
                5=>'爱情的习性(承诺与顾虑)',
                6=>'爱情的习性(婚姻家庭)',
                7=>'潜伏的危机',
                8=>'隐藏的一面',
                9=>'红娘给您的忠告');



//成功故事进程
$story_process=array(
    //4=>'第一次见面',
    1=>'恋爱',
    2=>'订婚',
    3=>'结婚',
);
//会员成功故事甜蜜进程
$story_sweet_process=array(
    //4=>'我们第一次见面',
    1=>'我们恋爱了',
    2=>'我们订婚了',
    3=>'我们结婚了',
);

//评分规则
$reprating=array(
    1=>'1、非常满意',
    2=>'2、满意',
    3=>'3、一般',
    4=>'4、不满意',
    5=>'5、非常不满意'
);


/*********************红娘意见箱**********************/
//模块划分
$areaid_arr = array(
    0 => '其他',
    1 => '前台',
    2 => '后台'
);

//前台模块
$foreground = array(
    0 => '其他',
    1 => '首页',
    2 => '我的红娘',
    3 => '红娘寻友',
    4 => '资料设置',
    5 => '爱情测试',
    6 => '诚信认证',
    7 => 'E见钟情',
    8 => '视频认证',
    9 => '会员升级'
);

//后台模块
$background = array(
    0 => '其他',
    1 => '网站管理',
    2 => '测试管理',
    3 => '我的用户',
    4 => '全部会员',
    5 => '会员升级',
    6 => '互动管理',
    7 => '报表',
    8 => '信息审核',
    9 => '系统管理',
    10 => '其他管理'
);

//意见采纳状态
$accept_arr = array(
    0 => '未处理',
    1 => '采纳',
    2 => '暂不需要'
);

//意见处理状态
$status_arr = array(
    0 => '等待处理',
    1 => '处理完成',
    2 => '正在处理'
);
/***************红娘意见箱end*****************/

//放弃理由
$del_cause_arr = array(
                1 => '空号',
                2 => '停机',
                3 => '非本人注册',
                4 => '3天内一直关机',
                5 => '已婚',
                6 => '注册玩的',
                7 => '其他'
            );




?>
