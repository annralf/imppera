<?php
include '/var/www/html/enkargo/services/meli_orders.php';
/*
MeliUpdate(shop id, update type(masive or unique))
*/
$create = new MeliOrders(1);
$create->orders_update();
#$create->orders_id_or("1767233840");
#$create->print_label("27654976846");
