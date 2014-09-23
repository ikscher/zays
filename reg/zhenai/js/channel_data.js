function createDataHTML(data1, data2, group){
	var data = [];
		$.each(data1, function(i){
			data[i] = new Array().concat(data1[i], data2[i]);
		});
	var html = '<ul>',
		i = 0,
		len = data.length,
		back, face;
	for( ; i < len ; i++){
		back = parseInt(i/4)%2 ? 1 : 0;
		face = back ? 0 : 1;
		html += '<li><div class="flipcard-container">'+
					'<div class="flipcard flipcard-click" data-sex="'+data[i][face].sex+'">'+
						'<div class="back face" data-sex="'+data[i][face].sex+'">'+
							'<img uname='+data[i][face].uname+' uage="'+data[i][face].uage+'" uaddress="'+data[i][face].uaddress+'" width="100" height="125" src="'+data[i][face].img+'">'+
						'</div>'+
						'<div class="front face" data-sex="'+data[i][back].sex+'">'+
							'<img uname='+data[i][back].uname+' uage="'+data[i][back].uage+'" uaddress="'+data[i][back].uaddress+'" width="100" height="125" src="'+data[i][back].img+'">'+
						'</div>'+
					'</div>'+
				'</div></li>';
	}
	
	return html+'</ul>';
};

function loadChannelData2(data){
	data.sort(function(a, b){//前一半是男，后一半是女
		return $.browser.webkit?(parseInt(a.sex)?0:1):(parseInt(a.sex)==1?1:0);
	});
	if($.browser.webkit){
		data.reverse();
	}
	var newData = data.slice(0,32);
		data = newData.concat( data.reverse().slice(0,32) );
		
	var channelData=$("#channelData"),
		html = '';
		html = createDataHTML( data.slice(0,16), data.slice(32,48) , true) + 
		createDataHTML( data.slice(16,32), data.slice(48,64));
	channelData.html(html);
	$('.flipcard-click').first().removeClass('flipcard-click');
	$('.flipcard-click').last().removeClass('flipcard-click');
	if($.browser.msie || ($.browser.webkit&&parseInt( $.browser.version )<29)){
		$('.face').removeClass('face');
	}

	
	channelData.find('.flipcard').bind('click',  function(){
		if( $('#touxiangTest').size()<1 )return;
		var sex = parseInt($(this).attr('data-sex')) ? 0 :1 ;
		var dom =  $('.flipcard-click[data-sex='+sex+']').toArray().sort(function(){
				return Math.random() > .5 ? 1 : 0;
			}).slice(0,5);
		
		if($.browser.mozilla || ($.browser.webkit&&parseInt( $.browser.version )>28)){
			$(dom).toggleClass('hover').attr('data-sex', sex?0:1);
		}else{
			$(dom).each(function(){
				$(this).attr('data-sex', sex?0:1).animate({
					top: (parseInt($(this).css('top')) >= 0) ? -125 : 0
				},600);
			});
			
		}
		
	});

	$('#mysex_0').bind('click', function(){
		if( $('#touxiangTest').size()<1 )return;
		var morden = ($.browser.mozilla || ($.browser.webkit&&parseInt( $.browser.version )>28)),
		dom = $('.flipcard[data-sex='+(morden?1:0)+']').toArray().sort(function(){return Math.random() > .5 ? 1 : 0;});
		$(dom).each(function(i){
			var that = this;
			(function(){
				setTimeout(function(){
					if(morden){
						$(that).toggleClass('hover').attr('data-sex', (morden?0:1));
					}else{
						$(that).attr('data-sex', (morden?0:1)).animate({
							top: (parseInt($(that).css('top')) >= 0) ? -125 : 0
						},600);							
					}
				},i*100);
			})();
		});		
	});
	$('#mysex_1').bind('click', function(){
		if( $('#touxiangTest').size()<1 )return;
		var morden = ($.browser.mozilla || ($.browser.webkit&&parseInt( $.browser.version )>28)),
		dom = $('.flipcard[data-sex='+(morden?0:1)+']').toArray().sort(function(){return Math.random() > .5 ? 1 : 0;});
		$(dom).each(function(i){
			var that = this;
			(function(){
				setTimeout(function(){
					if(morden){
						$(that).toggleClass('hover').attr('data-sex', (morden?1:0));
					}else{
						$(that).attr('data-sex', (morden?1:0)).animate({
							top: (parseInt($(that).css('top')) >= 0) ? -125 : 0
						},600);							
					}
				},i*100);
			})();
		});	
	});

};


