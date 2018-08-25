<?php
include '/var/www/html/enkargo/config/meli_items.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';
$status = 2;
$shop = 2;
$order = "1705212823";
$conn   = new DataBase();
$result = $conn->prepare("select * from meli.shop where id = '".$shop."';");
$result->execute();
$result = $result->fetch();
$meli_items = new items($result['access_token']);
$orders = $conn->prepare("select * from system.orders where autorice <> 'R' limit 10;");
$orders->execute();
$orders = $orders->fetchAll();
#Remover al llevar a producción
echo $meli_items->send_message($status, $shop, $order, $result['access_token'], $result['name'], $result['user_name']);die();
foreach ($orders as $key) {
	$order = $key['id_order'];
	switch ($key['autorice']) {
		case 'G':
		$status = 1;
		break;
		case 'C':
		$status = 2;
		break;
		case 'B':
		$status = 3;
		break;
	}
	echo $meli_items->send_message($status, $shop, $order, $result['access_token'], $result['name'], $result['user_name']);
}

?>
