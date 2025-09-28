<?php
// Railway Health Check - Basit versiyon
http_response_code(200);
header('Content-Type: text/plain');

echo "OK\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? 'Yes' : 'No') . "\n";
?>
