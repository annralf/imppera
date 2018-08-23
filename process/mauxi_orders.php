<?php
include '/var/www/html/enkargo/services/meli_orders.php';
/*
MeliUpdate(shop id, update type(masive or unique))
*/
$create = new MeliOrders(2);
$create->orders();
#$create->orders_id_or("1767233840");
#$create->print_label("27654976846");

