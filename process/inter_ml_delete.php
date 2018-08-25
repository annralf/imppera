<?php
include "/var/www/html/enkargo/services/cbt_items.php";
include_once '/var/www/html/enkargo/config/pdo_connector.php';

$hora_actual = strtotime(date("H:i"));
$hora_limite = strtotime( "23:00" );

while ($hora_actual < $hora_limite) { 
	$conn        = new DataBase();
	$create_item = new cbt_create(3);
	
	$items       = $conn->prepare("select id,mpid from cbt.items where bolborrado=1 and shop_id=3 limit 1000;");
	$items->execute();
	$j           = 1;
	while ($i = $items->fetchObject()) {
		$test = $create_item->delete($i->mpid);
		$valor =json_decode($test);
		
		if(!isset($valor->error)){
			print_r($valor);
			echo $j." - DELETED - ".$i->mpid."\n";
			$sql = $conn->prepare("update cbt.items set status ='deleted' WHERE id ='".$i->id."';");
			$sql->execute();
		}else{
			$array= $valor->error;		
			echo $j." - ".$array[0]->code." - ".$i->mpid."\n";

			if($array[0]->code == "SKU_OR_MPID_NOT_FOUND"){
				$sql = $conn->prepare("delete from cbt.items WHERE id ='".$i->id."';");
				$sql->execute();
				#$sql = pg_query("update cbt.items set status ='".$array[0]->code."' WHERE mpid ='".$i['mpid']."';");
			}else{
				$sql = $conn->prepare("update cbt.items set status ='".$array[0]->code."' WHERE id ='".$i->id."';");
				$sql->execute();
			}
		}
		$j++;
	}
	$hora_actual = strtotime(date("H:i"));
}

echo "hora limite de proceso alcanzada\n";
$conn->close();
