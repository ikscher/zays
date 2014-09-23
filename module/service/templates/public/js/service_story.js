//日期、对话人昵称、信息条数
function get_story(fromid,username){
	username = encodeURIComponent(username);
	
	var chatfromid = $('#chatfromid').val();
	
	var changeUrl = "ajax.php?n=service&h=storylist&chatfromid="+chatfromid+"&uid="+fromid+"&username="+username+"&sid="+Math.random();
	$.get(changeUrl,function(str){
		switch(str){
			case "no_login":
			//alert('未登陆，转向登陆页');
			location.href = "index.php?n=login";
			break;
			case "no_data":
			alert('没有记录');
			location.href="index.php?n=service";
			break;
			default:
			$("#storylist").html(str);
			$("#story_content").html('');
			break;
		}
	})
}
//详细聊天内容
function get_story_show(fromid,username,between){
	username = encodeURIComponent(username);
	var pagesize = $("#s_pagesize").val();
	var page = $("#s_page").val();
	
	var chatfromid = $('#chatfromid').val();
	
	var changeUrl = "ajax.php?n=service&h=storydetail&chatfromid="+chatfromid+"&uid="+fromid+"&username="+username+"&pagesize="+pagesize+"&page="+page+"&between="+between+"&sid="+Math.random();
	$.get(changeUrl,function(str){
		switch(str){
			case "no_login":
			//alert('未登陆，转向登陆页');
			location.href = "index.php?n=login";
			break;
			case "no_data":
			
			break;
			default:
			$("#storydetail").html(str);
			break;
		}
	})
}
//点击上一页、下一页
function get_story_page(page,fromid,username,between){
	switch(page){
		case 1:
		document.getElementById('s_page').options[0].selected = true;
		break;
		case 2:
		document.getElementById('s_page').options[$("#s_page").val()-2].selected = true;
		break;
		case 3:
		document.getElementById('s_page').options[$("#s_page").val()].selected = true;
		break;
		case 4:
		document.getElementById('s_page').options[document.getElementById('s_page').options.length-1].selected = true;
		break;
	}
	get_story_show(fromid,username,between);
}
//fromid获取会员昵称表的value和text
function getOptionText(){
	//text
	username = $("#s_fromid>option:selected").get(0).text;
	//value
	fromid = document.getElementById('s_fromid').value;
	get_story(fromid,username)
}