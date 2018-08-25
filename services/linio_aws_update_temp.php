<?php
include '/var/www/html/enkargo/config/conex_manager.php';
#ini_set("display_errors", 0);
#ini_set('error_reporting', E_ALL|E_STRICT);
class LinioUpdate {
	public $conn;
	public $url;
	public $site;
	public $shop_id;
	public $access_token;
	public $name;

	public function __construct($site, $shop_id) {
		$this->conn         = new Connect();
		$this->site         = $site;
		$this->shop_id      = $shop_id;
		$application        = pg_fetch_object(pg_query("SELECT * FROM linio.shop WHERE id ='".$shop_id."';"));
		$this->access_token = $application->access_token;
		$this->name         = $application->name;
		date_default_timezone_set("America/Bogota");
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
			#$constant   = 4000;
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
			#return round(($constant*$value+$pricePerKg*$weight)*$gain, $round);
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
			#return round($tmp+$guideCost+$stampCost, $round)-1;
		}

		#return 9999999;
	}

	function aws_update() {
		$sql  = "select aws.* from aws.items as aws join meli.items as meli on meli.aws_id = aws.id where meli.shop_id = '2' and aws.update_date > '2017-08-03 09:00:00' and aws.active = 't' order by aws.id asc;";
		$cant = pg_fetch_object(pg_query("select count(aws.*) from aws.items as aws join meli.items as meli on meli.aws_id = aws.id where meli.shop_id = '2' and aws.update_date > '2017-08-03 09:00:00';"));
		#$cant     = pg_fetch_object(pg_query("select count(aws.sku) from aws.items as aws left join linio.items as linio on linio.aws_id = aws.id where linio.shop_id ='".$this->shop_id."' and  aws.ean <> '0720018210610' and aws.active = 'f' limit 2;"));
		$query    = pg_query($sql);
		$i        = 0;
		$k        = 1;
		$y        = ($cant->count > 10000)?round($cant->count/($cant->count%6)):$cant->count;
		$products = "";
		$skus     = "";
		#echo $y;
		#die();
		echo "<pre";
		echo "N |SKU       |PRECIO $|PRECIO LINIO|   PESO k\n";
		while ($item = pg_fetch_object($query)) {
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
				if ($item->is_prime != 1) {
					$status = 'inactive';
				}
				if ($weight > 25) {
					$status = 'inactive';
				}
				if ($item->sale_price > 1700) {
					$status = 'inactive';
				}
				if ($item->active == 'f') {
					$status = 'inactive';
				}
				$sellerSku = (string) trim($item->sku);
				if ($status == 'active') {
					$quantity = 20;
					#."<ProductId>".$item->ean."</ProductId>"
					$request .= "<Product>"
					."<SellerSku>".$sellerSku."</SellerSku>"
					."<Status>".$status."</Status>"
					."<Quantity>".$quantity."</Quantity>"
					."<Price>".$price."</Price>"
					."<SalePrice>".$salePrice."</SalePrice>"
					."<SaleStartDate>".$saleStartDate."</SaleStartDate>"
					."<SaleEndDate>2099-12-31 11:59:59</SaleEndDate>"
					."</Product>";
				} else {
					$quantity = 0;
					$request .= "<Product>"
					."<SellerSku>".$sellerSku."</SellerSku>"
					."<Status>".$status."</Status>"
					."<Quantity>".$quantity."</Quantity>"
					."</Product>";
				}
				echo $i.",\"".$item->sku."\",\"".$item->sale_price."\",\"".$price."\",\"".$weight."\"\n";
				pg_query("UPDATE linio.items SET sale_price = '".$salePrice."', status='".$status."', update_date = '".date('Y-m-d H:i:s')."' WHERE shop_id = '".$this->shop_id."' AND aws_id = '".$item->id."';");
				if ($k < $y) {
					$products .= $request;
					$skus .= $item->sku;
				}
				/*
				if ($k == $y) {
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
				pg_query("INSERT INTO log.linio_success (request_action, response_type, shop_id ) VALUES ('ProductUpdate','".$response->Head->RequestId."','".$this->shop_id."')");
				$products = "";
				$skus     = "";
				sleep(900);
				$k = 0;
				}
				 */
				$k++;

				$i++;
			}
		}
	}
}
$conn = new Connect();
#$test = new LinioUpdate("co", 1);
$test = new LinioUpdate("mx", 2);

echo $test->aws_update();
