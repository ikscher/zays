var APE = {
	Config: {
		identifier: 'ape',
		init: true,
		frequency: 0,
		scripts: []
	},

	Client: function(core) {
			if(core) this.core = core;	
	}
}

APE.Client.prototype.eventProxy = [];
APE.Client.prototype.fireEvent = function(type, args, delay) {
	this.core.fireEvent(type, args, delay);
}

APE.Client.prototype.addEvent = function(type, fn, internal) {
	var newFn = fn.bind(this), ret = this;
	if(this.core == undefined){
		this.eventProxy.push([type, fn, internal]);
	}else{
		var ret = this.core.addEvent(type, newFn, internal);
		this.core.$originalEvents[type] = this.core.$originalEvents[type] || [];
		this.core.$originalEvents[type][fn] = newFn;
	}
	return ret;
}
APE.Client.prototype.removeEvent = function(type, fn) {
	return this.core.removeEvent(type, fn);
}

APE.Client.prototype.onRaw = function(type, fn, internal) {
		this.addEvent('raw_' + type.toLowerCase(), fn, internal); 
}

APE.Client.prototype.onCmd = function(type, fn, internal) {
		this.addEvent('cmd_' + type.toLowerCase(), fn, internal); 
}

APE.Client.prototype.onError = function(type, fn, internal) {
		this.addEvent('error_' + type, fn, internal); 
}

APE.Client.prototype.cookie = {};

APE.Client.prototype.cookie.write = function (name, value) {
	document.cookie = name + "=" + encodeURIComponent(value) + "; domain=" + document.domain;
}

APE.Client.prototype.cookie.read = function (name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0){
			return decodeURIComponent(c.substring(nameEQ.length,c.length));
		}
	}
	return null;
}

APE.Client.prototype.load = function(config){

	config = config || {};
   
	config.transport = config.transport || APE.Config.transport || 0;
	config.frequency = config.frequency || 0;
	config.domain = config.domain || APE.Config.domain || document.domain;
	config.scripts = config.scripts || APE.Config.scripts;
	config.server = config.server || APE.Config.server;
	config.secure = config.sercure || APE.Config.secure;
    
	config.init = function(core){
		this.core = core;
		for(var i = 0; i < this.eventProxy.length; i++){
			this.addEvent.apply(this, this.eventProxy[i]);
		}
	}.bind(this);
   
	//set document.domain
	if (config.transport != 2) {
		if (config.domain != 'auto') document.domain = config.domain;
		if (config.domain == 'auto') document.domain = document.domain;
	}

	//Get APE cookie
	var cookie = this.cookie.read('APE_Cookie');
	var tmp = eval('(' + cookie + ')');

	if (tmp) {
		config.frequency = tmp.frequency+1;
	} else {
		cookie = '{"frequency":0}';
	}

	var reg = new RegExp('"frequency":([ 0-9]+)' , "g")
	cookie = cookie.replace(reg, '"frequency":' + config.frequency);
	this.cookie.write('APE_Cookie', cookie);

	var iframe = document.createElement('iframe');
	iframe.setAttribute('id','ape_' + config.identifier);
	iframe.style.display = 'none';
	iframe.style.position = 'absolute';
	iframe.style.left = '-300px';
	iframe.style.top = '-300px';

	document.body.insertBefore(iframe,document.body.childNodes[0]);

	var initFn = function() {
		iframe.contentWindow.APE.init(config);
	}

	if (iframe.addEventListener) {
		iframe.addEventListener('load', initFn, false);
	} else if (iframe.attachEvent) {
		iframe.attachEvent('onload', initFn);
	}

	if (config.transport == 2) {
		var doc = iframe.contentDocument;
		if (!doc) doc = iframe.contentWindow.document;//For IE

		//If the content of the iframe is created in DOM, the status bar will always load...
		//using document.write() is the only way to avoid status bar loading with JSONP
		doc.open();
		var theHtml = '<html><head>';
		for (var i = 0; i < config.scripts.length; i++) {
			theHtml += '<script type="text/JavaScript" src="' + config.scripts[i] + '"></script>';
		}
		theHtml += '</head><body></body></html>';
		doc.write(theHtml);
		doc.close();
	} else {

        var host = config.server.replace(/\:\d+$/g, '');

		iframe.setAttribute('src',(config.secure ? 'https': 'http') + '://' + host + ':6969/?[{"cmd":"script","params":{"domain":"' + document.domain +'","scripts":["' + config.scripts.join('","') + '"]}}]');
		if (navigator.product == 'Gecko') { 
			//Firefox fix, see bug #356558 
			// https://bugzilla.mozilla.org/show_bug.cgi?id=356558
			iframe.contentWindow.location.href = iframe.getAttribute('src');
		}
		
	}
	
}

