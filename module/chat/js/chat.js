var _chat = {
	request:{},

	
	_userName:'我',	//用户名
	_toName:'客服',//客服名称
	_url:'',
	_apeServer:'',
	_userId:'',
	_userToId:'',
	_channel:'test',
	_chatTitle:'',
	_myPhoto:'',
	_toPhoto:'',
	_apeMark:false,
	_serverId:0,
	_winFocus:true,  //是否是窗口焦点
	_pageTitle:document.title,
	_scrollTime:null,

	apeInit:function(){
		if($.ape.currentPipe || this._apeMark) return;
		this._apeMark = true;
		//链接ape服务器
		
		$.ape.load({server:this._apeServer,channel:_chat._channel});
		//$.ape.addUser({id:_chat.randStr(),toId:_chat.randStr()});
	
		$.ape.addUser({id:this._userId,toId:this._userToId,serverId:this._serverId});
		$.ape.bindMsgFn(_chat.callback);
		$.ape.bindLeftFn(function(){
			_box.prompt('您的聊天窗口已经在其他地方打开，被强制离线');
			$("#choice,.send").unbind('click');
			_box._doc.designMode = 'Off';			
		});
		// $.ape.getError();
		
		_box._eframe.onfocus = window.onfocus = function(){
			if(_chat._winFocus == true) return;
			_chat._winFocus = true;
			clearTimeout(_chat._scrollTime);
			setTimeout(function(){document.title=_chat._pageTitle;}, 100)		
		}
		_box._eframe.onblur = window.onblur = function(){
			_chat._winFocus = false;
		}

		if($.browser.msie && ($.browser.version == "6.0") && !$.support.style) {
			//代码
			$(document).mouseover(function(){
				if(document.title != _chat._pageTitle){
					document.title=_chat._pageTitle;
					clearTimeout(_chat._scrollTime);
					_chat._winFocus = true;
				}
			});
		}

	},
	scrollTitle:function(strLen){
		if(this._winFocus) return;
		strLen = strLen || 2;
		var titleStr = '。。新消息。。。';
		var titleLen = titleStr.length;
		if(strLen == titleLen-3) strLen = 0;
		document.title = '【'+titleStr.substring(strLen,strLen+3)+'】';
		this._scrollTime = setTimeout("_chat.scrollTitle("+(strLen+1)+");",500);
	},
	open:function(){
		_box.init();
		_chat.apeInit();
	},
	close:function(){
		this._apeMark = false;
		_box.boxShow(0);
	},
	send:function(){
		if(!$.ape.currentPipe){
			_box.prompt('若正在链接服务器请稍等，若失败请关闭浏览器重新链接');
			return;
		}
		_chat.request.msg=_box.getMsg();
		var msgStr = _chat.request.msg.replace(/<(?!img)(?!br)[^<>]+>/ig,'');
		var msgLen = _chat.myStrLen(msgStr);
		if(msgStr==''){
			alert('内容为空,不可发送');
			delete _chat.request.msg;
			return false;
		}else if(msgLen[1]>140){
			_box.insertHtml(_chat.request.msg);
			alert('单个消息长度不能超过140个字符');
			delete _chat.request.msg;
			return false;
		}
		_box.showMsg({
			type:0,
			time:_chat.getHMS(),
			msg:_chat.request.msg
		});
		
		$.ape.sendMsg(_chat.request.msg);
		_box._win.focus();
	},
	callback:function(json){
		//修改为强有消息时强制打开
		var msg = {};
		msg.type = 1;
		msg.time = json.time;
		msg.msg = json.message;
		//浏览器窗口标题滚动提示
		_chat.scrollTitle(2);
		if(typeof json.message!='undefined') _box.showMsg(msg);
		if ($("#choice").val()=='2'){
			 $.ape.fireFn('_box.showStatusMsg("对方己离开，可能不能即时回复你的消息");');
		}
		_box._doc.designMode = 'On';
	},
	getHMS:function (){
		var dt = new Date();
		dt.setTime(dt.getTime());
		var h = dt.getHours();
		var m = dt.getMinutes();
		var s = dt.getSeconds();
		return (h<10?'0'+h:h)+':'+(m<10?'0'+m:m)+':'+(s<10?'0'+s:s);
	},
	keydown:function (e) {
		//判断浏览器
		if(navigator.appName == 'Microsoft Internet Explorer'){
			if((e.keyCode == 13 && e.ctrlKey)||(e.keyCode == 83 && e.altKey)){
				_chat.send();
			} else {
				return;
			}
		}else{
			if((e.ctrlKey && e.which == 13)||(e.altKey && e.which == 83)){
				_chat.send();
			} else {
				return;
			}
		}
	},
	checkMsg:function(){
		var ccmsg = this.checkLen(_box._doc_body.html(), 140);
		if(ccmsg!=false){
			$(_box._doc).find("body").html(ccmsg);
			alert('最多只能输入140个字');
			return false;
		};	
		return true;
	},
	checkLen:function(str,max){
		var leng = this.myStrLen(str);
		if(leng[1] > max){
			return str.substring(0,leng[0]);
		}
		return false;
	},
	myStrLen:function(str){
		
		var len1 = len2 = len3 = 0;
		var reg  = /<img[^<|>]+>/g;
		var imgarr = str.match(reg);
		for (var j in imgarr){
			len3 = len3+imgarr[j].length-1;
		}
		str = str.replace(reg,'图');
		var len = str.length;
		for (var i=0; i<len; i++) {
			var c = str.charCodeAt(i);
			if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) {
				len1++;
			}else {
				len2++;
			}
			if(len2 + len1 / 2 <= 140){
				len3++;
			}
		}
		return [len3,len2 + Math.floor(len1 / 2)];
		
		/*var i,sum;  
		 sum=0;  
		 for(i=0;i<strTemp.length;i++)  
		 {  
		  if ((strTemp.charCodeAt(i)>=0) && (strTemp.charCodeAt(i)<=255))  
		   sum=sum+1;  
		  else  
		   sum=sum+2;  
		 }  
		 return sum;  */
	},
	tipsMsg:function(){
    	_box.ps.show();
		var msg = _box.ps.text();
		var disp_msg = '';
		var tip = '正在连接信息服务';
		switch(msg.length){
			case 8:
				disp_msg = tip+'．';
				break;
			case 9:
				disp_msg = tip+'．．';
				break;
			case 10:
				disp_msg = tip+'．．．';
				break;
			case 11:
				disp_msg = tip;
				break;
			default:
				disp_msg = tip+'．．．';
				break;
		}
		_box.ps.text(disp_msg);
	},
	chatNum:function(){
		return $.cookie('chat_num');
	},
	addChatNum:function(){
		var cnum = this.chatNum();
		if(cnum==null) cnum = 0;
		$.cookie('chat_num', parseInt(cnum)+1);
	},
	removeChatNum:function(){
		var cnum = this.chatNum();
		if(cnum==null || cnum==0) return;
		$.cookie('chat_num', parseInt(cnum)-1);
	},
	end:true
}
	
