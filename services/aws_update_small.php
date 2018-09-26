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
	function __construct($key,$secret_key,$tag)
	{
		$this->aws  = new amazonManager($key, $secret_key, $tag);
		$this->conn = new DataBase();
		$this->k    = 0;
	#**************************************************** Main *******************************************************************
	}
	
	function execute_update($sentence, $type){
		echo "Update process Begin-".date("Y-m-d H:i:s")."*********************************\n";
		#SQL sentence to items update at aws.items table
		$query = $this->conn->prepare($sentence);
		$query->execute();
		#$this->conn->close_con();
		$this->k = 0;
		$array = array();
		$cant = 0;
		$num=1;

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
		$campo_avaliable 	=(string)" when 3074144 then '' ";
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
									
									$campo_avaliable	.=(string)" when ".$id." then '".$avaliable."'";
									

									echo $num."\t- ".$aws_result['asin']." - ".date("Y-m-d H:i:s")."- ".$aws_result['sale_price']." - id:".$id."\n";

									break;
									case 1:
										#Item with hide price
										#Item avaliable at AWS

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
									
									$campo_avaliable	.=(string)" when ".$id." then '".$avaliable."'";
									

									echo $num."\t- ".$aws_result['asin']." - ".date("Y-m-d H:i:s")."- no price or no prime - id:".$id."\n";
									break;
									case 2:
										#item not avaliable
									for ($a=0 ; $a<$total ; $a++ ){
										if($aws_result['asin']==$sku_[$a]){ $id=$id_[$a]; }
									}

									$campo_active 	.=(string)" when ".$id." then FALSE ";
									$campo_up_dat 	.=(string)" when ".$id." then '".date("Y-m-d H:i:s")."' ";
									$campo_bolbor 	.=(string)" when ".$id." then 1 ";

									echo $num."\t- ".$aws_result['asin']." - ".date("Y-m-d H:i:s")."- to delete - id:".$id."\n";
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
		currency =(CASE id ".$campo_currency." END), 
		condition =(CASE id ".$campo_condition." END), 
		weight_unit =(CASE id ".$campo_weight_unit." END), 
		package_height =(CASE id ".$campo_height." END), 
		package_length =(CASE id ".$campo_length." END), 
		item_height =(CASE id ".$campo_item_height." END), 
		item_length =(CASE id ".$campo_item_length." END), 
		item_width =(CASE id ".$campo_item_width." END), 
		avaliable =(CASE id ".$campo_avaliable." END)
		WHERE id in (".$mySkl.") and bolborrado not in (1);";

		$this->conn->exec($sql);

		$this->conn->close_con();

		echo "end - ".date("Y-m-d H:i:s")."-----------------\n";
	}
}
/*
$update_var = new aws_update();
$update_var->execute_update("select a.sku from aws.items as a join meli.items as m on a.id = m.aws_id where m.shop_id = '1' and m.mpid = 'MCO445187989';","unique");
/*$update_var->execute_update("select sku from aws.items order by update_date asc limit 20;","massive");
*/