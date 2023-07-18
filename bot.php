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

$merchant_id = "YOUR_MERCHAN_ID";
$signature = 'YOUR_SIGNATURE';
$secret_key = "YOUR_SECRET_KEY";

// URL untuk mendapatkan informasi akun
$urlsaldo = 'https://v1.apigames.id/merchant/' . $merchant_id;

// Membuat URL lengkap dengan signature
$urlWithParams = $urlsaldo . '?signature=' . urlencode($signature);

// Mengirim permintaan GET dan mendapatkan responsenya
$response = sendGetRequest($urlWithParams);
$data = json_decode($response, true);
if ($data['status'] == 1) {
    $nama = $data['data']['nama'];
    $saldo = $data['data']['saldo'];
    echo "Nama: " . $nama . "\n";
    echo "Saldo: " . $saldo . "\n";
} else {
    $error_msg = $data['error_msg'];
    echo "Terjadi kesalahan: " . $error_msg . "\n";
}

// Membaca file data JSON
$datakode = file_get_contents('data.json');

// Mendekode data JSON menjadi array
$dataArray = json_decode($datakode, true);

// Memeriksa status data
if ($dataArray['status'] == 1) {
    $operators = $dataArray['data']['operator'];
    $products = $dataArray['data']['produk'];

    // Menggabungkan operator engine yang sama menjadi satu
    $uniqueOperators = [];
    foreach ($operators as $operator) {
        $engine = $operator['engine'];
        if (!isset($uniqueOperators[$engine])) {
            $uniqueOperators[$engine] = [];
        }
        $uniqueOperators[$engine][] = $operator;
    }

    // Menampilkan semua operator engine dengan angka
    echo "Operator Engine:\n";
    $selectedEngineIndex = 1;
    $operatorEngines = [];
    foreach ($uniqueOperators as $engine => $operatorGroup) {
        echo $selectedEngineIndex . ". " . $engine . "\n";
        $operatorEngines[$selectedEngineIndex] = $operatorGroup;
        $selectedEngineIndex++;
    }

    // Memilih operator engine
    $selectedEngineIndex = readline("Masukkan nomor operator engine: ");
    $selectedOperators = $operatorEngines[$selectedEngineIndex];

    // Menampilkan operator nama dari operator engine yang dipilih sebelumnya
    echo "Operator Nama:\n";
    $selectedOperatorIndex = 1;
    $operatorNames = [];
    foreach ($selectedOperators as $operator) {
        echo $selectedOperatorIndex . ". " . $operator['nama'] . "\n";
        $operatorNames[$selectedOperatorIndex] = $operator['nama'];
        $selectedOperatorIndex++;
    }

    // Memilih operator nama
    $selectedOperatorIndex = readline("Masukkan nomor operator nama: ");
    $selectedOperator = $operatorNames[$selectedOperatorIndex];

    // Menampilkan produk nama dari operator nama yang dipilih sebelumnya
    echo "Produk Nama:\n";
    $selectedProductIndex = 1;
    $productNames = [];
    foreach ($products as $product) {
        if ($product['operator_id'] == $selectedOperators[$selectedOperatorIndex - 1]['id']) {
            echo $selectedProductIndex . ". " . $product['nama'] . "\n";
            $productNames[$selectedProductIndex] = $product['nama'];
            $selectedProductIndex++;
        }
    }

    // Memilih produk nama
    $selectedProductIndex = readline("Masukkan nomor produk nama: ");
    $selectedProductName = $productNames[$selectedProductIndex];

    // Menampilkan detail produk nama yang dipilih
    echo "Detail Produk Nama:\n";
    foreach ($products as $product) {
        if ($product['nama'] == $selectedProductName) {
            echo "Nama: " . $product['nama'] . "\n";
            //echo "ID: " . $product['id'] . "\n";
            echo "Code: " . $product['code'] . "\n";
            //echo "Operator ID: " . $product['operator_id'] . "\n";
            //echo "Produk Engine ID: " . $product['produk_engine_id'] . "\n";
        }
    }
} else {
    echo "Error: " . $dataArray['message'];
}

// URL untuk melakukan transaksi
$urltrx = 'https://v1.apigames.id/v2/transaksi';
$ref_id = "REF" . date('YmdHis') . "WAYAN";
$signaturetrx = md5($merchant_id . ":" . $secret_key . ":" . $ref_id);

// Data transaksi
$datatrx = [
    'ref_id' => $ref_id,
    'merchant_id' => $merchant_id,
    'produk' => '',
    'tujuan' => '',
    'server_id' => '',
    'signature' => $signaturetrx
];


// Meminta input dari pengguna
$datatrx['produk'] = getInput("Masukkan code produk");
$datatrx['tujuan'] = getInput("Masukkan tujuan");
$datatrx['server_id'] = getInput("Masukkan server_id");

// Mengirim permintaan POST dan mendapatkan responsenya
$responsetrx = sendPostRequest($urltrx, $datatrx);

$data = json_decode($responsetrx, true);
    
    // Memeriksa status dalam respons
    if ($data['status'] == 1) {
        $merchant_id = $data['data']['merchant_id'];
        $trx_id = $data['data']['trx_id'];
        $ref_id = $data['data']['ref_id'];
        $destination = $data['data']['destination'];
        $product_code = $data['data']['product_code'];
        $product_code_master = $data['data']['product_code_master'];
        $message = $data['data']['message'];
        $status = $data['data']['status'];
        $sn = $data['data']['sn'];
        $last_balance = $data['data']['last_balance'];
        $product_name = $data['data']['product_detail']['name'];
        $product_code_detail = $data['data']['product_detail']['code'];
        $product_price = $data['data']['product_detail']['price'];
        $product_price_unit = $data['data']['product_detail']['price_unit'];
        $product_rate = $data['data']['product_detail']['rate'];
        $product_price_rp = $data['data']['product_detail']['price_rp'];
        
        echo "Merchant ID: " . $merchant_id . "\n";
        echo "Transaction ID: " . $trx_id . "\n";
        echo "Reference ID: " . $ref_id . "\n";
        echo "Destination: " . $destination . "\n";
        echo "Product Code: " . $product_code . "\n";
        echo "Master Product Code: " . $product_code_master . "\n";
        echo "Message: " . $message . "\n";
        echo "Status: " . $status . "\n";
        echo "Serial Number: " . $sn . "\n";
        echo "Last Balance: " . $last_balance . "\n";
        echo "Product Name: " . $product_name . "\n";
        echo "Product Code Detail: " . $product_code_detail . "\n";
        echo "Product Price: " . $product_price . "\n";
        echo "Product Price Unit: " . $product_price_unit . "\n";
        echo "Product Rate: " . $product_rate . "\n";
        echo "Product Price in Rp: " . $product_price_rp . "\n";
    } else {
        $error_msg = $data['error_msg'];
        echo "Terjadi kesalahan: " . $error_msg . "\n";
    }
