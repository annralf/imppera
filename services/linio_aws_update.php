<?php
require_once '/var/www/html/enkargo/config/pdo_connector.php';
#include '/var/www/html/enkargo/config/conex_manager.php';
include '/var/www/html/enkargo/config/googleTranslate.php';

#ini_set("display_errors", 0);
#ini_set('error_reporting', E_ALL|E_STRICT);
class LinioUpdate {
	public $conn;
	public $url;
	public $site;
	public $shop_id;
	public $access_token;
	public $name;
	public $translate;

	public function __construct($site, $shop_id) {
		$this->conn    = new DataBase();
		$this->site    = $site;
		$this->shop_id = $shop_id;
		$application   = $this->conn->prepare("SELECT * FROM linio.shop WHERE id ='".$shop_id."';");
		$application->execute();
		$application        = (Object) $application->fetch();
		$this->access_token = $application->access_token;
		$this->name         = $application->name;
		$this->translate    = new GoogleTranslate();
		date_default_timezone_set("America/Caracas");
		$now        = new DateTime();
		$parameters = array(
			'UserID'    => $application->user_name,
			'Version'   => "1.0",
			'Action'    => "ProductUpdate",
			'Format'    => 'XML',
			'Timestamp' => $now->format(DateTime::ISO8601)
		);
		ksort($parameters);
		$encoded = array();
		foreach ($parameters as $name => $value) {
			$encoded[] = rawurlencode($name).'='.rawurlencode($value);
		}
		$concatenated            = implode('&', $encoded);
		$api_key                 = $this->access_token;
		$parameters['Signature'] = rawurlencode(hash_hmac('sha256', $concatenated, $api_key, false));
		$queryString             = http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);

		$url_tmp   = 'https://sellercenter-api.linio.com.'.$this->site;
		$this->url = $url_tmp."?".$queryString;
	}

	public function calculateValue($value, $weight, $acronym) {
		if ($acronym == 'co') {
			$constant   = 7000;
			$pricePerKg = 0;
			$gain       = 1.5;//50%
			$round      = -3;
			$pricePerKg = 25000;
			#Cambio para en reunión con Karem y Jhoam 18 julio de 2017
			if ($value < 17) {
				$final_price = ($value*9000)+($weight*$pricePerKg);
			} else {
				$final_price = ($value*$constant)+($weight*$pricePerKg);
			}
			return $final_price;
		}
		if ($acronym == 'mx') {
			$constant   = 35;
			$pricePerKg = 0;
			$gain       = 1.4;//40%
			$round      = -1;
			$guideCost  = 0;
			$stampCost  = 150;
			$pricePerKg = 130;
			if ($value < 17) {
				$final_price = (($value*40)+($pricePerKg*$weight))+$stampCost;
			} else {
				$final_price = (($constant*$value)+($pricePerKg*$weight))+$stampCost;
			}

			return $final_price;
		}
	}

	function aws_update() {
		$secuences = $this->conn->prepare("select * from linio.secuences where type = 'update';");
		$secuences->execute();
		$secuences = (Object) $secuences->fetch();
		#$items     = $this->conn->prepare("select * from aws.items_valido_linio_co_view  offset '".$secuences->offset_."' limit '".$secuences->limit_."';");
		$items = $this->conn->prepare("select * from aws.items_valido_linio_co_view offset 20000;");
		$items->execute();
		$items = $items->fetchAll();
		#$offset        = $secuences->offset_+$secuences->limit_;
		#$offset_update = $this->conn->prepare("update linio.secuences set offset_ = '".$offset."'");
		#$offset_update->execute();
		$i = 0;
		$k = 0;
		$y = 10000;
		#$secuences->limit_;
		$products = "";
		$skus     = "";
		$this->conn->beginTransaction();

		foreach ($items as $item) {
			$item = (Object) $item;
			if ($item->sale_price != ' ') {
				$request       = '';
				$saleStartDate = date('Y-m-d H:i:s');
				$weight        = round((($item->package_weight)/100)*0.4535);
				if ($weight < 1) {
					$weight = 1;
				}
				if ($this->site == "mx") {
					$salePrice = round($this->calculateValue($item->sale_price, $weight, $this->site));
					$mod       = $salePrice%10;
					$salePrice = $salePrice+($mod < (10/2)?-$mod:10-$mod);
					$price     = round($salePrice+($salePrice*0.20));
					$modP      = $price%10;
					$price     = ($price+($modP < (10/2)?-$modP:10-$modP))-1;
					$salePrice = $salePrice-1;
				}
				if ($this->site == "co") {
					$salePrice = round($this->calculateValue($item->sale_price, $weight, $this->site));
					$mod       = $salePrice%1000;
					$salePrice = $salePrice+($mod < (1000/2)?-$mod:1000-$mod);
					$price     = round($salePrice+($salePrice*0.20));
					$modP      = $price%1000;
					$price     = ($price+($modP < (1000/2)?-$modP:1000-$modP))-100;
					$salePrice = $salePrice-100;
				}
				$status = 'active';
				if ($item->sale_price == 0) {
					$status = 'inactive';
				}
				/*
				if ($item->is_prime != 1) {
				$status = 'inactive';
				}
				if ($weight > 25) {
				$status = 'inactive';
				}
				 */
				if ($item->sale_price > 1700) {
					$status = 'inactive';
				}

				/*
				if ($item->active == 'f') {
				$status = 'inactive';
				}
				 */
				if ($status == 'active') {
					$quantity = 20;
				} else {
					$quantity = 0;
				}
				$sellerSku = $item->sku;
				#$description = $this->translate->translate('en', 'es', $specification_english);
				$request .= "<Product>"
				."<SellerSku>".$sellerSku."</SellerSku>"
				."<Status>".$status."</Status>"
				."<Quantity>".$quantity."</Quantity>"
				."<Price>".$price."</Price>"
				#."<Description><![CDATA[<b>".$description."</b>.]]></Description>"
				 ."<SalePrice>".$salePrice."</SalePrice>"
				."<SaleStartDate>".$saleStartDate."</SaleStartDate>"
				."<SaleEndDate>2099-12-31 11:59:59</SaleEndDate>"
				#."<ProductData>"
				#."<ShortDescription><![CDATA[<b>Producto Entregado de 6 a 10 días Hábiles.\n</b>.]]><b>".$description."</b></ShortDescription>"
				#."</ProductData>"
				 ."</Product>";
				$this->conn->exec("UPDATE linio.items SET sale_price = '".$salePrice."', status='".$status."', update_date = '".date('Y-m-d H:i:s')."' WHERE shop_id = '".$this->shop_id."' AND aws_id = '".$item->id."';");
				echo $i."-".$item->sku."-".$status."-".date('Y-m-d H:i:s')."\n";

				if ($k <= $y) {
					$products .= $request;
					$skus .= $item->sku;
				}
				if ($k == $y-1) {
					echo "sent\n";
					$result = '<?xml version="1.0" encoding="UTF-8"?><Request>'.$products.'</Request>';
					$ch     = curl_init();
					curl_setopt($ch, CURLOPT_URL, $this->url);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $result);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
					$this->resultset = curl_exec($ch);
					$http_code       = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					curl_close($ch);
					$response = simplexml_load_string($this->resultset);
					$products = "";
					$skus     = "";
					$k        = 0;
				}
				$k++;
				$i++;
			}
		}
		$this->conn->commit();
		echo "Fin commit-".date('Y-m-d H:i:s')."\n";
		$this->conn->close_con();

	}
}
$test = new LinioUpdate("co", 1);
for ($i = 0; $i < 60; $i++) {
	$test->aws_update();
}