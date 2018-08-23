<?php

include "/var/www/html/enkargo/services/cbt_items.php";
$conn        = new Connect();
$create_item = new cbt_create(3);

#Getting secuences info
$secuence = pg_query("SELECT * FROM cbt.secuences WHERE type = 'createcbt';");
$secuence        = pg_fetch_object($secuence);
$offset          = $secuence->offset_+600;
$secuence_update = pg_query("UPDATE cbt.secuences SET offset_ ='".$offset."' WHERE type = 'createcbt';");

#$items       = pg_query("select * from aws.items a where a.active='t' and a.active_cbt='t' and a.is_prime=1 and a.sale_price<>0	and a.product_type<>'Toy' and a.package_weight<100 and a.package_weight<>0 and a.product_type <> '' and a.product_category  <> '' and a.image_url is not null	and a.bolborrado=0  and a.id in(select id from aws.items_to_cbt where bolborrado=0 and id is not null) offset '".$secuence->offset_."' limit '".$secuence->limit_."' ;");

$items       = pg_query("select * from aws.items a where a.sku='B014W3E6LO';");


$j           = 1;

while ($i = pg_fetch_array($items)) {
	echo $j." - ".date('y-m-d H:i:s')."-";
	$create_item->create("local", $i, null);
	$j++;
}