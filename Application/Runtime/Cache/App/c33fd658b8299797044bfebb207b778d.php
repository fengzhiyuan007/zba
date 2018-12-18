<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <meta charset="UTF-8">
    <title>直播A站</title>
    <link rel="stylesheet" href="/Public/share_live/css/reset.css"/>
    <link rel="stylesheet" href="/Public/share_live/css/share.css"/>
    <script src="/Public/share_live/js/pulice.js"></script>
</head>
<body>
<!-- 播放器 -->
<script src="/Public/admin/player/sewise.player.min.js"></script>
<div id="Player"></div>
<script>
    SewisePlayer.setup({
        server: 'vod',
        type: 'm3u8',
        autostart: 'true',    /*是否自动播放*/
        poster: "<?php echo ($live["play_img"]); ?>",    /*此处可填写封面地址*/
        videourl: "<?php echo ($live["play_address_m3u8"]); ?>",    /*此处填写购买获取到的m3u8地址 必填*/
        skin: 'vodOrange',
        title: '<?php echo ($live["title"]); ?>',
        claritybutton: 'disable',
        lang: 'zh_CN'
    },'Player');
</script>
<!-- 内容 -->
<div class="messages">
    <div class="message">
        <img class="anchor_img" src="<?php echo ($live["img"]); ?>" alt=""/>
        <div class="anchor_message">
            <p class="anchor_names">
                <span class="anchor_name"><?php echo ($live["username"]); ?></span>
                <span class="follow"></span>
            </p>
            <p class="anchor_remarks"><?php echo ($live["company"]); echo ($live["duty"]); ?></p>
        </div>
    </div>
    <p class="room_num">ID: <?php echo ($live["id"]); ?></p>
</div>
<div class="bottombtn">
    <div class="w2h2 information"></div>
    <div class="w2h2 gift"></div>
    <div class="w2h2 report"></div>
    <div class="download">
        <a href="<?php echo ($live["down_url"]); ?>">用直播A站APP查看</a>
    </div>
</div>
</body>
</html>