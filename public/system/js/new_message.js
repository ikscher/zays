// JavaScript Document
//note flash 播放mp3文件
//note 直接调用play()方法,,musicAddr声音地址(mp3格式)

function play(musicAddr){
	thisMovie("myFlash").playSound(musicAddr);
}
function thisMovie(movieName) {
	if(navigator.appName.indexOf("Microsoft") != -1) {
		return window[movieName];
	}else{
		return document[movieName];
	}
}