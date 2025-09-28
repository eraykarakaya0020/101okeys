<?php
// Basit index sayfası - Railway test için
echo "<h1>A101 E-commerce Platform</h1>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PDO MySQL: " . (extension_loaded('pdo_mysql') ? 'Available' : 'Not Available') . "</p>";
echo "<p>Port: " . ($_ENV['PORT'] ?? 'Not set') . "</p>";

// Health check linki
echo "<p><a href='/health.php'>Health Check</a></p>";
echo "<p><a href='/test.php'>Test Page</a></p>";

// Veritabanı testi
echo "<h2>Database Test</h2>";
try {
    include_once('config.php');
    echo "<p style='color: green;'>Database: Connected successfully!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Database Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
