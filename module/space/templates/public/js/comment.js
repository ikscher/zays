$(function(){
	$("#comment_select,.down_div").click(function(){
		$("#list_div").show();
		is_show = true;
	})
	$("#list_div li").click(function(){
		$("#list_div").hide();
		var id = this.id;
		var comment = $(this).text();
		var obj_comment_select = $("#comment_select");
		$("#commentid").val(id);
		var obj = $("#choose_comment");
		var obj2 = $("#choose_face");
		
		var sort_comment = obj.val();
		var sort_face = obj2.val();
		if(sort_face != "" && obj.val() == ""){
			var sel_face = obj_comment_select.val();
			obj_comment_select.val(sel_face + comment);
		}else{
			obj_comment_select.val(comment);
		}
		obj.val("1");
	})
	
})
