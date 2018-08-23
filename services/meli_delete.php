<?php
include '/var/www/html/enkargo/config/meli_items.php';
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
	public $translate;
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
		$this->translate = new GoogleTranslate();
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
				
				echo $sql = "SELECT id, mpid,bolborrado from meli.items where shop_id =  '".$this->shop[0]['id']."' and bolborrado=".$this->group." limit 1000;";
				$item = $this->conn->prepare($sql);
				$item->execute();
				$item = $item->fetchAll();
				break;	
		}
		#Manager Item
		foreach ($item as $items) {
			$id_meli			= $items['id'];
			$mpid         		= $items['mpid'];
			$detail_items		="";
			$detail_items 		= $this->meli->show($mpid);

			if (isset($detail_items->error)) {
				echo $i++ ."\t- Error de conexion ".$detail_items->message."\n";

			}elseif(isset($detail_items[0]->id)){

				$item_status = $detail_items[0]->status;
				switch ($items['bolborrado']) {
					case 1:
					echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Bolborrado 1 MELI\n";
					$this->meli->delete_item($item_status, $mpid, null);
					$this->conn->exec("UPDATE meli.items SET bolborrado = 3 WHERE id='".$id_meli."';");
					break;
					case 3:
					echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Delete MELI\n";
					$this->meli->delete_item($item_status, $mpid, "delete_item");
					break;
					case 4:
					echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - Closed MELI\n";
					$this->meli->delete_item($item_status, $mpid, null);
					$this->conn->exec("UPDATE meli.items SET bolborrado = 10 where id='".$id_meli."';");
					break;
				}
			}else{
				echo $i++ ."\t- ".$mpid." - ".date('Y-m-d H:i:s')." - deleted - mpid no found\n";
			}
		}
		$this->conn->close_con();
		echo "Fin update -".date("Y-m-d H:i:s")."***************************************************************\n";
	}
}
#Test section
#$test = new MeliUpdate(2,'massive');
#$test->update(1000);