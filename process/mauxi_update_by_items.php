<?php
include '/var/www/html/enkargo/services/meli_update.php';

$create = new MeliUpdate(2,'unique',null);
$create->update('MCO448173088');
#$create->consulta('MCO473233128');