
//喜爱的活动
var fondactions = new Array("0,请选择","1,电脑/网络","2,电子游戏","3,体育运动","4,饮酒","5,品茗","6,看电影/电视","7,听音乐","8,弹琴","9,烹调","10,摄影/艺术创作","11,下棋/打牌","12,观光旅游","13,逛街购物","14,阅读","15,写作","16,舞会/卡拉OK","17,养宠物","18,各种收集活动","19,储蓄/投资","20,健身/武术","21,水上活动","22,文艺表演","23,聊天","24,家事/手工艺","25,书法/绘画","26,溜冰/滑雪","27,其他");
//喜爱的体育运动
var fondsports = new Array("0,请选择","1,足球","2,排球","3,篮球","4,骑单车/摩托车","5,乒乓球","6,保龄球","7,健身/跑步","8,钓鱼","9,游泳","10,网球","11,羽毛球","12,高尔夫","13,滑冰/滑雪","14,其他");
//喜爱的音乐
var fondmusics = new Array("0,请选择","1,中文流行音乐","2,轻音乐","3,民族音乐","4,老歌","5,舞曲","6,歌剧","7,西部乡村","8,英文流行音乐","9,交响乐","10,地方戏曲","11,摇滚","12,另类","13,灵歌","14,爵士/蓝调","15,其他");
//喜爱的影视节目
var fondprograms = new Array("0,请选择","1,故事片","2,文艺爱情","3,科幻","4,动作武侠","5,侦探推理","6,实验电影","7,老电影","8,限制级电影","9,儿童/卡通片","10,纪录片","11,西部电影","12,恐怖","13,得奖电影","14,艺术电影","15,音乐歌舞","16,肥皂剧","17,喜剧","18,其他","19,什么都看");
//喜爱的食物
var fondfoods = new Array("0,请选择","1,川菜","2,江浙菜","3,湘菜","4,火锅","5,烧烤","6,家常菜","7,路边摊","8,健康食品","9,零食","10,粤菜","11,北方菜","12,面食","13,素食","14,甜食","15,自助餐","16,减肥餐","17,能填饱肚子就好","18,其他");
//喜爱的地方
var fondplaces = new Array("0,请选择","1,都市","2,村庄","3,小镇","4,山区","5,办公室/学校","6,游乐场","7,购物中心","8,我家/我的房间","9,海滨","10,岛屿","11,沙漠","12,雪野","13,图书馆/书店","14,展览馆","15,宗教圣地","16,古迹","17,森林","18,公园","19,咖啡厅/酒吧","20,动物园","21,夜市赶集","22,各种俱乐部","23,其他");
//语言能力
var tonguegifts = new Array("0,请选择","1,汉语普通话","2,粤语","3,汉语其他方言","4,少数民族语言","5,英语","6,其他国语言");

//day(日期)
var days = new Array("1","2","3","4","5","6","7","8","9","10",
					 	"11","12","13","14","15","16","17","18","19","20",
					 		"21","22","23","24","25","26","27","28","29","30","31");
//生成日期day下拉框
function getDaysSelect(classStyle,id,name,realValue,defaultValue){
	var htmlStr = '<select  class="'+classStyle+'" id="'+id+'" name="'+name+'">';
	htmlStr = htmlStr + '<option value="0">请选择</option>';
	for(i=0;i<days.length;i++){
		var selected = '';
		if(realValue!=null&&realValue!=''&&realValue==days[i]){
			selected = 'selected="selected"';
		}else if(realValue==null||realValue==''){
			if(defaultValue==days[i]){
				selected = 'selected="selected"';
			}
		}
		htmlStr = htmlStr + '<option value="'+days[i]+'" '+selected+'>'+days[i]+'</option>';
	}
	htmlStr = htmlStr + '</select>';
	document.write(htmlStr);
}

//投诉原因
var warnreason = new Array("-1,请选择您的举报原因","1,虚假照片","2,虚假信息","3,发送骚扰邮件","4,线下言行不检","99,其它原因");

//婚姻 new Array("-1,请选择","0,保留","1,未婚","2,已婚","3,离异","4,丧偶","5,分居");
var marriage = new Array("0,请选择","1,未婚","3,离异","4,丧偶");
var marriagebuxian = new Array("0,不限","1,未婚","3,离异","4,丧偶");

