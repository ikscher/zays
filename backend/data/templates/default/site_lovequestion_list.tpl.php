<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script type="text/javascript">
function checkForm(){
	var title = $("#title").val();
	if(title == ""){
		alert("请填写测试标题");
		return false;
	}
}
</script>
<h1>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 爱情测试题目列表</span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
<span class="action-span"><a href="index.php?action=site_lovequestion&h=add" onclick="parent.addTab('添加爱情测试题','index.php?action=site_lovequestion&h=add','icon');return false;">添加测试题目</a></span>

<div style="clear:both"></div>
</h1>
<p/>
<div class="list-div" id="listDiv">
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>ID</th>
    <th>分类</th>
    <th>标题</th>
    <th>测试类型</th>
    <th>答案1</th>
    <th>答案2</th>
    <th>答案3</th>
    <th>答案4</th>
    <th>答案5</th>
    <th>操作</th>
  </tr>
  <?php foreach((array)$title_list as $v) {?>
  <tr>
    <td align="center" ><?php echo $v['lid'];?></td>
    <td align="center"><?php echo $v['qid'];?></td>
    <td><?php echo $v['title'];?></td>
    <td align="center"><?php echo $love_type[$v['ty_pe']];?></td>
    <td align="center"><?php echo $v['ask1'];?>分</td>
    <td align="center"><?php echo $v['ask2'];?>分</td>
    <td align="center"><?php echo $v['ask3'];?>分</td>
    <td align="center"><?php echo $v['ask4'];?>分</td>
    <td align="center"><?php echo $v['ask5'];?>分</td>
    <td align="center"><a href="index.php?action=site_lovequestion&h=del&tid=<?php echo $v['lid'];?>">删除</a></td>
  </tr>
  <?php } ?>
    <tr>
		<td colspan="10" align="center"><?php echo $pages;?></td>
	</tr>
  </table>

</div>