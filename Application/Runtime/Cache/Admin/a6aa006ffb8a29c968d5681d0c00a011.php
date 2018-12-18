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
	 		'folder'         : '/Public/admin/Uploads',
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
         window.onload=function(){
             var radio = document.getElementsByName("type");
             for (i=0; i<radio.length; i++) {
                 if (radio[i].checked) {
                     key = radio[i].value;
                 }
             }
             if(key==1){
                 document.getElementById("yincang1").className="yincang1";
                 document.getElementById("yincang2").className="yincang2";
                 document.getElementById("yincang3").className="";
             }else if(key==2){
                 document.getElementById("yincang1").className="";
                 document.getElementById("yincang2").className="yincang2";
                 document.getElementById("yincang3").className="yincang3";
             }else if(key==3){
                 document.getElementById("yincang1").className="yincang1";
                 document.getElementById("yincang2").className="";
                 document.getElementById("yincang3").className="yincang3";
             }
         }
         function sellei(key) {
             if(key==1){
                 document.getElementById("yincang1").className="yincang1";
                 document.getElementById("yincang2").className="yincang2";
                 document.getElementById("yincang3").className="";
             }else if(key==2){
                 document.getElementById("yincang1").className="";
                 document.getElementById("yincang2").className="yincang2";
                 document.getElementById("yincang3").className="yincang3";
             }else if(key==3){
                 document.getElementById("yincang1").className="yincang1";
                 document.getElementById("yincang2").className="";
                 document.getElementById("yincang3").className="yincang3";
             }
         }
	</script>
<style>
    .yincang1,.yincang2,.yincang3{display: none}
</style>
<div class="content">
<div id="big"></div>
    <div id="big2"></div>
<!-----------------------------------------内容开始--------------------------------------------------->
<div class="infoBox">
<form name="form" action="<?php echo U('doupbanner');?>"  method="post" onsubmit="return checkss();">
<table width="90%" border="0" cellpadding="0" cellspacing="0" id="basic">
    <input type="hidden" value="<?php echo ($hots["b_id"]); ?>" name="id">
    <!--<tr>-->
        <!--<td  width="15%" class="infoBoxTd"><div style="width:55px;">类型:</div></td>-->
        <!--<td colspan="2">-->
            <!--<input type="radio" name="type" value="1" onchange="sellei(1);" <?php if( $hots["b_type"] == 1 ): ?>checked<?php endif; ?>>&nbsp;外链&nbsp;&nbsp;-->
            <!--<input type="radio" name="type" value="2" onchange="sellei(2);" <?php if( $hots["b_type"] == 2 ): ?>checked<?php endif; ?>>&nbsp;直播&nbsp;&nbsp;-->
            <!--<input type="radio" name="type" value="3" onchange="sellei(3);" <?php if( $hots["b_type"] == 3 ): ?>checked<?php endif; ?>>&nbsp;用户&nbsp;&nbsp;-->
        <!--</td>-->
    <!--</tr>-->
    <!--<tr id="yincang1" class="yincang1">-->
        <!--<td  width="15%" class="infoBoxTd"><div style="width:57px;">直播间ID:</div></td>-->
        <!--<td colspan="2">-->
            <!--<input type="text" id="live_id" name="live_id" size="40" value="<?php echo ($hots["live_id"]); ?>">-->
        <!--</td>-->
    <!--</tr>-->
    <!--<tr id="yincang2" class="yincang2">-->
        <!--<td  width="15%" class="infoBoxTd"><div style="width:57px;">用户ID:</div></td>-->
        <!--<td colspan="2">-->
            <!--<input type="text" id="uid" name="uid" size="40" value="<?php echo ($hots["id"]); ?>">-->
        <!--</td>-->
    <!--</tr>-->
    <!--<tr id="yincang3" class="yincang3">-->
        <!--<td  width="15%" class="infoBoxTd"><div style="width:57px;">URL:</div></td>-->
        <!--<td colspan="2">-->
            <!--<input type="text" id="url" name="url" size="40" value="<?php echo ($hots["url"]); ?>">-->
        <!--</td>-->
    <!--</tr>-->
    <tr>
        <td  width="2%"  style="text-align:right;color:#2d52a5"><div style="width:55px;">banner:</div></td>
        <td colspan="2">
            <div class="droparea spot" id="image1" style="background-image: url('<?php echo ($hots["b_img"]); ?>');background-size: 220px 160px;" >
                <div class="instructions" onclick="del_image('1')">删除</div>
                <div id="uparea1"></div>
                <input type="hidden" name="logo" id="image_1" value="<?php echo ($hots["b_img"]); ?>" />
                <input type="hidden" name="logos" id="image_2" value="<?php echo ($hots["b_img"]); ?>" />
            </div>
        </td>
    </tr>

    <!-- <tr>
        <td  width="2%"  style="text-align:right;color:#2d52a5"><div style="width:55px;">banner:</div></td>
        <td colspan="2">
            <div class="upimg-box"><div id="fileQueue2"></div>
                <input id="file_upload2" type="file" />
                <input name="logo" type="hidden" id="fileDoc" value="<?php echo ($hots["b_img"]); ?>" />
                &nbsp;&nbsp;<img  id="pic2" src="<?php if($hots["b_img"] == "" ): ?>/Public/admin/images/nopic.gif<?php else: echo ($hots["b_img"]); endif; ?>" width="110"  height="60"/>
            </div>
        </td> -->
    </tr>
    <tr>
        <td  width="15%" class="infoBoxTd"><div style="width:57px;">标题:</div></td>
        <td colspan="2">
            <input type="text" id="title" name="title" size="40" value="<?php echo ($hots["title"]); ?>">
        </td>
    </tr>
    <tr>
        <td  width="15%" class="infoBoxTd"><div style="width:57px;">排序:</div></td>
        <td colspan="2">
            <input type="text" id="remark" name="remark" size="40" value="<?php echo ($hots["remark"]); ?>">
        </td>
    </tr>
    <tr>
        <td  width="15%" class="infoBoxTd"><div style="width:57px;">内容:</div></td>
        <td colspan="2">
            <textarea name="content" id="content" style="width: 90%;height: 450px"><?php echo ($hots["content"]); ?></textarea>
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