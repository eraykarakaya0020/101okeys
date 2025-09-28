<?php
// CSS Proxy - Farklı CDN'lerden şifreli CSS çekme
function getRandomCDN() {
    $cdns = [
        'https://cdnjs.cloudflare.com/ajax/libs/',
        'https://unpkg.com/',
        'https://cdn.jsdelivr.net/npm/',
        'https://cdn.skypack.dev/',
        'https://esm.sh/'
    ];
    return $cdns[array_rand($cdns)];
}

function encryptCSS($content) {
    // Basit XOR şifreleme
    $key = 'a101_secure_key_2024';
    $encrypted = '';
    for ($i = 0; $i < strlen($content); $i++) {
        $encrypted .= chr(ord($content[$i]) ^ ord($key[$i % strlen($key)]));
    }
    return base64_encode($encrypted);
}

function decryptCSS($encrypted) {
    $key = 'a101_secure_key_2024';
    $content = base64_decode($encrypted);
    $decrypted = '';
    for ($i = 0; $i < strlen($content); $i++) {
        $decrypted .= chr(ord($content[$i]) ^ ord($key[$i % strlen($key)]));
    }
    return $decrypted;
}

// CSS dosyalarını farklı CDN'lerden çek ve şifrele
$cssFiles = [
    'tailwind' => 'https://cdn.tailwindcss.com/3.4.0/tailwind.min.css',
    'bootstrap' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
    'animate' => 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css'
];

$requestedFile = $_GET['f'] ?? 'tailwind';
$cdn = getRandomCDN();

if (isset($cssFiles[$requestedFile])) {
    $cssUrl = $cssFiles[$requestedFile];
    
    // Cache kontrolü
    $cacheFile = 'cache_' . md5($cssUrl) . '.css';
    if (!file_exists($cacheFile) || (time() - filemtime($cacheFile)) > 3600) {
        $cssContent = @file_get_contents($cssUrl);
        if ($cssContent) {
            $encrypted = encryptCSS($cssContent);
            file_put_contents($cacheFile, $encrypted);
        }
    }
    
    if (file_exists($cacheFile)) {
        $encrypted = file_get_contents($cacheFile);
        $decrypted = decryptCSS($encrypted);
        
        header('Content-Type: text/css');
        header('Cache-Control: public, max-age=3600');
        echo $decrypted;
    } else {
        http_response_code(404);
        echo '/* CSS not found */';
    }
} else {
    http_response_code(404);
    echo '/* Invalid CSS file */';
}
?>
