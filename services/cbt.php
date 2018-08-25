<?php
include '../config/cbt_items.php';
include '../config/googleTranslate.php';
#ini_set("display_errors", 0);
#ini_set('error_reporting', E_ALL|E_STRICT);
class Cbt {
	public $conn;
	public function __construct() {
		$this->conn = new Connect();
	}
	public function getItemsDetail() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$item               = $_POST['mlid'];
			$application_id     = $_POST['application'];
			$application_detail = pg_fetch_object(pg_query("SELECT * FROM cbt.shop WHERE id ='".$application_id."'"));
			$items_manager      = new items($application_detail->access_token);
			$response           = array();
			$detail_items       = $items_manager->get_item($item);
			http_response_code(202);
			die(json_encode($detail_items, JSON_UNESCAPED_UNICODE));
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	public function updateItemsDetail() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$id                            = $_POST['id'];
			$mpid                          = $_POST['mlid'];
			$application_id                = $_POST['application'];
			$item['SKU']                   = $_POST['SKU'];
			$item['product_title_english'] = $_POST['product_title_english'];
			$item['specification_english'] = $_POST['specification_english'];
			$item['sale_price']            = $_POST['sale_price'];
			$item['quantity']              = $_POST['quantity'];
			$item['package_weight']        = $_POST['package_weight'];
			$application_detail            = pg_fetch_object(pg_query("SELECT * FROM cbt.shop WHERE id ='".$application_id."'"));
			$video                         = pg_fetch_object(pg_query("SELECT video FROM cbt.description WHERE shop = '".$application_id."'"));
			$items_manager                 = new items($application_detail->access_token);
			$final_item                    = json_encode($items_manager->prepare_item_amazon($application_id, 'put', null, null, null, 10, $video->video, $item, 'local'));
			$response                      = array();
			$detail_items                  = json_decode($items_manager->update_item($final_item, $mpid));
			if (!$detail_items->error) {
				pg_query("UPDATE cbt.items SET mpid='".$detail_items->mpid."', title='".$detail_items->product_title_english."', price='".$detail_items->sale_price."', status='".$detail_items->status."', shop_id='".$application_id."', update_date='".date('Y-m-d H:i:s')."' WHERE id='".$id."';");
				$this->conn->close();
				http_response_code(202);
				die(json_encode(array('msg' => 'Success id '.$id, 'status' => 1)));
			} else {
				http_response_code(404);
				die(json_encode(array('msg' => $detail_items->error, 'status' => 0)));
				$this->conn->close();
			}
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
			$this->conn->close();
		}
	}
	/****							SET CBT BY LOCAL DATABASE FUNCTIONS 						****/
	public function getItems() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$application = $_POST['application'];
			$limit       = $_POST['limit'];
			$cbt_query   = pg_query("SELECT id, mpid, category, title, price, status FROM cbt.items  WHERE shop_id = '".$application."' LIMIT ".$limit.";");
			while ($row = pg_fetch_object($cbt_query)) {
				$cbt[] = $row;
			}
			http_response_code(200);
			die(json_encode($cbt));

		} catch (Exception $e) {
			http_response_code(404);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	#Service to addnew items by file_get_contents
	public function add_by_file() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$application        = $_POST['application'];
			$type               = $_POST['type'];
			$url                = $_POST['url'];
			$application_detail = pg_fetch_object(pg_query("SELECT * FROM meli.shop WHERE id ='".$application."'"));
			$item_manager       = new items($application_detail->access_token);
			$file               = file($url);

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
				$video                          = pg_fetch_object(pg_query("SELECT video FROM cbt.description WHERE shop= '".$application."';"));
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
				$array_item                     = json_encode($item_manager->prepare_item_amazon($application, 'post', $i[0], null, $video->video, null, null, $final, 'local'));
				$url_products_add_post          = "https://api-cbt.mercadolibre.com/api/SKUs/?access_token=".$application_detail->access_token;
				$ch                             = curl_init();
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
					die(json_encode(array("status" => "1", "message" => "Register succesfull"), JSON_UNESCAPED_UNICODE));
				} else {
					http_response_code(404);
					die(json_encode(array("status" => "1", "message" => "Something Wrong")));
				}

			}
		} catch (Exception $e) {
			$this->conn->close();
			echo json_encode(array('msg' => "Wrong Data base Connection", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	#Service adding new item from SQLServer Data Base
	public function add_local() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$application  = $_POST['application'];
			$access_token = $_POST['access_token'];
			$asin         = $_POST['asin'];
			$category_id  = $_POST['category_id'];
			$item_manager = new items($access_token);
			#Search in SS data base
			$sql_item   = "SELECT id, asin as SKU, marca as brand, ean as UPC, package_height as package_height, package_width as package_width, package_length as package_length, package_weight as package_weight, review as specification_english, publish as quantity, category as product_type, category as product_category, title_amazon as product_title_english, dollar_price as sale_price, prime as is_prime  FROM temporal.items WHERE asin = '".$asin."'";
			$query_item = pg_query($conn, $sql_item);
			$translate  = new GoogleTranslate();
			#Creating temp array
			$temp = array();
			$i    = 1;
			while ($item = pg_fetch_object($query_item)) {
				$sql_img   = "select * from temporal.items_pictures where item_id ='".$item->id."'";
				$query_img = pg_query($sql_img);
				$images    = "";
				$j         = 1;
				while ($images_set = pg_fetch_object($query_img)) {
					$images .= $images_set->url;
					$images .= "~^~";
				}
				$replace          = array("&lt;ul&gt;&lt;li&gt;", "&lt;/li&gt;&lt;li&gt;");
				$description      = htmlspecialchars(str_replace($replace, " ", $item->specification_english), ENT_QUOTES);
				$description      = $translate->translate('es', 'en', $description);
				$temp['is_prime'] = 1;
				$title            = htmlspecialchars($item->product_title_english, ENT_QUOTES);
				if (strlen($title) > 119) {
					$pos   = strpos($title, ' ', 118);
					$title = substr($title, 0, $pos);
				}
				$temp['sale_price']            = $item->sale_price;
				$temp['product_category']      = $item->product_category;
				$temp['quantity']              = ($item->quantity == 0)?1:$item->quantity;
				$temp['package_weight']        = (0 == $item->package_weight)?1:$item->package_weight;
				$temp['package_width']         = (0 == $item->package_width)?1:$item->package_width;
				$temp['package_height']        = (0 == $item->package_height)?1:$item->package_height;
				$temp['package_length']        = (0 == $item->package_length)?1:$item->package_length;
				$temp['product_type']          = substr($item->product_type, 0, 25);
				$temp['product_title_english'] = substr($title, 0, 120);
				$temp['specification_english'] = $description;
				$temp['currency']              = 'USD';
				$temp['brand']                 = $item->brand;
				$temp['UPC']                   = $item->upc;
				$temp['model']                 = ' ';
				$temp['condition']             = 'New';
				$temp['weight_unit']           = 'lb';
				$temp['image_url']             = substr($images, 0, -3);
				$array_item                    = json_encode($item_manager->prepare_item_amazon($application, 'post', $item->sku, $category_id, null, null, null, $temp, 'local'));
				$url_products_add_post         = "https://api-cbt.mercadolibre.com/api/SKUs/?access_token=".$access_token;
				$ch                            = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url_products_add_post);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $array_item);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				curl_close($ch);
				$response = json_decode($response);
				#Validating exect error and insert data to PG local data base
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
			}} catch (Exception $e) {
			$this->conn->close();
			echo json_encode(array('msg' => "Wrong Data base Connection", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}

	#Service updating item from SQLServer Data Base
	public function update_local() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');

		try {
			$xml = $_POST['xml'];
			$xml = simplexml_load_string($xml);
			if ($xml == NULL) {
				http_response_code(503);
				die(json_encode(array("message" => "Main file not found"), JSON_UNESCAPED_UNICODE));
			}
			$items_manager = new items($xml->connection->access_token);
			#Search in SS data base
			$translate = new GoogleTranslate();
			#Creating temp array
			$temp = array();
			#Match SKU into PG Data Base
			$aws_item = pg_fetch_object(pg_query("SELECT * FROM aws.items WHERE sku = '".$xml->product->SKU."'"));
			#Getting CBT item from PG Data Base
			foreach ($xml->product as $item) {
				$images = "";
				$j      = 1;
				for ($j = 0; $j < count($xml->product->images->url); $j++) {
					$images .= $xml->product->images->url[$j];
					$images .= "~^~";
				}
				$replace     = array("&lt;ul&gt;&lt;li&gt;", "&lt;/li&gt;&lt;li&gt;");
				$description = htmlspecialchars(str_replace($replace, " ", $item->specification_english), ENT_QUOTES);
				$description = $translate->translate('es', 'en', $description);
				if ($item->is_prime != "Y") {
					$items_manager->delete_item($cbt_item->mpid);
					http_response_code(201);
					die(json_encode(array("message" => "Update succesfull item deleted"), JSON_UNESCAPED_UNICODE));
				}
				$temp['is_prime'] = (string) $item->is_prime;
				$title            = htmlspecialchars($item->product_title_english, ENT_QUOTES);
				if (strlen($title) > 119) {
					$pos   = strpos($title, ' ', 118);
					$title = substr($title, 0, $pos);
				}
				$product_category              = $item->product_category;
				$product_type                  = substr($item->product_type, 0, 25);
				$temp['sale_price']            = (string) $item->sale_price;
				$temp['product_category']      = (string) ($product_category == NULL)?array($product_type):$product_category;
				$temp['quantity']              = (string) ($item->quantity == 0)?1:$item->quantity;
				$temp['package_weight']        = (float) ((0 == $item->package_weight)?1:$item->package_weight);
				$temp['package_width']         = (float) ((0 == $item->package_width)?1:$item->package_width);
				$temp['package_height']        = (float) ((0 == $item->package_height)?1:$item->package_height);
				$temp['package_length']        = (float) ((0 == $item->package_length)?1:$item->package_length);
				$temp['product_type']          = ($product_type == NULL)?$title:$product_type;
				$temp['product_title_english'] = substr($title, 0, 120);
				$temp['specification_english'] = $description;
				$temp['currency']              = 'USD';
				$temp['brand']                 = (string) $item->brand;
				$temp['UPC']                   = (string) $item->upc;
				$temp['model']                 = ' ';
				$temp['condition']             = 'New';
				$temp['weight_unit']           = 'lb';
				$temp['image_url']             = substr($images, 0, -3);
				$array_item                    = json_encode($items_manager->prepare_item_amazon($xml->connection->application_id, 'put', $item->SKU, null, null, null, null, $temp, 'local'));
				$url_products_publish_put      = "https://api-cbt.mercadolibre.com/api/SKUs/".$cbt_item->mpid."/?access_token=".$access_token;
				$ch                            = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url_products_publish_put);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $array_item);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				curl_close($ch);
				$response = json_decode($response);
				#Validating exect error and insert data to PG local data base
				if (!isset($response->error)) {
					pg_query($this->conn->conn, "UPDATE cbt.items SET title='".$response->product_title_english."', price='".$response->sale_price."', status='".$response->status."', update_date='".date('y-m-d H:i:s')."' WHERE id = '".$cbt_item->id."'");
					$this->conn->close();
					http_response_code(202);
					die(json_encode(array("message" => "Update succesfull"), JSON_UNESCAPED_UNICODE));
				} else {
					http_response_code(404);
					die(json_encode(array('message' => 'Something Wrong')));
				}
			}} catch (Exception $e) {
			$this->conn->close();
			echo json_encode(array('msg' => "Wrong Data base Connection", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	/****							SET CBT BY AWS SERVICE FUNCTIONS 						****/
	public function add_aws() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$application_id = $_POST['application'];
			$items          = $_POST['items'];
			$access_token   = $_POST['access_token'];
			$items_manager  = new items($access_token);
			foreach ($items as $it) {
				$item = $items_manager->prepare_item_amazon($application, "post", $it, null, null, null, null, null, 'aws');
				if ($item !== null) {
					$validation = $items_manager->validate_item($item);
					if ($validation == null) {
						$add_item = $items_manager->create_item($item);
						http_response_code(202);
						die(json_encode(array('message' => 'Register succesfull'), JSON_UNESCAPED_UNICODE));
					}
				} else {
					http_response_code(400);
					die(json_encode(array('message' => "Something Wrong", "error" => $validation), JSON_UNESCAPED_UNICODE));
				}
			}
		} catch (Exception $e) {
			$this->conn->close();
			http_response_code(500);
			echo json_encode(array('message' => "Wrong Data base Connection", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	#Service updating item from AWS Service
	public function update_aws() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$access_token                  = $_POST['access_token'];
			$application_id                = $_POST['application'];
			$item['SKU']                   = $_POST['SKU'];
			$item['product_title_english'] = $_POST['product_title_english'];
			$item['specification_english'] = $_POST['specification_english'];
			$item['sale_price']            = $_POST['sale_price'];
			$item['quantity']              = $_POST['quantity'];
			$item['package_weight']        = $_POST['package_weight'];
			$item['is_prime']              = $_POST['is_prime'];
			$items_manager                 = new items($access_token);
			$response                      = array();
			#Match SKU into PG Data Base
			$aws_item = pg_fetch_object(pg_query("SELECT * FROM aws.items WHERE sku = '".$item['SKU']."'"));
			#Getting CBT item from PG Data Base
			$cbt_item = pg_fetch_object(pg_query("SELECT id, mpid FROM cbt.items WHERE aws_id = '".$aws_item->id."'"));
			if ($item['is_prime'] !== 1) {
				$items_manager->delete_item($cbt_item->mpid);
				http_response_code(201);
				die(json_encode(array("message" => "Update succesfull item deleted"), JSON_UNESCAPED_UNICODE));
			}
			$response = $items_manager->update_item($item, $cbt_item->mpid);
			if (!isset($response->error)) {
				pg_query($this->conn->conn, "UPDATE cbt.items SET title='".$response->product_title_english."', price='".$response->sale_price."', status='".$response->status."', update_date='".date('y-m-d H:i:s')."' WHERE id = '".$cbt_item->id."'");
				$this->conn->close();
				http_response_code(202);
				die(json_encode(array("message" => "Update succesfull"), JSON_UNESCAPED_UNICODE));
			}
		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	#Service push item from PG Data Base
	public function push() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$application_id           = $_POST['application_id'];
			$mpid                     = $_POST['mpid'];
			$contry_to_publish        = array("publish_to_MX" => 1);
			$application              = pg_fetch_object(pg_query("SELECT * FROM cbt.shop WHERE id = '".$application_id."'"));
			$url_products_publish_put = "https://api-cbt.mercadolibre.com/api/SKUs/".$mpid."/?access_token=".$application->access_token;
			$ch                       = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url_products_publish_put);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($contry_to_publish));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
			if ($response == null) {
				pg_query($this->conn->conn, "UPDATE cbt.items SET status='publish', update_date='".date('y-m-d H:i:s')."' WHERE mpid = '".$mpid."'");
				$this->conn->close();
				echo json_encode(array('msg' => "Success mpid ".$mpid, 'status' => 1), JSON_UNESCAPED_UNICODE);
			}

		} catch (Exception $e) {
			$this->conn->close();
			echo json_encode(array('msg' => "Wrong Data base Connection", 'error' => $e->getMessage(), 'status' => 0), JSON_UNESCAPED_UNICODE);
		}
	}
	#Service push item from PG Data Base
	public function delete() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$application_id = $_POST['application_id'];
			$id             = $_POST['id'];
			$mpid           = $_POST['mpid'];
			$application    = pg_fetch_object(pg_query("SELECT * FROM cbt.shop WHERE id = '".$application_id."'"));
			$items_manager  = new items($application->access_token);
			$delete_item    = $items_manager->delete_item($mpid);
			if (!$delete_item) {
				pg_query("UPDATE cbt.items SET status='deleted', update_date='".date('Y-m-d H:i:s')."' WHERE id = '".$id."'");
				$this->conn->close();
			}
			http_response_code(201);
			die(json_encode(array("msg" => "Update succesfull item deleted", 'status' => 1), JSON_UNESCAPED_UNICODE));

		} catch (Exception $e) {
			$this->conn->close();
			echo json_encode(array('msg' => "Wrong Data base Connection", 'error' => $e->getMessage(), 'status' => 0), JSON_UNESCAPED_UNICODE);
		}
	}

}
