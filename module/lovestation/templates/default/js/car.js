// JavaScript Document
$(function()
{
	var tmp=0;
	var liNum=$(".leftBtn").siblings("ul").children("li");
	
	liNum.css({"display":"none"});
	liNum.eq(0).css({"display":"block"});
	
	$(".rightBtn").click(function()
	{
		tmp+=1;
		liNum.eq(tmp).css("display","block").siblings("li").css("display","none");
		if(tmp>liNum.length-1)
		{
			tmp=0;
		}
	});
	$(".leftBtn").click(function()
	{
		tmp-=1;
		liNum.eq(tmp).css("display","block").siblings("li").css("display","none");
		if(tmp<0)
		{
			tmp=liNum.length-1;
		}
	});
	var tmp2=0;
	var liNum2=$(".leftBtn2").siblings("ul").children("li");
	
	liNum2.css({"display":"none"});
	liNum2.eq(0).css({"display":"block"});
	
	$(".rightBtn2").click(function()
	{
		tmp2+=1;
		liNum2.eq(tmp2).css("display","block").siblings("li").css("display","none");
		if(tmp2>liNum2.length-1)
		{
			tmp2=0;
		}
	});
	$(".leftBtn2").click(function()
	{
		tmp2-=1;
		liNum2.eq(tmp2).css("display","block").siblings("li").css("display","none");
		if(tmp2<0)
		{
			tmp2=liNum2.length-1;
		}
	});



	
});