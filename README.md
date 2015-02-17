# weixin-jsapi-signature
微信 JSSDK 服务端生成签名认证，这个库的目的是提供一个后台生成微信 JSSDK 后台签名的 JSON 接口，方便前端调用，减少前后端的耦合。

# 使用方法
复制 `signature.php` 和 `wxjsSignature.class.php` 两个文件以及 `/cache` 文件夹到支持 PHP 的服务器，并修改 `signature.php` 中的第 8 和第 9 行，填入微信公众平台的 appid 以及 appsecret。


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
