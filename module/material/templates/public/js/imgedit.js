//JavaScript
$.extend($.imgAreaSelect, { animate: function (fx) { var start = fx.elem.start, end = fx.elem.end, now = fx.now, curX1 = Math.round(start.x1 + (end.x1 - start.x1) * now), curY1 = Math.round(start.y1 + (end.y1 - start.y1) * now), curX2 = Math.round(start.x2 + (end.x2 - start.x2) * now), curY2 = Math.round(start.y2 + (end.y2 - start.y2) * now); fx.elem.ias.setSelection(curX1, curY1, curX2, curY2); fx.elem.ias.update(); $(".imgareaselect-selection img").width(curX2-curX1).height(curY2-curY1);}, prototype: $.extend($.imgAreaSelect.prototype, { animateSelection: function (x1, y1, x2, y2, duration) { var fx = $.extend($('<div/>')[0], { ias: this, start: this.getSelection(), end: { x1: x1, y1: y1, x2: x2, y2: y2 } }); if (!$.imgAreaSelect.fxStepDefault) { $.imgAreaSelect.fxStepDefault = $.fx.step._default; $.fx.step._default = function (fx) { return fx.elem.ias ? $.imgAreaSelect.animate(fx) : $.imgAreaSelect.fxStepDefault(fx); }; } $(fx).animate({ cur: 1 }, duration, 'swing'); } }) });