if (Function.prototype.bind == null) {
	Function.prototype.bind = function(bind, args) {
		return this.create({'bind': bind, 'arguments': args});
	}
}
if (Function.prototype.create == null) {
	Function.prototype.create = function(options) {
			var self = this;
			options = options || {};
			return function(){
				var args = options.arguments || arguments;
				if(args && !args.length){
					args = [args];
				}
				var returns = function(){
					return self.apply(options.bind || null, args);
				};
				return returns();
			};
	}
}




(function($, APE){
    
	//添加jquery属性ape
	$.extend({ape:{
		name:'realtime-extends',
		isDebug:false,
		currentPipe:'',
		options:'',
		APE:APE,
		client:new APE.Client(),
		user:'',
		msgCallback:'',
		errorArr:[],
		closeFn:'',
		closeFireFn:'',
		leftFn:'',
		msgTips:'',
		connectSuccessAct:'',
		//初始化
		load:function(options){
			if(!options.server) return this.error('010', 'load方法参数server值未定义');
			if(!options.channel) return this.error('011', 'load方法参数channel值未定义');
			if(typeof options.channel != 'string') options.channel = options.channel.toString();
			this.options = options;
			
			//取domain值		
			var dm = this.options.server.split('.');
			this.options.domain = dm[dm.length-2]+'.'+dm[dm.length-1];
			
			//this.APE.Config.baseUrl = 'http://'+this.options.server; //APE JSF 
			this.APE.Config.baseUrl = 'http://zhenaiyisheng.cc/APE_JSF'; //APE JSF 
			this.APE.Config.domain = this.options.domain; 
			this.APE.Config.server = this.options.server+':6969'; //APE server URL
            // console.log(this.APE.Config.baseUrl);
			// console.log(this.APE.Config.domain);
			// console.log(this.APE.Config.server);
			(function(){
				for (var i = 0; i < arguments.length; i++){
					this.APE.Config.scripts.push(APE.Config.baseUrl + '/Source/' + arguments[i] + '.js');
				}
			})('mootools-core', 'Core/APE', 'Core/Events', 'Core/Core', 'Pipe/Pipe', 'Pipe/PipeProxy', 'Pipe/PipeMulti', 'Pipe/PipeSingle', 'Request/Request','Request/Request.Stack', 'Request/Request.CycledStack', 'Transport/Transport.longPolling','Transport/Transport.SSE', 'Transport/Transport.XHRStreaming', 'Transport/Transport.JSONP', 'Transport/Transport.WebSocket', 'Core/Utility', 'Core/JSON');

		},
		//添加用户
		addUser:function(user){
		    
			if(!this.options.channel) return this.error('012', '未进行load初始化');
			if(!user.id) return this.error('013', 'addUser方法参数id值未定义');
			if(!user.toId) return this.error('014', 'addUser方法参数toId值未定义');
			
			if(typeof user.id != 'string') user.id = user.id.toString();
			if(typeof user.toId != 'string') user.toId = user.toId.toString();
			
			this.user = user;
			
			this.client.onError('004',this.reConn);
			//拦截服务端返回消息
			this.client.onRaw('chat_message', this.receiveMsg);
			this.client.onRaw('chat_function', this.doFn);
			//消息提示
			this.client.onRaw('msg_tips',function(){
//				alert(arguments[0].data.msg);
				if ($.ape.msgTips) $.ape.msgTips($.ape.user.id,$.ape.user.toId);
			});
            
			//添加加载完成事件
			this.client.addEvent('load', function(){
				if($.ape.user.id) this.core.start({'name':$.ape.user.id, 'toname':$.ape.user.toId,'chan':$.ape.options.channel});
			});
            
			this.client.addEvent('multiPipeCreate',function(pipe,options){
				$.ape._setCurrentPipe(pipe.getPubid());
				if ($.ape.connectSuccessAct)$.ape.connectSuccessAct();
			});
			
			this.client.addEvent('userLeft', function (user, pipe) {
				if($.ape.user.id == user.properties.name){
					this.core.quit();
					if($.ape.leftFn) $.ape.leftFn();
					$.ape.currentPipe = false;
				}
			    //An user left the channel
			});
			
			this.client.load({'identifier':this.name, 'channel':this.options.channel});
			
			window.onbeforeunload=function(){
				return $.ape.unloadFn();
			}
		},
		_setCurrentPipe:function(pubid){
			this.currentPipe = this.client.core.getPipe(pubid);
		},
		//发送消息
		sendMsg:function(msg){
            
			if(!msg) return this.error('015', '发送的消息不能为空');
			if(!this.currentPipe) return this.error('016', '信息推送服务器未能正常链接');;
			var msgData = {'message':msg, 'fromId':this.user.id, 'toId':this.user.toId, 'serverId':(this.user.serverId||0)};
		
			this.currentPipe.request.send('SETMESSAGE',msgData);
		},
		//绑定信息接收回调函数
		bindMsgFn:function(callbackm){
			this.msgCallback = callbackm;
		},
		//绑定不同连接状态所执行的函数
		bindConnectSuccess:function(callbackm){
			this.connectSuccessAct = callbackm;
		},
		//绑定信息提示函数
		bindMsgTipsFn:function(callbackm){
			this.msgTips = callbackm;
		},
		receiveMsg:function(raw,pipe){
			raw.data.message = decodeURIComponent(raw.data.message);
			$.ape.msgCallback(raw.data);
		},
		//离开聊天
		closeChat:function(){
			if(!this.currentPipe) return true;
			this.client.core.left(this.currentPipe.getPubid());
			this.client.core.quit();
//			$("#ape_realtime-extends").remove();
//			window.location.replace('http://www.apedemo.com/chat_win_test/');
			if($.ape.closeFn) eval($.ape.closeFn);
			this.currentPipe = false;
		},
		bindClose:function(){
			var oClose = arguments;
			$.each(oClose,function(i,item){
				item.bind('click',function(){
					$.ape.unloadFn();
				});
			});
			//关闭浏览器触发
//			$(window).unload(function(){
//				alert('closed');
//				if(!this.currentPipe) return;
//				if($.ape.closeFn) $.ape.fireFn($.ape.closeFn);
//				$.ape.closeChat();
//			});
		},
		unloadFn:function(){
			if(!this.currentPipe) return;
			if($.ape.closeFn) eval($.ape.closeFn);
			if($.ape.closeFireFn) $.ape.fireFn($.ape.closeFireFn);
			$.ape.closeChat();
			return '离开页面将终止聊天';
		},
		//ex:$.ape.binCloseFn('test(232)');
		bindCloseFn:function(fn){
			this.closeFn = fn;
		},
		
		bindCloseFireFn:function(fn){
			this.closeFireFn = fn;
		},
		bindLeftFn:function(fn){
			this.leftFn = fn;
		},
		//错误处理
		error:function(code, msg){
			this.errorArr.push([code, msg]);
			if(this.isDebug) alert(msg);
			return code;
		},
		//获取所有错误信息
		getError:function(){
			//if(this.isDebug) console.dir(this.errorArr);
			return this.errorArr;
		},
		//触发其他用户的函数
		fireFn : function(execString){//fname:函数名，param:参数
			if(!execString) this.error('017', '触发函数为空'); 
			var data = {exec:execString};
			this.currentPipe.request.send("FIREFUNCTION",data);
		},
		//触发函数回调
		doFn: function(raw, pipe){
			if(!raw.data.exec) this.error('018', '触发函数失败');
			eval(decodeURIComponent(raw.data.exec));
		},
		reConn:function(){
			//this.core.clearSession();
			$.ape.addUser($.ape.user);
		}
	}});
	
})(jQuery, APE);