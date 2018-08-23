<?php
include '../config/meli_items.php';

if ($_POST['action'] == 'upload_file') {
	$img_load    = $_FILES['file']['name'];
	$target_dir  = "../docs/items/";
	$target_file = $target_dir.basename($_FILES['file']['name']);
	$img_load    = $_FILES['file']['tmp_name'];
	if (move_uploaded_file($img_load, $target_file)) {
		echo json_encode(array('status' => 1, 'url' => $target_file));
	} else {
		echo json_encode(array('status' => 0));
	}

}
if ($_POST['action'] == 'read_sku_single') {
	$sku  = $_POST['sku'];
	$conn = pg_connect('host=127.0.0.1 port=5432 dbname=enkargo user=postgres password=1234');
	try {
		$result = pg_fetch_object(pg_query("SELECT sku FROM aws.items WHERE encrypt = '".$sku."';"));
		echo json_encode(array('status' => 1, 'sku' => json_encode($result->sku)));
	} catch (Exception $e) {
		echo json_encode(array('status' => 0, 'msg' => $e->message()));
	}
}
if ($_POST['action'] == 'read_sku') {
	$url  = $_POST['url'];
	$conn = pg_connect('host=127.0.0.1 port=5432 dbname=enkargo user=postgres password=1234');
	try {
		$file  = file($url);
		$items = array();
		foreach ($file as $line) {
			$items[] = str_getcsv($line);
		}
		$skus = array();
		foreach ($items as $i) {
			$result = pg_fetch_object(pg_query("SELECT sku FROM aws.items WHERE encrypt = '".$i[0]."';"));
			array_push($skus, $result->sku);
		}
		echo json_encode(array('status' => 1, 'items' => json_encode($skus)));
	} catch (Exception $e) {
		echo json_encode(array('status' => 0, 'msg' => $e->message()));
	}
}
if ($_POST['action'] == 'read_items') {
	$url = $_POST['url'];
	try {
		$file  = file($url);
		$items = array();
		foreach ($file as $line) {
			$items[] = str_getcsv($line);
		}
		echo json_encode(array('status' => 1, 'items' => json_encode($items)));
	} catch (Exception $e) {
		echo json_encode(array('status' => 0, 'msg' => $e->message()));
	}
}
if ($_POST['action'] == 'load_items') {
	$url                = $_POST['url'];
	$application        = $_POST['application'];
	$shopType           = $_POST['shopType'];
	$type               = $_POST['type'];
	$file               = file($url);
	$items              = array();
	$product            = array();
	$conn               = pg_connect('host=127.0.0.1 port=5432 dbname=enkargo user=postgres password=1234');
	$translate          = new GoogleTranslate();
	$application_detail = pg_fetch_object(pg_query("SELECT * FROM meli.shop WHERE id ='".$application."'"));
	$items_manager      = new items($application_detail->access_token);
	foreach ($file as $line) {
		$items[] = str_getcsv($line);
	}
	$data_description = pg_fetch_object(pg_query("select * from meli.templates where shop_id = '".$application."' and id = '6';"));
	$video            = pg_fetch_object(pg_query("SELECT * FROM meli.video WHERE shop_id = '".$application."';"));
	$item_description = "";
	foreach ($items as $i) {
		$aws = pg_fetch_object(pg_query("SELECT id FROM aws.items WHERE sku = '".trim($i[0])."';"));
		if (!isset($aws->id)) {
			$aws = pg_fetch_object(pg_query("INSERT INTO aws.items (sku) VALUES('".$i[0]."') RETURNING id;"));
		}
		if (isset($aws->id)) {
			$meli = pg_fetch_object(pg_query("SELECT mpid FROM meli.items WHERE aws_id = '".$aws->id."' AND shop_id = '".$application_detail->id."';"));
			if (isset($meli)) {
				$item_img = array();
				for ($y = 18; $y < 23; $y++) {
					if ($i[$y]) {
						array_push($item_img, array('source' => $i[$y]));
					}
				}
				$title      = $i[9];
				$item_title = $i[9];
				#$translate->translate('es', 'en', $i[9]);
				$item             = (object) array('product_title_english' => $i[9], 'brand' => $i[14]);
				$item_description = "<ul>";
				for ($m = 9; $m < 16; $m++) {
					$item_description .= "<li>".$i[$m]."</li>";
				}
				$item_description .= "<li>SUJETO A DISPONIBILIDAD DE TALLAS</li>";
				$item_description .= "<li>NO APLICAN CAMBIOS</li>";
				$item_description .= "</ul>";
				$heigth_pack = $i[6];
				$width_pack  = $i[5];
				$length_pack = $i[7];
				$weight_pack = $i[4];
				$heigth_item = $i[6];
				$width_item  = $i[5];
				$length_item = $i[7];
				$img_1       = $i[18];
				$img_2       = $i[19];
				$img_3       = $i[20];
				$img_4       = $i[21];
				$date_update = date('Y-m-d i:h:s');
				$description = $data_description->template;
				eval("\$description = \"$description\";");
				$category_id = "MCO157623";
				#$items_manager->getCategoriesPredictor($i[9]);
				$category = $items_manager->validateCategory($category_id);
				if (strlen($title) > 60) {
					$pos   = strpos($title, ' ', 59);
					$title = substr($title, 0, $pos);

				}
				$warranty;
				if ($application == 1) {
					$warranty = "*** TRADUCCIÓN AUTOMÁTICA, VERIFICAR CARACTERISTICAS ANTES DE COMPRAR***, Sujetos a disponibilidad de tallas, NO APLICAN CAMBIOS. Los artículos importados tienen garantía de 30 días por defectos de fabrica únicamente. No nos hacemos responsables de los costos de envío en garantías NO APLICA GARANTIA EN DAÑOS CAUSADOS POR LA EMPRESA TRANSPORTADORA";
				}
				if ($application == 2) {
					$warranty = "*** TRADUCCIÓN AUTOMÁTICA, VERIFICAR CARACTERISTICAS ANTES DE COMPRAR***, Sujetos a disponibilidad de tallas, NO APLICAN CAMBIOS. Este es un artículo importado, tiene una garantía de 30 días por defectos de fábrica, la misma no incluye cualquier daño ocasionado por la empresa transportadora. Mauxi eshop no se hace responsable de los costos de envío del producto en caso de aplicar la garantía";
				}
				$product['title']               = $title;
				$product['category_id']         = $category->id;
				$product['domain_id']           = $category->settings->vip_subdomain;
				$product['price']               = intval(($type == 1)?$i[1]:$items_manager->liquidador($i[1], $weight_pack, $application));
				$product['currency_id']         = "COP";
				$product['buying_mode']         = "buy_it_now";
				$product['available_quantity']  = 10;
				$product['condition']           = "new";
				$product['listing_type_id']     = "gold_special";
				$product['description']         = $description;
				$product['video_id']            = $video->url;
				$product['warranty']            = $warranty;
				$product['pictures']            = $item_img;
				$product['seller_custom_field'] = $i[0];
				$product['shipping']            = array('mode'    => 'me2', 'local_pick_up'    => 'true', 'free_shipping'    => 'true');
				$product['location']            = array('country' => array('name' => 'Colombia'), 'state' => array('name' => 'Bogota D.C'), 'city' => array('name' => 'Bogota D.C'));
				$validation                     = $items_manager->validate($product);
				if ($validation != null) {
					$sql_log = "INSERT INTO meli.logs(sku, action, message, status) VALUES ('".$i[0]."', 'create', '".$validation->message."', '".$validation->status."');";
					pg_query($sql_log);
					echo json_encode(array('status' => 0, 'message' => 'validation wrong'));
				} else {
					$show       = $items_manager->create($product);
					$sql_insert = "INSERT INTO meli.items(mpid, title, seller_id, category_id, price, base_price, sold_quantity,start_time, stop_time, end_time, permalink, status, aws_id, automatic_relist, date_created, last_updated, shop_id, create_date, update_date,video, is_static)
			VALUES ('".$show->id."', '".htmlspecialchars($show->title, ENT_QUOTES)."', '".$show->seller_id."', '".$show->category_id."', '".$show->price."', '".$show->base_price."', '".$show->sold_quantity."',
			'".$show->start_time."', '".$show->stop_time."', '".$show->end_time."', '".$show->permalink."', '".$show->status."',
			'".$aws->id."', '".$show->automatic_relist."', '".$show->date_created."', '".$show->last_updated."', '2', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."','".$video->id."','1');";
					pg_query($sql_insert);
					echo json_encode(array('status' => 1));
					$k++;
				}
			} else {
				echo json_encode(array('status' => 0, 'message' => 'mpid duplicated'));
			}

		} else {
			echo json_encode(array('status' => 0, 'message' => 'sku not found'));
		}
	}
}
if ($_POST['action'] == 'delete_items') {
	$url = $_POST['url'];
	if (unlink($url)) {
		echo json_encode(array('status' => 1));
	} else {
		echo json_encode(array('status' => 0));
	}
}