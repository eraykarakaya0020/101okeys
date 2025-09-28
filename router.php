<?php
// Railway Router - .htaccess yerine kullanılacak
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

// Query string'i temizle
$path = strtok($path, '?');

// Trailing slash'ı kaldır
$path = rtrim($path, '/');

// Debug için
error_log("Router: Request URI: " . $request_uri);
error_log("Router: Path: " . $path);

// Static dosyaları (CSS, JS, images, fonts) doğrudan serve et
$static_extensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'];
$file_extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

if (in_array($file_extension, $static_extensions)) {
    $file_path = ltrim($path, '/');
    if (file_exists($file_path)) {
        // MIME type'ı belirle
        $mime_types = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject'
        ];
        
        $mime_type = $mime_types[$file_extension] ?? 'application/octet-stream';
        header('Content-Type: ' . $mime_type);
        
        // Cache headers
        header('Cache-Control: public, max-age=3600');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
        
        readfile($file_path);
        exit;
    }
}

// Ana sayfa
if ($path === '' || $path === '/') {
    include 'index.php';
    exit;
}

// Ürün sayfası
if ($path === '/urun') {
    include 'urun.php';
    exit;
}

// Sepet sayfası
if ($path === '/sepet') {
    include 'sepet.php';
    exit;
}

// Health check
if ($path === '/health.php' || $path === '/health') {
    include 'health.php';
    exit;
}

// Test sayfaları
if ($path === '/test.php' || $path === '/test') {
    include 'test.php';
    exit;
}

if ($path === '/index_simple.php' || $path === '/simple') {
    include 'index_simple.php';
    exit;
}

// Admin paneli
if (strpos($path, '/txmd') === 0) {
    $admin_path = substr($path, 5); // /txmd kısmını kaldır
    if ($admin_path === '' || $admin_path === '/') {
        $admin_path = '/index.php';
    }
    
    $admin_file = 'txmd' . $admin_path;
    if (file_exists($admin_file)) {
        include $admin_file;
        exit;
    }
    
    // .php uzantısı olmadan dene
    $admin_file_no_ext = 'txmd' . $admin_path . '.php';
    if (file_exists($admin_file_no_ext)) {
        include $admin_file_no_ext;
        exit;
    }
}

// Payment sayfaları
if (strpos($path, '/payment') === 0) {
    $payment_path = substr($path, 8); // /payment kısmını kaldır
    if ($payment_path === '' || $payment_path === '/') {
        $payment_path = '/index.php';
    }
    
    $payment_file = 'payment' . $payment_path;
    if (file_exists($payment_file)) {
        include $payment_file;
        exit;
    }
    
    // .php uzantısı olmadan dene
    $payment_file_no_ext = 'payment' . $payment_path . '.php';
    if (file_exists($payment_file_no_ext)) {
        include $payment_file_no_ext;
        exit;
    }
}

// Diğer PHP dosyaları - önce .php uzantısı ile
$php_file = ltrim($path, '/') . '.php';
if (file_exists($php_file)) {
    include $php_file;
    exit;
}

// .php uzantısı olmadan dene
$php_file_no_ext = ltrim($path, '/');
if (file_exists($php_file_no_ext . '.php')) {
    include $php_file_no_ext . '.php';
    exit;
}

// 404 - Dosya bulunamadı
http_response_code(404);
echo "404 - Sayfa bulunamadı: " . htmlspecialchars($path);
?>
