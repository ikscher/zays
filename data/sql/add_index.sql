/*更新members_recommend的城市值*/
update `web_members_recommend` set province=0 where province=-1;
update `web_members_recommend` set province=2 where province=-2;
update `web_members_recommend` set city=0 where city=-1;

/*更新vote_member城市值*/
update `vote_member` set region_city=0 where region_city=-1;

/*更新validuser_id的城市值*/
update `web_validuser_id` set province=2 where province=-2;
update `web_validuser_id` set province=0 where province=-1;
update `web_validuser_id` set city=0 where city=-1;

/*更新dating的城市值*/
update `web_dating` set dating_province=2 where dating_province=-2;
update `web_dating` set dating_province=0 where dating_province=-1;
update `web_dating` set dating_city=0 where dating_city=-1;

/*更新cooperation的城市值*/
update `web_cooperation` set province=2 where province=-2;
update `web_cooperation` set province=0 where province=-1;
update `web_cooperation` set city=0 where city=-1;

/*更新activity的城市值*/
update `web_activity` set province=2 where province=-2;
update `web_activity` set province=0 where province=-1;
update `web_activity` set city=0 where city=-1;

/*更新ahtv_reguser的城市值*/
update `web_ahtv_reguser` set province=2 where province=-2;
update `web_ahtv_reguser` set province=0 where province=-1;
update `web_ahtv_reguser` set city=0 where city=-1;


/*更改payment_new里的old_scid值*/
update web_payment_new set old_scid=10 where old_scid=-1;
update web_payment_new set old_scid=20 where old_scid=0;
update web_payment_new set old_scid=30 where old_scid=1;
update web_payment_new set old_scid=40 where old_scid=2;



ALTER table `web_service_contact` add INDEX sendtime(`sendtime`);
ALTER TABLE `web_members_action` ADD INDEX uid(`uid`);

/*member_admininfo*/
ALTER TABLE `web_member_admininfo` ADD INDEX effect_grade(`effect_grade`);
ALTER TABLE `web_member_admininfo` ADD INDEX dateline(`dateline`);

/*members_search*/
ALTER TABLE `web_members_search` ADD INDEX updatetime(`updatetime`);
ALTER TABLE `web_members_search` ADD INDEX sid(`sid`);
ALTER TABLE `web_members_search` ADD INDEX s_cid(`s_cid`);
ALTER TABLE `web_members_search` ADD INDEX usertype(`usertype`);
ALTER TABLE `web_members_search` ADD INDEX bgtime(`bgtime`);
ALTER TABLE `web_members_search` ADD INDEX endtime(`endtime`);
ALTER TABLE `web_members_search` ADD INDEX city_star(`city_star`);
ALTER TABLE `web_members_search` ADD INDEX images_ischeck(`images_ischeck`);
alter table `web_members_search` add index province(`province`);
alter table `web_members_search` add index city(`city`);

/*members_introduce*/
ALTER TABLE `web_members_introduce` ADD INDEX introduce_pass(`introduce_pass`);

/*members_login*/
ALTER TABLE `web_members_login` ADD INDEX lastvisit(`lastvisit`);

/*member_choice*/
ALTER TABLE `web_members_choice` ADD INDEX updatetime(`updatetime`);

/*member_backinfo*/
ALTER TABLE `web_member_backinfo` ADD INDEX dateline(`dateline`);

/*members_bind*/
alter table `web_members_bind` add index a_uid(`a_uid`);

/*web_certification*/
alter table `web_certification` add index toshoot_voice_check(`toshoot_voice_check`);