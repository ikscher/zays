//设置更多的搜索条件
//------------------------------------------------------------------------------------------------------
var searchConditionStr="";
var hash="TXlNZW1iZXJJZDotMS9hZ2VCZWdpbjoyNS9hZ2VFbmQ6NDUvc2V4OjEvaXNhbGw6MS9sZWVyOjEv%0AbWVtYmVydHlwZTotMS9QaG90bzoxL1dvcmtDaXR5OjEwMTAwMDAwL09yZGVyOmhwZi9QYWdlOjEv%0AUGFnZVNpemU6MjAv";
	$(function(){ 
	$(".carrow").hover(function () {
	$(this).css({'background-color':'#fff6fc'}); }, 
	function () { var cssObj = {'background-color':''}
	$(this).css(cssObj); });
	$(".darrow").hover(function () {
	$(this).css({'background-color':'#fff6fc'}); }, 
	function () { var cssObj = {'background-color':''}
	$(this).css(cssObj); });
	});
var tabtoggle = function(e,o){
	if($(o).is(":hidden")){
		$('li.carrow2').removeClass('carrow2').addClass('carrow');
		$('.commonlist').hide();
		$(o).show();
		$(e).addClass("carrow2");
		$(e).removeClass("carrow");
	}else{
		$(o).hide();
		$(e).addClass("carrow");
		$(e).removeClass("carrow2");
	}
};
var tabtoggle2 = function(e,o){
	if($(o).is(":hidden")){
		$('dt.darrow2').removeClass('darrow2').addClass('darrow');
		$('.datalist').hide();
		$(o).show();
		$(e).addClass("darrow2");
		$(e).removeClass("darrow");
	}else{
		$(o).hide();
		$(e).addClass("darrow");
		$(e).removeClass("darrow2");
	}
};

/*弹出层*/
function addConditionopen(){
	var bH=$("body").height();
	var bW=$("body").width()+0;
	$("#fullbg").css({"display":"block","width":bW,"height":bH});
	$("#group_id").css("display","block");
	$("#group").css("display","block");
	$("#search_more").css("display","none");
	$("#c2").css("display","none");
	$("#c5").css("display","none");
}
	$(function(){
	$('.Bl_3').click(function(){
	$('#group_id').css({"display":"block","left": "50%","top": "150px","margin-left":"-492px"});
	$('#group').fadeIn("1000");
	})	
	$('#closeDiv').click(function(){
		$('#group').animate({left: "0" }, { queue: false,complete: cssrain });
		$('#group').fadeOut( "600");
	  return false;})
	function cssrain(){
		   $('#group_id').css("display","none");
		   $("#fullbg").css("display","none");
	}})

//	function showBg(ct,content){
//	var bH=$("body").height();
//	var bW=$("body").width()+0;
//	var objWH=getObjWh(ct);
//	$("#fullbg").css({width:bW,height:bH,display:"block"});
//	var tbT=objWH.split("|")[0]+"px";
//	var tbL=objWH.split("|")[1]+"px";
//	$("#"+ct).css({top:tbT,left:tbL,display:"block"});
//	$("#"+content).html("<div style='text-align:center'>正在加载，请稍后...</div>");
//	$(window).scroll(function(){resetBg()});
//	$(window).resize(function(){resetBg()});
//	}
//	function getObjWh(obj){
//	var st=document.documentElement.scrollTop;//滚动条距顶部的距离
//	var sl=document.documentElement.scrollLeft;//滚动条距左边的距离
//	var ch=document.documentElement.clientHeight;//屏幕的高度
//	var cw=document.documentElement.clientWidth;//屏幕的宽度
//	var objH=$("#"+obj).height();//浮动对象的高度
//	var objW=$("#"+obj).width();//浮动对象的宽度
//	var objT=Number(st)+(Number(ch)-Number(objH))/2;
//	var objL=Number(sl)+(Number(cw)-Number(objW))/2;
//	return objT+"|"+objL;
//	}
	function resetBg(){
		var fullbg=$("#fullbg").css("display");
	}
/*end 弹出层2010-10-17*/

//-->

//被选择
var singleSelectedEdit = function(o) {
	$('.darrow').removeClass('darrow current').addClass('darrow');
	$('.datalist').css('display','none');
	$('#personal_'+o).removeClass('darrow').addClass('darrow current');
	$('#personal_view_'+o).css('display','');
}
//-->


//根据性别变换body
var changeBody = function(value){
	var bodybtbt = document.getElementById("bodybtbt");
	var htmlStr="";
	if(value=='20'){
		bodybtbt.innerHTML=syscode.checkboxHtml2('body','body','',syscode.body0,'p','0,不限','body');
		if($('#body_title').size()>0){
			$('#body_title').remove();
		}
	}else if(value=='1'){
		bodybtbt.innerHTML=syscode.checkboxHtml2('body','body','',syscode.body1,'p','0,不限','body');
		if($('#body_title').size()>0){
			$('#body_title').remove();
		}
	}else if(value=='0'){
		bodybtbt.innerHTML=syscode.checkboxHtml2('body','body','',syscode.body0,'p','0,不限','body');
		if($('#body_title').size()>0){
			$('#body_title').remove();
		}
	}
}
//--------------------------------------------------------------

