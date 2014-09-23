//打开关键词搜索
function show_search(evt,value){
	 $("#keyword_search").css('display','block');
	 evt=evt || window.event;
	 xPos=evt.clientX;
	 yPos=evt.clientY;
	 if(value=="work_address"){
		value=$("#work_province").html()+$("#work_city").html();
	 }
	 $("#up_value").html(value);
	 $("#keyword_search").css('left',xPos-140);
	 $("#keyword_search").css('top',yPos+20);
	 
}

function close_serrch(){
	$("#keyword_search").css('display','none');
}

function open_search(search_type){
	//var search_value=document.getElementById("up_value");
	var search_value=$("#up_value").html();
	
	search_value=encodeURI(search_value);
	
	//search_value=encodeURI(search_value);
	var url="./allmember_ajax.php?&n=open_search&search_type="+search_type+"&search_value="+search_value;
	if(search_type==1){
		window.open(url);
	}
	if(search_type==2){
		window.open("http://www.google.com.hk/search?q="+search_value);
	}
	if(search_type==3){
		window.open("http://cn.bing.com/search?q="+search_value);
	}
	if(search_type==4){
		window.open("http://renlifang.msra.cn/result.aspx?action=keyin&q="+search_value);
	}
	if(search_type==5){
		window.open(url);
	}
	if(search_type==6){
		window.open(url);
	}
	$("#keyword_search").css('display','none');
}


//note 检查小记表单
function checkForm(){
	var grade = $("#grade").val();
	if(!grade) { alert("请选择跟进步骤！");return false;}
	var contact = $("input[name=contact]:checked").val();
	if(!contact){alert("请确定是否有效联系! ");return false;}
	var phonecall = $("input[id=phonecall]:checked").val();
	if(!phonecall){alert("请确认是否打过电话! ");return false;}
}




//可控预测的会员
function updateControl(uid,type){
   var url = "./myuser_ajax.php?n=control";

   var isControl=$("#isControl_"+uid).val();
   var isForcast=$("#isForcast_"+uid).val();

   if (type=='control'){
	   if (isControl==1){
		   isControl=0;
		}else{
		   isControl=1;
		}
	}
	
	if (type=='forcast'){
		if (isForcast==1){
			isForcast=0;
		}else{
			isForcast=1;
		}
    }
	
    
   $.post(url,{uid:uid,isControl:isControl,isForcast:isForcast},function(data){
       if(data=='ok'){
         alert("更新成功: " + data);
       }else{
         alert("更新失败: " + data);
       }
   });   
}



//note 菜单切换
function menu(a){
	$("div[id^=item]").css("display","none");
	$("div[id^=item"+a+"]").css("display","block");
	$("li[id^=menu]").attr("class","");
	$("#menu"+a).attr("class","currentLink");
	//note 鲜花
	if(a==2) get('rose',a);
	//note 委托
	if(a==3) get('commission',a);
	//note 秋波
	if(a==4) get('leer',a);
	//note 意中人
	if(a==5) get('friend',a);
	//note 匹配搜索
	if(a==6) get('match',a);
	//note 心理测试
	if(a==7) get('test',a);
	//note 短信记录
	if(a==8) get('message',a);
	//note 信箱记录
	if(a==9) get('letter',a);
	//note 聊天记录
	if(a=='_a') get('chatrecord',a);
	//note 电话流程
	if(a=='_b') get('telphone_order',a);
	//note 评价
	if(a=='_c') get('pingjia',a);
	//note 售后
	if(a=='_d'){get_sell('sell',a);get_sellpage('sell_page',a);}
    if(a=='_10'){get('activity',a);}
	if (a == '_j') { get('join', a); }
}



function get_sellpage(n,a){
	$("#item"+a+a).html("<div style='text-align:center'><br/>页面加载中...</div>");
	var url = './allmember_ajax.php?n='+n+'&uid={$uid}&t='+a;
	$.get(url,function(data){  
		$("#item"+a+a).html(data);
	});
}



function sendActive(page,type,from,to){
	var name=new Array();
	name['commission']='委托';
	name['leer']='秋波';
	name['rose']='鲜花';
	$("#"+page+"_"+type+"_"+from).html(name[type]+"已发送");
	$("#"+page+"_"+type+"_"+to).html(name[type]+"已发送");
	var url = './allmember_ajax.php?n=sendactive&uid={$uid}&type='+type+'&from='+from+'&to='+to;
	$.get(url,function(data){
		if(data == 'ok'){
			alert(name[type]+"发送成功");
		}else{
			alert(name[type]+"发送失败");
		}
	})
}

function delActive(type,id){
	var url = './allmember_ajax.php?n=delactive&uid={$uid}&&type='+type+'&id='+id;
	$.get(url,function(data){
		if(data == 'ok'){
			$("#"+type+"_tr_"+id).css("display","none");
		}else if(data == 'err'){
			alert('删除失败');	
		}
	})
}

//修改备注电话号码
function submitcallno(callno_val,callno_val2,uid_val){
		$.get("./allmember_ajax.php?n=submitcallno",{callno:callno_val,uid:uid_val},function(str){
			if(str=='no'){
				alert('错误的手机格式');
			}else if(str=='yes'){
			
				alert('已有同手机号码注册!');
			}else{ //if(str != 'no')
				alert('修改成功');
				$("#byhm").css("display","block");
				$("#inputhm").css("display","none");
				if(callno_val=='') callno_val='暂无';
				$("#byhm").html('<a id="callno" href="javascript:showInputHm();">'+callno_val+'</a>');
				$("#byhmszd").css("display","block");
				$("#byhmszd_td").html(str);
			}
		})

}

