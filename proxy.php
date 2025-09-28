<?php
// Proxy script to serve external resources and avoid detection
header('Content-Type: text/plain; charset=utf-8');

// Get the URL parameter
$url = isset($_GET['url']) ? $_GET['url'] : '';

// Validate URL
if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    die('Invalid URL');
}

// Allowed domains for security
$allowed_domains = [
    'a101.com.tr',
    'cdn.a101.com.tr',
    'static.a101.com.tr',
    'fonts.googleapis.com',
    'fonts.gstatic.com',
    'cdnjs.cloudflare.com',
    'stackpath.bootstrapcdn.com',
    'code.jquery.com',
    'unpkg.com',
    'cdn.jsdelivr.net'
];

$parsed_url = parse_url($url);
$domain = $parsed_url['host'] ?? '';

if (!in_array($domain, $allowed_domains)) {
    http_response_code(403);
    die('Domain not allowed');
}

// Initialize cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

// Set headers
$headers = [];
if (isset($_SERVER['HTTP_ACCEPT'])) {
    $headers[] = 'Accept: ' . $_SERVER['HTTP_ACCEPT'];
}
if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
    $headers[] = 'Accept-Encoding: ' . $_SERVER['HTTP_ACCEPT_ENCODING'];
}
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $headers[] = 'Accept-Language: ' . $_SERVER['HTTP_ACCEPT_LANGUAGE'];
}
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    http_response_code(500);
    die('Proxy error: ' . $error);
}

if ($http_code !== 200) {
    http_response_code($http_code);
    die('HTTP Error: ' . $http_code);
}

// Set appropriate content type
if ($content_type) {
    header('Content-Type: ' . $content_type);
}

// Add cache headers
header('Cache-Control: public, max-age=3600');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');

// Return the content
echo $response;
?>