//教育程度  new Array("0,请选择","1,初中","2,高中","3,中专","4,大专","5,大学","6,硕士","7,博士"); 
var education = new Array("0,请选择","3,高中及以下","4,大专","5,大学本科","6,硕士","7,博士"); 
var educationbuxian = new Array("0,不限","3,高中及以下","4,大专","5,大学本科","6,硕士","7,博士"); 

//会员类型
var membertype = new Array("-1,请选择","1,普通会员","2,红娘通会员");

//性别
var sex = new Array("-1,请选择","0,男士","1,女士");

//在意Ta喝酒吗
var drinking = new Array("-1,请选择","0,都可以","1,在意");
var drinkingbuxian = new Array("-1,不限","0,都可以","1,在意");

//在意Ta吸烟吗
var smoking = new Array("-1,请选择","0,都可以","1,在意");
var smokingbuxian = new Array("-1,不限","0,都可以","1,在意");

//是否喝酒
var isDrinking = new Array("0,请选择","1,不喝酒","2,稍微喝一点/社交场合喝","3,喝的很凶","5,保密");

//是否吸烟
var isSmoking = new Array("0,请选择","1,不吸烟","2,稍微抽一点儿","3,抽的很凶","4,抽雪茄/烟斗","5,保密");

//月薪
var salary1 = new Array("0,请选择","1,1000元以下","2,1001-2000元","3,2001-3000元","4,3001-5000元","5,5001-8000元","6,8001-10000元","7,10001-20000元","8,20001-50000元","9,50000元以上");
var salary1buxian = new Array("0,不限","1,1000元以下","2,1001-2000元","3,2001-3000元","4,3001-5000元","5,5001-8000元","6,8001-10000元","7,10001-20000元","8,20001-50000元","9,50000元以上");

//血型
var bloodtype = new Array("0,请选择","1,A型","2,B型","3,AB型","4,O型","5,不确定");

//民族
var stock = new Array("0,请选择","1,汉族","2,藏族","3,朝鲜族","4,蒙古族","5,回族","6,满族","7,维吾尔族","8,壮族","9,彝族","10,苗族","11,侗族","12,瑶族","13,白族","14,布依族","15,傣族","16,京族","17,黎族","18,羌族","19,怒族","20,佤族","21,水族","22,畲族","23,土族","24,阿昌族","25,哈尼族","26,高山族","27,景颇族","28,珞巴族","29,锡伯族","30,德昂(崩龙)族","31,保安族","32,基诺族","33,门巴族","34,毛南族","35,赫哲族","36,裕固族","37,撒拉族","38,独龙族","39,普米族","40,仫佬族","41,仡佬族","42,东乡族","43,拉祜族","44,土家族","45,纳西族","46,傈僳族","47,布朗族","48,哈萨克族","49,达斡尔族","50,鄂伦春族","51,鄂温克族","52,俄罗斯族","53,塔塔尔族","54,塔吉克族","55,柯尔克孜族","56,乌兹别克族","57,国外");
var stockbuxian = new Array("0,不限","1,汉族","2,藏族","3,朝鲜族","4,蒙古族","5,回族","6,满族","7,维吾尔族","8,壮族","9,彝族","10,苗族","11,侗族","12,瑶族","13,白族","14,布依族","15,傣族","16,京族","17,黎族","18,羌族","19,怒族","20,佤族","21,水族","22,畲族","23,土族","24,阿昌族","25,哈尼族","26,高山族","27,景颇族","28,珞巴族","29,锡伯族","30,德昂(崩龙)族","31,保安族","32,基诺族","33,门巴族","34,毛南族","35,赫哲族","36,裕固族","37,撒拉族","38,独龙族","39,普米族","40,仫佬族","41,仡佬族","42,东乡族","43,拉祜族","44,土家族","45,纳西族","46,傈僳族","47,布朗族","48,哈萨克族","49,达斡尔族","50,鄂伦春族","51,鄂温克族","52,俄罗斯族","53,塔塔尔族","54,塔吉克族","55,柯尔克孜族","56,乌兹别克族","57,国外");

//信仰
var belief = new Array("0,请选择","1,不可知论者","2,不信教","3,儒家门徒","4,无神论者","5,佛教徒／道教徒","6,天主教徒","7,印度教徒","8,伊斯兰教徒","9,犹太教徒","10,新教徒","11,基督教徒","15,保密");

//生肖
var animals = new Array("0,请选择","1,鼠","2,牛","3,虎","4,兔","5,龙","6,蛇","7,马","8,羊","9,猴","10,鸡","11,狗","12,猪");

