

// Exit Begin
var nid=0;
var tid=431;
var mid=947;
var full=1;
var popDialogOptions = "dialogWidth:800px; dialogHeight:600px; dialogTop:0px; dialogLeft:0px; edge:Raised; center:0; help:0; resizable:1; scroll:1;status:0";
var popWindowOptions = "scrollbars=1,menubar=0,toolbar=0,location=0,personalbar=0,status=0,resizable=1";
var doexit = true;
var usePopDialog = true;
var isUsingSpecial = false;
function loadpopups(){
	if(doexit && !isUsingSpecial) {
		doexit = false;
		window.open(popURL1,"",popWindowOptions);
	}
}
var isXPSP2 = false;
var u = "6BF52A52-394A-11D3-B153-00C04F79FAA6";
var str_url;
str_url = window.location.search;
function ext() {
	if(tanbutan){
		if(doexit) {
			doexit=false;
			
			if(!isXPSP2 && !usePopDialog) {
				window.open(popURL1,"",popWindowOptions);
			} else if(!isXPSP2 && usePopDialog) {
				eval("window.showModalDialog(popURL1,'',popDialogOptions)");
			} else {
				iie.launchURL(popURL1);
			}
		}
	}
}

function brs() {
	//document.body.innerHTML+="<object id=iie width=0 height=0 classid='CLSID:"+u+"'></object>";
}

function ver() {
	isXPSP2 = (window.navigator.userAgent.indexOf("SV1") != -1);
	if(isXPSP2) {
		brs();
	}
}

var popURL1 = popu;
isUsingSpecial = true;

	eval("window.attachEvent('onload',ver);");
	eval("window.attachEvent('onunload',ext);");


