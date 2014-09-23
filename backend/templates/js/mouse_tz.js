/*
鼠标拖动层(可任意绑定DIV标签)（一）
调用方法:var myDrag=new Endrag(source,target,offSetX, offSetY);
参数说明:source--鼠标动作绑定对象;target--操作目标对象(要移动的对象);offSetX--横坐标偏移;offSetY--纵坐标偏移
说明:通过多次调用本方法绑定多个对象的拖动
*/

funs={
	index:100,
	getFocus:function (target){
		if(target.style.zIndex!=this.index){
			this.index += 2;  
			var idx = this.index;  
			target.style.zIndex=idx;  
		}  
	},
	abs:function (element) {
		var result = { x: element.offsetLeft, y: element.offsetTop};
		element = element.offsetParent;
		while (element) {
			result.x += element.offsetLeft;
			result.y += element.offsetTop;
			element = element.offsetParent;
		}
		return result;
	}
};

function Endrag(source,target,offSetX, offSetY){
	source=typeof(source)=="object" ? source:document.getElementById(source);
	target=typeof(target)=="object" ? target:document.getElementById(target);
	var x0=0,y0=0,x1=0,y1=0,moveable=false,index=100,NS=(navigator.appName=='Netscape');
	offSetX=typeof offSetX== "undefined" ? 0:offSetX;
	offSetY=typeof offSetY== "undefined" ? 0:offSetY;
	source.onmousedown=function(e){
		e = e ? e : (window.event ? window.event : null);
		funs.getFocus(target);
		if(e.button==(NS)?0 :1) {
			if(!NS){
				this.setCapture();
			}
			x0 = e.clientX ;  
			y0 = e.clientY ;  
			x1 = parseInt(funs.abs(target).x);
			y1 = parseInt(funs.abs(target).y);
			moveable = true;  
		}  
	};  
	//拖动;  
	source.onmousemove=function(e){
		e = e ? e : (window.event ? window.event : null);  
		if(moveable){  
			target.style.left = (x1 + e.clientX - x0 - offSetX) + "px";  
			target.style.top  = (y1 + e.clientY - y0 - offSetY) + "px";  
		}  
	}; 
	//停止拖动;  
	source.onmouseup=function (e){ 
		if(moveable)  {
			if(!NS){
				this.releaseCapture();
			}
			moveable = false;  
		} 
	};
}

function getMousePosition(obj) {
	if (self.innerHeight) {
		windowWidth = self.innerWidth;
		windowHeight = self.innerHeight;
	}else if (document.documentElement && document.documentElement.clientHeight) {
		windowWidth = document.documentElement.clientWidth;
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) {
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}
	
	var scrollTop = 0;
	if(document.documentElement && document.documentElement.scrollTop) {
		scrollTop = document.documentElement.scrollTop;
	} else if(document.body) {
		scrollTop = document.body.scrollTop;
	}
	
	var l = windowWidth / 2 - obj.width() / 2;
	var t = scrollTop + 150; //windowHeight / 2 - obj.height() / 2;
	return {left:l, top:t};
}