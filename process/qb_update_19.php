<?php
include '/var/www/html/enkargo/services/meli_update.php';

$hora_actual = strtotime(date("H:i"));
$hora_limite = strtotime( "23:30" );

while ($hora_actual < $hora_limite) { 

	$create = new MeliUpdate(1,'massive',19);
	$create->update(10000);

	$hora_actual = strtotime(date("H:i"));
}

echo "hora limite de proceso alcanzada\n";
$conn->close_con();