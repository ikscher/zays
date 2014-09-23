set names utf8;

/*==============================================================*/
/* Table: web_members_base                                      */
/*==============================================================*/

/*创建正表*/
drop table if exists web_members_base;

create table web_members_base
(
   uid                  int(10) unsigned not null comment 'ID',
   nature               tinyint(1) unsigned not null comment '会员性格 0是未选  15是保密',
   callno               char(16) not null comment '电话',
   regip                char(15) not null comment '注册ip',
   tastetime            int(10) unsigned not null default 0 comment '体验三天到期时间',
   birth                int(11) unsigned not null default 0 comment '出生日期',
   oldsex               tinyint(1) unsigned not null default 0 comment '多久找到对象 0是未选',
   mainimg              char(100) not null comment '形象照原图',
   rosenumber           int(4) unsigned not null default 3 comment '默认用户有3多鲜花可以送给别人',
   contact_num          int(1) unsigned not null default 0 comment '我的委托一天不超过3次',
   contact_time         int(11) unsigned not null default 0 comment '发送委托的时间',
   pic_date             char(10) not null comment '生成的图片日期',
   pic_name             char(50) not null comment '生成图片的名字',
   automatic            int(4) unsigned not null default 0 comment '是否自动登录',
   showinformation_val  tinyint(1) unsigned not null default 0 comment '用户关闭资料的理由',
   grade                tinyint(1) unsigned not null default 2 comment '成为会员的可能性,及电话是否接通',
   puid                 int(11) unsigned not null default 0 comment '推荐人UID ',
   website              varchar(50) not null comment '来源注册网站',
   is_phone             tinyint(1) unsigned not null default 1 comment '是否开启在会员收到消息是短信通知 1代表通知',
   is_awoke             tinyint(1) unsigned not null default 0 comment '是够上线提醒 1：是；0：否；',
   source               varchar(255) not null comment '会员来源地（站点）',
   allotdate            int(11) unsigned not null comment '分配客服时的日期',
   bind_id              int(8) unsigned not null comment '绑定对方的ID',
   isbind               smallint(2) unsigned not null comment '是否绑定,（1为绑定）',
   userGrade            tinyint(1) unsigned not null default 0 comment '1:A级别，2：AA级别，3：AAA级别',
   finishschool         varchar(30) not null comment '毕业院校',
   isschool             tinyint(1) unsigned not null comment '0,未通过，1,通过',
   fondfood             varchar(60) character set latin1 not null comment '喜欢的食物',
   fondplace            varchar(60) character set latin1 not null comment '喜欢的地方',
   fondactivity         varchar(60) character set latin1 not null comment '喜欢的活动',
   fondsport            varchar(60) character set latin1 not null comment '喜欢的体育运动',
   fondmusic            varchar(60) character set latin1 not null comment '喜欢的音乐',
   fondprogram          varchar(60) character set latin1 not null comment '喜欢的影视节目',
   blacklist            text not null comment '屏蔽会员列表',
   qq                   varchar(13) not null comment '会员qq号',
   msn                  varchar(50) not null comment '会员msn号',
   currentprovince      int(10) unsigned not null default 0 comment '目前所在地的省',
   currentcity          int(10) unsigned not null default 0 comment '目前所在地的市',
   friendprovince       varchar(200) not null comment '期望交友的地区',
   skin                 char(20) not null comment '皮肤名称',
   primary key (uid)
)
ENGINE = InnoDB  DEFAULT CHARSET=utf8;

alter table web_members_base comment '用户基本信息表';


/*创建有符号副表*/
drop table if exists web_members_base_tmp;

