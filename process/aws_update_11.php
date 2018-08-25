<?php
include '/var/www/html/enkargo/services/aws_update.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';
$update_var = new aws_update("AKIAJMDKLZSBYDBQH4FA","54UzR5Un3zTOuU59ZMV23dQ6bT7qNW5LJoy+exbw","Sebaspte-20");
$conn = new Database();

$conn->close_con();

$hora_actual = strtotime(date("H:i"));
$hora_limite = strtotime( "23:00" );

while ($hora_actual < $hora_limite) { 

	$update_var->execute_update("select id,upper(sku) as sku from aws.items where bolborrado = 11 order by update_date asc limit 1000;","massive");

	$hora_actual = strtotime(date("H:i"));
	$conn->close_con();
}

echo "hora limite de proceso alcanzada\n";
$conn->close_con();