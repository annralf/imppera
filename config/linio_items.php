<?php
include '/var/www/html/enkargo/config/googleTranslate.php';
include '/var/www/html/enkargo/config/conex_manager.php';
include '/var/www/html/enkargo/config/resize_image.php';

class items {
	public $source;
	public $target;
	public $categories_list = array();
	public $connector;
	public $site;
	public $shop;
	public $translate;

	public function __construct($site, $shop) {
		$this->connector = new Connect();
		$this->source    = 'en';
		$this->target    = 'es';
		$this->site      = $site;
		$this->shop      = $shop;
		$this->translate = new GoogleTranslate();

	}

	public function liquidador($value, $weight, $acronym) {
		if ($acronym == 'co') {
			$constant   = 7000;
			$pricePerKg = 0;
			$gain       = 1.5;
			$round      = -3;
			$pricePerKg = 25000;
			if ($value < 17) {
				$final_price = ($value*9000)+($weight*$pricePerKg);
			} else {
				$final_price = ($value*$constant)+($weight*$pricePerKg);
			}
			$salePrice = round($final_price);
			$mod       = $salePrice%1000;
			$salePrice = $salePrice+($mod < (1000/2)?-$mod:1000-$mod);
			$price     = round($salePrice+($salePrice*0.20));
			$modP      = $price%1000;
			$price     = ($price+($modP < (1000/2)?-$modP:1000-$modP))-100;
			$salePrice = $salePrice-100;
			return array('price' => $price, 'sale_price' => $salePrice);
		}
		if ($acronym == 'mx') {
			$constant   = 35;
			$pricePerKg = 0;
			$gain       = 1.4;
			$round      = -1;
			$guideCost  = 0;
			$stampCost  = 150;
			$pricePerKg = 130;
			if ($value < 17) {
				$final_price = (($value*40)+($pricePerKg*$weight))+$stampCost;
			} else {
				$final_price = (($constant*$value)+($pricePerKg*$weight))+$stampCost;
			}
			$salePrice = round($final_price);
			$mod       = $salePrice%10;
			$salePrice = $salePrice+($mod < (10/2)?-$mod:10-$mod);
			$price     = round($salePrice+($salePrice*0.20));
			$modP      = $price%10;
			$price     = ($price+($modP < (10/2)?-$modP:10-$modP))-1;
			$salePrice = $salePrice-1;
			return array('price' => $price, 'sale_price' => $salePrice);
		}
	}

	public function category_match($product_type, $category_name) {
		if ($this->site == 'co') {
			$query_meta_category = pg_query("select * from linio.category_master_co where padre =0;");
			$product_type        = $this->translate->translate('en', 'es', $product_type);
		}
		if ($this->site == 'mx') {
			$query_meta_category = pg_query("select * from linio.category_master where padre =0;");
			$product_type        = $product_type;
		}
		$match_meta_category = 0;
		$match_sub_category  = 0;
		$match_category      = 0;
		$category_name       = explode(",", $category_name);
		$total_aws_category  = count($category_name);

		if ($total_aws_category >= 1 && $product_type !== null) {
			while ($meta_category = pg_fetch_object($query_meta_category)) {
				similar_text($product_type, $meta_category->definition, $percent);
				if ($percent > $match_meta_category) {
					$match_meta_category = $percent;
					$meta_category_id    = $meta_category->id;
				}
			}
			if (!isset($meta_category_id)) {
				return null;
				exit;
			}
			if ($this->site == 'co') {
				$query_sub_category = pg_query("select * from linio.category_master_co where padre ='".$meta_category_id."';");
			}
			if ($this->site == 'mx') {
				$query_sub_category = pg_query("select * from linio.category_master where padre ='".$meta_category_id."';");
			}
			$sub_category_id = 0;
			while ($sub_category = pg_fetch_object($query_sub_category)) {
				similar_text($product_type, htmlspecialchars_decode($sub_category->definition), $percent);
				if ($percent > $match_sub_category) {
					$match_sub_category = $percent;
					$sub_category_id    = $sub_category->id;
				}
			}
			return $sub_category_id;
			break;
		}
	}

