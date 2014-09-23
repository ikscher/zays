function selectThumb(thumb, mainPhotoId, noPhotoId) {
	if (thumb.parentNode.className.indexOf('rimSelected') < 0) {
		var mainPhoto = document.getElementById(mainPhotoId);
		var photoBox=document.getElementById('photoBox');	    
        if(mainPhoto.filters != undefined && mainPhoto.filters.blendTrans != undefined){ 
			mainPhoto.filters.blendTrans.apply();
		    mainPhoto.src = replacePhoto(thumb.src, 2);
			if(mainPhoto.readyState=='complete'){
			    mainPhoto.filters.blendTrans.play();
		    }else{
			    if(photoBox) photoBox.style.background='url(http://images.zhenai.com/zhenai3/images/newUI/loading.gif) center center no-repeat';
		        mainPhoto.filters.blendTrans.stop();
			    mainPhoto.style.display='none';
		    }
		}else{
            mainPhoto.src = replacePhoto(thumb.src, 2);
		}
		
		var anchor = mainPhoto.parentNode;
		anchor.href = mainPhoto.src;		
		var photoListUL = thumb.parentNode.parentNode;
		var photos = photoListUL.getElementsByTagName('IMG');		
		for (var i = 0; i < photos.length; i++) {
			photos[i].parentNode.className = photos[i].parentNode.className.replace(' rimSelected', '');
		}		
		thumb.parentNode.className += ' rimSelected';		
		var noPhoto = document.getElementById(noPhotoId);		
		if (noPhoto != undefined) {
			noPhoto.style.display = 'none';
		}
		mainPhoto.parentNode.style.display = '';		
	}	
	//update manifest
	for (var i = 0; i < photoManifest.length; i++) {
		photoManifest[i][1] = false;
		if(photoManifest[i][0] == thumb.id) {
			photoManifest[i][1] = true;
		}
	}
}

function nextPhoto() {
	var found = false;
	for (var i = 0; i < photoManifest.length; i++) {
		if(photoManifest[i][1]) {
			found = true;
			if (i < photoManifest.length - 1) {
				document.getElementById(photoManifest[i + 1][0]).onclick();
			}
			else {	//click the first one
				document.getElementById(photoManifest[0][0]).onclick();
			}
			break;
		}
	}	
	if (!found)
		document.getElementById(photoManifest[0][0]).onclick();
}

function prevPhoto() {
	var found = false;
	for (var i = 0; i < photoManifest.length; i++) {
		if(photoManifest[i][1]) {
			found = true;
			if (i > 0) {
				document.getElementById(photoManifest[i - 1][0]).onclick();
			}
			else {	//click the first one
				document.getElementById(photoManifest[photoManifest.length - 1][0]).onclick();
			}
			break;
		}
	}	
	if (!found) {
		document.getElementById(photoManifest[photoManifest.length - 1][0]).onclick();
	}
}
function constrainPhoto(img) {
    if(img.src.indexOf("loading.gif")>0){return;}
	//img.style.display = 'none';				
	var testImg = new Image();
	testImg.src = img.src;
	var maxWidth = parseInt(img.getAttribute("constrainWidth"));
	var maxHeight = parseInt(img.getAttribute("constrainHeight"));	
	if (maxWidth == 0)
		maxWidth = testImg.width;	
	if (maxHeight == 0)
		maxHeight = testImg.height;	
	var heightScale = maxHeight / Math.max(testImg.height, maxHeight);
	var widthScale = maxWidth / Math.max(testImg.width, maxWidth);	
	var scaleFactor = Math.min(heightScale, widthScale);	
	img.width = testImg.width * scaleFactor;
	img.height = testImg.height * scaleFactor;
	var photoBox=document.getElementById('photoBox');
	if(photoBox) photoBox.style.background='none';
	img.style.display = '';	
	img.style.cursor='pointer';
	img.onclick=nextPhoto;
}
function replacePhoto(photoUrl, index){
   var tmpstrs1 = photoUrl.split(".thumb.");
   /*
   alert(tmpstrs1[0]);	
    var tmpstrs = photoUrl.split("_"); 
    var tmpstr = tmpstrs[0];
	var postfix;
    if (tmpstrs[1]!=null) {
        var aa = tmpstrs[1].split(".");
		if(aa[1] != null){
		    postfix = aa[1];
		}
    }
    //return tmpstr + "_" + index + "." + postfix;
    */
    return tmpstrs1[0];
}
function preLoadNext(nextImgId){
	var nextImg=document.getElementById('nextImgId');
    var img = new Image();
    img.src = nextImg.src;
}
