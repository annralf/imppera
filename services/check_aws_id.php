<?php
include '/var/www/html/enkargo/config/meli_items.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';
echo "Update process Begin-".date("Y-m-d H:i:s")."\n";
class check_aws_id
{
	public $conn;
	public $shop_id;
	function __construct($shop_id)
	{
		$this->shop_id = $shop_id;
		$this->conn      = new DataBase();
	}

	function get($limit){
		$i           = 1;
		$k                  = 0;
		$application_detail = $this->conn->prepare("SELECT * FROM meli.shop WHERE id ='".$this->shop_id."'");
		$application_detail->execute();
		$application_detail = $application_detail->fetchAll();
		$items_manager      = new items($application_detail[0]['access_token'],$application_detail[0]['user_name']);
		#$items_research = $this->conn->prepare("select mpid from meli.items where mpid ='MCO454746036';");
		$items_research = $this->conn->prepare("select trim(mpid) as mpid from meli.sin_sku where shop_id ='".$this->shop_id."' and sku is null order by mpid asc offset 0 limit '".$limit."';");
		$items_research->execute();
		$items_research = $items_research->fetchAll();
		$this->conn->close_con();
		foreach ($items_research as $items) {
			$temp         = array();
			$mpid         = $items['mpid'];
			$detail_items = $items_manager->show($mpid);
			#print_r($detail_items);

			if(isset($detail_items->status)){
				$this->conn->exec("delete from meli.items where mpid = '".$mpid."' and shop_id = '".$this->shop_id."';");
				$this->conn->exec("delete from meli.sin_sku where mpid = '".$mpid."' and shop_id = '".$this->shop_id."';");
				echo $i." - error 500 - ".$mpid."\n";

			}else{
				if (isset($detail_items[0])) {
					$permalink = $detail_items[0]->permalink;
					$sku = $detail_items[0]->seller_custom_field;
					$sku_attribute = $detail_items[0]->attributes;
					foreach ($sku_attribute as $skua) {
						if($skua->id=='SELLER_SKU'){
								$sku=$skua->value_name;
						}
					}
					$sku_variation = $detail_items[0]->attributes;
					foreach ($sku_variation as $skuv) {
						if(isset($skuv->seller_custom_field)){
								$sku=$skuv->seller_custom_field;
						}
					}

					if(isset($sku)){
						#$search_sku = $this->conn->prepare("select id from aws.items where sku = '".$sku."';");
						#$search_sku->execute();
						#$result = $search_sku->fetchAll();
						#if(isset($result[0]['id'])){
							$this->conn->exec("update meli.sin_sku set sku = '".$sku."',permalink='".$permalink."' where mpid = '".$mpid."';");
							echo $i." - ".$sku." - ".$mpid." -- actualizado\n";
						/*}else{
							$aws_id = $this->conn->prepare("insert into aws.items (sku, create_date, bolborrado) values ('".$sku."', '".date("Y-m-d H:i:s")."', '6') returning id;");
							$aws_id->execute();
							$id = $aws_id->fetchAll();
							echo $id[0]['id']."-";
							$this->conn->exec("update meli.items set aws_id = '".$id[0]['id']."' where mpid = '".$mpid."';");
							echo $i." - ".$sku." - ".$mpid." -- insertado\n";
						}*/

					}else{
						echo $i." - No SKU - ".$mpid."\n";
						#$this->conn->exec("update meli.items set status ='no sku',bolborrado=1 where mpid = '".$mpid."';");
					}
				}else{
					#$this->conn->exec("delete from meli.items where mpid = '".$mpid."' and shop_id = '".$this->shop_id."';");
					#$this->conn->exec("delete from meli.sin_sku where mpid = '".$mpid."' and shop_id = '".$this->shop_id."';");
					echo $i." - No Existe - ".$mpid."\n";
				}
			}	
			$i++;
		}
		$this->conn->close_con();
	}
}
