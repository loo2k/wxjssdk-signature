<?php

require_once './jssdk.php';

$url = $_GET['url'];

$jssdk = new JSSDK('<% your appid %>', '<% your appsecret %>');
$wxconfig = $jssdk->getSignPackage($url);

header('Access-Control-Allow-Origin: *');
echo json_encode($wxconfig);
