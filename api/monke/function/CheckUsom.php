<?php

    $usom = file_get_contents("https://www.usom.gov.tr/url-list.txt");
    header('Content-Type: text/plain');
    $domain = $_POST['domain'];

    if($usom !== false AND !empty($usom)) {
        $pattern = preg_quote($domain, '/');
        $pattern = "/^.*$pattern.*\$/m";
        if (preg_match_all($pattern, $usom, $matches)) {
           echo "yasaklandi";
        } else {
           echo "yasaklanmadi";
        }
    } else {
        echo("baglanti hatasi");
    }

?>