<?php
include "/var/www/html/enkargo/config/cbt_items.php";
include_once '/var/www/html/enkargo/config/pdo_connector.php';
class cbt_create {
	public $conn;
	public $application;
	public $shop;

	function __construct($shop) {
		$this->conn        = new DataBase();
		$this->shop        = $shop;
		$this->application = $this->conn->prepare("SELECT * FROM cbt.shop WHERE id = '".$this->shop."'");
		$this->application->execute();
		$this->application = $this->application->fetchObject();
	}

	function create($origin, $item, $price) {
		$cbt_items = new items($this->application->access_token);
		if ($origin == 'local') {
			$prepared_item = json_encode(($cbt_items->prepare_item_amazon($this->application->id, "post", null, null, null, null, null, $item, null, $price)));
		}
		if ($prepared_item != "0") {
			$validate_item = $cbt_items->validate_item($prepared_item);
			if (!isset($validate_item->error)) {
				$create_item = $cbt_items->create_item($prepared_item);
				if (!isset($create_item->error)) {
					$sql = $this->conn->prepare("UPDATE aws.items SET shop_cbt=1 WHERE id = '".$item->id."';");
					$sql->execute();
					$item_main = $this->conn->prepare("INSERT INTO cbt.items (aws_id, mpid, category, title, price, status, shop_id, create_date, update_date) VALUES ('".$item->id."', '".$create_item->mpid."','".$create_item->category_id."','".pg_escape_string(utf8_encode($create_item->product_title_english))."','".$create_item->sale_price."','".$create_item->status."','".$this->application->id."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')");
					$item_main->execute();
					echo date('y-m-d H:i:s')." - ".$item->sku." - ".$create_item->mpid."\n";

					$sql = $this->conn->prepare("UPDATE aws.items_to_cbt SET bolborrado=2 WHERE id = '".$item->id."';");
					$sql->execute();
				} else {
					print_r($create_item); #die();
					echo date('y-m-d H:i:s')." - ".$item->sku." - ".$create_item->error[0]->code."\n";
					$sql = $this->conn->prepare("INSERT INTO log.cbt(sku, action, response, executed_at, code_) VALUES ('".$item->sku."', 1, '".$create_item->error[0]->message."', '".date("Y-m-d H:i:s")."','".$create_item->error[0]->code."');");
					$sql->execute();
				}
			} else {

				echo date('y-m-d H:i:s')." - ".$item->sku." - ".$validate_item->error[0]->code."\n";
				print_r($validate_item);

				#$sku = $this->conn->prepare("INSERT INTO log.cbt(sku, action, response, executed_at, code_) VALUES ('".$item->sku."', 1, '".$validate_item->error[0]->message."', '".date("Y-m-d H:i:s")."','".$validate_item->error[0]->code."');");
				#$aql->execute();
			}
		} else {
			echo "NULL";
		}
	}

	function publish($mpid,$to_br,$to_mx) {
		$cbt_items         = new items($this->application->access_token);
		$contry_to_publish = array("publish_to_BR" => $to_br,"publish_to_MX" => $to_mx);
		$publish_item      = $cbt_items->publish_item($mpid, $contry_to_publish);
		$valor =json_decode($publish_item);
		return $valor;
	}

	function orders($mpid) {
		$cbt_items         = new items($this->application->access_token);
		$orders_item      = $cbt_items->get_orders($mpid);
		$valor =json_decode($orders_item);
		return $valor;
	}
	

	function get($mpid) {
		$cbt_items = new items($this->application->access_token);
		$get_item  = $cbt_items->get_item($mpid);
		return $get_item;
	}
	function delete($mpid) {
		$cbt_items   = new items($this->application->access_token);
		return $delete_item = $cbt_items->delete_item($mpid);
	}

	function update($local_item, $item_category, $video, $quantity, $condition, $item, $origin, $price) {
		$cbt_items = new items($this->application->access_token);
		if ($origin == 'local') {
			$prepared_item = json_encode($cbt_items->prepare_item_amazon($this->application->id, "put", $local_item, $item_category, $video, $quantity, $condition, $item, $origin, $price));
		}
		if ($origin == 'front') {
			$prepared_item = json_encode($cbt_items->prepare_item_amazon($this->application->id, "put", $local_item, $item_category, $video, $quantity, $condition, $item, $origin, $price));
		}


		if ($prepared_item != "0") {
			$validate_item = $cbt_items->validate_item($prepared_item);
			#print_r($prepared_item);die();
			if (!isset($validate_item->error)) {
				$create_item = json_decode($cbt_items->update_item($prepared_item, $item->mpid));
				#print_r($create_item);die();
				if (!isset($create_item->error)) {
					$item_main = $this->conn->prepare("UPDATE cbt.items SET update_date = '".date("Y-m-d H:i:s")."', status='".$create_item->status."' WHERE mpid = '".$item->mpid."';");
					$item_main->execute();
					return $create_item;
				} else {
					print_r($create_item);
					print_r(json_decode($prepared_item));
					if (is_array($create_item->error)){
						$item_main = $this->conn->prepare("UPDATE cbt.items SET status = '".$create_item->error[0]->code."', update_date = '".date("Y-m-d H:i:s")."' WHERE mpid = '".$item->mpid."';");
						$item_main->execute();
					}else{
						$item_main = $this->conn->prepare("UPDATE cbt.items SET status = '".$create_item->error->code."', update_date = '".date("Y-m-d H:i:s")."' WHERE mpid = '".$item->mpid."';");
						$item_main->execute();	
					}	
					return "ERROR";
				}
			} else {
				return "INVALIDED";
			}
		} else {
			return "NULL";
		}
	}
}