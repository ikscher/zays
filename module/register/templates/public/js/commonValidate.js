function checkMobile(obj){
     if(obj.value == "") return true;
	 //var reg0 = /^13\d{9}$/;   //130--139。至少7位
	 //var reg1 = /^15[0-35-9]\d{8}$/;  //150-159(154除外)。至少7位
	 //var reg2 = /^18\d{9}$/;   //180--189。至少7位
	 var reg2 = /^1[3|5|8]\d{9}$/;   //180--189。至少7位
	 var my=false;
	 //if (reg0.test(obj.value))my=true;
	 //if (reg1.test(obj.value))my=true;
	 if (reg2.test(obj.value))my=true;
	 if (!my){	
	    if(confirm("对不起，您输入的手机号码错误。")){
		    obj.focus();
		}else{
		    obj.value="";
		}
	 }
	 return my;
}

function Trim(ui){ 
	var notValid=/(^\s)|(\s$)/; 
	while(notValid.test(ui))
	{ 
		ui=ui.replace(notValid,"");
	} 
	return ui;
} 

function CheckEmail(str){
	var sReg = /[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/;
	//var emailRegexp = "^([a-zA-Z0-9_]+[\\.a-zA-Z0-9_-]*){1,}@([a-zA-Z0-9-]+\\.){1,}(com|org|net|edu|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|az|ax|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gsslands|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mkc of|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$";
	
	if ( ! sReg.test(str) )
	{
		return false;
	}

	return true;
}

function IsNum(str)
{
	var sReg = /\D+/;
	if ( sReg.test(str) )
	{
		return false;
	}
	return true;
}
 
function isNumber( s ){ 
var regu = "^[0-9]+$"; 
var re = new RegExp(regu); 
if (s.search(re) != -1) { 
return true; 
} else { 
return false; 
} 
}

/*
用途：检查输入的电话号码格式是否正确
输入：
strPhone：字符串
返回：
如果通过验证返回true,否则返回false
 
*/
function checkPhone( strPhone ) { 
//var phoneRegWithArea = /^[0][1-9]{2,3}-[0-9]{5,10}$/; 
var phoneRegNoArea = /^[1-9]{1}[0-9]{5,8}$/; 
//var prompt = "您输入的电话号码不正确!"
/*if( strPhone.length > 9 ) {
if( phoneRegWithArea.test(strPhone) ){
return true; 
}else{
alert( prompt );
return false; 
}
}else{*/
if( phoneRegNoArea.test( strPhone ) ){
return true; 
}else{
//alert( prompt );
return false; 
}
//}
}
/*
用途：检查输入的电话号码区号格式是否正确
输入：
Phonecn：字符串
返回：
如果通过验证返回true,否则返回false
 
*/
function checkPhonecn( Phonecn ) { 
var p = /^0?\d{2,3}$/;
if(p.test(Phonecn)) 
{ 
 return true;
} 
else 
{ 
return false;
}
}

/*
用途：检查输入的电话号码区号格式是否正确
输入：
Phonecn：字符串
返回：
spli('86-755-27645832-0'),结果：0755-27645832
 
*/
function spli(datastr){
 if(datastr == null || datastr == ""){
	 return;
 }
  var str= new Array();    
  var test= '';
  str=datastr.split("-");  
  test ='0'+str[1]+'-'+ str[2];
  	document.write(test);
}

//去除空格
function rltrim(str){
	return str.replace(/\s/g,"");
}

//判断email格式是否正确
function chkreg_email(regemail){
	if(!/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(regemail)) {
			return false;
	}
			return true;
}
//判断手机号是否正确
function chk_phone(phone){
	/*
	 var reg0 = /^13\d{9}$/;   //130--139。至少7位
	 var reg1 = /^15[0-35-9]\d{8}$/;  //150-159(154除外)。至少7位
	 //var reg2 = /^18[8-9]\d{8}$/;  //188-189。
	 var reg2 = /^18\d{9}$/;   //180--189。至少7位
	 var my=false;
	 if (reg0.test(phone))my=true;
	 if (reg1.test(phone))my=true;
	 if (reg2.test(phone))my=true;
	 */
	 var reg0 = /^((1[345]\d{9})|(18[0-9]\d{8}))$/;
	 var my=false;
	 if (reg0.test(phone))my=true;
	 return my;
}

//判断手机备注号码是否正确
function bek_phone(phone){
	var reg0 = /(^[0-9]{11}$)|(^((1[345]\d{9})|(18[024-9]\d{8}))$)/;
	if(reg0.test(phone))return true;
}
