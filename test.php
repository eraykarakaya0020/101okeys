<?php
// Basit test sayfası
echo "PHP Çalışıyor!<br>";
echo "PHP Version: " . PHP_VERSION . "<br>";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? 'Yüklü' : 'Yüklü değil') . "<br>";
echo "Port: " . ($_ENV['PORT'] ?? 'Tanımsız') . "<br>";
echo "Tarih: " . date('Y-m-d H:i:s') . "<br>";

// Veritabanı testi
try {
    include_once('config.php');
    echo "Veritabanı: Bağlandı<br>";
} catch (Exception $e) {
    echo "Veritabanı Hatası: " . $e->getMessage() . "<br>";
}
?>
