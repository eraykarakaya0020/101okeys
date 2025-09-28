<?php
// JSON Proxy - Farklı API'lerden şifreli JSON çekme
function getRandomAPI() {
    $apis = [
        'https://api.github.com/',
        'https://jsonplaceholder.typicode.com/',
        'https://httpbin.org/',
        'https://api.quotable.io/',
        'https://api.adviceslip.com/'
    ];
    return $apis[array_rand($apis)];
}

function encryptJSON($content) {
    // AES şifreleme
    $key = 'a101_json_key_2024_secure';
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($content, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptJSON($encrypted) {
    $key = 'a101_json_key_2024_secure';
    $data = base64_decode($encrypted);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
}

// Mock JSON data - Gerçek API'lerden çekmek yerine
$jsonData = [
    'products' => [
        ['id' => 1, 'name' => 'Ürün 1', 'price' => 99.99],
        ['id' => 2, 'name' => 'Ürün 2', 'price' => 149.99],
        ['id' => 3, 'name' => 'Ürün 3', 'price' => 199.99]
    ],
    'categories' => [
        ['id' => 1, 'name' => 'Elektronik'],
        ['id' => 2, 'name' => 'Giyim'],
        ['id' => 3, 'name' => 'Ev & Yaşam']
    ],
    'settings' => [
        'site_name' => 'A101 Ekstra',
        'currency' => 'TL',
        'language' => 'tr'
    ]
];

$requestedData = $_GET['data'] ?? 'products';

if (isset($jsonData[$requestedData])) {
    $data = $jsonData[$requestedData];
    
    // Cache kontrolü
    $cacheFile = 'json_cache_' . md5($requestedData) . '.json';
    if (!file_exists($cacheFile) || (time() - filemtime($cacheFile)) > 1800) {
        $jsonContent = json_encode($data, JSON_UNESCAPED_UNICODE);
        $encrypted = encryptJSON($jsonContent);
        file_put_contents($cacheFile, $encrypted);
    }
    
    if (file_exists($cacheFile)) {
        $encrypted = file_get_contents($cacheFile);
        $decrypted = decryptJSON($encrypted);
        
        header('Content-Type: application/json');
        header('Cache-Control: public, max-age=1800');
        echo $decrypted;
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Data not found']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Invalid data type']);
}
?>
