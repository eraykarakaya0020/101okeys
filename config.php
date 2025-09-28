<?php

date_default_timezone_set('Europe/Istanbul');
error_reporting(E_ERROR | E_PARSE);

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
    $pdo = new PDO("mysql:host=$sunucu;port=$port;dbname=$veritabaniadi", $kullaniciadi, $sifre);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8'; SET CHARSET 'utf8'");
} catch (PDOException $e) {
    die("Veritabanı'nı doğru bağladığınızdan emin olunuz. Hata: " . $e->getMessage());
}
