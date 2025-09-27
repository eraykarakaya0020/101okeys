<?php

date_default_timezone_set('Europe/Istanbul');
error_reporting(E_ERROR | E_PARSE);

################################################
#                   Veritabanı                 #
################################################

// Vercel environment variables kullan
$sunucu = $_ENV['DB_HOST'] ?? "yamanote.proxy.rlwy.net:44635";
$kullaniciadi = $_ENV['DB_USER'] ?? "root";
$sifre = $_ENV['DB_PASS'] ?? "DFOqPWVXjXtoDDwvIxPuagiTiwQIxdGQ";
$veritabaniadi = $_ENV['DB_NAME'] ?? "adminer_a101";

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
    $pdo = new PDO("mysql:host=$sunucu;dbname=$veritabaniadi", $kullaniciadi, $sifre);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'; SET CHARSET 'utf8'");
} catch (PDOException $e) {
    die("Veritabanı'nı doğru bağladığınızdan emin olunuz. Hata: " . $e->getMessage());
}
