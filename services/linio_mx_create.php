<?php
include '/var/www/html/enkargo/config/linio_items.php';

$linio_items = new items("mx", '2');
$offset      = 3000;
$limit       = 1000;
for ($k = 0; $k < 95; $k++) {
	#----------------------------------------- Seleccion de items a cargar ------------------------------------------------------
	$query   = pg_query("select aws.*, li.title, li.shop_description as short_description from aws.items aws join linio.items li on aws.id=li.aws_id where aws.bolborrado=5 and image_url is not null and aws_id not in (select aws.id from aws.items aws join linio.items li on aws.id=li.aws_id where aws.bolborrado=5 and aws.id not in (select aws_id from linio.items where shop_id=2) and image_url is not null) offset '".$offset."' limit 1000;");
	$request = "";
	$images  = "";
	$i       = 1;

	#------------------------------------ Carga de descripcion del producto -----------------------------------------------------
	/*
	while ($item = pg_fetch_object($query)) {
		echo $i."-".$item->sku."-".trim($item->encrypt)."-".date('Y-m-d H:i:s')."\n";
		$title       = (isset($item->title))?$item->title:null;
		$description = (isset($item->short_description))?$item->short_description:null;
		$request .= $linio_items->product_create($item->id, $item->sku, $title, $description, $item->specification_english, $item->sale_price, $item->package_weight, $item->product_type, $item->product_category, $item->ean, $item->upc, trim($item->encrypt), $item->item_width, $item->package_length, $item->package_height, true);
		$i++;
	}
	print_r($linio_items->send_product_create($request));
	$offset += 1000;
	sleep(120);
	*/
	#------------------------------------ Carga de imágenes del producto -------------------------------------------------------
	while ($item = pg_fetch_object($query)) {
		echo $i."-".$item->sku."-".$item->encrypt."-".date('Y-m-d H:i:s')."\n";
		$images .= $linio_items->image_create($item->sku, $item->encrypt, $item->image_url);
		$i++;
	}
	$linio_items->send_image($images);
	sleep(120);
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