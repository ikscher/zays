//添加年份
var str_year='';
var yl=years.length;
var j=0;
for(var i=0;i<yl;i++){
    if(i==0) str_year +='<p><i>90后:</i>';
	str_year+='<a data-value="'+years[i]+'" href="javascript:;">'+years[i]+'</a>';
	
	if(years[i]%10==0 && years[i] &&  i!=yl-1) { ++j;str_year +='</p><p><i>'+(90 - j*10)+'后:</i>';}
}
$('.year-selector dd').append(str_year);

//添加月份
var str_month='';
var ml=months.length;
for (var i=0;i<ml;i++){
    if(i==0) str_month='<p>';
	str_month+='<a data-value="'+months[i]+'" href="javascript:;">'+months[i]+'</a>';
	if(i==ml - 1) str_month+='</p>';
}
$('.month-selector dd').append(str_month);

//添加日
function appendDay(){
	var year=parseInt($('.year-selector dt').text());
	var month=parseInt($('.month-selector dt').text());
	if(year && month) $('.day-selector dd p').remove();
	var lastDay = new Date(year, month , 0).getDate();//上个月的第零天就是当前月的最后一天
	var str_day = '<p>';
	for(var i=1; i <= lastDay; i++){
		str_day += '<a data-value="'+i+'" href="javascript:;">'+i+'</a>';
	}
	$('.day-selector dd').append(str_day);
}

//添加省份
var str_province='';
var pl=province.length;
for (var i=0;i<pl;i++){
	var pkv=province[i].split(',');
	str_province+='<span><a data-value="'+pkv[0]+'" href="javascript:;">'+pkv[1]+'</a></span>';
}
$('.province-selector dd').append(str_province);


//添加市区县
function appendCity(province_val){
	if(province_val) $('.city-selector dd p').remove();
	if(province_val==2){
		var city_=city.filter(function(x) { return x.substr(0,8)%100000==0;});   
	}else{
	    var ps=province_val.substr(0,5); 
		var city_=city.filter(function(x) { return x.substr(0,5)==ps;});   
	}
	var str_city = '<p>';
	for(var i=0; i < city_.length; i++){
	    var city__=city_[i].split(',');
		str_city += '<span><a data-value="'+city__[0]+'" href="javascript:;">'+city__[1]+'</a></span>';
	}
	$('.city-selector dd').append(str_city);
}

//添加婚姻状况
var str_marriage='';
var nl=marriage.length;
for (var i=0;i<nl;i++){
	var mkv=marriage[i].split(',');
	str_marriage+='<span><a data-value="'+mkv[0]+'" href="javascript:;">'+mkv[1]+'</a></span>';
}
$('.marriage-selector dd').append(str_marriage);

//添加教育学历
var str_education='';
var el=education.length;
for (var i=0;i<el;i++){
	var ekv=education[i].split(',');
	str_education+='<span><a data-value="'+ekv[0]+'" href="javascript:;">'+ekv[1]+'</a></span>';
}
$('.education-selector dd').append(str_education);

//添加月收入
var str_salary='';
var sl=salary.length;
for (var i=0;i<sl;i++){
	var skv=salary[i].split(',');
	str_salary+='<span><a data-value="'+skv[0]+'" href="javascript:;">'+skv[1]+'</a></span>';
}
$('.salary-selector dd').append(str_salary);


//提交表单
$('#register_btn').on('click',function(){
    if(channelCheckForm()){
	   setTimeout(function(){ document.myForm.submit();},1000);
	}
});

/*加载背景用户*/
function getDataScript(){
	var id='channelScript';
	var url = 'reg/zhenai/js/channel_data.js?v=20140404';
	oScript = document.getElementById(id); 
	var head = document.getElementsByTagName("head").item(0); 
	if (oScript != null) {
		head.removeChild(oScript); 
	} 
	oScript = document.createElement("script"); 
	oScript.setAttribute("src", url); 
	oScript.setAttribute("id",id); 
	oScript.setAttribute("type","text/javascript"); 
	oScript.setAttribute("language","javascript"); 
	head.appendChild(oScript); 
}
getDataScript();

//year
$('.year-selector').mouseover(function(){
   $(this).children('dd').removeClass('h');
   $(this).css('position','relative');
   $(this).addClass('za-item-selector-hover');
}).mouseout(function(){
   $(this).children('dd').addClass('h');
   $(this).removeAttr("style"); 
   $(this).removeClass('za-item-selector-hover');
});

//month
$('.month-selector').mouseover(function(){
   $(this).children('dd').removeClass('h');
   $(this).css('position','relative');
   $(this).addClass('za-item-selector-hover');
}).mouseout(function(){
   $(this).children('dd').addClass('h');
   $(this).removeAttr("style"); 
   $(this).removeClass('za-item-selector-hover');
});

//day
$('.day-selector').mouseover(function(){
   $(this).children('dd').removeClass('h');
   $(this).css('position','relative');
   $(this).addClass('za-item-selector-hover');
}).mouseout(function(){
   $(this).children('dd').addClass('h');
   $(this).removeAttr("style"); 
   $(this).removeClass('za-item-selector-hover');
});

