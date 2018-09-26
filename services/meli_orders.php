<?php
/*Testing Update functions unsincronous*/
include '/var/www/html/enkargo/config/meli_items.php';
include '/var/www/html/enkargo/config/aws_item.php';
include '/var/www/html/enkargo/config/conex_manager.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';
/**
* 
*/
class MeliOrders
{
	public $shop;
	public $conn;
	public $meli;
	function __construct($shop)
	{	
		$this->aws  = new amazonManager('AKIAIM2EYANNK5NAEP2Q','zvhPAk5MqgJUk7OnBQ3WEN1RgavE1gJpkgP98yOF','Santiespi2000-20');
		$this->conn = new DataBase();
		$application = $this->conn->prepare("select * from meli.shop where id = '".$shop."';");
		$application->execute();
		$this->shop = $application->fetchAll();
		$this->meli = new items($this->shop[0]['access_token'],$this->shop[0]['user_name']);
	}
	function orders(){
		echo "BEGIN - ORDERS **************************************\n";
		switch ($this->shop[0]['id']) {
			case '1':
				$order = $this->meli->order_recent($this->shop[0]['user_name']);
			break;
			case '2':
				$order = $this->meli->order_recent($this->shop[0]['user_name']);					
			break;
		}
		$n=1;
		$mpid_lis="";
		#print_r($order);die();
		foreach ($order->results as $ord){
			$precio_esp		=0;
			$zip_code		='';
			$street_num		='';
			$street_name	='';
			$country		='';
			$state 			='';
			$city 			='';
			$address_line 	='';
			$id  			=$ord->id;
			$status  		=$ord->status;
			$order_items 	=$ord->order_items;
			$item 			=$order_items[0]->item;
			$sku			=$item->seller_custom_field;
			$buyer_id		=$ord->buyer->id;
			$mercado_l 		= $this->meli->show($item->id);
			$permalink 		= $mercado_l[0]->permalink;
			$sql = "SELECT id_order FROM system.orders WHERE id_order='".$id."' and shop_id='".$this->shop[0]['id']."';";
			$order_exist = $this->conn->prepare($sql);
			$order_exist->execute();
			$order_exist = $order_exist->fetchObject();
			if(!isset($order_exist->id_order)){
				if($sku == null){
					$sku="STOCK_INTERNO";
				}
				#$itm_sku		=$this->aws->search_item($sku); #extraido de aws
				$mpid			=$item->id;
				$mpid_lis 		.="'".$item->id."',";
				$quantity 		=$order_items[0]->quantity;
				$unit_price 	=$order_items[0]->unit_price;
				$total_amount	=$ord->total_amount;
				$payments		=$ord->payments;
				$shipping_id	=$ord->shipping->id;
				$shipping_mode	=$ord->shipping->shipping_mode;
				$id_payments	=$payments[0]->id;
				$zip_code		=$ord->shipping->receiver_address->zip_code;
				$street_num		=$ord->shipping->receiver_address->street_number;
				$street_name	=$ord->shipping->receiver_address->street_name;
				$country		=$ord->shipping->receiver_address->country->name;
				$state 			=$ord->shipping->receiver_address->state->name;
				$city 			=$ord->shipping->receiver_address->city->name;
				$address_line 	=$ord->shipping->receiver_address->address_line;
				$create_date	=substr(str_replace("T"," ",$ord->date_created), 0, 19);
				$avaliable='t';
				$package_weight	="0";
				/*if ($itm_sku == null){
					while ($itm_sku == null ) {
						$itm_sku	=$this->aws->search_item($sku); #extraido de aws						
					}
					if ($itm_sku[0]['notavaliable']==2){
						$sql = "SELECT sku,url,sale_price,package_weight FROM aws.items WHERE sku='".$sku."';";
						$sku_exist = $this->conn->prepare($sql);
						$sku_exist->execute();
						$sku_exist = $sku_exist->fetchObject();
						if (!isset($sku_exist->sku)){
							$sale_price		=$unit_price;
							$url_aws		="STOCK INERNO";	
							$avaliable='f';					
						}else{
							$sale_price		=$sku_exist->sale_price;
							$url_aws		=$sku_exist->url;
							$package_weight	=$sku_exist->package_weight;
							$avaliable='f';
						}
						echo $n."\t- new order created-".$sku." - ".$id."---- notavaliable 1 1\n";
					}else{
						$sale_price		=$itm_sku[0]['sale_price'];
						$is_prime		=$itm_sku[0]['is_prime'];
						$url_aws		=$itm_sku[0]['url'];
						$package_weight	=($itm_sku[0]['package_weight']/100);	
						$precio_esp =  $this->meli->liquidador_pro($sale_price, $package_weight, $is_prime,$this->shop[0]['id']);
						echo $n."\t- new order created-".$sku." - ".$id."---- ok show_aws \n";
					}
				}else{
					$package_weight	="0";
					if ($itm_sku[0]['notavaliable']==2){
						$sql = "SELECT sku,url,sale_price,package_weight FROM aws.items WHERE sku='".$sku."';";
						$sku_exist = $this->conn->prepare($sql);
						$sku_exist->execute();
						$sku_exist = $sku_exist->fetchObject();
						if (!isset($sku_exist->sku)){
							$sale_price		=$unit_price;
							$url_aws		="STOCK INERNO";	
							$avaliable='f';					
						}else{
							$sale_price		=$sku_exist->sale_price;
							$url_aws		=$sku_exist->url;
							$package_weight	=$sku_exist->package_weight;
							$avaliable='f';
						}
						echo $n."\t- new order created-".$sku." - ".$id."---- notavaliable 2\n";
					}else{
						$sale_price		=$itm_sku[0]['sale_price'];
						$is_prime		=$itm_sku[0]['is_prime'];
						$url_aws		=$itm_sku[0]['url'];
						$package_weight	=($itm_sku[0]['package_weight']/100);	
						$precio_esp =  $this->meli->liquidador_pro($sale_price, $package_weight, $is_prime,$this->shop[0]['id']);
						echo $n."\t- new order created-".$sku." - ".$id."---- ok show_aws 2 \n";
					}
				}*/
				$sale_price		=0;
				$url_aws		="";
				$package_weight	=0;
				$is_prime       =0;
				echo $n."\t- new order created-".$sku." - ".$id."---- notavaliable provivional\n";

				$sql_o	="INSERT INTO system.orders(id_order, shop_id, sku, url, package_weight, sale_price, quantity, unit_price, total_paid, status, id_payments, id_payer, create_date, mpid, avaliable, autorice, shipping_id, shipping_mode, zip_code, street_number, street_name, country_name, state_name, city_name, address_line,precio_esp,permalink,is_prime) VALUES ('".$id."',".$this->shop[0]['id'].",'".$sku."','".$url_aws."','".$package_weight."','".$sale_price."',".$quantity.",'".$unit_price."','".$total_amount."','".$status."','".$id_payments."','".$buyer_id."','".$create_date."','".$mpid."','".$avaliable."','G','".$shipping_id."','".$shipping_mode."','".$zip_code."','".$street_num."','".$street_name."','".$country."','".$state."','".$city."','".$address_line ."',".$precio_esp.",'".$permalink."','".$is_prime."');";
				$this->conn->exec($sql_o);
			}else{
				echo $n."\t- order exist-".$sku." - ".$id."\n";
			}
			/*$sql2 = "select id_payer from system.payer where id_payer='".$buyer_id."';";
			$buyer_exist = $this->conn->prepare($sql2);
			$buyer_exist->execute();
			$buyer_exist = $buyer_exist->fetchObject();
			if(!isset($buyer_exist->id_payer)){
				$buyer_nick		=$ord->buyer->nickname;
				$buyer_f_name	=$ord->buyer->first_name;
				$buyer_l_name	=$ord->buyer->last_name;
				$code_phone		=$ord->buyer->phone->area_code;
				$phone			=$ord->buyer->phone->number;
				$code_phone_a	=$ord->buyer->alternative_phone->area_code;
				$phone_a		=$ord->buyer->alternative_phone->number;
				$buyer_type_doc =$ord->buyer->billing_info->doc_type;
				$buyer_num_doc  =$ord->buyer->billing_info->doc_number;
				$phone 			=$code_phone.$phone;
				$phone_a 		=$code_phone_a.$phone_a;
				$sql_p		=	"INSERT INTO system.payer(id_payer, nickname, first_name, last_name, phone, alternative_phone, doc_type, doc_number) VALUES ('".$buyer_id."','".$buyer_nick."','".$buyer_f_name."','".$buyer_l_name."','".$phone."','".$phone_a."','".$buyer_type_doc."',".$buyer_num_doc.");";
				$this->conn->exec($sql_p);
			}*/
			$n++;
			sleep(1);
		}
		$mpid_lis = substr($mpid_lis, 0, -1);
		$sql_u="UPDATE system.orders o SET aws_id=m.aws_id,meli_id=m.id from meli.items m where o.mpid=m.mpid and m.mpid in (".$mpid_lis.");";
		$this->conn->exec($sql_u);
		echo $sql_u2="UPDATE system.orders o SET is_prime=a.is_prime,sku=a.sku,url=a.url,sale_price=a.sale_price,package_weight=a.package_weight from aws.items a where o.aws_id=a.id and o.mpid in (".$mpid_lis.");";
		$this->conn->exec($sql_u2);

		echo "END - ORDERS **************************************\n";
		$this->conn->close_con();
	}

