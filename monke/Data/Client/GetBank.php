<?php

$banka_logo = array(

    //KUVEYT TURK
    'KUVEYT TURK KATILIM BANKASI, A.S.' => '/monke/assets/banka/kuveytturk.png',
    'KUVEYT TURK KATILIM BANKASI A.S.' => '/monke/assets/banka/kuveytturk.png',
    'KUVEYT TURK BANK' => '/monke/assets/banka/kuveytturk.png',
    'KUVEYT TURK KATILIM BANKASI  A.S.' => '/monke/assets/banka/kuveytturk.png',

    //ING BANK
    'ING BANK, A.S.' => '/monke/assets/banka/ing.png',
    'ING BANK A.S.' => '/monke/assets/banka/ing.png',

    //FINANSBANK
    'FINANSBANK, A.S.' => '/monke/assets/banka/finansbank.png',
    'FINANSBANK A.S.' => '/monke/assets/banka/finansbank.png',
    'FINANS BANK, A.S.' => '/monke/assets/banka/finansbank.png',
    'FINANSBANK AS.' => '/monke/assets/banka/finansbank.png',
    'QNB FINANSBANK A.S' => '/monke/assets/banka/finansbank.png',
    
    //IS BANKASI
    'TURKIYE IS BANKASI, A.S.' => '/monke/assets/banka/turkiyeisbankasi.png',
    'TURKIYE IS BANKASI A.S.' => '/monke/assets/banka/turkiyeisbankasi.png',

    //AKBANK
    'AKBANK T.A.S.' => '/monke/assets/banka/akbank.png',

    //VAKIFBANK
    'TURKIYE VAKIFLAR BANKASI T.A.O.' => '/monke/assets/banka/vakifbank.png',
    'TURKIYE VAKIFLAR BANKASI T. A. O.' => '/monke/assets/banka/vakifbank.png',
    'TURKIYE VAKIFLAR BANKASI T.A.O' => '/monke/assets/banka/vakifbank.png',
    'VAKIFBANK' => '/monke/assets/banka/vakifbank.png',
    'VAKIF BANK' => '/monke/assets/banka/vakifbank.png',

    //ZIRAAT BANKASI
    'T.C. ZIRAAT BANKASI, A.S.' => '/monke/assets/banka/ziraatbankasi.png',
    'T.C. ZIRAAT BANKASI A.S.' => '/monke/assets/banka/ziraatbankasi.png',

    //HALK BANKASI
    'T. HALK BANKASI, A.S.' => '/monke/assets/banka/halkbank.png',
    'T. HALK BANKASI A.S.' => '/monke/assets/banka/halkbank.png',
    'TURKIYE HALK BANKASI, A.S.' => '/monke/assets/banka/halkbank.png',
    'TURKIYE HALK BANKASI A.S.' => '/monke/assets/banka/halkbank.png',

    //DENIZBANK
    'DENIZBANK' => '/monke/assets/banka/denizbank.png',
    'DENIZBANK A.S.' => '/monke/assets/banka/denizbank.png',
    'DENIZBANK, A.S.' => '/monke/assets/banka/denizbank.png',

    //YAPI KREDI
    'YAPI KREDI' => '/monke/assets/banka/yapikredi.png',
    'YAPI VE KREDI BANKASI A.S.' => '/monke/assets/banka/yapikredi.png',
    'YAPI VE KREDI BANKASI, A.S.' => '/monke/assets/banka/yapikredi.png',

    //TEB
    'TURK EKONOMI BANKASI, A.S.' => '/monke/assets/banka/teb.png',
    'TURK EKONOMI BANKASI A.S.' => '/monke/assets/banka/teb.png',

    //ANADOLUBANK
    'ANADOLUBANK, A.S.' => '/monke/assets/banka/anadolubank.png',
    'ANADOLUBANK A.S.' => '/monke/assets/banka/anadolubank.png',

    //HSBC BANK
    'HSBC' => '/monke/assets/banka/hsbc.png',
    'HSBC BANK, A.S.' => '/monke/assets/banka/hsbc.png',
    'HSBC BANK A.S.' => '/monke/assets/banka/hsbc.png',
    'HSBC BANK' => '/monke/assets/banka/hsbc.png',

    //ALBARAKA TURK
    'ALBARAKA TURK KATILIM BANKASI, A.S.' => '/monke/assets/banka/albarakaturk.png',
    'ALBARAKA TURK KATILIM BANKASI A.S.' => '/monke/assets/banka/albarakaturk.png',

    //FIBABANKA
    'FIBABANKA, A.S.' => '/monke/assets/banka/fibabanka.png',
    'FIBABANKA A.S.' => '/monke/assets/banka/fibabanka.png',

    //GARANTI
    'TURKIYE GARANTI BANKASI' => '/monke/assets/banka/garanti.png',
    'TURKIYE GARANTI BANKASI A. S.' => '/monke/assets/banka/garanti.png',
    'TURKIYE GARANTI BANKASI A.S.' => '/monke/assets/banka/garanti.png',
    'TURKIYE GARANTI BANKASI, A.S.' => '/monke/assets/banka/garanti.png',

    //TURKIYE FINANS
    'TURKIYE FINANS KATILIM BANKASI' => '/monke/assets/banka/turkiyefinans.png',
    'TURKIYE FINANS KATILIM BANKASI A.S.' => '/monke/assets/banka/turkiyefinans.png',
    'TURKIYE FINANS KATILIM BANKASI, A.S.' => '/monke/assets/banka/turkiyefinans.png',

    //DIGER
    'ODEA BANK A.S.' => '/monke/assets/banka/odeabank.png',
    'PAPARA ELEKTRONIK PARA VE ODEME' => '/monke/assets/banka/papara.png',
    'SEKERBANK T.A.S.' => '/monke/assets/banka/sekerbank.png',
    'VAKIF KATILIM BANKASI' => '/monke/assets/banka/vakifkatilim.png',
    'BULUNAMADI' => '/monke/assets/banka/a101.svg',

);

$marka_logo = array(
    'MASTERCARD' => '/monke/assets/banka/logo_mastercard.png',
    'VISA' => '/monke/assets/banka/logo_visa.png',
    'BULUNAMADI' => '/monke/assets/banka/go.png', 
);

$push_bank = json_encode($banka_logo);
$push_brand = json_encode($marka_logo);

$banka = json_decode($push_bank, true);
$marka = json_decode($push_brand, true);

?>