<?php
/**
 * weixin jsapi signature
 * @author LOO2K
 * @date 2015-01-12
 */

class wxjsSignature {

	public $appid = '';
	public $appsecret = '';
	private $_cacheDir = './cache/';
	private $_cacheExpired = 7200;

	public function config($url) {
		return $this->_getSignature($url);
	}

	private function _setCache($key, $content) {
		// check if cache folder exists
		if (!file_exists($this->_cacheDir)) {
			mkdir($this->_cacheDir, 0777);
		}

		// encode if content is array
		if ( is_array($content) ) {
			$content = json_encode($content);
		}

		$cache_file = $this->_cacheDir . $key;
		file_put_contents($cache_file, $content, LOCK_EX);
	}

	private function _getCache($key) {
		$cache_file = $this->_cacheDir . $key;
		if ( file_exists($cache_file) && filemtime($cache_file) > time() - $this->_cacheExpired ) {
			$cache_content = file_get_contents($cache_file);

			// decode if content is json
			json_decode($cache_content, true);
			if ( json_last_error() == JSON_ERROR_NONE ) {
				$cache_content = json_decode($cache_content, true);
			}
		}
		return $cache_content ? $cache_content : false;
	}

	private function _destroyCache($key) {
		$cache_file = $this->_cacheDir . $key;
		if ( file_exists($cache_file) ) {
			unlink($cache_file);
		}
	}

	private function _getAccessToken() {
		$access_token = $this->_getCache('access_token');
		if ( !$access_token ) {
			$access_token_api = sprintf('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s', $this->appid, $this->appsecret);
			$access_token_content = file_get_contents($access_token_api);
			$access_token = json_decode($access_token_content, true);
			$this->_setCache('access_token', $access_token_content);
		}
		return $access_token;
	}

	private function _getJsapiTicket() {
		$access_token = $this->_getAccessToken();
		$jsapi_ticket = $this->_getCache('jsapi_ticket');
		if ( !$jsapi_ticket ) {
			$jsapi_ticket_api = sprintf('https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi', $access_token['access_token']);
			$jsapi_ticket_content = file_get_contents($jsapi_ticket_api);
			$jsapi_ticket = json_decode($jsapi_ticket_content, true);
			$this->_setCache('jsapi_ticket', $jsapi_ticket_content);
		}
		return $jsapi_ticket;
	}

	private function _getNonceStr($length = 16) {
		$chr_list = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$chr_repeat_min = 1;
		$chr_repeat_max = 10;
		return substr(str_shuffle(str_repeat($chr_list, mt_rand($chr_repeat_min, $chr_repeat_max))), 1, $length);
	}

	private function _getSignature($url, $noncestr = '') {
		// check if custom noncestr
		if ( !$noncestr ) {
			$noncestr = $this->_getNonceStr();
		}
		$jsapi_ticket = $this->_getJsapiTicket();
		$timestamp = time();
		$signature_key = sprintf('jsapi_ticket=%s&noncestr=%s&timestamp=%s&url=%s', $jsapi_ticket['ticket'], $noncestr, $timestamp, $url);
		$signature = sha1($signature_key);

		return array(
			'appId'     => $this->appid,
			'timestamp' => $timestamp,
			'nonceStr'  => $noncestr,
			'signature' => $signature
		);
	}

}
