<?php
include '/var/www/html/enkargo/services/meli_aws_set_category.php';
$meli_category = new MeliCategory();
echo "Inicio-".date('Y-m-d H:i:s')."\n";


$hora_actual = strtotime(date("H:i"));
$hora_limite = strtotime( "23:00" );

while ($hora_actual < $hora_limite) { 

	$meli_category->set(20000);
	$hora_actual = strtotime(date("H:i"));
	
}

echo "hora limite de proceso alcanzada\n";
$conn->close_con();