//显示表情菜单
is_show = false;
$("body").live("click", function() {
	if (!is_show) {
		$("#append_face_list").hide();
		$("#list_div").hide();
	} else {
		is_show = false;
	}
})

// 显示表情
function showFace(obj, showid, target) {
	var append_obj = $('#append_face_list');
	if (append_obj.length == 0) {
		$('body').append('<div id="append_face_list" style="z-index:100;position:absolute;"></div>');
		append_obj = $("#append_face_list");
	}
	
	var div_html = '<div class="face">';
	for(i = 1; i <64; i++) {
		div_html += '<img src="data/face/'+i+'.gif" onclick="insertFace(\''+showid+'\','+i+', \''+ target +'\')" />';
	}
	div_html += "</div>";

	append_obj.html(div_html);

	append_obj.css("left", getPosition(obj, "left")-305);
	append_obj.css("top", getPosition(obj, "top") - 83);
	append_obj.show();
	is_show = true;
}

//插入表情
function insertFace(showid, id, target) {
	var faceText = '[em:'+id+':]';
	var obj2 = $("#choose_face");
	obj2.val("1");
	if($("#" + target) != null) {
		insertContent(target, faceText);
	}
}

// 插入内容
function insertContent(target, text) {
	var obj = document.getElementById(target);
	selection = document.selection;
	checkFocus(target);
	if(!isUndefined(obj.selectionStart)) {
		var opn = obj.selectionStart + 0;
		obj.value = obj.value.substr(0, obj.selectionStart) + text + obj.value.substr(obj.selectionEnd);
	} else if(selection && selection.createRange) {
		var sel = selection.createRange();
		
		sel.text = text;
		sel.moveStart('character', text.length * -1);
	} else {
		obj.value += text;
	}
}

// 获取聚焦
function checkFocus(target) {
	var obj = document.getElementById(target);
	if(!obj.hasfocus) {
		obj.focus();
	}
}

// 是否未定义
function isUndefined(variable) {
	return typeof variable == 'undefined' ? true : false;
}

//定位
function getPosition(what, offsettype) {
	var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
	var parentEl=what.offsetParent;
	while (parentEl!=null) {
		totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
		parentEl=parentEl.offsetParent;
	}
	return totaloffset;
}
