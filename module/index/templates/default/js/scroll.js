suspendcode="<DIV id=lovexin1 style='Z-INDEX: 10; LEFT: 0px; POSITION: absolute; TOP: 0px; width: 80; height: 635px;'><img src='module/index/templates/default/images/close.gif' onClick='javascript:window.hide()' width='100' height='14' border='0' vspace='3' alt=''><p></p><img src='module/index/templates/default/images/party90.gif' border='0'/></DIV>"
document.write(suspendcode);

suspendcode="<DIV id=lovexin2 style='Z-INDEX: 10; LEFT: 1320px; POSITION: absolute; TOP: 0px; width: 80; height: 635px;'><img src='module/index/templates/default/images/close.gif' onClick='javascript:window.hide()' width='100' height='14' border='0' vspace='3' alt=''><img src='module/index/templates/default/images/party90.gif' width='80' height='621' border='0'></DIV>"
document.write(suspendcode);


//flashʽ÷
//<EMBED src='flash.swf' quality=high  WIDTH=100 HEIGHT=300 TYPE='application/x-shockwave-flash' id=ad wmode=opaque></EMBED>

lastScrollY=0;
function heartBeat(){
diffY=document.body.scrollTop;
percent=.3*(diffY-lastScrollY);
if(percent>0)percent=Math.ceil(percent);
else percent=Math.floor(percent);
document.getElementById("lovexin1").style.pixelTop+=percent;
document.getElementById("lovexin2").style.pixelTop+=percent;
lastScrollY=lastScrollY+percent;
}
function hide()  
{   
document.getElementById("lovexin1").style.visibility="hidden"; 
document.getElementById("lovexin2").style.visibility="hidden";
}
window.setInterval("heartBeat()",1);