//全局变量
var ias;
var isIE = $.browser.msie;
var $imgarease;
//当页面加载完初始化
$(document).ready(function(){
	var lis = $("#li_select>li>a");
	lis.bind("click",function(){
		var li_index = lis.index(this);
		lis.removeClass("onthis").eq(li_index).addClass("onthis");
		$(".materials-box").hide().eq(li_index).show();
	});
	$("#frame_box>li>img").bind("click",function(){
		var imgurl = $(this).attr("src").replace('vs/','');
		imgurl = imgurl.replace('gif','png');
		if(isIE){
			iepngfix(imgurl);
		}else{
			$imgarease.attr("src",imgurl);
		}
		var _w = $("img#editimage").width();
		var _h = $("img#editimage").height();
		if (!ias.getSelection().width) {
			var op = this.op || {};
			op.x1 = _w/2 - 1;
			op.y1 = _h/2 - 1;
			op.x2 = _w/2;
			op.y2 = _h/2;
			ias.setOptions({ show: true, x1: op.x1, y1: op.y1, x2: op.x2, y2: op.y2 }); 
		}
		ias.setOptions({resizable: false});
		ias.animateSelection(0, 0, _w, _h, 'slow');
	});
	$("#pendant_box>li>img").bind("click",function(){
		var imgurl = $(this).attr("src");
		ias.setOptions({resizable: true});
		if(isIE){
			iepngfix(imgurl);
		}else{
			$imgarease.attr("src",imgurl);
		}
		var _w = $("img#editimage").width();
		var _h = $("img#editimage").height();
		var _sw = $("img#img_fix").width();
		var _sh = $("img#img_fix").height();
		var op = this.op || {};
			op.x1 = Math.abs(_w-_sw)/2;
			op.y1 = Math.abs(_h-_sh)/2;
			op.x2 = op.x1+_sw;
			op.y2 = op.y1+_sh;
		var t = ias.getSelection();
		if (!t.width || t.width == _w) {
			var op1 = {}
			op1.x1 = _w/2 - 1;
			op1.y1 = _h/2 - 1;
			op1.x2 = _w/2;
			op1.y2 = _h/2;
			if(!t.width){
				ias.setOptions({ show: true, x1: op1.x1, y1: op1.y1, x2: op1.x2, y2: op1.y2 });
			}
			ias.animateSelection(op1.x1-50, op1.y1-50, op1.x2+50, op1.y2+50, 'slow'); 
			return;
		}
		ias.animateSelection(t.x1, t.y1, t.x2, t.y2, 'slow'); 
	});
	$("#albums img").bind('click',function(){
		var k = $("#albums img").index(this);
		var url = "index.php?n=material&h=getimg&imageName="+$("#albums_"+k).val()+"&sid="+Math.random();
		if($('img#editimage').attr("src") == url){ return false; };
		$("#albums>li").removeClass("thisphoto").eq(k).addClass("thisphoto");
		$('img#editimage').attr("src",url);
		if(!ias.getOptions().resizable){
			ias.setOptions({ show: false, x1: 0, y1: 0, x2: 0, y2: 0 });
			ias.update();
		};
	});
	
	ias = $('img#editimage').imgAreaSelect({ handles: false, instance: true, onSelectChange: preview ,persistent:true, minHeight: 30, minWidth: 30, 
	onInit: function(){
			$(".imgareaselect-selection").append("<img id=\"img_fix\" src=\"module/material/templates/public/js/blank.gif\" />");
			$imgarease = $(".imgareaselect-selection img");
		} 
	});
});
//水平滑动
var box_width = box_li_width = 0;
function mySlider_l(flag){
	var flag = flag;//630
	var moveObj = $(".materials-box:visible ul");
	box_width = box_width || $(".materials-box:visible").width();
	box_li_width = box_li_width = $('li',moveObj).width();
	var total_width = total_width || ($('li',moveObj).length) * box_li_width;
	var curleft = parseInt(moveObj.css('margin-left').replace(/px/,''));
	if(flag == '-'){curleft -= box_width}else{curleft += box_width };
	if(total_width + curleft < 0 || curleft > 0) {return false;};
	var tpath = "module/material/templates/default/images/";
	if(total_width > box_width){
		if(total_width + curleft > box_width){
			$("#ipart_prev").attr('src',tpath+'last.gif');
		}else{
			$("#ipart_prev").attr('src',tpath+'last-none.gif');
		}
		if(curleft != 0){
			$("#ipart_next").attr('src',tpath+'next.gif');
		}else{
			$("#ipart_next").attr('src',tpath+'next-none.gif');
		}
		
	}else{
		$("#ipart_prev").attr('src',tpath+'last-none.gif');
		$("#ipart_next").attr('src',tpath+'next-none.gif');
		
	}

	moveObj.animate({ marginLeft: curleft }, 500);	
}
//垂直滑动相册列表
var box_height = total_height = 0;
function mySlider_v(flag){
	var flag = flag;//549
	var moveObj = $("#albums");
	box_height = box_height || moveObj.parent().height();
	total_height = total_height || ($("#albums li").length) * $("#albums li").height();
	var curtop = parseInt(moveObj.css('margin-top').replace(/px/,''));
	if(flag == '-'){curtop -= box_height}else{curtop += box_height };
	if( total_height + curtop < 0 || curtop > 0 ) { return false;};
	var tpath = "module/material/templates/default/images/";
	if(total_height > box_height){//m-nl01.gif
		if(total_height + curtop > box_height){
			$("#albums_up").attr('src',tpath+'m-nl01.gif');
		}else{
			$("#albums_up").attr('src',tpath+'m-nl03.gif');
		}
		if(curtop != 0){
			$("#albums_down").attr('src',tpath+'m-nl02.gif');
		}else{
			$("#albums_down").attr('src',tpath+'m-nl04.gif');
		}
		
	}else{
		$("#albums_up").attr('src',tpath+'m-nl03.gif');
		$("#albums_down").attr('src',tpath+'m-nl04.gif');
		
	}
	moveObj.animate({ marginTop: curtop }, 500);	
}
//ias 回调函数
function preview(img, selection) {
    if (!selection.width || !selection.height){ return false;}
    $('#x1').val(selection.x1);
    $('#y1').val(selection.y1);
    $('#x2').val(selection.x2);
    $('#y2').val(selection.y2);
    $('#w').val(selection.width);
    $('#h').val(selection.height);
	$("img#img_fix").width(selection.width).height(selection.height);
	
}
//动作函数
function editActive(active){
	var active = active;
	var cover = "&cover=no";
	var t = ias.getSelection();
	if(t.x2 == 0 && active == "composite" ) {return false;};
	var $actBut = $("#active_"+active);
	$actBut.attr('disabled','disabled');
	var reg = new RegExp("(^|&)imageName=([^&]*)(&|$)","i");
	var dstim = ($("img#editimage").attr("src").match(reg))[2];
	var srcim = '';
	if(isIE){
		var reg2 = new RegExp("src='([^&]*)',","i");
		srcim = $imgarease.attr("style");
		srcim = encodeURIComponent(srcim.match(reg2)[1]);
	}else{ srcim = encodeURIComponent($("img#img_fix").attr("src"));}
	if($("#coverOriginal:checked").val() == '1' && active == 'save'){
		cover = "&cover=yes";
	}
	var url = "index.php?n=material&h=imgactive"+cover+"&act="+active+"&dstim="+dstim+"&srcim="+srcim+"&sid="+Math.random();
	$.get(url,t,function(str){
		var json = eval("("+str+")");
		if(json.imageFound){
			if(active == "save") window.location.href = "index.php?n=material&h=show&sid="+Math.random();
			var url = "index.php?n=material&h=getimg&imageName="+json.imageName+"&sid="+Math.random();
			$('img#editimage').attr("src",url);
			ias.setOptions({ show: false, x1: 0, y1: 0, x2: 0, y2: 0 });
			ias.update();
		}else if(json.errMsg){ alert(json.errMsg);}
		$actBut.attr('disabled','');
	});
}

//IE png透明
function iepngfix(imgurl){
	var imgurl = imgurl;
	//if(!/\.png$/.test(imgurl)) {$imgarease.attr("src",imgurl);return;}
	$imgarease.attr("style","DISPLAY: inline-block; FILTER: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+imgurl+"',sizingMethod='scale');");
	$imgarease.attr("oSrc",imgurl);
}