<?php
include '/var/www/html/enkargo/services/aws_update.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';
$update_var = new aws_update('AKIAIN3DOJFW3UYRGJYQ', 'CZTzSFty8Ux02iL2WZ79qfAhgtiO6avThY2L24Wm', '322855990304-20');
$conn = new Database();
#$set_get = $conn->prepare("update aws.items set bolborrado = 17 where sku in (select sku from aws.items where shop_ml_mx <> 1 and  shop_ml_qb <> 1 order by update_date asc limit 50000);");

#$set_get = $conn->prepare("update aws.items set bolborrado = 17 where id in (select id from aws.items where create_date='2018-03-20 00:00:00' and bolborrado=0 order by update_date asc limit 50000);");
#$set_get->execute();
#$conn->close_con();


				$conteo = $conn->prepare("select count(*) as total from aws.items where bolborrado=19;");
				$conteo->execute();
				$conteo = $conteo->fetchAll();
				$total  = $conteo[0]['total'];


while ($total > 0) { 
	$update_var->execute_update("select sku from aws.items where bolborrado = 19 order by update_date asc limit 20000;","massive");

	$conteo = $conn->prepare("select count(*) as total from aws.items where bolborrado=19;");
	$conteo->execute();
	$conteo = $conteo->fetchAll();
	$total  = $conteo[0]['total'];


	$hora_actual = strtotime(date("H:i"));
	$hora_limite = strtotime( "23:00" );

	if( $hora_actual > $hora_limite ) {
	    echo 'hora limite de proceso alcanzada';
	    die();
	}

}
