var ok = '<img src="module/payment/templates/default/images/ajaxok.gif" style="width:21px;height:15px;margin-top:10px;" /> ';
//控制右边展现
var jl_val;
var arr = new Array();
var haystack = new Array('ECITICCREDIT','ICBCCREDIT','BOSHCREDIT','BOCCREDIT','CMBCCREDIT','GDBCREDIT','CIBCREDIT','CCBCREDIT','HXBCREDIT','CMBCHINACREDIT','ABCCREDIT','PINGANREDIT');
var date = new Date();
var year = date.getFullYear();
//var month = date.getMonth()+1;
function left_window(vble){
	if(jl_val != vble){
		$('.c-r-content dd').css('display','none');
		if($('#'+vble).css('display') != 'none'){
			$('.c-r-content dt p').removeClass('title-close').addClass('title-open');
			$('#'+vble).css('display','none');
			$('#'+vble+'c p').removeClass('title-open').addClass('title-close');
		}else{
			$('.c-r-content dt p').removeClass('title-open').addClass('title-close');
			$('#'+vble).css('display','');
			$('#'+vble+'c p').removeClass('title-close').addClass('title-open');
		}
	}else{
		$('.c-r-content dt p').removeClass('title-open').addClass('title-close');
		$('.c-r-content dd').css('display','none');
		vble = '';
	}
	jl_val = vble;
}

function show_credcode(){
	$('#pbcredcode').css('display','').html("<font style='font-size:14px;'>请输入证件号码</font>").removeClass('payment-clue-w').addClass('payment-clue');
	$('#pb_CredCode').addClass('public');
}
//function show_frpid(){
//	$('#frpid').css('display','').html("<font style='font-size:14px;'>请填写对应的发卡行英文代号</font>").removeClass('payment-clue-w').addClass('payment-clue');
//	$('#pd_FrpId').addClass('public');
//}
function show_buyertel(){
	$('#buyertel').css('display','').html("<font style='font-size:14px;'>请填入您现在手边的手机号码</font>").removeClass('payment-clue-w').addClass('payment-clue');
	$('#pe_BuyerTel').addClass('public');
}
function show_buyername(){
	$('#buyername').css('display','').html("<font style='font-size:14px;'>请输入持卡人姓名</font>").removeClass('payment-clue-w').addClass('payment-clue');
	$('#pf_BuyerName').addClass('public');
}
function show_actid(){
	$('#actid').css('display','').html("<font style='font-size:14px;'>请输入支付卡号</font>").removeClass('payment-clue-w').addClass('payment-clue');
	$('#pf_ActId').addClass('public');
}
function show_expireyear(){
	$('#expireyear').css('display','').html("<font style='font-size:14px;'>有效期（年）必须在"+year+"-2099年之间</font>").removeClass('payment-clue-w').addClass('payment-clue');
	$('#pa2_ExpireYear').addClass('public');
}
function show_expiremonth(){
	$('#expiremonth').css('display','').html("<font style='font-size:14px;'>有效期（月）必须在01-12之间</font>").removeClass('payment-clue-w').addClass('payment-clue');
	$('#pa3_ExpireMonth').addClass('public');
}
function show_cvv(){
	$('#cvv').css('display','').html("<font style='font-size:14px;'>信用卡背面的末尾3或4位cvv码，请看右图</font>").removeClass('payment-clue-w').addClass('payment-clue');
	$('#pa4_CVV').addClass('public');
}

