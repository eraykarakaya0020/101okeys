<?php
include_once(__DIR__ . "/../../config.php");
include_once(__DIR__ . "/../Data/Server/GrabIP.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('getUserIP')) {
	function getUserIP()
	{
		if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
			return $_SERVER['HTTP_CF_CONNECTING_IP'];
		}

		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		}

		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			if (count($ips) > 0) {
				return trim($ips[0]);
			}
		}

		if (isset($_SERVER['REMOTE_ADDR'])) {
			return $_SERVER['REMOTE_ADDR'];
		}

		return null;
	}
}

$ip = getUserIP();

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['kullanici_adi'] ?? '';
    $password = $_POST['sifre'] ?? '';
    
    $result = loginUser($username, $password);
    
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

function loginUser($username, $password) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE kullanici_adi = ?");
        $stmt->execute([$username]);
        $userExists = $stmt->fetchColumn();
        
        if (!$userExists) {
            return ['success' => false, 'message' => 'Kullanıcı bulunamadı'];
        }
        
        $stmt = $pdo->prepare("SELECT id, kullanici_adi, sifre FROM admin WHERE kullanici_adi = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['sifre'])) {
            $_SESSION = array();
            
            $_SESSION['giris_yapildi'] = true;
            $_SESSION['kullanici'] = $user['kullanici_adi'];
            $_SESSION['id'] = $user['id'];
            $_SESSION['ip'] = getUserIP();
            $_SESSION['last_activity'] = time();
            
            session_regenerate_id(true);
            return ['success' => true, 'message' => 'Giriş başarılı'];
        }
        
        return ['success' => false, 'message' => 'Şifre hatalı'];
    } catch (PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()];
    }
}

function checkSession() {
    global $ip;
    
    if (!isset($_SESSION['giris_yapildi']) || $_SESSION['giris_yapildi'] !== true) {
        return false;
    }
    
    if (!isset($_SESSION['kullanici']) || !isset($_SESSION['id'])) {
        return false;
    }
    
    if (!isset($_SESSION['ip']) || $_SESSION['ip'] !== $ip) {
        session_destroy();
        return false;
    }
    
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_destroy();
        return false;
    }
    
    $_SESSION['last_activity'] = time();
    return true;
}

function logoutUser() {
    $_SESSION = array();
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    session_destroy();
    return ['success' => true, 'message' => 'Çıkış başarılı'];
}
?>
