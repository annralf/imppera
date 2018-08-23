<?php
include "/var/www/html/enkargo/services/cbt_items.php";
include_once '/var/www/html/enkargo/config/pdo_connector.php';

$conn        = new DataBase();
$create_item = new cbt_create(4);
$secuence = $conn->prepare("SELECT * FROM cbt.secuences WHERE type = 'publishcbt_qb';");
$secuence->execute();
$secuence = $secuence->fetchObject();
$offset          = $secuence->offset_+2000;
$sql =$conn->prepare("UPDATE cbt.secuences SET offset_ = '".$offset."' WHERE type = 'publishcbt_qb';");
$sql->execute();
$mpids = $conn->prepare("SELECT c.id,c.mpid,c.to_br,c.to_mx FROM aws.items AS a JOIN cbt.items AS c ON c.aws_id = a.id where c.shop_id = 4  and c.mpid='9008746047';");
$mpids->execute();
$i           = 1;
while ($items = $mpids->fetchObject()) {
	#echo $i."-PUBLISH-".$items->mpid."\n";
	$publ = $create_item->publish($items->mpid,$items->to_br,$items->to_mx);

	if(!isset($publ)){
		$query =$conn->prepare("UPDATE cbt.items SET status = 'published', update_date = '".date("Y-m-d H:i:s")."' WHERE id = '".$items->id."';");
		$query->execute();
		echo $i."\t- PUBLISH - ".$items->mpid." - ".date("Y-m-d H:i:s")."\n";

	}else{
		$error = $publ->error;
		$query=$conn->prepare("UPDATE cbt.items SET status = '".$error[0]->code."', update_date = '".date("Y-m-d H:i:s")."' WHERE id = '".$items->id."';");
		$query->execute();
		echo $i."\t- ".$error[0]->code." - ".$items->mpid." - ".date("Y-m-d H:i:s")."\n";
	}
	$i++;
}