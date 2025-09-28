<?php
include_once(__DIR__ . '/../../config.php');

$url = $_POST['otourunlinki'];
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);
$dom = new DOMDocument;

libxml_use_internal_errors(true);
$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
libxml_clear_errors();

$xpath = new DOMXPath($dom);

$urun_baslik = $xpath->query("//h1[contains(@class, 'pr-new-br')]");
$urun_markasi1 = $xpath->query("//a[contains(@class, 'product-brand-name-with-link')]");
$urun_markasi2 = $xpath->query("//span[@class='product-brand-name-without-link']");
$urun_fiyat = $xpath->query("//div[contains(@class, 'product-price-container')]//span[contains(@class, 'prc-dsc')]");
$urun_aciklama = $xpath->query("//div[contains(@class, 'starred-attributes')]");
$urun_resim = $xpath->query("//div[contains(@class, 'base-product-image')]//img");

if($urun_markasi1->length > 0) {
    $urun_marka = $urun_markasi1[0]->textContent;
} else if($urun_markasi2->length > 0) {
    $urun_marka = $urun_markasi2[0]->textContent;
} else {
    $urun_marka = "Bulunamadı";
}

if ($urun_resim->length > 0) {
    $urun_resmi = $urun_resim[0]->getAttribute('src');
} else {
    $urun_resmi = "Bulunamadı";
}

if ($urun_aciklama->length > 0) {
    foreach ($urun_aciklama as $div) {
        $xf = $xpath->query(".//li[contains(@class, 'attribute-item')]", $div);
        $xc = $xpath->query(".//div[contains(@class, 'attributes')]", $div);

        foreach ($xf as $attribute) {
            if ($attribute instanceof DOMElement) {
                $attribute->removeAttribute('class');
                $attribute->setAttribute('style', 'display: inline-grid; border: solid #f5f5f5; background-color: #f5f5f5; padding: 8px 12px; width: 155px; border-radius: 4px;');
            }
        }
        
        foreach ($xc as $attribute) {
            if ($attribute instanceof DOMElement) {
                $attribute->removeAttribute('class');
                $attribute->setAttribute('style', 'display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px;');
            }
        }
        
        $stmt1 = $pdo->prepare('INSERT INTO urunler SET urun_adi = ?, urun_fiyati = ?, urun_resmi = ?, urun_aciklamasi=?, urun_kategorisi = ?, urun_markasi = ?');
        $stmt1->execute([ mb_convert_encoding($urun_baslik[0]->textContent, 'UTF-8', 'auto'), str_replace(['.'], '', $urun_fiyat[0]->textContent), $urun_resmi,  $dom->saveHTML($div), "Bilinmiyor", $urun_marka ]);
        if($stmt1) {
            echo("success");
            exit;
        } else {
            echo("error");
            exit;
        }
    }
}
?>
