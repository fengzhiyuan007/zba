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
<div class="content">
<div id="big"></div>
<div id="big2"></div>
	<!-----------------------------------------内容开始--------------------------------------------------->
	<div class="infoBox">
		<form name="form" action="/Admin/Base/about_us" method="post" enctype="multipart/form-data">
			<input type="hidden" name="id" value="<?php echo ($a["about_us_id"]); ?>">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" id="basic">
				<!--<tr>
					<td  width="2%"  style="text-align:right;color:#2d52a5"><div style="width:55px;">图片:</div></td>
					<td colspan="2">
						<div class="upimg-box"><div id="fileQueue2"></div>
							<input id="file_upload2" type="file" />
							<input name="logo" type="hidden" id="fileDoc" value="<?php echo ($a["imgs"]); ?>" />
							<img  id="pic2" src="<?php if($a["imgs"] == "" ): ?>/Public/admin/images/nopic.gif<?php else: echo ($a["imgs"]); endif; ?>" width="110"  height="60"/>
						</div>
					</td>
				</tr>-->

		<tr>
            <td  width="2%"  style="text-align:right;color:#2d52a5"><div style="width:55px;">图片:</div></td>
            <td colspan="2">
                <div class="droparea spot" id="image1" style="background-image: url('<?php echo ($a["imgs"]); ?>');background-size: 220px 160px;" >
                    <div class="instructions" onclick="del_image('1')">删除</div>
                    <div id="uparea1"></div>
                    <input type="hidden" name="logo" id="image_1" value="<?php echo ($a["imgs"]); ?>" />
                    <input type="hidden" name="logos" id="image_2" value="<?php echo ($a["imgs"]); ?>" />
                </div>
            </td>
        </tr>
				<tr>
					<td  width="15%" class="infoBoxTd"><div style="width:57px;">官方客服:</div></td>
					<td colspan="2">
						<input type="text" id="mobile" name="mobile" size="40" value="<?php echo ($a["mobile"]); ?>">
					</td>
				</tr>
				<tr>
					<td  width="15%" class="infoBoxTd"><div style="width:57px;">官方邮箱:</div></td>
					<td colspan="2">
						<input type="text" id="email" name="email" size="40" value="<?php echo ($a["email"]); ?>">
					</td>
				</tr>
				<tr>
					<td  width="15%" class="infoBoxTd"><div style="width:57px;">官方QQ:</div></td>
					<td colspan="2">
						<input type="text" id="qq" name="qq" size="40" value="<?php echo ($a["qq"]); ?>">
					</td>
				</tr>
				<tr>
					<td  width="15%" class="infoBoxTd"><div style="width:67px;">微信公众号:</div></td>
					<td colspan="2">
						<input type="text" id="wechat" name="wechat" size="40" value="<?php echo ($a["wechat"]); ?>">
					</td>
				</tr>
				<tr >
					<td  width="2%"  style="text-align:right;color:#2d52a5"><div style="width:55px;">服务隐私条款:</div></td>
					<td colspan="2">
						<textarea id="content" name="content" style="width:80%;height:380px;"><?php echo ($a["clause"]); ?> </textarea>
					</td>
				</tr>
				<!--<tr >-->
					<!--<td  width="2%"  style="text-align:right;color:#2d52a5"><div style="width:55px;">用户协议:</div></td>-->
					<!--<td colspan="2">-->
						<!--<textarea id="content2" name="xieyi" style="width:80%;height:280px;"><?php echo ($a["xieyi"]); ?> </textarea>-->
					<!--</td>-->
				<!--</tr>-->
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
			KindEditor.ready(function(K) {
				k1 = K.create('#content', {});
				k2 = K.create('#content2', {});

			});
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
			</script>
	</div>
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