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

<?php echo W("Easyui");?>

<link rel="stylesheet" type="text/css" href="/Public/admin/js/uploadify.css" />
<script type="text/javascript" src="/Public/admin/js/swfobject.js"></script>
<script type="text/javascript" src="/Public/admin/js/jquery.uploadify.v2.1.4.min.js"></script>

<script type="text/javascript">
	window.onload=function(){
		var leixing = document.getElementsByName("every_day_switch");
		for (i=0; i<leixing.length; i++) {
			if (leixing[i].checked) {
				b = leixing[i].value;
			}
		}
		if(b==1){
			document.getElementById("p_of_time_start").className="";
		}else{
			document.getElementById("p_of_time_start").className="p_of_time_start";
		}
		var day_switch = document.getElementsByName("day_switch");
		for (i=0; i<day_switch.length; i++) {
			if (day_switch[i].checked) {
				a = day_switch[i].value;
			}
		}
		if(a==1){
			document.getElementById("s_time").className="";
		}else{
			document.getElementById("s_time").className="s_time";
		}

	}
 function checkss(){
	 var leixing = document.getElementsByName("every_day_switch");
	 for (i=0; i<leixing.length; i++) {
		 if (leixing[i].checked) {
			 b = leixing[i].value;
		 }
	 }
	 var day_switch = document.getElementsByName("day_switch");
	 for (i=0; i<day_switch.length; i++) {
		 if (day_switch[i].checked) {
			 a = day_switch[i].value;
		 }
	 }
	 var period_of_time_start = $("#period_of_time_start").val();
	 var period_of_time_end = $("#period_of_time_end").val();
	 var start    = $("#start").val();
	 var end    = $("#end").val();
	 if (b==1){
	 	if (period_of_time_start=='' || period_of_time_end==''){
			$(".yzperiod_of_time_end").html('请填写时间段！');
			return false;
		}
	 }
	 if (a==1){
		 if (start=='' || end==''){
			 $(".yztime").html('选择日期！');
			 return false;
		 }
	 }
 }
 function selevery_day_switch(key) {
	 if(key==1){
		 document.getElementById("p_of_time_start").className="";
	 }else{
		 document.getElementById("p_of_time_start").className="p_of_time_start";
	 }
 }
	function selday_switch(key) {
		if(key==1){
			document.getElementById("s_time").className="";
		}else{
			document.getElementById("s_time").className="s_time";
		}
	}
</script>
<style>
	.p_of_time_start{display: none}
	.s_time{display: none}
