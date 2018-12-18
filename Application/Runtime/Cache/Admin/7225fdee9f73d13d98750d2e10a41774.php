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
	     function checkss(){
	    	 var title    = $("#username").val();
	    	 var person   = $("#person").val();
	    	 if(title==''){
	    		 alert('名称不能为空！');
	        	 return false;
	    	 }
	    	 
	     }  
	</script>
<div class="content">
<div id="big"></div>
<div id="big2"></div>
<!-----------------------------------------内容开始--------------------------------------------------->
<div class="infoBox">
<form name="form" action="<?php echo U('doadd_gift');?>"  method="post" onsubmit="return checkss();">
<table width="90%" border="0" cellpadding="0" cellspacing="0" id="basic">
               <input type="hidden" value="<?php echo ($p["gift_id"]); ?>" name="id">
                <!-- <tr>
                    <td  width="10%" class="infoBoxTd"><div style="width:55px;">图片:</div></td>
                    <td colspan="2">
                        <div class="upimg-box"><div id="fileQueue"></div>
                            <input id="file_upload" type="file" />
                            <input name="logo" type="hidden" id="fileDoc" value="<?php echo ($p["img"]); ?>" />
                            &nbsp;&nbsp;<img  id="pic" src="<?php if($p["img"] == "" ): ?>/Public/admin/images/nopic.gif<?php else: echo ($p["img"]); endif; ?>" width="110"  height="60"/>
                        </div>
                    </td>
                </tr> -->

        <tr>
            <td  width="2%"  style="text-align:right;color:#2d52a5"><div style="width:55px;">图片:</div></td>
            <td colspan="2">
                <div class="droparea spot" id="image1" style="background-image: url('<?php echo ($p["img"]); ?>');background-size: 220px 160px;" >
                    <div class="instructions" onclick="del_image('1')">删除</div>
                    <div id="uparea1"></div>
                    <input type="hidden" name="logo" id="image_1" value="<?php echo ($p["img"]); ?>" />
                    <input type="hidden" name="logos" id="image_2" value="<?php echo ($p["img"]); ?>" />
                </div>
            </td>
        </tr>
                <tr>
                    <td  width="10%" class="infoBoxTd"><div style="width:55px;">名称:</div></td>
                    <td colspan="2"><input type="text" id="name"  name="name" value="<?php echo ($p["name"]); ?>" style="width:280px;"></td>
                </tr>
                 <tr>
                  <td  width="10%" class="infoBoxTd"><div style="width:55px;">价格:</div></td>
		  		  <td colspan="2"><input type="text" id="price"  name="price" value="<?php echo ($p["price"]); ?>" style="width:280px;">&nbsp;钻石</td>
                 </tr>
                <tr>
                    <td  width="10%" class="infoBoxTd"><div style="width:55px;">经验:</div></td>
                    <td colspan="2"><input type="text" id="experience"  name="experience" value="<?php echo ($p["experience"]); ?>" style="width:280px;"></td>
                </tr>
                <tr>
                    <td  width="10%" class="infoBoxTd"><div style="width:55px;">是否连击:</div></td>
                    <td colspan="2">
                        <input type="radio" name="is_running" value="2" <?php if( $p["is_running"] == '' ): ?>checked<?php elseif( $p["is_running"] == 2 ): ?>checked<?php else: endif; ?>>&nbsp;连续&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="is_running" value="1" <?php if( $p["is_running"] == 1 ): ?>checked<?php else: endif; ?>>&nbsp;不连续
                    </td>
                </tr>
                <tr>
                    <td  width="10%" class="infoBoxTd"><div style="width:55px;">是否特效:</div></td>
                    <td colspan="2">
                        <input type="radio" name="is_special" value="-1" <?php if( ($p["is_special"] == '') or ($p["is_special"] == -1) ): ?>checked<?php endif; ?>>&nbsp;非特效&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio" name="is_special" value="1" <?php if( $p["is_special"] == 1 ): ?>checked<?php endif; ?>>&nbsp;汽车&nbsp;
                        <input type="radio" name="is_special" value="2" <?php if( $p["is_special"] == 2 ): ?>checked<?php endif; ?>>&nbsp;轮船
                        <input type="radio" name="is_special" value="3" <?php if( $p["is_special"] == 3 ): ?>checked<?php endif; ?>>&nbsp;飞机
                        <input type="radio" name="is_special" value="4" <?php if( $p["is_special"] == 4 ): ?>checked<?php endif; ?>>&nbsp;豪华邮轮
                        <input type="radio" name="is_special" value="5" <?php if( $p["is_special"] == 5 ): ?>checked<?php endif; ?>>&nbsp;城堡
                        <input type="radio" name="is_special" value="6" <?php if( $p["is_special"] == 6 ): ?>checked<?php endif; ?>>&nbsp;丘比特
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

KindEditor.ready(function(K) {
                K.create('#image_1');
                var editor = K.editor({
                    allowFileManager : true,
                    uploadJson:"<?php echo U('Common/upload');?>"
                    //sdl:false
                });
                K('#uparea1').click(function() {
                    console.log(1);
                    editor.loadPlugin('image', function() {
                        editor.plugin.imageDialog({
                            imageUrl : K('#image_1').val(),
                            clickFn : function(url, title, width, height, border, align) {
                                console.log(url);
                                $('#image1').css('background-image','url('+url+')').css('background-size','220px 160px');
                                K('#image_1').val(url);
                                K('#image_2').val(url);
                                // K('#getImgUrl').val(url);
                                editor.hideDialog();
                            }
                        });
                    });
                });

            });


            $("#uparea1").mouseover(function(e){
                $("#big").css({top:e.pageY,right:e.pageX});//鼠标定位一个点
                var img = $('#image_1').val();
                if(img.length !== 0) {
                    $("#big").html('<img src="' + $('#image_1').val() + '" width=380 height=300>');
                    $("#big").show();        //show：显示
                }
            });
            $("#uparea1").mouseout(function(){
                $("#big").hide();
            });
        ///var ue = UE.getEditor('content');
        ///var ue = UE.getEditor('content2');
        ///var ue = UE.getEditor('content3');
        function del_image(num){
            var img = $('#image_'+num).val();
            if(img.length !=0) {
                //if (confirm('是否要删除该图片?\n删除该图片将会连服务器的图片一起删除')) {
                //    $.post('/system.php/tools/del_img', {url: img}, function (data) {
                //        if (data['status'] == 'ok') {
                //            console.log(data);
                //            $('#image' + num).css('background-image', '');
                //            $('#image_' + num).val('');
                //        }
                //    }, 'json');
                if (confirm('是否要删除该图片?')) {
                    $('#image' + num).css('background-image', '');
                    $('#image_' + num).val('');

                };
                return false;
            } else {
                return false;
            }
        }
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