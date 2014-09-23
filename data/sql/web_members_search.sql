/*web_members_search*/

set names utf8;


/*删除表*/
drop table if exists web_members_search;

/*==============================================================*/
/* Table: web_members_search                                    */
/*==============================================================*/
create table web_members_search
(
   uid                  int(10) unsigned not null auto_increment comment 'ID',
   nickname             char(12) not null comment '昵称',
   username             char(40) not null comment '用户名',
   nickname2            char(25) not null comment '处理过的昵称',
   telphone             bigint(11) unsigned not null comment '手机',
   password             char(32) not null comment '密码',
   truename             char(20) not null comment '真实姓名',
   gender               tinyint(1) unsigned not null default 0 comment '性别 (0是男，1是女)',
   birthyear            smallint(4) unsigned not null default 0 comment '出生年',
   province             int(10) unsigned not null default 0 comment '工作省份 0是未选 2国外',
   city                 int(10) unsigned not null default 0 comment '工作城市 0是未选',
   marriage             tinyint(1) unsigned not null default 1 comment '婚姻状况 1未婚 3离异 4丧偶',
   education            tinyint(1) unsigned not null default 0 comment '学历0是未选',
   salary               tinyint(1) unsigned not null default 0 comment '月收入 0是未选',
   house                tinyint(1) unsigned not null default 0 comment '住房条件 0是未选',
   children             tinyint(1) unsigned not null default 0 comment '有没有孩子0是未选',
   height               smallint(3) unsigned not null default 0 comment '身高 0是未选',
   bgtime               int(11) unsigned not null default 0 comment '高级会员开始时间',
   s_cid                tinyint(2) unsigned not null default 40 comment '用户权限({10,铂金会员},{20,钻石会员}{30,高级会员}{40，普通会员}{50,红娘})',
   images_ischeck       tinyint(1) unsigned default 0 comment '会员的形象照是否通过审核，1为通过，2为未通过，0为暂无',
   is_lock              tinyint(1) unsigned default 1 comment '是否封锁用户 默认为1 不封锁',
   pic_num              mediumint(9) unsigned not null default 0 comment '图片总数',
   city_star            int(10) unsigned not null default 0 comment '是否是城市之星,非0表示是,存的是加入时间',
   certification        smallint(4) unsigned not null default 0 comment '诚信值',
   weight               int(10) unsigned not null default 0 comment '体重 0是未选',
   body                 tinyint(1) unsigned not null default 0 comment '体型 0是未选 20保密',
   animalyear           smallint(2) unsigned not null default 0 comment '生肖 0是未选',
   constellation        tinyint(1) unsigned not null default 0 comment '星座 0是未选',
   bloodtype            tinyint(1) unsigned not null default 0 comment '血型 0是未选',
   hometownprovince     int(10) unsigned not null default 0 comment '籍贯省份 0是未选 2是国外',
   hometowncity         int(10) unsigned not null default 0 comment '籍贯城市 0是未选',
   nation               tinyint(1) unsigned not null default 0 comment '民族 0是未选',
   religion             smallint(2) unsigned not null default 0 comment '宗教信仰 0是未选 15保密',
   family               tinyint(1) unsigned not null default 0 comment '兄弟姐妹 0是未选',
   language             char(15) not null comment '语言能力',
   smoking              tinyint(1) unsigned not null default 0 comment '是否吸烟 0是未选 5是保密',
   drinking             tinyint(1) unsigned not null default 0 comment '是否饮酒  0是未选 5是保密',
   occupation           smallint(2) unsigned not null default 0 comment '职业  0是未选',
   vehicle              tinyint(1) unsigned not null default 0 comment '是否有车  0是未选 3是保密',
   corptype             tinyint(1) unsigned not null default 0 comment '工作单位   0是未选',
   wantchildren         tinyint(1) unsigned not null default 0 comment '是否想要孩子  0是未选',
   usertype             tinyint(1) unsigned not null default 1 comment '会员种类{1:本站注册,2:外\r\n\r\n站 加入,3:英才会员（采集）,4:联盟会员,5:内部会员（客服自己注册的）}',
   regdate              int(10) unsigned not null default 0 comment '注册日期',
   sid                  smallint(4) unsigned default 0 comment '该用户客服的id号',
   is_well_user         tinyint(1) unsigned not null default 0 comment '是否是优质会员,1:是,0:否',
    is_vote              tinyint(1) unsigned not null default 0 comment '是否推荐为一眼定情  1代表推荐',
   showinformation      tinyint(1) unsigned not null default 1 comment '用户的资料是否允许其他会员查看1为允许，0为不允许',
   endtime              int(11) unsigned not null default 0 comment '高级会员结束时间',
   updatetime           int(11) unsigned not null default 0 comment '会员更新search表信息时间',
   primary key (uid)
)
ENGINE = InnoDB  DEFAULT CHARSET=utf8;

