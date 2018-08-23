<?php
include '/var/www/html/enkargo/config/meli_items.php';
/*
CREATED AT 20/07/2017
BY Ana Rafaela Guere
Funtion to get MELI items details from postgreSQL database
 */
$conn        = new DataBase();
$i           = 1;
$application = $conn->prepare("SELECT * FROM meli.shop WHERE id = '2';");
$application->execute();
$application = $application->fetchAll();
$meli_item   = new items($application[0]['access_token'],$this->shop[0]['user_name']);
$categories = $conn->prepare("SELECT child_category,id FROM meli.category_sub;");
$categories->execute();
$categories = (object) $categories->fetchAll();
/*foreach ($categories as $key) {
	$category_detail = $meli_item->validateCategory($key['child_category']);
	if(in_array("buy_it_now", $category_detail->settings->buying_modes)){
		echo $i."-".$key['child_category']."-valido\n";
		$i++;
	}else{
		echo $i."-".$key['child_category']."-para marcar\n";
		$i++;
		$conn->exec("update meli.category_sub set bolborrado = '1' where id='".$key['id']."';");
	}
}*/

$prueba 			= $meli_item->getCategoriesPredictor("zapatos rojos");
$category           = $meli_item->validateCategory($prueba);
echo $prueba."\n";
print_r($category);
$conn->close_con();