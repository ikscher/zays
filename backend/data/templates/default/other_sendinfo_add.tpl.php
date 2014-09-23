<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script>
function checkform(){
    var content=$.trim($("#content").val());
    var type=$("#type").val();
    if(!content){
        alert('请填写内容！');
        return false;
    }

    if(!type){
        alert('请选择类型！');
        return false;
    }

}
</script>


<h1>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 添加秋波鲜花发送信息 </span>
<span class="action-span"><a href="<?php echo $currenturl;?>">刷新</a></span>

</h1>

<div class="main-div">
<form name="theForm" method="post" action="index.php?action=other&h=add_sendinfo" onsubmit="return checkform();">
<input type="hidden" name="ispost" value="1"/>

<table width="100%"  cellspacing='1' cellpadding='3'  >
  <tr>
    <td class="label">发送内容：</td>
    <td>
      <textarea  name="content"  id="content"  style= "overflow:auto;width:500px"> </textarea> 
      
      <span class="require-field">*</span>
    </td>
  </tr>

  <tr>
    <td class="label">类型：</td>
    <td>
        <select name="type" id="type">
        <option value='' >-请选择-</option>
        <option value="1">女 发送到 男</option>
        <option value="2">男 发送到 女</option>
        </select>
    </td>
  </tr>
  
  <tr>
  	<td class="label">是否显示：</td>
  	<td>
  		<select name="isShow">
  		<option value="1" selected="selected">是</option>
  		<option value="0">否</option>
  		</select>
  	</td>
  </tr>
 

  <tr>
    <td colspan="2" align="center" style="padding-left:400px;">

      <input type="submit" value=" 确定 " class="button" />&nbsp;&nbsp;&nbsp;
      <input type="reset" value=" 重置 " class="button" />
    </td>
  </tr>
</table>
</form>
</div>