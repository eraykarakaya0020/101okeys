<?php

$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://bin-ip-checker.p.rapidapi.com/?bin=$kart_bin",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "{\r
    \"bin\": \"$kart_bin\"\r
}",
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: bin-ip-checker.p.rapidapi.com",
		"X-RapidAPI-Key: $bincheck_api_key",
		"content-type: application/json"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	$new = json_decode($response);
	$banka = $new->BIN->issuer->name;
	$marka = $new->BIN->brand;
	$seviye = $new->BIN->level;
}

?>