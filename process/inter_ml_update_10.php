<?php
include "/var/www/html/enkargo/services/cbt_items.php";
include_once '/var/www/html/enkargo/config/pdo_connector.php';

$items = array();
$conn  = new DataBase();
$hora_actual = strtotime(date("H:i"));
$hora_limite = strtotime( "23:00" );

while ($hora_actual < $hora_limite) { 
	echo "Inicio de Update CBT...";

	$items = $conn->prepare("SELECT a.*, c.mpid, c.to_br, c.to_mx, c.bolborrado as bol FROM aws.items AS a JOIN cbt.items AS c ON c.aws_id = a.id where c.shop_id = 3 and c.bolborrado =10 order by c.update_date asc offset 15000 limit 1000;");
	$items->execute();
	$j     = 1;
	echo "   Datos encontrados\n";
	while ($i = $items->fetchObject()) {
		$create_item = new cbt_create(3);
		$item_detail = $create_item->update(null, null, null, null, null, $i, "local", null);
		#print_r($item_detail); die();

		if($item_detail=="NULL"){
			echo $j." \t- ".$i->mpid." - ".date('y-m-d H:i:s')." - ".$i->sku." - error preparando items\n";
		}elseif($item_detail=="INVALIDED"){
			echo $j." \t- ".$i->mpid." - ".date('y-m-d H:i:s')." - ".$i->sku." - error validando items\n";
		}elseif($item_detail=="ERROR"){
			echo $j." \t- ".$i->mpid." - ".date('y-m-d H:i:s')." - ".$i->sku." - error actualizando items\n";
		}else{
			echo $j." \t- ".$i->mpid." - ".date('y-m-d H:i:s')." - ".$item_detail->status." - ".$i->sku." - price: ".$item_detail->sale_price."\n";
			if ($item_detail->status=='ready_to_publish'){
				$check = $create_item->publish($i->mpid,$i->to_br,$i->to_mx);
				if(!isset($check)){
					#$sql=$conn->prepare("UPDATE cbt.items SET status = 'published', update_date = '".date("Y-m-d H:i:s")."' WHERE mpid = '".$i->mpid."';");
					#$sql->execute();
					echo "--->\t- PUBLISH - ".$i->mpid." - ".date("Y-m-d H:i:s")."\n";
				}else{
					$error = $check->error;
					#$sql=$conn->prepare("UPDATE cbt.items SET status = '".$error[0]->code."', update_date = '".date("Y-m-d H:i:s")."' WHERE mpid = '".$i->mpid."';");
					#$sql->execute();
					echo "--->\t- ".$error[0]->code." - ".$i->mpid." - ".date("Y-m-d H:i:s")."\n";
				}
			}
			/*if(isset($item_detail->published_sites)){
				foreach ($item_detail->published_sites as $site) {
					if ($site->site=='MX'){
						if (isset($site->url)){
							$MLM=str_replace('-','',substr($site->url,36,13));	
							$sqlmlm = $conn->prepare("UPDATE cbt.items SET mpid_mx = '".$MLM."' WHERE mpid = '".$i->mpid."';");
							$sqlmlm->execute();
						}	
					}
					if ($site->site=='BR'){
						if (isset($site->url)){
							$MLB=str_replace('-','',substr($site->url,35,13));	
							$sqlmlb = $conn->prepare("UPDATE cbt.items SET mpid_br = '".$MLB."'  WHERE mpid = '".$i->mpid."';");
							$sqlmlb->execute();
						}
					}
				}
			}*/
		}

		$j++;
	}
	$hora_actual = strtotime(date("H:i"));
}

echo "hora limite de proceso alcanzada\n";
$conn->close();
