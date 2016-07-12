# wxjssdk-signature
微信 JSSDK 服务端生成签名认证，这个库的目的是提供一个后台生成微信 JSSDK 后台签名的 JSON 接口，方便前端调用，减少前后端的耦合。

## 使用须知安全性问题 !important

因为代码中使用的是文件缓存可能导致 `access_token` 泄露，所以请保证 `/cache` 文件夹不可被 HTTP 直接访问，所以需要对 `/cache` 文件夹做权限的限制。

### Nginx
在 nginx 中禁止用户访问 `/cache` 文件夹，`path` 修改为你的路径
```
location ^~ /path/cache {
  deny all;
}
```

### Apache
在 `/cache` 文件夹中添加一个 `.htaccess` 文件（源代码中已包含）
```
deny from all
```

## 部署

复制 `signature.php` 和 `jssdk.class.php` 两个文件以及 `/cache` 文件夹到支持 PHP 的服务器，并修改 `signature.php` 中的第 7  行，填入微信公众平台的 appid 以及 appsecret。

此时你可以得到一个接口 `http://example.com/signature.php?url=<%your url%`> 来获得针对某一个 URL 的签名，返回的格式一般为：
```js
{
  appId: "wxd752a69aa6b87228",
  timestamp: 1423535235,
  nonceStr: "DfxhMRuA7wXbSW9G",
  signature: "2e1773c60a7abbb7c55cb1a1173e9f943e63218a"
}
```
## 使用

在网页中加载以下脚本：

```html
<script src="//res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
```

在 JS 中调用接口：

```js
var verifyUrl = window.location.href.replace(window.location.hash, '');
var sdkScript = document.createElement('script');
sdkScript.src = 'http://example.com/signature.php?url=' + encodeURIComponent(verifyUrl) + '&callback=jssdkCallback';
document.getElementsByTagName('head')[0].appendChild(sdkScript);

window.jssdkCallback = function(res) {
  wx.config({
    debug: false,
    appId: res.data.appId,
    timestamp: res.data.timestamp,
    nonceStr: res.data.nonceStr,
    signature: res.data.signature,
    jsApiList: [
      'onMenuShareTimeline',
      'onMenuShareAppMessage',
      'onMenuShareQQ',
      'onMenuShareWeibo',
      'onMenuShareQZone'
    ]
  });
}
```
微信 JSSDK 会在 wx.config 方法执行完之后执行 wx.ready 方法，所以使用微信 JSSDK 的方法应写到 wx.ready 方法内。

附带一个自定义微信分享的代码片段
```javascript
// wx.config 成功后执行
wx.ready(function() {
  var shareData = {
    title: '分享标题文本',
    desc: '分享描述文本',
    link: window.location.href,
    imgUrl: 'http://example.com/cover.jpg',
    success: function() { },
    cancel: function() { }
  };

  // 分享给朋友事件绑定
  wx.onMenuShareAppMessage(shareData);

  // 分享到朋友圈
  wx.onMenuShareTimeline(shareData);

  // 分享到QQ
  wx.onMenuShareQQ(shareData);

  // 分享到QZone
  wx.onMenuShareQZone(shareData);

  // 分享到微博
  wx.onMenuShareWeibo(shareData);
});
```
