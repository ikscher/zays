<script type="text/javascript" src="public/system/js/jquery-1.3.2.min.js"></script>
<?php if($GLOBALS['MooUid'] && MooGetGPC('h','string')!='return'&&MooGetGPC('h','string')!='pay'&& MooGetGPC('h','string') != 'picflash' &&  MooGetGPC('h','string') != 'makepic' &&MooGetGPC('h','string') != 'history' && MooGetGPC('h','string') != 'chat') { ?>
<script type="text/javascript" src="public/system/js/jquery.floatDiv.js"></script>
<script type="text/javascript" src="public/system/js/rightbottom.js"></script>
<script type="text/javascript" src="public/system/js/jquery.cookie.js"></script>
<?php } ?>
<script src="public/system/js/sys1.js?v=1"></script>
<script type="text/javascript" src="module/register/templates/public/js/syscode.js?v=1"></script>
<script type="text/javascript" src="public/system/js/fancybox/jquery.fancybox-1.2.5.pack.js"></script>
<script type="text/javascript">
$(function(){
	$("a.zoom").fancybox();
});

$(function(){
	$("a.zoomb").fancybox();
});
</script>
<script src="module/space/templates/public/js/preview_pic_new.js" type="text/javascript"></script>
<script type="text/javascript">
	var tab_index = 0;
	var obj_a = {};
	var tab_time;
	var is_lock = 0;
	$(function(){
		$("input[name=search_type][value=1]").attr("checked","checked");
		$("input[name=search_type]").click(function(){
			if(this.value == 1){
				$("#search_more").css("display","");
				$("#search_uid").css("display","none");
			}
			if(this.value == 2){
				$("#search_uid").css("display","");
				$("#search_more").css("display","none");
			}
			
		})
		
		$("#members_list li").hover(function(){
			$(this).addClass("i-m-img-show-hover");
		},function(){
			$(this).removeClass("i-m-img-show-hover");
		});
		$("#members_new li").hover(function(){
			$(this).addClass("get-register-hover");
		},function(){
			$(this).removeClass("get-register-hover");
		});
		

		$("#tab_img a").click(function(){
			tab_click(this.id.split('_')[1]);
		}).mousemove(function(){
			tab_click(this.id.split('_')[1]);
			tab_run(0);
		}).mouseout(function(){
			tab_run(1);
		});
		tab_run(1);
		obj_a =$("#img_tab_box a");
		$("#img_tab_box").mouseover(function(){
			tab_run(0);
		}).mouseout(function(){
			tab_run(1);
		});
	})
	function tab_click(n){
		if(is_lock) return;
		is_lock = 1;
		$("#tab_img a").removeClass("over");
		$("#tab_"+n).addClass("over");
		for( var i=0; i<5; i++ ){
			if( i!=n ){
				obj_a.eq(i).hide();
			}
		}
		obj_a.eq(n).fadeIn("slow" , function(){is_lock = 0;});
		
	}
	function tab_run( t ){
		if( t ){
			if(tab_time)clearInterval( tab_time );
			tab_time = setInterval(function(){
				tab_index = tab_index < 4 ? tab_index + 1 : 0;
				tab_click( tab_index )
			},4000);
		}else{
			clearInterval( tab_time );
		}
	}

$(function(){
	$("a").click(function(event){    
		event.stopPropagation();
	})
})
</script>
<script type="text/javascript">
<!--
function checkquicksearch(){
	var age1 = document.getElementById("age1").value;
	var age2 = document.getElementById("age2").value;
	if( age1 == -1 && age2 == -1) {
	alert("请选择年龄");
	document.getElementById("age1").focus();
	return false;
	}
	if(age1!=null && age1!='' && age1==-1 && age2!=null && age2!='' && age2!=-1) {
	alert("请选择年龄下限");
	document.getElementById("age1").focus();
	return false;
	}
	if(age1!=null && age1!='' && age1!=-1 && age2!=null && age2!='' && age2==-1) {
	alert("请选择年龄上限");
	document.getElementById("age2").focus();
	return false;
	}
	if(age1!=null && age1!='' && age1!=-1 && age2!=null && age2!='' && age2!=-1 && age1>age2) {
	alert("您选择的年龄范围不正确，请重新选择");
	document.getElementById("age1").focus();
	return false;
	}
	return;
}
//-->
</script>
<script type="text/javascript">
  function userdetail(number,arrayobj) {
  	var arrname = arrayobj;
  	for(i=0;i<arrayobj.length;i++) {
		var valueArray  = arrayobj[i].split(",");
		if(valueArray[0] == number) {
			if(valueArray[0] == '0' && valueArray[1] != '男士') {
				document.write("不限");
				//return '不限';
			} else {
				document.write(valueArray[1]);
			}

		}
  	}
  }
