<?php
include '/var/www/html/enkargo/services/mp_balanc.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';

$test = new MPbalance(1);
$conn = new DataBase();

$sql="select id,id_payments from system.orders where shop_id=1 and id not in (select id_order from system.mp);";
$item = $conn->prepare($sql);
$item->execute();
$item = $item->fetchAll();

$i=1;
foreach ($item as $items) {
	echo $i;
	$valor = $test->pay_by_id($items['id_payments'],$items['id']);
	$i++;
}

$sql2="select o.id_payments,mp.id,mp.status from system.orders o join system.mp mp on o.id=mp.id_order where o.shop_id=1 and mp.date_created > (CURRENT_DATE - INTERVAL '15 days');";
$item2 = $conn->prepare($sql2);
$item2->execute();
$item2 = $item2->fetchAll();

$i=1;
foreach ($item2 as $items2) {
	echo $i;
	$valor = $test->check_pay_by_id($items2['id_payments'],$items2['id'],$items2['status']);
	$i++;
}