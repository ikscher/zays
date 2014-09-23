set names utf8;

/*==============================================================*/
/* Table: web_members_introduce                                 */
/*==============================================================*/

/*创建正表*/
drop table if exists web_members_introduce;
create table web_members_introduce
(
   uid                  int(10) unsigned not null default 0 comment '用户id',
   introduce            text not null comment '内心独白',
   introduce_check      text comment '审核之前的内心独白',
   introduce_pass       tinyint(1) unsigned not null default 0 comment '判断是内容是否为空标识，方便快速联合查询',
   primary key (uid)
)
ENGINE = InnoDB  DEFAULT CHARSET=utf8;

alter table web_members_introduce comment '存储用户的内心独白
更改了introduce_pass的状态对应值';

/*导入数据到表*/
insert into web_members_introduce(uid,introduce,introduce_check,introduce_pass)select m.uid,introduce,introduce_check,introduce_pass from web_members_op as m left join web_choice_op as f on m.uid=f.uid;