<?php
include '/var/www/html/enkargo/config/meli_items.php';
include "/var/www/html/enkargo/config/conex_manager.php";

class meliGet {
	public $conn;
	public $translate;
	public function __construct() {
		$this->conn      = new DataBase();
		$this->translate = new GoogleTranslate();
	}
	public function createItems($application) {
		try {

			$k                  = 0;
			$application_detail = $this->conn->prepare("SELECT * FROM meli.shop WHERE id ='".$application."'");
			$application_detail->execute();
			$application_detail = $application_detail->fetchAll();
			$items_manager      = new items($application_detail[0]['access_token']);

			if ($application == 1) {
				$items = $this->conn->prepare("SELECT aws.sku,
    aws.brand,
    aws.product_title_english,
    aws.specification_english,
    aws.sale_price,
    aws.quantity,
    round((aws.package_height / 0.393701::double precision)::numeric, 2) AS package_height,
    round((aws.package_length / 0.393701::double precision)::numeric, 2) AS package_length,
    round((aws.package_weight / 2.204623::double precision / 100::double precision)::numeric, 2) AS package_weight,
    round((aws.item_height / 0.393701::double precision)::numeric, 2) AS item_height,
    round((aws.item_width / 0.393701::double precision)::numeric, 2) AS item_width,
    round((aws.item_length / 0.393701::double precision)::numeric, 2) AS item_length,
    aws.image_url,
    aws.product_category,
    aws.product_type,
    aws.model,
    aws.weight_unit,
    aws.category_meli,
    aws.update_date,
    aws.color,
    aws.clothingsize from aws.items aws where aws.bolborrado=0 and upper(aws.product_category) like upper('%handbag%') and aws.quantity > 2 and aws.active='t'  limit 350;");
				$items->execute();
				#$this->conn->beginTransaction();
				$items = $items->fetchAll();

				foreach ($items as $item) {
					$item = (object) $item;

					$title = eliminar_simbolos($item->product_title_english);
					if (strlen($title) >= 200) {
						$pos   = strpos($title, ' ', 150);
						$title = substr($title, 0, $pos);
					}

					$item_title = $this->translate->translate('en', 'es', $title);
					if (strlen($item_title) >= 60) {
						$pos        = strpos($item_title, ' ', 40);
						$item_title = substr($item_title, 0, $pos);
					}


					/*Functions about descriotion plain text*/
					$item_description = $this->translate->translate('en', 'es', $item->specification_english);
					$description  = str_replace(array("'", "."),array(" ", "\n"), $item_description);


					$images      = explode("~^~", $item->image_url);
					$img_cant    = count($images);
					$precio 	 =  $items_manager->liquidador($item->sale_price, $item->package_weight, $application_detail[0]['id']);

					echo "'".$item->sku."';'".$item_title."';'".$item->quantity."';'".$item->product_category."';'".$description."';'".$precio."';";
					#echo "'".$item->sku."';'".$item_title."';'".$description."';";

					if (count($images) > 8) {
						$img_cant = 8;
					}
					for ($j = 0; $j < $img_cant; $j++) {
						echo "'".$images[$j]."';";
					}
					echo "\n";
		
				}
			}	
		} catch (Exception $e) {
			http_response_code(404);
			echo json_encode(array('msg' => "Falló la conexión a la base de datos", 'error' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
		}
		#$this->conn->commit();
		#echo "Fin de carga - ".date("Y-m-d H:i:s")."\n";
		$this->conn->close_con();
	}
}

$test = new meliGet();
/*
$conn      = new DataBase();
$application_detail = $conn->prepare("SELECT * FROM meli.shop WHERE id =1");
$application_detail->execute();
$application_detail = $application_detail->fetchAll();
$product = $conn->prepare("SELECT array_meli FROM meli.pre_charge WHERE shop_id =1 limit 3");
$product->execute();
$product = $product->fetchAll();
$i = 1;
foreach ($product as $key) {
	$product = json_decode($key[0]);
	echo $i."-";
	$test->loadItems($product, $application_detail[0]['id'],$application_detail[0]['access_token']);
	$i++;
}*/
$test->createItems(1);