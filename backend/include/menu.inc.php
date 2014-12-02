<?php
/**
 * 后台导航配置文件
 *  $Id$
 */
//note 所有导航
$menu_nav_arr = array(
			'site'=>'网站管理',
			'hntest'=>'测试管理',
			'myuser'=>'我的用户',
			'allmember'=>'全部会员',
			'vipuser'=>'会员升级',
			'active' => '互动管理',
			'financial' => '报表',
			'check' => '信息审核',
			'system' => '系统管理',
			'other' => '其它管理',
			'activity'=>'活动管理',
			'matchmaker'=>'红娘币管理',
            'lovestation'=>'爱情驿站',
);

//note 报表菜单
$leftMenu['financial'] = array(
	'financial_telphonetime_list'=>array('title'=>'个人电话时间','url'=>'index.php?action=financial_telphonetime&h=list'),
	'financial_orderok_list'=>array('title'=>'个人成功订单','url'=>'index.php?action=financial_orderok&h=list'),    
    'financial_ordertotal_list'=>array('title'=>'成功订单总数','url'=>'index.php?action=financial_ordertotal&h=list'),
	'financial_totalwage_list'=>array('title'=>'财务报表','url'=>'index.php?action=financial_totalwage&h=list'),
	'financial_tele_info_list'=>array('title'=>'会员联系情况统计','url'=>'index.php?action=financial_tele_info&h=list'),
	'financial_wordbook_list'=>array('title'=>'售后报表','url'=>'index.php?action=financial_wordbook&h=list'),
	'financial_urgencyclient_list'=>array('title'=>'最急需维护的客户','url'=>'index.php?action=financial_urgencyclient&h=list'),
	'financial_assert_list'=>array('title'=>'售后月维护情况','url'=>'index.php?action=financial_assert&h=list'),
	'financial_msm_grade_count'=>array('title'=>'短信评分及有效联系数','url'=>'index.php?action=financial&h=msm_grade_count'),
	'financial_feedback_fraction'=>array('title'=>'来自网站对红娘的评分','url'=>'index.php?action=financial&h=feedback_fraction'),
	'financial_delstatistics'=>array('title'=>'不可回收会员原因统计','url'=>'index.php?action=financial&h=delstatistics'),
	'financial_member_grade_count'=>array('title'=>'会员跟进步骤统计','url'=>'index.php?action=financial&h=member_grade_count'),
	'financial_classStatistics'=>array('title'=>'客服名下会员类统计','url'=>'index.php?action=financial&h=classStatistics'),
	'financial_NewClassStat'=>array('title'=>'客服当日会员类统计','url'=>'index.php?action=financial&h=NewClassStat'),
 /*    'financial_ahtv_list'=>array('title'=>'活动统计','url'=>'index.php?action=financial_ahtv&h=list'),
	'financial_ahtv_reguser_list'=>array('title'=>'活动注册统计','url'=>'index.php?action=financial_ahtv_reguser&h=list'), */
	'financial_joinstatistics'=>array('title'=>'会员交接统计','url'=>'index.php?action=financial_joinstatistics')
);

//note 互动管理菜单
$leftMenu['active'] = array(
    'active_email_list'=>array('title'=>'会员反馈邮件','url'=>'index.php?action=active_email&h=list'),
	'active_commission_list'=>array('title'=>'会员委托','url'=>'index.php?action=active_commission&h=list'),
	'active_websms_list'=>array('title'=>'站内短信','url'=>'index.php?action=active_websms&h=list'),
    'active_leer_list'=>array('title'=>'会员秋波','url'=>'index.php?action=active_leer&h=list'),
    'active_rose_list'=>array('title'=>'会员鲜花','url'=>'index.php?action=active_rose&h=list'),
	'active_liker_list'=>array('title'=>'会员意中人','url'=>'index.php?action=active_liker&h=list'),
	'active_chat_list'=>array('title'=>'会员聊天记录','url'=>'index.php?action=active_chat&h=list'),
	'active_bind_list'=>array('title'=>'会员绑定','url'=>'index.php?action=active_bind&h=list'),
    'active_cooperation_list'=>array('title'=>'志愿者合作列表','url'=>'index.php?action=active_cooperation&h=list'),
    'active_activity_list'=>array('title'=>'活动列表','url'=>'index.php?action=active_activity&h=list'),
    'active_activity_acceding_add'=>array('title'=>'活动参加批量填写','url'=>'index.php?action=active_activity_acceding&h=add'),
    'active_SendMMSStat'=>array('title'=>'已发送彩信记录数查询','url'=>'index.php?action=active_SendMMSStat'),
	'active_uplink_list'=>array('title'=>'会员上行短信','url'=>'index.php?action=active_uplink&h=list')
);