function showInputHm(){
	$("#byhm").css("display","none");
	$("#inputhm").css("display","block");
}







//获得用户所在地的天气情况
/*
$(document).ready(function() {
	var work_province=$("#work_province").html();
	if(work_province=="北京"||work_province=='天津'||work_province=='重庆'||work_province=='上海'){
		work_city=work_province;
		//alert(work_city);
	}else{
		var work_city=$("#work_city").html();
		//alert(work_city);
	}	
	var url= "./include/get_weather.php?city="+work_city;
	$.get(url,function(str){	
		if(str!='100'){
			var arr=new Array();
			arr=str.split('||');
			//alert(arr);
			var today_weather="今："+arr[0]+";"+arr[2]+"~"+arr[3]+"℃";
			var tomorrow_weather="明："+arr[4]+";"+arr[6]+"~"+arr[7]+"℃";
			var after_weather="后："+arr[8]+";"+arr[10]+"~"+arr[11]+"℃";
			$("#workcity_weather_today").html(today_weather);
			$("#workcity_weather_tomorrow").html(tomorrow_weather);
			$("#workcity_weather_after").html(after_weather);
		}
	});
   
});
*/




//note 鼠标移动出现被选择框
$("#enneagram_list").find("td").each(function(i){
	$(this).mousemove(function(){
		$(this).css('border','1px solid #7F99BE');$(this).find('input').css('display','');
	})

	$(this).mouseout(function(){
		$(this).css('border','');$(this).find('input').css('display','none');
	})
});




function view_transfer(evt,uid){
	var evt = evt;
	evt=evt || window.event;
	if(evt.pageX){          
		xPos1=evt.pageX;
		yPos1=evt.pageY;
	} else {  
		xPos1=evt.clientX+document.body.scrollLeft-document.body.clientLeft;
		yPos1=evt.clientY+document.body.scrollTop-document.body.clientTop;    
	}
	$("#transfer_box").css("left",xPos1+25);
	$("#transfer_box").css("top",yPos1-10);
	$("#transfer_box").show(); 
	url = "myuser_ajax.php?n=transfer&uid="+uid;
	$.getJSON(url,function(data){
		var str = '';
		if(data != 0){
			str = "原客服ID："+data.sid+"<br>服务期限："+data.servicetime+"个月<br>付款金额："+data.payments+"元<br>委托会员ID："+data.otheruid+"<br>模拟聊天记录："+data.chatnotes+"<br>升级会员情况："+data.intro+"<br>委托会员情况："+data.otherintro+"<br>最后一次沟通情况："+data.lastcom+"<br>备注："+data.remark;
		}else{
			str = '没有交接内容'; //$().find().each(){
				
				//}
		}
		$("#transfer_box p").html(str);
	})
}

function subitnotice(uid,type){
	var url="allmember_ajax.php?n=chang_isphone&uid="+uid+"&action_type="+type;
	$.get(url,function(str){
		if(str){
			//alert(str);
			var isphone_content="<font color='red'>"+str+"</font>";
			$("#open_isphone").html(isphone_content);
		}else{
			$("#open_isphone").html("错误！");
		}
		});
}


//当前此采集会员是否可以使用
function user_member(uid){

	window.location.href='allmember_ajax.php?n=user_member&uid='+uid;
}

//封锁采集会员
function block_member(uid){
	window.location.href='allmember_ajax.php?n=block_member&uid='+uid;
}

//开启采集会员
function open_member(uid){

	window.location.href='allmember_ajax.php?n=open_member&uid='+uid;
}
// 过期的全权会员标注
function overdue(uid){
    if(!confirm("你要对此会员"+uid+"进行过期操作吗？")){
        return false;
    }
    if(!confirm("请再次确认你的操作，点击确认后将无法恢复了")){
        return false;
    }
	$.post('allmember_ajax.php?n=overdue',{uid:uid},function(data){
		if(data.flag==0){
			alert(data.msg);
			return false;
		}else{
			$('#action_msg').html('<div style="border:solid #F00 5px; padding:10px; margin:5px auto; width:650px;">'+data.msg+'</div>');
			alert('全权会员过期标注成功');
            return false;
		}
	},'json');
}

//读写，删除cookie
function setCookie(name,value){
	var never = new Date();
	//设置never的时间为当前时间加上十年的毫秒值
	//never.setTime(never.getTime()+10*365*24*60*60*1000);    
	never.setTime(never.getTime()+10*60*1000); //10分钟后过期
	var expString = "expires="+ never.toGMTString()+";";
	 document.cookie = name + "="+ escape (value) + ";expires=" + expString+ 'path=/;';
	
}


function getCookie(name) {
	var arr = document.cookie.split('; ');
	var i = 0;
	for(i=0; i<arr.length; i++) {
		var arr2 = arr[i].split('=');
		if(arr2[0] == name) {return arr2[1];}
	}
	return '';
}
function removeCookie(name) {
	setCookie(name,'',-1);
}