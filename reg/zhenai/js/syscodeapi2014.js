﻿//syscode统一接口

(function(){
	if(window.syscode){
		return;
	}
	var syscode=window.syscode={};

	syscode.buxian='-1,不限';

	syscode.qingxuanze='-1,请选择';
	
	syscode.qingxuanze2='0,请选择';
	
	syscode.lv = ["1,LV1","2,LV2","3,LV3","4,LV4","5,LV5","6,LV6","7,LV7"];

	syscode.expectlovedate=["1,1个月内","2,3个月内","3,半年内","4,一年及一年以上"];

	syscode.viewtype=["3,相册模式","2,资料模式"];

	syscode.orderby=["hpf,原始顺序","createtime,最新注册","age,年龄顺序"];

	syscode.searchheight=["1,155cm以下","2,155cm-160cm","3,160cm-165cm","4,165cm-170cm","5,170cm-175cm","6,175cm-180cm","20,180cm以上"];

	syscode.searchweight=["1,40kg以下","2,40kg－45kg","3,45kg－50kg","4,50kg－55kg","5,55kg－60kg","6,60kg－65kg","7,65kg－70kg","8,70kg－80kg","9,80kg以上"];
	//年份
	syscode.years=["1996","1995","1994","1993","1992","1991","1990","1989","1988","1987","1986","1985","1984","1983","1982","1981","1980","1979","1978","1977","1976","1975","1974","1973",
				   "1972","1971","1970","1969","1968","1967","1966","1965","1964","1963","1962","1961","1960","1959","1958","1957","1956","1955","1954","1953",
				   "1952","1951","1950","1949","1948","1947","1946","1945","1944","1943","1942","1941","1940","1939","1938","1937","1936","1935","1934","1933",
				   "1932","1931","1930","1929","1928","1927","1926","1925","1924","1923","1922","1921"];
	//月份
	syscode.months=["1","2","3","4","5","6","7","8","9","10","11","12"];
	//日期
	syscode.days=["1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30","31"];
	//投诉原因
	syscode.warnreason=["1,虚假照片","2,虚假信息","3,发送骚扰邮件","4,线下言行不检","99,其它原因"];
	//婚姻 new Array("-1,请选择","0,保留","1,未婚","2,已婚","3,离异","4,丧偶","5,分居");
	syscode.marriage=["1,未婚","3,离异","4,丧偶"];
	//教育程度  new Array("-1,请选择","1,初中","2,高中","3,中专","4,大专","5,大学","6,硕士","7,博士");
	syscode.education=["3,高中及以下","2,中专","4,大专","5,大学本科","6,硕士","7,博士"];
	//会员类型
	syscode.membertype=["1,普通会员","2,珍爱通会员"];
	//明星年龄段
	syscode.starage=["18-24,18-24","25-29,25-29","30-34,30-34","35-39,35-39","40-44,40-44","45-49,45-49","50-54,50-54","55-59,55-59","60-64,60-64","65-69,65-69","70-74,70-74","75-79,75-79","80-84,80-84","85-89,85-89","90-94,90-94","95-99,95-99"];
	//性别
	syscode.sex=["0,男士","1,女士"];
	//在意Ta喝酒吗
	syscode.drinking=["0,都可以","1,在意"];
	//在意Ta吸烟吗
	syscode.smoking=["0,都可以","1,在意"];
	//是否喝酒
	syscode.isDrinking  =["1,不喝酒","2,稍微喝一点","4,只在社交场合喝","3,喝得很多"];
	syscode.taIsDrinking=["1,不喝酒","2,稍微喝一点","4,只在社交场合喝","3,喝得很多"];
	//是否吸烟
	syscode.isSmoking  =["1,不吸烟","2,稍微抽一点儿","5,只在社交场合抽","3,抽得很多"];
	syscode.taIsSmoking=["1,不吸烟","2,稍微抽一点儿","5,只在社交场合抽","3,抽得很多"];
	//月薪
	syscode.salary=["1,1000元以下","2,1001-2000元","3,2001-3000元","4,3001-5000元","5,5001-8000元","6,8001-10000元","7,10001-20000元","8,20001-50000元","9,50000元以上"];
	syscode.salary2=["101,1000元","102,2000元","103,3000元","104,5000元","105,8000元","106,10000元","107,20000元","108,50000元"];
	//血型
	syscode.bloodtype=["1,A型","2,B型","3,AB型","4,O型","5,不确定"];
	//民族
	syscode.stock=["1,汉族","2,藏族","3,朝鲜族","4,蒙古族","5,回族","6,满族","7,维吾尔族","8,壮族","9,彝族","10,苗族","11,侗族","12,瑶族","13,白族","14,布依族","15,傣族","16,京族","17,黎族","18,羌族","19,怒族","20,佤族","21,水族","22,畲族","23,土族","24,阿昌族","25,哈尼族","26,高山族","27,景颇族","28,珞巴族","29,锡伯族","30,德昂(崩龙)族","31,保安族","32,基诺族","33,门巴族","34,毛南族","35,赫哲族","36,裕固族","37,撒拉族","38,独龙族","39,普米族","40,仫佬族","41,仡佬族","42,东乡族","43,拉祜族","44,土家族","45,纳西族","46,傈僳族","47,布朗族","48,哈萨克族","49,达斡尔族","50,鄂伦春族","51,鄂温克族","52,俄罗斯族","53,塔塔尔族","54,塔吉克族","55,柯尔克孜族","56,乌兹别克族","57,国外"];
	//信仰
	syscode.belief  =["2,不信教","5,佛教","12,道教","8,伊斯兰教","11,基督教","6,天主教","3,儒家门徒","1,不可知论者","13,其他宗教"];
	syscode.taBelief=["2,不信教","5,佛教","12,道教","8,伊斯兰教","11,基督教","6,天主教","3,儒家门徒","1,不可知论者","13,其他宗教"];
	//生肖
	syscode.animals=["0,不限","1,鼠","2,牛","3,虎","4,兔","5,龙","6,蛇","7,马","8,羊","9,猴","10,鸡","11,狗","12,猪"];
	//星座
	syscode.constellation=["1,白羊座(03.21-04.20)","2,金牛座(04.21-05.20)","3,双子座(05.21-06.21)","4,巨蟹座(06.22-07.22)","5,狮子座(07.23-08.22)","6,处女座(08.23-09.22)","7,天秤座(09.23-10.22)","8,天蝎座(10.23-11.21)","9,射手座(11.22-12.21)","10,魔羯座(12.22-01.19)","11,水瓶座(01.20-02.19)","12,双鱼座(02.20-03.20)"];

	syscode.occupation=['100,销售','200,客户服务','300,计算机/互联网','400,通信/电子','500,生产/制造','600,物流/仓储','700,商贸/采购','800,人事/行政','900,高级管理','1000,广告/市场','1100,传媒/艺术','1200,生物/制药','1300,医疗/护理','1400,金融/银行/保险','1500,建筑/房地产','1600,咨询/顾问','1700,法律','1800,财会/审计','1900,教育/科研','2000,服务业','2100,交通运输','2200,政府机构','2300,军人/警察','2400,农林牧渔','2500,自由职业','2600,在校学生','2700,待业','2800,其他行业'];
	syscode.occupationbt=['100,销售','200,客户服务','300,计算机','400,通信/电子','500,生产/制造','600,物流/仓储','700,商贸/采购','800,人事/行政','900,高级管理','1000,广告/市场','1100,传媒/艺术','1200,生物/制药','1300,医疗/护理','1400,金融/保险','1500,建筑/地产','1600,咨询/顾问','1700,法律','1800,财会/审计','1900,教育/科研','2000,服务业','2100,交通运输','2200,政府机构','2300,军人/警察','2400,农林牧渔','2500,自由职业','2600,在校学生','2700,待业','2800,其他行业'];	
	syscode.occupation2=['101,销售总监','102,销售经理','103,销售主管','104,销售专员','105,渠道/分销管理','106,渠道/分销专员','107,经销商','108,客户经理','109,客户代表','110,其他','201,客服经理','202,客服主管','203,客服专员','204,客服协调','205,客服技术支持','206,其他','301,IT技术总监','302,IT技术经理','303,IT工程师','304,系统管理员','305,测试专员','306,运营管理','307,网页设计','308,网站编辑','309,网站产品经理','310,其他','401,通信技术','402,电子技术','403,其他','501,工厂经理','502,工程师','503,项目主管','504,营运经理','505,营运主管','506,车间主任','507,物料管理','508,生产领班','509,操作工人','510,安全管理','511,其他','601,物流经理','602,物流主管','603,物流专员','604,仓库经理','605,仓库管理员','606,货运代理','607,集装箱业务','608,海关事物管理','609,报单员','610,快递员','611,其他','701,商务经理','702,商务专员','703,采购经理','704,采购专员','705,外贸经理','706,外贸专员','707,业务跟单','708,报关员','709,其他','801,人事总监','802,人事经理','803,人事主管','804,人事专员','805,招聘经理','806,招聘专员','807,培训经理','808,培训专员','809,秘书','810,文员','811,后勤','812,其他','901,总经理','902,副总经理','903,合伙人','904,总监','905,经理','906,总裁助理','907,其他','1001,广告客户经理','1002,广告客户专员','1003,广告设计经理','1004,广告设计专员','1005,广告策划','1006,市场营销经理','1007,市场营销专员','1008,市场策划','1009,市场调研与分析','1010,市场拓展','1011,公关经理','1012,公关专员','1013,媒介经理','1014,媒介专员','1015,品牌经理','1016,品牌专员','1017,其他','1101,主编','1102,编辑','1103,作家','1104,撰稿人','1105,文案策划','1106,出版发行','1107,导演','1108,记者','1109,主持人','1110,演员','1111,模特','1112,经纪人','1113,摄影师','1114,影视后期制作','1115,设计师','1116,画家','1117,音乐家','1118,舞蹈','1119,其他','1201,生物工程','1202,药品生产','1203,临床研究','1204,医疗器械','1205,医药代表','1206,化工工程师','1207,其他','1301,医疗管理','1302,医生','1303,心理医生','1304,药剂师','1305,护士','1306,兽医','1307,其他','1401,投资','1402,保险','1403,金融','1404,银行','1405,证券','1406,其他','1501,建筑师','1502,工程师','1503,规划师','1504,景观设计','1505,房地产策划','1506,房地产交易','1507,物业管理','1508,其他','1601,专业顾问','1602,咨询经理','1603,咨询师','1604,培训师','1605,其他','1701,律师','1702,律师助理','1703,法务经理','1704,法务专员','1705,知识产权专员','1706,其他','1801,财务总监','1802,财务经理','1803,财务主管','1804,会计','1805,注册会计师','1806,审计师','1807,税务经理','1808,税务专员','1809,成本经理','1810,其他','1901,教授','1902,讲师/助教','1903,中学教师','1904,小学教师','1905,幼师','1906,教务管理人员','1907,职业技术教师','1908,培训师','1909,科研管理人员','1910,科研人员','1911,其他','2001,餐饮管理','2002,厨师','2003,餐厅服务员','2004,酒店管理','2005,大堂经理','2006,酒店服务员','2007,导游','2008,美容师','2009,健身教练','2010,商场经理','2011,零售店店长','2012,店员','2013,保安经理','2014,保安人员','2015,家政服务','2016,其他','2101,飞行员','2102,空乘人员','2103,地勤人员','2104,列车司机','2105,乘务员','2106,船长','2107,船员','2108,司机','2109,其他','2201,公务员','2202,其他'];

	//公司类别
	syscode.corptype =["1,政府机关","2,事业单位","4,外资企业","10,合资企业","6,国营企业","7,私营企业","8,自有公司","9,其他"];
	//住房条件
	syscode.house  =["1,和家人同住","2,已购房","3,租房","4,打算婚后购房","6,单位宿舍"];
	syscode.taHouse=["1,和家人同住","2,已购房","3,租房","4,打算婚后购房","6,单位宿舍"];
	
	//购房方式
	syscode.houseBuyWay  =["1,全款","2,贷款"];

	//购房每月还款金额
	syscode.buyHouseRepay  =["1,2000以下","2,2000-4000","3,4000-6000","4,6000-8000","5,8000以上"];

	//购车每月还款金额
	syscode.buyCarRepay  =["1,2000以下","2,2000-4000","3,4000-6000","4,6000-8000","5,8000以上"];

	//是否购车
	syscode.vehicle  =["1,已买车","2,未买车"];
	syscode.taVehicle=["1,已买车","2,未买车"];

	//购车方式
	syscode.vehicleBuyWay  =["1,全款","2,贷款"];

	//是否有孩子
	syscode.children   =["1,没有","3,有，我们住在一起","4,有，我们偶尔一起住","5,有，但不在身边"];
	syscode.taChildren =["1,没有","3,有孩子且住在一起","4,有孩子且偶尔会一起住","5,有孩子但不在身边"];
	//是否想要孩子
	syscode.wantChildren  =["2,想要孩子","3,不想要孩子","1,视情况而定"];
	syscode.taWantChildren=["2,希望对方要孩子","3,希望对方不要孩子","1,视情况而定"];
	//兄弟姐妹
	syscode.family =["1,独生子女","2,2","3,3","4,4","5,5","6,6","7,7","8,8"];
	//年龄
	syscode.age =["18,18","19,19","20,20","21,21","22,22","23,23","24,24","25,25","26,26","27,27","28,28","29,29","30,30","31,31","32,32","33,33","34,34","35,35","36,36","37,37","38,38","39,39","40,40","41,41","42,42","43,43","44,44","45,45","46,46","47,47","48,48","49,49","50,50","51,51","52,52","53,53","54,54","55,55","56,56","57,57","58,58","59,59","60,60","61,61","62,62","63,63","64,64","65,65","66,66","67,67","68,68","69,69","70,70","71,71","72,72","73,73","74,74","75,75","76,76","77,77","78,78","79,79","80,80","81,81","82,82","83,83","84,84","85,85","86,86","87,87","88,88","89,89","90,90","91,91","92,92","93,93","94,94","95,95","96,96","97,97","98,98","99,99"];
	//身高
	syscode.height =['129,130以下','130,130','131,131','132,132','133,133','134,134','135,135','136,136','137,137','138,138','139,139','140,140','141,141','142,142','143,143','144,144','145,145','146,146','147,147','148,148','149,149','150,150','151,151','152,152','153,153','154,154','155,155','156,156','157,157','158,158','159,159','160,160','161,161','162,162','163,163','164,164','165,165','166,166','167,167','168,168','169,169','170,170','171,171','172,172','173,173','174,174','175,175','176,176','177,177','178,178','179,179','180,180','181,181','182,182','183,183','184,184','185,185','186,186','187,187','188,188','189,189','190,190','191,191','192,192','193,193','194,194','195,195','196,196','197,197','198,198','199,199','200,200','201,201','202,202','203,203','204,204','205,205','206,206','207,207','208,208','209,209','210,210','211,210以上'];
	//体重
	syscode.weight=['29,30以下','30,30','31,31','32,32','33,33','34,34','35,35','36,36','37,37','38,38','39,39','40,40','41,41','42,42','43,43','44,44','45,45','46,46','47,47','48,48','49,49','50,50','51,51','52,52','53,53','54,54','55,55','56,56','57,57','58,58','59,59','60,60','61,61','62,62','63,63','64,64','65,65','66,66','67,67','68,68','69,69','70,70','71,71','72,72','73,73','74,74','75,75','76,76','77,77','78,78','79,79','80,80','81,81','82,82','83,83','84,84','85,85','86,86','87,87','88,88','89,89','90,90','91,91','92,92','93,93','94,94','95,95','96,96','97,97','98,98','99,99','100,100','101,101','102,102','103,103','104,104','105,105','106,106','107,107','108,108','109,109','110,110','111,111','112,112','113,113','114,114','115,115','116,116','117,117','118,118','119,119','120,120','121,121','122,122','123,123','124,124','125,125','126,126','127,127','128,128','129,129','130,130','131,130以上'];
	//男体形
	syscode.body0  =["1,一般","2,瘦长","3,运动员型","4,比较胖","5,体格魁梧","10,壮实","0,保密"];
//	syscode.body0  =["1,一般","12,偏瘦","11,修长","3,健壮","4,偏胖","5,魁梧"];
	syscode.taBody0=["0,保留","1,一般","2,瘦长","3,运动员型","4,比较胖","5,体格魁梧","10,矮壮结实"];
	//女体形
	syscode.body1  =["1,一般","2,瘦长","6,苗条","7,高大美丽","8,丰满","9,富线条美","0,保密"];
//	syscode.body1  =["1,一般","6,苗条","7,高挑","8,丰满","13,匀称","9,富线条美"];
	syscode.taBody1=["0,保留","1,一般","2,瘦长","6,苗条","7,高大美丽","8,丰满","9,富线条美"];	

	//喜爱的活动
	syscode.fondactions=["1,电脑/网络","2,电子游戏","4,与朋友喝酒","5,品茗","6,看电影/电视","7,听音乐","8,乐器演奏","9,烹调","10,摄影","11,下棋/打牌","12,观光旅游","13,逛街购物","14,阅读","15,写作","16,舞会/卡拉OK","18,各种收集活动","19,理财/投资","22,文艺表演","23,聊天","24,家务/手工艺","25,书法/绘画","27,其他"];
	//喜爱的体育运动
	syscode.fondsports=["1,足球","2,排球","3,篮球","4,骑单车/摩托车","5,乒乓球","6,保龄球","7,健身/跑步","8,钓鱼","9,游泳/冲浪/潜水","10,网球","11,羽毛球","12,高尔夫","13,滑冰/滑雪","14,其他"];
	//喜爱的音乐
	syscode.fondmusics=["1,中文流行音乐","2,轻音乐","3,民族音乐","4,老歌","5,舞曲","6,歌剧","7,西部乡村","8,英文流行音乐","9,交响乐","10,地方戏曲","11,摇滚","12,另类","13,灵歌","14,爵士/蓝调","15,其他"];
	//喜爱的影视节目
	syscode.fondprograms=["1,故事片","2,文艺爱情","3,科幻","4,动作武侠","5,侦探推理","6,实验电影","7,老电影","8,限制级电影","9,儿童/卡通片","10,纪录片","11,西部电影","12,恐怖","13,得奖电影","14,艺术电影","15,音乐歌舞","16,肥皂剧","17,喜剧","18,其他","19,什么都看"];
	//喜爱的食物
	syscode.fondfoods=["1,川菜","2,江浙菜","3,湘菜","4,火锅","5,烧烤","6,家常菜","7,路边摊","8,健康食品","9,零食","10,粤菜","11,北方菜","12,面食","13,素食","14,甜食","15,自助餐","16,减肥餐","17,能填饱肚子就好","18,其他"];
	//喜爱的地方
	syscode.fondplaces=["1,都市","2,村庄","3,小镇","4,山区","5,办公室/学校","6,游乐场","7,购物中心","8,我家/我的房间","9,海滨","10,岛屿","11,沙漠","12,雪野","13,图书馆/书店","14,展览馆","15,宗教圣地","16,古迹","17,森林","18,公园","19,咖啡厅/酒吧","20,动物园","21,夜市赶集","22,各种俱乐部","23,其他"];
	//语言能力
	syscode.tonguegifts=["1,汉语普通话","2,粤语","3,汉语其他方言","4,少数民族语言","5,英语","6,其他国语言"];
	//基本生活外的最大消费
	syscode.consumption=['1,美食','2,服饰','3,化妆','4,健身','5,娱乐','6,旅行','7,社交','8,文化','9,自我提升','10,其他'];
	//存款
	syscode.deposit=['1,3万以下','2,3-10万','3,10-50万','4,50万以上','5,保密'];
	//厨艺
	syscode.cuisine=['1,色香味俱全','2,能做几样可口的小菜','3,有待提高'];
	//家务
	syscode.housework=['1,愿承担大部分家务','2,希望对方承担大部分家务','3,看各自忙闲，协商分担家务'];
	//是否愿与对方父母同住
	syscode.liveWithParentsInLaw=['1,愿意','2,不愿意','3,视具体情况而定'];
	//婚后是否与自己父母同住
	syscode.liveWithParents=['1,与自己父母同住','2,不与自己父母同住','3,尊重伴侣意见','4,视具体情况而定'];
	//父母状况
	syscode.parents=['1,父母均健在','2,只有母亲健在','3,只有父亲健在','4,父母均已离世'];

	//父母经济状况
	syscode.parentIncomeState=['1,已退休，无经济压力','2,无经济来源需要赡养'];

	//兄弟姐妹数
	syscode.siblingsCount=['1,独生子女','2,2','3,3','4,4','5,5','6,6','7,7','8,8','9,更多'];

	//兄弟姐妹状况
	syscode.siblingsIncomeState=['1,需要经济支持','2,无经济压力'];

	//喜欢的约会场所/活动
	syscode.fonddate=['1,餐厅','2,茶楼','3,咖啡厅','4,酒吧','5,看电影','6,看演出','7,看展览','8,逛街','9,公园散步','10,郊游','11,户外运动','12,其它'];
	//何时结婚
	syscode.marryDate=['1,认同闪婚','2,一年内','3,两年内','4,三年内','5,时机成熟就结婚'];
	//宠物偏好
	syscode.keepPets=['1,没有，不愿意养','2,没有，愿意养','3,有'];
	//宠物
	syscode.pets=['1,狗','2,猫','3,鱼','4,兔','5,鸟','6,乌龟','7,蜥蜴','8,鼠','9,蛇','10,另类宠物'];

	//工作繁忙度
	syscode.workBusyLevel=['1,有双休','2,工作忙碌','3,工作清闲','4,自由工作时间','5,经常出差'];

	//婚恋史
	syscode.marriageHistory=['1,未谈过恋爱','2,谈过3次以内恋爱','3,恋爱次数很多'];

	//分手原因
	syscode.breakUpReason=['1,性格不合','2,对方没有上进心','3,其它'];

	//最长时间的一次恋爱
	syscode.longestLoveTime=['1,1年内','2,2年','3,3年','4,3年以上'];

	//单身原因
	syscode.singleReason=['1,生活圈子小 周围没找到合适的','2,上一段感情受伤','3,其它'];

	//征婚原因
	syscode.marriageSeekingReason=['1,年龄到了，岁月不等人','2,家人的期望，父母操心','3,生育的问题要及早考虑','4,身边的朋友差不多都结婚了','5,其他'];

	//结婚要不要彩礼
	syscode.isNeedBetrothalGifts=['1,根据习俗象征性的要点','2,必须要彩礼','3,不需要','4,协商'];

	//是否有处女（处男）情结
	syscode.isVirginComplexForMale=['1,对方必须是处女（处男）','2,不在意'];

	//是否有处女情结
	syscode.isVirginComplexForMale=['1,对方必须是处女','2,不在意'];

	//是否有处男情结
	syscode.isVirginComplexForFemale=['1,对方必须是处男','2,不在意'];

	//是否有生肖忌讳
	syscode.isChineseZodiacTaboo=['1,不想找属xx的','2,没有'];
 	
 	//谈过几次恋爱
 	syscode.loveDetail = ['1,0次','2,1次','3,2~3次','4,3次以上'];

 	//兄弟姐妹情况
 	syscode.siblingsDetail = ['1,哥哥','2,姐姐','3,弟弟','4,妹妹'];

 	//购车计划
 	syscode.carPlan = ['1,目前已有购车计划','2,暂无购车计划'];
	//生成下拉框
	/*
	* classStyle 样式
	* id select的id名称
	* name select的name名称
	* realValue 当前选项，用于数据回填
	* arrayObj 对应的数组
	* title 下拉框标题
	* event 选择框改变事件
	* isDisabled 是否禁用
	*/
	syscode.selectHtml=function(classStyle,id,name,realValue,defaultValue,options,title,event,isDisabled){
		if(event){
			if(isDisabled){
				var html='<select class="'+classStyle+'" id="'+id+'" name="'+name+'" onchange="'+event+'(this.value);return false;" disabled="disabled">';
			}else{
				var html='<select class="'+classStyle+'" id="'+id+'" name="'+name+'" onchange="'+event+'(this.value);return false;">';
			}
		}else{
			if(isDisabled){
				var html='<select class="'+classStyle+'" id="'+id+'" name="'+name+'" disabled="disabled">';
			}else{
				var html='<select class="'+classStyle+'" id="'+id+'" name="'+name+'">';
			}
			
		}
		if(title){
			var selected = '';
			var option = title.split(",");
			if(realValue!=null&&realValue!=''&&realValue==option[0]){
				selected = ' selected="selected"';
			}else if(defaultValue!=null&&defaultValue!=''&&defaultValue==option[0]){
					selected = ' selected="selected"';
			}
			html = html + '<option value="'+option[0]+'"'+selected+'>'+option[1]+'</option>';
		}
		for(var i=0;i<options.length;i++){
			var selected = '';
			var option = options[i].split(",");
			if(realValue!=null&&realValue!=''){
				if(realValue==option[0]){
					selected = ' selected="selected"';
				}
			}else if(defaultValue!=null&&defaultValue!=''&&defaultValue==option[0]){
					selected = ' selected="selected"';
			}
			html = html + '<option value="'+option[0]+'"'+selected+'>'+option[1]+'</option>';
		}
		html = html + '</select>';
		return html;
	};

	syscode.select=function(classStyle,id,name,realValue,defaultValue,options,title,event,isDisabled){
		document.write(syscode.selectHtml(classStyle,id,name,realValue,defaultValue,options,title,event,isDisabled));
	};

	/*添加下拉列表选项
	* text 显示的文本内容
	* value 选项值
	* obj 列表框
	*/
	syscode.addOption=function(text,value,select){
		var option = document.createElement("option");
		option.appendChild(document.createTextNode(text));
		option.setAttribute("value",value);
		select.appendChild(option);
	};

	//生成年份下拉框
	syscode.yearsSelect=function(classStyle,id,name,realValue){
		var html = '<select  class="'+classStyle+'" id="'+id+'" name="'+name+'">';
		html = html + '<option value="-1">请选择</option>';
		for(var i=0;i<syscode.years.length;i++){
			var selected = '';
			if(realValue!=null&&realValue!=''&&realValue==syscode.years[i]){
				selected = 'selected="selected"';
			}
			html = html + '<option value="'+syscode.years[i]+'" '+selected+'>'+syscode.years[i]+'</option>';
		}
		html = html + '</select>';
		document.write(html);
	}

	//生成月份下拉框
	syscode.monthsSelect=function(classStyle,id,name,realValue){
		var html = '<select  class="'+classStyle+'" id="'+id+'" name="'+name+'">';
		html = html + '<option value="-1">请选择</option>';
		for(var i=0;i<syscode.months.length;i++){
			var selected = '';
			if(realValue!=null&&realValue!=''&&realValue==syscode.months[i]){
				selected = 'selected="selected"';
			}
			html = html + '<option value="'+syscode.months[i]+'" '+selected+'>'+syscode.months[i]+'</option>';
		}
		html = html + '</select>';
		document.write(html);
	}
	//生成日期day下拉框
	syscode.daysSelect=function(classStyle,id,name,realValue){
		var html = '<select  class="'+classStyle+'" id="'+id+'" name="'+name+'">';
		html = html + '<option value="-1">请选择</option>';
		for(var i=0;i<syscode.days.length;i++){
			var selected = '';
			if(realValue!=null&&realValue!=''&&realValue==syscode.days[i]){
				selected = 'selected="selected"';
			}
			html = html + '<option value="'+syscode.days[i]+'" '+selected+'>'+syscode.days[i]+'</option>';
		}
		html = html + '</select>';
		document.write(html);
	}

	syscode.uncheckBuxian=function(thiz,buxian) {
		if(thiz.checked){
			buxian.checked=false;
		}
	}

	syscode.uncheckBuxian2=function(thiz,buxian) {
		if(thiz.checked){
			buxian.checked=false;
		}
		var oo=$(thiz);
		var id=oo.attr('id');
		var temp = new Array();
		temp = id.split("_");		
		var len = $("input[name="+temp[0]+"]:checked").size();
		if(len==0){
			$('#personal_'+temp[0]).removeClass('darrow current').addClass('darrow');
			document.getElementById(temp[0]+"_-1").checked="checked";
		}
	}

	syscode.checkBuxian=function(buxian,values) {
		if(buxian.checked){
			for(var j = 0; j<values.length;j++) {
				if(values.eq(j).attr('id')!=buxian.id){
					values.eq(j).removeAttr('checked');
				}
			}
		}
	}

	syscode.checkboxHtml=function(id,name,realValue,arrayObj,wrapperTagName,title){
		wrapperTagName=wrapperTagName||'li';
		var html = '';
		for(var i=0;i<arrayObj.length;i++){
			var selected = '';
			var valueArray = arrayObj[i].split(",");
			if(realValue!=null&&realValue!=''&&realValue.indexOf(","+valueArray[0]+",")>-1){
				selected = 'checked="checked"';
			}
			var onclick='';
			if(title){
				var titleArray=title.split(",");
				onclick="syscode.uncheckBuxian(this,document.getElementById('"+id+"_"+titleArray[0]+"'))";
			}
			html = html + '<'+wrapperTagName+'><input id="'+id+'_'+valueArray[0]+'" name="'+name+'" type="checkbox" value="'+valueArray[0]+'" onclick="'+onclick+'" '+selected+'/><label for="'+id+'_'+valueArray[0]+'">'+valueArray[1]+'</label></'+wrapperTagName+'>';
		}
		if(title){
			var valueArray=title.split(",");
			html+='<'+wrapperTagName+'><input id="'+id+'_'+valueArray[0]+'" name="'+name+'" type="checkbox" value="'+valueArray[0]+'" onclick="syscode.checkBuxian(this,$(\'input[name='+name+']\'));" /><label for="'+id+'_'+valueArray[0]+'">'+valueArray[1]+'</label></'+wrapperTagName+'>';
		}
		return html;
	}
	syscode.checkboxHtmlInZQ=function(id,name,realValue,arrayObj,wrapperTagName,wrapperTagClassName,className,title){
		wrapperTagName=wrapperTagName||'li';
		var html = '';
		for(var i=0;i<arrayObj.length;i++){
			var selected = '';
			var valueArray = arrayObj[i].split(",");
			if(realValue!=null&&realValue!=''&&realValue.indexOf(","+valueArray[0]+",")>-1){
				selected = 'checked="checked"';
			}
			var onclick='';
			if(title){
				var titleArray=title.split(",");
				onclick="syscode.uncheckBuxian(this,document.getElementById('"+id+"_"+titleArray[0]+"'))";
			}
			html = html + '<'+wrapperTagName+' class="'+wrapperTagClassName+'"><input class="'+className+'" id="'+id+'_'+valueArray[0]+'" name="'+name+'" type="checkbox" value="'+valueArray[0]+'" onclick="'+onclick+'" '+selected+'/>'+valueArray[1]+'</'+wrapperTagName+'>';
		}
		if(title){
			var valueArray=title.split(",");
			html+='<'+wrapperTagName+' class="'+wrapperTagClassName+'"><input class="'+className+'" '+id+'_'+valueArray[0]+'" name="'+name+'" type="checkbox" value="'+valueArray[0]+'" onclick="syscode.checkBuxian(this,$(\'input[name='+name+']\'));" />'+valueArray[1]+'</'+wrapperTagName+'>';
		}
		return html;
	}


	syscode.checkBuxian2=function(buxian,values,o) {
		if(buxian.checked){
			for(var j = 0; j<values.length;j++) {
				if(values.eq(j).attr('id')!=buxian.id){
					values.eq(j).removeAttr('checked');
				}
			}
			$('#'+o+"_title").remove();
			$('#personal_'+o).removeClass('current darrow').addClass('darrow');
		}
	}

	syscode.checkboxHtml2=function(id,name,realValue,arrayObj,wrapperTagName,title,itemname){
		wrapperTagName=wrapperTagName||'li';
		var html = '';
		var flag = false;
		for(var i=0;i<arrayObj.length;i++){
			var selected = '';
			var valueArray = arrayObj[i].split(",");
				if((realValue!=null&&realValue!=''&&realValue.indexOf(","+valueArray[0]+",")>-1)){
					selected = 'checked="checked"';
				}
			var onclick='';
			if(title){
				var titleArray=title.split(",");
				onclick="syscode.uncheckBuxian2(this,document.getElementById('"+id+"_"+titleArray[0]+"'))";
			}
			html = html + '<'+wrapperTagName+'><input id="'+id+'_'+valueArray[0]+'" name="'+name+'" type="checkbox" value="'+valueArray[0]+'" onclick="'+onclick+';addCondition(\''+itemname+'\',\''+valueArray[0]+'\',\''+valueArray[1]+'\');" '+selected+'/><label for="'+id+'_'+valueArray[0]+'">'+valueArray[1]+'</label></'+wrapperTagName+'>';
		}
		if(title){
			var valueArray=title.split(",");
			html+='<'+wrapperTagName+'><input id="'+id+'_'+valueArray[0]+'" name="'+name+'" type="checkbox" value="'+valueArray[0]+'" onclick="syscode.checkBuxian2(this,$(\'input[name='+name+']\'),\''+itemname+'\');" '+selected+' /><label for="'+id+'_'+valueArray[0]+'">'+valueArray[1]+'</label></'+wrapperTagName+'>';
		}
		return html;
	}	

	//生成checkbox
	/*
	* id select的id名称
	* name select的name名称
	* realValue 当前选项
	* arrayObj 对应的数组
	*/
	syscode.checkbox=function(id,name,realValue,arrayObj,wrapperTagName,title){
		document.write(syscode.checkboxHtml(id,name,realValue,arrayObj,wrapperTagName,title));
	}
	
	syscode.checkbox2=function(id,name,realValue,arrayObj,wrapperTagName,title,itemname){
		document.write(syscode.checkboxHtml2(id,name,realValue,arrayObj,wrapperTagName,title,itemname));
	}
	syscode.occupationSelectHtml=function(classStyle, id, name, occupationId, realValue, defaultValue, title) {
		var html = '<select class="'+classStyle+'" id="'+id+'" name="'+name+'" onchange=\'syscode.changeOccupation2Select(this.value,"'+occupationId+'","'+title+'");\'>';
		if(title){
			var selected = '';
			var option = title.split(",");
			if(realValue&&realValue==option[0]){
				selected = ' selected="selected"';
			}
			html = html + '<option value="'+option[0]+'"'+selected+'>'+option[1]+'</option>';
		}
		for (var i = 0; i < syscode.occupation.length; i++) {
			var selected = "";
			var option = syscode.occupation[i].split(",");
			if(realValue && realValue != '-1') {
				if (realValue-realValue%100 == option[0]) {
						selected = ' selected="selected"';
				}
			} else if (defaultValue && defaultValue != "-1") {
				if (defaultValue-defaultValue%100 == option[0]) {
					selected = ' selected="selected"';
				}
			}
			html = html + '<option value="' + option[0] + '"'+selected+'>' + option[1] + '</option>';
		}
		html = html + "</select>";
		return html;
	};

	syscode.occupationSelect=function(classStyle, id, name, occupationId, realValue, defaultValue, title){
		document.write(occupationSelectHtml(classStyle, id, name, occupationId, realValue, defaultValue, title));
	}

	syscode.changeOccupation2Select=function(value,occupation2Id,title){
		if(!occupation2Id){
			return;
		}
		var occupation2 = document.getElementById(occupation2Id);
		if(!occupation2){
			return;
		}
		if(value==-1){
			if(occupation2.options[0].value==-1||occupation2.options[0].value%100==0){
				occupation2.options.length=1;
				occupation2.options[0].value='-1';
			}else{
				occupation2.options.length=1;
				occupation2.options[0].value='-1';
				occupation2.options[0].value='';
			}
			return;
		}
		if(occupation2.options[0].value==-1||occupation2.options[0].value%100==0){
			occupation2.options.length=1;
			occupation2.options[0].value=value-value%100;
		}else{
			occupation2.options.length=0;
		}
		for (var i = 0; i < syscode.occupation2.length; i++) {
			var option = syscode.occupation2[i].split(",");
			if (option[0]-option[0]%100 == value) {
				syscode.addOption(option[1],option[0],occupation2);
			}
		}
	}

	syscode.occupation2SelectHtml=function(classStyle,id,name,realValue,defaultValue,title){
		var html = '<select class="'+classStyle+'" id="'+id+'" name="'+name+'">';
		if(title){
			var selected = '';
			var option = title.split(",");
			if(realValue&&realValue==option[0]){
				selected = ' selected="selected"';
			}else if(realValue){
				option[0]=realValue-realValue%100;
			}
			html = html + '<option value="'+option[0]+'"'+selected+'>'+option[1]+'</option>';
		}
		if(!realValue||realValue == '-1'){
			html = html + '</select>';
			return html;
		}
		for(var i=0;i<syscode.occupation2.length;i++){
			var selected = '';
			var option = syscode.occupation2[i].split(",");
			if(realValue&&realValue!='-1'){
				if(realValue==option[0]){
					selected = ' selected="selected"';
				}
				if(option[0]-option[0]%100==realValue-realValue%100){
					html += '<option value="'+option[0]+'"'+selected+'>'+option[1]+'</option>';
				}
			}else if(defaultValue&&defaultValue!='-1'){
				if(realValue==option[0]){
					selected = ' selected="selected"';
				}
				if(option[0]-option[0]%100==realValue-realValue%100){
					html += '<option value="'+option[0]+'"'+selected+'>'+option[1]+'</option>';
				}
			}
		}
		html = html + '</select>';
		return html;
	};

	syscode.occupation2Select=function(classStyle,id,name,realValue,defaultValue,title){
		document.write(occupation2SelectHtml(classStyle,id,name,realValue,defaultValue,title));
	}

	if ( typeof module === "object" && module && typeof module.exports === "object" ) {
   		module.exports = syscode;
	} else {
	    if ( typeof define === "function" ) {
	        define(function () { return syscode; } );
	    }
	}
   // if ( typeof define === "function" && define.amd) {
	//	define(function () { return syscode; } );
	//}

}());