<?php
require "mp_items.php";
include "conex_manager.php";

$conn       = new Connect();
$shop_query = pg_query("SELECT * FROM melishop.shop where id <> 3;");

while ($shop = pg_fetch_object($shop_query)) {
	echo $shop->id."\n";
	$client_id     = $shop->application_id,
	$client_secret = $shop->secret_key,
	$mp = new MP ($client_id,$client_secret);
	$access_token = $mp->get_access_token();
		
	if ($access_token != NULL) {
		$sql    = "UPDATE melishop.shop SET access_token='".$access_token."', update_date= '".date('Y-m-d H:i:s')."' WHERE id ='".$shop->id."';";
		$update = pg_query($sql);
		echo $update;
	}
}
?>