<?php 

include_once('../../config.php');
include_once("../Data/Server/GrabIP.php");

$dongu = $pdo->query("SELECT * FROM logs_visitor");

foreach ($dongu as $yazdir) {
    
    $ip_adresi = $yazdir['ip'];
    
    if($yazdir['log_notify'] == 1) {
        echo "log";
        $pdo->query("UPDATE logs_visitor SET log_notify = 0 WHERE ip = '{$ip_adresi}'");
    } else if($yazdir['sms_notify'] == 1)  {
        echo "sms";
        $pdo->query("UPDATE logs_visitor SET sms_notify = 0 WHERE ip = '{$ip_adresi}'");
    }

}




?>