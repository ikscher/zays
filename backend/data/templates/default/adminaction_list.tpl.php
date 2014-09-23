<link href="templates/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/css/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="templates/js/onmousemove_minutes.js"></script>
<script>
function remove(actionid){
    if(confirm('您确定要删除选中的项吗')){
    }else return false;
    var url="./ajax.php";
    $.post(url,{n:"del_action",actionid:actionid},function(str){
        if(str=='ok'){
            alert("删除成功");
            $("#action_"+actionid).empty();
            $("#action_"+actionid).remove();
        }
    });
}
</script>
<h1>
<span class="action-span"><a href="index.php?action=system_adminaction&h=add" onclick="parent.addTab('添加操作','index.php?action=system_adminaction&h=add','icon');return false;">添加操作</a></span>
<span class="action-span1"><a href="###">真爱一生网 管理中心</a> </span><span id="search_id" class="action-span1"> - 后台操作列表 </span>
<span class="action-span"><a href="<?php $u=explode('&',$_SERVER['QUERY_STRING']);?>index.php?<?php echo $u[0];?>&<?php echo $u[1];?>">刷新</a></span>
<div style="clear:both"></div>
</h1>
<div class="list-div" id="listDiv">
<table cellspacing='1' cellpadding='3' id='list-table'>
  <tr>
    <th>所属导航栏</th>
    <th>所属导航栏标识符</th>
    <th>操作名称</th>
    <th>标识符（由action+'_'+h组成）</th>
    <th>操作</th>
  </tr>
  <?php foreach((array)$action_list as $action) {?>
  <tr id="action_<?php echo $action['id'];?>">
    <td class="first-cell" ><a href="index.php?action=system_adminaction&h=list&navcode=<?php echo $action['navcode'];?>"><?php echo $action['navname'];?></a></td>
    <td align="center"><?php echo $action['navcode'];?></td>
    <td align="center"><?php echo $action['actiondesc'];?></td>
    <td align="center"><?php echo $action['actioncode'];?></td>
    <td align="center">
      <a href="###" onclick="remove(<?php echo $action['id'];?>)" title="移除">删除</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="index.php?action=system_adminaction&h=edit&actionid=<?php echo $action['id'];?>">修改</a></td>
  </tr>
  <?php } ?>
  </table>
<table cellpadding="4" cellspacing="0">
    <tr>
      <td align="center"> <?php echo $page_links;?></td>
    </tr>
  </table>
</div>