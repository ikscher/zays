// JavaScript Document
//


//更新消息为已读
var message_type,uid;
function update_read(message_type,uid){
	$.get("ajax.php?n=index&h=update_read&type="+message_type+"&uid="+uid);
}

//DIV显示提示消息
var c_msg;


function public_showmsg(){
    var timestamp = Date.parse(new Date())/1000;//获取当前时间的时间戳
    
    var last_login_time = $.cookie('Moo_last_login_time');

    var onlineTime = timestamp - last_login_time;

   
	$(".chat-conv").html('');
	$(".tixingBox").html('');
	var changeUrl = "ajax.php?n=index&h=showmsg&sid="+Math.random();
	$.get(changeUrl,function(str){
         c_msg=str;
         /*if(str=='' || str==null || !str){
        			$.cookie('thestaus',0);
        				$.cookie('cmsg',null);																	
        				$('.chat-window').hide();
        				$(this).addClass("tixingBox3");
 
     		return;
         }*/
      
		switch(str){
			case "no_login":
			//alert('未登陆，转向登陆页');
			//location.href = "index.php?n=login";
			break;
			case '0':
			$('.chat-window').slideUp();
			$('.tixingBox').removeClass("tixingBox3");
			break;
			default:
			
			if(str.length<=0){
				return;
			}
			
			strArr = str.split('|');
			
			
			$(".chat-conv").html(strArr[0]);
			$(".tixingBox").html(strArr[1]);
			
			//播放提示声音
			if(strArr[2] =='new_message') {
				try{play("/public/system/flash/new_message.mp3");}catch(error){}
				//play("/public/system/flash/new_message.mp3");
			}
			
		     if($.cookie('cmsg')!=c_msg&&$.cookie('cmsg')!=''){	
			    //展开
			    $('.chat-window').slideDown();
			    $.cookie('thestaus','0'); 
				$('.tixingBox').addClass("tixingBox3");
			 }
			 else if($.cookie('thestaus')!=1){
				 $('.chat-window').slideDown();	
				 $('.tixingBox').addClass("tixingBox3");
			 }			
			break;
		}
	
	});
	
	 
	/*1、模拟频率：

	1.1 在线五分钟以内 1分钟模拟一次；
	1.2 在线10分钟以内 2分钟模拟一次；
	1.3 在线10-30分钟  3分钟模拟一次；
	1.4 30分钟以上     4分钟一次；
	1.5 每次模拟都必须显示，不沿用之前的概率原则
    */
	//setInterval("public_showmsg();", 6000);
	var time_unit = 15000;
	
	if(onlineTime < 300){
	   /*window.setTimeout(function () {
		   public_showmsg();}, 60000);  */
		if(k) window.clearTimeout(k);

		if(arguments[0]==undefined) var k=setTimeout("public_showmsg()", time_unit);
	    
	}else if(onlineTime >= 300 && onlineTime < 600 ){
	 /*  window.setTimeout(function () {
			   public_showmsg();}, 120000); */ 
		if(k) window.clearTimeout(k);

		if(arguments[0]==undefined) var k=setTimeout("public_showmsg()", time_unit*2);
	    
	}else if(onlineTime >= 600 && onlineTime <= 1800){
		 /*window.setTimeout(function () {
			   public_showmsg();}, 180000);  */
		if(k) window.clearTimeout(k);

		if(arguments[0]==undefined) var k=setTimeout("public_showmsg()", time_unit*3);
	   
	}else if(onlineTime > 1800){
	/*	 window.setTimeout(function () {
			   public_showmsg();}, 240000);  */
		if(k) window.clearTimeout(k);

		if(arguments[0]==undefined) var k=setTimeout("public_showmsg()", time_unit*4);
	   
	}
	
	//setTimeout("public_showmsg()",6000);
}
/***********设置浮动位置、展开和收缩**********/
/*
$(function(){
	
	$("#imonline").floatdiv({left:"0px",top:"50px"});
	$("#imonline").floatdiv("rightbottom");
	$("#imonline").show();
	//显示、隐藏DIV
	$(".tixingBox").click(function () {
		$.cookie('thestaus',0);
		$.cookie('cmsg',null);																	
		$('.chat-window').slideToggle();
		$(this).toggleClass("tixingBox3");
    });
	//隐藏DIV
	$(".minimize").click(function () {

		$.cookie('thestaus',1);
		$.cookie('cmsg',c_msg);	
		$('.chat-window').slideUp();
		$('.tixingBox').removeClass("tixingBox3");
	});
	//最新提醒样式切换
	$(".tixingBox").hover(
		function () {
			$(this).addClass("tixingBox2");
		},
		function () {
			$(this).removeClass("tixingBox2");
		}
	);
	//清除、获取
	$(".tixingBox").click(
		function () {
			//public_showmsg();
		}
	);
	//聊天记录样式切换
	$(".jiluBox").hover(
		function () {
			$(this).addClass("jiluBox2");
		},
		function () {
			$(this).removeClass("jiluBox2");
		}
	);
});


//页面加载完毕时调用
$(function() {
	//初始化数据
	public_showmsg();
	
	
});
//展开
function showDiv(){
	//$(".chat-window").slideDown("slow");
	//public_showmsg();
	//$("#imonline").animate({height:220},1000,"swing");
	//$("#hi").hide(1300);
}
//收缩
function hideDiv(){
	//$(".chat-window").slideUp("slow");
	//public_showmsg()
	//$("#imonline").animate({height:220},1000,"swing");
	//$("#hi").hide(1300);
}
*/