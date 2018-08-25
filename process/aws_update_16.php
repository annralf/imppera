<?php
include '/var/www/html/enkargo/services/aws_update.php';
include_once '/var/www/html/enkargo/config/pdo_connector.php';
$update_var  = new aws_update('AKIAI3H6L6IHLGZ7VXWA','xJi2wZ/sxg3nBvD0dBnvyO5DyTdizPDjRnqXwq3u','Santiespi2000-20');
$conn = new Database();
$update_var->execute_update("select id,upper(sku) as sku from aws.items where bolborrado = 16 order by update_date asc;","massive");
$conn->close_con();