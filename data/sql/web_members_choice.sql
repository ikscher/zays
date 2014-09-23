set names utf8;


/*==============================================================*/
/* Table: web_members_choice                                    */
/*==============================================================*/
/*创建正表*/

drop table if exists web_members_choice;

create table web_members_choice
(
   uid                  int(10) unsigned not null,
   gender               tinyint(1) unsigned not null default 0 comment '性别 (0是男，1是女)',
   age1                 tinyint(2) unsigned not null default 0 comment '择偶对象（最小年龄）0是不限',
   age2                 tinyint(2) unsigned not null default 0 comment '择偶最大年龄 0是不限',
   workprovince         int(10) unsigned not null default 0 comment '工作省份 0是不限 2是国外',
   workcity             int(10) unsigned not null default 0 comment '工作城市 0是不限',
   marriage             tinyint(1) unsigned not null default 0 comment '婚姻状况 0是不限 1未婚 3离异 4丧偶',
   education            tinyint(1) unsigned not null default 0 comment '学历 0是不限',
   salary               tinyint(1) unsigned not null default 0 comment '月收入 0是不限',
   children             tinyint(1) unsigned not null default 0 comment '有无孩子 0是不限 ',
   height1              smallint(3) unsigned not null default 0 comment '身高1 0是不限',
   height2              smallint(3) unsigned not null default 0 comment '身高2 0是不限',
   hasphoto             tinyint(1) unsigned not null default 0 comment '是否有照片 1有 2无',
   nature               tinyint(1) unsigned not null default 0 comment '择偶的对象的性格 0是不限 15是保密',
   body                 tinyint(1) unsigned not null default 0 comment '体型 0是不限 20是保密',
   weight1              tinyint(1) unsigned not null default 0 comment '体重1 0是不限',
   weight2              tinyint(1) unsigned not null default 0 comment '体重2 0是不限',
   occupation           tinyint(2) unsigned not null default 0 comment '职业 0是不限',
   hometownprovince     int(10) unsigned not null default 0 comment '籍贯省份 0是不限 2是国外',
   hometowncity         int(10) unsigned not null default 0 comment '籍贯城市 0是不限 ',
   nation               tinyint(2) unsigned not null default 0 comment '民族 0是不限',
   wantchildren         tinyint(1) unsigned not null default 0 comment '是否想要孩子 0是不限 1也许 2想要我喜欢孩子 3暂时不想要孩子 4以后再告诉你',
   drinking             tinyint(1) unsigned not null default 0 comment '是否喝酒 0都可以 1在意',
   smoking              tinyint(1) unsigned not null default 0 comment '是否抽烟 0都可以 1在意',
   updatetime           int(11) unsigned not null default 0 comment '会员更新choice表信息的时间',
   primary key (uid)
)
ENGINE = InnoDB  DEFAULT CHARSET=utf8;

/*创建临时副表*/
drop table if exists web_members_choice_tmp;

