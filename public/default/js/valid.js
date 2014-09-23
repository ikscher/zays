//´ıÍêÉÆ

//È«½Ç×ª°ë½Ç
function CtoH(obj){ 
  var str=obj.value;
  var result="";
  for (var i = 0; i < str.length; i++){
    if (str.charCodeAt(i)==12288){
      result+= String.fromCharCode(str.charCodeAt(i)-12256);
      continue;
    }
    if (str.charCodeAt(i)>65280 && str.charCodeAt(i)<65375)
      result+= String.fromCharCode(str.charCodeAt(i)-65248);
    else 
      result+= String.fromCharCode(str.charCodeAt(i));
  } 
  obj.value=result;
}




