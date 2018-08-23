<?php
#include '../config/googleTranslate.php';
include '../config/meli_items.php';
#ini_set("display_errors", 0);
#ini_set('error_reporting', E_ALL|E_STRICT);
class meliGet {
	public $conn;
	public function __construct() {
		$this->conn = new Connect();
	}
	/****							SET CBT BY LOCAL DATABASE FUNCTIONS 						****/
	public function updateItems() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$application                        = $_POST['application'];
			$id                                 = $_POST['id'];
			$mlid                               = $_POST['mlid'];
			$SKU                                = $_POST['SKU'];
			$product_title_english              = $_POST['product_title_english'];
			$sale_price                         = $_POST['sale_price'];
			$quantity                           = $_POST['quantity'];
			$update_array['title']              = $product_title_english;
			$update_array['price']              = $sale_price;
			$update_array['available_quantity'] = $quantity;
			$application_detail                 = pg_fetch_object(pg_query("SELECT * FROM meli.shop WHERE id ='".$application."'"));
			$items_manager                      = new items($application_detail->access_token,$application_detail->user_name);
			$update                             = $items_manager->update($mlid, $update_array);
			if (!isset($update)) {
				$this->conn->close();
				http_response_code(202);
				die(json_encode(array("status" => 1), JSON_UNESCAPED_UNICODE));
			} else {
				http_response_code(202);
				die(json_encode(array("status" => o), JSON_UNESCAPED_UNICODE));
			}
		} catch (Exception $e) {
			http_response_code(404);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	public function 
	() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$application = $_POST['application'];
			$limit       = $_POST['limit'];
			$cbt_query   = pg_query("SELECT id, mpid, title, category_id, price, status FROM meli.items  WHERE shop_id = '".$application."' LIMIT ".$limit.";");
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
	public function getItemsDetail() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$application        = $_POST['application'];
			$mpid               = $_POST['mlid'];
			$application_detail = pg_fetch_object(pg_query("SELECT * FROM meli.shop WHERE id ='".$application."'"));
			$items_manager      = new items($application_detail->access_token);
			$response           = array();
			$detail_items       = $items_manager->show($mpid);
			if ($detail_items->status != 403 || $detail_items->status != 401) {
				http_response_code(202);
				pg_query("UPDATE meli.items   SET title='".$detail_items->title."', seller_id='".$detail_items->seller_id."', category_id='".$detail_items->category_id."', price='".$detail_items->price."', base_price='".$detail_items->base_price."', sold_quantity='".$detail_items->sold_quantity."', start_time='".$detail_items->start_time."', stop_time='".$detail_items->stop_time."', end_time='".$detail_items->end_time."', permalink='".$detail_items->permalink."', status='".$detail_items->status."', seller_custom_field='".$detail_items->seller_custom_field."', automatic_relist='".$detail_items->automatic_relist."', update_date='".date('Y-m-d H:i:s')."' WHERE mpid = '".$detail_items->id."';");
				$this->conn->close();
				die(json_encode($detail_items, JSON_UNESCAPED_UNICODE));
			} else {
				http_response_code(202);
				echo json_encode(array('status' => 0, 'msg' => "No se pueden realizar peticiones a la plataforma"), JSON_UNESCAPED_UNICODE);
			}
		} catch (Exception $e) {
			http_response_code(404);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	public function deleteItems() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$application        = $_POST['application'];
			$mpid               = $_POST['mpid'];
			$application_detail = pg_fetch_object(pg_query("SELECT * FROM meli.shop WHERE id ='".$application."'"));
			$items_manager      = new items($application_detail->access_token);
			$temp['status']     = "closed";
			$detail_items       = $items_manager->update($meli_item->mpid, $temp);
			if ($detail_items->status != 403) {
				$this->conn->close();
				http_response_code(202);
				die(json_encode(array('status' => 1, 'msg' => "Cerrado con éxito"), JSON_UNESCAPED_UNICODE));
			} else {
				http_response_code(202);
				die(json_encode(array('status' => 0, 'msg' => "No se pueden realizar peticiones a la plataforma"), JSON_UNESCAPED_UNICODE));
			}
		} catch (Exception $e) {
			http_response_code(404);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	public function relistItems() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$application        = $_POST['application'];
			$mpid               = $_POST['mpid'];
			$application_detail = pg_fetch_object(pg_query("SELECT * FROM meli.shop WHERE id ='".$application."'"));
			$items_manager      = new items($application_detail->access_token);
			$temp               = array();
			$temp['title']      = $_POST['title'];
			$temp['price']      = $_POST['price'];
			$update             = $items_manager->relist($meli_item->mpid, $temp);
			if (!isset($update)) {
				if ($detail_items->status != 403) {
					$this->conn->close();
					http_response_code(202);
					die(json_encode(array('status' => 1, 'msg' => "Re publicado con éxito"), JSON_UNESCAPED_UNICODE));
					pg_query($this->conn->conn, "UPDATE meli.items SET mpid='".$update->id."', title='".$item_title."', price='".$price."', start_time='".$update->start_time."', stop_time='".$update->stop_time."', end_time='".$update->end_time."', permalink='".$update->permalink."', last_updated='".date('y-m-d H:i:s')."', update_date='".date('y-m-d H:i:s')."' WHERE id = '".$meli_item->id."'");
					$this->conn->close();
				} else {
					http_response_code(202);
					die(json_encode(array('status' => 0, 'msg' => "No se pueden realizar peticiones a la plataforma"), JSON_UNESCAPED_UNICODE));
				}

			} else {
				http_response_code(404);
				die(json_encode(array('message' => 'Something Wrong')));
			}
		} catch (Exception $e) {
			http_response_code(404);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}

}
