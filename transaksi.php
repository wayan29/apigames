<?php

// Fungsi untuk melakukan permintaan HTTP POST dengan body JSON
function sendPostRequest($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

// Fungsi untuk meminta input dari pengguna
function getInput($message)
{
    echo $message . ": ";
    $input = trim(readline());
    
    return $input;
}

// URL untuk melakukan transaksi
$url = 'https://v1.apigames.id/v2/transaksi';

// Data transaksi
$data = [
    'ref_id' => '',
    'merchant_id' => 'xxxxxxxxxxx',
    'produk' => '',
    'tujuan' => '',
    'server_id' => '',
    'signature' => 'xxxxxxxxxxxxxxxxxx'
];

// Meminta input dari pengguna
$data['ref_id'] = getInput("Masukkan ref_id");
$data['produk'] = getInput("Masukkan produk");
$data['tujuan'] = getInput("Masukkan tujuan");
$data['server_id'] = getInput("Masukkan server_id");

// Mengirim permintaan POST dan mendapatkan responsenya
$response = sendPostRequest($url, $data);

// Menampilkan responsenya
echo "Response: $response" . PHP_EOL;