alter table web_members_search comment '存储用户部分个人信息，这此信息字段主要用于搜索显示，并用于sphinx生成索引数据的来源表，从sphinx搜索出来的数据';


/*创建临时副表*/
drop table if exists web_members_search_tmp;
/*==============================================================*/
/* Table: web_members_search                                    */
/*==============================================================*/
create table web_members_search_tmp
(
   uid                  int(10)  not null comment 'ID',
   nickname             char(12) not null comment '昵称',
   username             char(40) not null comment '用户名',
   nickname2            char(25) not null comment '处理过的昵称',
   telphone             bigint(11)  not null comment '手机',
   password             char(32) not null comment '密码',
   truename             char(20) not null comment '真实姓名',
   gender               tinyint(1)  not null default 0 comment '性别 (0是男，1是女)',
   birthyear            smallint(4)  not null default 0 comment '出生年',
   province             int(10)  not null default 0 comment '工作省份 0是未选 2国外',
   city                 int(10)  not null default 0 comment '工作城市 0是未选',
   marriage             tinyint(1)  not null default 1 comment '婚姻状况 1未婚 3离异 4丧偶',
   education            tinyint(1)  not null default 0 comment '学历0是未选',
   salary               tinyint(1)  not null default 0 comment '月收入 0是未选',
   house                tinyint(1)  not null default 0 comment '住房条件 0是未选',
   children             tinyint(1)  not null default 0 comment '有没有孩子0是未选',
   height               smallint(3)  not null default 0 comment '身高 0是未选',
   bgtime               int(11)  not null default 0 comment '高级会员开始时间',
   s_cid                tinyint(2) not null default 40 comment '用户权限({10,铂金会员},{20,钻石会员}{30,高级会员}{40，普通会员}{50,红娘})',
   images_ischeck       tinyint(1)  default 0 comment '会员的形象照是否通过审核，1为通过，2为未通过，0为暂无',
   is_lock              tinyint(1)  default 1 comment '是否封锁用户 默认为1 不封锁',
   pic_num              mediumint(9)  not null default 0 comment '图片总数',
   city_star            int(10)  not null default 0 comment '是否是城市之星,非0表示是,存的是加入时间',
   certification        smallint(4)  not null default 0 comment '诚信值',
   weight               int(10)  not null default 0 comment '体重 0是未选',
   body                 tinyint(1)  not null default 0 comment '体型 0是未选 20保密',
   animalyear           smallint(2)  not null default 0 comment '生肖 0是未选',
   constellation        tinyint(1)  not null default 0 comment '星座 0是未选',
   bloodtype            tinyint(1)  not null default 0 comment '血型 0是未选',
   hometownprovince     int(10)  not null default 0 comment '籍贯省份 0是未选 2是国外',
   hometowncity         int(10)  not null default 0 comment '籍贯城市 0是未选',
   nation               tinyint(1)  not null default 0 comment '民族 0是未选',
   religion             smallint(2)  not null default 0 comment '宗教信仰 0是未选 15保密',
   family               tinyint(1)  not null default 0 comment '兄弟姐妹 0是未选',
   language             char(15) not null comment '语言能力',
   smoking              tinyint(1)  not null default 0 comment '是否吸烟 0是未选 5是保密',
   drinking             tinyint(1)  not null default 0 comment '是否饮酒  0是未选 5是保密',
   occupation           smallint(2)  not null default 0 comment '职业  0是未选',
   vehicle              tinyint(1)  not null default 0 comment '是否有车  0是未选 3是保密',
   corptype             tinyint(1)  not null default 0 comment '工作单位   0是未选',
   wantchildren         tinyint(1)  not null default 0 comment '是否想要孩子  0是未选',
   usertype             tinyint(1)  not null default 1 comment '会员种类{1:本站注册,2:外\r\n\r\n站 加入,3:英才会员（采集）,4:联盟会员,5:内部会员（客服自己注册的）}',
   regdate              int(10)  not null default 0 comment '注册日期',
   sid                  smallint(4)  default 0 comment '该用户客服的id号',
    is_vote              tinyint(1)  not null default 0 comment '是否推荐为一眼定情  1代表推荐',
   showinformation      tinyint(1)  not null default 1 comment '用户的资料是否允许其他会员查看1为允许，0为不允许',
   is_well_user         tinyint(1)  not null default 0 comment '是否是优质会员,1:是,0:否',
   endtime              int(11)  not null default 0 comment '高级会员结束时间',
   updatetime           int(11)  not null default 0 comment '会员更新search表信息时间',
   primary key (uid)
)
ENGINE = InnoDB  DEFAULT CHARSET=utf8;

