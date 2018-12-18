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
	location.href="/Admin/User/edit/id/"+id;
}
//进入到下级
function xj(id){
	location.href="/Admin/User/index2/id/"+id;
}
function getnums(){
    var num = $("#nus").val();
    var start = $("#start").val();
    var end = $("#end").val();
    var username = $("#username").val();
    var banned = $("#banned").val();
    var is_authen = $("#is_authen").val();
    var titles = $("#titles").val();
    window.location.href="/Admin/User/index?nums="+num+"&start="+start+"&end="+end+"&username="+username+"&banned="+banned+"&titles="+titles+"&is_authen="+is_authen;
}
function sendname() {
    var num = $("#nus").val();
    var start = $("#start").val();
    var end = $("#end").val();
    var username = $("#username").val();
    window.location.href="/Admin/User/index?nums="+num+"&start="+start+"&end="+end+"&username="+username;
}
</script>

<form action="javascript:;" method="post">
<div class="tools"> 
<div class="add"><span><a href="/Admin/User/toadd">添加</a></span></div>
<div class="del"><span><span><a href="javascript:;">
<input name="dele" type="submit" value="删除" onclick="del();" class="wr"   style="border:none; background-color:#F2F7FD; color:#2D52A5;margin-top:3px;" /></a></span></div>

    <span style="float:left;padding-top:8px;">每页显示
        <select id="nus" onchange="getnums();">
          <?php if(is_array($nums)): $i = 0; $__LIST__ = $nums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?><option value="<?php echo ($l); ?>" <?php if( $l == $nus ): ?>selected<?php else: endif; ?>><?php echo ($l); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
        </select> 条
    </span>

    <span style="float:right;padding-top:5px;">
        认证:<select name="is_authen" id="is_authen" onchange="getnums()">
                <option value="">全部</option>
                <option value="1" <?php if( $is_authen == 1 ): ?>selected<?php endif; ?>>未认证</option>
                <option value="2" <?php if( $is_authen == 2 ): ?>selected<?php endif; ?>>已认证</option>
            </select>
        禁播:<select name="banned" id="banned" onchange="getnums()">
                <option value="">全部</option>
                <option value="1" <?php if( $banned == 1 ): ?>selected<?php endif; ?>>未禁播</option>
                <option value="2" <?php if( $banned == 2 ): ?>selected<?php endif; ?>>已禁播</option>
            </select>
        封号:<select name="titles" id="titles" onchange="getnums()">
                <option value="">全部</option>
                <option value="1" <?php if( $titles == 1 ): ?>selected<?php endif; ?>>未封号</option>
                <option value="2" <?php if( $titles == 2 ): ?>selected<?php endif; ?>>已封号</option>
            </select>
   &nbsp;&nbsp;注册日期:<input type="text" class="laydate-icon" name="start_time" id="start" size="12" value="<?php echo ($start); ?>" readonly> - <input type="text" class="laydate-icon" name="end_time" id="end" size="12" value="<?php echo ($end); ?>" readonly>
        关键词:<input type="text" name="username" id="username" value="<?php echo ($username); ?>" placeholder="输入账号、昵称、ID" size="25">
        <input type="button" name="button" value="搜索" id="button" onclick="getnums()">
</span>

</div>

<div class="content">

<!-----------------------------------------内容开始--------------------------------------------------->
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tabBox">
<tr class="tabTitleMain">
    <td width="8%" align="center">
    <input type="checkbox" name="checkbox11" id="checkbox11"  onclick="return checkAll(this,'chois[]')"  value="0">全选</td>
    <td width="12%" align="center">头像</td>
    <td width="10%" align="center">账号</td>
    <td width="10%" align="center">名称</td>
    <td width="10%" align="center">环信账号</td>
    <td width="10%" align="center">ID</td>
    <td width="6%" align="center">性别</td>
    <td width="8%" align="center">是否认证</td>
    <td width="8%" align="center">提现开关</td>
    <td width="20%" align="center">操作</td>
