<?php
include '/var/www/html/enkargo/config/aws_item.php';
require_once '/var/www/html/enkargo/config/pdo_connector.php';
/*#
Function Name: Update massive o AWS items from AWS service
Author: Ana Guere
Date: 13/07/2017
Detail: This funtion get aws_item.php functionsto connect to AWS source and get all item detail about
#*/

$aws  = new amazonManager('AKIAJEBTOLCUCVUCN4MQ','ZN5cqxT8O+eSkuLfuX1ZS3TEuA5RmJbgfbmOOvyo','Karengonza10-20');	
$conn = new DataBase();
$k    = 1;

#********************************************************* Log options *********************************************************
$array = array();

$search_index = array("All","Appliances","ArtsAndCrafts","Automotive","Baby","Beauty","Blended","Books","Collectibles","Electronics","Fashion","FashionBaby","FashionBoys","FashionGirls","FashionMen","FashionWomen","GiftCards","Grocery","HealthPersonalCare","HomeGarden","Industrial","KindleStore","LawnAndGarden","Luggage","MP3Downloads","Magazines","Merchants","MobileApps","Movies","Music","MusicalInstruments","OfficeProducts","PCHardware","PetSupplies","Software","SportingGoods","Tools","Toys","UnboxVideo","VideoGames","Wine","Wireless");

$conn->close_con();
$j = 1;

for ($i = 0; $i < count($search_index); $i++) {
	$key = $conn->prepare("select a.* from (select distinct brand from aws.items order by brand desc)a order by random();");
	$key->execute();	
	$quantity = 0;
	foreach ($key as $k) {
		$keywords = 'prime,'.trim($k['brand']);
		#$keywords = 'prime,'.trim($k);
		$quantity = $aws->main_search($search_index[$i], $keywords);
		$array    = array();
		if ($quantity > 5) {
			$quantity = 5;
		}
		echo "begin transaction for ".$search_index[$i]." - ".$keywords." - quantity: ".$quantity."\n";
		$conn->beginTransaction();
		for ($y = 1; $y <= $quantity; $y++) {
			foreach ($aws->item_search($search_index[$i], $keywords, $y) as $aws_result) {
				$sku = strtoupper($aws_result['asin']);
				$key = $conn->prepare("select upper(sku) as sku from aws.items where sku = '".$sku."';");
				$key->execute();
				$key = $key->fetch();
				if (!isset($key[0])) {
					$conn->exec("insert into aws.items (sku, create_date,update_date) values ('".$sku."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."');");
					echo $j." \t- ".$sku." - insertado - ".date("Y-m-d H:i:s")."\n";
					$j++;
				}
			}
			sleep(1);
		}
		$conn->commit();
		echo "end commit\n";
		$conn->close_con();
	}
}
$conn->close_con();