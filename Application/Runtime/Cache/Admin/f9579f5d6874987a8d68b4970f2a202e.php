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
     $("#file_upload").uploadify({
         'uploader'       : '/Public/admin/js/uploadify.swf',
         'script'         : '/Public/admin/js/uploadify.php',
         'cancelImg'      : '/Public/admin/images/cancel.png',
         'folder'         : '/Public/admin/Uploads',
         'queueID'        : 'fileQueue',
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
             $('#pic').attr('src', response);
         }
     });

 });
</script>
<SCRIPT language=JavaScript>
        window.onload=function(){
            var key =  $("#state").val();
            if(key==1){
                document.getElementById("yincang").className="";
            }else{
                document.getElementById("yincang").className="yincang";
            }
        }
	     function checkss(){
	    	 var name    = $("#name").val();
             var leixing = $('input:radio:checked').val();
	    	 if(leixing==undefined){
                 alert('请选择类别！');
                 return false;
             }else if (name==''){
                 alert('名称不能为空！');
                 return false;
             }
	    	 
	     }
         function sellei(key) {
             if(key==1){
                 document.getElementById("yincang").className="";
             }else{
                 document.getElementById("yincang").className="yincang";
             }
         }
	</script>
<style>
    .yincang{display: none}
</style>
<div class="content">
<!-----------------------------------------内容开始--------------------------------------------------->
<div class="infoBox">
<form name="form" action="<?php echo U('doadd_message');?>"  method="post" onsubmit="return checkss();">
<table width="90%" border="0" cellpadding="0" cellspacing="0" id="basic">
               <input type="hidden" value="<?php echo ($u["id"]); ?>" name="id">
                <tr>
                    <td  width="10%" class="infoBoxTd"><div style="width:55px;">类别:</div></td>
                    <td colspan="2">
                        <input type="radio" name="state" id="state[]" value="1" onchange="sellei(1);" <?php if( $u["state"] == '' or $u["state"] == 1 ): ?>checked<?php endif; ?>>&nbsp;即时消息&nbsp;&nbsp;
                        <input type="radio" name="state" id="state[]" value="2" onchange="sellei(2);" <?php if( $u["state"] == 2 ): ?>checked<?php endif; ?>>&nbsp;新注册消息
                    </td>
                </tr>
                <!--<tr id="yincang" class="yincang">-->
                    <!--<td  width="10%" class="infoBoxTd"><div style="width:55px;">对象:</div></td>-->
                    <!--<td colspan="2">-->
                        <!--<input type="radio" name="type" value="1" <?php if( $u["state"] == '' or $u["type"] == 1 ): ?>checked<?php endif; ?>>&nbsp;全部&nbsp;&nbsp;-->
                        <!--<input type="radio" name="type" value="2" <?php if( $u["type"] == 2 ): ?>checked<?php endif; ?>>&nbsp;vip用户&nbsp;&nbsp;-->
                        <!--<input type="radio" name="type" value="3" <?php if( $u["type"] == 3 ): ?>checked<?php endif; ?>>&nbsp;非vip用户-->
                    <!--</td>-->
                <!--</tr>-->
                 <tr>
                  <td  width="10%" class="infoBoxTd"><div style="width:55px;">内容:</div></td>
		  		  <td colspan="2">
                      <textarea name="content" id="content" style="width: 70%;height: 100px"><?php echo ($u["content"]); ?></textarea>
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
//k1 = K.create('#content', {});
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