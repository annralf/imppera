<?php
include '/var/www/html/enkargo/services/meli_update_small.php';
/*
MeliUpdate(shop id, update type(masive or unique))
*/
$create = new MeliUpdate(2,'massive',11);
$create->update(1000);