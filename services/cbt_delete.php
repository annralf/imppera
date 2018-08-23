<?php
include "/var/www/html/enkargo/services/cbt_items.php";
include '/var/www/html/enkargo/config/pdo_connector.php';

$create_item = new cbt_create(3);
$conn        = new Connect();
$items       = pg_query("select mpid from cbt.items where bolborrado=1 and shop_id=3;");
$j           = 1;
while ($i = pg_fetch_array($items)) {
	$create_item = new cbt_create(3);
	$test = $create_item->delete($i['mpid']);
	$valor =json_decode($test);
	
	if(!isset($valor->error)){
		print_r($valor);
		echo $j." - DELETED - ".$i['mpid']."\n";
		$sql = pg_query("update cbt.items set status ='deleted' WHERE mpid ='".$i['mpid']."';");
	}else{
		$array= $valor->error;		
		echo $j." - ".$array[0]->code." - ".$i['mpid']."\n";

		if($array[0]->code == "SKU_OR_MPID_NOT_FOUND"){
			$sql = pg_query("delete from cbt.items WHERE mpid ='".$i['mpid']."';");
		}else{
			$sql = pg_query("update cbt.items set status ='".$array[0]->code."' WHERE mpid ='".$i['mpid']."';");
		}
	}
	$j++;
}