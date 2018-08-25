<?php
include '/var/www/html/enkargo/services/aws_update_simple.php';
$conn = new Database();

echo "reset aws - ".date("Y-m-d H:i:s")."\n";
$set_get = $conn->prepare("update aws.items set bolborrado = 0 where bolborrado not in (1,5);");
$set_get->execute();
$conn->close_con();

for ($n=11;$n<16;$n++){
	echo "update aws in ".$n." - ".date("Y-m-d H:i:s")."\n";
	$set_get = $conn->prepare("update aws.items set bolborrado = ".$n." where id in (select id from aws.items where bolborrado = 0 order by update_date asc limit 255000);");
	$set_get->execute();
	$conn->close_con();

}
echo "end reset ".date("Y-m-d H:i:s");