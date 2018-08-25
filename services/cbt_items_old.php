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
					echo date('y-m-d H:i:s')." - ".$item['sku']." - ".$create_item->mpid."\n";

					pg_query("UPDATE aws.items_to_cbt SET bolborrado=2 WHERE id = '".$item['id']."';");

				} else {
					#print_r($create_item); die();
					echo date('y-m-d H:i:s')." - ".$item['sku']." - ".$create_item->error[0]->code."\n";
					pg_query("INSERT INTO log.cbt(sku, action, response, executed_at, code_) VALUES ('".$item['sku']."', 1, '".$create_item->error[0]->message."', '".date("Y-m-d H:i:s")."','".$create_item->error[0]->code."');");
				}
			} else {

				echo date('y-m-d H:i:s')." - ".$item['sku']." - ".$validate_item->error[0]->code."\n";
				pg_query("INSERT INTO log.cbt(sku, action, response, executed_at, code_) VALUES ('".$item['sku']."', 1, '".$validate_item->error[0]->message."', '".date("Y-m-d H:i:s")."','".$validate_item->error[0]->code."');");
			}
		} else {
			echo "NULL";
		}
	}

	function publish($mpid,$to_br,$to_mx) {
		$cbt_items         = new items($this->application->access_token);
		#$contry_to_publish = array("publish_to_BR" => 1);
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
			if (!isset($validate_item->error)) {
				$create_item = json_decode($cbt_items->update_item($prepared_item, $item['mpid']));
				if (!isset($create_item->error)) {
					$item_main = pg_query("UPDATE cbt.items SET update_date = '".date("Y-m-d H:i:s")."', status='".$create_item->status."' WHERE mpid = '".$item['mpid']."';");
					return $create_item;
				} else {
					$error = $create_item->error;
					$item_main = pg_query("UPDATE cbt.items SET status = '".$error[0]->code."', update_date = '".date("Y-m-d H:i:s")."' WHERE mpid = '".$item['mpid']."';");
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
#********************************************** Publish items *****************************************************************
/*
$create_item = new cbt_create(3);
$mpids       = pg_query("SELECT mpid FROM cbt.items WHERE shop_id ='3'  and create_date > '2017-10-31 00:00:00'");
$i           = 0;
while ($items = pg_fetch_object($mpids)) {
echo $i."-PUBLISH-".$items->mpid."\n";
$create_item->publish($items->mpid);
$i++;
}
 */
#**************************************************** Create items *************************************************************
/*
$conn        = new Connect();
$create_item = new cbt_create(3);
$items       = pg_query("select * from aws.items where active_cbt='t' and id not in (select aws_id from cbt.items)  and is_prime=1 and sale_price<>0 and package_weight<100 and sale_price>50 and product_type<>'Toy' and id not in (select id from aws.items where upper(product_title_english) like upper('%knife%') and upper(product_title_english) like upper('%blade%')) order by update_date desc limit 50000;");
$j           = 1;
while ($i = pg_fetch_array($items)) {
echo $j." - ".date('y-m-d H:i:s')."-";
$create_item->create("local", $i, null);
$j++;

}*/
#**************************************************** Update items *************************************************************
/*
$items = array();
$conn  = new Connect();
$items = pg_query("SELECT a.*, c.mpid FROM aws.items AS a JOIN cbt.items AS c ON c.aws_id = a.id order by a.update_date desc;");
$j     = 1;
while ($i = pg_fetch_array($items)) {
$create_item = new cbt_create(3);
echo $j." - ".date('y-m-d H:i:s')."-".$i['mpid']."\n";
print_r($create_item->update(null, null, null, null, null, $i, "local", null));
$j++;
}*/
#********************************************** Delete items *****************************************************************
/*
$create_item = new cbt_create(3);
$conn        = new Connect();
$items       = pg_query("select b.mpid from cbt.items b join aws.items a on a.id=b.aws_id where a.package_weight/100 >10");
$j           = 1;
while ($i = pg_fetch_array($items)) {
$create_item = new cbt_create(3);
$create_item->delete($i['mpid']);
echo $j."-DELETED-".$i['mpid']."\n";
$j++;
}
 */
/*
$mpids       = array(9004908602,9004908600,9004908598,9004908594,9004908593,9004908592,9004908591,9004908590,9004908589,9004908588,9004908564,9004908558,9004904593,9004904578,9004904574,9004904554,9004904514,9004904505);
$i           = 0;
for($k=0; $k < count($mpids); $k++){
$create_item->delete($mpids[$k]);
echo $k."-DELETED-".$mpids[$k]."\n";
}
 */