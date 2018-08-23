<?php
#---------------------------------------------------------------------------------------------------
#------------------------------ crear serializacion de sku Amazon ----------------------------------
#---------------------------------------------------------------------------------------------------

include '/var/www/html/enkargo/config/aws_item.php';
include '/var/www/html/enkargo/config/conex_manager.php';
include '/var/www/html/enkargo/config/encriptar.php';

$aws     = new amazonManager();
$conn    = new Connect();
$k       = 1;
$array   = array();
$encrypt = '';
$decr    = '';
$llave   = file_get_contents("/var/www/html/enkargo/config/key");

#Sentencia SQL para busquedas de sku
#$query = pg_query("select aws.sku from aws.items as aws where encrypt is null order by aws.sku asc;");
$query = pg_query("select a.sku from aws.items as a where a.product_type = 'Toy' and a.product_title_english <> 'N/A' and a.product_title_english is not null and a.create_date > '2017-10-07 00:00:00';");

$sql_update = "UPDATE aws.items SET encrypt = $1 WHERE sku = $2";

pg_prepare($conn->conn, "my_query", $sql_update);

while ($result = pg_fetch_object($query)) {

	$sku     = $result->sku;
	$encrypt = ltrim(encryptar($sku, $llave));

	$decr = decryptar($encrypt, $llave);

	pg_execute($conn->conn, "my_query", array($encrypt, $sku));

	echo $k."-".$sku."---".$encrypt."---".$decr."\n";
	$array = array();
	$k++;
}
$conn->close();