var _box = {
	all_box:'',
	msg_box:'',
	_win:'',
	_doc:'',
	_doc_body:'',
	face_tb:'',
	face_show_n:0,
	offset:[],
	ps:'',
	ps_timeout_id:'',
	
	init:function(flag){//fanglin,flag

		//this.box_init(flag);//fanglin,flag
		this.iframe_init();
		
		this.face_box_init();

		//fanglin,flag
		if(typeof flag=='undefined'){
			_box.all_box = $("#trace_user").show();
		}

	},
	iframe_init:function(){
	
		this._eframe = editor_frame;
		this._win=$('#editor_frame')[0].contentWindow;
		this._doc=this._win.document;
		this._doc.designMode = 'On';
		this._doc.write('<html style="overflow-x:hidden"><body style="margin:5px;width:308px;height:60px;word-wrap:break-word;word-break:break-all;font-size:12px;line-height:18px;"></body>\</html>');
		this._doc.close();
		$(this._doc).keydown(function(event){
			_chat.keydown(event);
		});
		$(this._doc).keyup(function(event){
			_chat.checkMsg();
		});
		$(this._doc).click(function(){
			_box.face_tb.hide();
		});
		_box._doc_body=$(_box._doc).find("body").empty();
	},
	face_box_init:function(){
		var facehtml = '';
		facehtml += '<table border="0" cellspacing="0" cellpadding="0" bgcolor="#dddddd">';
		facehtml += '<tr><td colspan="15" background="'+_chat._url+'images/m_titlebg.jpg">　添加表情</td></tr>';
		for(i = 0 ;i < 9 ; i ++){
			facehtml += '<tr bgcolor="#ffffff">';
			for(j = 0 ;j < 15 ; j ++){
				facehtml += '<td>';
				facehtml += '<img style="border:0px;" src="'+_chat._url+'images/qqface/' + (i * 15 + j) + '.gif" />';
				facehtml += '</td>';
			}
			facehtml += '</tr>';
		}
		facehtml += '</table>';
		_box.face_tb=$("#show_face_img").html(facehtml).hide();
		_box.face_tb.find('img').click(function(){
			_box.insertImg(this.src);
		});
		$(document).unbind('click');
		$(document).click(function(){
			if(_box.face_show_n>0) _box.face_show_n--;
			if(_box.face_show_n<=0)	_box.faceShow(0);
		});
		$(".expression").click(function(){_box.faceShow(1);});
	},
	boxShow:function(m){
		if(m){
			_box.all_box.show();
		}else{
			_box.all_box.hide();
		}
	},
	faceShow:function(m){
		if(m){
			_box.face_show_n=2;
			_box.face_tb.show();
		}else{
			_box.face_tb.hide();
		}
	},
	insertImg:function(path){
		_box._win.focus();
		_box._doc.execCommand('InsertImage' , '' , path );
		$('#show_face_img').hide();
		_box._win.focus();
	},
	insertHtml:function(sHtml){
		_box._win.focus();
		if($.browser.msie ){
			_box._doc.selection.createRange().pasteHTML( sHtml ) ;
		}else{
			_box._doc.execCommand('InsertHtml' , '' , sHtml );
		}
	},
	getMsg:function(){
		var msg = _box._doc_body.html().replace(/<img[^>]+?src="([^"]+)"[^>]*>/ig, '<img src="$1" \/>');
		_box._doc_body.empty();
		return msg;
	},
	showMsg:function(json){
		_box.msg_box.append('<div class="'+(json.type==1?'msg_admin':'msg_user')+'"><span>'+(json.type==1?_chat._toName:_chat._userName)+' '+json.time+'</span><p>'+json.msg+'</p></div>');
		_box.msg_box[0].scrollTop = _box.msg_box[0].scrollHeight;
	},
	showStatusMsg:function(msg){
		_box.msg_box.append('<div style="margin-left:15px;"><p>'+msg+'</p></div>');
		_box.msg_box[0].scrollTop = _box.msg_box[0].scrollHeight;
	},
	boxDraw:function( box_id, bar_id ){
		
		var box = $("#"+box_id);
		var bar = $("#"+bar_id);
		var s_h = window.screen.availHeight; //屏幕可用工作区域宽度
		var s_w = window.screen.availWidth;  //屏幕可用工作区域高度
		
		box.css({'position':'absolute','z-index':'9997','clip':'rect(auto, auto, auto, auto)','left':document.documentElement.scrollLeft+parseInt((s_w-box.width()+455)/2)+'px','top':document.documentElement.scrollTop+parseInt((s_h-box.height())/4)+'px'});
		bar.css('cursor','move');
		
		
		bar.mousedown(function(e){
			var mouse_xy = e ? [e.clientX, e.clientY] : [event.clientX, event.clientY] ;
			var box_xy = box.offset();
			_box.offset = [box_xy.left - mouse_xy['0'],box_xy.top - mouse_xy['1']];
		});
		$(document).mousemove(function(e){
			if( _box.offset == '' ) return;
			var mouse_xy = e ? [e.clientX, e.clientY] : [event.clientX, event.clientY] ;
			var l = mouse_xy['0']+_box.offset['0'];
			var t = mouse_xy['1']+_box.offset['1'];
			if( mouse_xy['0'] > 0 && mouse_xy['0'] < s_w-10 ){
				box.css('left',l+'px');
			}
			if( mouse_xy['1'] > 0 && mouse_xy['1'] < s_h-10 ){
				box.css('top',t+'px');
			}
			
		});
		box.mouseup(function(e){_box.offset='';});
	},
	prompt:function(t){
		if(typeof _box.ps=='string') return;
		if(t){
			_box.ps.text(t);
			_box.ps.fadeIn(1000,function(){
				_box.ps.fadeOut(2000);
			});
		}
	},
	end:true
}
