<?php
include '/var/www/html/enkargo/services/check_aws_id.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';

$a = new check_aws_id(2);
$y=10000;


$hora_actual = strtotime(date("H:i"));
$hora_limite = strtotime( "23:00" );

while ($hora_actual < $hora_limite) { 
	$a->get($y);
	$hora_actual = strtotime(date("H:i"));
}

echo "hora limite de proceso alcanzada\n";
$conn->close_con();