//note 信息审核
$leftMenu['check'] = array(
	'check_letter'=>array('title'=>'站内信审核','url'=>'index.php?action=check&h=letter'),
	'check_photo'=>array('title'=>'形象照审核','url'=>'index.php?action=check&h=photo'),
	'check_monolog'=>array('title'=>'内心独白审核','url'=>'index.php?action=check&h=monolog'),
	'check_image'=>array('title'=>'相册图片审核','url'=>'index.php?action=check&h=image'),
	'check_school'=>array('title'=>'毕业院校审核','url'=>'index.php?action=check&h=school'),
	'check_story'=>array('title'=>'成功故事审核','url'=>'index.php?action=check&h=story'),
	'check_storyfirst'=>array('title'=>'故事封面图审核','url'=>'index.php?action=check&h=storyfirst'),
	'check_storyimage'=>array('title'=>'故事图片审核','url'=>'index.php?action=check&h=storyimage'),
	'check_paper'=>array('title'=>'会员证件审核','url'=>'index.php?action=check&h=paper'),
	'check_report'=>array('title'=>'举报受理审核','url'=>'index.php?action=check&h=report'),
	'check_feedback'=>array('title'=>'意见反馈审核','url'=>'index.php?action=check&h=feedback'),
	//'check_memberinfo_list'=>array('title'=>'修改用户资料审核','url'=>'index.php?action=check_memberinfo&h=list'),
	'check_comment'=>array('title'=>'会员评价审核','url'=>'index.php?action=check&h=comment'),
	'check_video'=>array('title'=>'会员视频审核','url'=>'index.php?action=check&h=video'),
	'check_voice'=>array('title'=>'会员录音审核','url'=>'index.php?action=check&h=voice'),
	'check_activity'=>array('title'=>'活动评论审核','url'=>'index.php?action=check&h=activity'),
);

//网站管理
$leftMenu['site'] = array(
	'site_index'=>array('title'=>'首页推荐','url'=>'index.php?action=site&h=index'),
	'site_love_test'=>array('title'=>'测试分类','url'=>'index.php?action=site_lovetest&h=list'),
	'site_love_question'=>array('title'=>'测试题目','url'=>'index.php?action=site_lovequestion&h=list'),
	'site_love_type'=>array('title'=>'测试评分','url'=>'index.php?action=site_lovetype&h=list'),
	'site_media'=>array('title'=>'媒体关注','url'=>'index.php?action=site_media&h=list'),
	'site_love_clinic'=>array('title'=>'情感诊所','url'=>'index.php?action=site_loveclinic&h=list'),
	'site_diamond'=>array('title'=>'管理钻石会员','url'=>'index.php?action=site_diamond&h=list'),
	'site_story_list'=>array('title'=>'成功故事','url'=>'index.php?action=site_story&h=list'),
	'site_skin'=>array('title'=>'皮肤管理','url'=>'index.php?action=site_skin&h=list'),
	'site_recommend_diamond_list'=>array('title'=>'首页钻石会员推荐','url'=>'index.php?action=site_recommend_diamond&h=list'),
    'site_recommend_diamond_district'=>array('title'=>'首页钻石会员推荐（地区）','url'=>'index.php?action=site_recommend_diamond&h=district')
);

//测试管理
$leftMenu['hntest'] = array(
	'hntest_class_list'=>array('title'=>'测试分类',
							   'url'=>'index.php?action=hntest&h=class_list'),
	'hntest_question_list'=>array('title'=>'测试题目',
								  'url'=>'index.php?action=hntest&h=question_list'),
	'hntest_result_list'=>array('title'=>'测试结果',
								'url'=>'index.php?action=hntest&h=result_list'),
	
);