var selectedFiled = function(syscodeapi,currentsearchvalue,objstr,nextSearchField) {
	  currentsearchvalue = currentsearchvalue.substring(1,currentsearchvalue.length-1);
	  var salaryrealValueStr = currentsearchvalue;
	  var salaryrealValueId = salaryrealValueStr.split(",");
	  if(salaryrealValueId.length>2) {
	  		document.write('<li class="label"><a href="##" onclick="removeOldSelected(this);delCurrentItem(\''+nextSearchField+'\',\''+currentsearchvalue+'\');">'+salaryrealValueId.length+'个搜索条件</a></li>');
	  } else {
		  var salaryId = new Array();
		  var salaryViewStr = new Array();
		  for(var i=0;i<syscodeapi.length;i++) {
		  	temp = syscodeapi[i].split(",");
			salaryId[i]=temp[0];
			salaryViewStr[i]=temp[1];		
	  	  }
		for(var j=0;j<salaryrealValueId.length;j++) {
	    	for(var k=0;k<salaryId.length;k++) {
	        if(salaryrealValueId[j]==salaryId[k]) {
				if(nextSearchField=='TongueGift'){
					document.write('<li class="label"><a  href="##" id="'+objstr+salaryId[k]+'" onclick="removeOldSelected(this);delTonguegift(\''+salaryId[k]+'\');">'+salaryViewStr[k]+'</a></li>');
				} else {
					document.write('<li class="label"><a  href="##" id="'+objstr+salaryId[k]+'" onclick="removeOldSelected(this);delCurrentItem(\''+nextSearchField+'\',\''+salaryId[k]+'\');">'+salaryViewStr[k]+'</a></li>');
				} 
			}
		}
	}	  
}
}
var selectedFiled2 = function(syscodeapi,currentsearchvalue,objstr,nextSearchField) {
	currentsearchvalue = currentsearchvalue.substring(1,currentsearchvalue.length-1);
	var salaryrealValueStr = currentsearchvalue;
	var salaryrealValueId = salaryrealValueStr.split(",");
	var salaryId = new Array();
	var salaryViewStr = new Array();
	for(var i=0;i<syscodeapi.length;i++) {
		temp = syscodeapi[i].split(",");
		salaryId[i]=temp[0];
		salaryViewStr[i]=temp[1];		
	}
	for(var j=0;j<salaryrealValueId.length;j++) {
		for(var k=0;k<salaryId.length;k++) {
			if(salaryrealValueId[j]==salaryId[k]) {
	document.write('<li class="label"><a href="##" id="'+objstr+"_view_"+salaryId[k]+'" onclick="removeOldSelected(this);doCheckedFalse(\''+nextSearchField+'\',\''+salaryId[k]+'\')">'+salaryViewStr[k]+'</a></li>');
			}
		}
	}	  
}
//--------------------------------------------------------

//-------------------------------------------------------
var checkBuXian = function(v,o){if(v==',0,'){document.getElementById(o).checked="checked";} else {document.getElementById(o).checked="";}} 
//-----------------------------------------------------

//-------------------------------------------------------------
var baseSelectedEdit = function() {
	$('.darrow').removeClass('darrow current').addClass('darrow');
	$('.datalist').css('display','none');
	if($('#marriage_extends').size()>0) {updateSelectedClass('marriage');}
	if($('#salary_extends').size()>0) {updateSelectedClass('salary');}
	if($('#education_extends').size()>0) {updateSelectedClass('education');}
	if($('#height_extends').size()>0) {updateSelectedClass('height');}
	if($('#house_extends').size()>0) {updateSelectedClass('house');}
	if($('#vehicle_extends').size()>0) {updateSelectedClass('vehicle');}
	if($('#hometown_province_Id').size()>0) {updateSelectedClass('hometown');}
	if($('#ismoking_extends').size()>0) {updateSelectedClass('ismok');}
	if($('#isdrinking_extends').size()>0) {updateSelectedClass('isdrink');}
	if($('#children_extends').size()>0) {updateSelectedClass('child');}
	if($('#wantchildren_extends').size()>0) {updateSelectedClass('wtchildren');}
	if($('#weight_extends').size()>0) {updateSelectedClass('weight');}
	if($('#body_extends').size()>0) {updateSelectedClass('body');}
	if($('#stock_extends').size()>0) {updateSelectedClass('stock');}
	if($('#animals_extends').size()>0) {updateSelectedClass('animals');}
	if($('#constellation_extends').size()>0) {updateSelectedClass('constellation');}
	if($('#bloodtype_extends').size()>0) {updateSelectedClass('bty');}
	if($('#occupation_extends').size()>0) {updateSelectedClass('occupation');}
	if($('#ct_extends').size()>0) {updateSelectedClass('corptp');}
	if($('#belief_extends').size()>0) {updateSelectedClass('belief');}
	if($('#tonguegift_extends').size()>0) {updateSelectedClass('tonguegift');}
	if($('#family_extends').size()>0) {updateSelectedClass('family');}
}

var updateSelectedClass = function(o) {
	$('#personal_'+o).removeClass('darrow').addClass('darrow current');
}
//-------------------------------------------------------------------------



