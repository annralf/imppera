<?php
/*Testing Update functions unsincronous*/
include '/var/www/html/enkargo/config/cbt_items.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';

/**
* 
*/
class CbtOrders
{
	public $shop;
	public $conn;
	public $cbt;
	function __construct($shop)
	{	
		$this->aws  	= new amazonManager('AKIAIYEUT4YA3UTEPUNA','iiVSzwY9BI0CvSLcUshrJTv2q800GBvck3YVotnV','Tobon90-20');	
		$this->conn 	= new DataBase();
		$application 	= $this->conn->prepare("select * from cbt.shop where id = '".$shop."';");
		$application->execute();
		$this->shop 	= $application->fetchAll();
		$this->cbt 		= new items($this->shop[0]['access_token']);
	}

	function orders(){
		
		echo "BEGIN - ORDERS CBT **************************************\n";
		switch ($this->shop[0]['id']) {
			case '3':
				$order = $this->cbt->get_orders(null);
			break;
			case '4':
				$order = $this->cbt->get_orders(null);					
			break;
		}
		$n=1;
			
		foreach ($order->orders as $ord) {
			#print_r($ord);die();
			$id  			=$ord->order_id;
			#$order_id = $this->cbt->order_by_id($id);		
			#print_r($order_id);die();
			$status  		=$ord->status;
			$order_items 	=$ord->product;
			$sku 			=$order_items[0]->SKU;
			#$buyer_id		=$ord->buyer->id;

			$sql = "SELECT id_order FROM system.orders WHERE id_order='".$id."' and shop_id='".$this->shop[0]['id']."';";
			$order_exist = $this->conn->prepare($sql);
			$order_exist->execute();
			$order_exist = $order_exist->fetchObject();
			$update_date	=$ord->last_updated_date;
			$create_date	=$ord->created_date;

			if(!isset($order_exist->id_order)){
				//$itm_sku		=$this->aws->search_item($sku); #extraido de aws
				$mpid			=$order_items[0]->mpid;
				$quantity 		=$order_items[0]->quantity;
				$unit_price 	=$order_items[0]->merchandise_cost;
				$total_amount	=$ord->invoice_amount;
				$int_tracking	=$ord->international_tracking_id;
				$shipping_label =$ord->shipment_label_location;
				$avaliable='t';

				/*if ($itm_sku == null){
					while ($itm_sku == null ) {
						$itm_sku	=$this->aws->search_item($sku); #extraido de aws						
					}

					if ($itm_sku[0]['notavaliable']==1 || $itm_sku[0]['notavaliable']==2){
						$sql = "SELECT sku,url,sale_price,package_weight FROM aws.items WHERE sku='".$sku."';";
						$sku_exist = $this->conn->prepare($sql);
						$sku_exist->execute();
						$sku_exist = $sku_exist->fetchObject();

						if (!isset($sku_exist->sku)){
							$sale_price		=$unit_price;
							$url_aws		="STOCK INERNO";
							$package_weight	="0";	
							$avaliable='f';					
						}else{
							$sale_price		=$sku_exist->sale_price;
							$url_aws		=$sku_exist->url;
							$package_weight	=$sku_exist->package_weight;
							$avaliable='f';
						}
						echo $n."\t- new order created-".$sku." - ".$id."---- notavaliable 1 - 2 \n";
					}else{
						$sale_price		=$itm_sku[0]['sale_price'];
						$url_aws		=$itm_sku[0]['url'];
						$package_weight	=$itm_sku[0]['package_weight'];	
						echo $n."\t- new order created-".$sku." - ".$id."---- ok show_aws \n";
					}
					
				}else{
					if ($itm_sku[0]['notavaliable']==1 || $itm_sku[0]['notavaliable']==2){
						$sql = "SELECT sku,url,sale_price,package_weight FROM aws.items WHERE sku='".$sku."';";
						$sku_exist = $this->conn->prepare($sql);
						$sku_exist->execute();
						$sku_exist = $sku_exist->fetchObject();

						if (!isset($sku_exist->sku)){
							$sale_price		=$unit_price;
							$url_aws		="STOCK INERNO";
							$package_weight	="0";	
							$avaliable='f';					
						}else{
							$sale_price		=$sku_exist->sale_price;
							$url_aws		=$sku_exist->url;
							$package_weight	=$sku_exist->package_weight;
							$avaliable='f';
						}
						echo $n."\t- new order created-".$sku." - ".$id."---- notavaliable 1 - 2\n";
					}else{
						$sale_price		=$itm_sku[0]['sale_price'];
						$url_aws		=$itm_sku[0]['url'];
						$package_weight	=$itm_sku[0]['package_weight'];	
						echo $n."\t- new order created-".$sku." - ".$id."---- ok show_aws \n";
					}
				}*/

				$sale_price		=0;
				$url_aws		="";
				$package_weight	=0;
				$is_prime       =0;

				echo $n."\t- new order created-".$sku." - ".$id."---- notavaliable provivional\n";

				$sql_o	="INSERT INTO system.orders(id_order, shop_id, sku, url, package_weight, sale_price, quantity, unit_price, total_paid, status,  create_date, mpid,avaliable,autorice,tracking_cbt,shipment_label) VALUES ('".$id."',".$this->shop[0]['id'].",'".$sku."','".$url_aws."','".$package_weight."','".$sale_price."',".$quantity.",'".$unit_price."','".$total_amount."','".$status."','".$create_date."','".$mpid."','".$avaliable."','G','".$int_tracking."','".$shipping_label."');";

				$this->conn->exec($sql_o);
			}else{
				echo $n."\t- order exist-".$sku." - ".$id."\n";

				$sql = "UPDATE system.orders set status='".$status."', update_date='".$update_date."' WHERE id_order='".$id."';";
				$sku_exist = $this->conn->prepare($sql);
				$sku_exist->execute();
			}
			
			$n++;
			sleep(1);
		}
		echo "END - ORDERS **************************************\n";
		$this->conn->close_con();
	}


	function orders_id_or($id){
		
		echo "BEGIN - ORDERS **************************************\n";
		switch ($this->shop[0]['id']) {
			case '1':
				$order = $this->cbt->order_by_id($this->shop[0]['user_name'],"1739832011");
			break;
			case '2':
				$order = $this->cbt->order_by_id($this->shop[0]['user_name'],"1739832011");					
			break;
		}
		print_r($order);

	}
}
#Test section
#$test = new MeliOrders(1);
#$test->orders();