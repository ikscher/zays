//上一页
function prevPhoto(total){
	var setpage = parseInt($("#setpage").attr("value")); //获得可存储，设置的当前页指针
	if(setpage > 0) {
		document.getElementById("setpage").setAttribute("value",setpage-1); //当前页指向自动上一个
	}
	var num = 4; //显示3个图片
	var page = Math.ceil(total/4); //分多少页
	var currentpage = setpage - 1; //当前页
	
	//没有图片的时候不再继续
	if(currentpage >= 0) {
	
		//把全部的置为style:none
		for(i=0;i<total;i++) {
			ab = document.getElementById("ab"+i);	
			ab.style.display = "none";
		}
	
		//当前的页显示的图片
		var pic_min = currentpage * num;
		var pic_max = num + (currentpage * num);
		if(pic_min < total) {
			for(j=pic_min; j<pic_max;j++) {
				if(j != total) { //如果已经没有图片了不再操作了
					ac = document.getElementById("ab"+j);
					if(ac) ac.style.display = "block";
				}
			}
		}
		
	}
}

//下一页
function nextPhoto(total) {
	var setpage = parseInt($("#setpage").attr("value")); //获得可存储，设置的当前页指针
	var num = 4; //显示3个图片
	var page = Math.ceil(total/4); //分多少页
	var currentpage = setpage + 1; //当前页
	if(currentpage <= page - 1) { //如果大于分页就不再指下去
		$("#setpage").attr("value",setpage+1); //当前页指向自动下一个
		//把全部的置为style:none
		for(i=0;i<total;i++) {
			ab = document.getElementById("ab"+i);	
			ab.style.display = "none";
		}
		
		//当前的页显示的图片
		var pic_min = currentpage * num;
		var pic_max = num + (currentpage * num);
		if(pic_min < total) {
			for(j=pic_min; j<pic_max;j++) {
				if(j != total) { //如果已经没有图片了不再操作了
					ac = document.getElementById("ab"+j);
					if(ac) ac.style.display = "block"; 
				}
			}
		}
	}
}


this.imagePreview = function(){
	xOffset = 100;
	yOffset = 30;
	$("a.preview").hover(function(e){
	   this.t = this.title;
	  // alert($("#image_size").attr("height"));
	   this.title = "";
	   var c = (this.t != "") ? "<br/>" + this.t : "";
	
	   $("body").append("<p id='preview'><img src='"+ this.href +"' alt='' id='showImg' />"+"</p>");       
	   image = new Image();
	   image.src = this.href;
	   
	   width_img = image.width;
	   //alert(width_img);
	   height_img = image.height;
	   //alert(height_img);
	
	   w = width_img;
	   h = height_img;
	   if(width_img > 600) {
		   w = 600;
		   h = height_img * 600 / width_img;
	   }else if(height_img > 480){
		   h = 480;
		   w = width_img * 480 / height_img;
		}
		
		$("#showImg").attr("width",w);
		$("#showImg").attr("height",h);
	    $("#preview")
		.css("top",(e.pageY - xOffset) + "px")
		.css("left",(e.pageX + yOffset) + "px")
		.fadeIn("fast");      
		},
	function(){
	   //this.title = this.t;
	   $("#preview").remove();
		});
	$("a.preview").mousemove(function(e){
	   $("#preview")
		.css("top",(e.pageY - xOffset) + "px")
		.css("left",(e.pageX + yOffset) + "px");
	});   
};
