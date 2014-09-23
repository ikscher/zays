
var name_not_null = "请填写姓名！";
var profession_not_null = "请填写职业！";
var mobile_not_null = "请填写手机号码！";
var mobile_error = "手机号码填写不正确！";
var lovede_not_null = "请填写爱情宣言！";
var description_not_null = "请填写描述！";

var Utils = new Object();

Utils.isEmpty = function( val )
{
  switch (typeof(val))
  {
    case 'string':
      return Utils.trim(val).length == 0 ? true : false;
      break;
    case 'number':
      return val == 0;
      break;
    case 'object':
      return val == null;
      break;
    case 'array':
      return val.length == 0;
      break;
    default:
      return true;
  }
}

Utils.trim = function( text )
{
  if (typeof(text) == "string")
  {
    return text.replace(/^\s*|\s*$/g, "");
  }
  else
  {
    return text;
  }
}

Utils.isTel = function ( tel )
{
  //var reg = /^[\d|\-|\s|\_]+$/; //只允许使用数字-空格等
  return chk_phone(tel)
  //return reg.test( tel );
}

Utils.isNumber = function(val)
{
  var reg = /^[\d|\.|,]+$/;
  return reg.test(val);
}

var region = new Object();

function checkConsignee(frm){
	var err = false;
	var msg = new Array();
	if(Utils.isEmpty(frm.elements['name'].value)){
		err = true;
		msg.push(name_not_null);
	}
	
	if(Utils.isEmpty(frm.elements['profession'].value)){
		err = true;
		msg.push(profession_not_null);
	}
	
	if(Utils.isEmpty(frm.elements['mobile'].value)){
		err = true;
		msg.push(mobile_not_null);
	}
	
	if(frm.elements['mobile'] && frm.elements['mobile'].value.length > 0 && !Utils.isTel(frm.elements['mobile'].value)){
		err = true;
		msg.push(mobile_error);
	}
	var qq = Utils.trim(frm.elements['qq'].value);
	var telphone = Utils.trim(frm.elements['telphone'].value);
	var email = Utils.trim(frm.elements['email'].value);
	var msn = Utils.trim(frm.elements['msn'].value);
	if (telphone && ! /^\d{3,4}-?\d{7,8}$/.test(telphone)){
		err = true;
		msg.push('请填写有效的固定电话');
	}
	if(qq && !/^\d{5,12}$/.test(qq)){
		err = true;
		msg.push('请填写正确的QQ号');
	}
	if(msn && !/^([a-z0-9\+_\-\.]+)*@(hotmail\.com|live\.cn)$/.test(msn)){
		err = true;
		msg.push('请填写有效的MSN账号');
	}
	if(email && !/^([a-z0-9\+_\-\.]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/.test(email)){
		err = true;
		msg.push('请填写有效的邮箱');
	}
	if(Utils.isEmpty(frm.elements['lovede'].value) || frm.elements['lovede'].value == '（您期待的爱情是什么样的，对待爱情您有什么样的想法，大胆说出来吧！）'){
		err = true;
		msg.push(lovede_not_null);
	}
	
	if(Utils.isEmpty(frm.elements['description'].value) || frm.elements['description'].value == '（包括个人性格、爱好、特长等多个方面，越详细，越具备个人特色，将会获得优先权。）'){
		err = true;
		msg.push(description_not_null);
	}
	
	if(frm.elements['lovede'].value.length > 500){
		err = true;
		msg.push('爱情宣言字数太长');
	}
	if(frm.elements['description'].value.length > 500){
		err = true;
		msg.push('自我描述字数太长');
	}
	
	if(err){
		message = msg.join("\r\n");
		alert(message);
	}
	return ! err;
	
}





//判断手机号是否正确
function chk_phone(phone){
	 var reg0 = /^13\d{9}$/;   //130--139。至少7位
	 var reg1 = /^15[0-35-9]\d{8}$/;  //150-159(154除外)。至少7位
	 //var reg2 = /^18[8-9]\d{8}$/;  //188-189。
	 var reg2 = /^18\d{9}$/;   //180--189。至少7位
	 var my=false;
	 if (reg0.test(phone))my=true;
	 if (reg1.test(phone))my=true;
	 if (reg2.test(phone))my=true;
	 return my;
}
