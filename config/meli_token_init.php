<?php
include "meli.php";
include "conex_manager.php";

$conn       = new Connect();
$shop_query = pg_query("SELECT * FROM meli.shop WHERE id = 3;");
while ($shop = pg_fetch_object($shop_query)) {
	$meli = new Meli($shop->application_id, $shop->secret_key, $shop->access_token, $shop->refresh_access_token);
	if (!$_GET['code']) {
		header("Location:".$meli->getAuthUrl('https://core.enkargo.com.co/config/meli_token_init.php', Meli::$AUTH_URL['MLM']));
	} else {
		#Activación inicial del access token
		$user = $meli->authorize($_GET['code'], 'https://core.enkargo.com.co/config/meli_token_init.php');
		#$user               = $meli->refreshAccessToken();
		$access_token       = $user['body']->access_token;
		$refresh_token      = $user['body']->refresh_token;
		$time_refresh_token = time()+$refresh['body']->expires_in;
		$shop_query         = pg_query("UPDATE meli.shop SET access_token = '".$access_token."', refresh_access_token = '".$refresh_token."' WHERE id = '".$shop->id."'");
		print_r($user);

	}

}
