<?php
include '../config/cbt_items.php';
include '../config/googleTranslate.php';

if ($_POST['action'] == 'load_items') {
	$url                = $_POST['url'];
	$application        = $_POST['application'];
	$shopType           = $_POST['shopType'];
	$type               = $_POST['type'];
	$file               = file($url);
	$items              = array();
	$product            = array();
	$conn               = new Connect();
	$application_detail = pg_fetch_object(pg_query("SELECT * FROM meli.shop WHERE id ='".$application."'"));
	$items_manager      = new items($application_detail->access_token);
	foreach ($file as $line) {
		$items[] = str_getcsv($line);
	}

	$translate    = new GoogleTranslate();
	$item_manager = new items($application_detail->access_token);
	foreach ($items as $i) {
		$images = "";
		for ($y = 13; $y < 19; $y++) {
			$images .= $i[$y];
			$images .= "~^~";
		}
		$description                    = $translate->translate('es', 'en', $i[10]);
		$final['is_prime']              = 1;
		$title                          = htmlspecialchars($translate->translate('es', 'en', $i[9]), ENT_QUOTES);
		$final['sale_price']            = $i[1];
		$final['product_category']      = $i[8];
		$final['quantity']              = 10;
		$final['package_weight']        = $i[4];
		$final['package_width']         = $i[5];
		$final['package_height']        = $i[6];
		$final['package_length']        = $i[7];
		$final['product_type']          = substr($i[8], 0, 25);
		$final['product_title_english'] = substr($title, 0, 120);
		$final['specification_english'] = $description;
		$final['currency']              = 'USD';
		$final['brand']                 = $i[11];
		$final['UPC']                   = $i[12];
		$final['model']                 = ' ';
		$final['condition']             = 'New';
		$final['weight_unit']           = 'lb';
		$final['image_url']             = substr($images, 0, -3);
		$array_item                     = json_encode($item_manager->prepare_item_amazon($application, 'post', $item[0], null, null, null, null, $final, 'local'));
		print_r($item_manager->prepare_item_amazon($application, 'post', $item[0], null, null, null, null, $final, 'local'));
		die();
		$url_products_add_post = "https://api-cbt.mercadolibre.com/api/SKUs/?access_token=".$application_detail->access_token;
		$ch                    = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_products_add_post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS, $array_item);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response);
		if (!isset($response->error)) {
			$aws_item = pg_fetch_object(pg_query("SELECT id FROM aws.items WHERE sku = '".$response->SKU."'"));
			if ($aws_item == NULL) {
				$aws_item = pg_fetch_object(pg_query($this->conn->conn, "INSERT INTO aws.items (sku, product_type, product_category, product_title_english, specification_english, brand, model, image_url, upc, currency, sale_price, quantity, condition, weight_unit, package_weight, package_height, package_length, create_date, update_date, active) VALUES
							(
							'".$array_item['sku']."',
							'".$array_item['product_type']."',
							'".$temp['product_category']."',
							'".$array_item['product_title_english']."',
							'".$array_item['specification_english']."',
							'".$array_item['brand']."',
							'".$array_item['model']."',
							'".$array_item['image_url']."',
							'".$array_item['UPC']."',
							'".$array_item['currency']."',
							'".$array_item['sale_price']."',
							'".$array_item['quantity']."',
							'".$array_item['condition']."',
							'".$array_item['weight_unit']."',
							'".$array_item['package_weight']."',
							'".$array_item['package_height']."',
							'".$array_item['package_length']."',
							'".date('y-m-d H:i:s')."',
							'".date('y-m-d H:i:s')."',
							 '1') RETURNING id;"));
			}
			pg_query($this->conn->conn, "INSERT INTO cbt.items(aws_id, mpid, category, title, price, status, shop_id, create_date, update_date) VALUES ('".$aws_item->id."', '".$response->mpid."', '".$response->category_id."', '".$response->product_title_english."', '".$response->sale_price."', '".$response->status."', '".$application."', '".date('y-m-d H:i:s')."', '".date('y-m-d H:i:s')."');");
			$this->conn->close();
			http_response_code(202);
			die(json_encode(array("message" => "Register succesfull"), JSON_UNESCAPED_UNICODE));
		} else {
			http_response_code(404);
			die(json_encode(array('message' => 'Something Wrong')));
		}

	}
}