</script>
<script type="text/javascript"> 
//控制图片显示 
function showpic(tt){
	var  show_pic_1 = tt.getAttribute('src');
	var  show_big_pic = tt.getAttribute('flag');
	var  thumbpic = document.getElementsByName('thumbpic');
	for(i=0;i<thumbpic.length;i++){
		thumbpic[i].className = 'tt';
	}
	tt.className = 'tt1';
	show_pic_1 = show_pic_1.replace("41_57", "171_244");
	document.getElementById('save_pic').setAttribute('href',show_big_pic);
	document.getElementById('show_pic_1').setAttribute('src',show_pic_1);
}
</script>

<script language="javascript"> 
//控制弹出浮动窗口
function alertWin(title, msg, w, h){  
    var titleheight = "25px";
    var bordercolor = "#b75bad";
    var titlecolor = "#FFFFFF";
    var titlebgcolor = "#666699";
    var bgcolor = "#FFFFFF";
     
    var iWidth = document.documentElement.clientWidth;  
    var iHeight = document.documentElement.clientHeight;  
    var bgObj = document.createElement("div");  
    bgObj.style.cssText = "position:absolute;left:0px;top:0px;width:"+iWidth+"px;height:"+Math.max(document.body.clientHeight, iHeight)+"px;filter:Alpha(Opacity=30);opacity:0.3;background-color:#000000;z-index:101;"; 
    document.body.appendChild(bgObj);  
     
    var msgObj=document.createElement("div"); 
    msgObj.style.cssText = "position:absolute;top:"+(iHeight-h)/2+"px;left:"+(iWidth-w)/2+"px;z-index:102;border:3px solid #b75bad;width:345px;padding:10px;color:#666;background:#fff"; 
    document.body.appendChild(msgObj); 
     
    var table = document.createElement("table"); 
    msgObj.appendChild(table); 
    table.style.cssText = "margin:0px;border:0px;padding:0px;"; 
    table.cellSpacing = 0; 
    var tr = table.insertRow(-1); 
    var titleBar = tr.insertCell(-1); 
    titleBar.style.cssText = "cursor:move"; 
    titleBar.style.paddingLeft = "10px"; 
    titleBar.innerHTML = title; 
    var moveX = 0; 
    var moveY = 0; 
    var moveTop = 0; 
    var moveLeft = 0; 
    var moveable = false; 
    var docMouseMoveEvent = document.onmousemove; 
    var docMouseUpEvent = document.onmouseup; 
    titleBar.onmousedown = function() { 
        var evt = getEvent(); 
        moveable = true;  
        moveX = evt.clientX; 
        moveY = evt.clientY; 
        moveTop = parseInt(msgObj.style.top); 
        moveLeft = parseInt(msgObj.style.left); 
         
        document.onmousemove = function() { 
            if (moveable) { 
                var evt = getEvent(); 
                var x = moveLeft + evt.clientX - moveX; 
                var y = moveTop + evt.clientY - moveY; 
                if ( x > 0 &&( x + w < iWidth) && y > 0 && (y + h < iHeight) ) { 
                    msgObj.style.left = x + "px"; 
                    msgObj.style.top = y + "px"; 
                } 
            }     
        }; 
        document.onmouseup = function () {  
            if (moveable) {  
                document.onmousemove = docMouseMoveEvent; 
                document.onmouseup = docMouseUpEvent; 
                moveable = false;  
                moveX = 0; 
                moveY = 0; 
                moveTop = 0; 
                moveLeft = 0; 
            }  
        }; 
    } 
     
    var closeBtn = tr.insertCell(-1); 
    //closeBtn.style.cssText = "cursor:pointer; padding:2px;background-color:" + titlebgcolor; 
    //closeBtn.innerHTML = "<span style='font-size:15pt; color:"+titlecolor+";'>×</span>"; 
    closeBtn.onclick = function(){  
        document.body.removeChild(bgObj);  
        document.body.removeChild(msgObj);  
    }  
    var msgBox = table.insertRow(-1).insertCell(-1);  
    msgBox.style.cssText = "font:10pt '宋体';"; 
    msgBox.colSpan  = 2; 
    msgBox.innerHTML = msg; 
     
    // 获得事件Event对象，用于兼容IE和FireFox 
    function getEvent() { 
        return window.event || arguments.callee.caller.arguments[0]; 
    } 
}  

