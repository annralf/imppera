<?php
include '/var/www/html/enkargo/services/aws_update_simple.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';
$conn = new Database();

echo "reset meli - ".date("Y-m-d H:i:s")."\n";
$set_get = $conn->prepare("update meli.items set bolborrado = 0 where bolborrado not in (1,3,9,4);");
$set_get->execute();
$conn->close_con();

for ($n=10;$n<17;$n++){
	echo "update meli in ".$n." - ".date("Y-m-d H:i:s")."\n";
	$set_get = $conn->prepare("UPDATE meli.items set bolborrado = ".$n." where id in (select id from aws.items where bolborrado = 0 order by update_date asc limit 200000) and shop_id=2;");
	$set_get->execute();
	$conn->close_con();
}
echo "end reset ".date("Y-m-d H:i:s");
