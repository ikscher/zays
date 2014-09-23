//此js用作对联，模板里面不需要加入任何东西，只有两幅图片 ：distich_left.jpg and distich_right.jpg


var lastScrollY=0;



function heartBeat(){ 
var diffY;

if (document.documentElement && document.documentElement.scrollTop)
diffY = document.documentElement.scrollTop;
else if (document.body)
diffY = document.body.scrollTop
else
{/*Netscape stuff*/}
percent=.1*(diffY-lastScrollY); 
if(percent>0)percent=Math.ceil(percent); 
else percent=Math.floor(percent); 
document.getElementById("lovexin12").style.top=parseInt(document.getElementById("lovexin12").style.top)+percent+"px";
document.getElementById("lovexin14").style.top=parseInt(document.getElementById("lovexin12").style.top)+percent+"px";
lastScrollY=lastScrollY+percent; 
}

suspendcode12="<DIV id=\"lovexin12\" style='right:2px;POSITION:absolute;TOP:60px;width: 100px;Z-INDEX: 10; height: 300px;LEFT: 5%; '><img src='module/index/templates/default/images/close.gif' onClick='javascript:window.hide()' width='80' height='11' border='0' vspace='3' alt='关闭'><img src='module/index/templates/default/images/distich_left.jpg' width='80' height='516' border='0'></DIV>"

suspendcode14="<DIV id=\"lovexin14\" style='right:2px;POSITION:absolute;TOP:120px;width: 100px;Z-INDEX: 10; height: 300px;RIGHT: 4%; '><img src='module/index/templates/default/images/close.gif' onClick='javascript:window.hide()' width='80' height='11' border='0' vspace='3' alt='关闭'><img src='module/index/templates/default/images/distich_right.jpg' width='80' height='516' border='0'></DIV>"

document.write(suspendcode12); 
document.write(suspendcode14); 
window.setInterval("heartBeat()",1);




function hide()  {   
 document.getElementById("lovexin12").style.display="none";
 document.getElementById("lovexin14").style.display="none";

}

