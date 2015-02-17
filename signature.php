<?php

require_once './wxjsSignature.class.php';

$url = $_GET['url'];

$wxjsSignature = new wxjsSignature();
$wxjsSignature->appid = '';
$wxjsSignature->appsecret = '';
$wxconfig = $wxjsSignature->config($url);

header('Access-Control-Allow-Origin: *');
echo json_encode($wxconfig);
