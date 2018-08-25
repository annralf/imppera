<?php
include '/var/www/html/enkargo/config/aws_item.php';

require_once '/var/www/html/enkargo/config/pdo_connector.php';
/*#
Function Name: Update massive o AWS items from AWS service
Author: Rafael Alvarez
Date: 07/12/2017
#*/

$aws  = new amazonManager();
$conn = new DataBase();
$k    = 1;


$items = array();
$array = array();

#******************************************************* DataBase **********************************************************
$query = $conn->prepare("select sku from aws.items where sku='B00PUHRS0S';");
#$query = $conn->prepare("select sku from aws.items where sku='B01JK3JO5E';");
$query->execute();
$conn->close_con();
$k = 0;
foreach ($query->fetchAll() as $result) {

	$aws_result= $aws->item_search_url($result['sku']);

	#Item avaliable at AWS
	#echo $k."-".$aws_result['product_title_english']."\n";
	#$title  = pg_escape_string(utf8_encode($aws_result['product_title_english']));	
	print_r($aws_result);
	#echo $k."-".$title."\n";			

	$k++;
}
$conn->close_con();
