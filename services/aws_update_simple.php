<?php
include '/var/www/html/enkargo/config/aws_item.php';
include '/var/www/html/enkargo/config/pdo_connector.php';
/*#
Function Name: Update massive o AWS items from AWS service
Author: Ana Guere
Date: 13/07/2017
Detail: This funtion get aws_item.php functionsto connect to AWS source and get all item detail about
#*/
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
		$this->k    = 1;
	#**************************************************** Main *******************************************************************
	}
	function execute_update($sentence, $type){
		#SQL sentence to items update at aws.items table

		$query = $this->conn->prepare($sentence);
		$query->execute();
		#$this->conn->close_con();
		$this->k = 0;
		$array = array();
		$cant = 0;
		$campo_price 	=(string)" when 'DEFAULT' then 0";
		$campo_quant 	=(string)" when 'DEFAULT' then 0";
		$campo_prime 	=(string)" when 'DEFAULT' then 0";
		$campo_weigth	=(string)" when 'DEFAULT' then 1";
		$campo_active 	='';
		$campo_up_dat 	='';
		$campo_bolbor 	='';
		$skus			='';


		echo "inicio - ".date("Y-m-d H:i:s")."---------\n";
		foreach ($query->fetchAll() as $result) {
			$sku = str_replace("/", "", $result['sku']);
			if ($type == "massive") {
				array_push($array, $sku);
				$cant = count($array);
				if($cant == 10){
					$items = implode(",", $array);
					$array = array();
				
					foreach ($this->aws->search_item($items) as $aws_result) {
						$sku = $aws_result['asin'];

						switch ($aws_result['notavaliable']) {
							case 0:
								#Item avaliable at AWS
								
								$sale_price       = $aws_result['sale_price'];
								$quantity         = $aws_result['quantity'];
								$is_prime         = $aws_result['is_prime'];
								$package_weight   = $aws_result['package_weight'];
								
								$campo_price 	.=(string)" when '".$sku."' then ".$sale_price;
								$campo_quant 	.=(string)" when '".$sku."' then ".$quantity;
								$campo_prime 	.=(string)" when '".$sku."' then ".$is_prime;
								$campo_weigth 	.=(string)" when '".$sku."' then ".$package_weight;

								$campo_active 	.=(string)" when '".$sku."' then TRUE ";
								$campo_up_dat 	.=(string)" when '".$sku."' then CURRENT_TIMESTAMP(0) ";
								$campo_bolbor 	.=(string)" when '".$sku."' then 0 ";
								
								echo $this->k."\t-".$aws_result['asin']."-".date("Y-m-d H:i:s")."- ".$aws_result['sale_price']."\n";

							break;
							case 1:
								#Item with hide price
								$campo_active 	.=(string)" when '".$sku."' then FALSE ";
								$campo_up_dat 	.=(string)" when '".$sku."' then CURRENT_TIMESTAMP(0) ";
								$campo_bolbor 	.=(string)" when '".$sku."' then 5 ";

								echo $this->k."\t-".$aws_result['asin']."-".date("Y-m-d H:i:s")."- no price or no prime\n";
							break;
							case 2:
								#item not avaliable
								$campo_active 	.=(string)" when '".$sku."' then FALSE ";
								$campo_up_dat 	.=(string)" when '".$sku."' then CURRENT_TIMESTAMP(0) ";
								$campo_bolbor 	.=(string)" when '".$sku."' then 1 ";

								echo $this->k."\t-".$aws_result['asin']."-".date("Y-m-d H:i:s")."- to delete\n";
								
							break;
						}

						$skus .= "'".$sku."',";
						$this->k++;
					}

					#usleep(500);
				}
			}


			/*if ($type == "unique"){
				foreach ($this->aws->search_item($sku) as $aws_result) {
					switch ($aws_result['notavaliable']) {
						case 0:
					#Item avaliable at AWS
						echo $this->k."\t-".$aws_result['asin']."-".date("Y-m-d H:i:s")."-".$aws_result['sale_price']."\n";

						$sale_price       = $aws_result['sale_price'];
						$quantity         = $aws_result['quantity'];
						$is_prime         = $aws_result['is_prime'];
						$update_date      = date("Y-m-d H:i:s");
						$active           = 't';
						$this->conn->exec("UPDATE aws.items SET sale_price = '".$sale_price."', quantity = '".$quantity."', is_prime = '".$is_prime."',  update_date = '".$update_date."', active = '".$active."' bolborrado = 0 WHERE sku = '".$sku."';");
						break;
						case 1:
					#Item with hide price
						$update_date = date("Y-m-d H:i:s");
						$sku         = $aws_result['asin'];
						$this->conn->exec("UPDATE aws.items SET  active = 'f', update_date = '".$update_date."' WHERE sku = '".$sku."';");
						echo $this->k."\t-".$aws_result['asin']."-no price or no prime -".date("Y-m-d H:i:s")."\n";
						break;
						case 2:
					#item not avaliable
						$update_date = date("Y-m-d H:i:s");
						$sku         = $aws_result['asin'];
						$this->conn->exec("UPDATE aws.items SET active = 'f',  update_date = '".$update_date."', bolborrado = 1 WHERE sku = '".$sku."';");
						echo $this->k."\t-".$aws_result['asin']."-to delete-".date("Y-m-d H:i:s")."\n";
						break;
					}
					$this->k++;
				}
			}*/
		}
		

		$mySkl = substr($skus, 0, -1);

		$sql 	=(string)"update aws.items SET sale_price =(CASE sku ".$campo_price." END), quantity =(CASE sku ".$campo_quant." END),	is_prime =(CASE sku ".$campo_prime." END),	active =(CASE sku ".$campo_active." END), update_date =(CASE sku ".$campo_up_dat." END),	bolborrado =(CASE sku ".$campo_bolbor." END), package_weight =(CASE sku ".$campo_weigth." END) WHERE sku in (".$mySkl.");";

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