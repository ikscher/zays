// JavaScript Document
//
function makevisitor(){
	var changeUrl = "ajax.php?n=index&h=makevisitor"+"&sid="+Math.random();
	//var s = Math.floor(Math.random() * 30);

	//if(s == 5){
		$.get(changeUrl,function(str){
//			switch(str){
//				case "no_login":
//					//alert('未登陆，转向登陆页');
//					//location.href = "index.php?n=login";
//					break;
//				case '0':
//					
//					break;
//				default:
//				//alert(str);
//					//$(".chat-conv").html($(".chat-conv").html()+str);
//					//n = $(".tixingBox").text().substr(5,1);
//					//$(".tixingBox").html("最新提醒("+(parseInt(n)+1)+")");
//					$(".chat-conv").html(str);
//					$(".tixingBox").html("最新提醒");
//					 if($.cookie('cmsg')!=c_msg&&$.cookie('cmsg')!=''){	
//						//展开
//						$('.chat-window').slideDown();
//						$.cookie('thestaus','0'); 
//						$('.tixingBox').addClass("tixingBox3");
//					 }
//					 else if($.cookie('thestaus')!=1){
//						 $('.chat-window').slideDown();	
//						 $('.tixingBox').addClass("tixingBox3");
//					 }	 
//					break;
//			}
		});
	//}
	//setTimeout('makevisitor()',3000);
	//setTimeout('makevisitor()', Math.floor(Math.random()*360*1000))
	
}

//DIV显示提示消息
var c_msg;
function public_showmsg(){
	$(".chat-conv").html('');
	$(".tixingBox").html('');
	var changeUrl = "ajax.php?n=index&h=showmsg"+"&sid="+Math.random();
	$.get(changeUrl,function(str){
         c_msg=str;							 
		switch(str){
			case "no_login":
			//alert('未登陆，转向登陆页');
			//location.href = "index.php?n=login";
			break;
			case '0':
			$(".chat-conv").html("<p>您有<a href='#'>0条新消息</a></p>");
			$(".tixingBox").html("最新提醒(0)");
			break;
			default:
			
			strArr = str.split('|');
			$(".chat-conv").html(strArr[0]);
			$(".tixingBox").html(strArr[1]);
			
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
	})
	setTimeout("public_showmsg()",60000);
}
/***********设置浮动位置、展开和收缩**********/
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
		/*$('.chat-conv > a').each(function(i){
		readSysMsg(this);
		});*/
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
$(document).ready(function() {
	//初始化数据
	public_showmsg();
	//
	//makevisitor();
	//setInterval('makevisitor()', Math.floor(Math.random()*360*1000));

	//setTimeout($("#hi").hide(),2000);
	//$(".chat-window").slideUp("slow");
	//$("#imonline").animate({height:20},0,"swing");
	//$("#imonline").show();
	
	//$("#hi").animate({height:0},1000,"swing");
	//$("#test").show(2000);
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