	public function product_create($aws_id, $sku, $title, $description, $specification_english, $sale_price, $package_weight, $product_type, $product_category, $ean, $upc, $encrypt, $item_width, $package_length, $package_height, $trans) {
		$weight = round((($package_weight)/100)*0.4535);
		if ($weight < 1) {
			$weight = 1;
		}
		$price = $this->liquidador($sale_price, $weight, $this->site);
		if ($trans == false) {
			$title             = $this->translate->translate('en', 'es', $title);
			$description_short = $this->translate->translate('en', 'es', $specification_english);
		} else {
			$title             = $title;
			$description_short = $description;
		}
		$category      = $this->category_match($product_type, $product_category);
		$saleStartDate = date('Y-m-d H:i:s');
		$base          = $ean;
		if ($base == null) {
			$base = $upc;
			if ($base == null) {
				$base = 9999999999;
			}
		}
		$product_id = str_pad(mt_rand(1, $base), 14, '0', STR_PAD_LEFT);
		if ($this->site == 'co') {
			$tax = "Iva Excento 0%";
		}
		if ($this->site == 'mx') {
			$tax = "iva 0%";
		}
		$description     = "<ul><li>Producto Entregado de 6 a 10 día Hábiles.</li></ul>";
		$ProductMeasures = $item_width."x".$package_length."x".$package_height;
		$product         = "<Product>"
		."<SellerSku>".$encrypt."</SellerSku>"
		."<Status>active</Status>"
		."<Name>".htmlspecialchars($title, ENT_QUOTES)."</Name>"
		."<PrimaryCategory>".$category."</PrimaryCategory>"
		."<Description><![CDATA[<b>".htmlspecialchars($description_short, ENT_QUOTES)."</b>.]]></Description>"
		."<Brand>generic</Brand>"
		."<Price>".$price['price']."</Price>"
		."<SalePrice>".$price['sale_price']."</SalePrice>"
		."<SaleStartDate>".$saleStartDate."</SaleStartDate>"
		."<SaleEndDate>2099-12-31 11:59:59</SaleEndDate>"
		."<TaxClass>".$tax."</TaxClass>"
		."<ShipmentType>dropshipping</ShipmentType>"
		."<ProductId>".$product_id."</ProductId>"
		."<Condition>new</Condition>"
		."<ProductData>"
		."<ConditionType>Nuevo</ConditionType>"
		."<PackageWeight>".$weight."</PackageWeight>"
		."<PackageWidth>".$item_width."</PackageWidth>"
		."<PackageLength>".$package_length."</PackageLength>"
		."<PackageHeight>".$package_height."</PackageHeight>"
		."<ProductMeasures>".$ProductMeasures."</ProductMeasures>"
		."<ProductWeight>".$weight."</ProductWeight>"
		."<ShortDescription><![CDATA[<b>".htmlspecialchars($description, ENT_QUOTES)."</b>.]]></ShortDescription>"
		."</ProductData>"
		."<Quantity>10</Quantity>"
		."</Product>";
		#echo $category;
		#"\n";
		#pg_query("INSERT INTO linio.items(title, quantity, price, sale_start_date, status, product_id, aws_id, shop_id, update_date, create_date, shop_description, primary_category) VALUES ('".$title."', 10, '".$price['price']."','".$saleStartDate."', 'active', '".$product_id."', '".$aws_id."', '".$this->shop."', '".$saleStartDate."','".$saleStartDate."', '".pg_escape_string(utf8_encode($description))."', '".$category."')");
		return $product;
	}

