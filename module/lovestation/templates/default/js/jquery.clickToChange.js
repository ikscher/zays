;(function($)
	{
		$.fn.extend(
		{
			"clickToChange":function(options)
			{
				var defaults=
				{
					h:"150",
					w:"600",
					time:500
				};
				options=$.extend(defaults,options);
				return this.each(function()
				{
					var time=options.time;
					var objh=$(this).height(options.h);
					var objw=$(this).width(options.w);
					var arr=$(this).children("ul").children("li");
					var tmp=0,tmp2=1;
					$(this).children("ul").css({"height":options.h,"width":arr.width()*arr.length,"left":0});
					var obj=$(this).parent("div").children("div");
					var src=$(this).children("ul").children("li img:first").attr("src");
					obj.eq(3).find("img").attr("src",src);
					arr.hover(function()
					{
						src=$(this).find("img").attr("src");
						obj.eq(3).find("img").attr("src",src);

					});
					for (var i=0;i<arr.length ;i++ )
					{
						arr.eq(i).css("position","absolute").css("left",i*arr.width());
					}
					arr.css({"height":arr.height(),"width":arr.width()});
					obj.eq(2).css("visibility","hidden");
					obj.eq(0).click(function()
					{
					obj.eq(1).children("ul").stop(true,false).animate({left:(tmp-1)*arr.width()},time);
					tmp-=1;
					tmp2-=1;
					//$("ul").children("li:first").html(tmp+","+tmp2);
					if (obj.eq(1).children("ul").position().left<=0)
					{
						obj.eq(2).css("visibility","visible");
					} if (obj.eq(1).children("ul").position().left<=-((arr.length-1)*arr.width()-options.w))
					{
						obj.eq(0).css("visibility","hidden");
					}
				});
				obj.eq(2).click(function()
				{	
					tmp+=1;
					tmp2+=1;
					//$("ul").children("li:first").html(tmp+","+tmp2);
					obj.eq(1).children("ul").stop(true,false).animate({left:(tmp2-1)*arr.width()},time);
					if (tmp2==1)
					{
						obj.eq(2).css("visibility","hidden");
					}
					if (obj.eq(1).children("ul").position().left>=-((arr.length)*arr.width()-options.w))
					{
						obj.eq(0).css("visibility","visible");
					}
				});
				});
			}
		});
	})(jQuery)