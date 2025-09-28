<?php
// JavaScript Proxy - Farklı CDN'lerden şifreli JS çekme
function getRandomCDN() {
    $cdns = [
        'https://cdnjs.cloudflare.com/ajax/libs/',
        'https://unpkg.com/',
        'https://cdn.jsdelivr.net/npm/',
        'https://cdn.skypack.dev/',
        'https://esm.sh/',
        'https://unpkg.com/',
        'https://cdnjs.loli.net/ajax/libs/'
    ];
    return $cdns[array_rand($cdns)];
}

function encryptJS($content) {
    // ROT13 + Base64 şifreleme
    $rot13 = str_rot13($content);
    return base64_encode($rot13);
}

function decryptJS($encrypted) {
    $rot13 = base64_decode($encrypted);
    return str_rot13($rot13);
}

// JavaScript kütüphaneleri
$jsLibraries = [
    'jquery' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js',
    'bootstrap' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
    'lodash' => 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js',
    'moment' => 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js'
];

$requestedLib = $_GET['lib'] ?? 'jquery';
$cdn = getRandomCDN();

if (isset($jsLibraries[$requestedLib])) {
    $jsUrl = $jsLibraries[$requestedLib];
    
    // Cache kontrolü
    $cacheFile = 'js_cache_' . md5($jsUrl) . '.js';
    if (!file_exists($cacheFile) || (time() - filemtime($cacheFile)) > 3600) {
        $jsContent = @file_get_contents($jsUrl);
        if ($jsContent) {
            $encrypted = encryptJS($jsContent);
            file_put_contents($cacheFile, $encrypted);
        }
    }
    
    if (file_exists($cacheFile)) {
        $encrypted = file_get_contents($cacheFile);
        $decrypted = decryptJS($encrypted);
        
        header('Content-Type: application/javascript');
        header('Cache-Control: public, max-age=3600');
        echo $decrypted;
    } else {
        http_response_code(404);
        echo '/* JS library not found */';
    }
} else {
    http_response_code(404);
    echo '/* Invalid JS library */';
}
?>
