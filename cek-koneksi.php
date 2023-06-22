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

// URL dasar untuk memeriksa koneksi
$baseUrl = 'https://v1.apigames.id/merchant/:merchant_id/cek-koneksi?';
$merchantId = 'xxxxxxxx'; // Ganti dengan ID merchant Anda

// Pilihan engine
$engines = [
    'higgs',
    'kiosgamer',
    'smileone',
    'unipin',
    'unipinbr',
    'unipinmy',
    'gamepoint'
];

// Memeriksa koneksi berdasarkan pilihan engine
function checkConnection($engine)
{
    global $baseUrl, $merchantId;
    
    // Memasukkan engine dan signature ke URL
    $url = $baseUrl . http_build_query([
        'engine' => $engine,
        'signature' => 'xxxxxxxxx' // Ganti dengan signature yang valid
    ]);
    
    // Mengirim permintaan GET ke URL dan mendapatkan responsenya
    $response = sendGetRequest($url);
    
    // Menampilkan engine dan responsenya
    echo "Engine: $engine" . PHP_EOL;
    echo "Response: $response" . PHP_EOL;
    echo PHP_EOL;
}

// Memilih pilihan engine
function selectEngine()
{
    global $engines;
    
    // Menampilkan pilihan engine
    echo "Pilih engine: " . PHP_EOL;
    foreach ($engines as $index => $engine) {
        echo ($index + 1) . ". Cek Koneksi $engine" . PHP_EOL;
    }
    
    // Meminta input pilihan dari pengguna
    $choice = intval(readline("Masukkan nomor pilihan: "));
    
    // Memeriksa koneksi berdasarkan pilihan
    if ($choice >= 1 && $choice <= count($engines)) {
        $selectedEngine = $engines[$choice - 1];
        checkConnection($selectedEngine);
    } else {
        echo "Pilihan tidak valid." . PHP_EOL;
    }
}

// Program utama
selectEngine();