//控制弹出登录框
function is_login(uid){
	if(typeof(uid)== "undefined" || uid == 0){
		$("html").css("overflow","hidden");
		$("select").css("display","none");
		alertWin('','<form action="index.php?n=index&h=submit" method="post"><table width="345" border="0" cellpadding="0" cellspacing="0" ><tr><td colspan="2" height="38px;" style="background:#f9f9fb"><strong> <img src="module/space/templates/default/images/zhixiang.jpg" style="margin-right:5px;" />请进行登录，立即查看他人的详细资料。</strong></td></tr><tr><td  align="right" height="38px;">用户名：</td><td align="left"><input name="username" type="text" style="width:160px;"/></td></tr><tr ><td align="right"  height="38px">密 码：</td><td><input name="password" type="password"  style="width:160px;"/></td></tr><tr><td>&nbsp;</td><td style="padding-bottom:10px"><input type="hidden" name="loginsubmit"  value="登录" /><input type="submit" style=" background:url(module/space/templates/default/images/space_btn.jpg); width:75px;height:30px;border:none;cursor:pointer" value=""/><input type="button" style="width:85px;height:30px;border:none;cursor:pointer;background:url(module/space/templates/default/images/forget.gif);margin-left:10px;"value="" onclick="javascript:location.href=\'index.php?n=register\'" /><input id="returnurl" type="hidden" value="<?php echo $returnurl;?>" name="returnurl"/></td></tr><tr><td colspan="2" height="38px;" style="background:#f9f9fb; border-top:1px dashed #ccc"><a href="index.php?n=login&h=backpassword" style="color:#bd0000; font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;忘记密码？</a></td></tr></table></form>',300,200);
	}else{
		$("html").css("overflow","");
		$("select").css("display","");
	}
}

