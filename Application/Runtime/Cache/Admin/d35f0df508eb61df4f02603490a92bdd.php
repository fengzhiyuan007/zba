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
	location.href="/Admin/Caiwu/edit/id/"+id;
}
//进入到下级
function xj(id){
	location.href="/Admin/Caiwu/index2/id/"+id;
}
function xiazai(){
    var num = $("#nus").val();
    var status = $("#status").val();
    var start = $("#start").val();
    var end = $("#end").val();
    var username = $("#username").val();
    var account_binding = $("#account_binding").val();
    var banned = $("#banned").val();
    var titles = $("#titles").val();
    var sex = $("#sex").val();
    var certification = $("#certification").val();
    var scope = $("#scope").val();
    var download = 'download';
    window.location.href="/Admin/Caiwu/income?act="+download+"&nums="+num+"&start="+start+"&end="+end+"&username="+username+"&status="+status+"&account_binding="+account_binding+"&banned="+banned+"&titles="+titles+"&sex="+sex+"&certification="+certification+"&scope="+scope;
}
function sendname() {
    var num = $("#nus").val();
    var status = $("#status").val();
    var start = $("#start").val();
    var end = $("#end").val();
    var username = $("#username").val();
    var account_binding = $("#account_binding").val();
    var banned = $("#banned").val();
    var titles = $("#titles").val();
    var sex = $("#sex").val();
    var certification = $("#certification").val();
    var scope = $("#scope").val();
    window.location.href="/Admin/Caiwu/income?nums="+num+"&start="+start+"&end="+end+"&username="+username+"&status="+status+"&account_binding="+account_binding+"&banned="+banned+"&titles="+titles+"&sex="+sex+"&certification="+certification+"&scope="+scope;
}
</script>

<form action="javascript:;" method="post">
<div class="tools">
    <span>
        账户绑定:<select name="account_binding" id="account_binding" onchange="sendname();">
          <option value="">全部</option>
          <option value="1" <?php if( $account_binding == 1 ): ?>selected<?php endif; ?>>已绑定手机</option>
          <option value="2" <?php if( $account_binding == 2 ): ?>selected<?php endif; ?>>未绑定手机</option>
        </select>&nbsp;&nbsp;
        禁播:<select name="banned" id="banned" onchange="sendname();">
          <option value="">全部</option>
          <option value="1" <?php if( $banned == 1 ): ?>selected<?php endif; ?>>已禁播</option>
          <option value="2" <?php if( $banned == 2 ): ?>selected<?php endif; ?>>未禁播</option>
        </select>&nbsp;&nbsp;
        封号:<select name="titles" id="titles" onchange="sendname();">
          <option value="">全部</option>
          <option value="1" <?php if( $titles == 1 ): ?>selected<?php endif; ?>>已封号</option>
          <option value="2" <?php if( $titles == 2 ): ?>selected<?php endif; ?>>未封号</option>
        </select>&nbsp;&nbsp;
        性别:<select name="sex" id="sex" onchange="sendname();">
          <option value="">全部</option>
          <option value="1" <?php if( $sex == 1 ): ?>selected<?php endif; ?>>男</option>
          <option value="2" <?php if( $sex == 2 ): ?>selected<?php endif; ?>>女</option>
          <option value="3" <?php if( $sex == 3 ): ?>selected<?php endif; ?>>未知</option>
        </select>&nbsp;&nbsp;
        认证:<select name="certification" id="certification" onchange="sendname();">
          <option value="">全部</option>
          <option value="2" <?php if( $certification == 2 ): ?>selected<?php endif; ?>>已认证</option>
          <option value="1" <?php if( $certification == 1 ): ?>selected<?php endif; ?>>未认证</option>
        </select>&nbsp;&nbsp;
    </span>
    <span style="float:right;border: 1px solid #d0d0d0;padding: 0px 20px;"><a href="javascript:;" onclick="xiazai()">导出excl</a></span>
    <span style="float:right;padding-left: 20px">统计收礼钻石:<?php echo ((isset($sum) && ($sum !== ""))?($sum):0); ?>钻石</span>&nbsp;&nbsp;
