<?php

require_once './jssdk.php';

$jssdk = new JSSDK('<% your appid %>', '<% your appsecret %>');
$wxconfig = $jssdk->getSignPackage();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>wx.config</title>
</head>
<body>
	<h1>share to test</h1>
	<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script>
		wx.config({
			appId: '<?php echo $wxconfig['appId'];?>',
			timestamp: '<?php echo $wxconfig['timestamp'];?>',
			nonceStr: '<?php echo $wxconfig['nonceStr'];?>',
			signature: '<?php echo $wxconfig['signature'];?>',
			jsApiList: [
				'onMenuShareTimeline',
				'onMenuShareAppMessage',
				'onMenuShareQQ',
				'onMenuShareWeibo'
			]
		});

		wx.ready(function() {
			var shareTitle = '微信分享标题';
			var shareDesc = '微信分享描述';
			var shareLink = '微信分享链接';
			var shareImg = '微信分享封面标题';

			// 分享给朋友事件绑定
			wx.onMenuShareAppMessage({
				title: shareTitle,
				desc: shareDesc,
				link: shareLink,
				imgUrl: shareImg
			});

			// 分享到朋友圈
			wx.onMenuShareTimeline({
				title: shareTitle,
				link: shareLink,
				imgUrl: shareImg
			});

			// 分享到QQ
			wx.onMenuShareQQ({
				title: shareTitle,
				desc: shareDesc,
				link: shareLink,
				imgUrl: shareImg
			});

			// 分享到微博
			wx.onMenuShareWeibo({
				title: shareTitle,
				desc: shareDesc,
				link: shareLink,
				imgUrl: shareImg
			});
		});
	</script>
</body>
</html>