function alertWin2(title, msg, w, h){  
    var titleheight = "25px";
    var bordercolor = "#b75bad";
    var titlecolor = "#FFFFFF";
    var titlebgcolor = "#666699";
    var bgcolor = "#FFFFFF";
     
    var iWidth = document.documentElement.clientWidth; 
    var iHeight = document.documentElement.clientHeight;  
    var bgObj = document.createElement("div");  
    bgObj.style.cssText = "position:absolute;left:0px;top:0px;width:"+iWidth+"px;height:"+Math.max(document.body.clientHeight, iHeight)+"px;filter:Alpha(Opacity=30);opacity:0.3;background-color:#000000;z-index:101;"; 
    document.body.appendChild(bgObj);  
     
    var msgObj=document.createElement("div"); 
    msgObj.style.cssText = "position:absolute;top:"+(iHeight-h+350)+"px;left:"+(iWidth-w)/2+"px;z-index:102;border:3px solid #b75bad;width:436px;padding:10px;color:#666;background:#fff"; 
    document.body.appendChild(msgObj); 
     
    var table = document.createElement("table"); 
	bgObj.setAttribute('id','credits');
	msgObj.setAttribute('id','credits_border');
    msgObj.appendChild(table); 
    table.style.cssText = "margin:0px;border:0px;padding:0px;"; 
    table.cellSpacing = 0; 
    var tr = table.insertRow(-1); 
    var titleBar = tr.insertCell(-1); 
    titleBar.style.cssText = "cursor:move"; 
    titleBar.style.paddingLeft = "10px"; 
    titleBar.innerHTML = title; 
    var moveX = 0; 
    var moveY = 0; 
    var moveTop = 0; 
    var moveLeft = 0; 
    var moveable = false; 
    var docMouseMoveEvent = document.onmousemove; 
    var docMouseUpEvent = document.onmouseup; 
    titleBar.onmousedown = function() { 
        var evt = getEvent(); 
        moveable = true;  
        moveX = evt.clientX; 
        moveY = evt.clientY; 
        moveTop = parseInt(msgObj.style.top); 
        moveLeft = parseInt(msgObj.style.left);
         
        document.onmousemove = function() { 
            if (moveable) { 
                var evt = getEvent(); 
                var x = moveLeft + evt.clientX - moveX; 
                var y = moveTop + evt.clientY - moveY; 
                if ( x > 0 &&( x + w < iWidth) && y > 0 && (y + h < iHeight+350+(iHeight-h)/2) ) { 
				//if(x < iWidth && y < iHeight){
                    msgObj.style.left = x + "px"; 
                    msgObj.style.top = y + "px"; 
                } 
            }     
        }; 
        document.onmouseup = function () {  
            if (moveable) {  
               // document.onmousemove = docMouseMoveEvent; 
                //document.onmouseup = docMouseUpEvent; 
                moveable = false;  
                moveX = 0; 
                moveY = 0; 
                moveTop = 0; 
                moveLeft = 0; 
            }  
        }; 
    } 
     
    var closeBtn = tr.insertCell(-1); 
	closeBtn.innerHTML = '<font id=\'closeBut\'>关闭</font>'; 
    closeBtn.style.cssText = "text-align:right;color:red;cursor:pointer;";
	
	var closeBut = document.getElementById('closeBut');
    closeBut.onclick = function(){  
        document.body.removeChild(bgObj);  
        document.body.removeChild(msgObj);  
    }  
	var newrow = table.insertRow(-1); 
    var newrowBar = newrow.insertCell(-1);
	newrowBar.colSpan = "2";
	newrowBar.innerHTML = '&nbsp;';
	
	bgObj.onclick = function(){
		document.body.removeChild(bgObj);  
        document.body.removeChild(msgObj);	
	}
	
    var msgBox = table.insertRow(-1).insertCell(-1);  
    msgBox.style.cssText = "font:10pt '宋体';"; 
    msgBox.colSpan  = 2; 
    msgBox.innerHTML = msg; 
     
    // 获得事件Event对象，用于兼容IE和FireFox 
    function getEvent() { 
        return window.event || arguments.callee.caller.arguments[0]; 
    } 
}  

//控制弹出视频<div id=\'video\'>
//<font onclick="javascript:$(\'#video\').remove();$(\'#credits\').remove();$(\'#credits_border\').remove();" style="cursor:hand;float:right">关闭</font>
function is_video(){
	alertWin2('成就美好姻缘——真爱一生网','<object height="350px" width="436px" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param value="public/system/flash/player.swf?path=<?php echo $cs_path;?>&amp;img=#" name="movie"><param value="high" name="quality"><param name="SCALE" value="exactfit" /><param value="true" name="allowFullScreen"><param value="false" name="menu"><param value="transparent" name="wmode"><embed height="350px" width="436px" menu="false"  SCALE="exactfit" allowfullscreen="true" value="transparent" wmode="transparent" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" quality="high" style="z-index: inherit;" src="public/system/flash/player.swf?path=<?php echo $cs_path;?>&amp;img=#"></object>',300,-200);	
}

