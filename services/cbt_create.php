<?php
include "/var/www/html/enkargo/config/cbt_items.php";

class cbt_create {
	public $conn;
	public $application;
	public $shop;

	function __construct($shop) {
		$this->conn        = new Connect();
		$this->shop        = $shop;
		$this->application = pg_fetch_object(pg_query("SELECT * FROM cbt.shop WHERE id = '".$this->shop."'"));
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
					pg_query("UPDATE aws.items SET shop_cbt=1 WHERE id = '".$item['id']."';");
					$item_main = pg_query("INSERT INTO cbt.items (aws_id, mpid, category, title, price, status, shop_id, create_date, update_date) VALUES ('".$item['id']."', '".$create_item->mpid."','".$create_item->category_id."','".pg_escape_string(utf8_encode($create_item->product_title_english))."','".$create_item->sale_price."','".$create_item->status."','".$this->application->id."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')");
					echo $item['sku']."-".$create_item->mpid."\n";
				} else {
					echo $item['sku']."-";
					print_r($create_item);
					echo "\n";
				}
			} else {
				echo $item['sku']."-";
				print_r($validate_item);
				echo "\n";
			}
		} else {
			echo "NULL";
		}
	}

	function publish($mpid) {
		$cbt_items         = new items($this->application->access_token);
		$contry_to_publish = array("publish_to_MX" => 1);
		$publish_item      = $cbt_items->publish_item($mpid, $contry_to_publish);
		pg_query("UPDATE cbt.items SET status = 'published' WHERE mpid = '".$mpid."';");
		echo 1;
	}

	function get($mpid) {
		$cbt_items = new items($this->application->access_token);
		$get_item  = $cbt_items->get_item($mpid);
		return $get_item;
	}
	function delete($mpid) {
		$cbt_items   = new items($this->application->access_token);
		$delete_item = $cbt_items->delete_item($mpid);
		echo 1;
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
			if (!isset($validate_item->error)) {
				$create_item = $cbt_items->update_item($prepared_item, $item['mpid']);

				if (!isset($create_item->error)) {
					$item_main = pg_query("UPDATE cbt.items SET  update_date = '".date("Y-m-d H:i:s")."' WHERE mpid = '".$item['mpid']."';");
					echo 1;
				} else {
					echo 0;
				}
			} else {
				echo 0;
			}
		} else {
			echo "NULL";
		}
	}
}