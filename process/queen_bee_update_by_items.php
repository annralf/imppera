<?php
include '/var/www/html/enkargo/services/meli_update.php';

$create = new MeliUpdate(1,'unique',null);
$create->update('MCO470714164');
#$create->consulta('MCO470714164');