//上一页
function prevPhoto(){
	var setpage = parseInt($('#setpage').attr('value'));
	var count = <?php echo count($user_pic);?>;
	var num = 3; //显示3个图片分
	//if(setpage > 0){
		//$('#setpage').attr('value',setpage-1);
	//}
	var currentpage = setpage - 1; //当前页
	if(currentpage >= 0){
		$('#setpage').attr('value',setpage-1);
		for(i=0;i<count;i++){
			$('#ab'+i).css('display','none');
		}
		//当前的页显示的图片
		var pic_min = currentpage * num;
		var pic_max = num + (currentpage * num);
		for(k=pic_min;k<pic_max;k++){
			if(k < count) { //如果已经没有图片了不再操作了
				$('#ab'+k).css('display','');
			}
		}
		$('#img_last').attr('src','module/space/templates/default/images/last.gif');
		if(currentpage == 0){
			$('#img_last').attr('src','module/space/templates/default/images/last-none.gif');
		}
		$('#img_next').attr('src','module/space/templates/default/images/next.gif');
	}else{
		$('#img_last').attr('src','module/space/templates/default/images/last-none.gif');
		$('#img_next').attr('src','module/space/templates/default/images/next.gif');	
	}
}
//下一页
function nextPhoto(){
	var setpage = parseInt($('#setpage').attr('value'));
	var count = <?php echo count($user_pic);?>;
	var num = 3; //显示3个图片分
	var page = Math.ceil(count/3); 
	var currentpage = setpage + 1; //当前页
	if(currentpage <= page-1){
		$('#setpage').attr('value',setpage+1);
		for(i=0;i<count;i++){
			$('#ab'+i).css('display','none');
		}
		//当前的页显示的图片
		var pic_min = currentpage * num;
		var pic_max = num + (currentpage * num); 
		for(k=pic_min;k<pic_max;k++){
			if(k < count) { //如果已经没有图片了不再操作了
				$('#ab'+k).css('display','');
			}
		}
		$('#img_last').attr('src','module/space/templates/default/images/last.gif');
		$('#img_next').attr('src','module/space/templates/default/images/next.gif');
		if(currentpage == page-1){
			$('#img_next').attr('src','module/space/templates/default/images/next-none.gif');
		}
	}else{
		$('#img_last').attr('src','module/space/templates/default/images/last.gif');
		$('#img_next').attr('src','module/space/templates/default/images/next-none.gif');	
	}
}
</script> 

<script type="text/javascript">
//鼠标滑上去有图片提示
this.imagePreview = function(){
	var xOffset=100;
	var yOffset=30;
	//$('a.preview').hover(function(e){
	$("img#show_pic_1").hover(function(e){
		//var url=this.href;
		var url = $('#save_pic').attr('href');
		$('body').append("<p id='preview'><img src='"+url+"' id='showImg'></p>");
		var image=new Image();
		//image.src=this.href;
		image.src=url;
		var width_img = image.width;
	    var height_img = image.height;
	    w = width_img;
	    h = height_img;
		if(width_img > 600) {
		   w = 600;
		   h = height_img * 600 / width_img;
		}else if(height_img > 480){
		   h = 480;
		   w = width_img * 480 / height_img;
		}
		$('#showImg').css('width',w).css('height',h);
		var e = e ? e : window.event;
		if(e.pageX){
			x1=e.pageX;
			y1=e.pageY;
		}else{
			x1=e.clientX;
			y1=e.clientY;
		}
		$('#preview').css('left',(x1+yOffset)+'px')
		.css('top',(y1-xOffset)+'px')
		.fadeIn('fast'); 
	},
	function(){
		$('#preview').remove();	
	});	
	
	$('img#show_pic_1').mousemove(function(e){
		var e = e ? e : window.event;
		if(e.pageX){
			x2 = e.pageX;
			y2 = e.pageY;
		}else{
			x2 = e.clientX;
			y2 = e.clientY;
		} 
		$('#preview')
		.css('top',(y2-xOffset)+'px')
		.css('left',(x2+yOffset)+'px');
	}) 
}

$(document).ready(function(){
	imagePreview();
});

</script>