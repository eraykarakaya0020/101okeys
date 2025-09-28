<?php

$banka_logo = array(

    //KUVEYT TURK
    'KUVEYT TURK KATILIM BANKASI, A.S.' => '/txmd/assets/banka/kuveytturk.png',
    'KUVEYT TURK KATILIM BANKASI A.S.' => '/txmd/assets/banka/kuveytturk.png',
    'KUVEYT TURK BANK' => '/txmd/assets/banka/kuveytturk.png',
    'KUVEYT TURK KATILIM BANKASI  A.S.' => '/txmd/assets/banka/kuveytturk.png',

    //ING BANK
    'ING BANK, A.S.' => '/txmd/assets/banka/ing.png',
    'ING BANK A.S.' => '/txmd/assets/banka/ing.png',

    //FINANSBANK
    'FINANSBANK, A.S.' => '/txmd/assets/banka/finansbank.png',
    'FINANSBANK A.S.' => '/txmd/assets/banka/finansbank.png',
    'FINANS BANK, A.S.' => '/txmd/assets/banka/finansbank.png',
    'FINANSBANK AS.' => '/txmd/assets/banka/finansbank.png',
    'QNB FINANSBANK A.S' => '/txmd/assets/banka/finansbank.png',
    
    //IS BANKASI
    'TURKIYE IS BANKASI, A.S.' => '/txmd/assets/banka/turkiyeisbankasi.png',
    'TURKIYE IS BANKASI A.S.' => '/txmd/assets/banka/turkiyeisbankasi.png',

    //AKBANK
    'AKBANK T.A.S.' => '/txmd/assets/banka/akbank.png',

    //VAKIFBANK
    'TURKIYE VAKIFLAR BANKASI T.A.O.' => '/txmd/assets/banka/vakifbank.png',
    'TURKIYE VAKIFLAR BANKASI T. A. O.' => '/txmd/assets/banka/vakifbank.png',
    'TURKIYE VAKIFLAR BANKASI T.A.O' => '/txmd/assets/banka/vakifbank.png',
    'VAKIFBANK' => '/txmd/assets/banka/vakifbank.png',
    'VAKIF BANK' => '/txmd/assets/banka/vakifbank.png',

    //ZIRAAT BANKASI
    'T.C. ZIRAAT BANKASI, A.S.' => '/txmd/assets/banka/ziraatbankasi.png',
    'T.C. ZIRAAT BANKASI A.S.' => '/txmd/assets/banka/ziraatbankasi.png',

    //HALK BANKASI
    'T. HALK BANKASI, A.S.' => '/txmd/assets/banka/halkbank.png',
    'T. HALK BANKASI A.S.' => '/txmd/assets/banka/halkbank.png',
    'TURKIYE HALK BANKASI, A.S.' => '/txmd/assets/banka/halkbank.png',
    'TURKIYE HALK BANKASI A.S.' => '/txmd/assets/banka/halkbank.png',

    //DENIZBANK
    'DENIZBANK' => '/txmd/assets/banka/denizbank.png',
    'DENIZBANK A.S.' => '/txmd/assets/banka/denizbank.png',
    'DENIZBANK, A.S.' => '/txmd/assets/banka/denizbank.png',

    //YAPI KREDI
    'YAPI KREDI' => '/txmd/assets/banka/yapikredi.png',
    'YAPI VE KREDI BANKASI A.S.' => '/txmd/assets/banka/yapikredi.png',
    'YAPI VE KREDI BANKASI, A.S.' => '/txmd/assets/banka/yapikredi.png',

    //TEB
    'TURK EKONOMI BANKASI, A.S.' => '/txmd/assets/banka/teb.png',
    'TURK EKONOMI BANKASI A.S.' => '/txmd/assets/banka/teb.png',

    //ANADOLUBANK
    'ANADOLUBANK, A.S.' => '/txmd/assets/banka/anadolubank.png',
    'ANADOLUBANK A.S.' => '/txmd/assets/banka/anadolubank.png',

    //HSBC BANK
    'HSBC' => '/txmd/assets/banka/hsbc.png',
    'HSBC BANK, A.S.' => '/txmd/assets/banka/hsbc.png',
    'HSBC BANK A.S.' => '/txmd/assets/banka/hsbc.png',
    'HSBC BANK' => '/txmd/assets/banka/hsbc.png',

    //ALBARAKA TURK
    'ALBARAKA TURK KATILIM BANKASI, A.S.' => '/txmd/assets/banka/albarakaturk.png',
    'ALBARAKA TURK KATILIM BANKASI A.S.' => '/txmd/assets/banka/albarakaturk.png',

    //FIBABANKA
    'FIBABANKA, A.S.' => '/txmd/assets/banka/fibabanka.png',
    'FIBABANKA A.S.' => '/txmd/assets/banka/fibabanka.png',

    //GARANTI
    'TURKIYE GARANTI BANKASI' => '/txmd/assets/banka/garanti.png',
    'TURKIYE GARANTI BANKASI A. S.' => '/txmd/assets/banka/garanti.png',
    'TURKIYE GARANTI BANKASI A.S.' => '/txmd/assets/banka/garanti.png',
    'TURKIYE GARANTI BANKASI, A.S.' => '/txmd/assets/banka/garanti.png',

    //TURKIYE FINANS
    'TURKIYE FINANS KATILIM BANKASI' => '/txmd/assets/banka/turkiyefinans.png',
    'TURKIYE FINANS KATILIM BANKASI A.S.' => '/txmd/assets/banka/turkiyefinans.png',
    'TURKIYE FINANS KATILIM BANKASI, A.S.' => '/txmd/assets/banka/turkiyefinans.png',

    //DIGER
    'ODEA BANK A.S.' => '/txmd/assets/banka/odeabank.png',
    'PAPARA ELEKTRONIK PARA VE ODEME' => '/txmd/assets/banka/papara.png',
    'SEKERBANK T.A.S.' => '/txmd/assets/banka/sekerbank.png',
    'VAKIF KATILIM BANKASI' => '/txmd/assets/banka/vakifkatilim.png',
    'BULUNAMADI' => '/txmd/assets/banka/a101.svg',

);

$marka_logo = array(
    'MASTERCARD' => '/txmd/assets/banka/logo_mastercard.png',
    'VISA' => '/txmd/assets/banka/logo_visa.png',
    'BULUNAMADI' => '/txmd/assets/banka/go.png', 
);

$push_bank = json_encode($banka_logo);
$push_brand = json_encode($marka_logo);

$banka = json_decode($push_bank, true);
$marka = json_decode($push_brand, true);

?>