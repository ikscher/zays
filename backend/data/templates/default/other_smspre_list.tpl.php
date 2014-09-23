<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<h1>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 预设短信列表</span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
<span class="action-span"><a href="index.php?action=other&h=smspre_add" onclick="parent.addTab('添加预设短信','index.php?action=other&h=smspre_add','icon');return false;">添加预设短信</a></span>

<div style="clear:both"></div>
</h1>
<p/>
<div class="list-div" id="listDiv">
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>ID</th>
    <th>短信内容</th>
    <th>定义对象</th>
    <th>操作</th>
  </tr>
  <?php foreach((array)$sms as $v) {?>
  <tr>
    <td align="center"><?php echo $v['id'];?></td>
    <td align="left"><?php echo $v['content'];?></td>
    <td align="center"><?php if($v['target']) { ?>仅<?php echo $v['target'];?>号<?php } else { ?>所有<?php } ?>客服可用</td>
    <td align="center"><a href="index.php?action=other&h=smspre_edit&id=<?php echo $v['id'];?>"onclick="parent.addTab('编辑预设短信','index.php?action=other&h=smspre_edit&id=<?php echo $v['id'];?>','icon');return false;">编辑</a> <a href="index.php?action=other&h=smspre_del&id=<?php echo $v['id'];?>">删除</a></td>
  </tr>
  <?php } ?>
   <tr>
	<td colspan="4" align="center"><?php echo $pages;?></td>
	</tr>
  </table>
</div>