</tr>


<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?><tr class="tabTextMain" id="f_<?php echo ($l["id"]); ?>" onmouseout="this.style.background='#FFFFFF';" onmouseover="this.style.background='#fbf435';">
  <td align="center"><input type="checkbox" class="deleteids" value="<?php echo ($l["user_id"]); ?>" name="chois[]"/><?php echo ($i); ?></td>

    <td align="center" class="onerow"><img src="<?php echo ($l["img"]); ?>" style="width: 50px;height: 50px;border-radius:50%"></td>
    <td align="center" class="onerow"><?php if( $l["phone"] != '' ): echo ($l["phone"]); elseif( $l["openid"] != '' ): ?>微信<?php elseif( $l["qq_openid"] != '' ): ?>qq<?php elseif( $l["weibo"] != '' ): ?>微博<?php endif; ?></td>
    <td align="center" class="onerow"><?php echo ($l["username"]); ?></td>
    <td align="center" class="onerow"><?php echo ($l["hx_username"]); ?></td>
    <td align="center" class="onerow"><?php echo ($l["id"]); ?></td>
    <td align="center" class="onerow"><?php if( $l["sex"] == 1 ): ?>男<?php else: ?>女<?php endif; ?></td>
    <td align="center" class="onerow"><?php if( $l["is_authen"] == 1 ): ?><img src="/Public/admin/images/toolbar/x.png" /><?php else: ?><img src="/Public/admin/images/toolbar/p.png" /><?php endif; ?></td>
    <td align="center" class="onerow"><?php if( $l["withdraw_switch"] == 1 ): ?>开<?php else: ?>关<?php endif; ?></td>

  <td align="center">
                  <a href="<?php echo U('toadd',array('id'=>$l['user_id']));?>">编辑</a>
      | <a href="<?php echo U('details',array('id'=>$l['user_id']));?>">详情</a>
      | <a href="javascript:;"  onclick="del(<?php echo ($l["user_id"]); ?>);">删除</a><br/>
      <a href="<?php echo U('recharge',array('id'=>$l['user_id']));?>">充值</a>
      | <?php if( $l["is_titles"] == 1 ): ?><a href="<?php echo U('titles',array('id'=>$l['user_id']));?>" >封号</a>
          <?php else: ?><a href="javescript:;" id="fenghao<?php echo ($l["user_id"]); ?>" onclick="quxiao_titles(<?php echo ($l["user_id"]); ?>)" onmouseover="show_titles(<?php echo ($l["user_id"]); ?>,<?php echo ($l["is_titles"]); ?>)"  onmouseout="out()">取消封号</a><?php endif; ?>
      | <?php if( $l["is_banned"] == 1 ): ?><a href="<?php echo U('banned',array('id'=>$l['user_id']));?>" >禁播</a>
      <?php else: ?><a href="javescript:;" id="jinbo<?php echo ($l["user_id"]); ?>" onclick="quxiao(<?php echo ($l["user_id"]); ?>)" onmouseover="show(<?php echo ($l["user_id"]); ?>,<?php echo ($l["is_banned"]); ?>)"  onmouseout="out()">取消禁播</a><?php endif; ?>
               </td>
</tr>
    <input type="hidden" value="<?php echo ($l["banned_end_time"]); ?>" name="banned_end_time" id="banned_end_time<?php echo ($l["user_id"]); ?>">
    <input type="hidden" value="<?php echo ($l["banned_dis"]); ?>" name="banned_dis" id="banned_dis<?php echo ($l["user_id"]); ?>">
    <input type="hidden" value="<?php echo ($l["titles_end_time"]); ?>" name="titles_end_time" id="titles_end_time<?php echo ($l["user_id"]); ?>">
    <input type="hidden" value="<?php echo ($l["titles_dis"]); ?>" name="titles_dis" id="titles_dis<?php echo ($l["user_id"]); ?>"><?php endforeach; endif; else: echo "" ;endif; ?>
