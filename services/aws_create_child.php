<?php
include '/var/www/html/enkargo/config/aws_item.php';
require_once '/var/www/html/enkargo/config/pdo_connector.php';
/*#
Function Name: Update massive o AWS items from AWS service
Author: rafael alvarez
Date: 05/04/2014
Detail: This funtion get aws_item.php functionsto connect to AWS source and get all item detail about
#*/

$aws  = new amazonManager('AKIAIRFL65AVBR5EHS2Q','1+vkYHjFmvDdM1bFm1hv+QW9Pyzo/yaFeWXtDSyJ','Karengonza10-20');
$conn = new DataBase();
$k    = 1;

#********************************************************* Log options *********************************************************
$array = array();

#Sentencia SQL para busquedas de sku hijos
echo "begin transaction ------------------------------------\n";
$x=1;

$hora_actual = strtotime(date("H:i"));
$hora_limite = strtotime( "22:00" );

while ($hora_actual < $hora_limite) { 
	$key = $conn->prepare("select sku_padre from aws.items_sku_padre where status='f' limit 50;");
	$key->execute();	
	$conn->close_con();	
	foreach ($key as $k) {
		$sku_padre = $k['sku_padre'];
		echo $x."- Sku Padre: ".$sku_padre."\n";
		$conn->beginTransaction();
		$j=1;
		foreach ($aws->search_child($sku_padre) as $aws_result) {		
			if(isset($aws_result['notavaliable'])){
				echo "No hay hijos \n";
			}else{
				$sku = strtoupper($aws_result['asin']);
				$sql = $conn->prepare("select upper(sku) as sku from aws.items where sku = '".$sku."';");
				$sql->execute();
				$sql = $sql->fetch();
				if (!isset($sql[0])) {
					$conn->exec("insert into aws.items (sku, create_date) values ('".$sku."','".date("Y-m-d H:i:s")."');");
					echo "\t".$j."- ".$sku." - creado 	".date("Y-m-d H:i:s")."\n";	
				}else{
					#echo "\t".$j."- ".$sku." - ya existe\n";	
				}
			}
			$j++;
			sleep(1);
		}
		$conn->exec("update aws.items_sku_padre set status='t' where sku_padre='".$sku_padre."';");
		$conn->commit();
		$conn->close_con();
		$x++;
	}
	echo "end commit\n";
	$hora_actual = strtotime(date("H:i"));
	$conn->close_con();
}

echo "hora limite de proceso alcanzada\n";
$conn->close_con();
