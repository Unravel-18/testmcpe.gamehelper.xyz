<?php

$options = [];
$options[CURLOPT_URL] = $_SERVER['argv'][1];echo $_SERVER['argv'][1];
$options[CURLOPT_RETURNTRANSFER] = true;
$options[CURLOPT_FOLLOWLOCATION] = true;
$options[CURLOPT_SSL_VERIFYHOST] = false;
$options[CURLOPT_SSL_VERIFYPEER] = false;
$options[CURLOPT_CONNECTTIMEOUT] = 3;
$ch = curl_init();
curl_setopt_array($ch, $options);
curl_exec($ch);
$chinfo = curl_getinfo($ch);
$chdata = curl_multi_getcontent($ch);

echo $chdata;