</table> 
</form>
<div class="pages"><?php echo ($show); ?></div>
<!-----------------------------------------内容结束--------------------------------------------------->
</div>

<script type="text/javascript">
function checkAll(e,chois)
{
	var aa=document.getElementsByName(chois);
	for(var i=0;i<aa.length;i++)
	{  
		aa[i].checked=e.checked;
	}
}
//function tips(itemName){
//    var f=false;
//    var aa=document.getElementsByName(itemName);
//	for(var i=0;i<aa.length;i++){
// 		if(aa[i].checked==true){
//  	 		f=true;
// 		}
//	}
//	if(f==false){
//		alert("请选择要删除的选项");
//		return false;
//	}else{
//  return  confirm("一旦删除不可修复，确定删除吗？");
//}
//return true;
//}
function show(uid,is_banned) {
    if(is_banned==3){
        var date = "永久封禁";
    }else {
        var date = $("#banned_end_time"+uid).val();
    }
    var banned_dis = $("#banned_dis"+uid).val();
    layer.tips('禁播到期时间:'+date+'<br/>'+'禁播原因:'+banned_dis, '#jinbo'+uid, {
        tips: [1, '#3595CC'],
        area: ['250px', ''],
        time: 60000

    });
}
function quxiao(uid) {
    if(!confirm('确定取消禁播？'))
        return false;
    $.post("<?php echo U('quxiao');?>", {ids:uid}, function(v){
        if( v == 1 ){
            alert('成功！');
            location.reload("<?php echo U('index');?>");
        }else{
            alert('失败！');
        }
    });
}
function show_titles(uid,is_titles) {
    if(is_titles==3){
        var date = "永久封号";
    }else {
        var date = $("#titles_end_time"+uid).val();
    }
    var banned_dis = $("#titles_dis"+uid).val();
    layer.tips('封号到期时间:'+date+'<br/>'+'封号原因:'+banned_dis, '#fenghao'+uid, {
        tips: [1, '#3595CC'],
        area: ['250px', ''],
        time: 60000

    });
}
function quxiao_titles(uid) {
    if(!confirm('确定取消封号？'))
        return false;
    $.post("<?php echo U('quxiao_titles');?>", {ids:uid}, function(v){
        if( v == 1 ){
            alert('成功！');
            location.reload("<?php echo U('index');?>");
        }else{
            alert('失败！');
        }
    });
}
function out() {
    layer.closeAll();
}
function del(kid){
    kid = kid ? kid : getChecked();
    kid = kid.toString();
    if(kid == ''){
        alert("请选择要删除的选项");
        return false;
    }
    if(!confirm('确定删除？'))
        return false;
    $.post("<?php echo U('del');?>", {ids:kid}, function(v){
        if( v == 1 ){
            alert('删除成功！');
            location.reload("<?php echo U('index');?>");
        }else{
            alert('删除失败！');
        }
    });
}
function getChecked() {
    var gids = new Array();
    $.each($('input:checked'), function(i, n){
        gids.push( $(n).val() );
    });
    return gids;
}
</script>
<script>
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        var start = {
            elem: '#start',
            format: 'YYYY-MM-DD',
            //min: laydate.now(), //设定最小日期为当前日期
            max: '2099-06-16', //最大日期
            istime: false,
            istoday: false,
            choose: function(datas){
                $("#start").attr("value",datas);
                end.min = datas; //开始日选好后，重置结束日的最小日期
                end.start = datas //将结束日的初始值设定为开始日
            }
        };
        var end = {
            elem: '#end',
            format: 'YYYY-MM-DD',
            //min: laydate.now(),
            max: '2099-06-16',
            istime: false,
            istoday: false,
            choose: function(datas){
                $("#end").attr("value",datas);
                start.max = datas; //结束日选好后，重置开始日的最大日期
            }
        };
        document.getElementById('start').onclick = function(){
            start.elem = this;
            laydate(start);
        }
        document.getElementById('end').onclick = function(){
            end.elem = this
            laydate(end);
        }
    });
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