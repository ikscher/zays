var idval1;
var pas_val;
var total;
var Drag={
	"obj":null,
	"init":function(handle,dragBody,val,t1,t2, e){
	  idval1 = t1;  
	  total=t2;
if (e == null) {
			handle.onmousedown=Drag.start;
		}
		handle.root = dragBody;
		//alert(val);
		//alert(dragBody.style.left);
		if(isNaN(parseInt(handle.root.style.left)))handle.root.style.left=val+"px";
		handle.root.onDragStart=new Function();
		handle.root.onDragEnd=new Function();
		handle.root.onDrag=new Function();
		if (e !=null) {
			var handle=Drag.obj=handle;
			e=Drag.fixe(e);
			var left=parseInt(handle.root.style.left);
			handle.root.onDragStart(left,e.pageX);
			handle.lastMouseX=e.pageX;
			document.onmousemove=Drag.drag;
			document.onmouseup=Drag.end;
		}
 	},
	"start":function(e){
		var handle=Drag.obj=this;
		e=Drag.fixEvent(e);
		var left=parseInt(handle.root.style.left);
		handle.root.onDragStart(left,e.pageX);
		handle.lastMouseX=e.pageX;
		document.onmousemove=Drag.drag;
		document.onmouseup=Drag.end;
		return false;
	}, 
	"drag":function(e){//这里的this为document 所以拖动对象只能保存在Drag.obj里
		e=Drag.fixEvent(e);
		var handle=Drag.obj;
		var mouseX=e.pageX;
		var left=parseInt(handle.root.style.left);
		var currentLeft,currentTop;
		currentLeft=left+mouseX-handle.lastMouseX;
		//alert(currentLeft);
		if(currentLeft>425){
			
			currentLeft=422;
			}  
		
		if(currentLeft<=25){
			     currentLeft=25;
			}
	    pas_val=currentLeft;		
			
		handle.root.style.left=currentLeft +"px";
		 
		//alert(currentLeft);
		 
		handle.lastMouseX=mouseX;
		 

		handle.root.onDrag(currentLeft,e.pageX);//调用外面对应的函数
		return false;
	},
	"end":function(){
		//alert(Drag.obj.root.style.left);
		document.onmousemove=null;
		document.onmouseup=null;
		if(pas_val>25&&pas_val<104){
			
		  document.getElementById("id_"+idval1+"_1").checked='checked';
	      document.getElementById("is_vote_"+idval1).value=1;	
		}
		else if(pas_val>=104&&pas_val<=184){
		  document.getElementById("id_"+idval1+"_2").checked='checked';
	      document.getElementById("is_vote_"+idval1).value=2;		
			
		}
		else if(pas_val>184&&pas_val<=264){
			document.getElementById("id_"+idval1+"_3").checked='checked';
	        document.getElementById("is_vote_"+idval1).value=3;	
			
	    }
		else if(pas_val>264&&pas_val<=344){
			document.getElementById("id_"+idval1+"_4").checked='checked';
	       document.getElementById("is_vote_"+idval1).value=4;	
			
	    }
		else if(pas_val>344&&pas_val<=425){
		  document.getElementById("id_"+idval1+"_5").checked='checked';
	      document.getElementById("is_vote_"+idval1).value=5;	
	    }
		else{
		  document.getElementById("id_"+idval1+"_1").checked='';
		  document.getElementById("id_"+idval1+"_2").checked='';
		  document.getElementById("id_"+idval1+"_3").checked='';
		  document.getElementById("id_"+idval1+"_4").checked='';
		  document.getElementById("id_"+idval1+"_5").checked='';
	      document.getElementById("is_vote_"+idval1).value='';		
			
		}
		var is_auto=document.getElementById('is_auto').checked;
		var r=(idval1/num*100).toFixed(0); 
	    var pic_w=r*2;
		if(total<=num&&is_auto){
			  document.getElementById("sche").innerHTML=r;
			  document.getElementById("schedule").width=pic_w;
	          document.getElementById("schedule").height=12;
			setTimeout("auto_show("+idval1+","+total+")",300);
	    }
		
		
		Drag.obj.root.onDragEnd(parseInt(Drag.obj.root.style.left));
		Drag.obj=null;
	},
	"fixEvent":function(e){//格式化事件参数对象
		if(typeof e=="undefined")e=window.event;
		if(typeof e.layerX=="undefined")e.layerX=e.offsetX;
		if(typeof e.pageX == "undefined")e.pageX = e.clientX + document.body.scrollLeft - document.body.clientLeft;
		return e;
	}
};
