// JavaScript Document
$(function(){

	webcam.set_swf_url('module/myaccount/templates/default/js/webcam.swf');
	webcam.set_api_url('index.php?n=myaccount&h=makepic');	// The upload script
	webcam.set_quality(90);				// JPEG Photo Quality
	webcam.set_shutter_sound(true, 'module/myaccount/templates/default/js/shutter.mp3');
        //webcam.set_shutter_sound(true);
	// Generating the embed code and adding it to the page:	
	var cam = $("#webcam");
	cam.html(
	    webcam.get_html(cam.width(), cam.height())
	);

	var camera = $("#camera");
	var shown = false;
	camera.animate({
           bottom:0
	}); 
	
	$("#btn_shoot").click(function(){
	    if(!webcam.get_movie()) return false;
		
	        webcam.freeze();
		$("#shoot").hide();
		$("#upload").show();
		return false;
	});
	
	$('#btn_cancel').click(function(){
		webcam.reset();
		$("#shoot").show();
		$("#upload").hide();
		return false;
	});
	
	
	webcam.set_hook('onComplete', function(msg){
	       
		//msg = $.parseJSON(msg);

		/* if(msg.error){
		    alert(msg.message);
		}
		else { */
		    parent.location.href='index.php?n=myaccount&h=videoindex';

		    // Adding it to the page;
		    /*var pic = '<li><img src="'+msg.filename+'">' +
			'<p><a href="index.php?n=myaccount&h=videoindex&pic_url=data/upload/images/photo/2014/01/21/orgin/20140121121247372619.jpg&actio=u">��Ϊ������</a> ' +
			'<a href="index.php?n=myaccount&h=videoindex&tmp_id=172&pic_url=data/upload/images/photo/2014/01/21/orgin/20140121121247372619.jpg&actio=d">ɾ��</a></p></li>'
		    var pic = '<img src="'+msg.filename+'">';
		    $("#pic_flash").prepend(pic);
		    initFancyBox();*/
		//}
	    });

		
	$('#btn_upload').click(function(){
	    //if(!webcam.get_movie()) return false;
		webcam.upload();
		webcam.reset();
		$("#shoot").show();
		$("#upload").hide();
		return false;
	});
	
	 webcam.set_hook('onError',function(e){
		cam.html(e);
	    });
    
});