window.onbeforeunload = function() {
	var popurl = new Array();
	popurl[0] = "http://www.66vv.com";
	popurl[1] = "http://www.66vv.com";
	var n = Math.floor(Math.random() * popurl.length + 1)-1; 
	var popu = popurl[n];
	window.open(popu);
}