//星座
var constellation = new Array("0,请选择","1,牡羊座(03.21-04.20)","2,金牛座(04.21-05.20)","3,双子座(05.21-06.21)","4,巨蟹座(06.22-07.22)","5,狮子座(07.23-08.22)","6,处女座(08.23-09.22)","7,天秤座(09.23-10.22)","8,天蝎座(10.23-11.21)","9,射手座(11.22-12.21)","10,魔羯座(12.22-01.19)","11,水瓶座(01.20-02.19)","12,双鱼座(02.20-03.20)");

//职业类别,"44,科研工作","45,办公室职员","46,文学","47,艺术","48,编辑","49,影视","50,体育","51,交通/运输","52,采购"
var occupation= new Array("0,请选择","1,金融业","2,计算机业","3,商业","4,服务行业","5,教育业","6,工程师","7,主管，经理","8,政府部门","9,制造业","10,销售/广告/市场","11,资讯业","12,自由业","13,农渔牧","14,医生","15,律师","16,教师","17,幼师","18,会计师","19,设计师","20,空姐","21,护士","22,记者","23,学者","24,公务员","26,职业经理人","27,秘书","28,音乐家","29,画家","30,咨询师","31,审计师","32,注册会计师","33,军人","34,警察","35,学生","36,待业中","37,消防员","38,经纪人","39,模特","40,教授","41,IT工程师","42,摄影师","43,企业高管","44,作家","99,其他行业");
var occupationbuxian= new Array("0,不限","1,金融业","2,计算机业","3,商业","4,服务行业","5,教育业","6,工程师","7,主管，经理","8,政府部门","9,制造业","10,销售/广告/市场","11,资讯业","12,自由业","13,农渔牧","14,医生","15,律师","16,教师","17,幼师","18,会计师","19,设计师","20,空姐","21,护士","22,记者","23,学者","24,公务员","26,职业经理人","27,秘书","28,音乐家","29,画家","30,咨询师","31,审计师","32,注册会计师","33,军人","34,警察","35,学生","36,待业中","37,消防员","38,经纪人","39,模特","40,教授","41,IT工程师","42,摄影师","43,企业高管","44,作家","99,其他行业");

//公司类别
var corptype = new Array("0,请选择","1,政府机关","2,事业单位","3,世界500强","4,外资企业","5,上市公司","6,国营企业","7,私营企业","8,自有公司");

//住房条件
var house = new Array("0,请选择","1,和父母家人同住","2,自有物业","3,租房","4,婚后有房","5,保密");

//是否购车
var vehicle = new Array("0,请选择","1,已购","2,未购","3,保密");

//是否有孩子
var children = new Array("0,请选择","1,没有","3,有，我们住在一起","4,有，我们偶尔一起住","5,有，但不在身边","2,保密");
var childrenbuxian = new Array("0,不限","1,没有","2,以后再告诉您","3,有，我们住在一起","4,有，我们偶尔一起住","5,我有孩子，但不在身边");

//是否想要孩子
var wantchildren = new Array("0,请选择","1,也许","2,想要！我喜欢孩子！","3,暂时不想要孩子","4,保密");
var wantchildrenbuxian = new Array("0,不限","1,也许","2,想要！我喜欢孩子！","3,暂时不想要孩子","4,以后再告诉您");

//兄弟姐妹
var family = new Array("0,请选择","1,独生子女","2,2","3,3","4,4","5,5","6,6","7,7","8,8");

//年龄
var age = new Array("0,请选择","18,18","19,19","20,20","21,21","22,22","23,23","24,24","25,25","26,26","27,27","28,28","29,29","30,30","31,31","32,32","33,33","34,34","35,35","36,36","37,37","38,38","39,39","40,40","41,41","42,42","43,43","44,44","45,45","46,46","47,47","48,48","49,49","50,50","51,51","52,52","53,53","54,54","55,55","56,56","57,57","58,58","59,59","60,60","61,61","62,62","63,63","64,64","65,65","66,66","67,67","68,68","69,69","70,70","71,71","72,72","73,73","74,74","75,75","76,76","77,77","78,78","79,79","80,80","81,81","82,82","83,83","84,84","85,85","86,86","87,87","88,88","89,89","90,90","91,91","92,92","93,93","94,94","95,95","96,96","97,97","98,98","99,99");
var agebuxian = new Array("0,不限","18,18","19,19","20,20","21,21","22,22","23,23","24,24","25,25","26,26","27,27","28,28","29,29","30,30","31,31","32,32","33,33","34,34","35,35","36,36","37,37","38,38","39,39","40,40","41,41","42,42","43,43","44,44","45,45","46,46","47,47","48,48","49,49","50,50","51,51","52,52","53,53","54,54","55,55","56,56","57,57","58,58","59,59","60,60","61,61","62,62","63,63","64,64","65,65","66,66","67,67","68,68","69,69","70,70","71,71","72,72","73,73","74,74","75,75","76,76","77,77","78,78","79,79","80,80","81,81","82,82","83,83","84,84","85,85","86,86","87,87","88,88","89,89","90,90","91,91","92,92","93,93","94,94","95,95","96,96","97,97","98,98","99,99");

