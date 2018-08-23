<?php
include "/var/www/html/enkargo/services/cbt_items.php";

$items = array();
$conn  = new Connect();

$secuence = pg_query("SELECT * FROM cbt.secuences WHERE id = '3';");
$secuence        = pg_fetch_object($secuence);
$offset          = $secuence->offset_+10000;
pg_query("UPDATE cbt.secuences SET offset_ ='".$offset."' WHERE id = '3';");

$items = pg_query("SELECT a.*, c.mpid FROM aws.items AS a JOIN cbt.items AS c ON c.aws_id = a.id where c.shop_id = 3 order by c.update_date asc offset ".$secuence->offset_." limit 10000;");
$j     = 1;
while ($i = pg_fetch_array($items)) {
	$create_item = new cbt_create(3);
	$item_detail = json_decode($create_item->update(null, null, null, null, null, $i, "local", null));
	echo $j." - ".date('y-m-d H:i:s')." - ".$i['mpid']." - ".$i['sku']." - precio: ".$item_detail->sale_price." - ".$i['sale_price']." - quantity: ".$item_detail->quantity." - active: ".$i['active']."\n";
	$j++;
}