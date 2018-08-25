<?php
include '/var/www/html/enkargo/config/meli_items.php';
/*
CREATED AT 20/07/2017
BY Ana Rafaela Guere
Funtion to get MELI items details from postgreSQL database
 */
$conn           = new Connect();
$i              = 1;
$application    = pg_fetch_object(pg_query("SELECT * FROM meli.shop WHERE id = '2';"));
$meli_item      = new items($application->access_token);
$translate      = new GoogleTranslate();
$shop           = $application->id;
$secuence       = pg_fetch_object(pg_query("SELECT * FROM meli.secuences WHERE type = 'bannerQb';"));
$items_research = pg_query("select mpid from meli.items where shop_id=2 and template is null offset '".$secuence->offset_."' limit '".$secuence->limit_."';");
$offset         = $secuence->offset_+1000;
		pg_query("update meli.items set offset_ = '".$offset."'");
$items_manager  = new items($application->access_token);
$array          = array();
while ($items = pg_fetch_object($items_research)) {
	foreach ($items_manager->show($items->mpid) as $detail_items) {
		echo $i."-".$detail_items->id."-".date('Y-m-d H:i:s')."\n";
		$item        = pg_fetch_object(pg_query("SELECT * FROM aws.items WHERE sku = '".$detail_items->seller_custom_field."'"));
		if(isset($item->sku)){
		$images      = explode("~^~", $item->image_url);
		$heigth_pack = number_format($item->package_height/0.393701, 2);
		$width_pack  = number_format($item->item_width/0.393701, 2);
		$length_pack = number_format($item->package_length/0.393701, 2);
		$weight_pack = number_format($item->package_weight/2.204623, 2);
		$heigth_item = number_format($item->item_height/0.393701, 2);
		$width_item  = number_format($item->item_width/0.393701, 2);
		$length_item = number_format($item->item_length/0.393701, 2);
		$item_title  = $translate->translate('en', 'es', $item->product_title_english);
		if (strlen($item_title) > 61) {
			$pos        = strpos($item_title, ' ', 50);
			$item_title = substr($item_title, 0, $pos);
		}
		$item_img         = array();
		$img_1            = $images[0];
		$img_2            = (isset($images[1]))?$images[1]:"";
		$img_3            = (isset($images[2]))?$images[2]:"";
		$img_4            = (isset($images[3]))?$images[3]:"";
		$img_5            = (isset($images[4]))?$images[4]:"";
		$img_6            = (isset($images[5]))?$images[5]:"";
		$item_description = $translate->translate('en', 'es', $item->specification_english);
		$date_update      = date('Y-m-d H:i:s');
		for ($j = 0; $j < count($images); $j++) {
			array_push($item_img, array('source' => $images[$j]));
		}
		$data_description = pg_fetch_object(pg_query("SELECT des.*, tem.* FROM meli.description  AS des INNER JOIN meli.templates AS tem ON des.template_id = tem.id WHERE tem.type='1' AND tem.shop_id='".$application->id."';"));
		$description      = $data_description->template;
		eval("\$description = \"$description\";");
		$product['text'] = $description;
		$update          = $items_manager->banner($items->mpid, $product);
		pg_query("update meli.items set is_descripted = 'true', template ='".$data_description->id."'");
		$i++;
			
		}
	}
}
$conn->close();
