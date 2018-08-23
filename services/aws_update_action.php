<?php
include '/var/www/html/enkargo/config/aws_item.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';
/*#
/**
* 
*/
class aws_update {
	public $aws;
	public $conn;
	public $k;
	public $items;
	public $limit;
	public $return;
	function __construct($key,$secret_key,$tag)
	{
		$this->aws  = new amazonManager($key, $secret_key, $tag);
		$this->conn = new DataBase();
		$this->k    = 0;
	#**************************************************** Main *******************************************************************
	}
	
	function execute_update($sentence, $type){
		
		$query = $this->conn->prepare($sentence);
		$query->execute();
		#$this->conn->close_con();
		$this->k = 0;
		$array = array();
		$cant = 0;
		$num=1;
		$campo_product_type =(string)" when 3074144 then 'N/A' ";
		$campo_title 		=(string)" when 3074144 then 'N/A' ";
		$campo_description 	=(string)" when 3074144 then 'N/A' ";
		$campo_category 	=(string)" when 3074144 then '' ";
		$campo_category_p 	=(string)" when 3074144 then '' ";
		$campo_brand 		=(string)" when 3074144 then '' ";
		$campo_department	=(string)" when 3074144 then '' ";
		$campo_clothingSize	=(string)" when 3074144 then '' ";
		$campo_color 		=(string)" when 3074144 then '' ";
		$campo_model 		=(string)" when 3074144 then '' ";
		$campo_ean 			=(string)" when 3074144 then '' ";
		$campo_image_url 	=(string)" when 3074144 then '' ";
		$campo_upc 			=(string)" when 3074144 then '' ";
		$campo_currency 	=(string)" when 3074144 then '' ";
		$campo_condition 	=(string)" when 3074144 then 'New' ";
		$campo_weight_unit 	=(string)" when 3074144 then 'lb' ";
		$campo_height 		=(string)" when 3074144 then 0 ";
		$campo_length 		=(string)" when 3074144 then 0 ";
		$campo_item_height 	=(string)" when 3074144 then 0 ";
		$campo_item_length 	=(string)" when 3074144 then 0 ";
		$campo_item_width 	=(string)" when 3074144 then 0 ";
		$campo_price 		=(string)" when 3074144 then 0 ";
		$campo_quant 		=(string)" when 3074144 then 0 ";
		$campo_prime 		=(string)" when 3074144 then 0 ";
		$campo_weigth 		=(string)" when 3074144 then 0 ";
		$campo_active 		=(string)" when 3074144 then FALSE ";
		$campo_up_dat 		=(string)" when 3074144 then CURRENT_TIMESTAMP(0) ";
		$campo_bolbor 		=(string)" when 3074144 then 1 ";
		$campo_url 			=(string)" when 3074144 then '' ";
		$campo_sku_padre	=(string)" when 3074144 then '' ";
		$campo_avaliable 	=(string)" when 3074144 then '' ";
		$campo_ascii 		=(string)" when 3074144 then 0 ";
		$skus				='';
		$y=0;
		$conteo = $query->rowCount();
		foreach ($query->fetchAll() as $result) {
			$this->k++;
			$id_aws = $result['id'];
			$sku 	= $result['sku'];
			if ($type == "massive") {
				array_push($array, $sku);
				$cant  	= count($array);
				$sku_[$y]	=$sku;
				$id_[$y]	=$id_aws;
				$y++;
				if($cant == 10 || $this->k == $conteo){
					$items = implode(",", $array);
					$array = array();
					$y=0;
					$flag=0;
					while($flag==0){
						$aws_result1=$this->aws->search_item($items);
						if(!empty($aws_result1)){
							$flag=1;
							$total = count($aws_result1);
							foreach ($aws_result1 as $aws_result) {
								switch ($aws_result['notavaliable']) {
									case 0:
									#Item avaliable at AWS
									for ($a=0 ; $a<$total ; $a++ ){
										if($aws_result['asin']==$sku_[$a]){
											$id=$id_[$a]; 
											$sku_ascii 	= $this->aws->ToAscii($sku_[$a]);
										}
									}
									
									$product_type     = pg_escape_string(utf8_encode($aws_result['product_type']));
									$title            = pg_escape_string(utf8_encode($aws_result['product_title_english']));
									$description      = pg_escape_string(utf8_encode($aws_result['specification_english']));
									$product_category = pg_escape_string(utf8_encode($aws_result['product_category']));
									$product_category_p = pg_escape_string(utf8_encode($aws_result['category_p']));
									$brand            = pg_escape_string(utf8_encode($aws_result['brand']));
									$department       = pg_escape_string(utf8_encode($aws_result['department']));
									$clothingSize     = pg_escape_string(utf8_encode($aws_result['clothingSize']));
									$color            = pg_escape_string(utf8_encode($aws_result['color']));
									$model            = pg_escape_string(utf8_encode($aws_result['model']));
									$ean              = $aws_result['ean'];
									$image_url        = $aws_result['image_url'];
									$upc              = $aws_result['UPC'];
									$currency         = $aws_result['currency'];
									$sale_price       = $aws_result['sale_price'];
									$quantity         = $aws_result['quantity'];
									if ($quantity == null){
										$quantity 	  = 0;
									}
									$condition        = $aws_result['condition'];
									$weight_unit      = $aws_result['weight_unit'];
									$package_weight   = $aws_result['package_weight'];
									$package_height   = $aws_result['package_height'];
									$package_length   = $aws_result['package_length'];
									$is_prime         = $aws_result['is_prime'];
									$item_height      = $aws_result['item_height'];
									$item_length      = $aws_result['item_length'];
									$item_width       = $aws_result['item_width'];
									$sku              = $aws_result['asin'];
									$avaliable        = $aws_result['avaliable'];
									$url              = $aws_result['url'];
									$sku_padre        = $aws_result['ParentASIN'];

									$campo_product_type .=(string)" when ".$id." then '".$product_type."'";
									$campo_title 		.=(string)" when ".$id." then '".$title."'";
									$campo_description 	.=(string)" when ".$id." then '".$description."'";
									$campo_category 	.=(string)" when ".$id." then '".$product_category."'";
									$campo_category_p 	.=(string)" when ".$id." then '".$product_category_p."'";
									$campo_brand 		.=(string)" when ".$id." then '".$brand."'";
									$campo_department	.=(string)" when ".$id." then '".$department."'";
									$campo_clothingSize	.=(string)" when ".$id." then '".$clothingSize."'";
									$campo_color 		.=(string)" when ".$id." then '".$color."'";
									$campo_model 		.=(string)" when ".$id." then '".$model."'";
									$campo_ean 			.=(string)" when ".$id." then '".$ean."'";
									$campo_image_url 	.=(string)" when ".$id." then '".$image_url."'";
									$campo_upc 			.=(string)" when ".$id." then '".$upc."'";
									$campo_currency 	.=(string)" when ".$id." then '".$currency."'";
									$campo_condition 	.=(string)" when ".$id." then '".$condition."'";
									$campo_weight_unit 	.=(string)" when ".$id." then '".$weight_unit."'";
									$campo_height 		.=(string)" when ".$id." then ".$package_height;
									$campo_length 		.=(string)" when ".$id." then ".$package_length;
									$campo_item_height 	.=(string)" when ".$id." then ".$item_height;
									$campo_item_length 	.=(string)" when ".$id." then ".$item_length;
									$campo_item_width 	.=(string)" when ".$id." then ".$item_width;
									$campo_price 		.=(string)" when ".$id." then ".$sale_price;
									$campo_quant 		.=(string)" when ".$id." then ".$quantity;
									$campo_prime 		.=(string)" when ".$id." then ".$is_prime;
									$campo_weigth 		.=(string)" when ".$id." then ".$package_weight;
									$campo_active 		.=(string)" when ".$id." then TRUE ";
									$campo_up_dat 		.=(string)" when ".$id." then '".date("Y-m-d H:i:s")."' ";
									$campo_bolbor 		.=(string)" when ".$id." then 0 ";
									$campo_url 			.=(string)" when ".$id." then '".$url."'";
									$campo_avaliable	.=(string)" when ".$id." then '".$avaliable."'";
									$campo_sku_padre	.=(string)" when ".$id." then '".$sku_padre."'";
									$campo_ascii 		.=(string)" when ".$id." then ".$sku_ascii;

									$return= "create";
									#$this->conn->exec("UPDATE aws.items SET product_type = '".$product_type."', ean = '".$ean."', product_category = '".$product_category."', product_title_english = '".$title."', specification_english = '".$description."', brand = '".$brand."', model = '".$model."', image_url = '".$image_url."', upc = '".$upc."', currency = '".$currency."', sale_price = '".$sale_price."', quantity = '".$quantity."', condition = '".$condition."', weight_unit = '".$weight_unit."', package_weight = '".$package_weight."', package_height = '".$package_height."', package_length = '".$package_length."', clothingsize = '".$clothingSize."', color = '".$color."', department = '".$department."', is_prime = '".$is_prime."', item_height = '".$item_height."', item_length = '".$item_length."', item_width = '".$item_width."', update_date = '".$update_date."', active = '".$active."', url = '".$url."', bolborrado = 0 WHERE sku = '".$sku."';");
									break;
									case 1:
										#Item with hide price
										#Item avaliable at AWS
									for ($a=0 ; $a<$total ; $a++ ){
										if($aws_result['asin']==$sku_[$a]){
											$id=$id_[$a]; 
											$sku_ascii 	= $this->aws->ToAscii($sku_[$a]);
										}
									}

									$product_type     = pg_escape_string(utf8_encode($aws_result['product_type']));
									$title            = pg_escape_string(utf8_encode($aws_result['product_title_english']));
									$description      = pg_escape_string(utf8_encode($aws_result['specification_english']));
									$product_category = pg_escape_string(utf8_encode($aws_result['product_category']));
									$product_category_p = pg_escape_string(utf8_encode($aws_result['category_p']));
									$brand            = pg_escape_string(utf8_encode($aws_result['brand']));
									$department       = pg_escape_string(utf8_encode($aws_result['department']));
									$clothingSize     = pg_escape_string(utf8_encode($aws_result['clothingSize']));
									$color            = pg_escape_string(utf8_encode($aws_result['color']));
									$model            = pg_escape_string(utf8_encode($aws_result['model']));
									$ean              = $aws_result['ean'];
									$image_url        = $aws_result['image_url'];
									$upc              = $aws_result['UPC'];
									$currency         = $aws_result['currency'];
									$sale_price       = $aws_result['sale_price'];
									$quantity         = $aws_result['quantity'];
									if ($quantity == null){
										$quantity 	  = 0;
									}
									$condition        = $aws_result['condition'];
									$weight_unit      = $aws_result['weight_unit'];
									$package_weight   = $aws_result['package_weight'];
									$package_height   = $aws_result['package_height'];
									$package_length   = $aws_result['package_length'];
									$is_prime         = $aws_result['is_prime'];
									$item_height      = $aws_result['item_height'];
									$item_length      = $aws_result['item_length'];
									$item_width       = $aws_result['item_width'];
									$sku              = $aws_result['asin'];
									$avaliable        = $aws_result['avaliable'];
									$url              = $aws_result['url'];
									$sku_padre        = $aws_result['ParentASIN'];

									$campo_product_type .=(string)" when ".$id." then '".$product_type."'";
									$campo_title 		.=(string)" when ".$id." then '".$title."'";
									$campo_description 	.=(string)" when ".$id." then '".$description."'";
									$campo_category 	.=(string)" when ".$id." then '".$product_category."'";
									$campo_category_p 	.=(string)" when ".$id." then '".$product_category_p."'";
									$campo_brand 		.=(string)" when ".$id." then '".$brand."'";
									$campo_department	.=(string)" when ".$id." then '".$department."'";
									$campo_clothingSize	.=(string)" when ".$id." then '".$clothingSize."'";
									$campo_color 		.=(string)" when ".$id." then '".$color."'";
									$campo_model 		.=(string)" when ".$id." then '".$model."'";
									$campo_ean 			.=(string)" when ".$id." then '".$ean."'";
									$campo_image_url 	.=(string)" when ".$id." then '".$image_url."'";
									$campo_upc 			.=(string)" when ".$id." then '".$upc."'";
									$campo_currency 	.=(string)" when ".$id." then '".$currency."'";
									$campo_condition 	.=(string)" when ".$id." then '".$condition."'";
									$campo_weight_unit 	.=(string)" when ".$id." then '".$weight_unit."'";
									$campo_height 		.=(string)" when ".$id." then ".$package_height;
									$campo_length 		.=(string)" when ".$id." then ".$package_length;
									$campo_item_height 	.=(string)" when ".$id." then ".$item_height;
									$campo_item_length 	.=(string)" when ".$id." then ".$item_length;
									$campo_item_width 	.=(string)" when ".$id." then ".$item_width;
									$campo_price 		.=(string)" when ".$id." then ".$sale_price;
									$campo_quant 		.=(string)" when ".$id." then ".$quantity;
									$campo_prime 		.=(string)" when ".$id." then ".$is_prime;
									$campo_weigth 		.=(string)" when ".$id." then ".$package_weight;
									$campo_active 		.=(string)" when ".$id." then FALSE ";
									$campo_up_dat 		.=(string)" when ".$id." then '".date("Y-m-d H:i:s")."' ";
									$campo_bolbor 		.=(string)" when ".$id." then 5 ";
									$campo_url 			.=(string)" when ".$id." then '".$url."'";
									$campo_avaliable	.=(string)" when ".$id." then '".$avaliable."'";
									$campo_sku_padre	.=(string)" when ".$id." then '".$sku_padre."'";
									$campo_ascii 		.=(string)" when ".$id." then ".$sku_ascii;

									$return ="no_create_1";
									break;
									case 2:
										#item not avaliable
									for ($a=0 ; $a<$total ; $a++ ){
										if($aws_result['asin']==$sku_[$a]){ $id=$id_[$a]; }
									}

									$campo_active 	.=(string)" when ".$id." then FALSE ";
									$campo_up_dat 	.=(string)" when ".$id." then '".date("Y-m-d H:i:s")."' ";
									$campo_bolbor 	.=(string)" when ".$id." then 1 ";

									$return= "no_create_2";
									break;
								}
								$skus .= $id.",";
								$num++;
							}
						}
						sleep(1);
					}
				}
				
			}
		}


		$mySkl = substr($skus, 0, -1);

		$sql 	=(string)"update aws.items SET 
		sale_price =(CASE id ".$campo_price." END), 
		quantity =(CASE id ".$campo_quant." END),	
		is_prime =(CASE id ".$campo_prime." END),	
		active =(CASE id ".$campo_active." END), 
		update_date =(CASE id ".$campo_up_dat." END),	
		bolborrado =(CASE id ".$campo_bolbor." END), 
		package_weight =(CASE id ".$campo_weigth." END),
		product_type =(CASE id ".$campo_product_type." END), 
		ean =(CASE id ".$campo_ean." END), 
		product_category =(CASE id ".$campo_category." END), 
		product_title_english =(CASE id ".$campo_title." END), 
		specification_english =(CASE id ".$campo_description." END), 
		brand =(CASE id ".$campo_brand." END), 
		model =(CASE id ".$campo_model." END), 
		image_url =(CASE id ".$campo_image_url." END), 
		upc =(CASE id ".$campo_upc." END), 
		currency =(CASE id ".$campo_currency." END), 
		condition =(CASE id ".$campo_condition." END), 
		weight_unit =(CASE id ".$campo_weight_unit." END), 
		package_height =(CASE id ".$campo_height." END), 
		package_length =(CASE id ".$campo_length." END), 
		clothingsize =(CASE id ".$campo_clothingSize." END), 
		color =(CASE id ".$campo_color." END), 
		department =(CASE id ".$campo_department." END), 
		item_height =(CASE id ".$campo_item_height." END), 
		item_length =(CASE id ".$campo_item_length." END), 
		item_width =(CASE id ".$campo_item_width." END), 
		url =(CASE id ".$campo_url." END),
		avaliable =(CASE id ".$campo_avaliable." END),
		sku_padre =(CASE id ".$campo_sku_padre." END),
		category_p =(CASE id ".$campo_category_p." END),
		aku_ascii =(CASE id ".$campo_ascii." END)

		WHERE id in (".$mySkl.") and bolborrado not in (1);";
		$this->conn->exec($sql);

		$this->conn->close_con();
		return $return;

	}
}
