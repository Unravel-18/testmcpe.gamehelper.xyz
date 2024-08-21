<?php

use \App\Helpers\Helper;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$globaloptions = [];
$globaloptions[CURLOPT_RETURNTRANSFER] = true;
$globaloptions[CURLINFO_HEADER_OUT] = true;

$ch = curl_init();
$options = $globaloptions;
$options[CURLOPT_URL] = "https://testmcpe.gamehelper.xyz/api/like-savanna-paradise-resort-001";
$options[CURLOPT_POST] = true;
$options[CURLOPT_POSTFIELDS] = http_build_query([]);
curl_setopt_array($ch, $options);
$response = curl_exec($ch);
$chinfo = curl_getinfo($ch);
curl_close($ch);

$json = json_decode($response, true);

if ($json) {
    echo "<pre>";
    print_r($json);
} else {
    print_r($response);
}







