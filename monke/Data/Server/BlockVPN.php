<?php

$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "http://ip-api.com/json/$ip?fields=proxy,hosting",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	$json = json_decode($response);
    $proxy = $json->proxy;
    $hosting = $json->hosting;
}

?>