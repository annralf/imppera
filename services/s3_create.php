<?php
include '/var/www/html/enkargo/config/s3_item.php';
require_once '/var/www/html/enkargo/config/pdo_connector.php';

/*#
Function Name: create massive S3_items from Semantics3 service
Author: Rafael alvarez
Date: 07/11/2017
Detail: This funtion get s3_item.php functionsto connect to semantics3 source and get all item detail about
#*/


$s3  = new Semantics3();
$conn = new DataBase();
$offset    = 0; #offset

$array = array();

#$key = array("rubik","toaster","oven","rice cooker","griddle","cooking pot","powerbank","hard drive","BLACK+DECKER","Coffee Maker","Acoustic Guitar","router","Switches","towel set","Steam Iron","Full Face Helmet","Motorcycle Goggles","tote bag","Luggage","umbrella","BACKPACKS");
#$key = array("Acoustic Guitar","router","Switches","towel set","Steam Iron","Full Face Helmet","Motorcycle Goggles","tote bag","Luggage","umbrella","BACKPACKS");
$key = $conn->prepare("select distinct brand from aws.items;");
$key->execute();
$conn->close_con();
$j = 1;
$i = 1;

echo "inicio de busqueda de upc\n";
foreach ($key as $k) {

	$offset    = 0; 
	$i = 1;
	$canridad =2000;
	echo $j."-".$k['brand']."- cantidad:".$canridad."-".date('Y-m-d H:i:s')."\n";
	while ( $offset < $canridad) {
		#$conn->beginTransaction();
		$palabra=trim($k['brand']);
		foreach ($s3->search_offset($palabra,$offset) as $s3_result) {
				$upc = strtoupper($s3_result['upc']);
				$sku = strtoupper($s3_result['sku']);
				$url = strtoupper($s3_result['url']);
				$title = strtoupper($s3_result['title']);
				#echo $i."-".$upc."-".date('Y-m-d H:i:s')."\n";
				if($s3_result['seller']=='amazon.com'){
				$key = $conn->prepare("select sku from aws.items where sku = '".$sku."';");
				$key->execute();
				$key = $key->fetch();
				if (!isset($key[0])) {
						$conn->exec("insert into aws.items (sku,url,product_title_english, ean, create_date) values ('".$sku."','".$url."','".$title."','".$upc."','".date('Y-m-d H:i:s')."');");
						echo $j."-".$upc."-".$sku."-".date("Y-m-d H:i:s")."\n";
						$j++;
					}
				}
				$i++;
		
		#print_r($resultado);
		}		
		$offset=$offset+10;	
	}
	
$j++;
}

