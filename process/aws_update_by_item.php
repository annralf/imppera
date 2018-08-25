<?php
include '/var/www/html/enkargo/services/aws_update.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';
$update_var = new aws_update("AKIAJJGQTHD4SRXET36A","FV9hCymrnrXrO6j48ZKljPnEkJIlOI+VU3NJEfc5","Tobon90-20");
$conn = new Database();
$conn->close_con();
$update_var->execute_update("select sku from aws.items where sku='B00GNJ9I5K';","unique");
