<?php
include "/var/www/html/enkargo/services/cbt_items.php";

$create_item = new cbt_create(3);


$secuence = pg_query("SELECT * FROM cbt.secuences WHERE id = '2';");
$secuence        = pg_fetch_object($secuence);
$offset          = $secuence->offset_+2000;
pg_query("UPDATE cbt.secuences SET offset_ ='".$offset."' WHERE id = '2';");
#$mpids       = pg_query("SELECT mpid FROM cbt.items WHERE shop_id ='3' and mpid in('9006678824');");
$mpids       = pg_query("SELECT mpid FROM cbt.items WHERE shop_id ='3' order by update_date asc offset ".$secuence->offset_." limit 2000;");
$i           = 0;
while ($items = pg_fetch_object($mpids)) {
	#echo $i."-PUBLISH-".$items->mpid."\n";
	$prueba = $create_item->publish($items->mpid);

	if(!isset($prueba)){
		pg_query("UPDATE cbt.items SET status = 'published', update_date = '".date("Y-m-d H:i:s")."' WHERE mpid = '".$items->mpid."';");
		echo $i."\t- PUBLISH - ".$items->mpid." - ".date("Y-m-d H:i:s")."\n";

	}else{
		$error = $prueba->error;
		pg_query("UPDATE cbt.items SET status = '".$error[0]->code."', update_date = '".date("Y-m-d H:i:s")."' WHERE mpid = '".$items->mpid."';");
		echo $i." \t- ".$error[0]->code." - ".$items->mpid." - ".date("Y-m-d H:i:s")."\n";
	}
	$i++;
}