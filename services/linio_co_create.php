<?php
include '/var/www/html/enkargo/config/linio_items.php';
$file  = file("/var/www/html/enkargo/docs/missing_image.csv");
$items = array();
$temp  = array();
foreach ($file as $line) {
	$temp = str_getcsv($line);
	array_push($items, $temp[0]);
}
$linio_items = new items("co", '1');
$offset      = 0;
$limit       = 1000;
for ($k = 0; $k < count($items); $k++) {
	#----------------------------------------- Seleccion de items a cargar ------------------------------------------------------
	#$query   = pg_query("select distinct(aws.*), li.title, li.shop_description as short_description from aws.items aws join linio.items li on aws.id=li.aws_id where aws.bolborrado=5 and image_url is not null offset '".$offset."' limit 1000;");
	#$query   = pg_query("select a.*, a.product_title_english as title, a.specification_english as short_description from aws.items as a join meli.items as m on m.aws_id = a.id where m.template = 5 and a.product_type = 'Toy'and a.product_title_english <> 'N/A' and a.product_title_english is not null");
	$query   = pg_query("select a.*, a.product_title_english as title, a.specification_english as short_description from aws.items as a join meli.items as m on m.aws_id = a.id where a.encrypt = '".$items[$k]."'");
	$request = "";
	$images  = "";
	$i       = 1;

	#------------------------------------ Carga de descripcion del producto -----------------------------------------------------
	/*
	while ($item = pg_fetch_object($query)) {
	echo $i."-".$item->sku."-".trim($item->encrypt)."-".date('Y-m-d H:i:s')."\n";
	$title       = (isset($item->title))?$item->title:null;
	$description = (isset($item->short_description))?$item->short_description:null;
	$request .= $linio_items->product_create($item->id, $item->sku, $title, $description, $item->specification_english, $item->sale_price, $item->package_weight, $item->product_type, $item->product_category, $item->ean, $item->upc, trim($item->encrypt), $item->item_width, $item->package_length, $item->package_height, false);
	$i++;
	}
	print_r($linio_items->send_product_create($request));
	die();
	#$offset += 1000;
	#sleep(120);
	 */
	#------------------------------------ Carga de imágenes del producto -------------------------------------------------------
	while ($item = pg_fetch_object($query)) {
		echo $i."-".$item->sku."-".$item->encrypt."-".date('Y-m-d H:i:s')."\n";
		$images .= $linio_items->image_create($item->sku, $item->encrypt, $item->image_url);
		$i++;
	}
	$linio_items->send_image($images);
	sleep(10);
	#----------------------------------------- Eliminar producto en Linio -------------------------------------------------------
	/*
$request = "";
while ($item = pg_fetch_object($query)) {
echo $i."-".$item->sku."-".$item->encrypt."-".date('Y-m-d H:i:s')."\n";
$request .= $linio_items->product_remove($item->encrypt);
$i++;
}
print_r($linio_items->send_remove($request));
sleep(120);
 */
}