//我的用户
$leftMenu['myuser'] = array(
	'myuser_search'=>array('title'=>'用户搜索','url'=>'index.php?action=myuser&h=search'),
	'myuser_new_member'=>array('title'=>'新分客户','url'=>'index.php?action=myuser&h=new_member'),
	'myuser_continue_communication'=>array('title'=>'可以继续沟通','url'=>'index.php?action=myuser&h=continue_communication'),
	'myuser_have_need'=>array('title'=>'有急迫动机需求','url'=>'index.php?action=myuser&h=have_need'),
	'myuser_accept_service'=>array('title'=>'认可网站服务','url'=>'index.php?action=myuser&h=accept_service'),
	'myuser_accept_price'=>array('title'=>'认可价格','url'=>'index.php?action=myuser&h=accept_price'),
	'myuser_confirm_pay'=>array('title'=>'确定付款','url'=>'index.php?action=myuser&h=confirm_pay'),
	'myuser_destroy_order'=>array('title'=>'毁单','url'=>'index.php?action=myuser&h=destroy_order'),
	'myuser_reverse_member'=>array('title'=>'倒退会员','url'=>'index.php?action=myuser&h=reverse_member'),
	'myuser_give_up'=>array('title'=>'放弃会员','url'=>'index.php?action=myuser&h=give_up'),
	'myuser_high_member'=>array('title'=>'我的高级会员','url'=>'index.php?action=myuser&h=high_member'),
	'myuser_note_list'=>array('title'=>'最近联系会员','url'=>'index.php?action=myuser&h=note_list'),
    'myuser_goon_list'=>array('title'=>'重点跟进会员','url'=>'index.php?action=myuser&h=goon_list'),
	'myuser_remark'=>array('title'=>'客服盘库评语','url'=>'index.php?action=myuser&h=remark'),
	'myuser_record'=>array('title'=>'会员小记查询','url'=>'index.php?action=myuser&h=record')
);

//会部会员
$leftMenu['allmember'] = array(
	'allmember_advancesearch'=>array('title'=>'高级搜索','url'=>'index.php?action=allmember&h=advancesearch'),
	'allmember_general'=>array('title'=>'普通会员列表','url'=>'index.php?action=allmember&h=general'),
	'allmember_downgeneral'=>array('title'=>'VIP转为的普通会员','url'=>'index.php?action=allmember&h=downgeneral'),
	'allmember_high'=>array('title'=>'高级会员列表','url'=>'index.php?action=allmember&h=high'),
	'allmember_diamond'=>array('title'=>'钻石会员列表','url'=>'index.php?action=allmember&h=diamond'),
	'allmember_collect'=>array('title'=>'诚信会员列表','url'=>'index.php?action=allmember&h=collect'),
	// 'allmember_expireCollect'=>array('title'=>'是否过期诚信会员列表','url'=>'index.php?action=allmember&h=expireCollect'),
	'allmember_outsite'=>array('title'=>'外站加入会员','url'=>'index.php?action=allmember&h=outsite'),
	'allmember_alliance'=>array('title'=>'联盟会员列表','url'=>'index.php?action=allmember&h=alliance'),
	'allmember_class'=>array('title'=>'分类会员列表','url'=>'index.php?action=allmember&h=class'),
	'allmember_inside'=>array('title'=>'内部会员列表','url'=>'index.php?action=allmember&h=inside'),
	'allmember_samesearch'=>array('title'=>'相似会员搜索','url'=>'index.php?action=allmember&h=samesearch'),
	'allmember_nosid'=>array('title'=>'未分配VIP会员','url'=>'index.php?action=allmember&h=nosid'),
	'allmember_regnoallot_members'=>array('title'=>'未分配普通会员','url'=>'index.php?action=allmember&h=regnoallot_members'),
	'allmember_goodnoallot_members'=>array('title'=>'未分配优质会员','url'=>'index.php?action=allmember&h=goodnoallot_members'),
	'allmember_giveup'=>array('title'=>'删除的可回收会员','url'=>'index.php?action=allmember&h=giveup'),
	'allmember_notgiveup'=>array('title'=>'不可回收会员','url'=>'index.php?action=allmember&h=notgiveup'),
	'allmember_can_recovery'=>array('title'=>'可再分配优质会员','url'=>'index.php?action=allmember&h=can_recovery'),
	'allmember_uncontact'=>array('title'=>'联系不上的会员','url'=>'index.php?action=allmember&h=uncontact'),
    'allmember_excellent'=>array('title'=>'优质会员','url'=>'index.php?action=allmember&h=excellent&quicksearch=1'),
    'allmember_foo'=>array('title'=>'400查询','url'=>'index.php?action=allmember&h=foo'),
    'allmember_fooquery'=>array('title'=>'400回电查询','url'=>'index.php?action=allmember&h=fooquery')
);

