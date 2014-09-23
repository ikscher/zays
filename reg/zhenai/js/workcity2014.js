/**
 * 工作地优化
 */
(function($, window) {
	syscode.district={c10102000:{n:"\u5317\u4eac",d:1},c10104000:{n:"\u5929\u6d25",d:1},c10103000:{n:"\u4e0a\u6d77",d:1},c10105000:{n:"\u91cd\u5e86",d:1},c10101000:{n:"\u5e7f\u4e1c",c10101002:{n:"\u5e7f\u5dde"},c10101201:{n:"\u6df1\u5733"}},c10118000:{n:"\u6c5f\u82cf"},c10131000:{n:"\u6d59\u6c5f"},c10127000:{n:"\u56db\u5ddd"},c10107000:{n:"\u798f\u5efa"},c10124000:{n:"\u5c71\u4e1c"},c10115000:{n:"\u6e56\u5317"},c10112000:{n:"\u6cb3\u5317"},c10125000:{n:"\u5c71\u897f"},c10121000:{n:"\u5185\u8499\u53e4"},c10120000:{n:"\u8fbd\u5b81"},c10117000:{n:"\u5409\u6797"},c10114000:{n:"\u9ed1\u9f99\u6c5f"},c10106000:{n:"\u5b89\u5fbd"},c10119000:{n:"\u6c5f\u897f"},c10113000:{n:"\u6cb3\u5357"},c10116000:{n:"\u6e56\u5357"},c10109000:{n:"\u5e7f\u897f"},c10111000:{n:"\u6d77\u5357"},c10110000:{n:"\u8d35\u5dde"},c10130000:{n:"\u4e91\u5357"},c10128000:{n:"\u897f\u85cf"},c10126000:{n:"\u9655\u897f"},c10108000:{n:"\u7518\u8083"},c10123000:{n:"\u9752\u6d77"},c10122000:{n:"\u5b81\u590f"},c10129000:{n:"\u65b0\u7586"},c10132000:{n:"\u6fb3\u95e8",d:1},c10133000:{n:"\u9999\u6e2f"},c10134000:{n:"\u53f0\u6e7e",d:1},c10200000:{n:"\u56fd\u5916",d:1}};
    
    syscode.districtMap={};

     function getProvinceValue(value){
    	if(!value||value.length<8){
			return 0;
		}

		var province=value.substring(0,5)+'000';
		if(parseInt(province)> 10200000){
			province = 10200000;
		}
		return province;
    }

    //load district data
    function loadDistrict(value,callback){
    	if(value&&value.length==8){
    	    var provinceValue = getProvinceValue(value);
			if('undefined' !== typeof seajs && seajs){
				seajs.use("http://i0.zastatic.com/zhenai3/js/syscode2014/district/c"+provinceValue+".js",callback);
			}else{
				$.ajax({
				  url: "http://i0.zastatic.com/zhenai3/js/syscode2014/district/c"+provinceValue+".js",
				  dataType: "script",
				  cache:true,
				  success: callback
				});
			}
			
			 
    	}else{
			if(callback){
				callback.call(value);
			} 
		}
    } 

   


    syscode.getDistrictText=function(value){
		if(!value){return}
		value=value+"";
		var cvalue= "c" + value;
		var province = "c" + getProvinceValue(value);
		//var provinceMap = null;
		if(!this.districtMap[cvalue]){
		    var districtp = this.district[province];
		    for(var pd in districtp){
				var districtpd = districtp[pd];
                this.districtMap[pd] = districtpd.n;
                if("n"==pd){
                    syscode.districtMap[province] = districtp.n;
                    continue;
                }
                for(var pdd in districtpd){
                    var name=districtpd[pdd].n;
                    if(name){
                        this.districtMap[pdd] = name;
                    }
                }
                syscode.districtMap[province] = districtp.n;
            }
		}
        var text=this.districtMap[cvalue];
        return text?text:'';
    };
	
	var $win=$(window);
	syscode.calcPos=function(input,showDiv){
	   var $input=$(input);
	   var offset=$input.offset();
	   var offsetTop=offset.top;
	   var offsetLeft=offset.left;
	   var vleft=true;
	   var vtop=false;
	   var windowScrollTop=$win.scrollTop();
	   var showDivOuterWidth=showDiv.outerWidth();
	   var showDivOuterHeight=showDiv.outerHeight();
	   var inputOuterWidth=$input.outerWidth();
	   var inputOffsetHeight=input.offsetHeight;
	   if($win.width() < (offsetLeft + showDivOuterWidth)){
		   vleft = false;
	   }
	   if((offsetTop - windowScrollTop + showDivOuterHeight + inputOffsetHeight) > $win.height()){
		   vtop = true;
	   }
	   if((showDivOuterHeight - offsetTop - windowScrollTop) > 0){
		   vtop=false;
	   }
	   if(vleft){
		   if(vtop){
			   showDiv.css({left:offsetLeft,top:offsetTop - showDivOuterHeight});
		   }else{
			   showDiv.css({left:offsetLeft,top:offsetTop + inputOffsetHeight});
		   }
	   }else{
		   if(vtop){
			   showDiv.css({left:(offsetLeft + inputOuterWidth - showDivOuterWidth),top:(offsetTop - showDivOuterHeight)});
		   }else{
			   showDiv.css({left:(offsetLeft + inputOuterWidth - showDivOuterWidth),top:(offsetTop + inputOffsetHeight)});
		   }
	   }
	   if(showDiv.html()){
		   showDiv.show();
	   }
   };
   //判断是否是最底层值
	syscode.cityFloorValue=function(realValue){
		if(!realValue){
			return false;
		}
		//省级
		if("-1"==realValue||"10100000"==realValue||syscode.district[realValue]){
			return false;
		}
		var proValue=realValue;
        if(proValue>1){
            proValue=proValue.substring(0,5)+'000';
        }
		if(parseInt(proValue)>10200000){
			proValue="10200000";
		}
		var prefixproValue="c"+proValue;
		var prefixvalue="c"+realValue;
		var prefixWorkcitys = syscode.district[prefixproValue];
		var prefixvalueWorkcitys = prefixWorkcitys[prefixvalue];
		//市级
		if(prefixvalueWorkcitys){
			var hasThirdlevel=false;
			for(var p in prefixvalueWorkcitys){
                if("n"==p || "d" ==p){
                    continue;
                }
				if(hasThirdlevel){
					break;
				}
				hasThirdlevel=true;
			 }
			 if(!hasThirdlevel){
				return true;
			 }
		}
		//区级
		for(var p in prefixWorkcitys){
			if("n"==p || "d" ==p){continue;}
			for(var pd in prefixWorkcitys[p]){
				if("n"==pd || "d" ==pd){continue;}
				if(pd==prefixvalue){
					return true;
				}
			}
		}
		return false;
	};


	
	$(function(){
		$(document).bind("click",function(){
			$(".area_box").hide();
		});
	});

	//工作地三级选择控件
    $.workcity=function(options){
		//default settings
		var settings = {
			controlType:0,//控件类型默认，1为三级选择
			provinceInput:null,
			cityInput:null,
			districtInput:null,
			provinceHide:null,
			cityHide:null,
			readonly:true,//只读
			defaultJoin:false,
			defaultTitle:"请选择",
			cityLevel:null,
			citySelectLevel:3,//3 or 0 3只能选到3级,只对controlType为2才有用
			defaultValue:"-1",
			filterCitys:[],
			filterProvince:[],//过滤第一级
			customHotHas:true,//是否有连环直辖市
			customHotCitys:[],//只支持一、二级城市
			onlyOneNotShow:true,//当只有一个值时不弹出
			remember:true,//高亮选中值
			provinceBlurCallBack:null,
			cityCallBlurBack:null,
			districtBlurCallBack:null,
			provinceSelectCallBack:null,
			cityCallSelectBack:null,
			districtSelectCallBack:null,
			proviceTitle:"请选择省/直辖市",
			cityTitle:"请选择市/区",
			districtTitle:"请选择区县",
			auto:false,//只输入时根据CODE
			nullValue:"-1",//空时值后期启用
			zIndex:9999999//控件z-index值
		};
		$.extend(settings,options);
		//quick param if more not use settings
		var controlType=settings.controlType;
		var provinceInput=settings.provinceInput;
		var cityInput=settings.cityInput;
		var districtInput=settings.districtInput;
		var provinceHide=settings.provinceHide;
		var cityHide=settings.cityHide;
		var defaultValue=settings.defaultValue+"";
		var cityLevel=settings.cityLevel;//1自定义,2二级城市
		var defaultTitle=settings.defaultTitle;
		var defaultJoin=settings.defaultJoin;
		var filterCitys=settings.filterCitys;
		var filterProvince=settings.filterProvince;
		var onlyOneNotShow=settings.onlyOneNotShow;
		var remember=settings.remember;
		var nullValue=settings.nullValue;
		var zIndex=settings.zIndex;
		//var customHotHas=settings.customHotHas;
		//var defValue=options.defValue;
		//预留以后可自定层
		var provinceDiv;
		var cityDiv;
		var districtDiv;
		//
		var hasprovince=false;
		var hasWorkCity=false;
		var hasDistrict=false;
		var foreignCode="10200000";
		var hotcity=[];
		var hotcityOther=["10133000","10132000","10134000",foreignCode];
		//var 
        /**
         * 初始化
         */
        function initWorkCity(){
			if(provinceInput){
				hasprovince=true;
				provinceInput.attr("readonly",settings.readonly);
				provinceDiv=$('<div class="area_box province_box" style="display: none;positoion:absolute;z-index:' + zIndex + ';">');
			}
			if(cityInput){
			  hasWorkCity=true;
			  cityInput.attr("readonly",settings.readonly);
			  cityDiv=$('<div class="area_box w30" style="display: none;z-index:' + zIndex + ';">');
			}
			if(districtInput && controlType == 0){
			  hasDistrict=true;
			  districtInput.attr("readonly",settings.readonly);
			  districtInput.hide();
			  districtDiv=$('<div class="area_box w30" style="display: none;z-index:' + zIndex + ';">');
			}
			if(settings.customHotHas){
				var defaultHotCity=["10102000","10103000","10101002","10101201","10105000","10104000"];
				hotcity=hotcity.concat(defaultHotCity);
			}
			hotcity=hotcity.concat(settings.customHotCitys);
			if(cityLevel){
				if(2==cityLevel){
					filterCitys=filterCitys.concat(hotcity);
				}
				//others
			}
			var citycodepro=cityValuePro(defaultValue).split(",");
			
			var tempPro=defaultValue.substring(0,5)+'000';
			if(parseInt(tempPro)>parseInt(foreignCode)){
				tempPro=foreignCode;
			}
			init(citycodepro,defaultValue);
			if(hasprovince){
				provinceDiv.mousedown(function() {
					provinceDiv.data('hasfocus',true);
				}).mouseup(function() {
					provinceDiv.data('hasfocus',false);
				}).click(function(){
					return false;
				});
				provinceInput.bind("focus",function(event){
					$("body > .area_box").hide();
					syscode.calcPos(event.target,provinceDiv);
				}).bind("blur",function(){
					if(!provinceDiv.data('hasfocus')){
						provinceDiv.hide();
						if(settings.provinceBlurCallBack){
							settings.provinceBlurCallBack.call();
						}
					}
					if(settings.auto){
						var currentInpuValue=provinceInput.val();
						if(isNaN(currentInpuValue)){
							//以名字得ID
						}else{
							provinceSelect(currentInpuValue);
						}
					}
				}).bind("click",function(){
					return false;
				});
			}
			
			if(hasWorkCity){
				cityDiv.mousedown(function() {
					cityDiv.data('hasfocus',true);
				}).mouseup(function() {
					cityDiv.data('hasfocus',false);
				}).click(function(){
					cityInput.focus();
					return false;
				});
				cityInput.bind("focus",function(event){
					$("body > .area_box").hide();
					syscode.calcPos(event.target,cityDiv);
				}).bind("blur",function(){
					if(!cityDiv.data('hasfocus')){
						cityDiv.hide();
						if(settings.cityCallBlurBack){
							settings.cityCallBlurBack.call();
						}
					}
				}).bind("click",function(){
					return false;
				});
			}
			
			if(hasDistrict){
				districtDiv.mousedown(function() {
					districtDiv.data('hasfocus',true);
				}).mouseup(function() {
					districtDiv.data('hasfocus',false);
				}).click(function(){
					districtInput.focus();
					return false;
				});
				districtInput.bind("focus",function(event){
					$("body > .area_box").hide();
					syscode.calcPos(event.target,districtDiv);
				}).bind("blur",function(){
					if(!districtDiv.data('hasfocus')){
						districtDiv.hide();
						if(settings.districtBlurCallBack){
							settings.districtBlurCallBack.call();
						}
					}
				}).bind("click",function(){
					return false;
				});
			}
			
			if ($.fn.bgiframe ){
				if(hasprovince){
					provinceDiv.bgiframe();
				}
				if(defaultValue && defaultValue.length>8){
					if(hasWorkCity){
						cityDiv.bgiframe();
					}
					if(hasDistrict){
						districtDiv.bgiframe();
					}
				}
				
			}
			
			var toBody = function(){
				if(hasprovince){
					provinceDiv.appendTo(document.body);
				}
				if(hasWorkCity){
					cityDiv.appendTo(document.body);
				}
				if(hasDistrict){
					districtDiv.appendTo(document.body);
				}
			};
			
			if($.browser.msie&&$.browser.version<8){
				//解决IE6,IE7代码未放在Ready时未完全加载
				$(function(){
					toBody();
				});
			}else{
				toBody();
			}
        }
		
		function init(citycodepro,defaultValue){
			var defaultValueLevel=citycodepro[0];
			var pValue=citycodepro[1];
			var tempPro=defaultValue.substring(0,5)+'000';
			if(parseInt(tempPro)>parseInt(foreignCode)){
				tempPro=foreignCode;
			}
			if(hasprovince){ 
				fillprovince(tempPro);
				if(remember){
					provinceDiv.find('a[v='+tempPro+']').addClass("select");
				}
			}
			var notInitIe=false;
			var predefaultValue="c"+defaultValue;
            if(!defaultValue||defaultValue.length<8){
				if(hasprovince){
					provinceInput.val(defaultTitle).attr("title",defaultTitle);
					provinceHide.val("-1");
				}
				if(hasWorkCity){
					cityInput.val(defaultTitle).attr("title",defaultTitle);;
					cityHide.val("-1");
				}
				notInitIe=true;
            }else{
				if(hasprovince){
					var text = getDistrictText(tempPro);
					provinceInput.val(text).attr("title",text);
					provinceHide.val(tempPro);
				}
				if(hasWorkCity){
					var text = getDistrictText(defaultValue);
					cityInput.val(text).attr("title",text);
					cityHide.val(defaultValue);
					var notFillWorkcity=false;
					if(cityLevel){
						if(cityLevel==2){
							//过滤
							var filterCitysLength=filterCitys.length;
							for(var i=0; i < filterCitysLength; i++){
								if(filterCitys[i]==defaultValue){
									//过滤层级
									cityDiv.html("");notFillWorkcity=true;
									if(tempPro==defaultValue){
										cityInput.val(defaultTitle).attr("title",defaultTitle);
										cityHide.val("-1");
									}
									break;
								}
							}
						}//others
					}
					if(defaultValue==tempPro){
						cityInput.val(defaultTitle).attr("title",defaultTitle);
						cityHide.val("-1");
						if(defaultJoin&&remember){
							cityDiv.find('a[v=-1]').addClass("select");
						}
					}
					if(!notFillWorkcity){
						fillWorkcity(tempPro);
						var prePro="c"+tempPro;
						if(2==defaultValueLevel){
							cityDiv.find('a[v='+defaultValue+']').addClass("select");
						}else if(3==defaultValueLevel){
							if(controlType==1){
								var text = getDistrictText(pValue) + " " + getDistrictText(defaultValue);
								cityInput.val(text).attr("title",text);
								if(remember){
									cityDiv.find('a[v='+defaultValue+']').addClass("select");
								}
							}else{
								var text = getDistrictText(pValue);
								cityInput.val(text).attr("title",text);;
								cityHide.val(pValue);
								if(remember){
									cityDiv.find('a[v='+pValue+']').addClass("select");
								}
							}
							
						}else{
							cityInput.val(defaultTitle).attr("title",defaultTitle);
							cityHide.val("-1");
							if(defaultJoin&&remember){
								cityDiv.find('a[v=-1]').addClass("select");
							}
						}
					}
				}
                
				if(hasDistrict){
					if(defaultValueLevel==3){
						var text = getDistrictText(defaultValue);
						districtInput.val(text).attr("title",text).show();
						fillDistrict(pValue);
						districtDiv.find('a[v='+defaultValue+']').addClass("select");
					}else if(defaultValueLevel==2){
						if("true"!=citycodepro[2]){
							var onlyOne=onlyOnePropety(defaultValue);
							if(onlyOne&&"-1"!=onlyOne){
								if(remember){
									districtDiv.find('a[v='+onlyOne+']').addClass("select");
								}
								var text = getDistrictText(onlyOne);
								districtInput.val(text).attr("title",text).show();
							}
							fillDistrict(defaultValue);
							districtInput.val(defaultTitle).attr("title",defaultTitle).show();
							if(defaultJoin&&remember){
								districtDiv.find('a[v=-1]').addClass("select");
							}
						}
					}
				}
            }
		}
		
		function fillprovince(value){
		   var mainDiv2='<div class="pleasechoosebox">'+settings.proviceTitle+'</div>';
		   var mainDivHot=[];
		   mainDivHot.push('<ul class="area_box_tr">');
		   var hotcityLength=hotcity.length;
		   for(var i=0;i<hotcityLength;i++){
			   var key= hotcity[i];
			   var hasFilter=false;
			   for( var j=0;j<filterProvince.length;j++){
					if(key==filterProvince[j]){hasFilter=true;continue;}
			   }
			   if(hasFilter){continue;}
			   var prefixkey="c"+key;
			   mainDivHot.push('<li><a href="javascript:void(0)" v="' + key + '">' + getDistrictText(key) + '</a></li>');
		   }
		   mainDivHot.push('</ul>');
		   var mainDivOther=[];
		   mainDivOther.push('<ul class="area_box_tr2">');
		   var hotcityOtherLength = hotcityOther.length;
		   for(var i=0;i<hotcityOtherLength;i++){
			   var key= hotcityOther[i];  
			       var hasFilter=false;
			       for( var j=0;j<filterProvince.length;j++){
					    if(key==filterProvince[j]){hasFilter=true;continue;}
			       }
			       if(hasFilter){continue;}
			       var prefixkey="c"+key;
			       mainDivOther.push('<li><a href="javascript:void(0)" v="'+key+'">'+syscode.district[prefixkey].n+'</a></li>');
		   }
		   if(defaultJoin){
				mainDivOther.push( '<li><a href="javascript:void(0)" v="-1">' + defaultTitle + '</a></li>');
			}
		   mainDivOther.push('</ul>');
		   var mainDivContent=[];
		   mainDivContent.push('<ul class="area_box_tr">');
		   //循环数据
		   for(var pro in syscode.district){
			   var prefixpro=pro;
			   var  pro=pro.substring(1,pro.length);
			   var hasFilter=false;
			   for( var j=0;j<filterProvince.length;j++){
					if(pro==filterProvince[j]){hasFilter=true;continue;}
			   }
			   if(hasFilter){continue;}
			   var tempHtml='<li><a href="javascript:void(0)" v="'+pro+'">'+syscode.district[prefixpro].n+'</a></li>';
			   var hasHot=false;
			   for(var i=0;i<hotcityLength;i++){if(hotcity[i]==pro){ hasHot=true;break;}}
			   if(hasHot){continue;}
			   for(var i=0;i<hotcityOtherLength;i++){if(hotcityOther[i]==pro){ hasHot=true;break;}}
			   if(hasHot){continue;}
			   mainDivContent.push(tempHtml);
		   }
		   
		   mainDivContent.push('</ul>');
		   mainDiv2+=(mainDivHot.join("") + mainDivContent.join("") + mainDivOther.join(""));
		   provinceDiv.html(mainDiv2);
           if($.fn.bgiframe){
				  provinceDiv.bgiframe();
		   }
		   provinceDiv.find("a").click(function(){
			   var $this=$(this);
			   var value= $this.attr('v');
			   loadDistrict(value,function(){
				   
				   if(settings.provinceSelectCallBack){
						var result = settings.provinceSelectCallBack.call(settings.provinceSelectCallBack,value);
						if(false === result){
							return false;
						}
					}
					if(remember){
					   var currentSelect=provinceDiv.find(".select");
					   if(currentSelect.length>0){
							currentSelect.removeClass("select");
					   }
					   $this.addClass("select");
					}

				   provinceSelect(value);
				});
			   return false;
		   });
       }
	   
	   function provinceSelect(value){
			var tempText = getDistrictText(value);
			provinceInput.val(tempText).attr("title",tempText);
			provinceDiv.hide();
			provinceHide.val(value);
			if(hasWorkCity){
			   cityInput.val(defaultTitle).attr("title",defaultTitle);
			   cityHide.val("-1");
			   if(foreignCode==value){
					var text = getDistrictText("10500000");
					cityInput.val(text).attr("title",text);
					cityHide.val("10500000");
				}
			   if("-1"==value){
				cityDiv.html("");
				return;
			   }
			   if(cityLevel){
					if(cityLevel==2){
						//过滤
						for(var i=0;i<hotcity.length;i++){
							if(hotcity[i]==value){cityDiv.html("");return;}
						}
					}
				}
			   fillWorkcity(value);

			   var onlyValue=onlyOnePropety(value);
			   var tofocus=true;
			   if(onlyValue&&"-1"!=onlyValue){
					if(!defaultJoin){
						var text = getDistrictText(onlyValue);
						cityInput.val(text).attr("title",text);
						cityHide.val(onlyValue);
						if(remember){
							cityDiv.find('a[v='+onlyValue+']').addClass("select");
						}
						tofocus=false;
					}
			   }
			   if(value==foreignCode){
					cityDiv.find('a[v=10500000]').addClass("select");
				}
				if(tofocus){
					cityInput.focus();
				}
					
			   if(defaultJoin&&remember){
					cityDiv.find('a[v=-1]').addClass("select");
				}
			   if(hasDistrict){
				   districtInput.hide();
				}
				
			} 
	   }

       function fillWorkcity(cityvalue){           
            var provinceValue=cityvalue.substring(0,5)+'000';
			var prefixprovinceValue="c"+provinceValue;
			var prefixcityvalue="c"+cityvalue;
			var valueCityLevel=1;
			if(parseInt(provinceValue)>parseInt(foreignCode)){
				provinceValue=foreignCode;
				prefixprovinceValue="c"+provinceValue;
			}
			var workcitys;
            if(provinceValue!=cityvalue){
				//var hasspecial=$.inArray(cityvalue,hotcity);
				/*if(hasspecial>0){
					alert("aaa");
					return;
				}else{*/
						workcitys=syscode.district[prefixprovinceValue][prefixcityvalue];
						valueCityLevel=3;
					
				//}
            }else{
				workcitys=syscode.district[prefixprovinceValue];
				valueCityLevel=2;
				
				if(provinceValue==foreignCode){valueCityLevel=1}
			}
			var tempHtml=[];
            if(workcitys){
                tempHtml.push('<div class="pleasechoosebox">'+settings.cityTitle+'</div><div class="city_box">');
				if(controlType==1 && valueCityLevel==2 && 1!=workcitys.d){
					if(defaultJoin&&cityvalue!=foreignCode){
						tempHtml.push('<div class="area_box_tr">');
						tempHtml.push('<a href="javascript:void(0)" v="-1">'+defaultTitle+'</a>');
						tempHtml.push('</div>');
					}
					for(var city in workcitys){
						var citypds = workcitys[city];
						if("n"==city || "d" ==city){
							continue;
						}
						var readCity=city.substring(1,city.length);
						tempHtml.push('<div class="area_box_tr">');
						tempHtml.push('<a class="' + (defaultJoin ? 'now2' : 'now') + '" href="javascript:void(0)" v="' + readCity + '">' + citypds.n+'</a>');
						for(var citychild in citypds){
							var name= citypds[citychild].n;
							var realcitychild=citychild.substring(1,citychild.length);
							if(name){
								tempHtml.push('<a href="javascript:void(0)" v="'+realcitychild+'">'+name+'</a>');
							}
						}
						tempHtml.push('</div>');
					}
				}else{
					tempHtml.push('<div class="area_box_tr2">');
					if(defaultJoin&&cityvalue!=foreignCode){
						tempHtml.push('<a href="javascript:void(0)" v="-1">'+defaultTitle+'</a>');
					}
					for(var citychild in workcitys){
						var name= workcitys[citychild].n;
						if(name){
							var citychildValue=citychild.substring(1,citychild.length);
							tempHtml.push('<a href="javascript:void(0)" v="'+citychildValue+'">'+name+'</a>');
						}
					}
					tempHtml.push('</div>');
				}
                tempHtml.push('</div>');
                cityDiv.html(tempHtml.join(""));
				
				var filter='a';
				if(controlType==1 && valueCityLevel==2){
					filter='a:not(".now")';
					cityDiv.find(".area_box_tr:last").removeClass("area_box_tr").addClass("area_box_tr2");
				}
                cityDiv.find(filter).click(function(){
                    var $this=$(this);
                    var value= $this.attr('v');
					if(settings.cityCallSelectBack){
						var result = settings.cityCallSelectBack.call(settings.cityCallSelectBack,value);
						if(result===false){
							return false;
						}
					}
                    citySelect(value);
					if(remember){
						var currentSelect=cityDiv.find(".select");
						if(currentSelect.length>0){
							currentSelect.removeClass("select");
						}
						$this.addClass("select");
					}
					
                    return false;
                });
				if($.fn.bgiframe){
				  cityDiv.bgiframe();
				}
            }else{
				cityDiv.html("");
			}
	
        }
		
		function citySelect(value){
			if(controlType==1){
				var cityvaluepro=cityValuePro(value).split(",");
				var hasspecial=-1;
				if(provinceInput){
					hasspecial=$.inArray(provinceHide.val(),hotcity);
				}
				if(cityvaluepro[0] != "3" || hasspecial>0){
					var text = getDistrictText(value);
					cityInput.val(text).attr("title",text);
				}else{
					var text = getDistrictText(cityvaluepro[1]) + " " + getDistrictText(value);
					cityInput.val(text).attr("title",text);
				}
			}else{
				var text = getDistrictText(value);
				cityInput.val(text).attr("title",text);
			}
			cityDiv.hide();
			cityHide.val(value);
			var provinceValue=value.substring(0,5)+'000';
			var prefixprovinceValue="c"+provinceValue;
			//国外特殊处理
		   if(parseInt(provinceValue)>parseInt(foreignCode)){
				return;
			}
			if(hasDistrict){
				if("true" == cityValuePro(value).split(",")[2]){
					return;
				}
				districtInput.val(defaultTitle).attr("title",defaultTitle);
				if("-1"==value){
					districtInput.hide();
					return;
				}
				fillDistrict(value);
				var onlyValue=onlyOnePropety(value);
				var tofocus=true;
				if(onlyValue&&"-1"!=onlyValue){
					if(!defaultJoin){
						var text = getDistrictText(onlyValue);
						districtInput.val(text).attr("title",text);
						cityHide.val(onlyValue);
						if(remember){
							districtDiv.find('a[v='+onlyValue+']').addClass("select");
						}
					//  修复只有单一三级城市依然显示提示语
					//	tofocus=false;
					}
				}
				if(defaultJoin&&remember){
					districtDiv.find('a[v=-1]').addClass("select");
				}
				if(tofocus){
					districtInput.show().focus();
				}else{
					districtInput.show();
				}
			}
		
		}
	
       function  fillDistrict(value){
           var provinceValue=value.substring(0,5)+'000';
		   var prefixprovinceValue="c"+provinceValue;
		   var prefixvalue="c"+value;
		   /*//国外特殊处理
		   if(parseInt(provinceValue)>parseInt(foreignCode)){
				return;
			}*/
           var workcitys=syscode.district[prefixprovinceValue][prefixvalue];
           var tempHtml=[];
		   if(workcitys){
               tempHtml.push('<div class="pleasechoosebox">'+settings.districtTitle+'</div><div class="city_box"><div class="area_box_tr2">');
			   if(defaultJoin&&value!=foreignCode){
					tempHtml.push('<a href="javascript:void(0)" v="-1">'+defaultTitle+'</a>');
				}
               for(var citychild in workcitys){
                   var name= workcitys[citychild].n;
                   if(name){
					   var citychildValue=citychild.substring(1,citychild.length);
                       tempHtml.push('<a href="javascript:void(0)" v="'+citychildValue+'">'+name+'</a>');
                   }
               }
               tempHtml.push('</div></div>');
               districtDiv.html(tempHtml.join(""));
               districtDiv.find("a").click(function(){
                   var $this=$(this);
                   var readlvalue= $this.attr('v');
					 if(settings.districtSelectBack){
						var result =  settings.districtSelectBack.call(settings.districtSelectBack,readlvalue);
						if(result === false){
							return false;
						}
					}
				   districtSelect(value,readlvalue);
				   if(remember){
						var currentSelect=districtDiv.find(".select");
						if(currentSelect.length>0){
							currentSelect.removeClass("select");
						}
						$this.addClass("select");
					}
                   return false;
               });
			   if($.fn.bgiframe){
			      districtDiv.bgiframe();
			   }
           }else{
				districtDiv.html("");
		   }
       }
	   
	   function districtSelect(value,readlvalue){
			if("-1"==readlvalue){
				readlvalue=value;
				 districtInput.val(defaultTitle).attr("title",defaultTitle);
		   }else{
				var text = getDistrictText(readlvalue);
				 districtInput.val(text).attr("title",text);
		   }
		   cityHide.val(readlvalue);
		   districtDiv.hide();
		   
	   }
	  
       function onlyOnePropety(value){
            if(!value||value.length<8){
                return "-1";
            }
			var province=value.substring(0,5)+'000';
			var prefixvalue="c"+value;
			var prefixprovince="c"+province; 
			//国外处理
			if(parseInt(province)>parseInt(foreignCode)){
				province=foreignCode;
				prefixprovince="c"+province; 
			}
            //如果是城市
            var workcitys;
            if(province==value){
                workcitys=syscode.district[prefixprovince];
            }else{
                workcitys=syscode.district[prefixprovince][prefixvalue];
            }

            if(!workcitys){
				var preWorkcitys = syscode.district[prefixprovince];
                for(var p in preWorkcitys){
                    if("n"==p || "d" ==p){continue;}
                    for(var pd in preWorkcitys[p]){
                        if("n"==pd || "d" ==pd){continue;}
                        if(pd==prefixvalue){
                            workcitys=preWorkcitys[prefixvalue];
                            break;
                        }
                    }
                }
            }
            var countP=0;
            var tempp="";
            for(var p in workcitys){
                if("n"==p || "d" ==p){continue;}
                if(countP>2){
                    break;
                }
                tempp=p;
                countP++;
            }
            if(countP==1&&tempp){
               return tempp.substring(1,tempp.length);
            }
            return "-1";
       }
	   
	   function getParentValue(value){
			return cityValuePro(value).split(",")[1];
	   }
	   
	   function getValueLevel(value){
			return cityValuePro(value).split(",")[0];
	   }
	   
		/**
		*返回 城市级别,父值CODE,是否是最底层值
		*/
	  function cityValuePro(value){
		if(!value||value.length<8){
			return "0,0,false";
		}
		var province=value.substring(0,5)+'000';
		var prefixprovince="c"+province;
		var preValue="c"+value;
		var parentValue='';
		if(parseInt(province)>parseInt(foreignCode)){
			province = foreignCode;
			prefixprovince= "c" + province;
		}
		var preWorkcitys=syscode.district[prefixprovince];
		var realWorkcitys;
		if(preWorkcitys){
			realWorkcitys=preWorkcitys[preValue];
		}
		if(syscode.district[preValue]){
			if(parseInt(province)<parseInt(foreignCode)){
				parentValue= "10100000";
			}else{
				parentValue= foreignCode;
			}
			return "1,"+parentValue+",false";
		}else if(realWorkcitys){
			parentValue=province;
			var hasThirdlevel=false;
			for(var p in realWorkcitys){
                if("n"==p || "d" ==p){
                    continue;
                }
				if(hasThirdlevel){
					break;
				}
				hasThirdlevel=true;
			 }
			 return "2,"+parentValue+","+(hasThirdlevel?false:true);
		}else{
		
			for(var p in preWorkcitys){
				if("n"==p || "d" ==p){
					continue;
				}
				for(var pp in preWorkcitys[p]){
					if(pp==preValue){
						parentValue=p.substring(1,p.length);
						return "3,"+parentValue+",true"
					}
				}
			}
		}
		 return "0,0,false";
	   }

	function getFullTextValue(value,addStr,callback){
		if(!value||value.length<8){;
			 return defaultTitle;
		}
		if(!addStr){
			addStr = " ";
		}
		var province=value.substring(0,5)+'000';
		var prefixprovince="c"+province;
		var preValue="c"+value;
		var parentValue='';
		if(parseInt(province)>parseInt(foreignCode)){
			province = foreignCode;
			prefixprovince= "c" + province;
		}
		var preWorkcitys=syscode.district[prefixprovince];
		var realWorkcitys;
		if(preWorkcitys){
			realWorkcitys=preWorkcitys[preValue];
		}
		if(syscode.district[preValue]){
			if(parseInt(province)<parseInt(foreignCode)){
				parentValue= "10100000";
			}else{
				parentValue= foreignCode;
			}
			return syscode.getDistrictText(value);
		}else if(realWorkcitys){
			parentValue=province;
			var hasThirdlevel=false;
			for(var p in realWorkcitys){
                if("n"==p || "d" ==p){
                    continue;
                }
				if(hasThirdlevel){
					break;
				}
				hasThirdlevel=true;
			 }
			 return  syscode.getDistrictText(province)+ addStr + syscode.getDistrictText(value);
		}else{
		
			for(var p in preWorkcitys){
				if("n"==p || "d" ==p){
					continue;
				}
				for(var pp in preWorkcitys[p]){
					if(pp==preValue){
						parentValue=p.substring(1,p.length);
						return syscode.getDistrictText(province)+ addStr + syscode.getDistrictText(parentValue) + addStr + syscode.getDistrictText(value);
					}
				}
			}
		}
		  return defaultTitle;
	   };

	   function getDistrictText(value){
			if(value=="-1"){
				return defaultTitle;
			}
			
			return syscode.getDistrictText(value);
	   }
	   loadDistrict(settings.defaultValue,function(){
	   		initWorkCity();
	   });
	   
	   return {
			//return init method
			initValue:function(value){
				var citycodepro = cityValuePro(value).split(",");
				init(citycodepro,value);
			},
			getFullText:function(value,addStr,callback){
				loadDistrict(value,function(){
					var result = getFullTextValue(value,addStr);
					if(callback){
						callback.call(callback,result);
					}
				});
			}
	   }
    }
    

})(jQuery, window);

