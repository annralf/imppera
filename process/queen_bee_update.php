<?php
include '/var/www/html/enkargo/services/meli_update.php';
/*
MeliUpdate(shop id, update type(masive or unique))
*/
$create = new MeliUpdate(1,'massive',32);
$create->update(1000);