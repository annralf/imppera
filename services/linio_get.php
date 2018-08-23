<?php
include "../config/conex_manager.php";

$conn = new Connect();
$now  = new DateTime();

date_default_timezone_set("UTC");
$i          = 1;
$parameters = array(
	'UserID'  => 'grupoenkargo@gmail.com',
	'Version' => '1.0',
	'Action'  => 'GetProducts',
	'Format'  => 'xml',
);
$array = array();
$file  = file("../docs/linio.csv");
foreach ($file as $line) {
	$array[] = str_getcsv($line);
}
$i = 0;
foreach ($array as $item) {
	echo $i."\n";
	$aws_query = pg_fetch_object(pg_query("SELECT id FROM aws.items WHERE sku = '".$item[0]."';"));
	if ($aws_query == NULL) {
		$aws_query = pg_fetch_object(pg_query("INSERT INTO aws.items (sku) VALUES ('".$item[0]."') RETURNING id;"));
	}
	$linio = pg_fetch_object(pg_query("SELECT id FROM linio.items WHERE aws_id = '".$aws_query->id."' AND shop_id = '1';"));
	if ($linio == NULL) {
		$linio = "INSERT INTO linio.items(aws_id, shop_id) VALUES ('".$aws_query->id."', '1');";
		pg_query($linio);
		$i++;
	}
}