function hidden_credcode(t){
	var credcode = $(t).val();
	if(credcode==''){
		$('#pbcredcode').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">请输入证件号码！</span>');
		arr[0]=0;
	}else{
		var pa_CredType = $("#pa_CredType").val();
		if(pa_CredType=='IDCARD'){
			if(checkIdcard(credcode)!='验证通过!'){
				$('#pbcredcode').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">'+checkIdcard(credcode)+'</span>');
			}else{
				$(t).removeClass('public');
				$('#pbcredcode').removeClass('payment-clue').html(ok);
				arr[0]='';
			}
		}else if(pa_CredType=='OFFICERPASS'){
			$(t).removeClass('public');
			$('#pbcredcode').removeClass('payment-clue').html(ok);
			arr[0]='';
		}else if(pa_CredType=='HM_VISITORPASS'){
			$(t).removeClass('public');
			$('#pbcredcode').removeClass('payment-clue').html(ok);
			arr[0]='';
		}else if(pa_CredType=='T_VISITORPASS'){
			$(t).removeClass('public');
			$('#pbcredcode').removeClass('payment-clue').html(ok);
			arr[0]='';
		}else{
			if(checkIdcard(credcode)!='验证通过!'){
				$('#pbcredcode').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">'+checkIdcard(credcode)+'</span>');
			}else{
				$(t).removeClass('public');
				$('#pbcredcode').removeClass('payment-clue').html(ok);
				arr[0]='';
			}
		}
	}
	
}
//function hidden_frpid(t){
//	var frpid = $(t).val();
//	frpid = trim(frpid);
//	if(frpid==''){
//		$('#frpid').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">请输入发卡行！</span>');
//		arr[1]=1;
//	}else{
//		if(in_array(frpid,haystack)){
//			$(t).removeClass('public');
//			$('#frpid').removeClass('payment-clue').html(ok);
//			arr[1]='';
//		}else{
//			$('#frpid').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">请输入对应的发卡行英文代号！</span>');
//			arr[1]=1;
//		}
//	}
//}
//arr[1]='';
arr[2]='';
function hidden_buyertel(t){
	var buyertel = $(t).val();
	var telreg = /^(((13[0-9]{1})|14[0-9]{1}|15[0-9]{1}|18[0-9]{1}|)+\d{8})$/;
	if(buyertel==''){
		$('#buyertel').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">请填入您现在手边的手机号码！</span>');
		arr[2]=2;
	}else if(!telreg.test(buyertel)){
		$('#buyertel').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">您输入的手机号码不正确！</span>');
		arr[2]=2;
	}else{
		$(t).removeClass('public');
		$('#buyertel').removeClass('payment-clue').html(ok);
		arr[2]='';
	}
}
function hidden_buyername(t){
	var buyername = $(t).val();
	if(buyername==''){
		$('#buyername').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">请输入持卡人姓名！</span>');
		arr[3]=3;
	}else{
		$(t).removeClass('public');
		$('#buyername').removeClass('payment-clue').html(ok);
		arr[3]='';
	}
}
function hidden_actid(t){
	var actid = $(t).val();
	if(actid==''){
		$('#actid').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">请输入支付卡号！</span>');
		arr[4]=4;
	}else{
		$(t).removeClass('public');
		$('#actid').removeClass('payment-clue').html(ok);
		arr[4]='';
	}
}
function hidden_expireyear(t){
	var expireyear = $(t).val();
	if(expireyear==''){
		$('#expireyear').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">请输入信用卡有效期年！</span>');
		arr[5]=5;
	}else if(expireyear>=year&&expireyear<=2099){
		$(t).removeClass('public');
		$('#expireyear').removeClass('payment-clue').html(ok);
		arr[5]='';
	}else{
		$('#expireyear').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">信用卡有效期年不正确！</span>');
		arr[5]=5;
	}
}
function hidden_expiremonth(t){
	var expiremonth = $(t).val();
	if(expiremonth==''){
		$('#expiremonth').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">请输入信用卡有效期月！</span>');
		arr[6]=6;
	}else if(expiremonth>=01&&expiremonth<=12){
		$(t).removeClass('public');
		$('#expiremonth').removeClass('payment-clue').html(ok);
		arr[6]='';
	}else{
		$('#expiremonth').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">信用卡有效期月不正确！</span>');
		arr[6]=6;
	}
}
function hidden_cvv(t){
	var cvv = $(t).val();
	var len = cvv.length;
	if(cvv==''){
		$('#cvv').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">请输入信用卡背面的3或4位cvv码！</span>');
		arr[7]=7;
	}else if(len==3||len==4){
		$(t).removeClass('public');
		$('#cvv').removeClass('payment-clue').html(ok);	
		arr[7]='';
	}else{
		$('#cvv').addClass('payment-clue-w').html('<span style="color:red;font-size:14px;">您输入的cvv码不正确！</span>');
		arr[7]=7;
	}
}