	function orders_id_or($id){
		echo "BEGIN - ORDERS **************************************\n";
		switch ($this->shop[0]['id']) {
			case '1':
				$order = $this->meli->order_by_id($this->shop[0]['user_name'],$id);
			break;
			case '2':
				$order = $this->meli->order_by_id($this->shop[0]['user_name'],$id);					
			break;
		}
		print_r($order);
	}
	function print_label($id){
		echo "BEGIN - label **************************************\n";
		switch ($this->shop[0]['id']) {
			case '1':
				$order = $this->meli->label_by_ship($id);
			break;
			case '2':
				$order = $this->meli->label_by_ship($id);					
			break;
		}
		print_r($order);
	}
	function orders_update(){
		
		echo "BEGIN - UPDATE ORDERS **************************************\n";
		$sql = "SELECT id,id_order FROM system.orders WHERE shop_id='".$this->shop[0]['id']."' and shipping_id is null;";
		$order_upd = $this->conn->prepare($sql);
		$order_upd->execute();
		$order_upd = $order_upd->fetchAll();
		$n=1;
		foreach ($order_upd as $ord) {
			$id   	= $ord['id'];
			$id_or	= $ord['id_order'];
			$order 	= $this->meli->order_by_id($this->shop[0]['user_name'],$id_or);
			$order 	= $order->results[0];
			if(isset($order)){
				#print_r($order);die();
				$status			=$order->status;
				$shipping_id	=$order->shipping->id;
				$shipping_mode	=$order->shipping->shipping_mode;
				$zip_code='';
				$street_num='';
				$street_name='';
				$country='';
				$state='';
				$city='';
				$address_line='';
				#print_r($order->shipping->receiver_address->id);

				if (isset($order->shipping->receiver_address->id)){
					$zip_code		=$order->shipping->receiver_address->zip_code;
					$street_num		=$order->shipping->receiver_address->street_number;
					$street_name	=$order->shipping->receiver_address->street_name;
					$country		=$order->shipping->receiver_address->country->name;
					$state 			=$order->shipping->receiver_address->state->name;
					$city 			=$order->shipping->receiver_address->city->name;
					$address_line 	=$order->shipping->receiver_address->address_line;
				}
				$sql_u="UPDATE system.orders SET status='".$status."',shipping_id='".$shipping_id."',shipping_mode='".$shipping_mode."',zip_code='".$zip_code."',street_number='".$street_num."',street_name='".$street_name."',country_name='".$country."',state_name='".$state."',city_name='".$city."',address_line='".$address_line."' WHERE id=".$id.";";
				$this->conn->exec($sql_u);
				echo $n."\t- order update-".$id_or."\n";
				$n++;
			}
		}
	}
}
#Test section
#$test = new MeliOrders(1);
#$test->orders();