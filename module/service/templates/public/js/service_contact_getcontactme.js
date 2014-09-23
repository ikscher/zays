// JavaScript Document 
// 读取数组显示用户信息
function userdetail(number,arrayobj) {
	var arrname = arrayobj;
  	for(i=0;i<arrayobj.length;i++) {
		var valueArray  = arrayobj[i].split(",");
		if(valueArray[0] == number) {
			if(valueArray[0] == '0'  && valueArray[1] != '男士') {
				document.write("保密");
			} else {
				document.write(valueArray[1]);
			}	
		}
  	}
}
function doshow(num){
for(var id = 1;id<=3;id++)
{
	var tab=document.getElementById("tab_"+id);
	var menu=document.getElementById("menu_"+id);
	tab.style.display=id==num?"block":"none";
	//menu.className=id==num?"g_nav_hover":"";		
}
	if(num == 1)
		window.location.href="index.php?n=service&h=commission&t=getcontactme&page=1";
	if(num == 2)			
		window.location.href="index.php?n=service&h=commission&t=getcontactme&page2=1";
	if(num == 3)	
		window.location.href="index.php?n=service&h=commission&&t=getcontactme&page3=1";
}
//显示隐藏DIV
function setTab(name,cursel,n){
 for(i=1;i<=n;i++){
  var menu=document.getElementById(name+i);
  var con=document.getElementById("con_"+name+"_"+i);
  menu.className=i==cursel?"f-b-d73c90":"f-000";
  con.style.display=i==cursel?"block":"none";
 }
}