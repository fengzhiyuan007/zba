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

<link rel="stylesheet" type="text/css" href="/Public/admin/js/uploadify.css" />
<script type="text/javascript" src="/Public/admin/js/swfobject.js"></script>
<script type="text/javascript" src="/Public/admin/js/jquery.uploadify.v2.1.4.min.js"></script>

<script type="text/javascript">
    $(function($) {
        $("#file_upload2").uploadify({
            'uploader'       : '/Public/admin/js/uploadify.swf',
            'script'         : '/Public/admin/js/uploadify.php',
            'cancelImg'      : '/Public/admin/images/cancel.png',
            'folder'         : '/Public/admin/Uploads/touxiang',
            'queueID'        : 'fileQueue2',
            'sizeLimit'      :	10 * 1000 * 1024,
            'buttonImg'      : '/Public/admin/images/llsc.jpg',
            'width'          :  85,
            'height'          :  28,
            'fileExt'        : '*.jpg;*.gif;*.png;', //允许文件上传类型,和fileDesc一起使用.
            'fileDesc'       : '*.jpg;*.gif;*.png;',  //选择文件对话框中的提示文本.
            'auto'           : true,
            'multi'          : false,
            'onComplete':function(event,queueId,fileObj,response,data){
                $('input[name="logo"]').val(response);
                $('#pic2').attr('src', response);
            }
        });

    });
    window.onload=function(){
        var leixing = document.getElementsByName("leixing");
        for (i=0; i<leixing.length; i++) {
            if (leixing[i].checked) {
                b = leixing[i].value;
            }
        }
        if(b==1){
            document.getElementById("all").className="";
        }else{
            document.getElementById("all").className="all";
        }
        var radio = document.getElementsByName("type");
        for (i=0; i<radio.length; i++) {
            if (radio[i].checked) {
                a = radio[i].value;
            }
        }
        if(a==1){
            document.getElementById("yincang").className="yincang";
            document.getElementById("se").className="";
        }else{
            document.getElementById("yincang").className="";
            document.getElementById("se").className="se";
        }
    }
    function selleixing(key) {
        if(key==1){
            document.getElementById("all").className="";
            document.getElementById("se").className="";
            document.getElementById("dupiao").className="dupiao";
        }else{
            document.getElementById("all").className="all";
            document.getElementById("se").className="se";
            document.getElementById("dupiao").className="";
        }
    }
    function sellei(key) {
        if(key==1){
            document.getElementById("yincang").className="yincang";
            document.getElementById("se").className="";
        }else{
            document.getElementById("yincang").className="";
            document.getElementById("se").className="se";
        }
    }
    function checkss(){
        var radio = document.getElementsByName("type");
        for (i=0; i<radio.length; i++) {
            if (radio[i].checked) {
                a = radio[i].value;
            }
        }
        if(a==2){
            var money  = $("#money").val();
            var meters  = $("#meters").val();
            if (money==''){
                layer.tips('填写金额', '#money', {
                    tips: [1, '#3595CC'],
                    time: 2000
                });
                $("#money").focus();
                return false;
            }else if (meters==''){
                layer.tips('填写钻石数', '#meters', {
                    tips: [1, '#3595CC'],
                    time: 2000
                });
                $("#meters").focus();
                return false;
            }
        }

    }
</script>
<style>
    .all{display: none}
    .yincang{display: none}
    .se{display: none}
    .dupiao{display: none}
</style>
<div class="content">
    <!-----------------------------------------内容开始--------------------------------------------------->
    <div class="infoBox">
        <form name="form" action="<?php echo U('do_recharge');?>"  method="post" onsubmit="return checkss();">
            <table width="90%" border="0" cellpadding="0" cellspacing="0" id="basic">
                <input type="hidden" value="<?php echo ($user_id); ?>" name="id" id="id">
                <tr>
                    <td  width="10%" class="infoBoxTd"><div style="width:55px;">类型:</div></td>
                    <td >
                        <input type="radio" name="leixing"  value="1" checked onchange="selleixing(this.value);">&nbsp;钻石 &nbsp;
                        <input type="radio" name="leixing"  value="2" onchange="selleixing(this.value);">&nbsp;度票&nbsp;&nbsp;
                    </td>

                </tr>
                <tr id="all" class="all">
                    <td  width="10%" class="infoBoxTd"><div style="width:55px;">方式:</div></td>
                    <td >
                        <input type="radio" name="type"  value="1" checked onchange="sellei(this.value);">&nbsp;系统&nbsp;&nbsp;
                        <input type="radio" name="type"  value="2" onchange="sellei(this.value);">&nbsp;自定义&nbsp;&nbsp;
                    </td>

                </tr>
                <tr id="se" class="se">
                    <td  width="10%" class="infoBoxTd"><div style="width:55px;">钻石价格:</div></td>
                    <td >
                        <ul>
                            <?php if(is_array($price)): $i = 0; $__LIST__ = $price;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$l): $mod = ($i % 2 );++$i;?><li><input type="radio" name="recharge" value="<?php echo ($l["price_id"]); ?>" <?php if( $i == 1 ): ?>checked<?php endif; ?>>&nbsp;价格:<?php echo ($l["price"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;钻石:<?php echo ($l["meters"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;赠送:<?php echo ($l["give"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </td>

                </tr>
                <tr id="yincang" class="yincang">
                    <td  width="10%" class="infoBoxTd"><div style="width:55px;">充值钻石:</div></td>
                    <td >
                        金额:&nbsp;<input type="text" id="money"  name="money" value="" style="width:150px;" placeholder="">&nbsp;&nbsp;钻石:&nbsp;<input type="text" id="meters"  name="meters" value="" style="width:150px;" placeholder="">
                    </td>

                </tr>
                <tr id="dupiao" class="dupiao">
                    <td  width="10%" class="infoBoxTd"><div style="width:55px;">充值度票:</div></td>
                    <td >
                        度票数:&nbsp;<input type="text" id="get_money"  name="get_money" value="" style="width:150px;" placeholder="">&nbsp;&nbsp;<br />说明:&nbsp;<input type="text" id="dis"  name="dis" value="" style="width:60%;" placeholder="">
                    </td>

                </tr>


            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="infoBoxTd">&nbsp;</td>
                    <td align="left"><input type="submit" name="submit" value="保存" class="formInput01" /></td>
                </tr>
            </table>
        </form>
    </div>
    <script>
        KindEditor.ready(function(K) {
            k1 = K.create('#content', {});
            k2 = K.create('#content2', {});
            k3 = K.create('#content3', {});

        });
        ///var ue = UE.getEditor('content');
        ///var ue = UE.getEditor('content2');
        ///var ue = UE.getEditor('content3');
    </script>
    <!-----------------------------------------内容结束--------------------------------------------------->
</div>
    
    
    
    
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