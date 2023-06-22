<?php

// Fungsi untuk melakukan permintaan HTTP GET
function sendGetRequest($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

// URL untuk mendapatkan informasi akun
$url = 'https://v1.apigames.id/merchant/M220512WYZE9646NK';

// Signature untuk permintaan
$signature = 'd1b32ba8439e8ae4dc73abf49190e5be';

// Membuat URL lengkap dengan signature
$urlWithParams = $url . '?signature=' . urlencode($signature);

// Mengirim permintaan GET dan mendapatkan responsenya
$response = sendGetRequest($urlWithParams);

// Menampilkan responsenya
echo "Response: $response" . PHP_EOL;
