<?php
include '/var/www/html/enkargo/config/meli_items.php';
/*
CREATED AT 20/07/2017
BY Ana Rafaela Guere
Funtion to get MELI items details from postgreSQL database
 */
$conn        = new Connect();
$i           = 1;
$application = pg_fetch_object(pg_query("SELECT * FROM meli.shop WHERE id = '1';"));
$meli_item   = new items($application->access_token,$application->user_name);
$translate   = new GoogleTranslate();
$shop        = $application->id;

$items_research = pg_query("select mpid from meli.items where shop_id=1 and title is null;");
$items_manager  = new items($application->access_token);
$array          = array();
$sql_update     = "UPDATE meli.items SET mpid = $1, title=$2, seller_id=$3, category_id=$4, price=$5, base_price=$6, sold_quantity=$7, start_time=$8, stop_time=$9, permalink=$10, status=$11, aws_id=$12, automatic_relist=$13, update_date=$14 WHERE mpid = $15 and shop_id = '1';";
pg_prepare($conn->conn, "my_query", $sql_update);
while ($items = pg_fetch_object($items_research)) {
	foreach ($items_manager->show($items->mpid) as $detail_items) {
		echo $i."-".$detail_items->id."-".date('Y-m-d H:i:s')."\n";
		$item = pg_fetch_object(pg_query("SELECT * FROM aws.items WHERE sku = '".$detail_items->seller_custom_field."'"));
		if (!isset($item)) {
			echo "closed\n";
			if ($status != 'closed') {
				$temp['status'] = "closed";
			}
			$temp['deleted'] = "true";
			$update          = $items_manager->update($mpid, $temp);
			$status          = "deleted";
			pg_query("DELETE FROM meli.items WHERE mpid = '".$detail_items->id."'");
		} else {
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
			$data_description = pg_fetch_object(pg_query("SELECT des.*, tem.* FROM meli.description  AS des INNER JOIN meli.templates AS tem ON des.template_id = tem.id WHERE tem.type='1' AND tem.shop_id='".$shop."';"));
			$description      = $data_description->template;
			eval("\$description = \"$description\";");
			$weight = round((($item->package_weight)/100)/2);
			$video  = pg_fetch_object(pg_query("SELECT * FROM meli.video WHERE shop_id = '".$shop."';"));
			$price  = $items_manager->liquidador($item->sale_price, $item->package_weight, $shop);

			if ($item->quantity >= 3 && $item->active == 't' && $item->sale_price < 1700 && $weight < 25) {
				echo "active\n";
				$temp['title']              = $item_title;
				$temp['price']              = $price;
				$temp['available_quantity'] = 8;
				$temp['buying_mode']        = "buy_it_now";
				$temp['pictures']           = $item_img;
				$temp['condition']          = "new";
				$temp['video_id']           = $video->url;
				if ($shop = 1) {
					$temp['warranty'] = "Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA";
				}
				$update = $items_manager->update($items->mpid, $temp);
				if (isset($update->status)) {
					if ($update->status == 400) {
						$temp                    = array();
						$temp['title']           = $item_title;
						$temp['price']           = $price;
						$temp['quantity']        = 8;
						$temp['buying_mode']     = "buy_it_now";
						$temp['listing_type_id'] = "gold_special";
						$temp['video_id']        = $video->url;
						$temp['pictures']        = $item_img;
						$temp['condition']       = "new";
						if ($shop = 1) {
							$temp['warranty'] = "Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA";
						}
						echo "update\n";
						$update = $items_manager->relist($items->mpid, $temp);
						if ($update->status != 400) {
							echo $update->id."\n";
							echo "relist\n";
							pg_execute($conn->conn, "my_query", array($update->id, $update->seller_id, $update->category_id, $update->price, $update->base_price, $update->sold_quantity, $update->start_time, $update->stop_time, $update->permalink, $update->status, $item->id, $update->automatic_relist, $update_date, $items->mpid));
							pg_query("INSERT INTO log.meli (sku, status, response, executed_at) VALUES ('".$item->sku."','200','".$update->id."','".date("Y-m-d H:i:s")."')");

						} else {
							pg_query("INSERT INTO log.meli (sku, status, response, executed_at) VALUES ('".$item->sku."','400','".$update->id."','".date("Y-m-d H:i:s")."')");
						}
					}
				} else {
					pg_execute($conn->conn, "my_query", array($update->id, $update->seller_id, $update->category_id, $update->price, $update->base_price, $update->sold_quantity, $update->start_time, $update->stop_time, $update->permalink, $update->status, $item->id, $update->automatic_relist, $update_date, $items->mpid));
					pg_query("INSERT INTO log.meli (sku, status, response, executed_at) VALUES ('".$item->sku."','200','".$update->id."','".date("Y-m-d H:i:s")."')");
				}
			} else {
				$update = $items_manager->update($items->mpid, array('status' => 'closed'));
				echo "closed\n";
				pg_query("UPDATE meli.items SET mpid='".$update->id."', status = 'closed', update_date='".date("Y-m-d H:i:s")."' WHERE mpid = '".$items->mpid."'");
			}
		}
		$i++;
	}
}
$conn->close();
