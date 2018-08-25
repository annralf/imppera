<?php
include '/var/www/html/enkargo/services/meli_orders.php';
/*
MeliUpdate(shop id, update type(masive or unique))
*/
$create = new MeliOrders(1);
$create->orders();
#$create->orders_id_or("1767233840");