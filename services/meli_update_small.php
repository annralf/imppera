<?php
include '/var/www/html/enkargo/config/meli_items.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';
#include_once '/var/www/html/enkargo/services/aws_update.php';
#include '/var/www/html/enkargo/config/conex_manager.php';
/*
 * 
*/
class MeliUpdate
{
	public $shop;
	public $type;
	public $conn;
	public $meli;
	public $conn_sql;
	function __construct($shop, $type,$group)
	{
		$this->conn = new DataBase();
		$application = $this->conn->prepare("select * from meli.shop where id = '".$shop."';");
		$application->execute();
		$this->shop = $application->fetchAll();
		$this->meli = new items($this->shop[0]['access_token'],$this->shop[0]['user_name']);
		$this->type = $type;
		$this->group = $group;
	}
	function consulta($alias){
		$detail_items	="";
		$detail_items 	= $this->meli->show($alias);
		if (isset($detail_items->error)){
			echo "error consultando MPID\n";
			return 0;
		}else{
			if (isset($detail_items[0]->seller_custom_field)){
				echo "SKU: ".$detail_items[0]->seller_custom_field." -- ".$alias."\n"; 
				$seller = $detail_items[0]->seller_custom_field;
				return $seller;
			}else{
				echo "No hay SKU para ".$alias."\n";
				return 1;
			}
		}
	}
	function actualiza_bol($alias,$index){
		$this->conn->exec("UPDATE meli.items set bolborrado='".$index."' WHERE mpid='".$alias."';");
	}
	function update($alias){
		echo "Update process Begin - ".date("Y-m-d H:i:s")." *** ";
		$updated_items = "";
		$description   = "";
		$avaliable_d   = "";
		$i = 0;
		switch ($this->type) {
			case 'massive':
				echo "process massive *** \n";
				$upd ="update meli.items set update_date = '".date('Y-m-d H:i:s')."' where shop_id =  '".$this->shop[0]['id']."' and mpid in (select mpid from meli.items where shop_id = '".$this->shop[0]['id']."' and bolborrado=".$this->group." order by update_date asc offset '0' limit '100') returning  update_date";
				#$upd ="update meli.items set update_date = '".date('Y-m-d H:i:s')."' where shop_id =  '".$this->shop[0]['id']."' and mpid in ('MCO474213301','MCO474215808','MCO474640488','MCO474938573','MCO468750739','MCO468720803','MCO468593266','MCO474224985','MCO475639612','MCO474641534') returning  update_date";
				$fec = $this->conn->prepare($upd);
				$fec->execute();
				$fec = $fec->fetchAll();
				$fecha_upd=$fec[0]['update_date'];
				$sql = "SELECT a.id, a.sku, a.image_url,a.quantity, a.active, round(cast((a.package_height/0.393701) as numeric),2) as package_height, round(cast((a.item_width/0.393701) as numeric),2) as item_width, round(cast((a.package_length/0.393701) as numeric),2) as package_length, round(cast((a.item_height/0.393701) as numeric),2) as item_height, round(cast((a.item_length/0.393701) as numeric),2) as item_length, a.product_title_english, a.specification_english , a.brand, round(cast(a.package_weight/100 as numeric),2) as package_weight, a.sale_price, a.bolborrado as aws_bolborrado, a.brand, a.model, a.weight_unit,m.id as id_meli, m.mpid, m.bolborrado as meli_bolborrado, a.is_prime as prime, a.clothingsize, a.color, a.avaliable ,a.title_spanish,a.specification_spanish,a.flag from aws.items as a join meli.items as m on a.id=m.aws_id where m.shop_id =  '".$this->shop[0]['id']."' and  m.update_date ='".$fecha_upd."';";
				$item = $this->conn->prepare($sql);
				$item->execute();
				$item = $item->fetchAll();
				break;	
		}
		$update_array = array();
		unset($update_array);
		foreach ($item as $items) {
			$id_meli			= $items['id_meli'];
			$mpid         		= $items['mpid'];
			$update_array['available_quantity']=0;	
			switch ($items['meli_bolborrado']) {
				case 1:
				case 2:
				case 3:
				case 4:
					$update = $this->meli->update($mpid, $update_array);
					if($update->status=='paused'){
						echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Pausado simple\n";
					}else{
						print_r($update->status);							
					}
					break;
				case 9:
					echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Stock Interno Enkargo\n";
					break;
				default:
					if ($items['aws_bolborrado']==1 || $items['sale_price'] == 0 || $items['sale_price'] > 2500 || $items['active'] == 0 || $items['package_weight'] >= 110 || $items['quantity'] < 1){
						$update = $this->meli->update($mpid, $update_array);
						if($update->status=='paused'){
							echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Pausado simple\n";
						}else{
							print_r($update->status);							
						}
					}else if($items['package_weight'] == 0 && $items['sale_price'] >= 80){
						$update = $this->meli->update($mpid, $update_array);
						if($update->status=='paused'){
							echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Pausado simple\n";
						}else{
							print_r($update->status);							
						}
					}else{
						$precio		= $items['sale_price'];
						$precio_p 	= $this->meli->liquidador_pro($precio, $items['package_weight'], $items['prime'],$this->shop[0]['id']);
						$update_array['price'] =  $precio_p;
						$value['price'] =  $precio_p;
						$update_array['available_quantity'] = 8;	
						$update = $this->meli->update($mpid, $update_array);
						if ($update->status=='active'){
							echo $i++ ."\t- ".$items['mpid']." - ".date('Y-m-d H:i:s')." - updated/active - ".$items['sku']." - price: ".$precio_p."\n";
						}else{
							$detail_items 	= $this->meli->show($mpid);
							if (isset($detail_items[0]->variations[0]->id)){
								unset($update_array['price']);
								unset($update_array['available_quantity']);
								$value['id'] 					= $detail_items[0]->variations[0]->id;
								$value['available_quantity'] 	= 8;
								$update_array['variations']= array($value);
							}
							$update = $this->meli->update($mpid, $update_array);
							unset($update_array['variations']);
							if ($update->status=='active'){
								echo $i++ ."\t- ".$items['mpid']." - ".date('Y-m-d H:i:s')." - updated/active - ".$items['sku']." - price: ".$precio_p."\n";
							}else{
								print_r($update);
							}
						}	
					}
					break;
			}
		}
		$this->conn->close_con();
		echo "Fin update -".date("Y-m-d H:i:s")."***************************************************************\n";
	}
}
#Test section
#$test = new MeliUpdate(2,'massive');
#$test->update(1000);