function checkIdcard(idcard){
	var Errors=new Array(
	"验证通过!",
	"身份证号码位数不对!",
	"身份证号码出生日期超出范围或含有非法字符!",
	"身份证号码校验错误!",
	"身份证地区非法!"
	);
	var area={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外"}
	var idcard,Y,JYM;
	var S,M;
	var idcard_array = new Array();
	idcard_array = idcard.split("");
	//地区检验
	if(area[parseInt(idcard.substr(0,2))]==null) return Errors[4];
	//身份号码位数及格式检验
	switch(idcard.length){
	case 15:
	if ( (parseInt(idcard.substr(6,2))+1900) % 4 == 0 || ((parseInt(idcard.substr(6,2))+1900) % 100 == 0 && (parseInt(idcard.substr(6,2))+1900) % 4 == 0 )){
	ereg=/^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}$/;//测试出生日期的合法性
	} else {
	ereg=/^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}$/;//测试出生日期的合法性
	}
	if(ereg.test(idcard)) return Errors[0];
	else return Errors[2];
	break;
	case 18:
	//18位身份号码检测
	//出生日期的合法性检查
	//闰年月日:((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))
	//平年月日:((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))
	if ( parseInt(idcard.substr(6,4)) % 4 == 0 || (parseInt(idcard.substr(6,4)) % 100 == 0 && parseInt(idcard.substr(6,4))%4 == 0 )){
	ereg=/^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}[0-9Xx]$/;//闰年出生日期的合法性正则表达式
	} else {
	ereg=/^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}[0-9Xx]$/;//平年出生日期的合法性正则表达式
	}
	if(ereg.test(idcard)){//测试出生日期的合法性
	//计算校验位
	S = (parseInt(idcard_array[0]) + parseInt(idcard_array[10])) * 7
	+ (parseInt(idcard_array[1]) + parseInt(idcard_array[11])) * 9
	+ (parseInt(idcard_array[2]) + parseInt(idcard_array[12])) * 10
	+ (parseInt(idcard_array[3]) + parseInt(idcard_array[13])) * 5
	+ (parseInt(idcard_array[4]) + parseInt(idcard_array[14])) * 8
	+ (parseInt(idcard_array[5]) + parseInt(idcard_array[15])) * 4
	+ (parseInt(idcard_array[6]) + parseInt(idcard_array[16])) * 2
	+ parseInt(idcard_array[7]) * 1
	+ parseInt(idcard_array[8]) * 6
	+ parseInt(idcard_array[9]) * 3 ;
	Y = S % 11;
	M = "F";
	JYM = "10X98765432";
	M = JYM.substr(Y,1);//判断校验位
	if(M == idcard_array[17]) return Errors[0]; //检测ID的校验位
	else return Errors[3];
	}
	else return Errors[2];
	break;
	default:
	return Errors[1];
	break;
	}
}

function in_array(needle,haystack){
    // 得到needle的类型
	for(var i in haystack){
		if(haystack[i] == needle){
		   return true;
		}
	}
   return false;
}

//去左右空格;
function trim(mystr){
	while ((mystr.indexOf(" ")==0) && (mystr.length>1)){
		mystr=mystr.substring(1,mystr.length);
	}//去除前面空格
	while ((mystr.lastIndexOf(" ")==mystr.length-1)&&(mystr.length>1)){
		mystr=mystr.substring(0,mystr.length-1);
	}//去除后面空格
	if (mystr==" "){
		mystr="";
	}
	return mystr;
}

