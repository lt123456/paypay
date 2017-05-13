<?php if (!defined('THINK_PATH')) exit();?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="0">
	<meta name="format-detection" content="telephone=no">
	<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0,user-scalable=no" name="viewport" id="viewport">
	<title>充值结果</title>
	<style type="text/css">
		body {margin:0; padding:0; font-family:"Microsoft YaHei"; background:#F9F9F9;}
		.top_bar{ width:100%; height:38px; line-height:38px; background:#06C; color:#FFF; position: fixed;  left: 0px; top: 0px; overflow: visible; z-index: 999; text-align: center;}
		.top_bar span{margin: 0;padding: 0;font-size:18px;}
		.page_main{ width:100%; margin:0px auto; margin-top:48px;}
		.input_title{ width:94%; margin:0px auto; height:35px; line-height:35px;}
		.input_box{ width:94%; margin:0px auto;}
		.input_box input{ width:94%; height:36px; padding:0px 5px; line-height:36px; border:1px solid #DDD; border-radius:3px;}
		.input_box input:focus{ border:1px solid #069;}
		.index_btn{ width:100%; height:35px; background:#FA8806; color:#FFF; text-align:center; display:inline-block; line-height:35px; font-size:16px; border-radius:3px;}
		.index_btn:hover{ background:#FF3300;}
	</style></head>

<body>

<div class="top_bar">
	<span>充值结果</span>
</div>

<div class="page_main" style="background:#FFF; border-bottom:1px solid #DDD;">
	<div id="erweimaBox" class="input_box" style="text-align:center; font-size:14px; color:#666;padding:20px 0 20px 0;">
		<div id="status">
        <span style="width:100%;text-align:center;display:block;font-size:40px;color:red;margin:20px 0">
			<?php echo $message;?>
            <?php if(isset($message)): echo ($message); ?>
				<?php else: ?>
				<?php echo ($error); endif; ?>

				</span>
		</div>
		如有疑问请将订单发送给<a href="<?php echo C('CALL_KF_URL');?>" target="_blank" style="color:#2196F3;">在线客服</a>!
		<p class="jump">
			页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b><s></s>
		</p>
	</div>
</div>

<script type="text/javascript">
	(function(){
		var wait = document.getElementById('wait'),href = document.getElementById('href').href;
		var interval = setInterval(function(){
			var time = --wait.innerHTML;
			if(time <= 0) {
				location.href = href;
				clearInterval(interval);
			};
		}, 1000);
	})();
</script>

</body></html>