</style>
<div class="content">
	<!-----------------------------------------内容开始--------------------------------------------------->
	<div class="infoBox">
		<form name="form" action="/Admin/Base/cash_control" method="post" enctype="multipart/form-data" onsubmit="return checkss();">
			<input type="hidden" name="id" value="<?php echo ($base["id"]); ?>">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" id="basic">
				<tr bgcolor="white">
					<td width="18%" class="infoBoxTd" bgcolor="white">提现开关：</td>
					<td colspan="2">
						<input type="radio" name="withdraw_switch" value="1" <?php if( $base["withdraw_switch"] == 1 ): ?>checked<?php endif; ?>>&nbsp;开启&nbsp;&nbsp;
						<input type="radio" name="withdraw_switch" value="2" <?php if( $base["withdraw_switch"] == 2 ): ?>checked<?php endif; ?>>&nbsp;关闭
					</td>
				</tr>
				<tr bgcolor="white">
					<td width="18%" class="infoBoxTd" bgcolor="white">每天关闭时间段开关：</td>
					<td colspan="2">
						<input type="radio" name="every_day_switch" value="1" onchange="selevery_day_switch(this.value);" <?php if( $base["every_day_switch"] == 1 ): ?>checked<?php endif; ?>>&nbsp;开启&nbsp;&nbsp;
						<input type="radio" name="every_day_switch" value="2" onchange="selevery_day_switch(this.value);" <?php if( $base["every_day_switch"] == 2 ): ?>checked<?php endif; ?>>&nbsp;关闭
					</td>
				</tr>
				<tr bgcolor="white" id="p_of_time_start" class="p_of_time_start">
					<td width="18%" class="infoBoxTd" bgcolor="white">关闭时间段：</td>
					<td colspan="2">
						<input type="text"  name="period_of_time_start" id="period_of_time_start" size="20" value="<?php echo ($base["period_of_time_start"]); ?>" > - <input type="text" name="period_of_time_end" id="period_of_time_end" size="20" value="<?php echo ($base["period_of_time_end"]); ?>"> 注:24小时制,格式:例:12:10<span class="yzperiod_of_time_end" style="color:red"></span>
					</td>
				</tr>
				<tr bgcolor="white">
					<td width="18%" class="infoBoxTd" bgcolor="white">预设关闭日期开关：</td>
					<td colspan="2">
						<input type="radio" name="day_switch" value="1" onchange="selday_switch(this.value);" <?php if( $base["day_switch"] == 1 ): ?>checked<?php endif; ?>>&nbsp;开启&nbsp;&nbsp;
						<input type="radio" name="day_switch" value="2" onchange="selday_switch(this.value);" <?php if( $base["day_switch"] == 2 ): ?>checked<?php endif; ?>>&nbsp;关闭
					</td>
				</tr>
				<tr bgcolor="white" id="s_time" class="s_time">
					<td width="18%" class="infoBoxTd" bgcolor="white">关闭日期：</td>
					<td colspan="2">
						<input type="text" class="laydate-icon" name="start_time" id="start" size="22" value="<?php if( $base["start_time"] == '' ): else: echo (date("Y-m-d",$base["start_time"])); endif; ?>" readonly> - <input type="text" class="laydate-icon" name="end_time" id="end" size="22" value="<?php if( $base["end_time"] == '' ): else: echo (date("Y-m-d",$base["end_time"])); endif; ?>" readonly><span class="yztime" style="color:red"></span>
					</td>
				</tr>
				<tr bgcolor="white">
					<td width="18%" class="infoBoxTd" bgcolor="white">当日提现最低：</td>
					<td colspan="2">
						<input type="text" id="day_lowest" name="day_lowest" size="40" value="<?php echo ($base["day_lowest"]); ?>"> 元
					</td>
				</tr>
				<tr bgcolor="white">
					<td width="18%" class="infoBoxTd" bgcolor="white">当日提现最高：</td>
					<td colspan="2">
						<input type="text" id="day_highest" name="day_highest" size="40" value="<?php echo ($base["day_highest"]); ?>"> 元
					</td>
				</tr>
				<tr bgcolor="white">
					<td width="18%" class="infoBoxTd" bgcolor="white">当日提现次数：</td>
					<td colspan="2">
						<input type="text" id="day_number" name="day_number" size="40" value="<?php echo ($base["day_number"]); ?>"> 次
					</td>
				</tr>
			</table>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="infoBoxTd">&nbsp;</td>
					<td align="left"><input type="submit" name="submit" value="保存"
						class="formInput01" /></td>
				</tr>
			</table>
		</form>

		<script type="text/javascript">
				$(function() {
					$(".zhifustatus[value=<?php echo ($base["zhifustatus"]); ?>]").attr("checked","checked");
				})
				function check1(e){
					if($("#yongjin").val()>100){
						alert("佣金抽取不能超过100！");
						return false;
					}
				}
				function check2(e){
					if($("#sunshibai").val()>100){
						alert("外汇损失率不能超过100！");
						return false;
					}
				}
			</script>
	</div>
</div>
<script>
	layui.use('laydate', function(){
		var laydate = layui.laydate;
		var start = {
			elem: '#start',
			format: 'YYYY-MM-DD',
			min: laydate.now(), //设定最小日期为当前日期
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
			min: laydate.now(),
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