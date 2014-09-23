// JavaScript Document
//DIV显示提示消息

function public_showmsg(type){
	$(".chat-conv").html('');
	var sid=$("#sid").attr("value");
	var changeUrl = "ajax.php?n=remark&type="+type+"&sid="+sid+"&rand="+Math.random();
	$.get(changeUrl,function(str){
		switch(str){
			case '0':
			$(".chat-conv").html("<p>您有<a href='#'>0条备注</a></p>");
			$(".tixingBox").html("最近备注(0)");
			break;
			default:
			strArr = str.split('|');

			  $(".chat-conv").html(strArr[0]);
			  $(".tixingBox").html(strArr[1]);
			  $(".notimeBox").html(strArr[2]);

			//展开
			$('.chat-window').slideUp();
			$('.chat-window').slideDown();
			$(".jiluBox").removeClass("jiluBox3");
		        $(".notimeBox").removeClass("notime3");
			$('.tixingBox').addClass("tixingBox3");
			break;
		}
	})
	//setTimeout("public_showmsg('timeover')",6000);
}

function get_remark(type){
    $(".chat-conv").html('');
	var sid=$("#sid").attr("value");
	var changeUrl = "ajax.php?n=remark&type="+type+"&sid="+sid+"&rand="+Math.random();
	$.get(changeUrl,function(str){
		switch(str){
			case '0':
				$(".chat-conv").html("<p>您有<a href='#'>0条备注</a></p>");
			break;
			default:
				strArr = str.split('|');
				$(".chat-conv").html(strArr[0]);
				$(".tixingBox").html(strArr[1]);
				$(".notimeBox").html(strArr[2]);
			
			break;
		}
		if(type=='timeover'){
			strArr = str.split('|');
			$(".tixingBox").html(strArr[1]);}
	})
}

//展示和隐藏备注内容
function showcontent(i){
	if($("#content-"+i).css("display")=="none"){
		$("#content-"+i).css("display","block"); 
	}else{
		$("#content-"+i).css("display","none");
	}
}

//修改备注状态
function deal(id){
	var obj = $("#state-"+id);
	if(obj.html()=="已解决"){
		alert("已解决");
	}else{
		if(confirm("确定已经解决了吗，确定之后将不再显示!")){
			var url="ajax.php?n=change_remark_status&id="+id+"&rand="+Math.random();
			$.get(url,function(str){
				if(str=="ok"){
					//$("#state-"+id).html("已解决");
					$(".state-"+id).css("display","none");
				}
			});
		}	
	}
}

//
function aworke(){
	if($("div").html()=="已解决")
	alert("已经解决");	return false;
}

/***********设置浮动位置、展开和收缩**********/
$(function(){
	//$("#imonline").floatdiv({left:"0px",top:"50px"});
	$("#imonline").floatdiv("rightbottom");
	$("#imonline").show();
	
	//显示、隐藏DIV-最新备注
	$(".tixingBox").click(function () {
	    $('.chat-window').slideUp();
	    get_remark("timeover");
		$('.chat-window').slideToggle();
		$(this).addClass("tixingBox3");
		$(".jiluBox").removeClass("jiluBox3");
		$(".notimeBox").removeClass("notime3");
    });
	
	//显示、隐藏DIV
	$(".jiluBox").click(function () {
	        $('.chat-window').slideUp();
		get_remark("all");
		$('.chat-window').slideToggle();
		$(this).addClass("jiluBox3");
		$(".tixingBox").removeClass("tixingBox3");
		$(".notimeBox").removeClass("notime3");
        });
    $(".notimeBox").click(function () {
                $('.chat-window').slideUp();
		get_remark("notime");
		$('.chat-window').slideToggle();
		$(this).addClass("notime3");
		$(".tixingBox").removeClass("tixingBox3");
		$(".jiluBox").removeClass("jiluBox3");
        });
	//隐藏DIV
	$(".chat-head").click(function () {
		/*$('.chat-conv > a').each(function(i){
		readSysMsg(this);
		});*/
		$('.chat-window').slideUp();
		$('.tixingBox').removeClass("tixingBox3");
		$('.notimeBox').removeClass("notime3");
		$('.jiluBox').removeClass("jiluBox3");
	});
	//最新提醒样式切换
	$(".tixingBox").hover(
		function () {
			$(this).addClass("tixingBox2");
			//$('.chat-window').slideUp();
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
			//$('.chat-window').slideUp();
		},
		function () {
			$(this).removeClass("jiluBox2");
		}
	);
	$(".notimeBox").hover(
		function () {
			$(this).addClass("notimeBox2");
			//$('.chat-window').slideUp();
		},
		function () {
			$(this).removeClass("notimeBox2");
		}
	);
});

$(document).ready(function() {	
	//初始化数据
	setInterval("public_showmsg('timeover')",60000);
	//public_showmsg("timeover");
	
	//成功升级，后台祝贺提醒
	setInterval("congratulate_remark()",300000);
	
	$("#congratulate_remark").click(function(){
		congratulate_remark_hidden(1);
	})
	//setInterval("congratulate_remark_hidden()",5000);
});

//成功升级会员，祝贺提醒
var timerID = 0;
var sec = 0;
function congratulate_remark(){
	var changeUrl = "ajax.php?n=congratulate_remark&rand="+Math.random();
	$.get(changeUrl,function(str){
		if(str == 'nologin'){
			alert('未登录，请刷新页面并登录');return;
		}
		
		if(str !='no'){//alert(str);
			var re = /[0-9]{1,4}/ ; 
			if(!re.test(str)){
				return;
			}
			$("#congratulate_remark").floatdiv("middle");
			$("#congratulate_sid").html(str+'号客服');
			$("#congratulate_remark").fadeIn();
			sec = 0;
			if(timerID==0){
				timerID = setInterval("congratulate_remark_hidden(0)",1000);
			}
			
			//alert('congratulate_remark');
		}
	})
}


//隐藏祝贺提醒
function congratulate_remark_hidden(s){
	sec++;
	//if(($("#congratulate_remark").css('display') != 'none' && sec>=5) || s==1){alert(timerID+':timerID');
	if(sec>=5 || s==1){//alert(timerID+':timerID');
		clearInterval(timerID);
		timerID = 0;
		//alert(timerID+':timerID'+sec);
		var changeUrl = "ajax.php?n=congratulate_remark_hidden&rand="+Math.random();
		$.get(changeUrl,function(str){
			if(str=='ok'){
				
				$("#congratulate_remark").fadeOut();
			}
		})
	}
	
}