create table web_members_base_tmp
(
   uid                  int(10) not null comment 'ID',
   nature               tinyint(1) not null comment '会员性格 0是未选  15是保密',
   callno               char(16) not null comment '电话',
   regip                char(15) not null comment '注册ip',
   tastetime            int(10) not null default 0 comment '体验三天到期时间',
   birth                int(11) not null default 0 comment '出生日期',
   oldsex               tinyint(1) not null default 0 comment '多久找到对象 0是未选',
   mainimg              char(100) not null comment '形象照原图',
   rosenumber           int(4) not null default 3 comment '默认用户有3多鲜花可以送给别人',
   contact_num          int(1) not null default 0 comment '我的委托一天不超过3次',
   contact_time         int(11) not null default 0 comment '发送委托的时间',
   pic_date             char(10) not null comment '生成的图片日期',
   pic_name             char(50) not null comment '生成图片的名字',
   automatic            int(4) not null default 0 comment '是否自动登录',
   showinformation_val  tinyint(1) not null default 0 comment '用户关闭资料的理由',
   grade                tinyint(1) not null default 2 comment '成为会员的可能性,及电话是否接通',
   puid                 int(11) not null default 0 comment '推荐人UID ',
   website              varchar(50) not null comment '来源注册网站',
   is_phone             tinyint(1) not null default 1 comment '是否开启在会员收到消息是短信通知 1代表通知',
   is_awoke             tinyint(1) not null default 0 comment '是够上线提醒 1：是；0：否；',
   source               varchar(255) not null comment '会员来源地（站点）',
   allotdate            int(11) not null comment '分配客服时的日期',
   bind_id              int(8) not null comment '绑定对方的ID',
   isbind               smallint(2) not null comment '是否绑定,（1为绑定）',
   userGrade            tinyint(1) not null default 0 comment '1:A级别，2：AA级别，3：AAA级别',
   finishschool         varchar(30) not null comment '毕业院校',
   isschool             tinyint(1) not null comment '0,未通过，1,通过',
   fondfood             varchar(60) character set latin1 not null comment '喜欢的食物',
   fondplace            varchar(60) character set latin1 not null comment '喜欢的地方',
   fondactivity         varchar(60) character set latin1 not null comment '喜欢的活动',
   fondsport            varchar(60) character set latin1 not null comment '喜欢的体育运动',
   fondmusic            varchar(60) character set latin1 not null comment '喜欢的音乐',
   fondprogram          varchar(60) character set latin1 not null comment '喜欢的影视节目',
   blacklist            text not null comment '屏蔽会员列表',
   qq                   varchar(13) not null comment '会员qq号',
   msn                  varchar(50) not null comment '会员msn号',
   currentprovince      int(10) not null default 0 comment '目前所在地的省',
   currentcity          int(10) not null default 0 comment '目前所在地的市',
   friendprovince       varchar(200) not null comment '期望交友的地区',
   skin                 char(20) not null comment '皮肤名称',
   primary key (uid)
)
ENGINE = InnoDB  DEFAULT CHARSET=utf8;

/*导入数据到副表*/
insert into web_members_base_tmp(uid,nature,callno,regip,tastetime,birth,oldsex,mainimg,rosenumber,contact_num,contact_time,pic_date,pic_name,automatic,showinformation_val,grade,puid,website,is_phone,is_awoke,source,allotdate,bind_id,isbind,userGrade,finishschool,isschool,fondfood,fondplace,fondactivity,fondsport,fondmusic,fondprogram,blacklist,qq,msn,currentprovince,currentcity,friendprovince,skin)select m.uid as uid,nature,callno,regip,tastetime,unix_timestamp(concat(birthyear,'/',birthmonth,'/',birthday)) as birth,oldsex,mainimg,rosenumber,contact_num,contact_time,pic_date,pic_name,automatic,showinformation_val,grade,puid,website,is_phone,is_awoke,source,allotdate,bind_id,isbind,userGrade,finishschool,isschool,fondfood,fondplace,fondactivity,fondsport,fondmusic,fondprogram,blacklist,qq,msn,currentprovince,currentcity,friendprovince,skin from web_members_op as m left join web_memberfield_op as f on m.uid=f.uid;

/*更新副表的中值*/
update web_members_base_tmp set nature=15 where nature=0;
update web_members_base_tmp set nature=0 where nature=-1;
update web_members_base_tmp set tastetime=0 where tastetime=-1;
update web_members_base_tmp set oldsex=0 where oldsex=-1;
update web_members_base_tmp set contact_num=0 where contact_num=-1;
update web_members_base_tmp set automatic=0 where automatic=-1;
update web_members_base_tmp set grade=0 where grade=-1;
update web_members_base_tmp set puid=0 where puid=-1;
update web_members_base_tmp set is_phone=0 where is_phone=-1;
update web_members_base_tmp set is_awoke=0 where is_awoke=-1;
update web_members_base_tmp set allotdate=0 where allotdate=-1;
update web_members_base_tmp set bind_id=0 where bind_id=-1;
update web_members_base_tmp set isbind=0 where isbind=-1;
update web_members_base_tmp set userGrade=0 where userGrade=-1;
update web_members_base_tmp set isschool=0 where isschool=-1;
update web_members_base_tmp set currentprovince=0 where currentprovince=-1;
update web_members_base_tmp set currentprovince=2 where currentprovince=-2;
update web_members_base_tmp set currentcity=0 where currentcity=-1;

/*数据从副表导入正表*/
insert into web_members_base select * from web_members_base_tmp;

/*删除副表*/
drop table web_members_base_tmp;