loadChannelData2([{uaddress:"北京朝阳区",sex:"0",uage:"29",uname:"灵魂贩子",img:"http://photo1.zastatic.com/images/photo/8128/32511256/1307334869446_3.jpg"},{uaddress:"北京朝阳区",sex:"0",uage:"28",uname:"Jefferson",img:"http://photo11.zastatic.com/images/photo/13219/52873092/1359905719466_3.jpg"},{uaddress:"上海青浦区",sex:"0",uage:"31",uname:"等候爱情",img:"http://photo0.zastatic.com/images/photo/7876/31501625/1305182841267_3.JPG"},{uaddress:"北京西城区",sex:"0",uage:"31",uname:"缘来是你",img:"http://photo11.zastatic.com/images/photo/14233/56930778/1373862320231_3.jpg"},{uaddress:"广东深圳",sex:"0",uage:"26",uname:"shawn",img:"http://photo13.zastatic.com/images/photo/15168/60669014/1379816024659_3.jpg"},{uaddress:"北京海淀区",sex:"0",uage:"32",uname:"壁虎漫步",img:"http://photo0.zastatic.com/images/photo/3999/15995170/1340609415299_3.jpg"},{uaddress:"北京朝阳区",sex:"0",uage:"34",uname:"黑马",img:"http://photo12.zastatic.com/images/photo/12655/50616421/1378394447813_3.jpg"},{uaddress:"北京海淀区",sex:"0",uage:"32",uname:"爱情来了",img:"http://photo12.zastatic.com/images/photo/13313/53251003/1361345381953_3.jpg"},{uaddress:"北京海淀区",sex:"0",uage:"29",uname:"会员61076717",img:"http://photo16.zastatic.com/images/photo/15270/61076717/1381156841000_3.jpg"},{uaddress:"北京海淀区",sex:"0",uage:"29",uname:"大海晨星",img:"http://photo16.zastatic.com/images/photo/12584/50332373/1372637669969_3.jpg"},{uaddress:"北京海淀区",sex:"0",uage:"32",uname:"天下",img:"http://photo13.zastatic.com/images/photo/14511/58040174/1372209516320_3.jpg"},{uaddress:"北京海淀区",sex:"0",uage:"29",uname:"谜殇琉璃",img:"http://photo14.zastatic.com/images/photo/10228/40910943/1328596152728_3.JPG"},{uaddress:"北京西城区",sex:"0",uage:"29",uname:"天道酬勤",img:"http://photo15.zastatic.com/images/photo/10677/42706390/1332651688171_3.JPG"},{uaddress:"广东深圳",sex:"0",uage:"32",uname:"微风",img:"http://photo15.zastatic.com/images/photo/14451/57803434/1371631995120_3.jpg"},{uaddress:"广东深圳",sex:"0",uage:"31",uname:"为你心动",img:"http://photo15.zastatic.com/images/photo/13992/55967560/1367636108981_3.jpg"},{uaddress:"广东深圳",sex:"0",uage:"30",uname:"Alans",img:"http://photo15.zastatic.com/images/photo/10241/40963288/1328493193216_3.jpg"},{uaddress:"广东深圳",sex:"0",uage:"31",uname:"小佛",img:"http://photo1.zastatic.com/images/photo/8279/33113361/1355545620257_3.jpg"},{uaddress:"广东广州",sex:"0",uage:"31",uname:"明月当歌",img:"http://photo2.zastatic.com/images/photo/6977/27907447/1295153323729_3.jpg"},{uaddress:"广东广州",sex:"0",uage:"32",uname:"tone",img:"http://photo2.zastatic.com/images/photo/7314/29255907/1303952254202_3.jpg"},{uaddress:"广东广州",sex:"0",uage:"30",uname:"车辚辚",img:"http://photo3.zastatic.com/images/photo/7468/29868913/1300330230527_3.jpg"},{uaddress:"广东广州",sex:"0",uage:"30",uname:"将爱",img:"http://photo3.zastatic.com/images/photo/7264/29054178/1341195734871_3.JPG"},{uaddress:"广东广州",sex:"0",uage:"30",uname:"8号风球",img:"http://photo13.zastatic.com/images/photo/14564/58253456/1372935617524_3.jpg"},{uaddress:"广东广州",sex:"0",uage:"30",uname:"会员58446195",img:"http://photo14.zastatic.com/images/photo/14612/58446195/1373275618338_3.jpg"},{uaddress:"广东广州",sex:"0",uage:"32",uname:"岁月无声",img:"http://photo14.zastatic.com/images/photo/15280/61117461/1381215691643_3.jpg"},{uaddress:"广东广州",sex:"0",uage:"29",uname:"会员59417011",img:"http://photo12.zastatic.com/images/photo/14855/59417011/1376308105114_3.jpg"},{uaddress:"广东广州",sex:"0",uage:"28",uname:"会员49882272",img:"http://photo11.zastatic.com/images/photo/12471/49882272/1352154686126_3.jpg"},{uaddress:"广东广州",sex:"0",uage:"29",uname:"Legstrong",img:"http://photo16.zastatic.com/images/photo/10479/41913947/1376311831256_3.jpg"},{uaddress:"广东深圳",sex:"0",uage:"27",uname:"周一吃披萨",img:"http://photo12.zastatic.com/images/photo/8529/34113787/1375372773871_3.jpg"},{uaddress:"广东深圳",sex:"0",uage:"30",uname:"明月",img:"http://photo3.zastatic.com/images/photo/5972/23885793/1270614002859_3.jpg"},{uaddress:"陕西西安",sex:"0",uage:"32",uname:"天空",img:"http://photo15.zastatic.com/images/photo/10545/42177124/1377580445419_3.jpg"},{uaddress:"浙江杭州",sex:"0",uage:"33",uname:"周新新",img:"http://photo4.zastatic.com/images/photo/3854/15414224/1317532910753_3.jpg"},{uaddress:"广东深圳",sex:"0",uage:"31",uname:"anything  ",img:"http://photo12.zhenai.com/images/photo/16298/65189551/1395283560523_3.jpg"},{uaddress:"江苏南京",sex:"0",uage:"27",uname:"南情默默",img:"http://photo0.zastatic.com/images/photo/7717/30865040/1310180653340_3.jpg"},{uaddress:"江苏南京",sex:"0",uage:"30",uname:"会员57637936",img:"http://photo15.zastatic.com/images/photo/14410/57637936/1371298299927_3.jpg"},{uaddress:"江苏南京",sex:"0",uage:"29",uname:"Carcass",img:"http://photo12.zastatic.com/images/photo/11498/45990247/1341387303261_3.jpg"},{uaddress:"江苏南京",sex:"0",uage:"30",uname:"冰原の炎",img:"http://photo13.zastatic.com/images/photo/11187/44747630/1337735453086_3.jpg"},{uaddress:"北京海淀区",sex:"1",uage:"22",uname:"女屌丝一枚",img:"http://photo12.zastatic.com/images/photo/8431/33721957/1309761898609_3.jpg"},{uaddress:"北京朝阳区",sex:"1",uage:"22",uname:"暗里????",img:"http://photo16.zastatic.com/images/photo/12666/50663843/1354135698884_3.jpg"},{uaddress:"上海宝山区",sex:"1",uage:"26",uname:"佳佳",img:"http://photo11.zastatic.com/images/photo/14653/58610106/1373688083919_3.jpg"},{uaddress:"上海长宁区",sex:"1",uage:"25",uname:"hyechun",img:"http://photo15.zastatic.com/images/photo/9842/39367390/1369664594733_3.JPG"},{uaddress:"上海徐汇区",sex:"1",uage:"26",uname:"joyce",img:"http://photo11.zastatic.com/images/photo/14083/56328822/1368342941883_3.jpg"},{uaddress:"上海闸北区",sex:"1",uage:"24",uname:"艾比·周",img:"http://photo11.zastatic.com/images/photo/8532/34124292/1316586870317_3.jpg"},{uaddress:"上海杨浦区",sex:"1",uage:"24",uname:"猴子",img:"http://photo15.zastatic.com/images/photo/12016/48062536/1346991260316_3.jpg"},{uaddress:"上海浦东新区",sex:"1",uage:"23",uname:"会员52369612",img:"http://photo15.zastatic.com/images/photo/13093/52369612/1369722869342_3.jpg"},{uaddress:"上海普陀区",sex:"1",uage:"25",uname:"y_coco",img:"http://photo3.zastatic.com/images/photo/6834/27335023/1291960594234_3.jpg"},{uaddress:"上海松江区",sex:"1",uage:"24",uname:"独角戏",img:"http://photo13.zastatic.com/images/photo/13580/54319004/1363453939093_3.jpg"},{uaddress:"上海杨浦区",sex:"1",uage:"25",uname:"会员60895060",img:"http://photo15.zastatic.com/images/photo/15224/60895060/1380509654824_3.jpg"},{uaddress:"上海浦东新区",sex:"1",uage:"24",uname:"一枚小太阳",img:"http://photo14.zastatic.com/images/photo/13755/55016091/1377493934039_3.jpg"},{uaddress:"上海黄浦区",sex:"1",uage:"23",uname:"会员60320690",img:"http://photo13.zastatic.com/images/photo/15081/60320690/1378694964699_3.jpg"},{uaddress:"上海黄浦区",sex:"1",uage:"30",uname:"会员30000958",img:"reg/zhenai/images/member/1381007543139_3.jpg"},{uaddress:"上海徐汇区",sex:"1",uage:"25",uname:"Tyra",img:"http://photo13.zastatic.com/images/photo/12954/51813344/1377000126490_3.jpg"},{uaddress:"上海浦东新区",sex:"1",uage:"24",uname:"韵文",img:"http://photo1.zastatic.com/images/photo/7561/30242466/1368716206410_3.jpg"},{uaddress:"上海徐汇区",sex:"1",uage:"25",uname:"会员61041157",img:"http://photo12.zastatic.com/images/photo/15261/61041157/1380974834062_3.jpg"},{uaddress:"上海长宁区",sex:"1",uage:"24",uname:"Ula1989",img:"http://photo16.zastatic.com/images/photo/9224/36893753/1365588051743_3.jpg"},{uaddress:"上海浦东新区",sex:"1",uage:"23",uname:"会员60994307",img:"http://photo16.zastatic.com/images/photo/15249/60994307/1380821058506_3.jpg"},{uaddress:"上海黄浦区",sex:"1",uage:"25",uname:"会员60846779",img:"http://photo16.zastatic.com/images/photo/15212/60846779/1380330501565_3.jpg"},{uaddress:"上海闸北区",sex:"1",uage:"26",uname:"lily",img:"http://photo13.zastatic.com/images/photo/10836/43340072/1372388239501_3.jpg"},{uaddress:"重庆沙坪坝区",sex:"1",uage:"23",uname:"美丽邂逅",img:"http://photo13.zastatic.com/images/photo/13757/55024772/1365335313478_3.jpg"},{uaddress:"重庆渝中区",sex:"1",uage:"23",uname:"谷优美子",img:"http://photo3.zastatic.com/images/photo/7014/28054998/1368778461304_3.jpeg"},{uaddress:"重庆江北",sex:"1",uage:"24",uname:"王俪梦",img:"http://photo15.zastatic.com/images/photo/13427/53707714/1362934651743_3.jpg"},{uaddress:"北京房山区",sex:"1",uage:"24",uname:"会员60990426",img:"http://photo11.zastatic.com/images/photo/15248/60990426/1380882772826_3.jpg"},{uaddress:"北京朝阳区",sex:"1",uage:"23",uname:"释雪",img:"http://photo12.zastatic.com/images/photo/10533/42128485/1376404959078_3.jpg"},{uaddress:"北京朝阳区",sex:"1",uage:"25",uname:"会员61155392",img:"http://photo13.zastatic.com/images/photo/15289/61155392/1381295848268_3.jpg"},{uaddress:"北京朝阳区",sex:"1",uage:"25",uname:"安欣",img:"http://photo15.zastatic.com/images/photo/13132/52525084/1380076952579_3.jpg"},{uaddress:"北京西城区",sex:"1",uage:"23",uname:"会员60649846",img:"http://photo15.zastatic.com/images/photo/15163/60649846/1380373038899_3.jpg"},{uaddress:"北京西城区",sex:"1",uage:"22",uname:"郭小鬼",img:"http://photo13.zastatic.com/images/photo/14147/56586146/1378473077182_3.jpg"},{uaddress:"北京西城区",sex:"1",uage:"25",uname:"会员61143132",img:"http://photo11.zastatic.com/images/photo/15286/61143132/1381242396151_3.jpg"},{uaddress:"广东广州",sex:"1",uage:"26",uname:"小世",img:"http://photo2.zastatic.com/images/photo/6822/27286432/1340245982056_3.jpg"},{uaddress:"广东广州",sex:"1",uage:"26",uname:"猪小白",img:"http://photo2.zastatic.com/images/photo/6698/26788162/1350808548688_3.jpg"},{uaddress:"广东广州",sex:"1",uage:"26",uname:"蓦然回首",img:"http://photo13.zastatic.com/images/photo/14829/59314652/1375631316062_3.jpg"},{uaddress:"广东广州",sex:"1",uage:"26",uname:"Erica",img:"http://photo13.zastatic.com/images/photo/14203/56809568/1372060880365_3.jpg"},{uaddress:"广东深圳",sex:"1",uage:"25",uname:"sammer",img:"http://photo14.zastatic.com/images/photo/10899/43595109/1355806426607_3.jpg"},{uaddress:"广东深圳",sex:"1",uage:"27",uname:"相信缘分",img:"http://photo16.zastatic.com/images/photo/11786/47142671/1368794087979_3.jpg"},{uaddress:"广东深圳",sex:"1",uage:"25",uname:"会员61083153",img:"http://photo14.zastatic.com/images/photo/15271/61083153/1381086946116_3.jpg"},{uaddress:"广东深圳",sex:"1",uage:"24",uname:"会员53804764",img:"http://photo15.zastatic.com/images/photo/13452/53804764/1362667203396_3.jpg"},{uaddress:"广东深圳",sex:"1",uage:"24",uname:"佑夏　Florence",img:"http://photo11.zastatic.com/images/photo/12079/48314880/1347772379684_3.jpg"}]);
