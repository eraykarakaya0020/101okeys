<?php

include_once("config.php");
include_once("./monke/Data/Server/GrabIP.php");
include_once("./monke/Data/Server/BlockVPN.php");
include_once("./monke/Data/Server/BanControl.php");
$stmt = $pdo->query("SELECT COUNT(*) AS urun_sayisi FROM urunler");
$sonuc = $stmt->fetch(PDO::FETCH_ASSOC);
$urun_sayisi = $sonuc['urun_sayisi'];

$pdo->query("UPDATE logs SET durum = 'Ürün Ekranı' WHERE ip = '{$ip}'");
if (isset($_GET['id'])) {
   $urunId = $_GET['id'];
   $urunbilgi = $pdo->query("SELECT * FROM urunler WHERE id = '{$urunId}'")->fetch(PDO::FETCH_ASSOC);
   if ($urunbilgi['urun_kategorisi']) {

      $kategoriSorgu = $pdo->prepare("SELECT kategori_adi FROM kategoriler WHERE id = :kategoriId");
      $kategoriSorgu->bindParam(':kategoriId', $urunbilgi['urun_kategorisi'], PDO::PARAM_INT);
      $kategoriSorgu->execute();
      $kategoriBilgi = $kategoriSorgu->fetch(PDO::FETCH_ASSOC);

      if ($kategoriBilgi) {
         if ($urunbilgi['urun_markasi'] == "Yudum") {
            $kategori_adi = "Sıvı Yağlar";
         } else {
            $kategori_adi = $kategoriBilgi['kategori_adi'];
         }
      }
   }
} else {
   header('Location:/');
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
} else {
   $sepetSayisi = 0;
}
?>
<!DOCTYPE html>
<html class="bg-zinc-900" lang="tr">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width" />
   <title><?= $urunbilgi['urun_adi']; ?></title>
   <meta name="next-head-count" content="16" />
   <link rel="preload" href="./assets/css/47Kb1JsK8kaH.css" as="style" />
   <link rel="stylesheet" href="./assets/css/47Kb1JsK8kaH.css" data-n-g="" />
   <link rel="preload" href="./assets/css/J2kGLr82eY3z.css" as="style" />
   <link rel="stylesheet" href="./assets/css/J2kGLr82eY3z.css" data-n-p="" />
   <noscript data-n-css=""></noscript>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <style>
      .starred-attributes ul {
         display: grid;
         grid-template-columns: repeat(4, 1fr);
         gap: 8px;
      }

      .starred-attributes li {
         display: inline-grid;
         border: solid #f5f5f5;
         background-color: #f5f5f5;
         padding: 8px 12px;
         width: 155px;
         border-radius: 4px;
      }

      @media (max-width: 400px) {
         .cdasfsdfcsadcfdcfscsf {
            grid-template-columns: repeat(2, 1fr);
         }
      }
   </style>
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
                              <a class="cursor-pointer <?php if ($urunbilgi['urun_markasi'] == "Yudum") {
                                                            echo "bg-white";
                                                         } else {
                                                            echo "bg-brand-blue-secondary";
                                                         } ?> flex justify-center items-center  rounded-md mobile:px-4 laptop:!px-8 tablet:!px-10 mobile:h-11 tablet:h-[52px] w-[125px] tablet:w-[160px]" href="/">
                                 <div class="select-none relative w-full h-full">
                                    <div class="transition-opacity duration-300 opacity-100 aspect-[70/26] w-full h-full flex items-center justify-center"><img loading="lazy" draggable="false" alt="A101 Kurumsal" src="https://api.a101prod.retter.io/dbmk89vnr/CALL/Image/get/a101-logo-2_256x256.svg" class="scale-x-100" style="width: 100%;"></div>
                                 </div>
                              </a>
                           </div>
                           <div class="z-20 relative">
                              <a class="cursor-pointer <?php if ($urunbilgi['urun_markasi'] == "Yudum") {
                                                            echo "bg-brand-blue-secondary";
                                                         } else {
                                                            echo "bg-white";
                                                         } ?> flex justify-center items-center rounded-md mobile:px-4 laptop:!px-8 tablet:!px-10 mobile:h-11 tablet:h-[52px] w-[125px] tablet:w-[160px]" href="/">
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
         <div class="relative flex-1">
            <div>
               <div>
                  <div class="w-full px-4 tablet:mx-auto tablet:max-w-screen-tablet tablet:px-5 laptop:max-w-screen-laptop laptop:px-6 desktop:max-w-screen-desktop desktop:px-9 ">
                     <div class="pt-5 pb-3 pl-1 mobile:pt-3">
                        <div class="flex flex-wrap">
                           <div class="flex items-center">
                              <a class="block cursor-pointer mobile:text-xs tablet:text-xs laptop:text-sm desktop:text-sm" title="Ana Sayfa" href="/"> Ana Sayfa </a>
                              <div class="mx-3">
                                 <svg width="5" height="10" viewBox="0 0 5 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0.5 1L4 5L0.5 9" stroke="#788089" stroke-linecap="round" stroke-linejoin="round"></path>
                                 </svg>
                              </div>
                           </div>
                           <div class="flex items-center">
                              <a class="block cursor-pointer mobile:text-xs tablet:text-xs laptop:text-sm desktop:text-sm" title="<?= $kategori_adi; ?>" href="/"> <?= $kategori_adi; ?> </a>
                           </div>
                        </div>
                     </div>
                     <div class="laptop:grid desktop:grid tablet:flex mobile:flex tablet:flex-col mobile:flex-col laptop:grid-cols-2 desktop:gap-10 laptop:gap-4 tablet:grid-cols-1">
                        <div class="relative desktop:mb-6 mobile:mb-4">
                           <div class="relative overflow-auto w-full carousel pb-4">
                              <button class="top-1/2 -translate-y-1/2 absolute  z-[2] left-0 " role="button">
                                 <div class="cursor-pointer">
                                    <svg width="36" height="74" viewBox="0 0 36 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                                       <g clip-path="url(#clip0_13881_662920)">
                                          <g filter="url(#filter0_d_13881_662920)">
                                             <path d="M-0.601562 0C17.9552 0 32.9984 15.0432 32.9984 33.6V36.4C32.9984 54.9568 17.9552 70 -0.601562 70V0Z" fill="#F3F6FA"></path>
                                          </g>
                                          <path d="M17.1992 42L9.03255 35L17.1992 28" stroke="#333333" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                       </g>
                                       <defs>
                                          <filter id="filter0_d_13881_662920" x="-4.60156" y="-2" width="41.6016" height="78" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                             <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                             <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"></feColorMatrix>
                                             <feOffset dy="2"></feOffset>
                                             <feGaussianBlur stdDeviation="2"></feGaussianBlur>
                                             <feComposite in2="hardAlpha" operator="out"></feComposite>
                                             <feColorMatrix type="matrix" values="0 0 0 0 0.2 0 0 0 0 0.2 0 0 0 0 0.2 0 0 0 0.15 0"></feColorMatrix>
                                             <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_13881_662920"></feBlend>
                                             <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_13881_662920" result="shape"></feBlend>
                                          </filter>
                                          <clipPath id="clip0_13881_662920">
                                             <rect width="36" height="74" fill="white" transform="matrix(-1 0 0 1 36 0)"></rect>
                                          </clipPath>
                                       </defs>
                                    </svg>
                                 </div>
                              </button>
                              <div class="swiper swiper-initialized swiper-horizontal swiper-ios rounded-3xl swiper-backface-hidden">
                                 <div class="swiper-wrapper" style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;">
                                    <div class="swiper-slide cursor-pointer swiper-slide-active">
                                       <div class="select-none relative w-full h-full">
                                          <div class="transition-opacity duration-300 opacity-100  max-h-[592px] overflow-hidden"><img loading="lazy" draggable="false" alt="<?= $urunbilgi['urun_adi']; ?>" title="<?= $urunbilgi['urun_adi']; ?>" src="<?= $urunbilgi['urun_resmi']; ?>" class="scale-x-100" style="width: 100%; object-fit: cover;"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <button class="top-1/2 -translate-y-1/2 absolute  z-[2] right-0 " role="button">
                                 <div class="cursor-pointer">
                                    <svg width="36" height="74" viewBox="0 0 36 74" fill="none" xmlns="http://www.w3.org/2000/svg">
                                       <g clip-path="url(#clip0_13881_662924)">
                                          <g filter="url(#filter0_d_13881_662924)">
                                             <path d="M36.6016 0C18.0448 0 3.00156 15.0432 3.00156 33.6V36.4C3.00156 54.9568 18.0448 70 36.6016 70V0Z" fill="#F3F6FA"></path>
                                          </g>
                                          <path d="M24 42L32.1667 35L24 28" stroke="#333333" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                       </g>
                                       <defs>
                                          <filter id="filter0_d_13881_662924" x="-1" y="-2" width="41.6016" height="78" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                             <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                             <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"></feColorMatrix>
                                             <feOffset dy="2"></feOffset>
                                             <feGaussianBlur stdDeviation="2"></feGaussianBlur>
                                             <feComposite in2="hardAlpha" operator="out"></feComposite>
                                             <feColorMatrix type="matrix" values="0 0 0 0 0.2 0 0 0 0 0.2 0 0 0 0 0.2 0 0 0 0.15 0"></feColorMatrix>
                                             <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_13881_662924"></feBlend>
                                             <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_13881_662924" result="shape"></feBlend>
                                          </filter>
                                          <clipPath id="clip0_13881_662924">
                                             <rect width="36" height="74" fill="white"></rect>
                                          </clipPath>
                                       </defs>
                                    </svg>
                                 </div>
                              </button>
                              <div class="absolute bottom-2 left-1/2 -translate-x-1/2 w-[140px] h-7 z-[1]">
                                 <div class="flex absolute left-1/2 bottom-2 -translate-x-1/2 snap-mandatory snap-x overflow-hidden p-1 transition-all duration-300 ease-linear z-[2]" style="width: 40px;">
                                    <div class="snap-center flex-none transition-all duration-300 ease-linear bg-brand-blue-primary  scale-[2.5] rounded-full cursor-pointer" style="width: 4px; height: 4px; margin: 8px;"></div>
                                 </div>
                              </div>
                           </div>
                           <div class="relative w-full carousel">
                              <div class="swiper swiper-initialized swiper-horizontal swiper-ios w-fit">
                                 <div class="swiper-wrapper" style="transform: translate3d(0px, 0px, 0px);">
                                    <div class="swiper-slide swiper-slide-active !w-[60px]" style="width: 10px; margin-right: 8px;">
                                       <div class="select-none relative w-full h-full">
                                          <div class="transition-opacity duration-300 opacity-100 flex justify-center items-center shrink-0 bg-white border shadow-[0px_2px_2px_0px_rgba(19,88,101,0.02)] rounded-2xl border-solid cursor-pointer border-brand-blue-primary"><img loading="lazy" draggable="false" alt="<?= $urunbilgi['urun_adi']; ?>" title="<?= $urunbilgi['urun_adi']; ?>" src="<?= $urunbilgi['urun_resmi']; ?>" class="scale-x-100" style="width: 60px; height: 60px; border-radius: 16px; object-fit: cover;"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="absolute z-[2] right-4 top-4 space-y-2">
                              <div></div>
                           </div>
                        </div>
                        <div class="tablet:mb-6 mobile:mb-4">
                           <div class="bg-white rounded-3xl p-4">
                              <div class="w-[150px] h-[30px] overflow-hidden relative">
                                 <div class="select-none relative w-full h-full">
                                    <div class="transition-opacity duration-300 opacity-100 "><img loading="lazy" draggable="false" alt="aldin-aldin" title="aldin-aldin" src="https://api.a101kapida.com/dbmk89vnr/CALL/Image/get/aldin-aldin-ozel_256x256.png" class="scale-x-100" style="width: 150px; height: 30px; object-fit: cover;"></div>
                                 </div>
                              </div>
                              <div class="flex justify-between ">
                                 <div class="flex-col">
                                    <h1 class="text-2xl mb-2 break-words laptop:max-w-full font-normal mt-0"><?= $urunbilgi['urun_adi']; ?></h1>
                                    <div class="flex justify-between items-center">
                                       <div class="text-sm text-brand-gray-primary">Ürün Kodu: <span class="font-medium">26034419</span></div>
                                    </div>
                                 </div>
                                 <div class=" mobile:hidden laptop:!flex">
                                    <div>
                                       <div style="width: max-content;">
                                          <div class="select-none cursor-pointer w-max flex items-center gap-1">
                                             <div class="flex align-center justify-center border rounded-full w-12 h-12 cursor-pointer mr-[5px]">
                                                <div class="flex items-center gap-2 justify-center text-brand-gray-primary">
                                                   <svg width="23" height="20" viewBox="0 0 23 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                      <path d="M11.2904 19.2C10.9832 19.2 10.687 19.0887 10.4561 18.8865C9.58432 18.1242 8.74379 17.4078 8.00221 16.7759L7.99843 16.7726C5.82424 14.9198 3.94675 13.3197 2.64043 11.7436C1.18016 9.98149 0.5 8.3108 0.5 6.48567C0.5 4.71241 1.10805 3.07646 2.21202 1.87897C3.32916 0.667321 4.86204 0 6.52877 0C7.7745 0 8.91535 0.39384 9.91955 1.17049C10.4263 1.56252 10.8857 2.0423 11.2904 2.60194C11.6953 2.0423 12.1545 1.56252 12.6614 1.17049C13.6656 0.39384 14.8065 0 16.0522 0C17.7188 0 19.2518 0.667321 20.369 1.87897C21.4729 3.07646 22.0808 4.71241 22.0808 6.48567C22.0808 8.3108 21.4008 9.98149 19.9406 11.7434C18.6342 13.3197 16.7569 14.9196 14.5831 16.7723C13.8402 17.4052 12.9983 18.1227 12.1245 18.8868C11.8938 19.0887 11.5975 19.2 11.2904 19.2ZM6.52877 1.26417C5.21932 1.26417 4.0164 1.78677 3.14129 2.7358C2.25318 3.69916 1.76401 5.03084 1.76401 6.48567C1.76401 8.02069 2.33451 9.39353 3.61367 10.9369C4.85002 12.4288 6.68898 13.996 8.81821 15.8105L8.82216 15.8138C9.56654 16.4482 10.4104 17.1674 11.2886 17.9353C12.1721 17.1659 13.0172 16.4456 13.7631 15.8102C15.8922 13.9956 17.731 12.4288 18.9673 10.9369C20.2463 9.39353 20.8168 8.02069 20.8168 6.48567C20.8168 5.03084 20.3276 3.69916 19.4395 2.7358C18.5646 1.78677 17.3615 1.26417 16.0522 1.26417C15.093 1.26417 14.2123 1.5691 13.4346 2.1704C12.7416 2.70649 12.2589 3.38419 11.9758 3.85837C11.8303 4.10222 11.5741 4.24777 11.2904 4.24777C11.0067 4.24777 10.7505 4.10222 10.605 3.85837C10.3221 3.38419 9.83936 2.70649 9.14619 2.1704C8.36856 1.5691 7.48785 1.26417 6.52877 1.26417Z" fill="currentColor"></path>
                                                   </svg>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="flex">
                                       <div>
                                          <div style="width: max-content;">
                                             <div class="select-none cursor-pointer w-max flex items-center gap-1 z-30">
                                                <div class="flex align-center justify-center border rounded-full w-12 h-12 cursor-pointer ml-[5px]">
                                                   <div class="flex items-center gap-2 justify-center text-brand-gray-primary h-interit">
                                                      <svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                         <path d="M5.95432 13.6364C7.46055 13.6364 8.6816 12.4153 8.6816 10.9091C8.6816 9.40287 7.46055 8.18182 5.95432 8.18182C4.44809 8.18182 3.22705 9.40287 3.22705 10.9091C3.22705 12.4153 4.44809 13.6364 5.95432 13.6364Z" stroke="currentColor" stroke-width="1.36364" stroke-linecap="round" stroke-linejoin="round"></path>
                                                         <path d="M15.5002 19.7727C17.0065 19.7727 18.2275 18.5517 18.2275 17.0454C18.2275 15.5392 17.0065 14.3182 15.5002 14.3182C13.994 14.3182 12.7729 15.5392 12.7729 17.0454C12.7729 18.5517 13.994 19.7727 15.5002 19.7727Z" stroke="currentColor" stroke-width="1.36364" stroke-linecap="round" stroke-linejoin="round"></path>
                                                         <path d="M15.5002 7.50002C17.0065 7.50002 18.2275 6.27898 18.2275 4.77274C18.2275 3.26651 17.0065 2.04547 15.5002 2.04547C13.994 2.04547 12.7729 3.26651 12.7729 4.77274C12.7729 6.27898 13.994 7.50002 15.5002 7.50002Z" stroke="currentColor" stroke-width="1.36364" stroke-linecap="round" stroke-linejoin="round"></path>
                                                         <path d="M13.2063 6.24756L8.24854 9.43469" stroke="currentColor" stroke-width="1.36364" stroke-linecap="round" stroke-linejoin="round"></path>
                                                         <path d="M8.24854 12.3839L13.2063 15.571" stroke="currentColor" stroke-width="1.36364" stroke-linecap="round" stroke-linejoin="round"></path>
                                                      </svg>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="flex gap-2 items-center my-5">
                                 <div class="text-2xl text-[#EA242A]">₺<?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                              </div>
                              <div class="flex gap-2 items-center mb-5"><span class="text-sm">Marka: </span><span class="text-sm text-brand-blue-primary font-medium cursor-pointer"><?= $urunbilgi['urun_markasi']; ?></span></div>
                              <div class=""></div>
                              <div class="flex gap-2 items-center my-5">
                                 <div class="flex items-center text-sm border-r last:border-r-0 pr-2 w-auto">
                                    <div class="w-4 h-4">
                                       <div class="select-none relative w-full h-full">
                                          <div class="transition-opacity duration-300 opacity-100 "><img loading="lazy" draggable="false" alt="A101 Ekstra'ya Özel" title="A101 Ekstra'ya Özel" src="https://f-a101-l.mncdn.com/webfiles/Attribute-iconURL/1704454335938_online.png" class="scale-x-100" style="width: 100%;"></div>
                                       </div>
                                    </div>
                                    <span class="w-full text-brand-gray-secondary pl-1 text-xs">A101 Ekstra'ya Özel</span>
                                 </div>
                              </div>
                              <div class="laptop:flex mobile:grid mobile:grid-cols-1 gap-2">
                                 <div class="flex w-full flex-col"><button class="bg-brand-blue-primary  rounded-full text-base  px-5 py-3 text-center w-full text-white " onclick="SepeteEkle(<?php echo $urunbilgi['id']; ?>)">Sepete Ekle</button></div>
                                 <div class="w-full gap-2 mobile:flex tablet:flex laptop:hidden mt-2">
                                    <div class="select-none cursor-pointer w-full flex items-center gap-1 "><button class="!bg-white  rounded-full !text-base px-5 py-3  text-center w-full text-brand-gray-secondary !border !border-[#E5E7E9] ">
                                          <div class="flex items-center gap-2 justify-center text-brand-gray-primary"><svg width="23" height="22" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.95432 13.6364C7.46055 13.6364 8.6816 12.4153 8.6816 10.9091C8.6816 9.40287 7.46055 8.18182 5.95432 8.18182C4.44809 8.18182 3.22705 9.40287 3.22705 10.9091C3.22705 12.4153 4.44809 13.6364 5.95432 13.6364Z" stroke="currentColor" stroke-width="1.36364" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M15.5002 19.7727C17.0065 19.7727 18.2275 18.5517 18.2275 17.0454C18.2275 15.5392 17.0065 14.3182 15.5002 14.3182C13.994 14.3182 12.7729 15.5392 12.7729 17.0454C12.7729 18.5517 13.994 19.7727 15.5002 19.7727Z" stroke="currentColor" stroke-width="1.36364" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M15.5002 7.50002C17.0065 7.50002 18.2275 6.27898 18.2275 4.77274C18.2275 3.26651 17.0065 2.04547 15.5002 2.04547C13.994 2.04547 12.7729 3.26651 12.7729 4.77274C12.7729 6.27898 13.994 7.50002 15.5002 7.50002Z" stroke="currentColor" stroke-width="1.36364" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M13.2063 6.24756L8.24854 9.43469" stroke="currentColor" stroke-width="1.36364" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8.24854 12.3839L13.2063 15.571" stroke="currentColor" stroke-width="1.36364" stroke-linecap="round" stroke-linejoin="round"></path>
                                             </svg>
                                             <div class="mobile:hidden tablet:!flex">Paylaş</div>
                                          </div>
                                       </button></div>
                                    <div class="select-none cursor-pointer w-full items-center gap-1">
                                       <div class="w-full gap-2"><button class="!bg-white  rounded-full !text-base px-5 py-3  text-center w-full text-brand-gray-secondary !border !border-[#E5E7E9] ">
                                             <div class="flex items-center gap-2 justify-center text-brand-gray-primary"><svg width="23" height="20" viewBox="0 0 23 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                   <path d="M11.2904 19.2C10.9832 19.2 10.687 19.0887 10.4561 18.8865C9.58432 18.1242 8.74379 17.4078 8.00221 16.7759L7.99843 16.7726C5.82424 14.9198 3.94675 13.3197 2.64043 11.7436C1.18016 9.98149 0.5 8.3108 0.5 6.48567C0.5 4.71241 1.10805 3.07646 2.21202 1.87897C3.32916 0.667321 4.86204 0 6.52877 0C7.7745 0 8.91535 0.39384 9.91955 1.17049C10.4263 1.56252 10.8857 2.0423 11.2904 2.60194C11.6953 2.0423 12.1545 1.56252 12.6614 1.17049C13.6656 0.39384 14.8065 0 16.0522 0C17.7188 0 19.2518 0.667321 20.369 1.87897C21.4729 3.07646 22.0808 4.71241 22.0808 6.48567C22.0808 8.3108 21.4008 9.98149 19.9406 11.7434C18.6342 13.3197 16.7569 14.9196 14.5831 16.7723C13.8402 17.4052 12.9983 18.1227 12.1245 18.8868C11.8938 19.0887 11.5975 19.2 11.2904 19.2ZM6.52877 1.26417C5.21932 1.26417 4.0164 1.78677 3.14129 2.7358C2.25318 3.69916 1.76401 5.03084 1.76401 6.48567C1.76401 8.02069 2.33451 9.39353 3.61367 10.9369C4.85002 12.4288 6.68898 13.996 8.81821 15.8105L8.82216 15.8138C9.56654 16.4482 10.4104 17.1674 11.2886 17.9353C12.1721 17.1659 13.0172 16.4456 13.7631 15.8102C15.8922 13.9956 17.731 12.4288 18.9673 10.9369C20.2463 9.39353 20.8168 8.02069 20.8168 6.48567C20.8168 5.03084 20.3276 3.69916 19.4395 2.7358C18.5646 1.78677 17.3615 1.26417 16.0522 1.26417C15.093 1.26417 14.2123 1.5691 13.4346 2.1704C12.7416 2.70649 12.2589 3.38419 11.9758 3.85837C11.8303 4.10222 11.5741 4.24777 11.2904 4.24777C11.0067 4.24777 10.7505 4.10222 10.605 3.85837C10.3221 3.38419 9.83936 2.70649 9.14619 2.1704C8.36856 1.5691 7.48785 1.26417 6.52877 1.26417Z" fill="currentColor"></path>
                                                </svg>
                                                <div class="mobile:hidden tablet:!flex">Favorilere Ekle</div>
                                             </div>
                                          </button></div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div></div>
                     <div class="product-detail-tabs-list">
                        <div class="translate-bug-fix bg-white rounded-t-3xl">
                           <div class="border-b-2 border-b-brand-gray-border h-12">
                              <div class="flex gap-2 overflow-x-auto overflow-y-hidden carousel pl-2">
                                 <div>
                                    <div class="w-max border-b-2 h-12 flex items-center cursor-pointer mx-3 pt-3 pb-[11px] border-b-brand-blue-primary" id="ozellikbuton" onclick="tabdegis('ozellik')">
                                       <h2 class="mt-[5px] text-base text-brand-gray-primary" id="urunozellikyazi">Ürün Özellikleri</h2>
                                    </div>
                                 </div>
                                 <div>
                                    <div class="w-max border-b-2 h-12 flex items-center cursor-pointer mx-3 pt-3 pb-[11px] border-b-brand-gray-border" id="taksitbuton" onclick="tabdegis('taksit')">
                                       <h2 class="mt-[5px] text-base text-brand-gray-secondary" id="taksityazi">Taksit Bilgileri</h2>
                                    </div>
                                 </div>
                                 <div>
                                    <div class="w-max border-b-2 h-12 flex items-center cursor-pointer mx-3 pt-3 pb-[11px] border-b-brand-gray-border" id="iadebuton" onclick="tabdegis('iade')">
                                       <h2 class="mt-[5px] text-base text-brand-gray-secondary" id="iadeyazi">İade Koşulları</h2>
                                    </div>
                                 </div>
                                 <div>
                                    <div class="w-max border-b-2 h-12 flex items-center cursor-pointer mx-3 pt-3 pb-[11px] border-b-brand-gray-border" id="teslimatbuton" onclick="tabdegis('teslimat')">
                                       <h2 class="mt-[5px] text-base text-brand-gray-secondary" id="teslimatyazi">Teslimat</h2>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="bg-white px-4 py-4 rounded-b-3xl break-word" id="ozellikyeri">
                           <p><strong><?= $urunbilgi['urun_adi']; ?></strong></p>
                           <?php if ($urunbilgi['urun_markasi'] == "Yudum"): ?>
                              <br>
                              <div class="text-sm text-brand-gray-secondary -mt-4">
                                 <p>Yemekleri lezzetlendiren unsurlar arasında, kullanılan yağ ve baharatların yeri oldukça önemlidir. Tercih edilen yağın cinsi ve kalitesi yemeğin tadını etkileyecektir.Ayçiçekleri hayatımızın her alanında yer alan verimli ve bereketli bir bitkidir. Trakya Bölgesi ve Marmara’da yetiştirilen ayçiçeklerinden presleme yöntemiyle elde edilen Yudum Ayçiçek yağı; açık sarı rengi, berrak yapısı ve hafif lezzeti ile çok tercih ediliyor. Doğal E vitamini kaynağı olan ve bol miktarda doymamış yağ asitlerini yapısında bulunduran Yudum Ayçiçek Yağı'nı yemeklerinizde de kullanabilirsiniz. Hafif kızartmalar ve yemekler hazırlarken güvenle kullanacağınız bir yağ olan Yudum Ayçiçek Yağı ile son derece sağlıklı ve besleyici yemekler hazırlayabilirsiniz. Sağlıklı, hafif ve altın renginde olan ayçiçeği yağı diğer bitkisel yağlara kıyasla daha fazla E vitamini ve omega 6 yağları içermektedir.</p>
                                 <h2>İçindekiler</h2>
                                 <p>100 gram birime göre hesaplandığında yumurtada 884 kalori ve E vitamini bulunur.</p>
                                 <h2>Besin Değerleri</h2>
                                 <p>YUDUM AYÇİÇEK YAĞI 100 gramındaki besin değerleri tabloda görüldüğü gibidir.</p>
                                 <div align="center">
                                    <table style="width: 100%;" border="1" cellspacing="0" cellpadding="0">
                                       <tbody>
                                          <tr>
                                             <td valign="bottom" width="106">
                                                <p align="center">Enerji (kcal)</p>
                                             </td>
                                             <td valign="bottom" width="100">
                                                <p align="center">884</p>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td valign="bottom" width="106">
                                                <p align="center">Yağ (gr)</p>
                                             </td>
                                             <td valign="bottom" width="100">
                                                <p align="center">100</p>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td valign="bottom" width="106">
                                                <p align="center">Protein (gr)</p>
                                             </td>
                                             <td valign="bottom" width="100">
                                                <p align="center">0</p>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td valign="bottom" width="106">
                                                <p align="center">Karbonhidrat (gr)</p>
                                             </td>
                                             <td valign="bottom" width="100">
                                                <p align="center">0</p>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td valign="bottom" width="106">
                                                <p align="center">A Vitamini (IU)</p>
                                             </td>
                                             <td valign="bottom" width="100">
                                                <p align="center">0</p>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td valign="bottom" width="106">
                                                <p align="center">Demir</p>
                                             </td>
                                             <td valign="bottom" width="100">
                                                <p align="center">0</p>
                                             </td>
                                          </tr>
                                          <tr>
                                             <td valign="bottom" width="106">
                                                <p align="center">E vitamin(IU)</p>
                                             </td>
                                             <td valign="bottom" width="100">
                                                <p align="center">71,27</p>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                 </div>
                                 <h2>Kullanım Önerisi</h2>
                                 <p>Et ve sebze yemeklerinde rahatlıkla kullanılır. Salata ve salata soslarında da hafif olması sebebiyle tüketilir. Çorbalarınızı lezzetlerinden çorba sosları için de uygundur. En çok kullanıldığı yemek türü ise kızartmalardır. Yüksek ısıya dayanıklı olması sebebiyle sebze, hamur ve köfte kızartırken kolaylıkla kullanabilirsiniz.</p>
                                 <h3>Uyarılar</h3>
                                 <p>Ayçiçek yağının ihtiva ettiği maddelere karşı alerjisi olduğundan şüphelenenler kullanımdan önce&nbsp; doktora danışmalıdırlar.</p>
                                 <h2>Saklama Koşulları</h2>
                                 <p>Ayçiçek yağı 20-24 derece sıcaklıkta serin ve kuru bir yerde muhafaza edilmelidir.Ayçiçek yağı buzdolabına konmamalıdır. Onun yerine serin dolap içi veya kilerde bekletilmelidir. Ayçiçek yağını açtıktan sonra, koyu renkli cam şişelerde daha uzun süre saklayabilirsiniz.</p>
                                 <p>&nbsp;</p>
                                 <p>Bu üründen en fazla 3 adet sipariş verilebilir. Belirtilen adet üzerindeki siparişlerin iptal edilmesi hakkı saklıdır.</p>
                              </div>
                           <?php else: ?>
                              <p><?= $urunbilgi['urun_aciklamasi']; ?></p>
                              <br>
                              <p>İnternet sitemizde ve online satış kanallarımızda yer alan ürün etiket bilgileri, ürünün tedarikçisi tarafından A101 Yeni Mağazacılık A.Ş ‘ye iletilen en güncel bilgilerdir. Ürün etiket bilgileri ile internet sitemiz ve online satış kanallarımızda bulunan bilgiler arasında herhangi bir farklılık bulunması halinde sorumluluk tamamen tedarikçi firmaya aittir.</p>
                              <p>Fiyatlarımız; A101 Ekstra, A101 Kapıda&nbsp; ve mağazalarımızda farklılık gösterebilir, belirtilen ürün fiyatı yalnızca bu satış kanalı için geçerlidir.</p>
                              <p>Ürünlerimiz, yukarıda belirtilen satış kanallarında satışa sunulmaktadır. Ekstra satış kanalı, A101 Ekstra website ve mobil kısmını kapsamaktadır.</p>
                              <p>Mesafeli Satış Sözleşmemiz uyarınca; toptan veya yeniden satış amaçlı oluşturulduğu tespit edilen siparişler ile ürün, hizmet ve indirimlerimizden tüm müşterilerimizin faydalanabilmesi ve stokçuluğun engellenmesi için 5 adetin üzerindeki doğrudan ve dolaylı siparişleri A101’in iptal hakkı saklıdır.</p>
                           <?php endif; ?>
                        </div>
                     </div>

                     <div class="bg-white rounded-b-3xl" style="display: none" id="taksityeri">
                        <div class="grid laptop:grid-cols-2 mobile:grid-cols-1 gap-4 p-4">
                           <div>
                              <div class="bg-white border border-brand-gray-skeleton rounded-3xl">
                                 <div class="relative flex items-center justify-between cursor-pointer">
                                    <div class="text-sm w-11/12 p-4">
                                       <div class="select-none relative w-full h-full">
                                          <div class="transition-opacity duration-300  w-20"><img draggable="false" class="scale-x-100" src="https://cdn2.a101.com.tr/dbmk89vnr/CALL/Image/get/Axess_256x256.png" width="" height="" loading="lazy" fetchpriority="auto" style="width: 100%;"></div>
                                       </div>
                                    </div>
                                    <div class="pr-4">
                                       <svg width="10" height="2" viewBox="0 0 10 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M9.46428 0.464844C9.76015 0.464844 10 0.704691 10 1.00056C10 1.29642 9.76015 1.53627 9.46428 1.53627H0.535714C0.239847 1.53627 0 1.29642 0 1.00056C0 0.704691 0.239847 0.464844 0.535714 0.464844C6.31264 0.464844 4.03311 0.464844 9.46428 0.464844Z" fill="#333333"></path>
                                       </svg>
                                    </div>
                                 </div>
                                 <div class="text-sm font-light border-t block">
                                    <div class="flex justify-between py-3 px-4 text-brand-gray-secondary">
                                       <div class="flex w-full justify-between">
                                          <div class=""></div>
                                          <p>Aylık Ödeme</p>
                                       </div>
                                       <div class="flex justify-end w-full">
                                          <p>Toplam Tutar</p>
                                       </div>
                                    </div>
                                    <div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">3 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 3;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">5 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 5;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">9 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 9;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div>
                              <div class="bg-white border border-brand-gray-skeleton rounded-3xl">
                                 <div class="relative flex items-center justify-between cursor-pointer">
                                    <div class="text-sm w-11/12 p-4">
                                       <div class="select-none relative w-full h-full">
                                          <div class="transition-opacity duration-300  w-20"><img draggable="false" class="scale-x-100" src="https://cdn2.a101.com.tr/dbmk89vnr/CALL/Image/get/Bonus_256x256.png" width="" height="" loading="lazy" fetchpriority="auto" style="width: 100%;"></div>
                                       </div>
                                    </div>
                                    <div class="pr-4">
                                       <svg width="10" height="2" viewBox="0 0 10 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M9.46428 0.464844C9.76015 0.464844 10 0.704691 10 1.00056C10 1.29642 9.76015 1.53627 9.46428 1.53627H0.535714C0.239847 1.53627 0 1.29642 0 1.00056C0 0.704691 0.239847 0.464844 0.535714 0.464844C6.31264 0.464844 4.03311 0.464844 9.46428 0.464844Z" fill="#333333"></path>
                                       </svg>
                                    </div>
                                 </div>




                                 <div class="text-sm font-light border-t block">
                                    <div class="flex justify-between py-3 px-4 text-brand-gray-secondary">
                                       <div class="flex w-full justify-between">
                                          <div class=""></div>
                                          <p>Aylık Ödeme</p>
                                       </div>
                                       <div class="flex justify-end w-full">
                                          <p>Toplam Tutar</p>
                                       </div>
                                    </div>
                                    <div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">3 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 3;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">5 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 5;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">9 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 9;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div>
                              <div class="bg-white border border-brand-gray-skeleton rounded-3xl">
                                 <div class="relative flex items-center justify-between cursor-pointer">
                                    <div class="text-sm w-11/12 p-4">
                                       <div class="select-none relative w-full h-full">
                                          <div class="transition-opacity duration-300  w-20"><img draggable="false" class="scale-x-100" src="https://cdn2.a101.com.tr/dbmk89vnr/CALL/Image/get/Worldcard_256x256.png" width="" height="" loading="lazy" fetchpriority="auto" style="width: 100%;"></div>
                                       </div>
                                    </div>
                                    <div class="pr-4">
                                       <svg width="10" height="2" viewBox="0 0 10 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M9.46428 0.464844C9.76015 0.464844 10 0.704691 10 1.00056C10 1.29642 9.76015 1.53627 9.46428 1.53627H0.535714C0.239847 1.53627 0 1.29642 0 1.00056C0 0.704691 0.239847 0.464844 0.535714 0.464844C6.31264 0.464844 4.03311 0.464844 9.46428 0.464844Z" fill="#333333"></path>
                                       </svg>
                                    </div>
                                 </div>
                                 <div class="text-sm font-light border-t block">
                                    <div class="flex justify-between py-3 px-4 text-brand-gray-secondary">
                                       <div class="flex w-full justify-between">
                                          <div class=""></div>
                                          <p>Aylık Ödeme</p>
                                       </div>
                                       <div class="flex justify-end w-full">
                                          <p>Toplam Tutar</p>
                                       </div>
                                    </div>
                                    <div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">3 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 3;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">5 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 5;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">9 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 9;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div>
                              <div class="bg-white border border-brand-gray-skeleton rounded-3xl">
                                 <div class="relative flex items-center justify-between cursor-pointer">
                                    <div class="text-sm w-11/12 p-4">
                                       <div class="select-none relative w-full h-full">
                                          <div class="transition-opacity duration-300  w-20"><img draggable="false" class="scale-x-100" src="https://cdn2.a101.com.tr/dbmk89vnr/CALL/Image/get/Maximum_256x256.png" width="" height="" loading="lazy" fetchpriority="auto" style="width: 100%;"></div>
                                       </div>
                                    </div>
                                    <div class="pr-4">
                                       <svg width="10" height="2" viewBox="0 0 10 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M9.46428 0.464844C9.76015 0.464844 10 0.704691 10 1.00056C10 1.29642 9.76015 1.53627 9.46428 1.53627H0.535714C0.239847 1.53627 0 1.29642 0 1.00056C0 0.704691 0.239847 0.464844 0.535714 0.464844C6.31264 0.464844 4.03311 0.464844 9.46428 0.464844Z" fill="#333333"></path>
                                       </svg>
                                    </div>
                                 </div>
                                 <div class="text-sm font-light border-t block">
                                    <div class="flex justify-between py-3 px-4 text-brand-gray-secondary">
                                       <div class="flex w-full justify-between">
                                          <div class=""></div>
                                          <p>Aylık Ödeme</p>
                                       </div>
                                       <div class="flex justify-end w-full">
                                          <p>Toplam Tutar</p>
                                       </div>
                                    </div>
                                    <div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">3 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 3;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">5 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 5;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">9 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 9;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div>
                              <div class="bg-white border border-brand-gray-skeleton rounded-3xl">
                                 <div class="relative flex items-center justify-between cursor-pointer">
                                    <div class="text-sm w-11/12 p-4">
                                       <div class="select-none relative w-full h-full">
                                          <div class="transition-opacity duration-300  w-20"><img draggable="false" class="scale-x-100" src="https://cdn2.a101.com.tr/dbmk89vnr/CALL/Image/get/Bankkart_256x256.png" width="" height="" loading="lazy" fetchpriority="auto" style="width: 100%;"></div>
                                       </div>
                                    </div>
                                    <div class="pr-4">
                                       <svg width="10" height="2" viewBox="0 0 10 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M9.46428 0.464844C9.76015 0.464844 10 0.704691 10 1.00056C10 1.29642 9.76015 1.53627 9.46428 1.53627H0.535714C0.239847 1.53627 0 1.29642 0 1.00056C0 0.704691 0.239847 0.464844 0.535714 0.464844C6.31264 0.464844 4.03311 0.464844 9.46428 0.464844Z" fill="#333333"></path>
                                       </svg>
                                    </div>
                                 </div>
                                 <div class="text-sm font-light border-t block">
                                    <div class="flex justify-between py-3 px-4 text-brand-gray-secondary">
                                       <div class="flex w-full justify-between">
                                          <div class=""></div>
                                          <p>Aylık Ödeme</p>
                                       </div>
                                       <div class="flex justify-end w-full">
                                          <p>Toplam Tutar</p>
                                       </div>
                                    </div>
                                    <div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">3 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 3;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">5 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 5;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">9 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 9;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div>
                              <div class="bg-white border border-brand-gray-skeleton rounded-3xl">
                                 <div class="relative flex items-center justify-between cursor-pointer">
                                    <div class="text-sm w-11/12 p-4">
                                       <div class="select-none relative w-full h-full">
                                          <div class="transition-opacity duration-300  w-20"><img draggable="false" class="scale-x-100" src="https://cdn2.a101.com.tr/dbmk89vnr/CALL/Image/get/Paraf_256x256.png" width="" height="" loading="lazy" fetchpriority="auto" style="width: 100%;"></div>
                                       </div>
                                    </div>
                                    <div class="pr-4">
                                       <svg width="10" height="2" viewBox="0 0 10 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M9.46428 0.464844C9.76015 0.464844 10 0.704691 10 1.00056C10 1.29642 9.76015 1.53627 9.46428 1.53627H0.535714C0.239847 1.53627 0 1.29642 0 1.00056C0 0.704691 0.239847 0.464844 0.535714 0.464844C6.31264 0.464844 4.03311 0.464844 9.46428 0.464844Z" fill="#333333"></path>
                                       </svg>
                                    </div>
                                 </div>
                                 <div class="text-sm font-light border-t block">
                                    <div class="flex justify-between py-3 px-4 text-brand-gray-secondary">
                                       <div class="flex w-full justify-between">
                                          <div class=""></div>
                                          <p>Aylık Ödeme</p>
                                       </div>
                                       <div class="flex justify-end w-full">
                                          <p>Toplam Tutar</p>
                                       </div>
                                    </div>
                                    <div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">3 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 3;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">5 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 5;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                       <div class="flex justify-between w-full py-3 px-4 border-t">
                                          <div class="flex justify-between w-full">
                                             <div class="text-sm">9 Taksit</div>
                                             <div class="text-sm">₺
                                                <?php
                                                $urunFiyati = (float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati']));
                                                $dcqwsdcfqwqw = $urunFiyati / 9;
                                                echo number_format($dcqwsdcfqwqw, 2, ',', '.');
                                                ?>
                                             </div>
                                          </div>
                                          <div class="flex justify-end w-full">
                                             <div class="text-sm">₺ <?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>

                     <div class="bg-white px-4 py-4 rounded-b-3xl break-normal" style="display: none" id="iadeyeri">
                        <ul>

                           <li style="text-align: justify;"> İade süresi, siparişinizdeki ürünlerin adresinize teslim edilme tarihinden itibaren 14 gündür.</li>
                           <li>İade edilecek ürün kullanılmamış, orijinal ambalajına zarar verilmemiş ve tekrar satılabilirlik özelliğini kaybetmemiş olmalıdır.</li>
                           <li>İade kodu aynı olan ürünleri faturasıyla beraber aynı paket ile aynı iade kodu ile göndermelisiniz.</li>
                           <li>İade kodu farklı olan ürünleri faturasıyla beraber farklı paketler halinde sistem tarafından verilen iade kodu ile gönderilmelidir.</li>
                           <li>Ürününüzü iade kodu ile göndermeniz gerekmektedir. Üyelik hesabınıza giriş yaptıktan sonra “ Profilim &gt;&gt; Siparişlerim &gt;&gt; Sipariş Detayı" adımlarını izleyerek siparişinizdeki ürünleriniz için iade talebinde bulunup, ürününüzle ilgili kargo firması ve iade kargo kodunuzu alabilirsiniz.</li>
                           <li>Televizyon, görüntü sistemleri, cep telefonu, tablet, notebook gibi elektronik ürünlerin kullanılması ya da ambalajının açılması durumunda ürün iadeleri servis raporu ile birlikte yapılabilmektedir.</li>
                           <li>Kurulumu/montajı yapılmış mobilya ürünlerinin iadesi kabul edilememektedir.</li>
                           <li>Garanti kapsamında arızalanan ürünlerin iadesi servis raporu/iade raporu ile birlikte yapılabilmektedir.</li>
                           <li>Ürünlerin kargo paketinde ya da ürünlerde hasar tespit edilmesi durumunda kargo görevlisine hasar tespit tutanağı düzenletmelisiniz. Kargo görevlisinin tutanak düzenlemediği bu ve benzeri durumlarda ürünü teslim almayarak iade edebilirsiniz.</li>
                           <li>Hijyenik ürünlerin (iç giyim, kişisel bakım ürünleri, sağlık ürünleri vb.) ambalajı açılmış ise iadesi kabul edilmemektedir.</li>
                           <li>Niteliği itibarı ile son kullanma tarihi geçecek ya da çabuk bozulabilecek ürünlerin iadesi kabul edilememektedir.</li>
                           <li>Bayi-yetkili servis ya da Horoz Lojistik ile teslimatı yapılan ürün iade işlemleri için 0850 808 2 101 numaralı müşteri hizmetlerimiz ile irtibata geçebilirsiniz.</li>
                           <li>Set olarak satışa sunulan ürünlerin iadesinin sağlanabilmesi için tüm ürünlerin iade olarak gönderilmesi gerekmektedir.</li>
                           <li>Üye olmadan alışveriş yaptıysanız siparişinizin iadesi için A101 Ekstra sayfasında Sipariş Takip bölümünden sipariş verirken girmiş olduğunuz Telefon numaranız ile şifre alıp, siparişiniz için iade işlemi yapabilirsiniz. 0850 808 2 101 numaralı müşteri hizmetlerimiz ile irtibata geçip bilgi alabilirsiniz.</li>
                           <li>Ürün firmamıza teslim edildikten sonra incelenecek ve 7 iş günü içinde işlemleri tamamlanacaktır.</li>
                        </ul>
                        <p>&nbsp;</p>
                     </div>
                     <div class="bg-white px-4 py-4 rounded-b-3xl break-normal" style="display: none" id="teslimatyeri">
                        <p>Ürün afişinde belirtilen sürelerde kargolama ve teslimatlar gerçekleştirilir. Ürün afişinde, ürüne özel bir teslim tarihi belirtilmemişse mevzuattaki teslimat süreleri geçerlidir.</p>
                     </div>
                  </div>

               </div>

            </div>
         </div>
         <script>
            function tabdegis(tab) {

               if (tab == "ozellik") {
                  document.getElementById('ozellikyeri').style.display = "block"
                  document.getElementById('taksityeri').style.display = "none"
                  document.getElementById('iadeyeri').style.display = "none"
                  document.getElementById('teslimatyeri').style.display = "none"

                  document.getElementById('ozellikbuton').classList.add("border-b-brand-blue-primary")
                  document.getElementById('ozellikbuton').classList.remove("border-b-brand-gray-border")

                  document.getElementById('taksitbuton').classList.add("border-b-brand-gray-border")
                  document.getElementById('taksitbuton').classList.remove("border-b-brand-blue-primary")

                  document.getElementById('iadebuton').classList.add("border-b-brand-gray-border")
                  document.getElementById('iadebuton').classList.remove("border-b-brand-blue-primary")

                  document.getElementById('teslimatbuton').classList.add("border-b-brand-gray-border")
                  document.getElementById('teslimatbuton').classList.remove("border-b-brand-blue-primary")

                  document.getElementById('urunozellikyazi').classList.add("text-brand-gray-primary")
                  document.getElementById('urunozellikyazi').classList.remove("text-brand-gray-secondary")

                  document.getElementById('taksityazi').classList.add("text-brand-gray-secondary")
                  document.getElementById('taksityazi').classList.remove("text-brand-gray-primary")

                  document.getElementById('iadeyazi').classList.add("text-brand-gray-secondary")
                  document.getElementById('iadeyazi').classList.remove("text-brand-gray-primary")

                  document.getElementById('teslimatyazi').classList.add("text-brand-gray-secondary")
                  document.getElementById('teslimatyazi').classList.remove("text-brand-gray-primary")

               } else if (tab == "taksit") {
                  document.getElementById('ozellikyeri').style.display = "none"
                  document.getElementById('taksityeri').style.display = "block"
                  document.getElementById('iadeyeri').style.display = "none"
                  document.getElementById('teslimatyeri').style.display = "none"

                  document.getElementById('ozellikbuton').classList.remove("border-b-brand-blue-primary")
                  document.getElementById('ozellikbuton').classList.add("border-b-brand-gray-border")

                  document.getElementById('taksitbuton').classList.remove("border-b-brand-gray-border")
                  document.getElementById('taksitbuton').classList.add("border-b-brand-blue-primary")

                  document.getElementById('iadebuton').classList.add("border-b-brand-gray-border")
                  document.getElementById('iadebuton').classList.remove("border-b-brand-blue-primary")

                  document.getElementById('teslimatbuton').classList.add("border-b-brand-gray-border")
                  document.getElementById('teslimatbuton').classList.remove("border-b-brand-blue-primary")








                  document.getElementById('urunozellikyazi').classList.remove("text-brand-gray-primary")
                  document.getElementById('urunozellikyazi').classList.add("text-brand-gray-secondary")

                  document.getElementById('taksityazi').classList.remove("text-brand-gray-secondary")
                  document.getElementById('taksityazi').classList.add("text-brand-gray-primary")

                  document.getElementById('iadeyazi').classList.add("text-brand-gray-secondary")
                  document.getElementById('iadeyazi').classList.remove("text-brand-gray-primary")

                  document.getElementById('teslimatyazi').classList.add("text-brand-gray-secondary")
                  document.getElementById('teslimatyazi').classList.remove("text-brand-gray-primary")

               } else if (tab == "iade") {
                  document.getElementById('ozellikyeri').style.display = "none"
                  document.getElementById('taksityeri').style.display = "none"
                  document.getElementById('iadeyeri').style.display = "block"
                  document.getElementById('teslimatyeri').style.display = "none"

                  document.getElementById('ozellikbuton').classList.remove("border-b-brand-blue-primary")
                  document.getElementById('ozellikbuton').classList.add("border-b-brand-gray-border")

                  document.getElementById('taksitbuton').classList.add("border-b-brand-gray-border")
                  document.getElementById('taksitbuton').classList.remove("border-b-brand-blue-primary")

                  document.getElementById('iadebuton').classList.remove("border-b-brand-gray-border")
                  document.getElementById('iadebuton').classList.add("border-b-brand-blue-primary")

                  document.getElementById('teslimatbuton').classList.add("border-b-brand-gray-border")
                  document.getElementById('teslimatbuton').classList.remove("border-b-brand-blue-primary")



                  document.getElementById('urunozellikyazi').classList.add("text-brand-gray-primary")
                  document.getElementById('urunozellikyazi').classList.remove("text-brand-gray-secondary")

                  document.getElementById('taksityazi').classList.remove("text-brand-gray-secondary")
                  document.getElementById('taksityazi').classList.add("text-brand-gray-primary")

                  document.getElementById('iadeyazi').classList.remove("text-brand-gray-secondary")
                  document.getElementById('iadeyazi').classList.add("text-brand-gray-primary")

                  document.getElementById('teslimatyazi').classList.add("text-brand-gray-secondary")
                  document.getElementById('teslimatyazi').classList.remove("text-brand-gray-primary")

               } else if (tab == "teslimat") {
                  document.getElementById('ozellikyeri').style.display = "none"
                  document.getElementById('taksityeri').style.display = "none"
                  document.getElementById('iadeyeri').style.display = "none"
                  document.getElementById('teslimatyeri').style.display = "block"

                  document.getElementById('ozellikbuton').classList.remove("border-b-brand-blue-primary")
                  document.getElementById('ozellikbuton').classList.add("border-b-brand-gray-border")

                  document.getElementById('taksitbuton').classList.add("border-b-brand-gray-border")
                  document.getElementById('taksitbuton').classList.remove("border-b-brand-blue-primary")

                  document.getElementById('iadebuton').classList.add("border-b-brand-gray-border")
                  document.getElementById('iadebuton').classList.remove("border-b-brand-blue-primary")

                  document.getElementById('teslimatbuton').classList.remove("border-b-brand-gray-border")
                  document.getElementById('teslimatbuton').classList.add("border-b-brand-blue-primary")


                  document.getElementById('urunozellikyazi').classList.remove("text-brand-gray-primary")
                  document.getElementById('urunozellikyazi').classList.add("text-brand-gray-secondary")

                  document.getElementById('taksityazi').classList.remove("text-brand-gray-secondary")
                  document.getElementById('taksityazi').classList.add("text-brand-gray-primary")

                  document.getElementById('iadeyazi').classList.remove("text-brand-gray-secondary")
                  document.getElementById('iadeyazi').classList.add("text-brand-gray-primary")

                  document.getElementById('teslimatyazi').classList.remove("text-brand-gray-secondary")
                  document.getElementById('teslimatyazi').classList.add("text-brand-gray-primary")

               }
            }
         </script>
         <div>
            <div class="bg-white">
               <div class="w-full px-4 tablet:mx-auto tablet:max-w-screen-tablet tablet:px-5 laptop:max-w-screen-laptop laptop:px-6 desktop:max-w-screen-desktop desktop:px-9 ">
                  <div>
                     <div class="flex-1 text-center w-full py-4">
                        © 2024 A101 Ekstra
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

   <div class="fixed inset-0 ios-bottom w-full z-[50] flex items-center justify-center pointer-events-none" style='display: none' id='errNotify'>
      <div class="w-[calc(100%-1rem)] text-center relative desktop:max-w-[445px] tablet:max-w-[375px] mobile:max-w-[343px] flex flex-col p-4 items-center justify-center  bg-white rounded-3xl border border-brand-gray-border pointer-events-auto">
         <div class=" mt-[34px]">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
               <circle cx="40" cy="40" r="40" fill="#F3F6FA"></circle>
               <path d="M40.0977 25C38.9438 25 38 25.9439 38 27.0977V44.8814C38 46.0352 38.9439 46.9791 40.0977 46.9791C41.2516 46.9791 42.1954 46.0352 42.1954 44.8814V27.0977C42.1954 25.9438 41.2516 25 40.0977 25Z" fill="#F34133"></path>
               <path d="M40.0977 50C38.9438 50 38 50.9439 38 52.0977C38 53.2516 38.9439 54.1954 40.0977 54.1954C41.2516 54.1954 42.1954 53.2516 42.1954 52.0977C42.1954 50.8392 41.2516 50 40.0977 50Z" fill="#F34133"></path>
            </svg>
         </div>
         <div class="text-xl font-medium text-brand-gray-primary mt-6">Hata</div>
         <div class="text-sm font-normal text-brand-gray-primary mt-2 whitespace-pre-line break-words" id='errNotifyText'></div>
         <div class="self-stretch mt-8"><button class="bg-brand-blue-primary  rounded-full text-base  px-5 py-3 text-center w-full text-white " onclick="errNotifyKapat();">Tamam</button></div>
         <div class="absolute top-4 right-4 cursor-pointer" onclick="errNotifyKapat();">
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
                     document.getElementById('sepetNotify').style.display = "block"
                  } else if (data == "fail") {
                     errNotify('Şu anda bu üründen stoğumuzda daha fazla bulunmuyor.');
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

   <div id="sepetNotify" style='display: none'>
      <div data-headlessui-portal="">
         <div class="fixed inset-0 ios-bottom w-full z-20 bg-black/40"></div>
         <div class="fixed inset-0 ios-bottom w-full z-20 flex items-center justify-center pointer-events-none">
            <div class="w-[calc(100%-1rem)]  relative mobile:max-w-[375px] tablet:max-w-[600px]  bg-white   rounded-3xl border border-brand-gray-border">
               <div class="pointer-events-auto">
                  <div class="text-center pt-[42px] mb-2 text-xl px-4 font-medium">Ürün Sepetine Eklendi</div>
                  <div class="relative">
                     <div class="overflow-y-auto p-4 max-h-[75vh] ">
                        <div class="relative -mt-4 pt-4">
                           <div class="absolute top-0 -left-4 -right-4 h-[1px] border-t"></div>
                           <div class="flex items-center tablet:py-3 tablet:px-4 mobile:py-4 mobile:px-4 rounded-3xl bg-white cursor-pointer border">
                              <div class="flex justify-between w-full items-center gap-4 relative">
                                 <div class="flex">
                                    <div class="border border-brand-gray-border rounded-2xl overflow-hidden flex-none max-h-20">
                                       <div class="relative w-20 aspect-square">
                                          <div class="select-none relative w-full h-full">
                                             <div class="transition-opacity duration-300 opacity-100 "><img loading="lazy" draggable="false" src="<?= $urunbilgi['urun_resmi']; ?>" class="scale-x-100" style="width: 100%; object-fit: cover;"></div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="pl-4 flex flex-col justify-center">
                                       <div class="line-clamp-2 text-sm font-medium"><?= $urunbilgi['urun_adi']; ?></div>
                                       <div class="flex items-center gap-2">
                                          <div class="text-lg text-[#EA242A] font-medium">₺<?php echo number_format((float) str_replace(',', '.', str_replace('TL', '', $urunbilgi['urun_fiyati'])), 2, ',', '.'); ?></div>
                                       </div>

                                    </div>
                                 </div>
                              </div>

                           </div>
                           <div class="personaclick-recommend" data-recommender-block="dynamic" data-recommender-code="24bce634bb25e02c0618cb17f6b53a60"></div>
                           <div class="flex items-center justify-center mb-4 w-full gap-4 tablet:flex-nowrap mobile:flex-wrap laptop:flex-nowrap desktop:flex-nowrap"><button class="!bg-white  rounded-full !text-base px-5 py-3  text-center w-full text-brand-gray-secondary !border !border-[#E5E7E9] " onclick='window.location.href="/"'>Alışverişe devam et</button><button class="bg-brand-blue-primary  rounded-full text-base  p-[14px] text-md text-center w-full text-white " onclick="window.location.href='sepet'">Sepete Git</button></div>
                        </div>
                     </div>
                  </div>
                  <div class="w-5 h-5 absolute top-4 right-4 cursor-pointer" onclick='document.getElementById("sepetNotify").style.display = "none"'>
                     <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L15 15M15 1L1 15" stroke="#333333" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round"></path>
                     </svg>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</body>

</html>