//-------------------------------------------------------------------
var bsgsx = false;
var cookieWorkCity = false;//getCookieWorkCity();
if(!!cookieWorkCity && ",10102000,10103000,10101201,10101002,10107002,".indexOf(","+cookieWorkCity+",") >= 0){
	bsgsx=true;
}
//var memberid = getCookieMemberid();
var curURL = window.location.href;
function setCurItem() {
	if(curURL.indexOf("/personal/mymainPage.jsps") > 0 || curURL.indexOf("/vipmatch/vipmatchindex.jsps") > 0 || curURL.indexOf("/personal/getmyleers.jsps") > 0 || curURL.indexOf("/personal/getsendleerto.jsps") > 0 || curURL.indexOf("/personal/whoismyfriends.jsps") > 0 || curURL.indexOf("/personal/iswhosefriends.jsps") > 0 || curURL.indexOf("/match/matchlist.jsps") > 0 || curURL.indexOf("/match/matchlist.jsps?sendorreceive=2") > 0 || curURL.indexOf("/match/matchlist.jsps?sendorreceive=1") > 0 || curURL.indexOf("/personal/getlatestvisitor.jsps") > 0 || curURL.indexOf("/personal/getlatestvisitors.jsps") > 0 || curURL.indexOf("/personal/getupphotorequestlist.jsps") > 0 || curURL.indexOf("/personal/getupphotorequestlist.jsps?type=1") > 0 || curURL.indexOf("/personal/getupphotorequestlist.jsps?type=2") > 0 || curURL.indexOf("/personal/searchCreditmeindex.jsps") > 0 || curURL.indexOf("/personal/searchCreditme.jsps") > 0 || curURL.indexOf("/diary/adddiarypre.jsps") > 0 || curURL.indexOf("/diary/hotdiaryslist.jsps") > 0 || curURL.indexOf("/profile/getDiary.jsps?diaryid") > 0 || curURL.indexOf("/diary/mydiaryslist.jsps") > 0 || curURL.indexOf("/personal/getcontactme.jsps") > 0 || curURL.indexOf("/personal/getmycontacts.jsps") > 0 || curURL.indexOf("/personal/getmaillist.jsps") > 0 || curURL.indexOf("rpuSource=home") > 0 || curURL.indexOf("/idcard/") > 0) {
		document.write('<div id="headA" class="navBox">');
	} else if(curURL.indexOf("/search/") > 0 || curURL.indexOf("/lable/") > 0) {
		document.write('<div id="headB" class="navBox">');
	} else if(curURL.indexOf("/personal/material.jsps") > 0) {
		document.write('<div id="headC" class="navBox">');
	} else if(curURL.indexOf("/credit/convinceindex.jsps") > 0 || curURL.indexOf("/profile/validateContactPre.jsps") > 0 || curURL.indexOf("/video/videoindex.jsps") > 0 || curURL.indexOf("/credit/creditIndex.jsps") > 0 || curURL.indexOf("/credit/creditadvance.jsps") > 0 || curURL.indexOf("/credit/creditadvance-step") > 0 || curURL.indexOf("/personal/accountindex.jsps") > 0 || curURL.indexOf("/personal/accountinfo.jsps") > 0 ||curURL.indexOf("/personal/memberestate.jsps") > 0 || curURL.indexOf("/personal/zoarea.jsps") > 0 || curURL.indexOf("/personal/lucida.jsps") > 0 || curURL.indexOf("/personal/automatic.jsps") > 0 || curURL.indexOf("/personal/serv_emailsubscibe.jsps") > 0 || curURL.indexOf("/personal/listMyAccountInfo.jsps") > 0 || curURL.indexOf("/personal/memblockette.jsps") > 0 || curURL.indexOf("/personal/membblockette.jsps") > 0 || curURL.indexOf("/personal/serv_subscibePre.jsps") > 0 || curURL.indexOf("/credit/gztindex.jsps") > 0 || curURL.indexOf("/personal/serv_emailvalidate.jsps") > 0) {
		document.write('<div id="headE" class="navBox">');
	} else if(curURL.indexOf("story.zhenai.com") > 0 || curURL.indexOf("/lovestory/") > 0) {
		document.write('<div id="headF" class="navBox">');
	} else if(curURL.indexOf("/qingyuan/") > 0 ) {
		document.write('<div id="headD" class="navBox">');
	} else if(curURL.indexOf("/personal/loveqa") > 0){
		document.write('<div id="headG" class="navBox">');
	} else if(!!bsgsx && curURL.indexOf("/im/netlove.jsps") > 0){
		document.write('<div id="headH" class="navBox">');
	} else {
		document.write('<div class="navBox">');
	}
}
//------------------------------------------------------------------------

//----------------------------------------------------------------------------
function checkNavigator() {
  if (navigator.appName != "Microsoft Internet Explorer") {
    window.document.location="/error/errorIE.jsp";
   }
}

function getCookieVal(offset) {
	try{
		var endstr = document.cookie.indexOf (";", offset);
		if (endstr == -1)
		endstr = document.cookie.length;
		//return unescape(document.cookie.substring(offset, endstr));
		return document.cookie.substring(offset, endstr);
	}catch(e){
		return "";
	}
}

function GetCookie (name) {  
	var arg = name + "=";  
	var alen = arg.length;  
	var clen = document.cookie.length;  
	var i = 0;  
	while (i < clen) {    
	var j = i + alen;    
	if (document.cookie.substring(i, j) == arg)      
	return getCookieVal (j);    
	i = document.cookie.indexOf(" ", i) + 1;    
	if (i == 0) break;   
	}  
	return "";
}
function SetCookie (name, value) { 
	var exp1 = new Date(); 
	exp1.setTime(exp1.getTime() + (30*24*60*60*1000));
	var argv = SetCookie.arguments;  
	var argc = SetCookie.arguments.length;  
	var expires = (argc > 2) ? argv[2] : exp1;  
	var path = (argc > 3) ? argv[3] : '/';  
	var domain = (argc > 4) ? argv[4] : null;  
	var secure = (argc > 5) ? argv[5] : false;  
	document.cookie = name + "=" + escape (value) + 
	((expires == null) ? "" : ("; expires=" + expires.toGMTString())) + 
	((path == null) ? "" : ("; path=" + path)) +  
	((domain == null) ? "" : ("; domain=" + domain)) +    
	((secure == true) ? "; secure" : "");
}
function DeleteCookie (name) {  
	var exp = new Date();  
	exp.setTime (exp.getTime() - 1);  
	var cval = GetCookie (name);  
	document.cookie = name + "=" + cval + "; expires=" + exp.toGMTString();
}
/*
Function Desc:	Trim String
Function Num:	4
Function List:  Trim(str)
				LTrim(str)
				RTrim(str)
				AllTrim(str)
*/
function Trim(ui){ 
	var notValid=/(^\s)|(\s$)/; 
	while(notValid.test(ui))
	{ 
		ui=ui.replace(notValid,"");
	} 
	return ui;
} 

