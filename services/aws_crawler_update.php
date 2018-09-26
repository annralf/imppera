<?php
include '/var/www/html/enkargo/config/aws_crawler.php';
require_once '/var/www/html/enkargo/config/pdo_connector.php';
/*#
Function Name: Update AWS items from Crawler function
Author: Ana Guere
Date: 10/01/2018
#*/

$conn = new DataBase();


$hora_actual = strtotime(date("H:i"));
$hora_limite = strtotime( "23:00" );

while ($hora_actual < $hora_limite) { 

	#******************************************************* DataBase **********************************************************
	$array = array();
	#SQL sentence to items update at aws.items table
	$query = $conn->prepare("select id,sku,product_title_english,specification_english from aws.items where specification_spanish is null or title_spanish is null and bolborrado not in (1,5) limit 10000;");
	#$query = $conn->prepare("select id,sku,product_title_english,specification_english from aws.items where sku in ('B07997WDQN','B072C36ZVK','B071L6HC52')");

	$query->execute();
	$k = 0;
	#Start Crawler funcion
	$crawler = new Amazon();
	
	print_r($crawler->crawler("https://www.amazon.com/dp/B00IA5LSH6", "B00IA5LSH6", "23456"));
	die();

	echo "Inicio traductor\n";

	$campo_description_es	=(string)" when '3074144' then '' ";
	$campo_titulo_es 		=(string)" when '3074144' then '' ";
	$id_t					='';

	foreach ($query->fetchAll() as $result) {
		$url = "https://translate.google.com/?hl=&langpair=en|es&text=".urlencode(substr($result['specification_english'],0,4600)."~~~^~~~".substr($result['product_title_english'],0,100));
		#echo $url."\n\n\n";

		$aws_result = $crawler->crawler_translate($url, $result['sku'],$result['id']);
		

		switch ($aws_result['notavaliable']) {
			case 0:
			#print_r($aws_result);
						#Item avaliable at AWS
			echo $k." - ".$aws_result['sku']." --- traducido \n";

			$especification_spanish      	= pg_escape_string($aws_result['text']);
			$titulo      					= pg_escape_string($aws_result['titulo']);
			$id      					 	= $aws_result['id'];
			#Items boborrado 5 means it comes from crawler update
			$campo_description_es 	.=(string)" when ".$id." then '".$especification_spanish."'";
			$campo_titulo_es	 	.=(string)" when ".$id." then '".$titulo."'";
			$id_t .= $id.",";

			#$conn->exec("UPDATE aws.items SET specification_spanish = '".$especification_spanish."', title_spanish='".$titulo."' WHERE id = '".$id."';");
			break;
			case 1:
						#Item with hide price
			$update_date = date('Y-m-d H:i:s');
			$sku         = $aws_result['sku'];
			echo $k." - ".$sku." --- no traducido\n";
			break;
		}
		$k++;
		#sleep(1);
		#usleep(500000);
	}


		$mySkl = substr($id_t, 0, -1);

		$sql 	=(string)"update aws.items SET 
		specification_spanish = (CASE id ".$campo_description_es ." END), 
		title_spanish = (CASE id ".$campo_titulo_es." END)
		WHERE id in (".$mySkl.") and bolborrado not in (1);";

		$conn->exec($sql);
		$hora_actual = strtotime(date("H:i"));
}

echo "hora limite de proceso alcanzada\n";
$conn->close_con();