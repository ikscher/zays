// JavaScript Document
//控制回车是否提交表单和跳转页面
function checkPageGo(checkboxID){
	//页面输入框
/*	if($("#pageGo").val() != ''){
		gotoPage();
		return false;
	}*/
	//复选框
	var checkboxes = document.getElementsByName(checkboxID);
	var flg = true;
	for(j=0; j < checkboxes.length; j++) {
		if(checkboxes[j].checked){
			flg = false;
			break;
		}
	}
	if(flg){
		alert('请选择要删除的选项！');
		return false;
	}
	//是否执行删除操作
	if(confirm('确定删除吗？')){
		return true;
	}else{
		return false;
	}
}