	public function send_product_create($products) {
		$application  = pg_fetch_object(pg_query("SELECT * FROM linio.shop WHERE id ='".$this->shop."';"));
		$access_token = $application->access_token;
		$name         = $application->name;
		date_default_timezone_set("America/Bogota");
		$now        = new DateTime();
		$parameters = array(
			'UserID'    => $application->user_name,
			'Version'   => "1.0",
			'Action'    => "ProductCreate",
			'Format'    => 'XML',
			'Timestamp' => $now->format(DateTime::ISO8601)
		);
		ksort($parameters);
		$encoded = array();
		foreach ($parameters as $name => $value) {
			$encoded[] = rawurlencode($name).'='.rawurlencode($value);
		}
		$concatenated            = implode('&', $encoded);
		$api_key                 = $access_token;
		$parameters['Signature'] = rawurlencode(hash_hmac('sha256', $concatenated, $api_key, false));
		$queryString             = http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
		$url_tmp                 = 'https://sellercenter-api.linio.com.'.$this->site;
		$url                     = $url_tmp."?".$queryString;
		$result                  = '<?xml version="1.0" encoding="UTF-8"?><Request>'.$products.'</Request>';
		$ch                      = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $result);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$resultset = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_getinfo($ch);
		curl_close($ch);
		return simplexml_load_string($resultset);
	}

	public function send_product_update($products) {
		$application  = pg_fetch_object(pg_query("SELECT * FROM linio.shop WHERE id ='".$this->shop."';"));
		$access_token = $application->access_token;
		$name         = $application->name;
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
		$api_key                 = $access_token;
		$parameters['Signature'] = rawurlencode(hash_hmac('sha256', $concatenated, $api_key, false));
		$queryString             = http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
		$url_tmp                 = 'https://sellercenter-api.linio.com.'.$this->site;
		$url                     = $url_tmp."?".$queryString;
		$result                  = '<?xml version="1.0" encoding="UTF-8"?><Request>'.$products.'</Request>';
		$ch                      = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $result);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$resultset = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return simplexml_load_string($resultset);
	}

	public function image_create($sku, $encrypt, $image) {
		$imag_manager = new ReziseImage();
		$images       = explode("~^~", $image);
		$image_linio  = "<ProductImage>"
		."<SellerSku>".$encrypt."</SellerSku>"
		."<Images>";
		for ($j = 0; $j < count($images); $j++) {
			if ($j < 8) {
				$url = $imag_manager->adapt($images[$j]);
				$image_linio .= "<Image>";
				$image_linio .= $images[$j];
				$image_linio .= $url['url'];
				$image_linio .= "</Image>";
			}
		}
		$image_linio .= "</Images>"
		."</ProductImage>";
		return $image_linio;
	}

	public function send_image($image) {
		$application  = pg_fetch_object(pg_query("SELECT * FROM linio.shop WHERE id ='".$this->shop."';"));
		$access_token = $application->access_token;
		$name         = $application->name;
		date_default_timezone_set("America/Bogota");
		$now        = new DateTime();
		$parameters = array(
			'UserID'    => $application->user_name,
			'Version'   => "1.0",
			'Action'    => "Image",
			'Format'    => 'XML',
			'Timestamp' => $now->format(DateTime::ISO8601)
		);
		ksort($parameters);
		$encoded = array();
		foreach ($parameters as $name => $value) {
			$encoded[] = rawurlencode($name).'='.rawurlencode($value);
		}
		$concatenated            = implode('&', $encoded);
		$api_key                 = $access_token;
		$parameters['Signature'] = rawurlencode(hash_hmac('sha256', $concatenated, $api_key, false));
		$queryString             = http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
		$url_tmp                 = 'https://sellercenter-api.linio.com.'.$this->site;
		$url                     = $url_tmp."?".$queryString;
		$result                  = '<?xml version="1.0" encoding="UTF-8"?><Request>'.$image.'</Request>';
		$ch                      = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $result);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$resultset = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return simplexml_load_string($resultset);
	}

	public function product_remove($sku) {
		$request = "<Product>"
		."<SellerSku>".$sku."</SellerSku>"
		."</Product>";
		return $request;
	}

	public function send_remove($products) {
		$application  = pg_fetch_object(pg_query("SELECT * FROM linio.shop WHERE id ='".$this->shop."';"));
		$access_token = $application->access_token;
		$name         = $application->name;
		date_default_timezone_set("America/Bogota");
		$now        = new DateTime();
		$parameters = array(
			'UserID'    => $application->user_name,
			'Version'   => "1.0",
			'Action'    => "ProductRemove",
			'Format'    => 'XML',
			'Timestamp' => $now->format(DateTime::ISO8601)
		);
		ksort($parameters);
		$encoded = array();
		foreach ($parameters as $name => $value) {
			$encoded[] = rawurlencode($name).'='.rawurlencode($value);
		}
		$concatenated            = implode('&', $encoded);
		$api_key                 = $access_token;
		$parameters['Signature'] = rawurlencode(hash_hmac('sha256', $concatenated, $api_key, false));
		$queryString             = http_build_query($parameters, '', '&', PHP_QUERY_RFC3986);
		$url_tmp                 = 'https://sellercenter-api.linio.com.'.$this->site;
		$url                     = $url_tmp."?".$queryString;
		$result                  = '<?xml version="1.0" encoding="UTF-8"?><Request>'.$products.'</Request>';
		$ch                      = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $result);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		$resultset = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return simplexml_load_string($resultset);
	}

}
/*
$test = new items('dasdad');
echo $test->category_match('home', 'women,test');
 */