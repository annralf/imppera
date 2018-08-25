<?php
include "/var/www/html/enkargo/services/cbt_items_test.php";

$conn        = new DataBase();
$create_item = new cbt_create(4);

#Getting secuences info
$secuence = $conn->prepare("SELECT * FROM cbt.secuences WHERE type = 'createcbt_qb';");
$secuence->execute();
$secuence = $secuence->fetchObject();
$offset          = $secuence->offset_+600;
$secuence_update = $conn->prepare("UPDATE cbt.secuences SET offset_ ='".$offset."' WHERE type = 'createcbt_qb';");
$secuence_update->execute();
echo "Inicio de carga CBT\n";
#$items = $conn->prepare("SELECT * from aws.items where id in (select id from aws.view_create_cbt_ml where upper(brand) like upper('Under Armour')) offset '".$secuence->offset_."' limit 1000;");
$items = $conn->prepare("SELECT * from aws.items where id in (542174,2904605);");
$items->execute();
$j = 1;
while ($i = $items->fetchObject()) {
	echo $j." - ";
	$create_item->create("local", $i, null);
	$j++;
}
