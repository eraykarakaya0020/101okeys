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

// Ana sayfa
if ($path === '' || $path === '/') {
    include 'index.php';
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
