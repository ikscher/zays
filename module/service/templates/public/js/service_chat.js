//会员信息js处理
function userdetail(number,arrayobj) {
	var arrname = arrayobj;
	for(i=0;i<arrayobj.length;i++) {
		var valueArray  = arrayobj[i].split(",");
		if(valueArray[0] == number) {
			if(valueArray[0] == '0'  && valueArray[1] != '男士') {
				document.write("不限");
			} else {
				document.write(valueArray[1]);
			}	
		}
	}
}

//在线聊天信息初始化(第一次打开页面或刷新页面时)
var chatInfo = {to:[0,'',''], from:[0,'我'], content:''};
function get_chat_all(){
	chatInfo.to[0] = $('#chatorid').val();
	chatInfo.to[1] = $('#chatornickname').val();
	chatInfo.to[2] = encodeURIComponent(chatInfo.to[1]);
	chatInfo.from[0] = $('#chatfromid').val();
	var changeUrl = "ajax.php?n=service&h=showfirst&chatfromid="+chatInfo.from[0]+"&chatorid="+chatInfo.to[0]+"&chatornickname="+chatInfo.to[2]+"&sid="+Math.random();
	$.get(changeUrl,function(str){
		if(str != "no_login"){
			$("#chatbox").html(str);
		}
		
		
		$("#content").focus();
	})
}


var t;
//在线聊天——获取记录(更新对方发的消息)
function get_chat(){
    
    if (t) clearTimeout(t);
	var chat_id=$("#chat_id").val();
	console.log(chat_id+'ddd');
	var changeUrl = "ajax.php?n=service&h=show&chatfromid="+chatInfo.from[0]+"&chatorid="+chatInfo.to[0]+"&chatornickname="+chatInfo.to[2]+"&chat_id="+chat_id+"&sid="+Math.random();
	$.getJSON(changeUrl,function(str){
		if(str.content && str.s_id != "0"){
			var s = $("#chatbox").html();
			$("#chatbox").html(s + str.content);
			$("#chat_id").val(str.s_id);
			var e = document.getElementById('chatbox');
			e.scrollTop=e.scrollHeight;
			try{
				play("./public/system/flash/new_message.mp3");
			}catch(error) {}
		} else if(str.content == "no_login" && str.s_id=="0") {
			window.location.href = "index.php?n=login";
			return false;
		}
		t=window.setTimeout("get_chat()",1500);
	})
}
//在线聊天——添加记录、获取记录
function add_chat(content){
	var content = content;
	var leng = myStrLen(content);
	if(leng[1] > 150){
		alert("一次最多可以发送150个汉字。");
		return false;
	}
	$('#content').val('');//清空输入框
	var d_st = $("#chatbox").html();
	$("#chatbox").html(d_st + cContent(content));
	content = encodeURIComponent(content);
	var changeUrl = changeUrl = "ajax.php?n=service&h=add&chatfromid="+chatInfo.from[0]+"&chatorid="+chatInfo.to[0]+"&content="+content+"&chatornickname="+chatInfo.to[2]+"&sid="+Math.random();
	$.get(changeUrl,function(str){
		if(str && str != "no_login"){
			var e = document.getElementById('chatbox');
			e.scrollTop=e.scrollHeight;
		}else if(str == "no_login"){
			location.href = "index.php?n=login";
		}
	})
}
//设置用户按下ctrl+Enter键提交数据
document.onkeyup=sendMsg;
function sendMsg(e){
	//判断浏览器
	if(navigator.appName == 'Microsoft Internet Explorer'){
		if(window.event.keyCode == 13 && window.event.ctrlKey){
			//return;
		} else {
			return;
		}
	}else{
		if(e.ctrlKey && e.which == 13){
			//return;
		} else {
			return;
		}
	}
	var content = $('#content').val();
	if(content.replace(/\s/g,'') != ''){
		add_chat(content);
	}else{
		alert('没有可以发送的内容');
	}
}
//设置用户按下发送按钮提交数据
function button_sendMsg(){
	var content = $('#content').val();
	if(content.replace(/\s/g,'') != ''){
		add_chat(content);
	}else{
		alert('没有可以发送的内容');
	}
}
//改变DIV大小
window.onresize=div_height;
function div_height(){
	var h=$(window).height()-240;
	var w=$(window).width()-260;
	$('#chatbox').height(h<200?200:h);
	$('#content').width(w<100?100:w);
}
function myStrLen(str){
	var len1 = len2 = len3 = 0;
	var len = str.length;
	for (var i=0; i<len; i++) {
		var c = str.charCodeAt(i);
		if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) {
			len1++;
		}else {
			len2++;
		}
		if(len2 + len1 / 2 < 150){
			len3++;
		}
	}
	return [len3,len2 + Math.floor(len1 / 2)];
}
// cmx add =========================================================
function cContent(content) {
	var d = new Date();
	var h = d.getHours();
	var m = d.getMinutes();
	var s = d.getSeconds();

	var curtime = "(";
	curtime += h < 10 ? "0"+h : h ;
	curtime += ":" + (m < 10 ? "0"+m : m) ;
	curtime += ":" + (s < 10 ? "0"+s : s) ;
	curtime += ")";

	var content = content;
	content = content.replace(/</g,'&lt;');
	content = content.replace(/>/g,'&gt;');
	content = content.replace(/\n/g,'<br />');

	return "<p>我对" + chatInfo.to[1] + curtime + "说：</p><p class=\"myself\">" + content + "</p>";
}