</div>
    <div class="tools">
        <!--<div class="add"><span><a href="/Admin/Caiwu/toadd">添加</a></span></div>-->
        <!--<div class="del"><span><span><a href="javascript:;">-->
        <!--<input name="dele" type="submit" value="删除" onclick="del();" class="wr"   style="border:none; background-color:#F2F7FD; color:#2D52A5;margin-top:3px;" /></a></span></div>-->

        <span style="float:left;padding-top:8px;">每页显示
        <select id="nus" name="nus" onchange="sendname();">
          <?php if(is_array($nums)): $i = 0; $__LIST__ = $nums;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?><option value="<?php echo ($l); ?>" <?php if( $l == $nus ): ?>selected<?php else: endif; ?>><?php echo ($l); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
        </select> 条
    </span>
        <span style="float:right;padding-right:50px;padding-top:5px;">
   &nbsp;&nbsp;日期： <input type="text" class="laydate-icon" name="start_time" id="start" size="20" value="<?php echo ($start); ?>" readonly> - <input type="text" class="laydate-icon" name="end_time" id="end" size="20" value="<?php echo ($end); ?>" readonly>&nbsp;&nbsp;
        筛选范围:<select name="scope" id="scope">
            <option value="1" <?php if( $scope == 1 ): ?>selected<?php endif; ?>>账户</option>
            <option value="2" <?php if( $scope == 2 ): ?>selected<?php endif; ?>>ID</option>
            <option value="3" <?php if( $scope == 3 ): ?>selected<?php endif; ?>>昵称</option>
        </select>&nbsp;&nbsp;
            关键词: <input type="text" name="username" id="username" value="<?php echo ($username); ?>" placeholder="" size="25">
&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="button" value="搜索" id="button" onclick="sendname()">
</span>

    </div>

<div class="content">

<!-----------------------------------------内容开始--------------------------------------------------->
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="tabBox">
<tr class="tabTitleMain">
    <!--<td width="8%" align="center">-->
    <!--<input type="checkbox" name="checkbox11" id="checkbox11"  onclick="return checkAll(this,'chois[]')"  value="0">全选</td>-->
    <td width="10%" align="center">头像</td>
    <td width="8%" align="center">会员账号</td>
    <td width="8%" align="center">昵称</td>
    <td width="6%" align="center">ID</td>
    <td width="6%" align="center">性别</td>
    <td width="6%" align="center">是否认证</td>
    <td width="6%" align="center">师傅ID</td>
    <td width="6%" align="center">收益合计</td>
    <td width="6%" align="center">等级</td>
    <td width="12%" align="center">收礼时间</td>
    <td width="12%" align="center">注册时间</td>
    <!--<td width="12%" align="center">操作</td>-->
</tr>


<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?><tr class="tabTextMain" id="f_<?php echo ($l["id"]); ?>" onmouseout="this.style.background='#FFFFFF';" onmouseover="this.style.background='#fbf435';">
  <!--<td align="center"><input type="checkbox" class="deleteids" value="<?php echo ($l["user_id"]); ?>" name="chois[]"/><?php echo ($i); ?></td>-->
    <td align="center" class="onerow"><img src="<?php echo ($l["img"]); ?>" style="width:60px;height: 60px;"></td>
    <td align="center" class="onerow"><?php if( $l["phone"] != '' ): echo ($l["phone"]); elseif( $l["openid"] != '' ): ?>微信(<?php echo ($l["openid"]); ?>)<?php elseif( $l["qq_openid"] != '' ): ?>qq(<?php echo ($l["qq_openid"]); ?>)<?php elseif( $l["weibo"] != '' ): ?>微博(<?php echo ($l["weibo"]); ?>)<?php endif; ?></td>
    <td align="center" class="onerow"><?php echo ($l["username"]); ?></td>
    <td align="center" class="onerow"><?php echo ($l["id"]); ?></td>
    <td align="center" class="onerow"><?php if( $l["sex"] == 1 ): ?>男<?php elseif( $l["sex"] == 2 ): ?>女<?php else: ?>未知<?php endif; ?></td>
    <td align="center" class="onerow"><?php if( $l["is_authen"] == 1 ): ?>否<?php else: ?>是<?php endif; ?></td>
    <td align="center" class="onerow"><?php echo ($l["master_id"]); ?></td>
    <td align="center" class="onerow"><?php echo ($l["count"]); ?></td>
    <td align="center" class="onerow"><?php echo ($l["grade"]); ?></td>
    <td align="center" class="onerow"><?php echo (date("Y-m-d H:i",$l["give_time"])); ?></td>
    <td align="center" class="onerow"><?php echo (date("Y-m-d H:i",$l["intime"])); ?></td>

  <!--<td align="center">-->
                  <!--<a href="<?php echo U('edit',array('id'=>$l['withdraw_id']));?>">编辑</a> &lt;!&ndash;| <a href="javascript:;"  onclick="del(<?php echo ($l["user_id"]); ?>);">删除</a>&ndash;&gt;-->

               <!--</td>-->
</tr><?php endforeach; endif; else: echo "" ;endif; ?>
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
            max: '2099-06-16 23:59:59', //最大日期
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
            max: '2099-06-16 23:59:59',
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