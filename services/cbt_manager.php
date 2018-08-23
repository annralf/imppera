<?php
include '../config/googleTranslate.php';
include 'cbt_create.php';
$conn = new Connect();
if ($_POST['action'] == 'add_price_manager') {
	$add_query = "UPDATE cbt.price_manager SET range_1='".$_POST['range_1']."', range_2='".$_POST['range_2']."', range_3='".$_POST['range_3']."', range_4='".$_POST['range_4']."', update_date='".date("Y-m-d H:i:s")."' WHERE shop_id='".$_POST['shop_id']."';";
	try {
		pg_query($add_query);
		echo '1';
	} catch (Exception $e) {
		echo '0';
	}
}
if ($_POST['action'] == 'upload_video') {
	$add_query = "INSERT INTO cbt.description(video, shop, is_active, create_date) VALUES ('".$_POST['video']."', '".$_POST['shop']."', 'true', '".date("Y-m-d H:i:s")."');";
	try {
		pg_query($add_query);
		echo '1';
	} catch (Exception $e) {
		echo '0';
	}
}
if ($_POST['action'] == 'get_items') {
	$shop   = $_POST['application'];
	$offset = $_POST['offset'];
	$limit  = $_POST['limit'];
	$items  = pg_query("select a.sku, c.* from cbt.items as c join aws.items as a on c.aws_id = a.id where c.shop_id = '".$shop."' limit 50;");
	#$items  = pg_query("SELECT * FROM cbt.items WHERE shop_id = '".$shop."' OFFSET '".$offset."' LIMIT '".$limit."';");
	while ($item = pg_fetch_object($items)) {
		$result[] = $item;
	}
	echo json_encode($result);
}
if ($_POST['action'] == 'get_item_detail') {
	$shop        = $_POST['application'];
	$sku         = $_POST['sku'];
	$result      = pg_fetch_object(pg_query("select c.mpid from cbt.items as c join aws.items as a on a.id = c.aws_id where a.sku = '".$sku."';"));
	$search_item = new cbt_create($shop);
	echo $search_item->get($result->mpid);
}
if ($_POST['action'] == 'update_item_detail') {
	$shop                  = $_POST['application'];
	$mpid                  = $_POST['mpid'];
	$sku                   = $_POST['SKU'];
	$product_title_english = $_POST['product_title_english'];
	$specification_english = $_POST['specification_english'];
	$sale_price            = $_POST['sale_price'];
	$quantity              = $_POST['quantity'];
	$package_weight        = $_POST['package_weight'];
	$item                  = array('mpid' => $mpid, 'sku' => $sku, 'product_title_english' => $product_title_english, 'specification_english' => $specification_english, 'quantity' => $quantity, 'package_weight' => $package_weight);
	$search_item           = new cbt_create($shop);
	$search_item->update(null, null, null, $quantity, null, $item, "front", $sale_price);
}
if ($_POST['action'] == 'delete_item_detail') {
	$shop        = $_POST['application'];
	$mpid        = $_POST['mpid'];
	$search_item = new cbt_create($shop);
	echo $search_item->delete($mpid);
}
if ($_POST['action'] == 'publish_item_detail') {
	$shop        = $_POST['application'];
	$mpid        = $_POST['mpid'];
	$search_item = new cbt_create($shop);
	echo $search_item->publish($mpid);
}