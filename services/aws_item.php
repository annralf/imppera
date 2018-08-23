<?php
include '../config/conex_manager.php';
include '../config/aws_item.php';
#ini_set("display_errors", 0);
#ini_set('error_reporting', E_ALL|E_STRICT);

class aws_item {
	public $conn;
	public function __construct() {
		$this->conn = new Connect();
	}
	public function get() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type application/json; charset=utf-8');
		try {
			$asin     = $_POST['asin'];
			$aws_item = new amazonManager;
			$result   = $aws_item->search_item($asin);
			http_response_code(200);
			die(json_encode($result, JSON_UNESCAPED_UNICODE));

		} catch (Exception $e) {
			http_response_code(404);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
	public function getItems() {
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		header('Content-Type application/json; charset=utf-8');
		try {
			$limit      = $_POST['limit'];
			$offset     = $_POST['offset'];
			$user_query = pg_query("SELECT sku, product_type,product_title_english, brand, sale_price, quantity,package_weight,is_prime, active FROM aws.items  ORDER BY sku ASC LIMIT ".$limit." OFFSET ".$offset.";");
			while ($row = pg_fetch_object($user_query)) {
				$users[] = $row;
			}
			http_response_code(200);
			die(json_encode($users));

		} catch (Exception $e) {
			http_response_code(404);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
	}
}