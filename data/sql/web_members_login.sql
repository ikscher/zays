set names utf8;

/*==============================================================*/
/* Table: web_members_login                                     */
/*==============================================================*/

/*创建正表*/
drop table if exists web_members_login;
create table web_members_login
(
   uid                  int(10) unsigned not null auto_increment comment 'ID',
   lastip               char(15) not null comment '最后一次登陆ip',
   lastvisit            int(11) unsigned not null default 0 comment '最后一次访问时间',
   last_login_time      int(11) unsigned not null default 0 comment '最后登录时间',
   login_meb            smallint(6) default 1 comment '记录会员实际登录次数 默认为1  伪造登录不计数',
   primary key (uid)
)
ENGINE = InnoDB  DEFAULT CHARSET=utf8;

alter table web_members_login comment '从用户表新建表
记录用户登陆信息，登陆IP,访问时间，登陆时间
在线统计放在memcache里存';

/*导入数据到临时表*/
insert into web_members_login(uid,lastip,lastvisit,last_login_time,login_meb)select uid,lastip,lastvisit,last_login_time,login_meb from web_members_op;