//会员升级
$leftMenu['vipuser'] = array(
	//'vipuser_pay' => array('title'=>'已支付','url'=>'index.php?action=vipuser&h=pay'),
	//'vipuser_nopay' => array('title'=>'未支付','url'=>'index.php?action=vipuser&h=nopay'),
	//'vipuser_downline' => array('title'=>'线下支付','url'=>'index.php?action=vipuser&h=downline'),
	'vipuser_viped' =>array('title'=>'目前已到期的会员','url'=>'index.php?action=vipuser&h=viped'),
	'vipuser_high'=>array('title'=>'到期的高级会员','url'=>'index.php?action=vipuser&h=high'),
	'vipuser_diamond'=>array('title'=>'到期的钻石会员','url'=>'index.php?action=vipuser&h=diamond'),
	'vipuser_city_star' =>array('title'=>'到期的城市之星','url'=>'index.php?action=vipuser&h=city_star'),
	'vipuser_hurryhigh'=>array('title'=>'即将到期高级会员','url'=>'index.php?action=vipuser&h=hurryhigh'),
	'vipuser_hurrydiamond'=>array('title'=>'即将到期钻石会员','url'=>'index.php?action=vipuser&h=hurrydiamond'),
	'vipuser_upgrade_apply' => array('title'=>'会员升级申请','url'=>'index.php?action=vipuser&h=upgrade_apply'),
	'vipuser_apply_list' =>array('title'=>'升级支付列表','url'=>'index.php?action=vipuser&h=apply_list'),
	'vipuser_vip_summary' =>array('title'=>'会员升级汇总','url'=>'index.php?action=vipuser&h=vip_summary'),
	'vipuser_pay_other' =>array('title'=>'补款或预付','url'=>'index.php?action=vipuser&h=pay_other'),
	'vipuser_pay_query' =>array('title'=>'支付会员来源','url'=>'index.php?action=vipuser&h=pay_query'),
);
//系统管理
$leftMenu['system'] = array(
	'system_adminuser_list'=>array('title'=>'管理员列表','url'=>'index.php?action=system_adminuser&h=list'),
	'system_admingroup_list'=>array('title'=>'权限管理','url'=>'index.php?action=system_admingroup&h=list'),
	'system_adminaction_list'=>array('title'=>'所有操作','url'=>'index.php?action=system_adminaction&h=list'),
	'system_adminmanage_grouplist'=>array('title'=>'组管理','url'=>'index.php?action=system_adminmanage&h=grouplist'),
	'system_adminteam_teamlist'=>array('title'=>'队管理','url'=>'index.php?action=system_adminteam&h=teamlist'),
	'system_adminlog_list'=>array('title'=>'操作日志','url'=>'index.php?action=system_adminlog&h=list'),
	'system_adminuser_password'=>array('title'=>'修改密码','url'=>'index.php?action=system_adminuser&h=password'),
	'system_adminuser_kefucache'=>array('title'=>'生成客服缓存文件','url'=>'index.php?action=system_adminuser&h=kefucache'),
	'system_adminmembers_move'=>array('title'=>'会员批量转移','url'=>'index.php?action=system_adminmembers&h=move'),
	'system_story_clear'=>array('title'=>'成功故事首页', 'url'=>'index.php?action=site_story&h=list')
);
//其它管理
$leftMenu['other'] = array(
	'other_member_allot_record'=>array('title'=>'会员分配记录','url'=>'index.php?action=other&h=member_allot_record'),
	'other_member_allot_record_summary'=>array('title'=>'会员分配汇总','url'=>'index.php?action=other&h=member_allot_record_summary'),
	'other_delete_member'=>array('title'=>'删除会员','url'=>'index.php?action=other&h=delete_member'),
    'other_blacklist'=>array('title'=>'黑名单管理','url'=>'index.php?action=other&h=blacklist'),
	'other_giveup_member'=>array('title'=>'放弃会员列表','url'=>'index.php?action=other&h=giveup_member'),
	'other_smspre'=>array('title'=>'短信预设','url'=>'index.php?action=other&h=smspre_list'),
	'other_mmspre_list'=>array('title'=>'彩信预设','url'=>'index.php?action=other&h=mmspre_list'),
	'other_rightbottom_message'=>array('title'=>'右下角提醒','url'=>'index.php?action=other_rightbottom&h=message'),
	'other_rightbottom_sitemail' => array('title'=>'红娘站内邮件','url'=>'index.php?action=other_rightbottom&h=sitemail'),
	'other_members_transfer_list' => array('title'=>'升级会员交接资料','url'=>'index.php?action=other_members_transfer&h=list'),
	'other_set_del_user'=>array('title'=>'删除会员去向','url'=>'index.php?action=other&h=set_del_user'),
	'other_count_num_set'=>array('title'=>'客服联系会员组设置','url'=>'index.php?action=other&h=count_num_set'),
	'other_count_after_num'=>array('title'=>'管理员设置售后组','url'=>'index.php?action=other&h=count_after_num'),
	'other_sendmsm_most'=>array('title'=>'短信批量发送','url'=>'index.php?action=other&h=sendmsm_most'),
	'other_sendmsm'=>array('title'=>'短信发送','url'=>'index.php?action=other&h=sendmsm'),
	'other_sendmsg_record'=>array('title'=>'短信发送查询','url'=>'index.php?action=other&h=sendmsg_record'),
	'other_sendmail_batch'=>array('title'=>'邮件批量发送','url'=>'index.php?action=other&h=sendmail_batch'),
	'other_complaint_box'=>array('title'=>'红娘意见箱','url'=>'index.php?action=other&h=complaint_box'),
    'other_text_show'=>array('title'=>'文字轮播','url'=>'index.php?action=other&h=text_show'),
    'other_sendinfo'=>array('title'=>'秋波鲜花发送信息编辑','url'=>'index.php?action=other&h=sendinfo'),
	'other_update_fastdb'=>array('title'=>'更新指定fsatdb','url'=>'index.php?action=other&h=update_fastdb'),
	'other_add_rose'=>array('title'=>'鲜花赠送','url'=>'index.php?action=other&h=add_rose'),
	'other_white'=>array('title'=>'会员白名单','url'=>'index.php?action=other&h=white')
	
);
/*
$leftMenu['activity'] = array(
	'activity_member'=>array('title'=>'活动申请会员','url'=>'index.php?action=activity&h=activity_member'),
	'activity_kefu'=>array('title'=>'管理员查看客服','url'=>'index.php?action=activity&h=activity_kefu')	
);
 //红娘币
$leftMenu['matchmaker'] = array(
		'matchmaker_config_list'=>array('title'=>'红娘币奖罚设置','url'=>'index.php?action=matchmaker&h=config_list'),
		'matchmaker_want_pk'=>array('title'=>'红娘币PK','url'=>'index.php?action=matchmaker&h=want_pk'),
		'matchmaker_my_pk'=>array('title'=>'红娘币我的PK','url'=>'index.php?action=matchmaker&h=my_pk'),
		'matchmaker_pk_me'=>array('title'=>'红娘币我的挑战者','url'=>'index.php?action=matchmaker&h=pk_me'),
		'matchmaker_pk_list'=>array('title'=>'红娘币PK裁决','url'=>'index.php?action=matchmaker&h=pk_list'),
		'matchmaker_to_rewards'=>array('title'=>'红娘币奖励','url'=>'index.php?action=matchmaker&h=to_rewards'),
        'matchmaker_mylog'=>array('title'=>'我的红娘币','url'=>'index.php?action=matchmaker&h=mylog'),
        'matchmaker_reward_log'=>array('title'=>'红娘币奖罚明细','url'=>'index.php?action=matchmaker&h=reward_log'),
);
$leftMenu['lovestation'] = array(
    'lovestation_carfaq'=>array('title'=>'红娘来支招','url'=>'index.php?action=lovestation&h=carfaq'),
    'lovestation_carrelaxbar'=>array('title'=>'红娘休闲吧','url'=>'index.php?action=lovestation&h=carrelaxbar'),
	'lovestation_crazyfan' => array('title' => '疯狂粉丝会', 'url' => 'index.php?action=lovestation&h=crazyfan'),
	'lovestation_hnlove' => array('title' => '红娘爱车会', 'url' => 'index.php?action=lovestation&h=hnlove')

); */

