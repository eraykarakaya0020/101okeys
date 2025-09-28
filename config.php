<?php

date_default_timezone_set('Europe/Istanbul');
error_reporting(E_ERROR | E_PARSE);

// PDO MySQL driver kontrolü
if (!extension_loaded('pdo_mysql')) {
    die('PDO MySQL driver yüklü değil. Railway\'de MySQL extension\'ı aktif edin.');
}

// PHP version kontrolü
if (version_compare(PHP_VERSION, '8.0.0', '<')) {
    die('PHP 8.0 veya üzeri gerekli. Mevcut versiyon: ' . PHP_VERSION);
}

################################################
#                   Veritabanı                 #
################################################

$sunucu = "yamanote.proxy.rlwy.net";
$port = "44635";
$kullaniciadi = "root";
$sifre = "DFOqPWVXjXtoDDwvIxPuagiTiwQIxdGQ";
$veritabaniadi = "adminer_a101";

################################################
#                  IP Filtresi                 #
################################################

$ip_filter_config = [
    "::1",
    "xxx"
];

################################################
#                Veritabanı Start              #
################################################

try {
    // Railway için optimize edilmiş DSN
    $dsn = "mysql:host=$sunucu;port=$port;dbname=$veritabaniadi;charset=utf8mb4";
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ];
    
    $pdo = new PDO($dsn, $kullaniciadi, $sifre, $options);
    
    // Bağlantı testi
    $pdo->query("SELECT 1");
    
} catch (PDOException $e) {
    // Detaylı hata mesajı
    $error_message = "Veritabanı bağlantı hatası:\n";
    $error_message .= "Host: $sunucu\n";
    $error_message .= "Port: $port\n";
    $error_message .= "Database: $veritabaniadi\n";
    $error_message .= "Hata: " . $e->getMessage() . "\n";
    $error_message .= "PDO Drivers: " . implode(', ', PDO::getAvailableDrivers());
    
    die($error_message);
}