/*导入数据到临时表*/
insert into web_members_search_tmp(uid,nickname,username,nickname2,telphone,password,truename,gender,birthyear,province,city,marriage,education,salary,house,children,height,bgtime,s_cid,images_ischeck,is_lock,pic_num,city_star,certification,weight,body,animalyear,constellation,bloodtype,hometownprovince,hometowncity,nation,religion,family,language,smoking,drinking,occupation,vehicle,corptype,wantchildren,usertype,regdate,sid,is_well_user,endtime,is_vote)select m.uid,nickname,username,nickname2,telphone,password,truename,gender,birthyear,province,city,marriage,education,salary,house,children,height,bgtime,s_cid,images_ischeck,is_lock,pic_num,city_star,certification,weight,body,animalyear,constellation,bloodtype,hometownprovince,hometowncity,nation,religion,family,language,smoking,drinking,occupation,vehicle,corptype,wantchildren,usertype,regdate,sid,is_well_user,endtime,is_vote from web_members_op as m left join web_memberfield_op as f on m.uid=f.uid;


/*更新数据值*/
update web_members_search_tmp set gender=0 where gender=-1;
update web_members_search_tmp set birthyear=0 where birthyear=-1;
update web_members_search_tmp set province=0 where province=-1;
update web_members_search_tmp set province=2 where province=-2;
update web_members_search_tmp set city=0 where city=-1;
update web_members_search_tmp set marriage=0 where marriage=-1;
update web_members_search_tmp set education=0 where education=-1;
update web_members_search_tmp set salary=0 where salary=-1;
update web_members_search_tmp set house=0 where house=-1;
update web_members_search_tmp set children=0 where children=-1;
update web_members_search_tmp set height=0 where height=-1;
update web_members_search_tmp set s_cid=10 where s_cid=-1;
update web_members_search_tmp set s_cid=20 where s_cid= 0;
update web_members_search_tmp set s_cid=30 where s_cid= 1;
update web_members_search_tmp set s_cid=40 where s_cid= 2;
update web_members_search_tmp set s_cid=50 where s_cid= 3;
update web_members_search_tmp set images_ischeck=0 where images_ischeck=-1;
update web_members_search_tmp set images_ischeck=2 where images_ischeck= 0;
update web_members_search_tmp set pic_num=0 where pic_num=-1;
update web_members_search_tmp set city_star=0 where city_star=-1;
update web_members_search_tmp set certification=0 where certification=-1;
update web_members_search_tmp set weight=0 where weight=-1;
update web_members_search_tmp set body=20 where body=0;
update web_members_search_tmp set body=0 where body=-1;
update web_members_search_tmp set animalyear=0 where animalyear=-1;
update web_members_search_tmp set constellation=0 where constellation=-1;
update web_members_search_tmp set bloodtype=0 where bloodtype=-1;
update web_members_search_tmp set hometownprovince=0 where hometownprovince=-1;
update web_members_search_tmp set hometownprovince=2 where hometownprovince=-2;
update web_members_search_tmp set hometowncity=0 where hometowncity=-1;
update web_members_search_tmp set nation=0 where nation=-1;
update web_members_search_tmp set religion=15 where religion=0;
update web_members_search_tmp set religion=0 where religion=-1;
update web_members_search_tmp set family=0 where family=-1;
update web_members_search_tmp set smoking=5 where smoking=0;
update web_members_search_tmp set smoking=0 where smoking=-1;
update web_members_search_tmp set drinking=5 where drinking=0;
update web_members_search_tmp set drinking=0 where drinking=-1;
update web_members_search_tmp set occupation=0 where occupation=-1;
update web_members_search_tmp set vehicle=0 where vehicle=-1;
update web_members_search_tmp set corptype=0 where corptype=-1;
update web_members_search_tmp set wantchildren=0 where wantchildren=-1;
update web_members_search_tmp set sid=0 where sid=-1;
update web_members_search_tmp set is_well_user=0 where is_well_user=-1;
update web_members_search_tmp set is_vote=0 where is_vote=-1;

/*从临时表导数据到正表*/
insert into web_members_search select * from web_members_search_tmp;

/*删除临时表*/
drop table web_members_search_tmp;