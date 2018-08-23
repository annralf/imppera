<?php
include '/var/www/html/enkargo/services/meli_delete.php';

$hora_actual = strtotime(date("H:i"));
$hora_limite = strtotime( "23:30" );

while ($hora_actual < $hora_limite) { 

	$create = new MeliUpdate(2,'massive',3);
	$create->update(1000);

	$hora_actual = strtotime(date("H:i"));
}

echo "hora limite de proceso alcanzada\n";
$conn->close_con();