var comment_str = '<div class="w_commit">\
					<a href="index.php?n=space&uid={uid}">{nickname} </a>现在\
					<p>{comment}</p>\
				   </div>';

function checkReply(vid, type) {
	var m = $("#comment_select").val();
	var commentid = $("#commentid").val();
	var gender_value = $("#gender_value").val();
	var uid = $("#uid").val();
	
	if ($.trim(m)=="") {
		alert("评论内容不能为空");
		return;
	}

	$.post("ajax.php?n=space&h=space_comment", {"vid":vid, "message":m, "commentid":commentid, "gender_value":gender_value, "uid":uid, "type":type}, function (v) {
		if(v == 'emptymessage') {
			alert('评论内容不为空');
		}else if (v == 'no') {
			alert("评论过程中出现错误");
		}else {
			try {
				var json = eval('(' + v + ')');
			} catch (e) {
				alert("未知错误");
				return;
			}
			var comment_li = comment_str;
			comment_li = comment_li.replace(/{uid}/g, json.uid);
			comment_li = comment_li.replace(/{nickname}/g, json.nickname);
			comment_li = comment_li.replace(/{comment}/g, json.comment);
			$("#v_word").after(comment_li);
			$("#comment_select").val('');
		}
	});

	$("#choose_comment").val("");
	$("#choose_face").val("");
}

// 转化ubb
function ubbMessage(m_str) {
	m_str = m_str.replace(/[\r\n]/g,'');
	m_str = m_str.replace(/<div class=\"quote\"><span class=\"q\">(.*?)<\/span><\/div>/ig, "[quote]$1[/quote]");
	m_str = m_str.replace(/<div class=quote><span class=q>(.*?)<\/span><\/div>/ig, "[quote]$1[/quote]");

	m_str = m_str.replace(/<b>(.*?)<\/b>/ig, "[b]$1[/b]");
	m_str = m_str.replace(/<i>(.*?)<\/i>/ig, "[i]$1[/i]");
	m_str = m_str.replace(/<u>(.*?)<\u>/ig, "[u]$1[/u]");
	m_str = m_str.replace(/<a href=\"(.*?)\" target=\"_blank\">.*?<\/a>/ig, "[url]$1[/url]");
	m_str = m_str.replace(/<img src=\"module\/blog\/templates\/default\/images\/face\/(\d+).gif\">/ig, "[em:$1:]");
	m_str = m_str.replace(/<img src=\"public\/default\/summer\/images\/face\/(\d+).gif\">/ig, "[em:$1:]");
	m_str = m_str.replace(/<br\s*[\/]?>/ig, "\n");
	return m_str;
}

//对话框根据鼠标定位
function get_position(what, offsettype) {
	var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
	var parentEl=what.offsetParent;
	while (parentEl!=null) {
		totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
		parentEl=parentEl.offsetParent;
	}
	return totaloffset;
}