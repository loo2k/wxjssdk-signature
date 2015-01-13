<?php

require_once './wxjsSignature.class.php';

$url = $_GET['url'];

$wxjsSignature = new wxjsSignature();
$wxjsSignature->appid = '';
$wxjsSignature->appsecret = '';
$wxconfig = $wxjsSignature->config($url);

echo json_encode($wxconfig);
