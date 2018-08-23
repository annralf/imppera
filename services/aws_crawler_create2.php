<?php
include '/var/www/html/enkargo/config/aws_crawler.php';
require_once '/var/www/html/enkargo/config/pdo_connector.php';
/*#
Function Name: Update massive o AWS items from AWS service
Author: rafael alvarez
Date: 13/07/2018
Detail: This funtion get aws_item.php functionsto connect to AWS source and get all item detail about
#*/
$conn = new DataBase();
$hora_actual = strtotime(date("H:i"));
$hora_limite = strtotime( "23:00" );

while ($hora_actual < $hora_limite) { 
	$k    	= 1;
	$key 	= $conn->prepare("select a.* from (select distinct (replace(brand,' ','+')) as brand from aws.items where  brand is not null) a order by random() limit 100; ");
	$key->execute();	
	$quantity 	= 0;
	$crawler 	= new Amazon();
	$j          = 1;
		foreach ($key->fetchAll() as $k) {
			$keywords 	= trim($k['brand']);
	        $url        = "https://www.amazon.com/s/gp/search/ref=sr_nr_p_85_0?fst=as%3Aoff&rh=i%3Aaps%2Ck%3A".$keywords."%2Cp_76%3A2661625011%2Cp_85%3A2470955011&sort=price-desc-rank&keywords=".$keywords."&ie=UTF8&qid=1519921342&rnid=2470954011";
	        $aws_result = $crawler->crawler_create($url,1);
	        switch ($aws_result['notavaliable']) {
	        	case 0:
	        		$data =explode(",", $aws_result['skus']);
	        		echo "SKU PAGINA 1, producto: ".$keywords."\t\t total paginas:".$aws_result['pages']." \n";
	        		foreach ($data as $ku_result) {
						$sku = strtoupper($ku_result);
						$sql = $conn->prepare("select upper(sku) as sku from aws.items where sku = '".$sku."';");
						$sql->execute();
						$sql = $sql->fetch();
						if (!isset($sql[0])) {
							$conn->exec("insert into aws.items (sku, create_date) values ('".$sku."','".date("Y-m-d H:i:s")."');");
							echo $j."-\t".$sku." - insertado - ".date("Y-m-d H:i:s")."\n";
							$j++;
						}else{
							#echo $j."-\t".$sku." - ya existe - ".date("Y-m-d H:i:s")."\n";
						}
					}
					sleep(1);
		       		$pg 	= strtoupper($aws_result['pages']);      	
		       		$conn->close_con();
		        	for ($y =2; $y <= $pg; $y++ ){
		        		$url2 = "https://www.amazon.com/s/ref=sr_pg_".$y."?fst=as%3Aoff&rh=i%3Aaps%2Ck%3A".$keywords."%2Cp_76%3A2661625011%2Cp_85%3A2470955011%2Cp_n_condition-type%3A6461716011&page=".$y."&sort=price-desc-rank&keywords=".$keywords."&ie=UTF8&qid=1523033887";
			        	$aws_result2 = $crawler->crawler_create($url,2);
			        	switch ($aws_result2['notavaliable']) {
				        	case 0:
				 				$data =explode(",", $aws_result2['skus2']);
				        	 	echo "SKU PAGINA ".$y.", producto: ".$keywords."\n";
				        	 	foreach ($data as $ku_result) {
									$sku = strtoupper($ku_result);
									$sql = $conn->prepare("select upper(sku) as sku from aws.items where sku = '".$sku."';");
									$sql->execute();
									$sql = $sql->fetch();
									if (!isset($sql[0])) {
										$conn->exec("insert into aws.items (sku, create_date) values ('".$sku."','".date("Y-m-d H:i:s")."');");
										echo $j."-\t".$sku." - insertado - ".date("Y-m-d H:i:s")."\n";
										$j++;
									}else{
										#echo $j."-\t".$sku." - ya existe - ".date("Y-m-d H:i:s")."\n";
									}
								}
				        	break;
				        	case 1:
	        					echo  "pagina ".$y." - ".$aws_result2['message']."\n";
				        	break;	
			        	}
			        	sleep(1);
		        	}
		        break;	
	        	case 1:
	        		echo  $aws_result['message']." producto: ".$keywords."\n";
	        	break;  
	        }

		}
	$hora_actual = strtotime(date("H:i"));
	$conn->close_con();
}

echo "hora limite de proceso alcanzada\n";
$conn->close_con();

