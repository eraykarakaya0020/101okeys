<?php

include_once(__DIR__ . "/../config.php");
include_once(__DIR__ . "/Data/Server/GrabIP.php");
include_once(__DIR__ . "/function/MainFunction.php");

$QueryServer = $pdo->query("SELECT * FROM admin_settings")->fetch(PDO::FETCH_ASSOC);

if ($QueryServer["ip_filter"] == 1) {
  if (!in_array($ip, $ip_filter_config)) {
    die("IP Firewall - monke");
  }
}

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.gc_maxlifetime', 1800);
    session_start();
}

if (isset($_SESSION['giris_yapildi']) && $_SESSION['giris_yapildi'] === true) {
    header('Location: main');
    exit;
}

?>
<!doctype html>
<html lang="tr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>monke</title>
  <meta name="description" content="monke Panel">
  <meta name="author" content="monke">
  <meta name="robots" content="noindex, nofollow">
  <meta property="og:title" content="monke Panel">
  <meta property="og:site_name" content="Developed monke">
  <meta property="og:description" content="monke Panel">
  <meta property="og:type" content="website">
  <meta property="og:url" content="">
  <meta property="og:image" content="">
  <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
  <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
  <link rel="stylesheet" id="css-main" href="assets/css/oneui.min.css">
  <style>
    :root {
      --primary-color: #2563eb;
      --primary-light: #3b82f6;
      --text-color: #1f2937;
      --text-light: #6b7280;
      --border-color: #e5e7eb;
    }

    body {
      background-color: #f9fafb;
      color: var(--text-color);
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .login-container {
      min-height: 100vh;
      background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
      position: relative;
    }

    .login-container::before {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      background-image: 
        radial-gradient(circle at 25% 25%, rgba(37, 99, 235, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
      pointer-events: none;
    }

    .login-box {
      background: white;
      border-radius: 24px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
      padding: 3rem;
      position: relative;
      max-width: 420px;
      margin: 0 auto;
    }

    .form-control {
      background: #f9fafb;
      border: 2px solid var(--border-color);
      color: var(--text-color);
      border-radius: 12px;
      padding: 0.875rem 1rem;
      font-size: 1rem;
      transition: all 0.2s ease;
    }

    .form-control:focus {
      background: white;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
      outline: none;
    }

    .form-control::placeholder {
      color: var(--text-light);
    }

    .btn-alt-primary {
      background: var(--primary-color);
      color: white;
      border: none;
      border-radius: 12px;
      padding: 0.875rem 1.5rem;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.2s ease;
    }

    .btn-alt-primary:hover {
      background: var(--primary-light);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .logo-container {
      margin-bottom: 2.5rem;
    }

    .logo-container img {
      width: 80px;
      height: 80px;
      padding: 1rem;
      background: #f0f9ff;
      border-radius: 16px;
      transition: all 0.3s ease;
    }

    .logo-container img:hover {
      transform: scale(1.05);
      background: #e0f2fe;
    }

    .logo-container h1 {
      font-size: 1.875rem;
      font-weight: 700;
      color: var(--text-color);
      margin: 1rem 0 0.5rem;
    }

    .logo-container p {
      color: var(--text-light);
      font-size: 1rem;
      margin: 0;
    }

    .copyright {
      color: var(--text-light);
      font-size: 0.875rem;
      margin-top: 2rem;
      text-align: center;
    }

    .copyright strong {
      color: var(--primary-color);
    }

    /* Loading animation for button */
    .btn-alt-primary:disabled {
      background: var(--primary-light);
      opacity: 0.8;
      cursor: not-allowed;
    }

    .btn-alt-primary:disabled::after {
      content: '';
      display: inline-block;
      width: 1rem;
      height: 1rem;
      border: 2px solid white;
      border-radius: 50%;
      border-top-color: transparent;
      animation: spin 1s linear infinite;
      margin-left: 0.5rem;
      vertical-align: middle;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .input-group {
      position: relative;
    }

    .input-group .form-control {
      padding-left: 2.5rem;
    }

    .input-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-light);
      pointer-events: none;
    }

    @media (max-width: 576px) {
      .login-box {
        padding: 2rem;
        margin: 1rem;
      }
    }
  </style>
</head>

<body>
  <div id="page-container" class="login-container">
    <main id="main-container">
      <div class="hero-static d-flex align-items-center">
        <div class="w-100">
          <div class="content content-full">
            <div class="row g-0 justify-content-center">
              <div class="col-md-8 col-lg-6 col-xl-4 py-4 px-4 px-lg-5">
                <div class="login-box">
                  <div class="text-center logo-container">
                    <img src="assets/media/favicons/favicon.png" alt="monke">
                    <h1>monke</h1>
                    <p>Daha iyisini yapana kadar en iyisi 😜</p>
                  </div>
                  <form class="giris-yap-dogrulama" action="./function/LoginFunction.php" method="POST">
                    <div class="form-group">
                      <div class="input-group">
                        <i class="fa fa-user input-icon"></i>
                        <input type="text" class="form-control form-control-lg" id="kullanici_adi" name="kullanici_adi" placeholder="Kullanıcı Adı">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <i class="fa fa-lock input-icon"></i>
                        <input type="password" class="form-control form-control-lg" id="sifre" name="sifre" placeholder="Şifre">
                      </div>
                    </div>
                    <div class="form-group">
                      <button type="submit" class="btn w-100 btn-alt-primary" id="giris_btn">
                        <i class="fa fa-sign-in-alt me-2"></i>Giriş Yap
                      </button>
                    </div>
                  </form>
                  <div class="copyright">
                    <strong>monke</strong> © <span data-toggle="year-copy" class="js-year-copy-enabled">2023 v2.0</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
  <script type="text/javascript">
    let loginForm = document.querySelector(".content form");
    loginForm.onsubmit = event => {
      document.getElementById('giris_btn').disabled = true
      event.preventDefault();
      fetch(loginForm.action, {
        method: 'POST',
        body: new FormData(loginForm)
      }).then(response => response.json()).then(result => {
        if (result.success === true) {
          One.helpers('jq-notify', {
            type: 'success',
            icon: 'fa fa-check me-1',
            message: 'Hoşgeldiniz! 3 saniye içerisinde aktarılıyorsunuz.'
          });
          setTimeout(function() {
            window.location.href = "main";
          }, 3000);
        } else {
          document.getElementById('giris_btn').disabled = false
          One.helpers('jq-notify', {
            type: 'danger',
            icon: 'fa fa-times me-1',
            message: result.message
          });
        }
      }).catch(error => {
        document.getElementById('giris_btn').disabled = false
        One.helpers('jq-notify', {
          type: 'danger',
          icon: 'fa fa-times me-1',
          message: 'Bir hata oluştu. Lütfen tekrar deneyin.'
        });
      });
    };

    var title = document.title;
    var alttitle = "Vurgun için bekleniyorsun ;)";
    window.onblur = function() {
      document.title = alttitle;
    };
    window.onfocus = function() {
      document.title = title;
    };
  </script>
  <script src="assets/js/oneui.app.min.js"></script>
  <script src="assets/js/lib/jquery.min.js"></script>
  <script src="assets/js/plugins/jquery-validation/jquery.validate.min.js"></script>
  <script src="assets/js/pages/giris-yap.min.js"></script>
  <script src="assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
  <script>
    One.helpersOnLoad(['jq-notify']);
  </script>
</body>

</html>