//身高
var height = new Array("0,请选择","154,155以下","155,155","156,156","157,157","158,158","159,159","160,160","161,161","162,162","163,163","164,164","165,165","166,166","167,167","168,168","169,169","170,170","171,171","172,172","173,173","174,174","175,175","176,176","177,177","178,178","179,179","180,180","181,180以上");
var heightbuxian = new Array("0,不限","154,155以下","155,155","156,156","157,157","158,158","159,159","160,160","161,161","162,162","163,163","164,164","165,165","166,166","167,167","168,168","169,169","170,170","171,171","172,172","173,173","174,174","175,175","176,176","177,177","178,178","179,179","180,180","181,180以上");

//体重
var weight = new Array("0,请选择","39,40以下","40,40","41,41","42,42","43,43","44,44","45,45","46,46","47,47","48,48","49,49","50,50","51,51","52,52","53,53","54,54","55,55","56,56","57,57","58,58","59,59","60,60","61,61","62,62","63,63","64,64","65,65","66,66","67,67","68,68","69,69","70,70","71,71","72,72","73,73","74,74","75,75","76,76","77,77","78,78","79,79","80,80","81,80以上");
var weightbuxian = new Array("0,不限","39,40以下","40,40","41,41","42,42","43,43","44,44","45,45","46,46","47,47","48,48","49,49","50,50","51,51","52,52","53,53","54,54","55,55","56,56","57,57","58,58","59,59","60,60","61,61","62,62","63,63","64,64","65,65","66,66","67,67","68,68","69,69","70,70","71,71","72,72","73,73","74,74","75,75","76,76","77,77","78,78","79,79","80,80","81,80以上");

//男体形
var body0 = new Array("0,请选择","1,一般","2,瘦长","3,运动员型","4,比较胖","5,体格魁梧","10,矮壮结实","20,保密");
var body0buxian = new Array("0,不限","20,保留","1,一般","2,瘦长","3,运动员型","4,比较胖","5,体格魁梧","10,矮壮结实");

//女体形
var body1 = new Array("0,请选择","1,一般","2,瘦长","6,苗条","7,高大美丽","8,丰满","9,富线条美","20,保密");
var body1buxian = new Array("0,不限","20,保留","1,一般","2,瘦长","6,苗条","7,高大美丽","8,丰满","9,富线条美");

var expectlovedate = new Array("0,请选择","1,1个月内","2,3个月内","3,半年内","4,一年及一年以上");
 
var viewtype = new Array("3,照片显示","2,详细显示");

var orderby = new Array("hpf,原始顺序","createtime,最新注册","age,年龄顺序");

var searchheight =new Array("0,请选择","1,155cm以下","2,155cm-160cm","3,160cm-165cm","4,165cm-170cm","5,170cm-175cm","6,175cm-180cm","20,180cm以上");

var searchweight = new Array("0,请选择","1,40kg以下","2,40kg－45kg","3,45kg－50kg","4,50kg－55kg","5,55kg－60kg","6,60kg－65kg","7,65kg－70kg","8,70kg－80kg","9,80kg以上");


//year(年份)
var years = new Array("1989","1988","1987","1986","1985","1984","1983","1982","1981",
					   "1980","1979","1978","1977","1976","1975","1974","1973","1972","1971",
					   "1970","1969","1968","1967","1966","1965","1964","1963","1962","1961",
					   "1960","1959","1958","1957","1956","1955","1954","1953","1952","1951",
					   "1950","1949","1948","1947","1946","1945","1944","1943","1942","1941",
					   "1940","1939","1938","1937","1936","1935","1934","1933","1932","1931",
					   "1930","1929","1928","1927","1926","1925","1924","1923","1922","1921");