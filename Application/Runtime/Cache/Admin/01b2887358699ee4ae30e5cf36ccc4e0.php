<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>BLUSECMS</title>

<meta name="GENERATOR" content="MCBN">


<link rel="stylesheet" type="text/css" href="/Public/admin/css/base.css" />
<link rel="stylesheet" type="text/css" href="/Public/admin/css/H-ui.min.css" />

<link rel="stylesheet" type="text/css" href="/Public/admin/css/style.css" />
<link rel="stylesheet" type="text/css" href="/Public/common/mypage.css" /><!-- 分页样式css -->

<script type="text/javascript" src="/Public/admin/js/jquery.js"></script>
<script type="text/javascript" src="/Public/common/js/zczy-UI.js"></script>
<script type="text/javascript" src="/Public/admin/js/common.js"></script>
<script type="text/javascript" src="/Public/common/kindeditor/kindeditor.js"></script>

	<link rel="stylesheet" type="text/css" href="/Public/layui/css/layui.css" />
	<script type="text/javascript" src="/Public/layui/layui.js"></script>

	<script type="text/javascript" src="/Public/admin/layer/layer.js"></script>

	<script src="/Public/admin/player/sewise.player.min.js"></script>


<link href="/Public/home/css/qikoo.css" type="text/css" rel="stylesheet" />
<link href="/Public/home/css/store.css" type="text/css" rel="stylesheet" />

<!--升级后台框架-->
<link href="/Public/h-ui/css/H-ui.min.css" type="text/css" rel="stylesheet" />
<link href="/Public/h-ui.admin/css/H-ui.admin.css" type="text/css" rel="stylesheet" />
<link href="/Public/Hui-iconfont/1.0.7/iconfont.css" type="text/css" rel="stylesheet" />
<link href="/Public/h-ui.admin/skin/default/skin.css" type="text/css" rel="stylesheet" />
<link href="/Public/h-ui.admin/css/style.css" type="text/css" rel="stylesheet" />

<script type="text/javascript" src="/Public/h-ui/js/H-ui.js"></script>
<script type="text/javascript" src="/Public/h-ui.admin/js/H-ui.admin.js"></script>

<!--升级后台框架-->

<script type="text/javascript" src="/Public/home/js/qikoo.js"></script>

<script type="text/javascript" charset="utf-8" src="/Public/admin/js/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/admin/js/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/Public/admin/js/ueditor/lang/zh-cn/zh-cn.js"></script>

<script type="text/javascript">
    var editor = new UE.ui.Editor();
    $(document).keydown(function(event){
          switch(event.keyCode){
             case 13:return false; 
             }
      });
</script>

</head>
<body>
	

	<TABLE border=0 cellSpacing=0 cellPadding=0 width="100%">
		<TBODY>
			<TR>
				<TD vAlign=top background="/Public/admin/images/mail_leftbg.gif"
					width="17"><IMG
					src="/Public/admin/images/left-top-right.gif" width="17"
					height="29"></TD>
				<TD vAlign="top" background="/Public/admin/images/content-bg.gif">
					<TABLE id="table2" class="left_topbg" border="0" cellSpacing="0"
						cellPadding="0" width="100%" height="31">
						<TBODY>
							<TR>
								<TD height="31">
									<DIV class="titlebt">

										<?php if($pagetitle == ''): ?>系统基本信息 <?php else: ?>
										<?php echo ($pagetitle); endif; ?>

									</DIV>
								</TD>
							</TR>
						</TBODY>
					</TABLE>
				</TD>
				<TD vAlign="top"
					background="/Public/admin/images/mail_rightbg.gif" width="16">
					<IMG src="/Public/admin/images/nav-right-bg.gif" width="16"
					height="29">
				</TD>
			</TR>
			<TR>
				<TD vAlign="center"
					background="/Public/admin/images/mail_leftbg.gif">&nbsp;</TD>
				<TD align="left" vAlign="top" bgColor="#f7f8f9">

<script type="text/javascript">
function edit(id){
	location.href="/Admin/Menus/edit2/id/"+id;
}
</script>
<form action="/Admin/Menus/delete" method="post">
<div class="tools"> 
<div class="add"><span><a href="/Admin/Menus/add2?pid=<?php echo ($pid); ?>">添加</a></span></div>
<div class="del"><span><span><a href="javascript:;"><input name="dele" type="submit" value="删除" onclick="return tips('chois[]')" class="wr"   style="border:none; background-color:#F2F7FD; color:#2D52A5;margin-top:3px;" /></a></span></div>
</div>
<div class="content">
<!-----------------------------------------内容开始--------------------------------------------------->
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tabBox">
<tr class="tabTitleMain">
    <td width="5%" align="center"><input type="checkbox" name="checkbox11" id="checkbox11"  onclick="return checkAll(this,'chois[]')"  value="0">全选</td>
    <td width="11%" align="center">菜单名称</td>
    <td width="11%" align="center">链接地址</td>
    <td width="11%" align="center">状态</td>
	 <td width="11%" align="center">排序</td>
    <td width="11%" align="center">操作</td>
</tr>


<?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($k % 2 );++$k;?><tr class="tabTextMain" id="f_<?php echo ($l["id"]); ?>" onmouseout="this.style.background='#FFFFFF';" onmouseover="this.style.background='#fbf435';">
  <td align="center"><input type="checkbox" class="deleteids" value="<?php echo ($row["id"]); ?>" name="chois[]"/></td>
  <td align="center" class="onerow"><?php echo ($row["title"]); ?></td>
  <td align="center" class="onerow"><?php echo ($row["url"]); ?></td>
  <td align="center" class="onerow">
		<?php if(($row["status"]) == "1"): ?><img src="/Public/admin/images/toolbar/p.png" /><?php endif; ?>
		<?php if(($row["status"]) == "0"): ?><img src="/Public/admin/images/toolbar/x.png" /><?php endif; ?>
  </td>
   <td align="center" class="onerow"><?php echo ($row["px"]); ?></td>
  <td align="center">
  	<a href="javascript:edit('<?php echo ($row["id"]); ?>')">修改</a>
  </td>
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table> 
<div class="page"><?php echo ($show); ?></div>
<!-----------------------------------------内容结束--------------------------------------------------->
</div>
</form>
<script type="text/javascript">
function checkAll(e,chois)
{
	var aa=document.getElementsByName(chois);
	for(var i=0;i<aa.length;i++)
	{  
		aa[i].checked=e.checked;
	}
}
function tips(itemName){
    var f=false;
    var aa=document.getElementsByName(itemName);
	for(var i=0;i<aa.length;i++){
 		if(aa[i].checked==true){
  	 		f=true;
 		}
	}
	if(f==false){
		alert("请选择要删除的选项");
		return false;
	}else{
  return  confirm("一旦删除不可修复，确定删除吗？");
}
return true;
}
</script>
    

    
    
    
    
    </TD><TD background="/Public/admin/images/mail_rightbg.gif">&nbsp;</TD>
</TR>
<TR>
    <TD vAlign="bottom" background="/Public/admin/images/mail_leftbg.gif">
    <IMG src="/Public/admin/images/buttom_left2.gif" width="17" height="17"></TD>
    <TD background="/Public/admin/images/buttom_bgs.gif">
    <IMG src="/Public/admin/images/buttom_bgs.gif" width="17" height="17"></TD>
    <TD vAlign="bottom" background="/Public/admin/images/mail_rightbg.gif">
    <IMG src="/Public/admin/images/buttom_right2.gif" width="16" height="17">
    </TD>
</TR>

</TBODY>
</TABLE>
</body>
</html>