function LTrim(ui){
	var notValid=/^\s/; 
	while(notValid.test(ui))
	{ 
		ui=ui.replace(notValid,"");
	} 
	return ui;
} 

function RTrim(ui){ 
	var notValid=/\s$/; 
	while(notValid.test(ui))
	{ 
		ui=ui.replace(notValid,"");
	} 
	return ui;
} 

function AllTrim(ui){ 
	var notValid=/\s/; 
	while(notValid.test(ui))
	{ 
		ui=ui.replace(notValid,"");
	} 
	return ui;
} 

function IsNum(str)
{
	var sReg = /\D+/;
	if ( sReg.test(str) )
	{
		return false;
	}
	return true;
}

//name
function CheckTrueName(str){
	var sReg = /\d|\s/;
	if ( sReg.test(str) )
	{
		alert("??????????????????????????????");
		return false;
	}
	//????????????????????
	var sReg = /[a-zA-Z]/;
	if ( sReg.test(str) )
	{
		alert("????????????????????????????\n??????????????????????????????????????????????");
		return false;
	}
	if ( str.length > 4 )
	{
		alert("长度不能超过4！");
		return false;
	}

	return true;
}

//Email
function CheckEmail(str){
	var sReg = /[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/;
	//var emailRegexp = "^([a-zA-Z0-9_]+[\\.a-zA-Z0-9_-]*){1,}@([a-zA-Z0-9-]+\\.){1,}(com|org|net|edu|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|az|ax|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gsslands|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mkc of|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$";
	
	if ( ! sReg.test(str) )
	{
		return false;
	}

	return true;
}



var selectedCount=0;

function getSelectedIDs(obj){
      var selectedIDs="";
      //obj=(obj==undefined)?eval("document.forms[0].checkObject"):obj;
      obj=("undefined"==typeof(obj))?eval("document.forms[0].checkObject"):obj;
      if (obj == null) {return selectedIDs;}
      if(obj.length){
          for(var i=0;i<obj.length;i++){
             if(obj[i].checked){
                 selectedCount++;
		         if(selectedIDs=="")
	                selectedIDs=obj[i].value;
			     else 
	                selectedIDs += "," + obj[i].value;
	         }
          }
       }
       else{
          if(obj.checked){
             selectedCount++;
             selectedIDs=obj.value;
          }
       }
       return selectedIDs;
   }
   
function selectAll(obj){
  if (obj == null) {
    alert("没有选项可选！");
    return;
  }
  
  if(obj.length){
    for(var i=0;i<obj.length;i++){
      obj[i].checked=true;	
    }	
  } else{
    obj.checked=true;
  }
}

function selectNothing(obj){
  if (obj == null) {
    alert("没有选项可选！");
    return;
  }
  
  if(obj.length){
    for(var i=0;i<obj.length;i++){
      obj[i].checked=false;	
    }	
  } else{
    obj.checked=false;
  }
}

function selectSwitch(obj1,obj2){
   if(obj1.checked==true){
     selectAll(obj2);
   }else{
     selectNothing(obj2);
   }
}

function goPre(formName){
	
       if(page==1){
    	alert("已经是第一页！");
    	return;
    	}
      if(page>1){
          page--;
          formName.pageIndex.value=page;
          formName.submit();
      }
  }

function goNext(formName){
	
      if(page==pages){
    	alert("已经是最后一页！");
    	return;
    	}
      if(page<pages){
          page++;
          formName.pageIndex.value=page;		  
          formName.submit();
      }
  }

function goto(formName,pageIndex){
     formName.pageIndex.value=pageIndex;
     formName.submit();
  }
  
function gotoFirstPage(formName){
    if(page==1){
    	alert("已经是第一页！");
    	return;
    	}
    formName.pageIndex.value=1;
    formName.submit();
}


function gotoLastPage(formName){
    if(page==pages){
    	alert("已经是最后一页！");
    	return;
    	}
    formName.pageIndex.value=pages;
    formName.submit();
}


function zoom(srcImg,desImg,photoWidth,photoHeight){
   var sWidth=srcImg.width;
   var sHeight=srcImg.height;
   
   var scale=1;
   var sacleW=photoWidth/sWidth;
   var scaleH=photoHeight/sHeight;

   if(sWidth>photoWidth){
     if(sHeight>photoHeight){
       if(sacleW>scaleH){
         desImg.width=sWidth*scaleH;
         desImg.height=photoHeight;
       }
       else {
         desImg.width=photoWidth;
         desImg.height=sHeight*sacleW;
       }
     }
     else{
       desImg.width=photoWidth;
       desImg.height=sHeight*sacleW;
     }
   }
   else if(sHeight>photoHeight){
    desImg.width=sWidth*scaleH;
    desImg.height=photoHeight;  
   }
   else{
    desImg.width=sWidth;
    desImg.height=sHeight;  
   }
  desImg.src=srcImg.src;
}


function textCounter(field,countfield,leavingsfield,maxlimit) {
	if (field.value.length > maxlimit) // if too long...trim it!
	{
	  field.value = field.value.substring(0, maxlimit);
	  alert(" ??"+maxlimit+"??????");
	}
	// otherwise, update 'characters left' counter
        else{ 
	  //countfield.value = maxlimit - field.value.length;
	 if(leavingsfield!=null)
	   leavingsfield.innerHTML=maxlimit - field.value.length;
	 if(countfield!=null)
	   countfield.innerHTML=field.value.length;
	}
} 


function doselAll(strform,selectedallobj,itemobj)
{	var i=0;
	var selallobj;		
	var itemobjed;
	selallobj=eval("document."+strform+"."+selectedallobj);
	itemobjed=eval("document."+strform+"."+itemobj);
	if (itemobjed!=null)
		 if(selallobj.checked)
			{
				if (isNaN(itemobjed.length)){
				   if (!itemobjed.disabled)
					itemobjed.checked=true;
				}	
				else{
				    for(i=0;i<itemobjed.length;i++){
				    	if (!itemobjed[i].disabled)
					   itemobjed[i].checked=true;
				    }	
				}			  
			 }
		else
			{
				if (isNaN(itemobjed.length))
					itemobjed.checked=false;
				else{
				    for(i=0;i<itemobjed.length;i++)
					itemobjed[i].checked=false;
				}				
			}
}

function CheckEmail2(str){
	var sReg = /[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/;
	if ( ! sReg.test(str) )
	{
		return false;
	}

	return true;
}


function getFrameVars() { 
    var fVars     = new Object();       // Create new fVars object
    var nameVal   = "";                 // Holds array for a single name-value pair
    //var inString  = location.search;    // Get query string from URL
	var argv = getFrameVars.arguments;  
	var argc = getFrameVars.arguments.length;  
	var inString = (argc > 0) ? argv[0] : location.search;  

    var separator = ",";                // Character used to separate multiple values 

   // If URL contains query string 
	//if (inString.charAt(0) == "?") 
	if (inString.indexOf("?") != -1 ) 
    { 
        // Removes "?" character from query string. 
        inString = inString.substring(1, inString.length); 
        // Separates query string into name-value pairs. 
		keypairs = inString.split("&"); 
        // Loops through name-value pairs. 
        for (var i=0; i < keypairs.length; i++) 
		{ 
            // Splits name-value into array (nameVal[0]=name, nameVal[1]=value). 
            nameVal = keypairs[i].split("=");
            // Checks to see if name already exists 
			if (fVars[nameVal[0]]) 
            { 
			   fVars[nameVal[0]] += separator + nameVal[1]; 
            } 
            else 
            { 
               fVars[nameVal[0]] = nameVal[1]; 
            }
        } 
    } 
	return fVars; 
} 
FrameVars=getFrameVars();

function getParameter(parameterName){
	return eval("FrameVars."+parameterName);
}

//**控制键盘的输入*/
function controlKeyInput(obj,reg){//reg是正则表达式
  	if ( event.keyCode!='37'&&event.keyCode!='39' ){ //不是左右箭头
   		obj.value=obj.value.replace(reg,'');
  	}
}
//-------------------------------------------------------------------------------

//----------------------------------------------------------------------
var delCruSearchResultHeight = function(o){
	var oo = $(o);
	oo.parent().parent().remove();
	$('#h1').attr('value',0);
	$('#h2').attr('value',0);
	$('#personal_height').removeClass('current darrow2').addClass('darrow2');
	$('#personal_view_height').css("display","none");		
}
//---------------------------------------------------------------------

//---------------------------------------------------------------------
var delCruSearchResultHometown = function(o){
	var oo = $(o);
	oo.parent().parent().remove();
	document.getElementById('areaForm.workProvince1').value=0;
	document.getElementById('areaForm.workCity1').value=0;	
	$('#personal_hometown').removeClass('current darrow2').addClass('darrow2');
	$('#personal_view_hometown').css("display","none");
}
//------------------------------------------------------------------------

//---------------------------------------------------------------------------
var delCruSearchResultWeight = function(o){
	var oo = $(o);
	oo.parent().parent().remove();
	$('#w1').attr('value',0);
	$('#w2').attr('value',0);
	$('#personal_weight').removeClass('current darrow2').addClass('darrow2');
	$('#personal_view_weight').css("display","none");		
}
//---------------------------------------------------------------------------

//------------------------------------------------------------------------------------------------
var doCheckedFalse = function(o,v){
	$('#'+o+"_"+v).attr("checked",false);
}
var doDivide = function(o,p){
	var idtemp = new Array();
	var temp2 = new Array();
	var obj = o.find('li');
	for(var i=p,j=0;i<obj.size();i++,j++){
		idtemp[j]=obj.eq(i).children().attr('id');
	}
	for(var x=0;x<idtemp.length;x++){
		temp2 =idtemp[x].split("_");
		doCheckedFalse(temp2[0],temp2[2]);
	}
}
var doDel = function(o){
		var oo = $('#'+o);
		if(oo.find('li').eq(1).children().attr('tagName')=='A'){
		//全部是层上添加的
			doDivide(oo,1);
		} else{
			doDivide(oo,2);
		}
		$('#'+o).remove();
		var temp = new Array();
		temp=o.split("_");
		$('#personal_'+temp[0]).removeClass('current darrow').addClass('darrow');
		document.getElementById(temp[0]+"_0").checked="checked";
}
var delAllItem2 = function(){
	if($('#marriage_title').size()>0){doDel('marriage_title');} 	
	if($('#education_title').size()>0){doDel('education_title');}
	if($('#salary_title').size()>0){doDel('salary_title');}
	if($('#height_title').size()>0){$('#height_title').remove();$('#h1').attr('value',0);	$('#h2').attr('value',0);}
	if($('#house_title').size()>0){doDel('house_title');}
	if($('#vehicle_title').size()>0){doDel('vehicle_title');}
	if($('#hometown_title').size()>0){$('#hometown_title').remove();document.getElementById('areaForm.workProvince1').value=0;document.getElementById('areaForm.workCity1').value=0;}
	if($('#ismok_title').size()>0){doDel('ismok_title');}
	if($('#idrink_title').size()>0){doDel('idrink_title');}
	if($('#child_title').size()>0){doDel('child_title');}
	if($('#wtchildren_title').size()>0){doDel('wtchildren_title');}
	if($('#weight_title').size()>0){$('#weight_title').remove();$('#w1').attr('value',0);$('#w2').attr('value',0);}
	if($('#body_title').size()>0){doDel('body_title');}
	if($('#stock_title').size()>0){$('#stock_title').remove();$('#stock').attr('value',-1);}
	if($('#animals_title').size()>0){doDel('animals_title');}
	if($('#constellation_title').size()>0){doDel('constellation_title');}
	if($('#bty_title').size()>0){doDel('bty_title');}
	if($('#occupation_title').size()>0){doDel('occupation_title');}
	if($('#corptp_title').size()>0){doDel('corptp_title');}
	if($('#belief_title').size()>0){doDel('belief_title');}
	if($('#tonguegift_title').size()>0){doDel('tonguegift_title');}
	if($('#family_title').size()>0){doDel('family_title');}
	if($('#marriage_title').size()==0  && $('#education_title').size()==0  && $('#salary_title').size()==0 && $('#height_title').size()==0 && $('#house_title').size()==0  && $('#vehicle_title').size()==0  && $('#hometown_title').size()==0  && $('#ismok_title').size()==0  && $('#idrink_title').size()==0 && $('#child_title').size()==0  && $('#wtchildren_title').size()==0  && $('#weight_title').size()==0  && $('#body_title').size()==0  && $('#stock_title').size()==0  && $('#animals_title').size()==0  && $('#constellation_title').size()==0 && $('#bty_title').size()==0  && $('#occupation_title').size()==0  && $('#corptp_title').size()==0  && $('#belief_title').size()==0 && $('#tonguegift_title').size()==0 && $('#family_title').size()==0  ) {
	$('#alldelete2').css("display","none");}}
//-------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------
 var addCondition = function(o,value,title) {
 var html;
	if($('#'+o+"_title").size()>0) {
		//有节点
		if($('#'+o+"_view_"+value).size()>0) {
			//子节点已存在，删除之
			if($('#'+o+"_view_"+value).parent().parent().find('li').eq(1).children().attr('tagName')=='A') {
				//有一个子节点就删除根节点
				if($('#'+o+"_view_"+value).parent().parent().find('li').size()==2) {
					$('#'+o+"_view_"+value).parent().parent().remove();
				} else {
					$('#'+o+"_view_"+value).parent().remove();				
				}
			} else {
				if($('#'+o+"_view_"+value).parent().parent().find('li').size()==3) {
					$('#'+o+"_view_"+value).parent().parent().remove();
				} else {
					$('#'+o+"_view_"+value).parent().remove();				
				}
			}
		} else {
			html='<li class="label"><a href="##" onclick="removeNewSelected(this);doCheckedFalse(\''+o+'\','+value+');" id="'+o+'_view_'+value+'">'+title+'</a></li>';
			$('#'+o+"_title").append(html);
		}

	} else {
		//没节点
		html='<ul id="'+o+'_title" class="scbox2"><li class="title Bl_3"><span class="tred">'
		if(o=='child') {
			html+='是否有孩子';
		} else if(o=='wtchildren') {
			html+='是否想要孩子';
		} else if(o=='ismok') {
			html+='是否抽烟';
		} else if(o=='idrink') {
			html+='是否喝酒';
		} else if(o=='body') {
			html+='体型';
		} else if(o=='animals') {
			html+='生肖';
		} else if(o=='constellation') {
			html+='星座';
		} else if(o=='bty') {
			html+='血型';
		} else if(o=='marriage') {
			html+='婚姻状况';
		} else if(o=='education') {
			html+='教育程度';
		} else if(o=='salary') {
			html+='月收入';
		} else if(o=='occupation') {
			html+='从事职业';
		} else if(o=='corptp') {
			html+='公司类别';
		} else if(o=='belief') {
			html+='信仰';
		} else if(o=='family') {
			html+='兄弟姐妹';
		} else if(o=='house') {
			html+='住房情况';
		} else if(o=='vehicle') {
			html+='是否购车';
		} else if(o=='tonguegift') {
			html+='语言能力';
		} 
		html+='</span></li>';
		html+='<li class="label"><a href="##" onclick="removeNewSelected(this);doCheckedFalse(\''+o+'\','+value+');" id="'+o+'_view_'+value+'">'+title+'</a></li>';	
		html+='</ul>';		
		$('#addSearchCondition').append(html);
		$('#personal_'+o).removeClass('darrow').addClass('darrow current');
	}
	$('#alldelete2').css("display","");		
 }
 var removeNewSelected=function(o){
	var oo=$(o);
	var id=oo.attr('id');
	if(oo.parent().parent().find('li').size()==2){
		if(oo.parent().parent().parent().find('ul').size()==1){
		//最后一个同时要隐藏全部清除
				$('#alldelete2').css("display","none");			
		}
		var temp = new Array();
		temp=id.split("_");
		$('#personal_'+temp[0]).removeClass('current darrow2').addClass('darrow');
		$('#personal_view_'+temp[0]).removeClass('datalist2').addClass('datalist');	
		$('#personal_view_'+temp[0]).css("display","none");				
		oo.parent().parent().remove();
	}else{
		oo.parent().remove();
	}
 }
 var removeOldSelected=function(o){
	var oo=$(o);
	var id=oo.attr('id');
	if(oo.parent().parent().find('li').size()==3){
		if(oo.parent().parent().parent().find('ul').size()==1){
		//最后一个同时要隐藏全部清除
				$('#alldelete2').css("display","none");			
		}
		var temp = new Array();
		temp=id.split("_");
		$('#personal_'+temp[0]).removeClass('current darrow2').addClass('darrow');
		$('#personal_view_'+temp[0]).removeClass('datalist2').addClass('datalist');	
		$('#personal_view_'+temp[0]).css("display","none");				
		oo.parent().parent().remove();			
	}else{
		oo.parent().remove();
	}
 }
//-------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------
var addHeight = function() {
	var a = $('#h1').attr('value');
	var b = $('#h2').attr('value');
	if(a==0 && b==0) {
		if($('#height_title').size()>0) {
			$('#height_title').remove();
		}
		return false;
	}
	if(a!=0 && b!=0 && a > b) {
		$('#h1').focus();
		alert('您选择的‘身高’不正确');
		return false;
	} else {
	if($('#height_title').size()>0) {
		$('#height_title').remove();
	}
		html='<ul id="height_title" class="scbox2"><li class="title Bl_3"><span class="tred">'
		html+='身高';
		html+='</span></li>';
		html+='<li class="label"><a href="##" onclick="removeNewSelected(this);removeNewAddedHeight();">';
		if(a>0 && b>0){
			html+=a;;
			html+='cm 到';
			html+=b;
			html+='cm';				
		} else if(a==0 && b>0) {
			html+=b;
			html+='cm以下';
		} else if(a>0 && b==0) {
			html+=a;
			html+='cm以上';
		}			
		html+='</a></li>';	
		html+='</ul>';		
		$('#addSearchCondition').append(html);
		$('#personal_height').removeClass('darrow2').addClass('current darrow2');			
	}
	$('#alldelete2').css("display","");
}
var removeNewAddedHeight = function() {
	$('#h1').attr('value',0);
	$('#h2').attr('value',0);
	$('#personal_height').removeClass('current darrow2').addClass('darrow2');
	$('#personal_view_height').css("display","none");		
}
//-------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------
var addWeight = function() {
	var a = $('#w1').attr('value');
	var b = $('#w2').attr('value');
	if(a==0 && b==0) {
		if($('#weight_title').size()>0) {
			$('#weight_title').remove();
		}
		return false;
	}
	if(a!=0 && b!=0 && a > b) {
		$('#w1').focus();
		alert('您选择的‘体重’不正确');
		return false;
	} else {
	if($('#weight_title').size()>0) {
		$('#weight_title').remove();
	}
		html='<ul id="weight_title" class="scbox2"><li class="title Bl_3"><span class="tred">'
		html+='体重';
		html+='</span></li>';
		html+='<li class="label"><a href="##" onclick="removeNewSelected(this);removeNewAddedWeight();">';
		if(a>0 && b>0){
			html+=a;;
			html+='kg 到 ';
			html+=b;
			html+='kg';				
		} else if(a==0 && b>0) {
			html+=b;
			html+='kg以下';
		} else if(a>0 && b==0) {
			html+=a;
			html+='kg以上';
		}			
		html+='</a></li>';	
		html+='</ul>';		
		$('#addSearchCondition').append(html);
		$('#personal_weight').removeClass('darrow2').addClass('current darrow2');						
	}
	$('#alldelete2').css("display","");
}
var removeNewAddedWeight = function() {
	$('#w1').attr('value',0);
	$('#w2').attr('value',0);
	$('#personal_weight').removeClass('current darrow2').addClass('darrow2');
	$('#personal_view_weight').css("display","none");		
}
//-------------------------------------------------------------------------------------------

//-------------------------------------------------------------------------------------------
var addHometown = function(){
	var provinceId=document.getElementById('areaForm.workProvince1').value;
	var cityId=document.getElementById('areaForm.workCity1').value;
	if(provinceId==0){
		if($('#hometown_title').size()>0) {
			$('#hometown_title').remove();
		}
		document.getElementById('areaForm.workProvince1').focus();
		return false;
	}
	if($('#hometown_title').size()>0) {
		$('#hometown_title').remove();
	}
	var hometown = '';
	if(provinceId==2){
		var temp = new Array();
		var tempId = new Array();
		var tempViewStr = new Array();		
		for(var i=0;i<syscode.country.length;i++){
			temp = syscode.country[i].split(",");
			tempId[i]=temp[0];
			tempViewStr[i]=temp[1];		
		}
		for(var j=0;j<tempId.length;j++){
			if(tempId[j]==cityId){
				hometown=tempViewStr[j];
				break;			
			}
		}
		if($('#hometown_title').size()>0) {
			$('#hometown_title').remove();
		}
		html='<ul id="hometown_title" class="scbox2"><li class="title Bl_3"><span class="tred">'
		html+='籍贯';
		html+='</span></li>';
		html+='<li class="label"><a href="##" onclick="removeNewSelected(this);removeNewAddedHometown();">';
		html+=hometown;
		html+='</a></li>';	
		html+='</ul>';		
		$('#addSearchCondition').append(html);					
	} else{
		if(cityId!=0){
			var a = '';
			var b = '';
			var temp = new Array();
			var tempId = new Array();
			var tempViewStr = new Array();		
			var temp2 = new Array();
			var tempId2 = new Array();
			var tempViewStr2 = new Array();		
			for(var i=0;i<syscode.province.length;i++){
				temp = syscode.province[i].split(",");
				tempId[i]=temp[0];
				tempViewStr[i]=temp[1];		
			}
			for(var j=0;j<tempId.length;j++){
				if(tempId[j]==provinceId){
					a=tempViewStr[j];
					break;			
				}
			}
			for(var x=0;x<syscode.city.length;x++){
				temp2 = syscode.city[x].split(",");
				tempId2[x]=temp2[0];
				tempViewStr2[x]=temp2[1];		
			}
			for(var y=0;y<tempId2.length;y++){
				if(tempId2[y]==cityId){
					b=tempViewStr2[y];
					break;			
				}
			}
			hometown=a+b;
			if($('#hometown_title').size()>0) {
				$('#hometown_title').remove();
			}
			html='<ul id="hometown_title" class="scbox2"><li class="title Bl_3"><span class="tred">'
			html+='籍贯';
			html+='</span></li>';
			html+='<li class="label"><a href="##" onclick="removeNewSelected(this);removeNewAddedHometown();">';
			html+=hometown;
			html+='</a></li>';	
			html+='</ul>';		
			$('#addSearchCondition').append(html);					
		} else {
			var temp = new Array();
			var tempId = new Array();
			var tempViewStr = new Array();		
			for(var i=0;i<syscode.province.length;i++){
				temp = syscode.province[i].split(",");
				tempId[i]=temp[0];
				tempViewStr[i]=temp[1];		
			}
			for(var j=0;j<tempId.length;j++){
				if(tempId[j]==provinceId){
					hometown=tempViewStr[j];
					break;			
				}
			}
			if($('#hometown_title').size()>0) {
				$('#hometown_title').remove();
			}
			html='<ul id="hometown_title" class="scbox2"><li class="title Bl_3"><span class="tred">'
			html+='籍贯';
			html+='</span></li>';
			html+='<li class="label"><a href="##" onclick="removeNewSelected(this);removeNewAddedHometown();">';
			html+=hometown;
			html+='</a></li>';	
			html+='</ul>';		
			$('#addSearchCondition').append(html);					
		}
	}
	$('#alldelete2').css("display","");
$('#personal_hometown').removeClass('darrow2').addClass('current darrow2');				
}
var removeNewAddedHometown = function() {
document.getElementById('areaForm.workProvince1').value=0;
document.getElementById('areaForm.workCity1').value=0;
$('#personal_hometown').removeClass('current darrow2').addClass('darrow2');
$('#personal_view_hometown').css("display","none");
} 
//-------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------
var addStock = function() {
	if($('#stock').attr('value') == -1){
		if($('#stock_title').size()>0) {
			$('#stock_title').remove();
		}
		$('#stock').focus();
		return false;
	} 
	if($('#stock_title').size()>0) {
		$('#stock_title').remove();
	}
	var a = $('#stock').attr('value');
	var syscodeapi = syscode.stock;
	var salaryId = new Array();
	var salaryViewStr = new Array();
	for(var i=0;i<syscodeapi.length;i++) {
		temp = syscodeapi[i].split(",");
		salaryId[i]=temp[0];
		salaryViewStr[i]=temp[1];		
	}
	for(var j=0;j<salaryId.length;j++) {
		if(a==salaryId[j]) {
			b=salaryViewStr[j];
			break;
		}		  
	}			
	html='<ul id="stock_title" class="scbox2"><li class="title Bl_3"><span class="tred">'
	html+='民族';
	html+='</span></li>';
	html+='<li class="label"><a href="##" onclick="removeNewSelected(this);removeNewAddedStock();">';
	html+=b;			
	html+='</a></li>';	
	html+='</ul>';		
	$('#addSearchCondition').append(html);
	$('#personal_stock').removeClass('darrow2').addClass('current darrow2');			
	$('#alldelete2').css("display","");
}
var removeNewAddedStock = function() {
document.getElementById('stock').value=-1;
$('#personal_stock').removeClass('current darrow2').addClass('darrow2');
$('#personal_view_stock').css("display","none");
}
//--------------------------------------------------------------------------------------------

//-----------------------------------------------------------------------------------------
//JS判断字符长度
String.prototype.trim = function() { 
	return this.replace(/(^\s*)|(\s*$)/g, ""); 
}
//len长度  txt 文本
function checkWord(len,evt,txt){
	if(evt==null) evt = window.event;
	var src = evt.srcElement? evt.srcElement : evt.target; 
	var str=src.value.trim();
	myLen=0;
	i=0;
	for(;(i<str.length)&&(myLen<=len);i++){
		if(str.charCodeAt(i)>0&&str.charCodeAt(i)<128)
		myLen++;
		else
		myLen+=2;
	}
	if(myLen>len){
		alert(txt);
		src.value=str.substring(0,i-1);
		return false;
	}
}
//-----------------------------------------------------------------------------------------