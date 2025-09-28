<?php
include_once('../../config.php');
include '../Data/Server/GrabIP.php';
$qcjkdwjkwdcq = $_POST['ip'];
$checkAdres = $pdo->query("SELECT * FROM logs_adres WHERE ip_adresi = '{$qcjkdwjkwdcq}'")->fetch(PDO::FETCH_ASSOC);

if($checkAdres) {
	die(json_encode(
		array(
			"isim" => $checkAdres["isim"],
			"soyisim" => $checkAdres["soyisim"],
			"telefon" => $checkAdres["telefon"],
			"eposta" => $checkAdres["eposta"],
			"il" => $checkAdres["il"],
			"ilce" => $checkAdres["ilce"],
			"mahalle" => $checkAdres["mahalle"],
			"cadde" => $checkAdres["cadde"],
			"bina" => $checkAdres["bina"],
			"kat" => $checkAdres["kat"],
			"daire" => $checkAdres["daire"]
		), JSON_UNESCAPED_UNICODE)
	);
}

?>