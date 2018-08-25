<?php
include "meli.php";
include "conex_manager.php";

$conn       = new Connect();
$shop_query = pg_query("SELECT * FROM meli.shop where id <> 3;");

while ($shop = pg_fetch_object($shop_query)) {
	echo $shop->id."\n";
	$params = array(
		'grant_type'    => 'refresh_token',
		'client_id'     => $shop->application_id,
		'client_secret' => $shop->secret_key,
		'refresh_token' => $shop->refresh_access_token
	);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.mercadolibre.com/oauth/token');
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	$response = json_decode(curl_exec($ch));
	curl_close($ch);
	print_r($response);
	if ($response->access_token != NULL || $response->refresh_token != NULL) {
		$sql    = "UPDATE meli.shop  SET access_token='".$response->access_token."', refresh_access_token='".$response->refresh_token."', update_date= '".date('Y-m-d H:i:s')."' WHERE id ='".$shop->id."';";
		$update = pg_query($sql);
		echo $update;
	}
}
