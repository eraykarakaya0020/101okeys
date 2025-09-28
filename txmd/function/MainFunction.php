<?php
include_once('../../config.php');
include '../Data/Server/GrabIP.php';
session_start();

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

function giris_dogrulama($pdo)
{
	global $ip;
	
	if (!isset($_SESSION['giris_yapildi']) || !isset($_SESSION['kullanici']) || !isset($_SESSION['id'])) {
		header('Location: /txmd');
		exit;
	}

	if (!isset($_SESSION['ip']) || $_SESSION['ip'] !== $ip) {
		session_destroy();
		header('Location: /txmd');
		exit;
	}

	if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
		session_destroy();
		header('Location: /txmd');
		exit;
	}

	$_SESSION['last_activity'] = time();

	$date = date('d.m.Y H:i:s');
	$stmt = $pdo->prepare('UPDATE admin SET son_gorulme = ? WHERE ip_adresi = ?');
	$stmt->execute([$date, $ip]);
}

//Tablo Sayı
$logSQL = $pdo->query("SELECT COUNT(*) FROM logs");
$log_sayisi = $logSQL->fetchColumn();

$banSQL = $pdo->query("SELECT COUNT(*) FROM bans");
$ban_sayisi = $banSQL->fetchColumn();

$sepetSQL = $pdo->query("SELECT COUNT(*) FROM sepet");
$sepet_sayisi = $sepetSQL->fetchColumn();

$adresSQL = $pdo->query("SELECT COUNT(*) FROM logs_adres");
$adres_sayisi = $adresSQL->fetchColumn();


//Günün Mesajı
if (date('G') >= 5 && date('G') <= 11) {
	$mesaj = "Günaydın";
} else if (date('G') >= 12 && date('G') <= 18) {
	$mesaj = "İyi Günler";
} else if (date('G') >= 19 || date('G') <= 4) {
	$mesaj = "İyi Geceler";
}

//Zamanı Formatla
function ZamaniFormatla($time)
{
	$time = strtotime($time);
	$timeDifference = time() - $time;
	$second = $timeDifference;
	$minute = round($timeDifference / 60);
	$hour = round($timeDifference / 3600);
	$day = round($timeDifference / 86400);
	$week = round($timeDifference / 604800);
	$month = round($timeDifference / 2419200);
	$year = round($timeDifference / 29030400);
	if ($second < 60) {
		if ($second === 0) {
			return 'Az önce';
		} else {
			return $second . ' saniye önce';
		}
	} else if ($minute < 60) {
		return $minute . ' dakika önce';
	} else if ($hour < 24) {
		return $hour . ' saat önce';
	} else if ($day < 7) {
		return $day . ' gün önce';
	} else if ($week < 4) {
		return $week . ' hafta önce';
	} else if ($month < 12) {
		return $month . ' ay önce';
	} else {
		return $year . ' yıl önce';
	}
}
