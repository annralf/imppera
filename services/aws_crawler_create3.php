<?php
include '/var/www/html/enkargo/config/aws_crawler.php';
require_once '/var/www/html/enkargo/config/pdo_connector.php';

$conn = new DataBase();
$k    = 1;
#$key=""; 

$quantity = 0;
$crawler = new Amazon();
$j          = 1;

#	$llave =explode(",", $key);

#	foreach ($llave as $k) {	

#		$keywords 	= trim($k);

		$url        = "https://www.amazon.com/gp/search/ref=sr_st?fst=as%3Aoff&rh=n%3A283155%2Cp_n_feature_nine_browse-bin%3A3291439011%2Cp_n_condition-type%3A1294423011%2Cp_n_feature_browse-bin%3A2656022011&qid=1525815562&bbn=283155&sort=review-count-rank";
		$aws_result = $crawler->crawler_create($url,1);

        switch ($aws_result['notavaliable']) {
        	case 0:
        		$data =explode(",", $aws_result['skus']);
        		echo "SKU PAGINA 1, \t\t total paginas:".$aws_result['pages']." \n";

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
        		#$skus 	= strtoupper($aws_result['skus']);
	       		$pg 	= strtoupper($aws_result['pages']);    

	       		#$pg 	= 400;      	
	       		$conn->close_con();
	        	for ($y =2; $y <= $pg; $y++ ){

		        	#$url2 = "https://www.amazon.com/s/ref=sr_pg_".$y."?fst=as%3Aoff&rh=i%3Aaps%2Ck%3A".$keywords."%2Cp_76%3A2661625011%2Cp_85%3A2470955011&page=".$y."&keywords=".$keywords."&ie=UTF8&qid=1519150114";

	        		#$url2 = "https://www.amazon.com/s/ref=sr_pg_".$y."?fst=as%3Aoff&rh=i%3Aaps%2Ck%3A".$keywords."%2Cp_76%3A2661625011%2Cp_85%3A2470955011&page=".$y."&sort=price-desc-rank&keywords=".$keywords."&ie=UTF8&qid=1519921348";

	        		$url2 = "https://www.amazon.com/s/ref=sr_pg_".$y."?fst=as%3Aoff&rh=n%3A283155%2Cp_n_feature_nine_browse-bin%3A3291439011%2Cp_n_condition-type%3A1294423011%2Cp_n_feature_browse-bin%3A2656022011&page=".$y."&bbn=283155&sort=review-count-rank&ie=UTF8&qid=1526392244";

		        	$aws_result2 = $crawler->crawler_create($url,2);
		        	
		        	switch ($aws_result2['notavaliable']) {
			        	case 0:
			 				$data =explode(",", $aws_result2['skus2']);
			        	 	echo "SKU PAGINA ".$y.", producto: otro\n";
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
	        	/*aca*/

	        break;	
        	case 1:
        		echo  $aws_result['message']." producto: otro\n";
        	break;  
        }
	#}
