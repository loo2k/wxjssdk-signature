# wxjssdk-signature
微信 JSSDK 服务端生成签名认证，这个库的目的是提供一个后台生成微信 JSSDK 后台签名的 JSON 接口，方便前端调用，减少前后端的耦合。

## 使用须知安全性问题 !important

因为代码中使用的是文件缓存可能导致 `access_token` 泄露，所以请保证 `/cache` 文件夹不可被 HTTP 直接访问，所以需要对 `/cache` 文件夹做权限的限制。

### Nginx
在 nginx 中禁止用户访问 `/cache` 文件夹
```
location ^~ /wxjssdk-signature/cache {
  deny all;
}
```

### Apache
在 `/cache` 文件夹中添加一个 `.htaccess` 文件（源代码中已包含）
```
deny from all
```

# 使用方法
复制 `signature.php` 和 `jssdk.class.php` 两个文件以及 `/cache` 文件夹到支持 PHP 的服务器，并修改 `signature.php` 中的第 7  行，填入微信公众平台的 appid 以及 appsecret。

此时你可以通过 http 调用 `http://example.com/signature.php?url=<%your url%`> 来获得针对某一个 URL 的签名，返回的格式一般为：
```js
{
  appId: "wxd752a69aa6b87228",
  timestamp: 1423535235,
  nonceStr: "DfxhMRuA7wXbSW9G",
  signature: "2e1773c60a7abbb7c55cb1a1173e9f943e63218a"
}
```
在前端页面中可以这样调用：
```js
var currUrl = window.location.href.replace(window.location.hash, '');
$.getJSON('http://example.com/signature.php?url=' + encodeURIComponent(currUrl)).done(function(data) {
  wx.config({
    debug: true,
    appId: data.appId,
    timestamp: data.timestamp,
    nonceStr: data.nonceStr,
    signature: data.signature,
    jsApiList: [
      'checkJsApi',
      'onMenuShareTimeline',
      'onMenuShareAppMessage',
      'onMenuShareQQ',
      'onMenuShareWeibo',
      'hideMenuItems',
      'showMenuItems',
      'hideAllNonBaseMenuItem',
      'showAllNonBaseMenuItem',
      'translateVoice',
      'startRecord',
      'stopRecord',
      'onRecordEnd',
      'playVoice',
      'pauseVoice',
      'stopVoice',
      'uploadVoice',
      'downloadVoice',
      'chooseImage',
      'previewImage',
      'uploadImage',
      'downloadImage',
      'getNetworkType',
      'openLocation',
      'getLocation',
      'hideOptionMenu',
      'showOptionMenu',
      'closeWindow',
      'scanQRCode',
      'chooseWXPay',
      'openProductSpecificView',
      'addCard',
      'chooseCard',
      'openCard'
    ]
  });
});
```
微信 JSSDK 会在 wx.config 方法执行完之后执行 wx.ready 方法，所以使用微信 JSSDK 的方法应写到 wx.ready 方法内。

附带一个自定义微信分享的代码片段
```javascript
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
```
