<?php
include "/var/www/html/enkargo/services/cbt_items.php";
include_once '/var/www/html/enkargo/config/pdo_connector.php';

$conn        = new DataBase();
$create_item = new cbt_create(3);

#Getting secuences info
$secuence = $conn->prepare("SELECT * FROM cbt.secuences WHERE type = 'createcbt_ml';");
$secuence->execute();
$secuence = $secuence->fetchObject();
$offset          = $secuence->offset_+600;
$secuence_update = $conn->prepare("UPDATE cbt.secuences SET offset_ ='".$offset."' WHERE type = 'createcbt_ml';");
$secuence_update->execute();
echo "Inicio de carga CBT\n";
$sql="SELECT * from aws.items where id in (select id from aws.view_create_cbt_ml where category_p in ('Appliances','Automotive','Cell Phones & Accessories','Clothing','Clothing, Shoes & Jewelry','Electronics','Games & Arcade','Home & Kitchen','Musical Instruments','Patio, Lawn & Garden','Shoes','Sports & Outdoors','Video Games','Watches') order by random()) offset '".$offset."' limit 1000;";
$items = $conn->prepare($sql);
$items = $conn->prepare("SELECT * from aws.items where id=2985798;");
$items->execute();
$j = 1;
while ($i = $items->fetchObject()) {
	echo $j." - ";
	$create_item->create("local", $i, null);
	$j++;
}