//点击年份
$('.year-selector a').on('click',function(){
   var year=$(this).attr('data-value');
   $(this).parents('dd').siblings('dt').html(year);
   $('.month-selector').children('dd').removeClass('h');
   $('.month-selector').css('position','relative');
   $('.month-selector').addClass('za-item-selector-hover');
   $('#register_dateFormYear').val(year);
   appendDay();
});

//点击月份
$('.month-selector a').on('click',function(){
   var month=$(this).attr('data-value');
   $(this).parents('dd').siblings('dt').html(month);
   $('.day-selector').children('dd').removeClass('h');
   $('.day-selector').css('position','relative');
   $('.day-selector').addClass('za-item-selector-hover');
   $('#register_dateFormMonth').val(month);
   appendDay();
});

//点击日
$('.day-selector').on('click','p a',function(){
   var day=$(this).attr('data-value');
   $(this).parents('dd').siblings('dt').html(day);
   $(this).parents('dd').addClass('h');
   $(this).parents('dl').removeAttr("style"); 
   $(this).parents('dl').removeClass('za-item-selector-hover');
   $('#register_dateFormDay').val(day);
});

//province
$('.province-selector').mouseover(function(){
   $(this).children('dd').removeClass('h');
   $(this).css('position','relative');
   $(this).addClass('za-item-selector-hover');
}).mouseout(function(){
   $(this).children('dd').addClass('h');
   $(this).removeAttr("style"); 
   $(this).removeClass('za-item-selector-hover');
});


//city
$('.city-selector').mouseover(function(){
   $(this).children('dd').removeClass('h');
   $(this).css('position','relative');
   $(this).addClass('za-item-selector-hover');
}).mouseout(function(){
   $(this).children('dd').addClass('h');
   $(this).removeAttr("style"); 
   $(this).removeClass('za-item-selector-hover');
});

//点击省份
$('.province-selector a').on('click',function(){
   var province=$(this).html();
   var province_val=$(this).attr('data-value');
   $(this).parents('dd').siblings('dt').html(province);
   $('.city-selector dt').html('请选择');
   $('#workCity').val('');
   $(this).parents('dd').addClass('h');
   $(this).parents('dl').removeAttr("style"); 
   $(this).parents('dl').removeClass('za-item-selector-hover');
   $('.city-selector').children('dd').removeClass('h');
   $('.city-selector').css('position','relative');
   $('.city-selector').addClass('za-item-selector-hover');
   $("#workProvince").val(province_val);
   appendCity(province_val);
});

//点击市区县
$('.city-selector').on('click','dd p a',function(){
   var city=$(this).html();
   var city_val=$(this).attr('data-value');
   $(this).parents('dd').siblings('dt').html(city);
   $(this).parents('dd').addClass('h');
   $(this).parents('dl').removeAttr("style"); 
   $(this).parents('dl').removeClass('za-item-selector-hover');
   $('#workCity').val(city_val);
});

//marriage
$('.marriage-selector').mouseover(function(){
   $(this).children('dd').removeClass('h');
   $(this).css('position','relative');
   $(this).addClass('za-item-selector-hover');
}).mouseout(function(){
   $(this).children('dd').addClass('h');
   $(this).removeAttr("style"); 
   $(this).removeClass('za-item-selector-hover');
});

//点击婚姻
$('.marriage-selector a').on('click',function(){
   var marriage=$(this).html();
   var marriage_val=$(this).attr('data-value');
   $(this).parents('dd').siblings('dt').html(marriage);
   $(this).parents('dd').addClass('h');
   $(this).parents('dl').removeAttr("style"); 
   $(this).parents('dl').removeClass('za-item-selector-hover');
   $('input[name=marriage').val(marriage_val);
});


//education
$('.education-selector').mouseover(function(){
   $(this).children('dd').removeClass('h');
   $(this).css('position','relative');
   $(this).addClass('za-item-selector-hover');
}).mouseout(function(){
   $(this).children('dd').addClass('h');
   $(this).removeAttr("style"); 
   $(this).removeClass('za-item-selector-hover');
});

//点击教育
$('.education-selector a').on('click',function(){
   var education=$(this).html();
   var education_val=$(this).attr('data-value');
   $(this).parents('dd').siblings('dt').html(education);
   $(this).parents('dd').addClass('h');
   $(this).parents('dl').removeAttr("style"); 
   $(this).parents('dl').removeClass('za-item-selector-hover');
   $('input[name=education').val(education_val);
});


//salary
$('.salary-selector').mouseover(function(){
   $(this).children('dd').removeClass('h');
   $(this).css('position','relative');
   $(this).addClass('za-item-selector-hover');
}).mouseout(function(){
   $(this).children('dd').addClass('h');
   $(this).removeAttr("style"); 
   $(this).removeClass('za-item-selector-hover');
});

//点击收入
$('.salary-selector a').on('click',function(){
   var salary=$(this).html();
   var salary_val=$(this).attr('data-value');
   $(this).parents('dd').siblings('dt').html(salary);
   $(this).parents('dd').addClass('h');
   $(this).parents('dl').removeAttr("style"); 
   $(this).parents('dl').removeClass('za-item-selector-hover');
   $('input[name=salary').val(salary_val);
});

$('#mobile').hover(function(){
    $(this).focus();
});