create table web_members_choice_tmp
(
   uid                  int(10) not null,
   gender               tinyint(1) not null default 0 comment '性别 (0是男，1是女)',
   age1                 tinyint(2) not null default 0 comment '择偶对象（最小年龄）0是不限',
   age2                 tinyint(2) not null default 0 comment '择偶最大年龄 0是不限',
   workprovince         int(10) not null default 0 comment '工作省份 0是不限 2是国外',
   workcity             int(10) not null default 0 comment '工作城市 0是不限',
   marriage             tinyint(1) not null default 0 comment '婚姻状况 0是不限 1未婚 3离异 4丧偶',
   education            tinyint(1) not null default 0 comment '学历 0是不限',
   salary               tinyint(1) not null default 0 comment '月收入 0是不限',
   children             tinyint(1) not null default 0 comment '有无孩子 0是不限 ',
   height1              smallint(3) not null default 0 comment '身高1 0是不限',
   height2              smallint(3) not null default 0 comment '身高2 0是不限',
   hasphoto             tinyint(1) not null default 0 comment '是否有照片 1有 2无',
   nature               tinyint(1) not null default 0 comment '择偶的对象的性格 0是不限 15是保密',
   body                 tinyint(1) not null default 0 comment '体型 0是不限 20是保密',
   weight1              tinyint(1) not null default 0 comment '体重1 0是不限',
   weight2              tinyint(1) not null default 0 comment '体重2 0是不限',
   occupation           tinyint(2) not null default 0 comment '职业 0是不限',
   hometownprovince     int(10) not null default 0 comment '籍贯省份 0是不限 2是国外',
   hometowncity         int(10) not null default 0 comment '籍贯城市 0是不限 ',
   nation               tinyint(2) not null default 0 comment '民族 0是不限',
   wantchildren         tinyint(1) not null default 0 comment '是否想要孩子 0是不限 1也许 2想要我喜欢孩子 3暂时不想要孩子 4以后再告诉你',
   drinking             tinyint(1) not null default 0 comment '是否喝酒 0都可以 1在意',
   smoking              tinyint(1) not null default 0 comment '是否抽烟 0都可以 1在意',
   updatetime           int(11) not null default 0 comment '会员更新choice表信息的时间',
   primary key (uid)
)
ENGINE = InnoDB  DEFAULT CHARSET=utf8;

/*导入数据到临时表*/
insert into web_members_choice_tmp(uid,age1,age2,workprovince,workcity,marriage,education,salary,children,height1,height2,hasphoto,nature,body,weight1,weight2,occupation,hometownprovince,hometowncity,nation,wantchildren,drinking,smoking,gender)select c.uid,c.age1,c.age2,c.workprovince,c.workcity,c.marriage,c.education,c.salary,c.children,c.height1,c.height2,c.hasphoto,c.nature,c.body,c.weight1,c.weight2,c.occupation,c.hometownprovince,c.hometowncity,c.nation,c.wantchildren,c.drinking,c.smoking,m.gender from web_choice_op as c left join web_members_op as m on m.uid=c.uid;

/*更新数据值*/
update web_members_choice_tmp set gender=2 where gender=1;
update web_members_choice_tmp set gender=1 where gender=0;
update web_members_choice_tmp set gender=0 where gender=2;
update web_members_choice_tmp set gender=0 where gender=-1;
update web_members_choice_tmp set age1=0 where age1=-1;
update web_members_choice_tmp set age2=0 where age2=-1;
update web_members_choice_tmp set workprovince=0 where workprovince=-1;
update web_members_choice_tmp set workprovince=2 where workprovince=-2;
update web_members_choice_tmp set workcity=0 where workcity=-1;
update web_members_choice_tmp set marriage=0 where marriage=-1;
update web_members_choice_tmp set education=0 where education=-1;
update web_members_choice_tmp set salary=0 where salary=-1;
update web_members_choice_tmp set children=0 where children=-1;
update web_members_choice_tmp set height1=0 where height1=-1;
update web_members_choice_tmp set height2=0 where height2=-1;
update web_members_choice_tmp set hasphoto=0 where hasphoto=-1;
update web_members_choice_tmp set nature=15 where nature=0;
update web_members_choice_tmp set nature=0 where nature=-1;
update web_members_choice_tmp set body=20 where body=0;
update web_members_choice_tmp set body=0 where body=-1;
update web_members_choice_tmp set weight1=0 where weight1=-1;
update web_members_choice_tmp set weight2=0 where weight2=-1;
update web_members_choice_tmp set occupation=0 where occupation=-1;
update web_members_choice_tmp set hometownprovince=0 where hometownprovince=-1;
update web_members_choice_tmp set hometownprovince=2 where hometownprovince=-2;
update web_members_choice_tmp set hometowncity=0 where hometowncity=-1;
update web_members_choice_tmp set nation=0 where nation=-1;
update web_members_choice_tmp set wantchildren=0 where wantchildren=-1;
update web_members_choice_tmp set smoking=0 where smoking=-1;


/*从临时表导数据到正表*/
insert into web_members_choice select * from web_members_choice_tmp;

/*删除临时表*/
drop table web_members_choice_tmp;